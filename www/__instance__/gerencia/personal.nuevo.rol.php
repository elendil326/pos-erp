<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

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
        $form->onApiCallSuccessRedirect("personal.lista.rol.php");
        
        $form->makeObligatory(array( 
			"nombre"
		));
		
	$form->createComboBoxJoinDistintName("id_tarifa_venta", "id_tarifa" ,"nombre", TarifaDAO::search(new Tarifa(array("tipo_tarifa"=>"venta"))));
	$form->createComboBoxJoin("id_tarifa_compra", "nombre", TarifaDAO::search(new Tarifa(array("tipo_tarifa"=>"compra"))));
        
        
	$page->addComponent( $form );


	//render the page
                
		$page->render();
