<?php

/** Clientes Controller.
 *
 * Este archivo es la capa entre la interfaz de usuario y peticiones ajaxa y los
 * procedimientos para realizar las operaciones sobre Clientes.
 * @author Alan Gonzalez <alan@caffeina.mx>, Manuel Garcia Carmona <manuel@caffeina.mx>
 *
 */
require_once('model/cliente.dao.php');
require_once('model/ventas.dao.php');
require_once('model/inventario.dao.php');
require_once('model/pagos_venta.dao.php');
require_once('model/detalle_venta.dao.php');
require_once('model/factura_venta.dao.php');
require_once('model/sucursal.dao.php');
require_once('model/usuario.dao.php');
require_once('logger.php');

/**
 *
 * @param string $cadena rfc a valdar
 * @return type 
 */
function validaRFC($cadena) {

    /**
     * Morales: Se compone de 3 letras seguidas por 6 dígitos y 3 caracteres alfanumericos
     * Físicas: consta de 4 letras seguida por 6 dígitos y 3 caracteres alfanumericos
     * Para hacer una longitud de 12 y 13 caracteres, las primeras letras (3 y 4) pertenecen al nombre 
     * los siguientes 6 dígitos son la fecha de nacimiento o fecha de creación. 		
     * Para las morales, y los últimos 3 perteneces a la suma de valores pertenecientes al nombre.
     */
    //validamos al longitud de la cadena
    if (!(strlen($cadena) > 11 && strlen($cadena) < 14 )) {
        Logger::log("La longitud del RFC no es valida.");
        die('{"success": false, "reason": "La longitud del RFC no es valida." }');
    }

    //indicara la posicion en la cual se encuentra la cadena
    $i = 0;

    //verificamos si es una persona fisica y si es asi revisamos su primer digito
    if (strlen($cadena) == 12) {
        //es persona moral, entonces agregamos un relleno al principio de la cadena para dar una longitud igual a la de la persona fisica
        $cadena = "-" . $cadena;
    } else {
        //es persona fisica y verificamos si el primer caracter es una letra
        if (is_numeric($cadena[$i])) {

            Logger::log("Formato invalido del RFC del cliente, verifique si el " . ($i + 1) . "caracter es correcto");
            die('{"success": false, "reason": "Formato invalido del RFC del cliente, verifique si el ' . ($i + 1) . 'caracter es correcto" }');
        }
    }

    $i = 1;

    //revisamos los 3 caracteres que deberan de ir en el RFC (para personas fisicas son 4 pero ya anteriror mente revisamos el primero)
    for ($j = $i; $j <= 3; $j++) {

        $i = $j;

        if (is_numeric($cadena[$j])) {
            Logger::log("Formato invalido el RFC del cliente, verifique si el " . ($i + 1) . "caracter es correcto");
            die('{"success": false, "reason": "Formato invalido el RFC del cliente, verifique si el ' . ($i + 1) . 'caracter es correcto"}');
        }
    }

    //revisamos los 6 digitos
    for ($j = 4; $j <= 9; $j++) {

        $i = $j;

        if (!is_numeric($cadena[$j])) {
            Logger::log("Formato invalido el RFC del cliente, verifique si el " . ($i + 1) . " caracter es correcto");
            die('{"success": false, "reason": "Formato invalido el RFC del cliente, verifique si el ' . ($i + 1) . ' caracter es correcto"}');
        }
    }

    //revisamos los 3 caracteres alfanumericos que restan	
    for ($j = 10; $j <= 12; $j++) {

        $i = $j;

        if (!ctype_alnum($cadena[$j])) {
            Logger::log("Formato invalido el RFC del cliente, verifique si el " . ($i + 1) . " caracter es correcto");
            die('{"success": false, "reason": "Formato invalido el RFC del cliente, verifique si el ' . ($i + 1) . ' caracter es correcto"}');
        }
    }

}

/**
 * 	Crea un cliente.
 *
 * Este metodo intentara crear un cliente dado un arreglo de datos proporcionado.
 *
 * 	@static
 * @throws Exception si la operacion fallo.
 * @param Autorizacion [$autorizacion] El objeto de tipo Autorizacion
 * @return Un entero mayor o igual a cero denotando las filas afectadas, o un string con el error si es que hubo alguno.
 * */
function crearCliente($args) {

    if (!isset($args['data'])) {
        Logger::log("No hay parametros para ingresar nuevo cliente.");
        die('{ "success": false, "reason" : "Parametros invalidos" }');
    }

    $data = parseJSON($args['data']);

    if ($data == null) {
        Logger::log("Json invalido para crear cliente:" . $args['data']);
        die('{ "success": false, "reason" : "Parametros invalidos" }');
    }


    if (!( isset($data->rfc) &&
            isset($data->razon_social) &&
            isset($data->calle) &&
            isset($data->numero_exterior) &&
            isset($data->colonia) &&
            isset($data->municipio) &&
            isset($data->estado) &&
            isset($data->pais) &&
            isset($data->telefono) &&
            isset($data->codigo_postal) &&
            isset($data->descuento) &&
            isset($data->limite_credito)
            )) {
        Logger::log("Faltan parametros para crear cliente:" . $args['data']);
        die('{ "success": false, "reason" : "Faltan parametros." }');
    }

    //validamos el rfc
    validaRFC($data->rfc);           
    
    //crear el objeto de cliente a ingresar
    $cliente = new Cliente();
    
    $cliente->setGrantChanges(0);
    
    $cliente->setRfc(strtoupper($data->rfc));

    //buscar que no exista ya un cliente con este RFC
    if (count(ClienteDAO::search($cliente)) > 0) {
        Logger::log("RFC ya existe en clientes.");
        die('{"success": false, "reason": "Ya existe un cliente con este RFC." }');
    }



    if (strlen($data->razon_social) < 10) {
        Logger::log("Nombre muy corto para insertar cliente.");
        die('{"success": false, "reason": "El nombre del cliente es muy corto." }');
    }

    //un cliente puede no tener telefono
    /*
      if(strlen($data->telefono) < 5){
      Logger::log("Telefono muy corto para insertar cliente.");
      die ( '{"success": false, "reason": "El telefono del cliente es muy corto." }' );
      } */

    if ($data->descuento < 0) {
        Logger::log("Descuento negativo para el cliente.");
        die('{"success": false, "reason": "El descuento del cliente no puede ser negativo." }');
    }

    if ($data->descuento > POS_MAX_LIMITE_DESCUENTO) {
        Logger::log("Descuento mayor a " . POS_MAX_LIMITE_DESCUENTO . " para el cliente.");
        die('{"success": false, "reason": "El descuento del cliente no puede ser tan grande." }');
    }

    if (!is_numeric($data->limite_credito)) {
        die('{"success": false, "reason": "El Limite de credito debe ser un numero." }');
    }



    if ($data->limite_credito < 0) {
        Logger::log("Limite de credito negativo para el cliente.");
        die('{"success": false, "reason": "El Limite de credito del cliente no puede ser negativo." }');
    }

    if ($data->limite_credito > POS_MAX_LIMITE_DE_CREDITO) {
        Logger::log("Descuento mayor a " . POS_MAX_LIMITE_DESCUENTO . " para el cliente.");
        die('{"success": false, "reason": "El Limite de credito del cliente no puede ser tan grande." }');
    }

    $cliente->setRazonSocial(strtoupper($data->razon_social));

    $cliente->setCalle(strtoupper($data->calle));

    $cliente->setNumeroExterior($data->numero_exterior);

    if (isset($data->numero_interior))
        $cliente->setNumeroInterior($data->numero_interior);

    $cliente->setColonia($data->colonia);

    if (isset($data->referencia))
        $cliente->setReferencia(strtoupper($data->referencia));

    if (isset($data->localidad))
        $cliente->setLocalidad(strtoupper($data->localidad));

    $cliente->setMunicipio(strtoupper($data->municipio));

    $cliente->setEstado(strtoupper($data->estado));

    $cliente->setPais(strtoupper($data->pais));

    $cliente->setCodigoPostal($data->codigo_postal);

    $cliente->setLimiteCredito($data->limite_credito);

    $cliente->setDescuento($data->descuento);

    if (isset($data->telefono))
        $cliente->setTelefono($data->telefono);

    if (isset($data->e_mail))
        $cliente->setEMail(strtoupper($data->e_mail));

    $cliente->setActivo(1);

    $cliente->setIdUsuario($_SESSION['userid']);


    //si esta peticion viene de un administrador, usar los
    // datos que vienen en el request, de lo contrario
    // utilizar los datos que estan en la sesion
    if ($_SESSION['grupo'] <= 1) {
        if (isset($data->id_sucursal)) {
            $cliente->setIdSucursal($data->id_sucursal);
        } else {
            if (POS_MULTI_SUCURSAL) {
                //es multisucursal, y no envio la sucursal a la
                //que pertenece
                die('{"success": false, "reason": "No proporciono a que sucursal pertenece este nuevo cliente." }');
            } else {
                //no importa que no haya enviado sucursal
                $cliente->setIdSucursal(0);
            }
        }
    } else {
        $cliente->setIdSucursal($_SESSION['sucursal']);
    }

    try {
        ClienteDAO::save($cliente);
    } catch (Exception $e) {
        Logger::log("Error al guardar el nuevo cliente:" . $e);
        die('{"success": false, "reason": "Error al guardar el nuevo cliente." }');
    }

    printf('{"success": true, "id": "%s"}', $cliente->getIdCliente());
    Logger::log("Cliente creado !");
}

/*
 * 
 * 
 * 
 * */

function listarClientes() {

    $cliente = new Cliente();
    $cliente->setActivo(1);

    $clientes = ClienteDAO::search($cliente);
    $clientesDeudores = listarClientesDeudores();

    $clientesArray = array();
    for ($c = 0; $c < sizeof($clientes); $c++) {

        if ($clientes[$c]->getIdCliente() < 0)
            continue;

        $asArray = $clientes[$c]->asArray();

        $found = false;

        //buscar si este es un cliente deudor
        for ($cd = 0; $cd < sizeof($clientesDeudores); $cd++) {

            if ($clientesDeudores[$cd]["id_cliente"] == $asArray["id_cliente"]) {

                $asArray["credito_restante"] = $clientesDeudores[$cd]["credito_restante"];
                $found = true;
                break;
            }
        }


        if (!$found) {
            //si no lo encontre en deudores, entonces su credito restante es igual a su limite de credito
            $asArray["credito_restante"] = $asArray["limite_credito"];
        }

        array_push($clientesArray, $asArray);
    }

    return $clientesArray;
}

function modificarCliente($args) {

    if (!isset($args['data'])) {
        Logger::log("No hay parametros para editar cliente.");
        die('{"success": false, "reason": "Parametros invalidos." }');
    }


    $data = parseJSON($args['data']);

    if ($data == null) {
        Logger::log("Json invalido para modificar cliente: " . $args['data']);
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    //minimo debio haber mandado el id_cliente
    if (!isset($data->id_cliente)) {
        Logger::log("Json invalido para modificar cliente: " . $args['data']);
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    //crear el objeto de cliente a ingresar
    $cliente = ClienteDAO::getByPK($data->id_cliente);


    if (!$cliente) {
        Logger::log("No existe el cliente " . $data->id_cliente);
        die('{"success": false, "reason": "Este cliente no existe." }');
    }

    if (isset($data->rfc)){
        //validamos el rfc
        validaRFC($data->rfc);    
        $cliente->setRfc(strtoupper($data->rfc));
    }

    if (isset($data->razon_social))
        $cliente->setRazonSocial(strtoupper($data->razon_social));

    if (isset($data->calle))
        $cliente->setCalle(strtoupper($data->calle));

    if (isset($data->numero_exterior))
        $cliente->setNumeroExterior($data->numero_exterior);

    if (isset($data->numero_interior))
        $cliente->setNumeroInterior($data->numero_interior);

    if (isset($data->colonia))
        $cliente->setColonia(strtoupper($data->colonia));

    if (isset($data->referencia))
        $cliente->setReferencia(strtoupper($data->referencia));

    if (isset($data->localidad))
        $cliente->setLocalidad(strtoupper($data->localidad));

    if (isset($data->municipio))
        $cliente->setMunicipio(strtoupper($data->municipio));

    if (isset($data->estado))
        $cliente->setEstado(strtoupper($data->estado));

    if (isset($data->pais))
        $cliente->setPais(strtoupper($data->pais));

    if (isset($data->codigo_postal))
        $cliente->setCodigoPostal($data->codigo_postal);

    if (isset($data->telefono))
        $cliente->setTelefono($data->telefono);

    if (isset($data->e_mail))
        $cliente->setEMail(strtoupper($data->e_mail));



    if (isset($data->limite_credito)) {
        //validar limite de credito

        if ($data->limite_credito < 0) {
            Logger::log("Intentando ingresar limite de credito negativo");
            die('{"success": false, "reason": "El limite de credito no puede ser negativo." }');
        }

        if ($data->limite_credito >= POS_MAX_LIMITE_DE_CREDITO && $_SESSION['grupo'] == 2) {

            Logger::log("gerente intentando asignar limite de credito mayor a " . POS_MAX_LIMITE_DE_CREDITO);

            $max = POS_MAX_LIMITE_DE_CREDITO;
            die('{"success": false, "reason": "Si desea asignar un limite de credito mayor a ' . $max . ' debera pedir una autorizacion."  }');
        }

        $cliente->setLimiteCredito($data->limite_credito);
    }


    if (isset($data->descuento)) {

        if ($data->descuento > POS_MAX_LIMITE_DESCUENTO) {
            $max = POS_MAX_LIMITE_DESCUENTO;
            Logger::log("intentando asignar descuento mayor a " . $max);
            die('{"success": false, "reason": "No se puede asignar un descuento mayor al ' . $max . '%."  }');
        }

        $cliente->setDescuento($data->descuento);
    }


    if (isset($data->activo))
        $cliente->setActivo($data->activo);


    //solo el admin puede editar estos campos
    if ($_SESSION['grupo'] <= 1) {
        if (isset($data->id_sucursal))
            $cliente->setIdSucursal($data->id_sucursal);

        if (isset($data->id_usuario))
            $cliente->setIdUsuario($data->id_usuario);
    }

    try {
        ClienteDAO::save($cliente);
    } catch (Exception $e) {

        Logger::log("Error al guardar modificacion del cliente " . $e);
        die('{"success": false, "reason": "Error. Porfavor intente de nuevo." }');
    }

    printf('{"success": true, "id": "%s"}', $cliente->getIdCliente());
    Logger::log("Cliente " . $cliente->getIdCliente() . " modificado !");
}

/* * *
 * Esta funcion debe ser erradicada por el bien del performance 
 * */

function listarVentasClientes() {
    Logger::log("#################################################################");
    Logger::log("# Esta funcion debe ser deprecada por el bien del performance ! #");
    Logger::log("#################################################################");

    $ventas = VentasDAO::getAll();
    $tot_ventas = array();

    foreach ($ventas as $venta) {

        $decode_venta = $venta->asArray();

        $dventa = new DetalleVenta();
        $dventa->setIdVenta($venta->getIdVenta());

        //obtenemos el detalle de la venta
        $detalles_venta = DetalleVentaDAO::search($dventa);

        $array_detalle = array(); //guarda los detalles de las ventas

        foreach ($detalles_venta as $detalle_venta) {
            $detalle = parseJSON($detalle_venta);
            $descripcion = InventarioDAO::getByPK($detalle_venta->getIdProducto());
            $detalle->descripcion = $descripcion->getDescripcion();

            array_push($array_detalle, $detalle);
        }

        $decode_venta["detalle_venta"] = $array_detalle;

        $suc = SucursalDAO::getByPK($venta->getIdSucursal());
        $decode_venta['sucursal'] = $suc->getDescripcion();

        $cajero = UsuarioDAO::getByPK($venta->getIdUsuario());
        $decode_venta['cajero'] = $cajero->getNombre();

        array_push($tot_ventas, $decode_venta);
    }

    Logger::log("Listando ventas de clientes");
    return $tot_ventas;
}

//lista las ventas de un cliente en especidico (puede ser de contado o a credito si se especifica)
function listarVentaCliente($id_cliente, $tipo_venta = null) {


    if (!isset($id_cliente)) {
        return null;
    }

    $cC = array();

    $ventas = new Ventas();
    $ventas->setIdCliente($id_cliente);

    if (isset($tipo_venta)) {
        $ventas->setTipoVenta($tipo_venta);
    }

    $comprasCliente = VentasDAO::search($ventas);


    foreach ($comprasCliente as $c) {
        //make readable data

        $sucursal = SucursalDAO::getByPK($c->getIdSucursal());
        $cajero = UsuarioDAO::getByPK($c->getIdUsuario());

        $data = array(
            "cajero" => $cajero ? $cajero->getNombre() : "<b>Error</b>",
            "sucursal" => $sucursal ? $sucursal->getDescripcion() : "<b>Error</b>",
            "descuento" => $c->getDescuento(),
            "fecha" => $c->getFecha(),
            "id_cliente" => $c->getIdCliente(),
            "id_sucursal" => $c->getIdSucursal(),
            "id_usuario" => $c->getIdUsuario(),
            "id_venta" => $c->getIdVenta(),
            "ip" => $c->getIp(),
            "iva" => $c->getIva(),
            "pagado" => $c->getPagado(),
            "subtotal" => $c->getSubtotal(),
            "tipo_venta" => $c->getTipoVenta(),
            "total" => $c->getTotal(),
            "saldo" => $c->getTotal() - $c->getPagado(),
            "liquidada" => $c->getLiquidada()
        );



        array_push($cC, $data);
    }

    return $cC;
}

/*
 *  @TODO: Estea debe llamarse abonarAVenta !!
 *
 * */

function abonarVenta($args) {

    if (!isset($args['data'])) {
        Logger::log("No hay parametros para abonar a la compra");
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    $data = parseJSON($args['data']);


    if (!isset($data->id_venta)) {
        Logger::log("No se envio un id_venta para abonar");
        die('{"success": false, "reason": "No se envio un id_venta para abonar." }');
    }

    if (!isset($data->monto)) {
        Logger::log("No se envio un monto para abonar");
        die('{"success": false, "reason": "No se envio un monto para abonar." }');
    }

    if (!( is_numeric($data->monto) )) {
        Logger::log("El monto a abonar debe ser un valor numerico.");
        die('{"success": false, "reason": "El monto debe ser un valor numerico." }');
    }


    if ($data->monto < 0) {
        Logger::log("El monto a abonar no puede ser negativo");
        die('{"success": false, "reason": "No puede abonar un monto negativo." }');
    }

    if (!isset($data->tipo_pago)) {
        Logger::log("No se envio el tipo de pago");
        die('{"success": false, "reason": "No se envio el tipo de pago."}');
    }

    if (!( $venta = VentasDAOBase::getByPK($data->id_venta) )) {
        Logger::log("No se tiene registro de la venta : " . $data->id_venta);
        die('{"success": false, "reason": "No se tiene registro de la venta ' . $data->id_venta . '." }');
    }

    if ($venta->getLiquidada() != 0) {
        Logger::log("La venta : " . $data->id_venta . " esta liquidada.");
        die('{"success": false, "reason": "La venta : ' . $data->id_venta . ' esta liquidada. "}');
    }

    //si pago mas de lo que debo
    $saldo = $venta->getTotal() - $venta->getPagado();

    if ($data->monto > $saldo) {
        $data->monto = $saldo;
    }

    $pagosVenta = new PagosVenta();
    $pagosVenta->setIdVenta($data->id_venta);
    $pagosVenta->setIdSucursal($_SESSION['sucursal']);
    $pagosVenta->setIdUsuario($_SESSION['userid']);
    $pagosVenta->setMonto($data->monto);

    switch ($data->tipo_pago) {
        case "efectivo" :
            $pagosVenta->setTipoPago($data->tipo_pago);
            break;
        case "cheque" :
            $pagosVenta->setTipoPago($data->tipo_pago);
            break;
        case "tarjeta" :
            $pagosVenta->setTipoPago($data->tipo_pago);
            break;
        default:
            Logger::log("El tipo de pago no es compatible : {$data->tipo_pago}");
            die('{"success": false, "reason": "Parametros invalidos." }');
    }

    DAO::transBegin();

    try {
        PagosVentaDAO::save($pagosVenta);
        //ya que se ingreso modificamos lo pagado a al venta
        $venta->setPagado($venta->getPagado() + $data->monto);

        if ($venta->getPagado() >= $venta->getTotal()) {
            $venta->setLiquidada(1);
        }

        VentasDAOBase::save($venta);
    } catch (Exception $e) {
        Logger::log("Error al intentar guardar el abono : " . $e);
        DAO::transRollback();
        die('{"success": false, "reason": "Error, porfavor intente de nuevo." }');
    }

    $empleado = UsuarioDAO::getByPK($_SESSION['userid']);

    DAO::transEnd();
    Logger::log("Abono exitoso a la venta " . $data->id_venta);
    printf('{ "success": "true", "empleado" : "%s" }', $empleado->getNombre());
}

function listarClientesDeudores() {

    $deudores = ClienteDAO::obtenerClientesDeudores();

    for ($i = 0; $i < sizeof($deudores); $i++) {
        $deudores[$i]["credito_restante"] = $deudores[$i]["limite_credito"] - $deudores[$i]["saldo"];
    }
    
    return $deudores;
}

function facturarVenta($args) {

    if (!isset($args['id_venta'])) {
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    $venta = VentasDAOBase::getByPK($args['id_venta']);

    //verificamos que la venta exista
    if ($venta == null) {
        die('{"success": false, "reason": "No se encontro registro de la venta ' . $args['id_venta'] . '." }');
    }

    if ($venta->getPagado() != $venta->getTotal()) {
        die('{"success": false, "reason": "Esta venta no esta liquidada." }');
    }

    $factura = new FacturaVenta();

    $factura->setIdVenta($args['id_venta']);

    try {

        if (FacturaVentaDAOBase::save($factura)) {
            echo sprintf('{"success": "true"}');
        } else {
            die('{"success": false, "reason": "No se pudo crear la factura." }');
        }
    } catch (Exception $e) {
        die('{"success": false, "reason": "' . $e . '" }');
    }

    Logger::log("Facturando venta");
}

function imprimirSaldo($args) {

    if (!isset($args['id_venta'])) {
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    $venta = VentasDAOBase::getByPK($args['id_venta']);

    //verificamos que la venta exista
    if ($venta == null) {
        die('{"success": false, "reason": "No se encontro registro de la venta ' . $args['id_venta'] . '." }');
    }

    $saldo = $venta->getTotal() - $venta->getPagado(); {
        printf('{ "success": true, "saldo": %s }', json_encode($saldo));
    }
}

/*
 * Lista los abonos de un cliente especifico y de ser necesario una venta especifica
 * */

function listarAbonos($cid, $vid = null) {

    $abonos = array();

    $ventas = new Ventas();
    $ventas->setIdCliente($cid);
    $ventas->setTipoVenta('credito');
    $comprasCliente = VentasDAO::search($ventas);

    foreach ($comprasCliente as $venta) {

        $pago = new PagosVenta();
        $pago->setIdVenta($venta->getIdVenta());

        $pagosVenta = PagosVentaDAO::search($pago);

        foreach ($pagosVenta as $p) {
            //make readable data

            $sucursal = SucursalDAO::getByPK($p->getIdSucursal());
            $cajero = UsuarioDAO::getByPK($p->getIdUsuario());


            if ($vid != null && $vid != $p->getIdVenta()
            )
                continue;

            $data = array(
                "cajero" => $cajero ? $cajero->getNombre() : "<b>Error</b>",
                "sucursal" => $sucursal ? $sucursal->getDescripcion() : "<b>Error</b>",
                "fecha" => $p->getFecha(),
                "id_pago" => $p->getIdPago(),
                "id_sucursal" => $p->getIdSucursal(),
                "id_usuario" => $p->getIdUsuario(),
                "id_venta" => $p->getIdVenta(),
                "monto" => $p->getMonto()
            );



            array_push($abonos, $data);
        }
    }

    return $abonos;
}

/* * *
 * lista las ventas de un cliente en especifico
 * */

function listarVentasCliente($args) {



    if (!isset($args['id_cliente'])) {
        Logger::log("Error al listar ventas del cliente, no se ha especificado un cliente.");
        die('{"success": false, "reason": "Error al listar ventas del cliente, no se ha especificado un cliente."}');
    }

    Logger::log("Listando ventas del cliente : {$args['id_cliente'] }.");

    $ventas = new Ventas();
    $ventas->setIdCliente($args['id_cliente']);
    $ventas->setLiquidada(0);

    $ventas = VentasDAO::search($ventas);

    $tot_ventas = array();

    foreach ($ventas as $venta) {

        $decode_venta = $venta->asArray();

        $dventa = new DetalleVenta();
        $dventa->setIdVenta($venta->getIdVenta());

        //obtenemos el detalle de la venta
        $detalles_venta = DetalleVentaDAO::search($dventa);

        $array_detalle = array(); //guarda los detalles de las ventas

        foreach ($detalles_venta as $detalle_venta) {
            $detalle = parseJSON($detalle_venta);
            $descripcion = InventarioDAO::getByPK($detalle_venta->getIdProducto());
            $detalle->descripcion = $descripcion->getDescripcion();

            array_push($array_detalle, $detalle);
        }

        $decode_venta["detalle_venta"] = $array_detalle;

        $suc = SucursalDAO::getByPK($venta->getIdSucursal());
        $decode_venta['sucursal'] = $suc->getDescripcion();

        $cajero = UsuarioDAO::getByPK($venta->getIdUsuario());
        $decode_venta['cajero'] = $cajero->getNombre();

        $cliente = ClienteDAO::getByPK($venta->getIdCliente());
        $decode_venta["razon_social"] = $cliente->getRazonSocial();

        //verificamos si la venta esta facturada
        $factura_venta = FacturaVentaDAO::search(new FacturaVenta(array("id_venta" => $venta->getIdVenta(), "activa" => 1, "sellada" => 1)));

        $array_facturas = array();

        foreach ($factura_venta as $fv) {
            $fv = $fv->asArray();
            $fv['lugar_emision'] = SucursalDAO::getByPK($fv['lugar_emision'])->getDescripcion();
            $fv['usuario'] = UsuarioDAO::getByPK($fv['id_usuario'])->getNombre();
            array_push($array_facturas, $fv);
        }

        $decode_venta["factura"] = count($array_facturas) > 0 ? $array_facturas : null;

        array_push($tot_ventas, $decode_venta);
    }

    Logger::log("Listando ventas del cliente  {$args['id_cliente']}, se encontraron " . count($tot_ventas) . " coincidencias.");
    return $tot_ventas;
}

/**
 * Calcula el estado de cuenta de un cliente en especifico
 * @param Array $args,  $args['id_cliente'=>12[,'tipo_venta'=> 'credito | contado | saldo'] ], por default obtiene todas las compras del cliente
 * $args['tipo_venta
 *  @return Object, propiedades : array_ventas (arreglo con informacion de las ventas), limite_credito (limite de credito del cliente), saldo (saldo por pagar de todas sus compras)
 */
function estadoCuentaCliente($args) {

    if (!isset($args['id_cliente'])) {
        Logger::log("Error al obtener el estado de cuenta, no se ha especificado un cliente.");
        die('{"success": false, "reason": "Error al obtener el estado de cuenta, no se ha especificado un cliente."}');
    }

    if (!$cliente = ClienteDAO::getByPK($args['id_cliente'])) {
        Logger::log("Error al obtener el estado de cuenta, no se tiene registro del cliente {$args['id_cliente']}.");
        die('{"success": false, "reason": "Error al obtener el estado de cuenta, no se tiene registro del cliente ' . $args['id_cliente'] . '."}');
    }

    Logger::log("Obteniendo estado de cuenta del cliente : {$cliente->getIdCliente() }.");

    $ventas = new Ventas();
    $ventas->setIdCliente($args['id_cliente']);

    if (isset($args['tipo_venta'])) {

        switch ($args['tipo_venta']) {
            case 'credito':
                //obtiene todas las compras a credito
                $ventas->setTipoVenta("credito");
                break;
            case 'contado':
                //obtiene todas las compras de contado
                $ventas->setTipoVenta("contado");
                break;
            case 'saldo':
                //obtiene todas las compras a credito sin saldar
                $ventas->setLiquidada(0);
                break;
            default:
        }
    }

    $ventas = VentasDAO::search($ventas);

    $array_ventas = array();

    foreach ($ventas as $venta) {

        $decode_venta = $venta->asArray();

        $array_venta['id_venta'] = $venta->getIdVenta();

        $array_venta['fecha'] = $venta->getFecha();

        $sucursal = SucursalDAO::getByPK($venta->getIdSucursal());
        $array_venta['sucursal'] = $sucursal->getDescripcion();

        $cajero = UsuarioDAO::getByPK($venta->getIdUsuario());
        $array_venta['cajero'] = $cajero->getNombre();

        $array_venta['cancelada'] = $venta->getCancelada();

        $array_venta['tipo_venta'] = $venta->getTipoVenta();

        $array_venta['tipo_pago'] = $venta->getTipoPago();

        $array_venta['total'] = $venta->getTotal();

        $array_venta['pagado'] = $venta->getPagado();

        $array_venta['saldo'] = $venta->getTotal() - $venta->getPagado();

        array_push($array_ventas, $array_venta);
    }

    //calculamos el saldo total
    $saldo = 0;
    $ventas = new Ventas();
    $ventas->setIdCliente($args['id_cliente']);
    foreach (VentasDAO::search($ventas) as $venta) {
        if ($venta->getLiquidada() != "1") {
            $saldo += $venta->getTotal() - $venta->getPagado();
        }
    }


    $estado_cuenta = new StdClass();
    $estado_cuenta->array_ventas = $array_ventas;
    $estado_cuenta->limite_credito = $cliente->getLimiteCredito();
    $estado_cuenta->saldo = $saldo;


    Logger::log("Estado de cuenta del cliente  {$args['id_cliente']}, se encontraron " . count($estado_cuenta->array_ventas) . " coincidencias.");
    return $estado_cuenta;
}

/*
 * 
 * 	Case dispatching for proxy
 * 
 * */
if (isset($args['action'])) {
    switch ($args['action']) {
        case 300:
            //lista todos los clientes
            $json = json_encode(listarClientes());

            if (isset($args['hashCheck'])) {
                //revisar hashes
                if (md5($json) == $args['hashCheck']) {
                    return;
                }
            }

            printf('{ "success": true, "hash" : "%s" , "datos": %s }', md5($json), $json);
            break;

        case 301:
            //crea un nuevo cliente
            if ($_SESSION['grupo'] > 2) {
                die('{ "success": false, "reason": "No tiene privilegios para hacer esto." }');
            }

            crearCliente($args);
            break;

        case 302:
            //edita un cliente
            if ($_SESSION['grupo'] > 2) {
                die('{ "success": false, "reason": "No tiene privilegios para hacer esto." }');
            }
            modificarCliente($args);
            break;

        /*
          case 303:
          //lista las ventas de un cliente en especidico (puede ser de contado o a credito si se especifica)
          printf('{ "success": true, "datos": %s }',  json_encode( listarVentasCliente( $args['id_cliente'], $args['tipo_venta']) ));
          break;
         */

        case 304:
            //lista todas las ventas
            $json = json_encode(listarVentasClientes());

            if (isset($args['hashCheck'])) {
                //revisar hashes
                if (md5($json) == $args['hashCheck']) {
                    return;
                }
            }

            printf('{ "success": true, "hash" : "%s" , "datos": %s }', md5($json), $json);
            break;

        case 305:
            //agrega un pago a una venta
            abonarVenta($args);
            break;

        case 306:
            //clientes deudores
            printf('{ "success": true, "datos": [%s] }', json_encode(listarClientesDeudores()));
            break;

        case 307:
            //factura una venta
            facturarVenta($args);
            break;

        case 308:
            //imprime el saldo de una venta a credito
            imprimirSaldo($args);
            break;

        case 309:
            //lista todas las ventas de un cliente en especifico
            printf('{ "success": true, "datos": %s }', json_encode(listarVentasCliente($args)));
            break;

        case 310:
            //lista el estado de cuenta de los clientes
            $estado_cuenta = estadoCuentaCliente($args);
            printf('{ "success": true, "total": ' . count($estado_cuenta->array_ventas) . ', "datos": %s }', json_encode($estado_cuenta));
            break;
    }
}
