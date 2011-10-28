<?php
/**
  * POST api/sucursal/caja/nueva
  * Crear una caja en la sucursal
  *
  * Este metodo creara una caja asociada a una sucursal. Debe haber una caja por CPU. 
  *
  *
  *
  **/

  class ApiSucursalCajaNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"token" => new ApiExposedProperty("token", true, POST, array( "string" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "string" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, POST, array( "int" )),
			"basculas" => new ApiExposedProperty("basculas", false, POST, array( "json" )),
			"impresoras" => new ApiExposedProperty("impresoras", false, POST, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::NuevaCaja( 
 			
			
			isset($_POST['token'] ) ? $_POST['token'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null,
			isset($_POST['id_sucursal'] ) ? $_POST['id_sucursal'] : null,
			isset($_POST['basculas'] ) ? $_POST['basculas'] : null,
			isset($_POST['impresoras'] ) ? $_POST['impresoras'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
