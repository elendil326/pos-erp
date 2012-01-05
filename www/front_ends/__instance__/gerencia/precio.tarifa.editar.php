<?php
/**
  * POST api/precio/tarifa/editar
  * Edita la informacion de una tarifa
  *
  * Edita la informacion de una tarifa. Este metodo puede cambiar las formulas de una tarifa o la vigencia de la misma. 

Este metodo tambien puede ponder como default esta tarifa o quitarle el default. Si se le quita el default, automaticamente se pone como default la predeterminada del sistema.
Si se obtienen formulas en este metodo, se borraran todas las formulas de esta tarifa y se aplicaran las recibidas

Si se cambia el tipo de tarifa, se verfica que esta tarifa no sea una default para algun rol, usuario, clasificacion de cliente o de proveedor, y pierde su default si fuera la default, poniendo como default la predetermianda del sistema.

Aplican todas las consideraciones de la documentacion del metodo nuevaTarifa
  *
  *
  *
  **/

  class ApiPrecioTarifaEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tarifa" => new ApiExposedProperty("id_tarifa", true, POST, array( "int" )),
			"default" => new ApiExposedProperty("default", false, POST, array( "bool" )),
			"fecha_fin" => new ApiExposedProperty("fecha_fin", false, POST, array( "string" )),
			"fecha_inicio" => new ApiExposedProperty("fecha_inicio", false, POST, array( "string" )),
			"formulas" => new ApiExposedProperty("formulas", false, POST, array( "json" )),
			"id_moneda" => new ApiExposedProperty("id_moneda", false, POST, array( "int" )),
			"nombre" => new ApiExposedProperty("nombre", false, POST, array( "string" )),
			"tipo_tarifa" => new ApiExposedProperty("tipo_tarifa", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::EditarTarifa( 
 			
			
			isset($_POST['id_tarifa'] ) ? $_POST['id_tarifa'] : null,
			isset($_POST['default'] ) ? $_POST['default'] : null,
			isset($_POST['fecha_fin'] ) ? $_POST['fecha_fin'] : null,
			isset($_POST['fecha_inicio'] ) ? $_POST['fecha_inicio'] : null,
			isset($_POST['formulas'] ) ? json_decode($_POST['formulas']) : null,
			isset($_POST['id_moneda'] ) ? $_POST['id_moneda'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['tipo_tarifa'] ) ? $_POST['tipo_tarifa'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
