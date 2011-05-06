<?php

require_once('model/impresora.dao.php');
require_once('model/impresiones.dao.php');
require_once('model/documento.dao.php');
require_once('model/pos_config.dao.php');
require_once('model/sucursal.dao.php');
require_once("model/ventas.dao.php");
require_once("model/cliente.dao.php");
require_once("controller/ventas.controller.php");


function puntos_cm ($medida, $resolucion=72)
{
   //// 2.54 cm / pulgada
   //se supone ke la pagina mide 29.7 cm por 21.0 cm
   return ($medida/(2.54))*$resolucion;
}



function imprimirFactura($id_venta){


	
	$venta = VentasDAO::getByPK( $id_venta);

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


	$detalle_de_venta 		= detalleVenta( $venta->getIdVenta() );
	$productos 				= $detalle_de_venta["items"];
	$detalle_de_venta 		= $detalle_de_venta["detalles"];

	include_once('librerias/ezpdf/class.pdf.php');
	include_once('librerias/ezpdf/class.ezpdf.php');
	
	$pdf = new Cezpdf(); 
	$pdf->selectFont('../server/librerias/ezpdf/fonts/Helvetica.afm'); 

	//$pdf->ezImage("cerdo3.jpg", 0, 420, 'none', 'left');
	//$pdf->addJpegFromFile("cerdo3.jpg",50,50,300); 

	$pdf->selectFont		('fonts/Times-Roman');
	//$pdf->addText(puntos_cm(4),puntos_cm(26.7),12,'Encabezado');
	$pdf->setColor			(0.8,0.8,0.8);
	$pdf->setStrokeColor	(0,0,0);
	$pdf->filledrectangle 	(puntos_cm(2), puntos_cm(26.7), puntos_cm(17), puntos_cm(1.5));
	$pdf->setLineStyle		(3,'round');
	$pdf->setColor			(0, 0 ,0);
	$pdf->ezImage			('img.jpg', 0, 50, 'none', 'left');

	//$pdf->ezImage('http://www.error500.net/images/articulos/logo-google-chrome.jpg', 0, 50, 'none', 'left');

	$pdf->addText(puntos_cm(3),puntos_cm(27.3),12,'POS Papas Supremas - Factura de Venta');
	$pdf->addText(puntos_cm(15),puntos_cm(27.3),12,'Folio: '."emisor->folio");
	//$pdf->addPngFromFile('logo.png',puntos_cm(5),puntos_cm(15),puntos_cm(10));

	//$pdf->ezImage("logo.png", 0, 20, 'none', 'left');

	$pdf->setStrokeColor(0,0,0);
	$pdf->line(puntos_cm(2),puntos_cm(26.5),puntos_cm(19),puntos_cm(26.5));
	$pdf->setColor(0.8,0.8,0.8);
	$pdf->setStrokeColor(0,0,0);
	$pdf->setLineStyle(1,'round'); 
	$pdf->setColor(0,0,0);
	//$pdf->addText(puntos_cm(2),puntos_cm(25.5),12,'Folio: '.$emisor->folio);
	$pdf->addText(puntos_cm(15),puntos_cm(26),12,'Fecha: '.date("d / m / y"));
	$pdf->addText(puntos_cm(2),puntos_cm(25.5),12,'Emisor: ');
	$pdf->addText(puntos_cm(10.5),puntos_cm(25.5),12,'Receptor: ');

	//$pdf->filledrectangle (puntos_cm(14), puntos_cm(22), puntos_cm(3), puntos_cm(1.5));
	//$pdf->rectangle (puntos_cm(14), puntos_cm(22), puntos_cm(3), puntos_cm(1.5));

	//$pdf->setColor(0, 0 ,0);
	//$pdf->addText(puntos_cm(15),puntos_cm(22.5),12,'Final');

	$pdf->ezSetY(puntos_cm(25.5));

	$datos = array(
	array('id'=>'RFC', 'ref'=>"emisor->rfc"),
	array('id'=>'Nombre', 'ref'=>"emisor->razon_social"),
	array('id'=>'Domicilio', 'ref'=>"emisor->direccion")
	);

	////creamos un nuevo array en el que pondremos un borde=1
	///y las cabeceras de la tabla las pondremos ocultas
	unset ($opciones_tabla);
	
	//// mostrar las lineas
	$opciones_tabla['showlines']=1;
	//// mostrar las cabeceras
	$opciones_tabla['showHeadings']=0;
	//// lineas sombreadas
	$opciones_tabla['shaded']= 1;
	//// tamaño letra del texto
	$opciones_tabla['fontSize']= 10;
	//// alineacion de la tabla
	$opciones_tabla['xOrientation']= 'right';
	//// tamaño letra del texto
	$opciones_tabla['xPos']= puntos_cm(2);
	//// tamaño de la tabla
	$opciones_tabla['width']= puntos_cm(8);
	//// color del texto
	$opciones_tabla['textCol'] = array(0,0,0);
	//// tamaño de las cabeceras (texto)
	$opciones_tabla['titleFontSize'] = 12;
	//// margen interno de las celdas
	$opciones_tabla['rowGap'] = 3;
	$opciones_tabla['colGap'] = 3;

	
	$pdf->ezTable($datos, "", "",$opciones_tabla);


	$pdf->ezSetY(puntos_cm(25.5));
	$datos = array(
	array('id'=>'RFC', 'ref'=> "receptor->rfc"),
	array('id'=>'Nombre', 'ref'=> "receptor->razon_social"),
	array('id'=>'Domicilio', 'ref'=>"receptor->direccion")
	);

	////creamos un nuevo array en el que pondremos un borde=1
	///y las cabeceras de la tabla las pondremos ocultas
	unset ($opciones_tabla);
	//// mostrar las lineas
	$opciones_tabla['showlines']=1;
	//// mostrar las cabeceras
	$opciones_tabla['showHeadings']=0;
	//// lineas sombreadas
	$opciones_tabla['shaded']= 1;
	//// tamaño letra del texto
	$opciones_tabla['fontSize']= 10;
	//// alineacion de la tabla
	$opciones_tabla['xOrientation']= 'right';
	//// tamaño letra del texto
	$opciones_tabla['xPos']= puntos_cm(10.5);
	//// tamaño de la tabla
	$opciones_tabla['width']= puntos_cm(8.5);
	//// color del texto
	$opciones_tabla['textCol'] = array(0,0,0);
	//// tamaño de las cabeceras (texto)
	$opciones_tabla['titleFontSize'] = 12;
	//// margen interno de las celdas
	$opciones_tabla['rowGap'] = 3;
	$opciones_tabla['colGap'] = 3;
	$pdf->ezTable($datos, "", "",$opciones_tabla);
	$pdf->ezText('');


	$anio='A'.utf8_decode('ñ').'o Aprobacion';
	//certificado sello digital, num aprobacion, year aprobacion
	$data = array(
	array('certificado'=>'No. Certificado de Sello Digital                                                                                            ',
	 	'aprobacion'=>'No. Aprobacion', 
		'anioAprobacion'=>$anio),
		array(
			'certificado'=>"datos_fiscales->numero_certificado", 
			'aprobacion'=> "datos_fiscales->numero_aprobacion", 'anioAprobacion'=> "datos_fiscales->anio_aprobacion"
		)
	);
	////creamos un nuevo array en el que pondremos un borde=1
	///y las cabeceras de la tabla las pondremos ocultas
	unset ($opciones_tabla);
	//// mostrar las lineas
	$opciones_tabla['showLines']=0;
	//// mostrar las cabeceras
	$opciones_tabla['showHeadings']=0;
	//// lineas sombreadas
	$opciones_tabla['shaded']= 1;
	//// tamaño letra del texto
	$opciones_tabla['fontSize']= 10;
	//// alineacion de la tabla
	$opciones_tabla['xOrientation']= 'right';
	//// alineacion de la tabla
	$opciones_tabla['xPos']= puntos_cm(2);
	//// tamaño de la tabla
	$opciones_tabla['width']= puntos_cm(17);
	//// color del texto
	$opciones_tabla['textCol'] = array(0,0,0);
	//// tamaño de las cabeceras (texto)
	$opciones_tabla['titleFontSize'] = 10;
	//// margen interno de las celdas
	$opciones_tabla['rowGap'] = 2;
	$opciones_tabla['colGap'] = 2;
	$pdf->ezTable($data, "", "",$opciones_tabla);
	$pdf->ezText('');


	//PRODUCTOS
	$elementos = array(
	array('cantidad'=>'Cantidad', 'descripcion'=>'Descripcion                                                                                                     ', 'precio'=>'Precio', 'importe'=>'Importe'),
	);
	foreach($productos as $p){
	$prod['cantidad']= "p->cantidad";
	$prod['descripcion']= "p->descripcion";
	$prod['precio']="p->precio";
	$prod['importe']="p->importe";
	//array_push($elementos, 'cantidad'=>$cantidad, 'descripcion'=>$descripcion, 'precio'=>$precio, 'importe'=>$importe);
	array_push($elementos,$prod);
	}

	$prod['cantidad']="";
	$prod['descripcion']="";
	$prod['precio']='Subtotal';
	$prod['importe']= "factura->subtotal";
	array_push($elementos,$prod);

	$prod['cantidad']="";
	$prod['descripcion']="";
	$prod['precio']='Descuento';
	$prod['importe']= "factura->descuento";
	array_push($elementos,$prod);

	$prod['cantidad']="";
	$prod['descripcion']="";
	$prod['precio']='IVA';
	$prod['importe']= "factura->iva";
	array_push($elementos,$prod);

	$prod['cantidad']="";
	$prod['descripcion']="";
	$prod['precio']='Total';
	$prod['importe']= "factura->total";
	array_push($elementos,$prod);

	////creamos un nuevo array en el que pondremos un borde=1
	///y las cabeceras de la tabla las pondremos ocultas
	unset ($opciones_tabla);
	//// mostrar las lineas
	$opciones_tabla['showlines']=1;
	//// mostrar las cabeceras
	$opciones_tabla['showHeadings']=0;
	//// lineas sombreadas
	$opciones_tabla['shaded']= 1;
	//// tamaño letra del texto
	$opciones_tabla['fontSize']= 10;
	//// alineacion de la tabla
	$opciones_tabla['xOrientation']= 'right';
	//// alineacion de la tabla
	$opciones_tabla['xPos']= puntos_cm(2);
	//// tamaño de la tabla
	$opciones_tabla['width']= puntos_cm(17);
	//// color del texto
	$opciones_tabla['textCol'] = array(0,0,0);
	//// tamaño de las cabeceras (texto)
	$opciones_tabla['titleFontSize'] = 12;
	//// margen interno de las celdas
	$opciones_tabla['rowGap'] = 3;
	$opciones_tabla['colGap'] = 3;
	$pdf->ezTable($elementos, "", "Productos",$opciones_tabla);
	$pdf->ezText('');


	//Final de la factura
	$footer = array(
	array(	'letra'=>'Total con letra                                                                                        ',
			'forma'=>'Forma Pago',
			'metodo'=>'Metodo Pago'),
			array('letra'=>"factura->total_letra",
				'forma'=>"factura->forma_pago",
				'metodo'=>"factura->metodo_pago"
			)
	);

	////creamos un nuevo array en el que pondremos un borde=1
	///y las cabeceras de la tabla las pondremos ocultas
	unset ($opciones_tabla);
	//// mostrar las lineas
	$opciones_tabla['showLines']=0;
	//// mostrar las cabeceras
	$opciones_tabla['showHeadings']=0;
	//// lineas sombreadas
	$opciones_tabla['shaded']= 0;
	//// tamaño letra del texto
	$opciones_tabla['fontSize']= 10;
	//// alineacion de la tabla
	$opciones_tabla['xOrientation']= 'right';
	//// alineacion de la tabla
	$opciones_tabla['xPos']= puntos_cm(2);
	//// tamaño de la tabla
	$opciones_tabla['width']= puntos_cm(17);
	//// color del texto
	$opciones_tabla['textCol'] = array(0,0,0);
	//// tamaño de las cabeceras (texto)
	$opciones_tabla['titleFontSize'] = 12;
	//// margen interno de las celdas
	$opciones_tabla['rowGap'] = 3;
	$opciones_tabla['colGap'] = 3;
	$pdf->ezTable($footer, "", "",$opciones_tabla);

	$fiscales = array(
	array('campo1'=>'Cadena Original: '. "datos_fiscales->cadena_original"),
	array('campo1'=>'Sello Digital: '. "datos_fiscales->sello_digital"),
	array('campo1'=>'Sello Digital Proveedor CFDI: '. "datos_fiscales->sello_digital_proveedor"),
	array('campo1'=>'Informacion PAC: '. "datos_fiscales->pac")
	);

	////creamos un nuevo array en el que pondremos un borde=1
	///y las cabeceras de la tabla las pondremos ocultas
	unset ($opciones_tabla);
	//// mostrar las lineas
	$opciones_tabla['showLines']=1;
	//// mostrar las cabeceras
	$opciones_tabla['showHeadings']=0;
	//// lineas sombreadas
	$opciones_tabla['shaded']= 1;
	//// tamaño letra del texto
	$opciones_tabla['fontSize']= 10;
	//// alineacion de la tabla
	$opciones_tabla['xOrientation']= 'right';
	//// alineacion de la tabla
	$opciones_tabla['xPos']= puntos_cm(2);
	//// tamaño de la tabla
	$opciones_tabla['width']= puntos_cm(17);
	//// color del texto
	$opciones_tabla['textCol'] = array(0,0,0);
	//// tamaño de las cabeceras (texto)
	$opciones_tabla['titleFontSize'] = 12;
	//// margen interno de las celdas
	$opciones_tabla['rowGap'] = 3;
	$opciones_tabla['colGap'] = 3;
	$pdf->ezTable($fiscales, "", "",$opciones_tabla);

	$pdf->addText(puntos_cm(2),puntos_cm(1),12,'Este documento es una impresion de un CFDI');


	//$pdf->ezOutput(1);
	$documento_pdf = $pdf->ezOutput(1);
	$pdf->ezStream();
	
	
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