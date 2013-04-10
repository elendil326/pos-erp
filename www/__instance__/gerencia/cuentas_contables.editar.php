<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server//bootstrap.php");

	$page = new GerenciaTabPage();

	// Parametros necesarios
	// 
	$controller = new ContabilidadController();
	$page->requireParam(  "cid", "GET", "Esta empresa no existe." );
	
	$cuenta = $controller::DetalleCuenta( $_GET["cid"] );
	$cuentas = $controller::BuscarCuenta();

	//titulos
	$page->addComponent(new TitleComponent("Editando cuenta " . $cuenta["nombre_cuenta"], 2));


	$form = new DAOFormComponent(CuentaContableDAO::getByPK( $_GET["cid"]));

	$form->hideField( array( 
						"id_cuenta_contable",
						"clave",
						"nivel",
						"consecutivo_en_nivel",
						"afectable",
						"activa",
						"clasificacion"
		 ));
	$form->sendHidden("id_cuenta_contable");

	$form->createComboBoxJoin("cargos_aumentan", "cargos_aumentan", 
                        array(
                            array( "id" => 0, "caption" => "No" ),
                            array( "id" => 1, "caption" => "Si"  ) 
                            ) , $cuenta["cargos_aumentan"]
                        );
	$form->createComboBoxJoin("abonos_aumentan", "abonos_aumentan", 
                        array(
                            array( "id" => 0, "caption" => "No" ),
                            array( "id" => 1, "caption" => "Si"  ) 
                            ) , $cuenta["abonos_aumentan"]
                        );
	$form->createComboBoxJoin("es_cuenta_mayor", "es_cuenta_mayor", 
                        array(
                            array( "id" => 0, "caption" => "No" ),
                            array( "id" => 1, "caption" => "Si"  ) 
                            ) , $cuenta["es_cuenta_mayor"]
                        );
	$form->createComboBoxJoin("es_cuenta_orden", "es_cuenta_orden", 
                        array(
                            array( "id" => 0, "caption" => "No" ),
                            array( "id" => 1, "caption" => "Si"  ) 
                            ) , $cuenta["es_cuenta_orden"]
                        );
	$cuentas_p = array();
    //para enviar el id de cuenta contable en el combo de id_cuenta_padre se debe hacer este foreach
    foreach ($cuentas["resultados"] as $cta) {
        array_push($cuentas_p,array("id"=>$cta->getIdCuentaContable(),"caption"=>$cta->getNombreCuenta()));
    }
    //se llena el combo con los ids cambiados para que no se envien los id_cuenta_padre si no el id de la cuenta
	$form->createComboBoxJoin( "id_cuenta_padre", "nombre_cuenta", $cuentas_p,$cuenta["id_cuenta_padre"] );

	$form->createComboBoxJoin("tipo_cuenta", "tipo_cuenta", 
                        array(
                            array( "id" => "Balance", "caption" => "Balance" ),
                            array( "id" => "Estado de Resultados", "caption" => "Estado de Resultados" ) 
                            ), $cuenta["tipo_cuenta"]
                        );

	$form->createComboBoxJoin("naturaleza", "naturaleza", 
                        array(
                            array( "id" => "Acreedora", "caption" => "Acreedora" ),
                            array( "id" => "Deudora", "caption" => "Deudora"  )
                            ),$cuenta["naturaleza"]
                        );

	$form->addApiCall("api/contabilidad/cuenta/editar", "POST");
	$form->onApiCallSuccessRedirect("cuentas_contables.php");

	$page->addComponent( $form );
	$page->render();
