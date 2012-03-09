<?php 

		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Este cliente no existe." );

				
		$este_usuario = UsuarioDAO::getByPK( $_GET["cid"] );
		
		if(is_null($este_usuario)){
		
			die;
		}
		
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_usuario->getNombre() , 2 ));

		
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
		$page->addComponent( $form );



		$direccion = $este_usuario->getIdDireccion();
		$direccionObj = DireccionDAO::getByPK( $direccion );
		
		if(!is_null($direccionObj)){
			$dform = new DAOFormComponent( $direccionObj );
			$dform->setEditable(false);		
			$form->hideField( array( 
					"id_direccion"
				 ));
			$page->addComponent( $dform );
		}
		
	//AVALES

    $page->addComponent( new TitleComponent( "Nuevo Aval", 2 ) );

        $clientes_component = new ClienteSelectorComponent();                
        $clientes_component->addJsCallback("( function(record){ Ext.get('add_aval').setStyle({'display':'block'}); id_usuario = record.get('id_usuario'); nombre = record.get('nombre'); id_este_usuario = " . $este_usuario->getIdUsuario() . " } )");    
        $page->addComponent( $clientes_component );                        

        $page->addComponent( new FreeHtmlComponent ( "<br><div id = \"add_aval\" style = \"display:none;\" ><form name = \"tipo_aval\" id = \"tipo_aval\"> <input id = \"radio_hipoteca\" type='Radio' name='taval' value='hipoteca' checked> hipoteca <input id = \"radio_prendario\"type='Radio' name='taval' value='prendario'> prendario</form> <br> <div class='POS Boton' onClick = \"nuevoClienteAval(nombre, id_usuario, id_este_usuario)\" >Agregar como aval</div></div>" ) );
    
        $page->addComponent( new TitleComponent( "Lista de Avales", 2 ) );

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
		
		$page->render();
