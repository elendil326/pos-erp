<?php
/**
  * GET api/cliente/lista
  * Obtener la lista de clientes.
  *
  * Regresa una lista de clientes. Puede filtrarse por empresa, sucursal, activos, as? como ordenarse seg?n sus atributs con el par?metro orden. Es posible que algunos clientes sean dados de alta por un admnistrador que no les asigne algun id_empresa, o id_sucursal.

Update :  ?Es correcto que contenga el argumento id_sucursal? Ya que as? como esta entiendo que solo te regresara los datos de los clientes de una sola sucursal.
  *
  *
  *
  **/

  class ApiClienteLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"orden" => new ApiExposedProperty("orden", false, GET, array( "json" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"mostrar_inactivos" => new ApiExposedProperty("mostrar_inactivos", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ClientesController::Lista( 
 			
			
			isset($_GET['orden'] ) ? $_GET['orden'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['mostrar_inactivos'] ) ? $_GET['mostrar_inactivos'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
