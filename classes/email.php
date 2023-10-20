<?php
require_once 'app.php';

/*** Send email ***/
class mailer extends app {

  /*** properties ***/
  private static $reciever;
  private static $sender;
  private static $subject;
  private static $web;
  private static $template;
  private static $logo;
  private static $btn;
  private static $btnredirect;
  private static $title;
  private static $description;
  private static $body;
  private static $fullname;

  public static function template($name){
    self::$template = $name;
    return __CLASS__;
  }

  public static function file($name){
    if (file_exists("{$name}.php")) {
      self::$body = file_get_contents("{$name}.php");
    } else {
      $error = "<p>Invalid template name <mark>".$name."</mark></p>";
      $error .= "<p>This template file not exists in templates folder</p>";
      parent::error($error);
    }
    return __CLASS__;
  }
  public static function fullname($name){
    self::$fullname = $name;
    return __CLASS__;
  }

  public static function reciever($name){
    self::$reciever = $name;
    return __CLASS__;
  }

  public static function sender($name){
    self::$sender = $name;
    return __CLASS__;
  }

  public static function subject($name){
    self::$subject = $name;
    return __CLASS__;
  }

  public static function web($name){
    self::$web = $name;
    return __CLASS__;
  }

  public static function logo($name){
    self::$logo = parent::$url.$name;
    return __CLASS__;
  }

  public static function btn($name){
    self::$btn = $name;
    return __CLASS__;
  }

  public static function btnRedirect ($name){
    self::$btnredirect = parent::$url.$name;
    return __CLASS__;
  }

  public static function description($name){
    self::$description = $name;
    return __CLASS__;
  }

  public static function title($name){
    self::$title = $name;
    return __CLASS__;
  }


  public static function send($view = false){
    $sender = self::$web." <".self::$sender.">"; // sender
    $reciever = self::$reciever; //reciever
    $subject = self::$subject;
    // headers
    $headers = "From: ".self::$sender."\r\nReply-To: ".self::$sender."\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    if (self::$template == "forgot-password"  || self::$template == "verify-account") {
      $params = [
        "logo" => self::$logo,
        "url" => parent::$url,
        "btn" => self::$btn,
        "redirect" => self::$btnredirect,
        "description" => self::$description,
        "title" => self::$title,
        "name" => self::$reciever,
        "fullname" => self::$fullname
      ];
    } elseif (self::$template == "contact") {
      $params = [
        "logo" => self::$logo,
        "url" => parent::$url,
        "description" => self::$description,
        "title" => self::$title,
        "name" => self::$reciever,
        "fullname" => self::$fullname
      ];
    }

    foreach (array_keys($params) as $key){
      self::$body = str_replace("@".$key."@", $params[$key], self::$body);
    }

    if ($view == true) {
      echo self::$body;
    }

    if ( mail($reciever, $subject, self::$body, $headers)){
      return true;
    } else {
      // $error = "<p>Email not sent yet</p>";
      // parent::err($error);
    }
  }
}
