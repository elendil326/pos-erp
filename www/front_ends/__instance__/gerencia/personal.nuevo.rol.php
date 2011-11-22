<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nuevo Rol" ) );

	//forma de nuevo usuario
	$page->addComponent( new TitleComponent( "Datos del rol" , 3 ) );
	$form = new DAOFormComponent( array( new Rol() ) );
	
	$form->hideField( array( 
			"id_rol"
		 ));
        
        
        $form->addApiCall( "api/personal/rol/nuevo/" );
        
        $form->makeObligatory(array( 
			"nombre"
		));
		
	
        
        
	$page->addComponent( $form );


	//render the page
                
		$page->render();
