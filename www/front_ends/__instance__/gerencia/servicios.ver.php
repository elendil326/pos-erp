<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "sid", "GET", "Este servicio no existe." );
		$este_servicio = ServicioDAO::getByPK( $_GET["sid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_servicio->getNombreServicio() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar este servicio", "servicios.editar.php?sid=".$_GET["sid"]);
		//$menu->addItem("Desactivar este producto", null);
                
                $btn_eliminar = new MenuItem("Desactivar este servicio", null);
                $btn_eliminar->addApiCall("api/servicios/eliminar", "GET");
                $btn_eliminar->onApiCallSuccessRedirect("servicios.lista.php");
                $btn_eliminar->addName("eliminar");
                
                $funcion_eliminar = " function eliminar_servicio(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_servicio = ".$_GET["sid"].";".
                                "sendToApi_eliminar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar(){".
                        " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar este servicio?', eliminar_servicio );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_servicio );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_servicio",
			 ));
//		$form->makeObligatory(array( 
//				"compra_en_mostrador",
//				"costo_estandar",
//				"nombre_producto",
//				"id_empresas",
//				"codigo_producto",
//				"metodo_costeo",
//				"activo"
//			));
//	    $form->createComboBoxJoin("id_unidad", "nombre", UnidadDAO::getAll(), $este_producto->getIdUnidad() );
		$page->addComponent( $form );
		
		$page->render();
