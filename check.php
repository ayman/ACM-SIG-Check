<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../config/config.php';

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

// URL PARAM 'unsafe' means it will redirect automatically if it's set to anything.
$unsafe = isset($_REQUEST['unsafe']) ? TRUE : FALSE;

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
$_error_redirect = isset($_REQUEST['ep']);

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
  // This is the ACM member number
  $ids = trim($_POST['ids']);
  $hashes = preg_split('/[\s,]+/', $ids);

  // Define a secret key (keep this secure and do not hardcode in
  // production)
  $key = defined('ACM_CHECK_KEY') ? ACM_CHECK_KEY : FALSE;

  if ($key == FALSE) {
    echo "No secret key set in server, so can't decode.";
    exit;
  }
  
  // Choose a cipher method and mode (e.g., AES-256-CBC)
  $cipher_method = 'aes-256-cbc';

  // Generate a random initialization vector (IV)
  // This should be unique for each encryption and stored with the ciphertext
  $iv_length = openssl_cipher_iv_length($cipher_method);
  $iv = openssl_random_pseudo_bytes($iv_length);

  $results_found = FALSE;

  $bad_hashes = array();
  $good_hashes = array();
  $duplicates = array(); 

  foreach ($hashes as $hash) {
    // Code to execute for each $value
    $decoded_data = base64_decode($hash);
    // Extract the IV
    $retrieved_iv = substr($decoded_data, 0, $iv_length);
    // Extract the actual encrypted data
    $retrieved_encrypted_data = substr($decoded_data, $iv_length);
    $decrypted_data = openssl_decrypt($retrieved_encrypted_data, $cipher_method, $key, 0, $retrieved_iv);
    if ($decrypted_data == null) {
      array_push($bad_hashes, $hash);
    } else {
      $good_hashes[$hash] = $decrypted_data;
      array_push($duplicates, $hash);
    }
    $results_found = TRUE;
  }

  $duplicates = array_filter(array_count_values($duplicates), function($count) {
    return $count > 1;
  });


}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACM Check Checker</title>
    <style type="text/css">
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
     .wrapword {
       white-space: -moz-pre-wrap !important;  /* Mozilla, since 1999 */
       white-space: -webkit-pre-wrap;          /* Chrome & Safari */ 
       white-space: -pre-wrap;                 /* Opera 4-6 */
       white-space: -o-pre-wrap;               /* Opera 7 */
       white-space: pre-wrap;                  /* CSS3 */
       word-wrap: break-word;                  /* Internet Explorer 5.5+ */
       word-break: break-all;
       white-space: normal;
     }
    </style>
  </head>
  <body>
    <div class="form-container">
      <h1>ACM Check Checker</h1>
      <form id="memberCheckForm" method="POST" action="">
        <label for="id">Hashes to check:</label>
        <textarea rows="4" cols="50" type="text" id="ids" name="ids" required></textarea>
        <small><i>Required. Comma or line separated</i></small>
        <button type="submit">Submit</button>
        <button onclick="window.location.reload();">Reset</button>
      </form>
      <?php if (isset($results_found) && $results_found == TRUE): ?>
        <p>
          Good hashes:        
          <?php
          echo '<textarea rows="4" cols="50" type="text" id="gids" name="gids" readonly>';
          foreach ($good_hashes as $key => $value) {
            echo $key . ', ' . $value . PHP_EOL;
          }
          echo '</textarea>';
          ?>        
      </p>                                                           
      <p>
        Duplicate Good hashes:
        <?php
        echo '<textarea rows="4" cols="50" type="text" id="dids" name="dids" readonly>';          
        foreach ($duplicates as $key => $value) {
          echo $key . ', ' . $value . PHP_EOL;            
        }
        echo '</textarea>';
        ?>
      </p>                                                           
      <p>
        Bad hashes:
        <?php
        echo '<textarea rows="4" cols="50" type="text" id="bids" name="bids" readonly>';        
        for ($i = 0; $i < count($bad_hashes); $i++) { 
          echo $bad_hashes[$i] . PHP_EOL; 
        } 
        echo '</textarea>';
        ?>
      </p>
      <?php endif; ?>
      <small>
        <a href="https://github.com/ayman/ACM-SIG-Check/">Open
          source on Github</a> under GPLv3.
      </small>      
    </div>
  </body>
</html>
