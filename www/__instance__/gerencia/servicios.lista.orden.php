<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	$page->addComponent(new TitleComponent("Ordenes de servicio"));
	$page->addComponent(new MessageComponent("Lista de ordenes de servicio"));

	$ordenes = ServiciosController::ListaOrden();
	

	$tabla = new TableComponent(array(
		"fecha_orden" => "Fecha Orden",
		"id_servicio" => "Servicio",
		"id_usuario_venta" => "Cliente",
		/*"fecha_entrega" => "Fecha Entrega",
		"adelanto" => "Adelanto",
		"activa" => "Activa",
		"cancelada" => "Cancelada",
		"descripcion" => "Descripcion"*/
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

	$tabla->addColRender("activa", "funcion_activa");

	$tabla->addColRender("cancelada", "funcion_cancelada");

	$tabla->addColRender("id_servicio", "funcion_servicio");

	$tabla->addColRender("id_usuario_venta", "funcion_usuario_venta");

	$tabla->addOnClick("id_orden_de_servicio", "(function(a){ window.location = 'servicios.detalle.orden.php?oid=' + a; })");


	$page->addComponent($tabla);

	$page->render();
