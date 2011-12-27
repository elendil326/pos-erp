<?php
/**
  * GET api/sucursal/almacen/traspaso/programar
  * Crea un registro de traspaso
  *
  * Crea un registro de traspaso de producto de un almacen a otro. El usuario que envia sera tomada de la sesion.
  *
  *
  *
  **/

  class ApiSucursalAlmacenTraspasoProgramar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"fecha_envio_programada" => new ApiExposedProperty("fecha_envio_programada", true, GET, array( "string" )),
			"id_almacen_envia" => new ApiExposedProperty("id_almacen_envia", true, GET, array( "int" )),
			"id_almacen_recibe" => new ApiExposedProperty("id_almacen_recibe", true, GET, array( "int" )),
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::ProgramarTraspasoAlmacen( 
 			
			
			isset($_GET['fecha_envio_programada'] ) ? $_GET['fecha_envio_programada'] : null,
			isset($_GET['id_almacen_envia'] ) ? $_GET['id_almacen_envia'] : null,
			isset($_GET['id_almacen_recibe'] ) ? $_GET['id_almacen_recibe'] : null,
			isset($_GET['productos'] ) ? $_GET['productos'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
