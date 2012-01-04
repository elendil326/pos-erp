<?php
/**
  * POST api/precio/tarifa/editar
  * Edita la informacion de una tarifa
  *
  * Edita la informacion b?sica de una tarifa, su nombre, su tipo de tarifa o su moneda. Si se edita el tipo de tarifa se tiene que verificar que esta tarifa no este siendo usada por default en una tarifa de su tipo anterior. 

Ejemplo: La tarifa 1 es tarifa de compra, el usuario 1 tiene como default de tarifa de compra la tarifa 1. Si queremos editar el tipo de tarifa de la tarifa 1 a una tarifa de venta tendra que mandar error, especificando que la tarifa esta siendo usada como tarifa de compra por el usuario 1.

Los parametros que no sean explicitamente nulos seran tomados como edicion.
  *
  *
  *
  **/

  class ApiPrecioTarifaEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tarifa" => new ApiExposedProperty("id_tarifa", true, POST, array( "int" )),
			"id_moneda" => new ApiExposedProperty("id_moneda", false, POST, array( "int" )),
			"nombre" => new ApiExposedProperty("nombre", false, POST, array( "string" )),
			"tipo_tarifa" => new ApiExposedProperty("tipo_tarifa", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::EditarTarifa( 
 			
			
			isset($_POST['id_tarifa'] ) ? $_POST['id_tarifa'] : null,
			isset($_POST['id_moneda'] ) ? $_POST['id_moneda'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['tipo_tarifa'] ) ? $_POST['tipo_tarifa'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
