<?php


	require_once('controller/clientes.controller.php');
	require_once('model/cliente.dao.php');

?>

<h1>Realizar venta</h1>

<h2>Cliente</h2>


<?php
	if(!isset($_REQUEST['cid'])){
	    $clientes = listarClientes();
    
		if(sizeof($clientes ) > 0){
			echo '<select id="sucursal"> ';    
			foreach( $clientes as $c ){
				echo "<option value='" . $c['id_cliente'] . "' >" . $c['nombre']  . "</option>";
			}
			echo '</select>';    
		}else{
		
			echo "<h3>No hay clientes a quien realizarle la venta</h3>";
		}
	}else{
	
		$cliente = ClienteDAO::getByPK( $_REQUEST['cid'] );
		
		if($cliente === null){
			echo "<h3>Este cliente no existe</h3>";
		}else{
		
		?>
			<table border="0" cellspacing="5" cellpadding="5">
				<tr><td><b>Nombre</b></td><td><?php echo $cliente->getNombre(); ?></td><td rowspan=12><div id="map_canvas"></div></td></tr>
				<tr><td><b>RFC</b></td><td><?php echo $cliente->getRFC(); ?></td></tr>
				<tr><td><b>Direccion</b></td><td><?php echo $cliente->getDireccion(); ?></td></tr>
				<tr><td><b>Ciudad</b></td><td><?php echo $cliente->getCiudad(); ?></td></tr>
				<tr><td><b>Telefono</b></td><td><?php echo $cliente->getTelefono(); ?></td></tr>	
				<tr><td><b>E Mail</b></td><td><?php echo $cliente->getEMail(); ?></td></tr>	
				<tr><td><b>Limite de Credito</b></td><td><?php echo moneyFormat($cliente->getLimiteCredito()); ?></td></tr>	

				<tr><td><b>Descuento</b></td><td><?php echo percentFormat( $cliente->getDescuento() ); ?></td></tr>
				<tr><td><b>Fecha Ingreso</b></td><td><?php echo $cliente->getFechaIngreso() ; ?></td></tr>

				<tr><td><b>Gerente que dio de alta</b></td><td><?php echo UsuarioDAO::getByPK( $cliente->getIdUsuario() )->getNombre() ; ?></td></tr>
	
				<?php
					$foo = SucursalDAO::getByPK( $cliente->getIdSucursal() );
					$_suc = $foo == null ? "Ninguna" : $foo->getDescripcion();
				?>
				<tr><td><b>Sucursal donde se dio de alta</b></td><td><?php echo $_suc; ?></td></tr>

			</table>
		
		<?php
		
		}
	
	}


?>


<h2>Productos</h2>


