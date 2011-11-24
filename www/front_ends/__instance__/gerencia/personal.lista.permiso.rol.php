<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Permisos de Rol" ) );
		$page->addComponent( new MessageComponent( "Lista de roles con sus permisos" ) );
		
		$tabla = new TableComponent( 
			array(
				"id_permiso"    => "Permiso",
                                "id_rol"        => "Rol"
			),
                         PersonalYAgentesController::ListaPermisoRol()
		);
                
                function funcion_permiso($id_permiso)
                {
                    return PermisoDAO::getByPK($id_permiso) ? PermisoDAO::getByPK($id_permiso)->getPermiso() : "-----";
                }
                
                function funcion_rol($id_rol)
                {
                    return RolDAO::getByPK($id_rol) ? RolDAO::getByPK($id_rol)->getNombre() : "-----";
                }
                
                $tabla->addColRender("id_permiso", "funcion_permiso");
                
                $tabla->addColRender("id_rol", "funcion_rol");
		
		$tabla->addOnClick( "id_rol", "(function(a){window.location = 'personal.rol.ver.php?pid=' + a;})", false, true );
		
		$page->addComponent( $tabla );
                
		$page->render();
