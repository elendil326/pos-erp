<?php
/**
  * GET api/efectivo/ingreso/editar
  * Edita un ingreso
  *
  * Edita un ingreso

Update :El usuario y la fecha de la ultima modificaci?n se deber?an de obtener de la sesi?n
  *
  *
  *
  **/

  class ApiEfectivoIngresoEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_ingreso" => new ApiExposedProperty("id_ingreso", true, GET, array( "int" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"fecha_ingreso" => new ApiExposedProperty("fecha_ingreso", false, GET, array( "string" )),
			"folio" => new ApiExposedProperty("folio", false, GET, array( "string" )),
			"id_concepto_ingreso" => new ApiExposedProperty("id_concepto_ingreso", false, GET, array( "int" )),
			"nota" => new ApiExposedProperty("nota", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::EditarIngreso( 
 			
			
			isset($_GET['id_ingreso'] ) ? $_GET['id_ingreso'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['fecha_ingreso'] ) ? $_GET['fecha_ingreso'] : null,
			isset($_GET['folio'] ) ? $_GET['folio'] : null,
			isset($_GET['id_concepto_ingreso'] ) ? $_GET['id_concepto_ingreso'] : null,
			isset($_GET['nota'] ) ? $_GET['nota'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
