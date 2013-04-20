<?php 

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();


	
	//
	// Requerir parametros
	//
	$controller = new ContabilidadController();
	$page->requireParam(  "cid", "GET", "Esta cuenta no existe." );
	
	$cuenta = $controller::DetalleCuenta( $_GET["cid"] );
	$cuentas = $controller::BuscarCuenta($cuenta["id_catalogo_cuentas"]);
	// Titulo de la pagina
	// 
	$page->addComponent( new TitleComponent( "Detalles de la cuenta: " . $cuenta["nombre_cuenta"] , 2 ));


	//
	// Menu de opciones
	// 
	if($cuenta["activa"]=== true || $cuenta["activa"]=== 1 || $cuenta["activa"]=== "1"){
		
		$menu = new MenuComponent();

		$menu->addItem("<< Regresar", "contabilidad.cuentas.php?idcc=" . $cuenta["id_catalogo_cuentas"]);
		$menu->addItem("Editar", "contabilidad.cuentas.editar.php?cid=" . $cuenta["id_cuenta_contable"]);

		$btn_eliminar = new MenuItem("Eliminar esta cuenta", null);
		$btn_eliminar->addApiCall("api/contabilidad/cuenta/eliminar", "POST");
		$btn_eliminar->onApiCallSuccessRedirect("contabilidad.cuentas.php");
		$btn_eliminar->addName("eliminar");

		$funcion_eliminar = " function eliminar_cuenta(btn){".
		"if(btn == 'yes')".
		"{".
		"var p = {};".
		"p.id_cuenta_contable = ".$_GET["cid"].";".
		"sendToApi_eliminar(p);".
		"}".
		"}".
		"      ".
		"function confirmacion(){".
		" Ext.MessageBox.confirm('Desactivar', 'Desea eliminar esta cuenta?', eliminar_cuenta );".
		"}";
////
		$btn_eliminar->addOnClick("confirmacion", $funcion_eliminar);

		$menu->addMenuItem($btn_eliminar);

		$page->addComponent( $menu);
	}
		
	//
	// Forma de producto
	// 
	$form = new DAOFormComponent(CuentaContableDAO::getByPK( $_GET["cid"]));
	

	$form->setEditable(false);

	$form->hideField( array( 
						"id_cuenta_contable",
						"clave",
						"nivel",
						"consecutivo_en_nivel",
						"afectable",
						"activa",
						"clasificacion"
		 ));

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

	$form->createComboBoxJoin("clasificacion", "clasificacion", 
                        array(
                            array( "id" => "Activo Circulante", "caption" => "Activo Circulante" ),
                            array( "id" => "Activo Fijo", "caption" => "Activo Fijo"  ),
                            array( "id" => "Activo Diferido", "caption" => "Activo Diferido"  ),
                            array( "id" => "Pasivo Circulante", "caption" => "Pasivo Circulante"  ),
                            array( "id" => "Pasivo Largo Plazo", "caption" => "Pasivo Largo Plazo"  ),
                            array( "id" => "Capital Contable", "caption" => "Capital Contable"  ),
                            array( "id" => "Ingresos", "caption" => "Ingresos"  ),
                            array( "id" => "Egresos", "caption" => "Egresos"  ),
                            ), $cuenta["clasificacion"]
                        );

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

	$page->addComponent( $form );
	$page->render();
