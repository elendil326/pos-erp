<?php

    require_once('model/inventario.dao.php');
    require_once('model/detalle_venta.dao.php');
    require_once('model/actualizacion_de_precio.dao.php');
    require_once('model/compra_proveedor.dao.php');
	require_once('model/usuario.dao.php');    
    
    
    if( !isset($_REQUEST['id'])  ){
    	echo "<h1>Error</h1>Estos datos no existen.";
    	return;
    }
    
        
    $producto 	= InventarioDAO::getByPK		($_REQUEST['id']);

	if( $producto == null ){
		echo "<h1>Error</h1>Estos datos no existen.";
		return ;
	}

    //obtener todas las fluctuaciones de precio
    $a = new ActualizacionDePrecio();
    $a->setIdProducto( $_REQUEST['id'] );
    
    $fluctuaciones = ActualizacionDePrecioDAO::search( $a, 'fecha', 'desc' );
	$ultimaActualizacion = $fluctuaciones[0];
	
?>

<script>
document.getElementById("MAIN_TITLE").innerHTML = "<?php echo $producto->getDescripcion();  ?>";
</script>



<h2>Detalles</h2>

	
<form id="detalles">
<table border="0" cellspacing="1" cellpadding="1">
	<tr><td>ID Producto</td><td><?php echo $producto->getIdProducto();?></td></tr>
	<tr><td>Descripcion</td><td><?php echo $producto->getDescripcion();?></td></tr>

	<tr><td>Escala</td><td><?php echo $producto->getEscala();?>s</td></tr>
	<tr><td>Tratamiento</td><td><?php echo $producto->getTratamiento();?></td></tr>	

	<tr><td>
		<input type="button" onclick="window.location='inventario.php?action=editar&id=<?php echo $producto->getIdProducto();?>' " value="Editar" />
	</td></tr>
	
</table>
</form>


<h2>Fluctuaciones de precio</h2><?php

function renderUsuario( $uid )
{
	$u = UsuarioDAO::getByPK( $uid );
	return $u->getNombre();
}
    $header = array( 
//	    "id_actualizacion" => "ID",
	    "fecha"=> "Fecha",
//	    "id_producto"=> "Producto",
	    "id_usuario"=> "Usuario",
	    "precio_venta"=> "Precio Sugerido",
	    "precio_intersucursal"=> "Precio Intersusucursal" );

    $tabla = new Tabla( $header, $fluctuaciones );
    $tabla->addColRender( "precio_venta", "moneyFormat" ); 
    $tabla->addColRender( "precio_intersucursal", "moneyFormat" ); 
    $tabla->addColRender( "id_usuario", "renderUsuario" ); 
    $tabla->addColRender( "fecha", "toDate" ); 
    $tabla->render();

?>






