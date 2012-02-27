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
	        "id_usuario_ultima_modificacion"
		 ));
		
	$form->sendHidden( array( "id_empresa" ) );
               
               $form->addApiCall( "api/empresa/editar/" , "POST");
               $form->onApiCallSuccessRedirect("empresas.lista.php");

               $form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ), $esta_direccion->getIdCiudad( ) );

	$page->addComponent( $form );
               
	$page->render();
