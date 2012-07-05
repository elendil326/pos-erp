<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nueva Unidad" ) );

	//forma de nueva unidad
	$form = new DAOFormComponent( array( new unidad() ) );
	
	$form->hideField( array( 
			"id_unidad",
                        "activa"
		 ));
        
        $form->createComboBoxJoin("es_entero", "es_entero", array( array( "id" => 1, "caption" => "Si" ), array( "id" => 0, "caption" => "NO" ) ));

	$form->addApiCall( "api/producto/unidad/nueva/" , "GET");
        
        $form->onApiCallSuccessRedirect("productos.lista.unidad.php");
	
	$form->makeObligatory(array( 
			"nombre",
                        "es_entero"
		));
	
	
	
	$page->addComponent( $form );
        
        //render the page
		$page->render();
