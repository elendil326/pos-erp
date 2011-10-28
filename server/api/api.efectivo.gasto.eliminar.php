<?php
/**
  * GET api/efectivo/gasto/eliminar
  * Elimina un gasto
  *
  * Cancela un gasto 
  *
  *
  *
  **/

  class ApiEfectivoGastoEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_gasto" => new ApiExposedProperty("id_gasto", true, GET, array( "int" )),
			"motivo_cancelacion" => new ApiExposedProperty("motivo_cancelacion", false, GET, array( "string" )),
			"id_caja" => new ApiExposedProperty("id_caja", false, GET, array( "int" )),
			"billetes" => new ApiExposedProperty("billetes", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::EliminarGasto( 
 			
			
			isset($_GET['id_gasto'] ) ? $_GET['id_gasto'] : null,
			isset($_GET['motivo_cancelacion'] ) ? $_GET['motivo_cancelacion'] : null,
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null,
			isset($_GET['billetes'] ) ? $_GET['billetes'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
