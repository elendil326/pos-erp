<?php
/**
  * POST api/ventas/nueva
  * Genera una venta fuera de caja
  *
  * Genera una venta fuera de caja, puede usarse para que el administrador venda directamente a clientes especiales. EL usuario y la sucursal seran tomados de la sesion. La fecha se tomara del servidor. La empresa sera tomada del alamacen del que fueron tomados los productos.

Si hay dos productos en una misma sucursal pero disntintos almacenes entonces se intentara nivelar los almacenes al mismo valor.
  *
  *
  *
  **/

  class ApiVentasNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"descuento" => new ApiExposedProperty("descuento", true, POST, array( "float" )),
			"id_comprador_venta" => new ApiExposedProperty("id_comprador_venta", true, POST, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", true, POST, array( "int" )),
			"impuesto" => new ApiExposedProperty("impuesto", true, POST, array( "float" )),
			"retencion" => new ApiExposedProperty("retencion", true, POST, array( "float" )),
			"subtotal" => new ApiExposedProperty("subtotal", true, POST, array( "float" )),
			"tipo_venta" => new ApiExposedProperty("tipo_venta", true, POST, array( "string" )),
			"total" => new ApiExposedProperty("total", true, POST, array( "float" )),
			"datos_cheque" => new ApiExposedProperty("datos_cheque", false, POST, array( "json" )),
			"detalle_orden" => new ApiExposedProperty("detalle_orden", false, POST, array( "json" )),
			"detalle_paquete" => new ApiExposedProperty("detalle_paquete", false, POST, array( "json" )),
			"detalle_venta" => new ApiExposedProperty("detalle_venta", false, POST, array( "json" )),
			"saldo" => new ApiExposedProperty("saldo", false, POST, array( "float" )),
			"tipo_de_pago" => new ApiExposedProperty("tipo_de_pago", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = VentasController::Nueva( 
 			
			
			isset($_POST['descuento'] ) ? $_POST['descuento'] : null,
			isset($_POST['id_comprador_venta'] ) ? $_POST['id_comprador_venta'] : null,
			isset($_POST['id_sucursal'] ) ? $_POST['id_sucursal'] : null,
			isset($_POST['impuesto'] ) ? $_POST['impuesto'] : null,
			isset($_POST['retencion'] ) ? $_POST['retencion'] : null,
			isset($_POST['subtotal'] ) ? $_POST['subtotal'] : null,
			isset($_POST['tipo_venta'] ) ? $_POST['tipo_venta'] : null,
			isset($_POST['total'] ) ? $_POST['total'] : null,
			isset($_POST['datos_cheque'] ) ? $_POST['datos_cheque'] : null,
			isset($_POST['detalle_orden'] ) ? $_POST['detalle_orden'] : null,
			isset($_POST['detalle_paquete'] ) ? $_POST['detalle_paquete'] : null,
			isset($_POST['detalle_venta'] ) ? $_POST['detalle_venta'] : null,
			isset($_POST['saldo'] ) ? $_POST['saldo'] : null,
			isset($_POST['tipo_de_pago'] ) ? $_POST['tipo_de_pago'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
