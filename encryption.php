
<?php
function encryptData($data) {
    $key = hex2bin('acdb62d64e2029a1873cd28ef52c6bc8c2e1b486400d5c7b40e741f1e28bdf3a');
    $cipher = "aes-256-cbc";
    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($iv_length); // Generate IV with proper length
    $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted); // Concatenate IV with encrypted data
}

function decryptData($data) {
    $key = hex2bin('acdb62d64e2029a1873cd28ef52c6bc8c2e1b486400d5c7b40e741f1e28bdf3a');
    $cipher = "aes-256-cbc";
    $data = base64_decode($data);
    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = substr($data, 0, $iv_length); // Extract IV from the data
    $encrypted = substr($data, $iv_length); // Extract encrypted data
    return openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv);
}
?>


 <?php
// function encryptData($data, $key)
// {
//     $cipher = "aes-256-cbc";
//     $iv = openssl_random_pseudo_bytes(25); // Generate IV with length 25 bytes
//     $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
//     return base64_encode($iv . $encrypted); // Concatenate IV with encrypted data
// }

// function decryptData($data, $key)
// {
//     $cipher = "aes-256-cbc";
//     $data = base64_decode($data);
//     $iv_length = 25; // IV length is 25 bytes
//     $iv = substr($data, 0, $iv_length); // Extract IV from the data
//     $encrypted = substr($data, $iv_length); // Extract encrypted data
//     return openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv);
// }

?> 