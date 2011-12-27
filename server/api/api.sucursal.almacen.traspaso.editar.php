<?php
/**
  * GET api/sucursal/almacen/traspaso/editar
  * Edita la informacion de un traspaso
  *
  * Para poder editar un traspaso,este no tuvo que haber sido enviado aun
  *
  *
  *
  **/

  class ApiSucursalAlmacenTraspasoEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_traspaso" => new ApiExposedProperty("id_traspaso", true, GET, array( "int" )),
			"fecha_envio_programada" => new ApiExposedProperty("fecha_envio_programada", false, GET, array( "string" )),
			"productos" => new ApiExposedProperty("productos", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::EditarTraspasoAlmacen( 
 			
			
			isset($_GET['id_traspaso'] ) ? $_GET['id_traspaso'] : null,
			isset($_GET['fecha_envio_programada'] ) ? $_GET['fecha_envio_programada'] : null,
			isset($_GET['productos'] ) ? $_GET['productos'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
