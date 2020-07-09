<?php
    include_once("structure.php");

if ($data->cipher_key == null || $data->iv_key == null)
    die();

function parse_hours($pasrsed_hours)
{
	$array_hours = explode(":", $pasrsed_hours);
				
	return (intval($array_hours[0]) * 3600) + (intval($array_hours[1] ) * 60) + intval($array_hours[3]);
}
function get_key($key) 
{
    if ($mysqli == null)
        $mysqli = mysqli_connect(HOST,USER,PASS,DB);

    $key = mysqli_real_escape_string($mysqli, $key);
            
    $query = mysqli_query($mysqli, "SELECT * FROM license_keys WHERE license_key = '$key' AND status = 0");
            
    if (mysqli_error($mysqli))
        die(mysqli_error($mysqli));
            
    if (mysqli_num_rows($query) > 0)
        return mysqli_fetch_row($query);
    else
        return false;
}	    
if (isset($_GET["a"]) && isset($_GET["b"])) 
{
	$key = $aes::decrypt(mysqli_real_escape_string($helper::connect(), $helper::fix_request($_GET["a"])), $_SESSION['cipher_key'], $_SESSION['iv_key']);
	$hwid = $aes::decrypt(mysqli_real_escape_string($helper::connect(), $helper::fix_request($_GET["b"])), $_SESSION['cipher_key'], $_SESSION['iv_key']);
    $getted_key = get_key($key);
	$date = time(); $date += parse_hours($getted_key[3]);
	
	$query_check_key_ban = "SELECT * FROM license_keys WHERE license_key = '$key' AND banned = 1 LIMIT 1";    //проверяем забанен ли данный ключ
    $sql_check_key_ban = mysqli_query($helper::connect() ,$query_check_key_ban) or die(mysqli_error($helper::connect()));    //проверяем забанен ли данный ключ
	
    $query_check_subscribe_ban = "SELECT * FROM subscriptions WHERE license_key = '$key' AND hwid = '$hwid' AND banned = 1 LIMIT 1";    //проверяем забанена ли подписка
    $sql_check_subscribe_ban = mysqli_query($helper::connect(), $query_check_subscribe_ban) or die(mysqli_error($helper::connect()));    //проверяем забанена ли подписка
    
    if (mysqli_num_rows($sql_check_key_ban) == 1 || mysqli_num_rows($sql_check_subscribe_ban) == 1)    //если ключ или подписка забанена
    {
        $query_check_global_ban = "SELECT * FROM global_bans WHERE hwid = '$hwid' LIMIT 1";    //проверяем был ли записан забаненный хвид в таблицу
        $sql_check_global_ban = mysqli_query($helper::connect(), $query_check_global_ban) or die(mysqli_error($helper::connect()));    //проверяем был ли записан забаненный хвид в таблицу

        if (mysqli_num_rows($sql_check_global_ban) == 0)    //если хвид не был записан в таблицу
        {
            mysqli_query($helper::connect(), "INSERT INTO global_bans(hwid) VALUES('$hwid')") or die(mysqli_error($helper::connect()));    //записываем данный хвид
        }
    }

    $query_get_global_ban = "SELECT * FROM global_bans WHERE hwid = '$hwid' LIMIT 1";    //получаем глобальный бан, так как он был установлен ранее
    $sql_get_global_ban = mysqli_query($helper::connect(), $query_get_global_ban) or die(mysqli_error($helper::connect()));    //получаем глобальный бан, так как он был установлен ранее
    
    if (mysqli_num_rows($sql_get_global_ban) == 1)    //если глобальный бан установлен
    {
        $query_check_other_subscribe = "SELECT * FROM subscriptions WHERE hwid = '$hwid' AND banned = 0 LIMIT 1";    //проверяем на наличие подписок помимо этой
        $sql_check_other_subscribe = mysqli_query($helper::connect(), $query_check_other_subscribe) or die(mysqli_error($helper::connect()));    //проверяем на наличие активных подписок помимо этой
        
        mysqli_query($helper::connect(), "UPDATE license_keys SET banned = '1' WHERE license_key = '$key'") or die(mysqli_error($helper::connect()));    //выставляем бан на ключ
        
        if (mysqli_num_rows($sql_check_other_subscribe) == 1)    //если подписки найдены
        {
            mysqli_query($helper::connect(), "UPDATE subscriptions SET banned = '1' WHERE hwid = '$hwid'") or die(mysqli_error($helper::connect()));    //выставляем блокировку всем подпискам
        }            
        die($aes::encrypt("banned", $_SESSION['cipher_key'], $_SESSION['iv_key']));    //да связи
    }
    else 
    {    
        $query_active_key = "SELECT * FROM license_keys WHERE license_key = '$key' AND status = 0 AND banned = 0 LIMIT 1";
        $sql_active_key = mysqli_query($helper::connect(), $query_active_key) or die(mysqli_error($helper::connect()));
        
        if (mysqli_num_rows($sql_active_key) == 1 && mysqli_query($helper::connect(), "UPDATE license_keys SET status = '1' WHERE license_key = '$getted_key[1]'") == 1)
        {
        	mysqli_query($helper::connect(), "INSERT INTO subscriptions(license_key, cheat, hwid, end) VALUES('$getted_key[1]', '$getted_key[2]', '$hwid', FROM_UNIXTIME($date))");
        }
        
        $query_actived_key = "SELECT * FROM license_keys WHERE license_key = '$key' AND status = 1 AND banned = 0 LIMIT 1";
        $sql_actived_key = mysqli_query($helper::connect(), $query_actived_key) or die(mysqli_error($helper::connect()));    
        
        if (mysqli_num_rows($sql_actived_key) == 0 && mysqli_num_rows($sql_active_key) == 0)
        {
            die($aes::encrypt("invalid_key", $_SESSION['cipher_key'], $_SESSION['iv_key']));
        }
        
        if (mysqli_num_rows($sql_actived_key) == 1)
        {
            $query_subscribe = "SELECT * FROM subscriptions WHERE license_key = '$key' AND hwid = '$hwid' AND banned = 0 LIMIT 1";
            $sql_subscribe = mysqli_query($helper::connect(), $query_subscribe) or die(mysqli_error($helper::connect())); ;
    
            if (mysqli_num_rows($sql_subscribe) == 1) 
            {
        		$query_check_active_subscribe =  "SELECT * FROM subscriptions WHERE license_key = '$key' AND end > NOW()";
        		$sql_check_active_subscribe = mysqli_query($helper::connect(), $query_check_active_subscribe) or die(mysqli_error($helper::connect()));
        		
        		while ($info_active_subscribe = mysqli_fetch_assoc($sql_check_active_subscribe)) 
        		{
        			$cheat_name = $info_active_subscribe["cheat"];
        			$query_get_active_subscribe_cheats = "SELECT * FROM cheats WHERE name = '$cheat_name' LIMIT 1";
        			$sql_get_active_subscribe_cheats = mysqli_query($helper::connect(), $query_get_active_subscribe_cheats) or die(mysqli_error($helper::connect()));
        			$ip_request = $_SERVER['HTTP_X_REAL_IP'];
        			
        			if (mysqli_num_rows($sql_get_active_subscribe_cheats) == 1)
        			{			
            		    $info_active_cheat = mysqli_fetch_assoc($sql_get_active_subscribe_cheats);
            		    
            		    $return_result = $info_active_cheat["name"] . ";"  . $info_active_cheat["secure"];
            		    
            		    mysqli_query($helper::connect(), "INSERT INTO launch_log(license_key, ip) VALUES ('$key', '$ip_request')");
            		}
            		else
            		{
            		    die($aes::encrypt("unknown_cheat", $_SESSION['cipher_key'], $_SESSION['iv_key']));
            		}
        		}
        		if ($return_result == null)
        		{
        		    die($aes::encrypt("expired_subscribe", $_SESSION['cipher_key'], $_SESSION['iv_key']));
        		}
        		die($aes::encrypt($return_result, $_SESSION['cipher_key'], $_SESSION['iv_key']));
            }
            else
            {
                die($aes::encrypt("data_error", $_SESSION['cipher_key'], $_SESSION['iv_key']));
            }
        }
    }
}
else
{
    die();
}
?>