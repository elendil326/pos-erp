<?php
/**
  * GET api/proveedor/clasificacion/editar
  * Edita la informacion de una clasificacion de proveedor
  *
  * Edita la informacion de una clasificacion de proveedor
  *
  *
  *
  **/

  class ApiProveedorClasificacionEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_clasificacion_proveedor" => new ApiExposedProperty("id_clasificacion_proveedor", true, GET, array( "int" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, GET, array( "json" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProveedoresController::EditarClasificacion( 
 			
			
			isset($_GET['id_clasificacion_proveedor'] ) ? $_GET['id_clasificacion_proveedor'] : null,
			isset($_GET['retenciones'] ) ? $_GET['retenciones'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
