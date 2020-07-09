<?php
    include_once('structure.php');

if (isset($_GET["a"]) && isset($_GET["b"])) 
{	
    $ip_request = $_SERVER['HTTP_X_REAL_IP'];
    
    $unprotect_request = $aes::decrypt(mysqli_real_escape_string($helper::connect(), $helper::fix_request($_GET["a"])), "pogvbufpcusmftrurrszycfefhidvdfs", "kspgywjaldzgwphn");
    $protect_request = mysqli_real_escape_string($helper::connect(), $helper::fix_request($_GET["b"]));
    
    $tempory_cipher_key = substr($unprotect_request, 0, 32);
    $tempory_iv_key = substr($unprotect_request, 32, 48);

    if ($unprotect_request == $aes::decrypt($protect_request, $tempory_cipher_key, $tempory_iv_key))
    {
        $date_register_key = date("Y-m-d H:i:s");
        
        $generated_session_cipher_key = $aes::generate_key(64); 
        $session_cipher_key = $data->cipher_key = $generated_session_cipher_key;
        
        $generated_session_iv_key = $aes::generate_key(32);
        $session_iv_key = $data->iv_key = $generated_session_iv_key;         
        
        mysqli_query($helper::connect(),"INSERT INTO aes_keys(cipher, iv, date, ip) VALUES('$generated_session_cipher_key', '$generated_session_iv_key', '$date_register_key', '$ip_request')");
        
        $get_cipher_key = $aes::get_key($data->cipher_key, "cipher", $date_register_key)[1];
        $get_iv_key = $aes::get_key($data->iv_key, "iv", $date_register_key)[2];

        echo $aes::encrypt($get_cipher_key, $tempory_cipher_key, $tempory_iv_key) . ";" . $aes::encrypt($get_iv_key, $tempory_cipher_key, $tempory_iv_key);
        
        unset($tempory_cipher_key);
        unset($tempory_iv_key); 
    }
    else
    {
        die($aes::encrypt("data_error", aes::$a, aes::$b));
    }
}
else
{
    die();
}
?>