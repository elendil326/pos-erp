<?php	
//Para todos los php que tengan session
	session_start();

	include_once("libBD.php");
	
	include_once('sesion.php');
	
	include_once("model/cliente.class.php");
	include_once("model/clienteExistente.class.php");
	include_once("model/clienteVacio.class.php");
	
	include_once("model/compra.class.php");
	include_once("model/compraExistente.class.php");
	include_once("model/compraVacio.class.php");
	
	include_once("model/cotizacion.class.php");
	include_once("model/cotizacionExistente.class.php");
	include_once("model/cotizacionVacio.class.php");
	
	include_once("model/cuentaCliente.class.php");
	include_once("model/cuentaClienteExistente.class.php");
	include_once("model/cuentaClienteVacio.class.php");
	
	include_once("model/cuentaProveedor.class.php");
	include_once("model/cuentaProveedorExistente.class.php");
	include_once("model/cuentaProveedorVacio.class.php");
	
	include_once("model/detalleCompra.class.php");
	include_once("model/detalleCompraExistente.class.php");
	include_once("model/detalleCompraVacio.class.php");
	
	include_once("model/detalleCotizacion.class.php");
	include_once("model/detalleCotizacionExistente.class.php");
	include_once("model/detalleCotizacionVacio.class.php");
	
	include_once("model/detalleInventario.class.php");
	include_once("model/detalleInventarioExistente.class.php");
	include_once("model/detalleInventarioVacio.class.php");
	
	include_once("model/detalleVenta.class.php");
	include_once("model/detalleVentaExistente.class.php");
	include_once("model/detalleVentaVacio.class.php");
	
	include_once("model/detalleFactura.class.php");
	include_once("model/detalleFacturaExistente.class.php");
	include_once("model/detalleFacturaVacio.class.php");
		
	include_once("model/facturaCompra.class.php");
	include_once("model/facturaCompraExistente.class.php");
	include_once("model/facturaCompraVacio.class.php");
		
	include_once("model/facturaVenta.class.php");
	include_once("model/facturaVentaExistente.class.php");
	include_once("model/facturaVentaVacio.class.php");
	
	include_once("model/impuesto.class.php");
	include_once("model/impuestoExistente.class.php");
	include_once("model/impuestoVacio.class.php");
	
	include_once("model/inventario.class.php");
	include_once("model/inventarioExistente.class.php");
	include_once("model/inventarioVacio.class.php");
	
	include_once("model/notaRemision.class.php");
	include_once("model/notaRemisionExistente.class.php");
	include_once("model/notaRemisionVacio.class.php");
	
	include_once("model/pagosCompra.class.php");
	include_once("model/pagosCompraExistente.class.php");
	include_once("model/pagosCompraVacio.class.php");
	
	include_once("model/pagosVenta.class.php");
	include_once("model/pagosVentaExistente.class.php");
	include_once("model/pagosVentaVacio.class.php");
	
	include_once("model/productosProveedor.class.php");
	include_once("model/productosProveedorExistente.class.php");
	include_once("model/productosProveedorVacio.class.php");
	
	include_once("model/proveedor.class.php");
	include_once("model/proveedorExistente.class.php");
	include_once("model/proveedorVacio.class.php");
	
	include_once("model/sucursal.class.php");
	include_once("model/sucursalExistente.class.php");
	include_once("model/sucursalVacio.class.php");
	
	include_once("model/usuario.class.php");
	include_once("model/usuarioExistente.class.php");
	include_once("model/usuarioVacio.class.php");
	include_once("model/usuarioNombrePass.class.php");
	
	include_once("model/venta.class.php"); 
	include_once("model/ventaExistente.class.php"); 
	include_once("model/ventaVacio.class.php"); 
	
	include_once("model/listar.class.php");
	
	include_once("model/encargado.class.php"); 
	include_once("model/encargadoExistente.class.php"); 
	include_once("model/encargadoVacio.class.php"); 
	
	include_once("model/gastos.class.php"); 
	include_once("model/gastoExistente.class.php"); 
	include_once("model/gastoVacio.class.php"); 
	
	include_once("model/ingreso.class.php");
	include_once("model/ingresoExistente.class.php");
	include_once("model/ingresoVacio.class.php");

	include_once('model/funcionesCliente.php');
	include_once('model/funcionesCompra.php');
	include_once('model/funcionesVentas.php');
	include_once('model/funcionesCotizacion.php');
	include_once('model/funcionesProveedor.php');
	
	include_once('model/funcionesImpuesto.php');
	include_once('model/funcionesInventario.php');
	include_once('model/funcionesPago.php');
	include_once('model/funcionesSucursal.php');
	include_once('model/funcionesUsuario.php');
	
	
	
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
		case "insertarIngreso":					insertarIngreso(); break;
		
		
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
		case "agregarNuevoProducto" :				agregarNuevoProducto(); break;
		
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
		case 'reporteClientesComprasCredito_jgrid': 					reporteClientesComprasCredito_jgrid(); 	break;
		
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
