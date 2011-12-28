<?php
/**
  * GET api/efectivo/abono/lista
  * Lista los abonos
  *
  * Lista los abonos, puede filtrarse por empresa, por sucursal, por caja, por usuario que abona y puede ordenarse segun sus atributos
  *
  *
  *
  **/

  class ApiEfectivoAbonoLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"compra" => new ApiExposedProperty("compra", true, GET, array( "bool" )),
			"prestamo" => new ApiExposedProperty("prestamo", true, GET, array( "bool" )),
			"venta" => new ApiExposedProperty("venta", true, GET, array( "bool" )),
			"cancelado" => new ApiExposedProperty("cancelado", false, GET, array( "bool" )),
			"fecha_actual" => new ApiExposedProperty("fecha_actual", false, GET, array( "bool" )),
			"fecha_maxima" => new ApiExposedProperty("fecha_maxima", false, GET, array( "string" )),
			"fecha_minima" => new ApiExposedProperty("fecha_minima", false, GET, array( "string" )),
			"id_caja" => new ApiExposedProperty("id_caja", false, GET, array( "int" )),
			"id_compra" => new ApiExposedProperty("id_compra", false, GET, array( "int" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_prestamo" => new ApiExposedProperty("id_prestamo", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"id_usuario" => new ApiExposedProperty("id_usuario", false, GET, array( "int" )),
			"id_venta" => new ApiExposedProperty("id_venta", false, GET, array( "int" )),
			"monto_igual_a" => new ApiExposedProperty("monto_igual_a", false, GET, array( "float" )),
			"monto_mayor_a" => new ApiExposedProperty("monto_mayor_a", false, GET, array( "float" )),
			"monto_menor_a" => new ApiExposedProperty("monto_menor_a", false, GET, array( "float" )),
			"orden" => new ApiExposedProperty("orden", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::ListaAbono( 
 			
			
			isset($_GET['compra'] ) ? $_GET['compra'] : null,
			isset($_GET['prestamo'] ) ? $_GET['prestamo'] : null,
			isset($_GET['venta'] ) ? $_GET['venta'] : null,
			isset($_GET['cancelado'] ) ? $_GET['cancelado'] : null,
			isset($_GET['fecha_actual'] ) ? $_GET['fecha_actual'] : null,
			isset($_GET['fecha_maxima'] ) ? $_GET['fecha_maxima'] : null,
			isset($_GET['fecha_minima'] ) ? $_GET['fecha_minima'] : null,
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null,
			isset($_GET['id_compra'] ) ? $_GET['id_compra'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_prestamo'] ) ? $_GET['id_prestamo'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['id_usuario'] ) ? $_GET['id_usuario'] : null,
			isset($_GET['id_venta'] ) ? $_GET['id_venta'] : null,
			isset($_GET['monto_igual_a'] ) ? $_GET['monto_igual_a'] : null,
			isset($_GET['monto_mayor_a'] ) ? $_GET['monto_mayor_a'] : null,
			isset($_GET['monto_menor_a'] ) ? $_GET['monto_menor_a'] : null,
			isset($_GET['orden'] ) ? $_GET['orden'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
