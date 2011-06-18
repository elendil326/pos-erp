<?php
	define("I_AM_SUCURSAL", true);
	
	require_once( "../../server/bootstrap.php" );

	Logger::log(">> sucursal/sucursal.php <<");
	
?><!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
    <title>POS</title>
		<script>
		var DEBUG; 
		if(document.location.search=="?debug")
		{
		    DEBUG=true;
			console.log("Debug mode !");
			
		}else{
			DEBUG = false;
		}
		</script>

	    <link rel="stylesheet" href="http://api.caffeina.mx/sencha-latest/resources/css/sencha-touch.css" type="text/css">
	    <script type="text/javascript" src="http://api.caffeina.mx/sencha-latest/sencha-touch.js"></script>
	
		<link rel="stylesheet" type="text/css" href="../getResource.php?mod=sucursal&type=css">
		<link rel="stylesheet" type="text/css" href="../getResource.php?mod=shared&type=css">
		
		<script type="text/javascript" src="../getResource.php?mod=sucursal&type=js"></script>
		<script type="text/javascript" src="../getResource.php?mod=shared&type=js"></script>


</head>
<body></body>
</html>
