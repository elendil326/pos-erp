<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaTabPage();

		$page->addComponent(new TitleComponent("Catalogo de clientes", 2));


		$page->nextTab("Lista");
		
		$page->addComponent( "<div class='POS Boton' onClick='window.location=\"clientes.nuevo.php\"'>Nuevo cliente</div> " );
		
		$cselector = new ClienteSelectorComponent( );
		$cselector->addJsCallback( "(function(a){ window.location = 'clientes.ver.php?cid='+a.get('id_usuario'); })" );
		$page->addComponent( $cselector);

		$lista = ClientesController::Buscar();
		
		$tabla = new TableComponent( 
			array(
				"nombre"                        => "Nombre",
				"id_clasificacion_cliente"		=> "Clasifiacion",
				"saldo_del_ejercicio"           => "Saldo"
			),
			$lista["resultados"]
		);

 		$tabla->addColRender("saldo_del_ejercicio", "FormatMoney");
        
        function funcion_activo($activo){
            return ($activo ? "Activo" : "Inactivo" );
        }
        
        function funcion_consignatario($consignatario){
            return ($consignatario ? "Consignatario" : "----" );
        }

        function funcion_clasificacion_cliente($id_clasifiacion){
			if(is_null($id_clasifiacion)) return "";
			$c = ClasificacionClienteDAO::getByPK($id_clasifiacion);
			if(is_null($c)) return "";			
			return $c->getNombre();
		}

        $tabla->addColRender("id_clasificacion_cliente", "funcion_clasificacion_cliente");
        $tabla->addColRender("activo", "funcion_activo");
        $tabla->addColRender("consignatario", "funcion_consignatario");
		$tabla->addOnClick( "id_usuario", "(function(a){ window.location = 'clientes.ver.php?cid=' + a; })" );
		
			
		$page->addComponent( $tabla );

		$page->nextTab("Interacciones");
		
		//lista de clientes con los que se cuenta correo electronico
		
		
		
		
		

		$page->nextTab("Configuracion");

		$page->addComponent(new TitleComponent("Columnas extra",2));

		$epc = ExtraParamsEstructuraDAO::getByTabla("clientes");

		$h = array(
			"campo" => "campo",
			"tipo" => "tipo",
			"longitud" => "longitud",
			"obligatorio" => "olbigatorio"
		);


		$tabla = new TableComponent( $h, $epc );

		$page->addComponent($tabla);


		$page->addComponent(new TitleComponent("Nueva columna extra", 2));

		$nceObj = new ExtraParamsEstructura();
		$nceObj->setTabla("\"clientes\"");
		$nuevaColumnaForm = new DAOFormComponent( $nceObj );
		$nuevaColumnaForm->addApiCall("api/pos/bd/columna/nueva", "POST");
		$nuevaColumnaForm->makeObligatory(array( "campo", "tipo", "longitud", "olbigatorio" ));
		$nuevaColumnaForm->hideField( array("id_extra_params_estructura", "tabla") );
		$nuevaColumnaForm->sendHidden("tabla");
		$page->addComponent( $nuevaColumnaForm );


		$page->render();





