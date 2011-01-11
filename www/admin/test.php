<?php
	require_once("../../server/config.php");	
	require_once("db/DBConnection.php");
	require_once("admin/includes/checkSession.php");
	require_once("admin/includes/static.php");	

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" class=" wf-active">
	
	<!--<SPAN class="wf-font-watcher" style="position:absolute;left:-999px;font-size:200px;font-family:NONE,NONE">Mm</SPAN>
	<SPAN class="wf-font-watcher" style="position:absolute;left:-999px;font-size:200px;font-family:&quot;ff-din-web-1&quot;,&quot;ff-din-web-2&quot;,sans-serif,NONE">Mm</SPAN>-->
	
<HEAD>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <TITLE>Caffeina</TITLE>
	<LINK rel="stylesheet" type="text/css" href="./inc/marketingv4.css">
	<STYLE type="text/css">.tk-ff-din-web{font-family:"ff-din-web-1","ff-din-web-2",sans-serif;}</STYLE>
	<SCRIPT src="./inc/jquery.min.js" type="text/javascript"></SCRIPT>
	<STYLE type="text/css">.olark-key,#hbl_code,#olark-data{display: none !important;}</STYLE>
</HEAD>


<BODY class="sub">
  
  <DIV id="wrapper">
    
    <DIV id="header" style="background: #fff url(inc/35598623_e726362b9f_b.jpg) no-repeat 50% -300px  ">
      
      <DIV id="top-bar">

        <?php include_once("inc/header.php"); ?>
        
      </DIV> <!-- top-bar -->

      <DIV id="header-main">
		<h1 id="MAIN_TITLE"></h1> 
      	<DIV id="hero">
		
		</DIV>
      </DIV> <!-- header-main -->
    </DIV> <!-- header -->

    
    <DIV id="content">
	
	<?php
	 	if(isset($_GET["action"]) && is_file("../../server/admin/clientes." . $_GET["action"] . ".php")){
    		require_once("admin/clientes." . $_GET["action"] . ".php");
		}else{
    		echo "<h1>Error</h1><p>El sitio ha encontrado un error, porfavor intente de nuevo usando el menu en la parte de arriba.</p>";
    		echo '<script>document.getElementById("MAIN_TITLE").innerHTML = "Error";</script>';
		}
	?> 
	
    <?php include_once("inc/footer.php"); ?>	
    </DIV> <!-- content -->
    
    
  </DIV> <!-- wrapper -->

</BODY></HTML>
