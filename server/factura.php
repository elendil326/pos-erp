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
        $fv = new FacturaVenta();
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
            die( '{"success": false, "reason": "Actualice la informacion del cliente : La razon social del cliente es demaciado corta." }' );                
        }
        
            //rfc
         if( strlen( $cliente -> getRfc() ) < 13 || strlen( $cliente -> getRfc() ) > 13 ){
             Logger::log("verifique la estructura del rfc : 4 caracteres para el nombre, 6 para la fecha y 3 para la homoclave");
            die( '{"success": false, "reason": "Actualice la informacion del cliente : Verifique la estructura del rfc : 4 caracteres para el nombre, 6 para la fecha y 3 para la homoclave." }' );
        }
            //calle
         if( strlen( $cliente -> getCalle() ) < 3  ){
            Logger::log("La descripcion de la calle es demaciado corta.");
            die( '{"success": false, "reason": "Actualice la informacion del cliente : La descripcion de la calle es demaciado corta." }' );                
        }
            //numero exterior
         if( strlen( $cliente -> getNumeroExterior() ) == 0  ){
           Logger::log("Indique el numero exterior del domicilio");
            die( '{"success": false, "reason": "Actualice la informacion del cliente : Indique el numero exterior del domicilio." }' );  
        }
            //colonia
         if( strlen( $cliente -> getColonia() ) < 3  ){
           Logger::log("La descripcion de la colonia es demaciado corta.");
            die( '{"success": false, "reason": "Actualice la informacion del cliente : La descripcion de la colonia es demaciado corta." }' );       
        }
            //municipio
         if( strlen( $cliente -> getMunicipio() ) < 3  ){
            Logger::log("La descripcion del municipio es demaciado corta.");
            die( '{"success": false, "reason": "Actualice la informacion del cliente : La descripcion del municipio es demaciado corta." }' );       
        }
            //estado
         if( strlen( $cliente -> getEstado() ) < 3  ){
            Logger::log("La descripcion del estado es demaciado corta.");
            die( '{"success": false, "reason": "Actualice la informacion del cliente : La descripcion del estado es demaciado corta." }' );       
        }
            //codigo postal
         if( strlen( $cliente -> getCodigoPostal() ) == 0  ){
             Logger::log("Indique el codigo postal.");
            die( '{"success": false, "reason": "Actualice la informacion del cliente : Indique el codigo postal." }' );       
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
        if( !( $venta = VentasDAO::getByPK( $id_venta ) ) )
        {
            Logger::log("Error al obtener datos de la venta : {$id_venta}");
            die( '{"success": false, "reason": "Error al obtener datos de la venta ' . $id_venta . '." }' );
        }    
        
        //obtenemos el objeto cliente
        if( !( $cliente = ClienteDAO::getByPK( $id_cliente ) ) )
        {
            Logger::log("Error al obtener datos del cliente{$id_cliente}");
            die( '{"success": false, "reason": "Error al obtener datos del cliente ' . $id_cliente . '." }' );
        }            

        //obtenemos el detalle de la venta
        $detalle_venta = new DetalleVenta();
        $detalle_venta -> setIdVenta( $venta -> getIdVenta() );
        
        $productos = DetalleVentaDAO::search( $detalle_venta );                
        
	    if(count($productos) <= 0)
	    {
		    Logger::log("Error al obtener el detalle de la venta: {$id_venta}");
            die( '{"success": false, "reason": "Error al obtener el detalle de la venta ' . $id_venta . '." }' );
	    }

        DAO::transBegin();
        //DAO::transRollback();
        
        //creamos una nueva factura en la BD para obtener el folio
        $factura = creaFacturaBD( $venta -> getIdVenta() ) ;
        
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
                  
        $comprobante -> appendChild($xml -> createElement( 'serie', $sucursal -> getLetrasFactura()));
                
        $comprobante -> appendChild($xml -> createElement( 'folio_interno', $factura -> getIdFolio()));
               
        $comprobante -> appendChild($xml -> createElement( 'fecha', date("y-m-d").'T'.date("H:i:s")));
        
        $comprobante -> appendChild($xml -> createElement( 'forma_de_pago','Pago en una sola exhibicion'));
        
        $comprobante -> appendChild($xml -> createElement( 'metodo_de_pago', ucfirst( strtolower( $venta -> getTipoPago() ) ) ));
                
        $comprobante -> appendChild($xml -> createElement( 'subtotal', $venta -> getSubtotal() ));
        
        $comprobante -> appendChild($xml -> createElement( 'total', $venta -> getTotal() ));
        
        $comprobante -> appendChild($xml -> createElement( 'iva', $venta -> getIva() ));
        
        $emisor = $xml -> createElement( 'emisor' );                
                
        $emisor -> appendChild($xml -> createElement('razon_social', $sucursal -> getRazonSocial()));
        
        $emisor -> appendChild($xml -> createElement('rfc', $sucursal -> getRfc()));
               
        $emisor -> appendChild($xml -> createElement('calle',$sucursal -> getCalle()));
                
        $emisor -> appendChild($xml -> createElement('numero_exterior', $sucursal -> getNumeroExterior()));
                
        $emisor -> appendChild($xml -> createElement('numero_interior', $sucursal -> getNumeroInterior()));
                
        $emisor -> appendChild($xml -> createElement('colonia', $sucursal -> getColonia()));
                
        $emisor -> appendChild($xml -> createElement('localidad', $sucursal -> getLocalidad()));
                
        $emisor -> appendChild($xml -> createElement('referencia', $sucursal -> getReferencia()));
                
        $emisor -> appendChild($xml -> createElement('municipio', $sucursal -> getMunicipio()));
                
        $emisor -> appendChild($xml -> createElement('estado', $sucursal -> getEstado()));
                
        $emisor -> appendChild($xml -> createElement('pais', $sucursal -> getPais()));
                
        $emisor -> appendChild($xml -> createElement('codigo_postal', $sucursal -> getCodigoPostal()));
        
        $comprobante -> appendChild($emisor);
        
        $expedido_por = $xml -> createElement( 'expedido_por' );
                
        $expedido_por -> appendChild($xml -> createElement('calle'));
                
        $expedido_por -> appendChild($xml -> createElement('numero_exterior'));
                
        $expedido_por -> appendChild($xml -> createElement('numero_interior'));
                
        $expedido_por -> appendChild($xml -> createElement('colonia'));
                
        $expedido_por -> appendChild($xml -> createElement('localidad'));
                
        $expedido_por -> appendChild($xml -> createElement('referencia'));
                
        $expedido_por -> appendChild($xml -> createElement('municipio'));
                
        $expedido_por -> appendChild($xml -> createElement('estado'));
                
        $expedido_por -> appendChild($xml -> createElement('pais'));
                
        $expedido_por -> appendChild($xml -> createElement('codigo_postal'));
        
        $comprobante -> appendChild($expedido_por);
        
        $receptor = $xml -> createElement( 'receptor' ); 
		        
        $receptor -> appendChild($xml -> createElement('razon_social', $cliente -> getRazonSocial()));
                
        $receptor -> appendChild($xml -> createElement('rfc', $cliente -> getRfc()));
                
        $receptor -> appendChild($xml -> createElement('calle', $cliente -> getCalle()));
                
        $receptor -> appendChild($xml -> createElement('numero_exterior', $cliente -> getNumeroExterior()));
                
        $receptor -> appendChild($xml -> createElement('numero_interior', $cliente -> getNumeroInterior()));
                
        $receptor -> appendChild($xml -> createElement('colonia', $cliente -> getColonia()));
                
        $receptor -> appendChild($xml -> createElement('localidad', $cliente -> getLocalidad()));
                
        $receptor -> appendChild($xml -> createElement('referencia', $cliente -> getReferencia()));
                
        $receptor -> appendChild($xml -> createElement('municipio', $cliente -> getMunicipio()));
                
        $receptor -> appendChild($xml -> createElement('estado', $cliente -> getEstado()));
                
        $receptor -> appendChild($xml -> createElement('pais', $cliente -> getPais()));
                
        $receptor -> appendChild($xml -> createElement('codigo_postal', $cliente -> getCodigoPostal()));
        
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
            $proceso = $inventario -> getTratamiento() == "limpia"? true : false;
                        
            if( $venta_procesada )
            {
            
                $concepto = $xml -> createElement( 'concepto' );
                            
                $concepto -> appendChild($xml -> createElement( 'id_producto', $producto -> getIdProducto() ));
                                
                $concepto -> appendChild($xml -> createElement( 'cantidad', $producto -> getCantidadProcesada() - $producto -> getDescuento() ));
                                
                $concepto -> appendChild($xml -> createElement( 'unidad', $inventario -> getEscala() ));        
                                
                $concepto -> appendChild($xml -> createElement( 'descripcion', ucfirst( strtolower(  $inventario -> getDescripcion() . " ". $inventario -> getTratamiento() ) ) ));        
                                
                $concepto -> appendChild($xml -> createElement( 'valor', $producto -> getPrecioProcesada() ));        
                                
                $concepto -> appendChild($xml -> createElement( 'importe', ($producto -> getCantidad() - $producto -> getDescuento()) * $producto -> getPrecioProcesada() ));        

                $conceptos -> appendChild($concepto);
                        
            }
            
            $concepto = $xml -> createElement( 'concepto' );            
                        
            $concepto -> appendChild($xml -> createElement( 'id_producto', $producto -> getIdProducto() ));
                            
            $concepto -> appendChild($xml -> createElement( 'cantidad', $producto -> getCantidad() - $producto -> getDescuento() ));
                           
            $concepto -> appendChild($xml -> createElement( 'unidad', $inventario -> getEscala() ));        
                            
            $concepto -> appendChild($xml -> createElement( 'descripcion', $inventario -> getDescripcion() ));        
                            
            $concepto -> appendChild($xml -> createElement( 'valor', $producto -> getPrecio() ));        
                            
            $concepto -> appendChild($xml -> createElement( 'importe', ($producto -> getCantidad() - $producto -> getDescuento()) * $producto -> getPrecio() ));        
                                                              
            $conceptos -> appendChild($concepto);
            
        }                
        
        $comprobante -> appendChild($conceptos);
        
        $llaves = $xml -> createElement('llaves');
                
        $llaves -> appendChild($xml  -> createElement('publica',$pos_config -> publica));
                
        $llaves -> appendChild($xml -> createElement('privada', $pos_config -> privada));
                
        $llaves -> appendChild($xml -> createElement('noCertificado', $pos_config -> noCertificado));
        
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
                        

        if( !( $llaves -> privada = PosConfigDAO::getByPK( 'llave_privada' ) -> getValue() ) )
        {
            Logger::log("Error al obtener la llave privada");
            DAO::transRollback();
            die( '{"success": false, "reason": "Error al obtener la llave privada" }' );
        }
        
        if( !( $llaves -> publica = PosConfigDAO::getByPK( 'llave_publica' ) -> getValue() ) )
        {
            Logger::log("Error al obtener la llave publica");
            DAO::transRollback();
            die( '{"success": false, "reason": "Error al obtener la llave privada" }' );
        }
        
        if( !( $llaves -> noCertificado = PosConfigDAO::getByPK( 'noCertificado' ) -> getValue() ) )
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
    
		$xml_request = utf8_encode($xml_request);			
	
        //obtenemos la url del web service
        if( !( $url = PosConfigDAO::getByPK( 'url_timbrado' ) ) )
        {
            Logger::log("Error al obtener la url del ws");
            DAO::transRollback();
            die( '{"success": false, "reason": "Error al obtener la url del web service" }' );
        }  
				
		
        $client = new SoapClient($url -> getValue());	
		
		echo (string)$xml_request;
		
		//var_dump($xml_request);
	
        $result = $client->RececpcionComprobante(array('comprobante'=>$xml_request));
		
		echo "despues de llamar";
      
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
