<?php
/**
  * POST api/precio/version/duplicar
  * Duplica la version y la guarda en otra tarifa
  *
  * Duplica la version obtenida junto con todas sus reglas y la guarda en otra tarifa. Este metodo sirve cuando una misma version con todas sus reglas aplica a mas de una tarifa.

Al duplicar una version, las reglas duplicadas con ella actualizan su id de la version a la nueva version creada.

Cuando una version activa y/o default se duplica, al guardarse en la otra tarifa pierde estas propiedades.
  *
  *
  *
  **/

  class ApiPrecioVersionDuplicar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tarifa" => new ApiExposedProperty("id_tarifa", true, POST, array( "int" )),
			"id_version" => new ApiExposedProperty("id_version", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::DuplicarVersion( 
 			
			
			isset($_POST['id_tarifa'] ) ? $_POST['id_tarifa'] : null,
			isset($_POST['id_version'] ) ? $_POST['id_version'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
