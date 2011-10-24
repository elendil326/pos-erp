<?php
/**
  * POST api/servicios/orden/nueva
  * Una nueva orden de servicio a prestar.
  *
  * Una nueva orden de servicio a prestar. Este debe ser un servicio activo. Y prestable desde la sucursal desde donde se inicio la llamada. Los conceptos a llenar estan definidos por el concepto. Se guardara el id del agente que inicio la orden y el id del cliente. La fecha se tomara del servidor.
  *
  *
  *
  **/

  class ApiServiciosOrdenNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_cliente" => new ApiExposedProperty("id_cliente", true, POST, array( "int" )),
			"id_servicio" => new ApiExposedProperty("id_servicio", true, POST, array( "int" )),
			"fecha_entrega" => new ApiExposedProperty("fecha_entrega", true, POST, array( "string" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "string" )),
			"adelanto" => new ApiExposedProperty("adelanto", false, POST, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::NuevaOrden( 
 			
			
			isset($_POST['id_cliente'] ) ? $_POST['id_cliente'] : null,
			isset($_POST['id_servicio'] ) ? $_POST['id_servicio'] : null,
			isset($_POST['fecha_entrega'] ) ? $_POST['fecha_entrega'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null,
			isset($_POST['adelanto'] ) ? $_POST['adelanto'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
