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

	
	$form->addField( "descripcion", "Descripcion", "text" );
	$form->addField( "instance_token", "instance_token", "text" );
	$form->addField( "db_user", "db_user", "text" );
	$form->addField( "db_password", "db_password", "text" );
	$form->addField( "db_name", "db_name", "text" );
	$form->addField( "db_driver", "db_driver", "text" );
	$form->addField( "db_host", "db_host", "text" );
	$form->addField( "db_debug", "db_debug", "text" );



	$p->addComponent( $form );



	$p->render( );






