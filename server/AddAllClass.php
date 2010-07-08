<?php	
//Para todos los php que tengan session
	session_start();

	include_once("libBD.php");
	
	include_once('sesion.php');
	
	include_once("cliente.php");
	include_once("compra.php");
	include_once("cotizacion.php");
	include_once("cuenta_cliente.php");
	include_once("cuenta_proveedor.php");
	include_once("detalle_compra.php");
	include_once("detalle_cotizacion.php");
	include_once("detalle_inventario.php");
	include_once("detalle_venta.php");
	include_once("detalle_factura.php");
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
	include_once("encargado.php"); 
	include_once("gastos.php"); 
	

	include_once('pos/funcionesCliente.php');
	include_once('pos/funcionesCompra.php');
	include_once('pos/funcionesVentas.php');
	include_once('pos/funcionesCotizacion.php');
	include_once('pos/funcionesProveedor.php');
	
	include_once('funcionesImpuesto.php');
	include_once('funcionesInventario.php');
	include_once('funcionesPago.php');
	include_once('funcionesSucursal.php');
	include_once('funcionesUsuario.php');
	
	
	
	/*
		DA BIG SWITCHING FUNCTION 
	*/
	
	
	
	switch($_REQUEST["method"]){
	
		//------funciones para checar login
		case "login" :				login(); break;
	
		//-----Funciones compra
		case "insertarFacturaCompra" : 					insertarFacturaCompra(); break;
		case "agregarProductoDetalle_compra" : 			agregarProductoDetalle_compra(); break;
		case "eliminarProductoDetalle_compra" : 		eliminarProductoDetalle_compra(); break;
		case "actualizarCantidadProductoDetCompra" : 	actualizarCantidadProductoDetCompra(); break;
		case "actualizaCabeceraCompra" : 				actualizaCabeceraCompra(); break;
		case "mostrarDetalleCompra" : 					mostrarDetalleCompra(); break;
		case "eliminarFacturaCompra" : 					eliminarFacturaCompra(); break;
		case "actualizarFacturaCompra" :	 			actualizarFacturaCompra(); break;
		case "comprarProducto" : 						comprarProducto(); break;
		case "listarFacturasCompra" : 					listarFacturasCompra(); break;
		case "listarCompras" : 							listarCompras(); break;
		case "insertarCompra" : 						insertarCompra(); break;
		case "eliminarCompra" : 						eliminarCompra(); break;
		//				reportes
		case "reporteCompra" : 							reporteCompra(); break;
		case "reporteFacturaCompra" : 					reporteFacturaCompra(); break;
		case "reporteCompras" : 						reporteCompras(); break;
		
		
		//-----funciones Usuario
		case "insertarUsuario" : 				insertarUsuario(); break;
		case "eliminarUsuario" : 				eliminarUsuario(); break;
		case "cambiaPassword" : 				cambiaPassword(); break;
		case "actualizarUsuario" : 				actualizarUsuario(); break;
		case "listarUsuario" : 					listarUsuario(); break;
		case "datosUsuario" : 					datosUsuario(); break;
		case "loginUsuario" : 					loginUsuario(); break;
			
		
		//-----funciones Venta
		case "insertarFacturaVenta" : 			insertarFacturaVenta(); break;
		case "eliminarFacturaVenta" : 			eliminarFacturaVenta(); break;
		case "actualizarFacturaVenta" : 		actualizarFacturaVenta(); break;
		case "insertarNota" : 					insertarNota(); break;
		case "eliminarNota" : 					eliminarNota(); break;
		case "actualizarNota" : 				actualizarNota(); break;
		case "venderProducto" : 				venderProducto(); break;
		case "facturaProducto" : 				facturaProducto(); break;
		case "listarFacturasVenta" : 			listarFacturasVenta(); break;
		case "listarNotas" : 					listarNotas(); break;
		//----------reportes de ventas
		case "reporteVentasEmpleado" : 			reporteVentasEmpleado(); break;
		case "reporteVentasSucursales" : 		reporteVentasSucursales(); break;
		case "reporteVentas" : 					reporteVentas(); break;
		
		case "agregarProductoDetalle_venta":	agregarProductoDetalle_venta(); break;
		case "eliminarProductoDetalle_venta":	eliminarProductoDetalle_venta(); break;
		case "actualizarCantidadProductoDetVenta": actualizarCantidadProductoDetVenta(); break;
		case "listarVentas":					listarVentas(); break;
		case "listarVentasCliente":				listarVentasCliente(); break;
		case "mostrarDetalleVenta":				mostrarDetalleVenta(); break;
		case "insertarVenta":					insertarVenta(); break;
		case "eliminarVenta":					eliminarVenta(); break;
		case "listarVentasCreditoCliente":		listarVentasCreditoCliente(); break;
		case "abonosVentaCredito":				abonosVentaCredito(); break;
		//---------reportes
		case "reporteCompraCliente":			reporteCompraCliente(); break;
		case "listarClientesSaldo":				listarClientesSaldo(); break;
		
		
		//----- funciones sucursal
		
		case "insertarSucursal" : 				insertarSucursal(); break;
		case "eliminarSucursal" : 				eliminarSucursal(); break;
		case "actualizarSucursal" : 			actualizarSucursal(); break;
		case "listarSucursal" : 				listarSucursal(); break;
		case "detallesSucursal":				detallesSucursal(); break;
		//-Encargado
		case "insertarEncargado":				insertarEncargado(); break;
		case "eliminarEncargado":				eliminarEncargado(); break;
		case "cambiarEncargado":				cambiarEncargado(); break;
		case "insertarGasto":					insertarGasto(); break;
		case "eliminarGasto":					eliminarGasto(); break;
		case "actualizarGasto":					actualizarGasto(); break;
		
		
		//----- funciones productos proveedor
		case "insertarProductoProveedor" : 		insertarProductoProveedor(); break;
		case "eliminarProductoProveedor" : 		eliminarProductoProveedor(); break;
		case "actualizarProductoProveedor" : 	actualizarProductoProveedor(); break;
		case "listarProveedor" : 				listarProveedor(); break;
		//------------------
		case "reporteCompraProveedor" : 		reporteCompraProveedor(); break;
		
		
		//-----Funciones pago
		case "insertarPagoCompra" : 			insertarPagoCompra(); break;
		case "eliminarPagoCompra" : 			eliminarPagoCompra(); break;
		case "insertarPagoVenta" : 				insertarPagoVenta(); break;
		case "eliminarPagoVenta" : 				eliminarPagoVenta(); break;
		case "deletePagoVenta" : 				deletePagoVenta(); break;
		case "listarPagosVenta" : 				listarPagosVenta(); break;
		case "listarPagosVentaDeVenta" : 		listarPagosVentaDeVenta(); break;
		case "listarPagosCompra" : 				listarPagosCompra(); break;
		case "listarPagosCompraDeCompra" : 		listarPagosCompraDeCompra(); break;
		
		//-----funciones invenario
		case "insertarInventario" : 					insertarInventario(); break;
		case "eliminarInventario" : 					eliminarInventario(); break;
		case "actualizarInventario" : 					actualizarInventario(); break;
		case "listarProductosInventario" : 				listarProductosInventario(); break;
		case "listarProductosInventarioSucursal" : 		listarProductosInventarioSucursal(); break;
		case "existenciaProductoSucursal" : 			existenciaProductoSucursal(); break;
		case "obtenerSucursalUsuario" :				obtenerSucursalUsuario(); break;
		
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
		//reportes
		case 'reporteClientesTodos': 							reporteClientesTodos(); 	break;
		case 'reporteClientesDeben': 							reporteClientesDeben(); 	break;
		case 'reporteClientesCompras': 							reporteClientesCompras(); 	break;
		case 'reporteClientesComprasCredito': 					reporteClientesComprasCredito(); 	break;
		case 'reporteClientesComprasCreditoDeben': 				reporteClientesComprasCreditoDeben(); 	break;
		case 'reporteClientesComprasCreditoPagado': 			reporteClientesComprasCreditoPagado(); 	break;
		
		//reportes_jgrid
		case 'reporteClientesTodos_jgrid': 							reporteClientesTodos_jgrid(); 	break;
		case 'reporteClientesDeben_jgrid': 							reporteClientesDeben_jgrid(); 	break;
		case 'reporteClientesCompras_jgrid': 							reporteClientesCompras_jgrid(); 	break;
		
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
		
		//---funciones de la sesion
		case 'estaLoggeado': 					estaLoggeado(); break;
		
		default: echo "{success:false, reason:'Bad Request'}"; 
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function fail($razon){
		echo "{success : false, reason: '".$razon."'}";
		return;
	}
	function ok(){
		echo "{success : true}";
		return;
	}
	function ok_datos($datos){
		echo "{success : true , $datos}";
		return;
	}
?>
