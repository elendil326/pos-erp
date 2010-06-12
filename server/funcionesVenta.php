<?	include_once("AddAllClass.php");
	
	
	function addFactura(){
		if((isset($_REQUEST['folio']))&&(isset($_REQUEST['id_venta']))){
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
	
	function deleteFactura(){
		if(isset($_REQUEST['id_factura'])){
			$id=$_REQUEST['id_factura'];
			$factura=new factura_venta_existente($id);
			if($factura->existe()){												//verifica que si exista la factura
				if($factura->borra())					ok();					//elimina la factura
				else									fail("Error al borrar la factura.");
			}else 										fail("La factura que desea eliminar no existe.");
		}else fail("faltan datos.");
		return;
	}
	
	function cambiaDatos(){
		if((isset($_REQUEST['id_factura']))&&(isset($_REQUEST['folio']))&&(isset($_REQUEST['id_venta']))){
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
	
	if(isset($_REQUEST['method']))
	{
		switch($_REQUEST["method"]){
			case "addFactura" : 			addfactura(); break;
			case "deleteFactura" : 		deletefactura(); break;
			case "cambiaDatos" : 			cambiaDatos(); break;
			default: echo "-1"; 
		}
	}
?>