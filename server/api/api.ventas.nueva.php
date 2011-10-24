<?php
/**
  * GET api/ventas/nueva
  * Genera una venta fuera de caja
  *
  * Genera una venta fuera de caja, puede usarse para que el administrador venda directamente a clientes especiales. EL usuario y la sucursal seran tomados de la sesion. La fecha se tomara del servidor. La empresa sera tomada del alamacen del que fueron tomados los productos
  *
  *
  *
  **/

  class ApiVentasNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"total" => new ApiExposedProperty("total", true, GET, array( "float" )),
			"retencion" => new ApiExposedProperty("retencion", true, GET, array( "float" )),
			"descuento" => new ApiExposedProperty("descuento", true, GET, array( "float" )),
			"tipo_venta" => new ApiExposedProperty("tipo_venta", true, GET, array( "string" )),
			"impuesto" => new ApiExposedProperty("impuesto", true, GET, array( "float" )),
			"subtotal" => new ApiExposedProperty("subtotal", true, GET, array( "float" )),
			"id_comprador_venta" => new ApiExposedProperty("id_comprador_venta", true, GET, array( "int" )),
			"detalle_venta" => new ApiExposedProperty("detalle_venta", true, GET, array( "json" )),
			"datos_cheque" => new ApiExposedProperty("datos_cheque", false, GET, array( "json" )),
			"saldo" => new ApiExposedProperty("saldo", false, GET, array( "float" )),
			"tipo_de_pago" => new ApiExposedProperty("tipo_de_pago", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = VentasController::Nueva( 
 			
			
			isset($_GET['total'] ) ? $_GET['total'] : null,
			isset($_GET['retencion'] ) ? $_GET['retencion'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['tipo_venta'] ) ? $_GET['tipo_venta'] : null,
			isset($_GET['impuesto'] ) ? $_GET['impuesto'] : null,
			isset($_GET['subtotal'] ) ? $_GET['subtotal'] : null,
			isset($_GET['id_comprador_venta'] ) ? $_GET['id_comprador_venta'] : null,
			isset($_GET['detalle_venta'] ) ? $_GET['detalle_venta'] : null,
			isset($_GET['datos_cheque'] ) ? $_GET['datos_cheque'] : null,
			isset($_GET['saldo'] ) ? $_GET['saldo'] : null,
			isset($_GET['tipo_de_pago'] ) ? $_GET['tipo_de_pago'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
