<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "sid", "GET", "Esta sucursal no existe." );
		$esta_sucursal = SucursalDAO::getByPK( $_GET["sid"] );
                $esta_direccion = DireccionDAO::getByPK($esta_sucursal->getIdDireccion());
                if(is_null($esta_direccion))
                    $esta_direccion = new Direccion();
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_sucursal->getRazonSocial() , 2 ));

		
		//
		// Menu de opciones
		// 
                if($esta_sucursal->getActiva())
                {
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
                }
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_sucursal );
		$form->setEditable(false);	
		$form->hideField( array( 
				"id_sucursal",
                                "id_direccion"
			 ));
		$page->addComponent( $form );
		
                
                if(!is_null($esta_sucursal->getIdDireccion()))
                {
                    $page->addComponent(new TitleComponent("Direccion", 3));
                    
                    $form = new DAOFormComponent( $esta_direccion );
                    $form->setEditable(false);
                    $form->hideField(array( "id_direccion", "id_usuario_ultima_modificacion" ));
                    $form->createComboBoxJoin("id_ciudad", "nombre", CiudadDAO::getAll(), $esta_direccion->getIdCiudad());
                    
                    $page->addComponent($form);
                }
                
                $page->addComponent( new TitleComponent("Cajas", 3) );
                
                $tabla = new TableComponent( 
			array(
				"descripcion"=> "Descripcion",
                                "saldo"=> "Saldo",
				"abierta"=> "Abierta",
                                "activa"=>"Activa"
			),
                         SucursalesController::ListaCaja(NULL, $_GET["sid"])
		);
                
                
                function funcion_abierta( $abierta )
                {
                    return $abierta ? "Abierta" : "Cerrada";
                }
                
                function funcion_activa( $activa )
                {
                    return $activa ? "Activa" : "Inactiva";
                }
                
                $tabla->addColRender("abierta", "funcion_abierta");
                $tabla->addColRender("activa", "funcion_activa");
                
		$tabla->addOnClick( "id_caja", "(function(a){window.location = 'sucursales.caja.ver.php?cid='+a;})" );
                
                $page->addComponent($tabla);
                
                $page->addComponent( new TitleComponent( "Almacenes" , 3) );
                
                $tabla = new TableComponent( 
			array(
                                "nombre" => "Nombre",
				"id_empresa"=> "Empresa",
				"id_tipo_almacen"=> "Tipo de almacen",
				"activo"=> "Activo"
			),
                         SucursalesController::ListaAlmacen(null, null, $_GET["sid"])
		);
                
                function funcion_empresa( $id_empresa )
                {
                    return EmpresaDAO::getByPK($id_empresa) ? EmpresaDAO::getByPK($id_empresa)->getRazonSocial() : "------";
                }
                
                function funcion_tipo_almacen( $id_tipo_almacen )
                {
                    return TipoAlmacenDAO::getByPK($id_tipo_almacen) ? TipoAlmacenDAO::getByPK($id_tipo_almacen)->getDescripcion() : "------";
                }
                
                function funcion_activo( $activo )
                {
                    return ($activo) ? "Activo" : "Inactivo";
                }
                
                $tabla->addColRender("id_empresa", "funcion_empresa");
                $tabla->addColRender("id_tipo_almacen", "funcion_tipo_almacen");
                $tabla->addColRender("activo", "funcion_activo");
                
		$tabla->addOnClick( "id_almacen", "(function(a){window.location = 'sucursales.almacen.ver.php?aid='+a;})" );
		
		$page->addComponent( $tabla );
                
		$page->render();
