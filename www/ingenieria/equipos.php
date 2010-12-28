<?php

	require_once("../../server/config.php");	
	require_once("db/DBConnection.php");
	require_once("ingenieria/includes/checkSession.php");
	require_once("ingenieria/includes/static.php");	

?>
<!DOCTYPE html>
<html lang="es"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Punto de venta | Equipos</title>
	
	<link rel="stylesheet" type="text/css" href="./../getResource.php?mod=admin&type=css">
	<script type="text/javascript" src="./../getResource.php?mod=admin&type=js"></script>
  </head>
  <body>
    <div class="g-doc-800" id="g-doc">
        
    <?php include_once("ingenieria/includes/mainMenu.php"); ?>

	<div class="g-section g-tpl-160 main"> 
		<!--
		<div class="g-unit g-first nav"> 
			<div class="ga-container-nav-side"> 
			Menu
			</div> 
		</div>
		-->

	<?php 
		switch( $_GET["action"] )
		{
			case "lista" : require_once("ingenieria/equipos.lista.php"); break;
			case "editar" : require_once("ingenieria/equipos.editar.php"); break;
			default : echo "<h1>Error</h1><p>El sitio ha encontrado un error, porfavor intente de nuevo usando el menu en la parte de arriba.</p>";
		} 
	?>

	</div>
	<?php include_once("ingenieria/includes/footer.php"); ?>
    </div>
  
</body>
</html>
