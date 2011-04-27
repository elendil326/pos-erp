<?php

require_once('model/impresora.dao.php');
require_once('model/impresiones.dao.php');
require_once('model/documento.dao.php');
require_once('model/pos_config.dao.php');
require_once('model/sucursal.dao.php');
require_once('logger.php');

/**
 * Obtiene informacion acerca de los documentos y con que impresoras se deben de imprimir
 * y los regresa en forma de arreglo.
 * returns array documentos 
 */
function listarDocumentos() {

    Logger::log("Listando Documentos.");

    //obtenemos todos los documentos
    $documentos = ImpresionesDAO::getAll();

    $array_docs = array();

    foreach ($documentos as $documento) {

        $imp = ImpresoraDAO::getByPK($documento->getIdImpresora());
        $doc = DocumentoDAO::getByPK($documento->getIdDocumento());

        $doc_printer = new stdClass();

        $doc_printer->impresora = $imp->getIdentificador();
        $doc_printer->documento = $doc->getIdentificador();

        array_push($array_docs, $doc_printer);
    }

    return $array_docs;
}

function leerLeyendasTicket() {

    Logger::log("Costruyendo Leyendas Ticket.");

    $leyendasTicket = new stdClass();

    $pos_config = PosConfigDAO::getByPK('leyendasTicket');

    if ($pos_config == null) {
        Logger::log("Error al leer 'leyendasTicket' en la BD, verificar la tabla pos_config");
        die('{"success": false, "reason": "Verifique la configuracion de las leyendas de los ticket." }');
    }

    $pos_config = json_decode($pos_config->getValue());

    if (!isset($_SESSION['sucursal'])) {
        Logger::log("Error : No se ha iniciado la sesion de la sucursal");
        die('{"success": false, "reason": "Error al iniciar la sesion en la sucursal." }');
    }

    $sucursal = SucursalDAO::getByPK($_SESSION['sucursal']);

    if ($sucursal == null) {
        Logger::log("Error : No se han obtenido los datos de la sucursal");
        die('{"success": false, "reason": "No se han podido acceder a los datos de la sucursal." }');
    }

    $leyendasTicket->cabeceraTicket = $pos_config->cabeceraTicket;
    $leyendasTicket->rfc = $sucursal->getRfc();
    $leyendasTicket->nombreEmpresa = $sucursal->getDescripcion();
    $leyendasTicket->direccion = $sucursal->getCalle() . " #" . $sucursal->getNumeroExterior() . " col. " . $sucursal->getColonia() . ", " . $sucursal->getMunicipio() . " " . $sucursal->getEstado();
    $leyendasTicket->telefono = $sucursal->getTelefono();
    $leyendasTicket->notaFiscal = $pos_config->notaFiscal;
    $leyendasTicket->cabeceraPagare = $pos_config->cabeceraPagare;
    $leyendasTicket->pagare = $pos_config->pagare;
    $leyendasTicket->contacto = $pos_config->contacto;
    $leyendasTicket->gracias = $pos_config->gracias;

    return $leyendasTicket;
}

if (isset($args['action'])) {

    switch ($args['action']) {

        case 1300:
            printf('{"success": true, "datos": %s}', json_encode(listarDocumentos()));
            break;

        case 1301:
            printf('{"success": true, "datos": %s}', json_encode(leerLeyendasTicket()));
            break;

        default:
            printf('{ "success" : "false" }');
            break;
    }
}
?>