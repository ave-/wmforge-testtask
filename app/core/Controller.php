<?php
/**
 * Created by PhpStorm.
 * User: AVE
 * Date: 08.02.2016
 * Time: 15:27
 */

namespace core;


class Controller
{
   /** @var View  */
   public $view;
   /** @var Request  */
   public $request;

   /**
    * Controller constructor.
    */
   public function __construct()
   {
      $this->view = new View();
      $this->request = new Request();
   }

}