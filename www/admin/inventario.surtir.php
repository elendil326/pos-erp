<h1>Surtir una sucursal</h1>



<div class="content"> 
<h2>Seleccione la sucursal que desea surtir</h2><?php

/*
 * Nuevo Cliente
 */ 

	require_once("model/sucursal.dao.php");
	require_once("controller/clientes.controller.php");
	require_once("controller/sucursales.controller.php");
	require_once("controller/inventario.controller.php");	

?>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){
      $("input, select").uniform();
    });



	function seleccionarSucursal(){
		console.log($('#sucursal').val())
		$("#actual" + $('#sucursal').val()).slideDown();
		$("#InvMaestro").slideDown();		
		
	}

</script>




<form id="newClient">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Sucursal</td>
		<td>
			<select id="sucursal"> 
			<?php
			
				$sucursales = SucursalDAO::getAll();
				foreach( $sucursales as $suc ){
					echo "<option value='" . $suc->getIdSucursal() . "' >" .  $suc->getDescripcion()  . "</option>";
				}
			
			?>
	
	        </select>
		</td>
	</tr>
	<tr><td></td><td><input type="button" onClick="seleccionarSucursal()" value="Seleccionar"/> </td></tr>
</table>
</form>









<?php

//get sucursales
$sucursales = listarSucursales();

foreach( $sucursales as $sucursal ){
	
	print ("<div id='actual" . $sucursal["id_sucursal"] . "' style='display: none'>");
	print ("<h2>Inventario actual</h2><h3>" . $sucursal["descripcion"] . "</h3>");
	
	//obtener los clientes del controller de clientes
	$inventario = listarInventario( $sucursal["id_sucursal"] );

	//render the table
	$header = array( 
		"productoID" => "ID",
		"descripcion"=> "Descripcion",
		"precioVenta"=> "Precio Venta",
		"existenciasMinimas"=> "Minimas",
		"existencias"=> "Existencias",
		"medida"=> "Tipo",
		"precioIntersucursal"=> "Precio Intersucursal" );
		

	
	$tabla = new Tabla( $header, $inventario );
	$tabla->addColRender( "precioVenta", "moneyFormat" ); 
	$tabla->addColRender( "precioIntersucursal", "moneyFormat" ); 
	$tabla->render();
	printf("</div>");
}

?>







<div id="InvMaestro" style="display: none;">
<h2>Productos disponibles</h2><h3>Seleccione los productos que desee surtir a esta sucursal.</h3><?php

	//obtener los clientes del controller de clientes
	$inventario = listarInventarioMaestro( );

	//render the table
	$header = array( 
		"id_producto" => "ID",
		"descripcion"=> "Descripcion",
		"precio_intersucursal"=> "Precio Intersucursal",
		"costo"=> "Costo",
		"medida"=> "Medida");
		

	
	$tabla = new Tabla( $header, $inventario );
	$tabla->addColRender( "precioVenta", "moneyFormat" ); 
	$tabla->addColRender( "precioIntersucursal", "moneyFormat" ); 
	$tabla->render();

?> 
</div>