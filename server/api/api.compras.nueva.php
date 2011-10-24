<?php
/**
  * GET api/compras/nueva
  * Registra una nueva compra fuera de caja
  *
  * Registra una nueva compra fuera de caja, puede usarse para que el administrador haga directamente una compra. El usuario y al sucursal seran tomados de la sesion. La fecha sera tomada del servidor. La empresa sera tomada del almacen del cual fueron tomados los productos.
  *
  *
  *
  **/

  class ApiComprasNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"descuento" => new ApiExposedProperty("descuento", true, GET, array( "float" )),
			"subtotal" => new ApiExposedProperty("subtotal", true, GET, array( "float" )),
			"detalle" => new ApiExposedProperty("detalle", true, GET, array( "json" )),
			"impuesto" => new ApiExposedProperty("impuesto", true, GET, array( "float" )),
			"tipo_compra" => new ApiExposedProperty("tipo_compra", true, GET, array( "string" )),
			"retencion" => new ApiExposedProperty("retencion", true, GET, array( "float" )),
			"id_usuario_compra" => new ApiExposedProperty("id_usuario_compra", true, GET, array( "int" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", true, GET, array( "int" )),
			"total" => new ApiExposedProperty("total", true, GET, array( "float" )),
			"cheques" => new ApiExposedProperty("cheques", false, GET, array( "json" )),
			"saldo" => new ApiExposedProperty("saldo", false, GET, array( "float" )),
			"tipo_de_pago" => new ApiExposedProperty("tipo_de_pago", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ComprasController::Nueva( 
 			
			
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['subtotal'] ) ? $_GET['subtotal'] : null,
			isset($_GET['detalle'] ) ? $_GET['detalle'] : null,
			isset($_GET['impuesto'] ) ? $_GET['impuesto'] : null,
			isset($_GET['tipo_compra'] ) ? $_GET['tipo_compra'] : null,
			isset($_GET['retencion'] ) ? $_GET['retencion'] : null,
			isset($_GET['id_usuario_compra'] ) ? $_GET['id_usuario_compra'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['total'] ) ? $_GET['total'] : null,
			isset($_GET['cheques'] ) ? $_GET['cheques'] : null,
			isset($_GET['saldo'] ) ? $_GET['saldo'] : null,
			isset($_GET['tipo_de_pago'] ) ? $_GET['tipo_de_pago'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
