<?php
/**
  * GET api/compras/nueva_compra_arpilla
  * Compra por arpillas
  *
  * Registra una nueva compra por arpillas. Este metodo tiene que usarse en conjunto con el metodo api/compras/nueva
Update : Todo este metodo esta mal, habria que definir nuevamente como se van a manejar las compras a los proveedores ya que como esta definido aqui solo funcionaria para el POS de las papas.
  *
  *
  *
  **/

  class ApiComprasNuevaCompraArpilla extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"peso_por_arpilla" => new ApiExposedProperty("peso_por_arpilla", true, GET, array( "float" )),
			"arpillas" => new ApiExposedProperty("arpillas", true, GET, array( "float" )),
			"peso_recibido" => new ApiExposedProperty("peso_recibido", true, GET, array( "float" )),
			"id_compra" => new ApiExposedProperty("id_compra", true, GET, array( "int" )),
			"total_origen" => new ApiExposedProperty("total_origen", true, GET, array( "float" )),
			"merma_por_arpilla" => new ApiExposedProperty("merma_por_arpilla", true, GET, array( "float" )),
			"numero_de_viaje" => new ApiExposedProperty("numero_de_viaje", false, GET, array( "string" )),
			"folio" => new ApiExposedProperty("folio", false, GET, array( "string" )),
			"peso_origen" => new ApiExposedProperty("peso_origen", false, GET, array( "float" )),
			"fecha_origen" => new ApiExposedProperty("fecha_origen", false, GET, array( "string" )),
			"productor" => new ApiExposedProperty("productor", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ComprasController::Nueva_compra_arpilla( 
 			
			
			isset($_GET['peso_por_arpilla'] ) ? $_GET['peso_por_arpilla'] : null,
			isset($_GET['arpillas'] ) ? $_GET['arpillas'] : null,
			isset($_GET['peso_recibido'] ) ? $_GET['peso_recibido'] : null,
			isset($_GET['id_compra'] ) ? $_GET['id_compra'] : null,
			isset($_GET['total_origen'] ) ? $_GET['total_origen'] : null,
			isset($_GET['merma_por_arpilla'] ) ? $_GET['merma_por_arpilla'] : null,
			isset($_GET['numero_de_viaje'] ) ? $_GET['numero_de_viaje'] : null,
			isset($_GET['folio'] ) ? $_GET['folio'] : null,
			isset($_GET['peso_origen'] ) ? $_GET['peso_origen'] : null,
			isset($_GET['fecha_origen'] ) ? $_GET['fecha_origen'] : null,
			isset($_GET['productor'] ) ? $_GET['productor'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
