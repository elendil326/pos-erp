<?php
/**
  * POST api/compras/nueva
  * Registra una nueva compra
  *
  * Registra una nueva compra, la compra puede ser a cualquier usuario, siendo este un cliente, proveedor, o cualquiera. La compra siempre viene acompa?anda de un detalle de productos que han sido comprados, y cada uno estipula a que almacen y a que sucursal iran a parar.
  *
  *
  *
  **/

  class ApiComprasNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"descuento" => new ApiExposedProperty("descuento", true, POST, array( "float" )),
			"detalle" => new ApiExposedProperty("detalle", true, POST, array( "json" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", true, POST, array( "int" )),
			"id_usuario_compra" => new ApiExposedProperty("id_usuario_compra", true, POST, array( "int" )),
			"impuesto" => new ApiExposedProperty("impuesto", true, POST, array( "float" )),
			"retencion" => new ApiExposedProperty("retencion", true, POST, array( "float" )),
			"subtotal" => new ApiExposedProperty("subtotal", true, POST, array( "float" )),
			"tipo_compra" => new ApiExposedProperty("tipo_compra", true, POST, array( "string" )),
			"total" => new ApiExposedProperty("total", true, POST, array( "float" )),
			"cheques" => new ApiExposedProperty("cheques", false, POST, array( "json" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, POST, array( "int" )),
			"saldo" => new ApiExposedProperty("saldo", false, POST, array( "float" )),
			"tipo_de_pago" => new ApiExposedProperty("tipo_de_pago", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ComprasController::Nueva( 
 			
			
			isset($_POST['descuento'] ) ? $_POST['descuento'] : null,
			isset($_POST['detalle'] ) ? json_decode($_POST['detalle']) : null,
			isset($_POST['id_empresa'] ) ? $_POST['id_empresa'] : null,
			isset($_POST['id_usuario_compra'] ) ? $_POST['id_usuario_compra'] : null,
			isset($_POST['impuesto'] ) ? $_POST['impuesto'] : null,
			isset($_POST['retencion'] ) ? $_POST['retencion'] : null,
			isset($_POST['subtotal'] ) ? $_POST['subtotal'] : null,
			isset($_POST['tipo_compra'] ) ? $_POST['tipo_compra'] : null,
			isset($_POST['total'] ) ? $_POST['total'] : null,
			isset($_POST['cheques'] ) ? json_decode($_POST['cheques']) : null,
			isset($_POST['id_sucursal'] ) ? $_POST['id_sucursal'] : null,
			isset($_POST['saldo'] ) ? $_POST['saldo'] : null,
			isset($_POST['tipo_de_pago'] ) ? $_POST['tipo_de_pago'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
