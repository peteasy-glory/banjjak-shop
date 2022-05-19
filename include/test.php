<?
include "Crypto.class.php";


$json_string = "TEST STRING";

$access_key = "20110f71-b2e2-41f5-aa1b-bb1111381161";

$secret_key = "MrSyBBMOW1KXPU24m4+3r0q84i7gW56Ebx6kOwp79z8=";

$assertion = new Crypto();

echo $json_string."<br>";

$enc = $assertion->encode($json_string, $access_key, $secret_key);

echo $enc."<br>";
  
$dec = $assertion->decode($enc, $access_key, $secret_key);

echo $dec."<br>";

?>
