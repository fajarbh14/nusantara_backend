<?php
namespace App\Helpers;
class Context {

  public static $user = null;

  public static function setUser($data){
    static::$user = $data;
  }

  public static function user(){
    return static::$user;
  }

}
?>