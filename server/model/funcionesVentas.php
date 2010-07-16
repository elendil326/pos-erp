<?php	
//include("../AddAllClass.php");

	function venderProducto($id_producto, $existenciass, $id_Sucursal){
		
			$id=$id_producto;
			$cantidad=$existenciass;
			$id_sucursal=$id_Sucursal;
			
			$detalle_inventario=new detalleInventarioExistente($id,$id_sucursal);										//creamos un objeto de la clase detalle_inventario que vamos a modificar
			$producto=new inventarioExistente($id);																	//creamos un objeto de la clase inventario para ver si existe
			if($producto->existe()){																					//checamos si existe el producto en inventario
				$verifica_sucursal=new sucursalExistente($id_sucursal);												//creamos un objeto de la clase sucursal para veridicar que tambien exista
				if($verifica_sucursal->existe()){																		//checamos que exista la sucursal
					if($detalle_inventario->existe()){																	//Verificamos si ya existe el registro del producto en la sucursal
						$detalle_inventario->existencias=$detalle_inventario->existencias - $cantidad;					//restamos el producto que sale a la existencia
						if($detalle_inventario->actualiza())			return true;											//actualizamos y verificamos que realice la actualizacion
						else											return "Error al agregar los datos en el inventario";
					}else{
						return "Error El producto no exite funcion venderProducto else if detalle_inventario existe()";
					}
				}else													return "La sucursal de la venta no existe.";
			}else 														return "El producto que desea vender no existe.";
		
	}
	
	/*	SE AGREGA 1 PRODUCTO AL DETALLE DE LA VENTA Y SE REFRESCA LA CABECERA (tabla ventas)*/
	
	function agregarProductoDetalle_venta($id_venta, $id_producto, $cantidad, $precio, $id_sucursal){
		$resultado = array();
		array_push($resultado,false,"");
		
		$id_usuario = $_SESSION['id_usuario'];
		
		$detalle_venta= new detalle_venta($id_venta,$id_producto,$cantidad,$precio);//$id_venta,$id_producto,$cantidad,$precio

		if($detalle_venta->inserta()){
			$res = venderProducto($id_producto, $cantidad, $id_sucursal);
			
			if($res == true){ 
				$resultado[0] = true;
			}else{
				$resultado[0]= false;
				$resultado[1] = " -- ".$res;
			}
		}else{
			$resultado[0] = false;
			$resultado[1] = " -- No se pudo insertar el producto con ID: '".$id_producto."' de la venta. en la venta con id: ".$id_venta;
		}
		return $resultado;
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
			$venta_existente = new ventaExistente($id_venta);
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
			$venta_existente = new ventaExistente($id_venta);
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
	
	function actualizaCabeceraVenta($id_venta,$id_usuario,$subtotalVenta){
		
		$iva = new impuestoExistente(5);//en mi bd el iva es el id 5
		$iva->id_impuesto=5;
		
		$iva_valor=$iva->valor;
		
		$venta = new ventaExistente($id_venta);
		$venta->id_venta=$id_venta;
		$venta->id_proveedor=$id_usuario;
		$venta->subtotal=$subtotalVenta;
		$venta->iva=($iva_valor/100) * $subtotalVenta;
		
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
		$venta = new ventaExistente($id_venta);
		$venta->id_venta=$id_venta;
		if(count($detalle_venta=$venta->detalle_venta($id_venta))>0){
			echo "{ success: true , \"datos\" : ".json_encode($detalle_venta)."}";	
		}else{
			echo "{ success: false }";
		}
	}
	
	/*	SE INSERTA UNA NUEVA VENTA (UNICAMENTE LA CABECERA) */
	
	function insertarVenta(){
		$sucursal=$_SESSION['sucursal_id'];
		$id_usuario=$_SESSION['id_usuario'];
		$objson = $_REQUEST['jsonItems'];
		$arregloItems = json_decode($objson,true);
		$dim = count($arregloItems);
		$id_cliente =$_REQUEST['id_cliente'];
		$tipo_venta=$_REQUEST['tipo_venta']; 
		
		$venta = new venta($id_cliente,$tipo_venta,$sucursal,$id_usuario);
		$id_venta = 0;
		if( $venta->inserta() ){ //se inserta la cabecera de la venta
		
			$id_venta = $venta->id_venta;
			$succes = array(); array_push($succes,false,"");
			$subtotalVenta = 0;
		
			for($i=0; $i < $dim; $i++){
		
				$resul = agregarProductoDetalle_venta($id_venta, $arregloItems[$i]['id'], $arregloItems[$i]['cantidad'], $arregloItems[$i]['cost'], $sucursal); //se van agregando los productos que trae el json del lado del cliente a tabla detalle_venta
				$subtot = $arregloItems[$i]['cantidad'] * $arregloItems[$i]['cost']; //se saca el subtotal de ese detalle_venta
				$subtotalVenta += $subtot; //se van sumando los subtotales para el total de la cabecera de la venta
				$succes[0]=$resul[0];
				$succes[1].=$resul[1];
			}//fin for
			//se actualiza la cabecera con el nuevo total
		
			if (!actualizaCabeceraVenta($id_venta,$id_usuario,$subtotalVenta)) $succes[1].=" -- No se modifico el subtotal e iva de la compra";
			echo "{ \"success\" : \"true\" , reason :'".$succes[1]."' , final: '".$succes[0]."' }";
		}else{
			echo "{ \"success\" : \"false\" , reason : 'No se pudo insertar la cabecera de la venta ni sus detalles' }";
		}
	}//fin insertar
	
	
	/*	SE ELIMINA UNA VENTA */
	
	function eliminarVenta(){
		$id_venta=$_REQUEST['id_venta'];

		$venta= new ventaExistente($id_venta);
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
			$fac=new factura_ventaExistente($id);
			$query="select * , (precio*cantidad) as 'Subtotal' from detalle_venta where id_venta=?";
			$listar_detalle = new listar($query,array($fac->id_compra));
			$detalles=$listar_factura->lista_datos("detalle_factura");
			ok_datos("$factura , $detalles");
			return;
		}else 											fail("Faltan datos.");
	}
	
	
/*este documentotiene todas las funciones de ventas
como insertar, eliminar, actualizar, consultas, listar 
y algunas otras funciones
*/
	//esta funcion inserta una nueva factura para una venta
	function insertarFacturaVenta()
	{
		//verificamos que no nos envien datos vacios  		
		if((!empty($_REQUEST['folio']))&&(!empty($_REQUEST['id_venta'])))
		{
			//asignamos valores obtenidos a las variables
			$folio=$_REQUEST['folio'];
			$id_venta=$_REQUEST['id_venta'];
			//crea un objeto de la clase factura_venta con el id y el folio
			$factura=new factura_venta($folio,$id_venta);
			//crea un objeto de la clase venta con el id
			$verifica_venta=new ventaExistente($id_venta);
			//checa que exista dicha venta
			if($verifica_venta->existe())
			{						 	
				//checa que no haya otra factura para la venta
				if(!$factura->existe_venta())
				{						
					//checa que ninguna factura tenga ese folio
					if(!$factura->existe_folio())
					{					
						//verifica que no exista la factura
						if(!$factura->existe())
						{
							//intentamos insertar la factura
							if($factura->inserta())			ok();														//logramos inserta
							else							fail("Error al guardar la factura.");						//fallo el intento de insersion
						}//if no exista la fcatura
						else 								fail("Ya existe esta factura.");							//la factura ya existe
					}//if existe folio
					else 									fail("Ya existe una factura con este numero de folio.");	//el numero de folio ya existe
				}//if no existe factura
				else 										fail("Ya existe una factura para esta venta.");				//la venta ya tiene factura
			}//if verifica venta
			else 											fail("La venta que desea facturar no existe.");				//la venta no existe
		}//if verifica datos
		else												fail("Faltan datos.");										//no se pasaron todos los datos necesarios
		return;
	}
	//funcion insertar factura Venta
	
	//esta funcion elimina una factura de venta
	function eliminarFacturaVenta()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_factura']))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_factura'];
			//creamos objeto de la clase factura venta
			$factura=new factura_ventaExistente($id);
			//verifica que si exista la factura
			if($factura->existe())
			{												
				//intentamos elimina la factura
				if($factura->borra())						ok();														//se borro la factura
				else										fail("Error al borrar la factura.");						//se fallo el borrado de factura
			}//if existe factura
			else 											fail("La factura que desea eliminar no existe.");			//no existe la factura
		}//if verifica datos
		else 												fail("faltan datos.");										//datos incompletos
		return;
	}
	//funcion aliminarFacturaVenta
	
	//esta funcion actualiza una factura venta
	function actualizarFacturaVenta()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_factura']))&&(!empty($_REQUEST['folio']))&&(!empty($_REQUEST['id_venta']))&&(!empty($_REQUEST['subtotal']))&&(!empty($_REQUEST['iva'])))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_factura'];
			$folio=$_REQUEST['folio'];
			$id_venta=$_REQUEST['id_venta'];
			$subtotal=$_REQUEST['subtotal'];
			$iva=$_REQUEST['iva'];
			//creamos objeto venta existente para modificar
			$factura=new factura_ventaExistente($id);								
			//variable para verificar si es el mismo id de venta
			$venta=$factura->id_venta;												
			//variable para verificar si es el mismo folio
			$folio1=$factura->folio;												
			//verificamos que si exista la factura
			if($factura->existe())
			{											
				//le asignamos los valores al objeto
				$factura->folio=$folio;												
				$factura->id_venta=$id_venta;
				$factura->subtotal=$subtotal;
				$factura->iva=$iva;
				//creamos un objeto venta existente
				$verifica_venta=new ventaExistente($id_venta);						
				//checamos que exista la venta para poder facturar
				if($verifica_venta->existe())
				{	
					//checamos o que sea la misma venta o que no haya facturas para la venta nueva
					if(($venta==$id_venta)||(!$factura->existe_venta()))
					{
						//checamos o que sea el mismo folio o que no haya facturas con el
						if(($folio1==$folio)||(!$factura->existe_folio()))
						{
							//intentamos actualizar la factura
							if($factura->actualiza())		ok();														//actualizacion correcta
							else							fail("Error al modificar la factura.");						//error al actualizar
						}//if es el mismo filio o no existe el folio
						else								fail("Ya existe una factura con este numero de folio.");	//otra factura tiene el numero de folio
					}//if es la misma venta o no hay factura para venta
					else									fail("Ya existe una factura para esta venta.");				//la venta ya tiene factura
				}//if existe venta
				else										fail("La venta que desea facturar no existe.");				//no existe la venta
			}//if existe factura
			else											fail("La factura que desea modificar no existe.");			//no existe la factura
		}//if verifica datos
		else												fail("Faltan datos.");										//datos incompletos
		return;
	}
	//funcion actualizar factura venta
	
	//esta funcion inserta una nota de una venta
	function insertarNota()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_venta']))
		{
			//asignamos valores obtenidos a las variables
			$id_venta=$_REQUEST['id_venta'];
			$nota=new nota_remision($id_venta);
			//crea un objeto de la clase venta con el id
			$verifica_venta=new ventaExistente($id_venta);			
			//checa que exista dicha venta
			if($verifica_venta->existe())
			{
				//checa que no haya otra nota para la venta
				if(!$nota->existe_venta())
				{
					//verifica que no exista la nota
					if(!$nota->existe())
					{
						//intenta inserta
						if($nota->inserta())				ok();														//insercion correcta
						else								fail("Error al guardar la nota.");							//fallo la insercion
					}//if nota no existe
					else 									fail("Ya existe esta nota.");								//la nota con ese id ya existe
				}//if no existe nota de la venta
				else 										fail("Ya existe una nota para esta venta.");				//nota de venta existente
			}//if existe venta
			else 											fail("La venta para la nota no existe.");					//no existe la venta
		}//if verifica datoa
		else												fail("Faltan datos.");										//datos incompletos
		return;
	}
	//funcion insertar nota
	
	function eliminarNota()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_nota']))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_nota'];
			//creamos objeto del tipo nota existente
			$nota=new notaRemisionExistente($id);
			//verificamos que exista la nota
			if($nota->existe())
			{									
				//intentamos borrar la nota
				if($nota->borra())							ok();														//elimina la nota
				else										fail("Error al borrar la nota.");							//fallo el borrado
			}//if existe nota
			else 											fail("La nota que desea eliminar no existe.");				//nota inexistente
		}//if verifica datos
		else fail("faltan datos.");
		return;
	}
	//funcion eliminar nota
	
	
	//esta funcion actualiza los datos de una nota
	function actualizarNota()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_nota']))&&(!empty($_REQUEST['id_venta'])))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_nota'];
			$id_venta=$_REQUEST['id_venta'];
			//creamos objeto venta existente para modificar
			$nota=new notaRemisionExistente($id);									
			//variable para verificar si es el mismo id de venta
			$venta=$nota->id_venta;													
			//verificamos que si exista la nota
			if($nota->existe())
			{
				$nota->id_venta=$id_venta;
				//creamos un objeto venta existente
				$verifica_venta=new ventaExistente($id_venta);						
				//checamos que exista la venta para poder notar
				if($verifica_venta->existe())
				{
					//checamos o que sea la misma venta o que no haya notas para la venta nueva
					if(($venta==$id_venta)||(!$nota->existe_venta()))
					{
						//intentamos actualizamos los datos
						if($nota->actualiza())				ok();														//se actualizo correctamente
						else								fail("Error al modificar la nota.");						//fallo la actualizacion
					}//if misma venta o no hay nota para la venta
					else									fail("Ya existe una nota para esta venta.");				//venta ya con nota
				}//if existe venta
				else										fail("La venta para la nota no existe.");					//no existe venta
			}//if existe nota
			else											fail("La nota que desea modificar no existe.");				//no existe nota
		}//if verifica datos
		else												fail("Faltan datos.");										//datos incompletos
		return;
	}
	//funcion actualizanota
	
	//esta funcion vende un producto, checa las existencias antes de vender
	function vendeProducto()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_producto']))&&(!empty($_REQUEST['existencias']))&&(!empty($_REQUEST['id_sucursal'])))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_producto'];
			$existencias=$_REQUEST['existencias'];
			$id_sucursal=$_REQUEST['id_sucursal'];
			//creamos un objeto tipo detalle_inventario_existente para ver que vendamos el producto en la sucursal
			$detalle_inventario=new detalleInventarioExistente($id,$id_sucursal);
			//creamos un objeto inventario existente para veridicar que vendemos el producto
			$producto=new inventarioExistente($id);
			//verificamos que vendemos este prodcuto
			if($producto->existe())
			{
				//creamos un objeto sucursal
				$verifica_sucursal=new sucursalExistente($id_sucursal);
				//verificamos que exista la sucursal
				if($verifica_sucursal->existe())
				{
					//verificamos que vendemos el producto en la sucursal
					if($detalle_inventario->existe())
					{
						//verificamos que hay existencia
						if(($detalle_inventario->existencias)>0)
						{
							//verificamos que tenemos mas o igual productos que los que queremos vender
							if(($detalle_inventario->existencias)>=$existencias)
							{
								//restamos existencias al inventario
								$detalle_inventario->existencias-=$existencias;
								//intentamos actualizar los datos (vender)
								if($detalle_inventario->actualiza())				ok();													//venta exitosa
								else												fail("Error al agregar los datos");						//error al actualizar
							}//if existencias mas o igual que las que se venden
							else													fail("No puede vender mas producto de el que existe");	//se quiere vender mas de lo que existe
						}//if hay existencias
						else														fail("No hay mas producto, existencias en 0");			//no hay existencia
					}//if producto existe en sucursal
					else															fail("No existe el producto que desea vender");			//producto inexistente
				}//if sucursal existe
				else																fail("La sucursal de la vender no existe.");			//sucursal inexistente
			}//if existe producto
			else 																	fail("El producto que desea vender no existe.");		//producto inexistente
		}//if verifica datos
		else 																		fail("Faltan datos.");									//datos incompletos
		return;
	}
	//funcion vender producto
	
	//esta funcion factura un producto
	function facturaProducto()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_factura']))&&(!empty($_REQUEST['id_producto']))&&(!empty($_REQUEST['cantidad']))&&(!empty($_REQUEST['precio'])))
		{
			//asignamos valores obtenidos a las variables
			$id_factura=$_REQUEST['id_factura'];
			$id_producto=$_REQUEST['id_producto'];
			$cantidad=$_REQUEST['cantidad'];
			$precio=$_REQUEST['precio'];
			//creamos un objeto inventario existente para veridicar que vendemos el producto
			$producto=new inventarioExistente($id_producto);
			//creamos objeto de la clase factura_venta
			$factura=new factura_ventaExistente($id_factura);
			//verificamos que exista la factura
			if($factura->existe())
			{
				//verificamos que vendemos este prodcuto
				if($producto->existe())
				{
					//creamos objeto de la clase detalle_factura
					$produto_facturado=new detalle_factura($id_factura,$id_producto,$cantidad,$precio);
					//verificamos que no exista el producto para esta factura
					if(!$produto_facturado->existe()){
						//intentamos insertar
						if($produto_facturado->inserta())							ok();														//insercion correcta
						else														fail("no se pudo insertar el producto en la factura");		//insercion incorrecta
					}//if existe producto-factura
					else															fail("ya se facturo este producto para esta factura");
				}//if producto existe
				else																fail("No existe el producto que desea facturar");			//producto inexistente
			}//if factura existe
			else																	fail("No existe la factura");								//factura inexistente
		}//if verifica datos
		else 																		fail("Faltan datos.");										//datos incompletos
		return;
	}
	//funcion facturar producto
	
	//esta funcion lista todas las facturas
	function listarFacturasVenta()
	{
		//creamos un objeto de la clase listar con la consulta
		$listar = new listar("select * from factura_venta",array());
		//imprimimos el json
		echo $listar->lista();
		return;
	}
	//funcion listar facturas ventas
	
	//esta funcion lista todas las notas
	function listarNotas()
	{
		//creamos un objeto de la clase listar con la consulta
		$listar = new listar("select * from nota_remision",array());
		//imprimimos el json
		echo $listar->lista();
		return;
	}
	//funcion listar notas
	
	//esta funcion regresa un reporte de ventas por usuario,
	//si se le envian fechas agrega un periodo
	//si se le agrega un id de sucursal tambien lo busca
	function reporteVentasEmpleado(){
		//asignamos los datos recibidos a las variables (en caso de que se reciban)
		$id_sucursal=$_REQUEST['id_sucursal'];
		$de=$_REQUEST['de'];
		$al=$_REQUEST['al'];
		//asignamos variables que seran booleanos para saber si nos enviaron parametros de sucursal y/o periodo
		$sucursal=!empty($id_sucursal);
		$fecha=(!empty($de)&&!empty($al));
		//inicializamos arreglo de periodo vacio
		$params=array();
		//inicializamos la consulta, esta sera final si no se enviaron parametros
		$query="select u.nombre, count(*) as total
				from ventas v natural join usuario u ";
		//verificamos si se enviaron fechas
		if($fecha)
		{
				//agregamos la condicion que cuente los que esten en las fechas
				//si se agrego sucursal lo pone con and, de lo contrario pone el having
				$query.="where date(v.fecha) BETWEEN ? AND ? ";
				//agrega los parametros a la pila
				array_push($params,$de,$al);
		}//if fechas
		$query.=" group by u.nombre ";
		//verificamos el booleano de sucursal
		if($sucursal){
			//agregamos having para que solo cuente los de la sucursal deseada
			$query.=" ,v.sucursal
					having sucursal=? ";
			//agregamos parametro al arreglo
			array_push($params,$id_sucursal);
		}//if sucursal
		//agregamos el ; final
		$query.=";";
		//creamos objeto de la clase listar y le pasamos el arreglo de parametros
		$listar = new listar($query,$params);
		//imprimimos el resultado
		echo $listar->lista();
		return;
	}
	//reporte ventas empleado
	
	
	//esta funcion regresa un reporte de ventas por sucursal,
	//si se le envian fechas agrega un periodo
	function reporteVentasSucursales(){
		//asignamos los datos recibidos a las variables (en caso de que se reciban)
		$de=$_REQUEST['de'];
		$al=$_REQUEST['al'];
		//asignamos variables que seran booleanos para saber si nos enviaron parametros de periodo
		$fecha=(!empty($de)&&!empty($al));
		//inicializamos arreglo de parametros vacio
		$params=array();
		//inicializamos la consulta, esta sera final si no se enviaron parametros
		$query="select s.descripcion ,count(*) AS `total` 
				from ventas v join sucursal `s` on((s.id_sucursal=v.sucursal)) ";
		//verificamos si se enviaron fechas
		if($fecha)
		{
				//agregamos la condicion que cuente las que esten en las fechas
				$query.="where DATE(v.fecha) BETWEEN ? AND ? ";
				//agrega los parametros a la pila
				array_push($params,$de,$al);
		}//if fechas
		//agregamos el final de la query	
		$query.="group by s.descripcion;";
		//creamos objeto de la clase listar y le pasamos el arreglo de parametros
		$listar = new listar($query,$params);
		//imprimimos el resultado
		echo $listar->lista();
		return;
	}
	//reporte ventas sucursal
	
	
	//esta funcion regresa un reporte de ventas,
	//si se le envian fechas agrega un periodo
	//si se le agrega un id de sucursal tambien lo busca
	function reporteVentas()
	{
		//asignamos los datos recibidos a las variables (en caso de que se reciban)
		$id_sucursal=$_REQUEST['id_sucursal'];
		$de=$_REQUEST['de'];
		$al=$_REQUEST['al'];
		//asignamos variables que seran booleanos para saber si nos enviaron parametros de sucursal y/o periodo
		$sucursal=!empty($id_sucursal);
		$fecha=(!empty($de)&&!empty($al));
		//inicializamos arreglo de periodo vacio
		$params=array();
		//inicializamos la consulta, esta sera final si no se enviaron parametros
		$query="SELECT v.id_venta,u.nombre as empleado, c.nombre AS cliente, DATE( v.fecha ) AS fecha, (
				v.subtotal + v.iva
				) AS total
				FROM ventas v
				NATURAL JOIN cliente c
				left join usuario u
				on (v.id_usuario=u.id_usuario) ";
		//verificamos si se enviaron fechas
		if($fecha)
		{
				//agregamos la condicion que cuente los que esten en las fechas
				//si se agrego sucursal lo pone con and, de lo contrario pone el having
				$query.="where date(v.fecha) BETWEEN ? AND ? ";
				//agrega los parametros a la pila
				array_push($params,$de,$al);
		}//if fechas
		
		//verificamos el booleano de sucursal
		if($sucursal){
			//agregamos having para que solo cuente los de la sucursal deseada
			$query.=(($fecha)?" and ":" where ")." sucursal=? ";
			//agregamos parametro al arreglo
			array_push($params,$id_sucursal);
		}//if sucursal
		//agregamos el ; final
		$query.=";";
		//creamos objeto de la clase listar y le pasamos el arreglo de parametros
		$listar = new listar($query,$params);
		//imprimimos el resultado
		echo $listar->lista();
		return;
	}
	//reporte ventas


	/*******************************************************************************
	 REPORTES DEL TIPO "MEJOR X DE LA SUCURSAL Y" O "MEJOR X DE TODAS LAS SUCURSALES"
	*******************************************************************************/

	/*
	*	UTILS PARA LOS REPORTES
	*/ 

	//Obtenemos dos fechas dentro de un rango (semana, mes, año). Se refiere la ultima semana, mes, año.
	function getDateRange($dateInterval){

		$datesArray = array();
		$currentDate = getdate();
		$dateToday = $currentDate['year'].'-'.$currentDate['mon'].'-'.$currentDate['mday'];

		switch($dateInterval)
			{
				case 'semana': 	$fecha = date_create( $dateToday );
						//$fecha = date_create("2010-07-14");
						date_sub($fecha, date_interval_create_from_date_string('7 days'));
						$dateWeekBefore = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateWeekBefore);
						array_push($datesArray, $dateToday);
	
						return($datesArray);

						break;
				/************************************************************************/

				case 'mes':	$fecha = date_create( $dateToday );
						//$fecha = date_create("2010-07-14");
						date_sub($fecha, date_interval_create_from_date_string('1 month'));
						$dateWeekBefore = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateWeekBefore);
						array_push($datesArray, $dateToday);

						return($datesArray);

						break;
				/************************************************************************/

				case 'año':	$fecha = date_create( $dateToday );
						//$fecha = date_create("2010-07-14");
						date_sub($fecha, date_interval_create_from_date_string('1 year'));
						$dateWeekBefore = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateWeekBefore);
						array_push($datesArray, $dateToday);

						return($datesArray);

						break;
				/************************************************************************/

				default:	return false;
			}

	}

	/*
	*	VENDEDOR MAS PRODUCTIVO EN GENERAL
	*/

	function vendedorMasProductivo(){


		$dateRange = "";
		$params = array();

		$qry_select = "SELECT `ventas`.`id_usuario`, `usuario`.`nombre`, SUM(`ventas`.`subtotal`) AS `Vendido` FROM `ventas`, `usuario` WHERE `ventas`.`id_usuario` = `usuario`.`id_usuario` ";				


		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}
			
			
		}


		//Si existen los rangos de fechas, agregamos una linea al query para filtrar los resultados dentro de ese rango
		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']) )
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `id_usuario` ORDER BY `Vendido` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;

	}

	/*
	*	PRODUCTO MAS VENDIDO EN GENERAL
	*/	
	
	function productoMasVendido(){
		
		
		$dateRange = "";
		$params = array();

			
		$qry_select = " SELECT `inventario`.`denominacion` AS `nombre`, SUM(`detalle_venta`.`cantidad`) AS `Cantidad` FROM `inventario`, `detalle_venta`, `ventas`  WHERE `inventario`.`id_producto` = `detalle_venta`.`id_producto` AND `detalle_venta`.`id_venta` = `ventas`.`id_venta`";


		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}
			
			
		}


		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']))
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `detalle_venta`.`id_producto` ORDER BY `Cantidad` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;

	}

	/*
	*	SUCURSAL QUE VENDE MAS 
	*/

	function sucursalVentasTop(){
		
		$dateRange = "";
		$params = array();

			
		$qry_select = " SELECT `sucursal`.`descripcion` AS `nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `sucursal` WHERE `sucursal`.`id_sucursal` = `ventas`.`sucursal` ";

		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}

			
		}

		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']))
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `ventas`.`sucursal` ORDER BY `Cantidad` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;
		
	}

	/*
	*	CLIENTE QUE COMPRA MAS
	*/

	function clienteComprasTop(){
	
		$dateRange = "";
		$params = array();

			
		$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` ";

		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}
			
			
		}


		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']))
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `ventas`.`id_cliente` ORDER BY `Cantidad` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;
	}


	/*
	*	VENDEDOR MAS PRODUCTIVO EN UNA SUCURSAL ESPECIFICA
	*/

	function vendedorMasProductivoSucursal(){

		if ( !isset($_REQUEST['id_sucursal']) )
		{
			fail("Faltan parametros");
			return;
		}
		
		$id_sucursal = $_REQUEST['id_sucursal'];
		$dateRange = "";
		$params = array($id_sucursal);

		$qry_select = "SELECT `ventas`.`id_usuario`, `usuario`.`nombre`, SUM(`ventas`.`subtotal`) AS `Vendido` FROM `ventas`, `usuario` WHERE `ventas`.`id_usuario` = `usuario`.`id_usuario` AND `ventas`.`sucursal` = ?";				


		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}
			
			
		}

		//Si existen los rangos de fechas, agregamos una linea al query para filtrar los resultados dentro de ese rango
		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']))
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `ventas`.`id_usuario` ORDER BY `Vendido` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;

	}

	/*
	*	PRODUCTO MAS VENDIDO EN UNA SUCURSAL
	*/

	function productoMasVendidoSucursal(){
		

		if ( !isset($_REQUEST['id_sucursal']) )
		{
			fail("Faltan parametros");
			return;
		}
		
		$id_sucursal = $_REQUEST['id_sucursal'];
		
		$dateRange = "";
		$params = array($id_sucursal);

			
		$qry_select = " SELECT `inventario`.`denominacion`, SUM(`detalle_venta`.`cantidad`) AS `Cantidad` FROM `inventario`, `detalle_venta`, `ventas`  WHERE `inventario`.`id_producto` = `detalle_venta`.`id_producto` AND `detalle_venta`.`id_venta` = `ventas`.`id_venta` AND `ventas`.`sucursal` = ?";


		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}

			
		}

		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']) )
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `detalle_venta`.`id_producto` ORDER BY `Cantidad` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;

	}

	
	/*
	*	CLIENTE QUE COMPRA MAS EN UNA SUCURSAL
	*/

	function clienteComprasTopSucursal(){
	
		if ( !isset($_REQUEST['id_sucursal']) )
		{
			fail("Faltan parametros");
			return;
		}
		
		$id_sucursal = $_REQUEST['id_sucursal'];
		
		$dateRange = "";
		$params = array($id_sucursal);

			
		$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` AND `ventas`.`sucursal` = ? ";


		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}

			
		}

		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']) )
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `ventas`.`id_cliente` ORDER BY `Cantidad` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;
	}


	/***************************************************************************************************
				REPORTES PARA GRAFICAS
	***************************************************************************************************/

	/*
	*	UTILS PARA LOS REPORTES
	*/ 

	//Obtenemos dos fechas dentro de un rango (semana, mes, año). Se refiere la ultima semana, mes, año.
	function getDateRangeGraphics($dateInterval){

		$datesArray = array();
		$params = array();
		$currentDate = getdate();
		$dateToday = $currentDate['year'].'-'.$currentDate['mon'].'-'.$currentDate['mday'];


		//**** ESTE SWITCH NOS REGRESA UN ARREGLO CON TODAS LAS FECHAS QUE QUEREMOS ANALIZAR******//
		switch($dateInterval)
			{
				case 'semana': 	$fecha = date_create( $dateToday );
						$today = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $today);
						array_push($datesArray, $today);
						//$fecha = date_create("2010-07-14");
						date_sub($fecha, date_interval_create_from_date_string('1 day')); //A la fecha de hoy le quitamos un dia
						$dateDayBefore = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateDayBefore);
						array_push($datesArray, $dateDayBefore);						

						if ( $currentDate['wday'] == 0)
						{
							$weekControl = 7;
						}
						else
						{
							$weekControl = $currentDate['wday'];
						}
						//Obtenemos las fechas desde hoy, hasta el inicio de semana (Lunes) y lo guardamos en un arreglo
						for ( $i=2; $i < $weekControl ; $i++ )
						{
							$fecha = date_create( $dateToday );
							date_sub( $fecha, date_interval_create_from_date_string( $i.' days') ); //A la fecha de hoy le vamos quitando i dias para formar un arreglo de fechas
							$dateDayBefore = date_format($fecha, 'Y-m-d');
							array_push($datesArray, $dateDayBefore);
							array_push($datesArray, $dateDayBefore);
						}
						//var_dump($datesArray);
	
						//return($datesArray);

						break;
				/************************************************************************/

				case 'mes':	$fecha = date_create( $dateToday );
						$today = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $today);
						array_push($datesArray, $today);
						//$fecha = date_create("2010-07-14");
						date_sub($fecha, date_interval_create_from_date_string('1 day')); //A la fecha de hoy le quitamos un dia
						$dateDayBefore = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateDayBefore);
						array_push($datesArray, $dateDayBefore);						

						/*if ( $currentDate['wday'] == 0)
						{
							$weekControl = 7;
						}
						else
						{
							$weekControl = $currentDate['wday'];
						}*/
						$monthControl = $currentDate['mday'];
						//Obtenemos las fechas desde hoy, hasta el inicio de semana (Lunes) y lo guardamos en un arreglo
						for ( $i=2; $i < $monthControl ; $i++ )
						{
							$fecha = date_create( $dateToday );
							date_sub( $fecha, date_interval_create_from_date_string( $i.' days') ); //A la fecha de hoy le vamos quitando i dias para formar un arreglo de fechas
							$dateDayBefore = date_format($fecha, 'Y-m-d');
							array_push($datesArray, $dateDayBefore);
							array_push($datesArray, $dateDayBefore);
						}
						//var_dump($datesArray);
	
						//return($datesArray);

						break;

				/************************************************************************/

				case 'año':	//$fecha = date_create("2010-07-14");

						$fecha = date_create($currentDate['year'].'-01-01'); //Ponemos el inicio de mes para que al substraer meses, regrese el inicio de cada mes
						//date_sub($fecha, date_interval_create_from_date_string($i.' months'));
						$dateMonth = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateMonth);

						for ($i=2 ; $i < $currentDate['mon']+1 ; $i++)
						{
						$fecha = date_create($currentDate['year'].'-'.$i.'-01'); //Ponemos el inicio de mes para que al substraer meses, regrese el inicio de cada mes
						//date_sub($fecha, date_interval_create_from_date_string($i.' months'));
						$dateMonth = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateMonth);
						array_push($datesArray, $dateMonth);
						}

						//$fechaInicioMes = date_create($currentDate['year'].'-'.$currentDate['mon'].'-01');
						//array_push($datesArray, date_format($fechaInicioMes, 'Y-m-d'));

						$fecha = date_create( $dateToday );
						array_push($datesArray, date_format($fecha, 'Y-m-d')); //Agregamos la fecha de hoy porque sacara lo que lleva del mes actual
						
						

						//return($datesArray); //Regresa todas las fechas del inicio de cada mes, y la fecha actual

						break;
				/************************************************************************/

				default:	$datesArray = false;

			}

		if( $datesArray != false )
		{
				$count = count($datesArray);
				

				for( $i=0; $i < $count ; $i++)
				{
					$date = $datesArray[ $i ];
					array_push($params, $date);
			
				}

				$newcount = $count/2;

				/*
				*	FORMAMOS EL QUERY PARA LA SEMANA
				*/
				if ( $dateInterval == 'semana')
				{
					$qry_select = " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
	
					for( $j=0; $j < $newcount - 1 ; $j++)
					{
						$qry_select .= " OR DATE( `ventas`.`fecha` ) BETWEEN ? AND ? ";					
					}

					$qry_select .= "GROUP BY DAYOFWEEK(`ventas`.`fecha` )";
				}

				/*
				*	FORMAMOS EL QUERY PARA EL MES
				*/

				if ( $dateInterval == 'mes')
				{
					$qry_select = " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
	
					for( $j=0; $j < $newcount - 1 ; $j++)
					{
						$qry_select .= " OR DATE( `ventas`.`fecha` ) BETWEEN ? AND ? ";					
					}

					$qry_select .= "GROUP BY DAYOFMONTH(`ventas`.`fecha` )";


				}
				//var_dump($params);

				if ($dateInterval == 'año')
				{
					$qry_select = " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";

					for( $j=0; $j < $count/2-1; $j++)
					{
						$qry_select .= " OR DATE( `ventas`.`fecha` ) BETWEEN ? AND ? ";					
					}

					$qry_select .= "GROUP BY MONTH(`ventas`.`fecha` )";
				}

				array_push($params, $qry_select);
				return $params;

	}
	else
	{
		return false;
	}

}





	/*
	*	GRAFICA DE VENTAS AL CONTADO EN GENERAL
	*
	*	params:
	*		'dateRange' puede ser ['semana', 'mes', 'año']
	*/
	function graficaVentasContado(){

		
		//$array = getDateRangeGraphics('semana');
		$params = array();

		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
			$qry_select = getDateRangeGraphics($dateInterval);	
			
			//La clausula SELECT, FROM y WHERE la agregamos nosotros, esta es la que cambia
			switch( $dateInterval ) 
			{
				case 'semana'	: $qry = "SELECT DAYOFWEEK(`ventas`.`fecha`) AS `x`, SUM(`subtotal`) AS `y`, DAYNAME(`ventas`.`fecha`) AS `label` FROM `ventas` WHERE `ventas`.`tipo_venta` = 1 ";
						break;
				case 'mes'	: $qry = "SELECT DAYOFMONTH(`ventas`.`fecha`) AS `x`, SUM(`subtotal`) AS `y`, DAYOFMONTH(`ventas`.`fecha`) AS `label` FROM `ventas` WHERE `ventas`.`tipo_venta` = 1 ";
						break;
				case 'año'	: $qry = "SELECT MONTH(`ventas`.`fecha`) AS `x`, SUM(`subtotal`) AS `y`, MONTHNAME(`ventas`.`fecha`) AS `label` FROM `ventas` WHERE `ventas`.`tipo_venta` = 1 ";
						break;
				default: break;
			}




			
			if ( $qry_select != false )
			{			
				//echo $qry_select;

				//Usamos arraypop para enviarle la consulta ya que el arreglo tiene la consulta en su ultimo dato
				$listar = new listar( $qry.array_pop($qry_select), $qry_select);
				echo $listar->lista();

				return;
				
			}
			else
			{
				fail("Bad Request: dateRange");
				return;
			}



			
		}
		else
		{
			fail('Faltan parametros');
			return;
		}

	}
	
?>
