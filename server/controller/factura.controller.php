<?php

require_once("model/ventas.dao.php");
require_once("model/detalle_venta.dao.php");
require_once("model/cliente.dao.php");
require_once("model/factura_venta.dao.php");
require_once('model/sucursal.dao.php');
require_once("model/inventario.dao.php");
require_once("model/pos_config.dao.php");
require_once("pos.com/comprobante.php");
require_once("logger.php");

/**
 * Verifica que la venta que se decea facturar exista, este sin facturar
 * y que los datos criticos del cliente esten correctamente establecidos.
 *
 * @param Array $args
 * returns JSON
 */
function verificarDatosVenta($args) {

    Logger::log("Iniciando proceso de validacion de datos para realizar factura electronica");

    //verificamos que el cliente este definido
    if (!isset($args['id_cliente'])) {
        Logger::log("El cliente no esta definido");
        die('{"success": false, "reason": "El cliente no esta definido" }');
    }

    //verificamos que la venta este definida
    if (!isset($args['id_venta'])) {
        Logger::log("La venta no se ha definido");
        die('{"success": false, "reason": "La venta no se ha definido" }');
    }

    //verificamos que id_venta sea numerico
    if (is_nan($args['id_venta'])) {
        Logger::log("El id de la venta no es un numero");
        die('{"success": false, "reason": "Error el parametro de venta, no es un numero" }');
    }

    //verificamos que id_cliente sea numerico
    if (is_nan($args['id_cliente'])) {
        Logger::log("El id del cliente no es un numero");
        die('{"success": false, "reason": "Error el parametro de cliente, no es un numero" }');
    }

    //verificamos que la venta exista
    if (!( $venta = VentasDAO::getByPK($args['id_venta']) )) {
        Logger::log("No se tiene registro de la venta : {$args['id_venta']}");
        die('{"success": false, "reason": "No se tiene registro de la venta ' . $args['id_venta'] . '." }');
    }

    //verificamos que el cliente exista
    if (!( $cliente = ClienteDAO::getByPK($args['id_cliente']) )) {
        Logger::log("No se tiene registro del cliente : {$args['id_cliente']}.");
        die('{"success": false, "reason": "No se tiene registro del cliente ' . $args['id_cliente'] . '." }');
    }

    //verificamos que el cliente este acivo
    if ($cliente->getActivo != "1") {
        Logger::log("El cliente  : {$args['id_cliente']} no se encuentra activo.");
        die('{"success": false, "reason": "El cliente ' . $args['id_cliente'] . ' no se encuentra activo." }');
    }

    //verifiacmos que la venta este liquidada
    if ($venta->getLiquidada() != "1") {
        Logger::log("La venta {$venta->getIdVenta()} no esta lioquidada");
        die('{"success": false, "reason": "No se puede emitir la factura debido a que la venta ' . $venta->getIdVenta() . ' no ha sido liquidada." }');
    }

    //verificamos que la venta no este facturada
    $fv = new FacturaVenta();
    $fv->setIdVenta($venta->getIdVenta());

    //obtiene un conjunto que contiene todas las facturas venta
    $facturasVenta = FacturaVentaDAO::search($fv);

    //almacenara la factura para la venta $fv si es que la encuentra
    $facturaVenta = null;

    //revisa si encontro una factura para la venra $fv en el confjunto de todas las facuras
    foreach ($facturasVenta as $factura) {
        if ($factura->getEstado() == "1") {
            $facturaVenta = $factura;
            break;
        }
    }

    //si la encontro entonces termina el proceso
    if ($facturaVenta != null) {
        //la factura de la venta esta activa
        Logger::log("La venta {$facturaVenta->getIdVenta()} ha sido facturada con el folio {$facturaVenta->getFolio()}y esta la factura activa");
        die('{"success": false, "reason": "No se puede emitir la factura debido a que la venta ' . $args['id_venta'] . ' ha sido facturada con el folio : ' . $facturaVenta->getFolio() . ' y esta activa." }');
    }

    //verificamos que el cliente tenga correctamente definidos todos sus parametros
    //razon soclial
    if (strlen($cliente->getRazonSocial()) <= 10) {
        Logger::log("La razon social del cliente es demaciado corta.");
        die('{"success": false, "reason": "Actualice la informacion del cliente : La razon social del cliente es demaciado corta." }');
    }

    //rfc
    if (strlen($cliente->getRfc()) < 13 || strlen($cliente->getRfc()) > 13) {
        Logger::log("verifique la estructura del rfc : 4 caracteres para el nombre, 6 para la fecha y 3 para la homoclave");
        die('{"success": false, "reason": "Actualice la informacion del cliente : Verifique la estructura del rfc : 4 caracteres para el nombre, 6 para la fecha y 3 para la homoclave." }');
    }
    //calle
    if (strlen($cliente->getCalle()) < 3) {
        Logger::log("La descripcion de la calle es demaciado corta.");
        die('{"success": false, "reason": "Actualice la informacion del cliente : La descripcion de la calle es demaciado corta." }');
    }
    //numero exterior
    if (strlen($cliente->getNumeroExterior()) == 0) {
        Logger::log("Indique el numero exterior del domicilio");
        die('{"success": false, "reason": "Actualice la informacion del cliente : Indique el numero exterior del domicilio." }');
    }
    //colonia
    if (strlen($cliente->getColonia()) < 3) {
        Logger::log("La descripcion de la colonia es demaciado corta.");
        die('{"success": false, "reason": "Actualice la informacion del cliente : La descripcion de la colonia es demaciado corta." }');
    }
    //municipio
    if (strlen($cliente->getMunicipio()) < 3) {
        Logger::log("La descripcion del municipio es demaciado corta.");
        die('{"success": false, "reason": "Actualice la informacion del cliente : La descripcion del municipio es demaciado corta." }');
    }
    //estado
    if (strlen($cliente->getEstado()) < 3) {
        Logger::log("La descripcion del estado es demaciado corta.");
        die('{"success": false, "reason": "Actualice la informacion del cliente : La descripcion del estado es demaciado corta." }');
    }
    //codigo postal
    if (strlen($cliente->getCodigoPostal()) == 0) {
        Logger::log("Indique el codigo postal.");
        die('{"success": false, "reason": "Actualice la informacion del cliente : Indique el codigo postal." }');
    }

    Logger::log("Terminado proceso de validacion de datos para realizar factura electronica");
}

/**
 * Realiza todas las validaciones pertinentes para crear con esito la factura electronica
 * ademas realiza una peticion al webservice para emitir una nueva factura
 */
function generaFactura($args) {

    Logger::log("Iniciando proceso de facturacion");

    //verifica que los datos de la venta y el cliente esten correctos
    verificarDatosVenta($args);

    DAO::transBegin();

    $comprobante = new Comprobante();

    $comprobante->setGenerales(getDatosGenerales($args)); //1

    $comprobante->setConceptos(getConceptos($args)); //2

    $comprobante->setEmisor(getEmisor()); //3

    $comprobante->setExpedidoPor(getExpedidoPor()); //4

    $comprobante->setLlaves(getLlaves()); //5

    $comprobantes->setReceptor(getReceptor()); //6

    $success = $comprobante->isValid();

    if (!$success->getSuccess()) {
        Logger::log($success->getInfo());
        DAO::transRollback();
        die('{"success": false, "reason": "' . $success->getInfo() . '" }');
    }

    $xml_response = $comprobante->getNuevaFactura();      

    if($xml_response != null){
        echo $xml_response;
    }else{
        echo "Fallo";
    }

    echo $xml_response;

    //genera un json con todos los datos necesarios para generar el PDF de la factura electronica
    //$json_factura = parseFacturaToJSON($xml_response);
    //llamar al metodo que genera el pdf

    Logger::log("Terminando proceso de facturacion");
}

/**
 * Regresa un objeto qeu contiene la informacion acerca de los conceptos de la venta
 * return Object Conceptos
 */
function getConceptos($args) {

    //obtenemos el juego de articulos vendidos en la venta
    $detalle_venta = new DetalleVenta();
    $detalle_venta->setIdVenta($args['id_venta']);
    $detalle_venta = DetalleVentaDAO::search($detalle_venta);

    //objeto que almacenara a todos los conceptos de la venta
    $conceptos = new Conceptos();

    //llenamos a $conceptos con los conceptos de la venta
    foreach ($detalle_venta as $articulo) {

        //verificamos que el producto este registrado en el inventario
        if (!($inventario = InventarioDAO::getByPK($articulo->getIdProducto()))) {
            Logger::log("el producto : {$id_producto} no se tiene registrado en el inventario");
            DAO::transRollback();
            die('{"success": false, "reason": "el producto : ' . $id_producto . ' no se tiene registrado en el inventario" }');
        }

        $concepto = new Concepto();
        $concepto->setIdProducto($$inventario->getIdProducto());
        $concepto->setDescripcion($inventario->getDescripcion());
        $concepto->setUnidad($inventario->getEscala());

        /*
         * TODO: Por lo que veo como se esta manejando lo de cantidad procesada y sin procesar debemos de
         * manejarlo de forma diferente para otros puntos de venta ya que no esta bien asi como se esta manejando.
         */

        /*
         * ahora tenemos que verificar si en esta venta especificamente de este producto se vendio producto
         * procesado y sin procesar ya que si se vendio de los dos, se tienen que agregar 2 conceptos al
         * array de conceptos
         */

        if ($articulo->getCantidad() > 0) {

            $concepto->setCantidad($articulo->getCantidad());
            $concepto->setValor($articulo->getPrecio());
            $concepto->setImporte($articulo->getCantidad() * $articulo->getPrecio());

            $conceptos->addConcepto($concepto);
        }

        if ($articulo->getCantidadProcesada() > 0) {

            $concepto->setCantidad($articulo->getCantidadProcesada());
            $concepto->setValor($articulo->getPrecioProcesada());
            $concepto->setImporte($articulo->getCantidadProcesada() * $articulo->getPrecioProcesada());

            $conceptos->addConcepto($concepto);
        }
    }

    return $conceptos;
}

/**
 * Regresa un objeto cargado con los datos del receptor de la factura
 * return Object Receptor
 */
function getReceptor() {

    if (!($cliente = ClienteDAO::getByPK('id_cliente') )) {
        Logger::log("Error al obtener datos del cliente.");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al obtener datos del cliente." }');
    }

    $receptor = new Receptor();

    $receptor->setRazonSocial($cliente->getRazonSocial());

    $receptor->setRFC($cliente->getRfc());

    $receptor->setCalle($cliente->getCalle());

    $receptor->setNumeroExterior($cliente->getNumeroExterior());

    if ($cliente->getNumeroInterior() != "") {
        $emisor->setNumeroInterior($cliente->getNumeroInterior());
    }

    if ($cliente->getReferencia() != "") {
        $receptor->setReferencia($cliente->getReferencia());
    }

    $receptor->setColonia($cliente->getColonia());

    if ($cliente->getLocalidad() != "") {
        $receptor->setNumeroInterior($cliente->getLocalidad());
    }

    $receptor->setMunicipio($cliente->getMunicipio());

    $receptor->setEstado($cliente->getEstado());

    $receptor->setPais($cliente->getPais());

    $receptor->setCodigoPostal($cliente->getCodigoPostal());

    $success = $receptor->isValid();

    if ($success->getSuccess()) {
        return $receptor;
    } else {
        Logger::log("Error : {$success->getInfo()}");
        DAO::transRollback();
        die('{"success": false, "reason": "' . $success->getInfo() . '" }');
    }
}

/**
 * Regresa un objeto cargado con las llaves para generar la factura
 * return Object Llaves
 */
function getLlaves() {

    if (!($pos_config_privada = PosConfigDAO::getByPK('llave_privada') )) {
        Logger::log("Error al obtener datos de la llave privada.");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al obtener datos de la llave privada." }');
    }

    if (!($pos_config_publica = PosConfigDAO::getByPK('llave_publica') )) {
        Logger::log("Error al obtener datos de la llave publica.");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al obtener datos de la llave publica." }');
    }

    if (!($pos_config_certificado = PosConfigDAO::getByPK('noCertificado') )) {
        Logger::log("Error al obtener datos del numero de certificado.");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al obtener datos del numero de certificado." }');
    }

    $llaves = new Llaves();

    $llaves->setPrivada($pos_config_privada->getValue());

    $llaves->setPublica($pos_config_publica->getValue());

    $llaves->setNoCertificado($pos_config_certificado->getValue());

    $success = $llaves->isValid();

    if ($success->getSuccess()) {
        return $llaves;
    } else {
        Logger::log("Error : {$success->getInfo()}");
        DAO::transRollback();
        die('{"success": false, "reason": "' . $success->getInfo() . '" }');
    }
}

/**
 * Regresa un objeto cargado con los datos del emisor
 * 
 * returns Object Emisor
 */
function getEmisor() {

    if (!($pos_config = PosConfigDAO::getByPK('emisor') )) {
        Logger::log("Error al obtener datos del emisor.");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al obtener datos del emisor." }');
    }

    $json = json_decode($pos_config->getValue());

    $emisor = new Emisor();

    $emisor->setRazonSocial($json->nombre);

    $emisor->setRFC($json->rfc);

    $emisor->setCalle($json->calle);

    $emisor->setNumeroExterior($json->numeroExterior);

    if (isset($json->numeroInterior) && $json->numeroInterior != "") {
        $emisor->setNumeroInterior($json->numeroInterior);
    }

    if (isset($json->referencia) && $json->referencia != "") {
        $emisor->setReferencia($json->referencia);
    }

    $emisor->setColonia($json->colonia);

    if (isset($json->localidad) && $json->localidad != "") {
        $emisor->setNumeroInterior($json->localidad);
    }

    $emisor->setMunicipio($json->municipio);

    $emisor->setEstado($json->estado);

    $emisor->setPais($json->pais);

    $emisor->setCodigoPostal($json->codigoPostal);

    $success = $emisor->isValid();

    if ($success->getSuccess()) {
        return $emisor;
    } else {
        Logger::log("Error : {$success->getInfo()}");
        DAO::transRollback();
        die('{"success": false, "reason": "' . $success->getInfo() . '" }');
    }
}

function getExpedidoPor() {

    if (!($sucursal = SucursalDAO::getByPK($_SESSION['sucursal']) )) {
        Logger::log("Error al obtener datos de la sucursal.");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al obtener datos de la sucursal." }');
    }

    $expedidoPor = new ExpedidoPor();

    $expedidoPor->setRazonSocial($sucursal->getRazonSocial());

    $expedidoPor->setRFC($sucursal->getRfc());

    $expedidoPor->setCalle($sucursal->getCalle());

    $expedidoPor->setNumeroExterior($sucursal->getNumeroExterior());

    if (isset($sucursal->getNumeroInterior()) && $sucursal->getNumeroInterior() != "") {
        $expedidoPor->setNumeroInterior($sucursal->getNumeroInterior());
    }

    if (isset($sucursal->getReferencia()) && $$sucursal->getReferencia() != "") {
        $expedidoPor->setReferencia($sucursal->getReferencia());
    }

    $expedidoPor->setColonia($sucursal->getColonia());

    if (isset($sucursal->getLocalidad()) && $sucursal->getLocalidad() != "") {
        $expedidoPor->setNumeroInterior($sucursal->getLocalidad());
    }

    $expedidoPor->setMunicipio($sucursal->getMunicipio());

    $expedidoPor->setEstado($sucursal->getEstado());

    $expedidoPor->setPais($sucursal->getPais());

    $expedidoPor->setCodigoPostal($sucursal->getCodigoPostal());

    $success = $expedidoPor->isValid();

    if ($success->getSuccess()) {
        return $expedidoPor;
    } else {
        Logger::log("Error : {$success->getInfo()}");
        DAO::transRollback();
        die('{"success": false, "reason": "' . $success->getInfo() . '" }');
    }
}

function getDatosGenerales($args) {

    //generamos una factura en la BD

    $facturaBD = creaFacturaBD($args['id_venta']);

    $venta = VentasDAO::getByPK($args['id_venta']);

    $sucursal = SucursalDAO::getByPK($_SESSION['sucursal']);

    $generales = new Generales();

    $generales->setFecha(date("d/m/y") . "T" . date("H:i:s"));

    $generales->setFolioInterno($facturaBD->getIdFolio());

    //TODO : Hacer algo para los tipos de pago
    $generales->setFormaDePago("pago en una sola exibicion");

    //TODO : Verificar a fondo como implementar mas impuestos
    $generales->setIva("0");

    //TODO : Verificar que onda con los tipos de pago, solo hay credito y contado?
    $generales->setMetodoDePago($venta->getTipoPago());

    $generales->setSerie($sucursal->getLetrasFactura());

    $generales->setSubtotal($venta->getSubtotal());

    $generales->setTotal($venta->getTotal());

    $success = $generales->isValid();

    if ($success->getSuccess()) {
        return $generales;
    } else {
        Logger::log("Error : {$success->getInfo()}");
        DAO::transRollback();
        die('{"success": false, "reason": "' . $success->getInfo() . '" }');
    }
}



/**
 * Extrae la informacion acerca de las llaves para solicitar
 */
function getInformacionConfiguracion() {

    $llaves = new stdClass();


    if (!( $llaves->privada = PosConfigDAO::getByPK('llave_privada')->getValue() )) {
        Logger::log("Error al obtener la llave privada");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al obtener la llave privada" }');
    }

    if (!( $llaves->publica = PosConfigDAO::getByPK('llave_publica')->getValue() )) {
        Logger::log("Error al obtener la llave publica");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al obtener la llave privada" }');
    }

    if (!( $llaves->noCertificado = PosConfigDAO::getByPK('noCertificado')->getValue() )) {
        Logger::log("Error al obtener el nuemro de certificado");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al obtener el numero de certificado" }');
    }

    return $llaves;
}

/**
 * crea una factura en la BD y regresa una objeto con los tados de la factura realizada
 */
function creaFacturaBD($id_venta) {

    Logger::log("Iniciando proceso de creacion de factura en la BD");

    $factura = new FacturaVenta();

    $factura->setIdVenta($id_venta);
    $factura->setIdUsuario($_SESSION['userid']);

    try {
        FacturaVentaDAO::save($factura);
    } catch (Exception $e) {
        Logger::log("Error al salvar la factura de la venta : {$e}");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al salvar la factura de la venta intente nuevamente" }');
    }

    Logger::log("Terminado proceso de creacion de factura en la BD");

    return $factura;
}


/**
 * cancela una factura
 */
function cancelaFactura($args) {
    return printf('{"success" : true}');
}

/**
 * Reimprime una factura
 */
function reimprimirFactura($args) {

    //verificamos que el folio de la factura se haya especificado
    if (!isset($args['id_folio'])) {
        Logger::log("No se indico el id del folio de factura qeu se decea reimprimir");
        die('{"success": false, "reason": "No se indico el folio de la factura que se desea reimprimir." }');
    }

    //validamos que el id del folio sea nuemrico
    if (is_nan($args['id_folio'])) {
        Logger::log("Verifique el id del folio de la factura");
        die('{"success": false, "reason": "Verifique el id del folio de la factura." }');
    }

    //verificamos que exista ese folio
    if (!( $factura = FacturaVentaDAO::getByPK($args['id_folio']) )) {
        Logger::log("No se tiene registro del folio : {$args['id_folio']}.");
        die('{"success": false, "reason": "No se tiene registro del folio : ' . $args['id_folio'] . '." }');
    }

    //verificamos que la factura este activa
    if ($factura->getActiva() != "1") {
        Logger::log("La factura : {$args['id_folio']} no se puede reimprimir debido a que ha sido cancelada.");
        die('{"success": false, "reason": "La factura ' . $args['id_folio'] . ' no se puede imprimir debido a que ha sido cancelada." }');
    }

    $json = parseFacturaToJSON($args['id_folio']);

    //llamada a la funcion que reimprime la factura
}

/**
 *
 */
if (isset($args['action'])) {
    switch ($args['action']) {
        case 1200:
            //realiza una peticion al web service para que regrese una factura sellada
            generaFactura($args);
            break;

        case 1201:
            //cancela una factura
            cancelaFactura($args);
            break;

        case 1202 :
            //reimpresion de factura
            reimprimirFactura($args);
            break;
    }
}
?>
