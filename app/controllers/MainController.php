<?php
namespace controllers;

use core\Controller;
use models\UserModel;

/**
 * Created by PhpStorm.
 * User: AVE
 * Date: 08.02.2016
 * Time: 15:14
 */
class MainController extends Controller
{
   /**
    * index action
    *
    * does nothing but redirects
    */
   public function actionIndex()
   {
      $user = UserModel::getDefault();
      if (!$user)
      {
         $this->view->relocate('login');
      }
      else
      {
         $this->view->relocate('profile');
      }
   }

   /**
    * login action
    *
    * tests user login, checks for errors and redirects to profile if all ok
    */
   public function actionLogin()
   {
      $login_data = [
         'login_error' => 'hidden'
      ];
      if ($this->request->isPostRequest())
      {

         /** @var UserModel $user */
         $user = UserModel::findOneByField('login', $this->request->post('login'));
         if ($user)
         {
            if (!$user->auth($this->request->post('password')))
            {
               $login_data['login_error'] = 'visible';
            }
            else
            {
               $this->view->relocate('profile');
            }
         }
      }
      $login_data['login'] = $this->request->post('login', '');
      $login_data['__translator_lang'] = $this->request->post('__translator_lang', 'en');
      echo $this->view->display('login', 'Login', $login_data);
   }

   public function actionProfile()
   {
      $user = UserModel::getDefault();
      if ($user)
      {
         $user_data = $user->getData();
         $user_data['__translator_lang'] =  'en';
         echo $this->view->display('profile', 'Profile', $user_data);
      }
      else
      {
         $this->view->relocate('login');
      }
   }

   public function actionRegister()
   {
      $register_data = [
         'login_exists_error' => 'hidden',
         'login_missing_error' => 'hidden',
         'password_missing_error' => 'hidden',
         'retype_password_error' => 'hidden',
         'email_missing_error' => 'hidden',
         'email_exists_error' => 'hidden',
         'wrong_email_error' => 'hidden',
         'sex_error' => 'hidden',
         'birth_date_error' => 'hidden',
         'picture_extension_error' => 'hidden',
         'picture_generic_error' => 'hidden',
      ];
      $user_data = [];
      if ($this->request->isPostRequest())
      {
         /** @var UserModel $errors */
         $errors = UserModel::validate($this->request->post());
         if ($errors)
         {
            $register_data = array_merge($register_data, $errors);
         }
         else
         {
            $user_data = $this->request->post();
            if ($this->request->getFile('picture'))
            {
               $file = $this->request->getFile('picture');
               if ($file['error'] == UPLOAD_ERR_OK)
               {
                  $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                  if (!in_array($extension, [
                     'jpg',
                     'png',
                     'jpeg',
                     'gif'
                  ])
                  )
                  {
                     $register_data['picture_extension_error'] = 'visible';
                  }
                  else
                  {
                     $filename = md5($file['name'] . time()) . '.' . $extension;
                     $filepath = APP_PATH . '..' . DS . 'images' . DS . $filename;
                     if (!move_uploaded_file($file['tmp_name'], $filepath))
                     {
                        $errors['picture_generic_error'] = 'visible';
                     }
                     else
                     {
                        $user_data['picture'] = $filename;
                     }
                  }
               }
               else
               {
                  $errors['picture_generic_error'] = 'visible';
               }
            }
            if (!$errors)
            {
               $user = UserModel::save($user_data);
               $user->auth($user_data['password']);
               $this->view->relocate('profile');
            }
            else
            {
               $register_data = array_merge($register_data, $errors, $user_data);
            }
         }
      }
      $register_data['login'] = $this->request->post('login','');
      $register_data['email'] = $this->request->post('email','');
      $register_data['sex'] = $this->request->post('sex','1');
      $register_data['birth_date_day'] = $this->request->post('birth_date_day', 1);
      $register_data['birth_date_month'] = $this->request->post('birth_date_month', 1);
      $register_data['birth_date_year'] = $this->request->post('birth_date_year', getdate()['year']);
      $register_data['other_info'] = $this->request->post('other_info', '');

      $register_data['__translator_lang'] = $this->request->post('__translator_lang', 'en');
      $html_data = [
         'birth_date_days' => '',
         'birth_date_years' => '',
      ];
      $html_data['sex'] = '<option value="0" id="tr_Male" ' . ($register_data['sex'] == 0?'selected':'') . '>Male</option>
      <option value="1" id="tr_Female" ' . ($register_data['sex'] == 1?'selected':'') . '>Female</option>';
      foreach (range(1, 31) as $day)
      {
         $html_data['birth_date_days'] .= '<option value="' . $day . '"' .  ($day == $register_data['birth_date_day']?'selected ':'') .'>' . $day . '</option>';
      }
      $html_data['birth_date_months'] = '	<option value="1" id="tr_January" ' . ($register_data['birth_date_month'] == 1?'selected':'') . '>January</option>
	<option value="2" id="tr_February" ' . ($register_data['birth_date_month'] == 2?'selected':'') . '>February</option>
	<option value="2" id="tr_March" ' . ($register_data['birth_date_month'] == 3?'selected':'') . '>March</option>
	<option value="4" id="tr_April" ' . ($register_data['birth_date_month'] == 4?'selected':'') . '>April</option>
	<option value="5" id="tr_May" ' . ($register_data['birth_date_month'] == 5?'selected':'') . '>May</option>
	<option value="6" id="tr_June" ' . ($register_data['birth_date_month'] == 6?'selected':'') . '>June</option>
	<option value="7" id="tr_July" ' . ($register_data['birth_date_month'] == 7?'selected':'') . '>July</option>
	<option value="8" id="tr_August" ' . ($register_data['birth_date_month'] == 8?'selected':'') . '>August</option>
	<option value="9" id="tr_September" ' . ($register_data['birth_date_month'] == 9?'selected':'') . '>September</option>
	<option value="10" id="tr_October" ' . ($register_data['birth_date_month'] == 10?'selected':'') . '>October</option>
	<option value="11" id="tr_November" ' . ($register_data['birth_date_month'] == 11?'selected':'') . '>November</option>
	<option value="12" id="tr_December" ' . ($register_data['birth_date_month'] == 12?'selected':'') . '>December</option>';
      foreach (range($register_data['birth_date_year'] - 100, $register_data['birth_date_year']) as $year)
      {
         $html_data['birth_date_years'] .= '<option value="' . $year . '" ' . ($year == $register_data['birth_date_year'] ? 'selected ' : '') . '>' . $year . '</option>';
      }
      echo $this->view->display('register', 'Register', $register_data, $html_data);
   }

   public function action404()
   {
      echo $this->view->display('404', '404 Page not found!');
   }

   public function actionLogout()
   {
      $this->request->clearAuthCookie();
      $this->view->relocate('');
   }
}