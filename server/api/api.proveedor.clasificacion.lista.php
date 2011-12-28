<?php
/**
  * GET api/proveedor/clasificacion/lista
  * Lista las clasificaciones de proveedor
  *
  * Este emtodo lista las clasificaciones de proveedores
  *
  *
  *
  **/

  class ApiProveedorClasificacionLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
			"orden" => new ApiExposedProperty("orden", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProveedoresController::ListaClasificacion( 
 			
			
			isset($_GET['activo'] ) ? $_GET['activo'] : null,
			isset($_GET['orden'] ) ? $_GET['orden'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
