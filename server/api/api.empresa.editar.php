<?php
/**
  * POST api/empresa/editar
  * Edita una empresa existente.
  *
  * Un administrador puede editar una sucursal, incuso si hay puntos de venta con sesiones activas que pertenecen a esa empresa. 
  *
  *
  *
  **/

  class ApiEmpresaEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_empresa" => new ApiExposedProperty("id_empresa", true, POST, array( "int" )),
			"calle	" => new ApiExposedProperty("calle	", false, POST, array( "string" )),
			"ciudad" => new ApiExposedProperty("ciudad", false, POST, array( "int" )),
			"codigo_postal" => new ApiExposedProperty("codigo_postal", false, POST, array( "string" )),
			"colonia	" => new ApiExposedProperty("colonia	", false, POST, array( "string" )),
			"curp" => new ApiExposedProperty("curp", false, POST, array( "string" )),
			"direccion_web" => new ApiExposedProperty("direccion_web", false, POST, array( "string" )),
			"email" => new ApiExposedProperty("email", false, POST, array( "string" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, POST, array( "json" )),
			"numero_exterior	" => new ApiExposedProperty("numero_exterior	", false, POST, array( "string" )),
			"numero_interno" => new ApiExposedProperty("numero_interno", false, POST, array( "string" )),
			"razon_social" => new ApiExposedProperty("razon_social", false, POST, array( "string" )),
			"representante_legal" => new ApiExposedProperty("representante_legal", false, POST, array( "string" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, POST, array( "json" )),
			"rfc" => new ApiExposedProperty("rfc", false, POST, array( "string" )),
			"telefono1" => new ApiExposedProperty("telefono1", false, POST, array( "string" )),
			"telefono2" => new ApiExposedProperty("telefono2", false, POST, array( "string" )),
			"texto_extra" => new ApiExposedProperty("texto_extra", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EmpresasController::Editar( 
 			
			
			isset($_POST['id_empresa'] ) ? $_POST['id_empresa'] : null,
			isset($_POST['calle	'] ) ? $_POST['calle	'] : null,
			isset($_POST['ciudad'] ) ? $_POST['ciudad'] : null,
			isset($_POST['codigo_postal'] ) ? $_POST['codigo_postal'] : null,
			isset($_POST['colonia	'] ) ? $_POST['colonia	'] : null,
			isset($_POST['curp'] ) ? $_POST['curp'] : null,
			isset($_POST['direccion_web'] ) ? $_POST['direccion_web'] : null,
			isset($_POST['email'] ) ? $_POST['email'] : null,
			isset($_POST['impuestos'] ) ? json_decode($_POST['impuestos']) : null,
			isset($_POST['numero_exterior	'] ) ? $_POST['numero_exterior	'] : null,
			isset($_POST['numero_interno'] ) ? $_POST['numero_interno'] : null,
			isset($_POST['razon_social'] ) ? $_POST['razon_social'] : null,
			isset($_POST['representante_legal'] ) ? $_POST['representante_legal'] : null,
			isset($_POST['retenciones'] ) ? json_decode($_POST['retenciones']) : null,
			isset($_POST['rfc'] ) ? $_POST['rfc'] : null,
			isset($_POST['telefono1'] ) ? $_POST['telefono1'] : null,
			isset($_POST['telefono2'] ) ? $_POST['telefono2'] : null,
			isset($_POST['texto_extra'] ) ? $_POST['texto_extra'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
