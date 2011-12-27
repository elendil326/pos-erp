<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nueva sucursal" ) );

	//forma de nueva sucursal
	$form = new DAOFormComponent( array( new Sucursal(), new Direccion() ) );
	
	$form->hideField( array( 
			"id_sucursal",
			"id_direccion",
			"fecha_apertura",
                        "fecha_baja",
			"id_direccion",
			"ultima_modificacion",
			"id_usuario_ultima_modificacion"
		 ));
        
        $form->renameField( array( "id_gerente" => "id_usuario" ));
        
        $form->createComboBoxJoin( "id_usuario", "nombre", UsuarioDAO::search(new Usuario( array( "id_rol" => 2 ) )) );
	
	$form->addApiCall( "api/sucursal/nueva/");
        $form->onApiCallSuccessRedirect("sucursales.lista.php");
	
	$form->makeObligatory(array( 
			"saldo_a_favor",
                        "id_ciudad",
                        "numero_exterior",
                        "rfc",
                        "activa",
                        "colonia",
                        "calle",
                        "razon_social",
                        "codigo_postal"
		));
	
        //$form->addField("id_impuesto", "Impuestos", "text","","impuestos[]");
        //$form->addField("id_retencion", "Retenciones", "text","","retenciones[]");
        
	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll() );
        
        $form->createComboBoxJoin("activa", "activa", array( array("id" => 1, "caption" => "si" ), array( "id" => 0, "caption" => "no" ) ), 1);
        
        //$form->createListBoxJoin("id_impuesto", "nombre", ImpuestoDAO::getAll());
        
        //$form->createListBoxJoin("id_retencion", "nombre", RetencionDAO::getAll());
	
        $form->renameField( array( 
			//"id_ciudad" => "ciudad",
                        "id_usuario" => "gerente",
                        //"id_impuesto" => "impuestos",
                        //"id_retencion" => "retenciones",
                        "activa"    => "activo"
		));
        
	$page->addComponent( $form );


	//render the page
		$page->render();
