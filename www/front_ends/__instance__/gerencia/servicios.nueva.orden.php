<?php 



  	define("BYPASS_INSTANCE_CHECK", false);
  
  	require_once("../../../../server/bootstrap.php");
  
  	$page = new GerenciaComponentPage();


	//titulos
	$page->addComponent( new TitleComponent( "Nueva orden de servicio" ) );


	$csel = new ClienteSelectorComponent();
	$csel->addJsCallback("alert");
	
	$page->addComponent( $csel);



	//forma de nueva orden de servicio
	$form = new DAOFormComponent( array( new OrdenDeServicio() ) );
	
	
	$form->hideField( array( 
			"id_orden_de_servicio",
			"id_usuario",
			"fecha_orden",
			"activa",
			"cancelada",
			"motivo_cancelacion",
			"precio"
    	));


	$form->renameField( array( 
			"id_usuario_venta"    => "id_cliente"
		));
	
	$form->addApiCall( "api/servicios/orden/nueva/" );
    $form->onApiCallSuccessRedirect("servicios.lista.orden.php");
	
	$form->makeObligatory(array( 
			"id_cliente",
            "id_servicio"
		));
	
	
    $form->createComboBoxJoin("id_servicio", "nombre_servicio", ServicioDAO::search( new Servicio( array("activo" => 1) ) ) );
	
	$clientes = $lista = ClientesController::Buscar();
	
	$form->createComboBoxJoinDistintName(
			"id_cliente", 
			"id_usuario", 
			"nombre", 
			$clientes["resultados"]
			
		);

	$page->addComponent( $form );


	//render the page
	$page->render();
