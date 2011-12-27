<?php
/**
  * POST api/autorizaciones/venta/devolucion
  * Solicitud para devolver una venta.
  *
  * Solicitud para devolver una venta. La fecha de petici?n se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.
  *
  *
  *
  **/

  class ApiAutorizacionesVentaDevolucion extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"descripcion" => new ApiExposedProperty("descripcion", true, POST, array( "string" )),
			"id_venta" => new ApiExposedProperty("id_venta", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::DevolucionVenta( 
 			
			
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null,
			isset($_POST['id_venta'] ) ? $_POST['id_venta'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
