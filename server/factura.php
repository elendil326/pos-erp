<?php

    require_once("model/ventas.dao.php");
    require_once("model/cliente.dao.php");
    require_once("model/factura_venta.dao.php");
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
        if( !( $venta = VentaDAO::getByPK( $args['id_venta'] ) ) )
        {
            Logger::log("No se tiene registro de la venta : {$args['id_venta']}");
            die( '{"success": false, "reason": "No se tiene registro de la venta ' . $args['id_venta'] . '." }' );
        }
           
        //verificamos que el cliente exista
        if( !( $cliente = ClienteDAO::getByPK( $args['id_cliente'])) ) ){
            Logger::log("No se tiene registro del cliente : {$args['id_cliente']}.");
            die( '{"success": false, "reason": "No se tiene registro del cliente ' . $args['id_cliente'] . '." }' );
        }
        
        //verifiacmos que la venta este liquidada
        if( $venta->getLiquidada() != "1")
        {
            Logger::log("La venta {$venta - > getIdVenta()} no esta lioquidada");
            die( '{"success": false, "reason": "No se puede emitir la factura debido a que la venta ' . $venta - > getIdVenta() . ' no ha sido liquidada." }' );
        }
        
        //verificamos que la venta no este facturada
        $fv = new FacturaVentaDAO();
        $fv -> setIdVenta( $venta - > getIdVenta() );
        
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
        $xml = parseVentaToXML($args['id_venta'] );
                

        //Realizamos una peticion al webservice para que se genere una nueva factura en el sistema.
        $id_folio = nuevaFactura( $xml );        
        
        //genera un json con todos los datos necesarios para generar el PDF de la factura electronica
        $json_factura = parseFacturaToJSON($id_folio);
        
        //llamar al metodo que genera el pdf
        
        Logger::log("Terminando proceso de facturacion");
        
    }
    
    /**
        * Recibe el id de una venta, extrae sus datos y regresa un xml con el formato que
        * se necesita enviar al web service.
        */
    function parseVentaToXML($id_venta){
    
        return $xml;
    
    }
    
    /**
        * Recibe un xml con el formato que necesita el web service para generar
        * una nueva factura electronica.
        */
    function nuevaFactura($xml){
    
        return $id_folio;
    
    }
    
    /**
        * Recibe el id de un folio de una factura
        * y regresa un json con el formato necesario para generar una 
        * nueva factura en formato pdf.
        */
    function parseFacturaToJSON($id_folio){
    
        return $json;
    
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
        if( !( $factura = FacturaVentaDAO::getByPK( $args['id_folio'])) ) )
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
