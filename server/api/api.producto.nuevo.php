<?php
/**
  * GET api/producto/nuevo
  * Crear un nuevo produco
  *
  * Crear un nuevo producto, 

NOTA: Se crea un producto tipo = 1 que es para productos
  *
  *
  *
  **/

  class ApiProductoNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"activo" => new ApiExposedProperty("activo", true, GET, array( "bool" )),
			"costo_estandar" => new ApiExposedProperty("costo_estandar", true, GET, array( "float" )),
			"compra_en_mostrador" => new ApiExposedProperty("compra_en_mostrador", true, GET, array( "bool" )),
			"nombre_producto" => new ApiExposedProperty("nombre_producto", true, GET, array( "string" )),
			"codigo_producto" => new ApiExposedProperty("codigo_producto", true, GET, array( "string" )),
			"metodo_costeo" => new ApiExposedProperty("metodo_costeo", true, GET, array( "string" )),
			"costo_extra_almacen" => new ApiExposedProperty("costo_extra_almacen", false, GET, array( "float" )),
			"margen_de_utilidad" => new ApiExposedProperty("margen_de_utilidad", false, GET, array( "float" )),
			"foto_del_producto" => new ApiExposedProperty("foto_del_producto", false, GET, array( "string" )),
			"garantia" => new ApiExposedProperty("garantia", false, GET, array( "int" )),
			"descuento" => new ApiExposedProperty("descuento", false, GET, array( "float" )),
			"precio" => new ApiExposedProperty("precio", false, GET, array( "int" )),
			"codigo_de_barras" => new ApiExposedProperty("codigo_de_barras", false, GET, array( "string" )),
			"descripcion_producto" => new ApiExposedProperty("descripcion_producto", false, GET, array( "string" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"id_unidad" => new ApiExposedProperty("id_unidad", false, GET, array( "int" )),
			"clasificaciones" => new ApiExposedProperty("clasificaciones", false, GET, array( "json" )),
			"control_de_existencia" => new ApiExposedProperty("control_de_existencia", false, GET, array( "int" )),
			"peso_producto" => new ApiExposedProperty("peso_producto", false, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::Nuevo( 
 			
			
			isset($_GET['activo'] ) ? $_GET['activo'] : null,
			isset($_GET['costo_estandar'] ) ? $_GET['costo_estandar'] : null,
			isset($_GET['compra_en_mostrador'] ) ? $_GET['compra_en_mostrador'] : null,
			isset($_GET['nombre_producto'] ) ? $_GET['nombre_producto'] : null,
			isset($_GET['codigo_producto'] ) ? $_GET['codigo_producto'] : null,
			isset($_GET['metodo_costeo'] ) ? $_GET['metodo_costeo'] : null,
			isset($_GET['costo_extra_almacen'] ) ? $_GET['costo_extra_almacen'] : null,
			isset($_GET['margen_de_utilidad'] ) ? $_GET['margen_de_utilidad'] : null,
			isset($_GET['foto_del_producto'] ) ? $_GET['foto_del_producto'] : null,
			isset($_GET['garantia'] ) ? $_GET['garantia'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['precio'] ) ? $_GET['precio'] : null,
			isset($_GET['codigo_de_barras'] ) ? $_GET['codigo_de_barras'] : null,
			isset($_GET['descripcion_producto'] ) ? $_GET['descripcion_producto'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['id_unidad'] ) ? $_GET['id_unidad'] : null,
			isset($_GET['clasificaciones'] ) ? $_GET['clasificaciones'] : null,
			isset($_GET['control_de_existencia'] ) ? $_GET['control_de_existencia'] : null,
			isset($_GET['peso_producto'] ) ? $_GET['peso_producto'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
