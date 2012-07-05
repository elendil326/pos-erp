<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nueva Categoria Unidad Medida" ) );

	//forma de nueva unidad
	$form = new DAOFormComponent( array( new CategoriaUnidadMedida() ) );
	
	$form->hideField( array( 
			"id_categoria_unidad_medida",
                        "activa"
		 ));
        
    $form->addApiCall( "api/producto/udm/categoria/nueva" , "GET");
        
    $form->onApiCallSuccessRedirect("productos.lista.unidad.php");
	
	$form->makeObligatory(array( 			
            "descripcion"
		));	
	
	
	$page->addComponent( $form );
        
        //render the page
		$page->render();
