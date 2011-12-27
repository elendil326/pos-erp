<?php
/**
  * GET api/servicios/editar
  * Edita un servicio
  *
  * Edita un servicio
  *
  *
  *
  **/

  class ApiServiciosEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_servicio" => new ApiExposedProperty("id_servicio", true, GET, array( "int" )),
			"clasificaciones" => new ApiExposedProperty("clasificaciones", false, GET, array( "json" )),
			"codigo_servicio" => new ApiExposedProperty("codigo_servicio", false, GET, array( "string" )),
			"compra_en_mostrador" => new ApiExposedProperty("compra_en_mostrador", false, GET, array( "string" )),
			"control_de_existencia" => new ApiExposedProperty("control_de_existencia", false, GET, array( "int" )),
			"costo_estandar" => new ApiExposedProperty("costo_estandar", false, GET, array( "float" )),
			"descripcion_servicio" => new ApiExposedProperty("descripcion_servicio", false, GET, array( "string" )),
			"empresas" => new ApiExposedProperty("empresas", false, GET, array( "string" )),
			"foto_servicio" => new ApiExposedProperty("foto_servicio", false, GET, array( "string" )),
			"garantia" => new ApiExposedProperty("garantia", false, GET, array( "int" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"margen_de_utilidad" => new ApiExposedProperty("margen_de_utilidad", false, GET, array( "string" )),
			"metodo_costeo" => new ApiExposedProperty("metodo_costeo", false, GET, array( "string" )),
			"nombre_servicio" => new ApiExposedProperty("nombre_servicio", false, GET, array( "string" )),
			"precio" => new ApiExposedProperty("precio", false, GET, array( "float" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, GET, array( "json" )),
			"sucursales" => new ApiExposedProperty("sucursales", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::Editar( 
 			
			
			isset($_GET['id_servicio'] ) ? $_GET['id_servicio'] : null,
			isset($_GET['clasificaciones'] ) ? $_GET['clasificaciones'] : null,
			isset($_GET['codigo_servicio'] ) ? $_GET['codigo_servicio'] : null,
			isset($_GET['compra_en_mostrador'] ) ? $_GET['compra_en_mostrador'] : null,
			isset($_GET['control_de_existencia'] ) ? $_GET['control_de_existencia'] : null,
			isset($_GET['costo_estandar'] ) ? $_GET['costo_estandar'] : null,
			isset($_GET['descripcion_servicio'] ) ? $_GET['descripcion_servicio'] : null,
			isset($_GET['empresas'] ) ? $_GET['empresas'] : null,
			isset($_GET['foto_servicio'] ) ? $_GET['foto_servicio'] : null,
			isset($_GET['garantia'] ) ? $_GET['garantia'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['margen_de_utilidad'] ) ? $_GET['margen_de_utilidad'] : null,
			isset($_GET['metodo_costeo'] ) ? $_GET['metodo_costeo'] : null,
			isset($_GET['nombre_servicio'] ) ? $_GET['nombre_servicio'] : null,
			isset($_GET['precio'] ) ? $_GET['precio'] : null,
			isset($_GET['retenciones'] ) ? $_GET['retenciones'] : null,
			isset($_GET['sucursales'] ) ? $_GET['sucursales'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
