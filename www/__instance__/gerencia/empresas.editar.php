<?php 

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();


	//
	// Parametros necesarios
	// 
	$page->requireParam(  "eid", "GET", "La empresa que desea editar no existe." );
	$esta_empresa = EmpresaDAO::getByPK( $_GET["eid"] );
	$esta_direccion = DireccionDAO::getByPK($esta_empresa->getIdDireccion());
	
	if(is_null($esta_direccion)){
		$esta_direccion = new Direccion();
	}
	
	//
	// Titulo de la pagina
	// 
	$page->addComponent( new TitleComponent( "Editar empresa " . $esta_empresa->getRazonSocial() , 2 ));


	//forma de nueva empresa
	$page->addComponent( new TitleComponent( "Datos de la empresa" , 3 ) );
	$form = new DAOFormComponent( $esta_empresa );

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
		"calle",
		"id_ciudad"
	));
	
	$add_form = new DAOFormComponent( $esta_direccion );
	
	$js = "(function(){
				POS.API.POST(\"api/empresa/editar/\",{
					id_empresa 		: " .  $_GET['eid'] . ",
					rfc 			: Ext.get(\"".$form->getGuiComponentId()."rfc\").getValue(),
					razon_social 	: Ext.get(\"".$form->getGuiComponentId()."razon_social\").getValue(),
					representante_legal : Ext.get(\"".$form->getGuiComponentId()."representante_legal\").getValue(),
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
					window.location = \"empresas.ver.php?eid=\"+ " .  $_GET['eid'] . ";
				}});
			})()";

	$page->addComponent( $form );
	
	$page->addComponent(new TitleComponent("Direccion Fiscal", 3));

	$add_form->addOnClick( "Editar empresa", $js );
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



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");


	$page = new GerenciaComponentPage();


	//
	// Parametros necesarios
	// 
	$page->requireParam(  "eid", "GET", "La empresa que desea editar no existe." );
	$esta_empresa = EmpresaDAO::getByPK( $_GET["eid"] );
	$esta_direccion = DireccionDAO::getByPK($esta_empresa->getIdDireccion());
	
	if(is_null($esta_direccion)){
		$esta_direccion = new Direccion();
	}
	
	//
	// Titulo de la pagina
	// 
	$page->addComponent( new TitleComponent( "Editar empresa " . $esta_empresa->getRazonSocial() , 2 ));



	//
	// Forma de usuario
	// 
	$form = new DAOFormComponent( array( $esta_empresa, $esta_direccion ) );
	

	
	$form->hideField( array( 
	        "id_empresa",
	        "id_direccion",
	        "fecha_alta",
	        "fecha_baja",
	        "activo",
	        "id_direccion",
	        "ultima_modificacion",
			"direccion_web",
	        "id_usuario_ultima_modificacion"
		 ));
		
	$form->sendHidden( array( "id_empresa" ) );
               
	$form->addApiCall( "api/empresa/editar/" , "POST");
	$form->onApiCallSuccessRedirect("empresas.lista.php");
	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ), $esta_direccion->getIdCiudad( ) );

	$page->addComponent( $form );
               
	$page->render();
