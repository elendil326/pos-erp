<?php 



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

               $page->addComponent( new TitleComponent( "Clasificaciones de cliente" ) );
	$page->addComponent( new MessageComponent( "Lista de clasificaciones de cliente " ) );
	
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
               
	$page->render();
