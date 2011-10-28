<?php
/**
  * GET api/autorizaciones/editar_precio_cliente
  * Solicitud para cambiar la relaci?n precio-cliente
  *
  * Solicitud para cambiar la relaci?n entre cliente y el precio ofrecido para cierto producto ya sea en compra o en venta. La fecha de peticion se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.

UPDATE : Actualmente como se maneja esto es por medio de las ventas preferenciales, es decir, se manda una autorizaci?n para que el cajero pueda editar todos los precios que desee, de todos los productos "solo para esa venta y solo para ese cliente especificamente", ya que si el cliente quisiera que le vendieran mas de un solo producto a diferente precio tendr?as que generar mas de una autorizaci?n, esto implica un incremento considerable en el tiempo de respuesta y aplicaci?n de los cambios.

UPDATE 2: Creo que los metodos : 
api/autorizaciones/editar_precio_cliente y api/autorizaciones/editar_siguiente_compra_venta_precio_cliente
Se podr?an combinar y as? tener un solo m?todo para una compra venta preferencial.
  *
  *
  *
  **/

  class ApiAutorizacionesEditarPrecioCliente extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"siguiente_compra" => new ApiExposedProperty("siguiente_compra", true, GET, array( "bool" )),
			"id_cliente" => new ApiExposedProperty("id_cliente", true, GET, array( "int" )),
			"descripcion" => new ApiExposedProperty("descripcion", true, GET, array( "string" )),
			"id_productos" => new ApiExposedProperty("id_productos", true, GET, array( "json" )),
			"compra" => new ApiExposedProperty("compra", true, GET, array( "bool" )),
			"precio" => new ApiExposedProperty("precio", false, GET, array( "float" )),
			"id_precio" => new ApiExposedProperty("id_precio", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::Editar_precio_cliente( 
 			
			
			isset($_GET['siguiente_compra'] ) ? $_GET['siguiente_compra'] : null,
			isset($_GET['id_cliente'] ) ? $_GET['id_cliente'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['id_productos'] ) ? $_GET['id_productos'] : null,
			isset($_GET['compra'] ) ? $_GET['compra'] : null,
			isset($_GET['precio'] ) ? $_GET['precio'] : null,
			isset($_GET['id_precio'] ) ? $_GET['id_precio'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
