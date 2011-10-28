<?php
/**
  * GET api/empresa/editar
  * Edita una empresa existente.
  *
  * Un administrador puede editar una sucursal, incuso si hay puntos de venta con sesiones activas que pertenecen a esa empresa. 
  *
  *
  *
  **/

  class ApiEmpresaEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_empresa" => new ApiExposedProperty("id_empresa", true, GET, array( "int" )),
			"descuento" => new ApiExposedProperty("descuento", false, GET, array( "float" )),
			"margen_utilidad" => new ApiExposedProperty("margen_utilidad", false, GET, array( "float" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, GET, array( "json" )),
			"direccion_web" => new ApiExposedProperty("direccion_web", false, GET, array( "string" )),
			"ciudad" => new ApiExposedProperty("ciudad", false, GET, array( "int" )),
			"razon_social" => new ApiExposedProperty("razon_social", false, GET, array( "string" )),
			"rfc" => new ApiExposedProperty("rfc", false, GET, array( "string" )),
			"codigo_postal" => new ApiExposedProperty("codigo_postal", false, GET, array( "string" )),
			"curp" => new ApiExposedProperty("curp", false, GET, array( "string" )),
			"calle	" => new ApiExposedProperty("calle	", false, GET, array( "string" )),
			"numero_interno" => new ApiExposedProperty("numero_interno", false, GET, array( "string" )),
			"representante_legal" => new ApiExposedProperty("representante_legal", false, GET, array( "string" )),
			"telefono1" => new ApiExposedProperty("telefono1", false, GET, array( "string" )),
			"numero_exterior	" => new ApiExposedProperty("numero_exterior	", false, GET, array( "string" )),
			"colonia	" => new ApiExposedProperty("colonia	", false, GET, array( "string" )),
			"email" => new ApiExposedProperty("email", false, GET, array( "string" )),
			"telefono2" => new ApiExposedProperty("telefono2", false, GET, array( "string" )),
			"texto_extra" => new ApiExposedProperty("texto_extra", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EmpresasController::Editar( 
 			
			
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['margen_utilidad'] ) ? $_GET['margen_utilidad'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['retenciones'] ) ? $_GET['retenciones'] : null,
			isset($_GET['direccion_web'] ) ? $_GET['direccion_web'] : null,
			isset($_GET['ciudad'] ) ? $_GET['ciudad'] : null,
			isset($_GET['razon_social'] ) ? $_GET['razon_social'] : null,
			isset($_GET['rfc'] ) ? $_GET['rfc'] : null,
			isset($_GET['codigo_postal'] ) ? $_GET['codigo_postal'] : null,
			isset($_GET['curp'] ) ? $_GET['curp'] : null,
			isset($_GET['calle	'] ) ? $_GET['calle	'] : null,
			isset($_GET['numero_interno'] ) ? $_GET['numero_interno'] : null,
			isset($_GET['representante_legal'] ) ? $_GET['representante_legal'] : null,
			isset($_GET['telefono1'] ) ? $_GET['telefono1'] : null,
			isset($_GET['numero_exterior	'] ) ? $_GET['numero_exterior	'] : null,
			isset($_GET['colonia	'] ) ? $_GET['colonia	'] : null,
			isset($_GET['email'] ) ? $_GET['email'] : null,
			isset($_GET['telefono2'] ) ? $_GET['telefono2'] : null,
			isset($_GET['texto_extra'] ) ? $_GET['texto_extra'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
