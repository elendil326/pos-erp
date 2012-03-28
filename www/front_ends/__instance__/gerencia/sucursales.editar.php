<?php


	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();


	//
	// Parametros necesarios
	// 
	$page->requireParam("sid", "GET", "Esta sucursal no existe.");
	$esta_sucursal  = SucursalDAO::getByPK($_GET["sid"]);
	$esta_direccion = DireccionDAO::getByPK($esta_sucursal->getIdDireccion());


	//
	// Titulo de la pagina
	// 
	$page->addComponent(new TitleComponent("Editar sucursal " . $esta_sucursal->getRazonSocial(), 2));


	//forma de nueva empresa
	$form = new DAOFormComponent($esta_sucursal);

	$form->hideField( array( "id_sucursal", "rfc", "id_direccion", "activa", "fecha_baja", "id_gerente" ));
	

	$form->makeObligatory(array("razon_social" ));
	
	$add_form = new DAOFormComponent( $esta_direccion );
	$form->setType("fecha_apertura","date");

	$js = "(function(){
				POS.API.GET(\"api/sucursal/editar/\",{
					id_sucursal		: " .  $_GET['sid'] . ",
					razon_social 	: Ext.get(\"".$form->getGuiComponentId()."razon_social\").getValue(),
					saldo_a_favor 	: Ext.get(\"".$form->getGuiComponentId()."saldo_a_favor\").getValue(),
					descripcion		: Ext.get(\"".$form->getGuiComponentId()."descripcion\").getValue(),
					fecha_apertura	: Ext.get(\"".$form->getGuiComponentId()."fecha_apertura\").getValue(),
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
					window.location = \"sucursales.ver.php?sid=\"+ " .  $_GET['sid'] . ";
				}});
			})()";
	
	$page->addComponent( $form );

	$page->addComponent(new TitleComponent("Direccion", 3));

	$add_form->addOnClick( "Editar sucursal", $js );
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




die;


	//
	// Forma de usuario
	// 
	$form = new DAOFormComponent(array(
	    $esta_sucursal,
	    $esta_direccion
	));
	$form->hideField(array(
	    "id_sucursal",
	    "id_direccion",
	    "fecha_apertura",
	    "fecha_baja",
	    "id_direccion",
	    "ultima_modificacion",
	    "id_usuario_ultima_modificacion",
	    "referencia",
	    "activa"
	));
	$form->sendHidden("id_sucursal");

	$form->addApiCall("api/sucursal/editar/", "GET");
	$form->onApiCallSuccessRedirect("sucursales.lista.php");


	$form->createComboBoxJoin("id_ciudad", "nombre", CiudadDAO::getAll(), $esta_direccion->getIdCiudad());

	$form->createComboBoxJoinDistintName("id_gerente", "id_usuario", "nombre", UsuarioDAO::search(new Usuario(array(
	    "id_rol" => 2
	))), $esta_sucursal->getIdGerente());


	$form->renameField(array(
	    "id_ciudad" => "municipio"
	));

	$page->addComponent($form);

	$page->render();
