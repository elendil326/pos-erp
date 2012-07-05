<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Traspasos" ) );

		$tabla = new TableComponent( 
			array(
                                "id_almacen_envia" => "Del almacen",
				"id_almacen_recibe"=> "Al almacen",
				"estado"=> "Estado",
				"fecha_envio_programada"=> "Fecha de envio programada",
				"fecha_envio"=> "Fecha de envio real",
                                "fecha_recibo"=> "Fecha recibido"
			),
                         SucursalesController::ListaTraspasoAlmacen()
		);
		function funcion_almacen( $id_almacen )
                {
                    return AlmacenDAO::getByPK($id_almacen) ? AlmacenDAO::getByPK($id_almacen)->getNombre() : "------";
                }
                
                $tabla->addColRender("id_almacen_envia", "funcion_almacen");
                $tabla->addColRender("id_almacen_recibe", "funcion_almacen");
                
		$tabla->addOnClick( "id_traspaso", "(function(a){window.location = 'sucursales.traspaso.ver.php?tid='+a;})" );
		
		$page->addComponent( $tabla );
                
		$page->render();
