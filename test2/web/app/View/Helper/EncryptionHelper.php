<?php
/**
 * Encryption helper handles encryption functions used through out projects
 *
 * @author Priti Kabra
 * @since 1.0
 */
class EncryptionHelper extends AppHelper 
{
    public $helpers = array('Session');
    var $skey     = "SuPerEncKey20101";

    /**
     * encode a string
     * @param string $string crypted string
     * @return string
     * @author Gaurav
     */
    function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }
    
    /**
     * decode a string
     * @param string $string crypted string
     * @return string
     * @author Gaurav
     */
    function safe_b64decode($string) 
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
    
    /**
     * Encode a string
     * @param string $value string to be encoded
     * @return string encoded string
     * @author Gaurav
     */ 
  function encode($value){
        if(!$value){return false;}
        $text = $value;
        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $cryptText = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($cryptText));
  }

  /**
     * Decode an encoded string
     * @param string $value Encoded string
     * @return string original string
     * @author Gaurav
     */
    function decode($value)
    {
        if (!$value) {
            return false;
        }
        $cryptText = $this->safe_b64decode($value);
        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $cryptText, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }
}
