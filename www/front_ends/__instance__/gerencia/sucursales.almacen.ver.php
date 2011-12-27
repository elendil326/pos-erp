<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "aid", "GET", "Este almacen no existe." );
		$este_almacen = AlmacenDAO::getByPK( $_GET["aid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_almacen->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
                if($este_almacen->getActivo())
                {
                    $menu = new MenuComponent();
                    $menu->addItem("Editar este almacen", "sucursales.editar.almacen.php?aid=".$_GET["aid"]);
                    //$menu->addItem("Desactivar este producto", null);

                    $btn_eliminar = new MenuItem("Desactivar este almacen", null);
                    $btn_eliminar->addApiCall("api/sucursal/almacen/eliminar", "GET");
                    $btn_eliminar->onApiCallSuccessRedirect("sucursales.lista.almacen.php");
                    $btn_eliminar->addName("eliminar");

                    $funcion_eliminar = " function eliminar_almacen(btn){".
                                "if(btn == 'yes')".
                                "{".
                                    "var p = {};".
                                    "p.id_almacen = ".$_GET["aid"].";".
                                    "sendToApi_eliminar(p);".
                                "}".
                            "}".
                            "      ".
                            "function confirmar(){".
                            " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar este almacen?', eliminar_almacen );".
                            "}";

                    $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);

                    $menu->addMenuItem($btn_eliminar);

                    $page->addComponent( $menu);
                }
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_almacen );
		$form->setEditable(false);		
		$form->hideField( array( 
				"id_almacen",
			 ));
                
                $form->createComboBoxJoin( "id_sucursal", "razon_social", SucursalDAO::getAll(), $este_almacen->getIdSucursal() );
                $form->createComboBoxJoin( "id_empresa", "razon_social", EmpresaDAO::search( new Empresa( array( "activo" => 1 ) ) ), $este_almacen->getIdEmpresa() );
                $form->createComboBoxJoin("id_tipo_almacen", "descripcion", TipoAlmacenDAO::getAll(), $este_almacen->getIdTipoAlmacen() );
                
		$page->addComponent( $form );
                
                $page->addComponent( new TitleComponent( "Productos en este almacen" ), 3 );
                
                $tabla = new TableComponent(
                        array(
                            "id_producto"       => "Producto",
                            "id_unidad"         => "Unidad",
                            "cantidad"          => "Cantidad",
                        ),
                        ProductoAlmacenDAO::search( new ProductoAlmacen( array( "id_almacen" => $_GET["aid"] ) ) )
                        );
                function funcion_producto($id_producto)
                {
                    return ProductoDAO::getByPK($id_producto)? ProductoDAO::getByPK($id_producto)->getNombreProducto() : "--------";
                }
                
                function funcion_unidad($id_unidad)
                {
                    return UnidadDAO::getByPK($id_unidad)? UnidadDAO::getByPK($id_unidad)->getNombre() : "---------";
                }
                
                $tabla->addColRender("id_producto", "funcion_producto");
                
                $tabla->addColRender("id_unidad", "funcion_unidad");
                
                $page->addComponent($tabla);
		
		$page->render();
