<?php
/**
  * POST api/precio/tarifa/duplicar
  * Duplica una tarifa
  *
  * Duplica una tarifa con todas sus versiones, y cada una de ellas con todas sus reglas. Este metodo sirve cuando se tiene una tarifa muy completa y se quiere hacer una tarifa muy similar pero con unas ligeras modificaciones.

Al duplicar la tarifa, se actualizan sus versiones default y activa por los ids generados al duplicar las versiones.

La tarifa duplicada pierde ela tributo default.
  *
  *
  *
  **/

  class ApiPrecioTarifaDuplicar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_moneda" => new ApiExposedProperty("id_moneda", true, POST, array( "int" )),
			"id_tarifa" => new ApiExposedProperty("id_tarifa", true, POST, array( "int" )),
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"tipo_tarifa" => new ApiExposedProperty("tipo_tarifa", true, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::DuplicarTarifa( 
 			
			
			isset($_POST['id_moneda'] ) ? $_POST['id_moneda'] : null,
			isset($_POST['id_tarifa'] ) ? $_POST['id_tarifa'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['tipo_tarifa'] ) ? $_POST['tipo_tarifa'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
