<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server//bootstrap.php");

	$page = new GerenciaTabPage("Compras");
	$page->addComponent(new TitleComponent("Compras"));


	$page->nextTab("Activas");
	$page->addComponent(new MessageComponent("Lista de compras"));

	$compras_activas = CompraDAO::search(new Compra(array("cancelada" => 0)));

	$tabla = new TableComponent(array(
	    "id_vendedor_compra" => "Proveedor",
	    "tipo_de_compra" => "Tipo de compra",
	    "subtotal" => "Subtotal",
	    "impuesto" => "Impuesto",
	    "total" => "Total",
	    "saldo" => "Saldo",
	    "fecha" => "Fecha"
	), $compras_activas /*CompraDAO::getAll(NULL, null, "fecha", "DESC")*/ );



	function funcion_vendedor($id_vendedor){   return (UsuarioDAO::getByPK($id_vendedor) ? UsuarioDAO::getByPK($id_vendedor)->getNombre() : "-----"); }


	$tabla->addColRender("id_vendedor_compra", "funcion_vendedor");
	$tabla->addColRender("fecha", "FormatTime");
	$tabla->addColRender("subtotal", "FormatMoney");
	$tabla->addColRender("impuesto", "FormatMoney");
	$tabla->addColRender("total", "FormatMoney");
	$tabla->addColRender("saldo", "FormatMoney");
	$tabla->convertToExtJs(true);
	$tabla->addOnClick("id_compra", "(function(a){ window.location = 'compras.detalle.php?cid=' + a; })");

	$page->addComponent($tabla);










	$page->nextTab("Canceladas");

	$page->addComponent(new MessageComponent("Lista de compras canceladas"));

	$compras_no_activas = CompraDAO::search(new Compra(array("cancelada" => 1)));


	$tabla2 = new TableComponent(array(
	    "id_vendedor_compra" => "Proveedor",
	    "tipo_de_compra" => "Tipo de compra",
	    "subtotal" => "Subtotal",
	    "impuesto" => "Impuesto",
	    "total" => "Total",
	    "saldo" => "Saldo",
	    "fecha" => "Fecha"
	), $compras_no_activas );



	$tabla2->addColRender("id_vendedor_compra", "funcion_vendedor");
	$tabla2->addColRender("fecha", "FormatTime");
	$tabla2->addColRender("subtotal", "FormatMoney");
	$tabla2->addColRender("impuesto", "FormatMoney");
	$tabla2->addColRender("total", "FormatMoney");
	$tabla2->addColRender("saldo", "FormatMoney");


	$tabla2->addOnClick("id_compra", "(function(a){ window.location = 'compras.detalle.php?cid=' + a; })");

	$page->addComponent($tabla2);
	/*
	$t2 = clone $tabla;

	$t2-> setRows($compras_no_activas);

	$page->addComponent($t2);

	/*var_dump($t2);
	var_dump($tabla);
	die;*/

	$page->render();
