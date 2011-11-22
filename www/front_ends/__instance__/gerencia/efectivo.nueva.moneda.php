<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                 //titulos
	$page->addComponent( new TitleComponent( "Nueva moneda" ) );

	//forma de nueva moneda
	$form = new DAOFormComponent( array( new Moneda() ) );
	
	$form->hideField( array( 
			"id_moneda",
            "activa"
		 ));


	
	$form->addApiCall( "api/efectivo/moneda/nueva/" );
	
	$form->makeObligatory(array( 
			"nombre",
			"simbolo"
		));
	
	$page->addComponent( $form );


	//render the page
		$page->render();
