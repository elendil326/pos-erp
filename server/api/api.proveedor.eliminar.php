<?php
/**
  * GET api/proveedor/eliminar
  * Desactiva un proveedor
  *
  * Este metodo desactiva un proveedor. Para que este metodo funcione, no debe de haber ordenes de compra hacia ese proveedor ??
  *
  *
  *
  **/

  class ApiProveedorEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_proveedor" => new ApiExposedProperty("id_proveedor", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProveedoresController::Eliminar( 
 			
			
			isset($_GET['id_proveedor'] ) ? $_GET['id_proveedor'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
