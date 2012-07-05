<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");



	function funcion_producto($id_producto)
	{
		return ProductoDAO::getByPK($id_producto) ? ProductoDAO::getByPK($id_producto)->getNombreProducto() : "---------";
	}

	function funcion_orden_de_servicio($id_orden_de_servicio)
	{
		return OrdenDeServicioDAO::getByPK($id_orden_de_servicio) ? ServicioDAO::getByPK(OrdenDeServicioDAO::getByPK($id_orden_de_servicio)->getIdServicio())->getNombreServicio() : "---------";
	}

	function nombre_receptor($id_receptor, $obj)
	{
		if($obj["cancelado"] == 1){
			return "<font title = \"Abono cancelado debido a {$obj['motivo_cancelacion']}\" style = \"color:red; cursor: help;\" >" . UsuarioDAO::getByPK($id_receptor)->getNombre() . "</font>";    
		}        

		return UsuarioDAO::getByPK($id_receptor)->getNombre();
	}


	function isCancelado($foo, $obj){
		if($obj["cancelado"] == 1){
			return "<font title = \"Abono cancelado debido a {$obj['motivo_cancelacion']}\" style = \"color:red; cursor: help;\" >{$foo}</font>";    
		}

		return $foo;  
	}













	$page = new GerenciaComponentPage();

	//
	// Requerir parametros
	// 
	$page->requireParam("vid", "GET", "Esta venta no existe.");
	
	



	VentasController::ActualizarTotales($_GET["vid"]);
	

	$esta_venta = VentaDAO::getByPK($_GET["vid"]);



	//
	// Titulo de la pagina
	//
	if($esta_venta->getEsCotizacion()){
		$page->addComponent(new TitleComponent("Detalles de la cotizacion " . $esta_venta->getIdVenta(), 2));

	}else{
		$page->addComponent(new TitleComponent("Detalles de la venta " . $esta_venta->getIdVenta(), 2));

	}
	
	if($esta_venta->getCancelada()){
		$page->addComponent(new TitleComponent("[CANCELADA]",3));
	}

	//
	// Menu de opciones
	// 
	$menu = new MenuComponent();

	if (!$esta_venta->getEsCotizacion() && !$esta_venta->getCancelada())
	{
		
		if ($esta_venta->getTipoDeVenta() == "credito")
		{
			$menu->addItem("Abonar a esta venta", "cargos_y_abonos.nuevo.abono.php?vid=" . $_GET["vid"] . "&did=" . $esta_venta->getIdCompradorVenta());
		}
	
		$btn_eliminar = new MenuItem("Cancelar esta venta", null);
		$btn_eliminar->addApiCall("api/ventas/cancelar", "GET");
		$btn_eliminar->onApiCallSuccessRedirect("ventas.lista.php");
		$btn_eliminar->addName("cancelar");
	
		$funcion_eliminar = " function eliminar_empresa(btn){" . "if(btn == 'yes')" . "{" . "var p = {};" . "p.id_venta = " . $_GET["vid"] . ";" . "sendToApi_cancelar(p);" . "}" . "}" . "      " . "function confirmar(){" . " Ext.MessageBox.confirm('Desactivar', 'Desea cancelar esta venta?', eliminar_empresa );" . "}";
	
		$btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
	
		$menu->addMenuItem($btn_eliminar);
	
	}


	$btn = new MenuItem("<img src='../../media/iconos/printer.png'> Imprimir", null);
	$btn->addOnClick("i", "function i(){window.location = 'ventas.detalle.imprimir.php?vid=" . $esta_venta->getIdVenta() . "';}");
	$menu->addMenuItem($btn);
	$page->addComponent($menu);

	$esta_venta->setSaldo(	FormatMoney(	$esta_venta->getSaldo()));
	$esta_venta->setTotal(	FormatMoney(	$esta_venta->getTotal()));
	$esta_venta->setSubtotal(FormatMoney(	$esta_venta->getSubtotal()));

	$esta_venta->setFecha( 	FormatTime(		$esta_venta->getFecha(), "SUPLEMENTARY" ));

	$form = new DAOFormComponent($esta_venta);

	$form->setEditable(false);

	$form->hideField(array(
		"id_venta",
		"id_caja",
		"id_venta_caja",
		"id_comprador_venta",
		"id_sucursal",
		"id_usuario",
		"impuesto",
		"retencion",
		"es_cotizacion",
		"cancelada",
		"tipo_de_pago"
	));

	if($esta_venta->getTipoDeVenta() == "contado"){
		$form->hideField("saldo");
	}

	$page->addComponent($form);


	function function_importe($foo, $obj){
		return FormatMoney( (float)$obj["precio"]  * (float)$obj["cantidad"]);
	}


	if( sizeof($productos = VentaProductoDAO::search(new VentaProducto(array("id_venta" => $_GET["vid"])))) > 0 ){

			$page->addComponent(new TitleComponent("Productos en esta venta", 3));

			$tabla = new TableComponent(array(
				"id_producto" => "Producto",
				"cantidad" => "Cantidad",
				"precio" => "Precio Unitario",
				"id_unidad" => "Importe"
			), $productos);

			
			$tabla->addColRender("id_unidad", 	"function_importe");
			$tabla->addColRender("id_producto", 	"funcion_producto");
			//$tabla->addColRender("cantidad", 		"FormatMoney");
			$tabla->addColRender("precio", "FormatMoney");

			$page->addComponent($tabla);
	}


	


	if(sizeof($v = VentaOrdenDAO::search(new VentaOrden(array("id_venta" => $_GET["vid"] )))) > 0){

		$page->addComponent(new TitleComponent("Ordenes de servicio de esta venta", 3));

		$tabla = new TableComponent(array(
			"id_orden_de_servicio" => "Orden de Servicio",
			"precio" => "Precio",
			"descuento" => "Descuento"
		), $v);	
		

		$tabla->addColRender("id_orden_de_servicio", "funcion_orden_de_servicio");

		$page->addComponent($tabla);
	}

	


   	$page->addComponent(new TitleComponent("Abonos a esta venta", 3));

	$tabla_abonos = new TableComponent(array(
        "monto" => "Monto",
        "id_receptor" => "Recibio",
        "nota" => "Nota",
        "fecha" => "Fecha",
        "tipo_de_pago" => "Tipo de Pago"
	), AbonoVentaDAO::search(new AbonoVenta(array(
		"id_venta" => $_GET["vid"]
	))));


	$tabla_abonos->addColRender("monto", "isCancelado");
	$tabla_abonos->addColRender("id_receptor", "nombre_receptor");
	$tabla_abonos->addColRender("nota", "isCancelado");
	$tabla_abonos->addColRender("fecha", "isCancelado");
	$tabla_abonos->addColRender("tipo_de_pago", "isCancelado");

	$page->addComponent($tabla_abonos);

	$page->render();








