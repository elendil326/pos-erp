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
	
	include_once('funcionesImpuesto.php');
	include_once('funcionesInventario.php');
	include_once('funcionesPago.php');
	include_once('funcionesSucursal.php');
	include_once('funcionesUsuario.php');
	include_once('funcionesVenta.php');
	
	
	
	/*
		DA BIG SWITCHING FUNCTION 
	*/
	
	switch($_REQUEST["method"]){
	
		//-----Funciones compra
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
		
		
		//-----funciones Usuario
		case "insertarUsuario" : 				insertarUsuario(); break;
		case "eliminarUsuario" : 				eliminarUsuario(); break;
		case "cambiaPassword" : 				cambiaPassword(); break;
		case "actualizarUsuario" : 				actualizarUsuario(); break;
		case "listarUsuario" : 					listarUsuario(); break;
		case "datosUsuario" : 					datosUsuario(); break;
			
		
		//-----funciones Venta
		case "insertarFacturaVenta" : 			insertarFacturaVenta(); break;
		case "eliminarFacturaVenta" : 			eliminarFacturaVenta(); break;
		case "actualizarFacturaVenta" : 		actualizarFacturaVenta(); break;
		case "insertarNota" : 					insertarNota(); break;
		case "eliminarNota" : 					eliminarNota(); break;
		case "actualizarNota" : 				actualizarNota(); break;
		case "vendeProducto" : 					vendeProducto(); break;
		case "listarFacturasVenta" : 			listarFacturasVenta(); break;
		case "listarNotas" : 					listarNotas(); break;
		
		//----- funciones sucursal
		
		case "insertarSucursal" : 				insertarSucursal(); break;
		case "eliminarSucursal" : 				eliminarSucursal(); break;
		case "actualizarSucursal" : 			actualizarSucursal(); break;
		case "listarSucursal" : 				listarSucursal(); break;	
		
		//----- funciones productos proveedor
		case "insertarProductoProveedor" : 		insertarProductoProveedor(); break;
		case "eliminarProductoProveedor" : 		eliminarProductoProveedor(); break;
		case "actualizarProductoProveedor" : 	actualizarProductoProveedor(); break;
		case "listarProveedor" : 				listarProveedor(); break;
		
		//-----Funciones pago
		case "insertarPagoCompra" : 			insertarPagoCompra(); break;
		case "eliminarPagoCompra" : 			eliminarPagoCompra(); break;
		case "insertarPagoVenta" : 				insertarPagoVenta(); break;
		case "eliminarPagoVenta" : 				eliminarPagoVenta(); break;
		case "deletePagoVenta" : 				deletePagoVenta(); break;
		case "listarPagosVenta" : 				listarPagosVenta(); break;
		case "listarPagosCompra" : 				listarPagosCompra(); break;
		
		//-----funciones invenario
		case "insertarInventario" : 			insertarInventario(); break;
		case "eliminarInventario" : 			eliminarInventario(); break;
		case "actualizarInventario" : 			actualizarInventario(); break;
		case "listarProductosInventario" : 		listarProductosInventario(); break;
		
		//-----Funciones impuesto
		case "insertarImpuesto" : 				insertarImpuesto(); break;
		case "eliminarImpuesto" : 				eliminarImpuesto(); break;
		case "actualizarImpuesto" : 			actualizarImpuesto(); break;
		case "listarImpuesto" : 				listarImpuesto(); break;
		
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