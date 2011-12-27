<?php
/**
  * GET api/efectivo/abono/eliminar
  * Elimina un abono
  *
  * Cancela un abono
  *
  *
  *
  **/

  class ApiEfectivoAbonoEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_abono" => new ApiExposedProperty("id_abono", true, GET, array( "int" )),
			"billetes" => new ApiExposedProperty("billetes", false, GET, array( "json" )),
			"compra" => new ApiExposedProperty("compra", false, GET, array( "bool" )),
			"id_caja" => new ApiExposedProperty("id_caja", false, GET, array( "int" )),
			"motivo_cancelacion" => new ApiExposedProperty("motivo_cancelacion", false, GET, array( "string" )),
			"prestamo" => new ApiExposedProperty("prestamo", false, GET, array( "bool" )),
			"venta" => new ApiExposedProperty("venta", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::EliminarAbono( 
 			
			
			isset($_GET['id_abono'] ) ? $_GET['id_abono'] : null,
			isset($_GET['billetes'] ) ? $_GET['billetes'] : null,
			isset($_GET['compra'] ) ? $_GET['compra'] : null,
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null,
			isset($_GET['motivo_cancelacion'] ) ? $_GET['motivo_cancelacion'] : null,
			isset($_GET['prestamo'] ) ? $_GET['prestamo'] : null,
			isset($_GET['venta'] ) ? $_GET['venta'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
