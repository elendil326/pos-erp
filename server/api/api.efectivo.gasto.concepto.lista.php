<?php
/**
  * GET api/efectivo/gasto/concepto/lista
  * Lista los conceptos de gasto
  *
  * Lista los conceptos de gasto. Se puede ordenar por los atributos de concepto de gasto
Update : Falta especificar los parametros y el ejemplo de envio.
  *
  *
  *
  **/

  class ApiEfectivoGastoConceptoLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"orden" => new ApiExposedProperty("orden", false, GET, array( "string" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::ListaConceptoGasto( 
 			
			
			isset($_GET['orden'] ) ? $_GET['orden'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
