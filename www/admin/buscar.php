<?php

	require_once("includes/checkSession.php");
	require_once("includes/static.php");	
	require_once("../../server/config.php");	
	require_once("db/DBConnection.php");
?>
<!DOCTYPE html>
<html lang="es"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Punto de venta | Buscar</title>
	
	<link rel="stylesheet" type="text/css" href="./../getResource.php?mod=admin&type=css">
	<script type="text/javascript" src="./../getResource.php?mod=admin&type=js"></script>

  </head>
  <body>
    <div class="g-doc-800" id="g-doc">
        
    <?php include_once("includes/mainMenu.php"); ?>

	<div class="g-section g-tpl-160 main"> 
    <?php
    if(isset($_REQUEST['success'])){

        if($_REQUEST['success'] == 'true'){
            echo "<div class='success'>" . $_REQUEST['reason'] . "</div>";
        }else{
            echo "<div class='failure'>". $_REQUEST['reason'] ."</div>";
        }
    }
    ?>

    <h1>Busqueda</h1>
    <h2>Resultados de la busqueda para <b><?php echo $_REQUEST['q']; ?></b></h2>
    <?php

        $query = $_REQUEST['q'];

        //buscar en la lista de clientes
        $cliente = new Cliente();


        //buscar en la lista de personal

        
        echo $query;
    
        
    ?>

	</div>
	<?php include_once("includes/footer.php"); ?>
    </div>
  
</body>
</html>
