<?php

	require_once("../../server/bootstrap.php");	
	require_once("admin/includes/checkSession.php");
	require_once("admin/includes/static.php");	
	require_once('controller/autorizaciones.controller.php');
	require_once("model/ventas.dao.php");

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >

	
<head>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>POS | Inventario</title>
	<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript"></script>

	<script>
		$.noConflict();
	</script>
	
	<script type="text/javascript" charset="utf-8" src="../frameworks/prototype/prototype.js"></script>		

	<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
	<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">
	<script type="text/javascript" charset="utf-8">jQuery(function(){jQuery("input, select").uniform();});</script>
		
	<link rel="stylesheet" type="text/css" href="./../getResource.php?mod=admin&type=css">
	<script type="text/javascript" src="./../getResource.php?mod=admin&type=js"></script>

	<link href="../frameworks/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
	<script src="../frameworks/facebox/facebox.js" type="text/javascript"></script>
</head>


<body class="sub">
  <div id="wrapper">

    <div id="header" class="clientes" <?php if( POS_STYLE_SUCURSALES_BANNER ) echo " style='background-image: url(". POS_STYLE_SUCURSALES_BANNER .")'"; ?> >
      
      <div id="top-bar">
        
        <?php include_once("admin/includes/mainMenu.php"); ?>
            
      </div> 
      <!-- /top-bar -->

      <div id="header-main">
		<h1 id="MAIN_TITLE">Centro de distribuci&oacute;n</h1> 
      </div>
    </div>
    
    <div id="content" >

			<h2>Actividades</h2>
        <div  aling=center>

<!--
			<table border=0 style='width: 100%; font-size: 14px; cursor: pointer;'>
				<tr>
					<td class='prod rounded'   >
						<a href="autorizaciones.php?action=historial">Autorizaciones pendientes ( <?php echo sizeof(autorizacionesPendientes()); ?> )</a>
					</td>
					<td class='prod rounded'   >
						<a href="ventas.php?action=lista">
						Ventas de hoy ( <?php 
									$f = VentasDAO::contarVentasPorDia(null, 1);

									echo $f[0]["value"];
								?> )
						</a>
					</td>
					<td class='prod rounded'    >
						Surtir sucursal
					</td>

				</tr>
			</table>
-->
		</div>

	
    <?php include_once("admin/includes/footer.php"); ?>
    </div> 
    <!-- /content -->
    
    
  </div> 
  <!-- /wrapper -->

</body></HTML>
