<?php

    require_once("model/ventas.dao.php");
    require_once("model/detalle_venta.dao.php");
    require_once("model/cliente.dao.php");
    require_once("model/factura_venta.dao.php");
    require_once('model/sucursal.dao.php');
    require_once("model/inventario.dao.php");
    require_once("model/pos_config.dao.php");
    require_once("logger.php");
    

    /**
        * Verifica que la venta que se decea facturar exista, este sin facturar
        * y que los datos criticos del cliente esten correctamente establecidos
        */
    function validaDatos( $args ){
        Logger::log("Iniciando proceso de validacion de datos para realizar factura electronica");
        
        //verificamos que el cliente este definido
        if( !isset($args['id_cliente']) )
        {
              Logger::log("El cliente no esta definido");
              die('{"success": false, "reason": "El cliente no esta definido" }');
        }
        
        //verificamos que la venta este definida
        if( !isset($args['id_venta']) )
        {
            Logger::log("La venta no se ha definido");
            die('{"success": false, "reason": "La venta no se ha definido" }');
        }
        
        //verificamos que id_venta sea nuemrico
        if( is_nan($args['id_venta']) )
        {
            Logger::log("El id de la venta no es un numero");
            die('{"success": false, "reason": "Error el parametro de venta, no es un numero" }');
        }
        
        //verificamos que id_cliente sea nuemrico
        if( is_nan($args['id_cliente']) )
        {
            Logger::log("El id del cliente no es un numero");
            die('{"success": false, "reason": "Error el parametro de cliente, no es un numero" }');
        }
        
        //verificamos que la venta exista                
        if( !( $venta = VentasDAO::getByPK( $args['id_venta'] ) ) )
        {
            Logger::log("No se tiene registro de la venta : {$args['id_venta']}");
            die( '{"success": false, "reason": "No se tiene registro de la venta ' . $args['id_venta'] . '." }' );
        }
           
        //verificamos que el cliente exista
        if( !( $cliente = ClienteDAO::getByPK( $args['id_cliente'] ) ) ) {
            Logger::log("No se tiene registro del cliente : {$args['id_cliente']}.");
            die( '{"success": false, "reason": "No se tiene registro del cliente ' . $args['id_cliente'] . '." }' );
        }
        
        //verifiacmos que la venta este liquidada
        if( $venta->getLiquidada() != "1")
        {
            Logger::log("La venta {$venta -> getIdVenta()} no esta lioquidada");
            die( '{"success": false, "reason": "No se puede emitir la factura debido a que la venta ' . $venta -> getIdVenta() . ' no ha sido liquidada." }' );
        }
        
        //verificamos que la venta no este facturada
        $fv = new FacturaVentaDAO();
        $fv -> setIdVenta( $venta -> getIdVenta() );
        
        $factuasVenta = FacturaVentaDAO::search( $fv );        
        $facturaVenta = null;
                
        foreach($factuasVenta as $factura)
        {        
            if( $factura -> getEstado() == "1" )
            {
                $facturaVenta = $factura;
                break;
            }        
        }
        
        if( $facturaVenta != null )
        {            
            //la factura de la venta esta activa
            Logger::log("La venta {$facturaVenta -> getIdVenta()} ha sido facturada con el folio {$facturaVenta -> getFolio()}y esta la factura activa");
            die( '{"success": false, "reason": "No se puede emitir la factura debido a que la venta ' . $args['id_venta'] . ' ha sido facturada con el folio : ' . $facturaVenta -> getFolio() . ' y esta activa." }' );            
        }
        
        //verificamos que el cliente tenga correctamente definidos todos sus parametros
            //razon soclial
        if( strlen( $cliente -> getRazonSocial() ) <= 10  ){
            Logger::log("La razon social del cliente es demaciado corta.");
            die( '{"success": false, "reason": "La razon social del cliente es demaciado corta." }' );                
        }
        
            //rfc
         if( strlen( $cliente -> getRfc() ) < 13 || strlen( $cliente -> getRfc() ) > 13 ){
             Logger::log("verifique la estructura del rfc : 4 caracteres para el nombre, 6 para la fecha y 3 para la homoclave");
            die( '{"success": false, "reason": "verifique la estructura del rfc : 4 caracteres para el nombre, 6 para la fecha y 3 para la homoclave." }' );
        }
            //calle
         if( strlen( $cliente -> getCalle() ) < 3  ){
            Logger::log("La descripcion de la calle es demaciado corta.");
            die( '{"success": false, "reason": "La descripcion de la callel es demaciado corta." }' );                
        }
            //numero exterior
         if( strlen( $cliente -> getNumeroExterior() ) == 0  ){
           Logger::log("Indique el numero exterior del domicilio");
            die( '{"success": false, "reason": "Indique el numero exterior del domicilio." }' );  
        }
            //colonia
         if( strlen( $cliente -> getColonia() ) < 3  ){
           Logger::log("La descripcion de la colonia es demaciado corta.");
            die( '{"success": false, "reason": "La descripcion de la colonia es demaciado corta." }' );       
        }
            //municipio
         if( strlen( $cliente -> getMunicipio() ) < 3  ){
            Logger::log("La descripcion del municipio es demaciado corta.");
            die( '{"success": false, "reason": "La descripcion del municipio es demaciado corta." }' );       
        }
            //estado
         if( strlen( $cliente -> getEstado() ) < 3  ){
            Logger::log("La descripcion del estado es demaciado corta.");
            die( '{"success": false, "reason": "La descripcion del estado es demaciado corta." }' );       
        }
            //codigo postal
         if( strlen( $cliente -> getCodigoPostal() ) == 0  ){
             Logger::log("Indique el codigo postal.");
            die( '{"success": false, "reason": "Indique el codigo postal." }' );       
        }
        
        Logger::log("Terminado proceso de validacion de datos para realizar factura electronica");
        
    }

    /**
      * Realiza todas als validaciones pertinentes para crear con esito la factura electronica
      * ademas realiza una peticion al webservice para emitir una nueva factura
      */
    function generaFactura($args){
        
        Logger::log("Iniciando proceso de facturacion");
        
        //verifica que los datos de la venta y el cliente esten correctos
        validaDatos($args);
        
        //crea xml con los datos de la venta para enviar al webservice
        $xml_request = parseVentaToXML($args['id_venta'], $args['id_cliente'] );
                

        //Realizamos una peticion al webservice para que se genere una nueva factura en el sistema.
        $xml_response = getFacturaFromWebService( $xml_request );        
        
		echo $xml_response;
		
        //genera un json con todos los datos necesarios para generar el PDF de la factura electronica
        //$json_factura = parseFacturaToJSON($xml_response);
        
        //llamar al metodo que genera el pdf
        
        Logger::log("Terminando proceso de facturacion");
        
    }
    
    /**
        * Recibe el id de una venta, extrae sus datos y regresa un xml con el formato que
        * se necesita enviar al web service.
        */
    function parseVentaToXML($id_venta, $id_cliente){

        Logger::log("Iniciando proceso de parceo de venta a XML");

        //obtenemos el objeto venta
        if( !( $venta = VentaDAO::getByPK( $id_venta ) ) )
        {
            Logger::log("Error al obtener datos de la venta : {$id_venta}");
            die( '{"success": false, "reason": "Error al obtener datos de la venta ' . $id_venta . '." }' );
        }    
        
        //obtenemos el objeto cliente
        if( !( $cliente = ClientesDAO::getByPK( $id_cliente ) ) )
        {
            Logger::log("Error al obtener datos del cliente{$id_cliente}");
            die( '{"success": false, "reason": "Error al obtener datos del cliente ' . $id_cliente . '." }' );
        }            

        //obtenemos el detalle de la venta
        $detalle_venta = new DetalleVenta();
        $detalle_venta -> setIdVenta();
        
        $productos = DetalleVentaDAO::search( $detalle_venta );                
        
	    if(count($productos) <= 0)
	    {
		    Logger::log("Error al obtener el detalle de la venta: {$id_venta}");
            die( '{"success": false, "reason": "Error al obtener el detalle de la venta ' . $id_venta . '." }' );
	    }

        DAO::transBegin();
        //DAO::transRollback();
        
        //creamos una nueva factura en la BD para obtener el folio
        $factura =creaFacturaBD( $venta -> getIdVenta() ) ;
        
        //creamos un objeto sucursal
        if( !( $sucursal = SucursalDAO::getByPK( $_SESSION['sucursal'] ) ) )
        {
            Logger::log("Error al obtener datos de la sucursal.");
            die( '{"success": false, "reason": "Error al obtener datos de la sucursal." }' );
        }          
        
        //obtenemos un objeto que contenga la informacion de las llaves para crear el xml
        $pos_config = getInformacionConfiguracion();        
        
        //creamos la raiz del DOM DOCUMENT
        $xml =  new DOMDocument( '1.0', 'utf-8' );                
        
        $comprobante = $xml -> createElement( 'comprobante' );        
        
        $serie = $xml -> createElement( 'serie', $sucursal -> getLetrasFactura());            
        $comprobante -> appendChild($serie);
        
        $folio = $xml -> createElement( 'folio', $factua -> getIdFoio());
        $comprobante -> appendChild($folio);
        
        $fecha = $xml -> createElement( 'fecha', date("y-m-d").'T'.date("H:i:s"));
        $comprobante -> appendChild($fecha);
        
        $forma_de_pago = $xml -> createElement( 'forma_de_pago','Pago en una sola exhibición');
        $comprobante -> appendChild($forma_de_pago);
        
        $metodo_de_pago = $xml -> createElement( 'metodo_de_pago', ucfirst( strtolower( $venta -> getTipoPago ) ) );
        $comprobante -> appendChild($metodo_de_pago);
        
        $subtotal = $xml -> createElement( 'subtotal', $venta -> getSubtotal() );
        $comprobante -> appendChild($subtotal);
        
        $total = $xml -> createElement( 'total', $venta -> getTotal() );
        $comprobante -> appendChild($total);
        
        $iva = $xml -> createElement( 'iva', $venta -> getIva() );
        $comprobante -> appendChild($iva);
        
        $emisor = $xml -> createElement( 'emisor' );                
        
        $emisor_razon_social = $XML -> createElement('razon_social', $sucursal -> getRazonSocial());
        $emisor -> appendChild($emisor_razon_social);
        
        $emisor_rfc = $XML -> createElement('rfc', $sucursal -> getRfc());
        $emisor -> appendChild($emisor_rfc);
        
        $emisor_calle = $XML -> createElement('calle',$sucursal -> getCalle());
        $emisor -> appendChild($emisor_calle);
        
        $emisor_numero_exterior = $XML -> createElement('numero_exterior', $sucursal -> getNumeroExterior());
        $emisor -> appendChild($emisor_numero_exterior);
        
        $emisor_numero_interior = $XML -> createElement('numero_interior', $sucursal -> getNumeroInterior());
        $emisor -> appendChild($emisor_numero_interior);
        
        $emisor_colonia = $XML -> createElement('colonia', $sucursal -> getColonia());
        $emisor -> appendChild($emisor_colonia);
        
        $emisor_localidad = $XML -> createElement('localidad', $sucursal -> getLocalidad());
        $emisor -> appendChild($emisor_localidad);
        
        $emisor_referecia = $XML -> createElement('referencia', $sucursal -> getReferencia());
        $emisor -> appendChild($emisor_referencia);
        
        $emisor_municipio = $XML -> createElement('municipio', $sucursal -> getMunicipio());
        $emisor -> appendChild($emisor_municipio);
        
        $emisor_estado = $XML -> createElement('estado', $sucursal -> getEstado());
        $emisor -> appendChild($emisor_estado);
        
        $emisor_pais = $XML -> createElement('pais', $sucursal -> getPais());
        $emisor -> appendChild($emisor_pais);
        
        $emisor_codigo_postal = $XML -> createElement('codigo_postal', $sucursal -> getCodigoPostal());
        $emisor -> appendChild($emisor_codigo_postal);
        
        $comprobante -> appendChild($emisor);
        
        $expedido_por = $xml -> createElement( 'expedido_por' );
        
        $expedido_por_calle = $XML -> createElement('calle');
        $expedido_por-> appendChild($expedido_por_calle);
        
        $expedido_por_numero_exterior = $XML -> createElement('numero_exterior');
        $expedido_por-> appendChild($expedido_por_numero_exterior);
        
        $expedido_por_numero_interior = $XML -> createElement('numero_interior');
        $expedido_por-> appendChild($expedido_por_numero_interior);
        
        $expedido_por_colonia = $XML -> createElement('colonia');
        $expedido_por-> appendChild($expedido_por_colonia);
        
        $expedido_por_localidad = $XML -> createElement('localidad');
        $expedido_por-> appendChild($expedido_por_localidad);
        
        $expedido_por_referecia = $XML -> createElement('referencia');
        $expedido_por-> appendChild($expedido_por_referencia);
        
        $expedido_por_municipio = $XML -> createElement('municipio');
        $expedido_por-> appendChild($expedido_por_municipio);
        
        $expedido_por_estado = $XML -> createElement('estado');
        $expedido_por-> appendChild($expedido_por_estado);
        
        $expedido_por_pais = $XML -> createElement('pais');
        $expedido_por-> appendChild($expedido_por_pais);
        
        $expedido_por_codigo_postal = $XML -> createElement('codigo_postal');
        $expedido_por-> appendChild($expedido_por_codigo_postal);
        
        $comprobante -> appendChild($expedido_por);
        
        $receptor = $xml -> createElement( 'receptor' ); 
        
        $receptor_razon_social = $XML -> createElement('razon_social', $cliente -> getRazonSocial());
        $receptor = $xml -> createElement($receptor_razon_social);
        
        $receptor_rfc = $XML -> createElement('rfc', $cliente -> getRfc());
        $receptor = $xml -> createElement($receptor_rfc);
        
        $receptor_calle = $XML -> createElement('calle', $cliente -> getCalle());
        $receptor = $xml -> createElement($receptor_calle);
        
        $receptor_numero_exterior = $XML -> createElement('numero_exterior', $cliente -> getNumeroExterior());
        $receptor = $xml -> createElement($receptor_numero_exterior);
        
        $receptor_numero_interior = $XML -> createElement('numero_interior', $cliente -> getNumeroInterior());
        $receptor = $xml -> createElement($receptor_numero_interior);
        
        $receptor_colonia = $XML -> createElement('colonia', $cliente -> getColonia());
        $receptor = $xml -> createElement($receptor_colonia);
        
        $receptor_localidad = $XML -> createElement('localidad', $cliente -> getLocalidad());
        $receptor = $xml -> createElement($receptor_localidad);
        
        $receptor_referecia = $XML -> createElement('referencia', $cliente -> getReferencia());
        $receptor = $xml -> createElement($receptor_referencia);
        
        $receptor_municipio = $XML -> createElement('municipio', $cliente -> getMunicipio());
        $receptor = $xml -> createElement($receptor_municipio);
        
        $receptor_estado = $XML -> createElement('estado', $cliente -> getEstado());
        $receptor = $xml -> createElement($receptor_estado);
        
        $receptor_pais = $XML -> createElement('pais', $cliente -> getPais());
        $receptor = $xml -> createElement($receptor_pais);
        
        $receptor_codigo_postal = $XML -> createElement('codigo_postal', $cliente -> getCodigoPostal());
        $receptor = $xml -> createElement($receptor_codigo_postal);
        
        $comprobante -> appendChild($receptor);
        
        $conceptos = $xml -> createElement( 'conceptos' );       
        
        foreach( $productos as $producto ){                       
        
            /*
              * verificamos si el articulo tiene algun proceso:
              *     si :    
              *         verificamos si se vendio  original (case 2)
              *         verificamos si se vendio procesado (case 3)
              *         verificamos si se vendieron ambos (case 4)
              *     no :
              *         solo extraemos la descripcion y la cantidad (original) y su precio  (case 1)
              *
              */
              
            
            
            //creamos un objeto inventario para verificar si tiene un proceso
            if( !( $inventario = InventarioDAO::getByPK( $producto -> getIdProducto() ) ) )
            {
                DAO::transRollback();
                Logger::log("Error al obtener datos de la sucursal.");
                die( '{"success": false, "reason": "Error al obtener datos de la sucursal." }' );
            }
            
            
            $venta_original = $producto -> getCantidad() > 0 ? true : false;
            $venta_procesada = $producto -> getCantidadProcesada() > 0 ? true : false;
            $proceso = $inventario -> getTratamiento =="limpia"? true : false;
                        
            if( $venta_procesada )
            {
            
                $concepto = $xml -> createElement( 'concepto' );
            
                $id_producto = $xml -> createElement( 'id_producto', $producto -> getIdProducto() );
                $concepto -> appendChild($id_producto);
                
                $cantidad = $xml -> createElement( 'cantidad', $producto -> getCantidadProcesada() - $producto -> getDescuento() );
                $concepto -> appendChild($cantidad);
                
                $unidad = $xml -> createElement( 'unidad', $inventaio -> getUnidad() );
                $concepto -> appendChild($unidad);        
                
                $descripcion = $xml -> createElement( 'descripcion', ucfirst( strtolower(  $inventaio -> getDescripcion() . " ". $inventario -> getTratamiento ) ) );
                $concepto -> appendChild($descripcion);        
                
                $valor = $xml -> createElement( 'valor', $producto -> getPrecioProcesada() );
                $concepto -> appendChild($valor);        
                
                $importe = $xml -> createElement( 'importe', ($producto -> getCantidad() - $producto -> getDescuento()) * $producto -> getPrecioProcesada() );
                $concepto -> appendChild($importe);        

                $conceptos -> appendChild($concepto);
                        
            }
            
            $concepto = $xml -> createElement( 'concepto' );            
            
            $id_producto = $xml -> createElement( 'id_producto', $producto -> getIdProducto() );
            $concepto -> appendChild($id_producto);
                
            $cantidad = $xml -> createElement( 'cantidad', $producto -> getCantidad() - $producto -> getDescuento() );
            $concepto -> appendChild($cantidad);
               
            $unidad = $xml -> createElement( 'unidad', $inventaio -> getUnidad() );
            $concepto -> appendChild($unidad);        
                
            $descripcion = $xml -> createElement( 'descripcion', $inventaio -> getDescripcion() );
            $concepto -> appendChild($descripcion);        
                
            $valor = $xml -> createElement( 'valor', $producto -> getPrecio() );
            $concepto -> appendChild($valor);        
                
            $importe = $xml -> createElement( 'importe', ($producto -> getCantidad() - $producto -> getDescuento()) * $producto -> getPrecio() );
            $concepto -> appendChild($importe);        
                                                              
            $conceptos -> appendChild($concepto);
            
        }                
        
        $comprobante -> appendChild($conceptos);
        
        $llaves = $xml -> createElement('llaves');
        
        $llaves_publica = $xml  -> createElement('publica',$pos_config -> publica);
        $llaves = appendChild($llaves_publica);
        
        $llaves_privada = $xml -> createElement('privada', $pos_config -> privada);
        $llaves = appendChild($llaves_privada);
        
        $llaves_noCertificado = $xml -> createElement('noCertificado', $pos_config -> noCertificado);
        $llaves = appendChild($llaves_noCertificado);
        
        $comprobante -> appendChild($llaves);
        
        $xml -> appendChild( $comprobante );                
        
        DAO::transEnd();
        Logger::log("Terminado proceso de parceo de venta a XML");                       
        return $xml -> saveXML();
    
    }
    
    /**
        * Extrae la informacion acerca de las llaves para solicitar
        */
    function getInformacionConfiguracion(){        
    
        $llaves = new stdClass();
                        

        if( !( $llaves -> privada = PosConfigDAO::getByPK( 'llave_privada' ) ) )
        {
            Logger::log("Error al obtener la llave privada");
            DAO::transRollback();
            die( '{"success": false, "reason": "Error al obtener la llave privada" }' );
        }
        
        if( !( $llaves -> publica = PosConfigDAO::getByPK( 'llave_publica' ) ) )
        {
            Logger::log("Error al obtener la llave publica");
            DAO::transRollback();
            die( '{"success": false, "reason": "Error al obtener la llave privada" }' );
        }
        
        if( !( $llaves -> noCertificado = PosConfigDAO::getByPK( 'noCertificado' ) ) )
        {
            Logger::log("Error al obtener el nuemro de certificado");
            DAO::transRollback();
            die( '{"success": false, "reason": "Error al obtener el numero de certificado" }' );
        } 
        
        return $llaves;
    }            
    
    /**
        * crea una factura en la BD y regresa una objeto con los tados de la factura realizada
        */
    function creaFacturaBD( $id_venta ){
    
        Logger::log("Iniciando proceso de creacion de factura en la BD");                       
        
        $factura = new FacturaVenta();
    
        $factura -> setIdVenta( $id_venta );
        $factura -> setIdUsuario( $_SESSION['userid'] );

        try
        {
            FacturaVentaDAO::save($factura);
        }catch(Exception $e)
        {
            Logger::log("Error al salvar la factura de la venta");
        }       
        
        Logger::log("Terminado proceso de creacion de factura en la BD");                       
    
        return $factura;
        
    }
        
    
    /**
        * Recibe un xml con el formato que necesita el web service para generar
        * una nueva factura electronica.
        */
    function getFacturaFromWebService( $xml_request ){
    
        //obtenemos la url del web service
        if( !( $url = PosConfigDAO::getByPK( 'url_timbrado' ) ) )
        {
            Logger::log("Error al obtener la url del ws");
            DAO::transRollback();
            die( '{"success": false, "reason": "Error al obtener la url del web service" }' );
        }  
        
        $client = new SoapClient($url);
    
		echo "antes de llamar";
	
        $result = $client->RececpcionComprobante(array('comprobante' => $xml_request));
		
		echo "despues de llamar";
		
		var_dump($result);
      
        //verificamos si la llamada fallo  
        if (is_soap_fault($result)) {
            trigger_error("La llamada al webservice ha fallado", E_USER_ERROR);
        }
    
        //analizamos el success del xml
        
        
        libxml_use_internal_errors(true);
		
		
        
        $xml_response->loadXML( $result -> RececpcionComprobanteResult);
        
        if (!$xml_response) {
            $e = "Error cargando XML\n";
            foreach(libxml_get_errors() as $error) {
                $e.= "\t" . $error->message;
            }
            
            Logger::log("Error al leer xml del web service : {$e} ");
            DAO::transRollback();
            die( '{"success": false, "reason": "Error al leer xml del web service : ' . $e . '" }' );
            
        }
        
        
        $params = $xml_response->getElementsByTagName('Complemento');
		
        $k = 0;
                
        foreach ($params as $param) 
        {
            $success = $params->item($k)->getAttribute('success');
        }
        
        if( $success == false || $success == "false"){
            Logger::log("Error al generar el xml del web service");
            DAO::transRollback();
            die( '{"success": false, "reason": "Error al generar el xml del web service" }' );
        }        
    
        return $xml_response -> saveXML();
    
    }
    
    /**
        * Recibe el id de un folio de una factura
        * y regresa un json con el formato necesario para generar una 
        * nueva factura en formato pdf.
        */
    function parseFacturaToJSON($xml_response){
    
        //obtenemos la url del logo
        if( !( $url_logo = PosConfigDAO::getByPK( 'url_logo' ) ) )
        {
            $url_logo = "http://t2.gstatic.com/images?q=tbn:ANd9GcTLzjmaR_M58RmjwRE_xXRziJBi68hMg898kvKtYLD1lQ22i7Br";
        }  
    
        $json = array();                
        
        /*array_push($json , "url" -> $url_logo);
        array_push("emisor",array(
            "razon_social"
        ));*/
        /**
            
            {
                "url" : "string",
                "emisor": {
                    "razon_social" : "string",
                    "rfc": "string",
                    "direccion": "string",
                    "folio": "string" 
                },
                "receptor": {
                    "razon_social" : "string",
                    "rfc": "string",
                    "direccion": "string" 
                },
                "datos_fiscales": {
                    "numero_certificado": "string",
                    "numero_aprobacion": "string",
                    "anio_aprobacion": "string",
                    "cadena_original": "string",
                    "sello_digital": "string",
                    "sello_digital_proveedor": "string",
                    "pac": "string" 
                },
                "factura": {
                    "productos": [
                        {
                            "cantidad": "string",
                            "descripcion": "string",
                            "precio": "string",
                            "importe": "string" 
                        } 
                    ],
                    "subtotal": "string",
                    "descuento": "string",
                    "iva": "string",
                    "total": "string",
                    "total_letra": "string",
                    "forma_pago": "string",
                    "metodo_pago": "string" 
                }
            }
            
            */
        
    
        return json_encode($json);
    
    }        
    
    /**
      * cancela una factura
      */
    function cancelaFactura($args)
    {      
        return printf("{success : true}");        
    }

    /**
        * Reimprime una factura
        */
    function reimprimirFactura($args)
    {
    
        //verificamos que el folio de la factura se haya especificado
        if( !isset($args['id_folio']) )
        {
            Logger::log("No se indico el id del folio de factura qeu se decea reimprimir");
            die( '{"success": false, "reason": "No se indico el folio de la factura que se desea reimprimir." }' );       
        }
        
        //validamos que el id del folio sea nuemrico
        if( is_nan( $args['id_folio'] ) )
        {
             Logger::log("Verifique el id del folio de la factura");
            die( '{"success": false, "reason": "Verifique el id del folio de la factura." }' );       
        }
        
        //verificamos que exista ese folio
        if( !( $factura = FacturaVentaDAO::getByPK( $args['id_folio'] ) ) )
        { 
            Logger::log("No se tiene registro del folio : {$args['id_folio']}.");
            die( '{"success": false, "reason": "No se tiene registro del folio : ' . $args['id_folio'] . '." }' );
        }
        
        //verificamos que la factura este activa
        if( $factura -> getActiva() != "1" ){
            Logger::log("La factura : {$args['id_folio']} no se puede reimprimir debido a que ha sido cancelada.");
            die( '{"success": false, "reason": "La factura ' . $args['id_folio'] . ' no se puede imprimir debido a que ha sido cancelada." }' );
        }
        
        $json = parseFacturaToJSON($args['id_folio']);
        
        //llamada a la funcion que reimprime la factura
        
    }


    /**
        *
        */
    if( isset( $args['action'] ) )
    {
        switch( $args['action'] )
        {
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
