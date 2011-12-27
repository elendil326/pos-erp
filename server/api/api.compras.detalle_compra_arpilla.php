<?php
/**
  * GET api/compras/detalle_compra_arpilla
  * Muestra el detalle de una compra por arpillas
  *
  * Muestra el detalle de una compra por arpillas. Este detalle no es el detalle por producto, este muestra los detalles por embarque de la compra. Para el detalle por producto refierase a api/compras/detalle

Update : Todo este metodo esta mal, habria que definir nuevamente como se van a manejar las compras a los proveedores ya que como esta definido aqui solo funcionaria para el POS de las papas.
  *
  *
  *
  **/

  class ApiComprasDetalleCompraArpilla extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_compra" => new ApiExposedProperty("id_compra", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ComprasController::Detalle_compra_arpilla( 
 			
			
			isset($_GET['id_compra'] ) ? $_GET['id_compra'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
