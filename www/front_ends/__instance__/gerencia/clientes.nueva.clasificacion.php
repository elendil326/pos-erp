<?php 


		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		
		$form = new DAOFormComponent( new  ClasificacionCliente() );
		
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
