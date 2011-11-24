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
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tipo_proveedor" => new ApiExposedProperty("id_tipo_proveedor", true, GET, array( "int" )),
			"nombre" => new ApiExposedProperty("nombre", true, GET, array( "string" )),
			"password" => new ApiExposedProperty("password", true, GET, array( "string" )),
			"codigo_proveedor" => new ApiExposedProperty("codigo_proveedor", true, GET, array( "string" )),
			"dias_de_credito" => new ApiExposedProperty("dias_de_credito", false, GET, array( "int" )),
			"limite_credito" => new ApiExposedProperty("limite_credito", false, GET, array( "float" )),
			"codigo_postal" => new ApiExposedProperty("codigo_postal", false, GET, array( "string" )),
			"id_ciudad" => new ApiExposedProperty("id_ciudad", false, GET, array( "int" )),
			"texto_extra" => new ApiExposedProperty("texto_extra", false, GET, array( "string" )),
			"numero_interior" => new ApiExposedProperty("numero_interior", false, GET, array( "string" )),
			"numero_exterior" => new ApiExposedProperty("numero_exterior", false, GET, array( "string" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, GET, array( "json" )),
			"direccion_web" => new ApiExposedProperty("direccion_web", false, GET, array( "string" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"dias_embarque" => new ApiExposedProperty("dias_embarque", false, GET, array( "int" )),
			"telefono_personal1" => new ApiExposedProperty("telefono_personal1", false, GET, array( "string" )),
			"rfc" => new ApiExposedProperty("rfc", false, GET, array( "string" )),
			"calle" => new ApiExposedProperty("calle", false, GET, array( "string" )),
			"email" => new ApiExposedProperty("email", false, GET, array( "string" )),
			"id_moneda" => new ApiExposedProperty("id_moneda", false, GET, array( "int" )),
			"telefono_personal2" => new ApiExposedProperty("telefono_personal2", false, GET, array( "string" )),
			"colonia" => new ApiExposedProperty("colonia", false, GET, array( "string" )),
			"telefono2" => new ApiExposedProperty("telefono2", false, GET, array( "string" )),
			"telefono1" => new ApiExposedProperty("telefono1", false, GET, array( "string" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
			"cuenta_bancaria" => new ApiExposedProperty("cuenta_bancaria", false, GET, array( "string" )),
			"representante_legal" => new ApiExposedProperty("representante_legal", false, GET, array( "string" )),
			"tiempo_entrega" => new ApiExposedProperty("tiempo_entrega", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProveedoresController::Nuevo( 
 			
			
			isset($_GET['id_tipo_proveedor'] ) ? $_GET['id_tipo_proveedor'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['password'] ) ? $_GET['password'] : null,
			isset($_GET['codigo_proveedor'] ) ? $_GET['codigo_proveedor'] : null,
			isset($_GET['dias_de_credito'] ) ? $_GET['dias_de_credito'] : null,
			isset($_GET['limite_credito'] ) ? $_GET['limite_credito'] : null,
			isset($_GET['codigo_postal'] ) ? $_GET['codigo_postal'] : null,
			isset($_GET['id_ciudad'] ) ? $_GET['id_ciudad'] : null,
			isset($_GET['texto_extra'] ) ? $_GET['texto_extra'] : null,
			isset($_GET['numero_interior'] ) ? $_GET['numero_interior'] : null,
			isset($_GET['numero_exterior'] ) ? $_GET['numero_exterior'] : null,
			isset($_GET['retenciones'] ) ? $_GET['retenciones'] : null,
			isset($_GET['direccion_web'] ) ? $_GET['direccion_web'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['dias_embarque'] ) ? $_GET['dias_embarque'] : null,
			isset($_GET['telefono_personal1'] ) ? $_GET['telefono_personal1'] : null,
			isset($_GET['rfc'] ) ? $_GET['rfc'] : null,
			isset($_GET['calle'] ) ? $_GET['calle'] : null,
			isset($_GET['email'] ) ? $_GET['email'] : null,
			isset($_GET['id_moneda'] ) ? $_GET['id_moneda'] : null,
			isset($_GET['telefono_personal2'] ) ? $_GET['telefono_personal2'] : null,
			isset($_GET['colonia'] ) ? $_GET['colonia'] : null,
			isset($_GET['telefono2'] ) ? $_GET['telefono2'] : null,
			isset($_GET['telefono1'] ) ? $_GET['telefono1'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null,
			isset($_GET['cuenta_bancaria'] ) ? $_GET['cuenta_bancaria'] : null,
			isset($_GET['representante_legal'] ) ? $_GET['representante_legal'] : null,
			isset($_GET['tiempo_entrega'] ) ? $_GET['tiempo_entrega'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }

  
  
  
  
  
  
