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
			"abreviacion" =>"Nombre",
			"id_categoria_unidad_medida" =>"Categoria",
			"activa" =>"activa"
		),$u["resultados"]);
		
		function nombreRender($v, $obj){
			return $obj["descripcion"] . " (". $v .")";
		}
		
		function uCatRender($v, $obj){
			$c = CategoriaUnidadMedidaDAO::getByPK($v);
			return $c->getDescripcion();
		}

		$tUnidades->addColRender("id_categoria_unidad_medida", "uCatRender");		
		$tUnidades->addColRender("abreviacion", "nombreRender");
		$page->addcomponent($tUnidades);






		$page->addComponent(new TitleComponent("Nueva unidad de medida", 3));



		
		
		$nudmf = new DAOFormComponent(new UnidadMedida());
		$nudmf->hideField(array("id_unidad_medida"));
		$nudmf->addApiCall("api/producto/udm/unidad/nueva", "POST");
		$nudmf->createComboBoxJoin("id_categoria_unidad_medida", "descripcion", CategoriaUnidadMedidaDAO::getAll());
		$nudmf->createComboBoxJoin(	"tipo_unidad_medida", "desc", array( "desc" => "No Referencia" ) );
		$nudmf->createComboBoxJoin(	"activa", null,  array( "si", "no" ) );
		$nudmf->setCaption("id_categoria_unidad_medida", "Categoria");
		$nudmf->makeObligatory(array("abreviacion", "descripcion", "factor_conversion", "id_categoria_unidad_medida", "tipo_unidad_medida"));
		$page->addComponent( $nudmf );
		
		
		
		
		
		$page->addComponent(new TitleComponent("Nueva categoria de unidad de medida", 3));
		$ncudmf = new DAOFormComponent(new CategoriaUnidadMedida());
		$ncudmf->hideField(array("id_categoria_unidad_medida"));
		$ncudmf->addApiCall("api/producto/udm/categoria/nueva", "POST");
		$page->addComponent( $ncudmf );
		
		
		
		
		
		$page->render();
		
		
		
		
		
		
