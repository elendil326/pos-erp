<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server//bootstrap.php");

		$page = new GerenciaComponentPage();


		$q = DocumentoBaseDAO::getByPK( $_GET["dbid"] );
		$q->setUltimaModificacion(FormatTime($q->getUltimaModificacion()));

		$page->addComponent(new TitleComponent( $q->getNombre(),2));
		$tabla = new DAOFormComponent( $q );
		$tabla->setEditable(false);
		$tabla->hideField(array(
				"id_documento_base"
			));
		$tabla->setdHidden("id_documento_base");
		
		$page->addComponent($tabla);


		

		$page->render();
