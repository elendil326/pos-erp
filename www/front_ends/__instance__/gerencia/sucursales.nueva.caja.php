<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                 //titulos
	$page->addComponent( new TitleComponent( "Nueva caja" ) );

	//forma de nueva caja
	$form = new DAOFormComponent( array( new Caja() ) );
	
	$form->hideField( array( 
			"id_caja",
                        "abierta",
                        "saldo",
                        "control_billetes",
                        "activa"
		 ));

//
//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/sucursal/caja/nueva/" );
	
	$form->makeObligatory(array( 
			"token"
		));
	
	$form->createComboBoxJoin( "id_sucursal", "razon_social", SucursalDAO::search( new Sucursal( array( "activa" => 1 ) ) ) );
	
	$page->addComponent( $form );


	//render the page
		$page->render();
