var Database = function (){
	this.db = openDatabase("pos", "1.0", "Point of Sale", 200000);
	// Crear la esctructura de la base de datos
	this.db.transaction( function(tx) {
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `actualizacion_de_precio` ("
			+ "  `id_actualizacion` int(12) NOT NULL  , "
			+ "  `id_producto` int(11) NOT NULL,"
			+ "  `id_usuario` int(11) NOT NULL,"
			+ "  `precio_venta` float NOT NULL,"
			+ "  `precio_venta_procesado` float NOT NULL,"
			+ "  `precio_intersucursal` float NOT NULL,"
			+ "  `precio_intersucursal_procesado` float NOT NULL,"
			+ "  `precio_compra` float NOT NULL DEFAULT '0' , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
			+ " PRIMARY KEY (`id_actualizacion`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `autorizacion` ("
			+ "  `id_autorizacion` int(11)  NOT NULL ,"
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `fecha_peticion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `fecha_respuesta` timestamp NULL DEFAULT NULL , "
			+ "  `estado` int(11) NOT NULL , "
			+ "  `parametros` varchar(2048) NOT NULL , "
			+ " PRIMARY KEY (`id_autorizacion`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `cliente` ("
			+ "  `id_cliente` int(11) NOT NULL  , "
			+ "  `rfc` varchar(20)  NOT NULL , "
			+ "  `razon_social` varchar(100)  NOT NULL , "
			+ "  `calle` varchar(300)  NOT NULL , "
			+ "  `numero_exterior` varchar(20)  NOT NULL , "
			+ "  `numero_interior` varchar(20)  DEFAULT NULL , "
			+ "  `colonia` varchar(50)  NOT NULL , "
			+ "  `referencia` varchar(100)  DEFAULT NULL , "
			+ "  `localidad` varchar(50)  DEFAULT NULL , "
			+ "  `municipio` varchar(55)  NOT NULL , "
			+ "  `estado` varchar(50)  NOT NULL , "
			+ "  `pais` varchar(50)  NOT NULL , "
			+ "  `codigo_postal` varchar(15)  NOT NULL , "
			+ "  `telefono` varchar(25)  DEFAULT NULL , "
			+ "  `e_mail` varchar(60)  DEFAULT NULL , "
			+ "  `limite_credito` float NOT NULL DEFAULT '0' , "
			+ "  `descuento` float NOT NULL DEFAULT '0' , "
			+ "  `activo` tinyint(2) NOT NULL DEFAULT '1' , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `fecha_ingreso` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `password` varchar(64)  DEFAULT NULL , "
			+ "  `last_login` timestamp NULL DEFAULT NULL,"
			+ "  `grant_changes` tinyint(1) DEFAULT '0' , "
			+ " PRIMARY KEY (`id_cliente`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `compra_cliente` ("
			+ "  `id_compra` int(11) NOT NULL  , "
			+ "  `id_cliente` int(11) NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `subtotal` float DEFAULT NULL , "
			+ "  `impuesto` float DEFAULT '0' , "
			+ "  `descuento` float NOT NULL DEFAULT '0' , "
			+ "  `total` float NOT NULL DEFAULT '0' , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `pagado` float NOT NULL DEFAULT '0' , "
			+ "  `cancelada` tinyint(1) NOT NULL DEFAULT '0' , "
			+ "  `ip` varchar(16)  NOT NULL DEFAULT '0.0.0.0' , "
			+ "  `liquidada` tinyint(1) NOT NULL DEFAULT '0' , "
			+ "  PRIMARY KEY (`id_compra`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `compra_proveedor` ("
			+ "  `id_compra_proveedor` int(11) NOT NULL  , "
			+ "  `peso_origen` float NOT NULL,"
			+ "  `id_proveedor` int(11) NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `fecha_origen` date NOT NULL , "
			+ "  `folio` varchar(11)    DEFAULT NULL , "
			+ "  `numero_de_viaje` varchar(11)    DEFAULT NULL , "
			+ "  `peso_recibido` float NOT NULL , "
			+ "  `arpillas` float NOT NULL , "
			+ "  `peso_por_arpilla` float NOT NULL , "
			+ "  `productor` varchar(64) NOT NULL,"
			+ "  `calidad` tinyint(3)  DEFAULT NULL , "
			+ "  `merma_por_arpilla` float NOT NULL,"
			+ "  `total_origen` float DEFAULT NULL , "
			+ " PRIMARY KEY (`id_compra_proveedor`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `compra_proveedor_flete` ("
			+ "  `id_compra_proveedor` int(11) NOT NULL,"
			+ "  `chofer` varchar(64) NOT NULL,"
			+ "  `marca_camion` varchar(64) DEFAULT NULL,"
			+ "  `placas_camion` varchar(64) NOT NULL,"
			+ "  `modelo_camion` varchar(64) DEFAULT NULL,"
			+ "  `costo_flete` float NOT NULL,"
			+ "  PRIMARY KEY (`id_compra_proveedor`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `compra_proveedor_fragmentacion` ("
			+ "  `id_fragmentacion` int(11) NOT NULL ,"
			+ "  `id_compra_proveedor` int(11) NOT NULL , "
			+ "  `id_producto` int(11) NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `descripcion` varchar(16) NOT NULL , "
			+ "  `cantidad` double NOT NULL DEFAULT '0' , "
			+ "  `procesada` tinyint(1) NOT NULL , "
			+ "  `precio` int(11) NOT NULL , "
			+ "  `descripcion_ref_id` int(11) DEFAULT NULL , "
			+ "  PRIMARY KEY (`id_fragmentacion`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `compra_sucursal` ("
			+ "  `id_compra` int(11) NOT NULL  , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `subtotal` float NOT NULL , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `id_proveedor` int(11) DEFAULT NULL , "
			+ "  `pagado` float NOT NULL DEFAULT '0' , "
			+ "  `liquidado` tinyint(1) NOT NULL DEFAULT '0' , "
			+ "  `total` float NOT NULL,"
			+ " PRIMARY KEY (`id_compra`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `corte` ("
			+ "  `id_corte` int(12) NOT NULL  , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `id_sucursal` int(12) NOT NULL , "
			+ "  `total_ventas` float NOT NULL , "
			+ "  `total_ventas_abonado` float NOT NULL , "
			+ "  `total_ventas_saldo` float NOT NULL , "
			+ "  `total_compras` float NOT NULL , "
			+ "  `total_compras_abonado` float NOT NULL , "
			+ "  `total_gastos` float NOT NULL , "
			+ "  `total_gastos_abonado` float NOT NULL , "
			+ "  `total_ingresos` float NOT NULL , "
			+ "  `total_ganancia_neta` float NOT NULL , "
			+ " PRIMARY KEY (`id_corte`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `detalle_compra_cliente` ("
			+ "  `id_compra` int(11) NOT NULL , "
			+ "  `id_producto` int(11) NOT NULL , "
			+ "  `cantidad` float NOT NULL , "
			+ "  `precio` float NOT NULL , "
			+ "  `descuento` float  DEFAULT '0' , "
			+ " PRIMARY KEY (`id_compra`,`id_producto`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `detalle_compra_proveedor` ("
			+ "  `id_compra_proveedor` int(11) NOT NULL,"
			+ "  `id_producto` int(11) NOT NULL,"
			+ "  `variedad` varchar(64) NOT NULL,"
			+ "  `arpillas` int(11) NOT NULL,"
			+ "  `kg` int(11) NOT NULL,"
			+ "  `precio_por_kg` float NOT NULL,"
			+ " PRIMARY KEY (`id_compra_proveedor`,`id_producto`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `detalle_compra_sucursal` ("
			+ "  `id_detalle_compra_sucursal` int(11) NOT NULL ,"
			+ "  `id_compra` int(11) NOT NULL , "
			+ "  `id_producto` int(11) NOT NULL , "
			+ "  `cantidad` float NOT NULL , "
			+ "  `precio` float NOT NULL , "
			+ "  `descuento` int(11) NOT NULL,"
			+ "  `procesadas` tinyint(1) NOT NULL , "
			+ "  PRIMARY KEY (`id_detalle_compra_sucursal`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `detalle_inventario` ("
			+ "  `id_producto` int(11) NOT NULL , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `precio_venta` float NOT NULL , "
			+ "  `precio_venta_procesado` float NOT NULL DEFAULT '0' , "
			+ "  `existencias` float NOT NULL DEFAULT '0' , "
			+ "  `existencias_procesadas` float NOT NULL , "
			+ "  `precio_compra` float NOT NULL DEFAULT '0' , "
			+ " PRIMARY KEY (`id_producto`,`id_sucursal`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `detalle_venta` ("
			+ "  `id_venta` int(11) NOT NULL , "
			+ "  `id_producto` int(11) NOT NULL , "
			+ "  `cantidad` float NOT NULL , "
			+ "  `cantidad_procesada` float NOT NULL,"
			+ "  `precio` float NOT NULL , "
			+ "  `precio_procesada` float NOT NULL , "
			+ "  `descuento` float  DEFAULT '0' , "
			+ " PRIMARY KEY (`id_venta`,`id_producto`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `documento` ("
			+ "  `id_documento` int(11) NOT NULL  , "
			+ "  `numero_de_impresiones` int(11) NOT NULL , "
			+ "  `identificador` varchar(128)  NOT NULL , "
			+ "  `descripcion` varchar(256)  NOT NULL , "
			+ "  PRIMARY KEY (`id_documento`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `equipo` ("
			+ "  `id_equipo` int(6) NOT NULL  , "
			+ "  `token` varchar(128) DEFAULT NULL , "
			+ "  `full_ua` varchar(256) NOT NULL , "
			+ "  `descripcion` varchar(254) NOT NULL , "
			+ "  `locked` tinyint(1) NOT NULL DEFAULT '0' , "
			+ " PRIMARY KEY (`id_equipo`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `equipo_sucursal` ("
			+ "  `id_equipo` int(6) NOT NULL , "
			+ "  `id_sucursal` int(6) NOT NULL , "
			+ " PRIMARY KEY (`id_equipo`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `factura_compra` ("
			+ "  `folio` varchar(15)  NOT NULL,"
			+ "  `id_compra` int(11) NOT NULL , "
			+ " PRIMARY KEY (`folio`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `factura_venta` ("
			+ "  `id_folio` int(11)  NOT NULL  , "
			+ "  `id_venta` int(11) NOT NULL , "
			+ "  `id_usuario` int(10) NOT NULL , "
			+ "  `xml` text  NOT NULL , "
			+ "  `lugar_emision` int(11) NOT NULL , "
			+ "  `activa` tinyint(1) NOT NULL DEFAULT '1' , "
			+ "  `sellada` tinyint(1) NOT NULL DEFAULT '0' , "
			+ "  `forma_pago` varchar(100)  NOT NULL,"
			+ "  `fecha_emision` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
			+ "  `version_tfd` varchar(10)  NOT NULL,"
			+ "  `folio_fiscal` varchar(128)  NOT NULL,"
			+ "  `fecha_certificacion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
			+ "  `numero_certificado_sat` varchar(128)  NOT NULL,"
			+ "  `sello_digital_emisor` varchar(512)  NOT NULL,"
			+ "  `sello_digital_sat` varchar(512)  NOT NULL,"
			+ "  `cadena_original` varchar(2048)  NOT NULL,"
			+ " PRIMARY KEY (`id_folio`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `gastos` ("
			+ "  `id_gasto` int(11) NOT NULL  , "
			+ "  `folio` varchar(22) NOT NULL , "
			+ "  `concepto` varchar(100) NOT NULL , "
			+ "  `monto` float  NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `fecha_ingreso` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `nota` varchar(512) NOT NULL , "
			+ " PRIMARY KEY (`id_gasto`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `grupos` ("
			+ "  `id_grupo` int(11) NOT NULL,"
			+ "  `nombre` varchar(45) NOT NULL , "
			+ "  `descripcion` varchar(256) NOT NULL,"
			+ "  PRIMARY KEY (`id_grupo`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `grupos_usuarios` ("
			+ "  `id_grupo` int(11) NOT NULL,"
			+ "  `id_usuario` int(11) NOT NULL,"
			+ " PRIMARY KEY (`id_usuario`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `impresiones` ("
			+ "  `id_impresora` int(11) NOT NULL , "
			+ "  `id_documento` int(11) NOT NULL , "
			+ " PRIMARY KEY (`id_impresora`,`id_documento`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `impresora` ("
			+ "  `id_impresora` int(11) NOT NULL  , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `descripcion` varchar(256)  NOT NULL , "
			+ "  `identificador` varchar(128)  NOT NULL , "
			+ " PRIMARY KEY (`id_impresora`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `ingresos` ("
			+ "  `id_ingreso` int(11) NOT NULL  , "
			+ "  `concepto` varchar(100) NOT NULL , "
			+ "  `monto` float NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
			+ "  `fecha_ingreso` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `nota` varchar(512) NOT NULL , "
			+ " PRIMARY KEY (`id_ingreso`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `inventario` ("
			+ "  `id_producto` int(11) NOT NULL  , "
			+ "  `descripcion` varchar(30)  NOT NULL , "
			+ "  `agrupacion` varchar(8)  DEFAULT NULL , "
			+ "  `agrupacionTam` float DEFAULT NULL , "
			+ "  `activo` tinyint(1) NOT NULL DEFAULT '1' , "
			+ "  `precio_por_agrupacion` tinyint(1) NOT NULL DEFAULT '0',"
			+ "  PRIMARY KEY (`id_producto`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `inventario_maestro` ("
			+ "  `id_producto` int(11) NOT NULL,"
			+ "  `id_compra_proveedor` int(11) NOT NULL,"
			+ "  `existencias` float NOT NULL,"
			+ "  `existencias_procesadas` float NOT NULL,"
			+ "  `sitio_descarga` int(11) NOT NULL,"
			+ " PRIMARY KEY (`id_producto`,`id_compra_proveedor`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `pagos_compra` ("
			+ "  `id_pago` int(11) NOT NULL  , "
			+ "  `id_compra` int(11) NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `monto` float NOT NULL , "
			+ " PRIMARY KEY (`id_pago`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `pagos_venta` ("
			+ "  `id_pago` int(11) NOT NULL  , "
			+ "  `id_venta` int(11) NOT NULL , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `monto` float NOT NULL , "
			+ " PRIMARY KEY (`id_pago`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `pago_prestamo_sucursal` ("
			+ "  `id_pago` int(11) NOT NULL  , "
			+ "  `id_prestamo` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `monto` float NOT NULL , "
			+ "  PRIMARY KEY (`id_pago`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `pos_config` ("
			+ "  `opcion` varchar(30)  NOT NULL,"
			+ "  `value` varchar(2048)  NOT NULL,"
			+ " PRIMARY KEY (`opcion`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `prestamo_sucursal` ("
			+ "  `id_prestamo` int(11) NOT NULL  , "
			+ "  `prestamista` int(11) NOT NULL , "
			+ "  `deudor` int(11) NOT NULL , "
			+ "  `monto` float NOT NULL , "
			+ "  `saldo` float NOT NULL , "
			+ "  `liquidado` tinyint(1) NOT NULL DEFAULT '0' , "
			+ "  `concepto` varchar(256) NOT NULL , "
			+ "  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,"
			+ "  PRIMARY KEY (`id_prestamo`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `proveedor` ("
			+ "  `id_proveedor` int(11) NOT NULL  , "
			+ "  `rfc` varchar(20)  DEFAULT NULL , "
			+ "  `nombre` varchar(30)  NOT NULL , "
			+ "  `direccion` varchar(100)  DEFAULT NULL , "
			+ "  `telefono` varchar(20)  DEFAULT NULL , "
			+ "  `e_mail` varchar(60)  DEFAULT NULL , "
			+ "  `activo` tinyint(2) NOT NULL DEFAULT '1' , "
			+ "  PRIMARY KEY (`id_proveedor`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `sucursal` ("
			+ "  `id_sucursal` int(11) NOT NULL  , "
			+ "  `gerente` int(11) DEFAULT NULL , "
			+ "  `descripcion` varchar(100)  NOT NULL , "
			+ "  `razon_social` varchar(100)  NOT NULL , "
			+ "  `rfc` varchar(20)  NOT NULL , "
			+ "  `calle` varchar(50)  NOT NULL , "
			+ "  `numero_exterior` varchar(10)  NOT NULL , "
			+ "  `numero_interior` varchar(10)  DEFAULT NULL , "
			+ "  `colonia` varchar(50)  NOT NULL , "
			+ "  `localidad` varchar(50)  DEFAULT NULL , "
			+ "  `referencia` varchar(200)  DEFAULT NULL , "
			+ "  `municipio` varchar(100)  NOT NULL , "
			+ "  `estado` varchar(50)  NOT NULL , "
			+ "  `pais` varchar(50)  NOT NULL , "
			+ "  `codigo_postal` varchar(15)  NOT NULL , "
			+ "  `telefono` varchar(20)  DEFAULT NULL , "
			+ "  `token` varchar(512)  DEFAULT NULL , "
			+ "  `letras_factura` char(1)  NOT NULL,"
			+ "  `activo` tinyint(1) NOT NULL DEFAULT '1',"
			+ "  `fecha_apertura` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `saldo_a_favor` float NOT NULL DEFAULT '0' , "
			+ " PRIMARY KEY (`id_sucursal`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `usuario` ("
			+ "  `id_usuario` int(11) NOT NULL  , "
			+ "  `RFC` varchar(40)  NOT NULL , "
			+ "  `nombre` varchar(100)  NOT NULL , "
			+ "  `contrasena` varchar(128)  NOT NULL,"
			+ "  `id_sucursal` int(11) DEFAULT NULL , "
			+ "  `activo` tinyint(1) NOT NULL , "
			+ "  `finger_token` varchar(1024)  DEFAULT NULL , "
			+ "  `salario` float NOT NULL , "
			+ "  `direccion` varchar(512)  NOT NULL , "
			+ "  `telefono` varchar(16)  NOT NULL , "
			+ "  `fecha_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
			+ " PRIMARY KEY (`id_usuario`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `ventas` ("
			+ "  `id_venta` int(11) NOT NULL  , "
			+ "  `id_cliente` int(11) NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `subtotal` float DEFAULT NULL , "
			+ "  `iva` float DEFAULT '0' , "
			+ "  `descuento` float NOT NULL DEFAULT '0' , "
			+ "  `total` float NOT NULL DEFAULT '0' , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `pagado` float NOT NULL DEFAULT '0' , "
			+ "  `cancelada` tinyint(1) NOT NULL DEFAULT '0' , "
			+ "  `ip` varchar(16)  NOT NULL DEFAULT '0.0.0.0' , "
			+ "  `liquidada` tinyint(1) NOT NULL DEFAULT '0' , "
			+ " PRIMARY KEY (`id_venta`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    
		}, txFail, txWin);

		this.query = function( query, params, fn ){
			this.db.transaction( function(tx) {
			    tx.executeSql(
			        query,
			        params,
			        fn,
			        sqlFail
			    );
			}, txFail, txWin);
		}
	var txFail = function (err) { console.error("TX failed: " + err.message); }
	var txWin = function(tx){  }	
	var sqlFail = function(err) { console.error("SQL failed: " + err.message, err); }
	var sqlWin = function(tx, response) { /*console.log("SQL succeeded: " + response.rows.length + " rows.");*/ }
};
var db = new Database();
/** Value Object file for table actualizacion_de_precio.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var ActualizacionDePrecio = function ( config )
{
 /**
	* id_actualizacion
	* 
	* id de actualizacion de precio
	* <b>Llave Primaria</b>
	* @access private
	* @var int(12)
	*/
	var _id_actualizacion = config === undefined ? null : config.id_actualizacion || null,

 /**
	* id_producto
	* 
	*  [Campo no documentado]
	* @access private
	* @var int(11)
	*/
	_id_producto = config === undefined ? null : config.id_producto || null,

 /**
	* id_usuario
	* 
	*  [Campo no documentado]
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* precio_venta
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_precio_venta = config === undefined ? null : config.precio_venta || null,

 /**
	* precio_venta_procesado
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_precio_venta_procesado = config === undefined ? null : config.precio_venta_procesado || null,

 /**
	* precio_intersucursal
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_precio_intersucursal = config === undefined ? null : config.precio_intersucursal || null,

 /**
	* precio_intersucursal_procesado
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_precio_intersucursal_procesado = config === undefined ? null : config.precio_intersucursal_procesado || null,

 /**
	* precio_compra
	* 
	* precio al que se le compra al publico este producto en caso de ser POS_COMPRA_A_CLIENTES
	* @access private
	* @var float
	*/
	_precio_compra = config === undefined ? null : config.precio_compra || null,

 /**
	* fecha
	* 
	*  [Campo no documentado]
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null;

	/**
	  * getIdActualizacion
	  * 
	  * Get the <i>id_actualizacion</i> property for this object. Donde <i>id_actualizacion</i> es id de actualizacion de precio
	  * @return int(12)
	  */
	this.getIdActualizacion = function ()
	{
		return _id_actualizacion;
	};

	/**
	  * setIdActualizacion( $id_actualizacion )
	  * 
	  * Set the <i>id_actualizacion</i> property for this object. Donde <i>id_actualizacion</i> es id de actualizacion de precio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_actualizacion</i> es de tipo <i>int(12)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdActualizacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(12)
	  */
	this.setIdActualizacion  = function ( id_actualizacion )
	{
		_id_actualizacion = id_actualizacion;
	};

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getPrecioVenta
	  * 
	  * Get the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getPrecioVenta = function ()
	{
		return _precio_venta;
	};

	/**
	  * setPrecioVenta( $precio_venta )
	  * 
	  * Set the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_venta</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioVenta  = function ( precio_venta )
	{
		_precio_venta = precio_venta;
	};

	/**
	  * getPrecioVentaProcesado
	  * 
	  * Get the <i>precio_venta_procesado</i> property for this object. Donde <i>precio_venta_procesado</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getPrecioVentaProcesado = function ()
	{
		return _precio_venta_procesado;
	};

	/**
	  * setPrecioVentaProcesado( $precio_venta_procesado )
	  * 
	  * Set the <i>precio_venta_procesado</i> property for this object. Donde <i>precio_venta_procesado</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_venta_procesado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioVentaProcesado  = function ( precio_venta_procesado )
	{
		_precio_venta_procesado = precio_venta_procesado;
	};

	/**
	  * getPrecioIntersucursal
	  * 
	  * Get the <i>precio_intersucursal</i> property for this object. Donde <i>precio_intersucursal</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getPrecioIntersucursal = function ()
	{
		return _precio_intersucursal;
	};

	/**
	  * setPrecioIntersucursal( $precio_intersucursal )
	  * 
	  * Set the <i>precio_intersucursal</i> property for this object. Donde <i>precio_intersucursal</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_intersucursal</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioIntersucursal  = function ( precio_intersucursal )
	{
		_precio_intersucursal = precio_intersucursal;
	};

	/**
	  * getPrecioIntersucursalProcesado
	  * 
	  * Get the <i>precio_intersucursal_procesado</i> property for this object. Donde <i>precio_intersucursal_procesado</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getPrecioIntersucursalProcesado = function ()
	{
		return _precio_intersucursal_procesado;
	};

	/**
	  * setPrecioIntersucursalProcesado( $precio_intersucursal_procesado )
	  * 
	  * Set the <i>precio_intersucursal_procesado</i> property for this object. Donde <i>precio_intersucursal_procesado</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_intersucursal_procesado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioIntersucursalProcesado  = function ( precio_intersucursal_procesado )
	{
		_precio_intersucursal_procesado = precio_intersucursal_procesado;
	};

	/**
	  * getPrecioCompra
	  * 
	  * Get the <i>precio_compra</i> property for this object. Donde <i>precio_compra</i> es precio al que se le compra al publico este producto en caso de ser POS_COMPRA_A_CLIENTES
	  * @return float
	  */
	this.getPrecioCompra = function ()
	{
		return _precio_compra;
	};

	/**
	  * setPrecioCompra( $precio_compra )
	  * 
	  * Set the <i>precio_compra</i> property for this object. Donde <i>precio_compra</i> es precio al que se le compra al publico este producto en caso de ser POS_COMPRA_A_CLIENTES.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_compra</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioCompra  = function ( precio_compra )
	{
		_precio_compra = precio_compra;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es  [Campo no documentado]
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link ActualizacionDePrecio} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		ActualizacionDePrecio._callback_stack.push( _original_callback  );
		ActualizacionDePrecio._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		ActualizacionDePrecio.getByPK(  this.getIdActualizacion() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ActualizacionDePrecio} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from actualizacion_de_precio WHERE ("; 
		$val = [];
		if( this.getIdActualizacion() != null){
			$sql += " id_actualizacion = ? AND";
			$val.push( this.getIdActualizacion() );
		}

		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getPrecioVenta() != null){
			$sql += " precio_venta = ? AND";
			$val.push( this.getPrecioVenta() );
		}

		if( this.getPrecioVentaProcesado() != null){
			$sql += " precio_venta_procesado = ? AND";
			$val.push( this.getPrecioVentaProcesado() );
		}

		if( this.getPrecioIntersucursal() != null){
			$sql += " precio_intersucursal = ? AND";
			$val.push( this.getPrecioIntersucursal() );
		}

		if( this.getPrecioIntersucursalProcesado() != null){
			$sql += " precio_intersucursal_procesado = ? AND";
			$val.push( this.getPrecioIntersucursalProcesado() );
		}

		if( this.getPrecioCompra() != null){
			$sql += " precio_compra = ? AND";
			$val.push( this.getPrecioCompra() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new ActualizacionDePrecio($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto ActualizacionDePrecio suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto ActualizacionDePrecio dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio a crear.
	  **/
	var create = function( actualizacion_de_precio )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO actualizacion_de_precio ( id_actualizacion, id_producto, id_usuario, precio_venta, precio_venta_procesado, precio_intersucursal, precio_intersucursal_procesado, precio_compra, fecha ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			actualizacion_de_precio.getIdActualizacion(), 
			actualizacion_de_precio.getIdProducto(), 
			actualizacion_de_precio.getIdUsuario(), 
			actualizacion_de_precio.getPrecioVenta(), 
			actualizacion_de_precio.getPrecioVentaProcesado(), 
			actualizacion_de_precio.getPrecioIntersucursal(), 
			actualizacion_de_precio.getPrecioIntersucursalProcesado(), 
			actualizacion_de_precio.getPrecioCompra(), 
			actualizacion_de_precio.getFecha(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				ActualizacionDePrecio._callback_stack.pop().call(null, actualizacion_de_precio);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio a actualizar.
	  **/
	var update = function( $actualizacion_de_precio )
	{
		$sql = "UPDATE actualizacion_de_precio SET  id_producto = ?, id_usuario = ?, precio_venta = ?, precio_venta_procesado = ?, precio_intersucursal = ?, precio_intersucursal_procesado = ?, precio_compra = ?, fecha = ? WHERE  id_actualizacion = ?;";
		$params = [ 
			$actualizacion_de_precio.getIdProducto(), 
			$actualizacion_de_precio.getIdUsuario(), 
			$actualizacion_de_precio.getPrecioVenta(), 
			$actualizacion_de_precio.getPrecioVentaProcesado(), 
			$actualizacion_de_precio.getPrecioIntersucursal(), 
			$actualizacion_de_precio.getPrecioIntersucursalProcesado(), 
			$actualizacion_de_precio.getPrecioCompra(), 
			$actualizacion_de_precio.getFecha(), 
			$actualizacion_de_precio.getIdActualizacion(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				ActualizacionDePrecio._callback_stack.pop().call(null, $actualizacion_de_precio);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto ActualizacionDePrecio suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( ActualizacionDePrecio.getByPK(this.getIdActualizacion()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM actualizacion_de_precio WHERE  id_actualizacion = ?;";
		$params = [ this.getIdActualizacion() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ActualizacionDePrecio} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link ActualizacionDePrecio}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $actualizacion_de_precio , $orderBy , $orden )
	{
		$sql = "SELECT * from actualizacion_de_precio WHERE ("; 
		$val = [];
		if( (($a = this.getIdActualizacion()) != null) & ( ($b = $actualizacion_de_precio.getIdActualizacion()) != null) ){
				$sql += " id_actualizacion >= ? AND id_actualizacion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_actualizacion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdProducto()) != null) & ( ($b = $actualizacion_de_precio.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $actualizacion_de_precio.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioVenta()) != null) & ( ($b = $actualizacion_de_precio.getPrecioVenta()) != null) ){
				$sql += " precio_venta >= ? AND precio_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioVentaProcesado()) != null) & ( ($b = $actualizacion_de_precio.getPrecioVentaProcesado()) != null) ){
				$sql += " precio_venta_procesado >= ? AND precio_venta_procesado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_venta_procesado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioIntersucursal()) != null) & ( ($b = $actualizacion_de_precio.getPrecioIntersucursal()) != null) ){
				$sql += " precio_intersucursal >= ? AND precio_intersucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_intersucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioIntersucursalProcesado()) != null) & ( ($b = $actualizacion_de_precio.getPrecioIntersucursalProcesado()) != null) ){
				$sql += " precio_intersucursal_procesado >= ? AND precio_intersucursal_procesado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_intersucursal_procesado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioCompra()) != null) & ( ($b = $actualizacion_de_precio.getPrecioCompra()) != null) ){
				$sql += " precio_compra >= ? AND precio_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $actualizacion_de_precio.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new ActualizacionDePrecio($foo));
		//}
		return $sql;
	};


}
	ActualizacionDePrecio._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ActualizacionDePrecio}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	ActualizacionDePrecio.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from actualizacion_de_precio";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new ActualizacionDePrecio( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link ActualizacionDePrecio} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link ActualizacionDePrecio} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link ActualizacionDePrecio Un objeto del tipo {@link ActualizacionDePrecio}. NULL si no hay tal registro.
	  **/
	ActualizacionDePrecio.getByPK = function(  $id_actualizacion, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM actualizacion_de_precio WHERE (id_actualizacion = ? ) LIMIT 1;";
		db.query($sql, [ $id_actualizacion] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new ActualizacionDePrecio(results.rows.item(0)); 
				ActualizacionDePrecio._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : ActualizacionDePrecio._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table autorizacion.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Autorizacion = function ( config )
{
 /**
	* id_autorizacion
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_autorizacion = config === undefined ? null : config.id_autorizacion || null,

 /**
	* id_usuario
	* 
	* Usuario que solicito esta autorizacion
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* id_sucursal
	* 
	* Sucursal de donde proviene esta autorizacion
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* fecha_peticion
	* 
	* Fecha cuando se genero esta autorizacion
	* @access private
	* @var timestamp
	*/
	_fecha_peticion = config === undefined ? null : config.fecha_peticion || null,

 /**
	* fecha_respuesta
	* 
	* Fecha de cuando se resolvio esta aclaracion
	* @access private
	* @var timestamp
	*/
	_fecha_respuesta = config === undefined ? null : config.fecha_respuesta || null,

 /**
	* estado
	* 
	* Estado actual de esta aclaracion
	* @access private
	* @var int(11)
	*/
	_estado = config === undefined ? null : config.estado || null,

 /**
	* parametros
	* 
	* Parametros en formato JSON que describen esta autorizacion
	* @access private
	* @var varchar(2048)
	*/
	_parametros = config === undefined ? null : config.parametros || null,

 /**
	* tipo
	* 
	* El tipo de autorizacion
	* @access private
	* @var enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')
	*/
	_tipo = config === undefined ? null : config.tipo || null;

	/**
	  * getIdAutorizacion
	  * 
	  * Get the <i>id_autorizacion</i> property for this object. Donde <i>id_autorizacion</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdAutorizacion = function ()
	{
		return _id_autorizacion;
	};

	/**
	  * setIdAutorizacion( $id_autorizacion )
	  * 
	  * Set the <i>id_autorizacion</i> property for this object. Donde <i>id_autorizacion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_autorizacion</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdAutorizacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdAutorizacion  = function ( id_autorizacion )
	{
		_id_autorizacion = id_autorizacion;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Usuario que solicito esta autorizacion
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Usuario que solicito esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Sucursal de donde proviene esta autorizacion
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Sucursal de donde proviene esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getFechaPeticion
	  * 
	  * Get the <i>fecha_peticion</i> property for this object. Donde <i>fecha_peticion</i> es Fecha cuando se genero esta autorizacion
	  * @return timestamp
	  */
	this.getFechaPeticion = function ()
	{
		return _fecha_peticion;
	};

	/**
	  * setFechaPeticion( $fecha_peticion )
	  * 
	  * Set the <i>fecha_peticion</i> property for this object. Donde <i>fecha_peticion</i> es Fecha cuando se genero esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_peticion</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaPeticion  = function ( fecha_peticion )
	{
		_fecha_peticion = fecha_peticion;
	};

	/**
	  * getFechaRespuesta
	  * 
	  * Get the <i>fecha_respuesta</i> property for this object. Donde <i>fecha_respuesta</i> es Fecha de cuando se resolvio esta aclaracion
	  * @return timestamp
	  */
	this.getFechaRespuesta = function ()
	{
		return _fecha_respuesta;
	};

	/**
	  * setFechaRespuesta( $fecha_respuesta )
	  * 
	  * Set the <i>fecha_respuesta</i> property for this object. Donde <i>fecha_respuesta</i> es Fecha de cuando se resolvio esta aclaracion.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_respuesta</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaRespuesta  = function ( fecha_respuesta )
	{
		_fecha_respuesta = fecha_respuesta;
	};

	/**
	  * getEstado
	  * 
	  * Get the <i>estado</i> property for this object. Donde <i>estado</i> es Estado actual de esta aclaracion
	  * @return int(11)
	  */
	this.getEstado = function ()
	{
		return _estado;
	};

	/**
	  * setEstado( $estado )
	  * 
	  * Set the <i>estado</i> property for this object. Donde <i>estado</i> es Estado actual de esta aclaracion.
	  * Una validacion basica se hara aqui para comprobar que <i>estado</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setEstado  = function ( estado )
	{
		_estado = estado;
	};

	/**
	  * getParametros
	  * 
	  * Get the <i>parametros</i> property for this object. Donde <i>parametros</i> es Parametros en formato JSON que describen esta autorizacion
	  * @return varchar(2048)
	  */
	this.getParametros = function ()
	{
		return _parametros;
	};

	/**
	  * setParametros( $parametros )
	  * 
	  * Set the <i>parametros</i> property for this object. Donde <i>parametros</i> es Parametros en formato JSON que describen esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>parametros</i> es de tipo <i>varchar(2048)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(2048)
	  */
	this.setParametros  = function ( parametros )
	{
		_parametros = parametros;
	};

	/**
	  * getTipo
	  * 
	  * Get the <i>tipo</i> property for this object. Donde <i>tipo</i> es El tipo de autorizacion
	  * @return enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')
	  */
	this.getTipo = function ()
	{
		return _tipo;
	};

	/**
	  * setTipo( $tipo )
	  * 
	  * Set the <i>tipo</i> property for this object. Donde <i>tipo</i> es El tipo de autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo</i> es de tipo <i>enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')
	  */
	this.setTipo  = function ( tipo )
	{
		_tipo = tipo;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Autorizacion} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Autorizacion._callback_stack.push( _original_callback  );
		Autorizacion._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Autorizacion.getByPK(  this.getIdAutorizacion() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Autorizacion} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Autorizacion [$autorizacion] El objeto de tipo Autorizacion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from autorizacion WHERE ("; 
		$val = [];
		if( this.getIdAutorizacion() != null){
			$sql += " id_autorizacion = ? AND";
			$val.push( this.getIdAutorizacion() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getFechaPeticion() != null){
			$sql += " fecha_peticion = ? AND";
			$val.push( this.getFechaPeticion() );
		}

		if( this.getFechaRespuesta() != null){
			$sql += " fecha_respuesta = ? AND";
			$val.push( this.getFechaRespuesta() );
		}

		if( this.getEstado() != null){
			$sql += " estado = ? AND";
			$val.push( this.getEstado() );
		}

		if( this.getParametros() != null){
			$sql += " parametros = ? AND";
			$val.push( this.getParametros() );
		}

		if( this.getTipo() != null){
			$sql += " tipo = ? AND";
			$val.push( this.getTipo() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Autorizacion($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Autorizacion suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Autorizacion dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Autorizacion [$autorizacion] El objeto de tipo Autorizacion a crear.
	  **/
	var create = function( autorizacion )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO autorizacion ( id_autorizacion, id_usuario, id_sucursal, fecha_peticion, fecha_respuesta, estado, parametros, tipo ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			autorizacion.getIdAutorizacion(), 
			autorizacion.getIdUsuario(), 
			autorizacion.getIdSucursal(), 
			autorizacion.getFechaPeticion(), 
			autorizacion.getFechaRespuesta(), 
			autorizacion.getEstado(), 
			autorizacion.getParametros(), 
			autorizacion.getTipo(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Autorizacion._callback_stack.pop().call(null, autorizacion);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Autorizacion [$autorizacion] El objeto de tipo Autorizacion a actualizar.
	  **/
	var update = function( $autorizacion )
	{
		$sql = "UPDATE autorizacion SET  id_usuario = ?, id_sucursal = ?, fecha_peticion = ?, fecha_respuesta = ?, estado = ?, parametros = ?, tipo = ? WHERE  id_autorizacion = ?;";
		$params = [ 
			$autorizacion.getIdUsuario(), 
			$autorizacion.getIdSucursal(), 
			$autorizacion.getFechaPeticion(), 
			$autorizacion.getFechaRespuesta(), 
			$autorizacion.getEstado(), 
			$autorizacion.getParametros(), 
			$autorizacion.getTipo(), 
			$autorizacion.getIdAutorizacion(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Autorizacion._callback_stack.pop().call(null, $autorizacion);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Autorizacion suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Autorizacion.getByPK(this.getIdAutorizacion()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM autorizacion WHERE  id_autorizacion = ?;";
		$params = [ this.getIdAutorizacion() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Autorizacion} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Autorizacion}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Autorizacion [$autorizacion] El objeto de tipo Autorizacion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $autorizacion , $orderBy , $orden )
	{
		$sql = "SELECT * from autorizacion WHERE ("; 
		$val = [];
		if( (($a = this.getIdAutorizacion()) != null) & ( ($b = $autorizacion.getIdAutorizacion()) != null) ){
				$sql += " id_autorizacion >= ? AND id_autorizacion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_autorizacion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $autorizacion.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $autorizacion.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaPeticion()) != null) & ( ($b = $autorizacion.getFechaPeticion()) != null) ){
				$sql += " fecha_peticion >= ? AND fecha_peticion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_peticion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaRespuesta()) != null) & ( ($b = $autorizacion.getFechaRespuesta()) != null) ){
				$sql += " fecha_respuesta >= ? AND fecha_respuesta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_respuesta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getEstado()) != null) & ( ($b = $autorizacion.getEstado()) != null) ){
				$sql += " estado >= ? AND estado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " estado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getParametros()) != null) & ( ($b = $autorizacion.getParametros()) != null) ){
				$sql += " parametros >= ? AND parametros <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " parametros = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipo()) != null) & ( ($b = $autorizacion.getTipo()) != null) ){
				$sql += " tipo >= ? AND tipo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Autorizacion($foo));
		//}
		return $sql;
	};


}
	Autorizacion._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Autorizacion}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Autorizacion.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from autorizacion";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Autorizacion( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Autorizacion} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Autorizacion} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Autorizacion Un objeto del tipo {@link Autorizacion}. NULL si no hay tal registro.
	  **/
	Autorizacion.getByPK = function(  $id_autorizacion, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM autorizacion WHERE (id_autorizacion = ? ) LIMIT 1;";
		db.query($sql, [ $id_autorizacion] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Autorizacion(results.rows.item(0)); 
				Autorizacion._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Autorizacion._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Cliente = function ( config )
{
 /**
	* id_cliente
	* 
	* identificador del cliente
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_cliente = config === undefined ? null : config.id_cliente || null,

 /**
	* rfc
	* 
	* rfc del cliente si es que tiene
	* @access private
	* @var varchar(20)
	*/
	_rfc = config === undefined ? null : config.rfc || null,

 /**
	* razon_social
	* 
	* razon social del cliente
	* @access private
	* @var varchar(100)
	*/
	_razon_social = config === undefined ? null : config.razon_social || null,

 /**
	* calle
	* 
	* calle del domicilio fiscal del cliente
	* @access private
	* @var varchar(300)
	*/
	_calle = config === undefined ? null : config.calle || null,

 /**
	* numero_exterior
	* 
	* numero exteriror del domicilio fiscal del cliente
	* @access private
	* @var varchar(20)
	*/
	_numero_exterior = config === undefined ? null : config.numero_exterior || null,

 /**
	* numero_interior
	* 
	* numero interior del domicilio fiscal del cliente
	* @access private
	* @var varchar(20)
	*/
	_numero_interior = config === undefined ? null : config.numero_interior || null,

 /**
	* colonia
	* 
	* colonia del domicilio fiscal del cliente
	* @access private
	* @var varchar(50)
	*/
	_colonia = config === undefined ? null : config.colonia || null,

 /**
	* referencia
	* 
	* referencia del domicilio fiscal del cliente
	* @access private
	* @var varchar(100)
	*/
	_referencia = config === undefined ? null : config.referencia || null,

 /**
	* localidad
	* 
	* Localidad del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_localidad = config === undefined ? null : config.localidad || null,

 /**
	* municipio
	* 
	* Municipio de este cliente
	* @access private
	* @var varchar(55)
	*/
	_municipio = config === undefined ? null : config.municipio || null,

 /**
	* estado
	* 
	* Estado del domicilio fiscal del cliente
	* @access private
	* @var varchar(50)
	*/
	_estado = config === undefined ? null : config.estado || null,

 /**
	* pais
	* 
	* Pais del domicilio fiscal del cliente
	* @access private
	* @var varchar(50)
	*/
	_pais = config === undefined ? null : config.pais || null,

 /**
	* codigo_postal
	* 
	* Codigo postal del domicilio fiscal del cliente
	* @access private
	* @var varchar(15)
	*/
	_codigo_postal = config === undefined ? null : config.codigo_postal || null,

 /**
	* telefono
	* 
	* Telefono del cliete
	* @access private
	* @var varchar(25)
	*/
	_telefono = config === undefined ? null : config.telefono || null,

 /**
	* e_mail
	* 
	* dias de credito para que pague el cliente
	* @access private
	* @var varchar(60)
	*/
	_e_mail = config === undefined ? null : config.e_mail || null,

 /**
	* limite_credito
	* 
	* Limite de credito otorgado al cliente
	* @access private
	* @var float
	*/
	_limite_credito = config === undefined ? null : config.limite_credito || null,

 /**
	* descuento
	* 
	* Taza porcentual de descuento de 0.0 a 100.0
	* @access private
	* @var float
	*/
	_descuento = config === undefined ? null : config.descuento || null,

 /**
	* activo
	* 
	* Indica si la cuenta esta activada o desactivada
	* @access private
	* @var tinyint(2)
	*/
	_activo = config === undefined ? null : config.activo || null,

 /**
	* id_usuario
	* 
	* Identificador del usuario que dio de alta a este cliente
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* id_sucursal
	* 
	* Identificador de la sucursal donde se dio de alta este cliente
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* fecha_ingreso
	* 
	* Fecha cuando este cliente se registro en una sucursal
	* @access private
	* @var timestamp
	*/
	_fecha_ingreso = config === undefined ? null : config.fecha_ingreso || null,

 /**
	* password
	* 
	* el pass para que este cliente entre a descargar sus facturas
	* @access private
	* @var varchar(64)
	*/
	_password = config === undefined ? null : config.password || null,

 /**
	* last_login
	* 
	*  [Campo no documentado]
	* @access private
	* @var timestamp
	*/
	_last_login = config === undefined ? null : config.last_login || null,

 /**
	* grant_changes
	* 
	* verdadero cuando el cliente ha cambiado su contrasena y puede hacer cosas
	* @access private
	* @var tinyint(1)
	*/
	_grant_changes = config === undefined ? null : config.grant_changes || null;

	/**
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es identificador del cliente
	  * @return int(11)
	  */
	this.getIdCliente = function ()
	{
		return _id_cliente;
	};

	/**
	  * setIdCliente( $id_cliente )
	  * 
	  * Set the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es identificador del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cliente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCliente( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdCliente  = function ( id_cliente )
	{
		_id_cliente = id_cliente;
	};

	/**
	  * getRfc
	  * 
	  * Get the <i>rfc</i> property for this object. Donde <i>rfc</i> es rfc del cliente si es que tiene
	  * @return varchar(20)
	  */
	this.getRfc = function ()
	{
		return _rfc;
	};

	/**
	  * setRfc( $rfc )
	  * 
	  * Set the <i>rfc</i> property for this object. Donde <i>rfc</i> es rfc del cliente si es que tiene.
	  * Una validacion basica se hara aqui para comprobar que <i>rfc</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setRfc  = function ( rfc )
	{
		_rfc = rfc;
	};

	/**
	  * getRazonSocial
	  * 
	  * Get the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social del cliente
	  * @return varchar(100)
	  */
	this.getRazonSocial = function ()
	{
		return _razon_social;
	};

	/**
	  * setRazonSocial( $razon_social )
	  * 
	  * Set the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>razon_social</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setRazonSocial  = function ( razon_social )
	{
		_razon_social = razon_social;
	};

	/**
	  * getCalle
	  * 
	  * Get the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal del cliente
	  * @return varchar(300)
	  */
	this.getCalle = function ()
	{
		return _calle;
	};

	/**
	  * setCalle( $calle )
	  * 
	  * Set the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>calle</i> es de tipo <i>varchar(300)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(300)
	  */
	this.setCalle  = function ( calle )
	{
		_calle = calle;
	};

	/**
	  * getNumeroExterior
	  * 
	  * Get the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es numero exteriror del domicilio fiscal del cliente
	  * @return varchar(20)
	  */
	this.getNumeroExterior = function ()
	{
		return _numero_exterior;
	};

	/**
	  * setNumeroExterior( $numero_exterior )
	  * 
	  * Set the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es numero exteriror del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_exterior</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setNumeroExterior  = function ( numero_exterior )
	{
		_numero_exterior = numero_exterior;
	};

	/**
	  * getNumeroInterior
	  * 
	  * Get the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal del cliente
	  * @return varchar(20)
	  */
	this.getNumeroInterior = function ()
	{
		return _numero_interior;
	};

	/**
	  * setNumeroInterior( $numero_interior )
	  * 
	  * Set the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_interior</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setNumeroInterior  = function ( numero_interior )
	{
		_numero_interior = numero_interior;
	};

	/**
	  * getColonia
	  * 
	  * Get the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal del cliente
	  * @return varchar(50)
	  */
	this.getColonia = function ()
	{
		return _colonia;
	};

	/**
	  * setColonia( $colonia )
	  * 
	  * Set the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>colonia</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setColonia  = function ( colonia )
	{
		_colonia = colonia;
	};

	/**
	  * getReferencia
	  * 
	  * Get the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal del cliente
	  * @return varchar(100)
	  */
	this.getReferencia = function ()
	{
		return _referencia;
	};

	/**
	  * setReferencia( $referencia )
	  * 
	  * Set the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>referencia</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setReferencia  = function ( referencia )
	{
		_referencia = referencia;
	};

	/**
	  * getLocalidad
	  * 
	  * Get the <i>localidad</i> property for this object. Donde <i>localidad</i> es Localidad del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getLocalidad = function ()
	{
		return _localidad;
	};

	/**
	  * setLocalidad( $localidad )
	  * 
	  * Set the <i>localidad</i> property for this object. Donde <i>localidad</i> es Localidad del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>localidad</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setLocalidad  = function ( localidad )
	{
		_localidad = localidad;
	};

	/**
	  * getMunicipio
	  * 
	  * Get the <i>municipio</i> property for this object. Donde <i>municipio</i> es Municipio de este cliente
	  * @return varchar(55)
	  */
	this.getMunicipio = function ()
	{
		return _municipio;
	};

	/**
	  * setMunicipio( $municipio )
	  * 
	  * Set the <i>municipio</i> property for this object. Donde <i>municipio</i> es Municipio de este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>municipio</i> es de tipo <i>varchar(55)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(55)
	  */
	this.setMunicipio  = function ( municipio )
	{
		_municipio = municipio;
	};

	/**
	  * getEstado
	  * 
	  * Get the <i>estado</i> property for this object. Donde <i>estado</i> es Estado del domicilio fiscal del cliente
	  * @return varchar(50)
	  */
	this.getEstado = function ()
	{
		return _estado;
	};

	/**
	  * setEstado( $estado )
	  * 
	  * Set the <i>estado</i> property for this object. Donde <i>estado</i> es Estado del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>estado</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setEstado  = function ( estado )
	{
		_estado = estado;
	};

	/**
	  * getPais
	  * 
	  * Get the <i>pais</i> property for this object. Donde <i>pais</i> es Pais del domicilio fiscal del cliente
	  * @return varchar(50)
	  */
	this.getPais = function ()
	{
		return _pais;
	};

	/**
	  * setPais( $pais )
	  * 
	  * Set the <i>pais</i> property for this object. Donde <i>pais</i> es Pais del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>pais</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setPais  = function ( pais )
	{
		_pais = pais;
	};

	/**
	  * getCodigoPostal
	  * 
	  * Get the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es Codigo postal del domicilio fiscal del cliente
	  * @return varchar(15)
	  */
	this.getCodigoPostal = function ()
	{
		return _codigo_postal;
	};

	/**
	  * setCodigoPostal( $codigo_postal )
	  * 
	  * Set the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es Codigo postal del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>codigo_postal</i> es de tipo <i>varchar(15)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(15)
	  */
	this.setCodigoPostal  = function ( codigo_postal )
	{
		_codigo_postal = codigo_postal;
	};

	/**
	  * getTelefono
	  * 
	  * Get the <i>telefono</i> property for this object. Donde <i>telefono</i> es Telefono del cliete
	  * @return varchar(25)
	  */
	this.getTelefono = function ()
	{
		return _telefono;
	};

	/**
	  * setTelefono( $telefono )
	  * 
	  * Set the <i>telefono</i> property for this object. Donde <i>telefono</i> es Telefono del cliete.
	  * Una validacion basica se hara aqui para comprobar que <i>telefono</i> es de tipo <i>varchar(25)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(25)
	  */
	this.setTelefono  = function ( telefono )
	{
		_telefono = telefono;
	};

	/**
	  * getEMail
	  * 
	  * Get the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es dias de credito para que pague el cliente
	  * @return varchar(60)
	  */
	this.getEMail = function ()
	{
		return _e_mail;
	};

	/**
	  * setEMail( $e_mail )
	  * 
	  * Set the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es dias de credito para que pague el cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>e_mail</i> es de tipo <i>varchar(60)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(60)
	  */
	this.setEMail  = function ( e_mail )
	{
		_e_mail = e_mail;
	};

	/**
	  * getLimiteCredito
	  * 
	  * Get the <i>limite_credito</i> property for this object. Donde <i>limite_credito</i> es Limite de credito otorgado al cliente
	  * @return float
	  */
	this.getLimiteCredito = function ()
	{
		return _limite_credito;
	};

	/**
	  * setLimiteCredito( $limite_credito )
	  * 
	  * Set the <i>limite_credito</i> property for this object. Donde <i>limite_credito</i> es Limite de credito otorgado al cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>limite_credito</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setLimiteCredito  = function ( limite_credito )
	{
		_limite_credito = limite_credito;
	};

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es Taza porcentual de descuento de 0.0 a 100.0
	  * @return float
	  */
	this.getDescuento = function ()
	{
		return _descuento;
	};

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es Taza porcentual de descuento de 0.0 a 100.0.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setDescuento  = function ( descuento )
	{
		_descuento = descuento;
	};

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es Indica si la cuenta esta activada o desactivada
	  * @return tinyint(2)
	  */
	this.getActivo = function ()
	{
		return _activo;
	};

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es Indica si la cuenta esta activada o desactivada.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(2)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(2)
	  */
	this.setActivo  = function ( activo )
	{
		_activo = activo;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Identificador del usuario que dio de alta a este cliente
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Identificador del usuario que dio de alta a este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de la sucursal donde se dio de alta este cliente
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de la sucursal donde se dio de alta este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getFechaIngreso
	  * 
	  * Get the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha cuando este cliente se registro en una sucursal
	  * @return timestamp
	  */
	this.getFechaIngreso = function ()
	{
		return _fecha_ingreso;
	};

	/**
	  * setFechaIngreso( $fecha_ingreso )
	  * 
	  * Set the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha cuando este cliente se registro en una sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_ingreso</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaIngreso  = function ( fecha_ingreso )
	{
		_fecha_ingreso = fecha_ingreso;
	};

	/**
	  * getPassword
	  * 
	  * Get the <i>password</i> property for this object. Donde <i>password</i> es el pass para que este cliente entre a descargar sus facturas
	  * @return varchar(64)
	  */
	this.getPassword = function ()
	{
		return _password;
	};

	/**
	  * setPassword( $password )
	  * 
	  * Set the <i>password</i> property for this object. Donde <i>password</i> es el pass para que este cliente entre a descargar sus facturas.
	  * Una validacion basica se hara aqui para comprobar que <i>password</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	this.setPassword  = function ( password )
	{
		_password = password;
	};

	/**
	  * getLastLogin
	  * 
	  * Get the <i>last_login</i> property for this object. Donde <i>last_login</i> es  [Campo no documentado]
	  * @return timestamp
	  */
	this.getLastLogin = function ()
	{
		return _last_login;
	};

	/**
	  * setLastLogin( $last_login )
	  * 
	  * Set the <i>last_login</i> property for this object. Donde <i>last_login</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>last_login</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setLastLogin  = function ( last_login )
	{
		_last_login = last_login;
	};

	/**
	  * getGrantChanges
	  * 
	  * Get the <i>grant_changes</i> property for this object. Donde <i>grant_changes</i> es verdadero cuando el cliente ha cambiado su contrasena y puede hacer cosas
	  * @return tinyint(1)
	  */
	this.getGrantChanges = function ()
	{
		return _grant_changes;
	};

	/**
	  * setGrantChanges( $grant_changes )
	  * 
	  * Set the <i>grant_changes</i> property for this object. Donde <i>grant_changes</i> es verdadero cuando el cliente ha cambiado su contrasena y puede hacer cosas.
	  * Una validacion basica se hara aqui para comprobar que <i>grant_changes</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setGrantChanges  = function ( grant_changes )
	{
		_grant_changes = grant_changes;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Cliente} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Cliente._callback_stack.push( _original_callback  );
		Cliente._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Cliente.getByPK(  this.getIdCliente() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Cliente} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Cliente [$cliente] El objeto de tipo Cliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = [];
		if( this.getIdCliente() != null){
			$sql += " id_cliente = ? AND";
			$val.push( this.getIdCliente() );
		}

		if( this.getRfc() != null){
			$sql += " rfc = ? AND";
			$val.push( this.getRfc() );
		}

		if( this.getRazonSocial() != null){
			$sql += " razon_social = ? AND";
			$val.push( this.getRazonSocial() );
		}

		if( this.getCalle() != null){
			$sql += " calle = ? AND";
			$val.push( this.getCalle() );
		}

		if( this.getNumeroExterior() != null){
			$sql += " numero_exterior = ? AND";
			$val.push( this.getNumeroExterior() );
		}

		if( this.getNumeroInterior() != null){
			$sql += " numero_interior = ? AND";
			$val.push( this.getNumeroInterior() );
		}

		if( this.getColonia() != null){
			$sql += " colonia = ? AND";
			$val.push( this.getColonia() );
		}

		if( this.getReferencia() != null){
			$sql += " referencia = ? AND";
			$val.push( this.getReferencia() );
		}

		if( this.getLocalidad() != null){
			$sql += " localidad = ? AND";
			$val.push( this.getLocalidad() );
		}

		if( this.getMunicipio() != null){
			$sql += " municipio = ? AND";
			$val.push( this.getMunicipio() );
		}

		if( this.getEstado() != null){
			$sql += " estado = ? AND";
			$val.push( this.getEstado() );
		}

		if( this.getPais() != null){
			$sql += " pais = ? AND";
			$val.push( this.getPais() );
		}

		if( this.getCodigoPostal() != null){
			$sql += " codigo_postal = ? AND";
			$val.push( this.getCodigoPostal() );
		}

		if( this.getTelefono() != null){
			$sql += " telefono = ? AND";
			$val.push( this.getTelefono() );
		}

		if( this.getEMail() != null){
			$sql += " e_mail = ? AND";
			$val.push( this.getEMail() );
		}

		if( this.getLimiteCredito() != null){
			$sql += " limite_credito = ? AND";
			$val.push( this.getLimiteCredito() );
		}

		if( this.getDescuento() != null){
			$sql += " descuento = ? AND";
			$val.push( this.getDescuento() );
		}

		if( this.getActivo() != null){
			$sql += " activo = ? AND";
			$val.push( this.getActivo() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getFechaIngreso() != null){
			$sql += " fecha_ingreso = ? AND";
			$val.push( this.getFechaIngreso() );
		}

		if( this.getPassword() != null){
			$sql += " password = ? AND";
			$val.push( this.getPassword() );
		}

		if( this.getLastLogin() != null){
			$sql += " last_login = ? AND";
			$val.push( this.getLastLogin() );
		}

		if( this.getGrantChanges() != null){
			$sql += " grant_changes = ? AND";
			$val.push( this.getGrantChanges() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Cliente($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Cliente suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Cliente dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Cliente [$cliente] El objeto de tipo Cliente a crear.
	  **/
	var create = function( cliente )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO cliente ( id_cliente, rfc, razon_social, calle, numero_exterior, numero_interior, colonia, referencia, localidad, municipio, estado, pais, codigo_postal, telefono, e_mail, limite_credito, descuento, activo, id_usuario, id_sucursal, fecha_ingreso, password, last_login, grant_changes ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			cliente.getIdCliente(), 
			cliente.getRfc(), 
			cliente.getRazonSocial(), 
			cliente.getCalle(), 
			cliente.getNumeroExterior(), 
			cliente.getNumeroInterior(), 
			cliente.getColonia(), 
			cliente.getReferencia(), 
			cliente.getLocalidad(), 
			cliente.getMunicipio(), 
			cliente.getEstado(), 
			cliente.getPais(), 
			cliente.getCodigoPostal(), 
			cliente.getTelefono(), 
			cliente.getEMail(), 
			cliente.getLimiteCredito(), 
			cliente.getDescuento(), 
			cliente.getActivo(), 
			cliente.getIdUsuario(), 
			cliente.getIdSucursal(), 
			cliente.getFechaIngreso(), 
			cliente.getPassword(), 
			cliente.getLastLogin(), 
			cliente.getGrantChanges(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Cliente._callback_stack.pop().call(null, cliente);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Cliente [$cliente] El objeto de tipo Cliente a actualizar.
	  **/
	var update = function( $cliente )
	{
		$sql = "UPDATE cliente SET  rfc = ?, razon_social = ?, calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?, referencia = ?, localidad = ?, municipio = ?, estado = ?, pais = ?, codigo_postal = ?, telefono = ?, e_mail = ?, limite_credito = ?, descuento = ?, activo = ?, id_usuario = ?, id_sucursal = ?, fecha_ingreso = ?, password = ?, last_login = ?, grant_changes = ? WHERE  id_cliente = ?;";
		$params = [ 
			$cliente.getRfc(), 
			$cliente.getRazonSocial(), 
			$cliente.getCalle(), 
			$cliente.getNumeroExterior(), 
			$cliente.getNumeroInterior(), 
			$cliente.getColonia(), 
			$cliente.getReferencia(), 
			$cliente.getLocalidad(), 
			$cliente.getMunicipio(), 
			$cliente.getEstado(), 
			$cliente.getPais(), 
			$cliente.getCodigoPostal(), 
			$cliente.getTelefono(), 
			$cliente.getEMail(), 
			$cliente.getLimiteCredito(), 
			$cliente.getDescuento(), 
			$cliente.getActivo(), 
			$cliente.getIdUsuario(), 
			$cliente.getIdSucursal(), 
			$cliente.getFechaIngreso(), 
			$cliente.getPassword(), 
			$cliente.getLastLogin(), 
			$cliente.getGrantChanges(), 
			$cliente.getIdCliente(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Cliente._callback_stack.pop().call(null, $cliente);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Cliente suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Cliente.getByPK(this.getIdCliente()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM cliente WHERE  id_cliente = ?;";
		$params = [ this.getIdCliente() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Cliente} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Cliente}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Cliente [$cliente] El objeto de tipo Cliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $cliente , $orderBy , $orden )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = [];
		if( (($a = this.getIdCliente()) != null) & ( ($b = $cliente.getIdCliente()) != null) ){
				$sql += " id_cliente >= ? AND id_cliente <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_cliente = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getRfc()) != null) & ( ($b = $cliente.getRfc()) != null) ){
				$sql += " rfc >= ? AND rfc <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " rfc = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getRazonSocial()) != null) & ( ($b = $cliente.getRazonSocial()) != null) ){
				$sql += " razon_social >= ? AND razon_social <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " razon_social = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCalle()) != null) & ( ($b = $cliente.getCalle()) != null) ){
				$sql += " calle >= ? AND calle <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " calle = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroExterior()) != null) & ( ($b = $cliente.getNumeroExterior()) != null) ){
				$sql += " numero_exterior >= ? AND numero_exterior <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_exterior = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroInterior()) != null) & ( ($b = $cliente.getNumeroInterior()) != null) ){
				$sql += " numero_interior >= ? AND numero_interior <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_interior = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getColonia()) != null) & ( ($b = $cliente.getColonia()) != null) ){
				$sql += " colonia >= ? AND colonia <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " colonia = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getReferencia()) != null) & ( ($b = $cliente.getReferencia()) != null) ){
				$sql += " referencia >= ? AND referencia <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " referencia = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLocalidad()) != null) & ( ($b = $cliente.getLocalidad()) != null) ){
				$sql += " localidad >= ? AND localidad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " localidad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMunicipio()) != null) & ( ($b = $cliente.getMunicipio()) != null) ){
				$sql += " municipio >= ? AND municipio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " municipio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getEstado()) != null) & ( ($b = $cliente.getEstado()) != null) ){
				$sql += " estado >= ? AND estado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " estado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPais()) != null) & ( ($b = $cliente.getPais()) != null) ){
				$sql += " pais >= ? AND pais <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " pais = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCodigoPostal()) != null) & ( ($b = $cliente.getCodigoPostal()) != null) ){
				$sql += " codigo_postal >= ? AND codigo_postal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " codigo_postal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTelefono()) != null) & ( ($b = $cliente.getTelefono()) != null) ){
				$sql += " telefono >= ? AND telefono <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " telefono = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getEMail()) != null) & ( ($b = $cliente.getEMail()) != null) ){
				$sql += " e_mail >= ? AND e_mail <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " e_mail = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLimiteCredito()) != null) & ( ($b = $cliente.getLimiteCredito()) != null) ){
				$sql += " limite_credito >= ? AND limite_credito <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " limite_credito = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescuento()) != null) & ( ($b = $cliente.getDescuento()) != null) ){
				$sql += " descuento >= ? AND descuento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descuento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getActivo()) != null) & ( ($b = $cliente.getActivo()) != null) ){
				$sql += " activo >= ? AND activo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " activo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $cliente.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $cliente.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaIngreso()) != null) & ( ($b = $cliente.getFechaIngreso()) != null) ){
				$sql += " fecha_ingreso >= ? AND fecha_ingreso <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_ingreso = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPassword()) != null) & ( ($b = $cliente.getPassword()) != null) ){
				$sql += " password >= ? AND password <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " password = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLastLogin()) != null) & ( ($b = $cliente.getLastLogin()) != null) ){
				$sql += " last_login >= ? AND last_login <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " last_login = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getGrantChanges()) != null) & ( ($b = $cliente.getGrantChanges()) != null) ){
				$sql += " grant_changes >= ? AND grant_changes <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " grant_changes = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Cliente($foo));
		//}
		return $sql;
	};


}
	Cliente._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Cliente}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Cliente.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from cliente";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Cliente( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Cliente} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Cliente} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Cliente Un objeto del tipo {@link Cliente}. NULL si no hay tal registro.
	  **/
	Cliente.getByPK = function(  $id_cliente, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM cliente WHERE (id_cliente = ? ) LIMIT 1;";
		db.query($sql, [ $id_cliente] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Cliente(results.rows.item(0)); 
				Cliente._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Cliente._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table compra_cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var CompraCliente = function ( config )
{
 /**
	* id_compra
	* 
	* id de compra
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_compra = config === undefined ? null : config.id_compra || null,

 /**
	* id_cliente
	* 
	* cliente al que se le compro
	* @access private
	* @var int(11)
	*/
	_id_cliente = config === undefined ? null : config.id_cliente || null,

 /**
	* tipo_compra
	* 
	* tipo de compra, contado o credito
	* @access private
	* @var enum('credito','contado')
	*/
	_tipo_compra = config === undefined ? null : config.tipo_compra || null,

 /**
	* tipo_pago
	* 
	* tipo de pago para esta compra en caso de ser a contado
	* @access private
	* @var enum('efectivo','cheque','tarjeta')
	*/
	_tipo_pago = config === undefined ? null : config.tipo_pago || null,

 /**
	* fecha
	* 
	* fecha de compra
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null,

 /**
	* subtotal
	* 
	* subtotal de la compra, puede ser nulo
	* @access private
	* @var float
	*/
	_subtotal = config === undefined ? null : config.subtotal || null,

 /**
	* impuesto
	* 
	* impuesto agregado por la compra, depende de cada sucursal
	* @access private
	* @var float
	*/
	_impuesto = config === undefined ? null : config.impuesto || null,

 /**
	* descuento
	* 
	* descuento aplicado a esta compra
	* @access private
	* @var float
	*/
	_descuento = config === undefined ? null : config.descuento || null,

 /**
	* total
	* 
	* total de esta compra
	* @access private
	* @var float
	*/
	_total = config === undefined ? null : config.total || null,

 /**
	* id_sucursal
	* 
	* sucursal de la compra
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* id_usuario
	* 
	* empleado que lo vendio
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* pagado
	* 
	* porcentaje pagado de esta compra
	* @access private
	* @var float
	*/
	_pagado = config === undefined ? null : config.pagado || null,

 /**
	* cancelada
	* 
	* verdadero si esta compra ha sido cancelada, falso si no
	* @access private
	* @var tinyint(1)
	*/
	_cancelada = config === undefined ? null : config.cancelada || null,

 /**
	* ip
	* 
	* ip de donde provino esta compra
	* @access private
	* @var varchar(16)
	*/
	_ip = config === undefined ? null : config.ip || null,

 /**
	* liquidada
	* 
	* Verdadero si esta compra ha sido liquidada, falso si hay un saldo pendiente
	* @access private
	* @var tinyint(1)
	*/
	_liquidada = config === undefined ? null : config.liquidada || null;

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de compra
	  * @return int(11)
	  */
	this.getIdCompra = function ()
	{
		return _id_compra;
	};

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompra( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdCompra  = function ( id_compra )
	{
		_id_compra = id_compra;
	};

	/**
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le compro
	  * @return int(11)
	  */
	this.getIdCliente = function ()
	{
		return _id_cliente;
	};

	/**
	  * setIdCliente( $id_cliente )
	  * 
	  * Set the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le compro.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cliente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdCliente  = function ( id_cliente )
	{
		_id_cliente = id_cliente;
	};

	/**
	  * getTipoCompra
	  * 
	  * Get the <i>tipo_compra</i> property for this object. Donde <i>tipo_compra</i> es tipo de compra, contado o credito
	  * @return enum('credito','contado')
	  */
	this.getTipoCompra = function ()
	{
		return _tipo_compra;
	};

	/**
	  * setTipoCompra( $tipo_compra )
	  * 
	  * Set the <i>tipo_compra</i> property for this object. Donde <i>tipo_compra</i> es tipo de compra, contado o credito.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_compra</i> es de tipo <i>enum('credito','contado')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('credito','contado')
	  */
	this.setTipoCompra  = function ( tipo_compra )
	{
		_tipo_compra = tipo_compra;
	};

	/**
	  * getTipoPago
	  * 
	  * Get the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para esta compra en caso de ser a contado
	  * @return enum('efectivo','cheque','tarjeta')
	  */
	this.getTipoPago = function ()
	{
		return _tipo_pago;
	};

	/**
	  * setTipoPago( $tipo_pago )
	  * 
	  * Set the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para esta compra en caso de ser a contado.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_pago</i> es de tipo <i>enum('efectivo','cheque','tarjeta')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('efectivo','cheque','tarjeta')
	  */
	this.setTipoPago  = function ( tipo_pago )
	{
		_tipo_pago = tipo_pago;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de compra
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de compra.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getSubtotal
	  * 
	  * Get the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la compra, puede ser nulo
	  * @return float
	  */
	this.getSubtotal = function ()
	{
		return _subtotal;
	};

	/**
	  * setSubtotal( $subtotal )
	  * 
	  * Set the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la compra, puede ser nulo.
	  * Una validacion basica se hara aqui para comprobar que <i>subtotal</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setSubtotal  = function ( subtotal )
	{
		_subtotal = subtotal;
	};

	/**
	  * getImpuesto
	  * 
	  * Get the <i>impuesto</i> property for this object. Donde <i>impuesto</i> es impuesto agregado por la compra, depende de cada sucursal
	  * @return float
	  */
	this.getImpuesto = function ()
	{
		return _impuesto;
	};

	/**
	  * setImpuesto( $impuesto )
	  * 
	  * Set the <i>impuesto</i> property for this object. Donde <i>impuesto</i> es impuesto agregado por la compra, depende de cada sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>impuesto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setImpuesto  = function ( impuesto )
	{
		_impuesto = impuesto;
	};

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta compra
	  * @return float
	  */
	this.getDescuento = function ()
	{
		return _descuento;
	};

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setDescuento  = function ( descuento )
	{
		_descuento = descuento;
	};

	/**
	  * getTotal
	  * 
	  * Get the <i>total</i> property for this object. Donde <i>total</i> es total de esta compra
	  * @return float
	  */
	this.getTotal = function ()
	{
		return _total;
	};

	/**
	  * setTotal( $total )
	  * 
	  * Set the <i>total</i> property for this object. Donde <i>total</i> es total de esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>total</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotal  = function ( total )
	{
		_total = total;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la compra
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es empleado que lo vendio
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es empleado que lo vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getPagado
	  * 
	  * Get the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta compra
	  * @return float
	  */
	this.getPagado = function ()
	{
		return _pagado;
	};

	/**
	  * setPagado( $pagado )
	  * 
	  * Set the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>pagado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPagado  = function ( pagado )
	{
		_pagado = pagado;
	};

	/**
	  * getCancelada
	  * 
	  * Get the <i>cancelada</i> property for this object. Donde <i>cancelada</i> es verdadero si esta compra ha sido cancelada, falso si no
	  * @return tinyint(1)
	  */
	this.getCancelada = function ()
	{
		return _cancelada;
	};

	/**
	  * setCancelada( $cancelada )
	  * 
	  * Set the <i>cancelada</i> property for this object. Donde <i>cancelada</i> es verdadero si esta compra ha sido cancelada, falso si no.
	  * Una validacion basica se hara aqui para comprobar que <i>cancelada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setCancelada  = function ( cancelada )
	{
		_cancelada = cancelada;
	};

	/**
	  * getIp
	  * 
	  * Get the <i>ip</i> property for this object. Donde <i>ip</i> es ip de donde provino esta compra
	  * @return varchar(16)
	  */
	this.getIp = function ()
	{
		return _ip;
	};

	/**
	  * setIp( $ip )
	  * 
	  * Set the <i>ip</i> property for this object. Donde <i>ip</i> es ip de donde provino esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>ip</i> es de tipo <i>varchar(16)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(16)
	  */
	this.setIp  = function ( ip )
	{
		_ip = ip;
	};

	/**
	  * getLiquidada
	  * 
	  * Get the <i>liquidada</i> property for this object. Donde <i>liquidada</i> es Verdadero si esta compra ha sido liquidada, falso si hay un saldo pendiente
	  * @return tinyint(1)
	  */
	this.getLiquidada = function ()
	{
		return _liquidada;
	};

	/**
	  * setLiquidada( $liquidada )
	  * 
	  * Set the <i>liquidada</i> property for this object. Donde <i>liquidada</i> es Verdadero si esta compra ha sido liquidada, falso si hay un saldo pendiente.
	  * Una validacion basica se hara aqui para comprobar que <i>liquidada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setLiquidada  = function ( liquidada )
	{
		_liquidada = liquidada;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CompraCliente} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		CompraCliente._callback_stack.push( _original_callback  );
		CompraCliente._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		CompraCliente.getByPK(  this.getIdCompra() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraCliente} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from compra_cliente WHERE ("; 
		$val = [];
		if( this.getIdCompra() != null){
			$sql += " id_compra = ? AND";
			$val.push( this.getIdCompra() );
		}

		if( this.getIdCliente() != null){
			$sql += " id_cliente = ? AND";
			$val.push( this.getIdCliente() );
		}

		if( this.getTipoCompra() != null){
			$sql += " tipo_compra = ? AND";
			$val.push( this.getTipoCompra() );
		}

		if( this.getTipoPago() != null){
			$sql += " tipo_pago = ? AND";
			$val.push( this.getTipoPago() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getSubtotal() != null){
			$sql += " subtotal = ? AND";
			$val.push( this.getSubtotal() );
		}

		if( this.getImpuesto() != null){
			$sql += " impuesto = ? AND";
			$val.push( this.getImpuesto() );
		}

		if( this.getDescuento() != null){
			$sql += " descuento = ? AND";
			$val.push( this.getDescuento() );
		}

		if( this.getTotal() != null){
			$sql += " total = ? AND";
			$val.push( this.getTotal() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getPagado() != null){
			$sql += " pagado = ? AND";
			$val.push( this.getPagado() );
		}

		if( this.getCancelada() != null){
			$sql += " cancelada = ? AND";
			$val.push( this.getCancelada() );
		}

		if( this.getIp() != null){
			$sql += " ip = ? AND";
			$val.push( this.getIp() );
		}

		if( this.getLiquidada() != null){
			$sql += " liquidada = ? AND";
			$val.push( this.getLiquidada() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraCliente($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CompraCliente suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CompraCliente dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente a crear.
	  **/
	var create = function( compra_cliente )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO compra_cliente ( id_compra, id_cliente, tipo_compra, tipo_pago, fecha, subtotal, impuesto, descuento, total, id_sucursal, id_usuario, pagado, cancelada, ip, liquidada ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			compra_cliente.getIdCompra(), 
			compra_cliente.getIdCliente(), 
			compra_cliente.getTipoCompra(), 
			compra_cliente.getTipoPago(), 
			compra_cliente.getFecha(), 
			compra_cliente.getSubtotal(), 
			compra_cliente.getImpuesto(), 
			compra_cliente.getDescuento(), 
			compra_cliente.getTotal(), 
			compra_cliente.getIdSucursal(), 
			compra_cliente.getIdUsuario(), 
			compra_cliente.getPagado(), 
			compra_cliente.getCancelada(), 
			compra_cliente.getIp(), 
			compra_cliente.getLiquidada(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				CompraCliente._callback_stack.pop().call(null, compra_cliente);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente a actualizar.
	  **/
	var update = function( $compra_cliente )
	{
		$sql = "UPDATE compra_cliente SET  id_cliente = ?, tipo_compra = ?, tipo_pago = ?, fecha = ?, subtotal = ?, impuesto = ?, descuento = ?, total = ?, id_sucursal = ?, id_usuario = ?, pagado = ?, cancelada = ?, ip = ?, liquidada = ? WHERE  id_compra = ?;";
		$params = [ 
			$compra_cliente.getIdCliente(), 
			$compra_cliente.getTipoCompra(), 
			$compra_cliente.getTipoPago(), 
			$compra_cliente.getFecha(), 
			$compra_cliente.getSubtotal(), 
			$compra_cliente.getImpuesto(), 
			$compra_cliente.getDescuento(), 
			$compra_cliente.getTotal(), 
			$compra_cliente.getIdSucursal(), 
			$compra_cliente.getIdUsuario(), 
			$compra_cliente.getPagado(), 
			$compra_cliente.getCancelada(), 
			$compra_cliente.getIp(), 
			$compra_cliente.getLiquidada(), 
			$compra_cliente.getIdCompra(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				CompraCliente._callback_stack.pop().call(null, $compra_cliente);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CompraCliente suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( CompraCliente.getByPK(this.getIdCompra()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM compra_cliente WHERE  id_compra = ?;";
		$params = [ this.getIdCompra() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraCliente} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CompraCliente}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $compra_cliente , $orderBy , $orden )
	{
		$sql = "SELECT * from compra_cliente WHERE ("; 
		$val = [];
		if( (($a = this.getIdCompra()) != null) & ( ($b = $compra_cliente.getIdCompra()) != null) ){
				$sql += " id_compra >= ? AND id_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdCliente()) != null) & ( ($b = $compra_cliente.getIdCliente()) != null) ){
				$sql += " id_cliente >= ? AND id_cliente <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_cliente = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoCompra()) != null) & ( ($b = $compra_cliente.getTipoCompra()) != null) ){
				$sql += " tipo_compra >= ? AND tipo_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoPago()) != null) & ( ($b = $compra_cliente.getTipoPago()) != null) ){
				$sql += " tipo_pago >= ? AND tipo_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $compra_cliente.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSubtotal()) != null) & ( ($b = $compra_cliente.getSubtotal()) != null) ){
				$sql += " subtotal >= ? AND subtotal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " subtotal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getImpuesto()) != null) & ( ($b = $compra_cliente.getImpuesto()) != null) ){
				$sql += " impuesto >= ? AND impuesto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " impuesto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescuento()) != null) & ( ($b = $compra_cliente.getDescuento()) != null) ){
				$sql += " descuento >= ? AND descuento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descuento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotal()) != null) & ( ($b = $compra_cliente.getTotal()) != null) ){
				$sql += " total >= ? AND total <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $compra_cliente.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $compra_cliente.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPagado()) != null) & ( ($b = $compra_cliente.getPagado()) != null) ){
				$sql += " pagado >= ? AND pagado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " pagado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCancelada()) != null) & ( ($b = $compra_cliente.getCancelada()) != null) ){
				$sql += " cancelada >= ? AND cancelada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cancelada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIp()) != null) & ( ($b = $compra_cliente.getIp()) != null) ){
				$sql += " ip >= ? AND ip <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " ip = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLiquidada()) != null) & ( ($b = $compra_cliente.getLiquidada()) != null) ){
				$sql += " liquidada >= ? AND liquidada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " liquidada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraCliente($foo));
		//}
		return $sql;
	};


}
	CompraCliente._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CompraCliente}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	CompraCliente.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from compra_cliente";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new CompraCliente( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link CompraCliente} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CompraCliente} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CompraCliente Un objeto del tipo {@link CompraCliente}. NULL si no hay tal registro.
	  **/
	CompraCliente.getByPK = function(  $id_compra, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM compra_cliente WHERE (id_compra = ? ) LIMIT 1;";
		db.query($sql, [ $id_compra] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new CompraCliente(results.rows.item(0)); 
				CompraCliente._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : CompraCliente._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table compra_proveedor.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var CompraProveedor = function ( config )
{
 /**
	* id_compra_proveedor
	* 
	* identificador de la compra
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_compra_proveedor = config === undefined ? null : config.id_compra_proveedor || null,

 /**
	* peso_origen
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_peso_origen = config === undefined ? null : config.peso_origen || null,

 /**
	* id_proveedor
	* 
	* id del proveedor a quien se le hizo esta compra
	* @access private
	* @var int(11)
	*/
	_id_proveedor = config === undefined ? null : config.id_proveedor || null,

 /**
	* fecha
	* 
	* fecha de cuando se recibio el embarque
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null,

 /**
	* fecha_origen
	* 
	* fecha de cuando se envio este embarque
	* @access private
	* @var date
	*/
	_fecha_origen = config === undefined ? null : config.fecha_origen || null,

 /**
	* folio
	* 
	* folio de la remision
	* @access private
	* @var varchar(11)
	*/
	_folio = config === undefined ? null : config.folio || null,

 /**
	* numero_de_viaje
	* 
	* numero de viaje
	* @access private
	* @var varchar(11)
	*/
	_numero_de_viaje = config === undefined ? null : config.numero_de_viaje || null,

 /**
	* peso_recibido
	* 
	* peso en kilogramos reportado en la remision
	* @access private
	* @var float
	*/
	_peso_recibido = config === undefined ? null : config.peso_recibido || null,

 /**
	* arpillas
	* 
	* numero de arpillas en el camion
	* @access private
	* @var float
	*/
	_arpillas = config === undefined ? null : config.arpillas || null,

 /**
	* peso_por_arpilla
	* 
	* peso promedio en kilogramos por arpilla
	* @access private
	* @var float
	*/
	_peso_por_arpilla = config === undefined ? null : config.peso_por_arpilla || null,

 /**
	* productor
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(64)
	*/
	_productor = config === undefined ? null : config.productor || null,

 /**
	* calidad
	* 
	* Describe la calidad del producto asignando una calificacion en eel rango de 0-100
	* @access private
	* @var tinyint(3)
	*/
	_calidad = config === undefined ? null : config.calidad || null,

 /**
	* merma_por_arpilla
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_merma_por_arpilla = config === undefined ? null : config.merma_por_arpilla || null,

 /**
	* total_origen
	* 
	* Es lo que vale el embarque segun el proveedor
	* @access private
	* @var float
	*/
	_total_origen = config === undefined ? null : config.total_origen || null;

	/**
	  * getIdCompraProveedor
	  * 
	  * Get the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es identificador de la compra
	  * @return int(11)
	  */
	this.getIdCompraProveedor = function ()
	{
		return _id_compra_proveedor;
	};

	/**
	  * setIdCompraProveedor( $id_compra_proveedor )
	  * 
	  * Set the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es identificador de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompraProveedor( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdCompraProveedor  = function ( id_compra_proveedor )
	{
		_id_compra_proveedor = id_compra_proveedor;
	};

	/**
	  * getPesoOrigen
	  * 
	  * Get the <i>peso_origen</i> property for this object. Donde <i>peso_origen</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getPesoOrigen = function ()
	{
		return _peso_origen;
	};

	/**
	  * setPesoOrigen( $peso_origen )
	  * 
	  * Set the <i>peso_origen</i> property for this object. Donde <i>peso_origen</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>peso_origen</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPesoOrigen  = function ( peso_origen )
	{
		_peso_origen = peso_origen;
	};

	/**
	  * getIdProveedor
	  * 
	  * Get the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es id del proveedor a quien se le hizo esta compra
	  * @return int(11)
	  */
	this.getIdProveedor = function ()
	{
		return _id_proveedor;
	};

	/**
	  * setIdProveedor( $id_proveedor )
	  * 
	  * Set the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es id del proveedor a quien se le hizo esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdProveedor  = function ( id_proveedor )
	{
		_id_proveedor = id_proveedor;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de cuando se recibio el embarque
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de cuando se recibio el embarque.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getFechaOrigen
	  * 
	  * Get the <i>fecha_origen</i> property for this object. Donde <i>fecha_origen</i> es fecha de cuando se envio este embarque
	  * @return date
	  */
	this.getFechaOrigen = function ()
	{
		return _fecha_origen;
	};

	/**
	  * setFechaOrigen( $fecha_origen )
	  * 
	  * Set the <i>fecha_origen</i> property for this object. Donde <i>fecha_origen</i> es fecha de cuando se envio este embarque.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_origen</i> es de tipo <i>date</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param date
	  */
	this.setFechaOrigen  = function ( fecha_origen )
	{
		_fecha_origen = fecha_origen;
	};

	/**
	  * getFolio
	  * 
	  * Get the <i>folio</i> property for this object. Donde <i>folio</i> es folio de la remision
	  * @return varchar(11)
	  */
	this.getFolio = function ()
	{
		return _folio;
	};

	/**
	  * setFolio( $folio )
	  * 
	  * Set the <i>folio</i> property for this object. Donde <i>folio</i> es folio de la remision.
	  * Una validacion basica se hara aqui para comprobar que <i>folio</i> es de tipo <i>varchar(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(11)
	  */
	this.setFolio  = function ( folio )
	{
		_folio = folio;
	};

	/**
	  * getNumeroDeViaje
	  * 
	  * Get the <i>numero_de_viaje</i> property for this object. Donde <i>numero_de_viaje</i> es numero de viaje
	  * @return varchar(11)
	  */
	this.getNumeroDeViaje = function ()
	{
		return _numero_de_viaje;
	};

	/**
	  * setNumeroDeViaje( $numero_de_viaje )
	  * 
	  * Set the <i>numero_de_viaje</i> property for this object. Donde <i>numero_de_viaje</i> es numero de viaje.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_de_viaje</i> es de tipo <i>varchar(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(11)
	  */
	this.setNumeroDeViaje  = function ( numero_de_viaje )
	{
		_numero_de_viaje = numero_de_viaje;
	};

	/**
	  * getPesoRecibido
	  * 
	  * Get the <i>peso_recibido</i> property for this object. Donde <i>peso_recibido</i> es peso en kilogramos reportado en la remision
	  * @return float
	  */
	this.getPesoRecibido = function ()
	{
		return _peso_recibido;
	};

	/**
	  * setPesoRecibido( $peso_recibido )
	  * 
	  * Set the <i>peso_recibido</i> property for this object. Donde <i>peso_recibido</i> es peso en kilogramos reportado en la remision.
	  * Una validacion basica se hara aqui para comprobar que <i>peso_recibido</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPesoRecibido  = function ( peso_recibido )
	{
		_peso_recibido = peso_recibido;
	};

	/**
	  * getArpillas
	  * 
	  * Get the <i>arpillas</i> property for this object. Donde <i>arpillas</i> es numero de arpillas en el camion
	  * @return float
	  */
	this.getArpillas = function ()
	{
		return _arpillas;
	};

	/**
	  * setArpillas( $arpillas )
	  * 
	  * Set the <i>arpillas</i> property for this object. Donde <i>arpillas</i> es numero de arpillas en el camion.
	  * Una validacion basica se hara aqui para comprobar que <i>arpillas</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setArpillas  = function ( arpillas )
	{
		_arpillas = arpillas;
	};

	/**
	  * getPesoPorArpilla
	  * 
	  * Get the <i>peso_por_arpilla</i> property for this object. Donde <i>peso_por_arpilla</i> es peso promedio en kilogramos por arpilla
	  * @return float
	  */
	this.getPesoPorArpilla = function ()
	{
		return _peso_por_arpilla;
	};

	/**
	  * setPesoPorArpilla( $peso_por_arpilla )
	  * 
	  * Set the <i>peso_por_arpilla</i> property for this object. Donde <i>peso_por_arpilla</i> es peso promedio en kilogramos por arpilla.
	  * Una validacion basica se hara aqui para comprobar que <i>peso_por_arpilla</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPesoPorArpilla  = function ( peso_por_arpilla )
	{
		_peso_por_arpilla = peso_por_arpilla;
	};

	/**
	  * getProductor
	  * 
	  * Get the <i>productor</i> property for this object. Donde <i>productor</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	this.getProductor = function ()
	{
		return _productor;
	};

	/**
	  * setProductor( $productor )
	  * 
	  * Set the <i>productor</i> property for this object. Donde <i>productor</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>productor</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	this.setProductor  = function ( productor )
	{
		_productor = productor;
	};

	/**
	  * getCalidad
	  * 
	  * Get the <i>calidad</i> property for this object. Donde <i>calidad</i> es Describe la calidad del producto asignando una calificacion en eel rango de 0-100
	  * @return tinyint(3)
	  */
	this.getCalidad = function ()
	{
		return _calidad;
	};

	/**
	  * setCalidad( $calidad )
	  * 
	  * Set the <i>calidad</i> property for this object. Donde <i>calidad</i> es Describe la calidad del producto asignando una calificacion en eel rango de 0-100.
	  * Una validacion basica se hara aqui para comprobar que <i>calidad</i> es de tipo <i>tinyint(3)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(3)
	  */
	this.setCalidad  = function ( calidad )
	{
		_calidad = calidad;
	};

	/**
	  * getMermaPorArpilla
	  * 
	  * Get the <i>merma_por_arpilla</i> property for this object. Donde <i>merma_por_arpilla</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getMermaPorArpilla = function ()
	{
		return _merma_por_arpilla;
	};

	/**
	  * setMermaPorArpilla( $merma_por_arpilla )
	  * 
	  * Set the <i>merma_por_arpilla</i> property for this object. Donde <i>merma_por_arpilla</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>merma_por_arpilla</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setMermaPorArpilla  = function ( merma_por_arpilla )
	{
		_merma_por_arpilla = merma_por_arpilla;
	};

	/**
	  * getTotalOrigen
	  * 
	  * Get the <i>total_origen</i> property for this object. Donde <i>total_origen</i> es Es lo que vale el embarque segun el proveedor
	  * @return float
	  */
	this.getTotalOrigen = function ()
	{
		return _total_origen;
	};

	/**
	  * setTotalOrigen( $total_origen )
	  * 
	  * Set the <i>total_origen</i> property for this object. Donde <i>total_origen</i> es Es lo que vale el embarque segun el proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>total_origen</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotalOrigen  = function ( total_origen )
	{
		_total_origen = total_origen;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CompraProveedor} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		CompraProveedor._callback_stack.push( _original_callback  );
		CompraProveedor._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		CompraProveedor.getByPK(  this.getIdCompraProveedor() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraProveedor} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraProveedor [$compra_proveedor] El objeto de tipo CompraProveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from compra_proveedor WHERE ("; 
		$val = [];
		if( this.getIdCompraProveedor() != null){
			$sql += " id_compra_proveedor = ? AND";
			$val.push( this.getIdCompraProveedor() );
		}

		if( this.getPesoOrigen() != null){
			$sql += " peso_origen = ? AND";
			$val.push( this.getPesoOrigen() );
		}

		if( this.getIdProveedor() != null){
			$sql += " id_proveedor = ? AND";
			$val.push( this.getIdProveedor() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getFechaOrigen() != null){
			$sql += " fecha_origen = ? AND";
			$val.push( this.getFechaOrigen() );
		}

		if( this.getFolio() != null){
			$sql += " folio = ? AND";
			$val.push( this.getFolio() );
		}

		if( this.getNumeroDeViaje() != null){
			$sql += " numero_de_viaje = ? AND";
			$val.push( this.getNumeroDeViaje() );
		}

		if( this.getPesoRecibido() != null){
			$sql += " peso_recibido = ? AND";
			$val.push( this.getPesoRecibido() );
		}

		if( this.getArpillas() != null){
			$sql += " arpillas = ? AND";
			$val.push( this.getArpillas() );
		}

		if( this.getPesoPorArpilla() != null){
			$sql += " peso_por_arpilla = ? AND";
			$val.push( this.getPesoPorArpilla() );
		}

		if( this.getProductor() != null){
			$sql += " productor = ? AND";
			$val.push( this.getProductor() );
		}

		if( this.getCalidad() != null){
			$sql += " calidad = ? AND";
			$val.push( this.getCalidad() );
		}

		if( this.getMermaPorArpilla() != null){
			$sql += " merma_por_arpilla = ? AND";
			$val.push( this.getMermaPorArpilla() );
		}

		if( this.getTotalOrigen() != null){
			$sql += " total_origen = ? AND";
			$val.push( this.getTotalOrigen() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraProveedor($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CompraProveedor suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CompraProveedor dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CompraProveedor [$compra_proveedor] El objeto de tipo CompraProveedor a crear.
	  **/
	var create = function( compra_proveedor )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO compra_proveedor ( id_compra_proveedor, peso_origen, id_proveedor, fecha, fecha_origen, folio, numero_de_viaje, peso_recibido, arpillas, peso_por_arpilla, productor, calidad, merma_por_arpilla, total_origen ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			compra_proveedor.getIdCompraProveedor(), 
			compra_proveedor.getPesoOrigen(), 
			compra_proveedor.getIdProveedor(), 
			compra_proveedor.getFecha(), 
			compra_proveedor.getFechaOrigen(), 
			compra_proveedor.getFolio(), 
			compra_proveedor.getNumeroDeViaje(), 
			compra_proveedor.getPesoRecibido(), 
			compra_proveedor.getArpillas(), 
			compra_proveedor.getPesoPorArpilla(), 
			compra_proveedor.getProductor(), 
			compra_proveedor.getCalidad(), 
			compra_proveedor.getMermaPorArpilla(), 
			compra_proveedor.getTotalOrigen(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				CompraProveedor._callback_stack.pop().call(null, compra_proveedor);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param CompraProveedor [$compra_proveedor] El objeto de tipo CompraProveedor a actualizar.
	  **/
	var update = function( $compra_proveedor )
	{
		$sql = "UPDATE compra_proveedor SET  peso_origen = ?, id_proveedor = ?, fecha = ?, fecha_origen = ?, folio = ?, numero_de_viaje = ?, peso_recibido = ?, arpillas = ?, peso_por_arpilla = ?, productor = ?, calidad = ?, merma_por_arpilla = ?, total_origen = ? WHERE  id_compra_proveedor = ?;";
		$params = [ 
			$compra_proveedor.getPesoOrigen(), 
			$compra_proveedor.getIdProveedor(), 
			$compra_proveedor.getFecha(), 
			$compra_proveedor.getFechaOrigen(), 
			$compra_proveedor.getFolio(), 
			$compra_proveedor.getNumeroDeViaje(), 
			$compra_proveedor.getPesoRecibido(), 
			$compra_proveedor.getArpillas(), 
			$compra_proveedor.getPesoPorArpilla(), 
			$compra_proveedor.getProductor(), 
			$compra_proveedor.getCalidad(), 
			$compra_proveedor.getMermaPorArpilla(), 
			$compra_proveedor.getTotalOrigen(), 
			$compra_proveedor.getIdCompraProveedor(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				CompraProveedor._callback_stack.pop().call(null, $compra_proveedor);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CompraProveedor suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( CompraProveedor.getByPK(this.getIdCompraProveedor()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM compra_proveedor WHERE  id_compra_proveedor = ?;";
		$params = [ this.getIdCompraProveedor() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraProveedor} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CompraProveedor}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraProveedor [$compra_proveedor] El objeto de tipo CompraProveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $compra_proveedor , $orderBy , $orden )
	{
		$sql = "SELECT * from compra_proveedor WHERE ("; 
		$val = [];
		if( (($a = this.getIdCompraProveedor()) != null) & ( ($b = $compra_proveedor.getIdCompraProveedor()) != null) ){
				$sql += " id_compra_proveedor >= ? AND id_compra_proveedor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra_proveedor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPesoOrigen()) != null) & ( ($b = $compra_proveedor.getPesoOrigen()) != null) ){
				$sql += " peso_origen >= ? AND peso_origen <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " peso_origen = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdProveedor()) != null) & ( ($b = $compra_proveedor.getIdProveedor()) != null) ){
				$sql += " id_proveedor >= ? AND id_proveedor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_proveedor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $compra_proveedor.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaOrigen()) != null) & ( ($b = $compra_proveedor.getFechaOrigen()) != null) ){
				$sql += " fecha_origen >= ? AND fecha_origen <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_origen = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFolio()) != null) & ( ($b = $compra_proveedor.getFolio()) != null) ){
				$sql += " folio >= ? AND folio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " folio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroDeViaje()) != null) & ( ($b = $compra_proveedor.getNumeroDeViaje()) != null) ){
				$sql += " numero_de_viaje >= ? AND numero_de_viaje <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_de_viaje = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPesoRecibido()) != null) & ( ($b = $compra_proveedor.getPesoRecibido()) != null) ){
				$sql += " peso_recibido >= ? AND peso_recibido <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " peso_recibido = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getArpillas()) != null) & ( ($b = $compra_proveedor.getArpillas()) != null) ){
				$sql += " arpillas >= ? AND arpillas <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " arpillas = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPesoPorArpilla()) != null) & ( ($b = $compra_proveedor.getPesoPorArpilla()) != null) ){
				$sql += " peso_por_arpilla >= ? AND peso_por_arpilla <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " peso_por_arpilla = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getProductor()) != null) & ( ($b = $compra_proveedor.getProductor()) != null) ){
				$sql += " productor >= ? AND productor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " productor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCalidad()) != null) & ( ($b = $compra_proveedor.getCalidad()) != null) ){
				$sql += " calidad >= ? AND calidad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " calidad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMermaPorArpilla()) != null) & ( ($b = $compra_proveedor.getMermaPorArpilla()) != null) ){
				$sql += " merma_por_arpilla >= ? AND merma_por_arpilla <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " merma_por_arpilla = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotalOrigen()) != null) & ( ($b = $compra_proveedor.getTotalOrigen()) != null) ){
				$sql += " total_origen >= ? AND total_origen <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total_origen = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraProveedor($foo));
		//}
		return $sql;
	};


}
	CompraProveedor._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CompraProveedor}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	CompraProveedor.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from compra_proveedor";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new CompraProveedor( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link CompraProveedor} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CompraProveedor} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CompraProveedor Un objeto del tipo {@link CompraProveedor}. NULL si no hay tal registro.
	  **/
	CompraProveedor.getByPK = function(  $id_compra_proveedor, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM compra_proveedor WHERE (id_compra_proveedor = ? ) LIMIT 1;";
		db.query($sql, [ $id_compra_proveedor] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new CompraProveedor(results.rows.item(0)); 
				CompraProveedor._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : CompraProveedor._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table compra_proveedor_flete.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var CompraProveedorFlete = function ( config )
{
 /**
	* id_compra_proveedor
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_compra_proveedor = config === undefined ? null : config.id_compra_proveedor || null,

 /**
	* chofer
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(64)
	*/
	_chofer = config === undefined ? null : config.chofer || null,

 /**
	* marca_camion
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(64)
	*/
	_marca_camion = config === undefined ? null : config.marca_camion || null,

 /**
	* placas_camion
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(64)
	*/
	_placas_camion = config === undefined ? null : config.placas_camion || null,

 /**
	* modelo_camion
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(64)
	*/
	_modelo_camion = config === undefined ? null : config.modelo_camion || null,

 /**
	* costo_flete
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_costo_flete = config === undefined ? null : config.costo_flete || null;

	/**
	  * getIdCompraProveedor
	  * 
	  * Get the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdCompraProveedor = function ()
	{
		return _id_compra_proveedor;
	};

	/**
	  * setIdCompraProveedor( $id_compra_proveedor )
	  * 
	  * Set the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompraProveedor( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdCompraProveedor  = function ( id_compra_proveedor )
	{
		_id_compra_proveedor = id_compra_proveedor;
	};

	/**
	  * getChofer
	  * 
	  * Get the <i>chofer</i> property for this object. Donde <i>chofer</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	this.getChofer = function ()
	{
		return _chofer;
	};

	/**
	  * setChofer( $chofer )
	  * 
	  * Set the <i>chofer</i> property for this object. Donde <i>chofer</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>chofer</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	this.setChofer  = function ( chofer )
	{
		_chofer = chofer;
	};

	/**
	  * getMarcaCamion
	  * 
	  * Get the <i>marca_camion</i> property for this object. Donde <i>marca_camion</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	this.getMarcaCamion = function ()
	{
		return _marca_camion;
	};

	/**
	  * setMarcaCamion( $marca_camion )
	  * 
	  * Set the <i>marca_camion</i> property for this object. Donde <i>marca_camion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>marca_camion</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	this.setMarcaCamion  = function ( marca_camion )
	{
		_marca_camion = marca_camion;
	};

	/**
	  * getPlacasCamion
	  * 
	  * Get the <i>placas_camion</i> property for this object. Donde <i>placas_camion</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	this.getPlacasCamion = function ()
	{
		return _placas_camion;
	};

	/**
	  * setPlacasCamion( $placas_camion )
	  * 
	  * Set the <i>placas_camion</i> property for this object. Donde <i>placas_camion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>placas_camion</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	this.setPlacasCamion  = function ( placas_camion )
	{
		_placas_camion = placas_camion;
	};

	/**
	  * getModeloCamion
	  * 
	  * Get the <i>modelo_camion</i> property for this object. Donde <i>modelo_camion</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	this.getModeloCamion = function ()
	{
		return _modelo_camion;
	};

	/**
	  * setModeloCamion( $modelo_camion )
	  * 
	  * Set the <i>modelo_camion</i> property for this object. Donde <i>modelo_camion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>modelo_camion</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	this.setModeloCamion  = function ( modelo_camion )
	{
		_modelo_camion = modelo_camion;
	};

	/**
	  * getCostoFlete
	  * 
	  * Get the <i>costo_flete</i> property for this object. Donde <i>costo_flete</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getCostoFlete = function ()
	{
		return _costo_flete;
	};

	/**
	  * setCostoFlete( $costo_flete )
	  * 
	  * Set the <i>costo_flete</i> property for this object. Donde <i>costo_flete</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>costo_flete</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setCostoFlete  = function ( costo_flete )
	{
		_costo_flete = costo_flete;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CompraProveedorFlete} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		CompraProveedorFlete._callback_stack.push( _original_callback  );
		CompraProveedorFlete._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		CompraProveedorFlete.getByPK(  this.getIdCompraProveedor() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraProveedorFlete} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraProveedorFlete [$compra_proveedor_flete] El objeto de tipo CompraProveedorFlete
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from compra_proveedor_flete WHERE ("; 
		$val = [];
		if( this.getIdCompraProveedor() != null){
			$sql += " id_compra_proveedor = ? AND";
			$val.push( this.getIdCompraProveedor() );
		}

		if( this.getChofer() != null){
			$sql += " chofer = ? AND";
			$val.push( this.getChofer() );
		}

		if( this.getMarcaCamion() != null){
			$sql += " marca_camion = ? AND";
			$val.push( this.getMarcaCamion() );
		}

		if( this.getPlacasCamion() != null){
			$sql += " placas_camion = ? AND";
			$val.push( this.getPlacasCamion() );
		}

		if( this.getModeloCamion() != null){
			$sql += " modelo_camion = ? AND";
			$val.push( this.getModeloCamion() );
		}

		if( this.getCostoFlete() != null){
			$sql += " costo_flete = ? AND";
			$val.push( this.getCostoFlete() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraProveedorFlete($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CompraProveedorFlete suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CompraProveedorFlete dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CompraProveedorFlete [$compra_proveedor_flete] El objeto de tipo CompraProveedorFlete a crear.
	  **/
	var create = function( compra_proveedor_flete )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO compra_proveedor_flete ( id_compra_proveedor, chofer, marca_camion, placas_camion, modelo_camion, costo_flete ) VALUES ( ?, ?, ?, ?, ?, ?);";
		$params = [
			compra_proveedor_flete.getIdCompraProveedor(), 
			compra_proveedor_flete.getChofer(), 
			compra_proveedor_flete.getMarcaCamion(), 
			compra_proveedor_flete.getPlacasCamion(), 
			compra_proveedor_flete.getModeloCamion(), 
			compra_proveedor_flete.getCostoFlete(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				CompraProveedorFlete._callback_stack.pop().call(null, compra_proveedor_flete);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param CompraProveedorFlete [$compra_proveedor_flete] El objeto de tipo CompraProveedorFlete a actualizar.
	  **/
	var update = function( $compra_proveedor_flete )
	{
		$sql = "UPDATE compra_proveedor_flete SET  chofer = ?, marca_camion = ?, placas_camion = ?, modelo_camion = ?, costo_flete = ? WHERE  id_compra_proveedor = ?;";
		$params = [ 
			$compra_proveedor_flete.getChofer(), 
			$compra_proveedor_flete.getMarcaCamion(), 
			$compra_proveedor_flete.getPlacasCamion(), 
			$compra_proveedor_flete.getModeloCamion(), 
			$compra_proveedor_flete.getCostoFlete(), 
			$compra_proveedor_flete.getIdCompraProveedor(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				CompraProveedorFlete._callback_stack.pop().call(null, $compra_proveedor_flete);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CompraProveedorFlete suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( CompraProveedorFlete.getByPK(this.getIdCompraProveedor()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM compra_proveedor_flete WHERE  id_compra_proveedor = ?;";
		$params = [ this.getIdCompraProveedor() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraProveedorFlete} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CompraProveedorFlete}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraProveedorFlete [$compra_proveedor_flete] El objeto de tipo CompraProveedorFlete
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $compra_proveedor_flete , $orderBy , $orden )
	{
		$sql = "SELECT * from compra_proveedor_flete WHERE ("; 
		$val = [];
		if( (($a = this.getIdCompraProveedor()) != null) & ( ($b = $compra_proveedor_flete.getIdCompraProveedor()) != null) ){
				$sql += " id_compra_proveedor >= ? AND id_compra_proveedor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra_proveedor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getChofer()) != null) & ( ($b = $compra_proveedor_flete.getChofer()) != null) ){
				$sql += " chofer >= ? AND chofer <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " chofer = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMarcaCamion()) != null) & ( ($b = $compra_proveedor_flete.getMarcaCamion()) != null) ){
				$sql += " marca_camion >= ? AND marca_camion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " marca_camion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPlacasCamion()) != null) & ( ($b = $compra_proveedor_flete.getPlacasCamion()) != null) ){
				$sql += " placas_camion >= ? AND placas_camion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " placas_camion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getModeloCamion()) != null) & ( ($b = $compra_proveedor_flete.getModeloCamion()) != null) ){
				$sql += " modelo_camion >= ? AND modelo_camion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " modelo_camion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCostoFlete()) != null) & ( ($b = $compra_proveedor_flete.getCostoFlete()) != null) ){
				$sql += " costo_flete >= ? AND costo_flete <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " costo_flete = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraProveedorFlete($foo));
		//}
		return $sql;
	};


}
	CompraProveedorFlete._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CompraProveedorFlete}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	CompraProveedorFlete.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from compra_proveedor_flete";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new CompraProveedorFlete( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link CompraProveedorFlete} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CompraProveedorFlete} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CompraProveedorFlete Un objeto del tipo {@link CompraProveedorFlete}. NULL si no hay tal registro.
	  **/
	CompraProveedorFlete.getByPK = function(  $id_compra_proveedor, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM compra_proveedor_flete WHERE (id_compra_proveedor = ? ) LIMIT 1;";
		db.query($sql, [ $id_compra_proveedor] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new CompraProveedorFlete(results.rows.item(0)); 
				CompraProveedorFlete._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : CompraProveedorFlete._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table compra_proveedor_fragmentacion.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var CompraProveedorFragmentacion = function ( config )
{
 /**
	* id_fragmentacion
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_fragmentacion = config === undefined ? null : config.id_fragmentacion || null,

 /**
	* id_compra_proveedor
	* 
	* La compra a proveedor del producto
	* @access private
	* @var int(11)
	*/
	_id_compra_proveedor = config === undefined ? null : config.id_compra_proveedor || null,

 /**
	* id_producto
	* 
	* El id del producto
	* @access private
	* @var int(11)
	*/
	_id_producto = config === undefined ? null : config.id_producto || null,

 /**
	* fecha
	* 
	* la fecha de esta operacion
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null,

 /**
	* descripcion
	* 
	* la descripcion de lo que ha sucedido, vendido, surtido, basura... etc.
	* @access private
	* @var varchar(16)
	*/
	_descripcion = config === undefined ? null : config.descripcion || null,

 /**
	* cantidad
	* 
	* cuanto fue consumido o agregado !!! en la escala que se tiene de este prod
	* @access private
	* @var double
	*/
	_cantidad = config === undefined ? null : config.cantidad || null,

 /**
	* procesada
	* 
	* si estamos hablando de producto procesado, debera ser true
	* @access private
	* @var tinyint(1)
	*/
	_procesada = config === undefined ? null : config.procesada || null,

 /**
	* precio
	* 
	* a cuanto se vendio esta porcion del producto, si es el resultado de algun proceso sera 0 por ejemplo
	* @access private
	* @var int(11)
	*/
	_precio = config === undefined ? null : config.precio || null,

 /**
	* descripcion_ref_id
	* 
	* si se refiere a una venta, se puede poner el id de esa venta, si fue de surtir, el id de la compra, etc..
	* @access private
	* @var int(11)
	*/
	_descripcion_ref_id = config === undefined ? null : config.descripcion_ref_id || null;

	/**
	  * getIdFragmentacion
	  * 
	  * Get the <i>id_fragmentacion</i> property for this object. Donde <i>id_fragmentacion</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdFragmentacion = function ()
	{
		return _id_fragmentacion;
	};

	/**
	  * setIdFragmentacion( $id_fragmentacion )
	  * 
	  * Set the <i>id_fragmentacion</i> property for this object. Donde <i>id_fragmentacion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_fragmentacion</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdFragmentacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdFragmentacion  = function ( id_fragmentacion )
	{
		_id_fragmentacion = id_fragmentacion;
	};

	/**
	  * getIdCompraProveedor
	  * 
	  * Get the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es La compra a proveedor del producto
	  * @return int(11)
	  */
	this.getIdCompraProveedor = function ()
	{
		return _id_compra_proveedor;
	};

	/**
	  * setIdCompraProveedor( $id_compra_proveedor )
	  * 
	  * Set the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es La compra a proveedor del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdCompraProveedor  = function ( id_compra_proveedor )
	{
		_id_compra_proveedor = id_compra_proveedor;
	};

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es El id del producto
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es El id del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es la fecha de esta operacion
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es la fecha de esta operacion.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es la descripcion de lo que ha sucedido, vendido, surtido, basura... etc.
	  * @return varchar(16)
	  */
	this.getDescripcion = function ()
	{
		return _descripcion;
	};

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es la descripcion de lo que ha sucedido, vendido, surtido, basura... etc..
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(16)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(16)
	  */
	this.setDescripcion  = function ( descripcion )
	{
		_descripcion = descripcion;
	};

	/**
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cuanto fue consumido o agregado !!! en la escala que se tiene de este prod
	  * @return double
	  */
	this.getCantidad = function ()
	{
		return _cantidad;
	};

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cuanto fue consumido o agregado !!! en la escala que se tiene de este prod.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>double</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param double
	  */
	this.setCantidad  = function ( cantidad )
	{
		_cantidad = cantidad;
	};

	/**
	  * getProcesada
	  * 
	  * Get the <i>procesada</i> property for this object. Donde <i>procesada</i> es si estamos hablando de producto procesado, debera ser true
	  * @return tinyint(1)
	  */
	this.getProcesada = function ()
	{
		return _procesada;
	};

	/**
	  * setProcesada( $procesada )
	  * 
	  * Set the <i>procesada</i> property for this object. Donde <i>procesada</i> es si estamos hablando de producto procesado, debera ser true.
	  * Una validacion basica se hara aqui para comprobar que <i>procesada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setProcesada  = function ( procesada )
	{
		_procesada = procesada;
	};

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es a cuanto se vendio esta porcion del producto, si es el resultado de algun proceso sera 0 por ejemplo
	  * @return int(11)
	  */
	this.getPrecio = function ()
	{
		return _precio;
	};

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es a cuanto se vendio esta porcion del producto, si es el resultado de algun proceso sera 0 por ejemplo.
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setPrecio  = function ( precio )
	{
		_precio = precio;
	};

	/**
	  * getDescripcionRefId
	  * 
	  * Get the <i>descripcion_ref_id</i> property for this object. Donde <i>descripcion_ref_id</i> es si se refiere a una venta, se puede poner el id de esa venta, si fue de surtir, el id de la compra, etc..
	  * @return int(11)
	  */
	this.getDescripcionRefId = function ()
	{
		return _descripcion_ref_id;
	};

	/**
	  * setDescripcionRefId( $descripcion_ref_id )
	  * 
	  * Set the <i>descripcion_ref_id</i> property for this object. Donde <i>descripcion_ref_id</i> es si se refiere a una venta, se puede poner el id de esa venta, si fue de surtir, el id de la compra, etc...
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion_ref_id</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setDescripcionRefId  = function ( descripcion_ref_id )
	{
		_descripcion_ref_id = descripcion_ref_id;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CompraProveedorFragmentacion} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		CompraProveedorFragmentacion._callback_stack.push( _original_callback  );
		CompraProveedorFragmentacion._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		CompraProveedorFragmentacion.getByPK(  this.getIdFragmentacion() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraProveedorFragmentacion} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraProveedorFragmentacion [$compra_proveedor_fragmentacion] El objeto de tipo CompraProveedorFragmentacion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from compra_proveedor_fragmentacion WHERE ("; 
		$val = [];
		if( this.getIdFragmentacion() != null){
			$sql += " id_fragmentacion = ? AND";
			$val.push( this.getIdFragmentacion() );
		}

		if( this.getIdCompraProveedor() != null){
			$sql += " id_compra_proveedor = ? AND";
			$val.push( this.getIdCompraProveedor() );
		}

		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getDescripcion() != null){
			$sql += " descripcion = ? AND";
			$val.push( this.getDescripcion() );
		}

		if( this.getCantidad() != null){
			$sql += " cantidad = ? AND";
			$val.push( this.getCantidad() );
		}

		if( this.getProcesada() != null){
			$sql += " procesada = ? AND";
			$val.push( this.getProcesada() );
		}

		if( this.getPrecio() != null){
			$sql += " precio = ? AND";
			$val.push( this.getPrecio() );
		}

		if( this.getDescripcionRefId() != null){
			$sql += " descripcion_ref_id = ? AND";
			$val.push( this.getDescripcionRefId() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraProveedorFragmentacion($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CompraProveedorFragmentacion suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CompraProveedorFragmentacion dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CompraProveedorFragmentacion [$compra_proveedor_fragmentacion] El objeto de tipo CompraProveedorFragmentacion a crear.
	  **/
	var create = function( compra_proveedor_fragmentacion )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO compra_proveedor_fragmentacion ( id_fragmentacion, id_compra_proveedor, id_producto, fecha, descripcion, cantidad, procesada, precio, descripcion_ref_id ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			compra_proveedor_fragmentacion.getIdFragmentacion(), 
			compra_proveedor_fragmentacion.getIdCompraProveedor(), 
			compra_proveedor_fragmentacion.getIdProducto(), 
			compra_proveedor_fragmentacion.getFecha(), 
			compra_proveedor_fragmentacion.getDescripcion(), 
			compra_proveedor_fragmentacion.getCantidad(), 
			compra_proveedor_fragmentacion.getProcesada(), 
			compra_proveedor_fragmentacion.getPrecio(), 
			compra_proveedor_fragmentacion.getDescripcionRefId(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				CompraProveedorFragmentacion._callback_stack.pop().call(null, compra_proveedor_fragmentacion);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param CompraProveedorFragmentacion [$compra_proveedor_fragmentacion] El objeto de tipo CompraProveedorFragmentacion a actualizar.
	  **/
	var update = function( $compra_proveedor_fragmentacion )
	{
		$sql = "UPDATE compra_proveedor_fragmentacion SET  id_compra_proveedor = ?, id_producto = ?, fecha = ?, descripcion = ?, cantidad = ?, procesada = ?, precio = ?, descripcion_ref_id = ? WHERE  id_fragmentacion = ?;";
		$params = [ 
			$compra_proveedor_fragmentacion.getIdCompraProveedor(), 
			$compra_proveedor_fragmentacion.getIdProducto(), 
			$compra_proveedor_fragmentacion.getFecha(), 
			$compra_proveedor_fragmentacion.getDescripcion(), 
			$compra_proveedor_fragmentacion.getCantidad(), 
			$compra_proveedor_fragmentacion.getProcesada(), 
			$compra_proveedor_fragmentacion.getPrecio(), 
			$compra_proveedor_fragmentacion.getDescripcionRefId(), 
			$compra_proveedor_fragmentacion.getIdFragmentacion(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				CompraProveedorFragmentacion._callback_stack.pop().call(null, $compra_proveedor_fragmentacion);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CompraProveedorFragmentacion suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( CompraProveedorFragmentacion.getByPK(this.getIdFragmentacion()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM compra_proveedor_fragmentacion WHERE  id_fragmentacion = ?;";
		$params = [ this.getIdFragmentacion() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraProveedorFragmentacion} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CompraProveedorFragmentacion}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraProveedorFragmentacion [$compra_proveedor_fragmentacion] El objeto de tipo CompraProveedorFragmentacion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $compra_proveedor_fragmentacion , $orderBy , $orden )
	{
		$sql = "SELECT * from compra_proveedor_fragmentacion WHERE ("; 
		$val = [];
		if( (($a = this.getIdFragmentacion()) != null) & ( ($b = $compra_proveedor_fragmentacion.getIdFragmentacion()) != null) ){
				$sql += " id_fragmentacion >= ? AND id_fragmentacion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_fragmentacion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdCompraProveedor()) != null) & ( ($b = $compra_proveedor_fragmentacion.getIdCompraProveedor()) != null) ){
				$sql += " id_compra_proveedor >= ? AND id_compra_proveedor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra_proveedor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdProducto()) != null) & ( ($b = $compra_proveedor_fragmentacion.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $compra_proveedor_fragmentacion.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcion()) != null) & ( ($b = $compra_proveedor_fragmentacion.getDescripcion()) != null) ){
				$sql += " descripcion >= ? AND descripcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCantidad()) != null) & ( ($b = $compra_proveedor_fragmentacion.getCantidad()) != null) ){
				$sql += " cantidad >= ? AND cantidad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cantidad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getProcesada()) != null) & ( ($b = $compra_proveedor_fragmentacion.getProcesada()) != null) ){
				$sql += " procesada >= ? AND procesada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " procesada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecio()) != null) & ( ($b = $compra_proveedor_fragmentacion.getPrecio()) != null) ){
				$sql += " precio >= ? AND precio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcionRefId()) != null) & ( ($b = $compra_proveedor_fragmentacion.getDescripcionRefId()) != null) ){
				$sql += " descripcion_ref_id >= ? AND descripcion_ref_id <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion_ref_id = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraProveedorFragmentacion($foo));
		//}
		return $sql;
	};


}
	CompraProveedorFragmentacion._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CompraProveedorFragmentacion}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	CompraProveedorFragmentacion.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from compra_proveedor_fragmentacion";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new CompraProveedorFragmentacion( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link CompraProveedorFragmentacion} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CompraProveedorFragmentacion} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CompraProveedorFragmentacion Un objeto del tipo {@link CompraProveedorFragmentacion}. NULL si no hay tal registro.
	  **/
	CompraProveedorFragmentacion.getByPK = function(  $id_fragmentacion, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM compra_proveedor_fragmentacion WHERE (id_fragmentacion = ? ) LIMIT 1;";
		db.query($sql, [ $id_fragmentacion] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new CompraProveedorFragmentacion(results.rows.item(0)); 
				CompraProveedorFragmentacion._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : CompraProveedorFragmentacion._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table compra_sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var CompraSucursal = function ( config )
{
 /**
	* id_compra
	* 
	* id de la compra
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_compra = config === undefined ? null : config.id_compra || null,

 /**
	* fecha
	* 
	* fecha de compra
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null,

 /**
	* subtotal
	* 
	* subtotal de compra
	* @access private
	* @var float
	*/
	_subtotal = config === undefined ? null : config.subtotal || null,

 /**
	* id_sucursal
	* 
	* sucursal en que se compro
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* id_usuario
	* 
	* quien realizo la compra
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* id_proveedor
	* 
	* En caso de ser una compra a un proveedor externo, contendra el id de ese proveedor, en caso de ser una compra a centro de distribucion este campo sera null.
	* @access private
	* @var int(11)
	*/
	_id_proveedor = config === undefined ? null : config.id_proveedor || null,

 /**
	* pagado
	* 
	* total de pago abonado a esta compra
	* @access private
	* @var float
	*/
	_pagado = config === undefined ? null : config.pagado || null,

 /**
	* liquidado
	* 
	* indica si la cuenta ha sido liquidada o no
	* @access private
	* @var tinyint(1)
	*/
	_liquidado = config === undefined ? null : config.liquidado || null,

 /**
	* total
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_total = config === undefined ? null : config.total || null;

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de la compra
	  * @return int(11)
	  */
	this.getIdCompra = function ()
	{
		return _id_compra;
	};

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompra( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdCompra  = function ( id_compra )
	{
		_id_compra = id_compra;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de compra
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de compra.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getSubtotal
	  * 
	  * Get the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de compra
	  * @return float
	  */
	this.getSubtotal = function ()
	{
		return _subtotal;
	};

	/**
	  * setSubtotal( $subtotal )
	  * 
	  * Set the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de compra.
	  * Una validacion basica se hara aqui para comprobar que <i>subtotal</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setSubtotal  = function ( subtotal )
	{
		_subtotal = subtotal;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en que se compro
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en que se compro.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es quien realizo la compra
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es quien realizo la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getIdProveedor
	  * 
	  * Get the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es En caso de ser una compra a un proveedor externo, contendra el id de ese proveedor, en caso de ser una compra a centro de distribucion este campo sera null.
	  * @return int(11)
	  */
	this.getIdProveedor = function ()
	{
		return _id_proveedor;
	};

	/**
	  * setIdProveedor( $id_proveedor )
	  * 
	  * Set the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es En caso de ser una compra a un proveedor externo, contendra el id de ese proveedor, en caso de ser una compra a centro de distribucion este campo sera null..
	  * Una validacion basica se hara aqui para comprobar que <i>id_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdProveedor  = function ( id_proveedor )
	{
		_id_proveedor = id_proveedor;
	};

	/**
	  * getPagado
	  * 
	  * Get the <i>pagado</i> property for this object. Donde <i>pagado</i> es total de pago abonado a esta compra
	  * @return float
	  */
	this.getPagado = function ()
	{
		return _pagado;
	};

	/**
	  * setPagado( $pagado )
	  * 
	  * Set the <i>pagado</i> property for this object. Donde <i>pagado</i> es total de pago abonado a esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>pagado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPagado  = function ( pagado )
	{
		_pagado = pagado;
	};

	/**
	  * getLiquidado
	  * 
	  * Get the <i>liquidado</i> property for this object. Donde <i>liquidado</i> es indica si la cuenta ha sido liquidada o no
	  * @return tinyint(1)
	  */
	this.getLiquidado = function ()
	{
		return _liquidado;
	};

	/**
	  * setLiquidado( $liquidado )
	  * 
	  * Set the <i>liquidado</i> property for this object. Donde <i>liquidado</i> es indica si la cuenta ha sido liquidada o no.
	  * Una validacion basica se hara aqui para comprobar que <i>liquidado</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setLiquidado  = function ( liquidado )
	{
		_liquidado = liquidado;
	};

	/**
	  * getTotal
	  * 
	  * Get the <i>total</i> property for this object. Donde <i>total</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getTotal = function ()
	{
		return _total;
	};

	/**
	  * setTotal( $total )
	  * 
	  * Set the <i>total</i> property for this object. Donde <i>total</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>total</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotal  = function ( total )
	{
		_total = total;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CompraSucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		CompraSucursal._callback_stack.push( _original_callback  );
		CompraSucursal._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		CompraSucursal.getByPK(  this.getIdCompra() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraSucursal} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraSucursal [$compra_sucursal] El objeto de tipo CompraSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from compra_sucursal WHERE ("; 
		$val = [];
		if( this.getIdCompra() != null){
			$sql += " id_compra = ? AND";
			$val.push( this.getIdCompra() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getSubtotal() != null){
			$sql += " subtotal = ? AND";
			$val.push( this.getSubtotal() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getIdProveedor() != null){
			$sql += " id_proveedor = ? AND";
			$val.push( this.getIdProveedor() );
		}

		if( this.getPagado() != null){
			$sql += " pagado = ? AND";
			$val.push( this.getPagado() );
		}

		if( this.getLiquidado() != null){
			$sql += " liquidado = ? AND";
			$val.push( this.getLiquidado() );
		}

		if( this.getTotal() != null){
			$sql += " total = ? AND";
			$val.push( this.getTotal() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraSucursal($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CompraSucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CompraSucursal dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CompraSucursal [$compra_sucursal] El objeto de tipo CompraSucursal a crear.
	  **/
	var create = function( compra_sucursal )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO compra_sucursal ( id_compra, fecha, subtotal, id_sucursal, id_usuario, id_proveedor, pagado, liquidado, total ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			compra_sucursal.getIdCompra(), 
			compra_sucursal.getFecha(), 
			compra_sucursal.getSubtotal(), 
			compra_sucursal.getIdSucursal(), 
			compra_sucursal.getIdUsuario(), 
			compra_sucursal.getIdProveedor(), 
			compra_sucursal.getPagado(), 
			compra_sucursal.getLiquidado(), 
			compra_sucursal.getTotal(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				CompraSucursal._callback_stack.pop().call(null, compra_sucursal);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param CompraSucursal [$compra_sucursal] El objeto de tipo CompraSucursal a actualizar.
	  **/
	var update = function( $compra_sucursal )
	{
		$sql = "UPDATE compra_sucursal SET  fecha = ?, subtotal = ?, id_sucursal = ?, id_usuario = ?, id_proveedor = ?, pagado = ?, liquidado = ?, total = ? WHERE  id_compra = ?;";
		$params = [ 
			$compra_sucursal.getFecha(), 
			$compra_sucursal.getSubtotal(), 
			$compra_sucursal.getIdSucursal(), 
			$compra_sucursal.getIdUsuario(), 
			$compra_sucursal.getIdProveedor(), 
			$compra_sucursal.getPagado(), 
			$compra_sucursal.getLiquidado(), 
			$compra_sucursal.getTotal(), 
			$compra_sucursal.getIdCompra(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				CompraSucursal._callback_stack.pop().call(null, $compra_sucursal);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CompraSucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( CompraSucursal.getByPK(this.getIdCompra()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM compra_sucursal WHERE  id_compra = ?;";
		$params = [ this.getIdCompra() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraSucursal} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CompraSucursal}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraSucursal [$compra_sucursal] El objeto de tipo CompraSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $compra_sucursal , $orderBy , $orden )
	{
		$sql = "SELECT * from compra_sucursal WHERE ("; 
		$val = [];
		if( (($a = this.getIdCompra()) != null) & ( ($b = $compra_sucursal.getIdCompra()) != null) ){
				$sql += " id_compra >= ? AND id_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $compra_sucursal.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSubtotal()) != null) & ( ($b = $compra_sucursal.getSubtotal()) != null) ){
				$sql += " subtotal >= ? AND subtotal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " subtotal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $compra_sucursal.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $compra_sucursal.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdProveedor()) != null) & ( ($b = $compra_sucursal.getIdProveedor()) != null) ){
				$sql += " id_proveedor >= ? AND id_proveedor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_proveedor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPagado()) != null) & ( ($b = $compra_sucursal.getPagado()) != null) ){
				$sql += " pagado >= ? AND pagado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " pagado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLiquidado()) != null) & ( ($b = $compra_sucursal.getLiquidado()) != null) ){
				$sql += " liquidado >= ? AND liquidado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " liquidado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotal()) != null) & ( ($b = $compra_sucursal.getTotal()) != null) ){
				$sql += " total >= ? AND total <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraSucursal($foo));
		//}
		return $sql;
	};


}
	CompraSucursal._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CompraSucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	CompraSucursal.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from compra_sucursal";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new CompraSucursal( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link CompraSucursal} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CompraSucursal} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CompraSucursal Un objeto del tipo {@link CompraSucursal}. NULL si no hay tal registro.
	  **/
	CompraSucursal.getByPK = function(  $id_compra, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM compra_sucursal WHERE (id_compra = ? ) LIMIT 1;";
		db.query($sql, [ $id_compra] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new CompraSucursal(results.rows.item(0)); 
				CompraSucursal._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : CompraSucursal._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table corte.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Corte = function ( config )
{
 /**
	* id_corte
	* 
	* identificador del corte
	* <b>Llave Primaria</b>
	* @access private
	* @var int(12)
	*/
	var _id_corte = config === undefined ? null : config.id_corte || null,

 /**
	* fecha
	* 
	* fecha de este corte
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null,

 /**
	* id_sucursal
	* 
	* sucursal a la que se realizo este corte
	* @access private
	* @var int(12)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* total_ventas
	* 
	* total de activo realizado en ventas para esta sucursal incluyendo ventas a credito y ventas a contado aunque no esten saldadas
	* @access private
	* @var float
	*/
	_total_ventas = config === undefined ? null : config.total_ventas || null,

 /**
	* total_ventas_abonado
	* 
	* total de efectivo adquirido gracias a ventas, incluye ventas a contado y los abonos de las ventas a credito
	* @access private
	* @var float
	*/
	_total_ventas_abonado = config === undefined ? null : config.total_ventas_abonado || null,

 /**
	* total_ventas_saldo
	* 
	* total de dinero que se le debe a esta sucursal por ventas a credito
	* @access private
	* @var float
	*/
	_total_ventas_saldo = config === undefined ? null : config.total_ventas_saldo || null,

 /**
	* total_compras
	* 
	* total de gastado en compras
	* @access private
	* @var float
	*/
	_total_compras = config === undefined ? null : config.total_compras || null,

 /**
	* total_compras_abonado
	* 
	* total de abonado en compras
	* @access private
	* @var float
	*/
	_total_compras_abonado = config === undefined ? null : config.total_compras_abonado || null,

 /**
	* total_gastos
	* 
	* total de gastos con saldo o sin salgo
	* @access private
	* @var float
	*/
	_total_gastos = config === undefined ? null : config.total_gastos || null,

 /**
	* total_gastos_abonado
	* 
	* total de gastos pagados ya
	* @access private
	* @var float
	*/
	_total_gastos_abonado = config === undefined ? null : config.total_gastos_abonado || null,

 /**
	* total_ingresos
	* 
	* total de ingresos para esta sucursal desde el ultimo corte
	* @access private
	* @var float
	*/
	_total_ingresos = config === undefined ? null : config.total_ingresos || null,

 /**
	* total_ganancia_neta
	* 
	* calculo de ganancia neta
	* @access private
	* @var float
	*/
	_total_ganancia_neta = config === undefined ? null : config.total_ganancia_neta || null;

	/**
	  * getIdCorte
	  * 
	  * Get the <i>id_corte</i> property for this object. Donde <i>id_corte</i> es identificador del corte
	  * @return int(12)
	  */
	this.getIdCorte = function ()
	{
		return _id_corte;
	};

	/**
	  * setIdCorte( $id_corte )
	  * 
	  * Set the <i>id_corte</i> property for this object. Donde <i>id_corte</i> es identificador del corte.
	  * Una validacion basica se hara aqui para comprobar que <i>id_corte</i> es de tipo <i>int(12)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCorte( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(12)
	  */
	this.setIdCorte  = function ( id_corte )
	{
		_id_corte = id_corte;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de este corte
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de este corte.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal a la que se realizo este corte
	  * @return int(12)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal a la que se realizo este corte.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(12)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(12)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getTotalVentas
	  * 
	  * Get the <i>total_ventas</i> property for this object. Donde <i>total_ventas</i> es total de activo realizado en ventas para esta sucursal incluyendo ventas a credito y ventas a contado aunque no esten saldadas
	  * @return float
	  */
	this.getTotalVentas = function ()
	{
		return _total_ventas;
	};

	/**
	  * setTotalVentas( $total_ventas )
	  * 
	  * Set the <i>total_ventas</i> property for this object. Donde <i>total_ventas</i> es total de activo realizado en ventas para esta sucursal incluyendo ventas a credito y ventas a contado aunque no esten saldadas.
	  * Una validacion basica se hara aqui para comprobar que <i>total_ventas</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotalVentas  = function ( total_ventas )
	{
		_total_ventas = total_ventas;
	};

	/**
	  * getTotalVentasAbonado
	  * 
	  * Get the <i>total_ventas_abonado</i> property for this object. Donde <i>total_ventas_abonado</i> es total de efectivo adquirido gracias a ventas, incluye ventas a contado y los abonos de las ventas a credito
	  * @return float
	  */
	this.getTotalVentasAbonado = function ()
	{
		return _total_ventas_abonado;
	};

	/**
	  * setTotalVentasAbonado( $total_ventas_abonado )
	  * 
	  * Set the <i>total_ventas_abonado</i> property for this object. Donde <i>total_ventas_abonado</i> es total de efectivo adquirido gracias a ventas, incluye ventas a contado y los abonos de las ventas a credito.
	  * Una validacion basica se hara aqui para comprobar que <i>total_ventas_abonado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotalVentasAbonado  = function ( total_ventas_abonado )
	{
		_total_ventas_abonado = total_ventas_abonado;
	};

	/**
	  * getTotalVentasSaldo
	  * 
	  * Get the <i>total_ventas_saldo</i> property for this object. Donde <i>total_ventas_saldo</i> es total de dinero que se le debe a esta sucursal por ventas a credito
	  * @return float
	  */
	this.getTotalVentasSaldo = function ()
	{
		return _total_ventas_saldo;
	};

	/**
	  * setTotalVentasSaldo( $total_ventas_saldo )
	  * 
	  * Set the <i>total_ventas_saldo</i> property for this object. Donde <i>total_ventas_saldo</i> es total de dinero que se le debe a esta sucursal por ventas a credito.
	  * Una validacion basica se hara aqui para comprobar que <i>total_ventas_saldo</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotalVentasSaldo  = function ( total_ventas_saldo )
	{
		_total_ventas_saldo = total_ventas_saldo;
	};

	/**
	  * getTotalCompras
	  * 
	  * Get the <i>total_compras</i> property for this object. Donde <i>total_compras</i> es total de gastado en compras
	  * @return float
	  */
	this.getTotalCompras = function ()
	{
		return _total_compras;
	};

	/**
	  * setTotalCompras( $total_compras )
	  * 
	  * Set the <i>total_compras</i> property for this object. Donde <i>total_compras</i> es total de gastado en compras.
	  * Una validacion basica se hara aqui para comprobar que <i>total_compras</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotalCompras  = function ( total_compras )
	{
		_total_compras = total_compras;
	};

	/**
	  * getTotalComprasAbonado
	  * 
	  * Get the <i>total_compras_abonado</i> property for this object. Donde <i>total_compras_abonado</i> es total de abonado en compras
	  * @return float
	  */
	this.getTotalComprasAbonado = function ()
	{
		return _total_compras_abonado;
	};

	/**
	  * setTotalComprasAbonado( $total_compras_abonado )
	  * 
	  * Set the <i>total_compras_abonado</i> property for this object. Donde <i>total_compras_abonado</i> es total de abonado en compras.
	  * Una validacion basica se hara aqui para comprobar que <i>total_compras_abonado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotalComprasAbonado  = function ( total_compras_abonado )
	{
		_total_compras_abonado = total_compras_abonado;
	};

	/**
	  * getTotalGastos
	  * 
	  * Get the <i>total_gastos</i> property for this object. Donde <i>total_gastos</i> es total de gastos con saldo o sin salgo
	  * @return float
	  */
	this.getTotalGastos = function ()
	{
		return _total_gastos;
	};

	/**
	  * setTotalGastos( $total_gastos )
	  * 
	  * Set the <i>total_gastos</i> property for this object. Donde <i>total_gastos</i> es total de gastos con saldo o sin salgo.
	  * Una validacion basica se hara aqui para comprobar que <i>total_gastos</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotalGastos  = function ( total_gastos )
	{
		_total_gastos = total_gastos;
	};

	/**
	  * getTotalGastosAbonado
	  * 
	  * Get the <i>total_gastos_abonado</i> property for this object. Donde <i>total_gastos_abonado</i> es total de gastos pagados ya
	  * @return float
	  */
	this.getTotalGastosAbonado = function ()
	{
		return _total_gastos_abonado;
	};

	/**
	  * setTotalGastosAbonado( $total_gastos_abonado )
	  * 
	  * Set the <i>total_gastos_abonado</i> property for this object. Donde <i>total_gastos_abonado</i> es total de gastos pagados ya.
	  * Una validacion basica se hara aqui para comprobar que <i>total_gastos_abonado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotalGastosAbonado  = function ( total_gastos_abonado )
	{
		_total_gastos_abonado = total_gastos_abonado;
	};

	/**
	  * getTotalIngresos
	  * 
	  * Get the <i>total_ingresos</i> property for this object. Donde <i>total_ingresos</i> es total de ingresos para esta sucursal desde el ultimo corte
	  * @return float
	  */
	this.getTotalIngresos = function ()
	{
		return _total_ingresos;
	};

	/**
	  * setTotalIngresos( $total_ingresos )
	  * 
	  * Set the <i>total_ingresos</i> property for this object. Donde <i>total_ingresos</i> es total de ingresos para esta sucursal desde el ultimo corte.
	  * Una validacion basica se hara aqui para comprobar que <i>total_ingresos</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotalIngresos  = function ( total_ingresos )
	{
		_total_ingresos = total_ingresos;
	};

	/**
	  * getTotalGananciaNeta
	  * 
	  * Get the <i>total_ganancia_neta</i> property for this object. Donde <i>total_ganancia_neta</i> es calculo de ganancia neta
	  * @return float
	  */
	this.getTotalGananciaNeta = function ()
	{
		return _total_ganancia_neta;
	};

	/**
	  * setTotalGananciaNeta( $total_ganancia_neta )
	  * 
	  * Set the <i>total_ganancia_neta</i> property for this object. Donde <i>total_ganancia_neta</i> es calculo de ganancia neta.
	  * Una validacion basica se hara aqui para comprobar que <i>total_ganancia_neta</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotalGananciaNeta  = function ( total_ganancia_neta )
	{
		_total_ganancia_neta = total_ganancia_neta;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Corte} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Corte._callback_stack.push( _original_callback  );
		Corte._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Corte.getByPK(  this.getIdCorte() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Corte} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Corte [$corte] El objeto de tipo Corte
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from corte WHERE ("; 
		$val = [];
		if( this.getIdCorte() != null){
			$sql += " id_corte = ? AND";
			$val.push( this.getIdCorte() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getTotalVentas() != null){
			$sql += " total_ventas = ? AND";
			$val.push( this.getTotalVentas() );
		}

		if( this.getTotalVentasAbonado() != null){
			$sql += " total_ventas_abonado = ? AND";
			$val.push( this.getTotalVentasAbonado() );
		}

		if( this.getTotalVentasSaldo() != null){
			$sql += " total_ventas_saldo = ? AND";
			$val.push( this.getTotalVentasSaldo() );
		}

		if( this.getTotalCompras() != null){
			$sql += " total_compras = ? AND";
			$val.push( this.getTotalCompras() );
		}

		if( this.getTotalComprasAbonado() != null){
			$sql += " total_compras_abonado = ? AND";
			$val.push( this.getTotalComprasAbonado() );
		}

		if( this.getTotalGastos() != null){
			$sql += " total_gastos = ? AND";
			$val.push( this.getTotalGastos() );
		}

		if( this.getTotalGastosAbonado() != null){
			$sql += " total_gastos_abonado = ? AND";
			$val.push( this.getTotalGastosAbonado() );
		}

		if( this.getTotalIngresos() != null){
			$sql += " total_ingresos = ? AND";
			$val.push( this.getTotalIngresos() );
		}

		if( this.getTotalGananciaNeta() != null){
			$sql += " total_ganancia_neta = ? AND";
			$val.push( this.getTotalGananciaNeta() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Corte($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Corte suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Corte dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Corte [$corte] El objeto de tipo Corte a crear.
	  **/
	var create = function( corte )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO corte ( id_corte, fecha, id_sucursal, total_ventas, total_ventas_abonado, total_ventas_saldo, total_compras, total_compras_abonado, total_gastos, total_gastos_abonado, total_ingresos, total_ganancia_neta ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			corte.getIdCorte(), 
			corte.getFecha(), 
			corte.getIdSucursal(), 
			corte.getTotalVentas(), 
			corte.getTotalVentasAbonado(), 
			corte.getTotalVentasSaldo(), 
			corte.getTotalCompras(), 
			corte.getTotalComprasAbonado(), 
			corte.getTotalGastos(), 
			corte.getTotalGastosAbonado(), 
			corte.getTotalIngresos(), 
			corte.getTotalGananciaNeta(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Corte._callback_stack.pop().call(null, corte);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Corte [$corte] El objeto de tipo Corte a actualizar.
	  **/
	var update = function( $corte )
	{
		$sql = "UPDATE corte SET  fecha = ?, id_sucursal = ?, total_ventas = ?, total_ventas_abonado = ?, total_ventas_saldo = ?, total_compras = ?, total_compras_abonado = ?, total_gastos = ?, total_gastos_abonado = ?, total_ingresos = ?, total_ganancia_neta = ? WHERE  id_corte = ?;";
		$params = [ 
			$corte.getFecha(), 
			$corte.getIdSucursal(), 
			$corte.getTotalVentas(), 
			$corte.getTotalVentasAbonado(), 
			$corte.getTotalVentasSaldo(), 
			$corte.getTotalCompras(), 
			$corte.getTotalComprasAbonado(), 
			$corte.getTotalGastos(), 
			$corte.getTotalGastosAbonado(), 
			$corte.getTotalIngresos(), 
			$corte.getTotalGananciaNeta(), 
			$corte.getIdCorte(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Corte._callback_stack.pop().call(null, $corte);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Corte suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Corte.getByPK(this.getIdCorte()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM corte WHERE  id_corte = ?;";
		$params = [ this.getIdCorte() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Corte} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Corte}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Corte [$corte] El objeto de tipo Corte
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $corte , $orderBy , $orden )
	{
		$sql = "SELECT * from corte WHERE ("; 
		$val = [];
		if( (($a = this.getIdCorte()) != null) & ( ($b = $corte.getIdCorte()) != null) ){
				$sql += " id_corte >= ? AND id_corte <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_corte = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $corte.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $corte.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotalVentas()) != null) & ( ($b = $corte.getTotalVentas()) != null) ){
				$sql += " total_ventas >= ? AND total_ventas <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total_ventas = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotalVentasAbonado()) != null) & ( ($b = $corte.getTotalVentasAbonado()) != null) ){
				$sql += " total_ventas_abonado >= ? AND total_ventas_abonado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total_ventas_abonado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotalVentasSaldo()) != null) & ( ($b = $corte.getTotalVentasSaldo()) != null) ){
				$sql += " total_ventas_saldo >= ? AND total_ventas_saldo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total_ventas_saldo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotalCompras()) != null) & ( ($b = $corte.getTotalCompras()) != null) ){
				$sql += " total_compras >= ? AND total_compras <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total_compras = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotalComprasAbonado()) != null) & ( ($b = $corte.getTotalComprasAbonado()) != null) ){
				$sql += " total_compras_abonado >= ? AND total_compras_abonado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total_compras_abonado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotalGastos()) != null) & ( ($b = $corte.getTotalGastos()) != null) ){
				$sql += " total_gastos >= ? AND total_gastos <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total_gastos = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotalGastosAbonado()) != null) & ( ($b = $corte.getTotalGastosAbonado()) != null) ){
				$sql += " total_gastos_abonado >= ? AND total_gastos_abonado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total_gastos_abonado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotalIngresos()) != null) & ( ($b = $corte.getTotalIngresos()) != null) ){
				$sql += " total_ingresos >= ? AND total_ingresos <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total_ingresos = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotalGananciaNeta()) != null) & ( ($b = $corte.getTotalGananciaNeta()) != null) ){
				$sql += " total_ganancia_neta >= ? AND total_ganancia_neta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total_ganancia_neta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Corte($foo));
		//}
		return $sql;
	};


}
	Corte._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Corte}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Corte.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from corte";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Corte( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Corte} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Corte} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Corte Un objeto del tipo {@link Corte}. NULL si no hay tal registro.
	  **/
	Corte.getByPK = function(  $id_corte, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM corte WHERE (id_corte = ? ) LIMIT 1;";
		db.query($sql, [ $id_corte] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Corte(results.rows.item(0)); 
				Corte._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Corte._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table detalle_compra_cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var DetalleCompraCliente = function ( config )
{
 /**
	* id_compra
	* 
	* compra a que se referencia
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_compra = config === undefined ? null : config.id_compra || null,

 /**
	* id_producto
	* 
	* producto de la compra
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	_id_producto = config === undefined ? null : config.id_producto || null,

 /**
	* cantidad
	* 
	* cantidad que se compro
	* @access private
	* @var float
	*/
	_cantidad = config === undefined ? null : config.cantidad || null,

 /**
	* precio
	* 
	* precio al que se compro
	* @access private
	* @var float
	*/
	_precio = config === undefined ? null : config.precio || null,

 /**
	* descuento
	* 
	* indica cuanto producto original se va a descontar de ese producto en esa compra
	* @access private
	* @var float
	*/
	_descuento = config === undefined ? null : config.descuento || null;

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es compra a que se referencia
	  * @return int(11)
	  */
	this.getIdCompra = function ()
	{
		return _id_compra;
	};

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es compra a que se referencia.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompra( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdCompra  = function ( id_compra )
	{
		_id_compra = id_compra;
	};

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es producto de la compra
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es producto de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad que se compro
	  * @return float
	  */
	this.getCantidad = function ()
	{
		return _cantidad;
	};

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad que se compro.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setCantidad  = function ( cantidad )
	{
		_cantidad = cantidad;
	};

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se compro
	  * @return float
	  */
	this.getPrecio = function ()
	{
		return _precio;
	};

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se compro.
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecio  = function ( precio )
	{
		_precio = precio;
	};

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es indica cuanto producto original se va a descontar de ese producto en esa compra
	  * @return float
	  */
	this.getDescuento = function ()
	{
		return _descuento;
	};

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es indica cuanto producto original se va a descontar de ese producto en esa compra.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setDescuento  = function ( descuento )
	{
		_descuento = descuento;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleCompraCliente} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		DetalleCompraCliente._callback_stack.push( _original_callback  );
		DetalleCompraCliente._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		DetalleCompraCliente.getByPK(  this.getIdCompra() , this.getIdProducto() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraCliente} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_compra_cliente WHERE ("; 
		$val = [];
		if( this.getIdCompra() != null){
			$sql += " id_compra = ? AND";
			$val.push( this.getIdCompra() );
		}

		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getCantidad() != null){
			$sql += " cantidad = ? AND";
			$val.push( this.getCantidad() );
		}

		if( this.getPrecio() != null){
			$sql += " precio = ? AND";
			$val.push( this.getPrecio() );
		}

		if( this.getDescuento() != null){
			$sql += " descuento = ? AND";
			$val.push( this.getDescuento() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleCompraCliente($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleCompraCliente suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleCompraCliente dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente a crear.
	  **/
	var create = function( detalle_compra_cliente )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO detalle_compra_cliente ( id_compra, id_producto, cantidad, precio, descuento ) VALUES ( ?, ?, ?, ?, ?);";
		$params = [
			detalle_compra_cliente.getIdCompra(), 
			detalle_compra_cliente.getIdProducto(), 
			detalle_compra_cliente.getCantidad(), 
			detalle_compra_cliente.getPrecio(), 
			detalle_compra_cliente.getDescuento(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				DetalleCompraCliente._callback_stack.pop().call(null, detalle_compra_cliente);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente a actualizar.
	  **/
	var update = function( $detalle_compra_cliente )
	{
		$sql = "UPDATE detalle_compra_cliente SET  cantidad = ?, precio = ?, descuento = ? WHERE  id_compra = ? AND id_producto = ?;";
		$params = [ 
			$detalle_compra_cliente.getCantidad(), 
			$detalle_compra_cliente.getPrecio(), 
			$detalle_compra_cliente.getDescuento(), 
			$detalle_compra_cliente.getIdCompra(),$detalle_compra_cliente.getIdProducto(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				DetalleCompraCliente._callback_stack.pop().call(null, $detalle_compra_cliente);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleCompraCliente suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( DetalleCompraCliente.getByPK(this.getIdCompra(), this.getIdProducto()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_compra_cliente WHERE  id_compra = ? AND id_producto = ?;";
		$params = [ this.getIdCompra(), this.getIdProducto() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraCliente} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleCompraCliente}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $detalle_compra_cliente , $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_compra_cliente WHERE ("; 
		$val = [];
		if( (($a = this.getIdCompra()) != null) & ( ($b = $detalle_compra_cliente.getIdCompra()) != null) ){
				$sql += " id_compra >= ? AND id_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdProducto()) != null) & ( ($b = $detalle_compra_cliente.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCantidad()) != null) & ( ($b = $detalle_compra_cliente.getCantidad()) != null) ){
				$sql += " cantidad >= ? AND cantidad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cantidad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecio()) != null) & ( ($b = $detalle_compra_cliente.getPrecio()) != null) ){
				$sql += " precio >= ? AND precio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescuento()) != null) & ( ($b = $detalle_compra_cliente.getDescuento()) != null) ){
				$sql += " descuento >= ? AND descuento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descuento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleCompraCliente($foo));
		//}
		return $sql;
	};


}
	DetalleCompraCliente._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleCompraCliente}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	DetalleCompraCliente.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from detalle_compra_cliente";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new DetalleCompraCliente( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link DetalleCompraCliente} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleCompraCliente} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleCompraCliente Un objeto del tipo {@link DetalleCompraCliente}. NULL si no hay tal registro.
	  **/
	DetalleCompraCliente.getByPK = function(  $id_compra, $id_producto, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM detalle_compra_cliente WHERE (id_compra = ? AND id_producto = ? ) LIMIT 1;";
		db.query($sql, [ $id_compra, $id_producto] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new DetalleCompraCliente(results.rows.item(0)); 
				DetalleCompraCliente._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : DetalleCompraCliente._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table detalle_compra_proveedor.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var DetalleCompraProveedor = function ( config )
{
 /**
	* id_compra_proveedor
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_compra_proveedor = config === undefined ? null : config.id_compra_proveedor || null,

 /**
	* id_producto
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	_id_producto = config === undefined ? null : config.id_producto || null,

 /**
	* variedad
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(64)
	*/
	_variedad = config === undefined ? null : config.variedad || null,

 /**
	* arpillas
	* 
	*  [Campo no documentado]
	* @access private
	* @var int(11)
	*/
	_arpillas = config === undefined ? null : config.arpillas || null,

 /**
	* kg
	* 
	*  [Campo no documentado]
	* @access private
	* @var int(11)
	*/
	_kg = config === undefined ? null : config.kg || null,

 /**
	* precio_por_kg
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_precio_por_kg = config === undefined ? null : config.precio_por_kg || null;

	/**
	  * getIdCompraProveedor
	  * 
	  * Get the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdCompraProveedor = function ()
	{
		return _id_compra_proveedor;
	};

	/**
	  * setIdCompraProveedor( $id_compra_proveedor )
	  * 
	  * Set the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompraProveedor( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdCompraProveedor  = function ( id_compra_proveedor )
	{
		_id_compra_proveedor = id_compra_proveedor;
	};

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getVariedad
	  * 
	  * Get the <i>variedad</i> property for this object. Donde <i>variedad</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	this.getVariedad = function ()
	{
		return _variedad;
	};

	/**
	  * setVariedad( $variedad )
	  * 
	  * Set the <i>variedad</i> property for this object. Donde <i>variedad</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>variedad</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	this.setVariedad  = function ( variedad )
	{
		_variedad = variedad;
	};

	/**
	  * getArpillas
	  * 
	  * Get the <i>arpillas</i> property for this object. Donde <i>arpillas</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getArpillas = function ()
	{
		return _arpillas;
	};

	/**
	  * setArpillas( $arpillas )
	  * 
	  * Set the <i>arpillas</i> property for this object. Donde <i>arpillas</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>arpillas</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setArpillas  = function ( arpillas )
	{
		_arpillas = arpillas;
	};

	/**
	  * getKg
	  * 
	  * Get the <i>kg</i> property for this object. Donde <i>kg</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getKg = function ()
	{
		return _kg;
	};

	/**
	  * setKg( $kg )
	  * 
	  * Set the <i>kg</i> property for this object. Donde <i>kg</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>kg</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setKg  = function ( kg )
	{
		_kg = kg;
	};

	/**
	  * getPrecioPorKg
	  * 
	  * Get the <i>precio_por_kg</i> property for this object. Donde <i>precio_por_kg</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getPrecioPorKg = function ()
	{
		return _precio_por_kg;
	};

	/**
	  * setPrecioPorKg( $precio_por_kg )
	  * 
	  * Set the <i>precio_por_kg</i> property for this object. Donde <i>precio_por_kg</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_por_kg</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioPorKg  = function ( precio_por_kg )
	{
		_precio_por_kg = precio_por_kg;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleCompraProveedor} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		DetalleCompraProveedor._callback_stack.push( _original_callback  );
		DetalleCompraProveedor._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		DetalleCompraProveedor.getByPK(  this.getIdCompraProveedor() , this.getIdProducto() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraProveedor} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleCompraProveedor [$detalle_compra_proveedor] El objeto de tipo DetalleCompraProveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_compra_proveedor WHERE ("; 
		$val = [];
		if( this.getIdCompraProveedor() != null){
			$sql += " id_compra_proveedor = ? AND";
			$val.push( this.getIdCompraProveedor() );
		}

		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getVariedad() != null){
			$sql += " variedad = ? AND";
			$val.push( this.getVariedad() );
		}

		if( this.getArpillas() != null){
			$sql += " arpillas = ? AND";
			$val.push( this.getArpillas() );
		}

		if( this.getKg() != null){
			$sql += " kg = ? AND";
			$val.push( this.getKg() );
		}

		if( this.getPrecioPorKg() != null){
			$sql += " precio_por_kg = ? AND";
			$val.push( this.getPrecioPorKg() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleCompraProveedor($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleCompraProveedor suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleCompraProveedor dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleCompraProveedor [$detalle_compra_proveedor] El objeto de tipo DetalleCompraProveedor a crear.
	  **/
	var create = function( detalle_compra_proveedor )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO detalle_compra_proveedor ( id_compra_proveedor, id_producto, variedad, arpillas, kg, precio_por_kg ) VALUES ( ?, ?, ?, ?, ?, ?);";
		$params = [
			detalle_compra_proveedor.getIdCompraProveedor(), 
			detalle_compra_proveedor.getIdProducto(), 
			detalle_compra_proveedor.getVariedad(), 
			detalle_compra_proveedor.getArpillas(), 
			detalle_compra_proveedor.getKg(), 
			detalle_compra_proveedor.getPrecioPorKg(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				DetalleCompraProveedor._callback_stack.pop().call(null, detalle_compra_proveedor);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param DetalleCompraProveedor [$detalle_compra_proveedor] El objeto de tipo DetalleCompraProveedor a actualizar.
	  **/
	var update = function( $detalle_compra_proveedor )
	{
		$sql = "UPDATE detalle_compra_proveedor SET  variedad = ?, arpillas = ?, kg = ?, precio_por_kg = ? WHERE  id_compra_proveedor = ? AND id_producto = ?;";
		$params = [ 
			$detalle_compra_proveedor.getVariedad(), 
			$detalle_compra_proveedor.getArpillas(), 
			$detalle_compra_proveedor.getKg(), 
			$detalle_compra_proveedor.getPrecioPorKg(), 
			$detalle_compra_proveedor.getIdCompraProveedor(),$detalle_compra_proveedor.getIdProducto(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				DetalleCompraProveedor._callback_stack.pop().call(null, $detalle_compra_proveedor);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleCompraProveedor suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( DetalleCompraProveedor.getByPK(this.getIdCompraProveedor(), this.getIdProducto()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_compra_proveedor WHERE  id_compra_proveedor = ? AND id_producto = ?;";
		$params = [ this.getIdCompraProveedor(), this.getIdProducto() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraProveedor} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleCompraProveedor}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleCompraProveedor [$detalle_compra_proveedor] El objeto de tipo DetalleCompraProveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $detalle_compra_proveedor , $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_compra_proveedor WHERE ("; 
		$val = [];
		if( (($a = this.getIdCompraProveedor()) != null) & ( ($b = $detalle_compra_proveedor.getIdCompraProveedor()) != null) ){
				$sql += " id_compra_proveedor >= ? AND id_compra_proveedor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra_proveedor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdProducto()) != null) & ( ($b = $detalle_compra_proveedor.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getVariedad()) != null) & ( ($b = $detalle_compra_proveedor.getVariedad()) != null) ){
				$sql += " variedad >= ? AND variedad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " variedad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getArpillas()) != null) & ( ($b = $detalle_compra_proveedor.getArpillas()) != null) ){
				$sql += " arpillas >= ? AND arpillas <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " arpillas = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getKg()) != null) & ( ($b = $detalle_compra_proveedor.getKg()) != null) ){
				$sql += " kg >= ? AND kg <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " kg = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioPorKg()) != null) & ( ($b = $detalle_compra_proveedor.getPrecioPorKg()) != null) ){
				$sql += " precio_por_kg >= ? AND precio_por_kg <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_por_kg = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleCompraProveedor($foo));
		//}
		return $sql;
	};


}
	DetalleCompraProveedor._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleCompraProveedor}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	DetalleCompraProveedor.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from detalle_compra_proveedor";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new DetalleCompraProveedor( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link DetalleCompraProveedor} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleCompraProveedor} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleCompraProveedor Un objeto del tipo {@link DetalleCompraProveedor}. NULL si no hay tal registro.
	  **/
	DetalleCompraProveedor.getByPK = function(  $id_compra_proveedor, $id_producto, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM detalle_compra_proveedor WHERE (id_compra_proveedor = ? AND id_producto = ? ) LIMIT 1;";
		db.query($sql, [ $id_compra_proveedor, $id_producto] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new DetalleCompraProveedor(results.rows.item(0)); 
				DetalleCompraProveedor._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : DetalleCompraProveedor._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table detalle_compra_sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var DetalleCompraSucursal = function ( config )
{
 /**
	* id_detalle_compra_sucursal
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_detalle_compra_sucursal = config === undefined ? null : config.id_detalle_compra_sucursal || null,

 /**
	* id_compra
	* 
	* id de la compra
	* @access private
	* @var int(11)
	*/
	_id_compra = config === undefined ? null : config.id_compra || null,

 /**
	* id_producto
	* 
	* id del producto
	* @access private
	* @var int(11)
	*/
	_id_producto = config === undefined ? null : config.id_producto || null,

 /**
	* cantidad
	* 
	* cantidad comprada
	* @access private
	* @var float
	*/
	_cantidad = config === undefined ? null : config.cantidad || null,

 /**
	* precio
	* 
	* costo de compra
	* @access private
	* @var float
	*/
	_precio = config === undefined ? null : config.precio || null,

 /**
	* descuento
	* 
	*  [Campo no documentado]
	* @access private
	* @var int(11)
	*/
	_descuento = config === undefined ? null : config.descuento || null,

 /**
	* procesadas
	* 
	* verdadero si este detalle se refiere a compras procesadas (limpias)
	* @access private
	* @var tinyint(1)
	*/
	_procesadas = config === undefined ? null : config.procesadas || null;

	/**
	  * getIdDetalleCompraSucursal
	  * 
	  * Get the <i>id_detalle_compra_sucursal</i> property for this object. Donde <i>id_detalle_compra_sucursal</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdDetalleCompraSucursal = function ()
	{
		return _id_detalle_compra_sucursal;
	};

	/**
	  * setIdDetalleCompraSucursal( $id_detalle_compra_sucursal )
	  * 
	  * Set the <i>id_detalle_compra_sucursal</i> property for this object. Donde <i>id_detalle_compra_sucursal</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_detalle_compra_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdDetalleCompraSucursal( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdDetalleCompraSucursal  = function ( id_detalle_compra_sucursal )
	{
		_id_detalle_compra_sucursal = id_detalle_compra_sucursal;
	};

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de la compra
	  * @return int(11)
	  */
	this.getIdCompra = function ()
	{
		return _id_compra;
	};

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdCompra  = function ( id_compra )
	{
		_id_compra = id_compra;
	};

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es id del producto
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es id del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad comprada
	  * @return float
	  */
	this.getCantidad = function ()
	{
		return _cantidad;
	};

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad comprada.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setCantidad  = function ( cantidad )
	{
		_cantidad = cantidad;
	};

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es costo de compra
	  * @return float
	  */
	this.getPrecio = function ()
	{
		return _precio;
	};

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es costo de compra.
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecio  = function ( precio )
	{
		_precio = precio;
	};

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getDescuento = function ()
	{
		return _descuento;
	};

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setDescuento  = function ( descuento )
	{
		_descuento = descuento;
	};

	/**
	  * getProcesadas
	  * 
	  * Get the <i>procesadas</i> property for this object. Donde <i>procesadas</i> es verdadero si este detalle se refiere a compras procesadas (limpias)
	  * @return tinyint(1)
	  */
	this.getProcesadas = function ()
	{
		return _procesadas;
	};

	/**
	  * setProcesadas( $procesadas )
	  * 
	  * Set the <i>procesadas</i> property for this object. Donde <i>procesadas</i> es verdadero si este detalle se refiere a compras procesadas (limpias).
	  * Una validacion basica se hara aqui para comprobar que <i>procesadas</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setProcesadas  = function ( procesadas )
	{
		_procesadas = procesadas;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleCompraSucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		DetalleCompraSucursal._callback_stack.push( _original_callback  );
		DetalleCompraSucursal._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		DetalleCompraSucursal.getByPK(  this.getIdDetalleCompraSucursal() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraSucursal} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleCompraSucursal [$detalle_compra_sucursal] El objeto de tipo DetalleCompraSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_compra_sucursal WHERE ("; 
		$val = [];
		if( this.getIdDetalleCompraSucursal() != null){
			$sql += " id_detalle_compra_sucursal = ? AND";
			$val.push( this.getIdDetalleCompraSucursal() );
		}

		if( this.getIdCompra() != null){
			$sql += " id_compra = ? AND";
			$val.push( this.getIdCompra() );
		}

		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getCantidad() != null){
			$sql += " cantidad = ? AND";
			$val.push( this.getCantidad() );
		}

		if( this.getPrecio() != null){
			$sql += " precio = ? AND";
			$val.push( this.getPrecio() );
		}

		if( this.getDescuento() != null){
			$sql += " descuento = ? AND";
			$val.push( this.getDescuento() );
		}

		if( this.getProcesadas() != null){
			$sql += " procesadas = ? AND";
			$val.push( this.getProcesadas() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleCompraSucursal($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleCompraSucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleCompraSucursal dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleCompraSucursal [$detalle_compra_sucursal] El objeto de tipo DetalleCompraSucursal a crear.
	  **/
	var create = function( detalle_compra_sucursal )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO detalle_compra_sucursal ( id_detalle_compra_sucursal, id_compra, id_producto, cantidad, precio, descuento, procesadas ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			detalle_compra_sucursal.getIdDetalleCompraSucursal(), 
			detalle_compra_sucursal.getIdCompra(), 
			detalle_compra_sucursal.getIdProducto(), 
			detalle_compra_sucursal.getCantidad(), 
			detalle_compra_sucursal.getPrecio(), 
			detalle_compra_sucursal.getDescuento(), 
			detalle_compra_sucursal.getProcesadas(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				DetalleCompraSucursal._callback_stack.pop().call(null, detalle_compra_sucursal);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param DetalleCompraSucursal [$detalle_compra_sucursal] El objeto de tipo DetalleCompraSucursal a actualizar.
	  **/
	var update = function( $detalle_compra_sucursal )
	{
		$sql = "UPDATE detalle_compra_sucursal SET  id_compra = ?, id_producto = ?, cantidad = ?, precio = ?, descuento = ?, procesadas = ? WHERE  id_detalle_compra_sucursal = ?;";
		$params = [ 
			$detalle_compra_sucursal.getIdCompra(), 
			$detalle_compra_sucursal.getIdProducto(), 
			$detalle_compra_sucursal.getCantidad(), 
			$detalle_compra_sucursal.getPrecio(), 
			$detalle_compra_sucursal.getDescuento(), 
			$detalle_compra_sucursal.getProcesadas(), 
			$detalle_compra_sucursal.getIdDetalleCompraSucursal(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				DetalleCompraSucursal._callback_stack.pop().call(null, $detalle_compra_sucursal);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleCompraSucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( DetalleCompraSucursal.getByPK(this.getIdDetalleCompraSucursal()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_compra_sucursal WHERE  id_detalle_compra_sucursal = ?;";
		$params = [ this.getIdDetalleCompraSucursal() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraSucursal} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleCompraSucursal}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleCompraSucursal [$detalle_compra_sucursal] El objeto de tipo DetalleCompraSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $detalle_compra_sucursal , $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_compra_sucursal WHERE ("; 
		$val = [];
		if( (($a = this.getIdDetalleCompraSucursal()) != null) & ( ($b = $detalle_compra_sucursal.getIdDetalleCompraSucursal()) != null) ){
				$sql += " id_detalle_compra_sucursal >= ? AND id_detalle_compra_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_detalle_compra_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdCompra()) != null) & ( ($b = $detalle_compra_sucursal.getIdCompra()) != null) ){
				$sql += " id_compra >= ? AND id_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdProducto()) != null) & ( ($b = $detalle_compra_sucursal.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCantidad()) != null) & ( ($b = $detalle_compra_sucursal.getCantidad()) != null) ){
				$sql += " cantidad >= ? AND cantidad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cantidad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecio()) != null) & ( ($b = $detalle_compra_sucursal.getPrecio()) != null) ){
				$sql += " precio >= ? AND precio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescuento()) != null) & ( ($b = $detalle_compra_sucursal.getDescuento()) != null) ){
				$sql += " descuento >= ? AND descuento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descuento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getProcesadas()) != null) & ( ($b = $detalle_compra_sucursal.getProcesadas()) != null) ){
				$sql += " procesadas >= ? AND procesadas <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " procesadas = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleCompraSucursal($foo));
		//}
		return $sql;
	};


}
	DetalleCompraSucursal._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleCompraSucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	DetalleCompraSucursal.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from detalle_compra_sucursal";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new DetalleCompraSucursal( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link DetalleCompraSucursal} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleCompraSucursal} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleCompraSucursal Un objeto del tipo {@link DetalleCompraSucursal}. NULL si no hay tal registro.
	  **/
	DetalleCompraSucursal.getByPK = function(  $id_detalle_compra_sucursal, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM detalle_compra_sucursal WHERE (id_detalle_compra_sucursal = ? ) LIMIT 1;";
		db.query($sql, [ $id_detalle_compra_sucursal] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new DetalleCompraSucursal(results.rows.item(0)); 
				DetalleCompraSucursal._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : DetalleCompraSucursal._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table detalle_inventario.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var DetalleInventario = function ( config )
{
 /**
	* id_producto
	* 
	* identificador del proudcto en inventario
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_producto = config === undefined ? null : config.id_producto || null,

 /**
	* id_sucursal
	* 
	* id de la sucursal
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* precio_venta
	* 
	* precio al que se vendera al publico
	* @access private
	* @var float
	*/
	_precio_venta = config === undefined ? null : config.precio_venta || null,

 /**
	* precio_venta_procesado
	* 
	* cuando este producto tiene tratamiento este sera su precio de venta al estar procesado
	* @access private
	* @var float
	*/
	_precio_venta_procesado = config === undefined ? null : config.precio_venta_procesado || null,

 /**
	* existencias
	* 
	* cantidad de producto que hay actualmente en almacen de esta sucursal (originales+procesadas)
	* @access private
	* @var float
	*/
	_existencias = config === undefined ? null : config.existencias || null,

 /**
	* existencias_procesadas
	* 
	* cantidad de producto solamente procesado !
	* @access private
	* @var float
	*/
	_existencias_procesadas = config === undefined ? null : config.existencias_procesadas || null,

 /**
	* precio_compra
	* 
	* El precio de compra para este producto en esta sucursal, siempre y cuando este punto de venta tenga el modulo POS_COMPRA_A_CLIENTES
	* @access private
	* @var float
	*/
	_precio_compra = config === undefined ? null : config.precio_compra || null;

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es identificador del proudcto en inventario
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es identificador del proudcto en inventario.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdSucursal( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getPrecioVenta
	  * 
	  * Get the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es precio al que se vendera al publico
	  * @return float
	  */
	this.getPrecioVenta = function ()
	{
		return _precio_venta;
	};

	/**
	  * setPrecioVenta( $precio_venta )
	  * 
	  * Set the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es precio al que se vendera al publico.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_venta</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioVenta  = function ( precio_venta )
	{
		_precio_venta = precio_venta;
	};

	/**
	  * getPrecioVentaProcesado
	  * 
	  * Get the <i>precio_venta_procesado</i> property for this object. Donde <i>precio_venta_procesado</i> es cuando este producto tiene tratamiento este sera su precio de venta al estar procesado
	  * @return float
	  */
	this.getPrecioVentaProcesado = function ()
	{
		return _precio_venta_procesado;
	};

	/**
	  * setPrecioVentaProcesado( $precio_venta_procesado )
	  * 
	  * Set the <i>precio_venta_procesado</i> property for this object. Donde <i>precio_venta_procesado</i> es cuando este producto tiene tratamiento este sera su precio de venta al estar procesado.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_venta_procesado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioVentaProcesado  = function ( precio_venta_procesado )
	{
		_precio_venta_procesado = precio_venta_procesado;
	};

	/**
	  * getExistencias
	  * 
	  * Get the <i>existencias</i> property for this object. Donde <i>existencias</i> es cantidad de producto que hay actualmente en almacen de esta sucursal (originales+procesadas)
	  * @return float
	  */
	this.getExistencias = function ()
	{
		return _existencias;
	};

	/**
	  * setExistencias( $existencias )
	  * 
	  * Set the <i>existencias</i> property for this object. Donde <i>existencias</i> es cantidad de producto que hay actualmente en almacen de esta sucursal (originales+procesadas).
	  * Una validacion basica se hara aqui para comprobar que <i>existencias</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setExistencias  = function ( existencias )
	{
		_existencias = existencias;
	};

	/**
	  * getExistenciasProcesadas
	  * 
	  * Get the <i>existencias_procesadas</i> property for this object. Donde <i>existencias_procesadas</i> es cantidad de producto solamente procesado !
	  * @return float
	  */
	this.getExistenciasProcesadas = function ()
	{
		return _existencias_procesadas;
	};

	/**
	  * setExistenciasProcesadas( $existencias_procesadas )
	  * 
	  * Set the <i>existencias_procesadas</i> property for this object. Donde <i>existencias_procesadas</i> es cantidad de producto solamente procesado !.
	  * Una validacion basica se hara aqui para comprobar que <i>existencias_procesadas</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setExistenciasProcesadas  = function ( existencias_procesadas )
	{
		_existencias_procesadas = existencias_procesadas;
	};

	/**
	  * getPrecioCompra
	  * 
	  * Get the <i>precio_compra</i> property for this object. Donde <i>precio_compra</i> es El precio de compra para este producto en esta sucursal, siempre y cuando este punto de venta tenga el modulo POS_COMPRA_A_CLIENTES
	  * @return float
	  */
	this.getPrecioCompra = function ()
	{
		return _precio_compra;
	};

	/**
	  * setPrecioCompra( $precio_compra )
	  * 
	  * Set the <i>precio_compra</i> property for this object. Donde <i>precio_compra</i> es El precio de compra para este producto en esta sucursal, siempre y cuando este punto de venta tenga el modulo POS_COMPRA_A_CLIENTES.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_compra</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioCompra  = function ( precio_compra )
	{
		_precio_compra = precio_compra;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleInventario} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		DetalleInventario._callback_stack.push( _original_callback  );
		DetalleInventario._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		DetalleInventario.getByPK(  this.getIdProducto() , this.getIdSucursal() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleInventario} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_inventario WHERE ("; 
		$val = [];
		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getPrecioVenta() != null){
			$sql += " precio_venta = ? AND";
			$val.push( this.getPrecioVenta() );
		}

		if( this.getPrecioVentaProcesado() != null){
			$sql += " precio_venta_procesado = ? AND";
			$val.push( this.getPrecioVentaProcesado() );
		}

		if( this.getExistencias() != null){
			$sql += " existencias = ? AND";
			$val.push( this.getExistencias() );
		}

		if( this.getExistenciasProcesadas() != null){
			$sql += " existencias_procesadas = ? AND";
			$val.push( this.getExistenciasProcesadas() );
		}

		if( this.getPrecioCompra() != null){
			$sql += " precio_compra = ? AND";
			$val.push( this.getPrecioCompra() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleInventario($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleInventario suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleInventario dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario a crear.
	  **/
	var create = function( detalle_inventario )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO detalle_inventario ( id_producto, id_sucursal, precio_venta, precio_venta_procesado, existencias, existencias_procesadas, precio_compra ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			detalle_inventario.getIdProducto(), 
			detalle_inventario.getIdSucursal(), 
			detalle_inventario.getPrecioVenta(), 
			detalle_inventario.getPrecioVentaProcesado(), 
			detalle_inventario.getExistencias(), 
			detalle_inventario.getExistenciasProcesadas(), 
			detalle_inventario.getPrecioCompra(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				DetalleInventario._callback_stack.pop().call(null, detalle_inventario);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario a actualizar.
	  **/
	var update = function( $detalle_inventario )
	{
		$sql = "UPDATE detalle_inventario SET  precio_venta = ?, precio_venta_procesado = ?, existencias = ?, existencias_procesadas = ?, precio_compra = ? WHERE  id_producto = ? AND id_sucursal = ?;";
		$params = [ 
			$detalle_inventario.getPrecioVenta(), 
			$detalle_inventario.getPrecioVentaProcesado(), 
			$detalle_inventario.getExistencias(), 
			$detalle_inventario.getExistenciasProcesadas(), 
			$detalle_inventario.getPrecioCompra(), 
			$detalle_inventario.getIdProducto(),$detalle_inventario.getIdSucursal(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				DetalleInventario._callback_stack.pop().call(null, $detalle_inventario);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleInventario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( DetalleInventario.getByPK(this.getIdProducto(), this.getIdSucursal()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_inventario WHERE  id_producto = ? AND id_sucursal = ?;";
		$params = [ this.getIdProducto(), this.getIdSucursal() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleInventario} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleInventario}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $detalle_inventario , $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_inventario WHERE ("; 
		$val = [];
		if( (($a = this.getIdProducto()) != null) & ( ($b = $detalle_inventario.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $detalle_inventario.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioVenta()) != null) & ( ($b = $detalle_inventario.getPrecioVenta()) != null) ){
				$sql += " precio_venta >= ? AND precio_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioVentaProcesado()) != null) & ( ($b = $detalle_inventario.getPrecioVentaProcesado()) != null) ){
				$sql += " precio_venta_procesado >= ? AND precio_venta_procesado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_venta_procesado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getExistencias()) != null) & ( ($b = $detalle_inventario.getExistencias()) != null) ){
				$sql += " existencias >= ? AND existencias <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " existencias = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getExistenciasProcesadas()) != null) & ( ($b = $detalle_inventario.getExistenciasProcesadas()) != null) ){
				$sql += " existencias_procesadas >= ? AND existencias_procesadas <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " existencias_procesadas = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioCompra()) != null) & ( ($b = $detalle_inventario.getPrecioCompra()) != null) ){
				$sql += " precio_compra >= ? AND precio_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleInventario($foo));
		//}
		return $sql;
	};


}
	DetalleInventario._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleInventario}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	DetalleInventario.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from detalle_inventario";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new DetalleInventario( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link DetalleInventario} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleInventario} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleInventario Un objeto del tipo {@link DetalleInventario}. NULL si no hay tal registro.
	  **/
	DetalleInventario.getByPK = function(  $id_producto, $id_sucursal, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM detalle_inventario WHERE (id_producto = ? AND id_sucursal = ? ) LIMIT 1;";
		db.query($sql, [ $id_producto, $id_sucursal] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new DetalleInventario(results.rows.item(0)); 
				DetalleInventario._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : DetalleInventario._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table detalle_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var DetalleVenta = function ( config )
{
 /**
	* id_venta
	* 
	* venta a que se referencia
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_venta = config === undefined ? null : config.id_venta || null,

 /**
	* id_producto
	* 
	* producto de la venta
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	_id_producto = config === undefined ? null : config.id_producto || null,

 /**
	* cantidad
	* 
	* cantidad que se vendio
	* @access private
	* @var float
	*/
	_cantidad = config === undefined ? null : config.cantidad || null,

 /**
	* cantidad_procesada
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_cantidad_procesada = config === undefined ? null : config.cantidad_procesada || null,

 /**
	* precio
	* 
	* precio al que se vendio
	* @access private
	* @var float
	*/
	_precio = config === undefined ? null : config.precio || null,

 /**
	* precio_procesada
	* 
	* el precio de los articulos procesados en esta venta
	* @access private
	* @var float
	*/
	_precio_procesada = config === undefined ? null : config.precio_procesada || null,

 /**
	* descuento
	* 
	* indica cuanto producto original se va a descontar de ese producto en esa venta
	* @access private
	* @var float
	*/
	_descuento = config === undefined ? null : config.descuento || null;

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a que se referencia
	  * @return int(11)
	  */
	this.getIdVenta = function ()
	{
		return _id_venta;
	};

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a que se referencia.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdVenta( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdVenta  = function ( id_venta )
	{
		_id_venta = id_venta;
	};

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es producto de la venta
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es producto de la venta.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad que se vendio
	  * @return float
	  */
	this.getCantidad = function ()
	{
		return _cantidad;
	};

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad que se vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setCantidad  = function ( cantidad )
	{
		_cantidad = cantidad;
	};

	/**
	  * getCantidadProcesada
	  * 
	  * Get the <i>cantidad_procesada</i> property for this object. Donde <i>cantidad_procesada</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getCantidadProcesada = function ()
	{
		return _cantidad_procesada;
	};

	/**
	  * setCantidadProcesada( $cantidad_procesada )
	  * 
	  * Set the <i>cantidad_procesada</i> property for this object. Donde <i>cantidad_procesada</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad_procesada</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setCantidadProcesada  = function ( cantidad_procesada )
	{
		_cantidad_procesada = cantidad_procesada;
	};

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se vendio
	  * @return float
	  */
	this.getPrecio = function ()
	{
		return _precio;
	};

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecio  = function ( precio )
	{
		_precio = precio;
	};

	/**
	  * getPrecioProcesada
	  * 
	  * Get the <i>precio_procesada</i> property for this object. Donde <i>precio_procesada</i> es el precio de los articulos procesados en esta venta
	  * @return float
	  */
	this.getPrecioProcesada = function ()
	{
		return _precio_procesada;
	};

	/**
	  * setPrecioProcesada( $precio_procesada )
	  * 
	  * Set the <i>precio_procesada</i> property for this object. Donde <i>precio_procesada</i> es el precio de los articulos procesados en esta venta.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_procesada</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioProcesada  = function ( precio_procesada )
	{
		_precio_procesada = precio_procesada;
	};

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es indica cuanto producto original se va a descontar de ese producto en esa venta
	  * @return float
	  */
	this.getDescuento = function ()
	{
		return _descuento;
	};

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es indica cuanto producto original se va a descontar de ese producto en esa venta.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setDescuento  = function ( descuento )
	{
		_descuento = descuento;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleVenta} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		DetalleVenta._callback_stack.push( _original_callback  );
		DetalleVenta._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		DetalleVenta.getByPK(  this.getIdVenta() , this.getIdProducto() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleVenta} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_venta WHERE ("; 
		$val = [];
		if( this.getIdVenta() != null){
			$sql += " id_venta = ? AND";
			$val.push( this.getIdVenta() );
		}

		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getCantidad() != null){
			$sql += " cantidad = ? AND";
			$val.push( this.getCantidad() );
		}

		if( this.getCantidadProcesada() != null){
			$sql += " cantidad_procesada = ? AND";
			$val.push( this.getCantidadProcesada() );
		}

		if( this.getPrecio() != null){
			$sql += " precio = ? AND";
			$val.push( this.getPrecio() );
		}

		if( this.getPrecioProcesada() != null){
			$sql += " precio_procesada = ? AND";
			$val.push( this.getPrecioProcesada() );
		}

		if( this.getDescuento() != null){
			$sql += " descuento = ? AND";
			$val.push( this.getDescuento() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleVenta($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleVenta suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleVenta dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta a crear.
	  **/
	var create = function( detalle_venta )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO detalle_venta ( id_venta, id_producto, cantidad, cantidad_procesada, precio, precio_procesada, descuento ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			detalle_venta.getIdVenta(), 
			detalle_venta.getIdProducto(), 
			detalle_venta.getCantidad(), 
			detalle_venta.getCantidadProcesada(), 
			detalle_venta.getPrecio(), 
			detalle_venta.getPrecioProcesada(), 
			detalle_venta.getDescuento(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				DetalleVenta._callback_stack.pop().call(null, detalle_venta);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta a actualizar.
	  **/
	var update = function( $detalle_venta )
	{
		$sql = "UPDATE detalle_venta SET  cantidad = ?, cantidad_procesada = ?, precio = ?, precio_procesada = ?, descuento = ? WHERE  id_venta = ? AND id_producto = ?;";
		$params = [ 
			$detalle_venta.getCantidad(), 
			$detalle_venta.getCantidadProcesada(), 
			$detalle_venta.getPrecio(), 
			$detalle_venta.getPrecioProcesada(), 
			$detalle_venta.getDescuento(), 
			$detalle_venta.getIdVenta(),$detalle_venta.getIdProducto(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				DetalleVenta._callback_stack.pop().call(null, $detalle_venta);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleVenta suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( DetalleVenta.getByPK(this.getIdVenta(), this.getIdProducto()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_venta WHERE  id_venta = ? AND id_producto = ?;";
		$params = [ this.getIdVenta(), this.getIdProducto() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleVenta} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleVenta}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $detalle_venta , $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_venta WHERE ("; 
		$val = [];
		if( (($a = this.getIdVenta()) != null) & ( ($b = $detalle_venta.getIdVenta()) != null) ){
				$sql += " id_venta >= ? AND id_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdProducto()) != null) & ( ($b = $detalle_venta.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCantidad()) != null) & ( ($b = $detalle_venta.getCantidad()) != null) ){
				$sql += " cantidad >= ? AND cantidad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cantidad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCantidadProcesada()) != null) & ( ($b = $detalle_venta.getCantidadProcesada()) != null) ){
				$sql += " cantidad_procesada >= ? AND cantidad_procesada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cantidad_procesada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecio()) != null) & ( ($b = $detalle_venta.getPrecio()) != null) ){
				$sql += " precio >= ? AND precio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioProcesada()) != null) & ( ($b = $detalle_venta.getPrecioProcesada()) != null) ){
				$sql += " precio_procesada >= ? AND precio_procesada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_procesada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescuento()) != null) & ( ($b = $detalle_venta.getDescuento()) != null) ){
				$sql += " descuento >= ? AND descuento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descuento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleVenta($foo));
		//}
		return $sql;
	};


}
	DetalleVenta._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	DetalleVenta.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from detalle_venta";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new DetalleVenta( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link DetalleVenta} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleVenta} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleVenta Un objeto del tipo {@link DetalleVenta}. NULL si no hay tal registro.
	  **/
	DetalleVenta.getByPK = function(  $id_venta, $id_producto, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM detalle_venta WHERE (id_venta = ? AND id_producto = ? ) LIMIT 1;";
		db.query($sql, [ $id_venta, $id_producto] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new DetalleVenta(results.rows.item(0)); 
				DetalleVenta._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : DetalleVenta._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table documento.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Documento = function ( config )
{
 /**
	* id_documento
	* 
	* id del documento
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_documento = config === undefined ? null : config.id_documento || null,

 /**
	* numero_de_impresiones
	* 
	* numero de veces que se tiene que imprmir este documento
	* @access private
	* @var int(11)
	*/
	_numero_de_impresiones = config === undefined ? null : config.numero_de_impresiones || null,

 /**
	* identificador
	* 
	* identificador con el cual se le conocera en el sistema
	* @access private
	* @var varchar(128)
	*/
	_identificador = config === undefined ? null : config.identificador || null,

 /**
	* descripcion
	* 
	* descripcion breve del documento
	* @access private
	* @var varchar(256)
	*/
	_descripcion = config === undefined ? null : config.descripcion || null;

	/**
	  * getIdDocumento
	  * 
	  * Get the <i>id_documento</i> property for this object. Donde <i>id_documento</i> es id del documento
	  * @return int(11)
	  */
	this.getIdDocumento = function ()
	{
		return _id_documento;
	};

	/**
	  * setIdDocumento( $id_documento )
	  * 
	  * Set the <i>id_documento</i> property for this object. Donde <i>id_documento</i> es id del documento.
	  * Una validacion basica se hara aqui para comprobar que <i>id_documento</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdDocumento( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdDocumento  = function ( id_documento )
	{
		_id_documento = id_documento;
	};

	/**
	  * getNumeroDeImpresiones
	  * 
	  * Get the <i>numero_de_impresiones</i> property for this object. Donde <i>numero_de_impresiones</i> es numero de veces que se tiene que imprmir este documento
	  * @return int(11)
	  */
	this.getNumeroDeImpresiones = function ()
	{
		return _numero_de_impresiones;
	};

	/**
	  * setNumeroDeImpresiones( $numero_de_impresiones )
	  * 
	  * Set the <i>numero_de_impresiones</i> property for this object. Donde <i>numero_de_impresiones</i> es numero de veces que se tiene que imprmir este documento.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_de_impresiones</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setNumeroDeImpresiones  = function ( numero_de_impresiones )
	{
		_numero_de_impresiones = numero_de_impresiones;
	};

	/**
	  * getIdentificador
	  * 
	  * Get the <i>identificador</i> property for this object. Donde <i>identificador</i> es identificador con el cual se le conocera en el sistema
	  * @return varchar(128)
	  */
	this.getIdentificador = function ()
	{
		return _identificador;
	};

	/**
	  * setIdentificador( $identificador )
	  * 
	  * Set the <i>identificador</i> property for this object. Donde <i>identificador</i> es identificador con el cual se le conocera en el sistema.
	  * Una validacion basica se hara aqui para comprobar que <i>identificador</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	this.setIdentificador  = function ( identificador )
	{
		_identificador = identificador;
	};

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion breve del documento
	  * @return varchar(256)
	  */
	this.getDescripcion = function ()
	{
		return _descripcion;
	};

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion breve del documento.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	this.setDescripcion  = function ( descripcion )
	{
		_descripcion = descripcion;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Documento} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Documento._callback_stack.push( _original_callback  );
		Documento._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Documento.getByPK(  this.getIdDocumento() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Documento} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Documento [$documento] El objeto de tipo Documento
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from documento WHERE ("; 
		$val = [];
		if( this.getIdDocumento() != null){
			$sql += " id_documento = ? AND";
			$val.push( this.getIdDocumento() );
		}

		if( this.getNumeroDeImpresiones() != null){
			$sql += " numero_de_impresiones = ? AND";
			$val.push( this.getNumeroDeImpresiones() );
		}

		if( this.getIdentificador() != null){
			$sql += " identificador = ? AND";
			$val.push( this.getIdentificador() );
		}

		if( this.getDescripcion() != null){
			$sql += " descripcion = ? AND";
			$val.push( this.getDescripcion() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Documento($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Documento suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Documento dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Documento [$documento] El objeto de tipo Documento a crear.
	  **/
	var create = function( documento )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO documento ( id_documento, numero_de_impresiones, identificador, descripcion ) VALUES ( ?, ?, ?, ?);";
		$params = [
			documento.getIdDocumento(), 
			documento.getNumeroDeImpresiones(), 
			documento.getIdentificador(), 
			documento.getDescripcion(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Documento._callback_stack.pop().call(null, documento);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Documento [$documento] El objeto de tipo Documento a actualizar.
	  **/
	var update = function( $documento )
	{
		$sql = "UPDATE documento SET  numero_de_impresiones = ?, identificador = ?, descripcion = ? WHERE  id_documento = ?;";
		$params = [ 
			$documento.getNumeroDeImpresiones(), 
			$documento.getIdentificador(), 
			$documento.getDescripcion(), 
			$documento.getIdDocumento(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Documento._callback_stack.pop().call(null, $documento);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Documento suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Documento.getByPK(this.getIdDocumento()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM documento WHERE  id_documento = ?;";
		$params = [ this.getIdDocumento() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Documento} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Documento}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Documento [$documento] El objeto de tipo Documento
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $documento , $orderBy , $orden )
	{
		$sql = "SELECT * from documento WHERE ("; 
		$val = [];
		if( (($a = this.getIdDocumento()) != null) & ( ($b = $documento.getIdDocumento()) != null) ){
				$sql += " id_documento >= ? AND id_documento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_documento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroDeImpresiones()) != null) & ( ($b = $documento.getNumeroDeImpresiones()) != null) ){
				$sql += " numero_de_impresiones >= ? AND numero_de_impresiones <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_de_impresiones = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdentificador()) != null) & ( ($b = $documento.getIdentificador()) != null) ){
				$sql += " identificador >= ? AND identificador <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " identificador = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcion()) != null) & ( ($b = $documento.getDescripcion()) != null) ){
				$sql += " descripcion >= ? AND descripcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Documento($foo));
		//}
		return $sql;
	};


}
	Documento._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Documento}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Documento.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from documento";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Documento( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Documento} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Documento} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Documento Un objeto del tipo {@link Documento}. NULL si no hay tal registro.
	  **/
	Documento.getByPK = function(  $id_documento, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM documento WHERE (id_documento = ? ) LIMIT 1;";
		db.query($sql, [ $id_documento] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Documento(results.rows.item(0)); 
				Documento._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Documento._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table equipo.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Equipo = function ( config )
{
 /**
	* id_equipo
	* 
	* el identificador de este equipo
	* <b>Llave Primaria</b>
	* @access private
	* @var int(6)
	*/
	var _id_equipo = config === undefined ? null : config.id_equipo || null,

 /**
	* token
	* 
	* el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado
	* @access private
	* @var varchar(128)
	*/
	_token = config === undefined ? null : config.token || null,

 /**
	* full_ua
	* 
	* String de user-agent para este cliente
	* @access private
	* @var varchar(256)
	*/
	_full_ua = config === undefined ? null : config.full_ua || null,

 /**
	* descripcion
	* 
	* descripcion de este equipo
	* @access private
	* @var varchar(254)
	*/
	_descripcion = config === undefined ? null : config.descripcion || null,

 /**
	* locked
	* 
	* si este equipo esta lockeado para prevenir los cambios
	* @access private
	* @var tinyint(1)
	*/
	_locked = config === undefined ? null : config.locked || null;

	/**
	  * getIdEquipo
	  * 
	  * Get the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es el identificador de este equipo
	  * @return int(6)
	  */
	this.getIdEquipo = function ()
	{
		return _id_equipo;
	};

	/**
	  * setIdEquipo( $id_equipo )
	  * 
	  * Set the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es el identificador de este equipo.
	  * Una validacion basica se hara aqui para comprobar que <i>id_equipo</i> es de tipo <i>int(6)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdEquipo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(6)
	  */
	this.setIdEquipo  = function ( id_equipo )
	{
		_id_equipo = id_equipo;
	};

	/**
	  * getToken
	  * 
	  * Get the <i>token</i> property for this object. Donde <i>token</i> es el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado
	  * @return varchar(128)
	  */
	this.getToken = function ()
	{
		return _token;
	};

	/**
	  * setToken( $token )
	  * 
	  * Set the <i>token</i> property for this object. Donde <i>token</i> es el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado.
	  * Una validacion basica se hara aqui para comprobar que <i>token</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	this.setToken  = function ( token )
	{
		_token = token;
	};

	/**
	  * getFullUa
	  * 
	  * Get the <i>full_ua</i> property for this object. Donde <i>full_ua</i> es String de user-agent para este cliente
	  * @return varchar(256)
	  */
	this.getFullUa = function ()
	{
		return _full_ua;
	};

	/**
	  * setFullUa( $full_ua )
	  * 
	  * Set the <i>full_ua</i> property for this object. Donde <i>full_ua</i> es String de user-agent para este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>full_ua</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	this.setFullUa  = function ( full_ua )
	{
		_full_ua = full_ua;
	};

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion de este equipo
	  * @return varchar(254)
	  */
	this.getDescripcion = function ()
	{
		return _descripcion;
	};

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion de este equipo.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(254)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(254)
	  */
	this.setDescripcion  = function ( descripcion )
	{
		_descripcion = descripcion;
	};

	/**
	  * getLocked
	  * 
	  * Get the <i>locked</i> property for this object. Donde <i>locked</i> es si este equipo esta lockeado para prevenir los cambios
	  * @return tinyint(1)
	  */
	this.getLocked = function ()
	{
		return _locked;
	};

	/**
	  * setLocked( $locked )
	  * 
	  * Set the <i>locked</i> property for this object. Donde <i>locked</i> es si este equipo esta lockeado para prevenir los cambios.
	  * Una validacion basica se hara aqui para comprobar que <i>locked</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setLocked  = function ( locked )
	{
		_locked = locked;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Equipo} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Equipo._callback_stack.push( _original_callback  );
		Equipo._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Equipo.getByPK(  this.getIdEquipo() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Equipo} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Equipo [$equipo] El objeto de tipo Equipo
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from equipo WHERE ("; 
		$val = [];
		if( this.getIdEquipo() != null){
			$sql += " id_equipo = ? AND";
			$val.push( this.getIdEquipo() );
		}

		if( this.getToken() != null){
			$sql += " token = ? AND";
			$val.push( this.getToken() );
		}

		if( this.getFullUa() != null){
			$sql += " full_ua = ? AND";
			$val.push( this.getFullUa() );
		}

		if( this.getDescripcion() != null){
			$sql += " descripcion = ? AND";
			$val.push( this.getDescripcion() );
		}

		if( this.getLocked() != null){
			$sql += " locked = ? AND";
			$val.push( this.getLocked() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Equipo($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Equipo suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Equipo dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Equipo [$equipo] El objeto de tipo Equipo a crear.
	  **/
	var create = function( equipo )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO equipo ( id_equipo, token, full_ua, descripcion, locked ) VALUES ( ?, ?, ?, ?, ?);";
		$params = [
			equipo.getIdEquipo(), 
			equipo.getToken(), 
			equipo.getFullUa(), 
			equipo.getDescripcion(), 
			equipo.getLocked(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Equipo._callback_stack.pop().call(null, equipo);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Equipo [$equipo] El objeto de tipo Equipo a actualizar.
	  **/
	var update = function( $equipo )
	{
		$sql = "UPDATE equipo SET  token = ?, full_ua = ?, descripcion = ?, locked = ? WHERE  id_equipo = ?;";
		$params = [ 
			$equipo.getToken(), 
			$equipo.getFullUa(), 
			$equipo.getDescripcion(), 
			$equipo.getLocked(), 
			$equipo.getIdEquipo(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Equipo._callback_stack.pop().call(null, $equipo);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Equipo suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Equipo.getByPK(this.getIdEquipo()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM equipo WHERE  id_equipo = ?;";
		$params = [ this.getIdEquipo() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Equipo} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Equipo}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Equipo [$equipo] El objeto de tipo Equipo
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $equipo , $orderBy , $orden )
	{
		$sql = "SELECT * from equipo WHERE ("; 
		$val = [];
		if( (($a = this.getIdEquipo()) != null) & ( ($b = $equipo.getIdEquipo()) != null) ){
				$sql += " id_equipo >= ? AND id_equipo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_equipo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getToken()) != null) & ( ($b = $equipo.getToken()) != null) ){
				$sql += " token >= ? AND token <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " token = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFullUa()) != null) & ( ($b = $equipo.getFullUa()) != null) ){
				$sql += " full_ua >= ? AND full_ua <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " full_ua = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcion()) != null) & ( ($b = $equipo.getDescripcion()) != null) ){
				$sql += " descripcion >= ? AND descripcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLocked()) != null) & ( ($b = $equipo.getLocked()) != null) ){
				$sql += " locked >= ? AND locked <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " locked = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Equipo($foo));
		//}
		return $sql;
	};


}
	Equipo._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Equipo}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Equipo.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from equipo";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Equipo( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Equipo} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Equipo} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Equipo Un objeto del tipo {@link Equipo}. NULL si no hay tal registro.
	  **/
	Equipo.getByPK = function(  $id_equipo, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM equipo WHERE (id_equipo = ? ) LIMIT 1;";
		db.query($sql, [ $id_equipo] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Equipo(results.rows.item(0)); 
				Equipo._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Equipo._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table equipo_sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var EquipoSucursal = function ( config )
{
 /**
	* id_equipo
	* 
	* identificador del equipo 
	* <b>Llave Primaria</b>
	* @access private
	* @var int(6)
	*/
	var _id_equipo = config === undefined ? null : config.id_equipo || null,

 /**
	* id_sucursal
	* 
	* identifica una sucursal
	* @access private
	* @var int(6)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null;

	/**
	  * getIdEquipo
	  * 
	  * Get the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es identificador del equipo 
	  * @return int(6)
	  */
	this.getIdEquipo = function ()
	{
		return _id_equipo;
	};

	/**
	  * setIdEquipo( $id_equipo )
	  * 
	  * Set the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es identificador del equipo .
	  * Una validacion basica se hara aqui para comprobar que <i>id_equipo</i> es de tipo <i>int(6)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdEquipo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(6)
	  */
	this.setIdEquipo  = function ( id_equipo )
	{
		_id_equipo = id_equipo;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es identifica una sucursal
	  * @return int(6)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es identifica una sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(6)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(6)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link EquipoSucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		EquipoSucursal._callback_stack.push( _original_callback  );
		EquipoSucursal._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		EquipoSucursal.getByPK(  this.getIdEquipo() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link EquipoSucursal} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param EquipoSucursal [$equipo_sucursal] El objeto de tipo EquipoSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from equipo_sucursal WHERE ("; 
		$val = [];
		if( this.getIdEquipo() != null){
			$sql += " id_equipo = ? AND";
			$val.push( this.getIdEquipo() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new EquipoSucursal($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto EquipoSucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto EquipoSucursal dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param EquipoSucursal [$equipo_sucursal] El objeto de tipo EquipoSucursal a crear.
	  **/
	var create = function( equipo_sucursal )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO equipo_sucursal ( id_equipo, id_sucursal ) VALUES ( ?, ?);";
		$params = [
			equipo_sucursal.getIdEquipo(), 
			equipo_sucursal.getIdSucursal(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				EquipoSucursal._callback_stack.pop().call(null, equipo_sucursal);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param EquipoSucursal [$equipo_sucursal] El objeto de tipo EquipoSucursal a actualizar.
	  **/
	var update = function( $equipo_sucursal )
	{
		$sql = "UPDATE equipo_sucursal SET  id_sucursal = ? WHERE  id_equipo = ?;";
		$params = [ 
			$equipo_sucursal.getIdSucursal(), 
			$equipo_sucursal.getIdEquipo(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				EquipoSucursal._callback_stack.pop().call(null, $equipo_sucursal);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto EquipoSucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( EquipoSucursal.getByPK(this.getIdEquipo()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM equipo_sucursal WHERE  id_equipo = ?;";
		$params = [ this.getIdEquipo() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link EquipoSucursal} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link EquipoSucursal}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param EquipoSucursal [$equipo_sucursal] El objeto de tipo EquipoSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $equipo_sucursal , $orderBy , $orden )
	{
		$sql = "SELECT * from equipo_sucursal WHERE ("; 
		$val = [];
		if( (($a = this.getIdEquipo()) != null) & ( ($b = $equipo_sucursal.getIdEquipo()) != null) ){
				$sql += " id_equipo >= ? AND id_equipo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_equipo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $equipo_sucursal.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new EquipoSucursal($foo));
		//}
		return $sql;
	};


}
	EquipoSucursal._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link EquipoSucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	EquipoSucursal.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from equipo_sucursal";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new EquipoSucursal( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link EquipoSucursal} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link EquipoSucursal} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link EquipoSucursal Un objeto del tipo {@link EquipoSucursal}. NULL si no hay tal registro.
	  **/
	EquipoSucursal.getByPK = function(  $id_equipo, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM equipo_sucursal WHERE (id_equipo = ? ) LIMIT 1;";
		db.query($sql, [ $id_equipo] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new EquipoSucursal(results.rows.item(0)); 
				EquipoSucursal._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : EquipoSucursal._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table factura_compra.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var FacturaCompra = function ( config )
{
 /**
	* folio
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var varchar(15)
	*/
	var _folio = config === undefined ? null : config.folio || null,

 /**
	* id_compra
	* 
	* COMPRA A LA QUE CORRESPONDE LA FACTURA
	* @access private
	* @var int(11)
	*/
	_id_compra = config === undefined ? null : config.id_compra || null;

	/**
	  * getFolio
	  * 
	  * Get the <i>folio</i> property for this object. Donde <i>folio</i> es  [Campo no documentado]
	  * @return varchar(15)
	  */
	this.getFolio = function ()
	{
		return _folio;
	};

	/**
	  * setFolio( $folio )
	  * 
	  * Set the <i>folio</i> property for this object. Donde <i>folio</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>folio</i> es de tipo <i>varchar(15)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setFolio( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param varchar(15)
	  */
	this.setFolio  = function ( folio )
	{
		_folio = folio;
	};

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es COMPRA A LA QUE CORRESPONDE LA FACTURA
	  * @return int(11)
	  */
	this.getIdCompra = function ()
	{
		return _id_compra;
	};

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es COMPRA A LA QUE CORRESPONDE LA FACTURA.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdCompra  = function ( id_compra )
	{
		_id_compra = id_compra;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link FacturaCompra} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		FacturaCompra._callback_stack.push( _original_callback  );
		FacturaCompra._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		FacturaCompra.getByPK(  this.getFolio() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link FacturaCompra} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param FacturaCompra [$factura_compra] El objeto de tipo FacturaCompra
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from factura_compra WHERE ("; 
		$val = [];
		if( this.getFolio() != null){
			$sql += " folio = ? AND";
			$val.push( this.getFolio() );
		}

		if( this.getIdCompra() != null){
			$sql += " id_compra = ? AND";
			$val.push( this.getIdCompra() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new FacturaCompra($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto FacturaCompra suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto FacturaCompra dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param FacturaCompra [$factura_compra] El objeto de tipo FacturaCompra a crear.
	  **/
	var create = function( factura_compra )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO factura_compra ( folio, id_compra ) VALUES ( ?, ?);";
		$params = [
			factura_compra.getFolio(), 
			factura_compra.getIdCompra(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				FacturaCompra._callback_stack.pop().call(null, factura_compra);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param FacturaCompra [$factura_compra] El objeto de tipo FacturaCompra a actualizar.
	  **/
	var update = function( $factura_compra )
	{
		$sql = "UPDATE factura_compra SET  id_compra = ? WHERE  folio = ?;";
		$params = [ 
			$factura_compra.getIdCompra(), 
			$factura_compra.getFolio(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				FacturaCompra._callback_stack.pop().call(null, $factura_compra);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto FacturaCompra suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( FacturaCompra.getByPK(this.getFolio()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM factura_compra WHERE  folio = ?;";
		$params = [ this.getFolio() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link FacturaCompra} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link FacturaCompra}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param FacturaCompra [$factura_compra] El objeto de tipo FacturaCompra
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $factura_compra , $orderBy , $orden )
	{
		$sql = "SELECT * from factura_compra WHERE ("; 
		$val = [];
		if( (($a = this.getFolio()) != null) & ( ($b = $factura_compra.getFolio()) != null) ){
				$sql += " folio >= ? AND folio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " folio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdCompra()) != null) & ( ($b = $factura_compra.getIdCompra()) != null) ){
				$sql += " id_compra >= ? AND id_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new FacturaCompra($foo));
		//}
		return $sql;
	};


}
	FacturaCompra._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link FacturaCompra}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	FacturaCompra.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from factura_compra";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new FacturaCompra( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link FacturaCompra} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link FacturaCompra} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link FacturaCompra Un objeto del tipo {@link FacturaCompra}. NULL si no hay tal registro.
	  **/
	FacturaCompra.getByPK = function(  $folio, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM factura_compra WHERE (folio = ? ) LIMIT 1;";
		db.query($sql, [ $folio] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new FacturaCompra(results.rows.item(0)); 
				FacturaCompra._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : FacturaCompra._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table factura_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var FacturaVenta = function ( config )
{
 /**
	* id_folio
	* 
	* folio que tiene la factura
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_folio = config === undefined ? null : config.id_folio || null,

 /**
	* id_venta
	* 
	* venta a la cual corresponde la factura
	* @access private
	* @var int(11)
	*/
	_id_venta = config === undefined ? null : config.id_venta || null,

 /**
	* id_usuario
	* 
	* Id del usuario que hiso al ultima modificacion a la factura
	* @access private
	* @var int(10)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* xml
	* 
	* xml en bruto
	* @access private
	* @var text
	*/
	_xml = config === undefined ? null : config.xml || null,

 /**
	* lugar_emision
	* 
	* id de la sucursal donde se emitio la factura
	* @access private
	* @var int(11)
	*/
	_lugar_emision = config === undefined ? null : config.lugar_emision || null,

 /**
	* tipo_comprobante
	* 
	*  [Campo no documentado]
	* @access private
	* @var enum('ingreso','egreso')
	*/
	_tipo_comprobante = config === undefined ? null : config.tipo_comprobante || null,

 /**
	* activa
	* 
	* 1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada
	* @access private
	* @var tinyint(1)
	*/
	_activa = config === undefined ? null : config.activa || null,

 /**
	* sellada
	* 
	* Indica si el WS ha timbrado la factura
	* @access private
	* @var tinyint(1)
	*/
	_sellada = config === undefined ? null : config.sellada || null,

 /**
	* forma_pago
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(100)
	*/
	_forma_pago = config === undefined ? null : config.forma_pago || null,

 /**
	* fecha_emision
	* 
	*  [Campo no documentado]
	* @access private
	* @var timestamp
	*/
	_fecha_emision = config === undefined ? null : config.fecha_emision || null,

 /**
	* version_tfd
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(10)
	*/
	_version_tfd = config === undefined ? null : config.version_tfd || null,

 /**
	* folio_fiscal
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(128)
	*/
	_folio_fiscal = config === undefined ? null : config.folio_fiscal || null,

 /**
	* fecha_certificacion
	* 
	*  [Campo no documentado]
	* @access private
	* @var timestamp
	*/
	_fecha_certificacion = config === undefined ? null : config.fecha_certificacion || null,

 /**
	* numero_certificado_sat
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(128)
	*/
	_numero_certificado_sat = config === undefined ? null : config.numero_certificado_sat || null,

 /**
	* sello_digital_emisor
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(512)
	*/
	_sello_digital_emisor = config === undefined ? null : config.sello_digital_emisor || null,

 /**
	* sello_digital_sat
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(512)
	*/
	_sello_digital_sat = config === undefined ? null : config.sello_digital_sat || null,

 /**
	* cadena_original
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(2048)
	*/
	_cadena_original = config === undefined ? null : config.cadena_original || null;

	/**
	  * getIdFolio
	  * 
	  * Get the <i>id_folio</i> property for this object. Donde <i>id_folio</i> es folio que tiene la factura
	  * @return int(11)
	  */
	this.getIdFolio = function ()
	{
		return _id_folio;
	};

	/**
	  * setIdFolio( $id_folio )
	  * 
	  * Set the <i>id_folio</i> property for this object. Donde <i>id_folio</i> es folio que tiene la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>id_folio</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdFolio( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdFolio  = function ( id_folio )
	{
		_id_folio = id_folio;
	};

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a la cual corresponde la factura
	  * @return int(11)
	  */
	this.getIdVenta = function ()
	{
		return _id_venta;
	};

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a la cual corresponde la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdVenta  = function ( id_venta )
	{
		_id_venta = id_venta;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Id del usuario que hiso al ultima modificacion a la factura
	  * @return int(10)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Id del usuario que hiso al ultima modificacion a la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(10)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getXml
	  * 
	  * Get the <i>xml</i> property for this object. Donde <i>xml</i> es xml en bruto
	  * @return text
	  */
	this.getXml = function ()
	{
		return _xml;
	};

	/**
	  * setXml( $xml )
	  * 
	  * Set the <i>xml</i> property for this object. Donde <i>xml</i> es xml en bruto.
	  * Una validacion basica se hara aqui para comprobar que <i>xml</i> es de tipo <i>text</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param text
	  */
	this.setXml  = function ( xml )
	{
		_xml = xml;
	};

	/**
	  * getLugarEmision
	  * 
	  * Get the <i>lugar_emision</i> property for this object. Donde <i>lugar_emision</i> es id de la sucursal donde se emitio la factura
	  * @return int(11)
	  */
	this.getLugarEmision = function ()
	{
		return _lugar_emision;
	};

	/**
	  * setLugarEmision( $lugar_emision )
	  * 
	  * Set the <i>lugar_emision</i> property for this object. Donde <i>lugar_emision</i> es id de la sucursal donde se emitio la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>lugar_emision</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setLugarEmision  = function ( lugar_emision )
	{
		_lugar_emision = lugar_emision;
	};

	/**
	  * getTipoComprobante
	  * 
	  * Get the <i>tipo_comprobante</i> property for this object. Donde <i>tipo_comprobante</i> es  [Campo no documentado]
	  * @return enum('ingreso','egreso')
	  */
	this.getTipoComprobante = function ()
	{
		return _tipo_comprobante;
	};

	/**
	  * setTipoComprobante( $tipo_comprobante )
	  * 
	  * Set the <i>tipo_comprobante</i> property for this object. Donde <i>tipo_comprobante</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_comprobante</i> es de tipo <i>enum('ingreso','egreso')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('ingreso','egreso')
	  */
	this.setTipoComprobante  = function ( tipo_comprobante )
	{
		_tipo_comprobante = tipo_comprobante;
	};

	/**
	  * getActiva
	  * 
	  * Get the <i>activa</i> property for this object. Donde <i>activa</i> es 1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada
	  * @return tinyint(1)
	  */
	this.getActiva = function ()
	{
		return _activa;
	};

	/**
	  * setActiva( $activa )
	  * 
	  * Set the <i>activa</i> property for this object. Donde <i>activa</i> es 1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada.
	  * Una validacion basica se hara aqui para comprobar que <i>activa</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setActiva  = function ( activa )
	{
		_activa = activa;
	};

	/**
	  * getSellada
	  * 
	  * Get the <i>sellada</i> property for this object. Donde <i>sellada</i> es Indica si el WS ha timbrado la factura
	  * @return tinyint(1)
	  */
	this.getSellada = function ()
	{
		return _sellada;
	};

	/**
	  * setSellada( $sellada )
	  * 
	  * Set the <i>sellada</i> property for this object. Donde <i>sellada</i> es Indica si el WS ha timbrado la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>sellada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setSellada  = function ( sellada )
	{
		_sellada = sellada;
	};

	/**
	  * getFormaPago
	  * 
	  * Get the <i>forma_pago</i> property for this object. Donde <i>forma_pago</i> es  [Campo no documentado]
	  * @return varchar(100)
	  */
	this.getFormaPago = function ()
	{
		return _forma_pago;
	};

	/**
	  * setFormaPago( $forma_pago )
	  * 
	  * Set the <i>forma_pago</i> property for this object. Donde <i>forma_pago</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>forma_pago</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setFormaPago  = function ( forma_pago )
	{
		_forma_pago = forma_pago;
	};

	/**
	  * getFechaEmision
	  * 
	  * Get the <i>fecha_emision</i> property for this object. Donde <i>fecha_emision</i> es  [Campo no documentado]
	  * @return timestamp
	  */
	this.getFechaEmision = function ()
	{
		return _fecha_emision;
	};

	/**
	  * setFechaEmision( $fecha_emision )
	  * 
	  * Set the <i>fecha_emision</i> property for this object. Donde <i>fecha_emision</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_emision</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaEmision  = function ( fecha_emision )
	{
		_fecha_emision = fecha_emision;
	};

	/**
	  * getVersionTfd
	  * 
	  * Get the <i>version_tfd</i> property for this object. Donde <i>version_tfd</i> es  [Campo no documentado]
	  * @return varchar(10)
	  */
	this.getVersionTfd = function ()
	{
		return _version_tfd;
	};

	/**
	  * setVersionTfd( $version_tfd )
	  * 
	  * Set the <i>version_tfd</i> property for this object. Donde <i>version_tfd</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>version_tfd</i> es de tipo <i>varchar(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(10)
	  */
	this.setVersionTfd  = function ( version_tfd )
	{
		_version_tfd = version_tfd;
	};

	/**
	  * getFolioFiscal
	  * 
	  * Get the <i>folio_fiscal</i> property for this object. Donde <i>folio_fiscal</i> es  [Campo no documentado]
	  * @return varchar(128)
	  */
	this.getFolioFiscal = function ()
	{
		return _folio_fiscal;
	};

	/**
	  * setFolioFiscal( $folio_fiscal )
	  * 
	  * Set the <i>folio_fiscal</i> property for this object. Donde <i>folio_fiscal</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>folio_fiscal</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	this.setFolioFiscal  = function ( folio_fiscal )
	{
		_folio_fiscal = folio_fiscal;
	};

	/**
	  * getFechaCertificacion
	  * 
	  * Get the <i>fecha_certificacion</i> property for this object. Donde <i>fecha_certificacion</i> es  [Campo no documentado]
	  * @return timestamp
	  */
	this.getFechaCertificacion = function ()
	{
		return _fecha_certificacion;
	};

	/**
	  * setFechaCertificacion( $fecha_certificacion )
	  * 
	  * Set the <i>fecha_certificacion</i> property for this object. Donde <i>fecha_certificacion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_certificacion</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaCertificacion  = function ( fecha_certificacion )
	{
		_fecha_certificacion = fecha_certificacion;
	};

	/**
	  * getNumeroCertificadoSat
	  * 
	  * Get the <i>numero_certificado_sat</i> property for this object. Donde <i>numero_certificado_sat</i> es  [Campo no documentado]
	  * @return varchar(128)
	  */
	this.getNumeroCertificadoSat = function ()
	{
		return _numero_certificado_sat;
	};

	/**
	  * setNumeroCertificadoSat( $numero_certificado_sat )
	  * 
	  * Set the <i>numero_certificado_sat</i> property for this object. Donde <i>numero_certificado_sat</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>numero_certificado_sat</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	this.setNumeroCertificadoSat  = function ( numero_certificado_sat )
	{
		_numero_certificado_sat = numero_certificado_sat;
	};

	/**
	  * getSelloDigitalEmisor
	  * 
	  * Get the <i>sello_digital_emisor</i> property for this object. Donde <i>sello_digital_emisor</i> es  [Campo no documentado]
	  * @return varchar(512)
	  */
	this.getSelloDigitalEmisor = function ()
	{
		return _sello_digital_emisor;
	};

	/**
	  * setSelloDigitalEmisor( $sello_digital_emisor )
	  * 
	  * Set the <i>sello_digital_emisor</i> property for this object. Donde <i>sello_digital_emisor</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>sello_digital_emisor</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	this.setSelloDigitalEmisor  = function ( sello_digital_emisor )
	{
		_sello_digital_emisor = sello_digital_emisor;
	};

	/**
	  * getSelloDigitalSat
	  * 
	  * Get the <i>sello_digital_sat</i> property for this object. Donde <i>sello_digital_sat</i> es  [Campo no documentado]
	  * @return varchar(512)
	  */
	this.getSelloDigitalSat = function ()
	{
		return _sello_digital_sat;
	};

	/**
	  * setSelloDigitalSat( $sello_digital_sat )
	  * 
	  * Set the <i>sello_digital_sat</i> property for this object. Donde <i>sello_digital_sat</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>sello_digital_sat</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	this.setSelloDigitalSat  = function ( sello_digital_sat )
	{
		_sello_digital_sat = sello_digital_sat;
	};

	/**
	  * getCadenaOriginal
	  * 
	  * Get the <i>cadena_original</i> property for this object. Donde <i>cadena_original</i> es  [Campo no documentado]
	  * @return varchar(2048)
	  */
	this.getCadenaOriginal = function ()
	{
		return _cadena_original;
	};

	/**
	  * setCadenaOriginal( $cadena_original )
	  * 
	  * Set the <i>cadena_original</i> property for this object. Donde <i>cadena_original</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>cadena_original</i> es de tipo <i>varchar(2048)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(2048)
	  */
	this.setCadenaOriginal  = function ( cadena_original )
	{
		_cadena_original = cadena_original;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link FacturaVenta} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		FacturaVenta._callback_stack.push( _original_callback  );
		FacturaVenta._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		FacturaVenta.getByPK(  this.getIdFolio() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link FacturaVenta} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from factura_venta WHERE ("; 
		$val = [];
		if( this.getIdFolio() != null){
			$sql += " id_folio = ? AND";
			$val.push( this.getIdFolio() );
		}

		if( this.getIdVenta() != null){
			$sql += " id_venta = ? AND";
			$val.push( this.getIdVenta() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getXml() != null){
			$sql += " xml = ? AND";
			$val.push( this.getXml() );
		}

		if( this.getLugarEmision() != null){
			$sql += " lugar_emision = ? AND";
			$val.push( this.getLugarEmision() );
		}

		if( this.getTipoComprobante() != null){
			$sql += " tipo_comprobante = ? AND";
			$val.push( this.getTipoComprobante() );
		}

		if( this.getActiva() != null){
			$sql += " activa = ? AND";
			$val.push( this.getActiva() );
		}

		if( this.getSellada() != null){
			$sql += " sellada = ? AND";
			$val.push( this.getSellada() );
		}

		if( this.getFormaPago() != null){
			$sql += " forma_pago = ? AND";
			$val.push( this.getFormaPago() );
		}

		if( this.getFechaEmision() != null){
			$sql += " fecha_emision = ? AND";
			$val.push( this.getFechaEmision() );
		}

		if( this.getVersionTfd() != null){
			$sql += " version_tfd = ? AND";
			$val.push( this.getVersionTfd() );
		}

		if( this.getFolioFiscal() != null){
			$sql += " folio_fiscal = ? AND";
			$val.push( this.getFolioFiscal() );
		}

		if( this.getFechaCertificacion() != null){
			$sql += " fecha_certificacion = ? AND";
			$val.push( this.getFechaCertificacion() );
		}

		if( this.getNumeroCertificadoSat() != null){
			$sql += " numero_certificado_sat = ? AND";
			$val.push( this.getNumeroCertificadoSat() );
		}

		if( this.getSelloDigitalEmisor() != null){
			$sql += " sello_digital_emisor = ? AND";
			$val.push( this.getSelloDigitalEmisor() );
		}

		if( this.getSelloDigitalSat() != null){
			$sql += " sello_digital_sat = ? AND";
			$val.push( this.getSelloDigitalSat() );
		}

		if( this.getCadenaOriginal() != null){
			$sql += " cadena_original = ? AND";
			$val.push( this.getCadenaOriginal() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new FacturaVenta($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto FacturaVenta suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto FacturaVenta dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta a crear.
	  **/
	var create = function( factura_venta )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO factura_venta ( id_folio, id_venta, id_usuario, xml, lugar_emision, tipo_comprobante, activa, sellada, forma_pago, fecha_emision, version_tfd, folio_fiscal, fecha_certificacion, numero_certificado_sat, sello_digital_emisor, sello_digital_sat, cadena_original ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			factura_venta.getIdFolio(), 
			factura_venta.getIdVenta(), 
			factura_venta.getIdUsuario(), 
			factura_venta.getXml(), 
			factura_venta.getLugarEmision(), 
			factura_venta.getTipoComprobante(), 
			factura_venta.getActiva(), 
			factura_venta.getSellada(), 
			factura_venta.getFormaPago(), 
			factura_venta.getFechaEmision(), 
			factura_venta.getVersionTfd(), 
			factura_venta.getFolioFiscal(), 
			factura_venta.getFechaCertificacion(), 
			factura_venta.getNumeroCertificadoSat(), 
			factura_venta.getSelloDigitalEmisor(), 
			factura_venta.getSelloDigitalSat(), 
			factura_venta.getCadenaOriginal(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				FacturaVenta._callback_stack.pop().call(null, factura_venta);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta a actualizar.
	  **/
	var update = function( $factura_venta )
	{
		$sql = "UPDATE factura_venta SET  id_venta = ?, id_usuario = ?, xml = ?, lugar_emision = ?, tipo_comprobante = ?, activa = ?, sellada = ?, forma_pago = ?, fecha_emision = ?, version_tfd = ?, folio_fiscal = ?, fecha_certificacion = ?, numero_certificado_sat = ?, sello_digital_emisor = ?, sello_digital_sat = ?, cadena_original = ? WHERE  id_folio = ?;";
		$params = [ 
			$factura_venta.getIdVenta(), 
			$factura_venta.getIdUsuario(), 
			$factura_venta.getXml(), 
			$factura_venta.getLugarEmision(), 
			$factura_venta.getTipoComprobante(), 
			$factura_venta.getActiva(), 
			$factura_venta.getSellada(), 
			$factura_venta.getFormaPago(), 
			$factura_venta.getFechaEmision(), 
			$factura_venta.getVersionTfd(), 
			$factura_venta.getFolioFiscal(), 
			$factura_venta.getFechaCertificacion(), 
			$factura_venta.getNumeroCertificadoSat(), 
			$factura_venta.getSelloDigitalEmisor(), 
			$factura_venta.getSelloDigitalSat(), 
			$factura_venta.getCadenaOriginal(), 
			$factura_venta.getIdFolio(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				FacturaVenta._callback_stack.pop().call(null, $factura_venta);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto FacturaVenta suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( FacturaVenta.getByPK(this.getIdFolio()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM factura_venta WHERE  id_folio = ?;";
		$params = [ this.getIdFolio() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link FacturaVenta} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link FacturaVenta}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $factura_venta , $orderBy , $orden )
	{
		$sql = "SELECT * from factura_venta WHERE ("; 
		$val = [];
		if( (($a = this.getIdFolio()) != null) & ( ($b = $factura_venta.getIdFolio()) != null) ){
				$sql += " id_folio >= ? AND id_folio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_folio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdVenta()) != null) & ( ($b = $factura_venta.getIdVenta()) != null) ){
				$sql += " id_venta >= ? AND id_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $factura_venta.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getXml()) != null) & ( ($b = $factura_venta.getXml()) != null) ){
				$sql += " xml >= ? AND xml <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " xml = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLugarEmision()) != null) & ( ($b = $factura_venta.getLugarEmision()) != null) ){
				$sql += " lugar_emision >= ? AND lugar_emision <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " lugar_emision = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoComprobante()) != null) & ( ($b = $factura_venta.getTipoComprobante()) != null) ){
				$sql += " tipo_comprobante >= ? AND tipo_comprobante <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_comprobante = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getActiva()) != null) & ( ($b = $factura_venta.getActiva()) != null) ){
				$sql += " activa >= ? AND activa <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " activa = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSellada()) != null) & ( ($b = $factura_venta.getSellada()) != null) ){
				$sql += " sellada >= ? AND sellada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " sellada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFormaPago()) != null) & ( ($b = $factura_venta.getFormaPago()) != null) ){
				$sql += " forma_pago >= ? AND forma_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " forma_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaEmision()) != null) & ( ($b = $factura_venta.getFechaEmision()) != null) ){
				$sql += " fecha_emision >= ? AND fecha_emision <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_emision = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getVersionTfd()) != null) & ( ($b = $factura_venta.getVersionTfd()) != null) ){
				$sql += " version_tfd >= ? AND version_tfd <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " version_tfd = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFolioFiscal()) != null) & ( ($b = $factura_venta.getFolioFiscal()) != null) ){
				$sql += " folio_fiscal >= ? AND folio_fiscal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " folio_fiscal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaCertificacion()) != null) & ( ($b = $factura_venta.getFechaCertificacion()) != null) ){
				$sql += " fecha_certificacion >= ? AND fecha_certificacion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_certificacion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroCertificadoSat()) != null) & ( ($b = $factura_venta.getNumeroCertificadoSat()) != null) ){
				$sql += " numero_certificado_sat >= ? AND numero_certificado_sat <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_certificado_sat = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSelloDigitalEmisor()) != null) & ( ($b = $factura_venta.getSelloDigitalEmisor()) != null) ){
				$sql += " sello_digital_emisor >= ? AND sello_digital_emisor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " sello_digital_emisor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSelloDigitalSat()) != null) & ( ($b = $factura_venta.getSelloDigitalSat()) != null) ){
				$sql += " sello_digital_sat >= ? AND sello_digital_sat <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " sello_digital_sat = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCadenaOriginal()) != null) & ( ($b = $factura_venta.getCadenaOriginal()) != null) ){
				$sql += " cadena_original >= ? AND cadena_original <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cadena_original = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new FacturaVenta($foo));
		//}
		return $sql;
	};


}
	FacturaVenta._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link FacturaVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	FacturaVenta.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from factura_venta";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new FacturaVenta( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link FacturaVenta} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link FacturaVenta} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link FacturaVenta Un objeto del tipo {@link FacturaVenta}. NULL si no hay tal registro.
	  **/
	FacturaVenta.getByPK = function(  $id_folio, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM factura_venta WHERE (id_folio = ? ) LIMIT 1;";
		db.query($sql, [ $id_folio] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new FacturaVenta(results.rows.item(0)); 
				FacturaVenta._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : FacturaVenta._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table gastos.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Gastos = function ( config )
{
 /**
	* id_gasto
	* 
	* id para identificar el gasto
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_gasto = config === undefined ? null : config.id_gasto || null,

 /**
	* folio
	* 
	* El folio de la factura para este gasto
	* @access private
	* @var varchar(22)
	*/
	_folio = config === undefined ? null : config.folio || null,

 /**
	* concepto
	* 
	* concepto en lo que se gasto
	* @access private
	* @var varchar(100)
	*/
	_concepto = config === undefined ? null : config.concepto || null,

 /**
	* monto
	* 
	* lo que costo este gasto
	* @access private
	* @var float
	*/
	_monto = config === undefined ? null : config.monto || null,

 /**
	* fecha
	* 
	* fecha del gasto
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null,

 /**
	* fecha_ingreso
	* 
	* Fecha que selecciono el empleado en el sistema
	* @access private
	* @var timestamp
	*/
	_fecha_ingreso = config === undefined ? null : config.fecha_ingreso || null,

 /**
	* id_sucursal
	* 
	* sucursal en la que se hizo el gasto
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* id_usuario
	* 
	* usuario que registro el gasto
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* nota
	* 
	* nota adicional para complementar la descripcion del gasto
	* @access private
	* @var varchar(512)
	*/
	_nota = config === undefined ? null : config.nota || null;

	/**
	  * getIdGasto
	  * 
	  * Get the <i>id_gasto</i> property for this object. Donde <i>id_gasto</i> es id para identificar el gasto
	  * @return int(11)
	  */
	this.getIdGasto = function ()
	{
		return _id_gasto;
	};

	/**
	  * setIdGasto( $id_gasto )
	  * 
	  * Set the <i>id_gasto</i> property for this object. Donde <i>id_gasto</i> es id para identificar el gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_gasto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdGasto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdGasto  = function ( id_gasto )
	{
		_id_gasto = id_gasto;
	};

	/**
	  * getFolio
	  * 
	  * Get the <i>folio</i> property for this object. Donde <i>folio</i> es El folio de la factura para este gasto
	  * @return varchar(22)
	  */
	this.getFolio = function ()
	{
		return _folio;
	};

	/**
	  * setFolio( $folio )
	  * 
	  * Set the <i>folio</i> property for this object. Donde <i>folio</i> es El folio de la factura para este gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>folio</i> es de tipo <i>varchar(22)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(22)
	  */
	this.setFolio  = function ( folio )
	{
		_folio = folio;
	};

	/**
	  * getConcepto
	  * 
	  * Get the <i>concepto</i> property for this object. Donde <i>concepto</i> es concepto en lo que se gasto
	  * @return varchar(100)
	  */
	this.getConcepto = function ()
	{
		return _concepto;
	};

	/**
	  * setConcepto( $concepto )
	  * 
	  * Set the <i>concepto</i> property for this object. Donde <i>concepto</i> es concepto en lo que se gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>concepto</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setConcepto  = function ( concepto )
	{
		_concepto = concepto;
	};

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es lo que costo este gasto
	  * @return float
	  */
	this.getMonto = function ()
	{
		return _monto;
	};

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es lo que costo este gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setMonto  = function ( monto )
	{
		_monto = monto;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha del gasto
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha del gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getFechaIngreso
	  * 
	  * Get the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha que selecciono el empleado en el sistema
	  * @return timestamp
	  */
	this.getFechaIngreso = function ()
	{
		return _fecha_ingreso;
	};

	/**
	  * setFechaIngreso( $fecha_ingreso )
	  * 
	  * Set the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha que selecciono el empleado en el sistema.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_ingreso</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaIngreso  = function ( fecha_ingreso )
	{
		_fecha_ingreso = fecha_ingreso;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en la que se hizo el gasto
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en la que se hizo el gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es usuario que registro el gasto
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es usuario que registro el gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getNota
	  * 
	  * Get the <i>nota</i> property for this object. Donde <i>nota</i> es nota adicional para complementar la descripcion del gasto
	  * @return varchar(512)
	  */
	this.getNota = function ()
	{
		return _nota;
	};

	/**
	  * setNota( $nota )
	  * 
	  * Set the <i>nota</i> property for this object. Donde <i>nota</i> es nota adicional para complementar la descripcion del gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>nota</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	this.setNota  = function ( nota )
	{
		_nota = nota;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Gastos} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Gastos._callback_stack.push( _original_callback  );
		Gastos._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Gastos.getByPK(  this.getIdGasto() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Gastos} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Gastos [$gastos] El objeto de tipo Gastos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from gastos WHERE ("; 
		$val = [];
		if( this.getIdGasto() != null){
			$sql += " id_gasto = ? AND";
			$val.push( this.getIdGasto() );
		}

		if( this.getFolio() != null){
			$sql += " folio = ? AND";
			$val.push( this.getFolio() );
		}

		if( this.getConcepto() != null){
			$sql += " concepto = ? AND";
			$val.push( this.getConcepto() );
		}

		if( this.getMonto() != null){
			$sql += " monto = ? AND";
			$val.push( this.getMonto() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getFechaIngreso() != null){
			$sql += " fecha_ingreso = ? AND";
			$val.push( this.getFechaIngreso() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getNota() != null){
			$sql += " nota = ? AND";
			$val.push( this.getNota() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Gastos($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Gastos suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Gastos dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Gastos [$gastos] El objeto de tipo Gastos a crear.
	  **/
	var create = function( gastos )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO gastos ( id_gasto, folio, concepto, monto, fecha, fecha_ingreso, id_sucursal, id_usuario, nota ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			gastos.getIdGasto(), 
			gastos.getFolio(), 
			gastos.getConcepto(), 
			gastos.getMonto(), 
			gastos.getFecha(), 
			gastos.getFechaIngreso(), 
			gastos.getIdSucursal(), 
			gastos.getIdUsuario(), 
			gastos.getNota(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Gastos._callback_stack.pop().call(null, gastos);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Gastos [$gastos] El objeto de tipo Gastos a actualizar.
	  **/
	var update = function( $gastos )
	{
		$sql = "UPDATE gastos SET  folio = ?, concepto = ?, monto = ?, fecha = ?, fecha_ingreso = ?, id_sucursal = ?, id_usuario = ?, nota = ? WHERE  id_gasto = ?;";
		$params = [ 
			$gastos.getFolio(), 
			$gastos.getConcepto(), 
			$gastos.getMonto(), 
			$gastos.getFecha(), 
			$gastos.getFechaIngreso(), 
			$gastos.getIdSucursal(), 
			$gastos.getIdUsuario(), 
			$gastos.getNota(), 
			$gastos.getIdGasto(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Gastos._callback_stack.pop().call(null, $gastos);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Gastos suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Gastos.getByPK(this.getIdGasto()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM gastos WHERE  id_gasto = ?;";
		$params = [ this.getIdGasto() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Gastos} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Gastos}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Gastos [$gastos] El objeto de tipo Gastos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $gastos , $orderBy , $orden )
	{
		$sql = "SELECT * from gastos WHERE ("; 
		$val = [];
		if( (($a = this.getIdGasto()) != null) & ( ($b = $gastos.getIdGasto()) != null) ){
				$sql += " id_gasto >= ? AND id_gasto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_gasto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFolio()) != null) & ( ($b = $gastos.getFolio()) != null) ){
				$sql += " folio >= ? AND folio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " folio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getConcepto()) != null) & ( ($b = $gastos.getConcepto()) != null) ){
				$sql += " concepto >= ? AND concepto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " concepto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMonto()) != null) & ( ($b = $gastos.getMonto()) != null) ){
				$sql += " monto >= ? AND monto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " monto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $gastos.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaIngreso()) != null) & ( ($b = $gastos.getFechaIngreso()) != null) ){
				$sql += " fecha_ingreso >= ? AND fecha_ingreso <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_ingreso = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $gastos.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $gastos.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNota()) != null) & ( ($b = $gastos.getNota()) != null) ){
				$sql += " nota >= ? AND nota <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " nota = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Gastos($foo));
		//}
		return $sql;
	};


}
	Gastos._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Gastos}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Gastos.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from gastos";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Gastos( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Gastos} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Gastos} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Gastos Un objeto del tipo {@link Gastos}. NULL si no hay tal registro.
	  **/
	Gastos.getByPK = function(  $id_gasto, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM gastos WHERE (id_gasto = ? ) LIMIT 1;";
		db.query($sql, [ $id_gasto] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Gastos(results.rows.item(0)); 
				Gastos._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Gastos._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table grupos.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Grupos = function ( config )
{
 /**
	* id_grupo
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_grupo = config === undefined ? null : config.id_grupo || null,

 /**
	* nombre
	* 
	* Nombre del Grupo
	* @access private
	* @var varchar(45)
	*/
	_nombre = config === undefined ? null : config.nombre || null,

 /**
	* descripcion
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(256)
	*/
	_descripcion = config === undefined ? null : config.descripcion || null;

	/**
	  * getIdGrupo
	  * 
	  * Get the <i>id_grupo</i> property for this object. Donde <i>id_grupo</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdGrupo = function ()
	{
		return _id_grupo;
	};

	/**
	  * setIdGrupo( $id_grupo )
	  * 
	  * Set the <i>id_grupo</i> property for this object. Donde <i>id_grupo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_grupo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdGrupo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdGrupo  = function ( id_grupo )
	{
		_id_grupo = id_grupo;
	};

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es Nombre del Grupo
	  * @return varchar(45)
	  */
	this.getNombre = function ()
	{
		return _nombre;
	};

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es Nombre del Grupo.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre</i> es de tipo <i>varchar(45)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(45)
	  */
	this.setNombre  = function ( nombre )
	{
		_nombre = nombre;
	};

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es  [Campo no documentado]
	  * @return varchar(256)
	  */
	this.getDescripcion = function ()
	{
		return _descripcion;
	};

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	this.setDescripcion  = function ( descripcion )
	{
		_descripcion = descripcion;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Grupos} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Grupos._callback_stack.push( _original_callback  );
		Grupos._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Grupos.getByPK(  this.getIdGrupo() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Grupos} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Grupos [$grupos] El objeto de tipo Grupos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from grupos WHERE ("; 
		$val = [];
		if( this.getIdGrupo() != null){
			$sql += " id_grupo = ? AND";
			$val.push( this.getIdGrupo() );
		}

		if( this.getNombre() != null){
			$sql += " nombre = ? AND";
			$val.push( this.getNombre() );
		}

		if( this.getDescripcion() != null){
			$sql += " descripcion = ? AND";
			$val.push( this.getDescripcion() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Grupos($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Grupos suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Grupos dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Grupos [$grupos] El objeto de tipo Grupos a crear.
	  **/
	var create = function( grupos )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO grupos ( id_grupo, nombre, descripcion ) VALUES ( ?, ?, ?);";
		$params = [
			grupos.getIdGrupo(), 
			grupos.getNombre(), 
			grupos.getDescripcion(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Grupos._callback_stack.pop().call(null, grupos);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Grupos [$grupos] El objeto de tipo Grupos a actualizar.
	  **/
	var update = function( $grupos )
	{
		$sql = "UPDATE grupos SET  nombre = ?, descripcion = ? WHERE  id_grupo = ?;";
		$params = [ 
			$grupos.getNombre(), 
			$grupos.getDescripcion(), 
			$grupos.getIdGrupo(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Grupos._callback_stack.pop().call(null, $grupos);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Grupos suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Grupos.getByPK(this.getIdGrupo()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM grupos WHERE  id_grupo = ?;";
		$params = [ this.getIdGrupo() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Grupos} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Grupos}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Grupos [$grupos] El objeto de tipo Grupos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $grupos , $orderBy , $orden )
	{
		$sql = "SELECT * from grupos WHERE ("; 
		$val = [];
		if( (($a = this.getIdGrupo()) != null) & ( ($b = $grupos.getIdGrupo()) != null) ){
				$sql += " id_grupo >= ? AND id_grupo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_grupo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNombre()) != null) & ( ($b = $grupos.getNombre()) != null) ){
				$sql += " nombre >= ? AND nombre <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " nombre = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcion()) != null) & ( ($b = $grupos.getDescripcion()) != null) ){
				$sql += " descripcion >= ? AND descripcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Grupos($foo));
		//}
		return $sql;
	};


}
	Grupos._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Grupos}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Grupos.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from grupos";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Grupos( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Grupos} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Grupos} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Grupos Un objeto del tipo {@link Grupos}. NULL si no hay tal registro.
	  **/
	Grupos.getByPK = function(  $id_grupo, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM grupos WHERE (id_grupo = ? ) LIMIT 1;";
		db.query($sql, [ $id_grupo] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Grupos(results.rows.item(0)); 
				Grupos._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Grupos._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table grupos_usuarios.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var GruposUsuarios = function ( config )
{
 /**
	* id_grupo
	* 
	*  [Campo no documentado]
	* @access private
	* @var int(11)
	*/
	var _id_grupo = config === undefined ? null : config.id_grupo || null,

 /**
	* id_usuario
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null;

	/**
	  * getIdGrupo
	  * 
	  * Get the <i>id_grupo</i> property for this object. Donde <i>id_grupo</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdGrupo = function ()
	{
		return _id_grupo;
	};

	/**
	  * setIdGrupo( $id_grupo )
	  * 
	  * Set the <i>id_grupo</i> property for this object. Donde <i>id_grupo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_grupo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdGrupo  = function ( id_grupo )
	{
		_id_grupo = id_grupo;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdUsuario( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link GruposUsuarios} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		GruposUsuarios._callback_stack.push( _original_callback  );
		GruposUsuarios._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		GruposUsuarios.getByPK(  this.getIdUsuario() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link GruposUsuarios} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param GruposUsuarios [$grupos_usuarios] El objeto de tipo GruposUsuarios
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from grupos_usuarios WHERE ("; 
		$val = [];
		if( this.getIdGrupo() != null){
			$sql += " id_grupo = ? AND";
			$val.push( this.getIdGrupo() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new GruposUsuarios($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto GruposUsuarios suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto GruposUsuarios dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param GruposUsuarios [$grupos_usuarios] El objeto de tipo GruposUsuarios a crear.
	  **/
	var create = function( grupos_usuarios )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO grupos_usuarios ( id_grupo, id_usuario ) VALUES ( ?, ?);";
		$params = [
			grupos_usuarios.getIdGrupo(), 
			grupos_usuarios.getIdUsuario(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				GruposUsuarios._callback_stack.pop().call(null, grupos_usuarios);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param GruposUsuarios [$grupos_usuarios] El objeto de tipo GruposUsuarios a actualizar.
	  **/
	var update = function( $grupos_usuarios )
	{
		$sql = "UPDATE grupos_usuarios SET  id_grupo = ? WHERE  id_usuario = ?;";
		$params = [ 
			$grupos_usuarios.getIdGrupo(), 
			$grupos_usuarios.getIdUsuario(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				GruposUsuarios._callback_stack.pop().call(null, $grupos_usuarios);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto GruposUsuarios suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( GruposUsuarios.getByPK(this.getIdUsuario()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM grupos_usuarios WHERE  id_usuario = ?;";
		$params = [ this.getIdUsuario() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link GruposUsuarios} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link GruposUsuarios}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param GruposUsuarios [$grupos_usuarios] El objeto de tipo GruposUsuarios
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $grupos_usuarios , $orderBy , $orden )
	{
		$sql = "SELECT * from grupos_usuarios WHERE ("; 
		$val = [];
		if( (($a = this.getIdGrupo()) != null) & ( ($b = $grupos_usuarios.getIdGrupo()) != null) ){
				$sql += " id_grupo >= ? AND id_grupo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_grupo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $grupos_usuarios.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new GruposUsuarios($foo));
		//}
		return $sql;
	};


}
	GruposUsuarios._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link GruposUsuarios}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	GruposUsuarios.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from grupos_usuarios";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new GruposUsuarios( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link GruposUsuarios} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link GruposUsuarios} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link GruposUsuarios Un objeto del tipo {@link GruposUsuarios}. NULL si no hay tal registro.
	  **/
	GruposUsuarios.getByPK = function(  $id_usuario, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM grupos_usuarios WHERE (id_usuario = ? ) LIMIT 1;";
		db.query($sql, [ $id_usuario] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new GruposUsuarios(results.rows.item(0)); 
				GruposUsuarios._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : GruposUsuarios._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table impresiones.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Impresiones = function ( config )
{
 /**
	* id_impresora
	* 
	* id de la impresora
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_impresora = config === undefined ? null : config.id_impresora || null,

 /**
	* id_documento
	* 
	* id del documento
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	_id_documento = config === undefined ? null : config.id_documento || null;

	/**
	  * getIdImpresora
	  * 
	  * Get the <i>id_impresora</i> property for this object. Donde <i>id_impresora</i> es id de la impresora
	  * @return int(11)
	  */
	this.getIdImpresora = function ()
	{
		return _id_impresora;
	};

	/**
	  * setIdImpresora( $id_impresora )
	  * 
	  * Set the <i>id_impresora</i> property for this object. Donde <i>id_impresora</i> es id de la impresora.
	  * Una validacion basica se hara aqui para comprobar que <i>id_impresora</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdImpresora( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdImpresora  = function ( id_impresora )
	{
		_id_impresora = id_impresora;
	};

	/**
	  * getIdDocumento
	  * 
	  * Get the <i>id_documento</i> property for this object. Donde <i>id_documento</i> es id del documento
	  * @return int(11)
	  */
	this.getIdDocumento = function ()
	{
		return _id_documento;
	};

	/**
	  * setIdDocumento( $id_documento )
	  * 
	  * Set the <i>id_documento</i> property for this object. Donde <i>id_documento</i> es id del documento.
	  * Una validacion basica se hara aqui para comprobar que <i>id_documento</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdDocumento( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdDocumento  = function ( id_documento )
	{
		_id_documento = id_documento;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Impresiones} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Impresiones._callback_stack.push( _original_callback  );
		Impresiones._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Impresiones.getByPK(  this.getIdImpresora() , this.getIdDocumento() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Impresiones} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Impresiones [$impresiones] El objeto de tipo Impresiones
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from impresiones WHERE ("; 
		$val = [];
		if( this.getIdImpresora() != null){
			$sql += " id_impresora = ? AND";
			$val.push( this.getIdImpresora() );
		}

		if( this.getIdDocumento() != null){
			$sql += " id_documento = ? AND";
			$val.push( this.getIdDocumento() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Impresiones($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Impresiones suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Impresiones dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Impresiones [$impresiones] El objeto de tipo Impresiones a crear.
	  **/
	var create = function( impresiones )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO impresiones ( id_impresora, id_documento ) VALUES ( ?, ?);";
		$params = [
			impresiones.getIdImpresora(), 
			impresiones.getIdDocumento(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Impresiones._callback_stack.pop().call(null, impresiones);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Impresiones [$impresiones] El objeto de tipo Impresiones a actualizar.
	  **/
	var update = function( $impresiones )
	{
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Impresiones suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Impresiones.getByPK(this.getIdImpresora(), this.getIdDocumento()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM impresiones WHERE  id_impresora = ? AND id_documento = ?;";
		$params = [ this.getIdImpresora(), this.getIdDocumento() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Impresiones} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Impresiones}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Impresiones [$impresiones] El objeto de tipo Impresiones
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $impresiones , $orderBy , $orden )
	{
		$sql = "SELECT * from impresiones WHERE ("; 
		$val = [];
		if( (($a = this.getIdImpresora()) != null) & ( ($b = $impresiones.getIdImpresora()) != null) ){
				$sql += " id_impresora >= ? AND id_impresora <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_impresora = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdDocumento()) != null) & ( ($b = $impresiones.getIdDocumento()) != null) ){
				$sql += " id_documento >= ? AND id_documento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_documento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Impresiones($foo));
		//}
		return $sql;
	};


}
	Impresiones._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Impresiones}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Impresiones.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from impresiones";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Impresiones( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Impresiones} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Impresiones} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Impresiones Un objeto del tipo {@link Impresiones}. NULL si no hay tal registro.
	  **/
	Impresiones.getByPK = function(  $id_impresora, $id_documento, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM impresiones WHERE (id_impresora = ? AND id_documento = ? ) LIMIT 1;";
		db.query($sql, [ $id_impresora, $id_documento] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Impresiones(results.rows.item(0)); 
				Impresiones._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Impresiones._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table impresora.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Impresora = function ( config )
{
 /**
	* id_impresora
	* 
	* id de la impresora
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_impresora = config === undefined ? null : config.id_impresora || null,

 /**
	* id_sucursal
	* 
	* id de la sucursal donde se encuentra esta impresora
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* descripcion
	* 
	* descripcion breve de la impresora
	* @access private
	* @var varchar(256)
	*/
	_descripcion = config === undefined ? null : config.descripcion || null,

 /**
	* identificador
	* 
	* es el nombre de como esta dada de alta la impresora en la sucursal
	* @access private
	* @var varchar(128)
	*/
	_identificador = config === undefined ? null : config.identificador || null;

	/**
	  * getIdImpresora
	  * 
	  * Get the <i>id_impresora</i> property for this object. Donde <i>id_impresora</i> es id de la impresora
	  * @return int(11)
	  */
	this.getIdImpresora = function ()
	{
		return _id_impresora;
	};

	/**
	  * setIdImpresora( $id_impresora )
	  * 
	  * Set the <i>id_impresora</i> property for this object. Donde <i>id_impresora</i> es id de la impresora.
	  * Una validacion basica se hara aqui para comprobar que <i>id_impresora</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdImpresora( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdImpresora  = function ( id_impresora )
	{
		_id_impresora = id_impresora;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal donde se encuentra esta impresora
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal donde se encuentra esta impresora.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion breve de la impresora
	  * @return varchar(256)
	  */
	this.getDescripcion = function ()
	{
		return _descripcion;
	};

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion breve de la impresora.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	this.setDescripcion  = function ( descripcion )
	{
		_descripcion = descripcion;
	};

	/**
	  * getIdentificador
	  * 
	  * Get the <i>identificador</i> property for this object. Donde <i>identificador</i> es es el nombre de como esta dada de alta la impresora en la sucursal
	  * @return varchar(128)
	  */
	this.getIdentificador = function ()
	{
		return _identificador;
	};

	/**
	  * setIdentificador( $identificador )
	  * 
	  * Set the <i>identificador</i> property for this object. Donde <i>identificador</i> es es el nombre de como esta dada de alta la impresora en la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>identificador</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	this.setIdentificador  = function ( identificador )
	{
		_identificador = identificador;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Impresora} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Impresora._callback_stack.push( _original_callback  );
		Impresora._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Impresora.getByPK(  this.getIdImpresora() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Impresora} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Impresora [$impresora] El objeto de tipo Impresora
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from impresora WHERE ("; 
		$val = [];
		if( this.getIdImpresora() != null){
			$sql += " id_impresora = ? AND";
			$val.push( this.getIdImpresora() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getDescripcion() != null){
			$sql += " descripcion = ? AND";
			$val.push( this.getDescripcion() );
		}

		if( this.getIdentificador() != null){
			$sql += " identificador = ? AND";
			$val.push( this.getIdentificador() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Impresora($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Impresora suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Impresora dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Impresora [$impresora] El objeto de tipo Impresora a crear.
	  **/
	var create = function( impresora )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO impresora ( id_impresora, id_sucursal, descripcion, identificador ) VALUES ( ?, ?, ?, ?);";
		$params = [
			impresora.getIdImpresora(), 
			impresora.getIdSucursal(), 
			impresora.getDescripcion(), 
			impresora.getIdentificador(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Impresora._callback_stack.pop().call(null, impresora);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Impresora [$impresora] El objeto de tipo Impresora a actualizar.
	  **/
	var update = function( $impresora )
	{
		$sql = "UPDATE impresora SET  id_sucursal = ?, descripcion = ?, identificador = ? WHERE  id_impresora = ?;";
		$params = [ 
			$impresora.getIdSucursal(), 
			$impresora.getDescripcion(), 
			$impresora.getIdentificador(), 
			$impresora.getIdImpresora(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Impresora._callback_stack.pop().call(null, $impresora);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Impresora suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Impresora.getByPK(this.getIdImpresora()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM impresora WHERE  id_impresora = ?;";
		$params = [ this.getIdImpresora() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Impresora} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Impresora}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Impresora [$impresora] El objeto de tipo Impresora
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $impresora , $orderBy , $orden )
	{
		$sql = "SELECT * from impresora WHERE ("; 
		$val = [];
		if( (($a = this.getIdImpresora()) != null) & ( ($b = $impresora.getIdImpresora()) != null) ){
				$sql += " id_impresora >= ? AND id_impresora <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_impresora = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $impresora.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcion()) != null) & ( ($b = $impresora.getDescripcion()) != null) ){
				$sql += " descripcion >= ? AND descripcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdentificador()) != null) & ( ($b = $impresora.getIdentificador()) != null) ){
				$sql += " identificador >= ? AND identificador <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " identificador = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Impresora($foo));
		//}
		return $sql;
	};


}
	Impresora._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Impresora}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Impresora.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from impresora";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Impresora( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Impresora} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Impresora} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Impresora Un objeto del tipo {@link Impresora}. NULL si no hay tal registro.
	  **/
	Impresora.getByPK = function(  $id_impresora, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM impresora WHERE (id_impresora = ? ) LIMIT 1;";
		db.query($sql, [ $id_impresora] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Impresora(results.rows.item(0)); 
				Impresora._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Impresora._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table ingresos.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Ingresos = function ( config )
{
 /**
	* id_ingreso
	* 
	* id para identificar el ingreso
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_ingreso = config === undefined ? null : config.id_ingreso || null,

 /**
	* concepto
	* 
	* concepto en lo que se ingreso
	* @access private
	* @var varchar(100)
	*/
	_concepto = config === undefined ? null : config.concepto || null,

 /**
	* monto
	* 
	* lo que costo este ingreso
	* @access private
	* @var float
	*/
	_monto = config === undefined ? null : config.monto || null,

 /**
	* fecha
	* 
	* fecha del ingreso
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null,

 /**
	* fecha_ingreso
	* 
	* Fecha que selecciono el empleado en el sistema
	* @access private
	* @var timestamp
	*/
	_fecha_ingreso = config === undefined ? null : config.fecha_ingreso || null,

 /**
	* id_sucursal
	* 
	* sucursal en la que se hizo el ingreso
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* id_usuario
	* 
	* usuario que registro el ingreso
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* nota
	* 
	* nota adicional para complementar la descripcion del ingreso
	* @access private
	* @var varchar(512)
	*/
	_nota = config === undefined ? null : config.nota || null;

	/**
	  * getIdIngreso
	  * 
	  * Get the <i>id_ingreso</i> property for this object. Donde <i>id_ingreso</i> es id para identificar el ingreso
	  * @return int(11)
	  */
	this.getIdIngreso = function ()
	{
		return _id_ingreso;
	};

	/**
	  * setIdIngreso( $id_ingreso )
	  * 
	  * Set the <i>id_ingreso</i> property for this object. Donde <i>id_ingreso</i> es id para identificar el ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>id_ingreso</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdIngreso( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdIngreso  = function ( id_ingreso )
	{
		_id_ingreso = id_ingreso;
	};

	/**
	  * getConcepto
	  * 
	  * Get the <i>concepto</i> property for this object. Donde <i>concepto</i> es concepto en lo que se ingreso
	  * @return varchar(100)
	  */
	this.getConcepto = function ()
	{
		return _concepto;
	};

	/**
	  * setConcepto( $concepto )
	  * 
	  * Set the <i>concepto</i> property for this object. Donde <i>concepto</i> es concepto en lo que se ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>concepto</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setConcepto  = function ( concepto )
	{
		_concepto = concepto;
	};

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es lo que costo este ingreso
	  * @return float
	  */
	this.getMonto = function ()
	{
		return _monto;
	};

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es lo que costo este ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setMonto  = function ( monto )
	{
		_monto = monto;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha del ingreso
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha del ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getFechaIngreso
	  * 
	  * Get the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha que selecciono el empleado en el sistema
	  * @return timestamp
	  */
	this.getFechaIngreso = function ()
	{
		return _fecha_ingreso;
	};

	/**
	  * setFechaIngreso( $fecha_ingreso )
	  * 
	  * Set the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha que selecciono el empleado en el sistema.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_ingreso</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaIngreso  = function ( fecha_ingreso )
	{
		_fecha_ingreso = fecha_ingreso;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en la que se hizo el ingreso
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en la que se hizo el ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es usuario que registro el ingreso
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es usuario que registro el ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getNota
	  * 
	  * Get the <i>nota</i> property for this object. Donde <i>nota</i> es nota adicional para complementar la descripcion del ingreso
	  * @return varchar(512)
	  */
	this.getNota = function ()
	{
		return _nota;
	};

	/**
	  * setNota( $nota )
	  * 
	  * Set the <i>nota</i> property for this object. Donde <i>nota</i> es nota adicional para complementar la descripcion del ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>nota</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	this.setNota  = function ( nota )
	{
		_nota = nota;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Ingresos} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Ingresos._callback_stack.push( _original_callback  );
		Ingresos._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Ingresos.getByPK(  this.getIdIngreso() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ingresos} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from ingresos WHERE ("; 
		$val = [];
		if( this.getIdIngreso() != null){
			$sql += " id_ingreso = ? AND";
			$val.push( this.getIdIngreso() );
		}

		if( this.getConcepto() != null){
			$sql += " concepto = ? AND";
			$val.push( this.getConcepto() );
		}

		if( this.getMonto() != null){
			$sql += " monto = ? AND";
			$val.push( this.getMonto() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getFechaIngreso() != null){
			$sql += " fecha_ingreso = ? AND";
			$val.push( this.getFechaIngreso() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getNota() != null){
			$sql += " nota = ? AND";
			$val.push( this.getNota() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Ingresos($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Ingresos suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Ingresos dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos a crear.
	  **/
	var create = function( ingresos )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO ingresos ( id_ingreso, concepto, monto, fecha, fecha_ingreso, id_sucursal, id_usuario, nota ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			ingresos.getIdIngreso(), 
			ingresos.getConcepto(), 
			ingresos.getMonto(), 
			ingresos.getFecha(), 
			ingresos.getFechaIngreso(), 
			ingresos.getIdSucursal(), 
			ingresos.getIdUsuario(), 
			ingresos.getNota(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Ingresos._callback_stack.pop().call(null, ingresos);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos a actualizar.
	  **/
	var update = function( $ingresos )
	{
		$sql = "UPDATE ingresos SET  concepto = ?, monto = ?, fecha = ?, fecha_ingreso = ?, id_sucursal = ?, id_usuario = ?, nota = ? WHERE  id_ingreso = ?;";
		$params = [ 
			$ingresos.getConcepto(), 
			$ingresos.getMonto(), 
			$ingresos.getFecha(), 
			$ingresos.getFechaIngreso(), 
			$ingresos.getIdSucursal(), 
			$ingresos.getIdUsuario(), 
			$ingresos.getNota(), 
			$ingresos.getIdIngreso(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Ingresos._callback_stack.pop().call(null, $ingresos);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Ingresos suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Ingresos.getByPK(this.getIdIngreso()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM ingresos WHERE  id_ingreso = ?;";
		$params = [ this.getIdIngreso() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ingresos} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Ingresos}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $ingresos , $orderBy , $orden )
	{
		$sql = "SELECT * from ingresos WHERE ("; 
		$val = [];
		if( (($a = this.getIdIngreso()) != null) & ( ($b = $ingresos.getIdIngreso()) != null) ){
				$sql += " id_ingreso >= ? AND id_ingreso <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_ingreso = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getConcepto()) != null) & ( ($b = $ingresos.getConcepto()) != null) ){
				$sql += " concepto >= ? AND concepto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " concepto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMonto()) != null) & ( ($b = $ingresos.getMonto()) != null) ){
				$sql += " monto >= ? AND monto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " monto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $ingresos.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaIngreso()) != null) & ( ($b = $ingresos.getFechaIngreso()) != null) ){
				$sql += " fecha_ingreso >= ? AND fecha_ingreso <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_ingreso = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $ingresos.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $ingresos.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNota()) != null) & ( ($b = $ingresos.getNota()) != null) ){
				$sql += " nota >= ? AND nota <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " nota = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Ingresos($foo));
		//}
		return $sql;
	};


}
	Ingresos._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Ingresos}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Ingresos.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from ingresos";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Ingresos( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Ingresos} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Ingresos} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Ingresos Un objeto del tipo {@link Ingresos}. NULL si no hay tal registro.
	  **/
	Ingresos.getByPK = function(  $id_ingreso, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM ingresos WHERE (id_ingreso = ? ) LIMIT 1;";
		db.query($sql, [ $id_ingreso] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Ingresos(results.rows.item(0)); 
				Ingresos._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Ingresos._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table inventario.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Inventario = function ( config )
{
 /**
	* id_producto
	* 
	* id del producto
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_producto = config === undefined ? null : config.id_producto || null,

 /**
	* descripcion
	* 
	* descripcion del producto
	* @access private
	* @var varchar(30)
	*/
	_descripcion = config === undefined ? null : config.descripcion || null,

 /**
	* escala
	* 
	*  [Campo no documentado]
	* @access private
	* @var enum('kilogramo','pieza','litro','unidad')
	*/
	_escala = config === undefined ? null : config.escala || null,

 /**
	* tratamiento
	* 
	* Tipo de tratatiento si es que existe para este producto.
	* @access private
	* @var enum('limpia')
	*/
	_tratamiento = config === undefined ? null : config.tratamiento || null,

 /**
	* agrupacion
	* 
	* La agrupacion de este producto
	* @access private
	* @var varchar(8)
	*/
	_agrupacion = config === undefined ? null : config.agrupacion || null,

 /**
	* agrupacionTam
	* 
	* El tamano de cada agrupacion
	* @access private
	* @var float
	*/
	_agrupacionTam = config === undefined ? null : config.agrupacionTam || null,

 /**
	* activo
	* 
	* si este producto esta activo o no en el sistema
	* @access private
	* @var tinyint(1)
	*/
	_activo = config === undefined ? null : config.activo || null,

 /**
	* precio_por_agrupacion
	* 
	*  [Campo no documentado]
	* @access private
	* @var tinyint(1)
	*/
	_precio_por_agrupacion = config === undefined ? null : config.precio_por_agrupacion || null;

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es id del producto
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es id del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion del producto
	  * @return varchar(30)
	  */
	this.getDescripcion = function ()
	{
		return _descripcion;
	};

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(30)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(30)
	  */
	this.setDescripcion  = function ( descripcion )
	{
		_descripcion = descripcion;
	};

	/**
	  * getEscala
	  * 
	  * Get the <i>escala</i> property for this object. Donde <i>escala</i> es  [Campo no documentado]
	  * @return enum('kilogramo','pieza','litro','unidad')
	  */
	this.getEscala = function ()
	{
		return _escala;
	};

	/**
	  * setEscala( $escala )
	  * 
	  * Set the <i>escala</i> property for this object. Donde <i>escala</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>escala</i> es de tipo <i>enum('kilogramo','pieza','litro','unidad')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('kilogramo','pieza','litro','unidad')
	  */
	this.setEscala  = function ( escala )
	{
		_escala = escala;
	};

	/**
	  * getTratamiento
	  * 
	  * Get the <i>tratamiento</i> property for this object. Donde <i>tratamiento</i> es Tipo de tratatiento si es que existe para este producto.
	  * @return enum('limpia')
	  */
	this.getTratamiento = function ()
	{
		return _tratamiento;
	};

	/**
	  * setTratamiento( $tratamiento )
	  * 
	  * Set the <i>tratamiento</i> property for this object. Donde <i>tratamiento</i> es Tipo de tratatiento si es que existe para este producto..
	  * Una validacion basica se hara aqui para comprobar que <i>tratamiento</i> es de tipo <i>enum('limpia')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('limpia')
	  */
	this.setTratamiento  = function ( tratamiento )
	{
		_tratamiento = tratamiento;
	};

	/**
	  * getAgrupacion
	  * 
	  * Get the <i>agrupacion</i> property for this object. Donde <i>agrupacion</i> es La agrupacion de este producto
	  * @return varchar(8)
	  */
	this.getAgrupacion = function ()
	{
		return _agrupacion;
	};

	/**
	  * setAgrupacion( $agrupacion )
	  * 
	  * Set the <i>agrupacion</i> property for this object. Donde <i>agrupacion</i> es La agrupacion de este producto.
	  * Una validacion basica se hara aqui para comprobar que <i>agrupacion</i> es de tipo <i>varchar(8)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(8)
	  */
	this.setAgrupacion  = function ( agrupacion )
	{
		_agrupacion = agrupacion;
	};

	/**
	  * getAgrupacionTam
	  * 
	  * Get the <i>agrupacionTam</i> property for this object. Donde <i>agrupacionTam</i> es El tamano de cada agrupacion
	  * @return float
	  */
	this.getAgrupacionTam = function ()
	{
		return _agrupacionTam;
	};

	/**
	  * setAgrupacionTam( $agrupacionTam )
	  * 
	  * Set the <i>agrupacionTam</i> property for this object. Donde <i>agrupacionTam</i> es El tamano de cada agrupacion.
	  * Una validacion basica se hara aqui para comprobar que <i>agrupacionTam</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setAgrupacionTam  = function ( agrupacionTam )
	{
		_agrupacionTam = agrupacionTam;
	};

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es si este producto esta activo o no en el sistema
	  * @return tinyint(1)
	  */
	this.getActivo = function ()
	{
		return _activo;
	};

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es si este producto esta activo o no en el sistema.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setActivo  = function ( activo )
	{
		_activo = activo;
	};

	/**
	  * getPrecioPorAgrupacion
	  * 
	  * Get the <i>precio_por_agrupacion</i> property for this object. Donde <i>precio_por_agrupacion</i> es  [Campo no documentado]
	  * @return tinyint(1)
	  */
	this.getPrecioPorAgrupacion = function ()
	{
		return _precio_por_agrupacion;
	};

	/**
	  * setPrecioPorAgrupacion( $precio_por_agrupacion )
	  * 
	  * Set the <i>precio_por_agrupacion</i> property for this object. Donde <i>precio_por_agrupacion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_por_agrupacion</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setPrecioPorAgrupacion  = function ( precio_por_agrupacion )
	{
		_precio_por_agrupacion = precio_por_agrupacion;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Inventario} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Inventario._callback_stack.push( _original_callback  );
		Inventario._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Inventario.getByPK(  this.getIdProducto() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Inventario} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Inventario [$inventario] El objeto de tipo Inventario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from inventario WHERE ("; 
		$val = [];
		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getDescripcion() != null){
			$sql += " descripcion = ? AND";
			$val.push( this.getDescripcion() );
		}

		if( this.getEscala() != null){
			$sql += " escala = ? AND";
			$val.push( this.getEscala() );
		}

		if( this.getTratamiento() != null){
			$sql += " tratamiento = ? AND";
			$val.push( this.getTratamiento() );
		}

		if( this.getAgrupacion() != null){
			$sql += " agrupacion = ? AND";
			$val.push( this.getAgrupacion() );
		}

		if( this.getAgrupacionTam() != null){
			$sql += " agrupacionTam = ? AND";
			$val.push( this.getAgrupacionTam() );
		}

		if( this.getActivo() != null){
			$sql += " activo = ? AND";
			$val.push( this.getActivo() );
		}

		if( this.getPrecioPorAgrupacion() != null){
			$sql += " precio_por_agrupacion = ? AND";
			$val.push( this.getPrecioPorAgrupacion() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Inventario($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Inventario suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Inventario dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Inventario [$inventario] El objeto de tipo Inventario a crear.
	  **/
	var create = function( inventario )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO inventario ( id_producto, descripcion, escala, tratamiento, agrupacion, agrupacionTam, activo, precio_por_agrupacion ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			inventario.getIdProducto(), 
			inventario.getDescripcion(), 
			inventario.getEscala(), 
			inventario.getTratamiento(), 
			inventario.getAgrupacion(), 
			inventario.getAgrupacionTam(), 
			inventario.getActivo(), 
			inventario.getPrecioPorAgrupacion(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Inventario._callback_stack.pop().call(null, inventario);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Inventario [$inventario] El objeto de tipo Inventario a actualizar.
	  **/
	var update = function( $inventario )
	{
		$sql = "UPDATE inventario SET  descripcion = ?, escala = ?, tratamiento = ?, agrupacion = ?, agrupacionTam = ?, activo = ?, precio_por_agrupacion = ? WHERE  id_producto = ?;";
		$params = [ 
			$inventario.getDescripcion(), 
			$inventario.getEscala(), 
			$inventario.getTratamiento(), 
			$inventario.getAgrupacion(), 
			$inventario.getAgrupacionTam(), 
			$inventario.getActivo(), 
			$inventario.getPrecioPorAgrupacion(), 
			$inventario.getIdProducto(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Inventario._callback_stack.pop().call(null, $inventario);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Inventario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Inventario.getByPK(this.getIdProducto()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM inventario WHERE  id_producto = ?;";
		$params = [ this.getIdProducto() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Inventario} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Inventario}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Inventario [$inventario] El objeto de tipo Inventario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $inventario , $orderBy , $orden )
	{
		$sql = "SELECT * from inventario WHERE ("; 
		$val = [];
		if( (($a = this.getIdProducto()) != null) & ( ($b = $inventario.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcion()) != null) & ( ($b = $inventario.getDescripcion()) != null) ){
				$sql += " descripcion >= ? AND descripcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getEscala()) != null) & ( ($b = $inventario.getEscala()) != null) ){
				$sql += " escala >= ? AND escala <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " escala = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTratamiento()) != null) & ( ($b = $inventario.getTratamiento()) != null) ){
				$sql += " tratamiento >= ? AND tratamiento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tratamiento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getAgrupacion()) != null) & ( ($b = $inventario.getAgrupacion()) != null) ){
				$sql += " agrupacion >= ? AND agrupacion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " agrupacion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getAgrupacionTam()) != null) & ( ($b = $inventario.getAgrupacionTam()) != null) ){
				$sql += " agrupacionTam >= ? AND agrupacionTam <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " agrupacionTam = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getActivo()) != null) & ( ($b = $inventario.getActivo()) != null) ){
				$sql += " activo >= ? AND activo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " activo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioPorAgrupacion()) != null) & ( ($b = $inventario.getPrecioPorAgrupacion()) != null) ){
				$sql += " precio_por_agrupacion >= ? AND precio_por_agrupacion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_por_agrupacion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Inventario($foo));
		//}
		return $sql;
	};


}
	Inventario._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Inventario}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Inventario.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from inventario";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Inventario( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Inventario} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Inventario} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Inventario Un objeto del tipo {@link Inventario}. NULL si no hay tal registro.
	  **/
	Inventario.getByPK = function(  $id_producto, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM inventario WHERE (id_producto = ? ) LIMIT 1;";
		db.query($sql, [ $id_producto] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Inventario(results.rows.item(0)); 
				Inventario._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Inventario._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table inventario_maestro.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var InventarioMaestro = function ( config )
{
 /**
	* id_producto
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_producto = config === undefined ? null : config.id_producto || null,

 /**
	* id_compra_proveedor
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	_id_compra_proveedor = config === undefined ? null : config.id_compra_proveedor || null,

 /**
	* existencias
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_existencias = config === undefined ? null : config.existencias || null,

 /**
	* existencias_procesadas
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_existencias_procesadas = config === undefined ? null : config.existencias_procesadas || null,

 /**
	* sitio_descarga
	* 
	*  [Campo no documentado]
	* @access private
	* @var int(11)
	*/
	_sitio_descarga = config === undefined ? null : config.sitio_descarga || null;

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getIdCompraProveedor
	  * 
	  * Get the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdCompraProveedor = function ()
	{
		return _id_compra_proveedor;
	};

	/**
	  * setIdCompraProveedor( $id_compra_proveedor )
	  * 
	  * Set the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompraProveedor( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdCompraProveedor  = function ( id_compra_proveedor )
	{
		_id_compra_proveedor = id_compra_proveedor;
	};

	/**
	  * getExistencias
	  * 
	  * Get the <i>existencias</i> property for this object. Donde <i>existencias</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getExistencias = function ()
	{
		return _existencias;
	};

	/**
	  * setExistencias( $existencias )
	  * 
	  * Set the <i>existencias</i> property for this object. Donde <i>existencias</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>existencias</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setExistencias  = function ( existencias )
	{
		_existencias = existencias;
	};

	/**
	  * getExistenciasProcesadas
	  * 
	  * Get the <i>existencias_procesadas</i> property for this object. Donde <i>existencias_procesadas</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getExistenciasProcesadas = function ()
	{
		return _existencias_procesadas;
	};

	/**
	  * setExistenciasProcesadas( $existencias_procesadas )
	  * 
	  * Set the <i>existencias_procesadas</i> property for this object. Donde <i>existencias_procesadas</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>existencias_procesadas</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setExistenciasProcesadas  = function ( existencias_procesadas )
	{
		_existencias_procesadas = existencias_procesadas;
	};

	/**
	  * getSitioDescarga
	  * 
	  * Get the <i>sitio_descarga</i> property for this object. Donde <i>sitio_descarga</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getSitioDescarga = function ()
	{
		return _sitio_descarga;
	};

	/**
	  * setSitioDescarga( $sitio_descarga )
	  * 
	  * Set the <i>sitio_descarga</i> property for this object. Donde <i>sitio_descarga</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>sitio_descarga</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setSitioDescarga  = function ( sitio_descarga )
	{
		_sitio_descarga = sitio_descarga;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link InventarioMaestro} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		InventarioMaestro._callback_stack.push( _original_callback  );
		InventarioMaestro._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		InventarioMaestro.getByPK(  this.getIdProducto() , this.getIdCompraProveedor() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link InventarioMaestro} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param InventarioMaestro [$inventario_maestro] El objeto de tipo InventarioMaestro
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from inventario_maestro WHERE ("; 
		$val = [];
		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getIdCompraProveedor() != null){
			$sql += " id_compra_proveedor = ? AND";
			$val.push( this.getIdCompraProveedor() );
		}

		if( this.getExistencias() != null){
			$sql += " existencias = ? AND";
			$val.push( this.getExistencias() );
		}

		if( this.getExistenciasProcesadas() != null){
			$sql += " existencias_procesadas = ? AND";
			$val.push( this.getExistenciasProcesadas() );
		}

		if( this.getSitioDescarga() != null){
			$sql += " sitio_descarga = ? AND";
			$val.push( this.getSitioDescarga() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new InventarioMaestro($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto InventarioMaestro suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto InventarioMaestro dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param InventarioMaestro [$inventario_maestro] El objeto de tipo InventarioMaestro a crear.
	  **/
	var create = function( inventario_maestro )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO inventario_maestro ( id_producto, id_compra_proveedor, existencias, existencias_procesadas, sitio_descarga ) VALUES ( ?, ?, ?, ?, ?);";
		$params = [
			inventario_maestro.getIdProducto(), 
			inventario_maestro.getIdCompraProveedor(), 
			inventario_maestro.getExistencias(), 
			inventario_maestro.getExistenciasProcesadas(), 
			inventario_maestro.getSitioDescarga(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				InventarioMaestro._callback_stack.pop().call(null, inventario_maestro);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param InventarioMaestro [$inventario_maestro] El objeto de tipo InventarioMaestro a actualizar.
	  **/
	var update = function( $inventario_maestro )
	{
		$sql = "UPDATE inventario_maestro SET  existencias = ?, existencias_procesadas = ?, sitio_descarga = ? WHERE  id_producto = ? AND id_compra_proveedor = ?;";
		$params = [ 
			$inventario_maestro.getExistencias(), 
			$inventario_maestro.getExistenciasProcesadas(), 
			$inventario_maestro.getSitioDescarga(), 
			$inventario_maestro.getIdProducto(),$inventario_maestro.getIdCompraProveedor(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				InventarioMaestro._callback_stack.pop().call(null, $inventario_maestro);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto InventarioMaestro suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( InventarioMaestro.getByPK(this.getIdProducto(), this.getIdCompraProveedor()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM inventario_maestro WHERE  id_producto = ? AND id_compra_proveedor = ?;";
		$params = [ this.getIdProducto(), this.getIdCompraProveedor() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link InventarioMaestro} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link InventarioMaestro}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param InventarioMaestro [$inventario_maestro] El objeto de tipo InventarioMaestro
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $inventario_maestro , $orderBy , $orden )
	{
		$sql = "SELECT * from inventario_maestro WHERE ("; 
		$val = [];
		if( (($a = this.getIdProducto()) != null) & ( ($b = $inventario_maestro.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdCompraProveedor()) != null) & ( ($b = $inventario_maestro.getIdCompraProveedor()) != null) ){
				$sql += " id_compra_proveedor >= ? AND id_compra_proveedor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra_proveedor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getExistencias()) != null) & ( ($b = $inventario_maestro.getExistencias()) != null) ){
				$sql += " existencias >= ? AND existencias <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " existencias = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getExistenciasProcesadas()) != null) & ( ($b = $inventario_maestro.getExistenciasProcesadas()) != null) ){
				$sql += " existencias_procesadas >= ? AND existencias_procesadas <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " existencias_procesadas = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSitioDescarga()) != null) & ( ($b = $inventario_maestro.getSitioDescarga()) != null) ){
				$sql += " sitio_descarga >= ? AND sitio_descarga <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " sitio_descarga = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new InventarioMaestro($foo));
		//}
		return $sql;
	};


}
	InventarioMaestro._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link InventarioMaestro}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	InventarioMaestro.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from inventario_maestro";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new InventarioMaestro( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link InventarioMaestro} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link InventarioMaestro} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link InventarioMaestro Un objeto del tipo {@link InventarioMaestro}. NULL si no hay tal registro.
	  **/
	InventarioMaestro.getByPK = function(  $id_producto, $id_compra_proveedor, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM inventario_maestro WHERE (id_producto = ? AND id_compra_proveedor = ? ) LIMIT 1;";
		db.query($sql, [ $id_producto, $id_compra_proveedor] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new InventarioMaestro(results.rows.item(0)); 
				InventarioMaestro._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : InventarioMaestro._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table pagos_compra.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var PagosCompra = function ( config )
{
 /**
	* id_pago
	* 
	* identificador del pago
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_pago = config === undefined ? null : config.id_pago || null,

 /**
	* id_compra
	* 
	* identificador de la compra a la que pagamos
	* @access private
	* @var int(11)
	*/
	_id_compra = config === undefined ? null : config.id_compra || null,

 /**
	* fecha
	* 
	* fecha en que se abono
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null,

 /**
	* monto
	* 
	* monto que se abono
	* @access private
	* @var float
	*/
	_monto = config === undefined ? null : config.monto || null;

	/**
	  * getIdPago
	  * 
	  * Get the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es identificador del pago
	  * @return int(11)
	  */
	this.getIdPago = function ()
	{
		return _id_pago;
	};

	/**
	  * setIdPago( $id_pago )
	  * 
	  * Set the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es identificador del pago.
	  * Una validacion basica se hara aqui para comprobar que <i>id_pago</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdPago( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdPago  = function ( id_pago )
	{
		_id_pago = id_pago;
	};

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es identificador de la compra a la que pagamos
	  * @return int(11)
	  */
	this.getIdCompra = function ()
	{
		return _id_compra;
	};

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es identificador de la compra a la que pagamos.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdCompra  = function ( id_compra )
	{
		_id_compra = id_compra;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha en que se abono
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha en que se abono.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es monto que se abono
	  * @return float
	  */
	this.getMonto = function ()
	{
		return _monto;
	};

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es monto que se abono.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setMonto  = function ( monto )
	{
		_monto = monto;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link PagosCompra} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		PagosCompra._callback_stack.push( _original_callback  );
		PagosCompra._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		PagosCompra.getByPK(  this.getIdPago() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PagosCompra} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param PagosCompra [$pagos_compra] El objeto de tipo PagosCompra
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from pagos_compra WHERE ("; 
		$val = [];
		if( this.getIdPago() != null){
			$sql += " id_pago = ? AND";
			$val.push( this.getIdPago() );
		}

		if( this.getIdCompra() != null){
			$sql += " id_compra = ? AND";
			$val.push( this.getIdCompra() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getMonto() != null){
			$sql += " monto = ? AND";
			$val.push( this.getMonto() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PagosCompra($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto PagosCompra suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto PagosCompra dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param PagosCompra [$pagos_compra] El objeto de tipo PagosCompra a crear.
	  **/
	var create = function( pagos_compra )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO pagos_compra ( id_pago, id_compra, fecha, monto ) VALUES ( ?, ?, ?, ?);";
		$params = [
			pagos_compra.getIdPago(), 
			pagos_compra.getIdCompra(), 
			pagos_compra.getFecha(), 
			pagos_compra.getMonto(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				PagosCompra._callback_stack.pop().call(null, pagos_compra);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param PagosCompra [$pagos_compra] El objeto de tipo PagosCompra a actualizar.
	  **/
	var update = function( $pagos_compra )
	{
		$sql = "UPDATE pagos_compra SET  id_compra = ?, fecha = ?, monto = ? WHERE  id_pago = ?;";
		$params = [ 
			$pagos_compra.getIdCompra(), 
			$pagos_compra.getFecha(), 
			$pagos_compra.getMonto(), 
			$pagos_compra.getIdPago(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				PagosCompra._callback_stack.pop().call(null, $pagos_compra);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto PagosCompra suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( PagosCompra.getByPK(this.getIdPago()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM pagos_compra WHERE  id_pago = ?;";
		$params = [ this.getIdPago() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PagosCompra} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link PagosCompra}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param PagosCompra [$pagos_compra] El objeto de tipo PagosCompra
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $pagos_compra , $orderBy , $orden )
	{
		$sql = "SELECT * from pagos_compra WHERE ("; 
		$val = [];
		if( (($a = this.getIdPago()) != null) & ( ($b = $pagos_compra.getIdPago()) != null) ){
				$sql += " id_pago >= ? AND id_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdCompra()) != null) & ( ($b = $pagos_compra.getIdCompra()) != null) ){
				$sql += " id_compra >= ? AND id_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $pagos_compra.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMonto()) != null) & ( ($b = $pagos_compra.getMonto()) != null) ){
				$sql += " monto >= ? AND monto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " monto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PagosCompra($foo));
		//}
		return $sql;
	};


}
	PagosCompra._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link PagosCompra}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	PagosCompra.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from pagos_compra";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new PagosCompra( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link PagosCompra} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link PagosCompra} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link PagosCompra Un objeto del tipo {@link PagosCompra}. NULL si no hay tal registro.
	  **/
	PagosCompra.getByPK = function(  $id_pago, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM pagos_compra WHERE (id_pago = ? ) LIMIT 1;";
		db.query($sql, [ $id_pago] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new PagosCompra(results.rows.item(0)); 
				PagosCompra._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : PagosCompra._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table pagos_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var PagosVenta = function ( config )
{
 /**
	* id_pago
	* 
	* id de pago del cliente
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_pago = config === undefined ? null : config.id_pago || null,

 /**
	* id_venta
	* 
	* id de la venta a la que se esta pagando
	* @access private
	* @var int(11)
	*/
	_id_venta = config === undefined ? null : config.id_venta || null,

 /**
	* id_sucursal
	* 
	* Donde se realizo el pago
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* id_usuario
	* 
	* Quien cobro este pago
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* fecha
	* 
	* Fecha en que se registro el pago
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null,

 /**
	* monto
	* 
	* total de credito del cliente
	* @access private
	* @var float
	*/
	_monto = config === undefined ? null : config.monto || null,

 /**
	* tipo_pago
	* 
	* tipo de pago para este abono
	* @access private
	* @var enum('efectivo','cheque','tarjeta')
	*/
	_tipo_pago = config === undefined ? null : config.tipo_pago || null;

	/**
	  * getIdPago
	  * 
	  * Get the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es id de pago del cliente
	  * @return int(11)
	  */
	this.getIdPago = function ()
	{
		return _id_pago;
	};

	/**
	  * setIdPago( $id_pago )
	  * 
	  * Set the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es id de pago del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_pago</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdPago( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdPago  = function ( id_pago )
	{
		_id_pago = id_pago;
	};

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de la venta a la que se esta pagando
	  * @return int(11)
	  */
	this.getIdVenta = function ()
	{
		return _id_venta;
	};

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de la venta a la que se esta pagando.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdVenta  = function ( id_venta )
	{
		_id_venta = id_venta;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Donde se realizo el pago
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Donde se realizo el pago.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Quien cobro este pago
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Quien cobro este pago.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es Fecha en que se registro el pago
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es Fecha en que se registro el pago.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es total de credito del cliente
	  * @return float
	  */
	this.getMonto = function ()
	{
		return _monto;
	};

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es total de credito del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setMonto  = function ( monto )
	{
		_monto = monto;
	};

	/**
	  * getTipoPago
	  * 
	  * Get the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para este abono
	  * @return enum('efectivo','cheque','tarjeta')
	  */
	this.getTipoPago = function ()
	{
		return _tipo_pago;
	};

	/**
	  * setTipoPago( $tipo_pago )
	  * 
	  * Set the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para este abono.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_pago</i> es de tipo <i>enum('efectivo','cheque','tarjeta')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('efectivo','cheque','tarjeta')
	  */
	this.setTipoPago  = function ( tipo_pago )
	{
		_tipo_pago = tipo_pago;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link PagosVenta} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		PagosVenta._callback_stack.push( _original_callback  );
		PagosVenta._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		PagosVenta.getByPK(  this.getIdPago() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PagosVenta} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from pagos_venta WHERE ("; 
		$val = [];
		if( this.getIdPago() != null){
			$sql += " id_pago = ? AND";
			$val.push( this.getIdPago() );
		}

		if( this.getIdVenta() != null){
			$sql += " id_venta = ? AND";
			$val.push( this.getIdVenta() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getMonto() != null){
			$sql += " monto = ? AND";
			$val.push( this.getMonto() );
		}

		if( this.getTipoPago() != null){
			$sql += " tipo_pago = ? AND";
			$val.push( this.getTipoPago() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PagosVenta($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto PagosVenta suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto PagosVenta dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta a crear.
	  **/
	var create = function( pagos_venta )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO pagos_venta ( id_pago, id_venta, id_sucursal, id_usuario, fecha, monto, tipo_pago ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			pagos_venta.getIdPago(), 
			pagos_venta.getIdVenta(), 
			pagos_venta.getIdSucursal(), 
			pagos_venta.getIdUsuario(), 
			pagos_venta.getFecha(), 
			pagos_venta.getMonto(), 
			pagos_venta.getTipoPago(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				PagosVenta._callback_stack.pop().call(null, pagos_venta);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta a actualizar.
	  **/
	var update = function( $pagos_venta )
	{
		$sql = "UPDATE pagos_venta SET  id_venta = ?, id_sucursal = ?, id_usuario = ?, fecha = ?, monto = ?, tipo_pago = ? WHERE  id_pago = ?;";
		$params = [ 
			$pagos_venta.getIdVenta(), 
			$pagos_venta.getIdSucursal(), 
			$pagos_venta.getIdUsuario(), 
			$pagos_venta.getFecha(), 
			$pagos_venta.getMonto(), 
			$pagos_venta.getTipoPago(), 
			$pagos_venta.getIdPago(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				PagosVenta._callback_stack.pop().call(null, $pagos_venta);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto PagosVenta suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( PagosVenta.getByPK(this.getIdPago()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM pagos_venta WHERE  id_pago = ?;";
		$params = [ this.getIdPago() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PagosVenta} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link PagosVenta}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $pagos_venta , $orderBy , $orden )
	{
		$sql = "SELECT * from pagos_venta WHERE ("; 
		$val = [];
		if( (($a = this.getIdPago()) != null) & ( ($b = $pagos_venta.getIdPago()) != null) ){
				$sql += " id_pago >= ? AND id_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdVenta()) != null) & ( ($b = $pagos_venta.getIdVenta()) != null) ){
				$sql += " id_venta >= ? AND id_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $pagos_venta.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $pagos_venta.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $pagos_venta.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMonto()) != null) & ( ($b = $pagos_venta.getMonto()) != null) ){
				$sql += " monto >= ? AND monto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " monto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoPago()) != null) & ( ($b = $pagos_venta.getTipoPago()) != null) ){
				$sql += " tipo_pago >= ? AND tipo_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PagosVenta($foo));
		//}
		return $sql;
	};


}
	PagosVenta._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link PagosVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	PagosVenta.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from pagos_venta";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new PagosVenta( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link PagosVenta} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link PagosVenta} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link PagosVenta Un objeto del tipo {@link PagosVenta}. NULL si no hay tal registro.
	  **/
	PagosVenta.getByPK = function(  $id_pago, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM pagos_venta WHERE (id_pago = ? ) LIMIT 1;";
		db.query($sql, [ $id_pago] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new PagosVenta(results.rows.item(0)); 
				PagosVenta._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : PagosVenta._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table pago_prestamo_sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var PagoPrestamoSucursal = function ( config )
{
 /**
	* id_pago
	* 
	* El identificador de este pago
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_pago = config === undefined ? null : config.id_pago || null,

 /**
	* id_prestamo
	* 
	* El id del prestamo al que pertenece este prestamo
	* @access private
	* @var int(11)
	*/
	_id_prestamo = config === undefined ? null : config.id_prestamo || null,

 /**
	* id_usuario
	* 
	* El usurio que recibe este dinero
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* fecha
	* 
	* La fecha cuando se realizo este pago
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null,

 /**
	* monto
	* 
	* El monto a abonar
	* @access private
	* @var float
	*/
	_monto = config === undefined ? null : config.monto || null;

	/**
	  * getIdPago
	  * 
	  * Get the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es El identificador de este pago
	  * @return int(11)
	  */
	this.getIdPago = function ()
	{
		return _id_pago;
	};

	/**
	  * setIdPago( $id_pago )
	  * 
	  * Set the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es El identificador de este pago.
	  * Una validacion basica se hara aqui para comprobar que <i>id_pago</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdPago( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdPago  = function ( id_pago )
	{
		_id_pago = id_pago;
	};

	/**
	  * getIdPrestamo
	  * 
	  * Get the <i>id_prestamo</i> property for this object. Donde <i>id_prestamo</i> es El id del prestamo al que pertenece este prestamo
	  * @return int(11)
	  */
	this.getIdPrestamo = function ()
	{
		return _id_prestamo;
	};

	/**
	  * setIdPrestamo( $id_prestamo )
	  * 
	  * Set the <i>id_prestamo</i> property for this object. Donde <i>id_prestamo</i> es El id del prestamo al que pertenece este prestamo.
	  * Una validacion basica se hara aqui para comprobar que <i>id_prestamo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdPrestamo  = function ( id_prestamo )
	{
		_id_prestamo = id_prestamo;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es El usurio que recibe este dinero
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es El usurio que recibe este dinero.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es La fecha cuando se realizo este pago
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es La fecha cuando se realizo este pago.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es El monto a abonar
	  * @return float
	  */
	this.getMonto = function ()
	{
		return _monto;
	};

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es El monto a abonar.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setMonto  = function ( monto )
	{
		_monto = monto;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link PagoPrestamoSucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		PagoPrestamoSucursal._callback_stack.push( _original_callback  );
		PagoPrestamoSucursal._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		PagoPrestamoSucursal.getByPK(  this.getIdPago() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PagoPrestamoSucursal} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param PagoPrestamoSucursal [$pago_prestamo_sucursal] El objeto de tipo PagoPrestamoSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from pago_prestamo_sucursal WHERE ("; 
		$val = [];
		if( this.getIdPago() != null){
			$sql += " id_pago = ? AND";
			$val.push( this.getIdPago() );
		}

		if( this.getIdPrestamo() != null){
			$sql += " id_prestamo = ? AND";
			$val.push( this.getIdPrestamo() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getMonto() != null){
			$sql += " monto = ? AND";
			$val.push( this.getMonto() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PagoPrestamoSucursal($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto PagoPrestamoSucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto PagoPrestamoSucursal dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param PagoPrestamoSucursal [$pago_prestamo_sucursal] El objeto de tipo PagoPrestamoSucursal a crear.
	  **/
	var create = function( pago_prestamo_sucursal )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO pago_prestamo_sucursal ( id_pago, id_prestamo, id_usuario, fecha, monto ) VALUES ( ?, ?, ?, ?, ?);";
		$params = [
			pago_prestamo_sucursal.getIdPago(), 
			pago_prestamo_sucursal.getIdPrestamo(), 
			pago_prestamo_sucursal.getIdUsuario(), 
			pago_prestamo_sucursal.getFecha(), 
			pago_prestamo_sucursal.getMonto(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				PagoPrestamoSucursal._callback_stack.pop().call(null, pago_prestamo_sucursal);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param PagoPrestamoSucursal [$pago_prestamo_sucursal] El objeto de tipo PagoPrestamoSucursal a actualizar.
	  **/
	var update = function( $pago_prestamo_sucursal )
	{
		$sql = "UPDATE pago_prestamo_sucursal SET  id_prestamo = ?, id_usuario = ?, fecha = ?, monto = ? WHERE  id_pago = ?;";
		$params = [ 
			$pago_prestamo_sucursal.getIdPrestamo(), 
			$pago_prestamo_sucursal.getIdUsuario(), 
			$pago_prestamo_sucursal.getFecha(), 
			$pago_prestamo_sucursal.getMonto(), 
			$pago_prestamo_sucursal.getIdPago(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				PagoPrestamoSucursal._callback_stack.pop().call(null, $pago_prestamo_sucursal);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto PagoPrestamoSucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( PagoPrestamoSucursal.getByPK(this.getIdPago()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM pago_prestamo_sucursal WHERE  id_pago = ?;";
		$params = [ this.getIdPago() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PagoPrestamoSucursal} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link PagoPrestamoSucursal}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param PagoPrestamoSucursal [$pago_prestamo_sucursal] El objeto de tipo PagoPrestamoSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $pago_prestamo_sucursal , $orderBy , $orden )
	{
		$sql = "SELECT * from pago_prestamo_sucursal WHERE ("; 
		$val = [];
		if( (($a = this.getIdPago()) != null) & ( ($b = $pago_prestamo_sucursal.getIdPago()) != null) ){
				$sql += " id_pago >= ? AND id_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdPrestamo()) != null) & ( ($b = $pago_prestamo_sucursal.getIdPrestamo()) != null) ){
				$sql += " id_prestamo >= ? AND id_prestamo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_prestamo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $pago_prestamo_sucursal.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $pago_prestamo_sucursal.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMonto()) != null) & ( ($b = $pago_prestamo_sucursal.getMonto()) != null) ){
				$sql += " monto >= ? AND monto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " monto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PagoPrestamoSucursal($foo));
		//}
		return $sql;
	};


}
	PagoPrestamoSucursal._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link PagoPrestamoSucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	PagoPrestamoSucursal.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from pago_prestamo_sucursal";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new PagoPrestamoSucursal( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link PagoPrestamoSucursal} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link PagoPrestamoSucursal} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link PagoPrestamoSucursal Un objeto del tipo {@link PagoPrestamoSucursal}. NULL si no hay tal registro.
	  **/
	PagoPrestamoSucursal.getByPK = function(  $id_pago, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM pago_prestamo_sucursal WHERE (id_pago = ? ) LIMIT 1;";
		db.query($sql, [ $id_pago] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new PagoPrestamoSucursal(results.rows.item(0)); 
				PagoPrestamoSucursal._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : PagoPrestamoSucursal._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table pos_config.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var PosConfig = function ( config )
{
 /**
	* opcion
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var varchar(30)
	*/
	var _opcion = config === undefined ? null : config.opcion || null,

 /**
	* value
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(2048)
	*/
	_value = config === undefined ? null : config.value || null;

	/**
	  * getOpcion
	  * 
	  * Get the <i>opcion</i> property for this object. Donde <i>opcion</i> es  [Campo no documentado]
	  * @return varchar(30)
	  */
	this.getOpcion = function ()
	{
		return _opcion;
	};

	/**
	  * setOpcion( $opcion )
	  * 
	  * Set the <i>opcion</i> property for this object. Donde <i>opcion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>opcion</i> es de tipo <i>varchar(30)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setOpcion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param varchar(30)
	  */
	this.setOpcion  = function ( opcion )
	{
		_opcion = opcion;
	};

	/**
	  * getValue
	  * 
	  * Get the <i>value</i> property for this object. Donde <i>value</i> es  [Campo no documentado]
	  * @return varchar(2048)
	  */
	this.getValue = function ()
	{
		return _value;
	};

	/**
	  * setValue( $value )
	  * 
	  * Set the <i>value</i> property for this object. Donde <i>value</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>value</i> es de tipo <i>varchar(2048)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(2048)
	  */
	this.setValue  = function ( value )
	{
		_value = value;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link PosConfig} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		PosConfig._callback_stack.push( _original_callback  );
		PosConfig._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		PosConfig.getByPK(  this.getOpcion() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PosConfig} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param PosConfig [$pos_config] El objeto de tipo PosConfig
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from pos_config WHERE ("; 
		$val = [];
		if( this.getOpcion() != null){
			$sql += " opcion = ? AND";
			$val.push( this.getOpcion() );
		}

		if( this.getValue() != null){
			$sql += " value = ? AND";
			$val.push( this.getValue() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PosConfig($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto PosConfig suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto PosConfig dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param PosConfig [$pos_config] El objeto de tipo PosConfig a crear.
	  **/
	var create = function( pos_config )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO pos_config ( opcion, value ) VALUES ( ?, ?);";
		$params = [
			pos_config.getOpcion(), 
			pos_config.getValue(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				PosConfig._callback_stack.pop().call(null, pos_config);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param PosConfig [$pos_config] El objeto de tipo PosConfig a actualizar.
	  **/
	var update = function( $pos_config )
	{
		$sql = "UPDATE pos_config SET  value = ? WHERE  opcion = ?;";
		$params = [ 
			$pos_config.getValue(), 
			$pos_config.getOpcion(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				PosConfig._callback_stack.pop().call(null, $pos_config);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto PosConfig suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( PosConfig.getByPK(this.getOpcion()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM pos_config WHERE  opcion = ?;";
		$params = [ this.getOpcion() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PosConfig} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link PosConfig}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param PosConfig [$pos_config] El objeto de tipo PosConfig
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $pos_config , $orderBy , $orden )
	{
		$sql = "SELECT * from pos_config WHERE ("; 
		$val = [];
		if( (($a = this.getOpcion()) != null) & ( ($b = $pos_config.getOpcion()) != null) ){
				$sql += " opcion >= ? AND opcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " opcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getValue()) != null) & ( ($b = $pos_config.getValue()) != null) ){
				$sql += " value >= ? AND value <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " value = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PosConfig($foo));
		//}
		return $sql;
	};


}
	PosConfig._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link PosConfig}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	PosConfig.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from pos_config";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new PosConfig( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link PosConfig} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link PosConfig} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link PosConfig Un objeto del tipo {@link PosConfig}. NULL si no hay tal registro.
	  **/
	PosConfig.getByPK = function(  $opcion, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM pos_config WHERE (opcion = ? ) LIMIT 1;";
		db.query($sql, [ $opcion] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new PosConfig(results.rows.item(0)); 
				PosConfig._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : PosConfig._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table prestamo_sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var PrestamoSucursal = function ( config )
{
 /**
	* id_prestamo
	* 
	* El identificador de este prestamo
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_prestamo = config === undefined ? null : config.id_prestamo || null,

 /**
	* prestamista
	* 
	* La sucursal que esta prestando
	* @access private
	* @var int(11)
	*/
	_prestamista = config === undefined ? null : config.prestamista || null,

 /**
	* deudor
	* 
	* La sucursal que esta recibiendo
	* @access private
	* @var int(11)
	*/
	_deudor = config === undefined ? null : config.deudor || null,

 /**
	* monto
	* 
	* El monto prestado
	* @access private
	* @var float
	*/
	_monto = config === undefined ? null : config.monto || null,

 /**
	* saldo
	* 
	* El saldo pendiente para liquidar
	* @access private
	* @var float
	*/
	_saldo = config === undefined ? null : config.saldo || null,

 /**
	* liquidado
	* 
	* Bandera para buscar rapidamente los prestamos que aun no estan saldados
	* @access private
	* @var tinyint(1)
	*/
	_liquidado = config === undefined ? null : config.liquidado || null,

 /**
	* concepto
	* 
	* El concepto de este prestamo
	* @access private
	* @var varchar(256)
	*/
	_concepto = config === undefined ? null : config.concepto || null,

 /**
	* fecha
	* 
	* fecha en la que se registro el gasto
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null;

	/**
	  * getIdPrestamo
	  * 
	  * Get the <i>id_prestamo</i> property for this object. Donde <i>id_prestamo</i> es El identificador de este prestamo
	  * @return int(11)
	  */
	this.getIdPrestamo = function ()
	{
		return _id_prestamo;
	};

	/**
	  * setIdPrestamo( $id_prestamo )
	  * 
	  * Set the <i>id_prestamo</i> property for this object. Donde <i>id_prestamo</i> es El identificador de este prestamo.
	  * Una validacion basica se hara aqui para comprobar que <i>id_prestamo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdPrestamo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdPrestamo  = function ( id_prestamo )
	{
		_id_prestamo = id_prestamo;
	};

	/**
	  * getPrestamista
	  * 
	  * Get the <i>prestamista</i> property for this object. Donde <i>prestamista</i> es La sucursal que esta prestando
	  * @return int(11)
	  */
	this.getPrestamista = function ()
	{
		return _prestamista;
	};

	/**
	  * setPrestamista( $prestamista )
	  * 
	  * Set the <i>prestamista</i> property for this object. Donde <i>prestamista</i> es La sucursal que esta prestando.
	  * Una validacion basica se hara aqui para comprobar que <i>prestamista</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setPrestamista  = function ( prestamista )
	{
		_prestamista = prestamista;
	};

	/**
	  * getDeudor
	  * 
	  * Get the <i>deudor</i> property for this object. Donde <i>deudor</i> es La sucursal que esta recibiendo
	  * @return int(11)
	  */
	this.getDeudor = function ()
	{
		return _deudor;
	};

	/**
	  * setDeudor( $deudor )
	  * 
	  * Set the <i>deudor</i> property for this object. Donde <i>deudor</i> es La sucursal que esta recibiendo.
	  * Una validacion basica se hara aqui para comprobar que <i>deudor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setDeudor  = function ( deudor )
	{
		_deudor = deudor;
	};

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es El monto prestado
	  * @return float
	  */
	this.getMonto = function ()
	{
		return _monto;
	};

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es El monto prestado.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setMonto  = function ( monto )
	{
		_monto = monto;
	};

	/**
	  * getSaldo
	  * 
	  * Get the <i>saldo</i> property for this object. Donde <i>saldo</i> es El saldo pendiente para liquidar
	  * @return float
	  */
	this.getSaldo = function ()
	{
		return _saldo;
	};

	/**
	  * setSaldo( $saldo )
	  * 
	  * Set the <i>saldo</i> property for this object. Donde <i>saldo</i> es El saldo pendiente para liquidar.
	  * Una validacion basica se hara aqui para comprobar que <i>saldo</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setSaldo  = function ( saldo )
	{
		_saldo = saldo;
	};

	/**
	  * getLiquidado
	  * 
	  * Get the <i>liquidado</i> property for this object. Donde <i>liquidado</i> es Bandera para buscar rapidamente los prestamos que aun no estan saldados
	  * @return tinyint(1)
	  */
	this.getLiquidado = function ()
	{
		return _liquidado;
	};

	/**
	  * setLiquidado( $liquidado )
	  * 
	  * Set the <i>liquidado</i> property for this object. Donde <i>liquidado</i> es Bandera para buscar rapidamente los prestamos que aun no estan saldados.
	  * Una validacion basica se hara aqui para comprobar que <i>liquidado</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setLiquidado  = function ( liquidado )
	{
		_liquidado = liquidado;
	};

	/**
	  * getConcepto
	  * 
	  * Get the <i>concepto</i> property for this object. Donde <i>concepto</i> es El concepto de este prestamo
	  * @return varchar(256)
	  */
	this.getConcepto = function ()
	{
		return _concepto;
	};

	/**
	  * setConcepto( $concepto )
	  * 
	  * Set the <i>concepto</i> property for this object. Donde <i>concepto</i> es El concepto de este prestamo.
	  * Una validacion basica se hara aqui para comprobar que <i>concepto</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	this.setConcepto  = function ( concepto )
	{
		_concepto = concepto;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha en la que se registro el gasto
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha en la que se registro el gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link PrestamoSucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		PrestamoSucursal._callback_stack.push( _original_callback  );
		PrestamoSucursal._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		PrestamoSucursal.getByPK(  this.getIdPrestamo() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PrestamoSucursal} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param PrestamoSucursal [$prestamo_sucursal] El objeto de tipo PrestamoSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from prestamo_sucursal WHERE ("; 
		$val = [];
		if( this.getIdPrestamo() != null){
			$sql += " id_prestamo = ? AND";
			$val.push( this.getIdPrestamo() );
		}

		if( this.getPrestamista() != null){
			$sql += " prestamista = ? AND";
			$val.push( this.getPrestamista() );
		}

		if( this.getDeudor() != null){
			$sql += " deudor = ? AND";
			$val.push( this.getDeudor() );
		}

		if( this.getMonto() != null){
			$sql += " monto = ? AND";
			$val.push( this.getMonto() );
		}

		if( this.getSaldo() != null){
			$sql += " saldo = ? AND";
			$val.push( this.getSaldo() );
		}

		if( this.getLiquidado() != null){
			$sql += " liquidado = ? AND";
			$val.push( this.getLiquidado() );
		}

		if( this.getConcepto() != null){
			$sql += " concepto = ? AND";
			$val.push( this.getConcepto() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PrestamoSucursal($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto PrestamoSucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto PrestamoSucursal dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param PrestamoSucursal [$prestamo_sucursal] El objeto de tipo PrestamoSucursal a crear.
	  **/
	var create = function( prestamo_sucursal )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO prestamo_sucursal ( id_prestamo, prestamista, deudor, monto, saldo, liquidado, concepto, fecha ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			prestamo_sucursal.getIdPrestamo(), 
			prestamo_sucursal.getPrestamista(), 
			prestamo_sucursal.getDeudor(), 
			prestamo_sucursal.getMonto(), 
			prestamo_sucursal.getSaldo(), 
			prestamo_sucursal.getLiquidado(), 
			prestamo_sucursal.getConcepto(), 
			prestamo_sucursal.getFecha(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				PrestamoSucursal._callback_stack.pop().call(null, prestamo_sucursal);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param PrestamoSucursal [$prestamo_sucursal] El objeto de tipo PrestamoSucursal a actualizar.
	  **/
	var update = function( $prestamo_sucursal )
	{
		$sql = "UPDATE prestamo_sucursal SET  prestamista = ?, deudor = ?, monto = ?, saldo = ?, liquidado = ?, concepto = ?, fecha = ? WHERE  id_prestamo = ?;";
		$params = [ 
			$prestamo_sucursal.getPrestamista(), 
			$prestamo_sucursal.getDeudor(), 
			$prestamo_sucursal.getMonto(), 
			$prestamo_sucursal.getSaldo(), 
			$prestamo_sucursal.getLiquidado(), 
			$prestamo_sucursal.getConcepto(), 
			$prestamo_sucursal.getFecha(), 
			$prestamo_sucursal.getIdPrestamo(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				PrestamoSucursal._callback_stack.pop().call(null, $prestamo_sucursal);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto PrestamoSucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( PrestamoSucursal.getByPK(this.getIdPrestamo()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM prestamo_sucursal WHERE  id_prestamo = ?;";
		$params = [ this.getIdPrestamo() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PrestamoSucursal} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link PrestamoSucursal}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param PrestamoSucursal [$prestamo_sucursal] El objeto de tipo PrestamoSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $prestamo_sucursal , $orderBy , $orden )
	{
		$sql = "SELECT * from prestamo_sucursal WHERE ("; 
		$val = [];
		if( (($a = this.getIdPrestamo()) != null) & ( ($b = $prestamo_sucursal.getIdPrestamo()) != null) ){
				$sql += " id_prestamo >= ? AND id_prestamo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_prestamo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrestamista()) != null) & ( ($b = $prestamo_sucursal.getPrestamista()) != null) ){
				$sql += " prestamista >= ? AND prestamista <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " prestamista = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDeudor()) != null) & ( ($b = $prestamo_sucursal.getDeudor()) != null) ){
				$sql += " deudor >= ? AND deudor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " deudor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMonto()) != null) & ( ($b = $prestamo_sucursal.getMonto()) != null) ){
				$sql += " monto >= ? AND monto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " monto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSaldo()) != null) & ( ($b = $prestamo_sucursal.getSaldo()) != null) ){
				$sql += " saldo >= ? AND saldo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " saldo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLiquidado()) != null) & ( ($b = $prestamo_sucursal.getLiquidado()) != null) ){
				$sql += " liquidado >= ? AND liquidado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " liquidado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getConcepto()) != null) & ( ($b = $prestamo_sucursal.getConcepto()) != null) ){
				$sql += " concepto >= ? AND concepto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " concepto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $prestamo_sucursal.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PrestamoSucursal($foo));
		//}
		return $sql;
	};


}
	PrestamoSucursal._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link PrestamoSucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	PrestamoSucursal.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from prestamo_sucursal";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new PrestamoSucursal( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link PrestamoSucursal} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link PrestamoSucursal} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link PrestamoSucursal Un objeto del tipo {@link PrestamoSucursal}. NULL si no hay tal registro.
	  **/
	PrestamoSucursal.getByPK = function(  $id_prestamo, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM prestamo_sucursal WHERE (id_prestamo = ? ) LIMIT 1;";
		db.query($sql, [ $id_prestamo] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new PrestamoSucursal(results.rows.item(0)); 
				PrestamoSucursal._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : PrestamoSucursal._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table proveedor.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Proveedor = function ( config )
{
 /**
	* id_proveedor
	* 
	* identificador del proveedor
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_proveedor = config === undefined ? null : config.id_proveedor || null,

 /**
	* rfc
	* 
	* rfc del proveedor
	* @access private
	* @var varchar(20)
	*/
	_rfc = config === undefined ? null : config.rfc || null,

 /**
	* nombre
	* 
	* nombre del proveedor
	* @access private
	* @var varchar(30)
	*/
	_nombre = config === undefined ? null : config.nombre || null,

 /**
	* direccion
	* 
	* direccion del proveedor
	* @access private
	* @var varchar(100)
	*/
	_direccion = config === undefined ? null : config.direccion || null,

 /**
	* telefono
	* 
	* telefono
	* @access private
	* @var varchar(20)
	*/
	_telefono = config === undefined ? null : config.telefono || null,

 /**
	* e_mail
	* 
	* email del provedor
	* @access private
	* @var varchar(60)
	*/
	_e_mail = config === undefined ? null : config.e_mail || null,

 /**
	* activo
	* 
	* Indica si la cuenta esta activada o desactivada
	* @access private
	* @var tinyint(2)
	*/
	_activo = config === undefined ? null : config.activo || null,

 /**
	* tipo_proveedor
	* 
	* si este proveedor surtira al admin, a las sucursales o a ambos
	* @access private
	* @var enum('admin','sucursal','ambos')
	*/
	_tipo_proveedor = config === undefined ? null : config.tipo_proveedor || null;

	/**
	  * getIdProveedor
	  * 
	  * Get the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es identificador del proveedor
	  * @return int(11)
	  */
	this.getIdProveedor = function ()
	{
		return _id_proveedor;
	};

	/**
	  * setIdProveedor( $id_proveedor )
	  * 
	  * Set the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es identificador del proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>id_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProveedor( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdProveedor  = function ( id_proveedor )
	{
		_id_proveedor = id_proveedor;
	};

	/**
	  * getRfc
	  * 
	  * Get the <i>rfc</i> property for this object. Donde <i>rfc</i> es rfc del proveedor
	  * @return varchar(20)
	  */
	this.getRfc = function ()
	{
		return _rfc;
	};

	/**
	  * setRfc( $rfc )
	  * 
	  * Set the <i>rfc</i> property for this object. Donde <i>rfc</i> es rfc del proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>rfc</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setRfc  = function ( rfc )
	{
		_rfc = rfc;
	};

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del proveedor
	  * @return varchar(30)
	  */
	this.getNombre = function ()
	{
		return _nombre;
	};

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre</i> es de tipo <i>varchar(30)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(30)
	  */
	this.setNombre  = function ( nombre )
	{
		_nombre = nombre;
	};

	/**
	  * getDireccion
	  * 
	  * Get the <i>direccion</i> property for this object. Donde <i>direccion</i> es direccion del proveedor
	  * @return varchar(100)
	  */
	this.getDireccion = function ()
	{
		return _direccion;
	};

	/**
	  * setDireccion( $direccion )
	  * 
	  * Set the <i>direccion</i> property for this object. Donde <i>direccion</i> es direccion del proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>direccion</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setDireccion  = function ( direccion )
	{
		_direccion = direccion;
	};

	/**
	  * getTelefono
	  * 
	  * Get the <i>telefono</i> property for this object. Donde <i>telefono</i> es telefono
	  * @return varchar(20)
	  */
	this.getTelefono = function ()
	{
		return _telefono;
	};

	/**
	  * setTelefono( $telefono )
	  * 
	  * Set the <i>telefono</i> property for this object. Donde <i>telefono</i> es telefono.
	  * Una validacion basica se hara aqui para comprobar que <i>telefono</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setTelefono  = function ( telefono )
	{
		_telefono = telefono;
	};

	/**
	  * getEMail
	  * 
	  * Get the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es email del provedor
	  * @return varchar(60)
	  */
	this.getEMail = function ()
	{
		return _e_mail;
	};

	/**
	  * setEMail( $e_mail )
	  * 
	  * Set the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es email del provedor.
	  * Una validacion basica se hara aqui para comprobar que <i>e_mail</i> es de tipo <i>varchar(60)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(60)
	  */
	this.setEMail  = function ( e_mail )
	{
		_e_mail = e_mail;
	};

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es Indica si la cuenta esta activada o desactivada
	  * @return tinyint(2)
	  */
	this.getActivo = function ()
	{
		return _activo;
	};

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es Indica si la cuenta esta activada o desactivada.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(2)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(2)
	  */
	this.setActivo  = function ( activo )
	{
		_activo = activo;
	};

	/**
	  * getTipoProveedor
	  * 
	  * Get the <i>tipo_proveedor</i> property for this object. Donde <i>tipo_proveedor</i> es si este proveedor surtira al admin, a las sucursales o a ambos
	  * @return enum('admin','sucursal','ambos')
	  */
	this.getTipoProveedor = function ()
	{
		return _tipo_proveedor;
	};

	/**
	  * setTipoProveedor( $tipo_proveedor )
	  * 
	  * Set the <i>tipo_proveedor</i> property for this object. Donde <i>tipo_proveedor</i> es si este proveedor surtira al admin, a las sucursales o a ambos.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_proveedor</i> es de tipo <i>enum('admin','sucursal','ambos')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('admin','sucursal','ambos')
	  */
	this.setTipoProveedor  = function ( tipo_proveedor )
	{
		_tipo_proveedor = tipo_proveedor;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Proveedor} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Proveedor._callback_stack.push( _original_callback  );
		Proveedor._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Proveedor.getByPK(  this.getIdProveedor() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Proveedor} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from proveedor WHERE ("; 
		$val = [];
		if( this.getIdProveedor() != null){
			$sql += " id_proveedor = ? AND";
			$val.push( this.getIdProveedor() );
		}

		if( this.getRfc() != null){
			$sql += " rfc = ? AND";
			$val.push( this.getRfc() );
		}

		if( this.getNombre() != null){
			$sql += " nombre = ? AND";
			$val.push( this.getNombre() );
		}

		if( this.getDireccion() != null){
			$sql += " direccion = ? AND";
			$val.push( this.getDireccion() );
		}

		if( this.getTelefono() != null){
			$sql += " telefono = ? AND";
			$val.push( this.getTelefono() );
		}

		if( this.getEMail() != null){
			$sql += " e_mail = ? AND";
			$val.push( this.getEMail() );
		}

		if( this.getActivo() != null){
			$sql += " activo = ? AND";
			$val.push( this.getActivo() );
		}

		if( this.getTipoProveedor() != null){
			$sql += " tipo_proveedor = ? AND";
			$val.push( this.getTipoProveedor() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Proveedor($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Proveedor suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Proveedor dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor a crear.
	  **/
	var create = function( proveedor )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO proveedor ( id_proveedor, rfc, nombre, direccion, telefono, e_mail, activo, tipo_proveedor ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			proveedor.getIdProveedor(), 
			proveedor.getRfc(), 
			proveedor.getNombre(), 
			proveedor.getDireccion(), 
			proveedor.getTelefono(), 
			proveedor.getEMail(), 
			proveedor.getActivo(), 
			proveedor.getTipoProveedor(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Proveedor._callback_stack.pop().call(null, proveedor);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor a actualizar.
	  **/
	var update = function( $proveedor )
	{
		$sql = "UPDATE proveedor SET  rfc = ?, nombre = ?, direccion = ?, telefono = ?, e_mail = ?, activo = ?, tipo_proveedor = ? WHERE  id_proveedor = ?;";
		$params = [ 
			$proveedor.getRfc(), 
			$proveedor.getNombre(), 
			$proveedor.getDireccion(), 
			$proveedor.getTelefono(), 
			$proveedor.getEMail(), 
			$proveedor.getActivo(), 
			$proveedor.getTipoProveedor(), 
			$proveedor.getIdProveedor(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Proveedor._callback_stack.pop().call(null, $proveedor);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Proveedor suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Proveedor.getByPK(this.getIdProveedor()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM proveedor WHERE  id_proveedor = ?;";
		$params = [ this.getIdProveedor() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Proveedor} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Proveedor}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $proveedor , $orderBy , $orden )
	{
		$sql = "SELECT * from proveedor WHERE ("; 
		$val = [];
		if( (($a = this.getIdProveedor()) != null) & ( ($b = $proveedor.getIdProveedor()) != null) ){
				$sql += " id_proveedor >= ? AND id_proveedor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_proveedor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getRfc()) != null) & ( ($b = $proveedor.getRfc()) != null) ){
				$sql += " rfc >= ? AND rfc <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " rfc = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNombre()) != null) & ( ($b = $proveedor.getNombre()) != null) ){
				$sql += " nombre >= ? AND nombre <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " nombre = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDireccion()) != null) & ( ($b = $proveedor.getDireccion()) != null) ){
				$sql += " direccion >= ? AND direccion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " direccion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTelefono()) != null) & ( ($b = $proveedor.getTelefono()) != null) ){
				$sql += " telefono >= ? AND telefono <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " telefono = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getEMail()) != null) & ( ($b = $proveedor.getEMail()) != null) ){
				$sql += " e_mail >= ? AND e_mail <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " e_mail = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getActivo()) != null) & ( ($b = $proveedor.getActivo()) != null) ){
				$sql += " activo >= ? AND activo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " activo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoProveedor()) != null) & ( ($b = $proveedor.getTipoProveedor()) != null) ){
				$sql += " tipo_proveedor >= ? AND tipo_proveedor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_proveedor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Proveedor($foo));
		//}
		return $sql;
	};


}
	Proveedor._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Proveedor}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Proveedor.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from proveedor";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Proveedor( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Proveedor} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Proveedor} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Proveedor Un objeto del tipo {@link Proveedor}. NULL si no hay tal registro.
	  **/
	Proveedor.getByPK = function(  $id_proveedor, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM proveedor WHERE (id_proveedor = ? ) LIMIT 1;";
		db.query($sql, [ $id_proveedor] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Proveedor(results.rows.item(0)); 
				Proveedor._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Proveedor._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Sucursal = function ( config )
{
 /**
	* id_sucursal
	* 
	* Identificador de cada sucursal
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* gerente
	* 
	* Gerente de esta sucursal
	* @access private
	* @var int(11)
	*/
	_gerente = config === undefined ? null : config.gerente || null,

 /**
	* descripcion
	* 
	* nombre o descripcion de sucursal
	* @access private
	* @var varchar(100)
	*/
	_descripcion = config === undefined ? null : config.descripcion || null,

 /**
	* razon_social
	* 
	* razon social de la sucursal
	* @access private
	* @var varchar(100)
	*/
	_razon_social = config === undefined ? null : config.razon_social || null,

 /**
	* rfc
	* 
	* El RFC de la sucursal
	* @access private
	* @var varchar(20)
	*/
	_rfc = config === undefined ? null : config.rfc || null,

 /**
	* calle
	* 
	* calle del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_calle = config === undefined ? null : config.calle || null,

 /**
	* numero_exterior
	* 
	* nuemro exterior del domicilio fiscal
	* @access private
	* @var varchar(10)
	*/
	_numero_exterior = config === undefined ? null : config.numero_exterior || null,

 /**
	* numero_interior
	* 
	* numero interior del domicilio fiscal
	* @access private
	* @var varchar(10)
	*/
	_numero_interior = config === undefined ? null : config.numero_interior || null,

 /**
	* colonia
	* 
	* colonia del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_colonia = config === undefined ? null : config.colonia || null,

 /**
	* localidad
	* 
	* localidad del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_localidad = config === undefined ? null : config.localidad || null,

 /**
	* referencia
	* 
	* referencia del domicilio fiscal
	* @access private
	* @var varchar(200)
	*/
	_referencia = config === undefined ? null : config.referencia || null,

 /**
	* municipio
	* 
	* municipio del domicilio fiscal
	* @access private
	* @var varchar(100)
	*/
	_municipio = config === undefined ? null : config.municipio || null,

 /**
	* estado
	* 
	* estado del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_estado = config === undefined ? null : config.estado || null,

 /**
	* pais
	* 
	* pais del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_pais = config === undefined ? null : config.pais || null,

 /**
	* codigo_postal
	* 
	* codigo postal del domicilio fiscal
	* @access private
	* @var varchar(15)
	*/
	_codigo_postal = config === undefined ? null : config.codigo_postal || null,

 /**
	* telefono
	* 
	* El telefono de la sucursal
	* @access private
	* @var varchar(20)
	*/
	_telefono = config === undefined ? null : config.telefono || null,

 /**
	* token
	* 
	* Token de seguridad para esta sucursal
	* @access private
	* @var varchar(512)
	*/
	_token = config === undefined ? null : config.token || null,

 /**
	* letras_factura
	* 
	*  [Campo no documentado]
	* @access private
	* @var char(1)
	*/
	_letras_factura = config === undefined ? null : config.letras_factura || null,

 /**
	* activo
	* 
	*  [Campo no documentado]
	* @access private
	* @var tinyint(1)
	*/
	_activo = config === undefined ? null : config.activo || null,

 /**
	* fecha_apertura
	* 
	* Fecha de apertura de esta sucursal
	* @access private
	* @var timestamp
	*/
	_fecha_apertura = config === undefined ? null : config.fecha_apertura || null,

 /**
	* saldo_a_favor
	* 
	* es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras
	* @access private
	* @var float
	*/
	_saldo_a_favor = config === undefined ? null : config.saldo_a_favor || null;

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de cada sucursal
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de cada sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdSucursal( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getGerente
	  * 
	  * Get the <i>gerente</i> property for this object. Donde <i>gerente</i> es Gerente de esta sucursal
	  * @return int(11)
	  */
	this.getGerente = function ()
	{
		return _gerente;
	};

	/**
	  * setGerente( $gerente )
	  * 
	  * Set the <i>gerente</i> property for this object. Donde <i>gerente</i> es Gerente de esta sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>gerente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setGerente  = function ( gerente )
	{
		_gerente = gerente;
	};

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es nombre o descripcion de sucursal
	  * @return varchar(100)
	  */
	this.getDescripcion = function ()
	{
		return _descripcion;
	};

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es nombre o descripcion de sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setDescripcion  = function ( descripcion )
	{
		_descripcion = descripcion;
	};

	/**
	  * getRazonSocial
	  * 
	  * Get the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social de la sucursal
	  * @return varchar(100)
	  */
	this.getRazonSocial = function ()
	{
		return _razon_social;
	};

	/**
	  * setRazonSocial( $razon_social )
	  * 
	  * Set the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>razon_social</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setRazonSocial  = function ( razon_social )
	{
		_razon_social = razon_social;
	};

	/**
	  * getRfc
	  * 
	  * Get the <i>rfc</i> property for this object. Donde <i>rfc</i> es El RFC de la sucursal
	  * @return varchar(20)
	  */
	this.getRfc = function ()
	{
		return _rfc;
	};

	/**
	  * setRfc( $rfc )
	  * 
	  * Set the <i>rfc</i> property for this object. Donde <i>rfc</i> es El RFC de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>rfc</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setRfc  = function ( rfc )
	{
		_rfc = rfc;
	};

	/**
	  * getCalle
	  * 
	  * Get the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getCalle = function ()
	{
		return _calle;
	};

	/**
	  * setCalle( $calle )
	  * 
	  * Set the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>calle</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setCalle  = function ( calle )
	{
		_calle = calle;
	};

	/**
	  * getNumeroExterior
	  * 
	  * Get the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es nuemro exterior del domicilio fiscal
	  * @return varchar(10)
	  */
	this.getNumeroExterior = function ()
	{
		return _numero_exterior;
	};

	/**
	  * setNumeroExterior( $numero_exterior )
	  * 
	  * Set the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es nuemro exterior del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_exterior</i> es de tipo <i>varchar(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(10)
	  */
	this.setNumeroExterior  = function ( numero_exterior )
	{
		_numero_exterior = numero_exterior;
	};

	/**
	  * getNumeroInterior
	  * 
	  * Get the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal
	  * @return varchar(10)
	  */
	this.getNumeroInterior = function ()
	{
		return _numero_interior;
	};

	/**
	  * setNumeroInterior( $numero_interior )
	  * 
	  * Set the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_interior</i> es de tipo <i>varchar(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(10)
	  */
	this.setNumeroInterior  = function ( numero_interior )
	{
		_numero_interior = numero_interior;
	};

	/**
	  * getColonia
	  * 
	  * Get the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getColonia = function ()
	{
		return _colonia;
	};

	/**
	  * setColonia( $colonia )
	  * 
	  * Set the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>colonia</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setColonia  = function ( colonia )
	{
		_colonia = colonia;
	};

	/**
	  * getLocalidad
	  * 
	  * Get the <i>localidad</i> property for this object. Donde <i>localidad</i> es localidad del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getLocalidad = function ()
	{
		return _localidad;
	};

	/**
	  * setLocalidad( $localidad )
	  * 
	  * Set the <i>localidad</i> property for this object. Donde <i>localidad</i> es localidad del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>localidad</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setLocalidad  = function ( localidad )
	{
		_localidad = localidad;
	};

	/**
	  * getReferencia
	  * 
	  * Get the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal
	  * @return varchar(200)
	  */
	this.getReferencia = function ()
	{
		return _referencia;
	};

	/**
	  * setReferencia( $referencia )
	  * 
	  * Set the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>referencia</i> es de tipo <i>varchar(200)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(200)
	  */
	this.setReferencia  = function ( referencia )
	{
		_referencia = referencia;
	};

	/**
	  * getMunicipio
	  * 
	  * Get the <i>municipio</i> property for this object. Donde <i>municipio</i> es municipio del domicilio fiscal
	  * @return varchar(100)
	  */
	this.getMunicipio = function ()
	{
		return _municipio;
	};

	/**
	  * setMunicipio( $municipio )
	  * 
	  * Set the <i>municipio</i> property for this object. Donde <i>municipio</i> es municipio del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>municipio</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setMunicipio  = function ( municipio )
	{
		_municipio = municipio;
	};

	/**
	  * getEstado
	  * 
	  * Get the <i>estado</i> property for this object. Donde <i>estado</i> es estado del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getEstado = function ()
	{
		return _estado;
	};

	/**
	  * setEstado( $estado )
	  * 
	  * Set the <i>estado</i> property for this object. Donde <i>estado</i> es estado del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>estado</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setEstado  = function ( estado )
	{
		_estado = estado;
	};

	/**
	  * getPais
	  * 
	  * Get the <i>pais</i> property for this object. Donde <i>pais</i> es pais del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getPais = function ()
	{
		return _pais;
	};

	/**
	  * setPais( $pais )
	  * 
	  * Set the <i>pais</i> property for this object. Donde <i>pais</i> es pais del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>pais</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setPais  = function ( pais )
	{
		_pais = pais;
	};

	/**
	  * getCodigoPostal
	  * 
	  * Get the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es codigo postal del domicilio fiscal
	  * @return varchar(15)
	  */
	this.getCodigoPostal = function ()
	{
		return _codigo_postal;
	};

	/**
	  * setCodigoPostal( $codigo_postal )
	  * 
	  * Set the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es codigo postal del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>codigo_postal</i> es de tipo <i>varchar(15)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(15)
	  */
	this.setCodigoPostal  = function ( codigo_postal )
	{
		_codigo_postal = codigo_postal;
	};

	/**
	  * getTelefono
	  * 
	  * Get the <i>telefono</i> property for this object. Donde <i>telefono</i> es El telefono de la sucursal
	  * @return varchar(20)
	  */
	this.getTelefono = function ()
	{
		return _telefono;
	};

	/**
	  * setTelefono( $telefono )
	  * 
	  * Set the <i>telefono</i> property for this object. Donde <i>telefono</i> es El telefono de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>telefono</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setTelefono  = function ( telefono )
	{
		_telefono = telefono;
	};

	/**
	  * getToken
	  * 
	  * Get the <i>token</i> property for this object. Donde <i>token</i> es Token de seguridad para esta sucursal
	  * @return varchar(512)
	  */
	this.getToken = function ()
	{
		return _token;
	};

	/**
	  * setToken( $token )
	  * 
	  * Set the <i>token</i> property for this object. Donde <i>token</i> es Token de seguridad para esta sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>token</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	this.setToken  = function ( token )
	{
		_token = token;
	};

	/**
	  * getLetrasFactura
	  * 
	  * Get the <i>letras_factura</i> property for this object. Donde <i>letras_factura</i> es  [Campo no documentado]
	  * @return char(1)
	  */
	this.getLetrasFactura = function ()
	{
		return _letras_factura;
	};

	/**
	  * setLetrasFactura( $letras_factura )
	  * 
	  * Set the <i>letras_factura</i> property for this object. Donde <i>letras_factura</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>letras_factura</i> es de tipo <i>char(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param char(1)
	  */
	this.setLetrasFactura  = function ( letras_factura )
	{
		_letras_factura = letras_factura;
	};

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es  [Campo no documentado]
	  * @return tinyint(1)
	  */
	this.getActivo = function ()
	{
		return _activo;
	};

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setActivo  = function ( activo )
	{
		_activo = activo;
	};

	/**
	  * getFechaApertura
	  * 
	  * Get the <i>fecha_apertura</i> property for this object. Donde <i>fecha_apertura</i> es Fecha de apertura de esta sucursal
	  * @return timestamp
	  */
	this.getFechaApertura = function ()
	{
		return _fecha_apertura;
	};

	/**
	  * setFechaApertura( $fecha_apertura )
	  * 
	  * Set the <i>fecha_apertura</i> property for this object. Donde <i>fecha_apertura</i> es Fecha de apertura de esta sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_apertura</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaApertura  = function ( fecha_apertura )
	{
		_fecha_apertura = fecha_apertura;
	};

	/**
	  * getSaldoAFavor
	  * 
	  * Get the <i>saldo_a_favor</i> property for this object. Donde <i>saldo_a_favor</i> es es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras
	  * @return float
	  */
	this.getSaldoAFavor = function ()
	{
		return _saldo_a_favor;
	};

	/**
	  * setSaldoAFavor( $saldo_a_favor )
	  * 
	  * Set the <i>saldo_a_favor</i> property for this object. Donde <i>saldo_a_favor</i> es es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras.
	  * Una validacion basica se hara aqui para comprobar que <i>saldo_a_favor</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setSaldoAFavor  = function ( saldo_a_favor )
	{
		_saldo_a_favor = saldo_a_favor;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Sucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Sucursal._callback_stack.push( _original_callback  );
		Sucursal._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Sucursal.getByPK(  this.getIdSucursal() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Sucursal} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from sucursal WHERE ("; 
		$val = [];
		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getGerente() != null){
			$sql += " gerente = ? AND";
			$val.push( this.getGerente() );
		}

		if( this.getDescripcion() != null){
			$sql += " descripcion = ? AND";
			$val.push( this.getDescripcion() );
		}

		if( this.getRazonSocial() != null){
			$sql += " razon_social = ? AND";
			$val.push( this.getRazonSocial() );
		}

		if( this.getRfc() != null){
			$sql += " rfc = ? AND";
			$val.push( this.getRfc() );
		}

		if( this.getCalle() != null){
			$sql += " calle = ? AND";
			$val.push( this.getCalle() );
		}

		if( this.getNumeroExterior() != null){
			$sql += " numero_exterior = ? AND";
			$val.push( this.getNumeroExterior() );
		}

		if( this.getNumeroInterior() != null){
			$sql += " numero_interior = ? AND";
			$val.push( this.getNumeroInterior() );
		}

		if( this.getColonia() != null){
			$sql += " colonia = ? AND";
			$val.push( this.getColonia() );
		}

		if( this.getLocalidad() != null){
			$sql += " localidad = ? AND";
			$val.push( this.getLocalidad() );
		}

		if( this.getReferencia() != null){
			$sql += " referencia = ? AND";
			$val.push( this.getReferencia() );
		}

		if( this.getMunicipio() != null){
			$sql += " municipio = ? AND";
			$val.push( this.getMunicipio() );
		}

		if( this.getEstado() != null){
			$sql += " estado = ? AND";
			$val.push( this.getEstado() );
		}

		if( this.getPais() != null){
			$sql += " pais = ? AND";
			$val.push( this.getPais() );
		}

		if( this.getCodigoPostal() != null){
			$sql += " codigo_postal = ? AND";
			$val.push( this.getCodigoPostal() );
		}

		if( this.getTelefono() != null){
			$sql += " telefono = ? AND";
			$val.push( this.getTelefono() );
		}

		if( this.getToken() != null){
			$sql += " token = ? AND";
			$val.push( this.getToken() );
		}

		if( this.getLetrasFactura() != null){
			$sql += " letras_factura = ? AND";
			$val.push( this.getLetrasFactura() );
		}

		if( this.getActivo() != null){
			$sql += " activo = ? AND";
			$val.push( this.getActivo() );
		}

		if( this.getFechaApertura() != null){
			$sql += " fecha_apertura = ? AND";
			$val.push( this.getFechaApertura() );
		}

		if( this.getSaldoAFavor() != null){
			$sql += " saldo_a_favor = ? AND";
			$val.push( this.getSaldoAFavor() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Sucursal($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Sucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Sucursal dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal a crear.
	  **/
	var create = function( sucursal )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO sucursal ( id_sucursal, gerente, descripcion, razon_social, rfc, calle, numero_exterior, numero_interior, colonia, localidad, referencia, municipio, estado, pais, codigo_postal, telefono, token, letras_factura, activo, fecha_apertura, saldo_a_favor ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			sucursal.getIdSucursal(), 
			sucursal.getGerente(), 
			sucursal.getDescripcion(), 
			sucursal.getRazonSocial(), 
			sucursal.getRfc(), 
			sucursal.getCalle(), 
			sucursal.getNumeroExterior(), 
			sucursal.getNumeroInterior(), 
			sucursal.getColonia(), 
			sucursal.getLocalidad(), 
			sucursal.getReferencia(), 
			sucursal.getMunicipio(), 
			sucursal.getEstado(), 
			sucursal.getPais(), 
			sucursal.getCodigoPostal(), 
			sucursal.getTelefono(), 
			sucursal.getToken(), 
			sucursal.getLetrasFactura(), 
			sucursal.getActivo(), 
			sucursal.getFechaApertura(), 
			sucursal.getSaldoAFavor(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Sucursal._callback_stack.pop().call(null, sucursal);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal a actualizar.
	  **/
	var update = function( $sucursal )
	{
		$sql = "UPDATE sucursal SET  gerente = ?, descripcion = ?, razon_social = ?, rfc = ?, calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?, localidad = ?, referencia = ?, municipio = ?, estado = ?, pais = ?, codigo_postal = ?, telefono = ?, token = ?, letras_factura = ?, activo = ?, fecha_apertura = ?, saldo_a_favor = ? WHERE  id_sucursal = ?;";
		$params = [ 
			$sucursal.getGerente(), 
			$sucursal.getDescripcion(), 
			$sucursal.getRazonSocial(), 
			$sucursal.getRfc(), 
			$sucursal.getCalle(), 
			$sucursal.getNumeroExterior(), 
			$sucursal.getNumeroInterior(), 
			$sucursal.getColonia(), 
			$sucursal.getLocalidad(), 
			$sucursal.getReferencia(), 
			$sucursal.getMunicipio(), 
			$sucursal.getEstado(), 
			$sucursal.getPais(), 
			$sucursal.getCodigoPostal(), 
			$sucursal.getTelefono(), 
			$sucursal.getToken(), 
			$sucursal.getLetrasFactura(), 
			$sucursal.getActivo(), 
			$sucursal.getFechaApertura(), 
			$sucursal.getSaldoAFavor(), 
			$sucursal.getIdSucursal(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Sucursal._callback_stack.pop().call(null, $sucursal);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Sucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Sucursal.getByPK(this.getIdSucursal()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM sucursal WHERE  id_sucursal = ?;";
		$params = [ this.getIdSucursal() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Sucursal} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Sucursal}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $sucursal , $orderBy , $orden )
	{
		$sql = "SELECT * from sucursal WHERE ("; 
		$val = [];
		if( (($a = this.getIdSucursal()) != null) & ( ($b = $sucursal.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getGerente()) != null) & ( ($b = $sucursal.getGerente()) != null) ){
				$sql += " gerente >= ? AND gerente <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " gerente = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcion()) != null) & ( ($b = $sucursal.getDescripcion()) != null) ){
				$sql += " descripcion >= ? AND descripcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getRazonSocial()) != null) & ( ($b = $sucursal.getRazonSocial()) != null) ){
				$sql += " razon_social >= ? AND razon_social <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " razon_social = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getRfc()) != null) & ( ($b = $sucursal.getRfc()) != null) ){
				$sql += " rfc >= ? AND rfc <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " rfc = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCalle()) != null) & ( ($b = $sucursal.getCalle()) != null) ){
				$sql += " calle >= ? AND calle <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " calle = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroExterior()) != null) & ( ($b = $sucursal.getNumeroExterior()) != null) ){
				$sql += " numero_exterior >= ? AND numero_exterior <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_exterior = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroInterior()) != null) & ( ($b = $sucursal.getNumeroInterior()) != null) ){
				$sql += " numero_interior >= ? AND numero_interior <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_interior = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getColonia()) != null) & ( ($b = $sucursal.getColonia()) != null) ){
				$sql += " colonia >= ? AND colonia <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " colonia = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLocalidad()) != null) & ( ($b = $sucursal.getLocalidad()) != null) ){
				$sql += " localidad >= ? AND localidad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " localidad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getReferencia()) != null) & ( ($b = $sucursal.getReferencia()) != null) ){
				$sql += " referencia >= ? AND referencia <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " referencia = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMunicipio()) != null) & ( ($b = $sucursal.getMunicipio()) != null) ){
				$sql += " municipio >= ? AND municipio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " municipio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getEstado()) != null) & ( ($b = $sucursal.getEstado()) != null) ){
				$sql += " estado >= ? AND estado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " estado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPais()) != null) & ( ($b = $sucursal.getPais()) != null) ){
				$sql += " pais >= ? AND pais <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " pais = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCodigoPostal()) != null) & ( ($b = $sucursal.getCodigoPostal()) != null) ){
				$sql += " codigo_postal >= ? AND codigo_postal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " codigo_postal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTelefono()) != null) & ( ($b = $sucursal.getTelefono()) != null) ){
				$sql += " telefono >= ? AND telefono <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " telefono = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getToken()) != null) & ( ($b = $sucursal.getToken()) != null) ){
				$sql += " token >= ? AND token <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " token = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLetrasFactura()) != null) & ( ($b = $sucursal.getLetrasFactura()) != null) ){
				$sql += " letras_factura >= ? AND letras_factura <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " letras_factura = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getActivo()) != null) & ( ($b = $sucursal.getActivo()) != null) ){
				$sql += " activo >= ? AND activo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " activo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaApertura()) != null) & ( ($b = $sucursal.getFechaApertura()) != null) ){
				$sql += " fecha_apertura >= ? AND fecha_apertura <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_apertura = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSaldoAFavor()) != null) & ( ($b = $sucursal.getSaldoAFavor()) != null) ){
				$sql += " saldo_a_favor >= ? AND saldo_a_favor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " saldo_a_favor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Sucursal($foo));
		//}
		return $sql;
	};


}
	Sucursal._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Sucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Sucursal.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from sucursal";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Sucursal( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Sucursal} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Sucursal} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Sucursal Un objeto del tipo {@link Sucursal}. NULL si no hay tal registro.
	  **/
	Sucursal.getByPK = function(  $id_sucursal, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM sucursal WHERE (id_sucursal = ? ) LIMIT 1;";
		db.query($sql, [ $id_sucursal] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Sucursal(results.rows.item(0)); 
				Sucursal._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Sucursal._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table usuario.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Usuario = function ( config )
{
 /**
	* id_usuario
	* 
	* identificador del usuario
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* RFC
	* 
	* RFC de este usuario
	* @access private
	* @var varchar(40)
	*/
	_RFC = config === undefined ? null : config.RFC || null,

 /**
	* nombre
	* 
	* nombre del empleado
	* @access private
	* @var varchar(100)
	*/
	_nombre = config === undefined ? null : config.nombre || null,

 /**
	* contrasena
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(128)
	*/
	_contrasena = config === undefined ? null : config.contrasena || null,

 /**
	* id_sucursal
	* 
	* Id de la sucursal a que pertenece
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* activo
	* 
	* Guarda el estado de la cuenta del usuario
	* @access private
	* @var tinyint(1)
	*/
	_activo = config === undefined ? null : config.activo || null,

 /**
	* finger_token
	* 
	* Una cadena que sera comparada con el token que mande el scanner de huella digital
	* @access private
	* @var varchar(1024)
	*/
	_finger_token = config === undefined ? null : config.finger_token || null,

 /**
	* salario
	* 
	* Salario actual
	* @access private
	* @var float
	*/
	_salario = config === undefined ? null : config.salario || null,

 /**
	* direccion
	* 
	* Direccion del empleado
	* @access private
	* @var varchar(512)
	*/
	_direccion = config === undefined ? null : config.direccion || null,

 /**
	* telefono
	* 
	* Telefono del empleado
	* @access private
	* @var varchar(16)
	*/
	_telefono = config === undefined ? null : config.telefono || null,

 /**
	* fecha_inicio
	* 
	* Fecha cuando este usuario comenzo a laborar
	* @access private
	* @var timestamp
	*/
	_fecha_inicio = config === undefined ? null : config.fecha_inicio || null;

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es identificador del usuario
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es identificador del usuario.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdUsuario( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getRFC
	  * 
	  * Get the <i>RFC</i> property for this object. Donde <i>RFC</i> es RFC de este usuario
	  * @return varchar(40)
	  */
	this.getRFC = function ()
	{
		return _RFC;
	};

	/**
	  * setRFC( $RFC )
	  * 
	  * Set the <i>RFC</i> property for this object. Donde <i>RFC</i> es RFC de este usuario.
	  * Una validacion basica se hara aqui para comprobar que <i>RFC</i> es de tipo <i>varchar(40)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(40)
	  */
	this.setRFC  = function ( RFC )
	{
		_RFC = RFC;
	};

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del empleado
	  * @return varchar(100)
	  */
	this.getNombre = function ()
	{
		return _nombre;
	};

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del empleado.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setNombre  = function ( nombre )
	{
		_nombre = nombre;
	};

	/**
	  * getContrasena
	  * 
	  * Get the <i>contrasena</i> property for this object. Donde <i>contrasena</i> es  [Campo no documentado]
	  * @return varchar(128)
	  */
	this.getContrasena = function ()
	{
		return _contrasena;
	};

	/**
	  * setContrasena( $contrasena )
	  * 
	  * Set the <i>contrasena</i> property for this object. Donde <i>contrasena</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>contrasena</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	this.setContrasena  = function ( contrasena )
	{
		_contrasena = contrasena;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Id de la sucursal a que pertenece
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Id de la sucursal a que pertenece.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es Guarda el estado de la cuenta del usuario
	  * @return tinyint(1)
	  */
	this.getActivo = function ()
	{
		return _activo;
	};

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es Guarda el estado de la cuenta del usuario.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setActivo  = function ( activo )
	{
		_activo = activo;
	};

	/**
	  * getFingerToken
	  * 
	  * Get the <i>finger_token</i> property for this object. Donde <i>finger_token</i> es Una cadena que sera comparada con el token que mande el scanner de huella digital
	  * @return varchar(1024)
	  */
	this.getFingerToken = function ()
	{
		return _finger_token;
	};

	/**
	  * setFingerToken( $finger_token )
	  * 
	  * Set the <i>finger_token</i> property for this object. Donde <i>finger_token</i> es Una cadena que sera comparada con el token que mande el scanner de huella digital.
	  * Una validacion basica se hara aqui para comprobar que <i>finger_token</i> es de tipo <i>varchar(1024)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(1024)
	  */
	this.setFingerToken  = function ( finger_token )
	{
		_finger_token = finger_token;
	};

	/**
	  * getSalario
	  * 
	  * Get the <i>salario</i> property for this object. Donde <i>salario</i> es Salario actual
	  * @return float
	  */
	this.getSalario = function ()
	{
		return _salario;
	};

	/**
	  * setSalario( $salario )
	  * 
	  * Set the <i>salario</i> property for this object. Donde <i>salario</i> es Salario actual.
	  * Una validacion basica se hara aqui para comprobar que <i>salario</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setSalario  = function ( salario )
	{
		_salario = salario;
	};

	/**
	  * getDireccion
	  * 
	  * Get the <i>direccion</i> property for this object. Donde <i>direccion</i> es Direccion del empleado
	  * @return varchar(512)
	  */
	this.getDireccion = function ()
	{
		return _direccion;
	};

	/**
	  * setDireccion( $direccion )
	  * 
	  * Set the <i>direccion</i> property for this object. Donde <i>direccion</i> es Direccion del empleado.
	  * Una validacion basica se hara aqui para comprobar que <i>direccion</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	this.setDireccion  = function ( direccion )
	{
		_direccion = direccion;
	};

	/**
	  * getTelefono
	  * 
	  * Get the <i>telefono</i> property for this object. Donde <i>telefono</i> es Telefono del empleado
	  * @return varchar(16)
	  */
	this.getTelefono = function ()
	{
		return _telefono;
	};

	/**
	  * setTelefono( $telefono )
	  * 
	  * Set the <i>telefono</i> property for this object. Donde <i>telefono</i> es Telefono del empleado.
	  * Una validacion basica se hara aqui para comprobar que <i>telefono</i> es de tipo <i>varchar(16)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(16)
	  */
	this.setTelefono  = function ( telefono )
	{
		_telefono = telefono;
	};

	/**
	  * getFechaInicio
	  * 
	  * Get the <i>fecha_inicio</i> property for this object. Donde <i>fecha_inicio</i> es Fecha cuando este usuario comenzo a laborar
	  * @return timestamp
	  */
	this.getFechaInicio = function ()
	{
		return _fecha_inicio;
	};

	/**
	  * setFechaInicio( $fecha_inicio )
	  * 
	  * Set the <i>fecha_inicio</i> property for this object. Donde <i>fecha_inicio</i> es Fecha cuando este usuario comenzo a laborar.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_inicio</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaInicio  = function ( fecha_inicio )
	{
		_fecha_inicio = fecha_inicio;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Usuario} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Usuario._callback_stack.push( _original_callback  );
		Usuario._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Usuario.getByPK(  this.getIdUsuario() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Usuario} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Usuario [$usuario] El objeto de tipo Usuario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from usuario WHERE ("; 
		$val = [];
		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getRFC() != null){
			$sql += " RFC = ? AND";
			$val.push( this.getRFC() );
		}

		if( this.getNombre() != null){
			$sql += " nombre = ? AND";
			$val.push( this.getNombre() );
		}

		if( this.getContrasena() != null){
			$sql += " contrasena = ? AND";
			$val.push( this.getContrasena() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getActivo() != null){
			$sql += " activo = ? AND";
			$val.push( this.getActivo() );
		}

		if( this.getFingerToken() != null){
			$sql += " finger_token = ? AND";
			$val.push( this.getFingerToken() );
		}

		if( this.getSalario() != null){
			$sql += " salario = ? AND";
			$val.push( this.getSalario() );
		}

		if( this.getDireccion() != null){
			$sql += " direccion = ? AND";
			$val.push( this.getDireccion() );
		}

		if( this.getTelefono() != null){
			$sql += " telefono = ? AND";
			$val.push( this.getTelefono() );
		}

		if( this.getFechaInicio() != null){
			$sql += " fecha_inicio = ? AND";
			$val.push( this.getFechaInicio() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Usuario($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Usuario suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Usuario dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Usuario [$usuario] El objeto de tipo Usuario a crear.
	  **/
	var create = function( usuario )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO usuario ( id_usuario, RFC, nombre, contrasena, id_sucursal, activo, finger_token, salario, direccion, telefono, fecha_inicio ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			usuario.getIdUsuario(), 
			usuario.getRFC(), 
			usuario.getNombre(), 
			usuario.getContrasena(), 
			usuario.getIdSucursal(), 
			usuario.getActivo(), 
			usuario.getFingerToken(), 
			usuario.getSalario(), 
			usuario.getDireccion(), 
			usuario.getTelefono(), 
			usuario.getFechaInicio(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Usuario._callback_stack.pop().call(null, usuario);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Usuario [$usuario] El objeto de tipo Usuario a actualizar.
	  **/
	var update = function( $usuario )
	{
		$sql = "UPDATE usuario SET  RFC = ?, nombre = ?, contrasena = ?, id_sucursal = ?, activo = ?, finger_token = ?, salario = ?, direccion = ?, telefono = ?, fecha_inicio = ? WHERE  id_usuario = ?;";
		$params = [ 
			$usuario.getRFC(), 
			$usuario.getNombre(), 
			$usuario.getContrasena(), 
			$usuario.getIdSucursal(), 
			$usuario.getActivo(), 
			$usuario.getFingerToken(), 
			$usuario.getSalario(), 
			$usuario.getDireccion(), 
			$usuario.getTelefono(), 
			$usuario.getFechaInicio(), 
			$usuario.getIdUsuario(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Usuario._callback_stack.pop().call(null, $usuario);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Usuario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Usuario.getByPK(this.getIdUsuario()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM usuario WHERE  id_usuario = ?;";
		$params = [ this.getIdUsuario() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Usuario} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Usuario}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Usuario [$usuario] El objeto de tipo Usuario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $usuario , $orderBy , $orden )
	{
		$sql = "SELECT * from usuario WHERE ("; 
		$val = [];
		if( (($a = this.getIdUsuario()) != null) & ( ($b = $usuario.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getRFC()) != null) & ( ($b = $usuario.getRFC()) != null) ){
				$sql += " RFC >= ? AND RFC <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " RFC = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNombre()) != null) & ( ($b = $usuario.getNombre()) != null) ){
				$sql += " nombre >= ? AND nombre <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " nombre = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getContrasena()) != null) & ( ($b = $usuario.getContrasena()) != null) ){
				$sql += " contrasena >= ? AND contrasena <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " contrasena = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $usuario.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getActivo()) != null) & ( ($b = $usuario.getActivo()) != null) ){
				$sql += " activo >= ? AND activo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " activo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFingerToken()) != null) & ( ($b = $usuario.getFingerToken()) != null) ){
				$sql += " finger_token >= ? AND finger_token <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " finger_token = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSalario()) != null) & ( ($b = $usuario.getSalario()) != null) ){
				$sql += " salario >= ? AND salario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " salario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDireccion()) != null) & ( ($b = $usuario.getDireccion()) != null) ){
				$sql += " direccion >= ? AND direccion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " direccion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTelefono()) != null) & ( ($b = $usuario.getTelefono()) != null) ){
				$sql += " telefono >= ? AND telefono <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " telefono = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaInicio()) != null) & ( ($b = $usuario.getFechaInicio()) != null) ){
				$sql += " fecha_inicio >= ? AND fecha_inicio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_inicio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Usuario($foo));
		//}
		return $sql;
	};


}
	Usuario._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Usuario}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Usuario.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from usuario";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Usuario( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Usuario} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Usuario} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Usuario Un objeto del tipo {@link Usuario}. NULL si no hay tal registro.
	  **/
	Usuario.getByPK = function(  $id_usuario, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM usuario WHERE (id_usuario = ? ) LIMIT 1;";
		db.query($sql, [ $id_usuario] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Usuario(results.rows.item(0)); 
				Usuario._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Usuario._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table ventas.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Ventas = function ( config )
{
 /**
	* id_venta
	* 
	* id de venta
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_venta = config === undefined ? null : config.id_venta || null,

 /**
	* id_cliente
	* 
	* cliente al que se le vendio
	* @access private
	* @var int(11)
	*/
	_id_cliente = config === undefined ? null : config.id_cliente || null,

 /**
	* tipo_venta
	* 
	* tipo de venta, contado o credito
	* @access private
	* @var enum('credito','contado')
	*/
	_tipo_venta = config === undefined ? null : config.tipo_venta || null,

 /**
	* tipo_pago
	* 
	* tipo de pago para esta venta en caso de ser a contado
	* @access private
	* @var enum('efectivo','cheque','tarjeta')
	*/
	_tipo_pago = config === undefined ? null : config.tipo_pago || null,

 /**
	* fecha
	* 
	* fecha de venta
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? null : config.fecha || null,

 /**
	* subtotal
	* 
	* subtotal de la venta, puede ser nulo
	* @access private
	* @var float
	*/
	_subtotal = config === undefined ? null : config.subtotal || null,

 /**
	* iva
	* 
	* iva agregado por la venta, depende de cada sucursal
	* @access private
	* @var float
	*/
	_iva = config === undefined ? null : config.iva || null,

 /**
	* descuento
	* 
	* descuento aplicado a esta venta
	* @access private
	* @var float
	*/
	_descuento = config === undefined ? null : config.descuento || null,

 /**
	* total
	* 
	* total de esta venta
	* @access private
	* @var float
	*/
	_total = config === undefined ? null : config.total || null,

 /**
	* id_sucursal
	* 
	* sucursal de la venta
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? null : config.id_sucursal || null,

 /**
	* id_usuario
	* 
	* empleado que lo vendio
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? null : config.id_usuario || null,

 /**
	* pagado
	* 
	* porcentaje pagado de esta venta
	* @access private
	* @var float
	*/
	_pagado = config === undefined ? null : config.pagado || null,

 /**
	* cancelada
	* 
	* verdadero si esta venta ha sido cancelada, falso si no
	* @access private
	* @var tinyint(1)
	*/
	_cancelada = config === undefined ? null : config.cancelada || null,

 /**
	* ip
	* 
	* ip de donde provino esta compra
	* @access private
	* @var varchar(16)
	*/
	_ip = config === undefined ? null : config.ip || null,

 /**
	* liquidada
	* 
	* Verdadero si esta venta ha sido liquidada, falso si hay un saldo pendiente
	* @access private
	* @var tinyint(1)
	*/
	_liquidada = config === undefined ? null : config.liquidada || null;

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de venta
	  * @return int(11)
	  */
	this.getIdVenta = function ()
	{
		return _id_venta;
	};

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de venta.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdVenta( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdVenta  = function ( id_venta )
	{
		_id_venta = id_venta;
	};

	/**
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le vendio
	  * @return int(11)
	  */
	this.getIdCliente = function ()
	{
		return _id_cliente;
	};

	/**
	  * setIdCliente( $id_cliente )
	  * 
	  * Set the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cliente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdCliente  = function ( id_cliente )
	{
		_id_cliente = id_cliente;
	};

	/**
	  * getTipoVenta
	  * 
	  * Get the <i>tipo_venta</i> property for this object. Donde <i>tipo_venta</i> es tipo de venta, contado o credito
	  * @return enum('credito','contado')
	  */
	this.getTipoVenta = function ()
	{
		return _tipo_venta;
	};

	/**
	  * setTipoVenta( $tipo_venta )
	  * 
	  * Set the <i>tipo_venta</i> property for this object. Donde <i>tipo_venta</i> es tipo de venta, contado o credito.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_venta</i> es de tipo <i>enum('credito','contado')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('credito','contado')
	  */
	this.setTipoVenta  = function ( tipo_venta )
	{
		_tipo_venta = tipo_venta;
	};

	/**
	  * getTipoPago
	  * 
	  * Get the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para esta venta en caso de ser a contado
	  * @return enum('efectivo','cheque','tarjeta')
	  */
	this.getTipoPago = function ()
	{
		return _tipo_pago;
	};

	/**
	  * setTipoPago( $tipo_pago )
	  * 
	  * Set the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para esta venta en caso de ser a contado.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_pago</i> es de tipo <i>enum('efectivo','cheque','tarjeta')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('efectivo','cheque','tarjeta')
	  */
	this.setTipoPago  = function ( tipo_pago )
	{
		_tipo_pago = tipo_pago;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de venta
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de venta.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getSubtotal
	  * 
	  * Get the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la venta, puede ser nulo
	  * @return float
	  */
	this.getSubtotal = function ()
	{
		return _subtotal;
	};

	/**
	  * setSubtotal( $subtotal )
	  * 
	  * Set the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la venta, puede ser nulo.
	  * Una validacion basica se hara aqui para comprobar que <i>subtotal</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setSubtotal  = function ( subtotal )
	{
		_subtotal = subtotal;
	};

	/**
	  * getIva
	  * 
	  * Get the <i>iva</i> property for this object. Donde <i>iva</i> es iva agregado por la venta, depende de cada sucursal
	  * @return float
	  */
	this.getIva = function ()
	{
		return _iva;
	};

	/**
	  * setIva( $iva )
	  * 
	  * Set the <i>iva</i> property for this object. Donde <i>iva</i> es iva agregado por la venta, depende de cada sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>iva</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setIva  = function ( iva )
	{
		_iva = iva;
	};

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta venta
	  * @return float
	  */
	this.getDescuento = function ()
	{
		return _descuento;
	};

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta venta.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setDescuento  = function ( descuento )
	{
		_descuento = descuento;
	};

	/**
	  * getTotal
	  * 
	  * Get the <i>total</i> property for this object. Donde <i>total</i> es total de esta venta
	  * @return float
	  */
	this.getTotal = function ()
	{
		return _total;
	};

	/**
	  * setTotal( $total )
	  * 
	  * Set the <i>total</i> property for this object. Donde <i>total</i> es total de esta venta.
	  * Una validacion basica se hara aqui para comprobar que <i>total</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotal  = function ( total )
	{
		_total = total;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la venta
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la venta.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es empleado que lo vendio
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es empleado que lo vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getPagado
	  * 
	  * Get the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta venta
	  * @return float
	  */
	this.getPagado = function ()
	{
		return _pagado;
	};

	/**
	  * setPagado( $pagado )
	  * 
	  * Set the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta venta.
	  * Una validacion basica se hara aqui para comprobar que <i>pagado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPagado  = function ( pagado )
	{
		_pagado = pagado;
	};

	/**
	  * getCancelada
	  * 
	  * Get the <i>cancelada</i> property for this object. Donde <i>cancelada</i> es verdadero si esta venta ha sido cancelada, falso si no
	  * @return tinyint(1)
	  */
	this.getCancelada = function ()
	{
		return _cancelada;
	};

	/**
	  * setCancelada( $cancelada )
	  * 
	  * Set the <i>cancelada</i> property for this object. Donde <i>cancelada</i> es verdadero si esta venta ha sido cancelada, falso si no.
	  * Una validacion basica se hara aqui para comprobar que <i>cancelada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setCancelada  = function ( cancelada )
	{
		_cancelada = cancelada;
	};

	/**
	  * getIp
	  * 
	  * Get the <i>ip</i> property for this object. Donde <i>ip</i> es ip de donde provino esta compra
	  * @return varchar(16)
	  */
	this.getIp = function ()
	{
		return _ip;
	};

	/**
	  * setIp( $ip )
	  * 
	  * Set the <i>ip</i> property for this object. Donde <i>ip</i> es ip de donde provino esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>ip</i> es de tipo <i>varchar(16)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(16)
	  */
	this.setIp  = function ( ip )
	{
		_ip = ip;
	};

	/**
	  * getLiquidada
	  * 
	  * Get the <i>liquidada</i> property for this object. Donde <i>liquidada</i> es Verdadero si esta venta ha sido liquidada, falso si hay un saldo pendiente
	  * @return tinyint(1)
	  */
	this.getLiquidada = function ()
	{
		return _liquidada;
	};

	/**
	  * setLiquidada( $liquidada )
	  * 
	  * Set the <i>liquidada</i> property for this object. Donde <i>liquidada</i> es Verdadero si esta venta ha sido liquidada, falso si hay un saldo pendiente.
	  * Una validacion basica se hara aqui para comprobar que <i>liquidada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setLiquidada  = function ( liquidada )
	{
		_liquidada = liquidada;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Ventas} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Ventas._callback_stack.push( _original_callback  );
		Ventas._callback_stack.push( function(res){ 
						//console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create(this);
						}else{
							update(this);
						}
	 			});
		Ventas.getByPK(  this.getIdVenta() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ventas} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Ventas [$ventas] El objeto de tipo Ventas
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from ventas WHERE ("; 
		$val = [];
		if( this.getIdVenta() != null){
			$sql += " id_venta = ? AND";
			$val.push( this.getIdVenta() );
		}

		if( this.getIdCliente() != null){
			$sql += " id_cliente = ? AND";
			$val.push( this.getIdCliente() );
		}

		if( this.getTipoVenta() != null){
			$sql += " tipo_venta = ? AND";
			$val.push( this.getTipoVenta() );
		}

		if( this.getTipoPago() != null){
			$sql += " tipo_pago = ? AND";
			$val.push( this.getTipoPago() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getSubtotal() != null){
			$sql += " subtotal = ? AND";
			$val.push( this.getSubtotal() );
		}

		if( this.getIva() != null){
			$sql += " iva = ? AND";
			$val.push( this.getIva() );
		}

		if( this.getDescuento() != null){
			$sql += " descuento = ? AND";
			$val.push( this.getDescuento() );
		}

		if( this.getTotal() != null){
			$sql += " total = ? AND";
			$val.push( this.getTotal() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getPagado() != null){
			$sql += " pagado = ? AND";
			$val.push( this.getPagado() );
		}

		if( this.getCancelada() != null){
			$sql += " cancelada = ? AND";
			$val.push( this.getCancelada() );
		}

		if( this.getIp() != null){
			$sql += " ip = ? AND";
			$val.push( this.getIp() );
		}

		if( this.getLiquidada() != null){
			$sql += " liquidada = ? AND";
			$val.push( this.getLiquidada() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Ventas($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Ventas suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Ventas dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Ventas [$ventas] El objeto de tipo Ventas a crear.
	  **/
	var create = function( ventas )
	{
		//console.log('estoy en create()');
		$sql = "INSERT INTO ventas ( id_venta, id_cliente, tipo_venta, tipo_pago, fecha, subtotal, iva, descuento, total, id_sucursal, id_usuario, pagado, cancelada, ip, liquidada ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			ventas.getIdVenta(), 
			ventas.getIdCliente(), 
			ventas.getTipoVenta(), 
			ventas.getTipoPago(), 
			ventas.getFecha(), 
			ventas.getSubtotal(), 
			ventas.getIva(), 
			ventas.getDescuento(), 
			ventas.getTotal(), 
			ventas.getIdSucursal(), 
			ventas.getIdUsuario(), 
			ventas.getPagado(), 
			ventas.getCancelada(), 
			ventas.getIp(), 
			ventas.getLiquidada(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Ventas._callback_stack.pop().call(null, ventas);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Ventas [$ventas] El objeto de tipo Ventas a actualizar.
	  **/
	var update = function( $ventas )
	{
		$sql = "UPDATE ventas SET  id_cliente = ?, tipo_venta = ?, tipo_pago = ?, fecha = ?, subtotal = ?, iva = ?, descuento = ?, total = ?, id_sucursal = ?, id_usuario = ?, pagado = ?, cancelada = ?, ip = ?, liquidada = ? WHERE  id_venta = ?;";
		$params = [ 
			$ventas.getIdCliente(), 
			$ventas.getTipoVenta(), 
			$ventas.getTipoPago(), 
			$ventas.getFecha(), 
			$ventas.getSubtotal(), 
			$ventas.getIva(), 
			$ventas.getDescuento(), 
			$ventas.getTotal(), 
			$ventas.getIdSucursal(), 
			$ventas.getIdUsuario(), 
			$ventas.getPagado(), 
			$ventas.getCancelada(), 
			$ventas.getIp(), 
			$ventas.getLiquidada(), 
			$ventas.getIdVenta(),  ] ;
		//console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Ventas._callback_stack.pop().call(null, $ventas);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Ventas suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Ventas.getByPK(this.getIdVenta()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM ventas WHERE  id_venta = ?;";
		$params = [ this.getIdVenta() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ventas} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Ventas}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Ventas [$ventas] El objeto de tipo Ventas
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $ventas , $orderBy , $orden )
	{
		$sql = "SELECT * from ventas WHERE ("; 
		$val = [];
		if( (($a = this.getIdVenta()) != null) & ( ($b = $ventas.getIdVenta()) != null) ){
				$sql += " id_venta >= ? AND id_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdCliente()) != null) & ( ($b = $ventas.getIdCliente()) != null) ){
				$sql += " id_cliente >= ? AND id_cliente <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_cliente = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoVenta()) != null) & ( ($b = $ventas.getTipoVenta()) != null) ){
				$sql += " tipo_venta >= ? AND tipo_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoPago()) != null) & ( ($b = $ventas.getTipoPago()) != null) ){
				$sql += " tipo_pago >= ? AND tipo_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $ventas.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSubtotal()) != null) & ( ($b = $ventas.getSubtotal()) != null) ){
				$sql += " subtotal >= ? AND subtotal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " subtotal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIva()) != null) & ( ($b = $ventas.getIva()) != null) ){
				$sql += " iva >= ? AND iva <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " iva = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescuento()) != null) & ( ($b = $ventas.getDescuento()) != null) ){
				$sql += " descuento >= ? AND descuento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descuento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotal()) != null) & ( ($b = $ventas.getTotal()) != null) ){
				$sql += " total >= ? AND total <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $ventas.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $ventas.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPagado()) != null) & ( ($b = $ventas.getPagado()) != null) ){
				$sql += " pagado >= ? AND pagado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " pagado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCancelada()) != null) & ( ($b = $ventas.getCancelada()) != null) ){
				$sql += " cancelada >= ? AND cancelada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cancelada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIp()) != null) & ( ($b = $ventas.getIp()) != null) ){
				$sql += " ip >= ? AND ip <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " ip = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLiquidada()) != null) & ( ($b = $ventas.getLiquidada()) != null) ){
				$sql += " liquidada >= ? AND liquidada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " liquidada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Ventas($foo));
		//}
		return $sql;
	};


}
	Ventas._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Ventas}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Ventas.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from ventas";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Ventas( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Ventas} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Ventas} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Ventas Un objeto del tipo {@link Ventas}. NULL si no hay tal registro.
	  **/
	Ventas.getByPK = function(  $id_venta, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM ventas WHERE (id_venta = ? ) LIMIT 1;";
		db.query($sql, [ $id_venta] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Ventas(results.rows.item(0)); 
				Ventas._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Ventas._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


