<?php
/**
  * GET api/producto/editar
  * Edita un producto
  *
  * Edita la informaci?e un producto
  *
  *
  *
  **/

  class ApiProductoEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_producto" => new ApiExposedProperty("id_producto", true, GET, array( "int" )),
			"clasificaciones" => new ApiExposedProperty("clasificaciones", false, GET, array( "json" )),
			"codigo_de_barras" => new ApiExposedProperty("codigo_de_barras", false, GET, array( "string" )),
			"codigo_producto" => new ApiExposedProperty("codigo_producto", false, GET, array( "string" )),
			"compra_en_mostrador" => new ApiExposedProperty("compra_en_mostrador", false, GET, array( "bool" )),
			"control_de_existencia" => new ApiExposedProperty("control_de_existencia", false, GET, array( "int" )),
			"costo_estandar" => new ApiExposedProperty("costo_estandar", false, GET, array( "float" )),
			"costo_extra_almacen" => new ApiExposedProperty("costo_extra_almacen", false, GET, array( "float" )),
			"descripcion_producto" => new ApiExposedProperty("descripcion_producto", false, GET, array( "string" )),
			"descuento" => new ApiExposedProperty("descuento", false, GET, array( "float" )),
			"empresas" => new ApiExposedProperty("empresas", false, GET, array( "json" )),
			"foto_del_producto" => new ApiExposedProperty("foto_del_producto", false, GET, array( "string" )),
			"garantia" => new ApiExposedProperty("garantia", false, GET, array( "int" )),
			"id_unidad" => new ApiExposedProperty("id_unidad", false, GET, array( "int" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"margen_de_utilidad" => new ApiExposedProperty("margen_de_utilidad", false, GET, array( "float" )),
			"metodo_costeo" => new ApiExposedProperty("metodo_costeo", false, GET, array( "string" )),
			"nombre_producto" => new ApiExposedProperty("nombre_producto", false, GET, array( "string" )),
			"peso_producto" => new ApiExposedProperty("peso_producto", false, GET, array( "float" )),
			"precio" => new ApiExposedProperty("precio", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::Editar( 
 			
			
			isset($_GET['id_producto'] ) ? $_GET['id_producto'] : null,
			isset($_GET['clasificaciones'] ) ? $_GET['clasificaciones'] : null,
			isset($_GET['codigo_de_barras'] ) ? $_GET['codigo_de_barras'] : null,
			isset($_GET['codigo_producto'] ) ? $_GET['codigo_producto'] : null,
			isset($_GET['compra_en_mostrador'] ) ? $_GET['compra_en_mostrador'] : null,
			isset($_GET['control_de_existencia'] ) ? $_GET['control_de_existencia'] : null,
			isset($_GET['costo_estandar'] ) ? $_GET['costo_estandar'] : null,
			isset($_GET['costo_extra_almacen'] ) ? $_GET['costo_extra_almacen'] : null,
			isset($_GET['descripcion_producto'] ) ? $_GET['descripcion_producto'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['empresas'] ) ? $_GET['empresas'] : null,
			isset($_GET['foto_del_producto'] ) ? $_GET['foto_del_producto'] : null,
			isset($_GET['garantia'] ) ? $_GET['garantia'] : null,
			isset($_GET['id_unidad'] ) ? $_GET['id_unidad'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['margen_de_utilidad'] ) ? $_GET['margen_de_utilidad'] : null,
			isset($_GET['metodo_costeo'] ) ? $_GET['metodo_costeo'] : null,
			isset($_GET['nombre_producto'] ) ? $_GET['nombre_producto'] : null,
			isset($_GET['peso_producto'] ) ? $_GET['peso_producto'] : null,
			isset($_GET['precio'] ) ? $_GET['precio'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
