<?php
require_once("interfaces/Productos.interface.php");
/**
  *
  *
  *
  **/
	
  class ProductosController implements IProductos{
  
        //Metodo para pruebas que simula la obtencion del id de la sucursal actual
        private static function getSucursal()
        {
            return 1;
        }
        
        //metodo para pruebas que simula la obtencion del id de la caja actual
        private static function getCaja()
        {
            return 1;
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
         * Valida los parametros de la tabla unidad. Regresa un string con el error en caso de
         * encontrarse alguno, en caso contrario regresa true.
         */
        private static function validarParametrosUnidad
        (
                $id_unidad = null,
                $nombre = null,
                $descripcion = null,
                $es_entero = null,
                $activa = null
        )
        {
            //valida que la unidad exista y que este activa
            if(!is_null($id_unidad))
            {
                $unidad = UnidadDAO::getByPK($id_unidad);
                if(is_null($unidad))
                    return "La unidad con id ".$id_unidad." no existe";
                
                if(!$unidad->getActiva())
                    return "La unidad esta desactivada";
            }
            
            //valida que el nombre este en el rango
            if(!is_null($nombre))
            {
                $e = self::validarString($nombre, 100, "nombre");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la descripcion este en rango
            if(!is_null($descripcion))
            {
                $e = self::validarString($descripcion, 255, "descripcion");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano es_entero
            if(!is_null($es_entero))
            {
                $e = self::validarNumero($es_entero, 1, "es entero");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano activa
            if(!is_null($activa))
            {
                $e = self::validarNumero($activa, 1, "activa");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error, regresa true
            return true;
        }
        
        /*
         * Valida los parametros de la tabla producto. Regresa un string con el error
         * cuando se ha encontrado alguno, regresa true en caso contrario
         */
        private static function validarParametrosProducto
        (
                $id_producto = null,
                $compra_en_mostrador = null,
                $metodo_costeo = null,
                $activo = null,
                $codigo_producto = null,
                $nombre_producto = null,
                $garantia = null,
                $costo_estandar = null,
                $control_de_existencia = null,
                $margen_de_utilidad = null,
                $descuento = null,
                $descripcion = null,
                $foto_del_producto = null,
                $costo_extra_almacen = null,
                $codigo_de_barras = null,
                $peso_producto = null,
                $id_unidad = null,
                $precio = null
        )
        {
            //valida que el producto exista y que este activo
            if(!is_null($id_producto))
            {
                $producto = ProductoDAO::getByPK($id_producto);
                if(is_null($producto))
                {
                    return "El producto con id ".$id_producto." no existe";
                }
                if(!$producto->getActivo())
                {
                    return "El producto esta desactivado";
                }
            }
            
            //valida el boleano compra en mostrador
            if(!is_null($compra_en_mostrador))
            {
                $e = self::validarNumero($compra_en_mostrador, 1, "compra en mostrador");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el metodo de costeo sea valido
            if(!is_null($metodo_costeo))
            {
                if($metodo_costeo!="precio"&&$metodo_costeo!="margen")
                    return "E metodo de costeo (".$metodo_costeo.") es invalido";
            }
            
            //valida el boleano activo
            if(!is_null($activo))
            {
                $e = self::validarNumero($activo, 1, "activo");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el codigo de producto este en rango
            if(!is_null($codigo_producto))
            {
                $e = self::validarString($codigo_producto, 30, "codigo de producto");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el nombre del producto este en rango y que no se repita
            if(!is_null($nombre_producto))
            {
                $e = self::validarString($nombre_producto, 30, "nombre de producto");
                if(is_string($e))
                    return $e;
                $productos = ProductoDAO::search( new Producto( array( "nombre_producto" => trim($nombre_producto) ) ) );
                foreach($productos as $p)
                {
                    if($p->getActivo())
                        return "El nombre (".$nombre_producto.") ya esta en uso por el producto ".$p->getIdProducto();
                }
            }
            
            //valida que la garantia este en rango
            if(!is_null($garantia))
            {
                $e = self::validarNumero($garantia, PHP_INT_MAX, "garantia");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el costo estandar este en rango
            if(!is_null($costo_estandar))
            {
                $e = self::validarNumero($costo_estandar, 1.8e200, "costo estandar");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el control de existencia este en rango
            if(!is_null($control_de_existencia))
            {
                $e = self::validarNumero($control_de_existencia, 1.8e200, "control de existencia");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el margen de utilidad este en rango
            if(!is_null($margen_de_utilidad))
            {
                $e = self::validarNumero($margen_de_utilidad, 1.8e200, "margen de utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el descuento este en rango
            if(!is_null($descuento))
            {
                $e = self::validarNumero($descuento, 100, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la foto del producto este en rango
            if(!is_null($foto_del_producto))
            {
                $e = self::validarString($foto_del_producto, 100, "foto del producto");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el costo extra de almacen este en rango
            if(!is_null($costo_extra_almacen))
            {
                $e = self::validarNumero($costo_extra_almacen, 1.8e200, "costo extra de almacen");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el codigo de barras este en rango
            if(!is_null($codigo_de_barras))
            {
                $e = self::validarString($codigo_de_barras, 30, "codigo de barras");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el peso del producto este en rango
            if(!is_null($peso_producto))
            {
                $e = self::validarNumero($peso_producto, 1.8e200, "peso de producto");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la unidad exista y que este activa
            if(!is_null($id_unidad))
            {
                $e = self::validarParametrosUnidad($id_unidad);
                if(is_string($e))
                    return $e;
            }
            
            //valida que el precio este en rango
            if(!is_null($precio))
            {
                $e = self::validarNumero($precio, 1.8e200, "precio");
                if(is_string($e))
                    return $e;
            }
        }
        
        /*
         * Valida los parametros de la tabla producto_empresa. Regres aun string con el error si encuentra
         * alguno, regresa verdadero en caso contrario.
         */
        
        private static function validarParametrosProductoEmpresa
        (
                $id_empresa = null,
                $precio_utilidad = null,
                $es_margen_utilidad = null
        )
        {
            //valida que la empresa exista y este activa
            if(!is_null($id_empresa))
            {
                $empresa = EmpresaDAO::getByPK($id_empresa);
                if(is_null($empresa))
                    return "La empresa con id ".$id_empresa." no existe";
                
                if(!$empresa->getActivo())
                    return "La empresa esta desactivada";
            }
            
            //valida que el precio_utilidad este en rango
            if(!is_null($precio_utilidad))
            {
                $e = self::validarNumero($precio_utilidad, 1.8e200, "precio_utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano es_margen_utilidad
            if(!is_null($es_margen_utilidad))
            {
                $e = self::validarNumero($es_margen_utilidad, 1, "es margen de utilidad");
                if(is_string($e))
                    return $e;
            }
        }
      
	/**
 	 *
 	 *Lista las unidades. Se puede filtrar por activas o inactivas y ordenar por sus atributos
 	 *
 	 * @param activo bool Si este valor no es obtenido, se listaran tanto activas como inactivas, si es true, se listaran solo las activas, si es false se listaran solo las inactivas
 	 * @param ordenar json Valor que determina el orden de la lista
 	 * @return unidades_convertibles json Lista de unidades convertibles
 	 **/
	public static function ListaUnidad
	(
		$activo = null, 
		$ordenar = null
	)
	{  
            Logger::log("Listando unidades");
            
            //valida los parametros recibidos
            $validar = self::validarParametrosUnidad(null,null,null,null,$activo);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            if(!is_null($ordenar))
            {
                if
                (
                        $ordenar != "id_unidad"     &&
                        $ordenar != "nombre"        &&
                        $ordenar != "descripcion"   &&
                        $ordenar != "es_entero"     &&
                        $ordenar != "activa"        
                )
                {
                    Logger::error("La variable ordenar (".$ordenar.") es invalida");
                    throw new Exception("La variable ordenar (".$ordenar.") es invalida");
                }
            }
            
            $unidades=null;
            if(is_null($activo))
            {
                Logger::log("No se recibieron parametros, se listan todas las unidades");
                $unidades=UnidadDAO::getAll(null,null,$ordenar);
            }
            else
            {
                Logger::log("Se recibieron parametros, se listan las unidades en rango");
                $unidades=UnidadDAO::search(new Unidad(array( "activa" => $activo )), $ordenar);
            }
            Logger::log("Lista de unidades obtenida con ".count($unidades)." elementos");
            return $unidades;
	}
  
	/**
 	 *
 	 *Crea un registro de la equivalencia entre una unidad y otra. Ejemplo: 1 kg = 2.204 lb
 	 *
 	 * @param id_unidad int Id de la unidad. Esta unidad es tomada con coeficiente 1 en la ecuacion de, en el ejemplo es el kilogramo equivalencia
 	 * @param id_unidades int Id de la unidad equivalente, en el ejemplo es la libra
 	 * @param equivalencia float Valor del coeficiente de la segunda unidad, es decir, las veces que cabe la segunda unidad en la primera
 	 **/
	public static function Nueva_equivalenciaUnidad
	(
		$id_unidad, 
		$id_unidades, 
		$equivalencia
	)
	{  
            Logger::log("Crenado nueva equivalencia de unidades");
            
            //valida los parametros recibidos
            $validar = self::validarParametrosUnidad($id_unidad);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $validar = self::validarParametrosUnidad($id_unidades);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            $valdiar = self::validarNumero($equivalencia, 1.8e200, "equivalencia");
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            if($id_unidad==$id_unidades)
            {
                Logger::error("No se puede crear una equivalencia para la misma unidad");
                throw new Exception("No se puede crear una equivalencia para la misma unidad");
            }
            $unidad_equivalencia=new UnidadEquivalencia(array(
                                            "id_unidad"     => $id_unidad,
                                            "equivalencia"  => $equivalencia,
                                            "id_unidades"   => $id_unidades
                                            )
                                        );
            DAO::transBegin();
            try
            {
                UnidadEquivalenciaDAO::save($unidad_equivalencia);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la equivalencia: ".$e);
                throw new Exception("No se pudo crear la equivalencia");
            }
            DAO::transEnd();
            Logger::log("La equivalencia fue creada exitosamente");
	}
  
	/**
 	 *
 	 *Edita la equivalencia entre dos unidades.
1 kg = 2.204 lb
 	 *
 	 * @param id_unidades int Id de la segunda unidad, en el ejemplo son libras
 	 * @param equivalencia float La nueva equivalencia que se pondra entre los dos valores, en el ejemplo es 2.204
 	 * @param id_unidad int Id de la unidad, en el ejemplo son kilogramos
 	 **/
	public static function Editar_equivalenciaUnidad
	(
		$id_unidades, 
		$equivalencia, 
		$id_unidad
	)
	{  
            Logger::log("Editando la equivalencia entre la unidad ".$id_unidad." y las unidades ".$id_unidades);
            
            //valida los parametros
            $unidad_equivalencia = UnidadEquivalenciaDAO::getByPK($id_unidad, $id_unidades);
            if(is_null($unidad_equivalencia))
            {
                Logger::error("La equivalencia entre la unidad ".$id_unidad." y las unidades ".$id_unidades." no existe");
                throw new Exception("La equivalencia entre la unidad ".$id_unidad." y las unidades ".$id_unidades." no existe");
            }
            $validar = self::validarNumero($equivalencia, 1.8e200, "equivalencia");
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            $unidad_equivalencia->setEquivalencia($equivalencia);
            DAO::transBegin();
            try
            {
                UnidadEquivalenciaDAO::save($unidad_equivalencia);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar la equivalencia: ".$e);
                throw new Exception("No se pudo editar la equivalencia");
            }
            DAO::transEnd();
            
            Logger::log("Equivalencia editada exitosamente");
	}
  
	/**
 	 *
 	 *Se puede ordenar por los atributos de producto. 
 	 *
 	 * @param activo bool Si es true, mostrar solo los productos que estn activos, si es false mostrar solo los productos que no lo sean.
 	 * @param id_lote int Id del lote del cual se vern sus productos.
 	 * @param id_almacen int Id del almacen del cual se vern sus productos.
 	 * @param id_empresa int Id de la empresa de la cual se vern los productos.
 	 * @param id_sucursal int Id de la sucursal de la cual se vern los productos.
 	 * @return productos json Objeto que contendr� el arreglo de productos en inventario.
 	 **/
	public static function Lista
	(
		$activo = null, 
		$compra_en_mostrador = null, 
		$id_almacen = null, 
		$id_empresa = null, 
		$metodo_costeo = null
	)
	{  
            Logger::log("Listando los productos");
            $productos = array();
            //Se verifica si se reciben parametros o no para usar el metodo getAll o search
            $parametros = false;
            if
            (
                    !is_null($activo)               ||
                    !is_null($compra_en_mostrador)  ||
                    !is_null($metodo_costeo)        
            )
            {
                Logger::log("Se recibieron parametros, se listan los productos en rango");
                
                //Si se recibe el parametro id_empresa, se traen los productos de esa empresa y se intersectan
                //con los que cumplen los demas parametros. Si no se recibe, se busca el parametro id_almacen
                //
                //Si se recibe el parametro id_almacen, se traen los productos de ese almacen y se intersectan
                //con los que cumplen los demas parametros. Si no se recibe, la interseccion se hara con todos los productos
                $productos1 = array();
                $productos2 = array();
                if(!is_null($id_empresa))
                {
                    $productos_empresa = ProductoEmpresaDAO::search( new ProductoEmpresa( array( "id_empresa" => $id_empresa ) ) );
                    foreach($productos_empresa as $p_e)
                    {
                        array_push($productos1, ProductoDAO::getByPK($p_e->getIdProducto()));
                    }
                }
                else if(!is_null($id_almacen))
                {
                    $productos_almacen = ProductoAlmacenDAO::search( new ProductoAlmacen( array( "id_almacen" => $id_almacen ) ) );
                    foreach($productos_almacen as $p_a)
                    {
                        array_push($productos1, ProductoDAO::getByPK($p_a->getIdProducto()));
                    }
                }
                else
                {
                    $productos1 = ProductoDAO::getAll();
                }
                $producto_criterio = new Producto( array( 
                                                "activo"                => $activo,
                                                "compra_en_mostrador"   => $compra_en_mostrador,
                                                "metodo_costeo"         => $metodo_costeo
                                                        )
                                                    );
                $productos2=ProductoDAO::search($producto_criterio);
                $productos=array_intersect($productos1, $productos2);
            }
            else
            {
                Logger::log("No se recibieron parametros, se listan todos los productos");
                if(!is_null($id_empresa))
                {
                    $productos_empresa = ProductoEmpresaDAO::search( new ProductoEmpresa( array( "id_empresa" => $id_empresa ) ) );
                    foreach($productos_empresa as $p_e)
                    {
                        array_push($productos, ProductoDAO::getByPK($p_e->getIdProducto()));
                    }
                }
                else if(!is_null($id_almacen))
                {
                    $productos_almacen = ProductoAlmacenDAO::search( new ProductoAlmacen( array( "id_almacen" => $id_almacen ) ) );
                    foreach($productos_almacen as $p_a)
                    {
                        array_push($productos, ProductoDAO::getByPK($p_a->getIdProducto()));
                    }
                }
                else
                {
                    $productos = ProductoDAO::getAll();
                }
            }
            Logger::log("Lista obtenida exitosamente con ".count($productos)." elementos");
            return $productos;
	}
  
	/**
 	 *
 	 *Crear un nuevo producto, 

NOTA: Se crea un producto tipo = 1 que es para productos
 	 *
 	 * @param activo bool Si queremos que este activo o no este producto despues de crearlo.
 	 * @param codigo_producto string El codigo de control de la empresa para este producto, no se puede repetir
 	 * @param id_empresas json Objeto que contendra el arreglo de ids de las empresas a la que pertenece este producto
 	 * @param nombre_producto string Nombre del producto
 	 * @param metodo_costeo string  Mtodo de costeo del producto: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
 	 * @param costo_estandar float Valor del costo estndar del producto.
 	 * @param compra_en_mostrador bool Verdadero si este producto se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param garant�a int Si este producto cuenta con un nmero de meses de garanta  que no aplica a los productos de su categora
 	 * @param costo_extra_almacen float Si este producto produce un costo extra por tenerlo en almacen
 	 * @param margen_de_utilidad float Un porcentage de 0 a 100 si queremos que este producto marque utilidad en especifico
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param peso_producto float el peso de este producto en KG
 	 * @param descripcion_producto string Descripcion larga del producto
 	 * @param impuestos json array de ids de impuestos que tiene este producto
 	 * @param clasificaciones json Uno o varios id_clasificacion de este producto, esta clasificacion esta dada por el usuarioArray
 	 * @param id_unidad_convertible int Si este producto se relacionara con una unidad convertible ( kilos, litros, libras, etc.)
 	 * @param codigo_de_barras string El Codigo de barras para este producto
 	 * @param id_unidad_no_convertible int Si este producto se relacionara con una unidad no convertible ( lotes, cajas, costales, etc.)
 	 * @param foto_del_producto string url a una foto de este producto
 	 * @param descuento float Descuento que se aplicara a este producot
 	 * @return id_producto int Id generado por la inserci�n del nuevo producto
 	 **/
	public static function Nuevo
	(
		$activo, 
		$codigo_producto, 
		$id_empresas, 
		$nombre_producto, 
		$metodo_costeo, 
		$costo_estandar, 
		$compra_en_mostrador, 
		$garantia = null,
		$costo_extra_almacen = null, 
		$margen_de_utilidad = null, 
		$control_de_existencia = null, 
		$peso_producto = null, 
		$descripcion_producto = null, 
		$impuestos = null, 
		$clasificaciones = null, 
		$id_unidad = null, 
		$codigo_de_barras = null, 
		$precio = null, 
		$foto_del_producto = null, 
		$descuento = null
	)
	{  
            Logger::log("Creando nuevo producto");
            
            //valida los parametros recibidos
            $validar = self::validarParametrosProducto(null,$compra_en_mostrador,$metodo_costeo,
                    $activo,$codigo_producto,$nombre_producto,$garantia,$costo_estandar,$control_de_existencia,
                    $margen_de_utilidad,$descuento,$descripcion_producto,$foto_del_producto,$costo_extra_almacen,
                    $codigo_de_barras,$peso_producto,$id_unidad,$precio);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            if(is_null($descuento))
                $descuento = 0;
            $producto = new Producto( array( 
                                    "compra_en_mostrador"   => $compra_en_mostrador,
                                    "metodo_costeo"         => $metodo_costeo,
                                    "activo"                => $activo,
                                    "codigo_producto"       => $codigo_producto,
                                    "nombre_producto"       => trim($nombre_producto),
                                    "garantia"              => $garantia,
                                    "costo_estandar"        => $costo_estandar,
                                    "control_de_existencia" => $control_de_existencia,
                                    "margen_de_utilidad"    => $margen_de_utilidad,
                                    "descuento"             => $descuento,
                                    "descripcion"           => $descripcion_producto,
                                    "foto_del_producto"     => $foto_del_producto,
                                    "costo_extra_almacen"   => $costo_extra_almacen,
                                    "codigo_de_barras"      => $codigo_de_barras,
                                    "peso_producto"         => $peso_producto,
                                    "id_unidad"             => $id_unidad,
                                    "precio"                => $precio
                                            )
                                        );
            
            DAO::transBegin();
            try
            {
                //Se guarda el producto creado y se asignan las empresas, los impuestos y las clasificaciones recibidas
                ProductoDAO::save($producto);
                if(!is_null($id_empresas))
                {
                    $producto_empresa = new ProductoEmpresa( array( "id_producto" => $producto->getIdProducto() ) );
                    foreach($id_empresas as $id_empresa)
                    {
                        $validar = self::validarParametrosProductoEmpresa($id_empresa["id_empresa"],$id_empresa["precio_utilidad"],$id_empresa["es_margen_utilidad"]);
                        if(is_string($validar))
                            throw new Exception($validar);
                        
                        $producto_empresa->setIdEmpresa($id_empresa["id_empresa"]);
                        $producto_empresa->setPrecioUtilidad($id_empresa["precio_utilidad"]);
                        $producto_empresa->setEsMargenUtilidad($id_empresa["es_margen_utilidad"]);
                        ProductoEmpresaDAO::save($producto_empresa);
                    }
                }/* Fin if de empresas */
                if(!is_null($impuestos))
                {
                    $impuesto_producto = new ImpuestoProducto( array( "id_producto" => $producto->getIdProducto() ) );
                    foreach($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception ("El impuesto con id ".$impuesto." no existe");
                        $impuesto_producto->setIdImpuesto($impuesto);
                        ImpuestoProductoDAO::save($impuesto_producto);
                    }
                }/* Fin if de impuestos */
                if(!is_null($clasificaciones))
                {
                    $producto_clasificacion = new ProductoClasificacion( array( "id_producto" => $producto->getIdProducto() ) );
                    foreach($clasificaciones as $clasificacion)
                    {
                        $c = ClasificacionProductoDAO::getByPK($clasificacion);
                        if(is_null($c))
                                throw new Exception("La clasificacion de producto con id ".$clasificacion." no existe");
                        
                        if(!$c->getActiva())
                            throw new Exception("La clasificaicon de producto con id ".$clasificacion." no esta activa");
                        
                        $producto_clasificacion->setIdClasificacionProducto($clasificacion);
                        ProductoClasificacionDAO::save($producto_clasificacion);
                    }
                }/* Fin if de clasificaciones */
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo guardar el nuevo producto: ".$e);
                throw new Exception("No se pudo guardar el nuevo producto");
            }
            DAO::transEnd();
            Logger::log("Producto creado exitosamente");
            return array( "id_producto" => $producto->getIdProducto() );
	}
  
	/**
 	 *
 	 *Agregar productos en volumen mediante un archivo CSV.
 	 *
 	 * @param productos json Arreglo de objetos que contendr�n la informaci�n del nuevo producto
 	 * @return id_productos json Arreglo de enteros que contendr� los ids de los productos insertados.
 	 **/
	public static function En_volumenNuevo
	(
		$productos
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo sirve para dar de baja un producto
 	 *
 	 * @param id_producto int Id del producto a desactivar
 	 **/
	public static function Desactivar
	(
		$id_producto
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informaci?e un producto
 	 *
 	 * @param id_producto int Id del producto a editar
 	 * @param descuento float Descuento que tendra este producot
 	 * @param metodo_costeo string Mtodo de costeo del producto: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
 	 * @param descripcion_producto string Descripcion larga del producto
 	 * @param id_unidad_no_convertible int Si este producto se relacionara con una unidad no convertible ( lotes, cajas, costales, etc.)
 	 * @param impuestos json array de ids de impuestos que tiene este producto
 	 * @param clasificaciones json Uno o varios id_clasificacion de este producto, esta clasificacion esta dada por el usuarioArray
 	 * @param id_unidad_convertible int Si este producto se relacionara con una unidad convertible (kilos, libras, litros, etc.) 
 	 * @param margen_de_utilidad float Un porcentage de 0 a 100 si queremos que este producto marque utilidad en especifico
 	 * @param garant�a int Si este producto cuenta con un nmero de meses de garantia que no aplican a los demas productos de su categoria
 	 * @param compra_en_mostrador bool Verdadero si este producto se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param codigo_de_barras string El Codigo de barras para este producto
 	 * @param empresas json arreglo de empresas a las que pertenece este producto
 	 * @param peso_producto float el peso de este producto en KG
 	 * @param costo_estandar float Valor del costo estndar del producto.
 	 * @param nombre_producto string Nombre del producto
 	 * @param costo_extra_almacen float Si este producto produce un costo extra por tenerlo en almacen
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param foto_del_producto string url a una foto de este producto
 	 * @param codigo_producto string Codigo del producto
 	 **/
	public static function Editar
	(
		$id_producto, 
		$descuento = null, 
		$metodo_costeo = null, 
		$descripcion_producto = null, 
		$id_unidad_no_convertible = null, 
		$impuestos = null, 
		$clasificaciones = null, 
		$id_unidad_convertible = null, 
		$margen_de_utilidad = null, 
		$garantia = null,
		$compra_en_mostrador = null, 
		$codigo_de_barras = null, 
		$empresas = null, 
		$peso_producto = null, 
		$costo_estandar = null, 
		$nombre_producto = null, 
		$costo_extra_almacen = null, 
		$control_de_existencia = null, 
		$foto_del_producto = null, 
		$codigo_producto = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crea una nueva categoria de producto, la categoria de un producto se relaciona con los meses de garantia del mismo, las unidades en las que se almacena entre, si se es suceptible a devoluciones, entre otros.
 	 *
 	 * @param nombre string Nombre de la categoria
 	 * @param descripcion string Descripcion larga de la categoria
 	 * @param garant�a int Numero de meses de garantia con los que cuenta esta categoria de producto
 	 * @param margen_utilidad float Margen de utilidad que tendran los productos de esta categoria
 	 * @param descuento float Descuento que tendran los productos de esta categoria
 	 * @param impuestos json Ids de impuestos que afectan a esta categoria de producto
 	 * @param retenciones json Ids de retenciones que afectan esta clasificacion de productos
 	 * @return id_categoria int Id atogenerado por la insercion de la categoria
 	 **/
	public static function NuevaCategoria
	(
		$nombre, 
		$descripcion = null, 
		$garantia = null,
		$margen_utilidad = null, 
		$descuento = null, 
		$impuestos = null, 
		$retenciones = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo cambia la informacion de una categoria de producto
 	 *
 	 * @param id_categoria int Id de la categoria del producto
 	 * @param nombre string Nombre de la categoria del producto
 	 * @param garantia int Numero de meses de garantia con los que cuentan los productos de esta clasificacion
 	 * @param descuento float Descuento que tendran los productos de esta categoria
 	 * @param margen_utilidad float Margen de utilidad de los productos que formen parte de esta categoria
 	 * @param descripcion string Descripcion larga de la categoria
 	 * @param impuestos json Ids de impuestos que afectan a esta clasificacion de producto
 	 * @param retenciones json Ids de retenciones que afectan a esta clasificacion de producto
 	 **/
	public static function EditarCategoria
	(
		$id_categoria, 
		$nombre, 
		$garantia = null, 
		$descuento = null, 
		$margen_utilidad = null, 
		$descripcion = null, 
		$impuestos = null, 
		$retenciones = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo desactiva una categoria de tal forma que ya no se vuelva a usar como categoria sobre un producto.
 	 *
 	 * @param id_categoria int Id de la categoria a desactivar
 	 **/
	public static function DesactivarCategoria
	(
		$id_categoria
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Elimina una equivalencia entre dos unidades.
Ejemplo: 1 kg = 2.204 lb
 	 *
 	 * @param id_unidades int En el ejemplo son las libras
 	 * @param id_unidad int En el ejemplo es el kilogramo
 	 **/
	public static function Eliminar_equivalenciaUnidad
	(
		$id_unidades, 
		$id_unidad
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista las equivalencias existentes. Se puede ordenar por sus atributos
 	 *
 	 * @param orden string Nombre de la columna de la tabla por la cual se ordenara la lista
 	 * @return unidades_equivalencia json Lista de unidades
 	 **/
	public static function Lista_equivalenciaUnidad
	(
		$orden = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo crea unidades, como son Kilogramos, Libras, Toneladas, Litros, costales, cajas, arpillas, etc.
 	 *
 	 * @param nombre string Nombre de la unidad convertible
 	 * @param descripcion string Descripcion de la unidad convertible
 	 * @return id_unidad_convertible string Id de la unidad convertible
 	 **/
	public static function NuevaUnidad
	(
		$nombre, 
		$descripcion = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo modifica la informacion de una unidad
 	 *
 	 * @param id_unidad_convertible string Id de la unidad convertible a editar
 	 * @param descripcion string Descripcion de la unidad convertible
 	 * @param nombre string Nombre de la unidad convertible
 	 **/
	public static function EditarUnidad
	(
		$id_unidad_convertible, 
		$descripcion = null, 
		$nombre = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Descativa una unidad para que no sea usada por otro metodo
 	 *
 	 * @param id_unidad_convertible int Id de la unidad convertible a eliminar
 	 **/
	public static function EliminarUnidad
	(
		$id_unidad_convertible
	)
	{  
  
  
	}
  }
