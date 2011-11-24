<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		//
		// Requerir parametros
		// 
		$page->requireParam(  "eid", "GET", "Esta empresa no existe." );
		$esta_empresa = EmpresaDAO::getByPK( $_GET["eid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_empresa->getRazonSocial() , 2 ));
		
		//
		// Informacion basica de la empresa
		// 
		$direccion = DireccionDAO::getByPK( $esta_empresa->getIdEmpresa() );
		$ciudad = CiudadDAO::getByPK( $direccion->getIdCiudad() );
		
		$basic_address = $direccion->getCalle() . " " . $direccion->getNumeroExterior();
		

		$page->addComponent( new MessageComponent( $basic_address ));		
		
		
		$page->render();
