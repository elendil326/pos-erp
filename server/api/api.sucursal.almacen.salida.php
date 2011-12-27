<?php
/**
  * GET api/sucursal/almacen/salida
  * Envia productos fuera del almacen
  *
  * Envia productos fuera del almacen. Ya sea que sea un traspaso de un alamcen a otro o por motivos de inventarios fisicos.
  *
  *
  *
  **/

  class ApiSucursalAlmacenSalida extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_almacen" => new ApiExposedProperty("id_almacen", true, GET, array( "int" )),
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
			"motivo" => new ApiExposedProperty("motivo", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::SalidaAlmacen( 
 			
			
			isset($_GET['id_almacen'] ) ? $_GET['id_almacen'] : null,
			isset($_GET['productos'] ) ? $_GET['productos'] : null,
			isset($_GET['motivo'] ) ? $_GET['motivo'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
