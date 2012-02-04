<?php




	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../../server/bootstrap.php");
	
	/***
	 * 
	 *  Business Logic
	 * 
	 * 
	 * */
	
	

	
	/***
	 * 
	 *  Page Rendering
	 * 
	 * 
	 * */
	$p = new JediComponentPage( );
	$p->requireParam( "iid", "GET", "Esta instancia no existe", "InstanciasController::BuscarPorId" );
	
	
	

	/**
	  *
	  *	Nueva instancia
	  *
	  **/
	$p->addComponent( new TitleComponent( "Nueva instancia" ) );
	$p->addComponent( new TitleComponent( "Automatizado", 2 ) );
	
	$p->addComponent( new FreeHtmlComponent( 'Descripcion <input type="text" style="font-size: 17px;" id="_new_instance_desc">&nbsp;'));
	$p->addComponent( new FreeHtmlComponent( '<div class="POS Boton OK"  onclick="window.location=\'instancias.nueva.php?do=nueva&d=\'+HtmlEncode(Ext.get(\'_new_instance_desc\').getValue());">Nueva Instancia</div>') );	

	$p->addComponent( new FreeHtmlComponent( "<hr>") );



	$p->addComponent( new TitleComponent( "Utilizando una base de datos externa", 2 ) );
	$form = new FormComponent(  );
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
