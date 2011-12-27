<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Esta caja no existe." );
		$esta_caja = CajaDAO::getByPK( $_GET["cid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de caja: " . $esta_caja->getToken() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar esta caja", "sucursales.editar.caja.php?cid=".$_GET["cid"]);
		//$menu->addItem("Desactivar este producto", null);
                
                $btn_eliminar = new MenuItem("Desactivar esta caja", null);
                $btn_eliminar->addApiCall("api/sucursal/caja/eliminar", "GET");
                $btn_eliminar->onApiCallSuccessRedirect("sucursales.lista.caja.php");
                $btn_eliminar->addName("eliminar");
                
                $funcion_eliminar = " function eliminar_caja(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_caja = ".$_GET["cid"].";".
                                "sendToApi_eliminar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar(){".
                        " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar esta caja?', eliminar_caja );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_caja );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_caja",
			 ));
	    $form->createComboBoxJoin("id_sucursal", "razon_social", SucursalDAO::getAll(), $esta_caja->getIdSucursal() );
		$page->addComponent( $form );
		
		$page->render();
