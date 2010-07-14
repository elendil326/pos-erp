<?php	
/*este documentotiene todas las funciones de pagos
tanto de las compras como las ventas
como insertar, eliminar, actualizar, consultas, listar 
y algunas otras funciones
*/
	function insertarPagoCompra()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['monto']))&&(!empty($_REQUEST['id_compra'])))
		{
			//asignamos valores obtenidos a las variables
			$monto=$_REQUEST['monto'];
			$id_compra=$_REQUEST['id_compra'];
			//creamos objeto - pago compra
			$PagoCompra=new pagos_compra($id_compra,$monto);
			//crea un objeto de la clase compra con el id
			$verifica_compra=new compra_existente($id_compra);			
			//checa que exista dicha compra
			if($verifica_compra->existe())
			{						 		
				//checa que la compra haya sido a credito
				if($verifica_compra->tipo_compra==2)
				{					
					//checa que aun se deba de esa compra
					if(($total=($verifica_compra->subtotal+$verifica_compra->iva))>$PagoCompra->suma_pagos())
					{	
						//calculamos lo que se debe y el cambi en caso de que exista
						$total_adeudo=$total-$PagoCompra->suma_pagos();
						$cambio=$monto-$total_adeudo;
						if($cambio<0)$cambio=0;
						//creamos objeto - cuenta proveedor existente
						$cuenta=new cuentaProveedorExistente($verifica_compra->id_proveedor);
						//modificamos el saldo del proveedor
						$cuenta->saldo-=($monto-$cambio);
						//verificamos que el pago no exista
						if(!$PagoCompra->existe())
						{
							//asignamos el monto a el pago
							$PagoCompra->monto=$monto-$cambio;
							//intentamos insertar el pago
							if($PagoCompra->inserta())
							{	
								//intentamos actualizar la cuenta del proveedor
								if($cuenta->actualiza())
								{												
									//actualiza el saldo
									if($cambio>0)			echo "{success : true, cambio : ".$cambio."}";				//regresamos el true y el cambio
									else 					ok();														//si no hay cambio solo el true
								}//if actualiza la cuenta
								else						fail("Error al actualizar el saldo del proveedor.");		//no se pudo actualizar
							}//if inserta pago
							else							fail("Error al guardar el pago.");							//no se pudo guardar el pago
						}//if no existe el pago
						else								fail("Ya existe este pago");								//pago existente
					}//if aun se debe
					else									fail("El pago de esta compra ya fue cubierto");				//pagado
				}//if fue a credito
				else 										fail("La compra no fue a credito.");						//a contado
			}//if existe compra
			else 											fail("La compra que desea pagar no existe.");				//compra inexistente
		}//if verifica datos
		else												fail("Faltan datos.");										//datos incompletos
		return;
	}
	//funcion insertat pago compra
	
	
	//esta funcion elimina un pago de compra
	function eliminarPagoCompra()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_PagoCompra']))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_PagoCompra'];
			//creamos objeto - pago compra existente
			$PagoCompra=new pagosCompraExistente($id);
			//verifica que si exista el pago
			if($PagoCompra->existe())
			{												
				if($PagoCompra->borra())					ok();														//Elimina la Pago
				else										fail("Error al borrar el pago.");							//no se pudo borrar el pago
			}//if existe el pago
			else 											fail("El pago que desea eliminar no existe.");				//pago inexistente
		}//if verifica datos
		else 												fail("faltan datos.");										//datos incompletos
		return;
	}
	//funcion eliminar pago compra
	
	//esta funcion inserta un pago de una venta	
	function insertarPagoVenta()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['monto']))&&(!empty($_REQUEST['id_venta'])))
		{
			//asignamos valores obtenidos a las variables
			$monto=$_REQUEST['monto'];
			$id_venta=$_REQUEST['id_venta'];
			//creamos objeto - pago venta
			$Pagoventa=new pagos_venta($id_venta,$monto);
			//crea un objeto de la clase venta con el id
			$verifica_venta=new ventaExistente($id_venta);							
			//checa que exista dicha venta
			if($verifica_venta->existe())
			{						 					
				//checa que la venta haya sido a credito
				if($verifica_venta->tipo_venta==2)
				{
					//checa que aun se deba de esa venta
					if(($total=($verifica_venta->subtotal+$verifica_venta->iva))>$Pagoventa->suma_pagos()){	
						//calcula el total y el cambio
						$total_adeudo=$total-$Pagoventa->suma_pagos();
						$cambio=$monto-$total_adeudo;
						if($cambio<0)$cambio=0;
						//creamos objeto - cuenta cliente existente
						$cuenta=new cuentaClienteExistente($verifica_venta->id_cliente);
						//modificamos el saldo en el objeto
						$cuenta->saldo-=($monto-$cambio);
						//verificamos que no exista el pago
						if(!$Pagoventa->existe())
						{
							//asignamos el monto a pagar
							$Pagoventa->monto=$monto-$cambio;
							//intentamos insertar el pago
							if($Pagoventa->inserta())
							{
								//Intentamos actualiza el saldo
								if($cuenta->actualiza())
								{												
									//verificamos si hay cambio
									if($cambio>0)				echo "{success : true, cambio : ".$cambio."}";				//si hay cambio regresamos el true y el cambio
									else 						ok();														//si no hay cambio solo el true
								}//if actualiza cuenta
								else							fail("Error al actualizar el saldo del cliente.");		//fallo la actualizacion de saldo
							}//if inserta pago
							else								fail("Error al guardar el Pago de venta.");						//fallo guardar pago
						}//if no existe el pago
						else									fail("Ya existe este pago");								//pago existente
					}//if se aun debe
					else										fail("El pago de esta venta ya fue cubierto");				//pagado
				}//if a credito
				else 											fail("La venta no fue a credito.");							//contado
			}//if existe venta
			else 												fail("La venta de la que desea pagar no existe.");			//venta inexistente
		}//if verifica datos
		else													fail("Faltan datos.");										//datos incompletos
		return;
	}
	//funcion inserta pago venta
	
	//esta funcion elimina un pago de una venta
	function eliminarPagoVenta()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_Pagoventa']))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_Pagoventa'];
			//creamos objeto - pago venta existente
			$Pagoventa=new pagosVentaExistente($id);
			//verifica que si exista el pago
			if($Pagoventa->existe())
			{												
				//se intenta elimina el pago
				if($Pagoventa->borra())							ok();														//exito al borrar
				else											fail("Error al borrar el pago.");							//fallo el borrado
			}//if existe pago
			else 												fail("La Pago que desea eliminar no existe.");				//pago inexistente
		}//if verifica datos
		else													fail("faltan datos.");										//datos incompletos
		return;
	}
	//funcion eliminar pago venta
	
	
	//esta funcion nos lista todos los pagos de ventas
	function listarPagosVenta()
	{
		//creamos objeto - listar con la consulta
		$listar = new listar("select * from pagos_venta",array());
		//imprimimos el resultado de la consulta
		echo $listar->lista();
		return;
	}
	//funcion listar pagos venta
	
	//esta funcion muetra los valores de un pago de venta
	function listarPagosVentaDeVenta()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_venta']))
		{
			//asignamos el id obtenido a la variable id
			$id=$_REQUEST['id_venta'];
			//creamos objeto - listar con la consulta
			$listar = new listar("select * from pagos_venta where id_venta=?",array($id));
			//imprime los datos
			echo $listar->lista();
		}//if verifica datos
		else													fail("faltan datos.");										//datos incompletos
		return;
	}
	//funcion listar pagos venta de venta
	
	//esta funcion lista todos los pagos de una compra a credito
	function listarPagosCompra()
	{
		//creamos objeto - listar con la consulta
		$listar = new listar("select * from pagos_compra",array());
		//imprime los datos obtenidos
		echo $listar->lista();
		return;
	}
	//funcion listar pagos de compra
	
	//esta funcion muetra los valores de un pago de compra
	function listarPagosCompraDeCompra()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_compra']))
		{
			//asignamos el id obtenido a la variable id
			$id=$_REQUEST['id_compra'];
			//creamos objeto - listar con la consulta
			$listar = new listar("select * from pagos_compra where id_compra=?",array($id));
			//imprime los datos
			echo $listar->lista();
		}//if verifica datos
		else													fail("faltan datos.");										//datos incompletos
		return;
	}
	//funcion listar pagos compra de compra
	
?>