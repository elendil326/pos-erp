<h1>Detalles del producto</h1>



<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){
      $("input, select").uniform();
    });
</script>
<?php


    require_once('model/inventario.dao.php');
    require_once('model/detalle_venta.dao.php');
    require_once('model/actualizacion_de_precio.dao.php');
    $producto = InventarioDAO::getByPK($_REQUEST['id']);


    //obtener todas las fluctuaciones de precio
    $a = new ActualizacionDePrecio();
    $a->setIdProducto( $_REQUEST['id'] );
    $fluctuaciones = ActualizacionDePrecioDAO::search( $a, 'fecha', 'desc' );

    

?><h2>Detalles</h2>

	
<form id="detalles">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>ID Producto</td><td><?php echo $producto->getIdProducto();?></td></tr>
	<tr><td>Descripcion</td><td><?php echo $producto->getDescripcion();?></td></tr>
	<tr><td>Costo</td><td><?php echo moneyFormat($producto->getPrecioIntersucursal()); ?></td></tr>
	<tr><td>Medida</td><td><?php echo $producto->getMedida();?></td></tr>
	<tr><td><input type="button" onclick="window.location='inventario.php?action=editar&id=<?php echo $producto->getIdProducto();?>' " value="Editar" id="" /></td></tr>
</table>
</form>


<h2>Fluctuaciones de precio</h2><?php


    $header = array( 
	    "id_actualizacion" => "ID",
	    "fecha"=> "Descripcion",
	    "id_producto"=> "Producto",
	    "id_usuario"=> "Usuario",
	    "precio_venta"=> "Precio Venta",
	    "precio_compra"=> "Precio Compra",
	    "precio_intersucursal"=> "Precio Intersusucursal" );

    $tabla = new Tabla( $header, $fluctuaciones );
    $tabla->addColRender( "precio_venta", "moneyFormat" ); 
    $tabla->addColRender( "precio_compra", "moneyFormat" ); 
    $tabla->addColRender( "precio_intersucursal", "moneyFormat" ); 
    $tabla->render();

?>


	

<h2>Ultimas 100 ventas de este producto</h2><?php

    $dv = new DetalleVenta();
    $dv->setIdProducto( $_REQUEST['id'] );
    $res = DetalleVentaDAO::search( $dv, 'id_venta', 'desc' );
    
    $header = array( 
	    "id_venta" => "Venta",
	    "cantidad"=> "Cantidad",
	    "precio"=> "Precio unitario" );

    $tabla = new Tabla( $header, $res );
    $tabla->addColRender( "precio", "moneyFormat" ); 
    $tabla->render();    

?>





