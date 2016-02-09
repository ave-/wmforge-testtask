<?php
/**
 * Created by PhpStorm.
 * User: AVE
 * Date: 08.02.2016
 * Time: 16:47
 */

namespace core;


/**
 * Class Db
 *
 * Initializes db connection and returns PDO db handler via Db::get()
 *
 * @package core
 */
class Db
{
   /** @var  \PDO  - db handler*/
   private static $dbh;
   public static $table_prefix;

   /**
    * Get PDO db handler
    * @return \PDO
    */
   public static function get()
   {
      if (!self::$dbh)
      {
         self::init();
      }
      return self::$dbh;
   }

   /**
    * init PDO db handler
    */
   public static function init()
   {
      $config = include(APP_PATH . '..' . DS . 'config' . DS . 'db.php');
      self::$table_prefix = $config['table_prefix'];
      $connection_string = 'mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['db'];
      $options = array(
         \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
      );
      try
      {
         self::$dbh = new \PDO($connection_string, $config['user'], $config['password'], $options);
         self::$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      }
      catch (\PDOException $e)
      {
         echo "Connection failed: " . $e->getMessage();
         die();
      }
   }
}