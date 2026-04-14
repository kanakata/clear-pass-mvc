<?php

namespace App\Models\Security\Unmasker;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(ROOT);
$dotenv->load();
class Unmasker
{
    protected static function Unmasker()
    {

        $secret_key = $_ENV['ENCRYPTION_KEY'];
        $method = "aes-256-cbc";
        $encryptedData = file_get_contents('secure_code.php');

        // 1. Decode and split the IV from the Encrypted string
        $decoded = base64_decode($encryptedData);
        list($cipherText, $iv) = explode('::', $decoded, 2);

        // 2. Re-generate the same key
        $key = hash('sha256', $secret_key);

        // 3. Decrypt
        $unmaskedCode = openssl_decrypt($cipherText, $method, $key, 0, $iv);
    }
}
