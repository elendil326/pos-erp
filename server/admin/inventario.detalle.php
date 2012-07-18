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
<table border="0" cellspacing="5" cellpadding="5" style="width: 100%">
	<tr><td>ID Producto</td><td><?php echo $producto->getIdProducto();?></td><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	<tr><td>Descripcion</td><td><b><?php echo $producto->getDescripcion();?></b></td></tr>

	<tr><td>Escala</td><td><?php echo ucfirst($producto->getEscala());?>s</td></tr>
	<?php
	

		if( $producto->getTratamiento() )
			$tratamiento = ucfirst($producto->getTratamiento());
		else
			$tratamiento = "Sin tratamiento";
	?>
	<tr><td>Tratamiento</td><td><?php echo $tratamiento; ?></td></tr>	
	<tr><td>Agrupacion</td><td>
		<?php 
			if($producto->getAgrupacion()){
				echo $producto->getAgrupacionTam()  . " " . $producto->getEscala() .  "s por " . $producto->getAgrupacion();				
			}else{
				echo "Sin agrupacion";
			}
		?></td></tr>	
		<tr><td>Precio</td><td><?php
			if($producto->getPrecioPorAgrupacion()){
				echo "Por " . $producto->getAgrupacion() ;
			}else{
				echo "Por " . $producto->getEscala();
			}
		?></td></tr>
	<tr ><td colspan=4>
		<h4><input type="button" onclick="window.location='inventario.php?action=editar&id=<?php echo $producto->getIdProducto();?>' " value="Editar detalles del producto" /></h4>
	</td></tr>
	
</table>
</form>


<h2>Fluctuaciones de precio</h2><?php

function renderUsuario( $uid )
{
	$u = UsuarioDAO::getByPK( $uid );
	return $u->getNombre();
}


	//campos base
    $header = array( 
	    "fecha"=> "Fecha",
	    "id_usuario"=> "Usuario",
	    "precio_venta"=> "Precio de Venta Sugerido" );
	
	if(POS_MULTI_SUCURSAL)
		$header["precio_intersucursal"] = "Precio Intersusucursal" ;

	if(POS_COMPRA_A_CLIENTES)
		$header["precio_compra"] = "Precio de Compra Sugerido" ;
		
    $tabla = new Tabla( $header, $fluctuaciones );
    $tabla->addColRender( "precio_venta", "moneyFormat" ); 

	if(POS_MULTI_SUCURSAL)
    	$tabla->addColRender( "precio_intersucursal", "moneyFormat" ); 

	if(POS_COMPRA_A_CLIENTES)
    	$tabla->addColRender( "precio_compra", "moneyFormat" ); 

    $tabla->addColRender( "id_usuario", "renderUsuario" ); 
    $tabla->addColRender( "fecha", "toDate" ); 
    $tabla->render();

?>






