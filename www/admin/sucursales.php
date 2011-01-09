<?php
	require_once("../../server/config.php");	
	require_once("db/DBConnection.php");
	require_once("admin/includes/checkSession.php");
	require_once("admin/includes/static.php");	

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
        
    <?php include_once("admin/includes/mainMenu.php"); ?>

    <?php
    if(isset($_REQUEST['success'])){

        if($_REQUEST['success'] == 'true'){
            echo "<div class='success'>" . $_REQUEST['reason'] . "</div>";
        }else{
            echo "<div class='failure'>". $_REQUEST['reason'] ."</div>";
        }
    }
    
    ?><div id="ajax_failure" class="failure" style="display: none;"></div><?php 

	   	if(is_file("../../server/admin/sucursales." . $_GET["action"] . ".php")){
    		require_once("admin/sucursales." . $_GET["action"] . ".php");
		}else{
    		echo "<h1>Error</h1><p>El sitio ha encontrado un error, porfavor intente de nuevo usando el menu en la parte de arriba.</p>";
		} 

	?>
     
	<?php include_once("admin/includes/footer.php"); ?>
    </div>
  
</body>
</html>
