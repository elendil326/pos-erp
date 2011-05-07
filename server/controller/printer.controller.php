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



function puntos_cm ($medida, $resolucion=72)
{
   //// 2.54 cm / pulgada
   //se supone ke la pagina mide 29.7 cm por 21.0 cm
	//$medida += 29.7;
	return ( $medida / (2.54) ) * $resolucion ;
}




function readableText($bar){
	$foo = explode(" " , $bar);
	$end = "";
	foreach($foo as $i){
		$end .= ucfirst(strtolower($i)) . " ";
	}

	return $end;
}

function formatAddress($d){
	
	if($d instanceof stdClass){
		$e = "";
		$e .= readableText($d->calle) . " " . $d->numeroExterior ;
		if(isset($d->numeroInterior))
			$e .= "\n" . $d->numeroInterior;
		$e .= "\n";
		$e .= readableText($d->colonia) . " C.P." . $d->codigoPostal . "\n";
		$e .= readableText($d->municipio) . ", " . readableText($d->estado) . ", " . readableText($d->pais). "\n" ;		
	}else{
		$e = "";
		$e .= readableText($d->getCalle()) . " " . $d->getNumeroExterior() ;
		if($d->getNumeroInterior() != null)
			$e .= "\n" . $d->getNumeroInterior();
			
		$e .= readableText($d->getColonia()) . " C.P." . $d->getCodigoPostal() . "\n";
		$e .= readableText($d->getMunicipio()) . ", " . readableText($d->getEstado()) . ", " . readableText($d->getPais()). "\n" ;	
	}

	return $e;
}


function roundRect($pdf, $x,$y,$w,$h){
	
	$pdf->setStrokeColor(0.3359375,0.578125,0.89453125);
	$pdf->setLineStyle(1);
	
	$x -= puntos_cm(.1);
	$y -= 2; 

	$pdf->line(	 $x + 2 , $y, $x+$w-2, $y); //arriba
	$pdf->line(	 $x , $y-2, $x , $y-$h+2);		//izquierda
	$pdf->line(	 $x +2, $y-$h, $x+$w-2, $y-$h); //abajo
	$pdf->line(	 $x+$w , $y-2, $x+$w , $y-$h+2);	//derecha		

	$pdf->partEllipse($x+3	,$y-3	,90		,180	,3); //top-left
	$pdf->partEllipse($x+$w-3 ,$y-3	,0		,90		,3); //top-right
	$pdf->partEllipse($x+$w-3, $y-$h+3,360	,240	,3); //bottom-right
	$pdf->partEllipse($x +3,$y-$h+3	,180	,270	,3); //bottom-left
}


function imprimirFactura($id_venta, $venta_especial = null){


	if($id_venta == null){
		$venta = $venta_especial;
	}else{
		$venta = VentasDAO::getByPK( $id_venta);		
	}


	if(!$venta){
		die("Esta venta no existe");
	}

	//validar que la venta sea a contado, o bien que este saldada
	if(!$venta->getLiquidada()){
		die("Esta venta no ha sido liquidada, no se puede imprimir factura.");
	}


	//validar que el cliente tenga todos los datos necesarios
	$cliente = ClienteDAO::getByPK( $venta->getIdCliente() );

	if(!$cliente){
		die("El cliente de esta venta no existe.");
	}


	//buscar los detalles de la factura de venta
	$factura_q = new FacturaVenta();
	$factura_q->setIdVenta( $venta->getIdVenta() );
	
	$facturas_poll = FacturaVentaDAO::search( $factura_q );
	
	if(sizeof($facturas_poll) != 1){
		Logger::log("Los datos de esta factura estan incompletos o hay mas de un detalle de factura");
		die("Los datos de esta factura estan incompletos o hay mas de un detalle de factura");
	}

	$factura = $facturas_poll[0];

	$detalle_de_venta 		= detalleVenta( $venta->getIdVenta() );
	$productos 				= $detalle_de_venta["items"];
	$detalle_de_venta 		= $detalle_de_venta["detalles"];

	//buscar los datos del emisor
	$conf = new PosConfig();
	$conf->setOpcion("emisor");
	
	$results = PosConfigDAO::search( $conf );
	if(sizeof($results) != 1){
		Logger::log("no encuentro los datos del numero de certificado");
		die("no encuentro los datos del emisor");
	}

	$emisor = json_decode( $results[0]->getValue() )->emisor;

	//buscar los datos del no de certificado
	$conf = new PosConfig();
	$conf->setOpcion("noCertificado");
	
	$results = PosConfigDAO::search( $conf );
	if(sizeof($results) != 1){
		Logger::log("no encuentro los datos del numero de certificado");
		die("no encuentro los datos del numero de certificado");
	}

	$serie_cert_contribuyente = $results[0]->getValue();

	$sucursal = SucursalDAO::getByPK($venta->getIdSucursal());

	$qr_file_name = obternerQRCode( $emisor->rfc, $cliente->getRFC(), $venta->getTotal(), $factura->getFolioFiscal() );
	
	include_once('librerias/ezpdf/class.pdf.php');
	include_once('librerias/ezpdf/class.ezpdf.php');
	
	$pdf = new Cezpdf(); 

	$pdf->selectFont('../server/librerias/ezpdf/fonts/Helvetica.afm'); 
	
	//margenes de un centimetro para toda la pagina
	$pdf->ezSetMargins(1,1,1,1);

	/* *************************
	 * ENCABEZADO
	 * ************************* */

	
		/* *************************
	 	* TITULO
	    * Datos del emisor, lugar de expedicion, folio, fecha de emision, no de serie
	    * del certificado del contribuyente
	 	* ************************* */	
		$e = "<b>" . readableText($emisor->nombre) . "</b>\n";
		$e .= formatAddress( $emisor );
		$e .= "RFC: " . $emisor->rfc;

		//datos de la sucursal
		$s = "<b>Lugar de expedicion</b>\n";
		$s .= formatAddress( $sucursal );

		$datos = array(
			array(
				"emisor"	=> $e,
				'sucursal'	=> $s, 
				)
		);
		
		$pdf->ezSetY( puntos_cm( 26.7 ) );
		$opciones_tabla = array();
		$opciones_tabla['showLines']	= 0;
		$opciones_tabla['showHeadings']	= 0;
		$opciones_tabla['shaded']		= 0;
		$opciones_tabla['fontSize']		= 8;
		$opciones_tabla['xOrientation']	= 'right';
		$opciones_tabla['xPos']			= puntos_cm(3);
		$opciones_tabla['width']		= puntos_cm(11);
		$opciones_tabla['textCol'] 		= array(0,0,0);
		$opciones_tabla['titleFontSize']= 12;
		$opciones_tabla['rowGap'] 		= 3;
		$opciones_tabla['colGap'] 		= 3;

		$pdf->ezTable($datos, "", "", $opciones_tabla);
		
		$datos = array(
			array( "col"	=> "<b>Folio</b>" ),
			array( "col"	=> $factura->getIdFolio() ),
			array( "col"	=> "<b>Fecha y hora de Emision</b>" ),
			array( "col"	=> $factura->getFechaEmision() ),
			array( "col"	=> "<b>Numero de serie del certificado del contribuyente</b>" ),
			array( "col"	=> $serie_cert_contribuyente )			
		);
		
		$pdf->ezSetY( puntos_cm( 28.7 ) );
		
		$opciones_tabla['xPos']			= puntos_cm(14.2);
		$opciones_tabla['width']		= puntos_cm( 4);
		$opciones_tabla['showLines']	= 0;
		$opciones_tabla['shaded']		= 2;						
		$opciones_tabla['shadeCol']  	= array(1,1,1);
		$opciones_tabla['shadeCol2'] 	=  array(0.8984375,0.95703125,0.99609375);
		$pdf->ezTable( $datos, "", "", $opciones_tabla);

		roundRect($pdf, puntos_cm(14.2),puntos_cm(28.7),puntos_cm(4),puntos_cm(4));
		

		
	/* *************************
 	* Receptor del comprobante fiscal
    * Datos del receptor
 	* ************************* */
	$datos_receptor = $cliente->getRAzonSocial() .  "\n";
	$datos_receptor .= formatAddress($cliente) ;
	$datos_receptor .= "RFC:" . $cliente->getRfc();

	$receptor = array(
		array( "receptor"	=> "<b>Receptor del comprobante fiscal</b>" ),
		array( "receptor"	=> $datos_receptor ),		
	);

	$pdf->ezSetY( puntos_cm( 24.3 ) );
	$opciones_tabla['xPos']			= puntos_cm( 2);
	$opciones_tabla['width']		= puntos_cm( 8.2);
	$opciones_tabla['showLines']	= 0;
	$pdf->ezTable( $receptor, "", "", $opciones_tabla);
	


	
	/* *************************
	*	Timbrado
 	* ************************* */
	$pdf->ezSetY( puntos_cm( 24.3 ) );
	$opciones_tabla['xPos']			= puntos_cm( 10.4);
	$opciones_tabla['width']		= puntos_cm( 7.8);
	$timbre = array(
		array( "dat"	=> "<b>Folio Fiscal</b>" ),
		array( "dat"	=> $factura->getFolioFiscal() ),
		array( "dat"	=> "<b>Fecha y hora de certificacion</b>" ),
		array( "dat"	=> $factura->getFechaCertificacion() ),
		array( "dat"	=> "<b>Numero de serie del certificaco del sat</b>" ),
		array( "dat"	=> $factura->getNumeroCertificadoSat() )				
	);
	
	$pdf->ezTable( $timbre, "", "", $opciones_tabla);
	
	roundRect($pdf, 
			puntos_cm(2),puntos_cm(24.3),
			puntos_cm(16.2),puntos_cm(3.2));
		
	
	
	/* *************************
 	* Tipo de comprobante
 	* ************************* */
	$pdf->ezSetY( puntos_cm( 20.9 ) );
	$opciones_tabla['xPos']			= puntos_cm( 2 );
	$opciones_tabla['width']		= puntos_cm( 16.2 );
	$comprobante = array(
		array( 	"r1"	=> "Tipo de comprobante",
				"r2"	=> "Moneda",
				"r3" 	=> "Tipo de cambio",
				"r4"	=> "<b>Version</b>" ),
				
		array( 	"r1"	=> readableText($factura->getTipoComprobante()),
				"r2"	=> "MXN",
				"r3" 	=> "1.0",
				"r4"	=> "3.0" ),
	);
	$pdf->ezTable( $comprobante, "", "", $opciones_tabla);
	
	roundRect($pdf, 
			puntos_cm(2),puntos_cm(20.9),
			puntos_cm(16.2),puntos_cm(1.2));
	
	/* *************************
 	* PRODUCTOS
 	* ************************* */
	$elementos = array(
		array(	'cantidad'=>'Cantidad', 
				'descripcion'=>'Descripcion                                                                                                     ', 'precio'=>'Precio', 'importe'=>'Importe'),
	);
	
	
	foreach($productos as $p){
		if($p["cantidadProc"] > 0){
			
			$prod['cantidad']		= $p["cantidadProc"];
			$prod['descripcion']	= $p["descripcion"] . " PROCESADA";
			$prod['precio']			= moneyFormat($p["precioProc"], DONT_USE_HTML);
			$prod['importe']		= moneyFormat($p["precioProc"] * $p["cantidadProc"], DONT_USE_HTML);

			array_push($elementos,$prod);
						
		}
		
		if($p["cantidad"] > 0){
			$prod['cantidad']		= $p["cantidad"];
			$prod['descripcion']	= $p["descripcion"];
			$prod['precio']			= moneyFormat($p["precio"], DONT_USE_HTML);
			$prod['importe']		= moneyFormat($p["precio"] * $p["cantidad"], DONT_USE_HTML);

			array_push($elementos,$prod);
		}
	}
	


	array_push($elementos, 
		array( "cantidad" => "", 
				"descripcion" => "", 
				"precio" => "Subtotal", 	
				"importe" =>  moneyFormat($venta->getSubTotal(), DONT_USE_HTML)) );
		
	array_push($elementos, 
		array( "cantidad" => "", 
				"descripcion" => "", 
				"precio" => "Descuento", 	
				"importe" => moneyFormat( $venta->getDescuento(), DONT_USE_HTML)) );
		
	array_push($elementos, 
		array( "cantidad" => "", 
				"descripcion" => "", 
				"precio" => "IVA", 		
				"importe" => moneyFormat( $venta->getIVA(), DONT_USE_HTML)) );
		
	array_push($elementos, 
		array( "cantidad" => "", 
				"descripcion" => "", 
				"precio" => "Total", 		
				"importe" => moneyFormat( $venta->getTotal(), DONT_USE_HTML)) );
		
	
	$pdf->ezText("", 10 , array('justification' => 'center'));
	$pdf->ezTable($elementos, "", "", $opciones_tabla);
	
	$pdf->addJpegFromFile("../static_content/qr_codes/" . $qr_file_name, 30, 30,150  );
	
	roundRect($pdf, 
			puntos_cm(2),puntos_cm(19.7-.25),
			puntos_cm(16.2),puntos_cm(13.2));
	
	/* *************************
 	* Tipo de pago
 	* ************************* */
	$pdf->ezText("\nPago en una sola exibicion", 9 , array('justification' => 'center'));
	
	/* *************************
 	* DATOS DE SELLOS
 	* ************************* */


	
	$sellos = array(
		array( 	"r1"	=> "Sello digital del emisor:"),
		array( 	"r1"	=> $factura->getSelloDigitalEmisor()),
		array( 	"r1"	=> "Sello digital del SAT:"),	
		array( 	"r1"	=> $factura->getSelloDigitalSat()),	
		array( 	"r1"	=> "Cadena original del complemento de certificacion digital del sat:"),					
		array( 	"r1"	=> $factura->getCadenaOriginal())
	);
	
	$pdf->ezSetY( puntos_cm( 6.0 ) );	
	$opciones_tabla['xPos']			= puntos_cm( 6 );
	$opciones_tabla['width']		= puntos_cm( 12.4 );
	$opciones_tabla['fontSize']		= 6;	
	$opciones_tabla['showLines']	= 0;	
	$pdf->ezTable( $sellos, "", "", $opciones_tabla);
	
	
	
	/* *************************
 	* notas de abajo
 	* ************************* */
	$pdf->setLineStyle(1);
	$pdf->setStrokeColor(0.3359375,0.578125,0.89453125);
	
	$pdf->line( puntos_cm( 2 ), puntos_cm( 1.3  ),
				puntos_cm( 18.2 ), puntos_cm( 1.3 ) );

	
	$pdf->addText(puntos_cm( 2 ), 
					puntos_cm( 1.0 ), 
					7, 
					"Este documento es una representacion impresa de un CFDI");
					
				

	$pdf->addJpegFromFile("../www/media/logo_simbolo.jpg", 
						puntos_cm( 15.9 ), 
						puntos_cm( .25 ), 
						25  );
						

	$pdf->addText(puntos_cm( 16.70 ), 
					puntos_cm( .60 ), 
					8, 
					"caffeina.mx");	
	

	$pdf->setLineStyle(1,'round','',array(0,2));
	$pdf->setStrokeColor(0.3359375,0.578125,0.89453125);

	$pdf->line( puntos_cm( 8.2+2 ), puntos_cm( 24.3 - .15 ),
				puntos_cm( 8.2+2 ), puntos_cm( 21.051 ) );

					
	$documento_pdf = $pdf->ezOutput(1);

	$pdf->ezStream();
	
	//ok ya la hize, var si existe este documento en static content, sino, guardarlo
	if(!is_file( "../static_content/facturas/" . $_SESSION["INSTANCE_ID"] . "_" . $venta->getIdVenta() . ".pdf" ))
		file_put_contents("../static_content/facturas/" . $_SESSION["INSTANCE_ID"] . "_" . $venta->getIdVenta() . ".pdf", $documento_pdf);
	

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
function obternerQRCode( $rfcEmisor = null, $rfcReceptor = null, $total = null, $uuid = null  ){

	if( !$rfcEmisor || !$rfcReceptor || !$total || !$uuid ){
		Logger::log("Faltan datos para buscar el codigo qr");
		return null;
	}
	
	
	$file_name = $rfcEmisor . $rfcReceptor . $total . $uuid ;
	$file_full_path  = "../static_content/qr_codes/" . $file_name . ".jpg";
	
	if(is_file(  $file_full_path )){
		Logger::log("Ya existe este codigo QR");
		return $file_name . ".jpg";
	}

	//el codigo no existe, sacarlo de google
	//primero tengo que ver si es posible que escriba en la carpeta de qr codes
	if(!is_writable("../static_content/qr_codes/")){
		Logger::log( "No puedo escribir en la carpeta de QRCODES !" );
		return null;
	}

	
	Logger::log("Codigo QR no esta en static, sacarlo de google");
	
	$cadena = "?re=" . $rfcEmisor;
	$cadena .= "&rr=" . $rfcReceptor;
	
	$total_seis_decimales = sprintf(  "%10.6f", $total);
	Logger::log("AQUI FALTA SABER SI SON DIEZ CARATERES PARA EL TOTAL, O SOLO LOS NECESARIOS, O QUE PEDO?");
	
	$cadena .= "&tt=" . $total_seis_decimales;
	$cadena .= "&id=" . $uuid;

	$google_api_call = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chld=H|1&choe=UTF-8&chl=";

	$f = file_get_contents( $google_api_call . urlencode( $cadena ) );
	
	if($f == null){
		//volver a intentar
		Logger::log("FALLE AL OBTENER EL QR CODE DE GOOGLE, REINTENTANDO...", 3);
		obternerQRCode( $rfcEmisor, $rfcReceptor, $total , $uuid   );
	}
	
	file_put_contents($file_full_path, $f);

	//la imagen esta en png, hay que convertirla a jpg para que no haya pedos en el pdf
    $image = imagecreatefrompng($file_full_path);
    imagejpeg($image, "../static_content/qr_codes/" . $file_name . ".jpg" , 100);
    imagedestroy($image);

	return $file_name . ".jpg";

}



function imprimirNotaDeVenta($id_venta){
	
	require('librerias/fpdf16/fpdf.php');
	$venta = VentasDAO::getByPK( $id_venta );

	if(!$venta){
		die("Esta venta no existe");
	}

	//validar que la venta sea a contado, o bien que este saldada
	if(!$venta->getLiquidada()){
		die("Esta venta no ha sido liquidada, no se puede facturar.");
	}


	//validar que el cliente tenga todos los datos necesarios
	$cliente = ClienteDAO::getByPK( $venta->getIdCliente() );

	if(!$cliente){
		die("El cliente de esta venta no existe.");
	}


	$pdf=new FPDF();
	$pdf->AddPage();
	$pdf->SetMargins(0.5,0.5);
	$pdf->SetAutoPageBreak(true,-25); 
	$pdf->SetFont('Arial','B',14);

	//fecha
	$pdf->SetXY(30,10);$pdf->Write (5, 'JUAN ANTONIO GARCIA TAPIA');
	$pdf->SetXY(30,20);$pdf->Write (5, 'Venta de verduras');

	/* *****************
	 *  DETALLES DEL CLIENTE
	 * ***************** */

	$pdf->SetXY(7,10);
	$pdf->Cell(7,60,'                                                                                                                     '.date("d M Y"));
	$pdf->Cell(7,80,''.$cliente->getRazonSocial());
	$pdf->Cell(-7);
	$pdf->Cell(7,92,''.$cliente->getCalle());//direccion
	$pdf->Cell(-7);
	$pdf->Cell(7,104,''.$cliente->getMunicipio());//ciudad
	$pdf->Cell(-7);
	//rfc podria ser muy largo asi ke hacemos la letra mas chica
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(7,104,'                                                                                                                             '.$cliente->getRFC());
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(-10);




	/* *****************
	 *  PRODUCTOS
	 * ***************** */

	$detalle_de_venta = detalleVenta( $venta->getIdVenta() );
	$productos = $detalle_de_venta["items"];
	$detalle_de_venta = $detalle_de_venta["detalles"];



	$valor = 126;

	for($i=0;$i<sizeof($productos);$i++){

		$pdf->Cell(5,	$valor,''.	$productos[$i]["cantidad"]);
		$pdf->Cell(15,	$valor,'  '.$productos[$i]["descripcion"]);
		$pdf->Cell(-15);

		$pdf->Cell( 155,$valor,'    '.$productos[$i]["precio"],0,0,'R');
		$pu = $productos[$i]["precio"];

		/*
		if($procesado[$i]->procesado=='true'){
			$pdf->Cell(155,$valor,'    '.$productos[$i]->precioVenta,0,0,'R');
			$pu=$productos[$i]->precioVenta;
			}else{
			$pdf->Cell(155,$valor,'    '.$productos[$i]->precioVentaSinProcesar,0,0,'R');
			$pu=$productos[$i]->precioVentaSinProcesar;
		}*/

		$pdf->Cell(-155);
		$pdf->Cell(6,$valor,'                                                                                                                    $ '.$productos[$i]["cantidad"]*$pu);
		$pdf->Cell(-11);
		$valor+=15;
	}///aki terminan los productos


	//$valor=300;
	//$pdf->Cell(6,$valor,'');$valor+=15;

	//$pdf->Cell(340,265,'SERIE',0,0,'C',0); 
	//$pdf->Ln();
	//$pdf->SetY(-15);
	//$pdf->SetX(100);

	$pdf->SetX(0);
	$pdf->SetY(-45);
	$pdf->Write(0,'                                                                                                                                 $ '.$detalle_de_venta->getSubtotal());
	$pdf->SetY(-25);
	$pdf->Write(0,'                                                                                                                                 $ '.$detalle_de_venta->getTotal());
	$pdf->SetXY(30,-20);
	$pdf->SetFont('Helvetica','I',10);
	//$resultado = numtoletras( (float)$detalle_de_venta->getSubtotal() );
	$resultado = "asdfkasdflkja pesos";
	//print("<p>$resultado</p>");
	//echo number_format($numero);
	$pdf->Write (5, ''.$resultado);
	//$pdf->Cell(150,10,'                                                      $ ');

	//$pdf->Cell(190,50,date("d M Y"),0,0,'R');
	//echo "<body onload='javascript:window.print();'>";
	$pdf->Output();
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

if (isset($args['action'])) {

    switch ($args['action']) {

        case 1300:
            printf('{"success": true, "datos": %s}', json_encode(listarDocumentos()));
        break;

        case 1301:
            printf('{"success": true, "datos": %s}', json_encode(leerLeyendasTicket()));
        break;

		case 1305 : 
			imprimirFactura( $args['id_venta'] );
		break;

		case 1306 : 
			imprimirNotaDeVenta( $args['id_venta']  );
		break;

        default:
            printf('{ "success" : "false" }');
         break;
    }
}
?>