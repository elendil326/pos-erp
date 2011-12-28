<?php
/**
  * GET api/ventas/lista
  * Lista las ventas.
  *
  * Lista las ventas, puede filtrarse por empresa, sucursal, por el total, si estan liquidadas o no, por canceladas, y puede ordenarse por sus atributos.
  *
  *
  *
  **/

  class ApiVentasLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"canceladas" => new ApiExposedProperty("canceladas", false, GET, array( "bool" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"liquidados" => new ApiExposedProperty("liquidados", false, GET, array( "bool" )),
			"ordenar" => new ApiExposedProperty("ordenar", false, GET, array( "string" )),
			"total_igual_a" => new ApiExposedProperty("total_igual_a", false, GET, array( "float" )),
			"total_inferior_a" => new ApiExposedProperty("total_inferior_a", false, GET, array( "float" )),
			"total_superior_a" => new ApiExposedProperty("total_superior_a", false, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = VentasController::Lista( 
 			
			
			isset($_GET['canceladas'] ) ? $_GET['canceladas'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['liquidados'] ) ? $_GET['liquidados'] : null,
			isset($_GET['ordenar'] ) ? $_GET['ordenar'] : null,
			isset($_GET['total_igual_a'] ) ? $_GET['total_igual_a'] : null,
			isset($_GET['total_inferior_a'] ) ? $_GET['total_inferior_a'] : null,
			isset($_GET['total_superior_a'] ) ? $_GET['total_superior_a'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
