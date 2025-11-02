<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACM & SIG Verification</title>
    <style>
     body {
       font-family: Arial, sans-serif;
       max-width: 600px;
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
      <h1>About ACM <?= $sigs[$sigid]; ?> Check</h1>
      <p>
        This web script validates if an ACM Member Number is a SIG
        memeber.  It is customizable and private. No data is logged or
        stored.
      </p>
      <p>
        URL Params:
      </p>
      <p>
        <tt>sigid</tt> which SIG to check.
      </p>        
      <ul>
        <li><tt>001</tt>: SIGACT</li>
        <li><tt>003</tt>: SIGAI</li>
        <li><tt>004</tt>: SIGMIS</li>
        <li><tt>006</tt>: SIGACCESFS</li>
        <li><tt>007</tt>: SIGCAS</li>
        <li><tt>011</tt>: SIGCSE</li>
        <li><tt>013</tt>: SIGDA</li>
        <li><tt>014</tt>: SIGMOD</li>
        <li><tt>016</tt>: SIGIR</li>
        <li><tt>019</tt>: SIGMETRICS</li>
        <li><tt>020</tt>: SIGMICRO</li>
        <li><tt>022</tt>: SIGOPS</li>
        <li><tt>024</tt>: SIGSAM</li>
        <li><tt>025</tt>: SIGSIM</li>
        <li><tt>026</tt>: SIGCHI</li>
        <li><tt>028</tt>: SIGUCCS</li>
        <li><tt>033</tt>: SIGDOC</li>
        <li><tt>036</tt>: SIGSAC</li>
        <li><tt>037</tt>: SIGADA</li>
        <li><tt>038</tt>: SIGFORT</li>
        <li><tt>042</tt>: SIGAPP</li>
        <li><tt>043</tt>: SIGHYPERTEXT AND THE WEB</li>
        <li><tt>044</tt>: SIGMM</li>
        <li><tt>047</tt>: SIGMOBILE</li>
        <li><tt>048</tt>: SIGKDD</li>
        <li><tt>049</tt>: SIGECOM</li>
        <li><tt>050</tt>: SIGITE</li>
        <li><tt>051</tt>: SIGBED</li>
        <li><tt>052</tt>: SIGEVO</li>
        <li><tt>053</tt>: SIGSPATIAL</li>
        <li><tt>055</tt>: SIGBIOINFO</li>
        <li><tt>056</tt>: SIGHPC</li>
        <li><tt>057</tt>: SIGLOG</li>
        <li><tt>058</tt>: SIGENERGY</li>
        <li><tt>401</tt>: SIGACT</li>
        <li><tt>402</tt>: SIGARCH</li>
        <li><tt>408</tt>: SIGCOMM</li>
        <li><tt>411</tt>: SIGCSE</li>
        <li><tt>414</tt>: SIGMOD</li>
        <li><tt>415</tt>: SIGGRAPH</li>
        <li><tt>415P</tt>: SIGGRAPH PIONEER</li>
        <li><tt>422</tt>: SIGOPS</li>
        <li><tt>423</tt>: SIGPLAN</li>
        <li><tt>434</tt>: SIGSOFT</li>
      </ul>
      <p>
        <tt>sp</tt> the redirect success page.
      </p>
      <p>
        <tt>ep</tt> the redirect error page (if needed). If not set, the form will display an error.
      </p>
      <p>
        <tt>sendid</tt> if set to anything, the member number will be
        forwarded to the success page. <i>The form will notify
        people</i> if this is set. For privacy reasons, use this only
        if needed.
      </p>
    </div>
  </body>
</html>
