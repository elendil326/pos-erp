<?php

require_once("interfaces/Inventario.interface.php");

/**
 *
 *
 *
 * */
class InventarioController implements IInventario {

    /**
     * Actualiza las cantidades de productos en la tabla de LoteProducto
     * @param type $id_lote
     * @param type $id_producto
     * @return \stdClass 
     */
    private static function ajustarLoteProducto($id_lote, $id_producto, $id_unidad) {

        //TODO : ESTE CODIGO SE PODRIA ELIMINAR SOLO LLAMANDO A LA FUNCION ProductoDAO::ExistenciasLote($id_producto, $id_lote, $id_unidad); PERO SERA HASTA QEU TODO JALE AL 100%

        Logger::log("ENTRANDO A AJUSTAR LOTE PRODUCTO");

        /*//verificamos si el producto existe
        if (!$producto = ProductoDAO::getByPK($id_producto)) {
            Logger::error("No se tiene registro del producto {$id_producto}");
        }

        //verificamos si se envia el lote        
        if (!$lote = LoteDAO::getByPK($id_lote)) {
            Logger::error("No se tiene registro del lote {$id_lote}");
        }


        //esta cantidad esta basada en la unidad indicada en los parametros
        $cantidad = 0;

        //obtenemos los lotes de entrada        
        $lotes_entrada = LoteEntradaDAO::search(new LoteEntrada(array(
                            "id_lote" => $id_lote
                        )));

        //iteramos sobre los lote de entrada
        foreach ($lotes_entrada as $lote_entrada) {

            $array = array(
                "id_lote_entrada" => $lote_entrada->getIdLoteEntrada(),
                "id_producto" => $id_producto
            );

            $lotes_entrada_producto = LoteEntradaProductoDAO::search(new LoteEntradaProducto($array));

            foreach ($lotes_entrada_producto as $lote_entrada_producto) {

                //revisemos si es de la misma unidad
                if ($lote_entrada_producto->getIdUnidad() == $id_unidad) {
                    //es igual, solo hay que sumar
                    $cantidad += $lote_entrada_producto->getCantidad();
                } else {
                    //no es igual, hay que convertir
                    $equivalencia = UnidadMedidaDAO::convertir($lote_entrada_producto->getIdUnidad(), $id_unidad, $lote_entrada_producto->getCantidad());
                    $cantidad += $equivalencia;
                }
            }
        }

        //obtenemos los lotes de salida     
        $lotes_salida = LoteSalidaDAO::search(new LoteSalida(array(
                            "id_lote" => $id_lote
                        )));

        //iteramos sobre los lote de salida
        foreach ($lotes_salida as $lote_salida) {

            $array = array(
                "id_lote_salida" => $lote_salida->getIdLoteSalida(),
                "id_producto" => $id_producto
            );

            $lotes_salida_producto = LoteSalidaProductoDAO::search(new LoteSalidaProducto($array));

            foreach ($lotes_salida_producto as $lote_salida_producto) {

                //revisemos si es de la misma unidad
                if ($lote_salida_producto->getIdUnidad() == $id_unidad) {
                    //es igual, solo hay que sumar
                    $cantidad -= $lote_salida_producto->getCantidad();
                } else {
                    //no es igual, hay que convertir
                    $equivalencia = UnidadMedidaDAO::convertir($lote_salida_producto->getIdUnidad(), $id_unidad, $lote_salida_producto->getCantidad());
                    $cantidad -= $equivalencia;
                }
            }
        }

        Logger::log("-------------->   {$cantidad}   <----------------");*/
        
        //$cantidad = ProductoDAO::ExistenciasLote($id_producto, $id_lote, $id_unidad);
        $cantidad = ProductoDAO::ExistenciasTotales($id_producto);
        
        
        Logger::log("-------------->   {$cantidad}   <----------------");

        try {

            //actualizamos la cantidad de producto en lote_producto
            $lote_producto = LoteProductoDAO::getByPK($id_lote, $id_producto);

            $lote_producto->setCantidad(UnidadMedidaDAO::convertir($id_unidad, $lote_producto->getIdUnidad(), $cantidad));

            LoteProductoDAO::save($lote_producto);                    
            
        } catch (Exception $e) {

            Logger::error("Error al hacer el ajuste del lote : " . $e->getMessage());
        }


        Logger::log("-------------->   SE TERMINO DE HACER EL AJUSTE   <----------------");
                
        return $cantidad;
        
        /* global $conn;

          $query = "";

          $rs = $conn->Execute($query, $data);

          $res = array();

          foreach ($rs as $foo) {
          array_push($res, $foo);
          }

          return $res; */
    }

    public static function ProductoProcesar($cantidad_nueva, $cantidad_vieja, $id_almacen_nuevo, $id_almacen_viejo, $id_producto_nuevo, $id_producto_viejo, $id_unidad_nueva, $id_unidad_vieja) {
        
    }

    public static function CompraDeCargamentoTerminar() {
        
    }

    /**
     *
     * Ver la lista de productos y sus existencias, se puede filtrar por empresa, sucursal, almac?n, y lote.
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
     * */
    public static function Existencias
    (
    $id_almacen = null, $id_empresa = null, $id_producto = null, $id_sucursal = null
    ) {



        $e = AlmacenDAO::Existencias();
        return array("resultados" => $e, "numero_de_resultados" => sizeof($e));



        $daoProductos = ProductoDAO::getAll();

        $aOut = array();

        $daoAlmacenes = AlmacenDAO::getAll();

        for ($iProd = 0; $iProd < sizeof($daoProductos); $iProd++) {

            //por cada almacen
            for ($iAl = 0; $iAl < sizeof($daoAlmacenes); $iAl++) {
                //buscar sus lotes
            }

            array_push($aOut, $daoProductos[$iProd]->asArray());
        }




        return array(
            "numero_de_resultados" => sizeof($aOut),
            "resultados" => $aOut
        );

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

        //Si solo se especifica un producto, se regresa un arreglo con las sucursales donde esta ese producto y 
        //la cantidad total en cada una
        if
        (
                !is_null($id_producto) &&
                is_null($id_almacen) &&
                is_null($id_empresa) &&
                is_null($id_sucursal)
        ) {
            //Se obtienen todas las sucursales y se llama recursivamente a este metodo
            $sucursales = SucursalDAO::search(new Sucursal(array("activa" => 1)));
            $result = array();
            foreach ($sucursales as $sucursal) {
                $p_a = self::Existencias(null, null, $id_producto, $sucursal->getIdSucursal());

                if ($p_a["numero_de_resultados"] > 0) {
                    foreach ($p_a["resultados"] as $p) {
                        $result["id_sucursal"] = $sucursal->getIdSucursal();

                        $suc = SucursalDAO::getByPK($sucursal->getIdSucursal());
                        $result["nombre_sucursal"] = $suc->getDescripcion();

                        $result["id_producto"] = $p->getIdProducto();
                        $result["id_unidad"] = $p->getIdUnidad();
                        $result["cantidad"] = $p->getCantidad();
                        array_push($productos_almacenes, $result);
                    }
                }
            }
        } else if (!is_null($id_almacen)) {
            //Se buscan los registros de productos que cumplan con el almacen y con el producto recibidos
            $productos_almacenes = LoteProductoDAO::search(new LoteProducto(
                                    array("id_almacen" => $id_almacen, "id_producto" => $id_producto)));
        } else if (!is_null($id_empresa)) {
            //Se obtienen todos los almacenes de la empresa
            $almacenes_empresa = AlmacenDAO::search(new Almacen(array("id_empresa" => $id_empresa)));
            $productos_almacenes_empresa = array();

            //Se recorre cada almacen y se obtiene un arreglo de sus productos, para poder agruparlos, tenemos que seacarlos
            //de su arreglo y ponerlos en un arreglo general
            foreach ($almacenes_empresa as $almacen_empresa) {
                //Se obtiene el arreglo de productos
                $productos_almacen_empresa = LoteProductoDAO::search(new LoteProducto(
                                        array("id_almacen" => $almacen_empresa->getIdAlmacen(), "id_producto" => $id_producto)));

                //Se vacía el arreglo en uno general
                foreach ($productos_almacen_empresa as $producto_almacen_empresa)
                    array_push($productos_almacenes_empresa, $producto_almacen_empresa);
            }

            //Se agrupan los productos iguales
            $productos_almacenes = self::AgruparProductos($productos_almacenes_empresa);
        } else if (!is_null($id_sucursal)) {
            //Se obtienen todos los almacenes de la sucursal
            $almacenes_sucursal = AlmacenDAO::search(new Almacen(array("id_sucursal" => $id_sucursal)));
            $productos_almacenes_sucursal = array();

            //Se recorre cada almacen y se obtiene un arreglo de sus productos, para poder agruparlos, tenemos que sacarlos
            //de su arreglo y ponerlos en un arreglo general
            foreach ($almacenes_sucursal as $almacen_sucursal) {
                //Se obtiene el arreglo de productos
                $productos_almacen_sucursal = LoteProductoDAO::search(new LoteProducto(
                                        array("id_almacen" => $almacen_sucursal->getIdAlmacen(), "id_producto" => $id_producto)));

                //Se vacía el arreglo en uno general
                foreach ($productos_almacen_sucursal as $producto_almacen_sucursal)
                    array_push($productos_almacenes_sucursal, $producto_almacen_sucursal);
            }

            //Se agrupan los productos iguales
            $productos_almacenes = self::AgruparProductos($productos_almacenes_sucursal);
        } else {
            //Se obtienen todos los almacenes
            $almacenes = AlmacenDAO::getAll();
            $productos_almacen = array();

            //Se recorre cada almacen y se obtiene un arreglo de sus productos, para poder agruparlos, tenemos que sacarlos
            //de su arreglo y ponerlos en un arreglo general
            foreach ($almacenes as $almacen) {
                //Se obtiene el arreglo de productos
                $productos_a = LoteProductoDAO::search(
                                new LoteProducto(
                                        array(
                                            "id_almacen" => $almacen->getIdAlmacen(),
                                            "id_producto" => $id_producto
                                )));

                //Se vacía el arreglo en uno general
                foreach ($productos_a as $p_a)
                    array_push($productos_almacen, $p_a);
            }

            //Se agrupan los productos iguales
            $productos_almacenes = self::AgruparProductos($productos_almacen);
        }

        Logger::log("Se listan " . count($productos_almacenes) . " registros");



        $existencias = array(
            "resultados" => $productos_almacenes,
            "numero_de_resultados" => count($productos_almacenes)
        );
        return $existencias;
    }

    //Este metodo es usado por el metodo existencias, pues cuando se listan las existencias
    //de los productos de una empresa o de una sucursal, se tienen que recorrer todos sus
    //almacenes. Cuando se recuperan estos elementos, los productos resultan divididos,
    //y se tienen que agrupar sumando sus cantidades para que al final devuelva la lista de existencias.
    private static function AgruparProductos(array $productos_almacenes) {
        //Se inicializa el arreglo con los productos ordenados
        $productos_almacenes_acumulado = array();
        //Se recupera el tamaño del arreglo de productos obtenido
        $tamano = count($productos_almacenes);

        //Si el arreglo de productos recibido esta vacio entonces no hay nada que agrupar y se regresa un arreglo vacio.
        if ($tamano == 0)
            return $productos_almacenes_acumulado;

        //Se buscan los elementos repetidos en manera de burbuja. Se recorre del
        //primer al penultimo elemento y si el elemento no es nulo, se inserta en el 
        //arreglo final. 
        //
            //Despues se procede a recorrer los siguientes elmentos en busca de otro igual.
        //Si se encuentra uno igual, se suma su cantidad en el arreglo final y se borra del 
        //arreglo recibido asignandole un valor de nulo.
        for ($i = 0, $k = 0; $i < $tamano - 1; $i++) {
            //Se obtiene el producto de almacen actual
            $p_a = $productos_almacenes[$i];

            //Si es nulo, se prosigue a buscar en el siguiente
            if (is_null($p_a))
                continue;

            //Se inserta el registro actual en el arreglo final
            array_push($productos_almacenes_acumulado, $p_a);

            //Se busca en los demas elementos uno igual al actual. Si es encontrado,
            //se suma su cantidad a la del elemento actual en el arreglo final y 
            //despues es borrado del arreglo original asignandole un valor nulo.
            for ($j = $i + 1; $j < $tamano; $j++) {
                if (is_null($productos_almacenes[$j]))
                    continue;
                if ($p_a->getIdProducto() == $productos_almacenes[$j]->getIdProducto() && $p_a->getIdUnidad() == $productos_almacenes[$j]->getIdUnidad()) {
                    $productos_almacenes_acumulado[$k]->setCantidad($productos_almacenes_acumulado[$k]->getCantidad() + $productos_almacenes[$j]->getCantidad());
                    $productos_almacenes[$j] = null;
                }
            }

            //Se usa la variable $k para llevar seguimiento de la posicion del elemento actual en el arreglo final,
            //pues al encontrarse un elemento nulo, $i sigue incrementando, pero $k se mantiene igual.
            $k++;
        }

        //El ultimo elemento no es revisado, pues ya fue comparado contra todos los demas.
        //Si el ultimo elemento no es nulo, significa que es unico y tiene que ser includio en el arreglo final.
        if (!is_null($productos_almacenes[$tamano - 1]))
            array_push($productos_almacenes_acumulado, $productos_almacenes[$tamano - 1]);

        //Se regresa el arreglo final
        return $productos_almacenes_acumulado;
    }

    /**
     *
     * Procesar producto no es mas que moverlo de lote.
     *
     * @param id_lote_nuevo int Id del lote al que se mover el producto
     * @param id_producto int Id del producto a mover
     * @param id_lote_viejo int Id del lote donde se encontraba el producto
     * @param cantidad float Si solo se movera una cierta cantidad de producto al nuevo lote. Si este valor no es obtenido, se da por hecho que se movera toda la cantidad de ese producto al nuevo lote
     * */
    public static function Procesar_producto
    (
    $cantidad_nueva, $cantidad_vieja, $id_almacen_nuevo, $id_almacen_viejo, $id_producto_nuevo, $id_producto_viejo, $id_unidad_nueva, $id_unidad_vieja
    ) {
        Logger::log("Procesando " . $cantidad_vieja . " productos con id:" . $id_producto_viejo . " 
                en unidad:" . $id_unidad_vieja . " a " . $cantidad_nueva . " productos con id:" . $id_producto_nuevo . " en unidad:" . $id_unidad_nueva);

        $productos_salida = array(
            array("id_producto" => $id_producto_viejo, "id_unidad" => $id_unidad_vieja, "cantidad" => $cantidad_vieja)
        );

        $productos_entrada = array(
            array("id_producto" => $id_producto_nuevo, "id_unidad" => $id_unidad_nueva, "cantidad" => $cantidad_nueva)
        );

        //Se utilizaran los metodos de entrada y salida almacen, pues estos se encargaran de todas las validaciones
        DAO::transBegin();
        try {
            //primero se saca del almacen el producto a transformar
            SucursalesController::SalidaAlmacen($productos_salida, $id_almacen_viejo, "Producto que sera procesado");
            //Despues se inserta el nuevo producto en el nuevo almacen
            SucursalesController::EntradaAlmacen($productos_entrada, $id_almacen_nuevo, "Producto resultado del procesado");
        } catch (Exception $e) {
            DAO::transRollback();
            Logger::error("No se ha podido procesar todo el producto: " . $e);
            if ($e->getCode() == 901)
                throw new Exception("No se ha podido procesar todo el producto: " . $e->getMessage(), 901);
            throw new Exception("No se ha podido procesar todo el producto, consulte a su administrador de sistema", 901);
        }
        DAO::transEnd();
        Logger::log("Producto procesado exitosamente");
    }

    /**
     *
     * ver transporte y fletes...
     *
     * */
    public static function Terminar_cargamento_de_compra
    (
    ) {
        
    }

    /**
     *
     * Lista todas las compras de una sucursal.
     *
     * @param id_sucursal int Id de la sucursal de la cual se listaran sus compras
     * @return compras json Arreglo de objetos que tendr� las compras de la sucursal
     * */
    public static function Compras_sucursal
    (
    $id_sucursal
    ) {
        Logger::log("Listando las compras de la sucursal " . $id_sucursal);
        $compras = CompraDAO::search(new Compra(array("id_sucursal" => $id_sucursal)));

        Logger::log("Se listan " . count($compras) . " compras");
        return $compras;
    }

    /**
     *
     * Lista las ventas de una sucursal.
     *
     * @param id_sucursal int Id de la sucursal de la cual listaran sus ventas
     * @return ventas json Objeto que conendra la informacion de las ventas de esa sucursal
     * */
    public static function Ventas_sucursal
    (
    $id_sucursal
    ) {
        Logger::log("Listando las ventas de la sucursal " . $id_sucursal);
        $ventas = VentaDAO::search(new Venta(array("id_sucursal" => $id_sucursal)));

        Logger::log("Se listan " . count($ventas) . " ventas");
        return $ventas;
    }

    /**
     *
     * Permite dar conocer al sistema las verdaderas existencias en un almacen, o sucursal.
     *
     * @param inventario json [{id_producto: 1,id_unidad: 2,cantidad: 0,id_lote : 2}]
     * @param id_sucursal int 
     * */
    static function Fisico
    (
    $inventario, $id_sucursal = ""
    ) {


        //POS.API.POST("api/inventario/fisico", {inventario : Ext.JSON.encode([{id_producto:8, id_unidad:1, cantidad:7, id_lote:1}]) } , {callback:function(){}})
        $s = SesionController::Actual();


        Logger::log("---------- INVENTARIO FISICO SE ENCONTRARON " . count($inventario) . " AJUSTES ----------");

        foreach ($inventario as $producto) {

            //self::ajustarLoteProducto($producto->id_lote, $producto->id_producto, $producto->id_unidad);

            //[{id_producto: 1,id_unidad: 2,cantidad: 0,id_lote : 2}]

            $producto->nombre = ProductoDAO::getByPK($producto->id_producto)->getNombreProducto();
            Logger::log(" Estamos en {$producto->nombre}, id_unidad {$producto->id_unidad}, {$producto->cantidad} " . UnidadMedidaDAO::getByPK($producto->id_unidad)->getAbreviacion() . ", lote {$producto->id_lote}");

            try {

                //verificamos si el lote indicado existe
                if (is_null($producto->id_lote) || strlen($producto->id_lote) == 0) {
                    throw new InvalidDataException("No selecciono a que lote ira el producto {$producto->id_producto}");
                }

                //busquemos el id del lote
                if (!$lote = LoteDAO::getByPK($producto->id_lote)) {
                    throw new InvalidDataException("No se tiene registro del lote {$producto->id_lote}");
                }

                //verificamos que exista la unidad de medida y este activa
                if (!UnidadMedidaDAO::getByPK($producto->id_unidad)) {
                    throw new InvalidDataException("La unidad de medida {$producto->id_unidad} no existe, o no esta activa.");
                }

                //busquemos si este producto ya existe en este lote
                $lote_producto = LoteProductoDAO::getByPK($lote->getIdLote(), $producto->id_producto);

                if (is_null($lote_producto)) {

                    Logger::log("El producto no estaba en el lote, se insertara");

                    /*
                      //no existe, insertar
                      $loteProducto = new LoteProducto(array(
                      "id_lote" => $lote->getIdLote(),
                      "id_producto" => $producto->id_producto,
                      "cantidad" => $producto->cantidad,
                      "id_unidad" => $producto->id_unidad
                      ));

                      LoteProductoDAO::save($loteProducto);
                      Logger::log("Se guardo el LoteProducto : id_lote {$lote->getIdLote()}, id_producto {$producto->id_producto}, cantidad {$producto->cantidad} id_unidad {$producto->id_unidad}");

                      $loteEntrada = new LoteEntrada(array(
                      "id_lote" => $lote->getIdLote(),
                      "id_usuario" => $s['id_usuario'],
                      "fecha_registro" => time(),
                      "motivo" => "Entrada por ajuste de inventario"
                      ));

                      LoteEntradaDAO::save($loteEntrada);
                      Logger::log("Se guardo el LoteEntrada: id_lote {$lote->getIdLote()}, id_usuario {$s['id_usuario']}, motivo {Entrada por ajuste de inventario}");
                     */

                    AlmacenesController::EntradaLote($lote->getIdLote(), array($producto), "Entrada por ajuste de inventario");
                } else {

                    Logger::log("El producto si estaba en el lote, verificaremos las cantidades");

                    //revisemos si es de la misma unidad
                    if ($lote_producto->getIdUnidad() == $producto->id_unidad) {
                        Logger::log("Se encontro que la unidad enviada es igual a la unidad del lote producto");

                        Logger::log("Se detecto una merma de {$producto->cantidad} " . UnidadMedidaDAO::getByPK($producto->id_unidad)->getAbreviacion() . " de {$producto->nombre}");

                        //$existencias_lote = ProductoDAO::ExistenciasLote($producto->id_producto, $lote->getIdLote(), $lote_producto->getIdUnidad());
                        $existencias_lote = ProductoDAO::ExistenciasTotales($producto->id_producto);

                        Logger::log("Se encontraron {$existencias_lote} existencias en el lote {$lote->getIdLote()} para el producto {$producto->id_producto}");
                    } else {

                        Logger::log("Se encontro que la unidad enviada es diferente a la unidad del lote producto, se procede a transformar");

                        //$existencias_lote = ProductoDAO::ExistenciasLote($producto->id_producto, $lote->getIdLote(), $lote_producto->getIdUnidad());
                        $existencias_lote = ProductoDAO::ExistenciasTotales($producto->id_producto);

                        Logger::log("Se encontraron {$existencias_lote} " . UnidadMedidaDAO::getByPK($lote_producto->getIdUnidad())->getAbreviacion() . " en el lote {$lote->getIdLote()} para el producto " . ProductoDAO::getByPK($producto->id_producto)->getNombreProducto() . " , nosotros necesitamos que se transforme en " . UnidadMedidaDAO::getByPK($producto->id_unidad)->getAbreviacion());

                        //var_dump($producto->id_unidad, $lote_producto->getIdUnidad(), $existencias_lote);                            

                        try {
                            Logger::log("Enviara a transformar unidad base : {$producto->id_unidad}, unidad a transformar : {$lote_producto->getIdUnidad()}, cantidad a transformar : {$existencias_lote}");
                            $existencias_lote = UnidadMedidaDAO::convertir($lote_producto->getIdUnidad(), $producto->id_unidad, $existencias_lote);
                            Logger::log("Como producto de la transformacion se obtuvo $existencias_lote ");
                        } catch (BusinessLogicException $ide) {
                            //no se pudo convertir porque son de 
                            //diferentes categorias
                            throw $ide; //mostrar una excpetion mas fresona
                        }
                    }

                    Logger::log("se evaluara {$existencias_lote} - {$producto->cantidad}");

                    //hacemos el ajuste
                    $diff = $existencias_lote - $producto->cantidad;


                    if ($diff > 0) {
                        //entonces hay una merma y se reporta una salida al lote igual a $diff, especificando en motivo el id del movimiento realizado
                        //se actualiza la cantidad de producto en lote producto                            
                        //AlmacenesController::Salida($l->getIdAlmacen(), $producto, "100");

                        $diff = abs($diff);

                        Logger::log("Se detecto una merma de {$diff} " . UnidadMedidaDAO::getByPK($producto->id_unidad)->getAbreviacion() . " de {$producto->nombre}");
                        /*
                          $lote_salida = new LoteSalida(array(
                          "id_lote" => $lote->getIdLote(),
                          "id_usuario" => $s['id_usuario'],
                          "fecha_registro" => time(),
                          "motivo" => "Salida de producto por ajuste de inventario (merma)"
                          ));

                          LoteSalidaDAO::save($lote_salida);

                          Logger::log("Se creo un lote salida id_lote {$lote->getIdLote()}, id_usuario {$s['id_usuario']}, motivo Salida de producto por ajuste de inventario (merma)");

                          $lote_salida_producto = new LoteSalidaProducto(array(
                          "id_lote_salida" => $lote_salida->getIdLoteSalida(),
                          "id_producto" => $producto->id_producto,
                          "id_unidad" => $producto->id_unidad,
                          "cantidad" => $producto->cantidad
                          ));

                          LoteSalidaProductoDAO::save($lote_salida_producto);

                          Logger::log("Se creo un lote salida producto con id_lote_salida {$lote_salida->getIdLoteSalida()}, id_producto {$producto->id_producto}, id_unidad {$producto->id_unidad}, cantidad {$producto->cantidad}");
                         */
                        AlmacenesController::SalidaLote($lote->getIdLote(), array(array(
                                "id_producto" => $producto->id_producto,
                                "cantidad" => $diff,
                                "id_unidad" => $producto->id_unidad
                                )), "Salida de producto por ajuste de inventario (merma)");
                    }

                    if ($diff < 0) {

                        $diff = abs($diff);

                        //entonces hay un sobrante y se reporta una entrada al lote igual a $diff, especificando en motivo el id del movimiento realizado
                        //se actualiza la cantidad de producto en lote producto
                        //AlmacenesController::Entrada($l->getIdAlmacen(), $producto, "101");


                        Logger::log("Se detecto una sobrante de {$diff} " . UnidadMedidaDAO::getByPK($producto->id_unidad)->getAbreviacion());
                        /*
                          $lote_entrada = new LoteEntrada(array(
                          "id_lote" => $lote->getIdLote(),
                          "id_usuario" => $s['id_usuario'],
                          "fecha_registro" => time(),
                          "motivo" => "Entrada de producto por ajuste de inventario (sobrante)"
                          ));

                          LoteEntradaDAO::save($lote_entrada);

                          Logger::log("Se creo un lote entrada id_lote {$lote->getIdLote()}, id_usuario {$s['id_usuario']}, motivo Entrada de producto por ajuste de inventario (sobrante)");

                          $lote_entrada_producto = new LoteEntradaProducto(array(
                          "id_lote_entrada" => $lote_entrada->getIdLote(),
                          "id_producto" => $producto->id_producto,
                          "id_unidad" => $producto->id_unidad,
                          "cantidad" => $producto->cantidad
                          ));

                          LoteEntradaProductoDAO::save($lote_entrada_producto);

                          Logger::log("Se creo un lote entrada producto con id_lote_entrada {$lote_entrada->getIdLoteEntrada()}, id_producto {$producto->id_producto}, id_unidad {$producto->id_unidad}, cantidad {$producto->cantidad}");
                         */

                        //AlmacenesController::EntradaLote($lote->getIdLote(), array($producto), "Entrada de producto por ajuste de inventario (sobrante)");
                        AlmacenesController::EntradaLote($lote->getIdLote(), array(array(
                                "id_producto" => $producto->id_producto,
                                "cantidad" => $diff,
                                "id_unidad" => $producto->id_unidad
                                )), "Entrada de producto por ajuste de inventario (sobrante)");
                    }

                    //TODO:HAY QUE AHCER PRUEBAS EXHAUSTIVAS PARA VER SI ESTE ULTIMO BLOQUE DE CODIGO SE DEBERIA DE ELIMINAR
                    //actualizamos las existencias de lote producto
                    if ($diff != 0) {
                        Logger::log("Se procede a hacer el ajuste del lote producto");
                        self::ajustarLoteProducto($producto->id_lote, $producto->id_producto, $producto->id_unidad);
                    } else {
                        Logger::log("Se detecto que la cantidad en tote producto concuerda con la cantidad inventariada, pero aun asi se llamara al metodo de ajuste de prodcuto");
                        self::ajustarLoteProducto($producto->id_lote, $producto->id_producto, $producto->id_unidad);
                    }
                }
            } catch (InvalidDataException $e) {
                Logger::error($e);
                DAO::transRollback();
                throw $e;
            } catch (exception $e) {
                Logger::error($e);
                DAO::transRollback();
                throw new InvalidDatabaseOperationException($e);
            }
        }
    }

    /**
     *
     * @param type $productos
     * @param type $id_sucursal 
     */
    static function ExistenciasRecalcular($productos, $id_sucursal = "") {
        
        $response = array();
        
        foreach ($productos as $producto) {
            
            $producto->cantidad = self::ajustarLoteProducto($producto->id_lote, $producto->id_producto, $producto->id_unidad);
            
        }
        
        return array( "productos" => json_encode($productos));
        
        //printf("{success:true, productos:" . json_encode($productos) . "}");
        
    }

}
