<?php
namespace core;
/**
 * Created by PhpStorm.
 * User: AVE
 * Date: 08.02.2016
 * Time: 13:51
 */

/**
 * Class Router
 * basic routing
 * @package core
 */
class Router
{
   /**
    * gets URI from server params (support for IIS basically, anything else is returning URI via REQUEST_URI)
    * @return mixed|string
    * @throws \Exception
    */
   static function getUri()
   {
      if (isset($_SERVER['HTTP_X_REWRITE_URL']))
      { // IIS
         $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
      }
      elseif (isset($_SERVER['REQUEST_URI']))
      {
         $requestUri = $_SERVER['REQUEST_URI'];
         if ($requestUri !== '' && $requestUri[0] !== '/')
         {
            $requestUri = preg_replace('/^(http|https):\/\/[^\/]+/i', '', $requestUri);
         }
      }
      elseif (isset($_SERVER['ORIG_PATH_INFO']))
      { // IIS 5.0 CGI
         $requestUri = $_SERVER['ORIG_PATH_INFO'];
         if (!empty($_SERVER['QUERY_STRING']))
         {
            $requestUri .= '?' . $_SERVER['QUERY_STRING'];
         }
      }
      else
      {
         throw new \Exception('Unable to determine the request URI.');
      }

      return $requestUri;
   }

   /**
    * basic simplistic routing, one predefined controller and many actions, no GET/POST distinguish
    * @throws \Exception
    */
   public static function route()
   {
      $request_uri = self::getUri();
      $route = explode('/', $request_uri);
      //basic routing
      $controllerName = 'Main';
      $actionName = 'index';
      if (!empty($route[1]))
      {
         $actionName = $route[1];
      }
      $actionName = 'action' . $actionName; //prepend action
      $controllerName = "\\controllers\\" . $controllerName . 'Controller'; //add namespace and class name
      $controller = new $controllerName();
      if (method_exists($controller, $actionName))
      {
         $controller->$actionName();
      }
      else
      {
         self::display404();
      }
   }

   /**
    * relocate to 404 page when user mistyped anything
    */
   public static function display404()
   {
      header('HTTP/1.1 404 Not Found');
      header("Status: 404 Not Found");
      header('Location: /404');
   }
}