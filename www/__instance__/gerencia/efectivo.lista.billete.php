<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Billetes" ) );
		$page->addComponent( new MessageComponent( "Lista de billetes" ) );
		
		$tabla = new TableComponent( 
			array(
                                "nombre"                => "Nombre",
				"valor"                 => "Valor",
                                "id_moneda"             => "Moneda",
				"foto_billete"          => "Foto"
			),
			EfectivoController::ListaBillete()
		);
                
                function funcion_moneda($id_moneda)
                {
                    return (MonedaDAO::getByPK($id_moneda) ? MonedaDAO::getByPK($id_moneda)->getNombre() : "-----" );
                }
                
                $tabla->addColRender("id_moneda", "funcion_moneda");
                
		$tabla->addOnClick( "id_billete", "(function(a){ window.location = 'efectivo.billete.ver.php?bid=' + a; })" );
		
			
		$page->addComponent( $tabla );
                
		$page->render();
