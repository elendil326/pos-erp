<?php 



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");




	if( 
		isset($_GET["preview"])
		&& isset($_GET["dbid"])
	)
	{

		ImpresionesController::Documento($_GET["dbid"], true);
		exit;
	}


	$page = new GerenciaComponentPage();
		


	$q = DocumentoBaseDAO::getByPK( $_GET["dbid"] );

	$q->setUltimaModificacion(FormatTime($q->getUltimaModificacion()));

	$page->addComponent(new TitleComponent( $q->getNombre(),2));



	$page->addComponent( "<div class='POS Boton' onClick='window.location=\"documentos.editar.php?dbid=". $_GET["dbid"] ."\"'>Editar</div> " );
	$page->addComponent( "<div class='POS Boton' onClick='window.location=\"documentos.ver.php?preview=1&dbid=". $_GET["dbid"] ."\"'>Vista previa</div> " );

	$tabla = new DAOFormComponent( $q );
	$tabla->setEditable(false);
	$tabla->hideField(array(
			"id_documento_base",
			"json_impresion"
		));
	$page->addComponent($tabla);
















	$page->addComponent(new TitleComponent("Editar", 3));


	$q = DocumentoBaseDAO::getByPK( $_GET["dbid"] );
	

	


	$q->setJsonImpresion( str_replace ( "\\n" , "" , $q->getJsonImpresion() ) );
	$q->setJsonImpresion( str_replace ( "\\t" , "" , $q->getJsonImpresion() ) );
	$q->setJsonImpresion( stripslashes($q->getJsonImpresion())  );
	

	if( "\""  == substr ( $q->getJsonImpresion() , 0 , 1 ) ){
		$q->setJsonImpresion(  substr($q->getJsonImpresion(), 1 , -1) );	
	}

	$tabla = new DAOFormComponent( $q );
	$tabla->setEditable(true);
	$tabla->renameField(array("id_documento_base" => "id_documento"));

	$tabla->hideField(array(
			"id_documento",
			"ultima_modificacion",

		));
	$tabla->sendHidden("id_documento");
	$tabla->setType("json_impresion", "textarea");

	$tabla->addApiCall("api/documento/editar", "POST");
	$tabla->onApiCallSuccessRedirect("documentos.ver.php?dbid=" . $_GET["dbid"]);

	$page->addComponent($tabla);




















	$page->addComponent(new TitleComponent("Vista Previa", 3));
	
/*		$page->addComponent('<embed src="/file.pdf#toolbar=0&navpanes=0&scrollbar=0" width="500" height="375"></embed>');
	$page->addComponent('<embed src="documentos.ver.php?dbid='. $_GET["dbid"] .'&preview=1#toolbar=0&navpanes=0&scrollbar=0" width="500" height="375"></embed>');
*/
	$page->render();
