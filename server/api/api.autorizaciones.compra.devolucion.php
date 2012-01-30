<?php
/**
  * POST api/autorizaciones/compra/devolucion
  * Solicitud para devolver una compra.
  *
  * Solicitud para devolver una compra.

Consideraciones:
-Que hacer con el dinero
-Que hacer con la mercancia

  *
  *
  *
  **/

  class ApiAutorizacionesCompraDevolucion extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_compra" => new ApiExposedProperty("id_compra", true, POST, array( "int" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::DevolucionCompra( 
 			
			
			isset($_POST['id_compra'] ) ? $_POST['id_compra'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
