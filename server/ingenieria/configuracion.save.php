<?php


if(!is_writable( "../../server/config.php" )){
	print ("No se tienen permisos de escritura en config.php");
	return ;
}



$intro = "// Modificado el " . date("r") . " desde "  . getip() ;

$toWrite = "<?php \n\n";
$toWrite .= $intro . "\n\n";
$toWrite .= $_REQUEST['contents'];


$c = fopen(  "../../server/config.php", "w" );
fwrite($c, $toWrite);
fclose($c);

?>




