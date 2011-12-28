<?php
/**
  * POST api/producto/nuevo
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
	protected function GetRequest()
	{
		$this->request = array(	
			"activo" => new ApiExposedProperty("activo", true, POST, array( "bool" )),
			"codigo_producto" => new ApiExposedProperty("codigo_producto", true, POST, array( "string" )),
			"compra_en_mostrador" => new ApiExposedProperty("compra_en_mostrador", true, POST, array( "bool" )),
			"costo_estandar" => new ApiExposedProperty("costo_estandar", true, POST, array( "float" )),
			"metodo_costeo" => new ApiExposedProperty("metodo_costeo", true, POST, array( "string" )),
			"nombre_producto" => new ApiExposedProperty("nombre_producto", true, POST, array( "string" )),
			"clasificaciones" => new ApiExposedProperty("clasificaciones", false, POST, array( "json" )),
			"codigo_de_barras" => new ApiExposedProperty("codigo_de_barras", false, POST, array( "string" )),
			"control_de_existencia" => new ApiExposedProperty("control_de_existencia", false, POST, array( "int" )),
			"costo_extra_almacen" => new ApiExposedProperty("costo_extra_almacen", false, POST, array( "float" )),
			"descripcion_producto" => new ApiExposedProperty("descripcion_producto", false, POST, array( "string" )),
			"descuento" => new ApiExposedProperty("descuento", false, POST, array( "float" )),
			"foto_del_producto" => new ApiExposedProperty("foto_del_producto", false, POST, array( "string" )),
			"garantia" => new ApiExposedProperty("garantia", false, POST, array( "int" )),
			"id_unidad" => new ApiExposedProperty("id_unidad", false, POST, array( "int" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, POST, array( "json" )),
			"margen_de_utilidad" => new ApiExposedProperty("margen_de_utilidad", false, POST, array( "float" )),
			"peso_producto" => new ApiExposedProperty("peso_producto", false, POST, array( "float" )),
			"precio" => new ApiExposedProperty("precio", false, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::Nuevo( 
 			
			
			isset($_POST['activo'] ) ? $_POST['activo'] : null,
			isset($_POST['codigo_producto'] ) ? $_POST['codigo_producto'] : null,
			isset($_POST['compra_en_mostrador'] ) ? $_POST['compra_en_mostrador'] : null,
			isset($_POST['costo_estandar'] ) ? $_POST['costo_estandar'] : null,
			isset($_POST['metodo_costeo'] ) ? $_POST['metodo_costeo'] : null,
			isset($_POST['nombre_producto'] ) ? $_POST['nombre_producto'] : null,
			isset($_POST['clasificaciones'] ) ? json_decode($_POST['clasificaciones']) : null,
			isset($_POST['codigo_de_barras'] ) ? $_POST['codigo_de_barras'] : null,
			isset($_POST['control_de_existencia'] ) ? $_POST['control_de_existencia'] : null,
			isset($_POST['costo_extra_almacen'] ) ? $_POST['costo_extra_almacen'] : null,
			isset($_POST['descripcion_producto'] ) ? $_POST['descripcion_producto'] : null,
			isset($_POST['descuento'] ) ? $_POST['descuento'] : null,
			isset($_POST['foto_del_producto'] ) ? $_POST['foto_del_producto'] : null,
			isset($_POST['garantia'] ) ? $_POST['garantia'] : null,
			isset($_POST['id_unidad'] ) ? $_POST['id_unidad'] : null,
			isset($_POST['impuestos'] ) ? json_decode($_POST['impuestos']) : null,
			isset($_POST['margen_de_utilidad'] ) ? $_POST['margen_de_utilidad'] : null,
			isset($_POST['peso_producto'] ) ? $_POST['peso_producto'] : null,
			isset($_POST['precio'] ) ? $_POST['precio'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
