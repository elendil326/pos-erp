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
require_once("model/compra_proveedor_fragmentacion.dao.php");
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
 *                     "descuento": float
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

        //----------------------

        if (!($producto = InventarioDAO::getByPK($data->items[$i]->id_producto))) {
            Logger::log("No se tiene registro del producto {$data->items[$i]->id_producto}.");
            die('{"success": false, "reason": "No se tiene registro del producto ' . $data->items[$i]->id_producto . '." }');
        }

        //verificamos si su precio es por agrupacion
        if ($producto->getPrecioPorAgrupacion()) {
            $data->items[$i]->cantidad *= $producto->getAgrupacionTam();
        }

        if ($producto->getTratamiento() == null) {
            $data->items[$i]->procesado = false;
        }

        //----------------------


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
    // var_dump($array_items);

    if (!revisarExistenciasSucursal($array_items)) {
        Logger::log("No hay existencias suficientes en el inventario de la suursal para satisfacer la demanda");
        die('{"success": false, "reason": "No hay existencias suficientes en el inventario de la suursal para satisfacer la demanda. Intente de nuevo." }');
    }


	//buscar la ultima venta de este equipo
	$ultima_venta = VentasDAO::getAll( 1, 1, "id_venta_equipo", 'DESC'  );
	$esta_venta_equipo_id = 0;
	
	if(sizeof($ultima_venta) == 1){
		//no hay ventas anteriores
		$esta_venta_equipo_id = $ultima_venta[0]->getIdVentaEquipo() + 1;
	}
	
    //inicializamos un objeto venta
    $venta = new Ventas();
    $venta->setIdUsuario($_SESSION['userid']);
    $venta->setTotal(0);
    $venta->setIdEquipo( $_SESSION['id_equipo'] );
    $venta->setIdVentaEquipo(  $esta_venta_equipo_id  );
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

        /*
          if($producto->getPrecioPorAgrupacion()){
          $detalle_venta->setPrecio($producto->getAgrupacionTam() / $producto->precio );
          }else{
          $detalle_venta->setPrecio($producto->precio);
          }
         */
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
        $detalle_inventario->setExistencias($detalle_inventario->getExistencias() - ($producto->cantidad + $producto->cantidad_procesada));

        //verificamos si su precio es por agrupacion

        $producto_inventario = InventarioDAO::getByPK($producto->id_producto);
        if ($producto_inventario->getPrecioPorAgrupacion()) {
            $producto->cantidad /= $producto_inventario->getAgrupacionTam();
            $producto->cantidad_procesada /= $producto_inventario->getAgrupacionTam();
        }

        $subtotal += ( ( $producto->cantidad_procesada * $producto->precio_procesada ) + ( $producto->cantidad * $producto->precio ) );

        try {
            DetalleInventarioDAO::save($detalle_inventario);
        } catch (Exception $e) {
            DAO::transRollback();
            Logger::log("Error, al descontar el pedido de productos del inventario de la sucursal, exception : " . $e);
            die('{"success": false, "reason": "Error, al descontar el pedido de productos del inventario de la sucursal.}');
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

/**
 * Descuenta un producto del inventario maestro
 * 
 * Esta funcion alterara la base de datos, sin embargo no hara ninguna transaccion, ni la abrira ni la cerrara !
 *
 * @param id_compra
 * @param id_producto
 * @param la cantidad a descontar en *escala* ! nada de agrupaciones
 * @param si se descontara procesado o no, si este producto no se puede procesar, se descontara de orignales sin importar lo que este valor tenga
 * @return true si se pudo descontar y false si no se pudo descontar porque ya no habia de ese producto
 * */
function descontarDeInventarioMaestro($id_compra, $id_producto, $cantidad_en_escala_a_descontar, $procesado = null) {

    //primero vamos a ver que existan suficientes para abastecer la cantidad
    $producto_en_inventario_maestro = InventarioMaestroDAO::getByPK($id_producto, $id_compra);

    if (!$producto_en_inventario_maestro) {
        Logger::log("Esta remision no existe en el inventario maestro !");
        return false;
    }

    //buscar este producto para ver sus propiedades
    $producto_en_inventario = InventarioDAO::getByPK($id_producto);

    if (!$producto_en_inventario) {
        Logger::log("Esta remision no existe en el inventario  !");
        return false;
    }


    //vamos a ver si este producto se puede procesar o no
    if ($producto_en_inventario->getTratamiento()) {
        //si se puede procesar !
    } else {
        //no se puede procesar !
        $procesado = false;
    }

    $existencias_actuales = 0;

    if ($procesado) {
        $existencias_actuales = $producto_en_inventario_maestro->getExistenciasProcesadas();

        if ($cantidad_en_escala_a_descontar > $existencias_actuales) {
            Logger::log("No hay suficientes !");
            return false;
        }
    } else {
        $existencias_actuales = $producto_en_inventario_maestro->getExistencias();

        if ($cantidad_en_escala_a_descontar > $existencias_actuales) {
            Logger::log("No hay suficientes !");
            return false;
        }
    }

    //ok, si hay suficientes, ahora a descontar
    if ($procesado) {
        $producto_en_inventario_maestro->setExistenciasProcesadas($existencias_actuales - $cantidad_en_escala_a_descontar);
    } else {
        $producto_en_inventario_maestro->setExistencias($existencias_actuales - $cantidad_en_escala_a_descontar);
    }

    //guardar el objeto
    try {
        InventarioMaestroDAO::save($producto_en_inventario_maestro);
    } catch (Exception $e) {
        Logger::log("Error al descontar del inventario maestro !");
        Logger::log($e);
        return false;
    }

    return true;
}

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

    Logger::log("******** Venta desde administracion **********");
    Logger::log("cliente:" . $data->cliente);


    //verificamos que se manden todos los parametros necesarios

    if (!isset($data->cliente)) {
        Logger::log("Falta uno o mas parametros: cliente");
        die('{"success": false, "reason": "Verifique sus datos, falta uno o mas parametros." }');
    }

    if (!isset($data->efectivo)) {
        Logger::log("Falta uno o mas parametros: efectivo");
        die('{"success": false, "reason": "No se envio la cantidad en efectivo." }');
    }

    if (!isset($data->tipo_venta)) {
        Logger::log("Falta uno o mas parametros: tipo de venta");
        die('{"success": false, "reason": "Verifique sus datos, falta uno o mas parametros." }');
    }


    if (!isset($data->productos)) {
        Logger::log("Falta uno o mas parametros:  productos");
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




    if ($data->tipo_venta == "contado") {

        if (!isset($data->tipo_pago)) {
            Logger::log("Error en venta a contado : No se ha especificado el tipo de pago.");
            die('{"success": false, "reason": "Error en venta a contado : No se ha especificado el tipo de pago." }');
        }

        switch ($data->tipo_pago) {
            case 'efectivo' :
                //validamos que envie el efectivo con el cual pago
                if (!isset($data->efectivo)) {
                    Logger::log("Pago en efectivo, Error : No se ha especificado la cantidad de efectivo con la que pago.");
                    die('{"success": false, "reason": "Pago en efectivo, Error : No se ha especificado la cantidad de efectivo con la que pago." }');
                }

                if ($data->efectivo <= 0) {
                    Logger::log("Pago en efectivo, Error : La cantidad con la cual intenta pagar debe de ser mayor que cero.");
                    die('{"success": false, "reason": "Pago en efectivo, Error : La cantidad con la cual intenta pagar debe ser mayor o igual que cero." }');
                }
                break;
            case 'cheque' :
                if (!isset($data->efectivo)) {
                    Logger::log("Pago con cheque, Error : No se ha especificado la cantidad de efectivo con la que pago.");
                    die('{"success": false, "reason": "Pago con cheque, Error : No se ha especificado la cantidad de efectivo con la que pago." }');
                }

                if ($data->efectivo <= 0) {
                    Logger::log("Pago con cheque, Error : La cantidad con la cual intenta pagar debe de ser mayor que cero.");
                    die('{"success": false, "reason": "Pago con cheque, Error : La cantidad con la cual intenta pagar debe ser mayor o igual que cero." }');
                }
                break;
            case 'tarjeta' :
                if (!isset($data->efectivo)) {
                    Logger::log("Pago con targeta, Error : No se ha especificado la cantidad con la que pago.");
                    die('{"success": false, "reason": "Pago con targeta, Error : No se ha especificado la cantidad con la que pago." }');
                }

                if ($data->efectivo <= 0) {
                    Logger::log("Pago con targeta, Error : La cantidad con la cual intenta pagar debe de ser mayor que cero.");
                    die('{"success": false, "reason": "Pago con targeta, Error : La cantidad con la cual intenta pagar debe ser mayor o igual que cero." }');
                }
                break;
            default:
                Logger::log("Error en venta a contado : No se ha especificado un tipo de pago valido.");
                die('{"success": false, "reason": "Error en venta a contado : No se ha especificado un tipo de pago valido. (' . $data->tipo_pago . ')" }');
        }
    }



    //verificamos que cada objeto de producto tenga los parametros necesarios

    foreach ($data->productos as $items) {

        //verificar que $items  sea un array
        if (!is_array($items->items)) {
            Logger::log("No se genero correctamente las especificaciones de los productos a vender.");
            die('{"success": false, "reason": "No se genero correctamente las especificaciones de los productos a vender." }');
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

    //----------------- Termina validacion ----------------- //
    //iniciar la transaccion
    DAO::transBegin();


    //crear esta venta
    $venta_actual = new Ventas();

    //campos que ya tenemos sus valores
    $venta_actual->setIdUsuario($_SESSION['userid']);
    $venta_actual->setIdCliente($data->cliente);
    $venta_actual->setTipoVenta($data->tipo_venta);
    $venta_actual->setTipoPago($data->tipo_pago);
    $venta_actual->setIdSucursal($_SESSION['sucursal']);
    $venta_actual->setIp($_SERVER['REMOTE_ADDR']);
    $venta_actual->setCancelada(0);

    //campos que llenaremos despues
    $venta_actual->setTotal(0);
    $venta_actual->setSubtotal(0);
    $venta_actual->setPagado(0);
    $venta_actual->setDescuento(0);
    $venta_actual->setLiquidada(0);


    //guardar la venta
    try {
        VentasDAO::save($venta_actual);
    } catch (Exception $e) {
        Logger::log($e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudo registrar la venta." }');
    }

    //un lugar donde guardamos los totales
    $totales_de_venta = array(
        "subtotal" => 0
    );

    foreach ($data->productos as $producto) {

        $producto_a_vender = InventarioDAO::getByPK($producto->producto);

        Logger::log("Vendiendo " . $producto_a_vender->getDescripcion() . ", se compone de : ");

        //lugar para guardar todo de los subproductos
        $totales_de_sub_producto = array(
            "subtotal" => 0,
            "cantidad" => 0,
            "descuento" => 0
        );

        //iteramos por sus subproductos
        foreach ($producto->items as $sub_producto) {

            /*             * *****************************************************
             * Asi es como se ve un sub producto:
             *  "id_compra": 51,
             *  "id_producto": 6,
             *  "cantidad": 300,
             *  "procesada": false,
             *  "precio": 983.3333333333334,
             *  "descuento": 1
             *
             *  -La cantidad ya esta en escala, y no tiene aplicado el descuento.
             *  -Ignorar el valor de procesada si este producto no se procesa.
             *  -El precio ya es el total que se cobrara por todo esto, por toda 
             *   la cantidad YA con descuento
             *  -El descuento viene en escalas, si este producto tiene precio por agrupacion,
             *   entonces este descuento se aplica por cada agrupacion, sino, solamente se 
             *   resta de cantidad.
             *  -Ojo porque la agrupacion depende de si esta procesada o no !
             * ***************************************************** */

            //obtener ese subproducto para ver sus propiedades
            $sub_producto_a_vender = InventarioDAO::getByPK($sub_producto->id_producto);


            //mostrar los detalles en el log
            Logger::log("		" . $sub_producto_a_vender->getDescripcion());
            Logger::log("		" . $sub_producto->cantidad . " " . $sub_producto_a_vender->getEscala());
            Logger::log("		" . $sub_producto->descuento . " " . $sub_producto_a_vender->getEscala() . " de descuento");
            Logger::log("		$" . $sub_producto->precio);




            //ver que existan suficintes existencias
            $descuento_ok = descontarDeInventarioMaestro(
                    $sub_producto->id_compra, $sub_producto->id_producto, $sub_producto->cantidad, $sub_producto->procesada
            );


            $compra_proveedor_fragmentacion = new CompraProveedorFragmentacion();
            $compra_proveedor_fragmentacion->setIdCompraProveedor($sub_producto->id_compra);
            $compra_proveedor_fragmentacion->setIdProducto($sub_producto->id_producto);

            $_producto = InventarioDAO::getByPK($sub_producto->id_producto);

            if ($sub_producto->procesada) {
                $compra_proveedor_fragmentacion->setDescripcion("SE VENDIO A {$cliente->getRazonSocial()} LA CANTIDAD DE {$sub_producto->cantidad} {$_producto->getEscala()}" . ($_producto->getEscala() == "unidad"?"es":"s") . " DEL PRODUCTO {$_producto->getDescripcion()} PROCESADO.");
                $compra_proveedor_fragmentacion->setProcesada(true);
            } else {
                $compra_proveedor_fragmentacion->setDescripcion("SE VENDIO A {$cliente->getRazonSocial()} LA CANTIDAD DE {$sub_producto->cantidad} {$_producto->getEscala()}" . ($_producto->getEscala() == "unidad"?"es":"s") . " DEL PRODUCTO {$_producto->getDescripcion()} PROCESADO.");
                $compra_proveedor_fragmentacion->setProcesada(false);
            }

            $compra_proveedor_fragmentacion->setCantidad($sub_producto->cantidad);
            $compra_proveedor_fragmentacion->setPrecio($sub_producto->cantidad/$sub_producto->precio);
            $compra_proveedor_fragmentacion->setDescripcionRefId($venta_actual->getIdVenta());

            try {
                CompraProveedorFragmentacionDAO::save($compra_proveedor_fragmentacion);
            } catch (Exception $e) {
                DAO::transRollback();
                Logger::log("Error, al guardar los datos del historial del producto del inventario maestro. : " . $e);
            }


            if (!$descuento_ok) {
                //algo no salio bien al descontar !
                DAO::transRollback();
                echo '{"success":false, reason : "No hay suficiente producto para satisfacer la demanda"}';
                return;
            }

            // el producto ha sido descontado
            // podemos contabilizar los totales
            $totales_de_venta["subtotal"] += $sub_producto->precio;
            $totales_de_sub_producto["subtotal"] += $sub_producto->precio;

            //el total de este producto
            $totales_de_sub_producto["cantidad"] += $sub_producto->cantidad;

            //restar el descuento
            if ($sub_producto_a_vender->getPrecioPorAgrupacion()) {
                //el descuento es por agrupacion
                //debemos saber si se puede procesar, si no se puede
                //procesar entonces el tamano de la agrupacion
                //es agrupacion_tam, si si se puede procesar
                //el tamano de la agrupacion depende de si esta 
                //procesado o no. Si esta procesado es agrupacion_tam
                //si no, es el promedio de ese inventario maestro

                $descuento_en_escala = 0;

                if ($sub_producto_a_vender->getTratamiento()) {
                    //si se puede procesar, revisamos si esta procesado o no
                    if ($sub_producto->procesado) {
                        //esta procesado ! entonces su agrupacion es por agrupacion_tam
                        $foo = InventarioDAO::getByPK($sub_producto->id_producto);

                        //cuantas agrupaciones hay ?
                        $agrupaciones = $sub_producto->cantidad / $foo->getAgrupacionTam();
                        $descuento_en_escala = $sub_producto->descuento * $agrupaciones;
                    } else {
                        //no esta procesado ! entonces su agrupacion la dice el la compra a proveedor
                        $foo = CompraProveedorDAO::getByPK($sub_producto->id_compra);

                        $agrupaciones = $sub_producto->cantidad / $foo->getPesoPorArpilla();
                        $descuento_en_escala = $sub_producto->descuento * $agrupaciones;
                    }
                } else {
                    //no hay tratamiento, el tamano es por agrupacion_tam
                    $foo = InventarioDAO::getByPK($sub_producto->id_producto);

                    $agrupaciones = $sub_producto->cantidad / $foo->getAgrupacionTam();
                    $descuento_en_escala = $sub_producto->descuento * $agrupaciones;
                }
            } else {
                //el descuento ya viene como debe ir
                $descuento_en_escala = $sub_producto->descuento;
            }

            //agregar al subtotal 
            $totales_de_sub_producto["descuento"] += $descuento_en_escala;

            Logger::log("		descuento_total : " . $descuento_en_escala);
            Logger::log("		cantidad_ya_con_des : " . ($sub_producto->cantidad - $descuento_en_escala));

            Logger::log("");
        }//fin-sub-productos


        Logger::log("			Totales SubProducto:");
        Logger::log("			cantidad:" . $totales_de_sub_producto["cantidad"]);
        Logger::log("			descuent:" . $totales_de_sub_producto["descuento"]);
        Logger::log("			total_ca:" . ($totales_de_sub_producto["cantidad"] - $totales_de_sub_producto["descuento"]));
        Logger::log("			subtotal:" . $totales_de_sub_producto["subtotal"]);


        //ya han sido descontado los subproductos, es hora de generar los detalles de venta
        // recuerda que este ya es el producto final, no importa de como se formo
        $detalle_de_venta = new DetalleVenta();
        $detalle_de_venta->setIdVenta($venta_actual->getIdVenta());
        $detalle_de_venta->setIdProducto($producto->producto);

        //calcular el precio por unidad
        $precio_por_unidad = 0;

        //depende, si el precio es por agrupacion o no
        if ($producto_a_vender->getPrecioPorAgrupacion()) {
            //saca cuantos grupos hay...

            if ($producto_a_vender->getTratamiento()) {
                //si se puede procesar, revisamos si esta procesado o no

                if ($producto->procesado) {
                    //esta procesado ! entonces su agrupacion es por agrupacion_tam
                    //cuantas agrupaciones hay ?
                    $agrupaciones = $totales_de_sub_producto["cantidad"] / $producto_a_vender->getAgrupacionTam();
                    $precio_por_unidad = $totales_de_sub_producto["subtotal"] / $agrupaciones;
                } else {
                    //que paso ?
                    $agrupaciones = $totales_de_sub_producto["cantidad"] / $producto_a_vender->getAgrupacionTam();
                    $precio_por_unidad = $totales_de_sub_producto["subtotal"] / $agrupaciones;
                }
            } else {
                //no hay tratamiento, el tamano es por agrupacion_tam
                $agrupaciones = $totales_de_sub_producto["cantidad"] / $producto_a_vender->getAgrupacionTam();
                $precio_por_unidad = $totales_de_sub_producto["subtotal"] / $agrupaciones;
            }
        } else {
            //divide el subtotal entre la cantidad
            $precio_por_unidad = $totales_de_sub_producto["subtotal"] / $totales_de_sub_producto["cantidad"];
        }

        //poner si es procesada o no es procesada
        if (($producto_a_vender->getTratamiento() !== null) && $producto->procesado) {
            //esta procesada
            $detalle_de_venta->setCantidad(0);
            $detalle_de_venta->setPrecio(0);

            $detalle_de_venta->setCantidadProcesada($totales_de_sub_producto["cantidad"]);
            $detalle_de_venta->setPrecioProcesada($precio_por_unidad);
        } else {
            //no esta procesada !
            $detalle_de_venta->setCantidad($totales_de_sub_producto["cantidad"]);
            $detalle_de_venta->setPrecio($precio_por_unidad);

            $detalle_de_venta->setCantidadProcesada(0);
            $detalle_de_venta->setPrecioProcesada(0);
        }

        $detalle_de_venta->setDescuento($totales_de_sub_producto["descuento"]);
        Logger::log(" 			importe por unidad  " . $precio_por_unidad);

        //guardar el detalle de la venta
        try {
            DetalleVentaDAO::save($detalle_de_venta);
        } catch (Exception $e) {
            Logger::log($e);
            DAO::transRollback();
            die('{"success": false, "reason": "No se pudo registrar la venta." }');
        }
    }


    $venta_actual->setSubTotal($totales_de_venta["subtotal"]);

    $cliente = ClienteDAO::getByPK($data->cliente);

    $descuento_del_cliente = $cliente->getDescuento();

    $descuento_en_venta = ($descuento_del_cliente / 100) * $venta_actual->getSubTotal();

    $venta_actual->setDescuento($descuento_en_venta);

    $venta_actual->setTotal($venta_actual->getSubTotal() - $descuento_en_venta);

    if ($data->efectivo >= $venta_actual->getTotal()) {
        //se pago todo
        $venta_actual->setPagado($venta_actual->getTotal());
        $venta_actual->setLiquidada(true);
    } else {
        //no se ha pagado todo
        $venta_actual->setPagado($data->efectivo);
        $venta_actual->setLiquidada(false);
    }

    Logger::log("Totales:");
    Logger::log("	Subtotal:" . $venta_actual->getSubtotal());
    Logger::log("	Total:" . $venta_actual->getTotal());

    //ah pero espera ! si es a contado y no esta liquidada en el primer putaso, a la verga !
    if (($venta_actual->getTipoVenta() == "contado") && (!$venta_actual->getLiquidada())) {
        DAO::transRollback();
        echo '{"success":false, "reason" : "No hay suficiente dinero para cubrir la venta de contado"}';
        return;
    }

    try {
        VentasDAO::save($venta_actual);
    } catch (Exception $e) {
        Logger::log($e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudo registrar la venta." }');
    }

    $empleado = UsuarioDAO::getByPK($venta_actual->getIdUsuario());



    DAO::transEnd();

    printf('{"success": true, "id_venta":%s, "empleado":"%s"}', $venta_actual->getIdVenta(), $empleado->getNombre());

    Logger::log("***** Proceso de venta (admin) termino con exito!! id_venta : " . $venta_actual->getIdVenta() . " ****");

    return;

}

//vender


if (isset($args['action'])) {
    switch ($args['action']) {

        case 100:
            //realizar una venta desde una sucursal
            //vender($args);
			die('{"success": false}');
        break;

        case 101:
            //realizar una venta desde el admin
            venderAdmin($args);
        break;
    }
}
?>