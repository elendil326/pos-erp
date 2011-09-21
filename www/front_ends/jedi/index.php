<?php




	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../../server/bootstrap.php");


	$p = new JediComponentPage( );
	
	$p->addComponent( new TitleComponent( "Estado del servidor" ) );


	$header = array(  
			"id_venta" => "Venta", 
			"fecha" => "Fecha" );

	$datos = array( array ("id_venta" => "1", "fecha" => 2), array("id_venta" => "1", "fecha" => 2) );

	$estado_del_servidor = new TableComponent($header, $datos);
	


	$p->addComponent( $estado_del_servidor );	

	$p->render( );







