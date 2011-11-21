<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();
	
	$page->addComponent( new TitleComponent( "Empresas" ) );



	$page->addComponent( 
		new TableComponent( 
			array("id_empresa" => "id_empresa"),
			EmpresasController::Lista()
		) 
	);
	
	
	$page->render();





