<?php 

		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaTabPage(  );
		
		$page->addComponent(new TitleComponent("Configuracion"));
		
		
		$page->nextTab("Importar");
		
		$importarClientes = new FormComponent();
		$importarClientes->addField("raw_content", "contenido", "textarea");
		$importarClientes->addApiCall("api/clientes/importar/", "POST");
		$page->addComponent( $importarClientes );


		$page->nextTab("Exportar");
		
		
		$page->render();
