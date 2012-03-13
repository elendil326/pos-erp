<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

		$page->addComponent( new TitleComponent( "Nuevo Ingreso" ) );
		
		//forma de nuevo ingreso
		$form = new DAOFormComponent(array(
		    new Ingreso(),
			new ConceptoIngreso()		    
		));
		
		$form->hideField(array(	    
	    	"activo",	    
	    	"cancelado"
		));

		$form->createComboBoxJoin("id_billete", "nombre", BilleteDAO::search(new Billete(array(
		    "activo" => 1
		))));
		$form->createComboBoxJoin("id_empresa", "razon_social", EmpresaDAO::getAll());

		$form->createComboBoxJoin("id_caja", "descripcion", CajaDAO::getAll());
		
		$form->addApiCall("api/cargosyabonos/ingreso/nuevo");

		$page->addComponent($form);

		$page->render();
