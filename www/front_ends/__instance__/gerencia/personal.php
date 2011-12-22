<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Roles" ) );
		$page->addComponent( new MessageComponent( "Lista de roles de usuario" ) );
		
		$tabla = new TableComponent( 
			array(
				"nombre" => "Nombre",
				"descripcion"	=> "Descripcion",
				"descuento" 		=> "Desuento",
				"salario" 			=> "Salario"
			),
                         PersonalYAgentesController::ListaRol()
		);
		
		$tabla->addOnClick( "id_rol", "(function(a){window.location = 'personal.rol.ver.php?rid=' + a;})" );
		
		$page->addComponent( $tabla );
                
                $page->addComponent( new TitleComponent( "Usuarios" ) );
		$page->addComponent( new MessageComponent( "Lista de usuarios" ) );
		
		$tabla = new TableComponent( 
			array(
                                "codigo_usuario"                => "Codigo de usuario",
				"nombre"                        => "Nombre",
				"id_rol"                        => "Rol",
				"id_clasificacion_cliente" 	=> "Clasificacion de cliente",
                                "id_clasificacion_proveedor"    => "Clasificacion de proveedor",
                                "activo"                        => "Activo",
                                "consignatario"                 => "Consignatario",
                                "saldo_del_ejercicio"           => "Saldo"
			),
                         PersonalYAgentesController::ListaUsuario()
		);
		
                function funcion_rol($id_rol)
                {
                    return (RolDAO::getByPK($id_rol) ? RolDAO::getByPK($id_rol)->getNombre() : "sin rol" );
                }
                
                function funcion_clasificacion_cliente($id_clasificacion_cliente)
                {
                    return (ClasificacionClienteDAO::getByPK($id_clasificacion_cliente) ? ClasificacionClienteDAO::getByPK($id_clasificacion_cliente)->getNombre() : "----" );
                }
                
                function funcion_clasificacion_proveedor($id_clasificacion_proveedor)
                {
                    return (ClasificacionProveedorDAO::getByPK($id_clasificacion_proveedor) ? ClasificacionProveedorDAO::getByPK($id_clasificacion_proveedor)->getNombre() : "----" );
                }
                
                function funcion_activo($activo)
                {
                    return ($activo ? "Activo" : "Inactivo" );
                }
                
                function funcion_consignatario($consignatario)
                {
                    return ($consignatario ? "Consignatario" : "----" );
                }
                
                $tabla->addColRender("id_rol", "funcion_rol");
                $tabla->addColRender("id_clasificacion_cliente", "funcion_clasificacion_cliente");
                $tabla->addColRender("id_clasificacion_proveedor", "funcion_clasificacion_proveedor");
                $tabla->addColRender("activo", "funcion_activo");
                $tabla->addColRender("consignatario", "funcion_consignatario");
                
		$tabla->addOnClick( "id_usuario", "(function(a){window.location = 'personal.usuario.ver.php?uid=' + a;})", false, true );
		
		$page->addComponent( $tabla );
                
		$page->render();
