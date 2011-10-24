<?php
/**
  * POST api/cliente/editar_perfil
  * Editar la informacion basica de un cliente.
  *
  * Edita la informaci?e un cliente. El campo fecha_ultima_modificacion ser?lenado con la fecha actual del servidor. El campo Usuario_ultima_modificacion ser?lenado con la informaci?e la sesi?ctiva.
  *
  *
  *
  **/

  class ApiClienteEditarPerfil extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"password" => new ApiExposedProperty("password", true, POST, array( "string" )),
			"razon_social" => new ApiExposedProperty("razon_social", true, POST, array( "string" )),
			"id_cliente" => new ApiExposedProperty("id_cliente", true, POST, array( "int" )),
			"codigo_cliente" => new ApiExposedProperty("codigo_cliente", true, POST, array( "string" )),
			"moneda_del_cliente" => new ApiExposedProperty("moneda_del_cliente", false, POST, array( "int" )),
			"numero_exterior" => new ApiExposedProperty("numero_exterior", false, POST, array( "string" )),
			"numero_interior" => new ApiExposedProperty("numero_interior", false, POST, array( "string" )),
			"telefono1" => new ApiExposedProperty("telefono1", false, POST, array( "string" )),
			"rfc" => new ApiExposedProperty("rfc", false, POST, array( "string" )),
			"representante_legal" => new ApiExposedProperty("representante_legal", false, POST, array( "string" )),
			"curp" => new ApiExposedProperty("curp", false, POST, array( "string" )),
			"cuenta_de_mensajeria" => new ApiExposedProperty("cuenta_de_mensajeria", false, POST, array( "string" )),
			"codigo_postal" => new ApiExposedProperty("codigo_postal", false, POST, array( "string" )),
			"direccion_web" => new ApiExposedProperty("direccion_web", false, POST, array( "string" )),
			"mensajeria" => new ApiExposedProperty("mensajeria", false, POST, array( "bool" )),
			"telefono2" => new ApiExposedProperty("telefono2", false, POST, array( "string" )),
			"denominacion_comercial" => new ApiExposedProperty("denominacion_comercial", false, POST, array( "string" )),
			"estatus" => new ApiExposedProperty("estatus", false, POST, array( "string" )),
			"calle" => new ApiExposedProperty("calle", false, POST, array( "string" )),
			"municipio" => new ApiExposedProperty("municipio", false, POST, array( "int" )),
			"clasificacion_cliente" => new ApiExposedProperty("clasificacion_cliente", false, POST, array( "int" )),
			"email" => new ApiExposedProperty("email", false, POST, array( "string" )),
			"texto_extra" => new ApiExposedProperty("texto_extra", false, POST, array( "string" )),
			"colonia" => new ApiExposedProperty("colonia", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ClientesController::Editar_perfil( 
 			
			
			isset($_POST['password'] ) ? $_POST['password'] : null,
			isset($_POST['razon_social'] ) ? $_POST['razon_social'] : null,
			isset($_POST['id_cliente'] ) ? $_POST['id_cliente'] : null,
			isset($_POST['codigo_cliente'] ) ? $_POST['codigo_cliente'] : null,
			isset($_POST['moneda_del_cliente'] ) ? $_POST['moneda_del_cliente'] : null,
			isset($_POST['numero_exterior'] ) ? $_POST['numero_exterior'] : null,
			isset($_POST['numero_interior'] ) ? $_POST['numero_interior'] : null,
			isset($_POST['telefono1'] ) ? $_POST['telefono1'] : null,
			isset($_POST['rfc'] ) ? $_POST['rfc'] : null,
			isset($_POST['representante_legal'] ) ? $_POST['representante_legal'] : null,
			isset($_POST['curp'] ) ? $_POST['curp'] : null,
			isset($_POST['cuenta_de_mensajeria'] ) ? $_POST['cuenta_de_mensajeria'] : null,
			isset($_POST['codigo_postal'] ) ? $_POST['codigo_postal'] : null,
			isset($_POST['direccion_web'] ) ? $_POST['direccion_web'] : null,
			isset($_POST['mensajeria'] ) ? $_POST['mensajeria'] : null,
			isset($_POST['telefono2'] ) ? $_POST['telefono2'] : null,
			isset($_POST['denominacion_comercial'] ) ? $_POST['denominacion_comercial'] : null,
			isset($_POST['estatus'] ) ? $_POST['estatus'] : null,
			isset($_POST['calle'] ) ? $_POST['calle'] : null,
			isset($_POST['municipio'] ) ? $_POST['municipio'] : null,
			isset($_POST['clasificacion_cliente'] ) ? $_POST['clasificacion_cliente'] : null,
			isset($_POST['email'] ) ? $_POST['email'] : null,
			isset($_POST['texto_extra'] ) ? $_POST['texto_extra'] : null,
			isset($_POST['colonia'] ) ? $_POST['colonia'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
