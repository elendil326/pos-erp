<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                $page->addComponent( new TitleComponent( "Almacenes" ) );

		$tabla = new TableComponent( 
			array(
                                "nombre" => "Nombre",
				"id_sucursal"=> "Sucursal",
				"id_empresa"=> "Empresa",
				"id_tipo_almacen"=> "Tipo de almacen",
				"activo"=> "Activo"
			),
                         SucursalesController::ListaAlmacen()
		);
		function funcion_sucursal( $id_sucursal )
                {
                    return SucursalDAO::getByPK($id_sucursal) ? SucursalDAO::getByPK($id_sucursal)->getRazonSocial() : "------";
                }
                
                function funcion_empresa( $id_empresa )
                {
                    return EmpresaDAO::getByPK($id_empresa) ? EmpresaDAO::getByPK($id_empresa)->getRazonSocial() : "------";
                }
                
                function funcion_tipo_almacen( $id_tipo_almacen )
                {
                    return TipoAlmacenDAO::getByPK($id_tipo_almacen) ? TipoAlmacenDAO::getByPK($id_tipo_almacen)->getDescripcion() : "------";
                }
                
                function funcion_activo( $activo )
                {
                    return ($activo) ? "Activo" : "Inactivo";
                }
                
                $tabla->addColRender("id_sucursal", "funcion_sucursal");
                $tabla->addColRender("id_empresa", "funcion_empresa");
                $tabla->addColRender("id_tipo_almacen", "funcion_tipo_almacen");
                $tabla->addColRender("activo", "funcion_activo");
                
		$tabla->addOnClick( "id_almacen", "(function(a){window.location = 'sucursales.almacen.ver.php?aid='+a;})" );
		
		$page->addComponent( $tabla );
                
		$page->render();
