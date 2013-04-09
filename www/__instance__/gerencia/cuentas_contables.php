<?php

		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server//bootstrap.php");

		$page = new GerenciaTabPage();

		$page->addComponent(new TitleComponent("Catalogo de Cuentas", 2));


		$page->addComponent( "<div class='POS Boton' onClick='window.location=\"cuentas_contables.nueva.php\"'>Nueva Cuenta</div> " );

		$lista = ContabilidadController::BuscarCuenta();

		$page->addComponent(sizeof($lista["resultados"]) ." cuentas.");
		
		$tabla = new TableComponent( 
			array(
				"clave"							=> "Clave",
				"nombre_cuenta"					=> "Cuenta",
				"tipo_cuenta"					=> "Tipo",
				"naturaleza"					=> "Naturaleza",
				"clasificacion"					=> "Clasificacion",
				"es_cuenta_mayor"				=> "Cuenta Mayor",
				"es_cuenta_orden"				=> "Cuenta de Orden"
			),
			$lista["resultados"]
		);

		$tabla->convertToExtJs(false);
 		$tabla->addOnClick( "id_cuenta_contable", "(function(a){ window.location = 'cuentas_contables.ver.php?cid=' + a; })" );

		$page->addComponent( $tabla );

		$page->render();





