<?php
/**
  * GET api/consignacion/lista
  * Lista de cosnignaciones 
  *
  * Este metodo lista las consignaciones de la instancia. Puede filtrarse por empresa, por sucursal, por cliente, por producto y se puede ordenar por sus atributos.
  *
  *
  *
  **/

  class ApiConsignacionLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"id_cliente" => new ApiExposedProperty("id_cliente", false, GET, array( "int" )),
			"id_producto" => new ApiExposedProperty("id_producto", false, GET, array( "int" )),
			"orden" => new ApiExposedProperty("orden", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ConsignacionesController::Lista( 
 			
			
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['id_cliente'] ) ? $_GET['id_cliente'] : null,
			isset($_GET['id_producto'] ) ? $_GET['id_producto'] : null,
			isset($_GET['orden'] ) ? $_GET['orden'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
