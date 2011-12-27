<?php
/**
  * GET api/sucursal/editar
  * Edita una sucursal
  *
  * Edita los datos de una sucursal
  *
  *
  *
  **/

  class ApiSucursalEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_sucursal" => new ApiExposedProperty("id_sucursal", true, GET, array( "int" )),
			"calle" => new ApiExposedProperty("calle", false, GET, array( "string" )),
			"coidgo_postal" => new ApiExposedProperty("coidgo_postal", false, GET, array( "string" )),
			"colonia" => new ApiExposedProperty("colonia", false, GET, array( "string" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"descuento" => new ApiExposedProperty("descuento", false, GET, array( "float" )),
			"empresas" => new ApiExposedProperty("empresas", false, GET, array( "json" )),
			"id_gerente" => new ApiExposedProperty("id_gerente", false, GET, array( "int" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"margen_utilidad" => new ApiExposedProperty("margen_utilidad", false, GET, array( "float" )),
			"municipio" => new ApiExposedProperty("municipio", false, GET, array( "int" )),
			"numero_exterior" => new ApiExposedProperty("numero_exterior", false, GET, array( "string" )),
			"numero_interior" => new ApiExposedProperty("numero_interior", false, GET, array( "string" )),
			"razon_social" => new ApiExposedProperty("razon_social", false, GET, array( "string" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, GET, array( "json" )),
			"rfc" => new ApiExposedProperty("rfc", false, GET, array( "string" )),
			"saldo_a_favor" => new ApiExposedProperty("saldo_a_favor", false, GET, array( "float" )),
			"telefono1" => new ApiExposedProperty("telefono1", false, GET, array( "string" )),
			"telefono2" => new ApiExposedProperty("telefono2", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::Editar( 
 			
			
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['calle'] ) ? $_GET['calle'] : null,
			isset($_GET['coidgo_postal'] ) ? $_GET['coidgo_postal'] : null,
			isset($_GET['colonia'] ) ? $_GET['colonia'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['empresas'] ) ? $_GET['empresas'] : null,
			isset($_GET['id_gerente'] ) ? $_GET['id_gerente'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['margen_utilidad'] ) ? $_GET['margen_utilidad'] : null,
			isset($_GET['municipio'] ) ? $_GET['municipio'] : null,
			isset($_GET['numero_exterior'] ) ? $_GET['numero_exterior'] : null,
			isset($_GET['numero_interior'] ) ? $_GET['numero_interior'] : null,
			isset($_GET['razon_social'] ) ? $_GET['razon_social'] : null,
			isset($_GET['retenciones'] ) ? $_GET['retenciones'] : null,
			isset($_GET['rfc'] ) ? $_GET['rfc'] : null,
			isset($_GET['saldo_a_favor'] ) ? $_GET['saldo_a_favor'] : null,
			isset($_GET['telefono1'] ) ? $_GET['telefono1'] : null,
			isset($_GET['telefono2'] ) ? $_GET['telefono2'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
