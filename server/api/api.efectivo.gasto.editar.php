<?php
/**
  * GET api/efectivo/gasto/editar
  * Editar los detalles de un gasto.
  *
  * Editar los detalles de un gasto.
Update :  Tambien se deberia de tomar  de la sesion el id del usuario qeu hiso al ultima modificacion y una fecha de ultima modificacion.
  *
  *
  *
  **/

  class ApiEfectivoGastoEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_gasto" => new ApiExposedProperty("id_gasto", true, GET, array( "int" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"fecha_gasto" => new ApiExposedProperty("fecha_gasto", false, GET, array( "string" )),
			"folio" => new ApiExposedProperty("folio", false, GET, array( "string" )),
			"id_concepto_gasto" => new ApiExposedProperty("id_concepto_gasto", false, GET, array( "int" )),
			"nota" => new ApiExposedProperty("nota", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::EditarGasto( 
 			
			
			isset($_GET['id_gasto'] ) ? $_GET['id_gasto'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['fecha_gasto'] ) ? $_GET['fecha_gasto'] : null,
			isset($_GET['folio'] ) ? $_GET['folio'] : null,
			isset($_GET['id_concepto_gasto'] ) ? $_GET['id_concepto_gasto'] : null,
			isset($_GET['nota'] ) ? $_GET['nota'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
