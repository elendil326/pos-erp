<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

		$page->addComponent( new TitleComponent( "Existencias" ) );
		

		$tabla = new TableComponent( 
			array(
                "id_producto"                   => "Producto",
				"id_almacen"                    => "Almacen",
				"id_unidad"                     => "Unidad",
				"cantidad"               	=> "Cantidad"
			),
			InventarioController::Existencias()
		);
        

        
		function funcion_producto($id_producto)
                {
                    return (ProductoDAO::getByPK($id_producto) ? ProductoDAO::getByPK($id_producto)->getNombreProducto() : "-----" );
                }

                function funcion_almacen($id_almacen)
                {
                    return (AlmacenDAO::getByPK($id_almacen) ? AlmacenDAO::getByPK($id_almacen)->getNombre() : "----" );
                }

                function funcion_unidad($id_unidad)
                {
                    return (UnidadDAO::getByPK($id_unidad) ? UnidadDAO::getByPK($id_unidad)->getNombre() : "-----" );
                }

                $tabla->addColRender("id_producto", "funcion_producto");
                $tabla->addColRender("id_almacen", "funcion_almacen");
                $tabla->addColRender("id_unidad", "funcion_unidad");
        
		$tabla->addOnClick( "id_producto", "(function(a){ window.location = 'productos.ver.php?pid=' + a; })" );
		
			
		$page->addComponent( $tabla );
                
		$page->render();
