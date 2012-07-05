<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nueva categoria de producto" ) );

	//forma de nueva categoria de producto
	$form = new DAOFormComponent( array( new ClasificacionProducto() ) );
	
	$form->hideField( array( 
			"id_clasificacion_producto",
                        "activa"
		 ));

	$form->createComboBoxJoin("id_categoria_padre", "nombre", ClasificacionProductoDAO::getAll());	

	$form->addApiCall( "api/producto/categoria/nueva/" , "GET");
        $form->onApiCallSuccessRedirect("productos.lista.categoria.php");
	
	$form->makeObligatory(array( 
			"nombre"
		));
	
	
	
	$page->addComponent( $form );
        
        //render the page
		$page->render();
