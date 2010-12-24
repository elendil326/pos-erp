<?php

	require_once("includes/checkSession.php");
	require_once("includes/static.php");	
	require_once("../../server/config.php");	
	require_once("db/DBConnection.php");
?>
<!DOCTYPE html>
<html lang="es"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Punto de venta | Clientes</title>
	
	<link rel="stylesheet" type="text/css" href="./../getResource.php?mod=admin&type=css">
	<script type="text/javascript" src="./../getResource.php?mod=admin&type=js"></script>

  </head>
  <body>
    <div class="g-doc-800" id="g-doc">
        
    <?php include_once("includes/mainMenu.php"); ?>
		
	<?php 
		switch( $_GET["action"] )
		{

			case "lista" : 
				require_once("sucursales.lista.php"); 
			break;
			case "detalles" : 
				require_once("sucursales.detalles.php");
			break;
			case "abrir" : 
				require_once("sucursales.abrir.php");
			break;

			case "editar" : require_once("sucursales.editar.php"); break;
			case "cerrar" : require_once("sucursales.cerrar.php"); break;
			default : echo "<h1>Error</h1><p>El sitio ha encontrado un error, porfavor intente de nuevo usando el menu en la parte de arriba.</p>";
		} 
	?>
     
	<?php include_once("includes/footer.php"); ?>
    </div>
  
</body>
</html>
