<?php

	define("BYPASS_INSTANCE_CHECK", true);

	require_once("../../server/bootstrap.php");
	
	

	
	$p = new JediComponentPage( );


	$p->addComponent( new TitleComponent( "Instancias" ) );
	

	/**
	  *
	  * Lista de instancias
	  *
	  **/
	$p->addComponent( new TitleComponent( "Instancias instaladas", 3 ) );

	$headers = array( 		
						"instance_id" => "instance_id",
						"instance_token" => "instance_token",
						"fecha_creacion" => "Creada",
	 					"descripcion" => "Descripcion"
					);
	
	$t = new TableComponent( $headers , InstanciasController::Buscar());

	$t->addColRender( "fecha_creacion", "FormatTime" );

	$t->addOnClick( "instance_id" , "(function(i){window.location='instancias.ver.php?id='+i; })"  );

	$p->addComponent( new FreeHtmlComponent( '<div class="POS Boton OK"  onclick="window.location=\'instancias.bd.php\'">BD de Instancias</div>') );
	/*$t->addColRender("instance_id", "check_box");	

	function check_box($instance_id){
		return '<input type="checkbox" id="instance_'.$instance_id.'" value="'.$instance_id.'"  onclick="seleccionar_ins('.$instance_id.')">&nbsp;&nbsp;'.$instance_id;
	}
	*/

	$p->addComponent( $t );	

	$p->render( );






