<?php

//include '/var/www/html/include/Aes.class.php';

class Crypto {
  public function encode ($json_string, $access_key, $secret_key)
  {
    /*
    if (!$json_string) {
        return $json_string;
    }

    //AES Encryptor initialization
    $aes = new AES($json_string,  							// AcquireLicenseAssertion 
		$access_key, 			// AccessKey  
		base64_decode($secret_key), 	// Base64Encoded Secret Key 
		128); 								// Block Size 

    //Set Encryption Mode 
    $aes->setMode(AES::M_CBC); 

    //Encrypt AcquireLicenseAssertion and save result to ENC variable  
    $enc = $aes->encrypt();

    return $enc;
    */
    return $json_string;
  }

  public function decode ($json_string, $access_key, $secret_key)
  {
      /*
    if (!$json_string) {
	return $json_string;
    }
    //AES Encryptor initialization
    $aes = new AES($json_string,  							// AcquireLicenseAssertion 
		$access_key, 			// AccessKey  
		base64_decode($secret_key), 	// Base64Encoded Secret Key 
		128); 								// Block Size 

    //Set Encryption Mode
    $aes->setMode(AES::M_CBC);

    //Set AcuireLicenseAssertion prior decryption 
    $aes->setData($json_string);

    //Decrypt AcquireLicenseAssertion
    $dec=$aes->decrypt();

    return substr($dec, 32, strlen($dec));
    */
    return $json_string;
  }
}

/*$assertion = new Crypto();

$enc = $assertion->encode($json_string, $access_key, $secret_key);
  
$dec = $assertion->decode($enc, $access_key, $secret_key);
*/
?>
