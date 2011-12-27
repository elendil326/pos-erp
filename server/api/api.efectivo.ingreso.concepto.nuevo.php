<?php
/**
  * GET api/efectivo/ingreso/concepto/nuevo
  * Crear un nuevo concepto de ingreso
  *
  * Crea un nuevo concepto de ingreso

Update : En la respuesta basta con solo indicar success : true | false, y en caso de fallo indicar el por que.
  *
  *
  *
  **/

  class ApiEfectivoIngresoConceptoNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"nombre" => new ApiExposedProperty("nombre", true, GET, array( "string" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"monto" => new ApiExposedProperty("monto", false, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::NuevoConceptoIngreso( 
 			
			
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['monto'] ) ? $_GET['monto'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
