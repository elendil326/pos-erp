<?php
/**
  * GET api/documento/nota_de_venta/imprimir
  * Imprime una nota de venta
  *
  * Imprime una nota de venta de acuerdo al id_venta y al id_impresora
  *
  *
  *
  **/

  class ApiDocumentoNotaDeVentaImprimir extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_venta" => new ApiExposedProperty("id_venta", true, GET, array( "int" )),
			"id_impresora" => new ApiExposedProperty("id_impresora", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = DocumentosController::ImprimirNota_de_venta( 
 			
			
			isset($_GET['id_venta'] ) ? $_GET['id_venta'] : null,
			isset($_GET['id_impresora'] ) ? $_GET['id_impresora'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
