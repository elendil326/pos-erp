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
	    	"cancelado",
			"id_usuario",
			"id_ingreso",
			"motivo_cancelacion",
			"fecha_de_registro",
			"id_sucursal"
		));

		$form->createComboBoxJoin("id_billete", "nombre", BilleteDAO::search(new Billete(array(
		    "activo" => 1
		))));
		
		$form->setType("fecha_del_ingreso", "date");
		
		$form->createComboBoxJoin("id_empresa", "razon_social", EmpresaDAO::getAll());
		
		$form->createComboBoxJoin("id_concepto_ingreso", "nombre", ConceptoIngresoDAO::getAll());		
		
		$form->createComboBoxJoin("id_caja", "descripcion", CajaDAO::getAll());
		
		$form->addApiCall("api/cargosyabonos/ingreso/nuevo");
		
		$form->renameField( array("fecha_del_ingreso" => "fecha_ingreso") );

		$page->addComponent($form);

		$page->render();
