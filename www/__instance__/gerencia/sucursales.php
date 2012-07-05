<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                 $page->addComponent( new TitleComponent( "Sucursales" ) );

		$tabla = new TableComponent( 
			array(
                                "rfc" => "RFC",
				"razon_social"=> "Razon Social",
                                "descripcion"=> "Descripcion",
                                "id_gerente" => "Gerente",
				"saldo_a_favor"=> "Saldo",
                                "activa"=>"Activa"
			),
                         SucursalesController::Lista()
		);
		function funcion_gerente( $id_gerente )
                {
                    return UsuarioDAO::getByPK($id_gerente) ? UsuarioDAO::getByPK($id_gerente)->getNombre() : "------";
                }
                
                function funcion_activa( $activa )
                {
                    return $activa ? "Activa" : "Inactiva";
                }
                
                $tabla->addColRender("id_gerente", "funcion_gerente");
                $tabla->addColRender("activa", "funcion_activa");
                
		$tabla->addOnClick( "id_sucursal", "(function(a){window.location = 'sucursales.ver.php?sid='+a;})" );
		
		$page->addComponent( $tabla );
                
		$page->render();
