<?php
/**
  * GET api/efectivo/ingreso/concepto/lista
  * Lista los conceptos de ingreso
  *
  * Lista los conceptos de ingreso, se puede ordenar por los atributos del concepto de ingreso.  

Update :Falta especificar la estructura del JSON que se env?a como parametro
  *
  *
  *
  **/

  class ApiEfectivoIngresoConceptoLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"ordenar" => new ApiExposedProperty("ordenar", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::ListaConceptoIngreso( 
 			
			
			isset($_GET['ordenar'] ) ? $_GET['ordenar'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
