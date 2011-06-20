<?php

require_once("model/ventas.dao.php");
require_once("model/detalle_venta.dao.php");
require_once("model/cliente.dao.php");
require_once("model/factura_venta.dao.php");
require_once('model/sucursal.dao.php');
require_once('model/pagos_venta.dao.php');
require_once("model/inventario.dao.php");
require_once("model/pos_config.dao.php");
require_once("pos.com/comprobante.php");
require_once("logger.php");

/**
 * Verifica que la venta que se decea facturar exista, este sin facturar
 * y que los datos criticos del cliente esten correctamente establecidos.
 *
 * @param Array $args
 * returns Cliente
 */
function verificarDatosVenta($id_venta = null) {

    Logger::log("Iniciando proceso de validacion de datos de al venta..");

    //verificamos que el parametro de la venta este definido
    if (!isset($id_venta)) {
        Logger::log("EL parametro de la venta no se ha definido");
        die('{"success": false, "reason": "La venta no se ha definido" }');
    }

    //verificamos que id_venta sea numerico
    if (is_nan($id_venta)) {
        Logger::log("El id de la venta no es un numero");
        die('{"success": false, "reason": "Error el parametro de venta, no es un numero" }');
    }

    //verificamos que la venta exista
    if (!( $venta = VentasDAO::getByPK($id_venta) )) {
        Logger::log("No se tiene registro de la venta : {$id_venta}");
        die('{"success": false, "reason": "No se tiene registro de la venta ' . $id_venta . '." }');
    }

    if ($venta->getLiquidada() != "1") {
        Logger::log("Error : No puede facturar una venta que no este liquidada.");
        die('{"success": false, "reason": "Error : No puede facturar una venta que no este liquidada." }');
    }

    //extraemos el id del cliente de la venta 
    if (( $id_cliente = $venta->getIdCliente() ) <= 0) {
        Logger::log("Error : Se esta intentando facturar una venta que al momento que se registro la venta no se indico un cliente valido, en este caso usted debe de indicar a que cliente se debe de facturar esta venta.");
        die('{"success": false, "reason": "Error : Se esta intentando facturar una venta que al momento que se registro la venta no se indico un cliente valido, en este caso usted debe de indicar a que cliente se debe de facturar esta venta." }');
    }

    //verificamos que id_cliente sea numerico
    if (is_nan($id_cliente)) {
        Logger::log("El id del cliente no es un numero");
        die('{"success": false, "reason": "Error el parametro de cliente, no es un numero" }');
    }

    //verificamos que el cliente exista
    if (!( $cliente = ClienteDAO::getByPK($id_cliente) )) {
        Logger::log("No se tiene registro del cliente : {$id_cliente}.");
        die('{"success": false, "reason": "No se tiene registro del cliente ' . $id_cliente . '." }');
    }

    //verificamos que el cliente este acivo
    if ($cliente->getActivo() != "1") {
        Logger::log("El cliente  : {$id_cliente} no se encuentra activo.");
        die('{"success": false, "reason": "El cliente ' . $id_cliente . ' no se encuentra activo." }');
    }

    //verifiacmos que la venta este liquidada
    if ($venta->getLiquidada() != "1") {
        Logger::log("La venta {$venta->getIdVenta()} no esta liquidada");
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
        if ($factura->getActiva() == "1") {
            $facturaVenta = $factura;
            break;
        }
    }

    //si la encontro entonces termina el proceso
    if ($facturaVenta != null) {
        //la factura de la venta esta activa
        Logger::log("La venta {$facturaVenta->getIdVenta()} ha sido facturada con el folio {$facturaVenta->getIdFolio()} y esta la factura activa");
        die('{"success": false, "reason": "No se puede emitir la factura debido a que la venta ' . $id_venta . ' ha sido facturada con el folio : ' . $facturaVenta->getIdFolio() . ' y esta activa." }');
    }

    //verificamos que el cliente tenga correctamente definidos todos sus parametros
    //razon soclial
    if (strlen($cliente->getRazonSocial()) <= 10) {
        Logger::log("La razon social del cliente es demaciado corta.");
        die('{"success": false, "reason": "Actualice la informacion del cliente : La razon social del cliente es demaciado corta." }');
    }

    /* //rfc
      if (strlen($cliente->getRfc()) < 13 || strlen($cliente->getRfc()) > 13) {
      Logger::log("verifique la estructura del rfc : 4 caracteres para el nombre, 6 para la fecha y 3 para la homoclave");
      die('{"success": false, "reason": "Actualice la informacion del cliente : Verifique la estructura del rfc : 4 caracteres para el nombre, 6 para la fecha y 3 para la homoclave." }');
      } */

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

    Logger::log("Terminado proceso de validacion de datos de la venta");

    return $cliente;
}

/**
 * Realiza todas las validaciones pertinentes para crear con esto la factura electronica
 * ademas realiza una peticion al webservice para emitir una nueva factura
 * 
 * http://localhost/pos/trunk/www/proxy.php?action=1200?&id_venta=120&factura_generica={%22id_producto%22:%22xxl%22,%20%22descripcion%22:%22varios%20verduras%22,%22unidad%22:%22unidad%22,%22cantidad%22:1,%22valor%22:1350.50}
 */
function generaFactura($id_venta, $factura_generica = null) {

    Logger::log("Entrando a la funcion generaFactura");
    
    if (isset($factura_generica)) {

        Logger::log("Se detecto que se solicito un CFDI generico, los datos son los siguientes :");
        
        $factura_generica = parseJSON($factura_generica);

        if (!isset($factura_generica->id_producto)) {
            Logger::log("Error : No se definio el id del producto en la factura generia");
            die('{"success": false, "reason": "Error : No se definio el id del producto"}');
        }

        if (!isset($factura_generica->descripcion)) {
            Logger::log("Error : No se definio la descripcion del producto en la factura generia");
            die('{"success": false, "reason": "Error : No se definio la descripcion del producto"}');
        }

        if (!isset($factura_generica->unidad)) {
            Logger::log("Error : No se definio la unidad del producto en la factura generia");
            die('{"success": false, "reason": "Error : No se definio la unidad del producto"}');
        }

        /* if(!isset($factura_generica->cantidad)){
          Logger::log("Error : No se definio la cantidad del producto en la factura generia");
          die('{"success": false, "reason": "Error : No se definio la cantidad del producto"}');
          }

          if(!isset($factura_generica->valor)){
          Logger::log("Error : No se definio el valor del producto en la factura generia");
          die('{"success": false, "reason": "Error : No se definio el valor del producto"}');
          } */
        
        Logger::log("ID : {$factura_generica->id_producto}, DESCRIPCION : {$factura_generica->descripcion}, UNIDAD : {$factura_generica->unidad} ");
    }

    Logger::log("Iniciando proceso de facturacion");

    //verificamos si podemos escribir en la carpeta de facturas
    if (!is_writable("../static_content/facturas/")) {
        Logger::log("No puedo escribir en la carpeta de facturas.");
        die('{"success": false, "reason": "No puedo escribir en la carpeta de facturas." }');
    }

    //verifica que los datos de la venta y el cliente esten correctos
    $cliente = verificarDatosVenta($id_venta);

    DAO::transBegin();

    $data = getDatosGenerales($id_venta);

    /* Termino aqui la transaccion por que si ocurre un error, al momento de la lectura de los XML quiero que se guarde
     * el registro de factura_venta.     
     */
 

 

    Logger::log("Iniciando creacion de objeto comprobante..");
    $comprobante = new Comprobante();

    Logger::log("Asignando los datos del objeto generales al comprobante");
    $comprobante->setGenerales($data->generales); //1

    Logger::log("Asignando los datos los conceptos al comprobante");
    $comprobante->setConceptos(getConceptos($id_venta, $factura_generica)); //2

    $comprobante->setEmisor(getEmisor()); //3

    if ($_SESSION['sucursal'] != 0) {
        $comprobante->setExpedidoPor(getExpedidoPor()); //4
    }


    $comprobante->setLlaves(getLlaves()); //5

    $comprobante->setReceptor(getReceptor($cliente)); //6

    $comprobante->setUrlWS(getUrlWS()); //7


    //intentamos obtener la factura sellada
    $success = $comprobante->getNuevaFactura();


    if ($success->getSuccess()) {

        //si entra aqui significa que todo salio correctamente, podemos insertar los demas datos en la factura de la venta
        $data->factura->setXml($comprobante->getXMLresponse());
        $data->factura->setActiva("1");
        $data->factura->setSellada("1");
        $data->factura->setFechaCertificacion($comprobante->getFechaCertificacion());
        $data->factura->setVersionTfd($comprobante->getVersionTFD());
        $data->factura->setFolioFiscal($comprobante->getUUID());
        $data->factura->setNumeroCertificadoSat($comprobante->getNumeroCertificadoSAT());
        $data->factura->setSelloDigitalEmisor($comprobante->getSelloDigitalEmisor());
        $data->factura->setSelloDigitalSat($comprobante->getSelloDigitalSAT());
        $data->factura->setCadenaOriginal($comprobante->getCadenaOriginal());

        try {
            FacturaVentaDAO::save($data->factura);
        } catch (Exception $e) {
            Logger::log("Error al salvar la factura de la venta : {$e}");
            DAO::transRollback();
            die('{"success": false, "reason": "Error al salvar la factura de la venta intente nuevamente" }');
        }
    } else {
        Logger::log($success->getInfo());

        try {
            FacturaVentaDAO::delete($data->factura);
            DAO::transEnd();
            die('{"success": false, "reason": "' . $success->getInfo() . '" }');
        } catch (Exception $e) {
            Logger::log("Error al crear la factura y error al eliminar el registro de la factura de la venta : {$e}");
            DAO::transRollback();
            die('{"success": false, "reason": "Error al crear la factura y error al eliminar el registro de la factura de la venta" }');
        }
    }

    DAO::transEnd();

    $file = '../static_content/facturas/' . $_SESSION["INSTANCE_ID"] . "_" . $id_venta . '.xml';
    
    //verificamos que el archivo no exista, si existe lo eliminamos
    while (is_file($file) == TRUE) {
        chmod($file, 0666);
        unlink($file);
    }

    //creamos el archivo del xml
    $archivo = $file;
    $fp = fopen($archivo, "a");
    $string = $comprobante->getXMLresponse();
    $write = fputs($fp, $string);
    fclose($fp);

    //Termino todo correctamente   
    printf('{"success":true, "id_venta":%s}', $id_venta);

    Logger::log("Terminando proceso de facturacion");
}

/**
 * Obtenemos la url del web serice del cual se sirve este sistema
 */
function getUrlWS() {

    if (!( $url = PosConfigDAO::getByPK('url_timbrado') )) {
        Logger::log("Error al obtener la url del ws");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al obtener la url del web service" }');
    }

    return $url->getValue();
}

/**
 * Regresa un objeto qeu contiene la informacion acerca de los conceptos de la venta
 * return Object Conceptos
 */
function getConceptos($id_venta, $factura_generica = null) {

    Logger::log("Iniciando el proceso de extraccion de detalles de la venta");
    
    //obtenemos el juego de articulos vendidos en la venta
    $detalle_venta = new DetalleVenta();
    $detalle_venta->setIdVenta($id_venta);
    $detalle_venta = DetalleVentaDAO::search($detalle_venta);

    //objeto que almacenara a todos los conceptos de la venta
    $conceptos = new Conceptos();

    if (isset($factura_generica)) {
        
        Logger::log("El concepto es de una factura generica..");

        $concepto = new Concepto();

        $concepto->setIdProducto($factura_generica->id_producto);
        $concepto->setDescripcion($factura_generica->descripcion);
        $concepto->setUnidad($factura_generica->unidad);
        $concepto->setCantidad(1);
        $concepto->setValor(VentasDAO::getByPK($id_venta)->getTotal());
        $concepto->setImporte(VentasDAO::getByPK($id_venta)->getTotal());

        $conceptos->addConcepto($concepto);
        
        return $conceptos;
    }

    //llenamos a $conceptos con los conceptos de la venta
    foreach ($detalle_venta as $articulo) {

        //verificamos que el producto este registrado en el inventario
        if (!($inventario = InventarioDAO::getByPK($articulo->getIdProducto()))) {
            Logger::log("el producto : {$id_producto} no se tiene registrado en el inventario");
            DAO::transRollback();
            die('{"success": false, "reason": "el producto : ' . $id_producto . ' no se tiene registrado en el inventario" }');
        }

        $concepto = new Concepto();
        $concepto->setIdProducto($inventario->getIdProducto());
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
function getReceptor($cliente) {

    if ($cliente->getIdCliente() <= 0) {
        Logger::log("Error : El Cliente con el ID : {$cliente->getIdCliente()} es invalido.");
        DAO::transRollback();
        die('{"success": false, "reason": "El Cliente con el ID : ' . $cliente->getIdCliente() . ' es invalido." }');
    }

    $receptor = new Receptor();

    $receptor->setRazonSocial($cliente->getRazonSocial());

    $receptor->setRFC($cliente->getRfc());

    $receptor->setCalle($cliente->getCalle());

    $receptor->setNumeroExterior($cliente->getNumeroExterior());

    if ($cliente->getNumeroInterior() != "") {
        $receptor->setNumeroInterior($cliente->getNumeroInterior());
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

    $json = parseJSON($pos_config->getValue());

    $emisor = new Emisor();

    $emisor->setRazonSocial($json->emisor->nombre);

    $emisor->setRFC($json->emisor->rfc);

    $emisor->setCalle($json->emisor->calle);

    $emisor->setNumeroExterior($json->emisor->numeroExterior);

    if (isset($json->emisor->numeroInterior) && $json->emisor->numeroInterior != "") {
        $emisor->setNumeroInterior($json->emisor->numeroInterior);
    }

    if (isset($json->emisor->referencia) && $json->emisor->referencia != "") {
        $emisor->setReferencia($json->emisor->referencia);
    }

    $emisor->setColonia($json->emisor->colonia);

    if (isset($json->emisor->localidad) && $json->emisor->localidad != "") {
        $emisor->setNumeroInterior($json->emisor->localidad);
    }

    $emisor->setMunicipio($json->emisor->municipio);

    $emisor->setEstado($json->emisor->estado);

    $emisor->setPais($json->emisor->pais);

    $emisor->setCodigoPostal($json->emisor->codigoPostal);

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

    $expedidoPor->setCalle($sucursal->getCalle());

    $expedidoPor->setNumeroExterior($sucursal->getNumeroExterior());

    if ($sucursal->getNumeroInterior() != "") {
        $expedidoPor->setNumeroInterior($sucursal->getNumeroInterior());
    }

    if ($sucursal->getReferencia() != "") {
        $expedidoPor->setReferencia($sucursal->getReferencia());
    }

    $expedidoPor->setColonia($sucursal->getColonia());

    if ($sucursal->getLocalidad() != "") {
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

/**
 * Crea un objeto de tipo Generales y un objeto Factura con los datos basicos de la factura.
 * @param type $args
 * @return stdClass Regresa un objeto de tipo Generales generales y un objto Factura factura
 */
function getDatosGenerales($id_venta) {

    Logger::log("Iniciando creacion de objeto con los datos generales.");
    
    //generamos una factura en la BD

    $facturaBD = creaFacturaBD($id_venta);

    $venta = VentasDAO::getByPK($id_venta);

    $generales = new Generales();

    $generales->setFecha(date("Y-m-d") . "T" . date("H:i:s"));

    $generales->setFolioInterno($facturaBD->getIdFolio());

    //TODO : En un futuro incluir parcialidades
    $generales->setFormaDePago("Pago en una sola exhibicion");

    //TODO : Verificar a fondo como implementar mas impuestos
    $generales->setIva("0");


    //extraemos el metodo de pago con el cual liquido la venta en caso de ser venta a credito
    if ($venta->getTipoVenta() == "credito") {

        $pagos_venta = new PagosVenta();
        $pagos_venta->setIdVenta($venta->getIdVenta());
        $pagos_venta = PagosVentaDAO::search($pagos_venta, 'fecha', 'desc');

        $generales->setMetodoDePago($pagos_venta[0]->getTipoPago());
    } else {

        $generales->setMetodoDePago($venta->getTipoPago());
    }

    //significa que tomara la serie de la         
    $sucursal = SucursalDAO::getByPK($_SESSION['sucursal']);
    $generales->setSerie($sucursal->getLetrasFactura());


    //TODO : Investigar que es lo que pasa cuando tenemos un cliente que se le aplica un descuento, en el subtotal puede ir menor que el total en la factura? de momento cuando sea menor le pondre igual al total

    $generales->setSubtotal(( $venta->getSubtotal() > $venta->getTotal() ) ? $venta->getTotal() : $venta->getSubtotal() );

    $generales->setTotal($venta->getTotal());

    $success = $generales->isValid();

    $data = new stdClass();
    $data->generales = $generales;
    $data->factura = $facturaBD;

    if ($success->getSuccess()) {
        return $data;
    } else {
        Logger::log("Error : {$success->getInfo()}");
        DAO::transRollback();
        die('{"success": false, "reason": "' . $success->getInfo() . '" }');
    }
    
    Logger::log("Terminada creacion de objeto con los datos generales.");
    
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


    $venta = VentasDAO::getByPK($id_venta);

    $factura = new FacturaVenta();

    $factura->setIdVenta($id_venta);
    $factura->setIdUsuario($_SESSION['userid']);
    $factura->setActiva(1);
    $factura->setXml("");
    $factura->setLugarEmision($_SESSION['sucursal']);
    $factura->setTipoComprobante("ingreso");
    $factura->setActiva("-1");
    $factura->setSellada("0");
    //TODO:Modificar esto
    $factura->setFormaPago("Pago en una sola exhibicion");
    $factura->setFolioFiscal("0");
    $factura->setNumeroCertificadoSat("0");
    $factura->setSelloDigitalEmisor("0");
    $factura->setSelloDigitalSat("0");
    $factura->setCadenaOriginal("0");
    $factura->setVersionTfd("0");

    try {
        Logger::log("Salvando el registro de la factura de la venta");
        FacturaVentaDAO::save($factura);
    } catch (Exception $e) {
        Logger::log("Error al salvar la factura de la venta : {$e}");
        DAO::transRollback();
        die('{"success": false, "reason": "Error al salvar la factura de la venta intente nuevamente." }');
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

            if (!isset($args['id_venta'])) {
                die('{"success": false, "reason": "No se a especificado el id de la venta que se desea facturar." }');
            }

            $factura_generica = null;

            if (
				isset($args['factura_generica']) 
				&& $args['factura_generica'] != "null" 
				&& $args['factura_generica'] != null 
				&& $args['factura_generica'] != ""
			)
			{
				Logger::log("Es una factura generica...");
				Logger::log("Datos de la factura generia... " . $args['factura_generica']);
                $factura_generica = $args['factura_generica'];
            }



            generaFactura($args['id_venta'], $factura_generica);

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
