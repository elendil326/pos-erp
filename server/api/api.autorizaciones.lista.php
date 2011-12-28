<?php
/**
  * GET api/autorizaciones/lista
  * Lista todas las autorizaciones.
  *
  * Muestra la lista de autorizaciones, con la opci?n de filtrar por pendientes, aceptadas, rechazadas, en tr?nsito, embarques recibidos y de ordenar seg?n los atributos de autorizaciones. 
Update :  falta definir el ejemplo de envio.
  *
  *
  *
  **/

  class ApiAutorizacionesLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"filtro" => new ApiExposedProperty("filtro", false, GET, array( "string" )),
			"ordenar" => new ApiExposedProperty("ordenar", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::Lista( 
 			
			
			isset($_GET['filtro'] ) ? $_GET['filtro'] : null,
			isset($_GET['ordenar'] ) ? $_GET['ordenar'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
