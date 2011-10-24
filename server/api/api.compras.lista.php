<?php
/**
  * GET api/compras/lista
  * Lista las compras
  *
  * Lista las compras. Se puede filtrar por empresa, sucursal, caja, usuario que registra la compra, usuario al que se le compra, tipo de compra, si estan pagadas o no, por tipo de pago, canceladas o no, por el total, por fecha, por el tipo de pago y se puede ordenar por sus atributos.
  *
  *
  *
  **/

  class ApiComprasLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"tipo_pago" => new ApiExposedProperty("tipo_pago", false, GET, array( "string" )),
			"fecha_inicial" => new ApiExposedProperty("fecha_inicial", false, GET, array( "string" )),
			"id_vendedor_compra" => new ApiExposedProperty("id_vendedor_compra", false, GET, array( "int" )),
			"tipo_compra" => new ApiExposedProperty("tipo_compra", false, GET, array( "string" )),
			"id_caja" => new ApiExposedProperty("id_caja", false, GET, array( "int" )),
			"id_usuario" => new ApiExposedProperty("id_usuario", false, GET, array( "int" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"fecha_final" => new ApiExposedProperty("fecha_final", false, GET, array( "string" )),
			"total_maximo" => new ApiExposedProperty("total_maximo", false, GET, array( "float" )),
			"saldada" => new ApiExposedProperty("saldada", false, GET, array( "bool" )),
			"total_minimo" => new ApiExposedProperty("total_minimo", false, GET, array( "float" )),
			"cancelada" => new ApiExposedProperty("cancelada", false, GET, array( "bool" )),
			"orden" => new ApiExposedProperty("orden", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ComprasController::Lista( 
 			
			
			isset($_GET['tipo_pago'] ) ? $_GET['tipo_pago'] : null,
			isset($_GET['fecha_inicial'] ) ? $_GET['fecha_inicial'] : null,
			isset($_GET['id_vendedor_compra'] ) ? $_GET['id_vendedor_compra'] : null,
			isset($_GET['tipo_compra'] ) ? $_GET['tipo_compra'] : null,
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null,
			isset($_GET['id_usuario'] ) ? $_GET['id_usuario'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['fecha_final'] ) ? $_GET['fecha_final'] : null,
			isset($_GET['total_maximo'] ) ? $_GET['total_maximo'] : null,
			isset($_GET['saldada'] ) ? $_GET['saldada'] : null,
			isset($_GET['total_minimo'] ) ? $_GET['total_minimo'] : null,
			isset($_GET['cancelada'] ) ? $_GET['cancelada'] : null,
			isset($_GET['orden'] ) ? $_GET['orden'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
