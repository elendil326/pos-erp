<?php

/**
 *  @author Alan Gonzalez y Ernesto Serafin
 *
 * */

require_once("../../server/bootstrap.php");
require_once("model/ventas.dao.php");
require_once("model/cliente.dao.php");
require_once("controller/ventas.controller.php");

require('fpdf16/fpdf.php');

require "conversor.php";

if(!isset($_REQUEST["id_venta"])){
	die("Faltan parametros.");
}

$venta = VentasDAO::getByPK( $_REQUEST["id_venta"] );

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

?>