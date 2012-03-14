<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//titulos
	$page->addComponent( new TitleComponent( "Nueva Sucursal" ) );


	//forma de nueva empresa
	$page->addComponent( new TitleComponent( "Datos de la sucursal" , 3 ) );
	$form = new DAOFormComponent( new Sucursal() );

	$form->hideField( array( "id_sucursal"	));


	$form->makeObligatory(array("razon_social" ));
		$add_form = new DAOFormComponent( new Direccion() );

	$js = "(function(){
				POS.API.POST(\"api/sucursal/nueva/\",{
					razon_social 	: Ext.get(\"".$form->getGuiComponentId()."razon_social\").getValue(),
					id_moneda 		: 1,
					direccion : Ext.JSON.encode({
						 	calle			: Ext.get(\"".$add_form->getGuiComponentId()."calle\").getValue(),
							numero_exterior	: Ext.get(\"".$add_form->getGuiComponentId()."numero_exterior\").getValue(),
						    numero_interior	: Ext.get(\"".$add_form->getGuiComponentId()."numero_interior\").getValue(),
						    colonia			: Ext.get(\"".$add_form->getGuiComponentId()."colonia\").getValue(),
						    codigo_postal	: Ext.get(\"".$add_form->getGuiComponentId()."codigo_postal\").getValue(),
						    telefono1		: Ext.get(\"".$add_form->getGuiComponentId()."telefono\").getValue(),
						    telefono2		: Ext.get(\"".$add_form->getGuiComponentId()."telefono2\").getValue(),
						    id_ciudad		: Ext.get(\"".$add_form->getGuiComponentId()."ciudad\").getValue(),
						    referencia		: Ext.get(\"".$add_form->getGuiComponentId()."referencia\").getValue()
					})
				},{ callback : function(a,b){
					window.onbeforeunload = function(){ return;	};
					window.location = \"sucursales.ver.php?sid=\"+ a.id_sucursal;
				}});
			})()";

	$page->addComponent( $form );

	$page->addComponent(new TitleComponent("Direccion", 3));

	$add_form->addOnClick( "Crear sucursal", $js );
	$add_form->hideField( array( 
				"id_direccion",
				"ultima_modificacion",
				"id_usuario_ultima_modificacion"	));
			
	$add_form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll() );
	$add_form->renameField( array( 
		"id_ciudad" => "ciudad",
	));
	$page->addComponent( $add_form );



	//render the page
	$page->render();


