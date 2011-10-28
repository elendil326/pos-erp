<?php
/**
  * GET api/autorizaciones/editar
  * Editar una autorizaci?n en caso de tener permiso.
  *
  * Editar una autorizacion en caso de tener permiso.

Update :  Creo que seriabuena idea que se definiera de una vez la estructura de las autorizaciones, ya que como se maneja actualemnte es de la siguiente manera : 

Digo que seria buena idea definir el formato de las autorizaciones para ir pensando en como en un futuro se van a mostrar en las interfaces, apartir de que se se crearan los formularios, actualmente se toma el campo tipo para de ahi saber que tipo de autorizacion es y crear un formulario de este tipo para desplegar los datos, y dependiendo del tipo se identifica que formato de JSON se espera que contenga el campo parametros .



Al momento de editar la autorizacion veo que aparentemente se podria editar el id_autorizacion, id_usuario, id_sucursal, peticion y estado, creo yo que no es prudente editar ninguno de estos campos ya que el mal uso de esta informacion puede da?ar gravemente la integridad del sistema.
  *
  *
  *
  **/

  class ApiAutorizacionesEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"descripcion" => new ApiExposedProperty("descripcion", true, GET, array( "string" )),
			"id_autorizacion	" => new ApiExposedProperty("id_autorizacion	", true, GET, array( "int" )),
			"estado" => new ApiExposedProperty("estado", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::Editar( 
 			
			
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['id_autorizacion	'] ) ? $_GET['id_autorizacion	'] : null,
			isset($_GET['estado'] ) ? $_GET['estado'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
