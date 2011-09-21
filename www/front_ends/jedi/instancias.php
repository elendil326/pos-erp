<?php




	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../../server/bootstrap.php");


	$p = new JediComponentPage( );
	$p->addComponent( new TitleComponent( "Instancias" ) );


	/**
	  *
	  * Lista de instancias
	  *
	  **/
	$p->addComponent( new TitleComponent( "Instancias instaladas", 2 ) );

	$t = new SimpleTableComponent();
	$t->setRows(InstancesController::lista());
	$p->addComponent( $t );	


	/**
	  *
	  *	Nueva instancia
	  *
	  **/
	$p->addComponent( new TitleComponent( "Nueva instancia", 2 ) );
	$form = new FormComponent();

	$form->addField( "nombre", "nombre", "text" );

	$p->addComponent( $form );



	$p->render( );






