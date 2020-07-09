<?php
include_once("settings.php");
//ini_set("display_errors", 0);
session_start();

class panel_access
{
    private $mysqli;
    
    public function __construct()
    {
        $mysqli = mysqli_connect(HOST, USER, PASS, DB);
    }
    public function authorization($user, $password)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $user = mysqli_real_escape_string($mysqli, $user);
        $password = md5($password);
        
        $query = mysqli_query($mysqli, "SELECT * FROM panel WHERE login = '$user' AND password = '$password' AND banned = 0 LIMIT 1");
        
        if (mysqli_error($mysqli))
            return false;
        
        if (mysqli_num_rows($query) > 0)
            return mysqli_fetch_row($query);
        else
            return false;
    }
    public function check_job($user, $job)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $user = mysqli_real_escape_string($mysqli, $user);
        $job = mysqli_real_escape_string($mysqli, $job);
        
        $query = mysqli_query($mysqli, "SELECT * FROM panel WHERE login = '$user' AND job = '$job' AND banned = 0 LIMIT 1");
        
        if (mysqli_error($mysqli))
            return false;
        
        if (mysqli_num_rows($query) > 0)
            return mysqli_fetch_row($query);
        else
            return false;
    }    
    public function register($user, $password, $access_key)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $user = mysqli_real_escape_string($mysqli, $user);
        $password = md5($password);
        $access_key = mysqli_real_escape_string($mysqli, $access_key);        
        
        $query_check_access_key = mysqli_query($mysqli, "SELECT * FROM panel WHERE access_key = '$access_key' AND status = 0 LIMIT 1");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));
        
        if (mysqli_num_rows($query_check_access_key) == 0)
            return false;
            
        $query_check_avaliable_login = mysqli_query($mysqli, "SELECT * FROM panel WHERE login = '$user' LIMIT 1");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));
        
        if (mysqli_num_rows($query_check_avaliable_login) > 0)
            return false;            
        
        $query_register_user = mysqli_query($mysqli, "UPDATE panel SET login = '$user', password = '$password', status = '1' WHERE access_key = '$access_key'");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));
            
        return true;
    } 
}
class panel_admin
{
    private $mysqli;
    
    public function __construct()
    {
        $mysqli = mysqli_connect(HOST, USER, PASS, DB);
    }
    function generate_random_string($length) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) 
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
            
        return $randomString;
    }    
    public function generate_license_keys($cheat, $time, $seller)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $license_key = self::generate_random_string(60);
        $cheat = mysqli_real_escape_string($mysqli, $cheat);
        $time = mysqli_real_escape_string($mysqli, $time);
        $seller = mysqli_real_escape_string($mysqli, $seller); 
        
        $query = mysqli_query($mysqli, "INSERT INTO license_keys(license_key, cheat, time, seller) VALUES('$license_key', '$cheat', '$time', '$seller')");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));
            
        return $license_key;
    }
    public function generate_access_key($job)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $access_key = self::generate_random_string(60);
        $job = mysqli_real_escape_string($mysqli, $job);
        
        $query = mysqli_query($mysqli, "INSERT INTO panel(access_key, job) VALUES('$access_key', '$job')");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));
            
        return $access_key;
    }
    public function get_launch_log($license_key)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $license_key = mysqli_real_escape_string($mysqli, $license_key);
        
        $query = mysqli_query($mysqli, "SELECT * FROM launch_log WHERE license_key = '$license_key'");
            
        while ($row = mysqli_fetch_assoc($query))
        {
            $date_launch = $row["date"];
            $ip_launch = $row["ip"];
        }
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));        
            
        return $date_launch . ";" . $ip_launch;
    }
    public function get_data_subscribe($license_key)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $license_key = mysqli_real_escape_string($mysqli, $license_key);
        
        $query = mysqli_query($mysqli, "SELECT * FROM subscriptions WHERE license_key = '$license_key'");
            
        while ($row = mysqli_fetch_assoc($query))
        {
            $cheat = $row["cheat"];
            $end = $row["end"];
            $banned = $row["banned"];
        }
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));        
            
        return $license_key . ";" . $cheat . ";" . $end . ";" . $banned;
    }
    public function manage_users_panel($user, $action)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $user = mysqli_real_escape_string($mysqli, $user);
        $action = mysqli_real_escape_string($mysqli, $action);
        
        if ($action == "Ban")
            $query_set_ban = mysqli_query($mysqli, "UPDATE panel SET banned = 1 WHERE login = '$user'");
            
        else if ($action == "Unban")
            $query_set_unban = mysqli_query($mysqli, "UPDATE panel SET banned = 0 WHERE login = '$user'");
            
        else if ($action == "Remove")
            $query_remove = mysqli_query($mysqli, "DELETE FROM panel WHERE login = '$user'");
            
        else
            die("Incorrect action");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));        
        
        return true;
    }    
    public function manage_subscribe($license_key, $action)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $license_key = mysqli_real_escape_string($mysqli, $license_key);
        $action = mysqli_real_escape_string($mysqli, $action);        
            
        if ($action == "Ban")
        {
            $query_set_ban_subscribe = mysqli_query($mysqli, "UPDATE subscriptions SET banned = 1 WHERE license_key = '$license_key'");
            $query_set_ban_key = mysqli_query($mysqli, "UPDATE license_keys SET banned = 1 WHERE license_key = '$license_key'");            
        }
            
        else if ($action == "Unban")
        {
            $query_set_unban_subscribe = mysqli_query($mysqli, "UPDATE subscriptions SET banned = 0 WHERE license_key = '$license_key'");
            $query_set_unban_key = mysqli_query($mysqli, "UPDATE license_keys SET banned = 0 WHERE license_key = '$license_key'");                
        }
            
        else if ($action == "Remove")
        {
            $query_remove_subscribe = mysqli_query($mysqli, "DELETE FROM subscriptions WHERE license_key = '$license_key'");
            $query_remove_key =  mysqli_query($mysqli, "DELETE FROM license_keys WHERE license_key = '$license_key'");
        }
        else if ($action == "Reset hwid")
        {
            $query_reset_hwid = mysqli_query($mysqli, "UPDATE subscriptions SET hwid = '' WHERE license_key = '$license_key'");
        }        
            
        else
            die("Incorrect action");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));                
            
        return true;
    }
    public function manage_global_bans($hwid, $action)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $hwid = mysqli_real_escape_string($mysqli, $hwid);
        $action = mysqli_real_escape_string($mysqli, $action);        
            
        if ($action == "Ban")
        {
            $query_check_global_ban = mysqli_query($mysqli, "SELECT * FROM global_bans WHERE hwid = '$hwid'");
            if (mysqli_num_rows($query_check_global_ban) == 0)
            {            
                $query_set_global_ban = mysqli_query($mysqli, "INSERT INTO global_bans(hwid) VALUES('$hwid')");
            }
            else
                die("Global ban already setted");
            
        }
            
        else if ($action == "Unban")
        {
            $query_check_global_ban = mysqli_query($mysqli, "SELECT * FROM global_bans WHERE hwid = '$hwid'");
            if (mysqli_num_rows($query_check_global_ban) == 1)
            {                
                $query_unban_global_ban = mysqli_query($mysqli, "DELETE FROM global_bans WHERE hwid = '$hwid'");   
            }
            else
                die("Global ban not setted");            
        }
            
        else
            die("Incorrect action");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));                
            
        return true;
    }
    public function manage_loader_settings($version, $status)
    {
         if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
            
        $version = mysqli_real_escape_string($mysqli, $version);
        $status = mysqli_real_escape_string($mysqli, $status);    
        
        $query_set_version = mysqli_query($mysqli, "UPDATE loader_settings SET version = '$version'");
        $query_set_status = mysqli_query($mysqli, "UPDATE loader_settings SET status = '$status'");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));        
        
        return true;        
    }
    public function set_secure_cheat($cheat, $secure)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $cheat = mysqli_real_escape_string($mysqli, $cheat);
        $secure = mysqli_real_escape_string($mysqli, $secure);
        
        $query_set_ban_key = mysqli_query($mysqli, "UPDATE cheats SET secure = '$secure' WHERE name = '$cheat'");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));        
        
        return true;
    }    
    public function add_new_cheat($name, $secure)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $name = mysqli_real_escape_string($mysqli, $name);
        $secure = mysqli_real_escape_string($mysqli, $secure);

        $query_check_avaliable_cheat = mysqli_query($mysqli, "SELECT * FROM cheats WHERE name = '$name' LIMIT 1");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));
        
        if (mysqli_num_rows($query_check_avaliable_cheat) > 0)
            return false;           
        
        $query = mysqli_query($mysqli, "INSERT INTO cheats(name, secure) VALUES('$name', '$secure')");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));
            
        return true;
    }   
}
class panel_reseller
{
    private $mysqli;
    
    public function __construct()
    {
        $mysqli = mysqli_connect(HOST, USER, PASS, DB);
    }
    public function get_data_subscribe($license_key, $seller)
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST, USER, PASS, DB);
        
        $license_key = mysqli_real_escape_string($mysqli, $license_key);        
        $seller = mysqli_real_escape_string($mysqli, $seller);
        
        $query_check_seller_license_key = mysqli_query($mysqli, "SELECT * FROM license_keys WHERE license_key = '$license_key' AND seller = '$seller' LIMIT 1");
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));
        
        if (mysqli_num_rows($query_check_seller_license_key) == 0)
            return false;        
        
        $query = mysqli_query($mysqli, "SELECT * FROM subscriptions WHERE license_key = '$license_key'");
            
        while ($row = mysqli_fetch_assoc($query))
        {
            $cheat = $row["cheat"];
            $end = $row["end"];
            $banned = $row["banned"];
        }
        
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));        
            
        return $license_key . ";" . $cheat . ";" . $end . ";" . $banned;
    }       
}

//setup global var
$panel_access = new panel_access();
$panel_admin = new panel_admin();
$panel_reseller = new panel_reseller();