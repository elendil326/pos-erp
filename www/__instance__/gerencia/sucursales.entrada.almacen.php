<?php 


		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		$page->addComponent(new TitleComponent("Entrada a almacen"));




		$page->addComponent(new FreeHtmlComponent("<script>
			
			var hola = function(a,p){console.log(p);};
			
		</script>"));

		
		$buscar_prods = new SearchProductComponent( );
		$buscar_prods->setOnSelectedJsFunction("hola");
		$page->addComponent( $buscar_prods );
		


		$page->render();
