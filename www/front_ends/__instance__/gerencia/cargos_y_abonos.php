<?php 

		define("BYPASS_INSTANCE_CHECK", false);



		require_once("../../../../server/bootstrap.php");

		
		function funcion_cancelado($cancelado){
            return ($cancelado ? "Cancelado" : "Activo" );
        }





		$page = new GerenciaTabPage();
		
		/* **************************************************
		 *			*/ $page->nextTab("Gastos"); /*
		 *  
		 * ************************************************** */
		$page->addComponent( new TitleComponent( "Gastos", 3 ) );
		$lista = CargosYAbonosController::ListaGasto();

		$tabla = new TableComponent( 
			array(
				"id_gasto"			=> "id_gasto",
				"id_empresa"	 		=> "id_empresa",
				"id_usuario"			=> "id_usuario",
				"id_concepto_ingreso"	=> "concepto",
				"fecha_del_ingreso"		=> "fecha",
				"monto"					=> "monto"
			),
			$lista["resultados"]
		);
		
		$tabla->addColRender("cancelado", "funcion_cancelado");
		$page->addComponent( $tabla );
		
		
		$page->addComponent( new TitleComponent( "Nuevo Gastos" , 3) );

		$form = new DAOFormComponent(array(
		    new Gasto(),
			new ConceptoIngreso()		    
		));
 
		$form->hideField(array(	    
	    	"activo",	    
	    	"cancelado",
			"id_usuario",
			"id_gasto",
			"motivo_cancelacion",
			"fecha_de_registro",
			"id_sucursal",
			"id_caja"
		));

		
		$form->createComboBoxJoin("id_empresa", "razon_social", EmpresaDAO::getAll());
		$form->createComboBoxJoin("id_concepto_ingreso", "nombre", ConceptoIngresoDAO::getAll());		
		$form->createComboBoxJoin("id_caja", "descripcion", CajaDAO::getAll());
		$form->addApiCall("api/cargosyabonos/gasto/nuevo", "GET"); /* THIS SHOULD BE POST!!! */
		

		$form->renameField( array("fecha_del_gasto" => "fecha_gasto") );
		$form->setType("fecha_gasto", "date");
		$form->makeObligatory("fecha_gasto");
		$page->addComponent($form);		
		

		/* **************************************************
		 *			*/ $page->nextTab("Ingresos"); /*
		 *  
		 * ************************************************** */
		$page->addComponent( new TitleComponent( "Ingresos", 3 ) );
		$lista = CargosYAbonosController::ListaIngreso();

		$tabla = new TableComponent( 
			array(
				"id_ingreso"			=> "id_ingreso",
				"id_empresa"	 		=> "id_empresa",
				"id_usuario"			=> "id_usuario",
				"id_concepto_ingreso"	=> "concpto",
				"fecha_del_ingreso"		=> "fecha",
				"monto"					=> "monto"
			),
			$lista["resultados"]
		);
		
		$tabla->addColRender("cancelado", "funcion_cancelado");
		$page->addComponent( $tabla );
		
		
		$page->addComponent( new TitleComponent( "Nuevo Ingreso" , 3) );

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
			"id_sucursal",
			"id_caja"
		));

		//$form->setType("fecha_del_ingreso", "date");
		$form->createComboBoxJoin("id_empresa", "razon_social", EmpresaDAO::getAll());
		$form->createComboBoxJoin("id_concepto_ingreso", "nombre", ConceptoIngresoDAO::getAll());		
		$form->createComboBoxJoin("id_caja", "descripcion", CajaDAO::getAll());
		$form->addApiCall("api/cargosyabonos/ingreso/nuevo", "POST");
		$form->renameField( array("fecha_del_ingreso" => "fecha_ingreso") );
		$page->addComponent($form);
		
		
		/* **************************************************
		 *			*/ $page->nextTab("Abonos"); /*
		 *  
		 * ************************************************** */		

		
		
		$page->render();
