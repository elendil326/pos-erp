<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();


		$form = new DAOFormComponent( new ConceptoIngreso() );

		$form->addApiCall("api/cargosyabonos/ingreso/concepto/nuevo", "POST");
		$form->hideField(array("id_concepto_ingreso"));
		$form->makeObligatory( array( "nombre" ));
		$page->addComponent( $form );

		$page->render();
