<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Esta caja no existe." );
		$esta_caja = CajaDAO::getByPK( $_GET["cid"] );
                
                $page->addComponent( new TitleComponent( "Abriendo caja ".$esta_caja->getDescripcion() , 3) );
                
                //
		// Forma de producto
		// 
		$form = new DAOFormComponent( new AperturaCaja() );
                
                
                
                $form->addApiCall("api/sucursal/caja/abrir", "GET");
                $form->onApiCallSuccessRedirect("sucursales.lista.caja.php");
		
                $form->addField("client_token", "client_token", "text","clienttoken");
                
                $form->addField("control_billetes", "control_billetes", "text");
                
                $form->createComboBoxJoin("control_billetes", "control_billetes", 
                        array(
                            array( "id" => 1, "caption" => "Llevar control" ),
                            array( "id" => 0, "caption" => "No llevar control"  ) 
                            ),
                        $esta_caja->getControlBilletes()
                        );
                
		$form->hideField( array( 
				"id_apertura_caja",
                                "id_caja",
                                "fecha"
			 ));
                
                $form->makeObligatory(
                        array(
                                "saldo",
                                "client-token",
                                "control_billetes"
                            )
                        );
                
                $form->createComboBoxJoinDistintName("id_cajero","id_usuario", "nombre",
                    UsuarioDAO::search(new Usuario( array( "id_rol" => 3, "activo" => 1 ) )) );
                
                $form->setValueField("id_caja", $_GET["cid"]);
                $form->sendHidden("id_caja");
                
		$page->addComponent( $form );

		$page->render();
