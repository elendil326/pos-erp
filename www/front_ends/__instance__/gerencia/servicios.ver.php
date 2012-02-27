<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "sid", "GET", "Este servicio no existe." );
		$este_servicio = ServicioDAO::getByPK( $_GET["sid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_servicio->getNombreServicio() , 2 ));

		
		//
		// Menu de opciones
		// 

		$menu = new MenuComponent();
		$menu->addItem("Editar este servicio", "servicios.editar.php?sid=".$_GET["sid"]);

		$btn_eliminar = new MenuItem("Desactivar este servicio", null);
		$btn_eliminar->addApiCall("api/servicios/eliminar", "GET");
		$btn_eliminar->onApiCallSuccessRedirect("servicios.lista.php");
		$btn_eliminar->addName("eliminar");

		$funcion_eliminar = " function eliminar_servicio(btn){".
		"if(btn == 'yes')".
		"{".
		"var p = {};".
		"p.id_servicio = ".$_GET["sid"].";".
		"sendToApi_eliminar(p);".
		"}".
		"}".
		"      ".
		"function confirmar(){".
		" Ext.MessageBox.confirm('Desactivar', 'Desea eliminar este servicio?', eliminar_servicio );".
		"}";

		$btn_eliminar->addOnClick("confirmar", $funcion_eliminar);

		$menu->addMenuItem($btn_eliminar);

		$page->addComponent( $menu);
                
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_servicio );
		
		$form->setEditable(false);
		
		$form->hideField( array( 
				"id_servicio",
			 ));
		$page->addComponent( $form );
                

        /*
                $tabla = new TableComponent(
                        array(
                            "id_clasificacion_cliente"  => "Clasificacion de cliente",
                            "es_margen_utilidad"        => "Precio o margen de utilidad",
                            "precio_utilidad"           => "Precio/Margen de Utilidad"
                        ),
                         PrecioServicioTipoClienteDAO::search( new PrecioServicioTipoCliente( array( "id_servicio" => $_GET["sid"] ) ) )
                        );
                function funcion_es_margen_utilidad($es_margen_utilidad)
                {
                    return $es_margen_utilidad ? "Margen de Utilidad" : "Precio" ;
                }
                
                function funcion_clasificacion_cliente($id_clasificacion_cliente)
                {
                    return ClasificacionClienteDAO::getByPK($id_clasificacion_cliente) ? 
                            ClasificacionClienteDAO::getByPK($id_clasificacion_cliente)->getNombre() : "------";
                }
                
                $tabla->addColRender("es_margen_utilidad", "funcion_es_margen_utilidad");
                $tabla->addColRender("id_clasificacion_cliente", "funcion_clasificacion_cliente");
                
                $page->addComponent($tabla);

                $page->addComponent( new TitleComponent( "Precios segun usuario" ) );
                
                $tabla = new TableComponent(
                        array(
                            "id_usuario"                => "Usuario",
                            "es_margen_utilidad"        => "Precio o margen de utilidad",
                            "precio_utilidad"           => "Precio/Margen de Utilidad"
                        ),
                         PrecioServicioUsuarioDAO::search( new PrecioServicioUsuario( array( "id_servicio" => $_GET["sid"] ) ) )
                        );
                
                function funcion_usuario($id_usuario)
                {
                    return UsuarioDAO::getByPK($id_usuario) ? 
                            UsuarioDAO::getByPK($id_usuario)->getNombre() : "------";
                }
                
                $tabla->addColRender("es_margen_utilidad", "funcion_es_margen_utilidad");
                $tabla->addColRender("id_usuario", "funcion_usuario");
                
                $page->addComponent($tabla);
*//*
                $page->addComponent( new TitleComponent( "Precios segun rol" ) );
                
                $tabla = new TableComponent(
                        array(
                            "id_rol"                    => "Rol",
                            "es_margen_utilidad"        => "Precio o margen de utilidad",
                            "precio_utilidad"           => "Precio/Margen de Utilidad"
                        ),
                         PrecioServicioRolDAO::search( new PrecioServicioRol( array( "id_servicio" => $_GET["sid"] ) ) )
                        );
                
                function funcion_rol($id_rol)
                {
                    return RolDAO::getByPK($id_rol) ? 
                            RolDAO::getByPK($id_rol)->getNombre() : "------";
                }
                
                $tabla->addColRender("es_margen_utilidad", "funcion_es_margen_utilidad");
                $tabla->addColRender("id_rol", "funcion_rol");
                
                $page->addComponent($tabla);
		*/
		$page->render();
