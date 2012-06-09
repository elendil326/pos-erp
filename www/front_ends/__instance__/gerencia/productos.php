<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaTabPage();

		$page->addComponent(new TitleComponent( "Productos" ));

		$page->nextTab("Lista");
		
		$cols = array(
			"codigo_producto" 	=> "Codigo producto",
			"nombre_producto"	=> "Nombre Producto",
			"precio" 			=> "Precio"
		);
		
		
		$tabla = new TableComponent( 
			$cols,
			ProductosController::Lista()
		);
	
	
		function precio($precio, $obj){
			if($obj["metodo_costeo"] === "costo"){
				return FormatMoney($obj["costo_estandar"]);
			}
			return FormatMoney($precio);
			
		}
		$tabla->addColRender( "precio", "precio" );
                
		$tabla->addOnClick( "id_producto", "(function(a){ window.location = 'productos.ver.php?pid=' + a; })" );
		
			
		$page->addComponent( $tabla );
		
		$page->nextTab("Categorias");
		
		
		
		
		$page->nextTab("Unidades");
		
		$u = ProductosController::BuscarUnidadUdm();
		
		$tUnidades = new TableComponent(array(
			"abreviacion" =>"abreviacion",
			"tipo_unidad_medida" =>"tipo_unidad_medida",
			"activa" =>"activa"
		),$u["resultados"]);
		
		$page->addcomponent($tUnidades);


		$page->addComponent(new TitleComponent("Nueva unidad de medida", 3));
		
		
		$nudmf = new DAOFormComponent(new UnidadMedida());
		$nudmf->hideField(array("id_unidad_medida"));
		$nudmf->addApiCall("api/producto/udm/unidad/nueva", "POST");
		$page->addComponent( $nudmf );
		
		$page->render();
