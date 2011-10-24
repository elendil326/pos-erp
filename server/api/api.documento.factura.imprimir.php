<?php
/**
  * GET api/documento/factura/imprimir
  * Imprime una factura
  *
  * Imprime una factura
Update : La respuesta solo deber?de contener success :true | false, y en caso de error, su descripcion, no se necesita apra anda en el JSON de respuesta una propiedad factura.
  *
  *
  *
  **/

  class ApiDocumentoFacturaImprimir extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_folio" => new ApiExposedProperty("id_folio", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = DocumentosController::ImprimirFactura( 
 			
			
			isset($_GET['id_folio'] ) ? $_GET['id_folio'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
