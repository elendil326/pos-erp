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
                if($esta_caja->getActiva())
                {
                    $menu = new MenuComponent();
                    
                    $menu->addItem("Editar esta caja", "sucursales.editar.caja.php?cid=".$_GET["cid"]);
                    
                    if(!$esta_caja->getAbierta())
                    {
                        $menu->addItem("Abrir esta caja", "sucursales.abrir.caja.php?cid=".$_GET["cid"]);
                    }
                    else
                    {
                        $menu->addItem("Cerrar esta caja", "sucursales.cerrar.caja.php?cid=".$_GET["cid"]);

                        $menu->addItem("Realizar corte", "sucursales.corte.caja.php?cid=".$_GET["cid"]);
                    }

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
                }
		
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
		
                if($esta_caja->getControlBilletes())
                {
                    $page->addComponent( new TitleComponent( "Billetes en esta caja" , 3) );

                    $tabla = new TableComponent(
                            array(
                                "id_billete"    => "Billete",
                                "cantidad"      => "Cantidad"
                            ), 
                             BilleteCajaDAO::search( new BilleteCaja( array( "id_caja" => $_GET["cid"] ) ) )
                            );
                    
                    function funcion_billete($id_billete)
                    {
                        return BilleteDAO::getByPK($id_billete)? BilleteDAO::getByPK($id_billete)->getNombre() : "------";
                    }
                    
                    $tabla->addColRender("id_billete", "funcion_billete");
                    
                    $tabla->addOnClick( "id_billete", "(function(a){window.location = 'efectivo.billete.ver.php?bid='+a;})" );
                    
                    $page->addComponent($tabla);
                }
                
                $page->addComponent( new TitleComponent( "Aperturas" ) );
                
                $page->addComponent( new TitleComponent( "Cortes" ) );
                
                $page->addComponent( new TitleComponent( "Cierres" ) );
                
		$page->render();
