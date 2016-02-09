<?php
/**
 * Created by PhpStorm.
 * User: AVE
 * Date: 09.02.2016
 * Time: 15:02
 */

namespace models;


use core\Db;

class BasicModel  implements \ArrayAccess
{
   private $model_data;

   public function __construct($user_data)
   {
      $this->model_data = $user_data;
   }

   public static function tableName()
   {
      return '';
   }

   public static function findOneByField($fieldName = 'id', $fieldValue)
   {
      $pdo = Db::get();
      try
      {
         $query = $pdo->prepare('SELECT * FROM ' . static::tableName() . ' AS tbl WHERE tbl.' . $fieldName . ' = :fieldvalue');
      }
      catch (\PDOException $e)
      {
         echo "PDO Exception: ";
         var_dump($e->getMessage());
         die();
      }

      $query->bindValue(':fieldvalue', $fieldValue);
      $query->execute();
      $user_row = $query->fetch(\PDO::FETCH_ASSOC);
      if (!$user_row)
      {
         return null; //no entries with this restrictions
      }
      foreach ($user_row as $index => $value)
      {
         $user_row[$index] = htmlentities($value);
      }
      return new static($user_row);
   }

   /**
    * @param $id
    * @return UserModel|null
    */
   public static function findById($id)
   {
      return static::findOneByField('id', $id);
   }


   /**
    * Whether a offset exists
    * @link http://php.net/manual/en/arrayaccess.offsetexists.php
    * @param mixed $offset <p>
    * An offset to check for.
    * </p>
    * @return boolean true on success or false on failure.
    * </p>
    * <p>
    * The return value will be casted to boolean if non-boolean was returned.
    * @since 5.0.0
    */
   public function offsetExists($offset)
   {
      return array_key_exists($offset, $this->model_data);
   }

   /**
    * Offset to retrieve
    * @link http://php.net/manual/en/arrayaccess.offsetget.php
    * @param mixed $offset <p>
    * The offset to retrieve.
    * </p>
    * @return mixed Can return all value types.
    * @since 5.0.0
    */
   public function offsetGet($offset)
   {
      return array_key_exists($offset, $this->model_data) ? $this->model_data[$offset] : null;
   }

   /**
    * Offset to set
    * @link http://php.net/manual/en/arrayaccess.offsetset.php
    * @param mixed $offset <p>
    * The offset to assign the value to.
    * </p>
    * @param mixed $value <p>
    * The value to set.
    * </p>
    * @return void
    * @since 5.0.0
    */
   public function offsetSet($offset, $value)
   {
      if (is_null($offset))
      {
         $this->model_data[] = $value;
      }
      else
      {
         $this->model_data[$offset] = $value;
      }
   }

   /**
    * Offset to unset
    * @link http://php.net/manual/en/arrayaccess.offsetunset.php
    * @param mixed $offset <p>
    * The offset to unset.
    * </p>
    * @return void
    * @since 5.0.0
    */
   public function offsetUnset($offset)
   {
      unset($this->model_data[$offset]);
   }

   public function getData()
   {
      return $this->model_data;
   }

}