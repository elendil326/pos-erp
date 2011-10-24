<?php
/**
  * GET api/proveedor/editar
  * Edita la informacion de un proveedor
  *
  * Edita la informacion de un proveedor. 
  *
  *
  *
  **/

  class ApiProveedorEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_proveedor" => new ApiExposedProperty("id_proveedor", true, GET, array( "int" )),
			"limite_credito" => new ApiExposedProperty("limite_credito", false, GET, array( "float" )),
			"password" => new ApiExposedProperty("password", false, GET, array( "string" )),
			"tiempo_entrega" => new ApiExposedProperty("tiempo_entrega", false, GET, array( "int" )),
			"codigo_postal" => new ApiExposedProperty("codigo_postal", false, GET, array( "string" )),
			"id_ciudad" => new ApiExposedProperty("id_ciudad", false, GET, array( "int" )),
			"texto_extra" => new ApiExposedProperty("texto_extra", false, GET, array( "string" )),
			"direccion_web" => new ApiExposedProperty("direccion_web", false, GET, array( "string" )),
			"numero_interior" => new ApiExposedProperty("numero_interior", false, GET, array( "string" )),
			"numero_exterior" => new ApiExposedProperty("numero_exterior", false, GET, array( "string" )),
			"representante_legal" => new ApiExposedProperty("representante_legal", false, GET, array( "string" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
			"rfc" => new ApiExposedProperty("rfc", false, GET, array( "string" )),
			"id_tipo_proveedor" => new ApiExposedProperty("id_tipo_proveedor", false, GET, array( "int" )),
			"dias_de_credito" => new ApiExposedProperty("dias_de_credito", false, GET, array( "int" )),
			"calle" => new ApiExposedProperty("calle", false, GET, array( "string" )),
			"telefono_personal" => new ApiExposedProperty("telefono_personal", false, GET, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", false, GET, array( "string" )),
			"email" => new ApiExposedProperty("email", false, GET, array( "string" )),
			"dias_embarque" => new ApiExposedProperty("dias_embarque", false, GET, array( "int" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"telefono2" => new ApiExposedProperty("telefono2", false, GET, array( "string" )),
			"telefono1" => new ApiExposedProperty("telefono1", false, GET, array( "string" )),
			"cuenta_bancaria" => new ApiExposedProperty("cuenta_bancaria", false, GET, array( "string" )),
			"id_moneda" => new ApiExposedProperty("id_moneda", false, GET, array( "int" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, GET, array( "json" )),
			"codigo_proveedor" => new ApiExposedProperty("codigo_proveedor", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProveedoresController::Editar( 
 			
			
			isset($_GET['id_proveedor'] ) ? $_GET['id_proveedor'] : null,
			isset($_GET['limite_credito'] ) ? $_GET['limite_credito'] : null,
			isset($_GET['password'] ) ? $_GET['password'] : null,
			isset($_GET['tiempo_entrega'] ) ? $_GET['tiempo_entrega'] : null,
			isset($_GET['codigo_postal'] ) ? $_GET['codigo_postal'] : null,
			isset($_GET['id_ciudad'] ) ? $_GET['id_ciudad'] : null,
			isset($_GET['texto_extra'] ) ? $_GET['texto_extra'] : null,
			isset($_GET['direccion_web'] ) ? $_GET['direccion_web'] : null,
			isset($_GET['numero_interior'] ) ? $_GET['numero_interior'] : null,
			isset($_GET['numero_exterior'] ) ? $_GET['numero_exterior'] : null,
			isset($_GET['representante_legal'] ) ? $_GET['representante_legal'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null,
			isset($_GET['rfc'] ) ? $_GET['rfc'] : null,
			isset($_GET['id_tipo_proveedor'] ) ? $_GET['id_tipo_proveedor'] : null,
			isset($_GET['dias_de_credito'] ) ? $_GET['dias_de_credito'] : null,
			isset($_GET['calle'] ) ? $_GET['calle'] : null,
			isset($_GET['telefono_personal'] ) ? $_GET['telefono_personal'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['email'] ) ? $_GET['email'] : null,
			isset($_GET['dias_embarque'] ) ? $_GET['dias_embarque'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['telefono2'] ) ? $_GET['telefono2'] : null,
			isset($_GET['telefono1'] ) ? $_GET['telefono1'] : null,
			isset($_GET['cuenta_bancaria'] ) ? $_GET['cuenta_bancaria'] : null,
			isset($_GET['id_moneda'] ) ? $_GET['id_moneda'] : null,
			isset($_GET['retenciones'] ) ? $_GET['retenciones'] : null,
			isset($_GET['codigo_proveedor'] ) ? $_GET['codigo_proveedor'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
