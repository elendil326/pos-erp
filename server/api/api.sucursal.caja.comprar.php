<?php
/**
  * GET api/sucursal/caja/comprar
  * Compra de productos desde el mostrador. (caja)
  *
  * Comprar productos en mostrador. No debe confundirse con comprar productos a un proveedor. Estos productos se agregaran al inventario de esta sucursal de manera automatica e instantanea. La IP ser? tomada de la m?quina que realiza la compra. El usuario y la sucursal ser?n tomados de la sesion activa. El estado del campo liquidada ser? tomado de acuerdo al campo total y pagado.
  *
  *
  *
  **/

  class ApiSucursalCajaComprar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"retencion" => new ApiExposedProperty("retencion", true, GET, array( "float" )),
			"detalle" => new ApiExposedProperty("detalle", true, GET, array( "json" )),
			"descuento" => new ApiExposedProperty("descuento", true, GET, array( "float" )),
			"impuesto" => new ApiExposedProperty("impuesto", true, GET, array( "float" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", true, GET, array( "int" )),
			"subtotal" => new ApiExposedProperty("subtotal", true, GET, array( "float" )),
			"tipo_compra" => new ApiExposedProperty("tipo_compra", true, GET, array( "string" )),
			"total" => new ApiExposedProperty("total", true, GET, array( "float" )),
			"id_vendedor" => new ApiExposedProperty("id_vendedor", true, GET, array( "int" )),
			"saldo" => new ApiExposedProperty("saldo", false, GET, array( "float" )),
			"tipo_pago" => new ApiExposedProperty("tipo_pago", false, GET, array( "string" )),
			"billetes_pago" => new ApiExposedProperty("billetes_pago", false, GET, array( "json" )),
			"billetes_cambio" => new ApiExposedProperty("billetes_cambio", false, GET, array( "json" )),
			"cheques" => new ApiExposedProperty("cheques", false, GET, array( "json" )),
			"id_compra_caja" => new ApiExposedProperty("id_compra_caja", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::ComprarCaja( 
 			
			
			isset($_GET['retencion'] ) ? $_GET['retencion'] : null,
			isset($_GET['detalle'] ) ? $_GET['detalle'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['impuesto'] ) ? $_GET['impuesto'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['subtotal'] ) ? $_GET['subtotal'] : null,
			isset($_GET['tipo_compra'] ) ? $_GET['tipo_compra'] : null,
			isset($_GET['total'] ) ? $_GET['total'] : null,
			isset($_GET['id_vendedor'] ) ? $_GET['id_vendedor'] : null,
			isset($_GET['saldo'] ) ? $_GET['saldo'] : null,
			isset($_GET['tipo_pago'] ) ? $_GET['tipo_pago'] : null,
			isset($_GET['billetes_pago'] ) ? $_GET['billetes_pago'] : null,
			isset($_GET['billetes_cambio'] ) ? $_GET['billetes_cambio'] : null,
			isset($_GET['cheques'] ) ? $_GET['cheques'] : null,
			isset($_GET['id_compra_caja'] ) ? $_GET['id_compra_caja'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
