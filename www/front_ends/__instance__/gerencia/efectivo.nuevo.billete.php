<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nuevo billete" ) );

	//forma de nuevo billete
	$form = new DAOFormComponent( array( new Billete() ) );
	
	$form->hideField( array( 
			"id_billete",
                        "activo"
		 ));


	$form->renameField( array( 
			"nombre" 			=> "nombre"
		));
	
	$form->addApiCall( "api/efectivo/billete/nuevo/" );
	
	$form->makeObligatory(array( 
			"nombre",
			"valor",
			"id_moneda"
		));
	
	$page->addComponent( $form );


	//render the page
		$page->render();
