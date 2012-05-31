<?php

//require_once("librerias/CNumeroaLetra.php");
define("DONT_USE_HTML", 1);

require_once("libs/ezpdf/class.pdf.php");
require_once("libs/ezpdf/class.ezpdf.php");

class ImpresionesController {


	
	private static function puntos_cm($medida, $resolucion=72) {
	    //// 2.54 cm / pulgada
	    //se supone ke la pagina mide 29.7 cm por 21.0 cm
	    //$medida += 29.7;
	    return ( $medida / (2.54) ) * $resolucion;
	}

	private static function readableText($bar) {
	    $foo = explode(" ", $bar);
	    $end = "";
	    foreach ($foo as $i) {
	        $end .= ucfirst(strtolower(trim($i))) . " ";
	    }

	    return trim($end);
	}

	private static function formatAddress($id_direccion) {
		if( is_null($daoDireccion = DireccionDAO::getByPK( $id_direccion ))){
			throw new InvalidDataException("Esta direccion no existe.");
		}

        $out = self::readableText($daoDireccion->getCalle()) . " " . $daoDireccion->getNumeroExterior();

        if ($daoDireccion->getNumeroInterior() != null)
            $out .= "\n" . self::readableText($daoDireccion->getNumeroInterior());

        $out .= " " . self::readableText($daoDireccion->getColonia());

        if (strlen($daoDireccion->getCodigoPostal()) > 0) {
            $out .= " C.P. " . $daoDireccion->getCodigoPostal() . "\n";
        }

        if (!is_null($daoDireccion->getIdCiudad())) {
            $out .= self::readableText($daoDireccion->getIdCiudad()) . ", ";
            $out .= self::readableText("d->getEstado()") . ", ";
        }


        $out .= self::readableText("Mexico") . "\n";
    

	    return $out;
	}

	private static function roundRect($pdf, $x, $y, $w, $h) {

	    $pdf->setStrokeColor(0.3359375, 0.578125, 0.89453125);
	    $pdf->setLineStyle(1);

	    $x -= self::puntos_cm(0.1);
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





	private static function createPdf( $title = ""){

	    $pdf = new Cezpdf( $paper = 'letter');

		if (is_file(POS_PATH_TO_SERVER_ROOT . "libs/ezpdf/fonts/Helvetica.afm")){
			$pdf->selectFont(POS_PATH_TO_SERVER_ROOT . "libs/ezpdf/fonts/Helvetica.afm");
			
		}else{
			throw new Exception();
			
		}

	    //margenes de un centimetro para toda la pagina
	    $pdf->ezSetMargins(1, 1, 1, 1);
	    /**************************
	     * ENCABEZADO
	     ***************************/
	    $pdf->addText( self::puntos_cm(7.1), self::puntos_cm(26.1), 18, utf8_decode($title));
	    return $pdf;
	}


	private static function drawBasicGuide( &$pdf ){

	    /**************************
	     * LOGO
	     **************************/

		$logo = "../static/".IID.".jpg";

	    if (substr($logo, -3) == "jpg" || substr($logo, -3) == "JPG" || substr($logo, -4) == "jpeg" || substr($logo, -4) == "JPEG") {
	        $pdf->addJpegFromFile($logo, self::puntos_cm(2), self::puntos_cm(24), self::puntos_cm(4.1));

				
	    } elseif (substr($logo, -3) == "png" || substr($logo, -3) == "PNG") {
	        $pdf->addPngFromFile($logo, self::puntos_cm(2), self::puntos_cm(24), self::puntos_cm(4.1));



			Logger::log("png");	
			
	    } else {
	        Logger::log("Verifique la configuracion del pos_config, la extension de la imagen del logo no es compatible");
	        die("La extension de la imagen usada para el logo del negocio no es valida.");
	    }


		 self::roundRect($pdf, self::puntos_cm(14.2), self::puntos_cm(26.8), self::puntos_cm(4), self::puntos_cm(4.25));
		 self::roundRect($pdf, self::puntos_cm(2), self::puntos_cm(22), self::puntos_cm(16.2), self::puntos_cm(3.2));
		 self::roundRect($pdf, self::puntos_cm(2), self::puntos_cm(18.6), self::puntos_cm(16.2), self::puntos_cm(16.0));


 	    /*     * ************************
	     * notas de abajo
	     * ************************* */
	    $pdf->setLineStyle(1);
	
	    $pdf->setStrokeColor(0.3359375, 0.578125, 0.89453125);

	    $pdf->line( self::puntos_cm(1.9), self::puntos_cm(2.0), self::puntos_cm(18.1), self::puntos_cm(2.0));

	    $pdf->addText( self::puntos_cm(2), self::puntos_cm(1.61), 7, "Fecha de impresion: " . date('Y-m-d'));

	    $pdf->addJpegFromFile("../www/media/logo_simbolo.jpg", self::puntos_cm(15.9), self::puntos_cm(.95), 25);

	    $pdf->addText( self::puntos_cm(16.70), self::puntos_cm(1.30), 8, "caffeina.mx");	
	}


	private static function printProducts( &$pdf, $productos ){
	    $opciones_tabla = array();
	    $opciones_tabla['showLines'] = 0;
	    $opciones_tabla['showHeadings'] = 0;
	    $opciones_tabla['shaded'] = 0;
	    $opciones_tabla['fontSize'] = 8;
	    $opciones_tabla['xOrientation'] = 'right';
	    $opciones_tabla['xPos'] = self::puntos_cm(7.5);
	    $opciones_tabla['width'] = self::puntos_cm(11);
	    $opciones_tabla['textCol'] = array(0, 0, 0);
	    $opciones_tabla['titleFontSize'] = 12;
	    $opciones_tabla['rowGap'] = 3;
	    $opciones_tabla['colGap'] = 3;
	    $opciones_tabla['xPos'] = self::puntos_cm(14.2);
	    
	    $opciones_tabla['showLines'] = 0;
	    $opciones_tabla['shaded'] = 2;
	    $opciones_tabla['shadeCol'] = array(1, 1, 1);
	    $opciones_tabla['shadeCol2'] = array(0.8984375, 0.95703125, 0.99609375);

		/* *************************
	     * PRODUCTOS
	     * ************************* */
	    $elementos = array(
	        array('cantidad' => 'Cantidad',
	            'descripcion' => 'Descripcion                                                                                                     ', 'precio' => 'Precio', 'importe' => 'Importe'),
	    );

	    $subtotal = 0;
	    $total = 0;
	    $impuesto = 0;

	    foreach ($productos as $p) {

	        	$prodDao = ProductoDAO::getByPK( $p->getIdProducto() );

	            $prod['cantidad'] = $p->getCantidad();
	            $prod['descripcion'] = $prodDao->getNombreProducto();
	            $prod['precio'] = FormatMoney($p->getPrecio(), DONT_USE_HTML);
	            $prod['importe'] = FormatMoney($p->getPrecio() * $p->getCantidad(), DONT_USE_HTML);


	            $subtotal +=  ($p->getPrecio() * $p->getCantidad());
	            $total = $subtotal;

	            array_push($elementos, $prod);
	        
	    }



	    array_push($elementos, array("cantidad" => "",
	        "descripcion" => "",
	        "precio" => "Subtotal",
	        "importe" => FormatMoney($subtotal, DONT_USE_HTML)));

	    array_push($elementos, array("cantidad" => "",
	        "descripcion" => "",
	        "precio" => "Descuento",
	        "importe" => FormatMoney(0, DONT_USE_HTML)));

	    array_push($elementos, array("cantidad" => "",
	        "descripcion" => "",
	        "precio" => "IVA",
	        "importe" => FormatMoney(0, DONT_USE_HTML)));

	    array_push($elementos, array("cantidad" => "",
	        "descripcion" => "",
	        "precio" => "Total",
	        "importe" => FormatMoney($total, DONT_USE_HTML)));

		$letra = new CNumeroaLetra();
		$letra->setNumero($total);
		
	    array_push($elementos, array("cantidad" => "",
	        "descripcion" => $letra->letra(),
	        "precio" => "",
	        "importe" => ""));		
		
	    $pdf->ezSetY(self::puntos_cm(18.6));
	
	    $opciones_tabla['xPos'] = self::puntos_cm(2);
	    $opciones_tabla['width'] = self::puntos_cm(16.2);

	    $pdf->ezTable($elementos, "", "", $opciones_tabla);
	}


	private static function printClient( &$pdf, $daoCliente){



	    /* *************************
	     * Cliente
	     * ************************* */
	    $datos_receptor = $daoCliente->getNombre() . "\n";
	    $datos_receptor .= $daoCliente->getRfc();
            
        if( $direccion =  DireccionDAO::getByPK($daoCliente->getIdDireccion())){
            
            $datos_receptor .= $direccion->getCalle() . " ";
            $datos_receptor .= $direccion->getNumeroExterior() . " ";
            
            if($direccion->getNumeroInterior()){
                $datos_receptor .= (" INT " . $direccion->getNumeroInterior() . " ");
            }
            
            if($direccion->getColonia()){
                $datos_receptor .= ("Col. " . $direccion->getColonia() . "\n");
            }
            
            if($direccion->getCodigoPostal()){
                $datos_receptor .= ( "CP " . $direccion->getCodigoPostal());
            }
                            
            $datos_receptor .= $direccion->getIdCiudad();
            
        }



	    $receptor = array(
	        array("receptor" => "<b>Cliente</b>"),
	        array("receptor" => $datos_receptor),
	    );

	    
	    $opciones_tabla['showHeadings'] = 0;
	    $opciones_tabla['shaded'] = 0;
	    $opciones_tabla['fontSize'] = 8;
	    $opciones_tabla['xOrientation'] = 'right';
	    $opciones_tabla['xPos'] = self::puntos_cm(7.5);
	    $opciones_tabla['width'] = self::puntos_cm(11);
	    $opciones_tabla['textCol'] = array(0, 0, 0);
	    $opciones_tabla['titleFontSize'] = 12;
	    $opciones_tabla['rowGap'] = 3;
	    $opciones_tabla['colGap'] = 3;
	    $opciones_tabla['xPos'] = self::puntos_cm(14.2);
	    $opciones_tabla['width'] = self::puntos_cm(4);
	    $opciones_tabla['showLines'] = 0;
	    $opciones_tabla['shaded'] = 2;
	    $opciones_tabla['shadeCol'] = array(1, 1, 1);
	    $opciones_tabla['shadeCol2'] = array(0.8984375, 0.95703125, 0.99609375);
	    $opciones_tabla['xPos'] = self::puntos_cm(14.2);
	    $opciones_tabla['width'] = self::puntos_cm(4);
	    $opciones_tabla['showLines'] = 0;
	    $opciones_tabla['shaded'] = 2;
	    $opciones_tabla['shadeCol'] = array(1, 1, 1);
	    $opciones_tabla['shadeCol2'] = array(0.8984375, 0.95703125, 0.99609375);
	    $pdf->ezSetY(self::puntos_cm(22));
	    $opciones_tabla['xPos'] = self::puntos_cm(2);
	    $opciones_tabla['width'] = self::puntos_cm(16.2);
	    $opciones_tabla['showLines'] = 0;

	    $pdf->ezTable($receptor, "", "", $opciones_tabla);
	}


	public static function Venta($id_venta) {
		
		$ventaDao = VentaDAO::getByPK($id_venta);
		$clienteDao = UsuarioDAO::getByPK( $ventaDao->getIdCompradorVenta() );
		$agenteDao = UsuarioDAO::getByPK( $ventaDao->getIdUsuario() );
		$ventaProductoDaoArray = VentaProductoDAO::search( new VentaProducto( array(
				"id_venta" => $id_venta
			) ) );

		if($ventaDao->getEsCotizacion()){
			$pdf =	self::createPdf("Cotizacion");

		}else{
			$pdf =	self::createPdf("Nota de venta");	

		}


	    $opciones_tabla = array();
	    $opciones_tabla['showLines'] = 0;
	    $opciones_tabla['showHeadings'] = 0;
	    $opciones_tabla['shaded'] = 0;
	    $opciones_tabla['fontSize'] = 8;
	    $opciones_tabla['xOrientation'] = 'right';
	    $opciones_tabla['xPos'] = self::puntos_cm(7.5);
	    $opciones_tabla['width'] = self::puntos_cm(11);
	    $opciones_tabla['textCol'] = array(0, 0, 0);
	    $opciones_tabla['titleFontSize'] = 12;
	    $opciones_tabla['rowGap'] = 3;
	    $opciones_tabla['colGap'] = 3;

	    
		if($ventaDao->getEsCotizacion()){
			$datos = array(
		        array("col" => "<b>Cotizacion</b>"),
		        array("col" =>  $ventaDao->getIdVenta()),

		        array("col" => "<b>Numero de orden</b>"),
		        array("col" =>  "" ),
			
		        array("col" => "<b>Fecha de venta</b>"),
		        array("col" => $ventaDao->getFecha()),

		        array("col" => "<b>Agente que cotizo</b>"),
		        array("col" => self::readableText(  $agenteDao->getNombre() ))
	    	);

		}else{

			$datos = array(
		        array("col" => "<b>Venta</b>"),
		        array("col" =>  $ventaDao->getIdVenta()),

		        array("col" => "<b>Numero de orden</b>"),
		        array("col" =>  "" ),
			
		        array("col" => "<b>Fecha de venta</b>"),
		        array("col" => $ventaDao->getFecha()),

		        array("col" => "<b>Agente de venta</b>"),
		        array("col" => self::readableText(  $agenteDao->getNombre() ))
	    	);
		}
		
	 

	    $pdf->ezSetY(self::puntos_cm(26.8));

	    $opciones_tabla['xPos'] = self::puntos_cm(14.2);
	    $opciones_tabla['width'] = self::puntos_cm(4);
	    $opciones_tabla['showLines'] = 0;
	    $opciones_tabla['shaded'] = 2;
	    $opciones_tabla['shadeCol'] = array(1, 1, 1);
	    $opciones_tabla['shadeCol2'] = array(0.8984375, 0.95703125, 0.99609375);
	    $pdf->ezTable($datos, "", "", $opciones_tabla);

		self::printProducts($pdf, $ventaProductoDaoArray);

		self::printClient($pdf, $clienteDao);

		self::drawBasicGuide( $pdf );


	    $pdf->ezStream();


	}




	public static function OrdenDeServicio( $id_orden ) {

		//obtengamos datos de esta orden de servicio
		//el cliente
		$daoOrden = OrdenDeServicioDAO::getByPK($id_orden);
		$daoCliente = UsuarioDAO::getByPK( $daoOrden->getIdUsuarioVenta() );
		$daoAgente = UsuarioDAO::getByPK( $daoOrden->getIdUsuario() );
		$daoArrayVentaOrden = VentaOrdenDAO::search( new VentaOrden( array( "id_orden_de_servicio" => $id_orden  ) ) );
		$daoVentaOrden = $daoArrayVentaOrden[0];
		$daoVenta = VentaDAO::getByPK($daoVentaOrden->getIdVenta());
		$daoSucursal = SucursalDAO::getByPK($daoVenta->getIdSucursal());
		$daoServicio = ServicioDAO::getByPK( $daoOrden->getIdServicio() );
		

		$pdf = self::createPdf("Orden de servicio");

		self::printClient($pdf, $daoCliente);
	    

	    /**************************
	     * TITULO
	     * Datos del emisor, lugar de expedicion, folio, fecha de emision, no de serie
	     * del certificado del contribuyente
	     * **************************/            
            
	    $e = ""; //"<b>" . self::readableText("caffeina software") . "</b>\n";
	    /*$e .= self::formatAddress(415);
	    $e .= "RFC: " . "RFC";

	    //datos de la sucursal
		if(!is_null($daoSucursal)){
	    	$e .= "\n\n<b>Lugar de expedicion</b>\n";
	    	$e .= self::formatAddress( 415 );
		}
		*/

	    $datos = array(
	        array(
	            "emisor" => $e,
	      //      'sucursal' => $s,
	        )
	    );

	    
	    $opciones_tabla = array();
	    $opciones_tabla['showLines'] = 0;
	    $opciones_tabla['showHeadings'] = 0;
	    $opciones_tabla['shaded'] = 0;
	    $opciones_tabla['fontSize'] = 8;
	    $opciones_tabla['xOrientation'] = 'right';
	    $opciones_tabla['xPos'] = self::puntos_cm(7.5);
	    $opciones_tabla['width'] = self::puntos_cm(11);
	    $opciones_tabla['textCol'] = array(0, 0, 0);
	    $opciones_tabla['titleFontSize'] = 12;
	    $opciones_tabla['rowGap'] = 3;
	    $opciones_tabla['colGap'] = 3;

	    $pdf->ezTable($datos, "", "", $opciones_tabla);
	

	    $cajero = UsuarioDAO::getByPK( $daoVenta->getIdUsuario() );
		

		
	    $datos = array(
	        array("col" => "<b>Servicio</b>"),
	        array("col" =>  $daoServicio->getCodigoServicio()),

	        array("col" => "<b>Numero de orden</b>"),
	        array("col" =>  $daoOrden->getIdOrdenDeServicio() ),
		
	        array("col" => "<b>Fecha de venta</b>"),
	        array("col" => $daoVenta->getFecha()),

	        array("col" => "<b>Agente de venta</b>"),
	        array("col" => self::readableText(  $cajero->getNombre() ))
	    );

	    $pdf->ezSetY(self::puntos_cm(26.8));

	    $opciones_tabla['xPos'] = self::puntos_cm(14.2);
	    $opciones_tabla['width'] = self::puntos_cm(4);
	    $opciones_tabla['showLines'] = 0;
	    $opciones_tabla['shaded'] = 2;
	    $opciones_tabla['shadeCol'] = array(1, 1, 1);
	    $opciones_tabla['shadeCol2'] = array(0.8984375, 0.95703125, 0.99609375);
	    $pdf->ezTable($datos, "", "", $opciones_tabla);

	    



	    /* ************************
	     * PRODUCTOS
	     * ************************* */

	    $elementos = array(
	        array(  'cantidad' 	=> '',
	            	'unidad'		=>''
				)
		);


		$eP = $daoOrden->getExtraParams();
		
		if(!is_null($eP)){
			$ePObj = json_decode($eP);
			foreach ($ePObj as $obj) {
				$row["cantidad"] = "<b>" . $obj->desc . "</b>";
				$row["unidad"] = $obj->value;				
				$row["agrupacion"] = "";
				$row["descripcion"] = "";
				$row["precio"] = "";				
				$row["importe"] = "";				
				array_push($elementos, $row);	
			}
		}
		
		


	    $pdf->ezText("", 10, array('justification' => 'center'));
	    $pdf->ezSetY(self::puntos_cm(18.6));
	
	    $opciones_tabla['xPos'] = self::puntos_cm(2);
	    $opciones_tabla['width'] = self::puntos_cm(16.2);
	
	    $pdf->ezTable($elementos, "", "", $opciones_tabla);

		self::drawBasicGuide($pdf);

	    $pdf->ezStream();


	    return;
	}







	/**
	 * Crea un pdf con el estado de cuenta de el cliente especificado
	 * @param Array $args,  $args['id_cliente'=>12[,'tipo_venta'=> 'credito | contado | saldo'] ], por default obtiene todas las compras del cliente
	 */
	public static function imprimirEstadoCuentaCliente($args) {

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
	    $e = "<b>" . self::readableText($emisor->nombre) . "</b>\n";
	    $e .= formatAddress($emisor);
	    $e .= "RFC: " . $emisor->rfc . "\n\n";

	    //datos de la sucursal
	    $e .= "<b>Lugar de expedicion</b>\n";
	    $e .= self::readableText($sucursal->getDescripcion()) . "\n";
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
	        array("col" => self::readableText($cajero)),
	        array("col" => "<b>Cliente</b>"),
	        array("col" => self::readableText($cliente->getRazonSocial())),
	        array("col" => "<b>Limite de  Credito</b>"),
	        array("col" => FormatMoney($estado_cuenta->limite_credito, DONT_USE_HTML)),
	        array("col" => "<b>Saldo</b>"),
	        array("col" => FormatMoney($estado_cuenta->saldo, DONT_USE_HTML))
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
	        $array_venta['sucursal'] = self::readableText($venta['sucursal']);
	        $array_venta['cajero'] = self::readableText($venta['cajero']);
	        $array_venta['cancelada'] = self::readableText($venta['cancelada']);
	        $array_venta['tipo_venta'] = self::readableText($venta['tipo_venta']);
	        $array_venta['tipo_pago'] = self::readableText($venta['tipo_pago']);
	        $array_venta['total'] = FormatMoney($venta['total'], DONT_USE_HTML);
	        $array_venta['pagado'] = FormatMoney($venta['pagado'], DONT_USE_HTML);
	        $array_venta['saldo'] = FormatMoney($venta['saldo'], DONT_USE_HTML);

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
	    //$pdf->addJpegFromFile("../www/media/logo_simbolo.jpg", puntos_cm(15.9), puntos_cm(.25), 25);


	    $pdf->addText(puntos_cm(16.70), puntos_cm(.60), 8, "caffeina.mx");

	    $pdf->ezStream();
	}


	
	
}