<?php
/**
  * POST api/precio/tarifa/nueva
  * Crea una nueva tarifa
  *
  * Crea una nueva tarifa que le dara un precio especial a todos los productos, servicios y paquetes o solo a algunos. 

Una tarifa puede tener fechas de inicio y de fin que indican en que fechas se tomaran sus parametros. Si no se reciben fechas, se da por hecho que la tarifa no caduca. Si solo se recibe fecha de inicio, se toma como fecha de fin la maxima fecha permitida por MySQL (9999-12-31 23:59:59). Si solo se recibe fehca de fin, se toma como fecha de inicio la fecha actual del servidor.

Una tarifa puede afectar a uno o varios productos, servicios, clasificaciones de producto, clasificaciones de servicio, unidades, y/o paquetes; cada uno con los parametros de la siguiente funcion:

   Precio Final : Precio Base * (1 + porcentaje_utilidad) + utilidad_neta


Donde:


   Precio Base: Sera obtenido de la tarifa base de esta tarifa.

   porcentaje_utilidad: porcentaje de -1 a 1 de lo que se le ganara del precio base a esta tarifa.

   utilidad_neta: Ganancia neta para esta tarifa del precio base. Puede ser negativa implicando un descuento.

   Precio Final: El resultado de la formula, este valor puede ser afectado directamente por el usuario mediante los parametros metodo_redondeo, margen_min y margen_max. 

   metodo_redondeo: Es el multiplo con el cual se redondea el Precio Base despues de aplicar el porcentaje de utilidad y antes de sumar la utilidad neta. Si se quiere que todos los productos terminen en 9.99, entonces se configura el metodo_redondeo en 10 y la utilidad_neta en -0.01.

   margen_min: Es el Precio Final m?nimo permitido, si despues de realizar todos los calculos, el precio final resulta menor al valor de margen_min, se sobreecribe y se toma el valor de margen_min.

   margen_max: Es el Precio Final maximo permitido, si despues de realizar todos los calculos, el precio final resulta mayor al valor de margen_max, se sobreescribe y se toma el valor de margen_max.
   


Si no se recibe un producto, servicio, clasificacion de producto o servicio, unidad o paquete junto a estos parametros, se toma que afectara a todos los productos, servicios, clasificaciones, unidades y paquetes.

Si se recibe un producto sin unidad, entonces los parametros afectan a todos los productos sin importar su unidad, si solo se recibe una unidad sin productos, es ignorada y se toma la tarifa como que afecta a todos los productos, servicios, clasificaciones, etc.

NOTA: Se debe de tener cuidad al configurar el margen_min y margen_max pues si estos se aplican sin especificar un producto, servicio, clasificacion de producto o servicio, o paquete, aplicaran a todos los productos, servicios y paquetes.

La asignacion de una formula a algun producto, servicio, etc. requiere una secuencia, pues pueden ser afectados por mas de una formula. La secuencia indicara que formula se aplciara en lugar de otra ya almacenada.


  *
  *
  *
  **/

  class ApiPrecioTarifaNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_moneda" => new ApiExposedProperty("id_moneda", true, POST, array( "int" )),
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"tipo_tarifa" => new ApiExposedProperty("tipo_tarifa", true, POST, array( "string" )),
			"default" => new ApiExposedProperty("default", false, POST, array( "bool" )),
			"fecha_fin" => new ApiExposedProperty("fecha_fin", false, POST, array( "string" )),
			"fecha_inicio" => new ApiExposedProperty("fecha_inicio", false, POST, array( "string" )),
			"formulas" => new ApiExposedProperty("formulas", false, POST, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::NuevaTarifa( 
 			
			
			isset($_POST['id_moneda'] ) ? $_POST['id_moneda'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['tipo_tarifa'] ) ? $_POST['tipo_tarifa'] : null,
			isset($_POST['default'] ) ? $_POST['default'] : null,
			isset($_POST['fecha_fin'] ) ? $_POST['fecha_fin'] : null,
			isset($_POST['fecha_inicio'] ) ? $_POST['fecha_inicio'] : null,
			isset($_POST['formulas'] ) ? json_decode($_POST['formulas']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
