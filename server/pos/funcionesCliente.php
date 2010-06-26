<?php


	function insertarCliente(){
		$id =null;
		$rfc=$_REQUEST['rfc'];
		$nombre=$_REQUEST['nombre'];
		$direccion=$_REQUEST['direccion'];
		$telefono=$_REQUEST['telefono'];
		$e_mail=$_REQUEST['e_mail'];
		$limite_credito=$_REQUEST['limite_credito'];
		$cliente = new cliente($rfc,$nombre,$direccion,$telefono,$e_mail,$limite_credito);
		
		if ($cliente->inserta()){
			$cuenta_cliente = new cuenta_cliente($cliente->id_cliente,0);
			if($cuenta_cliente->inserta()){
				echo "{success: true , id_cliente: ".$cliente->id_cliente."}";
			}else{
				echo "{success: false }";
			}
			
		}else{
			echo "{success: false }";
		}
	}
	function actualizarCliente(){
		
		$cliente = new cliente_existente($_REQUEST['id']); 
		$cliente->id_cliente=$_REQUEST['id'];
		$cliente->rfc=$_REQUEST['rfc'];
		$cliente->nombre=$_REQUEST['nombre'];
		$cliente->direccion=$_REQUEST['direccion'];
		$cliente->telefono=$_REQUEST['telefono'];
		$cliente->e_mail=$_REQUEST['e_mail'];
		$cliente->limite_credito=$_REQUEST['limite_credito'];
	
		
		if($cliente->actualiza()){
			echo "{success: true}";
		}else{
			echo "{success: false}";
		}
	}
	
	function eliminarCliente(){
		$cliente = new cliente_existente($_REQUEST['id']); 
		$cliente->id_cliente =$_REQUEST['id'];
		if($cliente->borra()){
			echo "{success: true }";
		}else{
			echo "{success : false }";
		}
	}
	
	function listarClientes(){
		$listar = new listar("select * from cliente",array());
		echo $listar->lista();
		return $listar->lista();
	}
	
	function mostrarCliente(){
		$id=$_REQUEST['id'];
		//$cliente = new listar("select * from cliente where id_cliente=".$id.";",array());
		$cliente = new cliente_existente($id);
		$cliente->id_cliente=$id;
		echo "{ success: true , \"datos\":".$cliente->json()."}";
		//echo "".$cliente->lista();
	}
	
	function reporteClientesTodos(){
		$query="SELECT `id_cliente` as 'ID',`nombre` as 'Nombre',`rfc` as 'RFC',`direccion` as 'Direccion' ,`telefono` as Telefono ,`e_mail` as 'E-mail',`limite_credito` as 'Limite de credito' from cliente";
		$listar = new listar($query,array());
		echo $listar->lista();
		return $listar->lista();
	}
	function reporteClientesDeben(){
		$query="SELECT nombre AS  'Nombre', saldo AS  'Saldo',  `rfc` AS  'RFC',  `direccion` AS  'Direccion',  `telefono` AS Telefono,  `e_mail` AS  'E-mail' FROM cliente c NATURAL JOIN cuenta_cliente cc"; 
		$listar = new listar($query,array());
		echo $listar->lista();
		return $listar->lista();
	}
	function reporteClientesCompras(){
		$query="SELECT id_venta AS  'ID', nombre AS  'Cliente', ( subtotal + iva ) AS  'Total', IF( tipo_venta =1,  'Contado',  'Credito' ) AS  'Tipo', date(fecha) AS  'Fecha', sucursal AS  'Sucursal' FROM  `ventas` NATURAL JOIN cliente"; 
		$listar = new listar($query,array());
		echo $listar->lista();
		return $listar->lista();
	}	
	function reporteClientesComprasCredito(){
		$query="SELECT pv.id_venta, (
				v.subtotal + v.iva
				) AS  'Total', SUM( pv.monto ) AS  'Pagado', (
				v.subtotal + v.iva - pv.monto
				) AS  'Debe', c.nombre AS  'Nombre', DATE( v.fecha ) AS  'Fecha'
				FROM  `pagos_venta` pv
				LEFT JOIN ventas v ON ( pv.id_venta = v.id_venta ) 
				NATURAL JOIN cliente c
				GROUP BY pv.id_venta "; 
		$listar = new listar($query,array());
		echo $listar->lista();
		return $listar->lista();
	}
	
	function reporteClientesComprasCreditoDeben(){
		$query="SELECT pv.id_venta, (
				v.subtotal + v.iva
				) AS  'Total', SUM( pv.monto ) AS  'Pagado', (
				v.subtotal + v.iva - pv.monto
				) AS  'Debe', c.nombre AS  'Nombre', DATE( v.fecha ) AS  'Fecha'
				FROM  `pagos_venta` pv
				LEFT JOIN ventas v ON ( pv.id_venta = v.id_venta ) 
				NATURAL JOIN cliente c
				GROUP BY pv.id_venta
				having Pagado<Total"; 
		$listar = new listar($query,array());
		echo $listar->lista();
		return $listar->lista();
	}

	
?>

