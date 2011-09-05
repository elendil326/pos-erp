<?php

/**
 *  Controller para autorizaciones
 */
require_once("model/autorizacion.dao.php");
require_once("model/detalle_inventario.dao.php");
require_once("model/detalle_venta.dao.php");
require_once("model/cliente.dao.php");
require_once("model/ventas.dao.php");
require_once("model/compra_sucursal.dao.php");
require_once("model/inventario.dao.php");
require_once('model/actualizacion_de_precio.dao.php');
require_once("logger.php");

/**
 *  Solicitud de Autorización 
 *
 * Inserta en la BD una nueva autorizacion con estado = 0.
 *
 * @param $auth parametros que describen la autorización
 * @param $tipo descricion breve del tipo de autorizacion  
 * @returns void
 *
 * */
function solicitudDeAutorizacion($auth, $tipo) {

    Logger::log("Iniciando proceso de creacion de nueva autorizacion");

    $autorizacion = new Autorizacion();

    $autorizacion->setIdUsuario($_SESSION['userid']);
    $autorizacion->setIdSucursal($_SESSION['sucursal']);
    $autorizacion->setEstado('0');
    $autorizacion->setTipo($tipo);

    $autorizacion->setParametros($auth);

    try {
        AutorizacionDAO::save($autorizacion);
    } catch (Exception $e) {

        Logger::log("Error: " . $e);
        die('{ "success" : false , "reason" : "No se pudo enviar la autorizacion."}');
    }

    Logger::log("Terminado proceso de creacion de nueva autorizacion");
    echo '{"success": true }';
}

/**
 *  Autorizaciones Pendientes
 *
 * Busca todas las autorizaciones cuyo estado sea  0 (Pendientes de responder) .
 *
 * @return Array Un arreglo que contiene objetos del tipo {@link Autorizacion}.
 *
 * */
function autorizacionesPendientes() {

    Logger::log("Iniciando proceso de obtencion de autorizaciones pendientes.");

    $autorizacion = new Autorizacion();
    $autorizacion->setEstado('0');

    $array = AutorizacionDAO::search($autorizacion);

    Logger::log("Terminado proceso de obtencion de autorizaciones pendientes, preparandose para regresar los resultados.");

    return $array;
}

//autorizacionesPendientes

/**
 *  Autorizaciones Sucursal
 *
 * Busca todas las autorizaciones de tipo envioDeProductosASucursal ó solicitudDeProductos del dia en curso, referentes a una sucursal en especifico.
 *
 * @param $sid Id de la sucursal
 * @return Array Un arreglo que contiene objetos del tipo {@link Autorizacion}, con la caracteristica especial, de que se le icluye al descripcion de los productos y su escala..
 *
 * */
//TODO:  creo que el nombre de la funcion no va muy acorde con lo que hace :P

function autorizacionesSucursal($sid) {


    $dia = time() - (7 * 24 * 60 * 60); //Te resta un dia (2*24*60*60) te resta dos y //asi...
    $dia = date('Y-m-d', $dia); //Formatea dia

    $foo = new Autorizacion();
    $foo->setIdSucursal($sid);
    $foo->setFechaPeticion($dia . " 00:00:00");


    $bar = new Autorizacion();
    $bar->setIdSucursal($sid);
    $bar->setFechaPeticion(date("Y-m-d") . " 23:59:59");

    $autorizaciones = AutorizacionDAO::byRange($foo, $bar);

    $array_autorizaciones = array();

    foreach ($autorizaciones as $autorizacion) {


        //si la autorizacion contiene productos, agregarles la descripcion
        if ($autorizacion->getTipo() == 'envioDeProductosASucursal'
                || $autorizacion->getTipo() == 'solicitudDeProductos'
        ) {

            $params = json_decode($autorizacion->getParametros());

            foreach ($params->productos as $key => $prod) {

                $foo = InventarioDAO::getByPK($prod->id_producto);

                $params->productos[$key]->descripcion = $foo->getDescripcion();
                $params->productos[$key]->escala = $foo->getEscala();
            }

            $autorizacion->setParametros(json_encode($params));
        }

        $auth = $autorizacion->asArray();
        array_push($array_autorizaciones, $auth);
    }


    return $array_autorizaciones;
}

/**
 * Surtir Producto.
 *
 * Se encarga de cargar al inventario el juego de productos enviado por el administrador.
 *
 * @param id_autorizacion El id de una autorizacion existente en estado 3.
 *
 * */
function surtirProducto($id_autorizacion) {


    if (!( $autorizacion = AutorizacionDAO::getByPK($id_autorizacion) )) {
        die('{"success": false, "reason": "La autorizacion no existe. El administrador pudo haber eliminado esta autorizacion." }');
    }

    if ($autorizacion->getEstado() != 3) {
        die('{"success": false, "reason": "Esta autorizacion ya ha sido aprovada." }');
    }

    try {
        $data = parseJSON($autorizacion->getParametros());
    } catch (Exception $e) {
        Logger::log($e);
        die('{"success": false, "reason": "Parametros invalidos." }');
    }



    $productos = $data->productos;

    DAO::transBegin();

    foreach ($productos as $producto) {

        //buscar la existencia de cada producto en la sucursal en la que voy a insertar
        $p = DetalleInventarioDAO::getByPK($producto->id_producto, $_SESSION['sucursal']);

        if (!$p) {
            //no existe en la sucursal, voy a ver si existe en el inventario global
            $producto_inventario = InventarioDAO::getByPK($producto->id_producto);

            if (!$producto_inventario) {
                Logger::log("Se ha intentado surtir un producto que no existe");
                DAO::transRollback();
                die('{ "success" : false , "reason" : "El producto ' . $producto->id_producto . ' no se encuentra registrado en el inventario."}');
            }

            //el producto existe en el inventario global pero no en la sucursal, insertarlo
            $nuevo_detalle_producto = new DetalleInventario();
            $nuevo_detalle_producto->setIdProducto($producto->id_producto);
            $nuevo_detalle_producto->setIdSucursal($_SESSION['sucursal']);
            $nuevo_detalle_producto->setExistencias(0);
            $nuevo_detalle_producto->setExistenciasProcesadas(0);
            $nuevo_detalle_producto->setPrecioCompra(0);

            //buscar la ultima actualizacion de precio para este producto
            $foo = new ActualizacionDePrecio();
            $foo->setIdProducto($producto->id_producto);
            $actualizacion = ActualizacionDePrecioDAO::search($foo, 'fecha', 'DESC');
            $actualizacion = $actualizacion[0];

            $nuevo_detalle_producto->setPrecioVenta($actualizacion->getPrecioVenta());
            $nuevo_detalle_producto->setPrecioVentaProcesado($actualizacion->getPrecioVentaProcesado());

            try {
                DetalleInventarioDAO::save($nuevo_detalle_producto);
            } catch (Exception $e) {

                DAO::transRollback();
                Logger::log($e);
                die('{ "success" : false , "reason" : "Error al surtir esta autorizacion, intente de nuevo."}');
            }

            $p = $nuevo_detalle_producto;
        }

        //actualizamos el inventario
        $p->setExistenciasProcesadas($p->getExistenciasProcesadas() + $producto->cantidad_procesada);
        $p->setExistencias($p->getExistencias() + $producto->cantidad + $producto->cantidad_procesada);



        /*
          if($producto->procesada){
          $existencias = $p->getExistenciasProcesadas();
          $existencias += $producto->cantidad;
          $p->setExistenciasProcesadas( $existencias );
          }else{
          $existencias = $p->getExistencias();
          $existencias += $producto->cantidad;
          $p->setExistencias( $existencias );
          }
         */


        //guardamos los cambios
        try {
            DetalleInventarioDAO::save($p);
        } catch (Exception $e) {
            Logger::log($e);
            DAO::transRollback();
            die('{"success": false, "reason": "Error al surtir esta autorizacion, intente de nuevo." }');
        }
    }

    //cambiamos el estado de la autorizacion a surtido
    $autorizacion->setEstado(4);

    //guardamos quien surtio el envio
    $autorizacion->setIdUsuario($_SESSION['userid']);

    try {
        AutorizacionDAO::save($autorizacion);
    } catch (Exception $e) {
        DAO::transRollback();
        Logger::log($e);
        die('{ "success" : false , "reason" : "Error al surtir esta autorizacion, intente de nuevo."}');
    }

    DAO::transEnd();
    Logger::log("Autorizacion surtida satisfactoriamente");
    $empleado = UsuarioDAO::getByPK($autorizacion->getIdUsuario());
    printf('{"success": true, "empleado":"%s"}', $empleado->getNombre());
}

/**
 *  Detalle Autorizacion
 *
 * Busca una autorizacion apartir de un ID.
 *
 * @param $id_autorizacion id de una autorizacion.
 * @return objeto del tipo {@link Autorizacion}.
 *
 * */
function detalleAutorizacion($id_autorizacion) {

    Logger::log("Iniciando proceso de Detalle Autorizacion.");

    if (!isset($id_autorizacion)) {
        Logger::log("Error : faltan parametros.");
        die('{"success": false, "reason": "Faltan parametros." }');
    }

    if (!( $autorizacion = AutorizacionDAO::getByPK($id_autorizacion) )) {
        Logger::log("Error : No se encontro registro de la autorizacion");
        die('{"success": false, "reason": "Verifique que exista la autorizacion ' . $id_autorizacion . '." }');
    }

    Logger::log("Terminado proceso de Detalle Autorizacion, preparando los datos para enviar.");

    return $autorizacion;
}

/**
 *  Responder Autorizacion de Solicitud de Productos
 *
 * Crea una autorizacion para surtir productos a una sucursal en especifico o modifica una existente en caso de enviar el argumento opcional "responseToAut".
 *
 * @param Array $args( 
 *                       'data' => [{"id_producto" : int, "cantidad" : int, "cantidad_procesada" : int }],
 *                       'conductor' => string,
 *                       'id_sucursal' => int
 *                       [,'responseToAut' => int ]
 *                   )
 * @return void
 *
 * */
function responderAutorizacionSolicitudProductos($args) {

    Logger::log("Iniciando proceso de responder autorizacion de solicitud de productos.");

    DAO::transBegin();

    if (!isset($args['data']) || !isset($args['id_sucursal'])) {
        Logger::log("Faltan parametros para surtir sucursal.");
        DAO::transRollback();
        die('{"success": false, "reason": "Faltan parametros." }');
    }

    try {

        Logger::log("JSON Bruto vale : " + $args['data']);

        $data = parseJSON($args['data']);
    } catch (Exception $e) {
        Logger::log("JSON invalido para surtir sucursal.");
        DAO::transRollback();
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    /*
      si no se envio responseToAut entonces:
      crea un nuevo objeto de tipo Autorizacion
      si si
      entonces obtenlo.
     */

    if (isset($args['responseToAut']) && $args['responseToAut'] !== "null") {
        $autorizacion = AutorizacionDAO::getByPK($args['responseToAut']);
    } else {
        $autorizacion = new Autorizacion();
        $autorizacion->setTipo("envioDeProductosASucursal");
    }


    $autorizacion->setIdUsuario($_SESSION['userid']);
    $autorizacion->setEstado('3');
    $autorizacion->setIdSucursal($args['id_sucursal']);

    $time = strftime("%Y-%m-%d-%H-%M-%S", time());

    $autorizacion->setFechaPeticion($time);
    $autorizacion->setFechaRespuesta($time);

    if (!isset($args['conductor']) || $args['conductor'] == "") {
        $conductor = "No especificado";
    } else {
        $conductor = $args['conductor'];
    }


    $parametros = json_encode(array(
        'clave' => 209,
        'descripcion' => 'Envio de productos',
        'conductor' => $conductor,
        'productos' => $data
            ));

    $autorizacion->setParametros($parametros);

    try {
        AutorizacionDAO::save($autorizacion);

        if (!( isset($args['venta_intersucursal']) && $args['venta_intersucursal'] == true )) {
            printf('{ "success" : true }');
        }
    } catch (Exception $e) {

        DAO::transRollback();
        Logger::log("Imposible guardar autorizacion: " . $e);
        die('{ "success" : false , "reason" : "Exception al cambiar estado de la autorización."}');
    }


    DAO::transEnd();
    Logger::log("Terminado proceso de responder autorizacion de solicitud de productos. Autorizacion = {$autorizacion->getIdAutorizacion()}.");
}

/**
 *  responderAutorizacion Gasto
 *
 * Crea una autorizacion de para surtir productos a una sucursal en especifico o modifica una existente en caso de enviar el argumento opcional "responseToAut".
 *
 * @param Array $args( 'id_autorizacion' => int, 'reply' => 0 | 1 )
 * @return void
 *
 */
function responderAutorizacionGasto($args) {

    Logger::log("Iniciando proceso de Respuesta Autorizacion Gasto");

    $autorizacion = AutorizacionDAOBase::getByPK($args['id_autorizacion']);
    $autorizacion->setFechaRespuesta(strftime("%Y-%m-%d-%H-%M-%S", time()));
    $autorizacion->setEstado($args['reply']);

    try {
        AutorizacionDAO::save($autorizacion);
    } catch (Exception $e) {
        Logger::log("Error : " . $e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudoguardar la autorizacion." }');
    }

    DAO::transEnd();
    Logger::log("Terminado proceso de Respuesta Autorizacion Gasto");
    printf('{"success" : true}');
}

//responderAutorizacionGasto

/**
 *  Responder Autorizacion de Devolucion Cliente
 *
 *  Modifica el estado de la autorizacion y en caso de ser aceptada la autorizacion, modifica el detalle del inventario de la sucursal, la venta y el detalle de la venta correspondiente a esa devolucion. 
 *
 * @param Array $args( 'id_autorizacion' => int, 'reply' => 0 | 1 )
 * @return void
 *
 */
function responderAutorizacionDevolucionCliente($args) {

    Logger::log("Iniciando proceso de Respuesta de Autorizacion Devolución.");

    if (!$autorizacion = AutorizacionDAOBase::getByPK($args['id_autorizacion'])) {
        Logger::log("Error, no se encontro la autorizacion {$args['id_autorizacion'] }.");
        die('{"success": false, "reason": "No se pudo guardar la autorizacion 0." }');
    }


    $autorizacion->setFechaRespuesta(strftime("%Y-%m-%d-%H-%M-%S", time()));
    $autorizacion->setEstado($args['reply']);

    DAO::transBegin();

    //guardamos el nuevo estado de la autorización
    try {
        AutorizacionDAO::save($autorizacion);
    } catch (Exception $e) {
        Logger::log("Error : " . $e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudo guardar la autorizacion." }');
    }

    //obtenemos los parametros de la autorizacion
    try {
        $parametros = parseJSON($autorizacion->getParametros());
    } catch (Exception $e) {
        Logger::log("Error : " . $e);
        DAO::transRollback();
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    //modificamos el limite de credito del cliente en caso de que se haya autorizado
    if ($autorizacion->getEstado() == 1) {
        //aqui entra en caso de que se haya aprobado la autorizacion de merma
        //obtenemos el detalle del inventario
        if (!( $detalle_inventario = DetalleInventarioDAO::getByPK($parametros->id_producto, $autorizacion->getIdSucursal()) )) {
            Logger::log("Error : Verifique que exista el producto  {$parametros->id_producto} en la sucursal {$autorizacion->getIdSucursal()}.");
            DAO::transRollback();
            die('{"success": false, "reason": "Verifique que exista el producto ' . $data->id_producto . ' en la sucursal ' . $autorizacion->getIdSucursal() . '." }');
        }

        //cambiamos las existencias en el inventario
        $detalle_inventario->setExistencias($detalle_inventario->getExistencias() + $parametros->cantidad);
        $detalle_inventario->setExistenciasProcesadas($detalle_inventario->getExistenciasProcesadas() + $parametros->cantidad_procesada);

        //guardamos el detalle del iventario
        try {
            AutorizacionDAO::save($autorizacion);
        } catch (Exception $e) {
            Logger::log("Error : " . $e);
            DAO::transRollback();
            die('{"success": false, "reason": "No se pudo guardar del detalle del inventario." }');
        }

        //modificamos el detalle de la venta
        if (!( $detalle_venta = DetalleVentaDAO::getByPK($parametros->id_venta, $parametros->id_producto) )) {
            Logger::log("Error : Verifique que exista la venta  {$parametros->id_venta} y en ella el producto {$parametros->id_producto}.");
            DAO::transRollback();
            die('{"success": false, "reason": "Verifique que exista la venta ' . $parametros->id_venta . ' e en ella el producto ' . $parametros->id_producto . '." }');
        }

        //cambiamos las existencias en el detalle venta
        $detalle_venta->setCantidad($detalle_venta->getCantidad() - $parametros->cantidad);
        $detalle_venta->setCantidadProcesada($detalle_venta->getCantidadProcesada() - $parametros->cantidad_procesada);

        //guardamos el detalle de la venta
        try {
            DetalleVentaDAO::save($detalle_venta);
        } catch (Exception $e) {
            Logger::log("Error : " . $e);
            DAO::transRollback();
            die('{"success": false, "reason": "No se pudo guardar el detalle de la venta.." }');
        }

        //modificamos el lod datos de la venta
        if (!( $venta = VentasDAO::getByPK($parametros->id_venta) )) {
            Logger::log("Error : No se tiene registro de la venta {$parametros->id_venta}.");
            DAO::transRollback();
            die('{"success": false, "reason": "Verifique que exista la venta ' . $parametros->id_venta . '." }');
        }

        $valor_mercancia_devuelta = $detalle_venta->getPrecio() * $parametros->cantidad;
        $valor_mercancia_devuelta_procesada = $detalle_venta->getPrecioProcesada() * $parametros->cantidad_procesada;
        $venta->setSubtotal($venta->getSubtotal() - ( $valor_mercancia_devuelta + $valor_mercancia_devuelta_procesada ));
        $venta->setTotal($venta->getSubtotal() - ( ( $venta->getSubtotal() + $venta->getIva() ) * $venta->getDescuento() ));

        if ($venta->getLiquidada() == 1) {
            $venta->setPagado($venta->getTotal());
        } else {

            if ($venta->getPagado >= $venta->getTotal) {
                $venta->setLiquidada("1");
            }
        }

        //guardamos el detalle de la venta
        try {
            VentasDAO::save($venta);
        } catch (Exception $e) {
            Logger::log("Error : " . $e);
            DAO::transRollback();
            die('{"success": false, "reason": "No se pudo guardar los detalle de la venta.." }');
        }

        //modificamos el inventario de la sucursal
        if (!( $detalle_inventario = DetalleInventarioDAO::getByPK($parametros->id_producto, $autorizacion->getIdSucursal()) )) {
            Logger::log("Error : No se tiene registro del producto {$parametros->id_producto} en la sucursal {$autorizacion->id_sucursal}.");
            DAO::transRollback();
            die('{"success": false, "reason": "Verifique que el producto ' . $parametros->id_producto . ' en la venta ' . $autorizacion->getIdSucursal() . '." }');
        }

        $detalle_inventario->setExistencias($detalle_inventario->getExistencias() + $parametros->cantidad);
        $detalle_inventario->setExistenciasProcesadas($detalle_inventario->getExistenciasProcesadas() + $parametros->cantidad_procesada);

        //guardamos el detalle del inventario de la sucursal
        try {
            DetalleInventarioDAO::save($detalle_inventario);
        } catch (Exception $e) {
            Logger::log("Error : " . $e);
            DAO::transRollback();
            die('{"success": false, "reason": "No se pudo guardar el detalle del inventario." }');
        }
    }

    DAO::transEnd();
    Logger::log("Termiando proceso de Respuesta Autorizacion Devolución.");
    printf('{"success" : true}');
}

//responderAutorizacionDevolucionCliente

/**
 *  Responder Autorizacion de Limite de Credito
 *
 *  Modifica el estado de la autorizacion y en caso de ser aceptada la autorizacion, modifica el limite de credito de un cliente en especifico
 *
 *  @param Array $args( 'id_autorizacion' => int, 'reply' => 0 | 1 )
 *  @return void
 *
 */
function responderAutorizacionLimiteCredito($args) {

    Logger::log("Iniciando proceso de Respuesta Autorizacion Limite de Credito");

    $autorizacion = AutorizacionDAOBase::getByPK($args['id_autorizacion']);
    $autorizacion->setFechaRespuesta(strftime("%Y-%m-%d-%H-%M-%S", time()));
    $autorizacion->setEstado($args['reply']);

    DAO::transBegin();

    //guardamos el nuevo estado de la autorización
    try {
        AutorizacionDAO::save($autorizacion);
    } catch (Exception $e) {
        Logger::log("Error : " . $e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudo guardar la autorizacion." }');
    }

    //obtenemos los parametros de la autorizacion
    try {
        $parametros = parseJSON($autorizacion->getParametros());
    } catch (Exception $e) {
        Logger::log("Error : " . $e);
        DAO::transRollback();
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    //modificamos el limite de credito del cliente en caso de que se haya autorizado
    if ($autorizacion->getEstado() == 1) {
        //aqui entra si se aprobo la autorizacion

        if (!( $cliente = ClienteDAO::getByPK($parametros->id_cliente) )) {
            Logger::log("Error : " . $e);
            DAO::transRollback();
            die('{"success": false, "reason": "Verifique que exista el cliente ' . $parametros->id_cliente . '." }');
        }

        $cliente->setLimiteCredito($parametros->cantidad);
        try {
            ClienteDAO::save($cliente);
        } catch (Exception $e) {
            Logger::log("Error : " . $e);
            DAO::transRollback();
            die('{"success": false, "reason": "Error al cambiar el limite de credito al cliente ' . $cliente->getIdCliente() . '." }');
        }
    }

    DAO::transEnd();
    Logger::log("Terminado proceso de Respuesta Autorizacion Limite de Credito");
    printf('{ "success" : true }');
}

//responderAutorizacionLimiteCredito

/**
 * Cancelar autorizacion.
 *
 * Este metodo cambia el estado actual de la autorizacion cuyo id se pasa como argumento. Si dicha autorizacion no
 * existe se regresara success false en JSON. Si la autorizacion existe se cambiara el estado a 5 y se regresara 
 * success true en JSON.
 *
 * @param Array $args( 'id_autorizacion' => int )
 * @return void
 *
 */
function cancelarAutorizacion($args) {

    $autorizacion = AutorizacionDAO::getByPK($args["id_autorizacion"]);

    $existe = $autorizacion == NULL ? die('{"success"	:	false	,	"reason"	:	"No existe esa autorizacion"}') : "";

    $autorizacion->setEstado("5");

    try {
        $result = AutorizacionDAO::save($autorizacion);
        echo '{"success"	:	true	}';
    } catch (Exception $e) {

        Logger::log($e);
        die('{"success"	:	false	,	"reason"	:	"No se realizo el cambio de estado, intente de nuevo."}');
    }
}

//cancelarAutorizacion

/**
 *   Responder autorizacion. 
 *
 *   Este metodo es llamado desde el admin para aceptar o denegar una autorizacion, automaticamente
 *   detecta que tipo de autorizacion es y manda llamar al metodo indicado para desencadenar la accion adecuada.
 *
 *     Claves de las autorizaciones:
 *
 *         201 : Solicitud de autorizacion de gasto (Enviada por el gerente)  (true | false)
 *         202 : Solicitud de autorizacion de cambio de limite de credito (Enviada por el gerente)  (true | false)
 *         203 : Solicitud de autorizacion de devolucion de produto (Enviada por el gerente)  (llamar a una funcion que haga el ajuste)
 *         204 : Solicitud de autorizacion de precios especiales (Enviada por el gerente)  ((llamar a una funcion que haga el ajuste)
 *         209 : Solicitud de uno o mas productos (Enviado por el gerente)  (en espera haber si se podra responder)
 *         214 : Solicitud de autorizacion de envio de productos (Enviada por el admin) 
 *     
 *             -------------------------------------------------------------------------------
 *             | ESTADO |          DECRIPCION         |     APLICA A:                            | 
 *             |------------|-----------------------------------------------------------------
 *             |        0        | En espera de respuesta | 201, 202, 203, 204, 209          |
 *             |        1        | Aprobada                         | 201, 202, 203, 204                  |
 *             |        2        | Denegada                        | 201, 202, 203, 204                   | 
 *             |        3        | En transito                      | 214                                             |
 *             |        4        | Surtido                            | 214                                             |
 *             |        5        | Eliminada                         | TODAS                                      |
 *             |        6        | Aplicada                          | 204                                             |
 *             -------------------------------------------------------------------------------
 *
 *  @param Array $args( 'id_autorizacion' => int )
 *  @return void
 *
 */
function responderAutorizacion($args) {

    Logger::log("Iniciando proceso de respuesta de autorizacion.");

    if (!isset($args['reply']) || !isset($args['id_autorizacion'])) {
        Logger::log("Error : Faltan parametros.");
        die('{"success" : false, "reason" : "Faltan parametros." }');
    }

    if (!( $args['reply'] == 1 || $args['reply'] == 2 )) {
        Logger::log("Error : Faltan parametros.");
        die('{"success" : false, "reason" : "Parametros invalidos." }');
    }

    if (!( $autorizacion = AutorizacionDAOBase::getByPK($args['id_autorizacion']) )) {
        Logger::log("Error : No se encontro registro de la autorizacion : {$autorizacion}.");
        die('{"success" : false, "reason" : "No se encontro registro de la autorizacion : ' . $autorizacion . '." }');
    }

    //obtenemos los parametros de la autorizacion para poder extraer su clave
    try {
        $parametros = parseJSON($autorizacion->getParametros());
    } catch (Exception $e) {
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    switch ($parametros->clave) {

        case "201": //Solicitud de autorizacion de gasto (Enviada por el gerente)  (solo requiere cambiar su estado por true | false)

            Logger::log("Autorizacion : Solicitud de autorización de gasto.");
            responderAutorizacionGasto($args);

            break;

        case "202"://Solicitud de autorizacion de cambio de limite de credito (Enviada por el gerente)  (solo requiere cambiar su estado por true | false)

            Logger::log("Autorizacion : Solicitud de autorización de cambio de precio.");
            responderAutorizacionLimiteCredito($args);

            break;

        case "203"://Solicitud de autorizacion de devolucion de produto en venta a cliente (Enviada por el gerente)  (llamar a una funcion que haga el ajuste)

            Logger::log("Autorizacion : Solicitud de autorizacion de devolucion de producto por parte del cliente.");
            responderAutorizacionDevolucionCliente($args);

            break;

        case "204"://solicitud de autorizacion de precios especiales (Enviada por el gerente)  (llamar a una funcion que haga el ajuste)

            Logger::log("Autorizacion : Solicitud de autorizacón de precios especiales.");
            responderAutorizacionPreciosEspeciales($args);

            break;

        default:
            Logger::log("Error : Clave de autorizacion {$parametros->clave} es invalida.");
            die('{"success" : false, "reason" : "Clave de autorizacion ' . $parametros->clave . ' es invalida." }');
    }
}

//responderAutorizacion

/**
 *   Responder Autorizacion de Precios Especiales
 *
 *  Modifica el estado de la autorizacion y en caso de ser aceptada la autorizacion
 *
 */
function responderAutorizacionPreciosEspeciales($args) {

    Logger::log("Iniciando proceso de Respuesta Autorizacion Precios Especiales");

    DAO::transBegin();

    $autorizacion = AutorizacionDAOBase::getByPK($args['id_autorizacion']);
    $autorizacion->setFechaRespuesta(strftime("%Y-%m-%d-%H-%M-%S", time()));
    $autorizacion->setEstado($args['reply']);

    try {
        AutorizacionDAO::save($autorizacion);
    } catch (Exception $e) {
        Logger::log("Error : " . $e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudoguardar la autorizacion." }');
    }

    DAO::transEnd();
    Logger::log("Terminado proceso de Respuesta Autorizacion Precios Especiales");
    printf('{"success" : true}');
}

//responderAutorizacionPreciosEspeciales

/**
 *   Finalizar Venta Preferencial
 *
 *  Cuando ya se realizo la venta preferencial, entonces se cambia su estado de la autorizacion
 *
 */
function finalizarVentaPreferencial($args) {

    Logger::log("Iniciando proceso de finalizar venta preferencial");

    DAO::transBegin();

    if (!isset($args['id_autorizacion'])) {
        Logger::log("Error : No se especifico el id de la autorizacion.");
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudoguardar la autorizacion." }');
    }

    $autorizacion = AutorizacionDAOBase::getByPK($args['id_autorizacion']);
    $autorizacion->setFechaRespuesta(strftime("%Y-%m-%d-%H-%M-%S", time()));
    $autorizacion->setEstado(6);

    try {
        AutorizacionDAO::save($autorizacion);
    } catch (Exception $e) {
        Logger::log("Error : " . $e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudoguardar la autorizacion." }');
    }

    DAO::transEnd();
    Logger::log("Terminado proceso de finalizar venta preferencial");
    printf('{"success" : true}');
}

//finalizarVentaPreferencial


if (isset($args['action'])) {

    switch ($args['action']) {

        case 201://solicitud de autorizacion de gasto (gerente)

            if (!isset($args['concepto']) || !isset($args['monto'])) {
                die('{ "success" : false , "reason" : "Faltan datos" }');
            }

            if (!is_numeric($args['monto'])) {
                die('{ "success" : false , "reason" : "No es una cantidad valida." }');
            }

            $descripcion = json_encode(array(
                'clave' => $args['action'],
                'descripcion' => 'Autorización de gasto',
                'concepto' => $args['concepto'],
                'monto' => $args['monto']
                    ));

            solicitudDeAutorizacion($descripcion, "solicitudDeGasto");

            break;

        case 202://solicitud de autorizacion de cambio de limite de credito (gerente)

            if (!isset($args['id_cliente']) || !isset($args['cantidad'])) {
                die('{ "success" : false , "reason" : "Faltan datos" }');
            }

            if (!is_numeric($args['cantidad'])) {
                die('{ "success" : false , "reason" : "No es una cantidad valida." }');
            }

            $descripcion = json_encode(array(
                'clave' => $args['action'],
                'descripcion' => 'Autorización de limite de crédito',
                'id_cliente' => $args['id_cliente'],
                'nombre' => $args['nombre'],
                'cantidad' => $args['cantidad']
                    ));

            solicitudDeAutorizacion($descripcion, "solicitudDeCambioLimiteDeCredito");

            break;

        case 203://solicitud de autorizacion de devolucion en venta a cliente (gerente)

            if (!isset($args['data'])) {
                die('{"success": false, "reason": "No hay parametros para ingresar." }');
            }

            try {
                $data = parseJSON($args['data']);
            } catch (Exception $e) {
                die('{"success": false, "reason": "Parametros invalidos." }');
            }

            if (!( isset($data->id_venta) && isset($data->id_producto) && isset($data->cantidadDevuelta) && isset($data->cantidadProcesadaDevuelta) )) {
                die('{"success": false, "reason": "Faltan parametros." }');
            }

            $data->cantidadDevuelta += 0;
            $data->cantidadProcesadaDevuelta += 0;

            if (!is_numeric($data->cantidadDevuelta)) {
                die('{ "success" : false , "reason" : "No es una cantidad valida." }');
            }

            if (!is_numeric($data->cantidadProcesadaDevuelta)) {
                die('{ "success" : false , "reason" : "No es una cantidad valida." }');
            }

            $descripcion = json_encode(array(
                'clave' => $args['action'],
                'descripcion' => 'Autorización de devolución',
                'producto_descripcion' => $data->descripcion,
                'id_venta' => $data->id_venta,
                'id_producto' => $data->id_producto,
                'cantidad' => $data->cantidadDevuelta,
                'cantidad_procesada' => $data->cantidadProcesadaDevuelta
                    ));

            solicitudDeAutorizacion($descripcion, "solicitudDeDevolucion");

            break;

        case 204://solicitud de autorizacion de precios especiales (gerente)

            if (!isset($args['id_cliente'])) {
                die('{ "success" : false , "reason" : "Faltan datos" }');
            }

            $descripcion = json_encode(array(
                'clave' => $args['action'],
                'descripcion' => 'Autorización de venta preferencial',
                'id_cliente' => $args['id_cliente'],
                'nombre' => $args['nombre']
                    ));

            solicitudDeAutorizacion($descripcion, "solicitudDeVentaPreferencial");

            break;

        case 206://ver autorizaciones pendientes de todas las sucursales (admin)

            autorizacionesPendientes();

            break;

        case 207://ver autorizaciones de su sucursal (gerente)

            $array = autorizacionesSucursal($_SESSION['sucursal']);
            
            $json = json_encode($array);

            if (isset($args['hashCheck'])) {
                //revisar hashes
                if (md5($json) == $args['hashCheck']) {
                    return;
                }
            }

            printf('{ "success": true, "hash" : "%s" , "payload": %s, "total" : "%s" }', md5($json), $json,count($array));

            break;

        case 208://responder autorizacion (admin)           

            responderAutorizacion($args);

            break;

        case 209://solicitud de uno o mas productos (gerente)         

            if (!isset($args['data'])) {
                die('{"success": false, "reason": "No hay parametros para ingresar." }');
            }

            try {
                $data = parseJSON($args['data']);
            } catch (Exception $e) {
                die('{"success": false, "reason": "Parametros invalidos." }');
            }

            $descripcion = json_encode(array(
                'clave' => 209,
                'descripcion' => 'Solicitud de producto',
                'productos' => $data
                    ));

            solicitudDeAutorizacion($descripcion, "solicitudDeProductos");

            break;

        case 211://surtir producto (gerente)

            if (!(isset($args['id_autorizacion']))) {
                die('{"success" : false , "reason" : "Parametros invalidos" }');
            }

            surtirProducto($args['id_autorizacion']);

            break;

        case 213://detalle de autorizacion (admin)            

            detalleAutorizacion($args['id_autorizacion']);

            break;

        case 214://envio de productos a sucursal (admin)

            responderAutorizacionSolicitudProductos($args);

            break;

        case 219://cancelar autorizacion, cambiar a estado 5

            cancelarAutorizacion($args);

            break;

        case 220://finalizar venta prefereicial, cambiar al estado 6

            finalizarVentaPreferencial($args);

            break;
    }
}
