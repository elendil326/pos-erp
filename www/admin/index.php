<?php

	require_once("../../server/config.php");
	include_once("admin/includes/checkSession.php");

?>
<!DOCTYPE html>
<html lang="es"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Punto de venta | Administracion</title>
	


	<link rel="stylesheet" type="text/css" href="./../getResource.php?mod=admin&type=css">
	<script type="text/javascript" src="./../getResource.php?mod=admin&type=js"></script>


  </head>
  <body>
    <div class="g-doc-800" id="g-doc">
        
	  <?php include_once("admin/includes/mainMenu.php"); ?>

        <table>
        <tr>
            <td>
            <img src='../media/line_chart.png'>        
            </td>
        <td valign='top'>
      <h1>
       Centro de Administracion
      </h1>
            <p>
            Este punto de venta es una solución de analítica web para empresas que proporciona información
            muy valiosa sobre el tráfico del sitio web y la eficacia del plan de marketing. 
            </p>
        </td>
        </tr>
        </table>


	<?php include_once("admin/includes/footer.php"); ?>
    </div>
  
</body>
</html>
