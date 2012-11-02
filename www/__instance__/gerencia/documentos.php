<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		

		$q = DocumentoBaseDAO::getAll();

		$tDocs = new  TableComponent(array(
	  			"nombre" => "Nombre",
	  			"activo" => "Activo",
	  			"ultima_modificacion" => "ultima modificacion"
			), $q);

		$tDocs->addOnClick( "id_documento_base", "(function(a){ window.location = 'documentos.ver.php?dbid=' + a; })" );

		$page->addComponent($tDocs);

		$page->render();
