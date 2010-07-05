<?php	
//include("../AddAllClass.php");

	function venderProducto(){
		if((!empty($_REQUEST['id_producto']))&&(!empty($_REQUEST['cantidad']))&&(!empty($_REQUEST['id_sucursal']))){ //revisa que se envien todos los datos
			$id=$_REQUEST['id_producto'];																				//asigna valores a variables
			$cantidad=$_REQUEST['cantidad'];
			$id_sucursal=$_REQUEST['id_sucursal'];
			$detalle_inventario=new detalle_inventario_existente($id,$id_sucursal);										//creamos un objeto de la clase detalle_inventario que vamos a modificar
			$producto=new inventario_existente($id);																	//creamos un objeto de la clase inventario para ver si existe
			if($producto->existe()){																					//checamos si existe el producto en inventario
				$verifica_sucursal=new sucursal_existente($id_sucursal);												//creamos un objeto de la clase sucursal para veridicar que tambien exista
				if($verifica_sucursal->existe()){																		//checamos que exista la sucursal
					if($detalle_inventario->existe()){																	//Verificamos si ya existe el registro del producto en la sucursal
						$detalle_inventario->existencias=$detalle_inventario->existencias - $cantidad;					//restamos el producto que sale a la existencia
						if($detalle_inventario->actualiza())			ok();											//actualizamos y verificamos que realice la actualizacion
						else											fail("Error al agregar los datos");
					}else{
						fail("Error El producto no exite funcion venderProducto else if detalle_inventario existe()");
					}
				}else													fail("La sucursal de la compra no existe.");
			}else 														fail("El producto que desea comprar no existe.");
		}else 															fail("Faltan datos.");
		return;
	}
	
	/*	SE AGREGA 1 PRODUCTO AL DETALLE DE LA VENTA Y SE REFRESCA LA CABECERA (tabla ventas)*/
	
	function agregarProductoDetalle_venta(){
		$id_venta=$_REQUEST['id_venta'];
		$id_producto=$_REQUEST['id_producto'];
		$cantidad=$_REQUEST['cantidad'];
		$precio =$_REQUEST['precio'];
		$id_usuario = $_REQUEST['id_usuario'];
		
		$detalle_venta= new detalle_venta($id_venta,$id_producto,$cantidad,$precio);//$id_venta,$id_producto,$cantidad,$precio

		if($detalle_venta->inserta()){
			$venta_existente = new venta_existente($id_venta);//$id_proveedor,$tipo_compra,$sucursal,$id_usuario
			$venta_existente->id_venta=$id_venta;
			$detalle_venta = $venta_existente->detalle_venta($id_compra);
			if(actualizaCabeceraVenta($detalle_venta,$id_venta,$id_usuario)){ //<---aqui llamar a comprarProducto
				echo "{ success : true , \"datos\" : ".json_encode($detalle_venta)."}";
			}else{
				echo "{success: false}";	
			}
		}else{
			echo "{success: false , \"error\": [{\"metodo\":\"if_detalleCompra->inserta()\"}]}";
		}
	}
	
	/*	SE ELIMINA 1 PRODUCTO AL DETALLE DE LA VENTA Y SE REFRESCA LA CABECERA (tabla ventas)*/
	
	function eliminarProductoDetalle_venta(){
		$id_venta=$_REQUEST['id_venta'];
		$id_producto=$_REQUEST['id_producto'];
		$cantidad=$_REQUEST['cantidad'];
		$precio =$_REQUEST['precio'];
		$id_usuario= $_REQUEST['id_usuario'];
		
		$detalle_venta= new detalle_venta($id_venta,$id_producto,$cantidad,$precio);
		
		if($detalle_venta->borra()){
			$venta_existente = new venta_existente($id_venta);
			$venta_existente->id_venta=$id_venta;
			$detalle_venta=$venta_existente->detalle_venta($id_venta);
			if(actualizaCabeceraVenta($detalle_venta,$id_venta,$id_usuario)){
				echo "{ success : true , \"datos\" : ".json_encode($detalle_venta)."}";
			}else{
				echo "{success: false}";	
			}
		}else{
			echo "{success: false}";
		}
	}
	
	/*	CAMBIA LA CANTIDA DE PRODUCTO EN UN DETALLE DE LA VENTA Y SE REFRESCA LA CABECERA (tabla ventas)*/
	
	function actualizarCantidadProductoDetVenta(){
		$id_venta =$_REQUEST['id_venta'];
		$id_producto =$_REQUEST['id_producto'];
		$cantidad =$_REQUEST['cantidad'];
		$precio =$_REQUEST['precio'];
		$id_usuario = $_REQUEST['id_usuario'];
		
		$detalle_venta = new detalle_venta ($id_venta,$id_producto,$cantidad,$precio);
	
		if($detalle_venta->actualiza()){
			$venta_existente = new venta_existente($id_venta);
			$venta_existente->id_venta=$id_venta;
			$detalle_venta=$venta_existente->detalle_venta($id_venta);
			if(actualizaCabeceraVenta($detalle_venta,$id_venta,$id_usuario)){
				echo "{ success : true , \"datos\" : ".json_encode($detalle_venta)."}";
			}else{
				echo "{success: false}";	
			}
		}else{
			echo "{success: false}";
		}
	}//fin modificarCantidad
	
	/*	REFRESCA LA CABECERA (tabla ventas) CON RESPECTO A LOS PRODUCTOS QUE CONTIENE ESA VENTA ACTUALIZA EL TOTAL Y SUBTOTAL 
	PARA CADA MOVIMIENTO QUE SE HACE CON EL DETALLE DE LA VENTA*/
	
	function actualizaCabeceraVenta($detalle_venta,$id_venta,$id_usuario){
		$subtot=0;
		$dim = count($detalle_venta);
		for($i=0;$i<$dim;$i++){
			$subtot += $detalle_venta[$i]["subtotal"];
		}
		$iva = new impuesto_existente(5);//en mi bd el iva es el id 5
		$iva->id_impuesto=5;
		
		$iva_valor=$iva->valor;
		
		$venta = new venta_existente($id_venta);
		$venta->id_venta=$id_venta;
		$venta->id_proveedor=$id_usuario;
		$venta->subtotal=$subtot;
		$venta->iva=0;
		
		if($venta->actualiza()){
			return true;
		}else{
			return false;
		}
	}//actualiza cabecera
	
	/*	SE ENLISTAN TODAS LAS VENTAS EMITIDAS EN TODAS LAS SUCURSALES*/
	
	function listarVentas(){
		$listar = new listar("SELECT id_venta ,id_cliente,tipo_venta,fecha,subtotal,iva,sucursal,id_usuario,(iva + subtotal) as total FROM `ventas`",array());
		echo $listar->lista();
		return ;
	}
	
	/*	SE ENLISTAN TODAS LAS VENTAS EMITIDAS A UN CLIENTE EN CUALQUIER SUCURSAL*/
	
	function listarVentasCliente(){
		$id_cliente = $_REQUEST['id_cliente'];
		$listar = new listar("SELECT ventas.id_venta, ventas.id_cliente, ventas.tipo_venta, ventas.fecha, ventas.subtotal,ventas.iva, sucursal.descripcion, usuario.nombre, (iva + subtotal) AS total FROM  `ventas`  INNER JOIN  `usuario` ON ventas.id_usuario = usuario.id_usuario INNER JOIN  `sucursal` ON ventas.sucursal = sucursal.id_sucursal WHERE ventas.id_cliente =".$id_cliente,array());
		echo $listar->lista();
	}

	/* TRAE TODAS LAS VENTAS A CREDITO HECHAS POR UN CLIENTE ASI COMO LO QUE HA ABONADO DE CADA VENTA A CREDITO*/
	function listarVentasCreditoCliente(){
		$ventasCreditoCliente = array();
		$id_cliente = $_REQUEST['id_cliente'];
		
		$listar = new listar("SELECT ventas.id_venta, ventas.id_cliente, ventas.tipo_venta, ventas.fecha, ventas.subtotal,ventas.iva, sucursal.descripcion, usuario.nombre, (iva + subtotal) AS total FROM  `ventas`  INNER JOIN  `usuario` ON ventas.id_usuario = usuario.id_usuario INNER JOIN  `sucursal` ON ventas.sucursal = sucursal.id_sucursal WHERE ventas.id_cliente =".$id_cliente." AND ventas.tipo_venta =2",array());
		
		$ventasCredito;
		
		if (($ventasCredito  = $listar->Resultadoconsulta())< 1){
			 echo "{ success : false }";
		 }else{
		
		 $numV = count($ventasCredito);
		 
		 for($i=0; $i<$numV; $i++){
			 $query="SELECT SUM( monto ) as abonado FROM  `pagos_venta` WHERE pagos_venta.id_venta =".$ventasCredito[$i]["id_venta"].";";
			 $abonosVta = new listar($query,array());
			 $abonosTot = $abonosVta->Resultadoconsulta();
			 //echo "<br>---- FUNCION en la venta: ".$ventasCredito[$i]["id_venta"]." ha abonado: ".$abonosTot[0]["abonado"]." -----";
			 array_push($ventasCreditoCliente,array("id_venta"=>$ventasCredito[$i]["id_venta"],"id_cliente"=>$ventasCredito[$i]["id_cliente"],"tipo_venta"=>$ventasCredito[$i]["tipo_venta"],"fecha"=>$ventasCredito[$i]["fecha"],"subtotal"=>$ventasCredito[$i]["subtotal"],"iva"=>$ventasCredito[$i]["iva"],"descripcion"=>$ventasCredito[$i]["descripcion"],"nombre"=>$ventasCredito[$i]["nombre"],"total"=>$ventasCredito[$i]["total"],"abonado"=>$abonosTot[0]["abonado"]));
		 
		 }
		 echo " { success : true, datos : ". json_encode($ventasCreditoCliente)."}";
		}
	}//fin listar Ventas CreditoCliente
	
	function abonosVentaCredito(){
		$id_venta = $_REQUEST['id_venta'];
		$listar = new listar("SELECT id_venta,fecha,monto FROM pagos_venta WHERE id_venta =".$id_venta,array());
		echo $listar->lista();
	}
	
	/*	SE MUESTRA EL CONTENIDO DE UNA VENTA EN ESPECIFICO EMITIDA A UN CLIENTE */
	
	function mostrarDetalleVenta(){
		$id_cliente=$_REQUEST['id_cliente'];
		$id_venta=$_REQUEST['id_venta'];
		$venta = new venta_existente($id_venta);
		$venta->id_venta=$id_venta;
		if(count($detalle_venta=$venta->detalle_venta($id_venta))>0){
			echo "{ success: true , \"datos\" : ".json_encode($detalle_venta)."}";	
		}else{
			echo "{ success: false }";
		}
	}
	
	/*	SE INSERTA UNA NUEVA VENTA (UNICAMENTE LA CABECERA) */
	
	function insertarVenta(){
		$id_cliente =$_REQUEST['id_cliente'];
		$tipo_venta=$_REQUEST['tipo_venta'];
		$sucursal=$_REQUEST['sucursal'];
		$id_usuario=$_REQUEST['id_usuario'];
		$venta = new venta($id_cliente,$tipo_venta,$sucursal,$id_usuario);
				
		if($venta->inserta()){
			echo "{success: true}";
		}else{
			echo "{success: false}";
		}
	}//fin insertar
	
	/*	SE ELIMINA UNA VENTA */
	
	function eliminarVenta(){
		$id_venta=$_REQUEST['id_venta'];

		$venta= new venta_existente($id_venta);
		$venta->id_venta=$id_venta;
		if($venta->borra()){
			echo "{success: true}";
		}else{
			echo "{success: false}";
		}
	}	
	
	
	
	
	function reporteVenta(){
		if(!empty($_REQUEST['id_venta'])){
			$id=$_REQUEST['id_venta'];
			$query="SELECT IF( c.tipo_venta=1,  'Contado',  'Credito' ) AS  'Tipo', DATE( c.fecha ) AS  'Fecha', c.subtotal AS  'Subtotal', c.iva AS  'Iva', (
					c.subtotal + c.iva
					) AS  'Total', p.rfc AS  'RFC', p.nombre AS  'Nombre'
					FROM ventas c
					NATURAL JOIN cliente p
					where c.id_venta=?"; 
			$listar = new listar($query,array($id));
			$venta=$listar->lista_datos("venta");
			$listar->query="select * , (precio*cantidad) as 'Subtotal' from detalle_venta where id_venta=?";
			$detalles=$listar->lista_datos("detalle_venta");
			ok_datos("$venta , $detalles");
			return;
		}else 											fail("Faltan datos.");
	}
	function reporteFacturaVenta(){
		if(!empty($_REQUEST['id_factura'])){
			$id=$_REQUEST['id_factura'];
			$query="SELECT f.folio, IF( c.tipo_venta=1,  'Contado',  'Credito' ) AS  'Tipo', c.subtotal AS  'Subtotal', c.iva AS  'Iva', (
					c.subtotal + c.iva
					) AS  'Total', p.rfc AS  'RFC', p.nombre AS  'Nombre'
					FROM factura_venta f
					NATURAL JOIN ventas c
					NATURAL JOIN cliente p
					WHERE f.id_factura =?"; 
			$listar_factura = new listar($query,array($id));
			$factura=$listar_factura->lista_datos("factura");
			$fac=new factura_venta_existente($id);
			$query="select * , (precio*cantidad) as 'Subtotal' from detalle_venta where id_venta=?";
			$listar_detalle = new listar($query,array($fac->id_compra));
			$detalles=$listar_factura->lista_datos("detalle_factura");
			ok_datos("$factura , $detalles");
			return;
		}else 											fail("Faltan datos.");
	}
	

?>
