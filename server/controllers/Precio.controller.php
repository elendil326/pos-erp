<?php
require_once("interfaces/Precio.interface.php");
/**
  *
  *
  *
  **/
	
  class PrecioController implements IPrecio{

      
	/**
	 * 
	 * 
	 * 
	 * */
  	public static function calcularTarifas( VO $obj ){
		
		if( !( ($obj instanceof Producto)
			|| ($obj instanceof Servicio)
			|| ($obj instanceof Paquete) )
		 ){
			throw new Excpetion( "Debes enviar una instancia de Producto, Servicio o Paquete al calcular la tarifa" );
		}
		
		$tarifas = TarifaDAO::obtenerTarifasActuales();

		$respuesta = array();

		foreach($tarifas as $t)	{

			array_push( $respuesta, array(
				"id_tarifa"  => $t["id_tarifa"],
				"precio"	 => 55 //ReglasDAO::aplicarReglas( $t["reglas"], $obj );
			));
		}

		return $respuesta;
	}
      
        /*
         *Se valida que un string tenga longitud en un rango de un maximo inclusivo y un minimo exclusvio.
         *Regresa true cuando es valido, y un string cuando no lo es.
         */
          private static function validarString($string, $max_length, $nombre_variable,$min_length=0)
	{
		if(strlen($string)<=$min_length||strlen($string)>$max_length)
		{
		    return "La longitud de la variable ".$nombre_variable." proporcionada (".$string.") no esta en el rango de ".$min_length." - ".$max_length;
		}
		return true;
        }


        /*
         * Se valida que un numero este en un rango de un maximo y un minimo inclusivos
         * Regresa true cuando es valido, y un string cuando no lo es
         */
	private static function validarNumero($num, $max_length, $nombre_variable, $min_length=0)
	{
	    if($num<$min_length||$num>$max_length)
	    {
	        return "La variable ".$nombre_variable." proporcionada (".$num.") no esta en el rango de ".$min_length." - ".$max_length;
	    }
	    return true;
        }
	
        
        /*
         * Valida los parametros de un producto
         */
        private static function validarProducto
        (
                $id_producto = null,
                $id_unidad = null,
                $tipo_tarifa = null
        )
        {
            if(!is_null($id_producto))
            {
                //Se valida que el producto exista, que este activo y si se busca un precio de compra, que el producto se pueda comprar.
                $producto = ProductoDAO::getByPK($id_producto);
                if(is_null($producto))
                {
                    return "El producto ".$id_producto." no existe";
                }

                if(!$producto->getActivo())
                {
                    return "El producto ".$id_producto."  no esta activo";
                }

                if(!$producto->getCompraEnMostrador()&&$tipo_tarifa=="compra")
                {
                    return "Se quiere averiguar el precio de compra de un producto que no se puede comprar en mostrador";
                }
            }

            //valida la unidad si es que se recibio
            if(!is_null($id_unidad))
            {
                $unidad = UnidadDAO::getByPK($id_unidad);
                if(is_null($unidad))
                {
                    Logger::error("La unidad con id ".$id_unidad." no existe");
                    throw new Exception("La unidad con id ".$id_unidad." no existe",901);
                }

                if(!$unidad->getActiva())
                {
                    Logger::error("La unidad ".$id_unidad." no esta activa");
                    throw new Exception("La unidad ".$id_unidad." no esta activa",901);
                }
            }
        }
        
        /*
         * Valida los parametros de un servicio
         */
        private static function validarServicio
        (
                $id_servicio 
        )
        {
            $servicio = ServicioDAO::getByPK($id_servicio);
            if(is_null($servicio))
            {
                return "El servicio ".$id_servicio."  no existe";
            }
            
            if(!$servicio->getActivo())
            {
                return "El servicio ".$id_servicio." no esta activo";
            }
        }
        
        /*
         * Valida los parametros de un paquete
         */
        private static function validarPaquete
        (
                $id_paquete
        )
        {
            $paquete = PaqueteDAO::getByPK($id_paquete);
            if(is_null($paquete))
            {
                return "El paquete ".$id_paquete." no existe";
            }
            
            if(!$paquete->getActivo())
            {
                return "El paquete ".$id_paquete." no esta activo";
            }
        }
        
        
	/**
 	 *
 	 *Activa una tarifa preciamente eliminada
 	 *
 	 * @param id_tarifa int Id de la tarifa a activar
 	 **/
	public static function ActivarTarifa
	(
		$id_tarifa
	)
	{
            
        }
  
	/**
 	 *
 	 *Asigna el default a una tarifa de compra. La tarifa default es la que se usara en todas las compras a menos que el usuario indique otra tarifa.

Solo se puede elegir una tarifa de tipo compra.
 	 *
 	 * @param id_tarifa int Id de la tarifa de compra a elegir como default del sistema
 	 **/
	private static function CompraSetDefaultTarifa
	(
		$id_tarifa
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crea una nueva tarifa 
 	 *
 	 * @param id_moneda int Id de la moneda en la que se manejaran los valores de precios en esta tarifa
 	 * @param nombre string nombre de la tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" y sirve para identificar si la tarifa se aplicara en compras o en ventas
 	 * @param activa bool Si esta tarifa estara activa al momento de su creacion
 	 * @param default bool Si se quiere que esta tarifa sea la default del sistema
 	 * @return id_tarifa int Id de la tarifa creada
 	 **/
	private static function NuevaTarifaBase
	(
		$id_moneda, 
		$nombre, 
		$tipo_tarifa, 
		$activa = null, 
		$default = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion b?sica de una tarifa, su nombre, su tipo de tarifa o su moneda. Si se edita el tipo de tarifa se tiene que verificar que esta tarifa no este siendo usada por default en una tarifa de su tipo anterior. 

Ejemplo: La tarifa 1 es tarifa de compra, el usuario 1 tiene como default de tarifa de compra la tarifa 1. Si queremos editar el tipo de tarifa de la tarifa 1 a una tarifa de venta tendra que mandar error, especificando que la tarifa esta siendo usada como tarifa de compra por el usuario 1.

Los parametros que no sean explicitamente nulos seran tomados como edicion.
 	 *
 	 * @param id_tarifa int Id de la tarifa que se va a editar
 	 * @param id_moneda int Id de la moneda con la que se manejaran las operaciones de esta tarifa
 	 * @param nombre string Nombre de la tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si la tarifa sera usada en las operaciones de compra o de venta
 	 **/
	private static function EditarTarifaBase
	(
		$id_tarifa, 
		$id_moneda = null, 
		$nombre = null, 
		$tipo_tarifa = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Duplica la version obtenida junto con todas sus reglas y la guarda en otra tarifa. Este metodo sirve cuando una misma version con todas sus reglas aplica a mas de una tarifa.

Al duplicar una version, las reglas duplicadas con ella actualizan su id de la version a la nueva version creada.

Cuando una version activa y/o default se duplica, al guardarse en la otra tarifa pierde estas propiedades.
 	 *
 	 * @param id_tarifa int Id de la tarifa en la que se guardara la version con todas sus reglas
 	 * @param id_version int Id de la version a duplicar
 	 * @return id_version int Id de la version creada
 	 **/
	private static function DuplicarVersion
	(
		$id_tarifa, 
		$id_version
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Selecciona como default para las ventas una tarifa de venta. Esta tarifa sera usada para todas las ventas a menos que el usuario indique otra tarifa de venta.

Solo puede asignarse como default de ventas una tarifa de tipo venta
 	 *
 	 * @param id_tarifa int Id de la tarifa a poner como default
 	 **/
	private static function VentaSetDefaultTarifa
	(
		$id_tarifa
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion basica de una version. El nombre, la fecha de inicio y la fecha de fin.

?Sera necesario permitir que el usuario cambie una version de una tarifa a otra tarifa?
 	 *
 	 * @param id_version int Id de la version a editar
 	 * @param fecha_fin string Fecha a aprtir de la cual se dejaran de aplicar las reglas de esta version. Si esta fecha ya paso se aplicaran las reglas de la version default de la tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las reglas de esta version. Si esta fecha aun no llega, se aplicaran las reglas de la version default de la tarifa.
 	 * @param nombre string Nombre de la version
 	 **/
	private static function EditarVersion
	(
		$id_version, 
		$fecha_fin = null, 
		$fecha_inicio = null, 
		$nombre = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Activa una version. Como solo puede haber una version activa por tarifa, este metodo desactiva la version actualmente activa de la tarifa y activa la version obtenida como parametro.
 	 *
 	 * @param id_version int Id de la version a activar
 	 **/
	private static function ActivarVersion
	(
		$id_version
	)
	{  
  
  
	}
  
	/**
 	 *
<<<<<<< .mine
 	 *Pone como default a la version obtenida para esta tarifa. Solo puede haber una version default por tarifa, asi que este metodo le quita el default a la version que lo era anteriormente y lo pone en la version obtenida como parametro.

=======
 	 *Desactiva una tarifa. Para poder desactivar una tarifa, esta no tiene que estar asignada como default para ningun usuario. La tarifa default del sistema no puede ser eliminada.

La tarifa instalada por default no puede ser eliminada
 	 *
 	 * @param id_tarifa int Id de la tarifa a eliminar
 	 **/
	public static function EliminarTarifa
	(
		$id_tarifa
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Pone como default a la version obtenida para esta tarifa. Solo puede haber una version default por tarifa, asi que este metodo le quita el default a la version que lo era anteriormente y lo pone en la version obtenida como parametro.

>>>>>>> .r2127
Una version default no puede caducar.
 	 *
 	 * @param id_version int Id de la version a la que se le dara el default
 	 **/
	private static function SetDefaultVersion
	(
		$id_version
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Elimina una version permamentemente de la base de datos junto a todas sus reglas.

La version default de la tarifa no puede ser eliminada ni la version activa.

La version por default de la tarifa instalada por default no puede ser eliminada
 	 *
 	 * @param id_version int Id de la version a eliminar
 	 **/
	private static function EliminarVersion
	(
		$id_version
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crea una nueva version para una tarifa.

Si no se reciben fechas de inicio o fin, se da por hecho que la version no caduca. Si solo se recibe fecha de fin, se toma como la fecha de inicio la fecha actual del servidor. Si solo se recibe fecha de inicio, se toma como fecha final la maxima permitida por MySQL (9999-12-31 23:59:59).

La version por default de una tarifa no puede caducar.

Las tarifas solo pueden tener una version activa.
 	 *
 	 * @param id_tarifa int Id de la tarifa a la cual pertenece esta version
 	 * @param nombre string Nombre de la version
 	 * @param activa bool Determina si esta version sera la version activa para esta tarifa
 	 * @param default bool Si esta sera la version por default de esta tarifa. Una version por default no puede caducar
 	 * @param fecha_fin string Fecha a partir de la cual se dejaran de aplicar las reglas de esta version. Cuando pase esta fecha, se usaran las reglas de la version por default de esta tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las reglas de esta version. Si aun no llega etsa fecha, se usaran las reglas de la version por default de esta tarifa
 	 * @return id_version int Id de la version creada
 	 **/
	private static function NuevaVersion
	(
		$id_tarifa, 
		$nombre, 
		$activa = null, 
		$default = null, 
		$fecha_fin = null, 
		$fecha_inicio = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crea una nueva regla para una version. 

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
 	 * @param id_version int Id de la version a la que pertenecera esta regla
 	 * @param nombre string Nombre de la regla
 	 * @param secuencia int Numero de secuencia de la regla, sirve para definir prioridades entre las reglas.
 	 * @param cantidad_minima int Cantidad minima que debe cumplirse de objetos para que esta regla se cumpla
 	 * @param id_clasificacion_producto int Id de la clasificacion del producto a la que se le aplicara esta regla
 	 * @param id_clasificacion_servicio int Id de la clasificacion del servicio a la cual se le aplicara esta regla
 	 * @param id_paquete int Id del paquete al cal se le aplicara esta regla
 	 * @param id_producto int Id del producto al que se le aplicara esta regla
 	 * @param id_servicio int Id del servicio al cual se le aplicara esta regla
 	 * @param id_tarifa int Id de la tarifa que se usara para determinar el precio base del objeto
 	 * @param id_unidad int La regla se aplicara a los productos (especificados por el id_producto o id_clasificacion_producto) que esten en esta unidad. Si un id de producto no ha sido especificado, este valor se ignora.
 	 * @param margen_max float Pendiente descripcion por Manuel
 	 * @param margen_min float Pendiente descripcion por Manuel
 	 * @param metodo_redondeo float Pendiente descricpion por manuel
 	 * @param porcentaje_utilidad float Porcentaje de utilidad, va de -1 a 1
 	 * @param utilidad_neta float La utilidad neta que se ganara, puede ser negativa indicando un descuento
 	 * @return id_regla int Id de la regla creada
 	 **/
	private static function NuevaRegla
	(
		$id_version, 
		$nombre, 
		$secuencia, 
		$cantidad_minima = null, 
		$id_clasificacion_producto = null, 
		$id_clasificacion_servicio = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null, 
		$margen_max = 0, 
		$margen_min = 0, 
		$metodo_redondeo = 0, 
		$porcentaje_utilidad = 0, 
		$utilidad_neta = 0
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista las versiones existentes, se puede filtrar por la tarifa y ordenar por los atributos de al tabla
 	 *
 	 * @param id_tarifa int Si este valor es obtenido, se listaran las versiones pertenecientes a esta tarifa
 	 * @param orden string nombre de al columna por la cual sera ordenada l alista
 	 * @return lista_versiones json Arreglo de versiones
 	 **/
	private static function ListaVersion
	(
		$id_tarifa = null, 
		$orden = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista las tarifas existentes. Se puede ordenar de acuerdo a los atributos de la tabla y se puede filtrar por el tipo de tarifa, la moneda que usa y por el valor de activa.
 	 *
 	 * @param activa bool Si este valor es obtenido, se listaran las tarifas que cuyo valor de activa sea como el obtenido
 	 * @param id_moneda int Si es obtenido este valor, se listaran las tarifas que tengan el valor de moneda como el obtenido.
 	 * @param orden string El nombre de la columna de la tabla por la cual se ordenara la lista
 	 * @param tipo_tarifa string Si es obtenido, se listaran las tarifas que tengan el mismo valor de tipo de tarifa que este.
 	 * @return lista_tarifas json Arreglo de tarifas
 	 **/
	private static function ListaTarifa
	(
		$activa = null, 
		$id_moneda = null, 
		$orden = null, 
		$tipo_tarifa = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Duplica una tarifa con todas sus versiones, y cada una de ellas con todas sus reglas. Este metodo sirve cuando se tiene una tarifa muy completa y se quiere hacer una tarifa muy similar pero con unas ligeras modificaciones.

Al duplicar la tarifa, se actualizan sus versiones default y activa por los ids generados al duplicar las versiones.

La tarifa duplicada pierde ela tributo default.
 	 *
 	 * @param id_moneda int Id de la moneda que aplicara para la nueva tarifa
 	 * @param id_tarifa int Id de la tarifa a duplicar
 	 * @param nombre string Nombre de la nueva tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si la nueva tarifa sera aplicada para compras o ventas
 	 * @return id_tarifa int Id de la tarifa creada
 	 **/
	private static function DuplicarTarifa
	(
		$id_moneda, 
		$id_tarifa, 
		$nombre, 
		$tipo_tarifa
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion basica de una regla. 

Los parametros recibidos seran tomados para edicion.

?Sera necesario dar la oportunidad al usuario de cambiar la version a la que pertence la regla?
 	 *
 	 * @param id_regla int Id de la regla a editar
 	 * @param cantidad_minima int Cantidad minima de objetos para que esta regla se cumpla
 	 * @param id_clasificacion_producto int Id de la categoria del producto sobre la cual actuara esta regla
 	 * @param id_clasificacion_servicio int Id de la clasificacion de servicio sobre la cual actuara esta regla
 	 * @param id_paquete int Id del paquete sobre el cual actuara esta regla
 	 * @param id_producto int Id del producto sobre el cual actuara esta regla
 	 * @param id_servicio int Id del servicio sobre el cual actuara esta regla
 	 * @param id_tarifa int Id de la tarifa de donde se obtendra el precio base
 	 * @param id_unidad int La regla se aplicara a los productos (especificados por el id_producto o id_clasificacion_producto) que esten en esta unidad. Si un id de producto no ha sido especificado, este valor se ignora.
 	 * @param margen_max float Falta definir por Manuel
 	 * @param margen_min float Falta definir por Manuel
 	 * @param metodo_redondeo float Falta definir por Manuel
 	 * @param nombre string Nombre del usuario
 	 * @param porcentaje_utilidad float Porncetaje de utilidad que se ganara al comercia con este objeto
 	 * @param secuencia int Numero de secuencia de esta regla
 	 * @param utilidad_neta float Utilidad neta que s eganara al comerciar con este objeto
 	 **/
	private static function EditarRegla
	(
		$id_regla, 
		$cantidad_minima = null, 
		$id_clasificacion_producto = null, 
		$id_clasificacion_servicio = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null, 
		$margen_max = null, 
		$margen_min = null, 
		$metodo_redondeo = null, 
		$nombre = null, 
		$porcentaje_utilidad = null, 
		$secuencia = null, 
		$utilidad_neta = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Elimina una regla. La regla por default de l aversion por default de la tarifa por default no puede ser eliminada
 	 *
 	 * @param id_regla int Id de la regla a eliminar
 	 **/
	private static function EliminarRegla
	(
		$id_regla
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista las reglas existentes. Puede filtrarse por la version, por producto, por unidad, por categoria de producto o servicio, por servicio o por paquete, por tarifa base o por alguna combinacion de ellos.
 	 *
 	 * @param id_clasificacion_producto int Si se recibe este parametro, se lsitaran las reglas que afectan a esta clasificacion de producto
 	 * @param id_clasificacion_servicio int Si se recibe este parametro, se listaran las reglas que afecten a esta clasificacion de servicio
 	 * @param id_paquete int Si se recibe este parametro, se listaran las reglas que afecten a este paquete
 	 * @param id_producto int Si se recibe este parametro se listaran las reglas que afectan a este producto
 	 * @param id_servicio int Si se recibe este parametro se listaran las reglas que afecten a este servicio
 	 * @param id_tarifa int Si se recibe este parametro, se listaran las reglas que se basen en esta tarifa
 	 * @param id_unidad int Si se recibe este parametro, se listaran las reglas que afecten a esta unidad
 	 * @param id_version int Si se obtiene este parametro se listaran las relas de esta version
 	 * @return lista_reglas int Arreglo de reglas
 	 **/
	private static function ListaRegla
	(
		$id_clasificacion_producto = null, 
		$id_clasificacion_servicio = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null, 
		$id_version = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Duplica una regla y la guarda en otra version. Las reglas duplicadas actualizan el id de la version a la que pertenecen y mantienen todos sus datos.
 	 *
 	 * @param id_regla int Id de la regla a duplicar
 	 * @param id_version int Id de la version a la cual se duplicara la regla
 	 * @return id_regla int Id de la regla creada
 	 **/
	private static function DuplicarRegla
	(
		$id_regla, 
		$id_version
	)
	{  
  
  
	}
          
        
        /**
 	 *
 	 *Calcula el precio de un producto. Se calcula un precio por tarifa activa en el sistema y al final se regresa un arreglo con las tarifas y su respectivo precio.

El precio es calculado a partir de las reglas de una tarifa.
 	 *
 	 * @param id_producto int Id del producto al cual se le desea calcular su precio
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si los precios a calcular seran en base a tarifas de compra o de venta
 	 * @param cantidad float Cantidad de producto a calcular su precio. Pues existen algunas reglas que aplican solo si hay una cierta cantidad de producto
 	 * @return precios json Arreglo de tarifas con sus respectivos precios
 	 **/
	public static function CalcularPorArticulo
	(
		$tipo_tarifa, 
		$cantidad = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null
	)
	{  
            Logger::log("Calculando precio de ".$tipo_tarifa."  para el producto ".$id_producto." o el servicio ".$id_servicio." o el paquete ".$id_paquete);
            
            //si no se recibe ningun articulo, se regresa un error
            if(is_null($id_producto)&&is_null($id_servicio)&&  is_null($id_paquete))
            {
                Logger::error("No se sabe si se calculara el precio de un producto, un servicio o un paquete");
                throw new Exception("No se sabe si se calculara el precio de un producto, un servicio o un paquete",901);
            }
            
            //Se valida que el tipo de tarifa sea valido
            if($tipo_tarifa!="compra" && $tipo_tarifa!="venta")
            {
                Logger::error("El tipo de tarifa (".$tipo_tarifa.") es invalido, tiene que ser 'compra' o 'venta'");
                throw new Exception("El tipo de tarifa (".$tipo_tarifa.") es invalido, tiene que ser 'compra' o 'venta'",901);
            }
            
            //Valida la cantidad recibida
            $validar = self::validarNumero($cantidad, 1.8e200, "cantidad");
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            if(!is_null($id_producto))
            {
                $validar = self::validarProducto($id_producto, $id_unidad, $tipo_tarifa);
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar,901);
                }
            }
            else if(!is_null($id_servicio))
            {
                $validar = self::validarServicio($id_servicio);
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar,901);
                }
            }
            else if(!is_null($id_paquete))
            {
                $validar = self::validarPaquete($id_paquete);
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar,901);
                }
            }
            
            //Se obtienen las reglas de cada tarifa
            
            $reglas = TarifaDAO::obtenerTarifa();

            //Se calcula cada precio de acuerdo a las reglas obtenidas y se almacena en el arreglo final

            $precios = array();

            foreach($reglas as $regla)
            {
                $precio = array();

                $precio["id_tarifa"] = $regla["id_tarifa"];
                foreach($regla["reglas"] as $r)
                {
                    if($r->getIdTarifa()==-1)
                    {
                        if(!is_null($id_producto))
                        {
                            $producto = ProductoDAO::getByPK($id_producto);
                            if($producto->getMetodoCosteo()=="costo")
                            {
                                $precio_base = $producto->getCostoEstandar();
                            }
                            else if($producto->getMetodoCosteo()=="precio")
                            {
                                $precio_base = $producto->getPrecio();
                            }
                            else
                            {
                                Logger::error("El producto tiene un metodo de costeo invalido");
                                throw new Exception("El producto tiene un metodo de costeo invalido", 901);
                            }
                        }
                        else if(!is_null($id_servicio))
                        {
                            $servicio = ServicioDAO::getByPK($id_servicio);
                            if($servicio->getMetodoCosteo()=="costo")
                            {
                                $precio_base = $servicio->getCostoEstandar();
                            }
                            else if($servicio->getMetodoCosteo()=="precio")
                            {
                                $precio_base = $servicio->getPrecio();
                            }
                            else
                            {
                                Logger::error("El servicio tiene un metodo de costeo invalido");
                                throw new Exception("El servicio tiene un metodo de costeo invalido", 901);
                            }
                        }
                        else
                        {
                            $paquete = PaqueteDAO::getByPK($id_paquete);
                            $precio_base = $paquete->getPrecio();
                        }
                    }
                    else
                    {
                        
                    }
                }

            }
            
            
	}
  
	/**
 	 *
 	 *Crea una nueva tarifa que le dara un precio especial a todos los productos, servicios y paquetes o solo a algunos. 

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
 	 * @param id_moneda int Id de la moneda con la que se realizaran las operaciones.
 	 * @param nombre string Nombre de la tarifa.
 	 * @param tipo_tarifa string Puede ser "venta" o "compra" e indica si la tarifa sera aplicada en las operaciones de venta o compra.
 	 * @param default bool Si esta tarifa va a ser la default del sistema o no
 	 * @param fecha_fin string Fecha a partir de la cual se dejaran de aplicar las formulas de esta tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las formulas de esta tarifa
 	 * @param formulas json Un arreglo de objetos que contendran la siguiente informacion:
        "formulas" : [
       {
          "id_producto"                 : null,
          "id_unidad"                   : null,
          "id_clasificacion_producto"   : null,
          "id_servicio"                 : null,
          "id_paquete"                  : null,
          "id_clasificacion_servicio"   : null,
          "cantidad_minima"             : null,
          "id_tarifa"                   : -1,
          "porcentaje_utilidad"         : 0.00,
          "utilidad_neta"               : 0.00,
          "metodo_redondeo"             : 0.00,
          "margen_min"                  : 0.00,
          "margen_max"                  : 0.00,
          "secuencia"                   : 5
                 }
       ]
   Para mas informacion de estos parametros consulte la documentacionde este metodo. El parametro id_tarifa es la tarifa base de donde se sacara el Precio Base para la formula.
 	 * @return id_tarifa int Id de la tarifa creada
 	 **/
	public static function NuevaTarifa
	(
		$id_moneda, 
		$nombre, 
		$tipo_tarifa, 
		$default = null, 
		$fecha_fin = null, 
		$fecha_inicio = null, 
		$formulas = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion de una tarifa. Este metodo puede cambiar las formulas de una tarifa o la vigencia de la misma. 

Este metodo tambien puede ponder como default esta tarifa o quitarle el default. Si se le quita el default, automaticamente se pone como default la predeterminada del sistema.
Si se obtienen formulas en este metodo, se borraran todas las formulas de esta tarifa y se aplicaran las recibidas

Si se cambia el tipo de tarifa, se verfica que esta tarifa no sea una default para algun rol, usuario, clasificacion de cliente o de proveedor, y pierde su default si fuera la default, poniendo como default la predetermianda del sistema.

Aplican todas las consideraciones de la documentacion del metodo nuevaTarifa
 	 *
 	 * @param id_tarifa int Id de la tarifa a editar
 	 * @param default bool Si esta tarifa sera la default
 	 * @param fecha_fin string Fecha a partir de la cual se dejaran de aplicar las formulas de esta tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las formulas de esta tarifa
 	 * @param formulas json Un arreglo de objetos que contendran la siguiente informacion:        
"formulas" : [
       {
          "id_producto"                 : null,
          "id_unidad"                   : null,
          "id_clasificacion_producto"   : null,
          "id_servicio"                 : null,
          "id_paquete"                  : null,
          "id_clasificacion_servicio"   : null,
          "cantidad_minima"             : null,
          "id_tarifa"                   : -1,
          "porcentaje_utilidad"         : 0.00,
          "utilidad_neta"               : 0.00,
          "metodo_redondeo"             : 0.00,
          "margen_min"                  : 0.00,
          "margen_max"                  : 0.00,
          "secuencia"                   : 5
                 }
       ]
   Para mas informacion de estos parametros consulte la documentacion del metodo nuevaTarifa. El parametro id_tarifa es la tarifa base de donde se sacara el Precio Base para la formula. La tarifa -1 inidica que no hay una tarifa base, sino que se toma el precio base del producto, o su costo base, segun marque su metodo de costeo.
 	 * @param id_moneda int Id de la moneda con la cual se realizaran todos los movimientos de la tarifa
 	 * @param nombre string Nombre de la tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si la tarifa sera de compra o de venta
 	 **/
	public static function EditarTarifa
	(
		$id_tarifa, 
		$default = null, 
		$fecha_fin = null, 
		$fecha_inicio = null, 
		$formulas = null, 
		$id_moneda = null, 
		$nombre = null, 
		$tipo_tarifa = null
	)
	{  
  
  
	}

  }
