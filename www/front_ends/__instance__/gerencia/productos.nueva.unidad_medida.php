<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nueva Unidad Medida" ) );

	//forma de nueva unidad
	$form = new DAOFormComponent( array( new UnidadMedida() ) );
	
	$form->hideField( array( 
			"id_unidad_medida",
                        "activa"
		 ));
        
    $form->createComboBoxJoin("tipo_unidad_medida", "tipo_unidad_medida", array( "Referencia UdM para esta categoria", "Mayor que la UdM de referencia", "Menor que la UdM de referencia"));

	$form->addApiCall( "api/producto/udm/unidad/nueva" , "GET");
        
    $form->onApiCallSuccessRedirect("productos.lista.unidad.php");
	
	$form->makeObligatory(array( 
			"abreviacion",
            "descripcion",
			"factor_conversion",
			"id_categoria_unidad_medida",
			"tipo_unidad_medida"
		));
	
	
	
	$page->addComponent( $form );
        
        //render the page
		$page->render();
