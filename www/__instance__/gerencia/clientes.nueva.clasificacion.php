<?php 


		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
	
		$page->addComponent(new TitleComponent("Nueva clasifiacion"));	
		$form = new DAOFormComponent( new  ClasificacionCliente() );
	
		$form->createComboBoxJoinDistintName("id_tarifa_venta", "id_tarifa" ,"nombre", TarifaDAO::search(new Tarifa(array("tipo_tarifa"=>"venta"))));
		$form->createComboBoxJoin("id_tarifa_compra", "nombre", TarifaDAO::search(new Tarifa(array("tipo_tarifa"=>"compra"))));

		$form->setCaption("id_tarifa_venta","Tarifa de venta");
		$form->setCaption("id_tarifa_compra","Tarifa de compra");		
		
		$form->addApiCall("api/cliente/clasificacion/nueva/");
                
		$form->onApiCallSuccessRedirect("clientes.lista.clasificacion.php");
		
		

		$form->hideField( array( 
				"id_clasificacion_cliente"
			 ));

		$form->makeObligatory(array( 
				"nombre",
				"clave_interna"
			));
                
			
		$page->addComponent( $form );
		
		$page->render();
