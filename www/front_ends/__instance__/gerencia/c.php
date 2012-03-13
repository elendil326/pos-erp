<?php 

		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaTabPage(  );
		
		$page->addComponent(new TitleComponent("Configuracion"));
		
		
		$page->nextTab("Importar");

		$page->addComponent( new TitleComponent("Importar clientes", 3));		
		$importarClientes = new FormComponent();
		$importarClientes->addField("raw_content", "contenido", "textarea");
		$importarClientes->addApiCall("api/clientes/importar/", "POST");
		$page->addComponent( $importarClientes );



		$page->addComponent( new TitleComponent("Importar productos", 3));
		$importarProductos = new FormComponent();
		$importarProductos->addField("raw_content", "contenido", "textarea");
		$importarProductos->addApiCall("api/producto/importar/", "POST");
		$page->addComponent( $importarProductos );


		$page->nextTab("Exportar");
		
		
		$page->render();
