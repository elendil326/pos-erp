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
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"cancelado" => new ApiExposedProperty("cancelado", false, GET, array( "bool" )),
			"completo" => new ApiExposedProperty("completo", false, GET, array( "bool" )),
			"id_producto" => new ApiExposedProperty("id_producto", false, GET, array( "int" )),
			"id_almacen" => new ApiExposedProperty("id_almacen", false, GET, array( "int" )),
			"enviados" => new ApiExposedProperty("enviados", false, GET, array( "bool" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"estado" => new ApiExposedProperty("estado", false, GET, array( "string" )),
			"ordenar" => new ApiExposedProperty("ordenar", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::ListaTraspasoAlmacen( 
 			
			
			isset($_GET['cancelado'] ) ? $_GET['cancelado'] : null,
			isset($_GET['completo'] ) ? $_GET['completo'] : null,
			isset($_GET['id_producto'] ) ? $_GET['id_producto'] : null,
			isset($_GET['id_almacen'] ) ? $_GET['id_almacen'] : null,
			isset($_GET['enviados'] ) ? $_GET['enviados'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['estado'] ) ? $_GET['estado'] : null,
			isset($_GET['ordenar'] ) ? $_GET['ordenar'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
