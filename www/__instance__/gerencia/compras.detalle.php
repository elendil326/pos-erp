<?php


	define("BYPASS_INSTANCE_CHECK", false);


	require_once("../../../server//bootstrap.php");


	function funcion_producto($id_producto)
	{
	    return ProductoDAO::getByPK($id_producto) ? ProductoDAO::getByPK($id_producto)->getNombreProducto() : "---------";
	}
	
	

	$page = new GerenciaComponentPage();


	//
	// Requerir parametros
	// 
	$page->requireParam("cid", "GET", "Esta compra no existe.");

	$esta_compra = CompraDAO::getByPK($_GET["cid"]);

	if($esta_compra === null){
		$page->addComponent("<p>Esta compra no existe</p>");
		$page->render(0);
		exit;
	}

	//
	// Titulo de la pagina
	// 
	$page->addComponent(new TitleComponent("Detalles de la compra " . $esta_compra->getIdCompra(), 2));


	//
	// Menu de opciones
	// 
	if (!$esta_compra->getCancelada()) {
	    $menu = new MenuComponent();
    
	    if ($esta_compra->getTipoDeCompra() == "credito") {
	        $menu->addItem("Abonar a esta compra", "cargos_y_abonos.nuevo.abono.php?cid=" . $_GET["cid"] . "&did=" . $esta_compra->getIdVendedorCompra());
	    }
    
	    $btn_eliminar = new MenuItem("Cancelar esta compra", null);
	    $btn_eliminar->addApiCall("api/compras/cancelar", "GET");
	    $btn_eliminar->onApiCallSuccessRedirect("compras.lista.php");
	    $btn_eliminar->addName("cancelar");
    
	    $funcion_eliminar = " function eliminar_empresa(btn){" . "if(btn == 'yes')" . "{" . "var p = {};" . "p.id_compra = " . $_GET["cid"] . ";" . "sendToApi_cancelar(p);" . "}" . "}" . "      " . "function confirmar(){" . " Ext.MessageBox.confirm('Desactivar', 'Desea cancelar esta compra?', eliminar_empresa );" . "}";
    
	    $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
    
	    $menu->addMenuItem($btn_eliminar);
    
	    $page->addComponent($menu);
	}

	//
	// Forma de producto
	// 
	$esta_compra->setFecha( FormatTime($esta_compra -> getFecha()) );

	$esta_compra->setImpuesto(FormatMoney($esta_compra->getImpuesto()));
	$esta_compra->setDescuento(FormatMoney($esta_compra->getDescuento()));
	$esta_compra->setSubtotal(FormatMoney($esta_compra->getSubtotal()));
	$esta_compra->setTotal(FormatMoney($esta_compra->getTotal()));

	
				
	$form = new DAOFormComponent($esta_compra);

	$form->setEditable(false);
	
	$form->hideField(array(
	    "id_compra",
	    "id_caja",
	    "id_compra_caja",
	    "id_vendedor_compra",
	    "id_sucursal",
	    "id_usuario",
		"saldo",
		"retencion",
	    "id_empresa"
	));

	$page->addComponent($form);

	$page->addComponent(new TitleComponent("Productos de esta compra", 3));

	$tabla = new TableComponent(array(
	    "id_producto" => "Producto",
	    "cantidad" => "Cantidad",
	    "id_unidad" => "Unidad",	
	    "precio" => "Precio Unitario",
		"impuesto" => "Importe"
	), CompraProductoDAO::search(new CompraProducto(array(
	    "id_compra" => $_GET["cid"]
	))));


	function cImporte ($foo, $obj){
		return FormatMoney( $obj["precio"] * $obj["cantidad"] );
	}
	
	function toReadableUnidad($v,  $obj){
		$u = UnidadMedidaDAO::getByPK($v);
		return $u->getDescripcion();
	}
	
	$tabla->addColRender("id_unidad", "toReadableUnidad");
	$tabla->addColRender("precio", "FormatMoney");
	$tabla->addColRender("id_producto", "funcion_producto");
	$tabla->addColRender("impuesto", "cImporte");

	$page->addComponent($tabla);


	$page->render();
