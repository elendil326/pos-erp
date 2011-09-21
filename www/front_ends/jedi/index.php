<?php


	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../../server/bootstrap.php");


	$p = new JediComponentPage( );
	
	$p->addComponent( new TitleComponent( "Estado del servidor" ) );

	
	$t = new SimpleTableComponent();


	
	
	$t->addRow("PHP"	, "OK");
	$t->addRow("MySQL"	, "OK");
	$t->addRow("Perl"	, "OK");

	$p->addComponent( $t );	

	$p->render( );
