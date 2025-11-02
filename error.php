<?php
if (isset($_REQUEST['id'])) { 
    echo "Bad ID: " . $_REQUEST['id'];
} else {
    echo "ACM ID wasn't sent. Use 'sendid' to test in the form.";
}
echo "<br />";
echo "SIG: " . $_REQUEST['sigid'] . "<br />";
?>
