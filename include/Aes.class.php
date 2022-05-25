<?php
/**
Aes encryption
*/
class AES {
  
  const M_CBC = 'cbc';
  const M_CFB = 'cfb';
  const M_ECB = 'ecb';
  const M_NOFB = 'nofb';
  const M_OFB = 'ofb';
  const M_STREAM = 'stream';
  
  protected $secretKey;
  protected $accessKey;
  protected $cipher;
  protected $data;
  protected $mode;
  protected $IV;

/**
* 
* @param type $data
* @param type $secretKey
* @param type $blockSize
* @param type $mode
*/
  function __construct($data = null, $accessKey = null, $secretKey = null, $blockSize = null, $mode = null) {
    $this->setData($data);
    $this->setAccessKey($accessKey);
    $this->setSecretKey($secretKey);
    $this->setBlockSize($blockSize);
    $this->setMode($mode);
    $this->setIV("");
  }
  
/**
* 
* @param type $data
*/
  public function setData($data) {
    $this->data = $data;
  }
  
/**
* 
* @param type $secretkey
*/
  public function setSecretKey($secretKey) {
    $this->secretKey = $secretKey;
  }

  
/**
* 
* @param type $accesskey
*/
  public function setAccessKey($accessKey) {

	$str = str_replace('-', '', $accessKey);

   for($i=0,$l = strlen($str);$i<$l;$i+=2)
   {
	$bytes[] = hexdec($str[$i].$str[$i+1]);	
   }

   $bytes = array_merge(array_reverse(array_slice($bytes, 0, 4)), array_reverse(array_slice($bytes, 4, 2)), array_reverse(array_slice($bytes, 6, 2)), array_slice($bytes, 8, 8) );
   $this->accessKey = implode(array_map("chr", $bytes));
  }
  
/**
* 
* @param type $blockSize
*/
  public function setBlockSize($blockSize) {
    switch ($blockSize) {
      case 128:
      $this->cipher = MCRYPT_RIJNDAEL_128;
      break;
      
      case 192:
      $this->cipher = MCRYPT_RIJNDAEL_192;
      break;
      
      case 256:
      $this->cipher = MCRYPT_RIJNDAEL_256;
      break;
    }
  }
  
/**
* 
* @param type $mode
*/
  public function setMode($mode) {
    switch ($mode) {
      case AES::M_CBC:
      $this->mode = MCRYPT_MODE_CBC;
      break;
      case AES::M_CFB:
      $this->mode = MCRYPT_MODE_CFB;
      break;
      case AES::M_ECB:
      $this->mode = MCRYPT_MODE_ECB;
      break;
      case AES::M_NOFB:
      $this->mode = MCRYPT_MODE_NOFB;
      break;
      case AES::M_OFB:
      $this->mode = MCRYPT_MODE_OFB;
      break;
      case AES::M_STREAM:
      $this->mode = MCRYPT_MODE_STREAM;
      break;
      default:
      $this->mode = MCRYPT_MODE_ECB;
      break;
    }
  }
  
/**
* 
* @return boolean
*/
  public function validateParams() {
    if ($this->data != null &&
        $this->secretKey != null &&
        $this->cipher != null) {
      return true;
    } else {
      return FALSE;
    }
  }
  
  public function setIV($IV) {
        $this->IV = $IV;
    }
  protected function getIV() {
      if ($this->IV == "") {
        $this->IV = mcrypt_create_iv(mcrypt_get_iv_size($this->cipher, $this->mode), MCRYPT_RAND);
      }
      return $this->IV;
  }
  
/**
* @return type
* @throws Exception
*/
  public function encrypt() {
    $this->setIV("0000000000000000");
    if ($this->validateParams()) {
	
	$block = mcrypt_get_block_size($this->cipher, 'cbc');
	$pad = $block - (strlen($this->data) % $block);
	$this->data .= str_repeat(chr($pad), $pad);	

	return trim(base64_encode($this->accessKey.
				  $this->getIV().
				  mcrypt_encrypt(
				    $this->cipher, $this->secretKey, $this->data, $this->mode, $this->getIV())));
    } else {
      throw new Exception('Invlid params!');
    }
  }

/**
* 
* @return type
* @throws Exception
*/
  public function decrypt() {
    $this->setIV("0000000000000000");
    if ($this->validateParams()) {
	
	$this->data = trim(mcrypt_decrypt($this->cipher, $this->secretKey, base64_decode($this->data), $this->mode, $this->getIV()));

	$block = mcrypt_get_block_size($this->cipher, 'cbc');
    	$packing = ord($this->data{strlen($this->data) - 1});
    	
	if($packing and ($packing < $block)){
      		for($P = strlen($this->data) - 1; $P >= strlen($this->data) - $packing; $P--){
    			if(ord($this->data{$P}) != $packing){
      				$packing = 0;
    			}
      		}
    	}
	
	$padding_size = 0;
	$last_word = ord($this->data[strlen($this->data)-1]);
	if ($last_word >= 0 && $last_word <= 16) {
		$padding_size = $last_word;
	}
//	echo "padding : ".strval($padding_size)."<br>";
//	echo "padding : ".strval(strlen($this->data))."<br>";
//	echo "padding : ".strval($padding_size)."<br>";
	$this->data = substr($this->data,0,strlen($this->data)-$padding_size);
//	echo "padding : ".strval(strlen($this->data))."<br>";
	
	return $this->data;

    } else {
      throw new Exception('Invlid params!');
    }
  }
  
}
?>
