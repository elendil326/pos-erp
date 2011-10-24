<?php
/**
  * GET api/sucursal/almacen/lista
  * listar almacenes de la instancia
  *
  * listar almacenes de la isntancia. Se pueden filtrar por empresa, por sucursal, por tipo de almacen, por activos e inactivos y ordenar por sus atributos.
  *
  *
  *
  **/

  class ApiSucursalAlmacenLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"id_tipo_almacen" => new ApiExposedProperty("id_tipo_almacen", false, GET, array( "int" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::ListaAlmacen( 
 			
			
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['id_tipo_almacen'] ) ? $_GET['id_tipo_almacen'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
