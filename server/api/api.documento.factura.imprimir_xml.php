<?php
/**
  * GET api/documento/factura/imprimir_xml
  * Imprime el xml de una factura
  *
  * Imprime el xml de una factura.

Update : No se si este metodo tenga una utilidad real, ya que cuando se recibe el XML timbrado, se crea el archivo .xml y en el unico momento que se vuelve a ocupar es para enviarlo por correo al cliente.
  *
  *
  *
  **/

  class ApiDocumentoFacturaImprimirXml extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = DocumentosController::Imprimir_xmlFactura( 
 			
		
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
