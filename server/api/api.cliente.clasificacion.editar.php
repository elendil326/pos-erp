<?php
/**
  * POST api/cliente/clasificacion/editar
  * Edita la clasificacion de cliente
  *
  * Edita la informacion de la clasificacion de cliente
  *
  *
  *
  **/

  class ApiClienteClasificacionEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_clasificacion_cliente" => new ApiExposedProperty("id_clasificacion_cliente", true, POST, array( "int" )),
			"clave_interna" => new ApiExposedProperty("clave_interna", false, POST, array( "string" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ClientesController::EditarClasificacion( 
 			
			
			isset($_POST['id_clasificacion_cliente'] ) ? $_POST['id_clasificacion_cliente'] : null,
			isset($_POST['clave_interna'] ) ? $_POST['clave_interna'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
