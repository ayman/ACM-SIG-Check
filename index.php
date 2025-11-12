<?php
// URL PARAM 'sigid' to change sigs. Only one sig at a time supported.
$sigid = isset($_REQUEST['sig']) ? trim($_REQUEST['sig']) : '026';

// URL PARAM 'sp' is the success page redirect. Params will be added to this.
$sp = isset($_REQUEST['sp']) ? trim($_REQUEST['sp']) : 'success.php';
$sp = rtrim($sp, "?");

// URL PARAM 'ep' is the error page redirect. Params will be added to
// this. If not set, the form will just display an error.
$ep = isset($_REQUEST['ep']) ? trim($_REQUEST['ep']) : 'error.php';
$ep = rtrim($ep, "?"); 

// URL PARAM 'sendid' if set (to anything), then the Member number sent forward, defaults FALSE.
$sendid = isset($_REQUEST['sendid']) ? TRUE : FALSE;

$sigs = array(
  "001" => "SIGACT",
  "003" => "SIGAI",
  "004" => "SIGMIS",
  "006" => "SIGACCESFS",
  "007" => "SIGCAS",
  "011" => "SIGCSE",
  "013" => "SIGDA",
  "014" => "SIGMOD",
  "016" => "SIGIR",
  "019" => "SIGMETRICS",
  "020" => "SIGMICRO",
  "022" => "SIGOPS",
  "024" => "SIGSAM",
  "025" => "SIGSIM",
  "026" => "SIGCHI",
  "028" => "SIGUCCS",
  "033" => "SIGDOC",
  "036" => "SIGSAC",
  "037" => "SIGADA",
  "038" => "SIGFORT",
  "042" => "SIGAPP",
  "043" => "SIGHYPERTEXT AND THE WEB",
  "044" => "SIGMM",
  "047" => "SIGMOBILE",
  "048" => "SIGKDD",
  "049" => "SIGECOM",
  "050" => "SIGITE",
  "051" => "SIGBED",
  "052" => "SIGEVO",
  "053" => "SIGSPATIAL",
  "055" => "SIGBIOINFO",
  "056" => "SIGHPC",
  "057" => "SIGLOG",
  "058" => "SIGENERGY",
  "401" => "SIGACT",
  "402" => "SIGARCH",
  "408" => "SIGCOMM",
  "411" => "SIGCSE",
  "414" => "SIGMOD",
  "415" => "SIGGRAPH",
  "415P" => "SIGGRAPH PIONEER",
  "422" => "SIGOPS",
  "423" => "SIGPLAN",
  "434" => "SIGSOFT"
);

// Internal Vars, do not use.
$error_redirect = isset($_REQUEST['ep']);

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  // This is the ACM member number
  $id = trim($_POST['id']);
  
  // ACM Endpoint, do not edit!
  $apiUrl = 'https://cfapi.acm.org/rest/confRegistration/confRegistration/' . $sigid. '/' . urlencode($id);
  
  // Initialize cURL
  $ch = curl_init($apiUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  
  // Execute the GET request
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  // Define a secret key (keep this secure and do not hardcode in production)
  // $key = 'your_strong_secret_key_here';
  $key = $_POST['id'];
  
  // Choose a cipher method and mode (e.g., AES-256-CBC)
  $cipher_method = 'aes-256-cbc';

  // Generate a random initialization vector (IV)
  // This should be unique for each encryption and stored with the ciphertext
  $iv_length = openssl_cipher_iv_length($cipher_method);
  // $iv = openssl_random_pseudo_bytes($iv_length);
  $iv = str_pad($_POST['id'], $iv_length, '0', STR_PAD_LEFT);
  
  // String to be encrypted
  $plaintext = "SIGCHI";

  // Encryption
  $encrypted_data = openssl_encrypt($plaintext, $cipher_method, $key, 0, $iv);
  // Combine IV and encrypted data, then base64 encode for storage/transmission
  $encoded_data = base64_encode($iv . $encrypted_data); 

  $base_url = $ep;
  $is_error = TRUE;

  // Check if request was successful
  if ($httpCode == 200 && $response) {
    $data = json_decode($response, true);
    // Check if THISSIGACTIVE key exists and its value
    if (isset($data['THISSIGACTIVE']) && $data['THISSIGACTIVE'] === 'active') {
      $base_url = $sp;
      $is_error = FALSE;
    }
  }

  $params = array(
    "sigkey" => $encoded_data,
    "sigid" => $sigid
  );

  if ($sendid) {
    $params["id"] = urlencode($id);
  }
  
  $query_string = http_build_query($params);
  $redirect_url = $base_url . '?' . $query_string;
  $header_string = 'Location: ' . $redirect_url;

  if (!$is_error || $error_redirect) {
    header($header_string);
    exit;  
  } 
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACM & SIG Verification</title>
    <style>
     body {
       font-family: Arial, sans-serif;
       max-width: 400px;
       margin: 50px auto;
       padding: 20px;
       background-color: #f5f5f5;
     }
     .form-container {
       background: white;
       padding: 30px;
       border-radius: 8px;
       box-shadow: 0 2px 4px rgba(0,0,0,0.1);
     }
     h1 {
       color: #333;
       margin-top: 0;
     }
     label {
       display: block;
       margin-bottom: 8px;
       color: #555;
       font-weight: bold;
     }
     input[type="text"] {
       width: 100%;
       padding: 10px;
       border: 1px solid #ddd;
       border-radius: 4px;
       box-sizing: border-box;
       font-size: 16px;
     }
     button {
       width: 100%;
       padding: 12px;
       background-color: #007bff;
       color: white;
       border: none;
       border-radius: 4px;
       font-size: 16px;
       cursor: pointer;
       margin-top: 15px;
     }
     button:hover {
       background-color: #0056b3;
     }
    </style>
  </head>
  <body>
    <div class="form-container">
      <h1>ACM <?= $sigs[$sigid]; ?> Check</h1>
      <?php if ($is_error == TRUE): ?>
          <p><tt><?= $id; ?></tt> <i>is not a member of this SIG. <br />Check the number and try again.</i></p>
      <?php endif; ?>          
      <p>
        <form method="POST" action="">
          <label for="id">Member Number:</label>
          <input type="text" id="id" name="id" required>
          <small><i>Required</i></small>
          <button type="submit">Submit</button>
        </form>
      </p>
      <?php if ($sendid == TRUE): ?>
        <p><b><i>Your ACM Member Number will be forwarded with this form.</i></b></p>
      <?php endif; ?>
      <p>
        No information or data is stored in this form; this just
        validates your membership and redirects to another page anonymously.
      </p>
      <p>
        <small>
          <a href="about.php">About this form.</a> The maintainer of
          this form does not endorse or check the redirect URL.  The
          redirect URL is keyed to the member number but is anonymous. Do not
          share it.
        </small>
      </p>
      <p>
        <small>
          <a href="https://github.com/ayman/ACM-SIG-Check/">Open
          source on Github</a> under GPLv3.
        </small>
      </p>                                                           
    </div>
  </body>
</html>
