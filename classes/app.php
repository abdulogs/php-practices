<?php 


class app {

	/***  Properties ***/
	public static $con;
	private static $host;
	private static $username;
	private static $password;
	private static $database;
	private static $production;
	private static $timezone;
	protected static $url;
	protected static $email;
	protected static $phone;
	protected static $address;

	public function __construct() {
		if (self::$production == true) {
			return error_reporting(0);
		} else if(self::$production == false) {
			return error_reporting(1);
		}
	}


	/************** Debug **************/
	/**************************************/ 
	public static function setProduction($value){
		return self::$production = $value;
	}

	/************** Debug **************/
	/**************************************/ 


	
	/**************************************/ 
	/************** url **************/
	public static function setUrl($val){
		if (strtolower($val) !== "default"){
			self::$url = $val;	
		} else {
			$dirs = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
			$str = "";
		
			if($_SERVER["REQUEST_SCHEME"] == "https"){
				foreach($dirs as $index => $name) {
					$str .= ($index >= 4 && $name !== "classes") ?  $name."/" : "";
				}
			} elseif($_SERVER["REQUEST_SCHEME"] == "http" && $_SERVER["SERVER_NAME"] == "localhost"){
				foreach($dirs as $index => $name) {
					$str .= ($index >= 3 && $name !== "classes") ?  $name."/" : "";
				}
			}
			self::$url = strtolower($_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"]."/".$str);
		}
	}

	public static function getUrl(){
		return self::$url;
	}
	/************** url **************/
	/*********************************/ 


	/*********************************/ 
	/************* App info **********/

	public static function setEmail($name){
		 self::$email = $name;
	}

	public static function getEmail(){
		return self::$email;
	}

	public static function setPhone($name){
		self::$phone = $name;
   	}

   	public static function getPhone(){
	   return self::$phone;
   	}

   	public static function setAddress($name){
		self::$address = $name;
	}

	public static function getAddress(){
	return self::$address;
	}


	/************** App info **************/
	/*********************************/ 

	/**************************************/ 
	/************** Database **************/
	public static function setHost($local = "", $live = ""){
		if (self::$production ==  true) {
			return self::$host = $live;
		} elseif (self::$production ==  false) {
			return self::$host = $local;
		}
	}

	public static function setUsername($local = "", $live = ""){
		if (self::$production ==  true) {
			return self::$username = $live;
		} elseif (self::$production ==  false) {
			return self::$username = $local;
		}
	}

	public static function setPassword($local = "", $live = ""){
		if (self::$production ==  true) {
			return self::$password = $live;
		} elseif (self::$production ==  false) {
			return self::$password = $local;
		}
	}

	public static function setDatabase($local = "", $live = ""){
		if (self::$production ==  true) {
			return self::$database = $live;
		} elseif (self::$production ==  false) {
			return self::$database = $local;
		}
	}

	public static function setConnection(){
		try {
			self::$con = new PDO("mysql:host=".self::$host.";dbname=".self::$database, self::$username, self::$password);
		   self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
		$error = "";
		$error .= "<h3 style='font-size:16px;font-family:arial;margin:2px 0;'>Opps there is a error in your code</h3>";
		$error .= "<p style='font-size:14px;font-family:arial;margin:2px 0;'><b>Code :</b> {$e->getCode()}</p>";
		$error .= "<p style='font-size:14px;font-family:arial;margin:2px 0;'><b>Line number :</b> {$e->getLine()}</p>";
		$error .= "<p style='font-size:14px;font-family:arial;margin:2px 0;'><b>Filename</b> :</b> {$e->getFile()}</p>";
		$error .= "<p style='font-size:14px;font-family:arial;margin:2px 0;'><b>Message</b> :</b> {$e->getMessage()}</p>";
		$error .= "<p style='font-size:14px;font-family:arial;margin:2px 0;'><b>Trace</b> :</b>".$e->getTraceAsString()."</p>";
		$error .= "<hr>";
		echo $error;
		}
	}
	/************** Database **************/
	/**************************************/ 








	/*************** Session **************/
	/**************************************/ 
	public static function startSession($value = false){
		if ($value == true){
			session_start();
		}
	}

	public static function setSession($key, $value){
		$_SESSION[$key] = $value;
	}

	public static function getSession($key){
		return $_SESSION[$key];
	}

	public static function sessionHas($key){
		if(isset($_SESSION[$key])){
			return true;
		} 
	}

	public static function sessionHasNot($key){
		if(!isset($_SESSION[$key])){
			return true;
		} 
	}
	/*************** Session **************/
	/**************************************/ 






	/*****************************************/ 
	/************** Redirection **************/ 
	public static function redirect($path, $time ="", $local = true){
		if ($local == false) {
		  $path = $path;
		} else if($local == true) {
		  $path = self::$url."".$path;
		}
	
		if (empty($time)) {
		  echo "<script>window.open('".$path."', '_SELF');</script>";
		} else if(!empty($time)) {
		  echo "<script>setTimeout(function() { window.open('".$path."', '_SELF');}, {$time});</script>";
		}
	}

	public static function getRedirect($values, $path = "", $set = true){
		foreach ($values as $value) {
		   if ($set == true) {
			 if (isset($_GET[$value])) {
			   header("location: ".self::$url.$path);
			 }
		   } else if ($set == false) {
			 if (!isset($_GET[$value])) {
			   header("location: ".self::$url.$path);
			 }
		   }
		}
	}

	public static function postRedirect($values, $path = "", $set = true){
		foreach ($values as $value) {
		   if ($set == true) {
			 if (isset($_POST[$value])) {
			   header("location: ".self::$url.$path);
			 }
		   } else if ($set == false) {
			 if (!isset($_POST[$value])) {
			   header("location: ".self::$url.$path);
			 }
		   }
		}
	}

	public static function sessionRedirect($values, $path = "", $set = true){
		foreach ($values as $value) {
		   if ($set == true) {
			 if (isset($_SESSION[$value])) {
			   header("location: ".self::$url.$path);
			 }
		   } else if ($set == false) {
			 if (!isset($_SESSION[$value])) {
			   header("location: ".self::$url.$path);
			 }
		   }
		}
	}


	public static function isLogin($value, $path = ""){
		if (isset($_SESSION[$value])) {
			header("location: ".self::$url.$path);
		}
	}

	public static function isLogout($value, $path = ""){
		if (!isset($_SESSION[$value])) {
			header("location: ".self::$url.$path);
		}
	}

	public static function loginValue($value){
		if (isset($_SESSION[$value])) {
			return true;
		} else {
			return false;
		}
	}

	public static function logoutValue($value){
		if (!isset($_SESSION[$value])) {
			return true;
		} else {
			return false;
		}
	}

	public static function roleValue($value){
		if (!isset($_SESSION[$value])) {
			return true;
		} else {
			return false;
		}
	}


	public static function sessionRoleRedirect($key, $val, $path = ""){
		if(isset($_SESSION[$key])){
			if ($_SESSION[$key] == $val) {
				echo "<script>window.open('".$path."', '_SELF');</script>";
			}
		}
	}

	public static function roleRedirect($key, $val, $path = ""){
		if ($key == $val) {
			echo "<script>window.open('".$path."', '_SELF');</script>";
		}
	}


	public static function reload($time =""){
		if (empty($time)) {
		  echo "<script>location.reload();</script>";
		} else if(!empty($time)) {
		  echo "<script>setTimeout(function() { location.reload();}, {$time});</script>";
		}
	}
	/************** Redirection **************/ 
	/*****************************************/ 




	
	/**************************************/ 
	/************** TimeZone **************/ 
	public static function getTimeZone(){
		return self::$timezone;
	}

	public static function setTimeZone($value){
		self::$timezone = $value;
		return date_default_timezone_set(self::$timezone);
	}
	/************** TimeZone **************/ 
	/**************************************/ 







	/*******************************************/ 
	/************** Get ip address **************/ 
	public static function getIp() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	/************** Get ip address *************/ 
	/*******************************************/ 







	/*****************************************/ 
	/************** Get or Post **************/ 
	public static function get($data, $clean = []){
        $param = (isset($_GET[$data])) ? $_GET[$data] : "<b>GET => {$data}</b>  param not set <br>";
        $param = (in_array("tags", $clean)) ? strip_tags($param): $param;
        $param = (in_array("entities", $clean)) ? htmlentities($param): $param;
        $param = (in_array("chars", $clean)) ? htmlspecialchars($param): $param;
        return $param;
    }

    public static function post($data, $clean = []){
        $param = (isset($_POST[$data])) ? $_POST[$data] : "<b>POST => {$data}</b>  param not set <br>";
        $param = (in_array("tags", $clean)) ? strip_tags($param): $param;
        $param = (in_array("entities", $clean)) ? htmlentities($param): $param;
        $param = (in_array("chars", $clean)) ? htmlspecialchars($param): $param;
        return $param;
    }
	/************** Get or Post *************/ 
	/*******************************************/ 







	/********************************************/ 
	/**************Display messages**************/ 
	  public static function success($value =""){
		echo '<div class="custom-alert" role="alert">
		<div class="custom-alert-body">
		  <span class="fa fa-check ti-check custom-alert-icon-success"></span>
		<div class="custom-alert-content">
		  <h6 class="custom-alert-heading"><b>Congratulations!</b></h6>
		  <p class="custom-alert-subheading">'.$value.'</p>
		</div>
		<button class="fa fa-close alert-close ti-close" type="button" onclick="parentNode.parentNode.parentNode.removeChild(parentNode.parentNode);"
		 data-dismiss="alert"></button></p>
		</div>';
	  }
  
	  public static function error($value = ""){
		echo '<div class="custom-alert" role="alert">
		<div class="media custom-alert-body">
		  <span class="fa fa-close ti-close custom-alert-icon-error"></span>
		<div class="media-body custom-alert-content">
		  <h6 class="custom-alert-heading"><b>Opps!</b> you got an error</h6>
		  <p class="custom-alert-subheading">'.$value.'</p>
		</div>
		<button class="fa fa-close alert-close ti-close" onclick="parentNode.parentNode.parentNode.removeChild(parentNode.parentNode);"
		type="button" data-dismiss="alert"></button></p>
		</div>';
	  }
	/**************Display messages**************/ 
	/********************************************/ 






	


	/********************************************/ 
	/******************Datetime******************/ 
	public static function timeAgo($time) { 
        // echo $time_elapsed = timeAgo("21--21"); //The argument $time is in timestamp (Y-m-d H:i:s) format.
        $time = strtotime($time);
        $cur_time   = time();
        $time_elapsed   = $cur_time - $time;
        $seconds    = $time_elapsed ;
        $minutes    = round($time_elapsed / 60 );
        $hours      = round($time_elapsed / 3600);
        $days       = round($time_elapsed / 86400 );
        $weeks      = round($time_elapsed / 604800);
        $months     = round($time_elapsed / 2600640 );
        $years      = round($time_elapsed / 31207680 );
        // Seconds
        if($seconds <= 60){
            return "just now";
        }
        //Minutes
        else if($minutes <=60){
            if($minutes==1){
                return "one minute ago";
            }
            else{
                return "$minutes minutes ago";
            }
        }
        //Hours
        else if($hours <=24){
            if($hours==1){
                return "an hour ago";
            }else{
                return "$hours hrs ago";
            }
        }
        //Days
        else if($days <= 7){
            if($days==1){
                return "yesterday";
            }else{
                return "$days days ago";
            }
        }
        //Weeks
        else if($weeks <= 4.3){
            if($weeks==1){
                return "a week ago";
            }else{
                return "$weeks weeks ago";
            }
        }
        //Months
        else if($months <=12){
            if($months==1){
                return "a month ago";
            }else{
                return "$months months ago";
            }
        }
        //Years
        else{
            if($years==1){
                return "one year ago";
            }else{
                return "$years years ago";
            }
        }
    }

    public static function formatDatetime($datetime = "Y-m-d - h:i a", $pattern = "Y-m-d - h:i a"){
		$date = date($pattern, strtotime($datetime));
		return $date;
	}

    public static function currentDatetime($pattern = "Y-m-d - h:i a"){
		$date =  date("{$pattern}");
		return $date;
	}
	/******************Datetime******************/ 
	/********************************************/ 


	/********************************************/ 
	/******************Component*****************/ 
	public static function component($file){
		require_once "components/".$file.".php";
	}

	/******************Component*****************/ 
	/********************************************/ 



	public function __destruct() {}


}


require_once 'settings.php';

?>