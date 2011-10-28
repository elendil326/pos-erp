<?php
/**
  * GET api/efectivo/abono/editar
  * Edita un abono
  *
  * Edita la informaci?n de un abono
  *
  *
  *
  **/

  class ApiEfectivoAbonoEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_abono" => new ApiExposedProperty("id_abono", true, GET, array( "int" )),
			"motivo_cancelacion" => new ApiExposedProperty("motivo_cancelacion", false, GET, array( "string" )),
			"nota" => new ApiExposedProperty("nota", false, GET, array( "string" )),
			"compra" => new ApiExposedProperty("compra", false, GET, array( "bool" )),
			"venta" => new ApiExposedProperty("venta", false, GET, array( "bool" )),
			"prestamo" => new ApiExposedProperty("prestamo", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::EditarAbono( 
 			
			
			isset($_GET['id_abono'] ) ? $_GET['id_abono'] : null,
			isset($_GET['motivo_cancelacion'] ) ? $_GET['motivo_cancelacion'] : null,
			isset($_GET['nota'] ) ? $_GET['nota'] : null,
			isset($_GET['compra'] ) ? $_GET['compra'] : null,
			isset($_GET['venta'] ) ? $_GET['venta'] : null,
			isset($_GET['prestamo'] ) ? $_GET['prestamo'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
