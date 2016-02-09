<?php
/**
 * Created by PhpStorm.
 * User: AVE
 * Date: 08.02.2016
 * Time: 16:29
 */

namespace models;


use core\Db;
use core\Request;

class UserModel extends BasicModel
{

   /**
    * return prefixed table name for requests
    * @return string
    */
   public static function tableName()
   {
      return Db::$table_prefix . 'users';
   }

   public static function getMatches($user_data)
   {
      $pdo = Db::get();
      $result = [];
      $sql = 'SELECT * FROM ' . static::tableName() . ' AS tbl';
      $where = '';
      $params = [];
      if ($user_data)
      {
         if ($user_data['login'])
         {
            $where .= 'tbl.login = :login';
            $params[":login"] = $user_data['login'];
         }
         if ($user_data['email'])
         {
            if ($where)
            {
               $where .= ' OR ';
            }
            $where .= 'tbl.email = :email';
            $params[":email"] = $user_data['email'];
         }
      }
      if ($where)
      {
         $sql .= ' WHERE ' . $where;
      }
      try
      {
         $query = $pdo->prepare($sql);
         $query->execute($params);
      } catch (\PDOException $e)
      {
         echo "PDO Exception: ";
         var_dump($e->getMessage());
         echo $sql;
         die();
      }
      $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
      foreach ($rows as $row)
      {
         if ($row['email'] == $user_data['email'])
         {
            $result['email_error'] = 'visible';
         }
         if ($row['login'] == $user_data['login'])
         {
            $result['login_error'] = 'visible';
         }
      }
      return $result;
   }

   /**
    * get user id from session and fill data into model
    * @return UserModel|null
    */
   public static function getDefault()
   {
      $cookie = Request::getAuthCookie();
      if ($cookie)
      {
         $uid = $_SESSION[$cookie];
         if (!$uid)
         {
            return null;
         }
         return UserModel::findById($uid);
      }

      return null;
   }

   public static function save($user_data)
   {
      $dbh = Db::get();
      try
      {
         $query = $dbh->prepare('INSERT INTO ' . static::tableName() . ' (login, password, email, sex, birth_date, other_info, picture) VALUES (:login, :password, :email, :sex, :birth_date, :other_info, :picture)');
      }
      catch (\PDOException $e)
      {
         echo "PDO Exception: ";
         var_dump($e->getMessage());
         die();
      }
      $query->bindValue(':login', $user_data['login']);
      $query->bindValue(':password', static::bcrypt($user_data['password']));
      $query->bindValue(':email', $user_data['email']);
      $query->bindValue(':sex', $user_data['sex']);
      $query->bindValue(':birth_date', $user_data['birth_date_year'] . '-' . $user_data['birth_date_month'] . '-' . $user_data['birth_date_day']);
      $query->bindValue(':other_info', $user_data['other_info']);
      $query->bindValue(':picture', isset($user_data['picture']) ? $user_data['picture'] : null);
      try
      {
         $query->execute();
      } catch (\PDOException $e)
      {
         echo "Error while saving user info: " . $e->getMessage();
      }
      $user_data['password'] = static::bcrypt($user_data['password']);
      return new UserModel($user_data);
   }

   /**
    * verify user data
    * @param $user_data
    * @return array
    */
   public static function validate($user_data)
   {
      $errors = [];
      foreach ($user_data as $index => $value)
      {
         switch ($index)
         {
            case 'login':
               if ($value === '')
               {
                  $errors['login_missing_error'] = 'visible';
                  break;
               }
               break;
            case 'password':
               if ($value === '')
               {
                  $errors['password_missing_error'] = 'visible';
                  break;
               }
               break;
            case 'retype_password':
               if ($user_data['password'] != $value)
               {
                  $errors['retype_password_error'] = 'visible';
               }
               break;
            case 'email':
               if ($value === '')
               {
                  $errors['email_missing_error'] = 'visible';
                  break;
               }
               if (strpos($value, '@') === false)
               {
                  $errors['wrong_email_error'] = 'visible';
                  //Yeah, really, no need to write long regexps or RFC-validators. If we REALLY need email, use validation by sending activation to that address!
                  break;
               }
               break;
            case 'sex':
               if (($value !== '0') && ($value !== '1'))
               {
                  $errors['sex_error'] = 'visible';
               }
               break;
            case 'birth_date_day':
            case 'birth_date_month':
            case 'birth_date_year':
               if (\DateTime::createFromFormat('j-n-Y', $user_data['birth_date_day'] . '-' . $user_data['birth_date_month'] . '-' . $user_data['birth_date_year']) === false)
               {
                  $errors['birth_date_error'] = 'visible';
               }
               break;
         }
      }
      if (!$errors)
      {
         $errors = array_merge($errors, static::getMatches($user_data));
      }
      return $errors;
   }

   public function auth($password)
   {
      if (!static::verify_password($password, $this['password']))
      {
         return false;
      }
      $auth = static::bcrypt($this['login']);
      Request::saveAuthCookie($auth); //save auth cookie
      $_SESSION[$auth] = $this['id']; //save uid into session

      return true;
   }

   /**
    * @param $input
    * @param $existingHash
    * @return bool
    */
   public static function verify_password($input, $existingHash)
   {
      $hash = crypt($input, $existingHash);

      return $hash === $existingHash;
   }


   /**
    * @param $input
    * @param int $rounds
    * @param string $prefix
    * @return bool|string
    * @throws \Exception
    */
   public static function bcrypt($input, $rounds = 12, $prefix = '')
   {
      if (CRYPT_BLOWFISH != 1)
      {
         throw new \Exception("bcrypt not supported in this installation. See http://php.net/crypt");
      }

      $randombytes = '';
      if (function_exists('openssl_random_pseudo_bytes') &&
         (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
      )
      { // OpenSSL slow on Win
         $randombytes = openssl_random_pseudo_bytes(18);
      }

      if ($randombytes === '' && is_readable('/dev/urandom') &&
         ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE
      )
      {
         $randombytes = fread($hRand, 18);
         fclose($hRand);
      }

      if ($randombytes === '')
      {
         $key = uniqid($prefix, true);

         // 12 rounds of HMAC must be reproduced / created verbatim, no known shortcuts.
         // Salsa20 returns more than enough bytes.
         for ($i = 0; $i < 12; $i++)
         {
            $randombytes = hash_hmac('sha512', microtime() . $randombytes, $key, true);
            usleep(10);
         }
      }
      $salt = sprintf('$2a$%02d$%s', $rounds, substr(strtr(base64_encode($randombytes), '+', '.'), 0, 22));

      $hash = crypt($input, $salt);

      if (strlen($hash) > 13)
      {
         return $hash;
      }

      return false;
   }

   public function getData()
   {
      $data = parent::getData();
      $sex_names = ['Male', 'Female'];
      $data['sex'] = $sex_names[$data['sex']];
      return $data;
   }
}