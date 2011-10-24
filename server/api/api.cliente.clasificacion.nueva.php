<?php
/**
  * POST api/cliente/clasificacion/nueva
  * Crear una nueva clasificacion de cliente.
  *
  * Los cliente forzosamente pertenecen a una categoria. En base a esta categoria se calcula el precio que se le dara en una venta, o el descuento, o el credito.
  *
  *
  *
  **/

  class ApiClienteClasificacionNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"clave_interna" => new ApiExposedProperty("clave_interna", true, POST, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, POST, array( "json" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "string" )),
			"descuento" => new ApiExposedProperty("descuento", false, POST, array( "float" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, POST, array( "json" )),
			"utilidad" => new ApiExposedProperty("utilidad", false, POST, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ClientesController::NuevaClasificacion( 
 			
			
			isset($_POST['clave_interna'] ) ? $_POST['clave_interna'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['impuestos'] ) ? $_POST['impuestos'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null,
			isset($_POST['descuento'] ) ? $_POST['descuento'] : null,
			isset($_POST['retenciones'] ) ? $_POST['retenciones'] : null,
			isset($_POST['utilidad'] ) ? $_POST['utilidad'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
