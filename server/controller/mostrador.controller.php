<?php

/**
 *  Controller para mostrador
 */
require_once("model/ventas.dao.php");
require_once("model/cliente.dao.php");
require_once("model/detalle_venta.dao.php");
require_once("model/detalle_inventario.dao.php");
require_once("model/factura_venta.dao.php");
require_once("model/usuario.dao.php");
require_once("model/inventario_maestro.dao.php");
require_once("model/compra_proveedor.dao.php");
require_once("logger.php");
require_once('autorizaciones.controller.php');
require_once('clientes.controller.php');

/*
 * Crea una factura para este objeto venta
 * @param Venta
 * @returns verdadero si tuve exito, falso de lo contrario
 * */

function insertarFacturaVenta($venta) {
    //buscar que no exista antes
    $fv = new FacturaVenta();
    $fv->setIdVenta($venta->getIdVenta());

    $res = FacturaVentaDAO::search($fv);

    if (count($res) != 0) {
        return false;
    }

    try {
        FacturaVentaDAO::save($fv);
    } catch (Exception $e) {
        Logger::log("Error al salvar la factura a la venta");
    }
}

/*
 * @param DetalleVenta [] 
 * @returns verdadero si existen suficientes productos para satisfacer la demanda en esta sucursal para el arreglo de DetalleVenta, falso si no
 * */

function revisarExistenciasSucursal($productos) {

    foreach ($productos as $p) {

        //verificamos que exista el producto en el detalle inventario
        if (!( $i = DetalleInventarioDAO::getByPK($p->id_producto, $_SESSION['sucursal']) )) {
            Logger::log("No se tiene registro del producto " . $p->id_producto . " en esta sucursal.");
            DAO::transRollback();
            die('{"success": false, "reason": "No se tiene registro del producto ' . $p->id_producto . ' en esta sucursal.." }');
        }

        //Logger::log("Se busca el producto : {$p->id_producto}");

        if ($p->procesado == "true") {
            //requiere producto procesado
            if ($p->cantidad_procesada > $i->getExistenciasProcesadas()) {
                return false;
            }
        } else {
            //requiere producto sin procesar
            if ($p->cantidad > $i->getExistencias()) {
                return false;
            }
        }
    }

    return true;
}

/*
 * @param DetalleVenta [] 
 * @returns verdadero si existen suficientes productos para satisfacer la demanda en el inventario maestro
 * */

function revisarExistenciasAdmin($productos) {

    foreach ($productos as $p) {

        //verificamos que exista la compra al proveedor
        if (!( CompraProveedorDAO::getByPK($p->id_compra_proveedor) )) {
            Logger::log("No se tiene registro de la compra " . $p->id_compra_proveedor . " al proveedor.");
            DAO::transRollback();
            die('{"success": false, "reason": "No se tiene registro de la compra ' . $p->id_compra_proveedor . ' al proveedor." }');
        }

        //verificamos que en la comrpa se haya comprado el producto
        if (!( $i = InventarioMaestroDAO::getByPK($p->id_producto, $p->id_compra_proveedor) )) {
            Logger::log("No se tiene registro de la compra del producto " . $p->id_producto . " en la compra " . $p->id_compra_proveedor);
            DAO::transRollback();
            die('{"success": false, "reason": "No se tiene registro de la compra del producto ' . $p->id_producto . ' en la compra ' . $p->id_compra_proveedor . '." }');
        }

        if ($p->procesada == true) {
            //requiere producto procesado
            if (($p->cantidad + $p->descuento) > $i->getExistenciasProcesadas()) {
                return false;
            }
        } else {
            //requiere producto sin procesar
            if ($p->cantidad > $i->getExistencias()) {
                return false;
            }
        }
    }

    return true;
}

/*
 * Descuenta del inventario los productos dados en el arreglo detalle venta, y tambien inserta esos detalles venta en la base de datos
 * @param DetalleVenta[] 
 * @return verdadero si tuvo exito, falso si no fue asi
 * 
 * */

function descontarInventario($productos) {

    foreach ($productos as $dVenta) {


        //insertar el detalle de la venta
        try {
            if (!DetalleVentaDAO::save($dVenta)) {
                return false;
            }
        } catch (Exception $e) {
            die('{"success": false, "reason": "Error al guardar el detalle venta." }');
        }



        //descontar del inventario
        $dInventario = DetalleInventarioDAO::getByPK($dVenta->getIdProducto(), $_SESSION['sucursal']);

        $dInventario->setExistencias($dInventario->getExistencias() - $dVenta->getCantidad());

        $dInventario->setExitenciasProcesadas($dInventario->getExitenciasProcesadas() - $dVenta->getCantidadProcesada());

        try {
            DetalleInventarioDAO::save($dInventario);
        } catch (Exception $e) {
            return false;
        }
    }

    return true;
}

/**
 * Venta desde la sucursal.
 *
 * Realiza una venta desde la sucursal, descontando
 * los productos a vender del inventario de la sucursal.
 *
 *
 * Formato de json.
 * <code>
 *        {
 *             "id_cliente": int | null,
 *             "tipo_venta": "contado" | "credito",
 *             "tipo_pago": "tarjeta" | "cheque" | "efectivo",
 *             "factura": false | true,
 *             "items": [
 *                 {
 *                     "id_producto": int,
 *                     "procesado": true | false,
 *                     "precio":float,
 *                     "cantidad": float,
 *                      "descuento": float
 *                 }
 *             ]
 *        }
 * </code>
 *
 * {"id_cliente": 1,"tipo_venta": "contado","tipo_pago":"efectivo","factura": false,"items": [{"id_producto": 1,"procesado": true,"precio": 5.5,"cantidad": 5}]}
 * */
function vender($args) {

    Logger::log("Iniciando proceso de venta (sucursal)");

    if (!isset($args['payload'])) {
        Logger::log("Sin parametros para realizar venta (sucursal)");
        die('{"success": false, "reason": "No hay parametros para realizar la venta." }');
    }

    try {
        $data = parseJSON($args['payload']);
    } catch (Exception $e) {
        Logger::log("json invalido para realizar venta : " . $e);
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    if ($data == null) {
        Logger::log("el parseo del json de la venta resulto en un objeto nulo");
        die('{"success": false, "reason": "Parametros invalidos. El objeto es nulo." }');
    }

    //verificamos que se manden todos los parametros necesarios

    if (!( isset($data->tipo_venta) && isset($data->factura) && isset($data->items) )) {
        Logger::log("Falta uno o mas parametros");
        die('{"success": false, "reason": "Verifique sus datos, falta uno o mas parametros." }');
    }

    //verificar que $data->items  sea un array
    if (!is_array($data->items)) {
        Logger::log("data -> items no es un array de productos");
        die('{"success": false, "reason": "No se genero correctamente las especificaciones de los productos a vender." }');
    }

    //verificamos que $data->items almenos tenga un producto
    if (count($data->items) <= 0) {
        Logger::log("data -> items no contiene ningun producto");
        die('{"success": false, "reason": "No se especifico ningun producto para generar una nueva venta." }');
    }

    //verificamos que cada objeto de producto tenga los parametros necesarios
    foreach ($data->items as $item) {

        if (!( isset($item->id_producto) && isset($item->procesado) && isset($item->precio) && isset($item->cantidad) )) {
            Logger::log("Uno o mas objetos de data -> items no tiene el formato correcto");
            die('{"success": false, "reason": "No se genero correctamente la descripcion de uno o mas productos." }');
        }
    }


    /*
     * A las cantidades de producto totales que se van vender le restamos el descuento de los productos.
     * Ejemplo : Si se venden 100 articulos pero se indica qeu se descuenten 7, entonces la cantidad total
     * de articulos a vender es 93.
     */
    for ($i = 0; $i < count($data->items); $i++) {
        $data->items[$i]->cantidad -= $data->items[$i]->descuento;
    }


    /*
     * Condensamos los productos
     * iteramos el array de productos enviado ($data -> items)  e insertandolos en otro array, este nuevo array contiene objetos
     * de tipo standard se especifican la cantidad de producto procesado y sin procesar, antes de insertar un nuevo producto de
     * $data -> items antes revisamos que no haya un producto con el mismo id, y de haberlo sumamos las cantidades del producto
     * para que solo haya un mismo producto a la ves y asi pudiendo consegir un solo registro de un mismo producto con cantidaddes
     * de producto procesado o sin procesar.
     */

    $array_items = array();
    //insertamos el primer producto de item

    $item = new stdClass();
    $item->id_producto = $data->items[0]->id_producto;
    $item->procesado = $data->items[0]->procesado;

    if ($data->items[0]->cantidad <= 0) {
        Logger::log("La cantidad de los productos debe ser mayor que cero.");
        die('{"success": false, "reason": "La cantidad de los productos debe ser mayor que cero." }');
    }

    if ($data->items[0]->precio <= 0) {
        Logger::log("El precio de los productos debe ser mayor que cero.");
        die('{"success": false, "reason": "El precio de los productos debe ser mayor que cero." }');
    }

    if ($data->items[0]->procesado == "true") {

        $item->cantidad_procesada = $data->items[0]->cantidad;
        $item->precio_procesada = $data->items[0]->precio;
        $item->cantidad = 0;
        $item->precio = 0;
        $item->descuento = 0;
    } else {
        $item->cantidad_procesada = 0;
        $item->precio_procesada = 0;
        $item->cantidad = $data->items[0]->cantidad;
        $item->precio = $data->items[0]->precio;
        $item->descuento = $data->items[0]->descuento;
    }

    //insertamos el primer producto
    array_push($array_items, $item);



    //recorremos a $data->items para condensar el array de productos
    for ($i = 1; $i < count($data->items); $i++) {

        if ($data->items[$i]->cantidad <= 0) {
            Logger::log("La cantidad de los productos debe ser mayor que cero.");
            die('{"success": false, "reason": "La cantidad de los productos debe ser mayor que cero." }');
        }

        if ($data->items[$i]->precio <= 0) {
            Logger::log("El precio de los productos debe ser mayor que cero.");
            die('{"success": false, "reason": "El precio de los productos debe ser mayor que cero." }');
        }

        //bandera para verificar si el producto no esta dentro de array_items
        $found = false;

        //iteramos el array_items y lo comparamos cada elementod e el con el producto actual
        foreach ($array_items as $item) {

            //comparamos haber si ya existe el producto en array_items
            if ($data->items[$i]->id_producto == $item->id_producto) {

                $found = true;

                //(el producto se encontro) y es un producto procesado
                if ($data->items[$i]->procesado == "true") {
                    $item->cantidad_procesada += $data->items[$i]->cantidad;

                    if ($item->precio_procesada != 0 && $item->precio_procesada != $data->items[$i]->precio) {
                        Logger::log("Selecciono dos productos iguales, pero con diferente precio.");
                        die('{"success": false, "reason": "Selecciono dos o mas productos ' . $item->id_producto . ' - PROCESADO, pero con diferente precio." }');
                    }

                    $item->precio_procesada = $data->items[$i]->precio;
                } else {
                    //(el producto se encontro) y es un producto original
                    $item->cantidad += $data->items[$i]->cantidad;

                    if ($item->precio != 0 && $item->precio != $data->items[$i]->precio) {
                        Logger::log("Selecciono dos productos iguales, pero con diferente precio.");
                        die('{"success": false, "reason": "Selecciono dos o mas productos ' . $item->id_producto . ' - ORIGINAL, pero con diferente precio." }');
                    }

                    $item->precio = $data->items[$i]->precio;

                    if ($item->descuento != 0 && $item->descuento != $data->items[$i]->descuento) {
                        Logger::log("Selecciono dos productos iguales, pero con diferente descuento.");
                        die('{"success": false, "reason": "Selecciono dos o mas productos ' . $item->id_producto . ' - PROCESADO, pero con diferente descuento." }');
                    }

                    $item->descuento = $data->items[$i]->descuento;
                }
            }
        }//for each del array_items

        if (!$found) {

            //si no se encuentra el producto en el arreglo de objetos hay que crearlo
            $_item = new stdClass();
            //$_item -> id_compra_proveedor = $data->items[$i] -> id_compra_proveedor;
            $_item->id_producto = $data->items[$i]->id_producto;
            $_item->procesado = $data->items[$i]->procesado;

            if ($data->items[$i]->procesado == "true") {
                $_item->cantidad_procesada = $data->items[$i]->cantidad;
                $_item->precio_procesada = $data->items[$i]->precio;
                $_item->cantidad = 0;
                $_item->precio = 0;
                $_item->descuento = 0;
            } else {
                $_item->cantidad_procesada = 0;
                $_item->precio_procesada = 0;
                $_item->cantidad = $data->items[$i]->cantidad;
                $_item->precio = $data->items[$i]->precio;
                $_item->descuento = $data->items[$i]->descuento;
            }
            array_push($array_items, $_item);
        }
    }//for de $data->items
    //revisamos si las existencias en el inventario de la sucursal para ver si satisfacen a las requeridas en la venta
    if (!revisarExistenciasSucursal($array_items)) {
        Logger::log("No hay existencias suficientes en el inventario de la suursal para satisfacer la demanda");
        die('{"success": false, "reason": "No hay existencias suficientes en el inventario de la suursal para satisfacer la demanda. Intente de nuevo." }');
    }


    //inicializamos un objeto venta
    $venta = new Ventas();
    $venta->setIdUsuario($_SESSION['userid']);
    $venta->setTotal(0);
    $venta->setIdSucursal($_SESSION['sucursal']);
    $venta->setIp($_SERVER['REMOTE_ADDR']);
    $venta->setPagado(0);
    $venta->setLiquidada(0);
    $venta->setDescuento(0);
    $venta->setCancelada(0);

    if (!isset($data->cliente)) {

        $venta->setIdCliente($_SESSION['sucursal'] * -1);
        $venta->setTipoVenta("contado");
        $venta->setDescuento(0);
        $descuento = 0;
    } else {

        //verificamos que el cliente exista
        if (!( $cliente = ClienteDAO::getByPK($data->cliente->id_cliente) )) {
            Logger::log("No se tiene registro del cliente : " . $data->id_cliente);
            die('{"success": false, "reason": "Parametros invalidos 5." }');
        }

        $venta->setIdCliente($data->cliente->id_cliente);

        //verificamos que el tipo de venta sea valido
        switch ($data->tipo_venta) {

            case 'credito':
                $venta->setTipoVenta($data->tipo_venta);
                break;

            case 'contado':
                $venta->setTipoVenta($data->tipo_venta);
                break;

            default:
                Logger::log("El tipo de venta no es valido : " . $data->tipo_venta);
                die('{"success": false, "reason": "El tipo de venta no es valido ' . $data->tipo_venta . '." }');
        }

        $descuento = $cliente->getDescuento();

        //TODO : Creo que esto tiene que desaparecer, aqui no va
        /* if ($data->factura) {
          insertarFacturaVenta($venta);
          } */
    }

    if (isset($data->tipo_pago) && $venta->getTipoVenta() == "contado") {
        //verificamos que el tipo de pago sea valido
        switch ($data->tipo_pago) {

            case 'efectivo':
                $venta->setTipoPago($data->tipo_pago);
                break;

            case 'cheque':
                $venta->setTipoPago($data->tipo_pago);
                break;

            case 'tarjeta':
                $venta->setTipoPago($data->tipo_pago);
                break;

            default:
                Logger::log("El tipo de pago no es valido : " . $data->tipo_pago);
                die('{"success": false, "reason": "El tipo de pago no es valido : ' . $data->tipo_pago . '." }');
        }
    }

    DAO::transBegin();

    try {
        VentasDAO::save($venta);
        $id_venta = $venta->getIdVenta();

        //si se requiere factura hay que crearla
        //TODO : Desarrollar una funcion que cree la factura con el api
        /* if ($data->factura && isset($data->cliente)) {
          insertarFacturaVenta($venta);
          } */
    } catch (Exception $e) {
        Logger::log($e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudo registrar la venta." }');
    }


    //insertar detalles de la venta   
    foreach ($array_items as $producto) {
        $detalle_venta = new DetalleVenta();
        $detalle_venta->setIdVenta($id_venta);
        $detalle_venta->setIdProducto($producto->id_producto);
        $detalle_venta->setCantidad($producto->cantidad);
        $detalle_venta->setPrecio($producto->precio);
        $detalle_venta->setCantidadProcesada($producto->cantidad_procesada);
        $detalle_venta->setPrecioProcesada($producto->precio_procesada);
        $detalle_venta->setDescuento($producto->descuento);

        try {
            DetalleVentaDAO::save($detalle_venta);
        } catch (Exception $e) {
            DAO::transRollback();
            Logger::log($e);
            die('{"success": false, "reason": "Error, al guardar el detalle venta." }');
        }
    }

    //descontamos del inventario el pedido
    $subtotal = 0;

    foreach ($array_items as $producto) {

        $detalle_inventario = DetalleInventarioDAO::getByPK($producto->id_producto, $_SESSION['sucursal']);

        $detalle_inventario->setExistenciasProcesadas($detalle_inventario->getExistenciasProcesadas() - $producto->cantidad_procesada);
        $detalle_inventario->setExistencias($detalle_inventario->getExistencias() - $producto->cantidad);

        $subtotal += ( ( $producto->cantidad_procesada * $producto->precio_procesada ) + ( $producto->cantidad * $producto->precio ) );

        try {
            DetalleInventarioDAO::save($detalle_inventario);
        } catch (Exception $e) {
            DAO::transRollback();
            Logger::log("Error, al descontar el pedido de productos del inventario de la sucursal, exception : " . $e);
            die('{"success": false, "reason": "Error, al descontar el pedido de productos del inventario de la sucursal" }');
        }
    }

    //ya que se tiene el total de la venta se actualiza el total de la venta
    $venta->setSubtotal($subtotal);
    $total = ( $subtotal - ( ( $subtotal * $descuento ) / 100 ) );
    $venta->setTotal($total);

    //si la venta es de contado, hay que liquidarla
    if ($venta->getTipoVenta() == "contado") {
        $venta->setPagado($total);
        $venta->setLiquidada(1);
    }

    try {
        if (VentasDAO::save($venta)) {
            $empleado = UsuarioDAO::getByPK($venta->getIdUsuario());

            printf('{"success": true, "id_venta":%s, "empleado":"%s"}', $venta->getIdVenta(), $empleado->getNombre());
        } else {
            DAO::transRollback();
            die('{"success": false, "reason": "No se pudo actualizar el total de la venta" }');
        }
    } catch (Exception $e) {
        DAO::transRollback();
        Logger::log($e);
        die('{"success": false, "reason": "Intente de nuevo." }');
    }

    //verificamos si la venta se hiso a una sucursal, si es asi entonces se poner en transito el producto    
    if (isset($data->cliente) && $data->cliente->id_cliente < 0 && $venta->getTipoVenta() != "contado" && $data->cliente->id_cliente != ( $_SESSION['sucursal'] * -1 )) {
        $en_transito = array(
            'id_sucursal' => $data->cliente->id_cliente * -1,
            'data' => json_encode($array_items),
            'venta_intersucursal' => true
        );

        responderAutorizacionSolicitudProductos($en_transito);
    }


    DAO::transEnd();

    Logger::log("Proveso de venta (sucursal), termino con exito!! id_venta : {$id_venta}.");
}

//vender

/**
 *
 *
 * VENDER DESDE EL ADMIN VERSION 2
 *
 *
 * Venta desde el centro de distribucion.
 *
 * Realiza una venta desde el centro de distribucion, descontando
 * los productos a vender de las remisiones.
 *
 *
 * Formato de json.
 * <code>
 *        {
 *             "cliente": int,
 *             "tipo_venta": "contado" | "credito",
 *             "tipo_pago": "tarjeta" | "cheque" | "efectivo",
 *             "factura": false | true,
 *             "cliente": int,
 *             "efectivo" : float,
 *             "productos":[
 *                 "producto": int,
 *                 "procesado": false,
 *                 "items": [
 *                     {
 *                         "id_producto": int,
 *                         "id_compra": int,
 *                         "procesada": true | false,
 *                         "precio":float,
 *                         "cantidad": float,
 *                         "descuento": float
 *                     }
 *                 ]
 *             ]
 *        }
 * </code>
 *
 * {"id_cliente": 1,"tipo_venta": "contado","tipo_pago":"efectivo","factura": false,"items": [{"items":[{"id_compra":10,"id_producto":1,"cantidad":20,"desc":"papas primeras","procesada":false,"escala":"kilogramo","precio":"5","descuento":0},{"id_compra":6,"id_producto":2,"cantidad":12,"desc":"papa segunda","procesada":false,"escala":"kilogramo","precio":"4","descuento":0}],"producto":1,"procesado":false},{"items":[{"id_compra":5,"id_producto":1,"cantidad":52,"desc":"papas primeras","procesada":false,"escala":"kilogramo","precio":"12","descuento":0},{"id_compra":4,"id_producto":1,"cantidad":3,"desc":"papas primeras","procesada":false,"escala":"kilogramo","precio":"12","descuento":0},{"id_compra":6,"id_producto":2,"cantidad":5,"desc":"papa segunda","procesada":false,"escala":"kilogramo","precio":"4","descuento":0}],"producto":2,"procesado":false}]}
 * */
function venderAdmin($args) {

    Logger::log("Iniciando proceso de venta (admin)");

    if (!isset($args['data'])) {
        Logger::log("Sin parametros para realizar venta (admin)");
        die('{"success": false, "reason": "No hay parametros para realizar la venta." }');
    }

    try {
        $data = parseJSON($args['data']);
    } catch (Exception $e) {
        Logger::log("json invalido para realizar venta : " . $e);
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    if ($data == null) {
        Logger::log("el parseo del json de la venta resulto en un objeto nulo");
        die('{"success": false, "reason": "Parametros invalidos. El objeto es nulo." }');
    }

    //verificamos que se manden todos los parametros necesarios

    if (!( isset($data->cliente) && isset($data->tipo_venta) && isset($data->productos) )) {
        Logger::log("Falta uno o mas parametros");
        die('{"success": false, "reason": "Verifique sus datos, falta uno o mas parametros." }');
    }

    //verificar que $data->items  sea un array
    if (!is_array($data->productos)) {
        Logger::log("data -> items no es un array de productos");
        die('{"success": false, "reason": "No se generaron correctamente las descripciones de los productos para la venta." }');
    }

    //verificamos que $data->items almenos tenga un producto
    if (count($data->productos) <= 0) {
        Logger::log("data -> items no contiene ningun producto");
        die('{"success": false, "reason": "No se envio ningun producto para generar una nueva venta." }');
    }

    //verificamos que el cliente exista
    if (!( $cliente = ClienteDAO::getByPK($data->cliente) )) {
        Logger::log("No se tiene registro del cliente : " . $data->cliente);
        die('{"success": false, "reason": "No se tiene registro del cliente ' . $data->cliente . '." }');
    }

    //verificamos que cada objeto de producto tenga los parametros necesarios

    foreach ($data->productos as $items) {

        //verificar que $items  sea un array
        if (!is_array($items->items)) {
            Logger::log("No se genero correctamente las especificaciones de los productos a vender.");
            die('{"success": false, "reason": "No se genero correctamente las especificaciones de los productos a vender." }');
        }

        //verificamos que $data->items almenos tenga un producto
        if (count($items->items) <= 0) {
            Logger::log("items -> items no contiene ningun producto");
            die('{"success": false, "reason": "Error al formar el producto para la venta, no se especifico ningun producto para generar una nueva venta." }');
        }

        if (!(isset($items->procesado))) {
            Logger::log("En uno o mas productos no se ha especificado si se vendera procesado o sin procesar.");
            die('{"success": false, "reason": "En uno o mas productos no se ha especificado si se vendera procesado o sin procesar." }');
        }

        if (!(isset($items->producto))) {
            Logger::log("En uno o mas productos no se ha especificado cual es el producto que se desea vender.");
            die('{"success": false, "reason": "En uno o mas productos no se ha especificado cual es el producto que se desea vender." }');
        }

        //verificamos que cada objeto de producto tenga los parametros necesarios
        foreach ($items->items as $item) {
            if (!( isset($item->id_compra) && isset($item->id_producto) && isset($item->procesada) && isset($item->precio) && isset($item->cantidad) && isset($item->descuento) )) {
                Logger::log("Uno o mas objetos de items -> items no tiene el formato correcto");
                die('{"success": false, "reason": "No se genero correctamente la descripcion de uno o mas productos." }');
            }
        }
    }

    /*
     * Condensamos los productos (solo para verificar las existencias)
     * iteramos el array de productos enviado ($data->productos)  e insertandolos en otro array, este nuevo array contiene objetos
     * de tipo standard se especifican la cantidad de producto procesado y sin procesar, antes de insertar un nuevo producto de
     * $data -> productos -> items antes revisamos que no haya un producto con el mismo id_producto y id_compra , y de haberlo sumamos las cantidades del producto
     * para que solo haya un mismo producto a la ves y asi pudiendo consegir un solo registro de un mismo producto con cantidaddes
     * de producto procesado o sin procesar.
     */

    /**
     * Arreglo que contendra una version condensada de todos los productos que se van a solicitar
     */
    $array_items = array();

    /**
     * Arreglo que contendra los articulos que se armaron para vender
     */
    $array_items_venta = array();


    foreach ($data->productos as $items) {

        //Iniciamos la condenzacion de los productos

        $producto_armado = new stdClass();
        $producto_armado->id_producto = $items->producto;
        $producto_armado->procesado = $items->procesado;
        $producto_armado->cantidad = 0;
        $producto_armado->precio = 0;
        $producto_armado->descuento = 0;


        $suma_precios = 0;
        $cont_articulos = 0;
        $precio_promedio = 0;
        $cont_descuento = 0;
        $sum_total_articulos = 0;



        //var_dump($itm);
        //insertamos el primer producto de item

        $item = new stdClass();
        $item->id_compra_proveedor = $items->items[0]->id_compra;
        $item->id_producto = $items->items[0]->id_producto;
        $item->procesada = $items->items[0]->procesada;
        $item->descuento = $items->items[0]->descuento;

        //$suma_precios += $items->items[0]->precio;//<------------antes del cambio
        $suma_precios += $items->items[0]->precio * $items->items[0]->cantidad;


        if ($items->items[0]->procesada == true) {

            $sum_total_articulos += $items->items[0]->cantidad;
            $cont_articulos++;

            $item->cantidad_procesada = $items->items[0]->cantidad;
            $item->precio_procesada = $items->items[0]->precio;
            $item->cantidad = 0;
            $item->precio = 0;
        } else {

            $cont_descuento += $items->items[0]->descuento;

            $sum_total_articulos += ( $items->items[0]->cantidad - $items->items[0]->descuento);
            $cont_articulos++;

            $item->cantidad_procesada = 0;
            $item->precio_procesada = 0;
            $item->cantidad = $items->items[0]->cantidad - $items->items[0]->descuento;
            $item->precio = $items->items[0]->precio;
        }

        /* $producto->cantidad_producto_vendido += $item->cantidad;
          $producto->cantidad_producto_descontado += $item->descuento; */

        //insertamos el primer producto
        array_push($array_items, $item);

        //recorremos a $data->items para condensar el array de productos
        for ($i = 1; $i < count($items->items); $i++) {


            $found = false;

            //iteramos el $obj_items

            foreach ($array_items as $item) {

                if ($items->items[$i]->id_producto == $item->id_producto && $item->id_compra_proveedor == $items->items[$i]->id_compra) {

                    $found = true;

                    //$suma_precios += $items->items[$i]->precio; //<----- antes del cambio
                    $suma_precios += $items->items[$i]->precio * $items->items[$i]->cantidad;

                    //si se encuentra ese producto en el arreglo de objetos
                    if ($items->items[$i]->procesada == true) {

                        $sum_total_articulos += $items->items[$i]->cantidad;
                        $cont_articulos++;

                        $item->cantidad_procesada += $items->items[$i]->cantidad;
                        //TODO:ESTO ESTA BIEN? parece que si
                        $item->precio_procesada = $items->items[$i]->precio;
                    } else {

                        $cont_descuento += $items->items[$i]->descuento;

                        $sum_total_articulos += ( $items->items[$i]->cantidad - $items->items[$i]->descuento);
                        $cont_articulos++;

                        $item->cantidad += $items->items[$i]->cantidad - $items->items[$i]->descuento;
                        //TODO:ESTO ESTA BIEN? parece que si
                        $item->precio = $items->items[$i]->precio;
                    }
                }
            }

            if (!$found) {

                //si no se encuentra el producto en el arreglo de objetos hay que crearlo
                $_item = new stdClass();
                $_item->id_compra_proveedor = $items->items[$i]->id_compra;
                $_item->id_producto = $items->items[$i]->id_producto;
                $_item->descuento = $items->items[$i]->descuento;
                $_item->procesada = $items->items[$i]->procesada;

                //$suma_precios += $items->items[$i]->precio; //<------------ antes del cambio
                $suma_precios += $items->items[$i]->precio * $items->items[$i]->cantidad;

                if ($items->items[$i]->procesada == true) {

                    $sum_total_articulos += $items->items[$i]->cantidad;
                    $cont_articulos++;

                    $_item->cantidad_procesada = $items->items[$i]->cantidad;
                    $_item->precio_procesada = $items->items[$i]->precio;
                    $_item->cantidad = 0;
                    $_item->precio = 0;
                } else {

                    $cont_descuento += $items->items[$i]->descuento;

                    $sum_total_articulos += ( $items->items[$i]->cantidad - $items->items[$i]->descuento);
                    $cont_articulos++;

                    $_item->cantidad_procesada = 0;
                    $_item->precio_procesada = 0;
                    $_item->cantidad = $items->items[$i]->cantidad;
                    $_item->precio = $items->items[$i]->precio;
                }

                /* $producto->cantidad_producto_vendido += $_item->cantidad;
                  $producto->cantidad_producto_descontado += $_item->descuento; */

                array_push($array_items, $_item);
            }
        }


        //$precio_promedio = $suma_precios / $cont_articulos; //<----------antes
        $precio_promedio = $suma_precios / $sum_total_articulos;

        //echo "suma_precios : {$suma_precios} / cont_articulos : {$cont_articulos} = precio_promedio : {$precio_promedio}";


        $producto_armado->cantidad = $sum_total_articulos;
        $producto_armado->precio = $precio_promedio;
        $producto_armado->descuento = $cont_descuento;

        array_push($array_items_venta, $producto_armado);
    }


    //var_dump($array_items);
    //echo "----------------------------------";
    //var_dump($array_items_venta);
    //TODO : Cambiar esto
    //revisamos si las existencias en el inventario maestro satisfacen a las requeridas en la venta
    if (!revisarExistenciasAdmin($array_items)) {
        Logger::log("No hay existencias suficientes en el Inventario maestro para satisfacer la demanda");
        die('{"success": false, "reason": "No hay suficiente producto en el Inventario Maestro para satisfacer la demanda. Intente de nuevo." }');
    }


    /*
     * Ahora condenzamos los productos que realmente se van a vender para poder crear
     * correctamente el detalle de la venta con los productos que se armaron y no con
     * los que se descontaran del inventario maestro.
     */

    $array_items_venta_codenzado = array();
    //insertamos el primer producto de item

    $item = new stdClass();
    $item->id_producto = $array_items_venta[0]->id_producto;
    $item->procesado = $array_items_venta[0]->procesado;

    if ($array_items_venta[0]->cantidad <= 0) {
        Logger::log("La cantidad de los productos debe ser mayor que cero.");
        die('{"success": false, "reason": "La cantidad de los productos debe ser mayor que cero." }');
    }

    if ($array_items_venta[0]->precio <= 0) {
        Logger::log("El precio de los productos debe ser mayor que cero.");
        die('{"success": false, "reason": "El precio de los productos debe ser mayor que cero." }');
    }

    if ($array_items_venta[0]->procesado == "true") {

        $item->cantidad_procesada = $array_items_venta[0]->cantidad;
        $item->precio_procesada = $array_items_venta[0]->precio;
        $item->cantidad = 0;
        $item->precio = 0;
        $item->descuento = $array_items_venta[0]->descuento;
    } else {
        $item->cantidad_procesada = 0;
        $item->precio_procesada = 0;
        $item->cantidad = $array_items_venta[0]->cantidad;
        $item->precio = $array_items_venta[0]->precio;
        $item->descuento = 0;
    }

    //insertamos el primer producto
    array_push($array_items_venta_codenzado, $item);



    //recorremos a $data->items para condensar el array de productos
    for ($i = 1; $i < count($array_items_venta); $i++) {

        if ($array_items_venta[$i]->cantidad <= 0) {
            Logger::log("La cantidad de los productos debe ser mayor que cero.");
            die('{"success": false, "reason": "La cantidad de los productos debe ser mayor que cero." }');
        }

        if ($array_items_venta[$i]->precio <= 0) {
            Logger::log("El precio de los productos debe ser mayor que cero.");
            die('{"success": false, "reason": "El precio de los productos debe ser mayor que cero." }');
        }

        //bandera para verificar si el producto no esta dentro de array_items
        $found = false;

        //iteramos el array_items y lo comparamos cada elementod e el con el producto actual
        foreach ($array_items as $item) {

            //comparamos haber si ya existe el producto en array_items
            if ($array_items_venta[$i]->id_producto == $item->id_producto) {

                $found = true;

                //(el producto se encontro) y es un producto procesado
                if ($array_items_venta[$i]->procesado == "true") {
                    $item->cantidad_procesada += $array_items_venta[$i]->cantidad;

                    /* if ($item->precio_procesada != 0 && $item->precio_procesada != $array_items_venta[$i]->precio) {
                      Logger::log("Selecciono dos productos iguales, pero con diferente precio.");
                      die('{"success": false, "reason": "Selecciono dos o mas productos ' . $item->id_producto . ' - PROCESADO, pero con diferente precio." }');
                      } */

                    $item->precio_procesada = $array_items_venta[$i]->precio;
                } else {
                    //(el producto se encontro) y es un producto original
                    $item->cantidad += $array_items_venta[$i]->cantidad;

                    /* if ($item->precio != 0 && $item->precio != $array_items_venta[$i]->precio) {
                      Logger::log("Selecciono dos productos iguales, pero con diferente precio.");
                      die('{"success": false, "reason": "Selecciono dos o mas productos ' . $item->id_producto . ' - ORIGINAL, pero con diferente precio." }');
                      } */

                    $item->precio = $array_items_venta[$i]->precio;

                    if ($item->descuento != 0 && $item->descuento != $array_items_venta[$i]->descuento) {
                        Logger::log("Selecciono dos productos iguales, pero con diferente descuento.");
                        die('{"success": false, "reason": "Selecciono dos o mas productos ' . $item->id_producto . ' - PROCESADO, pero con diferente descuento." }');
                    }

                    $item->descuento = $array_items_venta[$i]->descuento;
                }
            }
        }//for each del array_items

        if (!$found) {

            //si no se encuentra el producto en el arreglo de objetos hay que crearlo
            $_item = new stdClass();
            //$_item -> id_compra_proveedor = $data->items[$i] -> id_compra_proveedor;
            $_item->id_producto = $array_items_venta[$i]->id_producto;
            $_item->procesado = $array_items_venta[$i]->procesado;

            if ($array_items_venta[$i]->procesado == "true") {
                $_item->cantidad_procesada = $array_items_venta[$i]->cantidad;
                $_item->precio_procesada = $array_items_venta[$i]->precio;
                $_item->cantidad = 0;
                $_item->precio = 0;
                $_item->descuento = 0;
            } else {
                $_item->cantidad_procesada = 0;
                $_item->precio_procesada = 0;
                $_item->cantidad = $array_items_venta[$i]->cantidad;
                $_item->precio = $array_items_venta[$i]->precio;
                $_item->descuento = $array_items_venta[$i]->descuento;
            }
            array_push($array_items_venta_codenzado, $_item);
        }
    }//for de $data->items


    $detallesVenta = array();
    $subtotal = 0.0;

    foreach ($array_items_venta_codenzado as $producto) {

        $subtotal += ( ( ($producto->cantidad) * $producto->precio ) + ( $producto->cantidad_procesada * $producto->precio_procesada ) );
        $dv = new DetalleVenta();
        $dv->setIdProducto($producto->id_producto);

        $dv->setCantidad($producto->cantidad);
        $dv->setPrecio($producto->precio);
        $dv->setCantidadProcesada($producto->cantidad_procesada);
        $dv->setPrecioProcesada($producto->precio_procesada);

        array_push($detallesVenta, $dv);
    }//foreach
    //inicializamos un objeto venta
    $venta = new Ventas();
    $venta->setIdUsuario($_SESSION['userid']);
    $venta->setTotal(0);
    $venta->setIdSucursal($_SESSION['sucursal']);
    $venta->setIp($_SERVER['REMOTE_ADDR']);
    $venta->setPagado(0);
    $venta->setLiquidada(0);

    $venta->setCancelada(0);

    //verificamos que el tipo de venta sea valido
    switch ($data->tipo_venta) {

        case 'credito':
            $venta->setTipoVenta($data->tipo_venta);
            break;

        case 'contado':
            $venta->setTipoVenta($data->tipo_venta);
            break;

        default:
            Logger::log("El tipo de venta no es valido : " . $data->tipo_venta);
            die('{"success": false, "reason": "El tipo de venta no es valido ' . $data->tipo_venta . '." }');
    }

    if (isset($data->tipo_pago) && $data->tipo_venta == "contado") {

        //verificamos que el tipo de pago sea valido
        switch ($data->tipo_pago) {

            case 'efectivo':
                $venta->setTipoPago($data->tipo_pago);
                break;

            case 'cheque':
                $venta->setTipoPago($data->tipo_pago);
                break;

            case 'tarjeta':
                $venta->setTipoPago($data->tipo_pago);
                break;

            default:
                Logger::log("El tipo de pago no es valido : " . $data->tipo_pago);
                die('{"success": false, "reason": "El tipo de pago no es valido : ' . $data->tipo_pago . '." }');
        }
    }

    $venta->setIdCliente($data->cliente);

    $venta->setDescuento($cliente->getDescuento());

    DAO::transBegin();

    try {
        VentasDAO::save($venta);
        $id_venta = $venta->getIdVenta();
    } catch (Exception $e) {
        Logger::log($e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudo registrar la venta." }');
    }

    //TODO : Desarrollar una funcion que cree la factura con el api
    /* if ($data->factura) {
      insertarFacturaVenta($venta);
      } */

    //hasta aqui ya esta creada la venta, ahora sigue llenar el detalle de la venta
    //$productos = $data->items;
    //insertar detalles de la venta
    foreach ($detallesVenta as $dVenta) {

        $dVenta->setIdVenta($id_venta);

        try {
            DetalleVentaDAO::save($dVenta);
        } catch (Exception $e) {

            DAO::transRollback();
            Logger::log($e);
            die('{"success": false, "reason": "Error, al guardar el detalle venta." }');
        }
    }

    //descontamos del inventario el pedido
    foreach ($array_items as $producto) {
        $inventario_maestro = InventarioMaestroDAO::getByPK($producto->id_producto, $producto->id_compra_proveedor);
        if ($producto->procesada == true) {
            //requiere producto procesada
            $inventario_maestro->setExistenciasProcesadas($inventario_maestro->getExistenciasProcesadas() - ($producto->cantidad + $producto->descuento));
        } else {
            //requiere producto sin procesar
            $inventario_maestro->setExistencias($inventario_maestro->getExistencias() - $producto->cantidad);
        }
        try {
            InventarioMaestroDAO::save($inventario_maestro);
        } catch (Exception $e) {
            DAO::transRollback();
            Logger::log("Error, al descontar el pedido de productos del inventario maestro, exception : " . $e);
            die('{"success": false, "reason": "Error, al descontar el pedido de productos del inventario maestro" }');
        }
    }

    //ya que se tiene el total de la venta se actualiza el total de la venta
    $venta->setSubtotal($subtotal);
    $total = ( $subtotal - ( ( $subtotal * $cliente->getDescuento() ) / 100 ) );
    $venta->setTotal($total);


    //para ventas a credito
    if ($venta->getTipoVenta() == "credito") {
        //verificamos si el cliente cuenta con suficiente limite de credito para solventar esta venta
        $ventas_credito = listarVentaCliente($cliente->getIdCliente(), $venta->getTipoVenta());
        $adeuda = 0;       

        foreach ($ventas_credito as $vc) {

            //var_dump($vc);

            if ($vc['liquidada'] == "0") {
                $adeuda += $vc['saldo'];
            }
        }

        //echo "limite : {$cliente->getLimiteCredito()}, total : {$venta->getTotal()} adeuda : {$adeuda}";

        if ($cliente->getLimiteCredito() < ($venta->getTotal() + $adeuda)) {
            DAO::transRollback();
            Logger::log("Error : No cuenta con suficiente limite de credito para realizar esta venta a credito.");
            die('{"success": false, "reason": "Error : No cuenta con suficiente limite de credito para realizar esta venta a credito." }');
        }
    }


    //si la venta es de contado, hay que liquidarla
    if ($venta->getTipoVenta() == "contado") {
        $venta->setPagado($total);

        //validamos que el efectivo con el que se pago sea suficiente
        if($data->efectivo < $venta->getTotal())
        {
            DAO::transRollback();
            Logger::log("Error : No cuenta con suficiente efectivo para cubrir el total de la venta.");
            die('{"success": false, "reason": "Error : No cuenta con suficiente efectivo para cubrir el total de la venta." }');
        }
    }

    try {
        if (VentasDAO::save($venta)) {
            $empleado = UsuarioDAO::getByPK($venta->getIdUsuario());

            printf('{"success": true, "id_venta":%s, "empleado":"%s"}', $venta->getIdVenta(), $empleado->getNombre());
        } else {
            DAO::transRollback();
            die('{"success": false, "reason": "No se pudo actualizar el total de la venta" }');
        }
    } catch (Exception $e) {
        DAO::transRollback();
        Logger::log($e);
        die('{"success": false, "reason": "Intente de nuevo." }');
    }

    DAO::transEnd();

    Logger::log("Proveso de venta (admin), termino con exito!! id_venta : {$id_venta}.");
}

//vender


if (isset($args['action'])) {
    switch ($args['action']) {

        case 100:
            //realizar una venta desde una sucursal
            vender($args);
            break;

        case 101:
            //realizar una venta desde el admin
            venderAdmin($args);
            break;
    }
}
?>