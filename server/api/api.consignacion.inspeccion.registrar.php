<?php
/**
  * GET api/consignacion/inspeccion/registrar
  * Registra la inspeccion realizada a una consignacion
  *
  * Se llama cuando se realiza una revision a una orden de consigacion. Actualiza el estado del almacen del cliente, se facturan a credito o de contado las ventas realizadas y se registra la entrada de dinero. La fecha sera tomada del servidor.
  *
  *
  *
  **/

  class ApiConsignacionInspeccionRegistrar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"productos_actuales" => new ApiExposedProperty("productos_actuales", true, GET, array( "json" )),
			"id_inspeccion" => new ApiExposedProperty("id_inspeccion", true, GET, array( "int" )),
			"id_inspector" => new ApiExposedProperty("id_inspector", true, GET, array( "int" )),
			"monto_abonado" => new ApiExposedProperty("monto_abonado", false, GET, array( "float" )),
			"producto_solicitado" => new ApiExposedProperty("producto_solicitado", false, GET, array( "json" )),
			"producto_devuelto" => new ApiExposedProperty("producto_devuelto", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ConsignacionesController::RegistrarInspeccion( 
 			
			
			isset($_GET['productos_actuales'] ) ? $_GET['productos_actuales'] : null,
			isset($_GET['id_inspeccion'] ) ? $_GET['id_inspeccion'] : null,
			isset($_GET['id_inspector'] ) ? $_GET['id_inspector'] : null,
			isset($_GET['monto_abonado'] ) ? $_GET['monto_abonado'] : null,
			isset($_GET['producto_solicitado'] ) ? $_GET['producto_solicitado'] : null,
			isset($_GET['producto_devuelto'] ) ? $_GET['producto_devuelto'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  