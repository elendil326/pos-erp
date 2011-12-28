<?php
/**
  * GET api/cliente/clasificacion/lista
  * Lista de las clasificaciones existentes
  *
  * Obtener una lista de las categorias de clientes actuales en el sistema. Se puede ordenar por sus atributos
  *
  *
  *
  **/

  class ApiClienteClasificacionLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"orden" => new ApiExposedProperty("orden", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ClientesController::ListaClasificacion( 
 			
			
			isset($_GET['orden'] ) ? $_GET['orden'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
