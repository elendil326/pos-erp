<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "tid", "GET", "Este tipo de almacen no existe." );
		$este_tipo_almacen = TipoAlmacenDAO::getByPK( $_GET["tid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_tipo_almacen->getDescripcion() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar este tipo de almacen", "sucursales.editar.tipo_almacen.php?tid=".$_GET["tid"]);
                
                $btn_eliminar = new MenuItem("Eliminar este tipo de almacen", null);
                $btn_eliminar->addApiCall("api/sucursal/tipo_almacen/eliminar", "GET");
                $btn_eliminar->onApiCallSuccessRedirect("sucursales.lista.tipo_almacen.php");
                $btn_eliminar->addName("eliminar");
                
                $funcion_eliminar = " function eliminar_sucursal(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_tipo_almacen = ".$_GET["tid"].";".
                                "sendToApi_eliminar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar(){".
                        " Ext.MessageBox.confirm('Eliminar', 'Desea eliminar este tipo de almacen?', eliminar_sucursal );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_tipo_almacen );
		$form->setEditable(false);	
		$form->hideField( array( 
				"id_tipo_almacen"
			 ));
		$page->addComponent( $form );
		
		$page->render();