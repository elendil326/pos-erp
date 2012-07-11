<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");




		if( isset($_GET["preview"])
			&& isset($_GET["dbid"])
			)
		{

			ImpresionesController::Documento($_GET["dbid"], true);
			die;
		}


		$page = new GerenciaComponentPage();
			


		$q = DocumentoBaseDAO::getByPK( $_GET["dbid"] );

		$q->setUltimaModificacion(FormatTime($q->getUltimaModificacion()));

		$page->addComponent(new TitleComponent( $q->getNombre(),2));



		$page->addComponent( "<div class='POS Boton' onClick='window.location=\"documentos.editar.php?dbid=". $_GET["dbid"] ."\"'>Editar</div> " );

		$tabla = new DAOFormComponent( $q );
		$tabla->setEditable(false);
		$tabla->hideField(array(
				"id_documento_base"
			));
		$page->addComponent($tabla);





		$page->addComponent(new TitleComponent("Vista Previa", 3));
		$page->addComponent('<embed src="documentos.ver.php?dbid='. $_GET["dbid"] .'&preview=ok#toolbar=0&navpanes=0&scrollbar=0" width="500″ height="375″></embed>');

		

		$page->render();
