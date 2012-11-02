<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );

		//
		// Parametros necesarios
		// 
		$page->requireParam(  "rid", "GET", "Este rol no existe." );
		$este_rol = RolDAO::getByPK( $_GET["rid"] );
		
                $usuarios_rol = UsuarioDAO::search( new Usuario( array( "id_rol" => $_GET["rid"] , "activo" => 1 ) ) );
                
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_rol->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar este rol", "personal.editar.rol.php?rid=".$_GET["rid"]);
                
                if(empty ($usuarios_rol))
                {
                
                    $btn_eliminar = new MenuItem("Eliminar este rol", null);
                    $btn_eliminar->addApiCall("api/personal/rol/eliminar");
                    $btn_eliminar->onApiCallSuccessRedirect("personal.lista.rol.php");
                    $btn_eliminar->addName("eliminar");

                    $funcion_eliminar = " function eliminar_rol(btn){".
                                "if(btn == 'yes')".
                                "{".
                                    "var p = {};".
                                    "p.id_rol = ".$_GET["rid"].";".
                                    "sendToApi_eliminar(p);".
                                "}".
                            "}".
                            "      ".
                            "function confirmar(){".
                            " Ext.MessageBox.confirm('Eliminar', 'Desea eliminar este rol?', eliminar_rol );".
                            "}";

                    $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);

                    $menu->addMenuItem($btn_eliminar);
                }
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_rol );
                $form->setEditable(false);
		$form->hideField( array( 
				"id_rol",
			 ));
                
		$page->addComponent( $form );
                
                $page->addComponent( new TitleComponent( "Usuarios con este rol" ), 3 );
                
                $tabla = new TableComponent(
                        array(
                            "codigo_usuario"    => "Codigo de usuario",
                            "nombre"            => "Nombre"
                        ),
                         $usuarios_rol
                        );
                
                $tabla->addOnClick( "id_usuario", "(function(a){window.location = 'personal.usuario.ver.php?uid=' + a;})" );
                
                $page->addComponent($tabla);
		
		$page->render();
