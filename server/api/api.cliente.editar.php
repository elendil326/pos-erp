<?php
/**
  * POST api/cliente/editar
  * Editar todos los campos de un cliente.
  *
  * Edita la informaci?n de un cliente. Se diferenc?a del m?todo editar_perfil en qu? est? m?todo modifica informaci?n m?s sensible del cliente. El campo fecha_ultima_modificacion ser? llenado con la fecha actual del servidor. El campo Usuario_ultima_modificacion ser? llenado con la informaci?n de la sesi?n activa.

Si no se envia alguno de los datos opcionales del cliente. Entonces se quedaran los datos que ya tiene.
  *
  *
  *
  **/

  class ApiClienteEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_cliente" => new ApiExposedProperty("id_cliente", true, POST, array( "int" )),
			"clasificacion_cliente" => new ApiExposedProperty("clasificacion_cliente", false, POST, array( "int" )),
			"codigo_cliente" => new ApiExposedProperty("codigo_cliente", false, POST, array( "string" )),
			"cuenta_de_mensajeria" => new ApiExposedProperty("cuenta_de_mensajeria", false, POST, array( "string" )),
			"curp" => new ApiExposedProperty("curp", false, POST, array( "string" )),
			"denominacion_comercial" => new ApiExposedProperty("denominacion_comercial", false, POST, array( "string" )),
			"descuento_general" => new ApiExposedProperty("descuento_general", false, POST, array( "float" )),
			"direccion_web" => new ApiExposedProperty("direccion_web", false, POST, array( "string" )),
			"email" => new ApiExposedProperty("email", false, POST, array( "string" )),
			"id_tarifa_compra" => new ApiExposedProperty("id_tarifa_compra", false, POST, array( "int" )),
			"id_tarifa_venta" => new ApiExposedProperty("id_tarifa_venta", false, POST, array( "int" )),
			"limite_de_credito" => new ApiExposedProperty("limite_de_credito", false, POST, array( "float" )),
			"mensajeria" => new ApiExposedProperty("mensajeria", false, POST, array( "bool" )),
			"moneda_del_cliente" => new ApiExposedProperty("moneda_del_cliente", false, POST, array( "string" )),
			"password" => new ApiExposedProperty("password", false, POST, array( "string" )),
			"password_actual" => new ApiExposedProperty("password_actual", false, POST, array( "string" )),
			"razon_social" => new ApiExposedProperty("razon_social", false, POST, array( "string" )),
			"representante_legal" => new ApiExposedProperty("representante_legal", false, POST, array( "string" )),
			"rfc" => new ApiExposedProperty("rfc", false, POST, array( "string" )),
			"sucursal" => new ApiExposedProperty("sucursal", false, POST, array( "int" )),
			"telefono1" => new ApiExposedProperty("telefono1", false, POST, array( "string" )),
			"telefono_personal2" => new ApiExposedProperty("telefono_personal2", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ClientesController::Editar( 
 			
			
			isset($_POST['id_cliente'] ) ? $_POST['id_cliente'] : null,
			isset($_POST['clasificacion_cliente'] ) ? $_POST['clasificacion_cliente'] : null,
			isset($_POST['codigo_cliente'] ) ? $_POST['codigo_cliente'] : null,
			isset($_POST['cuenta_de_mensajeria'] ) ? $_POST['cuenta_de_mensajeria'] : null,
			isset($_POST['curp'] ) ? $_POST['curp'] : null,
			isset($_POST['denominacion_comercial'] ) ? $_POST['denominacion_comercial'] : null,
			isset($_POST['descuento_general'] ) ? $_POST['descuento_general'] : null,
			isset($_POST['direccion_web'] ) ? $_POST['direccion_web'] : null,
			isset($_POST['email'] ) ? $_POST['email'] : null,
			isset($_POST['id_tarifa_compra'] ) ? $_POST['id_tarifa_compra'] : null,
			isset($_POST['id_tarifa_venta'] ) ? $_POST['id_tarifa_venta'] : null,
			isset($_POST['limite_de_credito'] ) ? $_POST['limite_de_credito'] : null,
			isset($_POST['mensajeria'] ) ? $_POST['mensajeria'] : null,
			isset($_POST['moneda_del_cliente'] ) ? $_POST['moneda_del_cliente'] : null,
			isset($_POST['password'] ) ? $_POST['password'] : null,
			isset($_POST['password_actual'] ) ? $_POST['password_actual'] : null,
			isset($_POST['razon_social'] ) ? $_POST['razon_social'] : null,
			isset($_POST['representante_legal'] ) ? $_POST['representante_legal'] : null,
			isset($_POST['rfc'] ) ? $_POST['rfc'] : null,
			isset($_POST['sucursal'] ) ? $_POST['sucursal'] : null,
			isset($_POST['telefono1'] ) ? $_POST['telefono1'] : null,
			isset($_POST['telefono_personal2'] ) ? $_POST['telefono_personal2'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
