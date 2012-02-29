<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();
	/*
	//titulos
	$page->addComponent(new TitleComponent("Nueva sucursal"));

	//forma de nueva sucursal
	$form = new DAOFormComponent(array(
	    new Sucursal(),
	    new Direccion()
	));

	$form->hideField(array(
	    "id_sucursal",
	    "id_direccion",
	    "fecha_apertura",
	    "fecha_baja",
	    "id_direccion",
	    "ultima_modificacion",
	    "id_usuario_ultima_modificacion"
	));

	$form->renameField(array(
	    "id_gerente" => "id_usuario"
	));

	$form->createComboBoxJoin("id_usuario", "nombre", UsuarioDAO::search(new Usuario(array(
	    "id_rol" => 2
	))));

	$form->addApiCall("api/sucursal/nueva/");
	$form->onApiCallSuccessRedirect("sucursales.lista.php");

	$form->makeObligatory(array(
	    "id_ciudad",
	    "numero_exterior",
	    "rfc",
	    "activa",
	    "colonia",
	    "calle",
	    "razon_social",
	    "codigo_postal"
	));

	//$form->addField("id_impuesto", "Impuestos", "text","","impuestos[]");
	//$form->addField("id_retencion", "Retenciones", "text","","retenciones[]");

	$form->createComboBoxJoin("id_ciudad", "nombre", CiudadDAO::getAll());

	$form->createComboBoxJoin("activa", "activa", array(
	    array(
	        "id" => 1,
	        "caption" => "si"
	    ),
	    array(
	        "id" => 0,
	        "caption" => "no"
	    )
	), 1);

	//$form->createListBoxJoin("id_impuesto", "nombre", ImpuestoDAO::getAll());
	//$form->createListBoxJoin("id_retencion", "nombre", RetencionDAO::getAll());

	$form->renameField(array(
	    "id_usuario" => "gerente",
	    "activa" => "activo"
	));

	$page->addComponent($form);
	*/
#########################################


	//titulos
	$page->addComponent( new TitleComponent( "Nueva Sucursal" ) );


	//forma de nueva empresa
	$page->addComponent( new TitleComponent( "Datos de la sucursal" , 3 ) );
	$form = new DAOFormComponent( new Sucursal() );

	$form->hideField( array( "id_sucursal"	));


	$form->makeObligatory(array("razon_social" ));

	$js = "(function(){
				POS.API.POST(\"api/sucursal/nueva/\",{
					rfc 			: Ext.get(\"rfc\").getValue(),
					razon_social 	: Ext.get(\"razon_social\").getValue(),
					id_moneda 		: 1,
					direccion : Ext.JSON.encode({
						 	calle			: Ext.get(\"calle\").getValue(),
							numero_exterior	: Ext.get(\"numero_exterior\").getValue(),
						    numero_interior	: Ext.get(\"numero_interior\").getValue(),
						    colonia			: Ext.get(\"colonia\").getValue(),
						    codigo_postal	: Ext.get(\"codigo_postal\").getValue(),
						    telefono1		: Ext.get(\"telefono\").getValue(),
						    telefono2		: Ext.get(\"telefono2\").getValue(),
						    id_ciudad		: Ext.get(\"ciudad\").getValue(),
						    referencia		: Ext.get(\"referencia\").getValue()
					})
				},{ callback : function(a,b){
					window.onbeforeunload = function(){ return;	};
					window.location = \"sucursal.ver.php?sid=\"+ a.id_sucursal;
				}});
			})()";

	$page->addComponent( $form );

	$page->addComponent(new TitleComponent("Direccion", 3));
	$add_form = new DAOFormComponent( new Direccion() );
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


