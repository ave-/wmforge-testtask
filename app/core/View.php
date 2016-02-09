<?php
/**
 * Created by PhpStorm.
 * User: AVE
 * Date: 08.02.2016
 * Time: 15:27
 */

namespace core;


class View
{
   private $translated_langs = [
      'ru',
      'en',
   ];
   private function prepare_translator($data)
   {
      foreach ($this->translated_langs as $lang)
      {
         $data['__' . $lang . '_lang_active'] = '';
      }
      if (array_key_exists('__translator_lang', $data))
      {
         $lang = $data['__translator_lang'];
         $data['__' . $lang . '_lang_active'] = 'translated';
      }
      else
      {
         $data['__en_lang_active'] = 'translated'; //default
      }
      return $data;
   }
   /**
    * basic templating system
    *
    * @param $viewName
    * @param $title
    * @param array $data
    * @param string $templateName
    * @return mixed|string
    */
   public function display($viewName, $title, $data = [], $html_data = [] ,$templateName = 'basic_template')
   {
      $path_to_template = APP_PATH . DS . 'views' . DS . $templateName . '.tpl';
      $path_to_view = APP_PATH . DS . 'views' . DS . $viewName . '.tpl';
      if (!file_exists($path_to_template)) {
         return "Error loading template file ($templateName).";
      }
      $output = file_get_contents($path_to_template);
      $view = file_get_contents($path_to_view);

      $data = $this->prepare_translator($data);

      foreach ($data as $key => $value)
      {
         $view = str_replace('[@' . $key . ']', htmlentities($value), $view);// safety
      }
      foreach ($html_data as $key => $value)
      {
         $view = str_replace('{{' . $key . '}}', $value, $view);// can be unsafe because we control it

      }
      $output = str_replace('[@title]', $title , $output);//add custom title
      $output = str_replace('[@viewName]', $viewName , $output);//add custom css and js
      $output = str_replace('[@content]', $view , $output);

      return $output;
   }

   /**
    * simple redirect
    * @param $uri
    */
   public function relocate($uri)
   {
      header('HTTP/1.1 301 Moved Temporarily');
      header("Status: 301 Moved Temporarily");
      header('Location: /' . $uri);
      die();
   }
}