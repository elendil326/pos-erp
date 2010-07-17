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


	



?>
