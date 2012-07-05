<?php


	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../server/bootstrap.php");


	$p = new JediComponentPage( );

	$p->addComponent( new TitleComponent( "POS ERP JEDI INTERFACE" ) );

	
	$t = new SimpleTableComponent();
	


	$p->addComponent( $t );	

	$p->render( );
