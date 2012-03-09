<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//
	// Requerir parametros
	// 
	$page->requireParam("vid", "GET", "Esta venta no existe.");
	$esta_venta = VentaDAO::getByPK($_GET["vid"]);



	//
	// Titulo de la pagina
	// 
	$page->addComponent(new TitleComponent("Detalles de la venta " . $esta_venta->getIdVenta(), 2));


	//
	// Menu de opciones
	// 
	if (!$esta_venta->getCancelada())
	{
		$menu = new MenuComponent();
	
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
	
		$page->addComponent($menu);
	}

	//
	// Forma de producto
	// 
	$form = new DAOFormComponent($esta_venta);

	$form->setEditable(false);

	$form->hideField(array(
		"id_venta",
		"id_caja",
		"id_venta_caja",
		"id_comprador_venta",
		"id_sucursal",
		"id_usuario"
	));

	$page->addComponent($form);

	$page->addComponent(new TitleComponent("Productos de esta venta", 3));

	$tabla = new TableComponent(array(
		"id_producto" => "Producto",
		"id_unidad" => "Unidad",
		"cantidad" => "Cantidad",
		"precio" => "Precio Unitario",
		"descuento" => "Descuento",
		"impuesto" => "Impuesto",
		"retencion" => "Retencion"
	), VentaProductoDAO::search(new VentaProducto(array(
		"id_venta" => $_GET["vid"]
	))));

	function funcion_producto($id_producto)
	{
		return ProductoDAO::getByPK($id_producto) ? ProductoDAO::getByPK($id_producto)->getNombreProducto() : "---------";
	}

	$tabla->addColRender("id_producto", "funcion_producto");

	$page->addComponent($tabla);

	$page->addComponent(new TitleComponent("Ordenes de servicio de esta venta", 3));

	$tabla = new TableComponent(array(
		"id_orden_de_servicio" => "Orden de Servicio",
		"cantidad" => "Cantidad",
		"precio" => "Precio Unitario",
		"descuento" => "Descuento",
		"impuesto" => "Impuesto",
		"retencion" => "Retencion"
	), VentaOrdenDAO::search(new VentaOrden(array(
		"id_venta" => $_GET["vid"]
	))));

	function funcion_orden_de_servicio($id_orden_de_servicio)
	{
		return OrdenDeServicioDAO::getByPK($id_orden_de_servicio) ? ServicioDAO::getByPK(OrdenDeServicioDAO::getByPK($id_orden_de_servicio)->getIdServicio())->getNombreServicio() : "---------";
	}

	$tabla->addColRender("id_orden_de_servicio", "funcion_orden_de_servicio");

	$page->addComponent($tabla);

	$page->addComponent(new TitleComponent("Paquetes de esta venta", 3));


	$page->partialRender();
	
?>


	aqui puedo poner html 

<?php

	$tabla = new TableComponent(array(
		"id_paquete" => "Paquete",
		"cantidad" => "Cantidad",
		"precio" => "Precio Unitario",
		"descuento" => "Descuento",
		"impuesto" => "Impuesto",
		"retencion" => "Retencion"
	), VentaPaqueteDAO::search(new VentaPaquete(array(
		"id_venta" => $_GET["vid"]
	))));

	function funcion_paquete($id_paquete)
	{
		return PaqueteDAO::getByPK($id_paquete) ? PaqueteDAO::getByPK($id_paquete)->getNombre() : "---------";
	}

	$tabla->addColRender("id_paquete", "funcion_paquete");

	$page->addComponent($tabla);

/*
	$page->addComponent(new TitleComponent("Informacion de Arpilla", 3));

	$tabla = new TableComponent(array(
		"folio" => "Folio",
		"productor" => "Productor",
		"numero_de_viaje" => "Numero de viaje",
		"fecha_origen" => "Fecha en que se envio",
		"peso_origen" => "Peso en el origen",
		"peso_recibido" => "Peso recibido",
		"arpillas" => "Arpillas",
		"peso_por_arpilla" => "Peso por arpilla",
		"merma_por_arpilla" => "Merma por arpilla",
		"total_origen" => "Total en el origen"
	), VentaArpillaDAO::search(new VentaArpilla(array(
		"id_venta" => $_GET["vid"]
	))));

	$page->addComponent($tabla);
*/

	$page->render();
