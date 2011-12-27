<?php
/**
  * GET api/efectivo/gasto/lista
  * Lista los gastos
  *
  * Lista los gastos, se puede filtrar de acuerdo a la empresa, la sucursal, el usuario que registra el gasto, el concepto de gasto, la orden de servicio, la caja de la cual se sustrajo el dinero para pagar el gasto, de una fecha inicial a una final, por monto, por cancelacion, y se puede ordenar de acuerdo a ss atributos.
  *
  *
  *
  **/

  class ApiEfectivoGastoLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"cancelado" => new ApiExposedProperty("cancelado", false, GET, array( "bool" )),
			"fecha_actual" => new ApiExposedProperty("fecha_actual", false, GET, array( "bool" )),
			"fecha_final" => new ApiExposedProperty("fecha_final", false, GET, array( "string" )),
			"fecha_inicial" => new ApiExposedProperty("fecha_inicial", false, GET, array( "string" )),
			"id_caja" => new ApiExposedProperty("id_caja", false, GET, array( "int" )),
			"id_concepto_gasto" => new ApiExposedProperty("id_concepto_gasto", false, GET, array( "int" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_orden_servicio" => new ApiExposedProperty("id_orden_servicio", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"id_usuario" => new ApiExposedProperty("id_usuario", false, GET, array( "int" )),
			"monto_maximo" => new ApiExposedProperty("monto_maximo", false, GET, array( "float" )),
			"monto_minimo" => new ApiExposedProperty("monto_minimo", false, GET, array( "float" )),
			"orden" => new ApiExposedProperty("orden", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::ListaGasto( 
 			
			
			isset($_GET['cancelado'] ) ? $_GET['cancelado'] : null,
			isset($_GET['fecha_actual'] ) ? $_GET['fecha_actual'] : null,
			isset($_GET['fecha_final'] ) ? $_GET['fecha_final'] : null,
			isset($_GET['fecha_inicial'] ) ? $_GET['fecha_inicial'] : null,
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null,
			isset($_GET['id_concepto_gasto'] ) ? $_GET['id_concepto_gasto'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_orden_servicio'] ) ? $_GET['id_orden_servicio'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['id_usuario'] ) ? $_GET['id_usuario'] : null,
			isset($_GET['monto_maximo'] ) ? $_GET['monto_maximo'] : null,
			isset($_GET['monto_minimo'] ) ? $_GET['monto_minimo'] : null,
			isset($_GET['orden'] ) ? $_GET['orden'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
