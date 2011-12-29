<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "oid", "GET", "Esta orden de servicio no existe." );
		$esta_orden = OrdenDeServicioDAO::getByPK( $_GET["oid"] );
		
		
		//
		// Titulo de la pagina
		// 

		
		$customer = UsuarioDAO::getByPK($esta_orden->getIdUsuarioVenta() );
		$link_to_customer = "<a href='clientes.ver.php?cid=". $esta_orden->getIdUsuarioVenta() . "'>";
		$link_to_customer .= $customer->getNombre();
		$link_to_customer .= "</a>";


		$page->addComponent( new TitleComponent( "Orden de servicio " . $_GET["oid"] ." para " . $link_to_customer, 2 ));
		
		//
		// Menu de opciones
		// 
                if($esta_orden->getActiva())
                {
                    $menu = new MenuComponent();
                    $menu->addItem("Nuevo seguimiento a esta orden de servicio", "servicios.seguimiento.orden.php?oid=".$_GET["oid"]);

                    $menu->addItem("Agregar productos a esta orden de servicio", "servicios.agregar_productos.orden.php?oid=".$_GET["oid"]);

                    $menu->addItem("Quitar productos a esta orden de servicio", "servicios.quitar_productos.orden.php?oid=".$_GET["oid"]);


                    $btn_eliminar = new MenuItem("Cancelar esta orden de servicio", null);
                    $btn_eliminar->addApiCall("api/servicios/orden/cancelar", "GET");
                    $btn_eliminar->onApiCallSuccessRedirect("servicios.lista.orden.php");
                    $btn_eliminar->addName("cancelar");

                    $funcion_cancelar = " function cancelar_orden(btn){".
                                "if(btn == 'yes')".
                                "{".
                                    "var p = {};".
                                    "p.id_orden_de_servicio = ".$_GET["oid"].";".
                                    "sendToApi_cancelar(p);".
                                "}".
                            "}".
                            "      ".
                            "function confirmar_cancelacion(){".
                            " Ext.MessageBox.confirm('Cancelar', 'Desea cancelar esta orden?', cancelar_orden );".
                            "}";

                    $btn_eliminar->addOnClick("confirmar_cancelacion", $funcion_cancelar);

                    $menu->addMenuItem($btn_eliminar);

                    $btn_terminar = new MenuItem("Terminar esta orden de servicio", null);
                    $btn_terminar->addApiCall("api/servicios/orden/terminar", "POST");
                    $btn_terminar->onApiCallSuccessRedirect("servicios.lista.orden.php");
                    $btn_terminar->addName("terminar");

                    $funcion_terminar = " function terminar_orden(btn){".
                                "if(btn == 'yes')".
                                "{".
                                    "window.location = \"servicios.terminar.orden.php?oid=".$_GET["oid"]."\";".
                                "}".
                            "}".
                            "      ".
                            "function confirmar_termino(){".
                            " Ext.MessageBox.confirm('Terminar', 'Desea terminar esta orden?', terminar_orden );".
                            "}";

                    $btn_terminar->addOnClick("confirmar_termino", $funcion_terminar);

                    $menu->addMenuItem($btn_terminar);
                
                
                    $page->addComponent( $menu);
                }
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_orden );
		$form->setEditable(false);
		
		$form->hideField( array( 
				"id_orden_de_servicio",
			 ));


	    $form->createComboBoxJoin("id_servicio", "nombre_servicio", ServicioDAO::getAll(), $esta_orden->getIdServicio() );
		$form->createComboBoxJoin("id_usuario", "nombre", UsuarioDAO::getAll(), $esta_orden->getIdUsuario() );
		$form->createComboBoxJoinDistintName("id_usuario_venta", "id_usuario", "nombre", UsuarioDAO::getAll(), $esta_orden->getIdUsuarioVenta() );
		$page->addComponent( $form );
		
		
		$page->addComponent( new TitleComponent( "Seguimientos de esta orden", 2 ) );
		
		$seguimientos = SeguimientoDeServicioDAO::seguimientosPorServicio( $_GET["oid"] );
		
		$header = array(
			"fecha_seguimiento" 			=> "Fecha", 
			"id_localizacion" 				=> "Sucursal actual" ,
			"id_usuario" 					=> "Usuario que registro" ,
			"estado" 						=> "Estado" 
		);
                
                $table = new TableComponent($header, $seguimientos);
                
                function funcion_sucursal($id_sucursal)
                {
                    return ( SucursalDAO::getByPK($id_sucursal) ? SucursalDAO::getByPK($id_sucursal)->getRazonSocial() : "---------" );
                }
                
                function funcion_usuario($id_usuario)
                {
                    return ( UsuarioDAO::getByPK($id_usuario) ? UsuarioDAO::getByPK($id_usuario)->getNombre() : "----------" );
                }
                
                $table->addColRender("id_localizacion", "funcion_sucursal");
                
                $table->addColRender("id_usuario", "funcion_usuario");
                
		
		$page->addComponent( $table );
		
		$page->render();
