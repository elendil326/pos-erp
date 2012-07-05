<?php 



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server//bootstrap.php");

	$page = new GerenciaTabPage();


               $page->addComponent( new TitleComponent( "Clasificaciones de clientes" ) );

	$page->nextTab("Lista");

	$page->addComponent("<div class=\"POS Boton\" onClick=\"window.location='clientes.nueva.clasificacion.php';\">Nueva clasificacion</div>");
	
	$tabla = new TableComponent( 
		array(
			"clave_interna"		=> "Clave interna",
			"nombre" 			=> "Nombre",
			"descripcion" 		=> "Descripcion"
		),
		ClientesController::ListaClasificacion()
	);
               
	$tabla->addOnClick( "id_clasificacion_cliente", "(function(a){ window.location = 'clientes.clasificacion.ver.php?cid=' + a; })" );

		
	$page->addComponent( $tabla );


	$page->nextTab("Nueva");
	
		$form = new DAOFormComponent( new  ClasificacionCliente() );
		
		$form->addApiCall("api/cliente/clasificacion/nueva/");
                
		$form->onApiCallSuccessRedirect("clientes.lista.clasificacion.php");


		$form->hideField( array( 
				"id_clasificacion_cliente"
			 ));

		$form->makeObligatory(array( 
				"nombre",
				"clave_interna"
			));
                
			
		$page->addComponent( $form );
               
	$page->render();
