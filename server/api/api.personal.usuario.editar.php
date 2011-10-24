<?php
/**
  * GET api/personal/usuario/editar
  * Editar los detalles de un usuario.
  *
  * Editar los detalles de un usuario.
  *
  *
  *
  **/

  class ApiPersonalUsuarioEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_usuario" => new ApiExposedProperty("id_usuario", true, GET, array( "int" )),
			"colonia_2" => new ApiExposedProperty("colonia_2", false, GET, array( "string" )),
			"id_rol" => new ApiExposedProperty("id_rol", false, GET, array( "int" )),
			"salario" => new ApiExposedProperty("salario", false, GET, array( "float" )),
			"descuento" => new ApiExposedProperty("descuento", false, GET, array( "float" )),
			"telefono_personal_1" => new ApiExposedProperty("telefono_personal_1", false, GET, array( "string" )),
			"limite_de_credito" => new ApiExposedProperty("limite_de_credito", false, GET, array( "float" )),
			"pagina_web" => new ApiExposedProperty("pagina_web", false, GET, array( "string" )),
			"telefono2_2" => new ApiExposedProperty("telefono2_2", false, GET, array( "string" )),
			"facturar_a_terceros" => new ApiExposedProperty("facturar_a_terceros", false, GET, array( "bool" )),
			"mensajeria" => new ApiExposedProperty("mensajeria", false, GET, array( "bool" )),
			"telefono_personal_2" => new ApiExposedProperty("telefono_personal_2", false, GET, array( "string" )),
			"ventas_a_credito" => new ApiExposedProperty("ventas_a_credito", false, GET, array( "int" )),
			"texto_extra_2" => new ApiExposedProperty("texto_extra_2", false, GET, array( "string" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, GET, array( "json" )),
			"saldo_del_ejercicio" => new ApiExposedProperty("saldo_del_ejercicio", false, GET, array( "float" )),
			"id_ciudad_2" => new ApiExposedProperty("id_ciudad_2", false, GET, array( "int" )),
			"dia_de_pago" => new ApiExposedProperty("dia_de_pago", false, GET, array( "string" )),
			"calle" => new ApiExposedProperty("calle", false, GET, array( "string" )),
			"numero_interior_2" => new ApiExposedProperty("numero_interior_2", false, GET, array( "string" )),
			"codigo_postal" => new ApiExposedProperty("codigo_postal", false, GET, array( "string" )),
			"texto_extra" => new ApiExposedProperty("texto_extra", false, GET, array( "string" )),
			"numero_interior" => new ApiExposedProperty("numero_interior", false, GET, array( "string" )),
			"id_ciudad" => new ApiExposedProperty("id_ciudad", false, GET, array( "int" )),
			"password" => new ApiExposedProperty("password", false, GET, array( "string" )),
			"id_clasificacion_proveedor" => new ApiExposedProperty("id_clasificacion_proveedor", false, GET, array( "int" )),
			"codigo_usuario" => new ApiExposedProperty("codigo_usuario", false, GET, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", false, GET, array( "string" )),
			"colonia" => new ApiExposedProperty("colonia", false, GET, array( "string" )),
			"comision_ventas" => new ApiExposedProperty("comision_ventas", false, GET, array( "float" )),
			"correo_electronico" => new ApiExposedProperty("correo_electronico", false, GET, array( "string" )),
			"representante_legal" => new ApiExposedProperty("representante_legal", false, GET, array( "string" )),
			"calle_2" => new ApiExposedProperty("calle_2", false, GET, array( "string" )),
			"dias_de_embarque" => new ApiExposedProperty("dias_de_embarque", false, GET, array( "int" )),
			"telefono2" => new ApiExposedProperty("telefono2", false, GET, array( "string" )),
			"dias_de_credito" => new ApiExposedProperty("dias_de_credito", false, GET, array( "int" )),
			"rfc" => new ApiExposedProperty("rfc", false, GET, array( "string" )),
			"curp" => new ApiExposedProperty("curp", false, GET, array( "string" )),
			"numero_exterior_2" => new ApiExposedProperty("numero_exterior_2", false, GET, array( "string" )),
			"numero_exterior" => new ApiExposedProperty("numero_exterior", false, GET, array( "string" )),
			"denominacion_comercial" => new ApiExposedProperty("denominacion_comercial", false, GET, array( "string" )),
			"descuento_es_porcentaje" => new ApiExposedProperty("descuento_es_porcentaje", false, GET, array( "bool" )),
			"id_clasificacion_cliente" => new ApiExposedProperty("id_clasificacion_cliente", false, GET, array( "int" )),
			"cuenta_bancaria" => new ApiExposedProperty("cuenta_bancaria", false, GET, array( "string" )),
			"dia_de_revision" => new ApiExposedProperty("dia_de_revision", false, GET, array( "string" )),
			"cuenta_mensajeria" => new ApiExposedProperty("cuenta_mensajeria", false, GET, array( "string" )),
			"telefono1" => new ApiExposedProperty("telefono1", false, GET, array( "string" )),
			"codigo_postal_2" => new ApiExposedProperty("codigo_postal_2", false, GET, array( "string" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"telefono1_2" => new ApiExposedProperty("telefono1_2", false, GET, array( "string" )),
			"intereses_moratorios" => new ApiExposedProperty("intereses_moratorios", false, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::EditarUsuario( 
 			
			
			isset($_GET['id_usuario'] ) ? $_GET['id_usuario'] : null,
			isset($_GET['colonia_2'] ) ? $_GET['colonia_2'] : null,
			isset($_GET['id_rol'] ) ? $_GET['id_rol'] : null,
			isset($_GET['salario'] ) ? $_GET['salario'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['telefono_personal_1'] ) ? $_GET['telefono_personal_1'] : null,
			isset($_GET['limite_de_credito'] ) ? $_GET['limite_de_credito'] : null,
			isset($_GET['pagina_web'] ) ? $_GET['pagina_web'] : null,
			isset($_GET['telefono2_2'] ) ? $_GET['telefono2_2'] : null,
			isset($_GET['facturar_a_terceros'] ) ? $_GET['facturar_a_terceros'] : null,
			isset($_GET['mensajeria'] ) ? $_GET['mensajeria'] : null,
			isset($_GET['telefono_personal_2'] ) ? $_GET['telefono_personal_2'] : null,
			isset($_GET['ventas_a_credito'] ) ? $_GET['ventas_a_credito'] : null,
			isset($_GET['texto_extra_2'] ) ? $_GET['texto_extra_2'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['retenciones'] ) ? $_GET['retenciones'] : null,
			isset($_GET['saldo_del_ejercicio'] ) ? $_GET['saldo_del_ejercicio'] : null,
			isset($_GET['id_ciudad_2'] ) ? $_GET['id_ciudad_2'] : null,
			isset($_GET['dia_de_pago'] ) ? $_GET['dia_de_pago'] : null,
			isset($_GET['calle'] ) ? $_GET['calle'] : null,
			isset($_GET['numero_interior_2'] ) ? $_GET['numero_interior_2'] : null,
			isset($_GET['codigo_postal'] ) ? $_GET['codigo_postal'] : null,
			isset($_GET['texto_extra'] ) ? $_GET['texto_extra'] : null,
			isset($_GET['numero_interior'] ) ? $_GET['numero_interior'] : null,
			isset($_GET['id_ciudad'] ) ? $_GET['id_ciudad'] : null,
			isset($_GET['password'] ) ? $_GET['password'] : null,
			isset($_GET['id_clasificacion_proveedor'] ) ? $_GET['id_clasificacion_proveedor'] : null,
			isset($_GET['codigo_usuario'] ) ? $_GET['codigo_usuario'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['colonia'] ) ? $_GET['colonia'] : null,
			isset($_GET['comision_ventas'] ) ? $_GET['comision_ventas'] : null,
			isset($_GET['correo_electronico'] ) ? $_GET['correo_electronico'] : null,
			isset($_GET['representante_legal'] ) ? $_GET['representante_legal'] : null,
			isset($_GET['calle_2'] ) ? $_GET['calle_2'] : null,
			isset($_GET['dias_de_embarque'] ) ? $_GET['dias_de_embarque'] : null,
			isset($_GET['telefono2'] ) ? $_GET['telefono2'] : null,
			isset($_GET['dias_de_credito'] ) ? $_GET['dias_de_credito'] : null,
			isset($_GET['rfc'] ) ? $_GET['rfc'] : null,
			isset($_GET['curp'] ) ? $_GET['curp'] : null,
			isset($_GET['numero_exterior_2'] ) ? $_GET['numero_exterior_2'] : null,
			isset($_GET['numero_exterior'] ) ? $_GET['numero_exterior'] : null,
			isset($_GET['denominacion_comercial'] ) ? $_GET['denominacion_comercial'] : null,
			isset($_GET['descuento_es_porcentaje'] ) ? $_GET['descuento_es_porcentaje'] : null,
			isset($_GET['id_clasificacion_cliente'] ) ? $_GET['id_clasificacion_cliente'] : null,
			isset($_GET['cuenta_bancaria'] ) ? $_GET['cuenta_bancaria'] : null,
			isset($_GET['dia_de_revision'] ) ? $_GET['dia_de_revision'] : null,
			isset($_GET['cuenta_mensajeria'] ) ? $_GET['cuenta_mensajeria'] : null,
			isset($_GET['telefono1'] ) ? $_GET['telefono1'] : null,
			isset($_GET['codigo_postal_2'] ) ? $_GET['codigo_postal_2'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['telefono1_2'] ) ? $_GET['telefono1_2'] : null,
			isset($_GET['intereses_moratorios'] ) ? $_GET['intereses_moratorios'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
