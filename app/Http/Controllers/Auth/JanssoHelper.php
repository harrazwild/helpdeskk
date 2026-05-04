<?php
namespace App\Http\Controllers\Auth;

trait JanssoHelper
{
	public static function safe_b64encode($string='') {
        $data = base64_encode($string);
        $data = str_replace(['+','/','='],['-','_',''],$data);
        return $data;
    }

    public static function safe_b64decode($string='') {
        $data = str_replace(['-','_'],['+','/'],$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public static function JANSSOCrypt($value=false , $key = false){

        if(!$value) return false;
        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($iv_size);
        $crypttext = openssl_encrypt($value, 'aes-256-cbc', $key , OPENSSL_RAW_DATA, $iv);
        return self::safe_b64encode($iv.$crypttext);
    }

    public static function JANSSODecrypt($value=false , $key = false ){
        if(!$value) return false;
        // echo "Have value<br>";
        $crypttext = self::safe_b64decode($value);
        // echo "crypttext: ".$crypttext;
        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        // echo "<br>iv_size: ".$iv_size;
        $iv = substr($crypttext, 0, $iv_size);
        // echo "<br>iv: ".$iv;
        $crypttext = substr($crypttext, $iv_size);
        // echo "<br>crypttext: ".$crypttext;
        if(!$crypttext) return false;
        $decrypttext = openssl_decrypt($crypttext, 'aes-256-cbc', $key , OPENSSL_RAW_DATA, $iv);
        // echo "<br>decrypttext: ".$decrypttext;
        return rtrim($decrypttext);
    }
}
?>