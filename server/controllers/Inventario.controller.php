<?php
require_once("interfaces/Inventario.interface.php");
/**
  *
  *
  *
  **/
	
  class InventarioController implements IInventario{
  
  
	/**
 	 *
 	 *Ver la lista de productos y sus existencias, se puede filtrar por empresa, sucursal, almac?n, y lote.
Se puede ordenar por los atributos de producto. 
 	 *
 	 * @param existencia_mayor_que float Se regresaran los productos cuya existencia sea mayor a la especificada por este valor
 	 * @param existencia_igual_que float Se regresaran los productos cuya existencia sea igual a la especificada por este valor
 	 * @param existencia_menor_que float Se regresaran los productos cuya existencia sea menor a la especificada por este valor
 	 * @param id_empresa int Id de la empresa de la cual se vern los productos.
 	 * @param id_sucursal int Id de la sucursal de la cual se vern los productos.
 	 * @param id_almacen	 int Id del almacen del cual se vern los productos.
 	 * @param activo	 bool Si es true, mostrar solo los productos que estn activos, si es false mostrar solo los productos que no lo sean.
 	 * @param id_lote int Id del lote del cual se veran los productos en existencia
 	 * @return existecias json Lista de existencias
 	 **/
	public static function Existencias
	(
		$id_empresa = null, 
		$id_sucursal = null, 
		$id_almacen	 = null, 
		$id_producto	 = null
	)
	{  
            Logger::log("Listando las existencias");
            
            //Si se recibe un id producto, solo se listan las existencias de dicho producto, se puede combinar con 
            //los demas parametros. Si no se recibe ningun otro, se realiza un acumulado de este producto en todos los almacenes.
            //
            //Si se recibe un id almacen, solo se listan las existencias de dicho almacen
            //
            //Si se recibe la variable id_empresa o id_sucursal, se listara un acumulado de todos los productos
            //con las cantidades de productos de los diferentes almacenes dentro de ella
            //
            //Cuando se recibe alguno de ellos, primero se consiguen todos los almacenes que le pertencen, despues
            //se consiguen todos los productos de cada almacen y se guardan en un arreglo temporal que despues es ordenado.
            //EL arreglo ordenado es el que se envia.
            //
            //Si no se recibe ningun parametro, se listaran todos los productos existentes en todos los almacenes
            
            $productos_almacenes = array();
            
            
            if(!is_null($id_almacen))
            {
                //Se buscan los registros de productos que cumplan con el almacan y con el producto recibidos
                $productos_almacenes = ProductoAlmacenDAO::search( new ProductoAlmacen( 
                        array( "id_almacen" => $id_almacen, "id_producto" => $id_producto ) ) );
            }
            else if(!is_null($id_empresa))
            {
                //Se obtienen todos los almacenes de la empresa
                $almacenes_empresa = AlmacenDAO::search( new Almacen( array("id_empresa" => $id_empresa) ) );
                $productos_almacenes_empresa = array();
                
                //Se recorre cada almacen y se obtiene un arreglo de sus productos, para poder agruparlos, tenemos que seacarlos
                //de su arreglo y ponerlos en un arreglo general
                foreach($almacenes_empresa as $almacen_empresa)
                {
                    //Se obtiene el arreglo de productos
                    $productos_almacen_empresa = ProductoAlmacenDAO::search( new ProductoAlmacen(
                            array( "id_almacen" => $almacen_empresa->getIdAlmacen(), "id_producto" => $id_producto ) ) );
                    
                    //Se vacía el arreglo en uno general
                    foreach($productos_almacen_empresa as $producto_almacen_empresa)
                        array_push ($productos_almacenes_empresa, $producto_almacen_empresa);
                }
                
                //Se agrupan los productos iguales
                $productos_almacenes = self::AgruparProductos($productos_almacenes_empresa);
            }
            else if(!is_null($id_sucursal))
            {
                //Se obtienen todos los almacenes de la sucursal
                $almacenes_sucursal = AlmacenDAO::search( new Almacen( array( "id_sucursal" => $id_sucursal ) ) );
                $productos_almacenes_sucursal = array();
                
                //Se recorre cada almacen y se obtiene un arreglo de sus productos, para poder agruparlos, tenemos que sacarlos
                //de su arreglo y ponerlos en un arreglo general
                foreach($almacenes_sucursal as $almacen_sucursal)
                {
                    //Se obtiene el arreglo de productos
                    $productos_almacen_sucursal = ProductoAlmacenDAO::search( new ProductoAlmacen( 
                            array( "id_almacen" => $almacen_sucursal->getIdAlmacen(), "id_producto" => $id_producto ) ) );
                    
                    //Se vacía el arreglo en uno general
                    foreach($productos_almacen_sucursal as $producto_almacen_sucursal)
                        array_push($productos_almacenes_sucursal,$producto_almacen_sucursal);
                }
                
                //Se agrupan los productos iguales
                $productos_almacenes = self::AgruparProductos($productos_almacenes_sucursal);
            }
            else
            {
                //Se obtienen todos los almacenes
                $almacenes = AlmacenDAO::getAll();
                $productos_almacen = array();
                
                //Se recorre cada almacen y se obtiene un arreglo de sus productos, para poder agruparlos, tenemos que sacarlos
                //de su arreglo y ponerlos en un arreglo general
                foreach($almacenes as $almacen)
                {
                    //Se obtiene el arreglo de productos
                    $productos_a = ProductoAlmacenDAO::search( new ProductoAlmacen( array( "id_almacen" => $almacen->getIdAlmacen(), "id_producto" => $id_producto ) ) );
                    
                    //Se vacía el arreglo en uno general
                    foreach($productos_a as $p_a)
                        array_push($productos_almacen,$p_a);
                }
                
                //Se agrupan los productos iguales
                $productos_almacenes = self::AgruparProductos($productos_almacen);
                
            }
            
            Logger::log("Se listan ".count($productos_almacenes)." registros");
            return $productos_almacenes;
            
	}
        
        //Este metodo es usado por el metodo existencias, pues cuando se listan las existencias
        //de los productos de una empresa o de una sucursal, se tienen que recorrer todos sus
        //almacenes. Cuando se recuperan estos elementos, los productos resultan divididos,
        //y se tienen que agrupar sumando sus cantidades para que al final devuelva la lista de existencias.
        private static function AgruparProductos( array $productos_almacenes )
        {
            //Se inicializa el arreglo con los productos ordenados
            $productos_almacenes_acumulado = array();
            //Se recupera el tamaño del arreglo de productos obtenido
            $tamano = count($productos_almacenes);
            
            //Se buscan los elementos repetidos en manera de burbuja. Se recorre del
            //primer al penultimo elemento y si el elemento no es nulo, se inserta en el 
            //arreglo final. 
            //
            //Despues se procede a recorrer los siguientes elmentos en busca de otro igual.
            //Si se encuentra uno igual, se suma su cantidad en el arreglo final y se borra del 
            //arreglo recibido asignandole un valor de nulo.
            for($i = 0, $k = 0; $i< $tamano-1; $i++)
            {
                //Se obtiene el producto de almacen actual
                $p_a = $productos_almacenes[$i];
                
                //Si es nulo, se prosigue a buscar en el siguiente
                if(is_null($p_a))
                    continue;
                
                //Se inserta el registro actual en el arreglo final
                array_push($productos_almacenes_acumulado,$p_a);
                
                //Se busca en los demas elementos uno igual al actual. Si es encontrado,
                //se suma su cantidad a la del elemento actual en el arreglo final y 
                //despues es borrado del arreglo original asignandole un valor nulo.
                for($j = $i+1; $j < $tamano ; $j++)
                {
                    if(is_null($productos_almacenes[$j]))
                        continue;
                    if( $p_a->getIdProducto() == $productos_almacenes[$j]->getIdProducto() && $p_a->getIdUnidad() == $productos_almacenes[$j]->getIdUnidad())
                    {
                        $productos_almacenes_acumulado[$k]->setCantidad($productos_almacenes_acumulado[$k]->getCantidad() + $productos_almacenes[$j]->getCantidad());
                        $productos_almacenes[$j]=null;
                    }
                }
                
                //Se usa la variable $k para llevar seguimiento de la posicion del elemento actual en el arreglo final,
                //pues al encontrarse un elemento nulo, $i sigue incrementando, pero $k se mantiene igual.
                $k++;
            }
            
            //El ultimo elemento no es revisado, pues ya fue comparado contra todos los demas.
            //Si el ultimo elemento no es nulo, significa que es unico y tiene que ser includio en el arreglo final.
            if(!is_null($productos_almacenes[$tamano-1]))
                array_push($productos_almacenes_acumulado,$productos_almacenes[$tamano-1]);
            
            //Se regresa el arreglo final
            return $productos_almacenes_acumulado;
        }
  
	/**
 	 *
 	 *Procesar producto no es mas que moverlo de lote.
 	 *
 	 * @param id_lote_nuevo int Id del lote al que se mover el producto
 	 * @param id_producto int Id del producto a mover
 	 * @param id_lote_viejo int Id del lote donde se encontraba el producto
 	 * @param cantidad float Si solo se movera una cierta cantidad de producto al nuevo lote. Si este valor no es obtenido, se da por hecho que se movera toda la cantidad de ese producto al nuevo lote
 	 **/
	public static function Procesar_producto
	(
		$id_almacen_nuevo, 
		$id_producto_viejo, 
		$cantidad_vieja, 
		$id_almacen_viejo,
                $id_unidad_vieja,
                $id_producto_nuevo,
                $id_unidad_nueva,
                $cantidad_nueva
	)
	{  
            Logger::log("Procesando ".$cantidad_vieja." productos con id:".$id_producto_viejo." 
                en unidad:".$id_unidad_vieja." a ".$cantidad_nueva." productos con id:".$id_producto_nuevo." en unidad:".$id_unidad_nueva);
            
            $productos_salida = array(
              array( "id_producto" => $id_producto_viejo, "id_unidad" => $id_unidad_vieja, "cantidad" => $cantidad_vieja )  
            );
            
            $productos_entrada = array(
              array( "id_producto" => $id_producto_nuevo, "id_unidad" => $id_unidad_nueva, "cantidad" => $cantidad_nueva )  
            );
            
            //Se utilizaran los metodos de entrada y salida almacen, pues estos se encargaran de todas las validaciones
            DAO::transBegin();
            try
            {
                //primero se saca del almacen el producto a transformar
                SucursalesController::SalidaAlmacen($productos_salida, $id_almacen_viejo,"Producto que sera procesado");
                //Despues se inserta el nuevo producto en el nuevo almacen
                SucursalesController::EntradaAlmacen($productos_entrada, $id_almacen_nuevo, "Producto resultado del procesado");
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido procesar todo el producto: ".$e);
                throw new Exception("No se ha podido procesar todo el producto");
            }
            DAO::transEnd();
            Logger::log("Producto procesado exitosamente");
	}
  
	/**
 	 *
 	 *ver transporte y fletes...
 	 *
 	 **/
	public static function Terminar_cargamento_de_compra
	(
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista todas las compras de una sucursal.
 	 *
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus compras
 	 * @return compras json Arreglo de objetos que tendr� las compras de la sucursal
 	 **/
	public static function Compras_sucursal
	(
		$id_sucursal
	)
	{  
            Logger::log("Listando las compras de la sucursal ".$id_sucursal);
            $compras = CompraDAO::search( new Compra( array( "id_sucursal" => $id_sucursal ) ) );
            
            Logger::log("Se listan ".count($compras)." compras");
            return $compras;
	}
  
	/**
 	 *
 	 *Lista las ventas de una sucursal.
 	 *
 	 * @param id_sucursal int Id de la sucursal de la cual listaran sus ventas
 	 * @return ventas json Objeto que conendra la informacion de las ventas de esa sucursal
 	 **/
	public static function Ventas_sucursal
	(
		$id_sucursal
	)
	{  
            Logger::log("Listando las ventas de la sucursal ".$id_sucursal);
            $ventas = VentaDAO::search( new Venta( array( "id_sucursal" => $id_sucursal ) ) );
            
            Logger::log("Se listan ".count($ventas)." ventas");
            return $ventas;
	}
  }
