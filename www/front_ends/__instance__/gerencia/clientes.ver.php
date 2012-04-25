<?php 

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaTabPage(  );

	$este_usuario = UsuarioDAO::getByPK( $_GET["cid"] );
	
	if(is_null($este_usuario)){
		die;
	}
	//
	// Parametros necesarios
	// 
	$page->requireParam(  "cid", "GET", "Este cliente no existe." );


	//
	// Titulo de la pagina
	// 
	$page->addComponent( new TitleComponent(  utf8_decode($este_usuario->getNombre()) , 2 ));

	
	$page->nextTab("Panorama");


	

	$page->nextTab("General");




	
	//
	// Menu de opciones
	// 
       if($este_usuario->getActivo()){

           $menu = new MenuComponent();

           $menu->addItem("Editar este cliente", "clientes.editar.php?cid=".$_GET["cid"]);

           $page->addComponent( $menu);
       }


	//
	// Forma de producto
	// 

	$form = new DAOFormComponent( $este_usuario );
	
	$form->setEditable(false);
               
	$form->hideField( array( 
			"id_usuario",
			"salario",
			"id_rol",
			"comision_ventas",
			"dia_de_revision",
			"id_clasificacion_proveedor",
			"id_direccion",
			"id_direccion_alterna",
			"fecha_asignacion_rol",
			"activo",
			"password"
		 ));
               
               
               
               
    $form->createComboBoxJoin( "id_rol", "nombre", RolDAO::getAll() , $este_usuario->getIdRol());
    $form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::getAll() , $este_usuario->getIdMoneda());
    $form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll() , $este_usuario->getIdClasificacionCliente());

    $form->createComboBoxJoin( "id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::getAll(), $este_usuario->getIdClasificacionProveedor() );
	$form->createComboBoxJoin( "id_sucursal", "razon_social", SucursalDAO::getAll(), $este_usuario->getIdSucursal() );

	$form->createComboBoxJoinDistintName("id_tarifa_venta", "id_tarifa" ,"nombre",TarifaDAO::search(new Tarifa(array("id_tarifa"=>$este_usuario->getIdTarifaVenta()))));
	$form->createComboBoxJoin("id_tarifa_compra", "nombre", TarifaDAO::search(new Tarifa(array("id_tarifa"=>$este_usuario->getIdTarifaCompra()))));

	$page->addComponent( $form );

	$page->nextTab("Direccion");

	$menu = new MenuComponent();
	$menu->addItem("Editar Direccion","clientes.editar.direccion.php?cid=".$este_usuario->getIdUsuario()."&did=".$este_usuario->getIdDireccion() );
	$page->addComponent($menu);

	$direccion = $este_usuario->getIdDireccion();
	$direccionObj = DireccionDAO::getByPK( $direccion );
	
	if(!is_null($direccionObj)){
		$usr_ultima = UsuarioDAO::getByPK($direccionObj->getIdUsuarioUltimaModificacion());	
		
		if(!is_null($usr_ultima))	
			$direccionObj->setIdUsuarioUltimaModificacion( $usr_ultima->getNombre() );

		$dform = new DAOFormComponent( $direccionObj );
		$dform->setEditable(false);		
		$dform->hideField( array( 
				"id_direccion"
			 ));		
		$dform->createComboBoxJoin("id_ciudad","nombre",CiudadDAO::getAll(), $direccionObj->getIdCiudad());
		$page->addComponent( $dform );
	}

	
	
	
	
	/* ********************************************************
	 *	Avales
	 *
	 * ******************************************************** */
	$page->nextTab("Avales");

    $page->addComponent( new TitleComponent( "Nuevo Aval", 3 ) );

    $clientes_component = new ClienteSelectorComponent(); 

    $clientes_component->addJsCallback("( function(record){ Ext.get('add_aval').setStyle({'display':'block'}); id_usuario = record.get('id_usuario'); nombre = record.get('nombre'); id_este_usuario = " . $este_usuario->getIdUsuario() . " } )");    

    $page->addComponent( $clientes_component );

    $page->addComponent( new FreeHtmlComponent ( "<br><div id = \"add_aval\" style = \"display:none;\" ><form name = \"tipo_aval\" id = \"tipo_aval\"> <input id = \"radio_hipoteca\" type='Radio' name='taval' value='hipoteca' checked> hipoteca <input id = \"radio_prendario\"type='Radio' name='taval' value='prendario'> prendario</form> <br> <div class='POS Boton' onClick = \"nuevoClienteAval(nombre, id_usuario, id_este_usuario)\" >Agregar como aval</div></div>" ) );

    $page->addComponent( new TitleComponent( "Lista de Avales", 3 ) );

    $avales = ClienteAvalDAO::search( new ClienteAval( array( "id_cliente" => $este_usuario->getIdUsuario() ) ) );

    $array_avales = array();

    foreach( $avales as $aval ){
        array_push( $array_avales, $aval->asArray() );
    }
	
	$tabla_avales = new TableComponent( 
		array(
			"id_aval"           => "Nombre",
			"tipo_aval" 		=> "Tipo de Aval"
		),
        $array_avales
	);

    function funcion_nombre_aval($id_usuario){
        return (UsuarioDAO::getByPK($id_usuario)->getNombre());
    }                

    $tabla_avales->addColRender("id_aval", "funcion_nombre_aval");
				
	$page->addComponent( $tabla_avales );        
	
	
	
	
	
	/* ********************************************************
	 *	Seguimientos
	 *
	 * ******************************************************** */
	$page->nextTab("Seguimiento");	
	
	
	
	
	//$page->addComponent(new TitleComponent("Nuevo seguimiento", 3));
	
	
	$segs = ClienteSeguimientoDAO::search( new ClienteSeguimiento(array(
		"id_cliente" => $_GET["cid"]

	)));

	$header = array(
			"texto" => "Descripcion",
			"fecha" => "Fecha",
			"id_usuario" => "Agente"
			
		);

	
	
	function nagente($id){ $a = UsuarioDAO::getByPK($id); return $a->getNombre(); }
	function funcion_transcurrido($a, $obj){
		return FormatTime(($a));
	}
	$lseguimientos = new TableComponent($header, $segs);
	$lseguimientos->addColRender("id_usuario", "nagente");
	$lseguimientos->addColRender("fecha", "funcion_transcurrido");	
	$page->addComponent($lseguimientos);
	
	
	$nseguimiento = new DAOFormComponent( new ClienteSeguimiento( array( "id_cliente" => $este_usuario->getIdUsuario() ) ) );
	$nseguimiento->addApiCall("api/cliente/seguimiento/nuevo");
	$nseguimiento->settype("texto", "textarea");
	$nseguimiento->hideField( array(
		"id_usuario",
		"id_cliente",
		"id_cliente_seguimiento",
		"fecha"
	) );
	$nseguimiento->sendHidden("id_cliente");
	$page->addComponent( $nseguimiento );
	



	$page->render();
