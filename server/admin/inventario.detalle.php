<?php

    require_once('model/inventario.dao.php');
    require_once('model/detalle_venta.dao.php');
    require_once('model/actualizacion_de_precio.dao.php');
    require_once('model/compra_proveedor.dao.php');
    
    $producto = InventarioDAO::getByPK($_REQUEST['producto']);
    $compra = CompraProveedorDAO::getByPK($_REQUEST['compra']);    

	if( $producto == null || $compra == null){
		echo "<h1>Error</h1>Estos datos no existen.";
		return ;
	}

    //obtener todas las fluctuaciones de precio
    $a = new ActualizacionDePrecio();
    $a->setIdProducto( $_REQUEST['producto'] );
    
    $fluctuaciones = ActualizacionDePrecioDAO::search( $a, 'fecha', 'desc' );
	$ultimaActualizacion = $fluctuaciones[0];
	
?>


<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">


<h1><?php echo $producto->getDescripcion();  ?></h1>



<script type="text/javascript" charset="utf-8">
	$(function(){  $("input, select").uniform();  });
</script>

<h2>Detalles</h2>

	
<form id="detalles">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>ID Producto</td><td><?php echo $producto->getIdProducto();?></td></tr>
	<tr><td>Descripcion</td><td><?php echo $producto->getDescripcion();?></td></tr>
	<tr><td>Precio interusucursal</td><td><?php echo moneyFormat($ultimaActualizacion->getPrecioIntersucursal()); ?></td></tr>
	<tr><td>Escala</td><td><?php echo $producto->getEscala();?></td></tr>
	<tr><td><input type="button" onclick="window.location='inventario.php?action=editar&id=<?php echo $producto->getIdProducto();?>' " value="Editar" id="" /></td></tr>
</table>
</form>


<h2>Fluctuaciones de precio</h2><?php


    $header = array( 
//	    "id_actualizacion" => "ID",
	    "fecha"=> "Fecha",
//	    "id_producto"=> "Producto",
	    "id_usuario"=> "Usuario",
	    "precio_venta"=> "Precio Venta",
	    "precio_intersucursal"=> "Precio Intersusucursal" );

    $tabla = new Tabla( $header, $fluctuaciones );
    $tabla->addColRender( "precio_venta", "moneyFormat" ); 
    $tabla->addColRender( "precio_intersucursal", "moneyFormat" ); 
    $tabla->render();

?>






