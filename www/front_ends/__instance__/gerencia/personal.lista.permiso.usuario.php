<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Permisos de Usuario" ) );
		$page->addComponent( new MessageComponent( "Lista de usuarios con sus permisos" ) );
		
		$tabla = new TableComponent( 
			array(
				"id_permiso"    => "Permiso",
                                "id_usuario"        => "Usuario"
			),
                         PersonalYAgentesController::ListaPermisoUsuario()
		);
                
                function funcion_permiso($id_permiso)
                {
                    return PermisoDAO::getByPK($id_permiso) ? PermisoDAO::getByPK($id_permiso)->getPermiso() : "-----";
                }
                
                function funcion_usuario($id_usuario)
                {
                    return UsuarioDAO::getByPK($id_usuario) ? UsuarioDAO::getByPK($id_usuario)->getNombre() : "-----";
                }
                
                $tabla->addColRender("id_permiso", "funcion_permiso");
                
                $tabla->addColRender("id_usuario", "funcion_usuario");
		
		$tabla->addOnClick( "id_usuario", "(function(a){window.location = 'personal.usuario.ver.php?uid=' + a;})", false, true );
		
		$page->addComponent( $tabla );
                
		$page->render();
