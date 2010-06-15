<?	include_once("AddAllClass.php");
	
	function addPagoCompra(){
		if((!empty($_REQUEST['monto']))&&(!empty($_REQUEST['id_compra']))){
			$monto=$_REQUEST['monto'];
			$id_compra=$_REQUEST['id_compra'];
			$PagoCompra=new pagos_compra($id_compra,$monto);
			$verifica_compra=new compra_existente($id_compra);			//crea un objeto de la clase compra con el id
			if($verifica_compra->existe()){						 		//checa que exista dicha compra
				if($verifica_compra->tipo_compra==2){					//checa que la compra haya sido a credito
					if(($total=($verifica_compra->subtotal+$verifica_compra->iva))>$PagoCompra->suma_pagos()){	//checa que aun se deba de esa compra
						$total_adeudo=$total-$PagoCompra->suma_pagos();
						$cambio=$monto-$total_adeudo;
						if($cambio<0)$cambio=0;
						$cuenta=new cuenta_proveedor_existente($verifica_compra->id_proveedor);
						$cuenta->saldo-=($monto-$cambio);
						if(!$PagoCompra->existe()){															//verifica que no exista la Pago
							$PagoCompra->monto=$monto-$cambio;
							if($PagoCompra->inserta()){													//inserta el pago
								if($cuenta->actualiza()){												//actualiza el saldo
									if($cambio>0)			echo "{success : true, cambio : ".$cambio."}";
									else 					ok();					
								}else						fail("Error al actualizar el saldo del proveedor.");
							}else							fail("Error al guardar el pago.");
						}else									fail("Ya existe este pago");
					}else										fail("El pago de esta compra ya fue cubierto");
				}else 											fail("La compra no fue a credito.");
			}else 												fail("La compra que desea pagar no existe.");
		}else													fail("Faltan datos.");
		return;
	}
	
	function deletePagoCompra(){
		if(!empty($_REQUEST['id_PagoCompra'])){
			$id=$_REQUEST['id_PagoCompra'];
			$PagoCompra=new pagos_compra_existente($id);
			if($PagoCompra->existe()){												//verifica que si exista el pago
				if($PagoCompra->borra())					ok();					//elimina la Pago
				else									fail("Error al borrar el pago.");
			}else 										fail("El pago que desea eliminar no existe.");
		}else fail("faltan datos.");
		return;
	}
	
	function addPagoVenta(){
		if((!empty($_REQUEST['monto']))&&(!empty($_REQUEST['id_venta']))){
			$monto=$_REQUEST['monto'];
			$id_venta=$_REQUEST['id_venta'];
			$Pagoventa=new pagos_venta($id_venta,$monto);
			$verifica_venta=new venta_existente($id_venta);							//crea un objeto de la clase venta con el id
			if($verifica_venta->existe()){						 					//checa que exista dicha venta
				if($verifica_venta->tipo_venta==2){									//checa que la venta haya sido a credito
					if(($total=($verifica_venta->subtotal+$verifica_venta->iva))>$Pagoventa->suma_pagos()){	//checa que aun se deba de esa venta
						$total_adeudo=$total-$Pagoventa->suma_pagos();
						$cambio=$monto-$total_adeudo;
						if($cambio<0)$cambio=0;
						$cuenta=new cuenta_cliente_existente($verifica_venta->id_cliente);
						$cuenta->saldo-=($monto-$cambio);
						if(!$Pagoventa->existe()){															//verifica que no exista el pago
							$Pagoventa->monto=$monto-$cambio;
							if($Pagoventa->inserta()){													//inserta el pago
								if($cuenta->actualiza()){												//actualiza el saldo
									if($cambio>0)				echo "{success : true, cambio : ".$cambio."}";
									else 						ok();					
								}else							fail("Error al actualizar el saldo del proveedor.");
							}else								fail("Error al guardar la Pagoventa.");
						}else									fail("Ya existe este pago");
					}else										fail("El pago de esta venta ya fue cubierto");
				}else 											fail("La venta no fue a credito.");
			}else 												fail("La venta de la que desea pagar no existe.");
		}else													fail("Faltan datos.");
		return;
	}
	
	function deletePagoventa(){
		if(!empty($_REQUEST['id_Pagoventa'])){
			$id=$_REQUEST['id_Pagoventa'];
			$Pagoventa=new pagos_venta_existente($id);
			if($Pagoventa->existe()){												//verifica que si exista el pago
				if($Pagoventa->borra())					ok();					//elimina el pago
				else									fail("Error al borrar el pago.");
			}else 										fail("La Pago que desea eliminar no existe.");
		}else fail("faltan datos.");
		return;
	}
	
	if(!empty($_REQUEST['method']))
	{
		switch($_REQUEST["method"]){
			case "addPagoCompra" : 					addPagoCompra(); break;
			case "deletePagoCompra" : 				deletePagoCompra(); break;
			case "addPagoVenta" : 					addPagoVenta(); break;
			case "deletePagoVenta" : 				deletePagoVenta(); break;
			default: echo "-1"; 
		}
	}
?>