<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaTabPage();

		//----------------------------------- 
		$page->nextTab("Ordenes");
		
		


		$ordenes = ServiciosController::ListaOrden();

		if(  $ordenes["numero_de_resultados"] == 1){
			$msg = "Hay <b style='color:#325C99'>" . $ordenes["numero_de_resultados"] . "</b> orden en espera.";	
		}else{
			$msg = "Hay <b style='color:#325C99'>" . $ordenes["numero_de_resultados"] . "</b> ordenes en espera.";	
		}
		

		$page->addComponent(new MessageComponent("<h2>" . $msg . "</h2>"));

		$tabla = new TableComponent(array(
			"id_usuario_venta" => "Cliente",
			"fecha_orden" => "Fecha Orden",
			"id_servicio" => "Servicio"
			
		), $ordenes["resultados"]);

		function funcion_servicio($servicio)
		{
			return ServicioDAO::getByPK($servicio) ? ServicioDAO::getByPK($servicio)->getNombreServicio() : "????";
		}

		function funcion_usuario_venta($usuario_venta)
		{
			return UsuarioDAO::getByPK($usuario_venta) ? UsuarioDAO::getByPK($usuario_venta)->getNombre() : "????";
		}

		function funcion_activa($activa)
		{
			return ($activa) ? "Activa" : "Inactiva";
		}

		function funcion_cancelada($cancelada)
		{
			return ($cancelada) ? "Cancelada" : "No Cancelada";
		}
		
		
		function funcion_transcurrido($a, $obj){
			return FormatTime(strtotime($obj["fecha_orden"]));
		}

		$tabla->addColRender("fecha_orden", "funcion_transcurrido");

		$tabla->addColRender("activa", "funcion_activa");

		$tabla->addColRender("cancelada", "funcion_cancelada");

		$tabla->addColRender("id_servicio", "funcion_servicio");

		$tabla->addColRender("id_usuario_venta", "funcion_usuario_venta");

		$tabla->addOnClick("id_orden_de_servicio", "(function(a){ window.location = 'servicios.detalle.orden.php?oid=' + a; })");


		$page->addComponent($tabla);


		//----------------------------------- 
		$page->nextTab("Servicios");
		
		$page->addComponent( new MessageComponent( "Lista de servicios" ) );

		$r = ServiciosController::Buscar();

		$tabla = new TableComponent( 
		array(
		"codigo_servicio" => "Codigo de servicio",
		"nombre_servicio" => "Nombre",
		"metodo_costeo" => "Metodo de costeo",
		"precio" => "Precio",
		"activo" => "Activo"
		),
		$r["resultados"]
		);

		function funcion_activo( $activo )
		{
		return ($activo) ? "Activo" : "Inactivo";
		}

		$tabla->addColRender("activo", "funcion_activo");

		$tabla->addOnClick( "id_servicio", "(function(a){ window.location = 'servicios.ver.php?sid=' + a; })" );


		$page->addComponent( $tabla );




		$page->render();
