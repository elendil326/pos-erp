<?php
/**
  * POST api/cliente/nuevo
  * Crear un nuevo cliente.
  *
  * Crea un nuevo cliente en el sistema.

Al crear un cliente en el sistema tambi?n creara un usuario para la interfaz de cliente, en caso de especificar un email se enviara un correo con los datos de acceso para la interfaz de clientes.
  *
  *
  *
  **/

  class ApiClienteNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"razon_social" => new ApiExposedProperty("razon_social", true, POST, array( "string" )),
			"clasificacion_cliente" => new ApiExposedProperty("clasificacion_cliente", false, POST, array( "int" )),
			"cuenta_de_mensajeria" => new ApiExposedProperty("cuenta_de_mensajeria", false, POST, array( "string" )),
			"curp" => new ApiExposedProperty("curp", false, POST, array( "string" )),
			"denominacion_comercial" => new ApiExposedProperty("denominacion_comercial", false, POST, array( "string" )),
			"direcciones" => new ApiExposedProperty("direcciones", false, POST, array( "json" )),
			"direccion_web" => new ApiExposedProperty("direccion_web", false, POST, array( "string" )),
			"email" => new ApiExposedProperty("email", false, POST, array( "string" )),
			"id_cliente_padre" => new ApiExposedProperty("id_cliente_padre", false, POST, array( "int" )),
			"id_moneda" => new ApiExposedProperty("id_moneda", false, POST, array( "int" )),
			"id_tarifa_compra" => new ApiExposedProperty("id_tarifa_compra", false, POST, array( "int" )),
			"id_tarifa_venta" => new ApiExposedProperty("id_tarifa_venta", false, POST, array( "int" )),
			"limite_credito" => new ApiExposedProperty("limite_credito", false, POST, array( "float" )),
			"password" => new ApiExposedProperty("password", false, POST, array( "string" )),
			"representante_legal" => new ApiExposedProperty("representante_legal", false, POST, array( "string" )),
			"rfc" => new ApiExposedProperty("rfc", false, POST, array( "string" )),
			"telefono" => new ApiExposedProperty("telefono", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ClientesController::Nuevo( 
 			
			
			isset($_POST['razon_social'] ) ? $_POST['razon_social'] : null,
			isset($_POST['clasificacion_cliente'] ) ? $_POST['clasificacion_cliente'] : null,
			isset($_POST['cuenta_de_mensajeria'] ) ? $_POST['cuenta_de_mensajeria'] : null,
			isset($_POST['curp'] ) ? $_POST['curp'] : null,
			isset($_POST['denominacion_comercial'] ) ? $_POST['denominacion_comercial'] : null,
			isset($_POST['direcciones'] ) ? json_decode($_POST['direcciones']) : null,
			isset($_POST['direccion_web'] ) ? $_POST['direccion_web'] : null,
			isset($_POST['email'] ) ? $_POST['email'] : null,
			isset($_POST['id_cliente_padre'] ) ? $_POST['id_cliente_padre'] : null,
			isset($_POST['id_moneda'] ) ? $_POST['id_moneda'] : null,
			isset($_POST['id_tarifa_compra'] ) ? $_POST['id_tarifa_compra'] : null,
			isset($_POST['id_tarifa_venta'] ) ? $_POST['id_tarifa_venta'] : null,
			isset($_POST['limite_credito'] ) ? $_POST['limite_credito'] : null,
			isset($_POST['password'] ) ? $_POST['password'] : null,
			isset($_POST['representante_legal'] ) ? $_POST['representante_legal'] : null,
			isset($_POST['rfc'] ) ? $_POST['rfc'] : null,
			isset($_POST['telefono'] ) ? $_POST['telefono'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
