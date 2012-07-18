<?php
  
  define("WHO_AM_I", "JEDI");

  require_once("../../server/bootstrap.php");

  require_once("controller/login.controller.php");
  require_once("controller/gui.controller.php");


  $session_mananger = new JediLoginController();
  $jedi_gui = new JediGuiController();



  if($session_mananger->checkCurrentSession() === false)
  {
      //invalid session
      $jedi_gui->printLoginWindow();
      



  }else{
      //session is cool
      



  }

  