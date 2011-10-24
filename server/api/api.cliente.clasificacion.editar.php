<?php
/**
  * GET api/cliente/clasificacion/editar
  * Edita la clasificacion de cliente
  *
  * Edita la informacion de la clasificacion de cliente
  *
  *
  *
  **/

  class ApiClienteClasificacionEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_clasificacion_cliente" => new ApiExposedProperty("id_clasificacion_cliente", true, GET, array( "int" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"descuento" => new ApiExposedProperty("descuento", false, GET, array( "float" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, GET, array( "json" )),
			"clave_interna" => new ApiExposedProperty("clave_interna", false, GET, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", false, GET, array( "string" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"margen_de_utilidad" => new ApiExposedProperty("margen_de_utilidad", false, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ClientesController::EditarClasificacion( 
 			
			
			isset($_GET['id_clasificacion_cliente'] ) ? $_GET['id_clasificacion_cliente'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['retenciones'] ) ? $_GET['retenciones'] : null,
			isset($_GET['clave_interna'] ) ? $_GET['clave_interna'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['margen_de_utilidad'] ) ? $_GET['margen_de_utilidad'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
