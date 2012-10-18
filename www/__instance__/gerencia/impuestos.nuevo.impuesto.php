<?php 
		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		
                $page->addComponent(new TitleComponent("Agregar nuevo impuesto"));
		$form = new DAOFormComponent( new Impuesto() );

		
		$form->addApiCall( "api/impuesto/nuevo");//Nueva llamada a la API para agregar campos
                $form->onApiCallSuccessRedirect("impuestos.php");//Redireciona hacia la pagina de impuestos
		$form->addField("codigo", "codigo", "text");
                
		$form->makeObligatory( array(
			"nombre",
			"codigo",
			"importe",
			"incluido_precio"			
		));
                $form->hideField( array( "id_impuesto", ));
                $form->createComboBoxJoin("activo", "activo", array( array( "id" => 1, "caption" => "Si" ), array( "id" => 0, "caption" => "No" ) ));
                $form->createComboBoxJoin("incluido", "incluido", array( array( "id" => 1, "caption" => "Si" ), array( "id" => 0, "caption" => "No" ) ));
                $form->createComboBoxJoin("tipo", "tipo", array( array( "id" => 0, "caption" => "Porcentaje" ), array( "id" => 1, "caption" => "Importe fijo" ), array( "id" => 2, "caption" => "Ninguno" ), array( "id" => 3, "caption" => "Saldo pendiente" ) ));
                $form->createComboBoxJoin("aplica","aplica",array(array("id" => 0, "caption" => "Compra"),array("id" => 1, "caption" => "Venta"), array("id" => 2, "caption" => "Ambos")));
                $page->addComponent($form);//Agrega los componentes al formulario
                $page->render();