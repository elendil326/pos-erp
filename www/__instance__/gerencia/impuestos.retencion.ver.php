<?php 

		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
				
		//
		// Requerir parametros
		// 
		$page->requireParam(  "rid", "GET", "Esta retencion no existe." );
		$esta_retencion = RetencionDAO::getByPK( $_GET["rid"] );
                
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_retencion->getNombre() , 2 ));

		//
		// Menu de opciones
		// 
                $menu = new MenuComponent();


                $menu->addItem("Editar esta retencion", "impuestos.editar.retencion.php?rid=".$_GET["rid"]);

                $page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_retencion );
		
		$form->setEditable(false);

		$form->hideField( array( 
				"id_retencion"
			 ));
		
		$page->addComponent( $form );
                
		$page->render();