<?php	include_once("AddAllClass.php");
	
	
	function insertarFacturaVenta(){
		if((!empty($_REQUEST['folio']))&&(!empty($_REQUEST['id_venta']))){
			$folio=$_REQUEST['folio'];
			$id_venta=$_REQUEST['id_venta'];
			$factura=new factura_venta($folio,$id_venta);
			$verifica_venta=new venta_existente($id_venta);			//crea un objeto de la clase venta con el id
			if($verifica_venta->existe()){						 	//checa que exista dicha venta
				if(!$factura->existe_venta()){						//checa que no haya otra factura para la venta
					if(!$factura->existe_folio()){					//checa que ninguna factura tenga ese folio
						if(!$factura->existe()){					//verifica que no exista la factura
							if($factura->inserta()){		ok();	//inserta
							}else							fail("Error al guardar la factura.");
						}else 								fail("Ya existe esta factura.");
					}else 									fail("Ya existe una factura con este numero de folio.");
				}else 										fail("Ya existe una factura para esta venta.");
			}else 											fail("La venta que desea facturar no existe.");
		}else												fail("Faltan datos.");
		return;
	}
	
	function eliminarFacturaVenta(){
		if(!empty($_REQUEST['id_factura'])){
			$id=$_REQUEST['id_factura'];
			$factura=new factura_venta_existente($id);
			if($factura->existe()){												//verifica que si exista la factura
				if($factura->borra())					ok();					//elimina la factura
				else									fail("Error al borrar la factura.");
			}else 										fail("La factura que desea eliminar no existe.");
		}else fail("faltan datos.");
		return;
	}
	
	function actualizarFacturaVenta(){
		if((!empty($_REQUEST['id_factura']))&&(!empty($_REQUEST['folio']))&&(!empty($_REQUEST['id_venta']))){
			$id=$_REQUEST['id_factura'];
			$folio=$_REQUEST['folio'];
			$id_venta=$_REQUEST['id_venta'];
			$factura=new factura_venta_existente($id);								//creamos objeto venta existente para modificar
			$venta=$factura->id_venta;												//variable para verificar si es el mismo id de venta
			$folio1=$factura->folio;												//variable para verificar si es el mismo folio
			if($factura->existe()){													//verificamos que si exista la factura
				$factura->folio=$folio;												//le asignamos los valores al objeto
				$factura->id_venta=$id_venta;
				$verifica_venta=new venta_existente($id_venta);						//creamos un objeto venta existente
				if($verifica_venta->existe()){										//checamos que exista la venta para poder facturar
					if(($venta==$id_venta)||(!$factura->existe_venta())){			//checamos o que sea la misma venta o que no haya facturas para la venta nueva
						if(($folio1==$folio)||(!$factura->existe_folio())){			//checamos o que sea el mismo folio o que no haya facturas con el
							if($factura->actualiza())		ok();					//actualizamos los datos
							else							fail("Error al modificar la factura.");
						}else								fail("Ya existe una factura con este numero de folio.");
					}else									fail("Ya existe una factura para esta venta.");
				}else										fail("La venta que desea facturar no existe.");
			}else											fail("La factura que desea modificar no existe.");
		}else												fail("Faltan datos.");
		return;
	}
	function insertarNota(){
		if(!empty($_REQUEST['id_venta'])){
			$id_venta=$_REQUEST['id_venta'];
			$nota=new nota_remision($id_venta);
			$verifica_venta=new venta_existente($id_venta);			//crea un objeto de la clase venta con el id
			if($verifica_venta->existe()){						 	//checa que exista dicha venta
				if(!$nota->existe_venta()){							//checa que no haya otra nota para la venta
					if(!$nota->existe()){							//verifica que no exista la nota
						if($nota->inserta()){			ok();		//inserta
						}else							fail("Error al guardar la nota.");
					}else 								fail("Ya existe esta nota.");
				}else 									fail("Ya existe una nota para esta venta.");
			}else 										fail("La venta para la nota no existe.");
		}else											fail("Faltan datos.");
		return;
	}
	
	function eliminarNota(){
		if(!empty($_REQUEST['id_nota'])){
			$id=$_REQUEST['id_nota'];
			$nota=new nota_remision_existente($id);
			if($nota->existe()){												//verifica que si exista la nota
				if($nota->borra())						ok();					//elimina la nota
				else									fail("Error al borrar la nota.");
			}else 										fail("La nota que desea eliminar no existe.");
		}else fail("faltan datos.");
		return;
	}
	
	function actualizarNota(){
		if((!empty($_REQUEST['id_nota']))&&(!empty($_REQUEST['id_venta']))){
			$id=$_REQUEST['id_nota'];
			$id_venta=$_REQUEST['id_venta'];
			$nota=new nota_remision_existente($id);									//creamos objeto venta existente para modificar
			$venta=$nota->id_venta;													//variable para verificar si es el mismo id de venta
			if($nota->existe()){													//verificamos que si exista la nota
				$nota->id_venta=$id_venta;
				$verifica_venta=new venta_existente($id_venta);						//creamos un objeto venta existente
				if($verifica_venta->existe()){										//checamos que exista la venta para poder notar
					if(($venta==$id_venta)||(!$nota->existe_venta())){				//checamos o que sea la misma venta o que no haya notas para la venta nueva
						if($nota->actualiza())				ok();					//actualizamos los datos
						else								fail("Error al modificar la nota.");
					}else									fail("Ya existe una nota para esta venta.");
				}else										fail("La venta para la nota no existe.");
			}else											fail("La nota que desea modificar no existe.");
		}else												fail("Faltan datos.");
		return;
	}
	
	function vendeProducto(){
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
							if(($detalle_inventario->existencias)>0){
								if(($detalle_inventario->existencias)>$existencias){
									$detalle_inventario->existencias-=$existencias;
									if($detalle_inventario->actualiza())			ok();
									else											fail("Error al agregar los datos");
								}else												fail("No puede vender mas producto de el que existe");
							}else													fail("No hay mas producto, existencias en 0");
						}else														fail("No existe el producto que desea vender");
				}else																fail("La sucursal de la vender no existe.");
			}else 																	fail("El producto que desea vender no existe.");
		}else 																		fail("Faltan datos.");
		return;
	}
	
	
	function listarFacturasVenta(){
		$listar = new listar("select * from factura_venta",array());
		echo $listar->lista();
		return;
	}
	function listarNotas(){
		$listar = new listar("select * from nota_remision",array());
		echo $listar->lista();
		return;
	}
?>