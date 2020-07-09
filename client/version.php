<?php
    include_once("structure.php");
    
if ($data->cipher_key == null || $data->iv_key == null)
    die();

if (isset($_GET["a"])) 
{
    $version = $aes::decrypt(mysqli_real_escape_string($helper::connect(), $helper::fix_request($_GET["a"])), $_SESSION['cipher_key'], $_SESSION['iv_key']);
    
    $query_check_valid_version = "SELECT * FROM loader_settings WHERE version = '$version'";
    $sql_check_valid_version = mysqli_query($helper::connect(), $query_check_valid_version) or die(mysqli_error($helper::connect()));
    
    if (mysqli_num_rows($sql_check_valid_version) > 0)
    {
     	while ($get_info_loader_settings = mysqli_fetch_assoc($sql_check_valid_version)) 
    	{
    	    die($aes::encrypt($get_info_loader_settings["version"], $_SESSION['cipher_key'], $_SESSION['iv_key']) . ";" . $aes::encrypt($get_info_loader_settings["status"], $_SESSION['cipher_key'], $_SESSION['iv_key']));
    	}
    }
    else
    {
         die($aes::encrypt("incorrect_version", $_SESSION['cipher_key'], $_SESSION['iv_key']));
    }
}
else
{
    die();
}
?>