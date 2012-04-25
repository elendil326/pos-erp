<?php
	define("I_AM_SUCURSAL", true);
	
	require_once( "../../../../../server/bootstrap.php" );

	Logger::log(">> sucursal(v1)sucursal.php <<");
	
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
	    <script type="text/javascript" src="http://api.caffeina.mx/sencha-latest/sencha-touch-debug.js"></script>
	
			<link rel="stylesheet" type="text/css" href="css/shared/Basic.css">
			<link rel="stylesheet" type="text/css" href="css/shared/Keyboard.css">
			<link rel="stylesheet" type="text/css" href="css/shared/Tabla.css">

			<link rel="stylesheet" type="text/css" href="css/sucursal/Autorizaciones.css">
			<link rel="stylesheet" type="text/css" href="css/sucursal/Mosaico.css">
			<link rel="stylesheet" type="text/css" href="css/sucursal/Mostrador.css">
			<link rel="stylesheet" type="text/css" href="css/sucursal/sink.css">

			<script type="text/javascript" src="js/sucursal/pre/DAO.js?<?php echo rand(); ?>"></script>
			<script type="text/javascript" src="js/sucursal/pre/index.js?<?php echo rand(); ?>"></script>
			<script type="text/javascript" src="js/sucursal/pre/Mosaico.js?<?php echo rand(); ?>"></script>
			<script type="text/javascript" src="js/sucursal/pre/POS.js?<?php echo rand(); ?>"></script>

			<script type="text/javascript" src="js/sucursal/apps/Aplicacion.Clientes.js?<?php echo rand(); ?>"></script>
			<script type="text/javascript" src="js/sucursal/apps/Aplicacion.Inventario.js?<?php echo rand(); ?>"></script>
			<script type="text/javascript" src="js/sucursal/apps/Aplicacion.Mostrador.js?<?php echo rand(); ?>"></script>
			<script type="text/javascript" src="js/sucursal/apps/Aplicacion.Salir.js?<?php echo rand(); ?>"></script>
			<script type="text/javascript" src="js/sucursal/apps/AplicacionComprasMostrador.js?<?php echo rand(); ?>"></script>
                        <script type="text/javascript" src="js/sucursal/apps/Aplicacion.Servicios.js?<?php echo rand(); ?>"></script>
<!--
			<script type="text/javascript" src="js/sucursal/apps/gerente/Aplicacion.Efectivo.js"></script>
			<script type="text/javascript" src="js/sucursal/apps/gerente/Aplicacion.Operaciones.js"></script>
			<script type="text/javascript" src="js/sucursal/apps/gerente/Aplicacion.Personal.js"></script>
			<script type="text/javascript" src="js/sucursal/apps/gerente/Aplicacion.Proveedores.js"></script>

-->		
			<script type="text/javascript" src="js/sucursal/post/structure.js?<?php echo rand(); ?>"></script>

			<script type="text/javascript" src="js/shared/hash.js"></script>
			<script type="text/javascript" src="js/shared/POS.AjaxToClient.js"></script>
			<script type="text/javascript" src="js/shared/POS.Keyboard.js"></script>
</head>
<body></body>
</html>
