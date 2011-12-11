<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

                $page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Clientes" ) );
		$page->addComponent( new MessageComponent( "Lista de clientes " ) );
		
		$tabla = new TableComponent( 
			array(
                                "codigo_usuario"                => "Codigo de usuario",
				"nombre"                        => "Nombre",
				"id_rol"                        => "Rol",
				"id_clasificacion_cliente" 	=> "Clasificacion de cliente",
                                "activo"                        => "Activo",
                                "consignatario"                 => "Consignatario",
                                "saldo_del_ejercicio"           => "Saldo"
			),
			ClientesController::Lista()
		);
                
                function funcion_rol($id_rol)
                {
                    return (RolDAO::getByPK($id_rol) ? RolDAO::getByPK($id_rol)->getNombre() : "sin rol" );
                }
                
                function funcion_clasificacion_cliente($id_clasificacion_cliente)
                {
                    return (ClasificacionClienteDAO::getByPK($id_clasificacion_cliente) ? ClasificacionClienteDAO::getByPK($id_clasificacion_cliente)->getNombre() : "----" );
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
                $tabla->addColRender("activo", "funcion_activo");
                $tabla->addColRender("consignatario", "funcion_consignatario");
                
		$tabla->addOnClick( "id_usuario", "(function(a){ window.location = 'clientes.ver.php?cid=' + a; })" );
		
			
		$page->addComponent( $tabla );
                
		$page->render();
