<?php 



  	define("BYPASS_INSTANCE_CHECK", false);
  
  	require_once("../../../../server/bootstrap.php");
  
  	$page = new GerenciaComponentPage();
                
	//titulos
	$page->addComponent( new TitleComponent( "Nueva orden de servicio" ) );


	//forma de nueva orden de servicio
	$form = new DAOFormComponent( array( new OrdenDeServicio() ) );
	
	
	$form->hideField( array( 
			"id_orden_de_servicio",
			"id_usuario",
			"fecha_orden",
			"activa",
			"cancelada",
			"motivo_cancelacion"
    	));


	$form->renameField( array( 
			"id_usuario_venta"    => "id_cliente"
		));
	
	$form->addApiCall( "api/servicios/orden/nueva/" );
	
	$form->makeObligatory(array( 
			"id_cliente",
            "id_servicio",
            "descripcion",
            "fecha_entrega"
		));
	
	
    $form->createComboBoxJoin("id_servicio", "nombre_servicio", ServicioDAO::search( new Servicio( array("activo" => 1) ) ) );
	
	
	$form->createComboBoxJoinDistintName("id_cliente", "id_usuario", "nombre", 
                array_diff(UsuarioDAO::byRange( new Usuario( array("id_clasificacion_cliente" => 1) ) , new Usuario( array("id_clasificacion_cliente" => 100000) ) ) ,
                        UsuarioDAO::search( new Usuario( array( "activo" => 0 ) ) ) ) );
                
	$page->addComponent( $form );


	//render the page
	$page->render();
