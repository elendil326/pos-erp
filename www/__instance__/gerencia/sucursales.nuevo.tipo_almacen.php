<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nuevo tipo de almacen" ) );

	//forma de nuevo almacen
	$form = new DAOFormComponent( array( new TipoAlmacen() ) );
	
	$form->hideField( array( 
			"id_tipo_almacen",
			"activo"
		 ));

	
	$form->addApiCall( "api/almacen/tipo/nuevo");
        $form->onApiCallSuccessRedirect("sucursales.lista.tipo_almacen.php");
	
	$form->makeObligatory(array( 
			"descripcion"
		));
	
	$page->addComponent( $form );


	//render the page
		$page->render();
