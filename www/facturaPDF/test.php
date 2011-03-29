<?
include('class.pdf.php');
$json = json_decode($_REQUEST["json"]);
require('class.ezpdf.php');

//var_dump($json);
$emisor=$json->emisor;
$receptor=$json->receptor;
$datos_fiscales=$json->datos_fiscales;
$factura=$json->factura;
$productos=$factura->productos;


function puntos_cm ($medida, $resolucion=72)
{
   //// 2.54 cm / pulgada
   //se supone ke la pagina mide 29.7 cm por 21.0 cm
   return ($medida/(2.54))*$resolucion;
}

$pdf = new Cezpdf();

//$pdf->ezImage("cerdo3.jpg", 0, 420, 'none', 'left');
//$pdf->addJpegFromFile("cerdo3.jpg",50,50,300); 

$pdf->selectFont('fonts/Times-Roman');
//$pdf->addText(puntos_cm(4),puntos_cm(26.7),12,'Encabezado');
$pdf->setColor(0.8,0.8,0.8);
$pdf->setStrokeColor(0,0,0);
$pdf->filledrectangle (puntos_cm(2), puntos_cm(26.7), puntos_cm(17), puntos_cm(1.5));
$pdf->setLineStyle(3,'round');
$pdf->setColor(0, 0 ,0);
$pdf->ezImage('img.jpg', 0, 50, 'none', 'left');
//$pdf->ezImage('http://www.error500.net/images/articulos/logo-google-chrome.jpg', 0, 50, 'none', 'left');
$pdf->addText(puntos_cm(3),puntos_cm(27.3),12,'POS Papas Supremas - Factura de Venta');
$pdf->addText(puntos_cm(15),puntos_cm(27.3),12,'Folio: '.$emisor->folio);
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
array('id'=>'RFC', 'ref'=>$emisor->rfc),
array('id'=>'Nombre', 'ref'=>$emisor->razon_social),
array('id'=>'Domicilio', 'ref'=>$emisor->direccion)
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
array('id'=>'RFC', 'ref'=>$receptor->rfc),
array('id'=>'Nombre', 'ref'=>$receptor->razon_social),
array('id'=>'Domicilio', 'ref'=>$receptor->direccion)
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
array('certificado'=>'No. Certificado de Sello Digital                                                                                            ', 'aprobacion'=>'No. Aprobacion', 'anioAprobacion'=>$anio),
array('certificado'=>$datos_fiscales->numero_certificado, 'aprobacion'=>$datos_fiscales->numero_aprobacion, 'anioAprobacion'=>$datos_fiscales->anio_aprobacion)
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
$prod['cantidad']=$p->cantidad;
$prod['descripcion']=$p->descripcion;
$prod['precio']=$p->precio;
$prod['importe']=$p->importe;
//array_push($elementos, 'cantidad'=>$cantidad, 'descripcion'=>$descripcion, 'precio'=>$precio, 'importe'=>$importe);
array_push($elementos,$prod);
}

$prod['cantidad']="";$prod['descripcion']="";$prod['precio']='Subtotal';$prod['importe']=$factura->subtotal;
array_push($elementos,$prod);
$prod['cantidad']="";$prod['descripcion']="";$prod['precio']='Descuento';$prod['importe']=$factura->descuento;
array_push($elementos,$prod);
$prod['cantidad']="";$prod['descripcion']="";$prod['precio']='IVA';$prod['importe']=$factura->iva;
array_push($elementos,$prod);
$prod['cantidad']="";$prod['descripcion']="";$prod['precio']='Total';$prod['importe']=$factura->total;
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
array('letra'=>'Total con letra                                                                                        ','forma'=>'Forma Pago','metodo'=>'Metodo Pago'),
array('letra'=>$factura->total_letra,'forma'=>$factura->forma_pago,'metodo'=>$factura->metodo_pago),
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
array('campo1'=>'Cadena Original: '.$datos_fiscales->cadena_original),
array('campo1'=>'Sello Digital: '.$datos_fiscales->sello_digital),
array('campo1'=>'Sello Digital Proveedor CFDI: '.$datos_fiscales->sello_digital_proveedor),
array('campo1'=>'Informacion PAC: '.$datos_fiscales->pac)
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
$fichero = fopen('asd.pdf','w');
fwrite ($fichero, $documento_pdf);
fclose ($fichero);
?>