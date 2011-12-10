<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "sid", "GET", "Esta sucursal no existe." );
		$esta_sucursal = SucursalDAO::getByPK( $_GET["sid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_sucursal->getRazonSocial() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar esta sucursal", "sucursales.editar.php?sid=".$_GET["sid"]);
		//$menu->addItem("Desactivar este producto", null);
                
                $btn_eliminar = new MenuItem("Desactivar esta sucursal", null);
                $btn_eliminar->addApiCall("api/sucursal/eliminar", "GET");
                $btn_eliminar->onApiCallSuccessRedirect("sucursales.lista.php");
                $btn_eliminar->addName("eliminar");
                
                $funcion_eliminar = " function eliminar_sucursal(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_sucursal = ".$_GET["sid"].";".
                                "sendToApi_eliminar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar(){".
                        " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar esta sucursal?', eliminar_sucursal );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_sucursal );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_sucursal",
                                "id_direccion"
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
