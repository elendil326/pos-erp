<?php
require('fpdf16/fpdf.php');
require "conversor.php";

$par=$_REQUEST["json"];
$json = json_decode($par);

$cliente=$json->cliente;
//var_dump($json);
//return;
//echo $impresion->tipo_venta;
//$pdf=new FPDF('P','mm','A4');
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetMargins(0.5,0.5);
$pdf->SetAutoPageBreak(true,-25); 
$pdf->SetFont('Arial','B',14);
//fecha
$pdf->SetXY(30,10);$pdf->Write (5, 'JUAN ANTONIO GARCIA TAPIA');
$pdf->SetXY(30,20);$pdf->Write (5, 'Venta de verduras');
//$pdf->SetXY(35,40);
//$pdf->Cell(-30);
$pdf->SetXY(7,10);
$pdf->Cell(7,60,'                                                                                                                     '.date("d M Y"));
$pdf->Cell(7,80,''.$cliente->nombre);//nombre
$pdf->Cell(-7);
$pdf->Cell(7,92,''.$cliente->direccion);//direccion
$pdf->Cell(-7);
$pdf->Cell(7,104,''.$cliente->ciudad);//ciudad
$pdf->Cell(-7);
//rfc podria ser muy largo asi ke hacemos la letra mas chica
$pdf->SetFont('Arial','B',12);
$pdf->Cell(7,104,'                                                                                                                             '.$cliente->rfc);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(-10);
/////aki empiezan los productos
$productos=$json->items;
//var_dump($productos);
//return;
$valor=126;
for($i=0;$i<sizeof($productos);$i++){
//echo $productos[$i]->descripcion;
$pdf->Cell(5,$valor,''.$productos[$i]->cantidad);
$pdf->Cell(15,$valor,'  '.$productos[$i]->descripcion);
$pdf->Cell(-15);



$pdf->Cell( 155,$valor,'    '.$productos[$i]->precioVenta,0,0,'R');
$pu = $productos[$i]->precioVenta;
	
/*
if($procesado[$i]->procesado=='true'){
	$pdf->Cell(155,$valor,'    '.$productos[$i]->precioVenta,0,0,'R');
	$pu=$productos[$i]->precioVenta;
	}else{
	$pdf->Cell(155,$valor,'    '.$productos[$i]->precioVentaSinProcesar,0,0,'R');
	$pu=$productos[$i]->precioVentaSinProcesar;
}*/

$pdf->Cell(-155);
$pdf->Cell(6,$valor,'                                                                                                                    $ '.$productos[$i]->cantidad*$pu);
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
$pdf->Write(0,'                                                                                                                                 $ '.$json->subtotal);
$pdf->SetY(-25);
$pdf->Write(0,'                                                                                                                                 $ '.$json->total);
$pdf->SetXY(30,-20);
      $pdf->SetFont('Helvetica','I',10);
$resultado = convertir($json->subtotal);
//print("<p>$resultado</p>");
//echo number_format($numero);
      $pdf->Write (5, ''.$resultado);
//$pdf->Cell(150,10,'                                                      $ ');

//$pdf->Cell(190,50,date("d M Y"),0,0,'R');
//echo "<body onload='javascript:window.print();'>";
$pdf->Output();

?>