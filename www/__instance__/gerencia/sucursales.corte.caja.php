<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                 //
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Esta caja no existe." );
		$esta_caja = CajaDAO::getByPK( $_GET["cid"] );
                
                $page->addComponent( new TitleComponent( "Corte a la caja ".$esta_caja->getDescripcion() .". El saldo esperado es de: ".$esta_caja->getSaldo(), 3) );
                
                //
		// Forma de producto
		// 
		$form = new DAOFormComponent( new CorteDeCaja() );
                
                
                
                $form->addApiCall("api/sucursal/caja/corte", "GET");
                $form->onApiCallSuccessRedirect("sucursales.lista.caja.php");
                
		$form->hideField( array( 
				"id_corte_de_caja",
                                "saldo_esperado",
                                "id_caja",
                                "fecha"
			 ));
                
                $form->makeObligatory(
                        array(
                                "saldo_real",
                                "saldo_final"
                            )
                        );
                
                $form->createComboBoxJoinDistintName("id_cajero","id_usuario", "nombre",
                    UsuarioDAO::search(new Usuario( array( "id_rol" => 3, "activo" => 1 ), SesionController::getCurrentUser() )) );
                
                $form->createComboBoxJoinDistintName("id_cajero_nuevo","id_usuario", "nombre",
                    UsuarioDAO::search(new Usuario( array( "id_rol" => 3, "activo" => 1 ), SesionController::getCurrentUser() )) );
                
                $form->setValueField("id_caja", $_GET["cid"]);
                $form->sendHidden("id_caja");
                
		$page->addComponent( $form );
                
		$page->render();
