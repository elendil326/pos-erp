<?php

	define("I_AM_JEDI", true);
	
	require_once("../../server/jedi/check_session.php");
	
	require_once("../../server/jedi/bootstrap.php");
	

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >


<head>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>POS | Inventario</title>
	<script src="http://api.caffeina.mx/jquery/jquery-1.4.2.min.js" type="text/javascript"></script>

	<script>
		$.noConflict();
	</script>

	<script type="text/javascript" charset="utf-8" src="http://api.caffeina.mx/prototype/prototype.js"></script>		

	<script src="http://api.caffeina.mx/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
	<link rel="stylesheet" href="http://api.caffeina.mx/uniform/css/uniform.default.css" type="text/css" media="screen">
	<script type="text/javascript" charset="utf-8">jQuery(function(){jQuery("input, select").uniform();});</script>

	<link rel="stylesheet" type="text/css" href="./../getResource.php?mod=admin&type=css">

	<link href="http://api.caffeina.mx/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
	<script src="http://api.caffeina.mx/facebox/facebox.js" type="text/javascript"></script>
</head>


<body class="sub">
  <div id="wrapper">

    <div id="header" class="matrix" >

      <div id="top-bar">

        <?php include_once("jedi/mainMenu.php"); ?>

      </div> 
      <!-- /top-bar -->

      <div id="header-main">
		<h1 id="MAIN_TITLE">Jedi Master</h1> 
      </div>
    </div>

    <div id="content" align=center>


        <h1>Welcome Jedi</h1>



    
    </div> 
    <!-- /content -->


  </div> 
  <!-- /wrapper -->

</body></HTML>
