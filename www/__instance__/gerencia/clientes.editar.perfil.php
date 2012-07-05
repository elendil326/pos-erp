<?php 

		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

		//
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Este cliente no existe." );

		$este_cliente = UsuarioDAO::getByPK( $_GET["cid"] );
		$esta_direccion = DireccionDAO::getByPK($este_cliente->getIdDireccion());
                
		//titulos
		$page->addComponent( new TitleComponent( "Editar a ".$este_cliente->getNombre() ) );

		//forma de nuevo cliente
        if(is_null($esta_direccion)){
	    	$esta_direccion = new Direccion();
		}
        
		$form = new DAOFormComponent( array( $este_cliente, $esta_direccion ) );
	
		$form->hideField( array( 
			"id_usuario",
			"id_direccion",
			"id_direccion_alterna",
			"id_sucursal",
			"id_rol",	
			"id_clasificacion_proveedor",	
			"fecha_asignacion_rol",
			"comision_ventas",
			"fecha_alta"	,
			"fecha_baja",
			"activo",
			"last_login",
			"salario",
			"saldo_del_ejercicio",
			"dia_de_pago",
			"dias_de_embarque",
			"id_direccion",
			"ultima_modificacion",
			"id_usuario_ultima_modificacion",
            "consignatario",
            "facturar_a_terceros",
            "dia_de_revision",
            "tiempo_entrega",
            "ventas_a_credito",
            "intereses_moratorios",
            "cuenta_bancaria",
            "dias_de_credito",
            "limite_credito"
		 ));
		


	$form->renameField(array("id_usuario"=>"id_cliente"));
	$form->sendHidden("id_cliente");

	$form->setValueField("password", "");

	$form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::search( new Moneda(array("activa" => 1)) ), $este_cliente->getIdMoneda() );
	$form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll( ), $este_cliente->getIdClasificacionCliente() );

	$form->addApiCall( "api/cliente/editar_perfil/" );
	$form->onApiCallSuccessRedirect("clientes.lista.php");

	
	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ), $esta_direccion->getIdCiudad() );

	$form->renameField( array( 
		"nombre" 			=> "razon_social",
		"codigo_usuario"	=> "codigo_cliente",
		"telefono"          => "telefono1",
		"correo_electronico"    => "email",
		"id_clasificacion_cliente"  => "clasificacion_cliente",
		"id_moneda"     => "moneda_del_cliente",
		"pagina_web"    => "direccion_web",
		"id_ciudad"     => "municipio",
		"referencia"    => "texto_extra"
	));

	$page->addComponent( $form );


	$page->render();
