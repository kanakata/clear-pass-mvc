<?php

namespace App\Models\Mpesa;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(ROOT);
$dotenv->load();
abstract class Mpesa
{
    private static function Access_token()
    {
        $credentials = base64_encode($_ENV['CONSUMER_KEY'] . ':' . $_ENV['CONSUMER_SECRET']);
        $url = $_ENV['AUTH_URL'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        print_r($result);

        return $result['access_token'];
    }
    public static function Stk_push(int $phone, int $amount)
    {
        $token = self::Access_token();
        $timestamp = date('YmdHis');
        $password = base64_encode($_ENV['SHORTCODE'] . $_ENV['PASSKEY'] . $timestamp);

        $payload = [
            'ShortCode' => $_ENV['SHORTCODE'],
            'CommandID' => 'CustomerPayBillOnline',
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'Amount'            => $amount,
            'PartyA'            => $phone,
            'PartyB'            => 174379,
            'Msisdn'       => $phone,
            'CallBackURL'       => $_ENV['CALLBACK_URL'],
            'AccountReference'  => 'Test123',
            'TransactionDesc'   => 'Payment',

        ];

        $url = $_ENV['STKPUSH_URL'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
    private static function Callback()
    {
        header("Content-Type: application/json");

        $stkCallbackResponse = file_get_contents('php://input');
        $logFile = "logs/mpesa_responses.log";

        // Log the response for debugging
        file_put_contents($logFile, $stkCallbackResponse . PHP_EOL, FILE_APPEND);

        $data = json_decode($stkCallbackResponse);
        $resultCode = $data->Body->stkCallback->ResultCode;

        if ($resultCode == 0) {
            // Payment Successful
            $metadata = $data->Body->stkCallback->CallbackMetadata->Item;
            $mpesaReceiptNumber = $metadata[1]->Value;
            $amount = $metadata[0]->Value;
            $phoneNumber = $metadata[4]->Value;

            // TODO: Update your database (studentgeneraldata) set status = 'online'
        }

        echo json_encode(["ResultCode" => 0, "ResultDesc" => "Success"]);

        include 'db_connect.php'; // Your existing database connection
        header("Content-Type: application/json");

        $stkCallbackResponse = file_get_contents('php://input');
        $data = json_decode($stkCallbackResponse);

        if ($data->Body->stkCallback->ResultCode == 0) {
            // 1. Get the Metadata
            $items = $data->Body->stkCallback->CallbackMetadata->Item;

            // The MerchantRequestID or AccountReference helps identify the student
            // In many Daraja versions, the reference is passed back or can be tracked via CheckoutRequestID
            $checkoutID = $data->Body->stkCallback->CheckoutRequestID;
            $amount = 0;

            foreach ($items as $item) {
                if ($item->Name == "Amount") $amount = $item->Value;
                if ($item->Name == "MpesaReceiptNumber") $receipt = $item->Value;
            }

            // 2. Update the database
            // Note: You need to have stored the CheckoutRequestID in a temp table 
            // OR use the phone number/reference to find the student.

            // Example: Updating finance status to 'online' for that student
            $stmt = $db_connect->prepare("UPDATE studentgeneraldata SET `finance status` = 'online', `finance value` = `finance value` - ? WHERE admission = ?");

            // You'll need to logic to map the checkoutID back to the admission number
            // For this example, let's assume you track by phone:
            $phoneNumber = $data->Body->stkCallback->CallbackMetadata->Item[4]->Value;

            $stmt->bind_param("ds", $amount, $admission_from_lookup);
            $stmt->execute();
        }

        echo json_encode(["ResultCode" => 0, "ResultDesc" => "Accepted"]);
    }
}
