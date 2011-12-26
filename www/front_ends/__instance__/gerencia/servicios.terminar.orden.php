<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                $page->requireParam(  "oid", "GET", "Esta orden de servicio no existe." );
		$esta_orden = OrdenDeServicioDAO::getByPK( $_GET["oid"] );
                
                //titulos
                $page->addComponent( new TitleComponent( "Terminar Orden " ) );

                $form = new DAOFormComponent( array (new Venta() ) );
                
                $form->addField("id_orden", "id_orden", "text", $_GET["oid"]);

                $form->hideField( array( 
                                "id_orden",
                                "id_venta",
                                "id_caja",
                                "id_venta_caja",    
                                "id_comprador_venta",
                                "fecha",
                                "subtotal",
                                "impuesto",
                                "total",
                                "id_sucursal",
                                "id_usuario",
                                "cancelada",
                                "retencion"
                         ));
                
                $form->renameField(array( "tipo_de_venta" => "tipo_venta" ));
                
                $form->sendHidden("id_orden");

                $form->addApiCall( "api/servicios/orden/terminar/");

                $form->onApiCallSuccessRedirect( "servicios.lista.orden.php" );

                $form->makeObligatory(array( 
                                "tipo_venta"
		));
                
                $form->createComboBoxJoin("tipo_venta", "tipo_venta", array( "credito" , "contado" ));
                
                $form->createComboBoxJoin("tipo_de_pago", "tipo_de_pago", array( "cheque" , "tarjeta", "efectivo" ), "efectivo");
                
                $page->addComponent($form);

		$page->render();
