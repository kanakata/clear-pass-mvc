<?php
namespace App\Models\Security\Masker;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(ROOT);
$dotenv->load();
class Masker
{
    protected static function Mask_content()
    {

        // 1. The code you want to protect
        $rawCode = $_ENV['ENCRYPTION_KEY'];;

        // 2. Setup Encryption Parameters
        $method = "aes-256-cbc";
        $secret_key = "my-super-secret-password-123"; // Keep this safe!
        $key = hash('sha256', $secret_key); // Generate a 32-byte key
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));

        // 3. Encrypt the code
        $encrypted = openssl_encrypt($rawCode, $method, $key, 0, $iv);

        // 4. Combine IV and Encrypted Data (we need the IV to decrypt later)
        // We use a separator '::' so we can split them easily later
        $outputString = base64_encode($encrypted . '::' . $iv);

    }
}
