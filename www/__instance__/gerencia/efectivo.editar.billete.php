<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "bid", "GET", "Este billete no existe." );
		$este_billete = BilleteDAO::getByPK( $_GET["bid"] );
                //titulos
	$page->addComponent( new TitleComponent( "Editar Billete: ".$este_billete->getNombre() ) );

	//forma de nuevo paquete
	$form = new DAOFormComponent( $este_billete );
	
	$form->hideField( array( 
			"id_billete",
			"activo"
		 ));
        $form->sendHidden("id_billete");
	
	$form->addApiCall( "api/efectivo/billete/editar/", "GET" );
        $form->onApiCallSuccessRedirect("efectivo.lista.billete.php");
	$form->createComboBoxJoin("id_moneda", "nombre", MonedaDAO::getAll());
	
	$page->addComponent( $form );


	//render the page
                
		$page->render();
