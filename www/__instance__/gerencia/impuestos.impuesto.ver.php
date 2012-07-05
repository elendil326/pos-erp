<?php 

		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
				
		//
		// Requerir parametros
		// 
		$page->requireParam(  "iid", "GET", "Este impuesto no existe." );
		$este_impuesto = ImpuestoDAO::getByPK( $_GET["iid"] );
                
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_impuesto->getNombre() , 2 ));

		//
		// Menu de opciones
		// 
                $menu = new MenuComponent();


                $menu->addItem("Editar este impuesto", "impuestos.editar.impuesto.php?iid=".$_GET["iid"]);

                $page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_impuesto );
		
		$form->setEditable(false);

		$form->hideField( array( 
				"id_impuesto"
			 ));
		
		$page->addComponent( $form );
                
		$page->render();

