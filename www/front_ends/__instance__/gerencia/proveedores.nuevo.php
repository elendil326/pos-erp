<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //titulos
	$page->addComponent( new TitleComponent( "Nuevo proveedor" ) );

	//forma de nuevo proveedor
	$form = new DAOFormComponent( array( new Usuario() , new Direccion() ) );
	
	$form->hideField( array( 
			"id_usuario",
                        "id_direccion",
                        "id_direccion_alterna",
                        "id_sucursal",
                        "id_rol",
                        "id_clasificacion_cliente",
                        "fecha_asignacion_rol",
                        "comision_ventas",
                        "fecha_alta",
                        "fecha_baja",
                        "last_login",
                        "consignatario",
                        "salario",
                        "saldo_del_ejercicio",
                        "ventas_a_credito",
                        "facturar_a_terceros",
                        "dia_de_pago",
                        "mensajeria",
                        "intereses_moratorios",
                        "denominacion_comercial",
                        "cuenta_de_mensajeria",
                        "dia_de_revision",
                        "id_direccion",
                        "id_usuario_ultima_modificacion",
                        "ultima_modificacion",
                        "descuento",
                        "curp"
		 ));
        
        $form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ) );
	$form->createComboBoxJoin( "id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::search( new ClasificacionProveedor( array( "activa" => 1 ) ) ) );
        $form->createComboBoxJoin("id_moneda", "nombre", MonedaDAO::search( new Moneda( array( "activa" => 1 ) ) ));
        

	$form->renameField( array( 
			"id_clasificacion_proveedor" 			=> "id_tipo_proveedor",
			"codigo_usuario"	=> "codigo_proveedor",
                        "referencia"        =>  "texto_extra",
                        "pagina_web"        => "direccion_web",
                        "telefono"          => "telefono1",
                        "correo_electronico" => "email",
                        "dias_de_embarque"  => "dias_embarque"
		));
	
	$form->addApiCall( "api/proveedor/nuevo/", "GET" );
	
	$form->makeObligatory(array( 
			"nombre",
                        "codigo_proveedor",
                        "password",
                        "id_tipo_proveedor"
		));
	
	
	
	$page->addComponent( $form );


	//render the page
		$page->render();
