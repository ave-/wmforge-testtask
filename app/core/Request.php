<?php
/**
 * Created by PhpStorm.
 * User: AVE
 * Date: 09.02.2016
 * Time: 14:47
 */

namespace core;


use models\UserModel;

class Request
{

   public function isPostRequest()
   {
      return $this->getMethod() === 'POST';
   }

   public function isGetRequest()
   {
      return $this->getMethod() === 'GET';
   }

   public function getMethod()
   {
      if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']))
      {
         return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
      }
      else
      {
         return isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
      }
   }

   public function post($name = null, $default_value = null)
   {
      if ($name === null)
      {
         return $_POST;
      }
      else
      {
         return isset($_POST[$name]) ? $_POST[$name] : $default_value;
      }
   }

   public function getFile($name)
   {
      return isset($_FILES[$name]) ? $_FILES[$name] : null;
   }

   public static function getAuthCookie()
   {
      return isset($_COOKIE[md5('ave_simple_mvc')]) ? $_COOKIE[md5('ave_simple_mvc')] : null;
   }

   public static function saveAuthCookie($value)
   {
      setcookie(md5('ave_simple_mvc'), $value, time()+2592000); //30 days
   }

   public static function clearAuthCookie()
   {
      unset($_COOKIE[md5('ave_simple_mvc')]);
      setcookie(md5('ave_simple_mvc'), null, -1, '/');
   }
}