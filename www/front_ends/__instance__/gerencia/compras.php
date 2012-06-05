<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	$page->addComponent(new TitleComponent("Compras"));
	$page->addComponent(new MessageComponent("Lista de compras"));

	$tabla = new TableComponent(array(
	    "id_vendedor_compra" => "Proveedor",
	    "tipo_de_compra" => "Tipo de compra",
	    "subtotal" => "Subtotal",
	    "impuesto" => "Impuesto",
	    "total" => "Total",
	    "saldo" => "Saldo",
//	    "cancelada" => "Cancelada",
	    "fecha" => "Fecha"
	), CompraDAO::getAll(NULL, null, "fecha", "DESC"));
	//                

	function funcion_vendedor($id_vendedor)
	{
	    return (UsuarioDAO::getByPK($id_vendedor) ? UsuarioDAO::getByPK($id_vendedor)->getNombre() : "-----");
	}



	$tabla->addColRender("id_vendedor_compra", "funcion_vendedor");
	$tabla->addColRender("fecha", "FormatTime");

	$tabla->addColRender("subtotal", "FormatMoney");
	$tabla->addColRender("impuesto", "FormatMoney");
	$tabla->addColRender("total", "FormatMoney");
	$tabla->addColRender("saldo", "FormatMoney");

	$tabla->addOnClick("id_compra", "(function(a){ window.location = 'compras.detalle.php?cid=' + a; })");

	$page->addComponent($tabla);

	$page->render();
