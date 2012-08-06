<?php

require_once ('Estructura.php');
require_once("base/producto.dao.base.php");
require_once("base/producto.vo.base.php");
/** Page-level DocBlock .
 * 
 * @author Andres
 * @package docs
 * 
 */

/** Producto Data Access Object (DAO).
 * 
 * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
 * almacenar de forma permanente y recuperar instancias de objetos {@link Producto }. 
 * @author Andres
 * @access public
 * @package docs
 * 
 */
class ProductoDAO extends ProductoDAOBase {

    public static function buscarProductos($query, $how_many = 100) {


        /* $sql = "select * from producto where ( nombre_producto like ? or codigo_producto like ? ) and activo = 1 limit ?; ";
          $val = array( "%" . $query . "%" , "%" . $query . "%" , $how_many ); */



        $parts = explode(" ", $query);

        $sql = "select * from producto where (";
        $val = array();
        $first = true;
        foreach ($parts as $p) {
            if ($first) {
                $first = false;
            } else {
                $sql .= " and ";
            }
            $sql .= "  nombre_producto like ? ";
            array_push($val, "%" . $p . "%");
        }

        $sql .= " or codigo_producto like ? ) limit 20 ";
        array_push($val, "%" . $query . "%");

        global $conn;
        $rs = $conn->Execute($sql, $val);
        $ar = array();
        foreach ($rs as $foo) {
            $bar = new Producto($foo);
            array_push($ar, $bar);
        }
        return $ar;
    }

    public static function ExistenciasTotales($id_producto, $id_lote = null, $id_sucursal = null) {

        $total = 0;

        //calcula las existencias de todos los productos de todos los lotes y todas las sucursales
        if (is_null($id_sucursal) && is_null($id_lote)) {

            $lotes = LoteProductoDAO::search(new LoteProducto(array("id_producto" => $id_producto)));

            foreach ($lotes as $l) {
                $total += $l->getCantidad();
            }

            return $total;
        }

        //calcula las existencias de un lote en especifico
        if (is_null($id_sucursal) && !is_null($id_lote)) {

            $lotes = LoteProductoDAO::search(new LoteProducto(array("id_producto" => $id_producto, "id_lote" => $id_lote)));

            foreach ($lotes as $l) {
                $total += $l->getCantidad();
            }

            return $total;
        }

        //calcula las existencias de un producto de todos los lotes de una sucursal en especifico
        if (!is_null($id_sucursal) && is_null($id_lote)) {

            //obtenemos los lotes de una sucursal
            $almacenes = AlmacenDAO::search(new Almacen(array("id_sucursal" => $id_sucursal)));

            //iteramos los almacenes para sacar sus lotes
            foreach ($almacenes as $almacen) {

                $lotes = LoteDAO::search(new Lote(array("id_almacen" => $almacen->getIdAlacen())));

                //iteramos los lotes para conocer las existencias del producto en ese lote especifico
                foreach ($lotes as $lote) {

                    $loteProducto = LoteProductoDAO::search(new LoteProducto(array("id_producto" => $id_producto, "id_lote" => $lote->getIdLote())));

                    foreach ($loteProducto as $l) {
                        $total += $l->getCantidad();
                    }
                }
            }

            return $total;
        }

        return $total;
    }

    /**
     * Regresa la cantidad total de producto en un lote especifico
     * @param type $id_producto
     * @param type $id_lote
     * @param type $id_unidad
     * @return \stdClass 
     */
    public static function ExistenciasLote($id_producto, $id_lote, $id_unidad) {

        Logger::log("EXISTENCIAS LOTE PARA RECIBE " . ProductoDAO::getByPK($id_producto)->getNombreProducto() . ", LOTE {$id_lote}, todas las operaciones para el calculo deberan hacerse en {$id_unidad} (" . UnidadMedidaDAO::getByPK($id_unidad)->getAbreviacion() . ")");

        $error = "";
        $cantidad = 0;
        $nentradas = 0;
        $nsalidas = 0;

        //verificamos si el producto existe
        if (!$producto = ProductoDAO::getByPK($id_producto)) {
            $error .= "No se tiene registro del producto {$id_producto}. \n";
        }

        //verificamos si se envia el lote        
        if (!$lote = LoteDAO::getByPK($id_lote)) {
            $error .= "No se tiene registro del lote {$id_lote}. \n";
        }


        //obtenemos los lotes de entrada        
        $lotes_entrada = LoteEntradaDAO::search(new LoteEntrada(array(
                            "id_lote" => $id_lote
                        )));

        Logger::log("Iteramos sobre los lote entrada, se encontraron " . count($lotes_entrada) . " lotes entrada.");

        //iteramos sobre los lote de entrada
        foreach ($lotes_entrada as $lote_entrada) {

            if ($lote_entrada->getIdLote() != $id_lote) {
                continue;
            }

            $array = array(
                "id_lote_entrada" => $lote_entrada->getIdLoteEntrada(),
                "id_producto" => $id_producto
            );

            $lotes_entrada_producto = LoteEntradaProductoDAO::search(new LoteEntradaProducto($array));

            //Logger::log("--- Iteramos sobre los lote entrada producto , se encontraron " . count($lotes_entrada_producto) . " lotes entrada.");

            foreach ($lotes_entrada_producto as $lote_entrada_producto) {

                Logger::log("--- Revisando el lote entrada producto tiene como unidad " . UnidadMedidaDAO::getByPK($lote_entrada_producto->getIdUnidad())->getAbreviacion() . " se comparara contra " . UnidadMedidaDAO::getByPK($id_unidad)->getAbreviacion() . ".");

                if ($lote_entrada_producto->getIdProducto() != $id_producto) {
                    Logger::error("El search fallo!! el lote entrada producto trajo al producto {$lote_entrada_producto->getIdProducto()} y lo compara con {$id_producto}");
                    continue;
                } else {

                    //revisemos si es de la misma unidad
                    if ($lote_entrada_producto->getIdUnidad() == $id_unidad) {
                        Logger::log("Se detectaron que las unidades son iguales, el conteo se encuentra en {$cantidad}, se agregaran {$lote_entrada_producto->getCantidad()}");
                        //es igual, solo hay que sumar
                        $cantidad += $lote_entrada_producto->getCantidad();
                        $nentradas += $lote_entrada_producto->getCantidad();

                      Logger::log("Des pues de la operacion el conteo se encuentra en {$cantidad}");
                    } else {
                        //no es igual, hay que convertir

                        Logger::log("Se detecto que las unidades son diferentes, se procede a transformar {$lote_entrada_producto->getCantidad()} " . UnidadMedidaDAO::getByPK($lote_entrada_producto->getIdUnidad())->getDescripcion() . " a " . UnidadMedidaDAO::getByPK($id_unidad)->getDescripcion());
                        Logger::log("**** INFO DEL LOTE : " . $lote_entrada_producto . " ***");

                        $equivalencia = UnidadMedidaDAO::convertir($lote_entrada_producto->getIdUnidad(), $id_unidad, $lote_entrada_producto->getCantidad());

                        Logger::log("El conteo se encuentra en {$cantidad}, se agregaran {$equivalencia} " . UnidadMedidaDAO::getByPK($id_unidad)->getDescripcion());

                        $cantidad += $equivalencia;
                        $nentradas += $equivalencia;

                        Logger::log("Des pues de la operacion el conteo se encuentra en {$cantidad}");
                    }
                }
            }
        }

        //obtenemos los lotes de salida     
        $lotes_salida = LoteSalidaDAO::search(new LoteSalida(array(
                            "id_lote" => $id_lote
                        )));

        Logger::log("Iteramos sobre los lote salida, se encontraron " . count($lotes_entrada) . " lotes salida.");

        //iteramos sobre los lote de salida
        foreach ($lotes_salida as $lote_salida) {

            $array = array(
                "id_lote_salida" => $lote_salida->getIdLoteSalida(),
                "id_producto" => $id_producto
            );

            $lotes_salida_producto = LoteSalidaProductoDAO::search(new LoteSalidaProducto($array));

            //Logger::log("--- Iteramos sobre los lote salida producto , se encontraron " . count($lotes_salida_producto) . " lotes salida producto.");

            foreach ($lotes_salida_producto as $lote_salida_producto) {

                Logger::log("--- Revisando el lote salida producto tiene como unidad " . UnidadMedidaDAO::getByPK($lote_salida_producto->getIdUnidad())->getAbreviacion() . " se comparara contra " . UnidadMedidaDAO::getByPK($id_unidad)->getAbreviacion() . ".");

                if ($lote_salida_producto->getIdProducto() != $id_producto) {
                    Logger::error("El search fallo!! el lote salida producto trajo al producto {$lote_salida_producto->getIdProducto()} y lo compara con {$id_producto}");
                    continue;
                } else {

                    //revisemos si es de la misma unidad
                    if ($lote_salida_producto->getIdUnidad() == $id_unidad) {

                       Logger::log("Se detectaron que las unidades son iguales, el conteo se encuentra en {$cantidad}, se restaran {$lote_salida_producto->getCantidad()}");

                        //es igual, solo hay que restar
                        $cantidad -= $lote_salida_producto->getCantidad();
                        $nsalidas += $lote_salida_producto->getCantidad();

                        Logger::log("Des pues de la operacion el conteo se encuentra en {$cantidad}");
                    } else {

                        Logger::log("Se detecto que las unidades son diferentes, se procede a transformar {$lote_salida_producto->getCantidad()} " . UnidadMedidaDAO::getByPK($lote_salida_producto->getIdUnidad())->getDescripcion() . " a " . UnidadMedidaDAO::getByPK($id_unidad)->getDescripcion());

                        Logger::log("**** INFO DEL LOTE : " . $lote_salida_producto . " ***");

                        //no es igual, hay que convertir
                        $equivalencia = UnidadMedidaDAO::convertir($lote_salida_producto->getIdUnidad(), $id_unidad, $lote_salida_producto->getCantidad());

                        Logger::log("El conteo se encuentra en {$cantidad}, se restaran {$equivalencia} " . UnidadMedidaDAO::getByPK($id_unidad)->getDescripcion());

                        $cantidad -= $equivalencia;
                        $nsalidas += $equivalencia;

                        Logger::log("Des pues de la operacion el conteo se encuentra en {$cantidad}");
                    }
                }
            }
        }

        if ($error != "") {
            Logger::error($error);
        }

        Logger::log("########### Se encontro que para el producto " . ProductoDAO::getByPK($id_producto)->getNombreProducto() . " existen {$cantidad} " . UnidadMedidaDAO::getByPK($id_unidad)->getAbreviacion() . ". Hubo {$nentradas} entradas y {$nsalidas} salidas ###############");

        return $cantidad;
    }

    public static function buscarProductoEnSucursal($id_producto, $id_sucursal) {
        $sql = "SELECT
				lp.cantidad,
				lp.id_unidad,
				lp.id_lote,
				l.folio
			FROM 
				lote_producto lp,
				lote l,
				sucursal s,
				almacen a
			WHERE 
				lp.id_producto = ?
				and lp.id_lote = l.id_lote
				and l.id_almacen = a.id_almacen
				and a.id_sucursal = ?";

        global $conn;
        $rs = $conn->Execute($sql, array($id_producto, $id_sucursal));
        $ar = array();
        foreach ($rs as $foo) {
            array_push($ar, $foo);
        }
        return $ar;
    }

}
