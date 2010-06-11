<?php	
	include_once("AddAllClass.php");
	$listar = new listar("select * from cliente",array());
	echo $listar->lista();

?>