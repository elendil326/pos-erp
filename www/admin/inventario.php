<?php

	require_once("../../server/config.php");	
	require_once("db/DBConnection.php");
	require_once("admin/includes/checkSession.php");
	require_once("admin/includes/static.php");	

?>
<!DOCTYPE html>
<html lang="es"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Punto de venta | Inventario</title>
	
	<link rel="stylesheet" type="text/css" href="./../getResource.php?mod=admin&type=css">
	<script type="text/javascript" src="./../getResource.php?mod=admin&type=js"></script>

  </head>
  <body>
    <div class="g-doc-800" id="g-doc">
        
    <?php include_once("admin/includes/mainMenu.php"); ?>
	
	<div class="g-section g-tpl-160 main">
    <?php
    if(isset($_REQUEST['success'])){

        if($_REQUEST['success'] == 'true'){
            echo "<div class='success'>" . $_REQUEST['reason'] . "</div>";
        }else{
            echo "<div class='failure'>". $_REQUEST['reason'] ."</div>";
        }
    }
    ?><div id="ajax_failure" class="failure" style="display: none;"></div><?php 
    
		switch( $_GET["action"] )
		{
			case "lista" : require_once("admin/inventario.lista.php"); break;
			case "nuevo" : require_once("admin/inventario.nuevo.php"); break;
			case "surtir" : require_once("admin/inventario.surtir.php"); break;			
			case "detalle" : require_once("admin/inventario.detalle.php"); break;
			case "transit" : require_once("admin/inventario.transit.php"); break;
			case "editar" : require_once("admin/inventario.editar.php"); break;
			default : echo "<h1>Error</h1><p>El sitio ha encontrado un error, porfavor intente de nuevo usando el menu en la parte de arriba.</p>";
		} 
	?>
    </div>
	<?php include_once("admin/includes/footer.php"); ?>
    </div>
  
</body>
</html>
