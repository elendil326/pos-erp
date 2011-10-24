<?php
/**
  * GET api/sucursal/nueva
  * Crea una nueva sucursal
  *
  * Metodo que crea una nueva sucursal
  *
  *
  *
  **/

  class ApiSucursalNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"codigo_postal" => new ApiExposedProperty("codigo_postal", true, GET, array( "string" )),
			"calle" => new ApiExposedProperty("calle", true, GET, array( "string" )),
			"empresas" => new ApiExposedProperty("empresas", true, GET, array( "json" )),
			"activo" => new ApiExposedProperty("activo", true, GET, array( "bool" )),
			"colonia" => new ApiExposedProperty("colonia", true, GET, array( "string" )),
			"razon_social" => new ApiExposedProperty("razon_social", true, GET, array( "string" )),
			"numero_exterior" => new ApiExposedProperty("numero_exterior", true, GET, array( "string" )),
			"rfc" => new ApiExposedProperty("rfc", true, GET, array( "string" )),
			"id_ciudad" => new ApiExposedProperty("id_ciudad", true, GET, array( "int" )),
			"saldo_a_favor" => new ApiExposedProperty("saldo_a_favor", true, GET, array( "float" )),
			"id_gerente" => new ApiExposedProperty("id_gerente", false, GET, array( "int" )),
			"referencia" => new ApiExposedProperty("referencia", false, GET, array( "string" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, GET, array( "json" )),
			"numero_interior" => new ApiExposedProperty("numero_interior", false, GET, array( "string" )),
			"telefono2" => new ApiExposedProperty("telefono2", false, GET, array( "string" )),
			"telefono1" => new ApiExposedProperty("telefono1", false, GET, array( "string" )),
			"margen_utilidad" => new ApiExposedProperty("margen_utilidad", false, GET, array( "float" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"descuento" => new ApiExposedProperty("descuento", false, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::Nueva( 
 			
			
			isset($_GET['codigo_postal'] ) ? $_GET['codigo_postal'] : null,
			isset($_GET['calle'] ) ? $_GET['calle'] : null,
			isset($_GET['empresas'] ) ? $_GET['empresas'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null,
			isset($_GET['colonia'] ) ? $_GET['colonia'] : null,
			isset($_GET['razon_social'] ) ? $_GET['razon_social'] : null,
			isset($_GET['numero_exterior'] ) ? $_GET['numero_exterior'] : null,
			isset($_GET['rfc'] ) ? $_GET['rfc'] : null,
			isset($_GET['id_ciudad'] ) ? $_GET['id_ciudad'] : null,
			isset($_GET['saldo_a_favor'] ) ? $_GET['saldo_a_favor'] : null,
			isset($_GET['id_gerente'] ) ? $_GET['id_gerente'] : null,
			isset($_GET['referencia'] ) ? $_GET['referencia'] : null,
			isset($_GET['retenciones'] ) ? $_GET['retenciones'] : null,
			isset($_GET['numero_interior'] ) ? $_GET['numero_interior'] : null,
			isset($_GET['telefono2'] ) ? $_GET['telefono2'] : null,
			isset($_GET['telefono1'] ) ? $_GET['telefono1'] : null,
			isset($_GET['margen_utilidad'] ) ? $_GET['margen_utilidad'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
