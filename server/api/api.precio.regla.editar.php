<?php
/**
  * POST api/precio/regla/editar
  * Edita la informaciond e una regla
  *
  * Edita la informacion basica de una regla. 

Los parametros recibidos seran tomados para edicion.

?Sera necesario dar la oportunidad al usuario de cambiar la version a la que pertence la regla?
  *
  *
  *
  **/

  class ApiPrecioReglaEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_regla" => new ApiExposedProperty("id_regla", true, POST, array( "int" )),
			"cantidad_minima" => new ApiExposedProperty("cantidad_minima", false, POST, array( "int" )),
			"id_clasificacion_producto" => new ApiExposedProperty("id_clasificacion_producto", false, POST, array( "int" )),
			"id_clasificacion_servicio" => new ApiExposedProperty("id_clasificacion_servicio", false, POST, array( "int" )),
			"id_paquete" => new ApiExposedProperty("id_paquete", false, POST, array( "int" )),
			"id_producto" => new ApiExposedProperty("id_producto", false, POST, array( "int" )),
			"id_servicio" => new ApiExposedProperty("id_servicio", false, POST, array( "int" )),
			"id_tarifa" => new ApiExposedProperty("id_tarifa", false, POST, array( "int" )),
			"id_unidad" => new ApiExposedProperty("id_unidad", false, POST, array( "int" )),
			"margen_max" => new ApiExposedProperty("margen_max", false, POST, array( "float" )),
			"margen_min" => new ApiExposedProperty("margen_min", false, POST, array( "float" )),
			"metodo_redondeo" => new ApiExposedProperty("metodo_redondeo", false, POST, array( "float" )),
			"nombre" => new ApiExposedProperty("nombre", false, POST, array( "string" )),
			"porcentaje_utilidad" => new ApiExposedProperty("porcentaje_utilidad", false, POST, array( "float" )),
			"secuencia" => new ApiExposedProperty("secuencia", false, POST, array( "int" )),
			"utilidad_neta" => new ApiExposedProperty("utilidad_neta", false, POST, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::EditarRegla( 
 			
			
			isset($_POST['id_regla'] ) ? $_POST['id_regla'] : null,
			isset($_POST['cantidad_minima'] ) ? $_POST['cantidad_minima'] : null,
			isset($_POST['id_clasificacion_producto'] ) ? $_POST['id_clasificacion_producto'] : null,
			isset($_POST['id_clasificacion_servicio'] ) ? $_POST['id_clasificacion_servicio'] : null,
			isset($_POST['id_paquete'] ) ? $_POST['id_paquete'] : null,
			isset($_POST['id_producto'] ) ? $_POST['id_producto'] : null,
			isset($_POST['id_servicio'] ) ? $_POST['id_servicio'] : null,
			isset($_POST['id_tarifa'] ) ? $_POST['id_tarifa'] : null,
			isset($_POST['id_unidad'] ) ? $_POST['id_unidad'] : null,
			isset($_POST['margen_max'] ) ? $_POST['margen_max'] : null,
			isset($_POST['margen_min'] ) ? $_POST['margen_min'] : null,
			isset($_POST['metodo_redondeo'] ) ? $_POST['metodo_redondeo'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['porcentaje_utilidad'] ) ? $_POST['porcentaje_utilidad'] : null,
			isset($_POST['secuencia'] ) ? $_POST['secuencia'] : null,
			isset($_POST['utilidad_neta'] ) ? $_POST['utilidad_neta'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
