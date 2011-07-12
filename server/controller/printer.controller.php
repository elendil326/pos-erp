<?php

require_once('model/impresora.dao.php');
require_once('model/impresiones.dao.php');
require_once('model/documento.dao.php');
require_once('model/pos_config.dao.php');
require_once('model/sucursal.dao.php');
require_once("model/ventas.dao.php");
require_once("model/cliente.dao.php");
require_once("model/factura_venta.dao.php");
require_once("controller/ventas.controller.php");
require_once("model/pos_config.dao.php");
require_once("admin/includes/static.php");
require_once("clientes.controller.php");
require_once("librerias/CNumeroaLetra.php");

function puntos_cm($medida, $resolucion=72) {
    //// 2.54 cm / pulgada
    //se supone ke la pagina mide 29.7 cm por 21.0 cm
    //$medida += 29.7;
    return ( $medida / (2.54) ) * $resolucion;
}

function readableText($bar) {
    $foo = explode(" ", $bar);
    $end = "";
    foreach ($foo as $i) {
        $end .= ucfirst(strtolower(trim($i))) . " ";
    }

    return trim($end);
}

function formatAddress($d) {

    if ($d instanceof stdClass) {
        $e = "";
        $e .= readableText($d->calle) . " " . $d->numeroExterior;
        if (isset($d->numeroInterior))
            $e .= "\n" . readableText($d->numeroInterior);
        $e .= "\n";

        $e .= " " . readableText($d->colonia);
        if (strlen($d->codigoPostal) > 0) {
            $e .= " C.P. " . $d->codigoPostal . "\n";
        }

        if (strlen($d->municipio) > 0) {
            $e .= readableText($d->municipio) . ", ";
        }

        if (strlen($d->estado) > 0) {
            $e .= readableText($d->estado) . ", ";
        }

        $e .= readableText($d->pais) . "\n";
    } else {

        $e = "";
        $e .= readableText($d->getCalle()) . " " . $d->getNumeroExterior();
        if ($d->getNumeroInterior() != null)
            $e .= "\n" . readableText($d->getNumeroInterior());

        //$e .= " " . readableText($d->getColonia()) . " C.P. " . $d->getCodigoPostal() . "\n";
        $e .= " " . readableText($d->getColonia());
        if (strlen($d->getCodigoPostal()) > 0) {
            $e .= " C.P. " . $d->getCodigoPostal() . "\n";
        }

        if (strlen($d->getMunicipio()) > 0) {
            $e .= readableText($d->getMunicipio()) . ", ";
        }

        if (strlen($d->getEstado()) > 0) {
            $e .= readableText($d->getEstado()) . ", ";
        }

        $e .= readableText($d->getPais()) . "\n";
    }

    return $e;
}

function roundRect($pdf, $x, $y, $w, $h) {

    $pdf->setStrokeColor(0.3359375, 0.578125, 0.89453125);
    $pdf->setLineStyle(1);

    $x -= puntos_cm(.1);
    $y -= 2;

    $pdf->line($x + 2, $y, $x + $w - 2, $y); //arriba
    $pdf->line($x, $y - 2, $x, $y - $h + 2);  //izquierda
    $pdf->line($x + 2, $y - $h, $x + $w - 2, $y - $h); //abajo
    $pdf->line($x + $w, $y - 2, $x + $w, $y - $h + 2); //derecha		

    $pdf->partEllipse($x + 3, $y - 3, 90, 180, 3); //top-left
    $pdf->partEllipse($x + $w - 3, $y - 3, 0, 90, 3); //top-right
    $pdf->partEllipse($x + $w - 3, $y - $h + 3, 360, 240, 3); //bottom-right
    $pdf->partEllipse($x + 3, $y - $h + 3, 180, 270, 3); //bottom-left
}

function imprimirFactura($id_venta, $venta_especial = null) {


    if ($id_venta == null) {
        $venta = $venta_especial;
    } else {
        $venta = VentasDAO::getByPK($id_venta);
    }


    if (!$venta) {
        die("Esta venta no existe");
    }



    /**
     * Si soy un cliente, validar que esta venta sea mia !
     * 
     * 
     * 
     * * */
    if ($_SESSION["grupo"] == 4) {
        /**
         * Si soy un cliente  !
         *
         * * */
        if ($_SESSION["cliente_id"] != $venta->getIdCliente()) {
            /**
             * Esta venta no me pertenece  !
             *
             * * */
            Logger::log("*******************************************************");
            Logger::log("!!!!! CLIENTE HA SOLICITADO VENTA QUE NO ES DE EL !!!!!");
            Logger::log("CLIENTE:" . $_SESSION["cliente_id"]);
            Logger::log("*******************************************************");
            die("ACCESSO NO AUTORIZADO: INCIDENTE REPORTADO.");
        }
    }




    //validar que la venta sea a contado, o bien que este saldada
    if (!$venta->getLiquidada()) {
        die("Esta venta no ha sido liquidada, no se puede imprimir factura.");
    }


    //validar que el cliente tenga todos los datos necesarios
    $cliente = ClienteDAO::getByPK($venta->getIdCliente());

    if (!$cliente) {
        die("El cliente de esta venta no existe.");
    }


    //buscar los detalles de la factura de venta
    $factura_q = new FacturaVenta();
    $factura_q->setIdVenta($venta->getIdVenta());

    $facturas_poll = FacturaVentaDAO::search($factura_q);

    if (sizeof($facturas_poll) != 1) {
        Logger::log("Los datos de esta factura estan incompletos o hay mas de un detalle de factura");
        die("Los datos de esta factura estan incompletos o hay mas de un detalle de factura");
    }

    $factura = $facturas_poll[0];

    $detalle_de_venta = detalleVenta($venta->getIdVenta());
    $productos = $detalle_de_venta["items"];
    $detalle_de_venta = $detalle_de_venta["detalles"];

    //buscar los datos del emisor
    $conf = new PosConfig();
    $conf->setOpcion("emisor");

    $results = PosConfigDAO::search($conf);
    if (sizeof($results) != 1) {
        Logger::log("no encuentro los datos del numero de certificado");
        die("no encuentro los datos del emisor");
    }

    $emisor = json_decode($results[0]->getValue())->emisor;

    //buscar los datos del no de certificado
    $conf = new PosConfig();
    $conf->setOpcion("noCertificado");

    $results = PosConfigDAO::search($conf);
    if (sizeof($results) != 1) {
        Logger::log("no encuentro los datos del numero de certificado");
        die("no encuentro los datos del numero de certificado");
    }

    $serie_cert_contribuyente = $results[0]->getValue();

    $sucursal = SucursalDAO::getByPK($venta->getIdSucursal());

    $qr_file_name = obternerQRCode($emisor->rfc, $cliente->getRFC(), $venta->getTotal(), $factura->getFolioFiscal());

    include_once('librerias/ezpdf/class.pdf.php');
    include_once('librerias/ezpdf/class.ezpdf.php');

    $pdf = new Cezpdf();

    $pdf->selectFont('../server/librerias/ezpdf/fonts/Helvetica.afm');

    //margenes de un centimetro para toda la pagina
    $pdf->ezSetMargins(1, 1, 1, 1);

    /*
     * LOGO
     */

    if (!$logo = PosConfigDAO::getByPK('url_logo')) {
        Logger::log("Verifique la configuracion del pos_config, no se encontro el camṕo 'url_logo'");
        die("Verifique la configuracion del POS, no se encontro el url del logo");
    }

    //addJpegFromFile(imgFileName,x,y,w,[h])
    //$pdf->addJpegFromFile($logo->getValue(), puntos_cm(2), puntos_cm(25.5), puntos_cm(3.5));

    if (substr($logo->getValue(), -3) == "jpg" || substr($logo->getValue(), -3) == "JPG" || substr($logo->getValue(), -4) == "jpeg" || substr($logo->getValue(), -4) == "JPEG") {
        $pdf->addJpegFromFile($logo->getValue(), puntos_cm(2), puntos_cm(25.5), puntos_cm(3.5));
    } elseif (substr($logo->getValue(), -3) == "png" || substr($logo->getValue(), -3) == "PNG") {
        $pdf->addPngFromFile($logo->getValue(), puntos_cm(2), puntos_cm(25.5), puntos_cm(3.5));
    } else {
        Logger::log("Verifique la configuracion del pos_config, la extension de la imagen del logo no es compatible");
        die("La extension de la imagen usada para el logo del negocio no es valida.");
    }


    /*     * ************************
     * ENCABEZADO
     * ************************* */


    /*     * ************************
     * TITULO
     * Datos del emisor, lugar de expedicion, folio, fecha de emision, no de serie
     * del certificado del contribuyente
     * ************************* */
    $e = "<b>" . readableText($emisor->nombre) . "</b>\n";
    $e .= formatAddress($emisor);
    $e .= "RFC: " . $emisor->rfc . "\n\n";

    //datos de la sucursal
    //$s = "<b>Lugar de expedicion</b>\n";
    //$s .= formatAddress($sucursal);
    $e .= "<b>Lugar de expedicion</b>\n";
    if ($sucursal->getIdSucursal() != '0') {
        $e .= formatAddress($sucursal);
    }


    $datos = array(
        array(
            "emisor" => $e/* ,
          'sucursal' => $s, */
        )
    );

    //$pdf->ezSetY(puntos_cm(26.7));
    $pdf->ezSetY(puntos_cm(28.7));
    $opciones_tabla = array();
    $opciones_tabla['showLines'] = 0;
    $opciones_tabla['showHeadings'] = 0;
    $opciones_tabla['shaded'] = 0;
    $opciones_tabla['fontSize'] = 8;
    //$opciones_tabla['xOrientation'] = 'right';
    $opciones_tabla['xOrientation'] = 'right';
    //$opciones_tabla['xPos'] = puntos_cm(3);
    $opciones_tabla['xPos'] = puntos_cm(7.5);
    $opciones_tabla['width'] = puntos_cm(11);
    $opciones_tabla['textCol'] = array(0, 0, 0);
    $opciones_tabla['titleFontSize'] = 12;
    $opciones_tabla['rowGap'] = 3;
    $opciones_tabla['colGap'] = 3;

    $pdf->ezTable($datos, "", "", $opciones_tabla);

    $datos = array(
        array("col" => "<b>Folio</b>"),
        array("col" => $factura->getIdFolio()),
        array("col" => "<b>Fecha y hora de Emision</b>"),
        array("col" => $factura->getFechaEmision()),
        array("col" => "<b>Numero de serie del certificado del contribuyente</b>"),
        array("col" => $serie_cert_contribuyente)
    );

    $pdf->ezSetY(puntos_cm(28.7));

    $opciones_tabla['xPos'] = puntos_cm(14.2);
    $opciones_tabla['width'] = puntos_cm(4);
    $opciones_tabla['showLines'] = 0;
    $opciones_tabla['shaded'] = 2;
    $opciones_tabla['shadeCol'] = array(1, 1, 1);
    $opciones_tabla['shadeCol2'] = array(0.8984375, 0.95703125, 0.99609375);
    $pdf->ezTable($datos, "", "", $opciones_tabla);

    roundRect($pdf, puntos_cm(14.2), puntos_cm(28.7), puntos_cm(4), puntos_cm(4));



    /*     * ************************
     * Receptor del comprobante fiscal
     * Datos del receptor
     * ************************* */
    $datos_receptor = readableText($cliente->getRazonSocial()) . "\n";
    $datos_receptor .= formatAddress($cliente);
    $datos_receptor .= "RFC:" . $cliente->getRfc();

    $receptor = array(
        array("receptor" => "<b>Receptor del comprobante fiscal</b>"),
        array("receptor" => $datos_receptor),
    );

    $pdf->ezSetY(puntos_cm(24.3));
    $opciones_tabla['xPos'] = puntos_cm(2);
    $opciones_tabla['width'] = puntos_cm(8.2);
    $opciones_tabla['showLines'] = 0;
    $pdf->ezTable($receptor, "", "", $opciones_tabla);




    /*     * ************************
     * 	Timbrado
     * ************************* */
    $pdf->ezSetY(puntos_cm(24.3));
    $opciones_tabla['xPos'] = puntos_cm(10.4);
    $opciones_tabla['width'] = puntos_cm(7.8);
    $timbre = array(
        array("dat" => "<b>Folio Fiscal</b>"),
        array("dat" => $factura->getFolioFiscal()),
        array("dat" => "<b>Fecha y hora de certificacion</b>"),
        array("dat" => $factura->getFechaCertificacion()),
        array("dat" => "<b>Numero de serie del certificaco del sat</b>"),
        array("dat" => $factura->getNumeroCertificadoSat())
    );

    $pdf->ezTable($timbre, "", "", $opciones_tabla);

    roundRect($pdf, puntos_cm(2), puntos_cm(24.3), puntos_cm(16.2), puntos_cm(3.2));



    /*     * ************************
     * Tipo de comprobante
     * ************************* */
    $pdf->ezSetY(puntos_cm(20.9));
    $opciones_tabla['xPos'] = puntos_cm(2);
    $opciones_tabla['width'] = puntos_cm(16.2);
    $comprobante = array(
        array("r1" => "Tipo de comprobante",
            "r2" => "Moneda",
            "r3" => "Tipo de cambio",
            "r4" => "<b>Version</b>"),
        array("r1" => readableText($factura->getTipoComprobante()),
            "r2" => "MXN",
            "r3" => "1.0",
            "r4" => "3.0"),
    );
    $pdf->ezTable($comprobante, "", "", $opciones_tabla);

    roundRect($pdf, puntos_cm(2), puntos_cm(20.9), puntos_cm(16.2), puntos_cm(1.2));

    /*     * ************************
     * PRODUCTOS
     * ************************* */
    $elementos = array(
        array('cantidad' => 'Cantidad',
            'descripcion' => 'Descripcion                                                                                                     ', 'precio' => 'Precio', 'importe' => 'Importe'),
    );


    foreach ($productos as $p) {
        if ($p["cantidadProc"] > 0) {

            $prod['cantidad'] = $p["cantidadProc"];
            $prod['descripcion'] = $p["descripcion"] . " PROCESADA";
            $prod['precio'] = moneyFormat($p["precioProc"], DONT_USE_HTML);
            $prod['importe'] = moneyFormat($p["precioProc"] * $p["cantidadProc"], DONT_USE_HTML);

            array_push($elementos, $prod);
        }

        if ($p["cantidad"] > 0) {
            $prod['cantidad'] = $p["cantidad"];
            $prod['descripcion'] = $p["descripcion"];
            $prod['precio'] = moneyFormat($p["precio"], DONT_USE_HTML);
            $prod['importe'] = moneyFormat($p["precio"] * $p["cantidad"], DONT_USE_HTML);

            array_push($elementos, $prod);
        }
    }



    array_push($elementos, array("cantidad" => "",
        "descripcion" => "",
        "precio" => "Subtotal",
        "importe" => moneyFormat($venta->getSubTotal(), DONT_USE_HTML)));

    array_push($elementos, array("cantidad" => "",
        "descripcion" => "",
        "precio" => "Descuento",
        "importe" => moneyFormat($venta->getDescuento(), DONT_USE_HTML)));

    array_push($elementos, array("cantidad" => "",
        "descripcion" => "",
        "precio" => "IVA",
        "importe" => moneyFormat($venta->getIVA(), DONT_USE_HTML)));

    array_push($elementos, array("cantidad" => "",
        "descripcion" => "",
        "precio" => "Total",
        "importe" => moneyFormat($venta->getTotal(), DONT_USE_HTML)));


    $pdf->ezText("", 10, array('justification' => 'center'));
    $pdf->ezTable($elementos, "", "", $opciones_tabla);

    $pdf->addJpegFromFile("../static_content/qr_codes/" . $qr_file_name, 30, 30, 150);

    roundRect($pdf, puntos_cm(2), puntos_cm(19.7 - .25), puntos_cm(16.2), puntos_cm(13.2));

    /*     * ************************
     * Tipo de pago
     * ************************* */
    $pdf->ezText("\nPago en una sola exibicion", 9, array('justification' => 'center'));

    /*     * ************************
     * DATOS DE SELLOS
     * ************************* */



    $sellos = array(
        array("r1" => "Sello digital del emisor:"),
        array("r1" => $factura->getSelloDigitalEmisor()),
        array("r1" => "Sello digital del SAT:"),
        array("r1" => $factura->getSelloDigitalSat()),
        array("r1" => "Cadena original del complemento de certificacion digital del sat:"),
        array("r1" => $factura->getCadenaOriginal())
    );

    $pdf->ezSetY(puntos_cm(6.0));
    $opciones_tabla['xPos'] = puntos_cm(6);
    $opciones_tabla['width'] = puntos_cm(12.4);
    $opciones_tabla['fontSize'] = 6;
    $opciones_tabla['showLines'] = 0;
    $pdf->ezTable($sellos, "", "", $opciones_tabla);



    /*     * ************************
     * notas de abajo
     * ************************* */
    $pdf->setLineStyle(1);
    $pdf->setStrokeColor(0.3359375, 0.578125, 0.89453125);

    $pdf->line(puntos_cm(2), puntos_cm(1.3), puntos_cm(18.2), puntos_cm(1.3));


    $pdf->addText(puntos_cm(2), puntos_cm(1.0), 7, "Este documento es una representacion impresa de un CFDI");



    $pdf->addJpegFromFile("../www/media/logo_simbolo.jpg", puntos_cm(15.9), puntos_cm(.25), 25);


    $pdf->addText(puntos_cm(16.70), puntos_cm(.60), 8, "caffeina.mx");


    $pdf->setLineStyle(1, 'round', '', array(0, 2));
    $pdf->setStrokeColor(0.3359375, 0.578125, 0.89453125);

    $pdf->line(puntos_cm(8.2 + 2), puntos_cm(24.3 - .15), puntos_cm(8.2 + 2), puntos_cm(21.051));


    $documento_pdf = $pdf->ezOutput(1);

    $pdf->ezStream();

    //ok ya la hize, var si existe este documento en static content, sino, guardarlo
    if (!is_file("../static_content/facturas/" . $_SESSION["INSTANCE_ID"] . "_" . $venta->getIdVenta() . ".pdf"))
        file_put_contents("../static_content/facturas/" . $_SESSION["INSTANCE_ID"] . "_" . $venta->getIdVenta() . ".pdf", $documento_pdf);
}

/**
 *
 * @param type $id_venta
 * @param type $venta_especial 
 */
function imprimirFacturaXML($id_venta, $venta_especial = null) {

    //leemos el archivo del xml

    $archivo = '../static_content/facturas/' . $_SESSION["INSTANCE_ID"] . "_" . $id_venta . '.xml';
    
    $fp = @fopen($archivo, "r");

    if(!$fp){
        die("Error al leer el XML de la factura de la venta : {$id_venta}");
    }
    
    $xml = "";

    while (!feof($fp)) {
        $linea = fgets($fp);
        $lineasalto = nl2br($linea);
        $xml .= $lineasalto;
    }

    //quitamos toda la mierda que pone hacienda
    //TODO : Verificar si en Comprobante.php se elimina la mierda de Domicilio, creo qeu no.
    $xml = str_replace(array("cfdi:Comprobante", "cfdi:Emisor", "cfdi:Receptor", "cfdi:Domicilio", "cfdi:Conceptos", "cfdi:Concepto", "cfdi:Impuestos", "cfdi:Complemento", "cfdi:Traslados", "cfdi:Traslado", "tfd:TimbreFiscalDigital"), array("Comprobante", "Emisor", "Receptor", "Domicilio", "Conceptos", "Concepto", "Impuestos", "Complemento", "Traslados", "Traslado", "TimbreFiscalDigital"), $xml);
    ;

    $xml = new SimpleXMLElement($xml);

    //echo $xml->Complemento->TimbreFiscalDigital["UUID"];

    if ($fp != false) {
        fclose($fp);
    } else {
        die("Error al acceder al XML de la factura");
    }

    $qr_file_name = obternerQRCode($xml->Emisor['rfc'], $xml->Receptor['rfc'], $xml['total'], $xml->Complemento->TimbreFiscalDigital['UUID']);

    include_once('librerias/ezpdf/class.pdf.php');
    include_once('librerias/ezpdf/class.ezpdf.php');

    $pdf = new Cezpdf( $paper = 'letter');

    $pdf->selectFont('../server/librerias/ezpdf/fonts/Helvetica.afm');

    //margenes de un centimetro para toda la pagina
    $pdf->ezSetMargins(1, 1, 1, 1);

    /*
     * LOGO
     */

    if (!$logo = PosConfigDAO::getByPK('url_logo')) {
        Logger::log("Verifique la configuracion del pos_config, no se encontro el camṕo 'url_logo'");
        die("Verifique la configuracion del POS, no se encontro el url del logo");
    }

    //addJpegFromFile(imgFileName,x,y,w,[h])
    //$pdf->addJpegFromFile($logo->getValue(), puntos_cm(2), puntos_cm(25.5), puntos_cm(3.5));

    if (substr($logo->getValue(), -3) == "jpg" || substr($logo->getValue(), -3) == "JPG" || substr($logo->getValue(), -4) == "jpeg" || substr($logo->getValue(), -4) == "JPEG") {
        $pdf->addJpegFromFile($logo->getValue(), puntos_cm(2), puntos_cm(23.8), puntos_cm(3.5));
    } elseif (substr($logo->getValue(), -3) == "png" || substr($logo->getValue(), -3) == "PNG") {
        $pdf->addPngFromFile($logo->getValue(), puntos_cm(2), puntos_cm(23.8), puntos_cm(3.5));
    } else {
        Logger::log("Verifique la configuracion del pos_config, la extension de la imagen del logo no es compatible");
        die("La extension de la imagen usada para el logo del negocio no es valida.");
    }


    /*     * ************************
     * ENCABEZADO
     * ************************* */


    /*     * ************************
     * TITULO
     * Datos del emisor, lugar de expedicion, folio, fecha de emision, no de serie
     * del certificado del contribuyente
     * ************************* */

    $emisor_address = new stdClass();
    $emisor_address->calle = $xml->Emisor->DomicilioFiscal['calle'];
    $emisor_address->numeroExterior = $xml->Emisor->DomicilioFiscal['noExterior'];

    if (isset($xml->Emisor->DomicilioFiscal['noInterior'])) {
        $emisor_address->numeroInterior = $xml->Emisor->DomicilioFiscal['noInterior'];
    }

    $emisor_address->colonia = $xml->Emisor->DomicilioFiscal['colonia'];
    $emisor_address->codigoPostal = $xml->Emisor->DomicilioFiscal['codigoPostal'];
    $emisor_address->municipio = $xml->Emisor->DomicilioFiscal['municipio'];
    $emisor_address->estado = $xml->Emisor->DomicilioFiscal['estado'];
    $emisor_address->pais = $xml->Emisor->DomicilioFiscal['pais'];

    //TODO: Incluir la referencia en formatAddress()
    //$emisor_addres->referencia =$xml->Emisor->DomicilioFiscal['referencia'];


    $e = "<b>" . readableText($xml->Emisor['nombre']) . "</b>\n";
    $e .= formatAddress($emisor_address);
    $e .= "RFC: " . $xml->Emisor['rfc'] . "\n\n";

    //datos de la sucursal
    //$s = "<b>Lugar de expedicion</b>\n";
    //$s .= formatAddress($sucursal);
    //TODO : Tomar en cuenta la sucursal de emision

    $e .= "<b>Lugar de expedicion</b>\n";
    /* if ($sucursal->getIdSucursal() != '0') {
      $e .= formatAddress($sucursal);
      } */


    $datos = array(
        array(
            "emisor" => $e/* ,
          'sucursal' => $s, */
        )
    );

    $pdf->ezSetY(puntos_cm(27));
    $opciones_tabla = array();
    $opciones_tabla['showLines'] = 0;
    $opciones_tabla['showHeadings'] = 0;
    $opciones_tabla['shaded'] = 0;
    $opciones_tabla['fontSize'] = 8;
    $opciones_tabla['xOrientation'] = 'right';
    $opciones_tabla['xPos'] = puntos_cm(6);
    $opciones_tabla['width'] = puntos_cm(11);
    $opciones_tabla['textCol'] = array(0, 0, 0);
    $opciones_tabla['titleFontSize'] = 12;
    $opciones_tabla['rowGap'] = 3;
    $opciones_tabla['colGap'] = 3;

    $pdf->ezTable($datos, "", "", $opciones_tabla);

    $datos = array(
        array("col" => "<b>Factura</b>"),
        array("col" => $xml['folio']),
        array("col" => "<b>Fecha y hora de Emision</b>"),
        array("col" => str_replace(array("T"), array(" "), $xml['fecha'])),
        array("col" => "<b>Numero de serie del certificado del contribuyente</b>"),
        array("col" => $xml['noCertificado'])
    );

    $pdf->ezSetY(puntos_cm(27));

    $opciones_tabla['xPos'] = puntos_cm(14.2);
    $opciones_tabla['width'] = puntos_cm(4);
    $opciones_tabla['showLines'] = 0;
    $opciones_tabla['shaded'] = 2;
    $opciones_tabla['shadeCol'] = array(1, 1, 1);
    $opciones_tabla['shadeCol2'] = array(0.8984375, 0.95703125, 0.99609375);
    $pdf->ezTable($datos, "", "", $opciones_tabla);

    roundRect($pdf, puntos_cm(14.2), puntos_cm(27), puntos_cm(4), puntos_cm(4));



    /*     * ************************
     * Receptor del comprobante fiscal
     * Datos del receptor
     * ************************* */

    $receptor_address = new stdClass();
    $receptor_address->calle = $xml->Receptor->Domicilio['calle'];
    $receptor_address->numeroExterior = $xml->Receptor->Domicilio['noExterior'];

    if (isset($xml->Receptor->DomicilioFiscal['noInterior'])) {
        $receptor_address->numeroInterior = $xml->Receptor->Domicilio['noInterior'];
    }

    $receptor_address->colonia = $xml->Receptor->Domicilio['colonia'];
    $receptor_address->codigoPostal = $xml->Receptor->Domicilio['codigoPostal'];
    $receptor_address->municipio = $xml->Receptor->Domicilio['municipio'];
    $receptor_address->estado = $xml->Receptor->Domicilio['estado'];
    $receptor_address->pais = $xml->Receptor->Domicilio['pais'];

    $datos_receptor = readableText($xml->Receptor['nombre']) . "\n";
    $datos_receptor .= formatAddress($receptor_address);
    $datos_receptor .= "RFC:" . $xml->Receptor['rfc'];

    $receptor = array(
        array("receptor" => "<b>Receptor del comprobante fiscal</b>"),
        array("receptor" => $datos_receptor),
    );

    $pdf->ezSetY(puntos_cm(22.6));
    $opciones_tabla['xPos'] = puntos_cm(2);
    $opciones_tabla['width'] = puntos_cm(8.2);
    $opciones_tabla['showLines'] = 0;
    $pdf->ezTable($receptor, "", "", $opciones_tabla);




    /*     * ************************
     * 	Timbrado
     * ************************* */
    $pdf->ezSetY(puntos_cm(22.6));
    $opciones_tabla['xPos'] = puntos_cm(10.4);
    $opciones_tabla['width'] = puntos_cm(7.8);
    $timbre = array(
        array("dat" => "<b>Folio Fiscal</b>"),
        array("dat" => $xml->Complemento->TimbreFiscalDigital['UUID']),
        array("dat" => "<b>Fecha y hora de certificacion</b>"),
        array("dat" => str_replace(array("T"), array(" "), $xml->Complemento->TimbreFiscalDigital['FechaTimbrado'])),
        array("dat" => "<b>Numero de serie del certificaco del sat</b>"),
        array("dat" => $xml->Complemento->TimbreFiscalDigital['noCertificadoSAT'])
    );

    $pdf->ezTable($timbre, "", "", $opciones_tabla);

    roundRect($pdf, puntos_cm(2), puntos_cm(22.6), puntos_cm(16.2), puntos_cm(3.2));



    /*     * ************************
     * Tipo de comprobante
     * ************************* */
    $pdf->ezSetY(puntos_cm(19.2));
    $opciones_tabla['xPos'] = puntos_cm(2);
    $opciones_tabla['width'] = puntos_cm(16.2);
    $comprobante = array(
        array("r1" => "Tipo de comprobante",
            "r2" => "Moneda",
            "r3" => "Tipo de cambio",
            "r4" => "<b>Version</b>"),
        array("r1" => $xml['tipoDeComprobante'],
            "r2" => $xml['Moneda'],
            "r3" => $xml['TipoCambio'],
            "r4" => $xml['version']),
    );
    $pdf->ezTable($comprobante, "", "", $opciones_tabla);

    roundRect($pdf, puntos_cm(2), puntos_cm(19.2), puntos_cm(16.2), puntos_cm(1.2));

    /*     * ************************
     * PRODUCTOS
     * ************************* */
    $elementos = array(
        array('cantidad' => 'Cantidad',
            'unidad' => 'Unidad',
            'descripcion' => 'Descripcion                                                                                                     ', 'precio' => 'Precio', 'importe' => 'Importe'),
    );


    for ($i = 0; $i < count($xml->Conceptos->Concepto); $i++) {
        
        $prod['cantidad'] = $xml->Conceptos->Concepto[$i]["cantidad"];
        $prod['unidad'] = $xml->Conceptos->Concepto[$i]["unidad"];
        $prod['descripcion'] = $xml->Conceptos->Concepto[$i]["descripcion"];
        $prod['precio'] = moneyFormat($xml->Conceptos->Concepto[$i]["valorUnitario"], DONT_USE_HTML);
        $prod['importe'] = moneyFormat($xml->Conceptos->Concepto[$i]["importe"], DONT_USE_HTML);
        

        array_push($elementos, $prod);
    }




    array_push($elementos, array("cantidad" => "",
        "unidad"=>"",
        "descripcion" => "",
        "precio" => "Subtotal",
        "importe" => moneyFormat($xml["subTotal"], DONT_USE_HTML)));



    //TODO : El iva esta harcodeado, hay qeu revisar bien como se manejan los impuestos

    array_push($elementos, array("cantidad" => "",
        "unidad"=>"",
        "descripcion" => "",
        "precio" => "IVA",
        "importe" => moneyFormat(0, DONT_USE_HTML)));

    array_push($elementos, array("cantidad" => "",
        "unidad"=>"",
        "descripcion" => "",
        "precio" => "Total",
        "importe" => moneyFormat($xml["total"], DONT_USE_HTML)));


    $pdf->ezText("", 10, array('justification' => 'center'));
    $pdf->ezTable($elementos, "", "", $opciones_tabla);

    $pdf->addJpegFromFile("../static_content/qr_codes/" . $qr_file_name, puntos_cm(5.7), puntos_cm(1), puntos_cm(3));
    
    /*
     * CEDULA
     */

    if (!$cedula = PosConfigDAO::getByPK('url_cedula')) {
        Logger::log("Verifique la configuracion del pos_config, no se encontro el camṕo 'url_cedula'");
        die("Verifique la configuracion del POS, no se encontro el url de la cedula");
    }

    if (substr($cedula->getValue(), -3) == "jpg" || substr($cedula->getValue(), -3) == "JPG" || substr($cedula->getValue(), -4) == "jpeg" || substr($cedula->getValue(), -4) == "JPEG") {
        $pdf->addJpegFromFile($cedula->getValue(), puntos_cm(2), puntos_cm(1.4), puntos_cm(4));
    } elseif (substr($cedula->getValue(), -3) == "png" || substr($cedula->getValue(), -3) == "PNG") {
        $pdf->addPngFromFile($cedula->getValue(), puntos_cm(2), puntos_cm(1.4), puntos_cm(4));
    } else {
        Logger::log("Verifique la configuracion del pos_config, la extension de la imagen de la cedula no es compatible");
        die("La extension de la imagen usada para la cedula del negocio no es valida.");
    }

    roundRect($pdf, puntos_cm(2), puntos_cm(18 - .25), puntos_cm(16.2), puntos_cm(8.5));

    /*     * ************************
     * Tipo de pago
     * ************************* */
    $pdf->ezText("\nPago en una sola exibicion", 9, array('justification' => 'center'));

    /*     * ************************
     * DATOS DE SELLOS
     * ************************* */


    $cadena_original = "||" . $xml->Complemento->TimbreFiscalDigital["version"] . "|";
    $cadena_original .= $xml->Complemento->TimbreFiscalDigital["UUID"] . "|";
    $cadena_original .= str_replace(array(" "), array("T"), $xml->Complemento->TimbreFiscalDigital["FechaTimbrado"]) . "|";
    $cadena_original .= $xml->Complemento->TimbreFiscalDigital["selloCFD"] . "|";
    //$this->cadena_original .= $this->getSelloDigitalSAT() . "|";        
    $cadena_original .= $xml->Complemento->TimbreFiscalDigital["noCertificadoSAT"] . "||";


    $sellos = array(
        array("r1" => "Sello digital del emisor:"),
        array("r1" => $xml->Complemento->TimbreFiscalDigital["selloCFD"]),
        array("r1" => "Sello digital del SAT:"),
        array("r1" => $xml->Complemento->TimbreFiscalDigital["selloSAT"]),
        array("r1" => "Cadena original del complemento de certificacion digital del sat:"),
        array("r1" => $cadena_original)
    );

    $pdf->ezSetY(puntos_cm(5));
    $opciones_tabla['xPos'] = puntos_cm(8.4);
    $opciones_tabla['width'] = puntos_cm(9.8);
    $opciones_tabla['fontSize'] = 5;
    $opciones_tabla['showLines'] = 0;
    $pdf->ezTable($sellos, "", "", $opciones_tabla);

    
    /*     * ************************
     * pagare
     * ************************* */
    $mes = "";
    
    $fecha_emision = str_replace(array("T"), array(" "), $xml->Complemento->TimbreFiscalDigital['FechaTimbrado']);
    
    switch( date( "m", strtotime( $fecha_emision ) ) ){
        case 1 : 
            $mes = 'Enero';
            break;
        case 2 : 
            $mes = 'Febrero';
            break;
        case 3 : 
            $mes = 'Marzo';
            break;
        case 4 : 
            $mes = 'Abril';
            break;
        case 5 : 
            $mes = 'Mayo';
            break;
        case 6 : 
            $mes = 'Junio';
            break;
        case 7 : 
            $mes = 'Julio';
            break;
        case 8 : 
            $mes = 'Agosto';
            break;
        case 9 : 
            $mes = 'Septiembre';
            break;
        case 10 : 
            $mes = 'Octubre';
            break;
        case 11 : 
            $mes = 'Noviembre';
            break;
        case 12 : 
            $mes = 'Diciembre';
            break;
    }

    $en_letra = new CNumeroaletra();
    $en_letra->setNumero($xml["total"]);

    
    $pagare = "\n\nNo. _________                                                                                                                                                                                    En " . readableText($xml->Emisor->DomicilioFiscal['municipio']) . " a " . date("d", strtotime( $fecha_emision )) . " de " . $mes . " del " . date("Y", strtotime( $fecha_emision )) ."\n\n";
    $pagare .= " Debe(mos) y pagare(mos) incondicionalmente por este Pagaré a la orden de " . readableText($xml->Emisor['nombre']) . " en " . readableText($xml->Emisor->DomicilioFiscal['municipio']) . " " . readableText($xml->Emisor->DomicilioFiscal['estado']) . " ";
    $pagare .= "el __________________________  la cantidad de ";
    $pagare .= moneyFormat($xml["total"], DONT_USE_HTML) . " " . $en_letra->letra() . ". Valor recibido a mi"; 
    $pagare .= "(nuestra) entera satisfacción. Este pagaré forma parte de una serie numerada de 1 al 1 y todos estan sujetos a la condición de que, ";
    $pagare .= "al no pagarse cualquiera de ellos a su vencimiento, serán exigibles todos los que le sigan en numero, además de los ya vencidos, ";
    $pagare .= "desde la fecha de vencimiento de este documento hasta el día de su liquidacón, ";
    $pagare .= "causara intereses moratorios al tipo de 20% mensual, ";
    $pagare .= "pagadero en esta ciudad juntamente con el principal.";
  
    $receptor = array(
        array("receptor" => utf8_decode($pagare))
    ); 


    $pdf->addText(puntos_cm(2.1),puntos_cm(8.3),15,utf8_decode('<i>P a g a r é</i>'));
    $pdf->addText(puntos_cm(14),puntos_cm(8.3),8,utf8_decode('BUENO POR  ' . moneyFormat($xml["total"], DONT_USE_HTML)));    
    
    $pdf->addText(puntos_cm(8.4),puntos_cm(5.2),8,utf8_decode('<b>Acepto(amos)</b>'));
    $pdf->addText(puntos_cm(12.9),puntos_cm(5.2),8,utf8_decode('<b>Firma(s) _________________________</b>'));
    
    $pdf->setColor(0.419, 0.466, 0.443);
    
    $pdf->addText(puntos_cm(4),puntos_cm(5.5),6,utf8_decode('Nombre y datos del deudor'));
    $pdf->addText(puntos_cm(2),puntos_cm(5),6.5,utf8_decode('Nombre _____________________________________'));
    $pdf->addText(puntos_cm(2),puntos_cm(4.525),6.5,utf8_decode('Dirección ____________________________________'));
    $pdf->addText(puntos_cm(2),puntos_cm(4.05),6.5,utf8_decode('Población ____________________________________'));
   

    $pdf->ezSetY(puntos_cm(8.7));
    $opciones_tabla['xPos'] = puntos_cm(2);
    $opciones_tabla['width'] = puntos_cm(16.2);
    $opciones_tabla['shaded'] = 0;
    $opciones_tabla['showLines'] = 0;
    $opciones_tabla['fontSize'] = 6.2;
    $pdf->ezTable($receptor, "", "", $opciones_tabla);
    

    /*     * ************************
     * notas de abajo
     * ************************* */
    $pdf->setLineStyle(1);
    $pdf->setStrokeColor(0.3359375, 0.578125, 0.89453125);

    $pdf->line(puntos_cm(2), puntos_cm(1.3), puntos_cm(18.2), puntos_cm(1.3));


    $pdf->addText(puntos_cm(2), puntos_cm(1.0), 7, "Este documento es una representacion impresa de un CFDI");



    $pdf->addJpegFromFile("../www/media/logo_simbolo.jpg", puntos_cm(15.9), puntos_cm(.25), 25);


    $pdf->addText(puntos_cm(16.70), puntos_cm(.60), 8, "caffeina.mx");


    $pdf->setLineStyle(1, 'round', '', array(0, 2));
    $pdf->setStrokeColor(0.3359375, 0.578125, 0.89453125);

    $pdf->line(puntos_cm(8.2 + 2), puntos_cm(22.6 - .15), puntos_cm(8.2 + 2), puntos_cm(19.351));


    $documento_pdf = $pdf->ezOutput(1);

    $pdf->ezStream();

    //ok ya la hize, var si existe este documento en static content, sino, guardarlo
    if (!is_file("../static_content/facturas/" . $_SESSION["INSTANCE_ID"] . "_" . $id_venta . ".pdf"))
        file_put_contents("../static_content/facturas/" . $_SESSION["INSTANCE_ID"] . "_" . $id_venta . ".pdf", $documento_pdf);
}

/*
 * Código de barras bidimensional QR, con base al estándar ISO/IEC 18004:2000, conteniendo los siguientes datos en el siguiente formato:
  RFC del emisor
  RFC del receptor
  Total (a 6 decimales fijos)
  Identificador único del timbre (UUID) asignado
  Donde se manejarán 95 caracteres conformados de la siguiente manera:
  Prefijo	Datos	Caracteres
  re	RFC del Emisor, a 12/13 posiciones, precedido por el texto ”?re=”	17
  rr	RFC del Receptor, a 12/13 posiciones, precedido por el texto
  “&rr=”	17
  tt	Total del comprobante a 17 posiciones (10 para los enteros, 1 para carácter “.”, 6 para los decimales), precedido por el texto “&tt=”	21
  id	UUID del comprobante, precedido por el texto “&id=”	40
  95

  De esta manera se generan los datos válidos para realizar una consulta de un CFDI por medio de su expresión impresa.
  Ejemplo:
  ?re=XAXX010101000&rr=XAXX010101000&tt=1234567890.123456&id=ad662d33-6934-459c-a128-BDf0393f0f44
  ?re=GATJ740714F48&rr=CAAI6012142Q6&tt=0000010000.000000&id=5CA3BD64-0507-41E4-B6D4-1F629705ABF1
  https://chart.googleapis.com/chart?chs=500x500&cht=qr&chld=H|1&choe=UTF-8&chl=%3Fre%3DGATJ740714F48%26rr%3DCAAI6012142Q6%26tt%3D0000010000.000000%26id%3D5CA3BD64-0507-41E4-B6D4-1F629705ABF1
 */

function obternerQRCode($rfcEmisor = null, $rfcReceptor = null, $total = null, $uuid = null) {

    if (!$rfcEmisor || !$rfcReceptor || !$total || !$uuid) {
        Logger::log("Faltan datos para buscar el codigo qr. rfcEmisor : {$rfcEmisor}, rfcReceptor : {$rfcReceptor}, total : {$total}, uuid : {$uuid}");
        return null;
    }


    $file_name = $rfcEmisor . $rfcReceptor . $total . $uuid;
    $file_full_path = "../static_content/qr_codes/" . $file_name . ".jpg";

    if (is_file($file_full_path)) {
        Logger::log("Ya existe este codigo QR");
        return $file_name . ".jpg";
    }

    //el codigo no existe, sacarlo de google
    //primero tengo que ver si es posible que escriba en la carpeta de qr codes
    if (!is_writable("../static_content/qr_codes/")) {
        Logger::log("No puedo escribir en la carpeta de QRCODES !");
        return null;
    }


    Logger::log("Codigo QR no esta en static, sacarlo de google");

    $cadena = "?re=" . $rfcEmisor;
    $cadena .= "&rr=" . $rfcReceptor;

    $total_seis_decimales = sprintf("%10.6f", $total);
    Logger::log("AQUI FALTA SABER SI SON DIEZ CARATERES PARA EL TOTAL, O SOLO LOS NECESARIOS, O QUE PEDO?");

    $cadena .= "&tt=" . $total_seis_decimales;
    $cadena .= "&id=" . $uuid;

    $google_api_call = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chld=H|1&choe=UTF-8&chl=";

    $f = file_get_contents($google_api_call . urlencode($cadena));

    if ($f == null) {
        //volver a intentar
        Logger::log("FALLE AL OBTENER EL QR CODE DE GOOGLE, REINTENTANDO...", 3);
        obternerQRCode($rfcEmisor, $rfcReceptor, $total, $uuid);
    }

    file_put_contents($file_full_path, $f);

    //la imagen esta en png, hay que convertirla a jpg para que no haya pedos en el pdf
    $image = imagecreatefrompng($file_full_path);
    imagejpeg($image, "../static_content/qr_codes/" . $file_name . ".jpg", 100);
    imagedestroy($image);

    return $file_name . ".jpg";
}

function imprimirNotaDeVenta($id_venta) {

    $venta = VentasDAO::getByPK($id_venta);

    if (!$venta) {
        die("Esta venta no existe");
    }

    //validar que el cliente tenga todos los datos necesarios
    $cliente = ClienteDAO::getByPK($venta->getIdCliente());


    if (!$cliente) {
        die("El cliente de esta venta no existe.");
    }


    /**
     * Si soy un cliente, validar que esta venta sea mia !
     * 
     * 
     * 
     * * */
    if ($_SESSION["grupo"] == 4) {
        /**
         * Si soy un cliente  !
         *
         * * */
        if ($_SESSION["cliente_id"] != $venta->getIdCliente()) {
            /**
             * Esta venta no me pertenece  !
             *
             * * */
            Logger::log("*******************************************************");
            Logger::log("!!!!! CLIENTE HA SOLICITADO VENTA QUE NO ES DE EL !!!!!");
            Logger::log("CLIENTE:" . $_SESSION["cliente_id"]);
            Logger::log("*******************************************************");
            die("ACCESSO NO AUTORIZADO: INCIDENTE REPORTADO.");
        }
    }



    $detalle_de_venta = detalleVenta($venta->getIdVenta());
    $productos = $detalle_de_venta["items"];
    $detalle_de_venta = $detalle_de_venta["detalles"];

    //buscar los datos del emisor
    $conf = new PosConfig();
    $conf->setOpcion("emisor");

    $results = PosConfigDAO::search($conf);
    if (sizeof($results) != 1) {
        Logger::log("no encuentro los datos del numero de certificado");
        die("no encuentro los datos del emisor");
    }

    $emisor = json_decode($results[0]->getValue())->emisor;

    $sucursal = SucursalDAO::getByPK($venta->getIdSucursal());

    if (!$sucursal) {
        die("Sucursal invalida");
    }

    include_once('librerias/ezpdf/class.pdf.php');
    include_once('librerias/ezpdf/class.ezpdf.php');

    $pdf = new Cezpdf( $paper = 'letter');

    $pdf->selectFont('../server/librerias/ezpdf/fonts/Helvetica.afm');

    //margenes de un centimetro para toda la pagina
    $pdf->ezSetMargins(1, 1, 1, 1);
    
    
    /*
     * LOGO
     */

    if (!$logo = PosConfigDAO::getByPK('url_logo')) {
        Logger::log("Verifique la configuracion del pos_config, no se encontro el camṕo 'url_logo'");
        die("Verifique la configuracion del POS, no se encontro el url del logo");
    }
    
    
    if (substr($logo->getValue(), -3) == "jpg" || substr($logo->getValue(), -3) == "JPG" || substr($logo->getValue(), -4) == "jpeg" || substr($logo->getValue(), -4) == "JPEG") {
        $pdf->addJpegFromFile($logo->getValue(), puntos_cm(2), puntos_cm(23.8), puntos_cm(3.5));
    } elseif (substr($logo->getValue(), -3) == "png" || substr($logo->getValue(), -3) == "PNG") {
        $pdf->addPngFromFile($logo->getValue(), puntos_cm(2), puntos_cm(23.8), puntos_cm(3.5));
    } else {
        Logger::log("Verifique la configuracion del pos_config, la extension de la imagen del logo no es compatible");
        die("La extension de la imagen usada para el logo del negocio no es valida.");
    }
    

    /*     * ************************
     * ENCABEZADO
     * ************************* */


    /*     * ************************
     * TITULO
     * Datos del emisor, lugar de expedicion, folio, fecha de emision, no de serie
     * del certificado del contribuyente
     * ************************* */
    $e = "<b>" . readableText($emisor->nombre) . "</b>\n";
    $e .= formatAddress($emisor);
    $e .= "RFC: " . $emisor->rfc;

    //datos de la sucursal
    $e .= "\n\n<b>Lugar de expedicion</b>\n";
    $e .= formatAddress($sucursal);

    $datos = array(
        array(
            "emisor" => $e,
      //      'sucursal' => $s,
        )
    );

    $pdf->ezSetY(puntos_cm(26.8));
    $opciones_tabla = array();
    $opciones_tabla['showLines'] = 0;
    $opciones_tabla['showHeadings'] = 0;
    $opciones_tabla['shaded'] = 0;
    $opciones_tabla['fontSize'] = 8;
    $opciones_tabla['xOrientation'] = 'right';
    $opciones_tabla['xPos'] = puntos_cm(7.5);
    $opciones_tabla['width'] = puntos_cm(11);
    $opciones_tabla['textCol'] = array(0, 0, 0);
    $opciones_tabla['titleFontSize'] = 12;
    $opciones_tabla['rowGap'] = 3;
    $opciones_tabla['colGap'] = 3;

    $pdf->ezTable($datos, "", "", $opciones_tabla);

    $cajero = UsuarioDAO::getByPK($venta->getIdUsuario())->getNombre();
    $datos = array(
        array("col" => "<b>Venta</b>"),
        array("col" => $venta->getIdVenta()),
        array("col" => "<b>Fecha de venta</b>"),
        array("col" => toDate($venta->getFecha())),
        array("col" => "<b>Tipo de venta</b>"),
        array("col" => readableText($venta->getTipoVenta())),
        array("col" => "<b>Cajero</b>"),
        array("col" => readableText($cajero))
    );

    $pdf->ezSetY(puntos_cm(26.8));

    $opciones_tabla['xPos'] = puntos_cm(14.2);
    $opciones_tabla['width'] = puntos_cm(4);
    $opciones_tabla['showLines'] = 0;
    $opciones_tabla['shaded'] = 2;
    $opciones_tabla['shadeCol'] = array(1, 1, 1);
    $opciones_tabla['shadeCol2'] = array(0.8984375, 0.95703125, 0.99609375);
    $pdf->ezTable($datos, "", "", $opciones_tabla);

    roundRect($pdf, puntos_cm(14.2), puntos_cm(26.8), puntos_cm(4), puntos_cm(4.25));



    /*     * ************************
     * Cliente
     * ************************* */
    $datos_receptor = $cliente->getRAzonSocial() . "\n";
    $datos_receptor .= formatAddress($cliente);
    $datos_receptor .= "RFC: " . $cliente->getRfc();

    $receptor = array(
        array("receptor" => "<b>Cliente</b>"),
        array("receptor" => $datos_receptor),
    );

    $pdf->ezSetY(puntos_cm(22));
    $opciones_tabla['xPos'] = puntos_cm(2);
    $opciones_tabla['width'] = puntos_cm(16.2);
    $opciones_tabla['showLines'] = 0;
    $pdf->ezTable($receptor, "", "", $opciones_tabla);

    roundRect($pdf, puntos_cm(2), puntos_cm(22), puntos_cm(16.2), puntos_cm(3.2));


    /*     * ************************
     * PRODUCTOS
     * ************************* */
    $elementos = array(
        array('cantidad' => 'Cantidad',
            'unidad'=>'Unidad',
            'agrupacion' => 'Agrupacion',
            'descripcion' => 'Descripcion                                                                                                     ', 'precio' => 'Precio', 'importe' => 'Importe'),
    );


    foreach ($productos as $p) {

        $p_inventario = InventarioDAO::getByPK($p["id_producto"]);
        
        $agrupacion = $p_inventario->getAgrupacion() != null ? $p_inventario->getAgrupacion() : "unidad";

        if ($p["cantidadProc"] > 0) {

            $prod['cantidad'] = sprintf("%01.2f",$p["cantidadProc"]);            
            $prod['descripcion'] = $p["descripcion"] . " PROCESADA";
            $prod['precio'] = moneyFormat($p["precioProc"], DONT_USE_HTML);
            $size = $p_inventario->getAgrupacion() != null ? sprintf("%01.2f", ($p["cantidadProc"] / $p_inventario->getAgrupacionTam())) : "";    

            if ($p_inventario->getPrecioPorAgrupacion()) {
                
                $prod['importe'] = moneyFormat($size * $p["precioProc"], DONT_USE_HTML);
                $prod['unidad'] = $p_inventario->getAgrupacion();
                
            } else {
                $prod['importe'] = moneyFormat($p["precioProc"] * $p["cantidadProc"], DONT_USE_HTML);
                $prod['unidad'] = $p_inventario->getEscala();
            }
            
            $prod['agrupacion'] = $size . " " . $agrupacion;
            array_push($elementos, $prod);
        }

        if ($p["cantidad"] > 0) {
            
            $prod['cantidad'] = sprintf("%01.2f",$p["cantidad"]);
            $prod['descripcion'] = $p["descripcion"];
            $prod['precio'] = moneyFormat($p["precio"], DONT_USE_HTML);
            
            $size = $p_inventario->getAgrupacion() != null ? sprintf("%01.2f", ($p["cantidad"] / $p_inventario->getAgrupacionTam())) : "";    
            
            //ver si hay precio por agrupacion
            if ($p_inventario->getPrecioPorAgrupacion()) {
                
                $prod['importe'] = moneyFormat($size * $p["precio"], DONT_USE_HTML);
                $prod['unidad'] = $p_inventario->getAgrupacion();
                
            } else {
                $prod['importe'] = moneyFormat($p["precio"] * $p["cantidad"], DONT_USE_HTML);
                $prod['unidad'] = $p_inventario->getEscala();
            }
            
            $prod['agrupacion'] = $size . " " . $agrupacion;
            array_push($elementos, $prod);
        }
    }



    array_push($elementos, array("cantidad" => "",
        "unidad" => "",
        "agrupacion" => "",
        "descripcion" => "",
        "precio" => "Subtotal",
        "importe" => moneyFormat($venta->getSubTotal(), DONT_USE_HTML)));

    array_push($elementos, array("cantidad" => "",
        "unidad" => "",
        "agrupacion" => "",
        "descripcion" => "",
        "precio" => "Descuento",
        "importe" => moneyFormat($venta->getDescuento(), DONT_USE_HTML)));

    array_push($elementos, array("cantidad" => "",
        "unidad" => "",
        "agrupacion" => "",
        "descripcion" => "",
        "precio" => "IVA",
        "importe" => moneyFormat($venta->getIVA(), DONT_USE_HTML)));

    array_push($elementos, array("cantidad" => "",
        "unidad" => "",
        "agrupacion" => "",
        "descripcion" => "",
        "precio" => "Total",
        "importe" => moneyFormat($venta->getTotal(), DONT_USE_HTML)));
    
    if($venta->getPagado() < $venta->getTotal()){
        array_push($elementos, array("cantidad" => "",
        "unidad" => "",
        "agrupacion" => "",
        "descripcion" => "",
        "precio" => "Saldo",
        "importe" => moneyFormat(($venta->getTotal() - $venta->getPagado()), DONT_USE_HTML)));
    }


    $pdf->ezText("", 10, array('justification' => 'center'));
    $pdf->ezSetY(puntos_cm(18.6));
    $opciones_tabla['xPos'] = puntos_cm(2);
    $opciones_tabla['width'] = puntos_cm(16.2);
    $pdf->ezTable($elementos, "", "", $opciones_tabla);

    //roundedRect($x, $y, $w, $h)
    roundRect($pdf, puntos_cm(2), puntos_cm(18.6), puntos_cm(16.2), puntos_cm(9.7));


    /*     * ************************
     * PAGARE
     * ************************* */
    $mes = "";
    
    switch( date( "m", strtotime( $venta->getFecha() ) ) ){
        case 1 : 
            $mes = 'Enero';
            break;
        case 2 : 
            $mes = 'Febrero';
            break;
        case 3 : 
            $mes = 'Marzo';
            break;
        case 4 : 
            $mes = 'Abril';
            break;
        case 5 : 
            $mes = 'Mayo';
            break;
        case 6 : 
            $mes = 'Junio';
            break;
        case 7 : 
            $mes = 'Julio';
            break;
        case 8 : 
            $mes = 'Agosto';
            break;
        case 9 : 
            $mes = 'Septiembre';
            break;
        case 10 : 
            $mes = 'Octubre';
            break;
        case 11 : 
            $mes = 'Noviembre';
            break;
        case 12 : 
            $mes = 'Diciembre';
            break;
    }

    $en_letra = new CNumeroaletra();
    $en_letra->setNumero($venta->getTotal());

    
    $pagare = "\n\nNo. _________                                                                                                                            En " . readableText($emisor->municipio) . " a " . date("d", strtotime( $venta->getFecha() )) . " de " . $mes . " del " . date("Y", strtotime( $venta->getFecha() )) ."\n\n";
    $pagare .= " Debe(mos) y pagare(mos) incondicionalmente por este Pagaré a la orden de " . readableText($emisor->nombre) . " en " . readableText($emisor->municipio) . " " . readableText($emisor->estado) . " ";
    $pagare .= "el __________________________  la cantidad de ";
    $pagare .= moneyFormat($venta->getTotal(), DONT_USE_HTML) . " " . $en_letra->letra() . ". Valor recibido a mi";
    $pagare .= "(nuestra) entera satisfacción. Este pagaré forma parte de una serie numerada de 1 al 1 y esta sujeto a la condición de que, ";
    $pagare .= "al no pagarse a su vencimiento, sera exigible ";
    $pagare .= "desde la fecha de vencimiento de este documento hasta el dia de su liquidacón, ";
    $pagare .= "causara intereses moratorios al tipo de 20% mensual, ";
    $pagare .= "pagadero en esta ciudad juntamente con el principal.";
  
    $receptor = array(
        array("receptor" => utf8_decode($pagare))
    ); 


    $pdf->addText(puntos_cm(2.1),puntos_cm(7.8),18,utf8_decode('<i>P a g a r é</i>'));
    $pdf->addText(puntos_cm(14),puntos_cm(7.8),9,utf8_decode('BUENO POR  ' . moneyFormat($venta->getTotal(), DONT_USE_HTML)));    
    
    $pdf->addText(puntos_cm(12.9),puntos_cm(4),8,utf8_decode('<b>Acepto(amos)</b>'));
    $pdf->addText(puntos_cm(12.9),puntos_cm(2.65),8,utf8_decode('<b>Firma(s) ________________________</b>'));
    
    $pdf->setColor(0.419, 0.466, 0.443);
    
    $pdf->addText(puntos_cm(4),puntos_cm(4.15),6,utf8_decode('Nombre y datos del deudor'));
    $pdf->addText(puntos_cm(2),puntos_cm(3.6),6.5,utf8_decode('Nombre _____________________________________________'));
    $pdf->addText(puntos_cm(2),puntos_cm(3.125),6.5,utf8_decode('Dirección ____________________________________________'));
    $pdf->addText(puntos_cm(2),puntos_cm(2.65),6.5,utf8_decode('Población ____________________________________________'));
    

    $pdf->ezSetY(puntos_cm(8.2));
    $opciones_tabla['xPos'] = puntos_cm(2);
    $opciones_tabla['width'] = puntos_cm(16.2);
    $opciones_tabla['shaded'] = 0;
    $opciones_tabla['showLines'] = 0;
    $pdf->ezTable($receptor, "", "", $opciones_tabla);

    //roundedRect($x, $y, $w, $h)
    roundRect($pdf, puntos_cm(2), puntos_cm(8.5), puntos_cm(16.2), puntos_cm(6.06));

    /*     * ************************
     * notas de abajo
     * ************************* */
    $pdf->setLineStyle(1);
    $pdf->setStrokeColor(0.3359375, 0.578125, 0.89453125);

    $pdf->line(puntos_cm(2), puntos_cm(2.0), puntos_cm(18.1), puntos_cm(2.0));

    $pdf->addText(puntos_cm(2), puntos_cm(1.61), 7, "Fecha de impresion: " . date('Y-m-d'));

    $pdf->addJpegFromFile("../www/media/logo_simbolo.jpg", puntos_cm(15.9), puntos_cm(.95), 25);

    $pdf->addText(puntos_cm(16.70), puntos_cm(1.30), 8, "caffeina.mx");

    $pdf->ezStream();


    return;
}

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

/**
 * Crea un pdf con el estado de cuenta de el cliente especificado
 * @param Array $args,  $args['id_cliente'=>12[,'tipo_venta'=> 'credito | contado | saldo'] ], por default obtiene todas las compras del cliente
 */
function imprimirEstadoCuentaCliente($args) {

    //verificamos que se haya especificado el id del cliente    
    if (!isset($args['id_cliente'])) {
        Logger::log("Error al obtener el estado de cuenta, no se ha especificado un cliente.");
        die('{"success": false, "reason": "Error al obtener el estado de cuenta, no se ha especificado un cliente."}');
    }

    //verificamos que el cliente exista
    if (!($cliente = ClienteDAO::getByPK($args['id_cliente']))) {
        Logger::log("Error al obtener el estado de cuenta, no se tiene registro del cliente {$args['id_cliente']}.");
        die('{"success": false, "reason": "Error al obtener el estado de cuenta, no se tiene registro del cliente ' . $args['id_cliente'] . '"}');
    }

    //obtenemos los datos del emisor
    $estado_cuenta = estadoCuentaCliente($args);

    //buscar los datos del emisor       
    if (!$emisor = PosConfigDAO::getByPK('emisor')) {
        Logger::log("no encuentro los datos del emisor");
        die("no encuentro los datos del emisor");
    }

    $emisor = json_decode($emisor->getValue())->emisor;

    $sucursal = SucursalDAO::getByPK($_SESSION['sucursal']);

    if (!$sucursal) {
        die("Sucursal invalida");
    }

    include_once('librerias/ezpdf/class.pdf.php');
    include_once('librerias/ezpdf/class.ezpdf.php');

    $pdf = new Cezpdf();

    $pdf->selectFont('../server/librerias/ezpdf/fonts/Helvetica.afm');

    //margenes de un centimetro para toda la pagina
    $pdf->ezSetMargins(1, 1, 1, 1);

    /*
     * LOGO
     */

    if (!$logo = PosConfigDAO::getByPK('url_logo')) {
        Logger::log("Verifique la configuracion del pos_config, no se encontro el camṕo 'url_logo'");
        die("Verifique la configuracion del POS, no se encontro el url del logo");
    }

    //addJpegFromFile(imgFileName,x,y,w,[h])    
    //detectamos el tipo de imagen del logo
    if (substr($logo->getValue(), -3) == "jpg" || substr($logo->getValue(), -3) == "JPG" || substr($logo->getValue(), -4) == "jpeg" || substr($logo->getValue(), -4) == "JPEG") {
        $pdf->addJpegFromFile($logo->getValue(), puntos_cm(2), puntos_cm(25.5), puntos_cm(3.5));
    } elseif (substr($logo->getValue(), -3) == "png" || substr($logo->getValue(), -3) == "PNG") {
        $pdf->addPngFromFile($logo->getValue(), puntos_cm(2), puntos_cm(25.5), puntos_cm(3.5));
    } else {
        Logger::log("Verifique la configuracion del pos_config, la extension de la imagen del logo no es compatible");
        die("La extension de la imagen usada para el logo del negocio no es valida.");
    }


    /*     * ************************
     * ENCABEZADO
     * ************************* */
    $e = "<b>" . readableText($emisor->nombre) . "</b>\n";
    $e .= formatAddress($emisor);
    $e .= "RFC: " . $emisor->rfc . "\n\n";

    //datos de la sucursal
    $e .= "<b>Lugar de expedicion</b>\n";
    $e .= readableText($sucursal->getDescripcion()) . "\n";
    $e .= formatAddress($sucursal);

    $datos = array(
        array(
            "emisor" => $e
        )
    );

    $pdf->ezSetY(puntos_cm(28.6));
    $opciones_tabla = array();
    $opciones_tabla['showLines'] = 0;
    $opciones_tabla['showHeadings'] = 0;
    $opciones_tabla['shaded'] = 0;
    $opciones_tabla['fontSize'] = 8;
    $opciones_tabla['xOrientation'] = 'right';
    $opciones_tabla['xPos'] = puntos_cm(7.3);
    $opciones_tabla['width'] = puntos_cm(11);
    $opciones_tabla['textCol'] = array(0, 0, 0);
    $opciones_tabla['titleFontSize'] = 12;
    $opciones_tabla['rowGap'] = 3;
    $opciones_tabla['colGap'] = 3;

    $pdf->ezTable($datos, "", "", $opciones_tabla);

    $cajero = UsuarioDAO::getByPK($_SESSION['userid'])->getNombre();
    $datos = array(
        array("col" => "<b>Cajero</b>"),
        array("col" => readableText($cajero)),
        array("col" => "<b>Cliente</b>"),
        array("col" => readableText($cliente->getRazonSocial())),
        array("col" => "<b>Limite de  Credito</b>"),
        array("col" => moneyFormat($estado_cuenta->limite_credito, DONT_USE_HTML)),
        array("col" => "<b>Saldo</b>"),
        array("col" => moneyFormat($estado_cuenta->saldo, DONT_USE_HTML))
    );

    $pdf->ezSetY(puntos_cm(28.8));

    $opciones_tabla['xPos'] = puntos_cm(12.2);
    $opciones_tabla['width'] = puntos_cm(6);
    $opciones_tabla['showLines'] = 0;
    $opciones_tabla['shaded'] = 2;
    $opciones_tabla['shadeCol'] = array(1, 1, 1);
    //$opciones_tabla['shadeCol2'] = array(0.054901961, 0.756862745, 0.196078431);
    $opciones_tabla['shadeCol2'] = array(0.8984375, 0.95703125, 0.99609375);
    $pdf->ezTable($datos, "", "", $opciones_tabla);

    //roundRect($pdf, puntos_cm(12.2), puntos_cm(28.8), puntos_cm(6), puntos_cm(4.25));


    /**
     * ESTADO DE CUENTA
     */
    $elementos = array(
        array('id_venta' => 'Venta',
            'fecha' => 'Fecha',
            'sucursal' => 'Sucursal',
            'cajero' => 'Cajero',
            //'cancelada' => 'Cancelada',
            'tipo_venta' => 'Tipo',
            'tipo_pago' => 'Pago',
            'total' => 'Total',
            'pagado' => 'Pagado',
            'saldo' => 'Saldo'),
    );


    foreach ($estado_cuenta->array_ventas as $venta) {

        $array_venta = array();

        $array_venta['id_venta'] = $venta['id_venta'];
        $array_venta['fecha'] = $venta['fecha'];
        $array_venta['sucursal'] = readableText($venta['sucursal']);
        $array_venta['cajero'] = readableText($venta['cajero']);
        $array_venta['cancelada'] = readableText($venta['cancelada']);
        $array_venta['tipo_venta'] = readableText($venta['tipo_venta']);
        $array_venta['tipo_pago'] = readableText($venta['tipo_pago']);
        $array_venta['total'] = moneyFormat($venta['total'], DONT_USE_HTML);
        $array_venta['pagado'] = moneyFormat($venta['pagado'], DONT_USE_HTML);
        $array_venta['saldo'] = moneyFormat($venta['saldo'], DONT_USE_HTML);

        array_push($elementos, $array_venta);
    }


    $pdf->ezText("", 8, array('justification' => 'center'));
    $pdf->ezSetY(puntos_cm(24));
    $opciones_tabla['xPos'] = puntos_cm(2);
    $opciones_tabla['width'] = puntos_cm(16.2);
    $pdf->ezTable($elementos, "", "Estado de Cuenta", $opciones_tabla);


    //roundRect($pdf, puntos_cm(2), puntos_cm(24.3), puntos_cm(16.2), puntos_cm(3.2));


    /*     * ************************
     * notas de abajo
     * ************************* */
    $pdf->setLineStyle(1);
    $pdf->setStrokeColor(0.3359375, 0.578125, 0.89453125);

    $pdf->line(puntos_cm(2), puntos_cm(1.3), puntos_cm(18.2), puntos_cm(1.3));


    $pdf->addText(puntos_cm(2), puntos_cm(1.0), 7, "Fecha de impresion: " . date("d/m/y") . " " . date("H:i:s"));

    //addJpegFromFile(imgFileName,x,y,w,[h])
    $pdf->addJpegFromFile("../www/media/logo_simbolo.jpg", puntos_cm(15.9), puntos_cm(.25), 25);


    $pdf->addText(puntos_cm(16.70), puntos_cm(.60), 8, "caffeina.mx");

    $pdf->ezStream();
}

if (isset($args['action'])) {

    switch ($args['action']) {

        case 1300:
            printf('{"success": true, "datos": %s}', json_encode(listarDocumentos()));
            break;

        case 1301:
            printf('{"success": true, "datos": %s}', json_encode(leerLeyendasTicket()));
            break;

        case 1305 :
            imprimirFactura($args['id_venta']);
            break;

        case 1306 :
            imprimirNotaDeVenta($args['id_venta']);
            break;

        case 1307 :
            imprimirEstadoCuentaCliente($args);

        case 1308 :
            imprimirFacturaXML($args['id_venta']);

        default:
            printf('{ "success" : "false" }');
            break;
    }
}
?>