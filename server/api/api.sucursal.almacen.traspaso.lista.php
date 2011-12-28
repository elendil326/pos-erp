<?php
/**
  * GET api/sucursal/almacen/traspaso/lista
  * Lista los traspasos
  *
  * Lista los traspasos de almacenes. Puede filtrarse por empresa, por sucursal, por almacen, por producto, cancelados, completos, estado
  *
  *
  *
  **/

  class ApiSucursalAlmacenTraspasoLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"cancelado" => new ApiExposedProperty("cancelado", false, GET, array( "bool" )),
			"completo" => new ApiExposedProperty("completo", false, GET, array( "bool" )),
			"estado" => new ApiExposedProperty("estado", false, GET, array( "string" )),
			"id_almacen_envia" => new ApiExposedProperty("id_almacen_envia", false, GET, array( "int" )),
			"id_almacen_recibe" => new ApiExposedProperty("id_almacen_recibe", false, GET, array( "int" )),
			"ordenar" => new ApiExposedProperty("ordenar", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::ListaTraspasoAlmacen( 
 			
			
			isset($_GET['cancelado'] ) ? $_GET['cancelado'] : null,
			isset($_GET['completo'] ) ? $_GET['completo'] : null,
			isset($_GET['estado'] ) ? $_GET['estado'] : null,
			isset($_GET['id_almacen_envia'] ) ? $_GET['id_almacen_envia'] : null,
			isset($_GET['id_almacen_recibe'] ) ? $_GET['id_almacen_recibe'] : null,
			isset($_GET['ordenar'] ) ? $_GET['ordenar'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
