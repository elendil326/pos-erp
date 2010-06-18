<?php	include_once("AddAllClass.php");
	
	
	function addFactura(){
		if((!empty($_REQUEST['folio']))&&(!empty($_REQUEST['id_compra']))){
			$folio=$_REQUEST['folio'];
			$id_compra=$_REQUEST['id_compra'];
			$factura=new factura_compra($folio,$id_compra);
			$verifica_compra=new compra_existente($id_compra);		//crea un objeto de la clase compra con el id
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
	
	function deleteFactura(){
		if(!empty($_REQUEST['id_factura'])){
			$id=$_REQUEST['id_factura'];
			$factura=new factura_compra_existente($id);
			if($factura->existe()){												//verifica que si exista la factura
				if($factura->borra())					ok();					//elimina la factura
				else									fail("Error al borrar la factura.");
			}else 										fail("La factura que desea eliminar no existe.");
		}else fail("faltan datos.");
		return;
	}
	
	function cambiaDatosFactura(){
		if((!empty($_REQUEST['id_factura']))&&(!empty($_REQUEST['folio']))&&(!empty($_REQUEST['id_compra']))){
			$id=$_REQUEST['id_factura'];
			$folio=$_REQUEST['folio'];
			$id_compra=$_REQUEST['id_compra'];
			$factura=new factura_compra_existente($id);
			$compra=$factura->id_compra;											//variable para verificar si es el mismo id de compra
			$folio1=$factura->folio;												//variable para verificar si es el mismo folio
			if($factura->existe()){													//verificamos que si exista la factura
				$factura->folio=$folio;												//le asignamos los valores al objeto
				$factura->id_compra=$id_compra;
				$verifica_compra=new compra_existente($id_compra);					//creamos un objeto compra existente
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
	
	function compraProducto(){
		if((!empty($_REQUEST['id_producto']))&&(!empty($_REQUEST['existencias']))&&(!empty($_REQUEST['id_sucursal']))){
			$id=$_REQUEST['id_producto'];
			$existencias=$_REQUEST['existencias'];
			$id_sucursal=$_REQUEST['id_sucursal'];
			$detalle_inventario=new detalle_inventario_existente($id,$id_sucursal);
			$producto=new inventario_existente($id);
			if($producto->existe()){
				$verifica_sucursal=new sucursal_existente($id_sucursal);
				if($verifica_sucursal->existe()){
					if($detalle_inventario->existe()){
						$detalle_inventario->existencias=$detalle_inventario->existencias+$existencias;
						if($detalle_inventario->actualiza())			ok();
						else											fail("Error al agregar los datos");
					}else{
						$detalle_inventario->id_producto=$id;
						$detalle_inventario->existencias=$existencias;
						$detalle_inventario->id_sucursal=$id_sucursal;
						if($detalle_inventario->inserta())				ok();
						else											fail("Error al guardar los datos");
					}
				}else													fail("La sucursal de la compra no existe.");
			}else 														fail("El producto que desea comprar no existe.");
		}else 															fail("Faltan datos.");
		return;
	}
	
	function listarFacturas(){
		$listar = new listar("select * from factura_compra",array());
		echo $listar->lista();
		return;
	}
	
	if(!empty($_REQUEST['method']))
	{
		switch($_REQUEST["method"]){
			case "addFactura" : 			addfactura(); break;
			case "deleteFactura" : 			deletefactura(); break;
			case "cambiaDatosFactura" : 	cambiaDatosFactura(); break;
			case "compraProducto" : 		compraProducto(); break;
			case "listarFacturas" : 		listarFacturas(); break;
			default: echo "-1"; 
		}
	}
?>