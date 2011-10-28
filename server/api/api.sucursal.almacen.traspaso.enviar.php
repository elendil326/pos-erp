<?php
/**
  * GET api/sucursal/almacen/traspaso/enviar
  * Envia un traspaso de productos
  *
  * Cambia el estado del traspaso a enviado y captura la fecha de envio del servidor. El usuario que envia sera tomado del servidor.
  *
  *
  *
  **/

  class ApiSucursalAlmacenTraspasoEnviar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_traspaso" => new ApiExposedProperty("id_traspaso", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::EnviarTraspasoAlmacen( 
 			
			
			isset($_GET['id_traspaso'] ) ? $_GET['id_traspaso'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
