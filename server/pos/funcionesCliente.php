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
		$listar = new listar("SELECT cliente.id_cliente,rfc,nombre,direccion,telefono,e_mail,limite_credito,cuenta_cliente.saldo 
FROM  `cliente` INNER JOIN  `cuenta_cliente` ON cliente.id_cliente = cuenta_cliente.id_cliente",array());
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
	
	    //esta funcion nos regresa un listado con los datos de todas las ventas a credito
		//si se le manda un id_cliente nos regresa las compras a credito de ese cliente
		//si le mandamo de y al como fechas en formato YYYY-MM-DD nos regresa las compras en ese periodo
        function reporteClientesComprasCredito(){
        
        $id_cliente=$_REQUEST['id_cliente'];
                $de=$_REQUEST['de'];
                $al=$_REQUEST['al'];
                $cliente=!empty($id_cliente);
                $fecha=(!empty($de)&&!empty($al));
                $params=array();
                
                $query="SELECT v.id_venta, (
                                v.subtotal + v.iva
                                ) AS  'Total', IF(SUM( pv.monto )>0,SUM(pv.monto),0) AS  'Pagado',
                                if((v.subtotal + v.iva - SUM( pv.monto ))>0,(v.subtotal + v.iva - SUM( pv.monto )),(v.subtotal + v.iva)
                                ) AS  'Debe', c.nombre AS  'Nombre', DATE( v.fecha ) AS  'Fecha'
                                FROM  `pagos_venta` pv
                                RIGHT JOIN ventas v ON ( pv.id_venta = v.id_venta ) 
                                NATURAL JOIN cliente c
                                GROUP BY v.id_venta,c.id_cliente,v.fecha ,v.tipo_venta
                                having v.tipo_venta =2 "; 
                if($cliente){
                        $query.=" and c.id_cliente=? ";
                        array_push($params,$id_cliente);
                }
                if($fecha){
                        $query.=" and  DATE(v.fecha) BETWEEN ? AND ? ";
                        array_push($params,$de);
                        array_push($params,$al);
                }
                $query.=" ORDER BY v.fecha ;";
                $listar = new listar($query,$params);
                echo $listar->lista();
                return $listar->lista();
        }
        
	    //esta funcion nos regresa un listado con los datos de todas las ventas a credito que aun se deben
		//si se le manda un id_cliente nos regresa las compras a credito de ese cliente
		//si le mandamo de y al como fechas en formato YYYY-MM-DD nos regresa las compras en ese periodo
        function reporteClientesComprasCreditoDeben(){
                $id_cliente=$_REQUEST['id_cliente'];
                $de=$_REQUEST['de'];
                $al=$_REQUEST['al'];
                $cliente=!empty($id_cliente);
                $fecha=(!empty($de)&&!empty($al));
                $params=array();
                $query="SELECT v.id_venta, (
                                v.subtotal + v.iva
                                ) AS  'Total', IF(SUM( pv.monto )>0,SUM(pv.monto),0) AS  'Pagado',
                                if((v.subtotal + v.iva - SUM( pv.monto ))>0,(v.subtotal + v.iva - SUM( pv.monto )),(v.subtotal + v.iva)
                                ) AS  'Debe', c.nombre AS  'Nombre', DATE( v.fecha ) AS  'Fecha'
                                FROM  `pagos_venta` pv
                                RIGHT JOIN ventas v ON ( pv.id_venta = v.id_venta ) 
                                NATURAL JOIN cliente c
                                GROUP BY v.id_venta,c.id_cliente,v.fecha,v.tipo_venta,v.tipo_venta
                                having Pagado < Total and v.tipo_venta =2 ";
                if($cliente){
                        $query.=" and c.id_cliente=? ";
                        array_push($params,$id_cliente);
                }
                if($fecha){
                        $query.=" and DATE(v.fecha) BETWEEN ? AND ? ";
                        array_push($params,$de);
                        array_push($params,$al);
                }
                $query.=" ORDER BY v.fecha;";
                $listar = new listar($query,$params);
                echo $listar->lista();
                return $listar->lista();
        }

		
	    //esta funcion nos regresa un listado con los datos de todas las ventas a credito
		//si se le manda un id_cliente nos regresa las compras a credito de ese cliente
		//si le mandamo de y al como fechas en formato YYYY-MM-DD nos regresa las compras en ese periodo
        function reporteClientesComprasCreditoPagado(){
                $id_cliente=$_REQUEST['id_cliente'];
                $de=$_REQUEST['de'];
                $al=$_REQUEST['al'];
                $cliente=!empty($id_cliente);
                $fecha=(!empty($de)&&!empty($al));
                $params=array();
                $query="SELECT v.id_venta, (
                                v.subtotal + v.iva
                                ) AS  'Total', IF(SUM( pv.monto )>0,SUM(pv.monto),0) AS  'Pagado', 
                                if((v.subtotal + v.iva - SUM( pv.monto ))>0,(v.subtotal + v.iva - SUM( pv.monto )),(v.subtotal + v.iva)
                                ) AS  'Debe', c.nombre AS  'Nombre', DATE( v.fecha ) AS  'Fecha'
                                FROM  `pagos_venta` pv
                                RIGHT JOIN ventas v ON ( pv.id_venta = v.id_venta ) 
                                NATURAL JOIN cliente c
                                GROUP BY v.id_venta,c.id_cliente,v.fecha,v.tipo_venta
                                having Pagado >= Total and v.tipo_venta =2 ";
                if($cliente){
                        $query.=" and c.id_cliente=? ";
                        array_push($params,$id_cliente);
                }
                if($fecha){
                        $query.=" and DATE(v.fecha) BETWEEN ? AND ? ";
                        array_push($params,$de);
                        array_push($params,$al);
                }
                $query.=" ORDER BY v.fecha;";
                $listar = new listar($query,$params);
                echo $listar->lista();
                return $listar->lista();
        }
		
?>
