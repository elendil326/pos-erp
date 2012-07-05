<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Esta clasificacion no existe." );
		$esta_clasificacion = ClasificacionServicioDAO::getByPK( $_GET["cid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_clasificacion->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
                if($esta_clasificacion->getActiva())
                {
                    $menu = new MenuComponent();
                    $menu->addItem("Editar esta clasificacion de servicio", "servicios.editar.clasificacion.php?cid=".$_GET["cid"]);

                    $btn_eliminar = new MenuItem("Desactivar esta clasificacion", null);
                    $btn_eliminar->addApiCall("api/servicios/clasificacion/eliminar/", "GET");
                    $btn_eliminar->onApiCallSuccessRedirect("servicios.lista.clasificacion.php");
                    $btn_eliminar->addName("eliminar");

                    $funcion_eliminar = " function eliminar_clasificacion(btn){".
                                "if(btn == 'yes')".
                                "{".
                                    "var p = {};".
                                    "p.id_clasificacion_servicio = ".$_GET["cid"].";".
                                    "sendToApi_eliminar(p);".
                                "}".
                            "}".
                            "      ".
                            "function confirmar(){".
                            " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar esta clasificacion?', eliminar_clasificacion );".
                            "}";

                    $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);

                    $menu->addMenuItem($btn_eliminar);

                    $page->addComponent( $menu);
                }
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_clasificacion );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_clasificacion_servicio",
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
