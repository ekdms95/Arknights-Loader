<?php
    include_once("settings.php");
    ini_set("display_errors", 0);
    $data = session::get_instance();
    
class session
{
    const SESSION_STARTED = TRUE;
    const SESSION_NOT_STARTED = FALSE;
   
    // The state of the session
    private $session_state = self::SESSION_NOT_STARTED;
   
    // THE only instance of the class
    private static $instance;
   
    public function __construct() {}
   
    public static function get_instance()
    {
        if ( !isset(self::$instance))
        {
            self::$instance = new self;
        }
       
        self::$instance->start_session();
       
        return self::$instance;
    }
   
    public function start_session()
    {
        if ( $this->session_state == self::SESSION_NOT_STARTED )
        {
            $this->session_state = session_start();
        }
       
        return $this->session_state;
    }
   
    public function __set( $name , $value )
    {
        $_SESSION[$name] = $value;
    }
   
    public function __get( $name )
    {
        if ( isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
    }
   
   
    public function __isset( $name )
    {
        return isset($_SESSION[$name]);
    }
   
   
    public function __unset( $name )
    {
        unset( $_SESSION[$name] );
    }

    public function destroy()
    {
        if ( $this->session_state == self::SESSION_STARTED )
        {
            $this->session_state = !session_destroy();
            unset( $_SESSION );
           
            return !$this->session_state;
        }
       
        return FALSE;
    }
}
class helper
{
    public function __construct() {}
     
    public function connect()
    {
        return mysqli_connect(HOST, USER, PASS, DB);
    }
    public function generate_random_string($length = 30) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) 
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
            
        return $randomString;
    }
    public function fix_request($string) 
    {
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        return str_replace($entities, $replacements, urlencode($string));
    }
}

class aes
{
    public function __construct() {}
    
    public function generate_key($length) 
    {
      $size_key = $length / 4;
      $key = substr(bin2hex(openssl_random_pseudo_bytes($size_key)), 0, $length);
      
      return $key;
    }
    public function encrypt($str, $cipher_key, $iv_key) 
    {
        $method = 'aes-256-cfb';
        $encrypted = base64_encode(openssl_encrypt($str, $method, $cipher_key, true, $iv_key));
        
        return $encrypted;
    }
    public function decrypt($str, $cipher_key, $iv_key) 
    {
        $method = 'aes-256-cfb';
        $decrypted = openssl_decrypt(base64_decode($str), $method, $cipher_key, true, $iv_key);
        
        return $decrypted;
    }
    public function get_key($key, $key_mode, $date_register_key) 
    {
        if ($mysqli == null)
            $mysqli = mysqli_connect(HOST,USER,PASS,DB);
    
        $key = mysqli_real_escape_string($mysqli, $key);
        $key_mode = mysqli_real_escape_string($mysqli, $key_mode);
        $date_register_key = mysqli_real_escape_string($mysqli, $date_register_key);
                
        $query = mysqli_query($mysqli, "SELECT * FROM aes_keys WHERE $key_mode = '$key' AND date = '$date_register_key' LIMIT 1");
                
        if (mysqli_error($mysqli))
            die(mysqli_error($mysqli));
                
        if (mysqli_num_rows($query) > 0)
                return mysqli_fetch_row($query);
        else
            return false;
    }    
}
//setup global var
$aes = new aes(); 
$helper = new helper();
?>