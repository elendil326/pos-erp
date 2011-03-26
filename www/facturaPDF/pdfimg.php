<?php
include('class.ezpdf.php');
$pdf =& new Cezpdf();
$pdf->selectFont('fonts/courier.afm');

$pdf->ezImage("img.jpg", 0, 420, 'none', 'left');

$pdf->ezText("<b>Hora:</b> ".date("H:i:s"),10);
$pdf->ezText('<b>Fuente:</b> <c:alink:http://blog.unijimpe.net/>blog.unijimpe.net</c:alink>');
ob_end_clean();
$pdf->ezStream();
?>

