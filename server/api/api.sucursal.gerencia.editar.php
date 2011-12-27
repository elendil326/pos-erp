<?php
/**
  * GET api/sucursal/gerencia/editar
  * Edita la gerencia de una sucursal
  *
  * Edita la gerencia de una sucursal
  *
  *
  *
  **/

  class ApiSucursalGerenciaEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_gerente" => new ApiExposedProperty("id_gerente", true, GET, array( "string" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::EditarGerencia( 
 			
			
			isset($_GET['id_gerente'] ) ? $_GET['id_gerente'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
