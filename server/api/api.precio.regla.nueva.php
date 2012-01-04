<?php
/**
  * POST api/precio/regla/nueva
  * Crea una nueva regla
  *
  * Crea una nueva regla para una version. 

Una regla que no tiene producto, categoria de producto o alguna otra relacion, es una regla que se aplica a todos los productos, servicios y paquetes.

Las secuencias de las reglas no se pueden repetir.

La formula que siguen las reglas para obtener el precio fina es la siguiente: 

       Precio Final = Precio Base * (1 + porcentaje_utilidad) + utilidad_neta

Donde :
 
    Precio Base : Es obtenido de la tarifa con la que se relaciona esta regla. 
                  Si no se relaciona con ninguna tarifa, entonces lo toma del 
                  precio o costo (dependiendo del metodo de costeo) del producto,servicio
                  o paquete.

    porcentaje_utilidad:El porcentaje de utilidad que se le ganara al precio o costo base.
                        Puede ser negativo

    utilidad_neta: La utilidad neta que se ganara al comerciar este producto,servicio o
                   paquete. Puede ser negativo.


Al asignar una tarifa base a una regla se verifica que no haya una dependencia circular.

Una misma regla puede aplicar a un producto, una clasificacion de producto, un servicio, una clasificacion de servicio y un paquete a la vez.
  *
  *
  *
  **/

  class ApiPrecioReglaNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_version" => new ApiExposedProperty("id_version", true, POST, array( "int" )),
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"secuencia" => new ApiExposedProperty("secuencia", true, POST, array( "int" )),
			"cantidad_minima" => new ApiExposedProperty("cantidad_minima", false, POST, array( "int" )),
			"id_clasificacion_producto" => new ApiExposedProperty("id_clasificacion_producto", false, POST, array( "int" )),
			"id_clasificacion_servicio" => new ApiExposedProperty("id_clasificacion_servicio", false, POST, array( "int" )),
			"id_paquete" => new ApiExposedProperty("id_paquete", false, POST, array( "int" )),
			"id_producto" => new ApiExposedProperty("id_producto", false, POST, array( "int" )),
			"id_servicio" => new ApiExposedProperty("id_servicio", false, POST, array( "int" )),
			"id_tarifa" => new ApiExposedProperty("id_tarifa", false, POST, array( "int" )),
			"id_unidad" => new ApiExposedProperty("id_unidad", false, POST, array( "int" )),
			"margen_max" => new ApiExposedProperty("margen_max", false, POST, array( "float" )),
			"margen_min" => new ApiExposedProperty("margen_min", false, POST, array( "float" )),
			"metodo_redondeo" => new ApiExposedProperty("metodo_redondeo", false, POST, array( "float" )),
			"porcentaje_utilidad" => new ApiExposedProperty("porcentaje_utilidad", false, POST, array( "float" )),
			"utilidad_neta" => new ApiExposedProperty("utilidad_neta", false, POST, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::NuevaRegla( 
 			
			
			isset($_POST['id_version'] ) ? $_POST['id_version'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['secuencia'] ) ? $_POST['secuencia'] : null,
			isset($_POST['cantidad_minima'] ) ? $_POST['cantidad_minima'] : null,
			isset($_POST['id_clasificacion_producto'] ) ? $_POST['id_clasificacion_producto'] : null,
			isset($_POST['id_clasificacion_servicio'] ) ? $_POST['id_clasificacion_servicio'] : null,
			isset($_POST['id_paquete'] ) ? $_POST['id_paquete'] : null,
			isset($_POST['id_producto'] ) ? $_POST['id_producto'] : null,
			isset($_POST['id_servicio'] ) ? $_POST['id_servicio'] : null,
			isset($_POST['id_tarifa'] ) ? $_POST['id_tarifa'] : null,
			isset($_POST['id_unidad'] ) ? $_POST['id_unidad'] : null,
			isset($_POST['margen_max'] ) ? $_POST['margen_max'] : null,
			isset($_POST['margen_min'] ) ? $_POST['margen_min'] : null,
			isset($_POST['metodo_redondeo'] ) ? $_POST['metodo_redondeo'] : null,
			isset($_POST['porcentaje_utilidad'] ) ? $_POST['porcentaje_utilidad'] : null,
			isset($_POST['utilidad_neta'] ) ? $_POST['utilidad_neta'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
