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

	$page->addComponent( new TitleComponent( "Nuevo " . $DocumentoBase->getNombre() , 1 ) );

	//Buscar sus parametros extra
	$ExtraParamsStructs = ExtraParamsEstructuraDAO::search( 
							new ExtraParamsEstructura( 
								array( 
									"tabla" => "documento_base-" . $DocumentoBase->getIdDocumentoBase( )
									)
								)
							);

	$f = new FormComponent( );
	$f->addApiCall( "api/documento/nuevo" );
	for( $i = 0 ; $i < sizeof( $ExtraParamsStructs ); $i++ ) {
		switch( $ExtraParamsStructs[$i]->tipo ){

		}
		Logger::debug( $ExtraParamsStructs[$i]->campo );
			
		$f->addField( $ExtraParamsStructs[$i]->campo , $ExtraParamsStructs[$i]->caption,  $ExtraParamsStructs[$i]->tipo  );
	}
	$f->beforeSend("attachExtraParams");
	$f->setStyle("big");
	$page->addComponent( $f );
	$page->partialRender();
	?>
		<script>
			function attachExtraParams( a ) {
				
				//var GuiComponentId = <?php echo $f->getGuiComponentId(); ?>getParams();
				a.id_documento_base  = <?php echo  $DocumentoBase->getIdDocumentoBase( ); ?>;
				a.extra_params = Ext.JSON.encode({
					<?php
						for( $i = 0 ; $i < sizeof( $ExtraParamsStructs ); $i++ ) {
							echo $ExtraParamsStructs[$i]->campo .  " : Ext.get(\"".  $f->getGuiComponentId() . $ExtraParamsStructs[$i]->campo  ."\" ).getValue() , ";
						}
					?>
				});
				return a;
			}	
		</script>
	<?php


	$page->Render();



