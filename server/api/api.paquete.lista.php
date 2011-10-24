<?php
/**
  * GET api/paquete/lista
  * Lista los paquetes
  *
  * Lista los paquetes, se puede filtrar por empresa, por sucursal, por producto, por servicio y se pueden ordenar por sus atributos
  *
  *
  *
  **/

  class ApiPaqueteLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"id_producto" => new ApiExposedProperty("id_producto", false, GET, array( "int" )),
			"id_servicio" => new ApiExposedProperty("id_servicio", false, GET, array( "int" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PaquetesController::Lista( 
 			
			
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['id_producto'] ) ? $_GET['id_producto'] : null,
			isset($_GET['id_servicio'] ) ? $_GET['id_servicio'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
