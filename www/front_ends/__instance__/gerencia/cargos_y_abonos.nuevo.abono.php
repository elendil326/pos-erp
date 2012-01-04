<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                $compra = $venta = $prestamo = null;
                $page->requireParam(  "did", "GET", "No se obtuvo al deudor al que se le cargara el abono." );
                //
                // Titulo
                // 
                if(isset ($_GET["cid"]))
                {
                    $compra = true;
                    $page->addComponent( new TitleComponent( "Nuevo abono a la compra ".$_GET["cid"] , 2 ));
                }
                else if(isset($_GET["vid"]))
                {
                    $venta = true;
                    $page->addComponent( new TitleComponent( "Nuevo abono a la venta ".$_GET["vid"] , 2 ));
                }
                else if( isset( $_GET["pid"] ) )
                {
                    $prestamo = true;
                    $page->addComponent( new TitleComponent( "Nuevo abono al prestamo ".$_GET["pid"] , 2 ));
                }
                else
                {
                    $page->requireParam(  "cid", "GET", "No se sabe si el abono sera para compra, venta o prestamo." );
                }


                //
                // Forma de nuevo producto
                // 
                if($compra)
                {
                    $form = new DAOFormComponent( new AbonoCompra() );
                }
                else if ($venta)
                {
                    $form = new DAOFormComponent( new AbonoVenta() );
                }
                else if($prestamo)
                {
                    $form = new DAOFormComponent( new AbonoPrestamo() );
                }
                
                $form->addApiCall("api/efectivo/abono/nuevo/", "GET");
                $form->onApiCallSuccessRedirect( "cargos_y_abonos.lista.abono.php" );
                
                $id_deudor = $_GET["did"];
                
                $send = array("id_deudor");
                
                $hidden = array(
                                "id_sucursal",
                                "id_caja",
                                "id_receptor",
                                "id_deudor",
                                "cancelado",
                                "motivo_cancelacion",
                                "fecha"
                );
                
                if($compra)
                {
                    array_push($hidden,"id_compra");
                    array_push($hidden,"id_abono_compra");
                    array_push($send, "id_compra");
                    $form->setValueField("id_compra", $_GET["cid"]);
                }
                else if($venta)
                {
                    array_push($hidden,"id_venta");
                    array_push($hidden,"id_abono_venta");
                    array_push($send,"id_venta");
                    $form->setValueField("id_venta", $_GET["vid"]);
                }
                else if($prestamo)
                {
                    array_push($hidden,"id_prestamo");
                    array_push($hidden,"id_abono_prestamo");
                    array_push($send,"id_prestamo");
                    $form->setValueField("id_prestamo", $_GET["pid"]);
                }
                
                $form->hideField($hidden);
                
                $form->renameField(array( "tipo_de_pago" => "tipo_pago" ));
                
                $form->makeObligatory(array( 
                                "tipo_pago",
                                "monto"
                        ));
                $form->sendHidden($send);
                
                
                $form->setValueField( "id_deudor", $id_deudor);

                $form->createComboBoxJoin( "tipo_pago", "tipo_pago", array( "cheque" , "efectivo", "tarjeta" ) );
                $page->addComponent( $form );
                
		$page->render();
