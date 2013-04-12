<?php 

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//titulos
	$page->addComponent( new TitleComponent( "Nueva empresa" ) );


	//forma de nueva empresa
	$page->addComponent( new TitleComponent( "Datos de la empresa" , 3 ) );
	$form = new DAOFormComponent( new Empresa() );

	$form->hideField( array( 
				"id_empresa",
				"id_direccion",
				"fecha_alta",
				"fecha_baja",
				"activo",
				"id_direccion",
				"direccion_web"
		));


	$form->makeObligatory(array(
		"rfc",
		"razon_social",
		"curp",
		"ciudad",
		"numero_exterior",
		"colonia",
		"codigo_postal",
		"calle"
	));
	
		$add_form = new DAOFormComponent( new Direccion() );
	
	$js = "(function(){
				POS.API.POST(\"api/empresa/nuevo/\",{
					rfc 			: Ext.get(\"".$form->getGuiComponentId()."rfc\").getValue(),
					razon_social 	: Ext.get(\"".$form->getGuiComponentId()."razon_social\").getValue(),
					representante_legal : Ext.get(\"".$form->getGuiComponentId()."representante_legal\").getValue(),
					cedula : Ext.get(\"".$form->getGuiComponentId()."cedula\").getValue(),					
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
					}),
					contabilidad : Ext.JSON.encode({
						\"id_moneda\": 1,
						\"ejercicio\" : \"2013\",
						\"periodo_actual\" : 1,
						\"duracion_periodo\" : 1
					})
				},{ callback : function(a,b){
					window.onbeforeunload = function(){ return;	};
					window.location = \"empresas.ver.php?eid=\"+ a.id_empresa;
				}});
			})()";

	$page->addComponent( $form );
	
	$page->addComponent(new TitleComponent("Direccion Fiscal", 3));

	$add_form->addOnClick( "Crear empresa", $js );
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





