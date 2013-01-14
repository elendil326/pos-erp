<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();
	

	// Validar parametro de base
	$DocumentoBase = DocumentoBaseDAO::getByPK( $_GET["base"] );

	if(is_null($DocumentoBase)){
		$page->render();
		exit;

	}else if( $DocumentoBase->GetActivo() == false  ){
		$page->render();
		exit;

	}

	$page->addComponent(new TitleComponent( "Nuevo " . $DocumentoBase->getNombre() , 1));

	//Buscar sus parametros extra
	$ExtraParamsStructs = ExtraParamsEstructuraDAO::search( new ExtraParamsEstructura( array( "tabla" => "documento_base", "campo" => $DocumentoBase->getIdDocumentoBase( ) ) ) );

	$f = new FormComponent(  );

	for( $i = 0 ; $i < sizeof( $ExtraParamsStructs ); $i++ ){
		switch( $ExtraParamsStructs[$i]->tipo ){
		}
		Logger::log( "tipo->" .  $ExtraParamsStructs[$i]->tipo );
		$f->addField( $ExtraParamsStructs[$i]->tabla . $ExtraParamsStructs[$i]->campo . $i, $ExtraParamsStructs[$i]->caption,  $ExtraParamsStructs[$i]->tipo  );
	}
	$page->addComponent( $f );
	$page->Render();

die;

	$f->addApiCall("api/documento/nuevo", "POST");
	$f->setType("json_impresion", "textarea");
	$page->addComponent($f);

	$page->render();


