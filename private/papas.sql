-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 03-01-2011 a las 19:45:52
-- Versión del servidor: 5.1.47
-- Versión de PHP: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `pcsyste1_pos01`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actualizacion_de_precio`
--

CREATE TABLE IF NOT EXISTS `actualizacion_de_precio` (
  `id_actualizacion` int(12) NOT NULL AUTO_INCREMENT COMMENT 'id de actualizacion de precio',
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `precio_venta` float NOT NULL,
  `precio_compra` float NOT NULL,
  `precio_intersucursal` float NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_actualizacion`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Actualizaciones de precios' AUTO_INCREMENT=18 ;

--
-- Volcar la base de datos para la tabla `actualizacion_de_precio`
--

INSERT INTO `actualizacion_de_precio` (`id_actualizacion`, `id_producto`, `id_usuario`, `precio_venta`, `precio_compra`, `precio_intersucursal`, `fecha`) VALUES
(1, 1, 1, 7.5, 7.4, 7.4, '2011-01-03 13:51:13'),
(2, 2, 1, 6, 6, 6, '2011-01-03 13:52:03'),
(3, 3, 1, 9, 8.5, 8.5, '2011-01-03 13:52:45'),
(4, 3, 1, 9, 7.5, 7.5, '2011-01-03 13:53:20'),
(5, 4, 1, 4, 4, 4, '2011-01-03 13:53:53'),
(6, 5, 1, 7, 7, 7, '2011-01-03 13:54:30'),
(7, 6, 1, 280, 250, 250, '2011-01-03 13:55:29'),
(8, 7, 1, 180, 150, 150, '2011-01-03 13:56:09'),
(9, 8, 1, 100, 80, 80, '2011-01-03 13:56:40'),
(10, 9, 1, 9, 9, 9, '2011-01-03 13:58:15'),
(11, 10, 1, 7, 7, 7, '2011-01-03 14:01:59'),
(12, 11, 1, 12.5, 7.5, 7.5, '2011-01-03 14:02:36'),
(13, 11, 1, 12.5, 12.5, 12.5, '2011-01-03 14:02:56'),
(14, 1, 1, 7.5, 7.4, 7.4, '2011-01-03 15:20:42'),
(15, 1, 1, 9, 7.5, 7.5, '2011-01-03 15:21:24'),
(16, 2, 1, 7.5, 7.4, 7.4, '2011-01-03 15:22:42'),
(17, 3, 1, 6, 6, 6, '2011-01-03 15:23:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autorizacion`
--

CREATE TABLE IF NOT EXISTS `autorizacion` (
  `id_autorizacion` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL COMMENT 'Usuario que solicito esta autorizacion',
  `id_sucursal` int(11) NOT NULL COMMENT 'Sucursal de donde proviene esta autorizacion',
  `fecha_peticion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha cuando se genero esta autorizacion',
  `fecha_respuesta` timestamp NULL DEFAULT NULL COMMENT 'Fecha de cuando se resolvio esta aclaracion',
  `estado` int(11) NOT NULL COMMENT 'Estado actual de esta aclaracion',
  `parametros` varchar(2048) NOT NULL COMMENT 'Parametros en formato JSON que describen esta autorizacion',
  PRIMARY KEY (`id_autorizacion`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `autorizacion`
--

INSERT INTO `autorizacion` (`id_autorizacion`, `id_usuario`, `id_sucursal`, `fecha_peticion`, `fecha_respuesta`, `estado`, `parametros`) VALUES
(1, -1, 5, '2011-01-03 16:26:52', '2011-01-03 16:26:52', 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":2,"cantidad":"120"},{"id_producto":3,"cantidad":"240"}]}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del cliente',
  `rfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'rfc del cliente si es que tiene',
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre del cliente',
  `direccion` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'domicilio del cliente calle, no, colonia',
  `ciudad` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Ciudad de este cliente',
  `telefono` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Telefono del cliete',
  `e_mail` varchar(60) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT 'dias de credito para que pague el cliente',
  `limite_credito` float NOT NULL DEFAULT '0' COMMENT 'Limite de credito otorgado al cliente',
  `descuento` float NOT NULL DEFAULT '0' COMMENT 'Taza porcentual de descuento de 0.0 a 100.0',
  `activo` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'Indica si la cuenta esta activada o desactivada',
  `id_usuario` int(11) NOT NULL COMMENT 'Identificador del usuario que dio de alta a este cliente',
  `id_sucursal` int(11) NOT NULL COMMENT 'Identificador de la sucursal donde se dio de alta este cliente',
  `fecha_ingreso` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha cuando este cliente se registro en una sucursal',
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `rfc`, `nombre`, `direccion`, `ciudad`, `telefono`, `e_mail`, `limite_credito`, `descuento`, `activo`, `id_usuario`, `id_sucursal`, `fecha_ingreso`) VALUES
(-5, 'GATJ740714F48', 'Caja Comun', 'CENTRAL DE ABASTOS DEL BAJIO A. C. ANDEN E, BODEGA 43, APASEO EL GRANDE, GUANAJUATO', '', '014616172030', '', 0, 0, 1, -1, 5, '2011-01-03 14:56:30'),
(-4, 'GATJ740714F48', 'Caja Comun', 'MERCADO DE ABASTOS B JUAREZ, ANDEN D, BODEGA 120, CELAYA', '', '014616080371', '', 0, 0, 1, -1, 4, '2011-01-03 14:52:57'),
(1, 'MELA760306', 'ALVARO MERINO LANUZA', 'CENTRAL DE ABASTOS DEL BAJIO A.C. ANDEN E BODEGA 33', 'APASEO EL GRANDE, GUANAJUATO', '52*17416*1', '', 5000, 0, 1, 95, 5, '2011-01-03 15:09:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE IF NOT EXISTS `compras` (
  `id_compra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la compra',
  `id_proveedor` int(11) NOT NULL COMMENT 'PROVEEDOR AL QUE SE LE COMPRO',
  `tipo_compra` enum('credito','contado') COLLATE utf8_unicode_ci NOT NULL COMMENT 'tipo de compra, contado o credito',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de compra',
  `subtotal` float NOT NULL COMMENT 'subtotal de compra',
  `iva` float NOT NULL COMMENT 'iva de la compra',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en que se compro',
  `id_usuario` int(11) NOT NULL COMMENT 'quien realizo la compra',
  `pagado` float NOT NULL DEFAULT '0' COMMENT 'total de pago abonado a esta compra',
  `liquidado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'indica si la cuenta ha sido liquidada o no',
  PRIMARY KEY (`id_compra`),
  KEY `compras_proveedor` (`id_proveedor`),
  KEY `compras_sucursal` (`id_sucursal`),
  KEY `compras_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `compras`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `corte`
--

CREATE TABLE IF NOT EXISTS `corte` (
  `id_corte` int(12) NOT NULL AUTO_INCREMENT COMMENT 'identificador del corte',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de este corte',
  `id_sucursal` int(12) NOT NULL COMMENT 'sucursal a la que se realizo este corte',
  `total_ventas` float NOT NULL COMMENT 'total de activo realizado en ventas para esta sucursal incluyendo ventas a credito y ventas a contado aunque no esten saldadas',
  `total_ventas_abonado` float NOT NULL COMMENT 'total de efectivo adquirido gracias a ventas, incluye ventas a contado y los abonos de las ventas a credito',
  `total_ventas_saldo` float NOT NULL COMMENT 'total de dinero que se le debe a esta sucursal por ventas a credito',
  `total_compras` float NOT NULL COMMENT 'total de gastado en compras',
  `total_compras_abonado` float NOT NULL COMMENT 'total de abonado en compras',
  `total_gastos` float NOT NULL COMMENT 'total de gastos con saldo o sin salgo',
  `total_gastos_abonado` float NOT NULL COMMENT 'total de gastos pagados ya',
  `total_ingresos` float NOT NULL COMMENT 'total de ingresos para esta sucursal desde el ultimo corte',
  `total_ganancia_neta` float NOT NULL COMMENT 'calculo de ganancia neta',
  PRIMARY KEY (`id_corte`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `corte`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE IF NOT EXISTS `detalle_compra` (
  `id_compra` int(11) NOT NULL COMMENT 'id de la compra',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `cantidad` float NOT NULL COMMENT 'cantidad comprada',
  `precio` float NOT NULL COMMENT 'costo de compra',
  PRIMARY KEY (`id_compra`,`id_producto`),
  KEY `detalle_compra_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `detalle_compra`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_inventario`
--

CREATE TABLE IF NOT EXISTS `detalle_inventario` (
  `id_producto` int(11) NOT NULL COMMENT 'id del producto al que se refiere',
  `id_sucursal` int(11) NOT NULL COMMENT 'id de la sucursal',
  `precio_venta` float NOT NULL COMMENT 'precio al que se vendera al publico',
  `min` float NOT NULL DEFAULT '0' COMMENT 'cantidad minima que debe de haber del producto en almacen de esta sucursal',
  `existencias` float NOT NULL DEFAULT '0' COMMENT 'cantidad de producto que hay actualmente en almacen de esta sucursal',
  PRIMARY KEY (`id_producto`,`id_sucursal`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `detalle_inventario`
--

INSERT INTO `detalle_inventario` (`id_producto`, `id_sucursal`, `precio_venta`, `min`, `existencias`) VALUES
(2, 5, 7.5, 100, 120),
(3, 5, 6, 100, 180);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE IF NOT EXISTS `detalle_venta` (
  `id_venta` int(11) NOT NULL COMMENT 'venta a que se referencia',
  `id_producto` int(11) NOT NULL COMMENT 'producto de la venta',
  `cantidad` float NOT NULL COMMENT 'cantidad que se vendio',
  `precio` float NOT NULL COMMENT 'precio al que se vendio',
  PRIMARY KEY (`id_venta`,`id_producto`),
  KEY `detalle_venta_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id_venta`, `id_producto`, `cantidad`, `precio`) VALUES
(1, 3, 60, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE IF NOT EXISTS `equipo` (
  `id_equipo` int(6) NOT NULL AUTO_INCREMENT COMMENT 'el identificador de este equipo',
  `token` varchar(128) NOT NULL COMMENT 'el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado',
  `full_ua` varchar(256) NOT NULL COMMENT 'String de user-agent para este cliente',
  PRIMARY KEY (`id_equipo`),
  UNIQUE KEY `token` (`token`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcar la base de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id_equipo`, `token`, `full_ua`) VALUES
(5, 'asdf', 'Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10'),
(6, 'asdffd', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; es-ES) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4'),
(7, 'Jbchkckc', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo_sucursal`
--

CREATE TABLE IF NOT EXISTS `equipo_sucursal` (
  `id_equipo` int(6) NOT NULL COMMENT 'identificador del equipo ',
  `id_sucursal` int(6) NOT NULL COMMENT 'identifica una sucursal',
  PRIMARY KEY (`id_equipo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `equipo_sucursal`
--

INSERT INTO `equipo_sucursal` (`id_equipo`, `id_sucursal`) VALUES
(5, 3),
(6, 3),
(7, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_compra`
--

CREATE TABLE IF NOT EXISTS `factura_compra` (
  `folio` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `id_compra` int(11) NOT NULL COMMENT 'COMPRA A LA QUE CORRESPONDE LA FACTURA',
  PRIMARY KEY (`folio`),
  KEY `factura_compra_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `factura_compra`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_venta`
--

CREATE TABLE IF NOT EXISTS `factura_venta` (
  `folio` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'folio que tiene la factura',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la factura',
  PRIMARY KEY (`folio`),
  KEY `factura_venta_venta` (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `factura_venta`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE IF NOT EXISTS `gastos` (
  `id_gasto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id para identificar el gasto',
  `folio` varchar(22) NOT NULL COMMENT 'El folio de la factura para este gasto',
  `concepto` varchar(100) NOT NULL COMMENT 'concepto en lo que se gasto',
  `monto` float unsigned NOT NULL COMMENT 'lo que costo este gasto',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha del gasto',
  `fecha_ingreso` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Fecha que selecciono el empleado en el sistema',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en la que se hizo el gasto',
  `id_usuario` int(11) NOT NULL COMMENT 'usuario que registro el gasto',
  `nota` varchar(512) NOT NULL COMMENT 'nota adicional para complementar la descripcion del gasto',
  PRIMARY KEY (`id_gasto`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `gastos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE IF NOT EXISTS `grupos` (
  `id_grupo` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL COMMENT 'Nombre del Grupo',
  `descripcion` varchar(256) NOT NULL,
  PRIMARY KEY (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `nombre`, `descripcion`) VALUES
(0, 'inge', 'inge'),
(1, 'admin', 'admin'),
(2, 'gerente', 'Gerente'),
(3, 'cajero', 'cajero'),
(4, 'repartidor', 'repartidor'),
(5, 'personal', 'personal'),
(6, 'chofer', 'chofer'),
(7, 'cocinero', 'cocinero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos_usuarios`
--

CREATE TABLE IF NOT EXISTS `grupos_usuarios` (
  `id_grupo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_grupos_usuarios_1` (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `grupos_usuarios`
--

INSERT INTO `grupos_usuarios` (`id_grupo`, `id_usuario`) VALUES
(0, 88),
(1, 1),
(2, 94),
(2, 95);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE IF NOT EXISTS `ingresos` (
  `id_ingreso` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id para identificar el ingreso',
  `concepto` varchar(100) NOT NULL COMMENT 'concepto en lo que se ingreso',
  `monto` float NOT NULL COMMENT 'lo que costo este ingreso',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha del ingreso',
  `fecha_ingreso` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Fecha que selecciono el empleado en el sistema',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en la que se hizo el ingreso',
  `id_usuario` int(11) NOT NULL COMMENT 'usuario que registro el ingreso',
  `nota` varchar(512) NOT NULL COMMENT 'nota adicional para complementar la descripcion del ingreso',
  PRIMARY KEY (`id_ingreso`),
  KEY `usuario_ingreso` (`id_usuario`),
  KEY `sucursal_ingreso` (`id_sucursal`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=203 ;

--
-- Volcar la base de datos para la tabla `ingresos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del producto',
  `descripcion` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'descripcion del producto',
  `precio_intersucursal` float NOT NULL,
  `costo` float NOT NULL,
  `medida` enum('fraccion','unidad') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Volcar la base de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_producto`, `descripcion`, `precio_intersucursal`, `costo`, `medida`) VALUES
(1, 'PAPA PRIMERA', 7.5, 7.5, 'fraccion'),
(2, 'PAPA SEGUNDA', 7.4, 7.4, 'fraccion'),
(3, 'PAPA TERCERA', 6, 6, 'fraccion'),
(4, 'PAPA CUARTA', 4, 4, 'fraccion'),
(5, 'PAPA PARA DORAR', 7, 7, 'fraccion'),
(6, 'PAPA VERDE GRANDE', 250, 250, 'unidad'),
(7, 'PAPA VERDE CHICA', 150, 150, 'unidad'),
(8, 'PAPA VERDE CUARTA', 80, 80, 'unidad'),
(9, 'FRIJOL JUNIO B', 9, 9, 'fraccion'),
(10, 'FRIJOL JUNIO DESLV', 7, 7, 'fraccion'),
(11, 'FRIJOL PERUANO', 12.5, 12.5, 'fraccion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_compra`
--

CREATE TABLE IF NOT EXISTS `pagos_compra` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del pago',
  `id_compra` int(11) NOT NULL COMMENT 'identificador de la compra a la que pagamos',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha en que se abono',
  `monto` float NOT NULL COMMENT 'monto que se abono',
  PRIMARY KEY (`id_pago`),
  KEY `pagos_compra_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pagos_compra`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_venta`
--

CREATE TABLE IF NOT EXISTS `pagos_venta` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de pago del cliente',
  `id_venta` int(11) NOT NULL COMMENT 'id de la venta a la que se esta pagando',
  `id_sucursal` int(11) NOT NULL COMMENT 'Donde se realizo el pago',
  `id_usuario` int(11) NOT NULL COMMENT 'Quien cobro este pago',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha en que se registro el pago',
  `monto` float NOT NULL COMMENT 'total de credito del cliente',
  PRIMARY KEY (`id_pago`),
  KEY `pagos_venta_venta` (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `pagos_venta`
--

INSERT INTO `pagos_venta` (`id_pago`, `id_venta`, `id_sucursal`, `id_usuario`, `fecha`, `monto`) VALUES
(1, 1, 5, 95, '2011-01-03 15:35:36', 360);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_proveedor`
--

CREATE TABLE IF NOT EXISTS `productos_proveedor` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del producto',
  `clave_producto` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'clave de producto para el proveedor',
  `id_proveedor` int(11) NOT NULL COMMENT 'clave del proveedor',
  `id_inventario` int(11) NOT NULL COMMENT 'clave con la que entra a nuestro inventario',
  `descripcion` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descripcion del producto que nos vende el proveedor',
  `precio` int(11) NOT NULL COMMENT 'precio al que se compra el producto (sin descuento)',
  PRIMARY KEY (`id_producto`),
  UNIQUE KEY `clave_producto` (`clave_producto`,`id_proveedor`),
  UNIQUE KEY `id_proveedor` (`id_proveedor`,`id_inventario`),
  KEY `productos_proveedor_proveedor` (`id_proveedor`),
  KEY `productos_proveedor_producto` (`id_inventario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `productos_proveedor`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE IF NOT EXISTS `proveedor` (
  `id_proveedor` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del proveedor',
  `rfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'rfc del proveedor',
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre del proveedor',
  `direccion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'direccion del proveedor',
  `telefono` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'telefono',
  `e_mail` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'email del provedor',
  `activo` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'Indica si la cuenta esta activada o desactivada',
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `proveedor`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
  `id_sucursal` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de cada sucursal',
  `gerente` int(11) NOT NULL COMMENT 'Gerente de esta sucursal',
  `descripcion` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre o descripcion de sucursal',
  `direccion` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'direccion de la sucursal',
  `rfc` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'El RFC de la sucursal',
  `telefono` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'El telefono de la sucursal',
  `token` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Token de seguridad para esta sucursal',
  `letras_factura` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_apertura` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de apertura de esta sucursal',
  `saldo_a_favor` float NOT NULL DEFAULT '0' COMMENT 'es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras',
  PRIMARY KEY (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id_sucursal`, `gerente`, `descripcion`, `direccion`, `rfc`, `telefono`, `token`, `letras_factura`, `activo`, `fecha_apertura`, `saldo_a_favor`) VALUES
(1, 11, 'PAPAS SUPREMAS 1', 'MERCADO DE ABASTOS B. JUAREZ, ANDEN 2 BODEGA 49, CELAYA', 'GATJ740714F48', '014616128194', NULL, 'A', 1, '2010-12-30 22:12:02', 0),
(4, 94, 'PAPAS SUPREMAS 2', 'MERCADO DE ABASTOS B JUAREZ, ANDEN D, BODEGA 120, CELAYA', 'GATJ740714F48', '014616080371', NULL, 'B', 1, '2011-01-03 14:52:57', 0),
(5, 95, 'PAPAS SUPREMAS 3', 'CENTRAL DE ABASTOS DEL BAJIO A. C. ANDEN E, BODEGA 43, APASEO EL GRANDE, GUANAJUATO', 'GATJ740714F48', '014616172030', NULL, 'C', 1, '2011-01-03 14:56:30', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del usuario',
  `RFC` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'RFC de este usuario',
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre del empleado',
  `contrasena` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `id_sucursal` int(11) DEFAULT NULL COMMENT 'Id de la sucursal a que pertenece',
  `activo` tinyint(1) NOT NULL COMMENT 'Guarda el estado de la cuenta del usuario',
  `finger_token` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Una cadena que sera comparada con el token que mande el scanner de huella digital',
  `salario` float NOT NULL COMMENT 'Salario actual',
  `direccion` varchar(512) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Direccion del empleado',
  `telefono` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Telefono del empleado',
  `fecha_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha cuando este usuario comenzo a laborar',
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuario_1` (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=96 ;

--
-- Volcar la base de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `RFC`, `nombre`, `contrasena`, `id_sucursal`, `activo`, `finger_token`, `salario`, `direccion`, `telefono`, `fecha_inicio`) VALUES
(1, '1', 'Juan Antonio Garcia Tapia', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 0, '0', '0', '2010-12-30 00:00:00'),
(11, 'EOMJ740922CY1', 'JUANA ESCOBAR MARTINEZ', '202cb962ac59075b964b07152d234b70', 1, 1, NULL, 2000, 'saltodel agua 129', '6146366', '2010-12-30 00:00:00'),
(12, 'FMAJ741212', 'Jose Flores Martinez', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, '', 2000, 'Salvatierra 109, Col Santa Maria, Celaya', '6165693', '2010-12-30 00:00:00'),
(88, '0', '0', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 0, '0', '0', '2010-12-30 20:39:17'),
(94, 'GATJ680304', 'JUANA MARIA GARCIA TAPIA', '14d11cb5f903ae068fe3e39969db49a5', 4, 1, NULL, 2500, 'SALTO DEL AGUA #129, COL LAS FUENTES, CELAYA', '01 461 61 46366', '2011-01-03 00:00:00'),
(95, 'LEFR870204', 'RAUL ALEJANDRO LEMUS FLORES', '76e91698d162ac84d2a3fede1e10f835', 5, 1, NULL, 2800, 'EJIDO DE LA MACHUCA #354, COL MONTE BLANCO, CELAYA', '014611604586', '2011-01-03 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE IF NOT EXISTS `ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de venta',
  `id_cliente` int(11) NOT NULL COMMENT 'cliente al que se le vendio',
  `tipo_venta` enum('credito','contado') COLLATE utf8_unicode_ci NOT NULL COMMENT 'tipo de venta, contado o credito',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de venta',
  `subtotal` float DEFAULT NULL COMMENT 'subtotal de la venta, puede ser nulo',
  `iva` float DEFAULT NULL COMMENT 'iva agregado por la venta, depende de cada sucursal',
  `descuento` float NOT NULL DEFAULT '0' COMMENT 'descuento aplicado a esta venta',
  `total` float NOT NULL DEFAULT '0' COMMENT 'total de esta venta',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal de la venta',
  `id_usuario` int(11) NOT NULL COMMENT 'empleado que lo vendio',
  `pagado` float NOT NULL DEFAULT '0' COMMENT 'porcentaje pagado de esta venta',
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT 'ip de donde provino esta compra',
  PRIMARY KEY (`id_venta`),
  KEY `ventas_cliente` (`id_cliente`),
  KEY `ventas_sucursal` (`id_sucursal`),
  KEY `ventas_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `tipo_venta`, `fecha`, `subtotal`, `iva`, `descuento`, `total`, `id_sucursal`, `id_usuario`, `pagado`, `ip`) VALUES
(1, 1, 'credito', '2011-01-03 15:31:14', 360, NULL, 0, 360, 5, 95, 360, '189.163.68.194');

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `compras_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_inventario`
--
ALTER TABLE `detalle_inventario`
  ADD CONSTRAINT `detalle_inventario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_inventario_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
  ADD CONSTRAINT `factura_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura_venta`
--
ALTER TABLE `factura_venta`
  ADD CONSTRAINT `factura_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

--
-- Filtros para la tabla `grupos_usuarios`
--
ALTER TABLE `grupos_usuarios`
  ADD CONSTRAINT `grupos_usuarios_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `grupos_usuarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_compra`
--
ALTER TABLE `pagos_compra`
  ADD CONSTRAINT `pagos_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
  ADD CONSTRAINT `pagos_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `productos_proveedor`
--
ALTER TABLE `productos_proveedor`
  ADD CONSTRAINT `productos_proveedor_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_proveedor_ibfk_2` FOREIGN KEY (`id_inventario`) REFERENCES `inventario` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

