<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server//bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Esta clasificacion de cliente no existe." );
		$esta_clasificacion = ClasificacionClienteDAO::getByPK( $_GET["cid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_clasificacion->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar esta clasificacion", "clientes.editar.clasificacion.php?cid=".$_GET["cid"]);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_clasificacion );
		$form->setEditable(false);
                
		$form->hideField( array( 
				"id_clasificacion_cliente",
			 ));
		$page->addComponent( $form );
		
		$page->render();
