<?php
require_once("../../server/bootstrap.php");	
require_once("ingenieria/includes/checkSession.php");
require_once("admin/includes/static.php");

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >

	
<head>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>POS | Clientes</title>
	<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript"></script>

	<script>
		$.noConflict();
	</script>
	
	<script type="text/javascript" charset="utf-8" src="../frameworks/prototype/prototype.js"></script>		

	<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
	<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">
	<script type="text/javascript" charset="utf-8">jQuery(function(){jQuery("input, select").uniform();});</script>
		
	<link rel="stylesheet" type="text/css" href="./../getResource.php?mod=admin&type=css">
	<script type="text/javascript" src="./../getResource.php?mod=admin&type=js"></script>

</head>


<body class="sub">
  <div id="wrapper">

    <div id="header" class="control" >
      
      <div id="top-bar">
        
        <?php include_once("ingenieria/includes/mainMenu.php"); ?>
            
      </div> 
      <!-- /top-bar -->

      <div id="header-main">
		<h1 id="MAIN_TITLE">Centro de Ingenieria</h1> 
      </div>
    </div>
    
    <div id="content">
	
	
      <h2>
       Estado del servidor
      </h2>
		
	<table >
		<tr>
			<th></th>
			<th>Descripcion&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>Valor actual</th>
		</tr>
		<tr>
			<td><?php echo "<img src='../media/icons/" . (version_compare(PHP_VERSION, '5.2.0') >= 0 ? "s_success.png" : "close_16.png") . "'>" ; ?></td>
			<td>Version PHP</td>
			<td><?php echo phpversion(); ?></td>
		</tr>
	
		<tr>
			<td><?php echo "<img src='../media/icons/" . (is_writable( POS_LOG_TO_FILE_FILENAME ) ? "s_success.png" : "close_16.png") . "'>" ; ?></td>
			<td>Archivo de log</td>
			<td><?php echo POS_LOG_TO_FILE_FILENAME ; ?></td>
		</tr>	
	</table>
	
	
	<h2>Cliente de POS</h2>
	<table>
		<tr>
			<td></td>
			<td><a href="download.php?file=pos_client">Descargar</a></td>
			<td></td>
		</tr>	
	</table>
	
	
    <?php include_once("admin/includes/footerInge.php"); ?>
    </div> 
    <!-- /content -->
    
    
  </div> 
  <!-- /wrapper -->

</body></HTML>
