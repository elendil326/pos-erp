<?php
/**
  * POST api/pos/mail/enviar
  * Enviar un correo electronico
  *
  * Este metodo se utiliza para poder enviar un correo electronico a un tercero. 
  *
  *
  *
  **/

  class ApiPosMailEnviar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"cuerpo" => new ApiExposedProperty("cuerpo", true, POST, array( "string" )),
			"destinatario" => new ApiExposedProperty("destinatario", true, POST, array( "string" )),
			"titulo" => new ApiExposedProperty("titulo", true, POST, array( "string" )),
			"emisor" => new ApiExposedProperty("emisor", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = POSController::EnviarMail( 
 			
			
			isset($_POST['cuerpo'] ) ? $_POST['cuerpo'] : null,
			isset($_POST['destinatario'] ) ? $_POST['destinatario'] : null,
			isset($_POST['titulo'] ) ? $_POST['titulo'] : null,
			isset($_POST['emisor'] ) ? $_POST['emisor'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
