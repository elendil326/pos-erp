<?php
/**
  * GET api/proveedor/nuevo
  * Crea un nuevo proveedor
  *
  * Crea un nuevo proveedor
  *
  *
  *
  **/

  class ApiProveedorNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"codigo_proveedor" => new ApiExposedProperty("codigo_proveedor", true, GET, array( "string" )),
			"id_tipo_proveedor" => new ApiExposedProperty("id_tipo_proveedor", true, GET, array( "int" )),
			"nombre" => new ApiExposedProperty("nombre", true, GET, array( "string" )),
			"password" => new ApiExposedProperty("password", true, GET, array( "string" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
			"cuenta_bancaria" => new ApiExposedProperty("cuenta_bancaria", false, GET, array( "string" )),
			"dias_de_credito" => new ApiExposedProperty("dias_de_credito", false, GET, array( "int" )),
			"dias_embarque" => new ApiExposedProperty("dias_embarque", false, GET, array( "int" )),
			"direcciones" => new ApiExposedProperty("direcciones", false, GET, array( "json" )),
			"direccion_web" => new ApiExposedProperty("direccion_web", false, GET, array( "string" )),
			"email" => new ApiExposedProperty("email", false, GET, array( "string" )),
			"id_moneda" => new ApiExposedProperty("id_moneda", false, GET, array( "int" )),
			"id_tarifa_compra" => new ApiExposedProperty("id_tarifa_compra", false, GET, array( "int" )),
			"id_tarifa_venta" => new ApiExposedProperty("id_tarifa_venta", false, GET, array( "int" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"limite_credito" => new ApiExposedProperty("limite_credito", false, GET, array( "float" )),
			"representante_legal" => new ApiExposedProperty("representante_legal", false, GET, array( "string" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, GET, array( "json" )),
			"rfc" => new ApiExposedProperty("rfc", false, GET, array( "string" )),
			"telefono_personal1" => new ApiExposedProperty("telefono_personal1", false, GET, array( "string" )),
			"telefono_personal2" => new ApiExposedProperty("telefono_personal2", false, GET, array( "string" )),
			"tiempo_entrega" => new ApiExposedProperty("tiempo_entrega", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProveedoresController::Nuevo( 
 			
			
			isset($_GET['codigo_proveedor'] ) ? $_GET['codigo_proveedor'] : null,
			isset($_GET['id_tipo_proveedor'] ) ? $_GET['id_tipo_proveedor'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['password'] ) ? $_GET['password'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null,
			isset($_GET['cuenta_bancaria'] ) ? $_GET['cuenta_bancaria'] : null,
			isset($_GET['dias_de_credito'] ) ? $_GET['dias_de_credito'] : null,
			isset($_GET['dias_embarque'] ) ? $_GET['dias_embarque'] : null,
			isset($_GET['direcciones'] ) ? json_decode($_GET['direcciones']) : null,
			isset($_GET['direccion_web'] ) ? $_GET['direccion_web'] : null,
			isset($_GET['email'] ) ? $_GET['email'] : null,
			isset($_GET['id_moneda'] ) ? $_GET['id_moneda'] : null,
			isset($_GET['id_tarifa_compra'] ) ? $_GET['id_tarifa_compra'] : null,
			isset($_GET['id_tarifa_venta'] ) ? $_GET['id_tarifa_venta'] : null,
			isset($_GET['impuestos'] ) ? json_decode($_GET['impuestos']) : null,
			isset($_GET['limite_credito'] ) ? $_GET['limite_credito'] : null,
			isset($_GET['representante_legal'] ) ? $_GET['representante_legal'] : null,
			isset($_GET['retenciones'] ) ? json_decode($_GET['retenciones']) : null,
			isset($_GET['rfc'] ) ? $_GET['rfc'] : null,
			isset($_GET['telefono_personal1'] ) ? $_GET['telefono_personal1'] : null,
			isset($_GET['telefono_personal2'] ) ? $_GET['telefono_personal2'] : null,
			isset($_GET['tiempo_entrega'] ) ? $_GET['tiempo_entrega'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
