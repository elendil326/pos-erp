<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

	$page->addComponent( new TitleComponent( "Clientes" ) );
	
	$header = array( 
				"id_usuario" => "id_usuario",
				"nombre" => "nombre" 
			);
	$lista = ClientesController::Lista();
	$t = new TableComponent( $header, $lista );
	$page->addComponent( $t );

	
		$page->render();





