<?php
/**
  * GET api/servicios/orden/lista
  * Lista las ordenes de los servicios
  *
  * Lista de todos las ordenes, se puede filtrar por id_sucursal id_empresa fecha_desde fecha_hasta estado Este metodo se puede utilizar para decirle a un cliente cuando le tocara un servicio en caso de haber mas ordenes en espera.
  *
  *
  *
  **/

  class ApiServiciosOrdenLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"fecha_desde" => new ApiExposedProperty("fecha_desde", false, GET, array( "string" )),
			"fecha_hasta" => new ApiExposedProperty("fecha_hasta", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::ListaOrden( 
 			
			
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['fecha_desde'] ) ? $_GET['fecha_desde'] : null,
			isset($_GET['fecha_hasta'] ) ? $_GET['fecha_hasta'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
