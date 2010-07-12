<?php	


	function comprarProducto($id_producto, $existenciass, $id_Sucursal){
		
			$id=$id_producto;																				//asigna valores a variables
			$existencias=$existenciass;
			$id_sucursal=$id_Sucursal;
			$detalle_inventario=new detalle_inventario_existente($id,$id_sucursal);										//creamos un objeto de la clase detalle_inventario que vamos a modificar
			$producto=new inventario_existente($id);																	//creamos un objeto de la clase inventario para ver si existe
			if($producto->existe()){																					//checamos si existe el producto en inventario
				$verifica_sucursal=new sucursal_existente($id_sucursal);												//creamos un objeto de la clase sucursal para veridicar que tambien exista
				if($verifica_sucursal->existe()){																		//checamos que exista la sucursal
					if($detalle_inventario->existe()){																//Verificamos si ya existe el registro del producto en la sucursal
						$detalle_inventario->existencias=$detalle_inventario->existencias + $existencias;					//sumamos el productoentrante a la existencia
						if($detalle_inventario->actualiza())			return true;											//actualizamos y verificamos que realice la actualizacion
						else											return "Error al agregar los datos en el inventario";
					}else{																								//generamos el registro podiendo en ceros el precio y el minimo
						$detalle_inventario->id_producto=$id;
						$detalle_inventario->id_sucursal=$id_sucursal;
						$detalle_inventario->existencias=$existencias;
						$detalle_inventario->precio_venta=0;
						$detalle_inventario->minimo=0;
						if($detalle_inventario->inserta())				return true;											//insertamos y verificamos que realize la insercion
						else											return "Error al agregar nuevo producto en el inventario";
					}
				}else													return "La sucursal de la compra no existe.";
			}else 														return "El producto que desea comprar no existe.";
		
	}
	
	function eliminarProductoDetalle_compra(){
		$id_compra=$_REQUEST['id_compra'];
		$id_producto=$_REQUEST['id_producto'];
		$cantidad=$_REQUEST['cantidad'];
		$precio =$_REQUEST['precio'];
		$id_proveedor= $_REQUEST['id_proveedor'];
		
		$detalle_compra= new detalle_compra($id_compra,$id_producto,$cantidad,$precio);
		
		if($detalle_compra->borra()){
			$compraExistente = new compraExistente($id_compra);
			$compraExistente->id_compra=$id_compra;
			$detalle_compra=$compraExistente->detalle_compra($id_compra);
			if(actualizaCabeceraCompra($detalle_compra,$id_compra,$id_proveedor)){
				echo "{ success : true , \"datos\" : ".json_encode($detalle_compra)."}";
			}else{
				echo "{success: false}";	
			}
		}else{
			echo "{success: false}";
		}
	}
	
	function actualizarCantidadProductoDetCompra(){
		$id_compra=$_REQUEST['id_compra'];
		$id_producto=$_REQUEST['id_producto'];
		$cantidad=$_REQUEST['cantidad'];
		$precio =$_REQUEST['precio'];
		$id_proveedor= $_REQUEST['id_proveedor'];
		
		$detalle_compra= new detalle_compra($id_compra,$id_producto,$cantidad,$precio);
	
		if($detalle_compra->actualiza()){
			$compraExistente = new compraExistente($id_compra);
			$compraExistente->id_compra=$id_compra;
			$detalle_compra=$compraExistente->detalle_compra($id_compra);
			if(actualizaCabeceraCompra($detalle_compra,$id_compra,$id_proveedor)){
				echo "{ success : true , \"datos\" : ".json_encode($detalle_compra)."}";
			}else{
				echo "{success: false}";	
			}
		}else{
			echo "{success: false}";
		}
	}//fin modificarCantidad
	
	function actualizaCabeceraCompra($id_compra,$id_proveedor,$subtotalCompra){
		
		$iva = new impuesto_existente(5);//en mi bd el iva es el id 5
		$iva->id_impuesto=5;
		
		$iva_valor=$iva->valor;
		
		$compra = new compraExistente($id_compra);
		$compra->id_compra=$id_compra;
		$compra->id_proveedor=$id_proveedor;
		$compra->subtotal=$subtotalCompra;
		$compra->iva=($iva_valor/100) * $subtotalCompra;
		
		if($compra->actualiza()){
			return true;
		}else{
			return false;
		}
	}//actualiza cabecera
	
	function listarCompras(){
		$listar = new listar("SELECT id_compra ,id_proveedor,tipo_compra,fecha,subtotal,iva,sucursal,id_usuario,(iva + subtotal) as total FROM `compras`",array());
		echo $listar->lista();
		return ;
	}
	function mostrarDetalleCompra(){
		$id_proveedor=$_REQUEST['id_proveedor'];
		$id_compra=$_REQUEST['id_compra'];
		$compra = new compra($id_compra);
		$compra->id_compra=$id_compra;
		if(count($detalle_compra=$compra->detalle_compra($id_compra))>0){
			echo "{ success: true , \"datos\" : ".json_encode($detalle_compra)."}";	
		}else{
			echo "{ success: false }";
		}
	}
	
	function insertarCompra(){
		$sucursal=$_SESSION['sucursal_id'];
		$id_usuario=$_SESSION['id_usuario'];
		$objson = $_REQUEST['jsonItems'];
		$arregloItems = json_decode($objson,true);
		$dim = count($arregloItems);
		$id_proveedor=$_REQUEST['id_proveedor'];
		$tipo_compra=$_REQUEST['tipo_compra'];
		
		$compra = new compra($id_proveedor,$tipo_compra,$sucursal,$id_usuario);
		$id_compra=0;
		if($compra->inserta()){
			$id_compra = $compra->id_compra;
			$succes = array(); array_push($succes,false,"");
			$subtotalCompra = 0;
			for($i=0; $i < $dim; $i++){
				
				$inventario = ( $arregloItems[$i]['pesoArp'] + $arregloItems[$i]['kgR'] ) * $arregloItems[$i]['nA'];
				
				$pesoArpReal = $arregloItems[$i]['pesoArp'] + $arregloItems[$i]['kgR'];
				
				$resul = agregarProductoDetalle_compra($id_compra, $arregloItems[$i]['id'],$arregloItems[$i]['kgTot'],$arregloItems[$i]['prKg'] , $inventario, $sucursal, $arregloItems[$i]['pesoArp'], $pesoArpReal);
				
				$subtotalCompra += $arregloItems[$i]['subtot'];
				
				$succes[0]=$resul[0];
				$succes[1].=$resul[1];
			}
			if (!actualizaCabeceraCompra($id_compra,$id_proveedor,$subtotalCompra)) $succes[1].=" -- No se modifico el subtotal e iva de la compra";
			
			echo "{ success : true , reason :'".$succes[1]."' , final: ".$succes[0]." }";//El proceso de insertar una compra se llevo con exito  { success : true, datos : [{"sucursal_id":"1"}]}
		}else{
			echo "{ success : false , reason : No se pudo insertar la cabecera de la compra ni sus detalles }";
		}
	}//fin insertar
	
	function agregarProductoDetalle_compra($idCompra , $idProducto, $cantidad , $precio , $paraInventario, $id_sucursal, $peso_arpillaPagado,$peso_arpillaReal ){
		
		$resultado = array();
		array_push($resultado,false,"");
		
		$detalle_compra= new detalle_compra($idCompra,$idProducto,$cantidad,$precio,$peso_arpillaPagado,$peso_arpillaReal);
		if($detalle_compra->inserta()){//<---aqui llamar a comprarProducto
			$res = comprarProducto($idProducto, $paraInventario, $id_sucursal);
			if ( $res == true ){
				$resultado[0] = true;
			}else{
				$resultado[0]= false;
				$resultado[1] = " -- ".$res;
			}
		}else{
			$resultado[0] = false;
			$resultado[1] = " -- No se pudo insertar el producto con ID: '".$idProducto."' de la compra.";
		}
		return $resultado;
	}
	
	
	
	
	
	function eliminarCompra(){
		$id_compra=$_REQUEST['id_compra'];

		$compra= new compraExistente($id_compra);
		$compra->id_compra=$id_compra;
		if($compra->borra()){
			echo "{success: true}";
		}else{
			echo "{success: false}";
		}
	}	
	
	function listarFacturasCompra(){
		$listar = new listar("select * from factura_compra",array());
		echo $listar->lista();
		return;
	}
	
	function insertarFacturaCompra(){
		if((!empty($_REQUEST['folio']))&&(!empty($_REQUEST['id_compra']))){
			$folio=$_REQUEST['folio'];
			$id_compra=$_REQUEST['id_compra'];
			$factura=new factura_compra($folio,$id_compra);
			$verifica_compra=new compraExistente($id_compra);		//crea un objeto de la clase compra con el id
			if($verifica_compra->existe()){						 	//checa que exista dicha compra
				if(!$factura->existe_compra()){						//checa que no haya otra factura para la compra
					if(!$factura->existe_folio()){					//checa que ninguna factura tenga ese folio
						if(!$factura->existe()){					//verifica que no exista la factura
							if($factura->inserta()){		ok();	//inserta
							}else							fail("Error al guardar la factura.");
						}else 								fail("Ya existe esta factura.");
					}else 									fail("Ya existe una factura con este numero de folio.");
				}else 										fail("Ya existe una factura para esta compra.");
			}else 											fail("La compra que desea facturar no existe.");
		}else												fail("Faltan datos.");
		return;
	}
	
	function eliminarFacturaCompra(){
		if(!empty($_REQUEST['id_factura'])){
			$id=$_REQUEST['id_factura'];
			$factura=new factura_compraExistente($id);
			if($factura->existe()){												//verifica que si exista la factura
				if($factura->borra())					ok();					//elimina la factura
				else									fail("Error al borrar la factura.");
			}else 										fail("La factura que desea eliminar no existe.");
		}else fail("faltan datos.");
		return;
	}
	
	function actualizarFacturaCompra(){
		if((!empty($_REQUEST['id_factura']))&&(!empty($_REQUEST['folio']))&&(!empty($_REQUEST['id_compra']))){
			$id=$_REQUEST['id_factura'];
			$folio=$_REQUEST['folio'];
			$id_compra=$_REQUEST['id_compra'];
			$factura=new factura_compraExistente($id);
			$compra=$factura->id_compra;											//variable para verificar si es el mismo id de compra
			$folio1=$factura->folio;												//variable para verificar si es el mismo folio
			if($factura->existe()){													//verificamos que si exista la factura
				$factura->folio=$folio;												//le asignamos los valores al objeto
				$factura->id_compra=$id_compra;
				$verifica_compra=new compraExistente($id_compra);					//creamos un objeto compra existente
				if($verifica_compra->existe()){										//checamos que exista para poder facturar
					if(($compra==$id_compra)||(!$factura->existe_compra())){		//checamos o que sea la misma compra o que no haya facturas para la compra nueva
						if(($folio1==$folio)||(!$factura->existe_folio())){			//checamos o que sea el mismo folio o que no haya facturas con el
							if($factura->actualiza())		ok();					//actualizamos los datos
							else							fail("Error al modificar la factura.");
						}else								fail("Ya existe una factura con este numero de folio.");
					}else									fail("Ya existe una factura para esta compra.");
				}else										fail("La compra que desea facturar no existe.");
			}else											fail("La factura que desea modificar no existe.");
		}else												fail("Faltan datos.");
		return;
	}
	
	function reporteCompra(){
		if(!empty($_REQUEST['id_compra'])){
			$id=$_REQUEST['id_compra'];
			$query="SELECT IF( c.tipo_compra =1,  'Contado',  'Credito' ) AS  'Tipo', DATE( c.fecha ) AS  'Fecha', c.subtotal AS  'Subtotal', c.iva AS  'Iva', (
					c.subtotal + c.iva
					) AS  'Total', p.rfc AS  'RFC', p.nombre AS  'Nombre'
					FROM compras c
					NATURAL JOIN proveedor p
					where c.id_compra=?"; 
			$listar = new listar($query,array($id));
			$compra=$listar->lista_datos("compra");
			$listar->query="select * , (precio*cantidad) as 'Subtotal' from detalle_compra where id_compra=?";
			$detalles=$listar->lista_datos("detalle_compra");
			ok_datos("$compra , $detalles");
			return;
		}else 											fail("Faltan datos.");
	}
	
	function reporteFacturaCompra(){
		if(!empty($_REQUEST['id_factura'])){
			$id=$_REQUEST['id_factura'];
			$query="SELECT f.folio, IF( c.tipo_compra =1,  'Contado',  'Credito' ) AS  'Tipo', c.subtotal AS  'Subtotal', c.iva AS  'Iva', (
					c.subtotal + c.iva
					) AS  'Total', p.rfc AS  'RFC', p.nombre AS  'Nombre'
					FROM factura_compra f
					NATURAL JOIN compras c
					NATURAL JOIN proveedor p
					WHERE f.id_factura =?"; 
			$listar_factura = new listar($query,array($id));
			$factura=$listar_factura->lista_datos("factura");
			$fac=new factura_compraExistente($id);
			$query="select * , (precio*cantidad) as 'Subtotal' from detalle_compra where id_compra=?";
			$listar_detalle = new listar($query,array($fac->id_compra));
			$detalles=$listar_factura->lista_datos("detalle_factura");
			ok_datos("$factura , $detalles");
			return;
		}else 											fail("Faltan datos.");
	}
	

?>