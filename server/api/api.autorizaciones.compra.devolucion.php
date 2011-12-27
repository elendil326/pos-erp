<?php
/**
  * GET api/autorizaciones/compra/devolucion
  * Solicitud para devolver una compra.
  *
  * Solicitud para devolver una compra. La fecha de petici?n se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.
  *
  *
  *
  **/

  class ApiAutorizacionesCompraDevolucion extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_compra" => new ApiExposedProperty("id_compra", true, GET, array( "int" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::DevolucionCompra( 
 			
			
			isset($_GET['id_compra'] ) ? $_GET['id_compra'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
