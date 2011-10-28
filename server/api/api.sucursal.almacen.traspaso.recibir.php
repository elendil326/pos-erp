<?php
/**
  * GET api/sucursal/almacen/traspaso/recibir
  * Recibe un traspaso de producto
  *
  * Cambia el estado de un traspaso a recibido. La  bandera de completo se prende si los productos enviados son los mismos que los recibidos. La fecha de recibo es tomada del servidor. El usuario que recibe sera tomada de la sesion actual.
  *
  *
  *
  **/

  class ApiSucursalAlmacenTraspasoRecibir extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
			"id_traspaso" => new ApiExposedProperty("id_traspaso", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::RecibirTraspasoAlmacen( 
 			
			
			isset($_GET['productos'] ) ? $_GET['productos'] : null,
			isset($_GET['id_traspaso'] ) ? $_GET['id_traspaso'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
