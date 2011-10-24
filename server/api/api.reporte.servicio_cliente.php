<?php
/**
  * GET api/reporte/servicio_cliente
  * Muestra una lista de los servicios que ha comprado el cliente con su cantidad
  *
  * Muestra una lista de los servicios que ha comprado el cliente con su cantidad, puede ordenarse por cantidad.Puede filtrarse por un cliente especifico, por la sucursal en la que compro, la empresa en la que compro.
  *
  *
  *
  **/

  class ApiReporteServicioCliente extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_cliente" => new ApiExposedProperty("id_cliente", false, GET, array( "int" )),
			"orden" => new ApiExposedProperty("orden", false, GET, array( "json" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ReportesController::Servicio_cliente( 
 			
			
			isset($_GET['id_cliente'] ) ? $_GET['id_cliente'] : null,
			isset($_GET['orden'] ) ? $_GET['orden'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
