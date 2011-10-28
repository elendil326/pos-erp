<?php
/**
  * GET api/sucursal/lista
  * Lista todas las sucursales existentes.
  *
  * Lista las sucursales relacionadas con esta instancia. Se puede filtrar por empresa,  saldo inferior o superior a, fecha de apertura, ordenar por fecha de apertura u ordenar por saldo. Se agregar? un link en cada una para poder acceder a su detalle.
  *
  *
  *
  **/

  class ApiSucursalLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"saldo_inferior_que" => new ApiExposedProperty("saldo_inferior_que", false, GET, array( "float" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"saldo_superior_que" => new ApiExposedProperty("saldo_superior_que", false, GET, array( "float" )),
			"fecha_apertura_inferior_que" => new ApiExposedProperty("fecha_apertura_inferior_que", false, GET, array( "string" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
			"fecha_apertura_superior_que" => new ApiExposedProperty("fecha_apertura_superior_que", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::Lista( 
 			
			
			isset($_GET['saldo_inferior_que'] ) ? $_GET['saldo_inferior_que'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['saldo_superior_que'] ) ? $_GET['saldo_superior_que'] : null,
			isset($_GET['fecha_apertura_inferior_que'] ) ? $_GET['fecha_apertura_inferior_que'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null,
			isset($_GET['fecha_apertura_superior_que'] ) ? $_GET['fecha_apertura_superior_que'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
