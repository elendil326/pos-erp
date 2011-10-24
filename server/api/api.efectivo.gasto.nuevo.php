<?php
/**
  * GET api/efectivo/gasto/nuevo
  * Registrar un gasto.
  *
  * Registrar un gasto. El usuario y la sucursal que lo registran ser?tomados de la sesi?ctual.

Update :Ademas deber?tambi?de tomar la fecha de ingreso del gasto del servidor y agregar tambi?como par?tro una fecha a la cual se deber?de aplicar el gasto. Por ejemplo si el d?09/09/11 (viernes) se tomo dinero para pagar la luz, pero resulta que ese d?se olvidaron de registrar el gasto y lo registran el 12/09/11 (lunes). Entonces tambien se deberia de tomar como parametro una fecha a la cual aplicar el gasto, tambien se deberia de enviar como parametro una nota
  *
  *
  *
  **/

  class ApiEfectivoGastoNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"fecha_gasto" => new ApiExposedProperty("fecha_gasto", true, GET, array( "string" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", true, GET, array( "int" )),
			"monto" => new ApiExposedProperty("monto", false, GET, array( "float" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"id_caja" => new ApiExposedProperty("id_caja", false, GET, array( "int" )),
			"id_orden_de_servicio" => new ApiExposedProperty("id_orden_de_servicio", false, GET, array( "int" )),
			"id_concepto_gasto" => new ApiExposedProperty("id_concepto_gasto", false, GET, array( "int" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"folio" => new ApiExposedProperty("folio", false, GET, array( "string" )),
			"nota" => new ApiExposedProperty("nota", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::NuevoGasto( 
 			
			
			isset($_GET['fecha_gasto'] ) ? $_GET['fecha_gasto'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['monto'] ) ? $_GET['monto'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null,
			isset($_GET['id_orden_de_servicio'] ) ? $_GET['id_orden_de_servicio'] : null,
			isset($_GET['id_concepto_gasto'] ) ? $_GET['id_concepto_gasto'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['folio'] ) ? $_GET['folio'] : null,
			isset($_GET['nota'] ) ? $_GET['nota'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
