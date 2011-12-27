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
			"activa" => new ApiExposedProperty("activa", false, GET, array( "bool" )),
			"cancelada" => new ApiExposedProperty("cancelada", false, GET, array( "bool" )),
			"fecha_desde" => new ApiExposedProperty("fecha_desde", false, GET, array( "string" )),
			"fecha_hasta" => new ApiExposedProperty("fecha_hasta", false, GET, array( "string" )),
			"id_servicio" => new ApiExposedProperty("id_servicio", false, GET, array( "int" )),
			"id_usuario_venta" => new ApiExposedProperty("id_usuario_venta", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::ListaOrden( 
 			
			
			isset($_GET['activa'] ) ? $_GET['activa'] : null,
			isset($_GET['cancelada'] ) ? $_GET['cancelada'] : null,
			isset($_GET['fecha_desde'] ) ? $_GET['fecha_desde'] : null,
			isset($_GET['fecha_hasta'] ) ? $_GET['fecha_hasta'] : null,
			isset($_GET['id_servicio'] ) ? $_GET['id_servicio'] : null,
			isset($_GET['id_usuario_venta'] ) ? $_GET['id_usuario_venta'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
