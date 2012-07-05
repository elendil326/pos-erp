<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "mid", "GET", "Esta moneda no existe." );
		$esta_moneda = MonedaDAO::getByPK( $_GET["mid"] );
                //titulos
	$page->addComponent( new TitleComponent( "Editar Moneda: ".$esta_moneda->getNombre() ) );

	//forma de nuevo paquete
	$form = new DAOFormComponent( $esta_moneda );
	
	$form->hideField( array( 
			"id_moneda",
			"activa"
		 ));
        $form->sendHidden("id_moneda");

	
	$form->addApiCall( "api/efectivo/moneda/editar/", "GET" );
        $form->onApiCallSuccessRedirect("efectivo.lista.moneda.php");
	
	
	$page->addComponent( $form );


	//render the page
                
		$page->render();
