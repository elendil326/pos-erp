<?php
/**
  * POST api/autorizaciones/cliente/editar
  * Solicitar autorizacion para editar algun campo de un cliente.
  *
  * Solicitud para cambiar alg?n dato de un cliente. La fecha de petici?n se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.

La autorizacion se guardara con los datos del usuario que la pidio. Si es aceptada, entonces el usuario podra editar al cliente una vez.
  *
  *
  *
  **/

  class ApiAutorizacionesClienteEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_cliente" => new ApiExposedProperty("id_cliente", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::EditarCliente( 
 			
			
			isset($_POST['id_cliente'] ) ? $_POST['id_cliente'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
