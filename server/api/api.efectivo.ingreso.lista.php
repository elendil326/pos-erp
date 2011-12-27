<?php
/**
  * GET api/efectivo/ingreso/lista
  * Lista los ingresos
  *
  * Lista los ingresos, se puede filtrar de acuerdo a la empresa, la sucursal, el usuario que registra el ingreso, el concepto de ingreso, la caja que recibi? el ingreso, de una fecha inicial a una final, por monto, por cancelacion, y se puede ordenar de acuerdo a sus atributos.
  *
  *
  *
  **/

  class ApiEfectivoIngresoLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"cancelado" => new ApiExposedProperty("cancelado", false, GET, array( "bool" )),
			"fecha_actual" => new ApiExposedProperty("fecha_actual", false, GET, array( "bool" )),
			"fecha_final" => new ApiExposedProperty("fecha_final", false, GET, array( "string" )),
			"fecha_inicial" => new ApiExposedProperty("fecha_inicial", false, GET, array( "string" )),
			"id_caja" => new ApiExposedProperty("id_caja", false, GET, array( "int" )),
			"id_concepto_ingreso" => new ApiExposedProperty("id_concepto_ingreso", false, GET, array( "int" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"id_usuario" => new ApiExposedProperty("id_usuario", false, GET, array( "int" )),
			"monto_maximo" => new ApiExposedProperty("monto_maximo", false, GET, array( "float" )),
			"monto_minimo" => new ApiExposedProperty("monto_minimo", false, GET, array( "float" )),
			"orden" => new ApiExposedProperty("orden", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::ListaIngreso( 
 			
			
			isset($_GET['cancelado'] ) ? $_GET['cancelado'] : null,
			isset($_GET['fecha_actual'] ) ? $_GET['fecha_actual'] : null,
			isset($_GET['fecha_final'] ) ? $_GET['fecha_final'] : null,
			isset($_GET['fecha_inicial'] ) ? $_GET['fecha_inicial'] : null,
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null,
			isset($_GET['id_concepto_ingreso'] ) ? $_GET['id_concepto_ingreso'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
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
  
  
  
  
  
  
