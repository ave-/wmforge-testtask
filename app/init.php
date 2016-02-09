<?php
/**
 * Created by PhpStorm.
 * User: AVE
 * Date: 08.02.2016
 * Time: 14:36
 */
define("DS", DIRECTORY_SEPARATOR);
ini_set("session.cookie_lifetime","2592000"); //30 days
//openshift session requirements
session_save_path(__DIR__ . DS .'..' . DS . '..' . DS . 'data');
session_set_cookie_params(2592000);
session_start();
define("APP_PATH", __DIR__. DS. '..'. DS. 'app'. DS);
set_include_path(APP_PATH);
spl_autoload_register('my_autoload');
function my_autoload($className)
{
   $filename = APP_PATH . str_replace('\\', '/', $className) . '.php';
   include($filename);
}