<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "bid", "GET", "Este billete no existe." );
		$este_billete = BilleteDAO::getByPK( $_GET["bid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_billete->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
                if($este_billete->getActivo())
                {
                    $menu = new MenuComponent();
                    $menu->addItem("Editar este billete", "efectivo.editar.billete.php?bid=".$_GET["bid"]);
                    //$menu->addItem("Desactivar este producto", null);

                    $btn_eliminar = new MenuItem("Desactivar este billete", null);
                    $btn_eliminar->addApiCall("api/efectivo/billete/eliminar", "GET");
                    $btn_eliminar->onApiCallSuccessRedirect("efectivo.lista.billete.php");
                    $btn_eliminar->addName("eliminar");

                    $funcion_eliminar = " function eliminar_billete(btn){".
                                "if(btn == 'yes')".
                                "{".
                                    "var p = {};".
                                    "p.id_billete = ".$_GET["bid"].";".
                                    "sendToApi_eliminar(p);".
                                "}".
                            "}".
                            "      ".
                            "function confirmar(){".
                            " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar este billete?', eliminar_billete );".
                            "}";

                    $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);

                    $menu->addMenuItem($btn_eliminar);

                    $page->addComponent( $menu);
                }
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_billete );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_billete",
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
	    $form->createComboBoxJoin("id_moneda", "nombre", MonedaDAO::getAll(), $este_billete->getIdMoneda() );
		$page->addComponent( $form );
		
		$page->render();
