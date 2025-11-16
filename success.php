<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../config/config.php';

echo "Good ID: ";
if (isset($_REQUEST['id'])) {
    echo $_REQUEST['id'];
} else {
    echo "ID not sent.";
}
echo "<br />";
echo "SIG: " . $_REQUEST['sigid'] . "<br />";
echo "Hash: " . $_REQUEST['sigkey'] . "<br />";

// Define a secret key (keep this secure and do not hardcode in
// production)
$key = defined('ACM_CHECK_KEY') ? ACM_CHECK_KEY : $_REQUEST['id'];

// Choose a cipher method and mode (e.g., AES-256-CBC)
$cipher_method = 'aes-256-cbc';

// Generate a random initialization vector (IV)
// This should be unique for each encryption and stored with the ciphertext
$iv_length = openssl_cipher_iv_length($cipher_method);
$iv = openssl_random_pseudo_bytes($iv_length);

$decoded_data = base64_decode($_REQUEST['sigkey']);
// Extract the IV
$retrieved_iv = substr($decoded_data, 0, $iv_length);
// Extract the actual encrypted data
$retrieved_encrypted_data = substr($decoded_data, $iv_length);

$decrypted_data = openssl_decrypt($retrieved_encrypted_data, $cipher_method, $key, 0, $retrieved_iv);

if (defined('ACM_CHECK_KEY') || isset($_REQUEST['id'])) {
    echo "Decrypted: " . $decrypted_data;
} else {
    echo "Can't decrypt since ACM ID wasn't sent. Use 'sendid' to test in the form.";
}
?>
