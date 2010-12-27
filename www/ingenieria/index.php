<?php

	include_once("includes/checkSession.php");

?>
<!DOCTYPE html>
<html lang="es"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Punto de venta | Centro de Ingenieria</title>
	

	<link rel="stylesheet" type="text/css" href="./../getResource.php?mod=admin&type=css">
	<script type="text/javascript" src="./../getResource.php?mod=admin&type=js"></script>

  </head>
  <body>
    <div class="g-doc-800" id="g-doc">
        
	  <?php include_once("includes/mainMenu.php"); ?>

      <h1>
       Estado del servidor
      </h1>
		
	<table>
		<tr>
			<th></th>
			<th>Descripcion</th>
			<th>Valor actual</th>
		</tr>
		<tr>
			<td><?php echo "<img src='../media/icons/" . (version_compare(PHP_VERSION, '5.3.0') >= 0 ? "s_success.png" : "close_16.png") . "'>" ; ?></td>
			<td>Version PHP</td>
			<td><?php echo phpversion(); ?></td>
		</tr>
		
		<tr>
			<td><?php echo "<img src='../media/icons/" . (function_exists ( 'date_diff' ) ? "s_success.png" : "close_16.png") . "'>" ; ?></td>
			<td>Funciones de DateTime</td>
			<td></td>
		</tr>		
		<tr>
			<td><?php echo "<img src='../media/icons/" . (is_writable( _POS_LOG_TO_FILE_FILENAME ) ? "s_success.png" : "close_16.png") . "'>" ; ?></td>
			<td>Archivo de log</td>
			<td><?php echo _POS_LOG_TO_FILE_FILENAME ; ?></td>
		</tr>	
	</table>


	<?php include_once("includes/footer.php"); ?>
    </div>
  
</body>
</html>
