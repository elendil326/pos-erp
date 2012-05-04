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

	$page->addComponent(new TitleComponent("Ultima actividad",3));
	

	//buscar sus ventas
	$ventas = VentaDAO::search(new Venta(array( "id_comprador_venta"  => $este_usuario->getIdUsuario() )));


	$actividad = $ventas;

	$header = array("fecha" => "fecha");

	$tabla = new TableComponent($header, $actividad);
	$page->addComponent($tabla);




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

    $t = TarifaDAO::getByPK( $este_usuario->getIdTarifaVenta() );
    if(is_null($t)){
		$este_usuario->setIdTarifaVenta("-----" );
    }else{
    	$este_usuario->setIdTarifaVenta($t->getNombre() );	
    }
    

	$form = new DAOFormComponent( $este_usuario );
	
	$form->setEditable(false);
               
	$form->hideField( array( 
	    "id_usuario",
	    "id_rol",
	    "id_clasificacion_proveedor",
		"id_direccion",
		"id_direccion_alterna",
	    "fecha_asignacion_rol",
	    "comision_ventas",
	    "fecha_alta",
	    "fecha_baja",
	    "activo",
	    "last_login",
	    "salario",
	    "dias_de_embarque",
	    "consignatario",
	    "tiempo_entrega",
	    "cuenta_bancaria",
		"mensajeria",
		"token_recuperacion_pass",
		"ventas_a_credito",
		"dia_de_pago",
		"dia_de_revision",
		"password",
		"id_sucursal"
	));

	

    
    $form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::getAll() , $este_usuario->getIdMoneda());
    $form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll() , $este_usuario->getIdClasificacionCliente());

    $form->createComboBoxJoin( "id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::getAll(), $este_usuario->getIdClasificacionProveedor() );
	$form->createComboBoxJoin( "id_sucursal", "razon_social", SucursalDAO::getAll(), $este_usuario->getIdSucursal() );

	$form->setCaption("id_tarifa_venta", "Tarifa de Venta");
	$form->createComboBoxJoin("id_tarifa_compra", "nombre", TarifaDAO::search(new Tarifa(array("id_tarifa"=>$este_usuario->getIdTarifaCompra()))));

	$page->addComponent( $form );







	$page->nextTab("Direccion");

	$menu = new MenuComponent();
	$menu->addItem("Editar Direccion","clientes.editar.direccion.php?cid=".$este_usuario->getIdUsuario()."&did=".$este_usuario->getIdDireccion() );
	$page->addComponent($menu);



	$direccion = $este_usuario->getIdDireccion();
	$direccionObj = DireccionDAO::getByPK( $direccion );

	if(!is_null($direccionObj)){

		$ciudad = CiudadDAO::getByPK( $direccionObj->getIdCiudad() );

		if(null === $ciudad){
			$ciudad = new Ciudad();
		}


		$page->addComponent(new FreeHtmlComponent("<div id=\"map_canvas\"></div>"));
		$page->addComponent(new FreeHtmlComponent("<script>startMap(\""
																. $direccionObj->getCalle() 
																. " "
																. $direccionObj->getNumeroExterior() 
																. ", "																
																. $direccionObj->getColonia() 
																. ", "
																. $ciudad->getNombre() 
																. "\");</Script>"));
	}

	

	
	
	if(!is_null($direccionObj)){
		$usr_ultima = UsuarioDAO::getByPK($direccionObj->getIdUsuarioUltimaModificacion());	
		
		if(!is_null($usr_ultima))	
			$direccionObj->setIdUsuarioUltimaModificacion( $usr_ultima->getNombre() );

		$dform = new DAOFormComponent( $direccionObj );
		$dform->setEditable(false);		
		$dform->hideField( array( 
				"id_direccion",
				"id_usuario_ultima_modificacion",
				"ultima_modificacion"
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

    $clientes_component->addJsCallback("( function(record){ Ext.get('add_aval').setStyle({'display':'block'}); id_usuario = record.get('id_usuario'); nombre = record.get('nombre'); id_este_usuario = " . $este_usuario->getIdUsuario() . "; if(id_usuario == id_este_usuario){ Ext.core.Element.fly(\"agregar_aval_btn\").setVisible(false); Ext.get(\"nombre_aval_a_agregar\").update('No se puede ser aval de si mismo');}else{ Ext.core.Element.fly(\"agregar_aval_btn\").setVisible(true); Ext.get(\"nombre_aval_a_agregar\").update('Nuevo Aval: '+record.get('nombre'));}  } )");    

    $page->addComponent( $clientes_component );
	
	$page->addComponent( new FreeHtmlComponent ("<div id= \"nombre_aval_a_agregar\" style =\"display:block; font-size=14; font-weight:bold;\" ></div>") );
	
    $page->addComponent( new FreeHtmlComponent ( "<br><div id = \"add_aval\" style = \"display:none;\" ><form name = \"tipo_aval\" id = \"tipo_aval\"> <input id = \"radio_hipoteca\" type='Radio' name='taval' value='hipoteca' checked> hipoteca <input id = \"radio_prendario\"type='Radio' name='taval' value='prendario'> prendario</form> <br> <div id=\"agregar_aval_btn\" class='POS Boton' onClick = \"nuevoClienteAval(nombre, id_usuario, id_este_usuario)\" >Agregar como aval</div></div>" ) );
	
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
