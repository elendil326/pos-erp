<?php
include('class.ezpdf.php');
include_once('class.pdf.php');
$pdf = new Cezpdf();
//$pdf->ezImage('cerdo3.JPG',1,500,1,'left');
$pdf->ezImage("img.jpg", 0, 420, 'none', 'left');
$pdf->ezText("<b>Hora:</b> ".date("H:i:s"),10);
//ob_end_clean();
$pdf->ezStream();
?>