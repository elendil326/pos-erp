<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	$page->addComponent(new TitleComponent("Ventas"));
	$page->addComponent(new MessageComponent("Lista de ventas"));

	$tabla = new TableComponent(array(
		"id_comprador_venta" => "Cliente",
		"tipo_de_venta" => "Tipo de venta",
		"subtotal" => "Subtotal",
		/*"impuesto" => "Impuesto",
		"descuento" => "Descuento",
		"retencion" => "Retencion",*/
		"total" => "Total",
		"saldo" => "Saldo",
		"cancelada" => "Cancelada",
		"fecha" => "Fecha"
	), VentaDAO::getAll(NULL, NULL, "fecha", "DESC"));

	//
	function funcion_comprador($id_comprador)
	{
		return (UsuarioDAO::getByPK($id_comprador) ? UsuarioDAO::getByPK($id_comprador)->getNombre() : "-----");
	}


	$tabla->addColRender("id_comprador_venta", "funcion_comprador");
	$tabla->addColRender("cancelada", "funcion_cancelada");
	$tabla->addOnClick("id_venta", "(function(a){ window.location = 'ventas.detalle.php?vid=' + a; })");
	$page->addComponent($tabla);

	$page->render();
