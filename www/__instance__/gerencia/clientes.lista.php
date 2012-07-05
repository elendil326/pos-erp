<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server//bootstrap.php");

        $page = new GerenciaComponentPage();

		$page->addComponent( new TitleComponent( "Clientes" ) );
		$page->addComponent( new MessageComponent( "Lista de clientes" ) );

		
		$cselector = new ClienteSelectorComponent( );
		$cselector->addJsCallback( "(function(a){ window.location = 'clientes.ver.php?cid='+a.get('id_usuario'); })" );
		$page->addComponent( $cselector);

		$lista = ClientesController::Buscar();
		
		$tabla = new TableComponent( 
			array(
				"nombre"                        => "Nombre",
				"activo"                        => "Activo",
				"saldo_del_ejercicio"           => "Saldo"
			),
			$lista["resultados"]
		);

 		$tabla->addColRender("saldo_del_ejercicio", "FormatMoney");

         /*
        function funcion_clasificacion_cliente($id_clasificacion_cliente){
            return (ClasificacionClienteDAO::getByPK($id_clasificacion_cliente) 
						? ClasificacionClienteDAO::getByPK($id_clasificacion_cliente)->getNombre() : "----" );
        }
*/
        
        function funcion_activo($activo){
            return ($activo ? "Activo" : "Inactivo" );
        }
        
        function funcion_consignatario($consignatario){
            return ($consignatario ? "Consignatario" : "----" );
        }
                

        //$tabla->addColRender("id_clasificacion_cliente", "funcion_clasificacion_cliente");
        $tabla->addColRender("activo", "funcion_activo");
        $tabla->addColRender("consignatario", "funcion_consignatario");
		$tabla->addOnClick( "id_usuario", "(function(a){ window.location = 'clientes.ver.php?cid=' + a; })" );
		
			
		//$page->addComponent( $tabla );
                


        $page->partialRender();
        

		$page->render();
