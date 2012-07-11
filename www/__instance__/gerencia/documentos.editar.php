<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server//bootstrap.php");

		$page = new GerenciaComponentPage();


		$q = DocumentoBaseDAO::getByPK( $_GET["dbid"] );
		

		$page->addComponent(new TitleComponent( $q->getNombre(),2));


		$q->setJsonImpresion( str_replace ( "\\n" , "" , $q->getJsonImpresion() ) );
		$q->setJsonImpresion( str_replace ( "\\t" , "" , $q->getJsonImpresion() ) );
		$q->setJsonImpresion( stripslashes($q->getJsonImpresion())  );
		$q->setJsonImpresion( substr($q->getJsonImpresion(), 1 , -1) );


		$tabla = new DAOFormComponent( $q );
		$tabla->setEditable(true);
		$tabla->renameField(array("id_documento_base" => "id_documento"));

		$tabla->hideField(array(
				"id_documento",
				"ultima_modificacion"
			));
		$tabla->sendHidden("id_documento");
		$tabla->setType("json_impresion", "textarea");

		$tabla->addApiCall("api/documento/editar", "POST");
		$tabla->onApiCallSuccessRedirect("documentos.ver.php?dbid=" . $_GET["dbid"]);

		$page->addComponent($tabla);


		

		$page->render();
