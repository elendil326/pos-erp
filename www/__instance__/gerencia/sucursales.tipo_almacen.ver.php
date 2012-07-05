<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

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
                
                $almacenes = AlmacenDAO::search( new Almacen( array( "id_tipo_almacen" => $_GET["tid"] ) ) );
                if(empty($almacenes))
                {
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
                }
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_tipo_almacen );
		$form->setEditable(false);	
		$form->hideField( array( 
				"id_tipo_almacen",
				"activo"
			 ));
		$page->addComponent( $form );
                
                $page->addComponent( new TitleComponent( "Almacenes con este tipo de almacen" ) );
		$r = AlmacenesController::Buscar(null, null, null, $_GET["tid"]);
		$tabla = new TableComponent( 
			array(
                                "nombre" => "Nombre",
				"id_sucursal"=> "Sucursal",
				"id_empresa"=> "Empresa",
				"activo"=> "Activo"
			),
			$r['resultados']
		);
		function funcion_sucursal( $id_sucursal )
                {
                    return SucursalDAO::getByPK($id_sucursal) ? SucursalDAO::getByPK($id_sucursal)->getRazonSocial() : "------";
                }
                
                function funcion_empresa( $id_empresa )
                {
                    return EmpresaDAO::getByPK($id_empresa) ? EmpresaDAO::getByPK($id_empresa)->getRazonSocial() : "------";
                }
                
                function funcion_activo( $activo )
                {
                    return ($activo) ? "Activo" : "Inactivo";
                }
                
                $tabla->addColRender("id_sucursal", "funcion_sucursal");
                $tabla->addColRender("id_empresa", "funcion_empresa");
                $tabla->addColRender("id_tipo_almacen", "funcion_tipo_almacen");
                $tabla->addColRender("activo", "funcion_activo");
                
		$tabla->addOnClick( "id_almacen", "(function(a){window.location = 'sucursales.almacen.ver.php?aid='+a;})" );
		
		$page->addComponent( $tabla );
		
		$page->render();
