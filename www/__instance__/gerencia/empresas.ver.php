<?php 

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	// Requerir parametros
	$page->requireParam(  "eid", "GET", "Esta empresa no existe." );

	$empresa = EmpresasController::Detalles($_GET["eid"]);

	$esta_empresa = $empresa["detalles"];

	$esta_direccion = $esta_empresa->direccion;

	// Titulo de la pagina
	$page->addComponent( new TitleComponent( "Detalles de " . $esta_empresa->getRazonSocial() , 2 ));

	// Menu de opciones
	if($esta_empresa->getActivo()){

		$menu = new MenuComponent();

		$menu->addItem("Editar esta empresa", "empresas.editar.php?eid=".$_GET["eid"]);

		$btn_eliminar = new MenuItem("Desactivar esta empresa", null);
		$btn_eliminar->addApiCall("api/empresa/eliminar", "POST");
		$btn_eliminar->onApiCallSuccessRedirect("empresas.lista.php");
		$btn_eliminar->addName("eliminar");

		$funcion_eliminar = " function eliminar_empresa(btn){".
		"if(btn == 'yes')".
		"{".
		"var p = {};".
		"p.id_empresa = ".$_GET["eid"].";".
		"sendToApi_eliminar(p);".
		"}".
		"}".
		"      ".
		"function confirmar(){".
		" Ext.MessageBox.confirm('Desactivar', 'Desea eliminar esta empresa?', eliminar_empresa );".
		"}";

		$btn_eliminar->addOnClick("confirmar", $funcion_eliminar);

		$menu->addMenuItem($btn_eliminar);

		$page->addComponent( $menu);
	}

	// Forma de producto
	$form = new DAOFormComponent( $esta_empresa );

	$form->setEditable(false);

	$form->hideField( array( 
		"id_empresa",
		"direccion_web",
		"id_direccion"
	));

	$page->addComponent( $form );

	if(!is_null($esta_direccion)){

		$page->addComponent( new TitleComponent("Direccion",3) );

		$form = new DAOFormComponent($esta_direccion);

		$form->hideField(
			array(
			"id_direccion",
			"id_usuario_ultima_modificacion"
			)
		);

		$form->setEditable(false);

		$form->createComboBoxJoin("id_ciudad", "nombre", CiudadDAO::getAll(), $esta_direccion->getIdCiudad());

		$page->addComponent($form);

	}

	$page->addComponent( new TitleComponent("Sucursales",3));

	$suce = SucursalEmpresaDAO::search( new SucursalEmpresa( array( "id_empresa" => $_GET["eid"] ) ) );

	$empresas_vinculadas = new TableComponent( array( "id_sucursal" => "Sucursales Vinculadas" ), $suce );

	function funcion_sucursal_descripcion($valor){
			return SucursalDAO::getByPK($valor)->getRazonSocial();
	}

	$empresas_vinculadas->addColRender( "id_sucursal", "funcion_sucursal_descripcion" );
	
	$page->addComponent( $empresas_vinculadas );


	$page->addComponent( "<p>Agregar una sucursal </p>" );

	$js = "Ext.MessageBox.show({
				title: 'Error',
				msg: '&iquest; Seguro que desea vincular esta sucursal a la empresa '+_suc.get('razon_social')+' ?',
				buttons: Ext.MessageBox.YESNO,
				icon: 'error',
				callback : function(a,b){
					if(a=='yes'){
						POS.API.GET('api/sucursal/editar', 
						{ id_sucursal : _suc.get('id_sucursal'), empresas : Ext.JSON.encode([ ".$_GET["eid"]." ]) }, 
						{callback: function(a){
								window.location = 'empresas.ver.php?eid=".$_GET["eid"]."';
						}}
						)
					}
				}
	       });";

	$ssel = new SucursalSelectorComponent();

	$ssel->addJsCallback("(function(_suc){".$js."})");
	$page->addComponent( $ssel );

/*	$page->addComponent( new TitleComponent( "Ventas" ), 3 );

	$page->addComponent( new TitleComponent( "Productos" ), 3 );

	$page->addComponent( new TitleComponent( "Servicios" ), 3 );

	$page->addComponent( new TitleComponent( "Paquetes" ), 3 );
*/      


	$r = new ReporteComponent();

	$data = array(
		array(
			"fecha" => "2012-01-01",
			"value" => "15"
		),
		array(
			"fecha" => "2012-01-02",
			"value" => "20"
		),
		array(
			"fecha" => "2012-01-03",
			"value" => "25"
		)
	);

	$data = EmpresasController::flujoEfectivo( (int)$_GET["eid"] );

	$r->agregarMuestra	( "uno", $data, true );
	$r->fechaDeInicio( strtotime( "2012-03-01"));
	$page->addComponent($r);
	$page->render();
