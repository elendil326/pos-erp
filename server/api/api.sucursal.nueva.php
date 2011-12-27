<?php
/**
  * POST api/sucursal/nueva
  * Crea una nueva sucursal
  *
  * Metodo que crea una nueva sucursal
  *
  *
  *
  **/

  class ApiSucursalNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"activo" => new ApiExposedProperty("activo", true, POST, array( "bool" )),
			"calle" => new ApiExposedProperty("calle", true, POST, array( "string" )),
			"codigo_postal" => new ApiExposedProperty("codigo_postal", true, POST, array( "string" )),
			"colonia" => new ApiExposedProperty("colonia", true, POST, array( "string" )),
			"id_ciudad" => new ApiExposedProperty("id_ciudad", true, POST, array( "int" )),
			"numero_exterior" => new ApiExposedProperty("numero_exterior", true, POST, array( "string" )),
			"razon_social" => new ApiExposedProperty("razon_social", true, POST, array( "string" )),
			"rfc" => new ApiExposedProperty("rfc", true, POST, array( "string" )),
			"saldo_a_favor" => new ApiExposedProperty("saldo_a_favor", true, POST, array( "float" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "string" )),
			"descuento" => new ApiExposedProperty("descuento", false, POST, array( "float" )),
			"id_gerente" => new ApiExposedProperty("id_gerente", false, POST, array( "int" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, POST, array( "json" )),
			"margen_utilidad" => new ApiExposedProperty("margen_utilidad", false, POST, array( "float" )),
			"numero_interior" => new ApiExposedProperty("numero_interior", false, POST, array( "string" )),
			"referencia" => new ApiExposedProperty("referencia", false, POST, array( "string" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, POST, array( "json" )),
			"telefono1" => new ApiExposedProperty("telefono1", false, POST, array( "string" )),
			"telefono2" => new ApiExposedProperty("telefono2", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::Nueva( 
 			
			
			isset($_POST['activo'] ) ? $_POST['activo'] : null,
			isset($_POST['calle'] ) ? $_POST['calle'] : null,
			isset($_POST['codigo_postal'] ) ? $_POST['codigo_postal'] : null,
			isset($_POST['colonia'] ) ? $_POST['colonia'] : null,
			isset($_POST['id_ciudad'] ) ? $_POST['id_ciudad'] : null,
			isset($_POST['numero_exterior'] ) ? $_POST['numero_exterior'] : null,
			isset($_POST['razon_social'] ) ? $_POST['razon_social'] : null,
			isset($_POST['rfc'] ) ? $_POST['rfc'] : null,
			isset($_POST['saldo_a_favor'] ) ? $_POST['saldo_a_favor'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null,
			isset($_POST['descuento'] ) ? $_POST['descuento'] : null,
			isset($_POST['id_gerente'] ) ? $_POST['id_gerente'] : null,
			isset($_POST['impuestos'] ) ? $_POST['impuestos'] : null,
			isset($_POST['margen_utilidad'] ) ? $_POST['margen_utilidad'] : null,
			isset($_POST['numero_interior'] ) ? $_POST['numero_interior'] : null,
			isset($_POST['referencia'] ) ? $_POST['referencia'] : null,
			isset($_POST['retenciones'] ) ? $_POST['retenciones'] : null,
			isset($_POST['telefono1'] ) ? $_POST['telefono1'] : null,
			isset($_POST['telefono2'] ) ? $_POST['telefono2'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
