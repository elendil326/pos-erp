<?php 	
	include_once("libBD.php");
	include_once("cliente.php");
	include_once("compra.php");
	include_once("cotizacion.php");
	include_once("cuenta_cliente.php");
	include_once("cuenta_proveedor.php");
	include_once("detalle_compra.php");
	include_once("detalle_cotizacion.php");
	include_once("detalle_inventario.php");
	include_once("detalle_venta.php");
	include_once("factura_compra.php");
	include_once("factura_venta.php");
	include_once("impuesto.php");
	include_once("inventario.php");
	include_once("nota_remision.php");
	include_once("pagos_compra.php");
	include_once("pagos_venta.php");
	include_once("productos_proveedor.php");
	include_once("proveedor.php");
	include_once("sucursal.php");
	include_once("usuario.php");
	include_once("venta.php"); 
	include_once("listar.php"); 
	

	include_once('pos/funcionesCliente.php');
	include_once('pos/funcionesCompra.php');
	include_once('pos/funcionesCotizacion.php');
	include_once('pos/funcionesProveedor.php');
	
	
	
	/*
		DA BIG SWITCHING FUNCTION 
	*/
	
	switch($_REQUEST["method"]){
		case "insertarFacturaCompra" : 					insertarFacturaCompra(); break;
		case "agregarProductoDetalle_compra" : 			agregarProductoDetalle_compra(); break;
		case "eliminarProductoDetalle_compra" : 		eliminarProductoDetalle_compra(); break;
		case "actualizarCantidadProductoDetCot" : 		actualizarCantidadProductoDetCot(); break;
		case "actualizaCabeceraCompra" : 				actualizaCabeceraCompra(); break;
		case "mostrarDetalleCompra" : 					mostrarDetalleCompra(); break;
		case "eliminarFacturaCompra" : 					eliminarFacturaCompra(); break;
		case "actualizarFacturaCompra" :	 			actualizarFacturaCompra(); break;
		case "comprarProducto" : 						comprarProducto(); break;
		case "listarFacturasCompra" : 					listarFacturasCompra(); break;
		case "listarCompras" : 							listarCompras(); break;
		case "insertarCompra" : 						insertarCompra(); break;
		case "eliminarCompra" : 						eliminarCompra(); break;
		case "reporteCompra" : 							reporteCompra(); break;
		case "reporteFactura" : 						reporteFactura(); break;
		
		
		
		//-----
		case 'listarClientes':									listarClientes();	break;
		case 'actualizarCliente':								actualizarCliente();	break;
		case 'eliminarCliente':									eliminarCliente();	break;
		case 'insertarCliente':									insertarCliente();	break;
		case 'mostrarCliente':									mostrarCliente();	break;
		case 'reporteClientesTodos': 							reporteClientesTodos(); 	break;
		case 'reporteClientesDeben': 							reporteClientesDeben(); 	break;
		case 'reporteClientesCompras': 							reporteClientesCompras(); 	break;
		case 'reporteClientesComprasCredito': 					reporteClientesComprasCredito(); 	break;
		case 'reporteClientesComprasCreditoDeben': 				reporteClientesComprasCreditoDeben(); 	break;
		
		
		//------
		case 'listarProveedores': 								listarProveedores(); 	break;
		case 'actualizarProveedor':								actualizarProveedor();	break;
		case 'eliminarProveedor':								eliminarProveedor(); 	break;
		case 'insertarProveedor': 								insertarProveedor();	break;
		case 'mostrarProveedor':								mostrarProveedor();		break;
		
		
		
		//------
		case 'listarCotizaciones':						$fC = new funcionesCotizacion(); $fC->listarCotizaciones();	break;
		case 'actualizarCantidadProductoDetCot':		$fC = new funcionesCotizacion(); $fC->actualizarCantidadProductoDetCot();	break;
		case 'eliminarCotizacion':						$fC = new funcionesCotizacion(); $fC->eliminarCotizacion();	break;
		case 'insertarCotizacion': 						$fC = new funcionesCotizacion(); $fC->insertarCotizacion();	break;
		case 'agregarProductoCotizacion':				$fC = new funcionesCotizacion(); $fC->agregarProductoCotizacion();	break;
		case 'eliminarProductoCotizacion':				$fC = new funcionesCotizacion(); $fC->eliminarProductoCotizacion();	break;
		case 'mostrarDetalleCotizacion':				$fC = new funcionesCotizacion(); $fC->mostrarDetalleCotizacion();	break;
		
		
		
		default: echo "{success:false, reason:'Bad Request'}"; 
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function fail($razon){
		echo "{success : false, error: '".$razon."'}";
		return;
	}
	function ok(){
		echo "{success : true}";
		return;
	}
	function ok_datos($datos){
		echo "{success : true ,$datos}";
		return;
	}
?>