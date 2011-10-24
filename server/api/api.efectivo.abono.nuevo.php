<?php
/**
  * GET api/efectivo/abono/nuevo
  * Crea un nuevo abono
  *
  * Se crea un  nuevo abono, la caja o sucursal y el usuario que reciben el abono se tomaran de la sesion. La fecha se tomara del servidor
  *
  *
  *
  **/

  class ApiEfectivoAbonoNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"monto" => new ApiExposedProperty("monto", true, GET, array( "float" )),
			"id_deudor" => new ApiExposedProperty("id_deudor", true, GET, array( "int" )),
			"tipo_pago" => new ApiExposedProperty("tipo_pago", true, GET, array( "json" )),
			"id_prestamo" => new ApiExposedProperty("id_prestamo", false, GET, array( "int" )),
			"cheques" => new ApiExposedProperty("cheques", false, GET, array( "json" )),
			"billetes" => new ApiExposedProperty("billetes", false, GET, array( "json" )),
			"id_compra" => new ApiExposedProperty("id_compra", false, GET, array( "int" )),
			"id_venta" => new ApiExposedProperty("id_venta", false, GET, array( "int" )),
			"nota" => new ApiExposedProperty("nota", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::NuevoAbono( 
 			
			
			isset($_GET['monto'] ) ? $_GET['monto'] : null,
			isset($_GET['id_deudor'] ) ? $_GET['id_deudor'] : null,
			isset($_GET['tipo_pago'] ) ? $_GET['tipo_pago'] : null,
			isset($_GET['id_prestamo'] ) ? $_GET['id_prestamo'] : null,
			isset($_GET['cheques'] ) ? $_GET['cheques'] : null,
			isset($_GET['billetes'] ) ? $_GET['billetes'] : null,
			isset($_GET['id_compra'] ) ? $_GET['id_compra'] : null,
			isset($_GET['id_venta'] ) ? $_GET['id_venta'] : null,
			isset($_GET['nota'] ) ? $_GET['nota'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
