<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                $page->addComponent( new TitleComponent( "Roles" ) );
		$page->addComponent( new MessageComponent( "Lista de roles de usuario" ) );
		
		$tabla = new TableComponent( 
			array(
				"nombre" => "nombre",
				"descripcion"	=> "descripcion",
				"descuento" 		=> "desuento",
				"salario" 			=> "salario"
			),
                         PersonalYAgentesController::ListaRol()
		);
		
		$tabla->addOnClick( "id_rol", "(function(a){window.location = 'personal.rol.ver.php?pid=' + a;})", false, true );
		
		$page->addComponent( $tabla );
                
		$page->render();
