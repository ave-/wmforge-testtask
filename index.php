<?php
/**
 * Created by PhpStorm.
 * User: AVE
 * Date: 08.02.2016
 * Time: 13:48
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("app/init.php");

try
{
   core\Router::route();
}
catch (Exception $e)
{
   echo "Exception: " . $e->getMessage();
}

