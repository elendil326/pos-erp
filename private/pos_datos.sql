-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 27, 2010 at 12:59 PM
-- Server version: 5.0.51
-- PHP Version: 5.3.3-0.dotdeb.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `actualizacion_de_precio`
--

CREATE TABLE IF NOT EXISTS `actualizacion_de_precio` (
  `id_actualizacion` int(12) NOT NULL auto_increment COMMENT 'id de actualizacion de precio',
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `precio_venta` float NOT NULL,
  `precio_compra` float NOT NULL,
  `precio_intersucursal` float NOT NULL,
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id_actualizacion`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Actualizaciones de precios' AUTO_INCREMENT=14 ;

--
-- Dumping data for table `actualizacion_de_precio`
--

INSERT INTO `actualizacion_de_precio` (`id_actualizacion`, `id_producto`, `id_usuario`, `precio_venta`, `precio_compra`, `precio_intersucursal`, `fecha`) VALUES
(1, 1, 37, 8, 5, 5, '2010-12-20 01:10:35'),
(2, 1, 1, 2, 5, 5, '2010-12-23 23:08:29'),
(3, 1, 1, 8.5, 8, 8, '2010-12-23 23:09:22'),
(4, 1, 1, 8.5, 8, 8, '2010-12-24 00:17:25'),
(5, 1, 1, 8.5, 8, 8, '2010-12-24 00:20:58'),
(6, 1, 1, 8.5, 8, 8, '2010-12-24 00:21:36'),
(7, 1, 1, 8.5, 8, 8, '2010-12-24 00:26:21'),
(8, 1, 1, 9.5, 7.5, 7.5, '2010-12-24 00:33:50'),
(9, 1, 1, 9.5, 7.5, 7.5, '2010-12-24 00:35:03'),
(10, 1, 1, 9.5, 7.5, 7.5, '2010-12-24 00:43:47'),
(11, 1, 1, 9.45, 7.5, 7.5, '2010-12-24 00:47:13'),
(12, 1, 1, 9.4, 7.5, 7.5, '2010-12-24 00:49:44'),
(13, 1, 1, 9.5, 7.5, 7.5, '2010-12-24 03:26:12');

-- --------------------------------------------------------

--
-- Table structure for table `autorizacion`
--

CREATE TABLE IF NOT EXISTS `autorizacion` (
  `id_autorizacion` int(11) unsigned NOT NULL auto_increment,
  `id_usuario` int(11) NOT NULL COMMENT 'Usuario que solicito esta autorizacion',
  `id_sucursal` int(11) NOT NULL COMMENT 'Sucursal de donde proviene esta autorizacion',
  `fecha_peticion` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Fecha cuando se genero esta autorizacion',
  `fecha_respuesta` timestamp NULL default NULL COMMENT 'Fecha de cuando se resolvio esta aclaracion',
  `estado` int(11) NOT NULL COMMENT 'Estado actual de esta aclaracion',
  `parametros` varchar(2048) NOT NULL COMMENT 'Parametros en formato JSON que describen esta autorizacion',
  PRIMARY KEY  (`id_autorizacion`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `autorizacion`
--

INSERT INTO `autorizacion` (`id_autorizacion`, `id_usuario`, `id_sucursal`, `fecha_peticion`, `fecha_respuesta`, `estado`, `parametros`) VALUES
(7, 2, 1, '2010-12-24 04:05:29', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"1","cantidad":"5000"}'),
(5, -1, 1, '2010-12-26 01:30:43', '2010-12-26 01:30:43', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"cantidad":"123"}]}'),
(3, -1, 1, '2010-12-26 01:26:27', '2010-12-26 01:26:27', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"cantidad":"500"}]}'),
(4, -1, 1, '2010-12-22 01:36:57', '2010-12-22 01:36:57', 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"cantidad":"600"}]}'),
(12, -1, 1, '2010-12-26 01:33:55', '2010-12-26 01:33:55', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"cantidad":"12"}]}'),
(6, -1, 1, '2010-12-24 03:28:52', '2010-12-24 03:28:52', 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"cantidad":"500"}]}'),
(8, 2, 1, '2010-12-24 04:17:46', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"1","cantidad":"8000"}'),
(9, 2, 1, '2010-12-24 04:30:13', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"1","cantidad":"5555"}'),
(10, -1, 1, '2010-12-24 04:38:44', '2010-12-24 04:38:44', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"cantidad":"500"}]}'),
(11, -1, 1, '2010-12-26 01:14:36', '2010-12-26 01:14:36', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"cantidad":"1"}]}');

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` int(11) NOT NULL auto_increment COMMENT 'identificador del cliente',
  `rfc` varchar(20) collate utf8_unicode_ci NOT NULL COMMENT 'rfc del cliente si es que tiene',
  `nombre` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'nombre del cliente',
  `direccion` varchar(300) collate utf8_unicode_ci NOT NULL COMMENT 'domicilio del cliente calle, no, colonia',
  `ciudad` varchar(55) collate utf8_unicode_ci NOT NULL COMMENT 'Ciudad de este cliente',
  `telefono` varchar(25) collate utf8_unicode_ci default NULL COMMENT 'Telefono del cliete',
  `e_mail` varchar(60) collate utf8_unicode_ci default '0' COMMENT 'dias de credito para que pague el cliente',
  `limite_credito` float NOT NULL default '0' COMMENT 'Limite de credito otorgado al cliente',
  `descuento` float NOT NULL default '0' COMMENT 'Taza porcentual de descuento de 0.0 a 100.0',
  `activo` tinyint(2) NOT NULL default '1' COMMENT 'Indica si la cuenta esta activada o desactivada',
  `id_usuario` int(11) NOT NULL COMMENT 'Identificador del usuario que dio de alta a este cliente',
  `id_sucursal` int(11) NOT NULL COMMENT 'Identificador de la sucursal donde se dio de alta este cliente',
  `fecha_ingreso` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'Fecha cuando este cliente se registro en una sucursal',
  PRIMARY KEY  (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `rfc`, `nombre`, `direccion`, `ciudad`, `telefono`, `e_mail`, `limite_credito`, `descuento`, `activo`, `id_usuario`, `id_sucursal`, `fecha_ingreso`) VALUES
(-2, '234234324', 'Caja Comun', 'asdfasdfsadf', '', 'asdfasdf', '', 0, 0, 1, -1, 2, '2010-12-25 09:03:09'),
(-1, 'ARBOL2349237', 'Caja Comun', 'Arboledas 2da Secc', '', '6149974', '', 0, 0, 1, -1, 1, '2010-12-20 01:00:28'),
(1, 'AGH6666543', 'ALAN GONZAEZ HERNANDEZ', 'Monte Balcanes #107, 2Da Seccion Arboledas', 'CELAYA', '1741449', 'ALABOY', 510, 0, 1, 2, 1, '2010-12-20 08:12:23');

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

CREATE TABLE IF NOT EXISTS `compras` (
  `id_compra` int(11) NOT NULL auto_increment COMMENT 'id de la compra',
  `id_proveedor` int(11) NOT NULL COMMENT 'PROVEEDOR AL QUE SE LE COMPRO',
  `tipo_compra` enum('credito','contado') collate utf8_unicode_ci NOT NULL COMMENT 'tipo de compra, contado o credito',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha de compra',
  `subtotal` float NOT NULL COMMENT 'subtotal de compra',
  `iva` float NOT NULL COMMENT 'iva de la compra',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en que se compro',
  `id_usuario` int(11) NOT NULL COMMENT 'quien realizo la compra',
  `pagado` float NOT NULL default '0' COMMENT 'total de pago abonado a esta compra',
  `liquidado` tinyint(1) NOT NULL default '0' COMMENT 'indica si la cuenta ha sido liquidada o no',
  PRIMARY KEY  (`id_compra`),
  KEY `compras_proveedor` (`id_proveedor`),
  KEY `compras_sucursal` (`id_sucursal`),
  KEY `compras_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `compras`
--


-- --------------------------------------------------------

--
-- Table structure for table `corte`
--

CREATE TABLE IF NOT EXISTS `corte` (
  `id_corte` int(12) NOT NULL auto_increment COMMENT 'identificador del corte',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha de este corte',
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
  PRIMARY KEY  (`id_corte`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `corte`
--


-- --------------------------------------------------------

--
-- Table structure for table `detalle_compra`
--

CREATE TABLE IF NOT EXISTS `detalle_compra` (
  `id_compra` int(11) NOT NULL COMMENT 'id de la compra',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `cantidad` float NOT NULL COMMENT 'cantidad comprada',
  `precio` float NOT NULL COMMENT 'costo de compra',
  PRIMARY KEY  (`id_compra`,`id_producto`),
  KEY `detalle_compra_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `detalle_compra`
--


-- --------------------------------------------------------

--
-- Table structure for table `detalle_inventario`
--

CREATE TABLE IF NOT EXISTS `detalle_inventario` (
  `id_producto` int(11) NOT NULL COMMENT 'id del producto al que se refiere',
  `id_sucursal` int(11) NOT NULL COMMENT 'id de la sucursal',
  `precio_venta` float NOT NULL COMMENT 'precio al que se vendera al publico',
  `min` float NOT NULL default '0' COMMENT 'cantidad minima que debe de haber del producto en almacen de esta sucursal',
  `existencias` float NOT NULL default '0' COMMENT 'cantidad de producto que hay actualmente en almacen de esta sucursal',
  PRIMARY KEY  (`id_producto`,`id_sucursal`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detalle_inventario`
--

INSERT INTO `detalle_inventario` (`id_producto`, `id_sucursal`, `precio_venta`, `min`, `existencias`) VALUES
(1, 1, 9.5, 100, 496);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_venta`
--

CREATE TABLE IF NOT EXISTS `detalle_venta` (
  `id_venta` int(11) NOT NULL COMMENT 'venta a que se referencia',
  `id_producto` int(11) NOT NULL COMMENT 'producto de la venta',
  `cantidad` float NOT NULL COMMENT 'cantidad que se vendio',
  `precio` float NOT NULL COMMENT 'precio al que se vendio',
  PRIMARY KEY  (`id_venta`,`id_producto`),
  KEY `detalle_venta_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `detalle_venta`
--

INSERT INTO `detalle_venta` (`id_venta`, `id_producto`, `cantidad`, `precio`) VALUES
(1, 1, 1, 5),
(2, 1, 1, 5),
(3, 1, 1, 5),
(4, 1, 1, 5),
(5, 1, 1, 5),
(6, 1, 1, 5),
(7, 1, 1, 5),
(8, 1, 1, 5),
(9, 1, 1, 5),
(10, 1, 1, 5),
(11, 1, 1, 5),
(12, 1, 1, 5),
(13, 1, 1, 5),
(14, 1, 1, 5),
(15, 1, 1, 5),
(26, 1, 1, 5),
(27, 1, 1, 5),
(28, 1, 1, 5),
(29, 1, 1, 5),
(30, 1, 1, 5),
(31, 1, 1, 5),
(32, 1, 1, 5),
(36, 1, 1, 5),
(37, 1, 1, 5),
(38, 1, 1, 5),
(39, 1, 1, 5),
(40, 1, 1, 5),
(41, 1, 1, 5),
(42, 1, 1, 5),
(43, 1, 1, 5),
(44, 1, 1, 5),
(46, 1, 1, 5),
(47, 1, 1, 5),
(48, 1, 467, 5),
(49, 1, 467, 5),
(50, 1, 450, 5),
(51, 1, 453, 5),
(52, 1, 40, 5),
(54, 1, 1, 5),
(55, 1, 6, 5),
(56, 1, 1, 5),
(57, 1, 5, 5),
(58, 1, 1, 5),
(59, 1, 593, 5),
(60, 1, 1, 5),
(61, 1, 1, 9.45),
(62, 1, 1, 9.5),
(63, 1, 1, 9.5),
(64, 1, 1, 9.5);

-- --------------------------------------------------------

--
-- Table structure for table `equipo`
--

CREATE TABLE IF NOT EXISTS `equipo` (
  `id_equipo` int(6) NOT NULL auto_increment COMMENT 'el identificador de este equipo',
  `token` varchar(128) NOT NULL COMMENT 'el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado',
  `full_ua` varchar(256) NOT NULL COMMENT 'String de user-agent para este cliente',
  PRIMARY KEY  (`id_equipo`),
  UNIQUE KEY `token` (`token`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `equipo`
--

INSERT INTO `equipo` (`id_equipo`, `token`, `full_ua`) VALUES
(1, 'X8s5z', 'sid={X8s5z} (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10'),
(2, 'asdf', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; es-ES) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5'),
(3, 'asdfsdaf', 'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; es-es) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B367 Safari/531.21.10'),
(4, '0000', 'Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');

-- --------------------------------------------------------

--
-- Table structure for table `equipo_sucursal`
--

CREATE TABLE IF NOT EXISTS `equipo_sucursal` (
  `id_equipo` int(6) NOT NULL COMMENT 'identificador del equipo ',
  `id_sucursal` int(6) NOT NULL COMMENT 'identifica una sucursal',
  PRIMARY KEY  (`id_equipo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equipo_sucursal`
--

INSERT INTO `equipo_sucursal` (`id_equipo`, `id_sucursal`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `factura_compra`
--

CREATE TABLE IF NOT EXISTS `factura_compra` (
  `folio` varchar(15) collate utf8_unicode_ci NOT NULL,
  `id_compra` int(11) NOT NULL COMMENT 'COMPRA A LA QUE CORRESPONDE LA FACTURA',
  PRIMARY KEY  (`folio`),
  KEY `factura_compra_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `factura_compra`
--


-- --------------------------------------------------------

--
-- Table structure for table `factura_venta`
--

CREATE TABLE IF NOT EXISTS `factura_venta` (
  `folio` int(11) unsigned NOT NULL auto_increment COMMENT 'folio que tiene la factura',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la factura',
  PRIMARY KEY  (`folio`),
  KEY `factura_venta_venta` (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `factura_venta`
--

INSERT INTO `factura_venta` (`folio`, `id_venta`) VALUES
(1, 59);

-- --------------------------------------------------------

--
-- Table structure for table `gastos`
--

CREATE TABLE IF NOT EXISTS `gastos` (
  `id_gasto` int(11) NOT NULL auto_increment COMMENT 'id para identificar el gasto',
  `folio` varchar(22) NOT NULL COMMENT 'El folio de la factura para este gasto',
  `concepto` varchar(100) NOT NULL COMMENT 'concepto en lo que se gasto',
  `monto` float unsigned NOT NULL COMMENT 'lo que costo este gasto',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha del gasto',
  `fecha_ingreso` timestamp NOT NULL default '0000-00-00 00:00:00' COMMENT 'Fecha que selecciono el empleado en el sistema',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en la que se hizo el gasto',
  `id_usuario` int(11) NOT NULL COMMENT 'usuario que registro el gasto',
  `nota` varchar(512) NOT NULL COMMENT 'nota adicional para complementar la descripcion del gasto',
  PRIMARY KEY  (`id_gasto`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `gastos`
--

INSERT INTO `gastos` (`id_gasto`, `folio`, `concepto`, `monto`, `fecha`, `fecha_ingreso`, `id_sucursal`, `id_usuario`, `nota`) VALUES
(1, 'RE', 'agua', 55, '2010-12-24 02:25:37', '2010-12-28 06:00:00', 1, 2, 'JUMAPA'),
(2, 'TEXSERU', 'telefono', 52.55, '2010-12-24 02:48:04', '2010-12-04 06:00:00', 1, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `grupos`
--

CREATE TABLE IF NOT EXISTS `grupos` (
  `id_grupo` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL COMMENT 'Nombre del Grupo',
  `descripcion` varchar(256) NOT NULL,
  PRIMARY KEY  (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `nombre`, `descripcion`) VALUES
(1, 'admin', 'Administrador del Sistema'),
(2, 'gerente', 'Gerente'),
(3, 'cajero', 'maneja las cajas'),
(4, 'limpieza', 'maneja la limpieza'),
(5, 'contador', 'maneja los dineros'),
(6, 'audi', 'maneja el audi de la empresa');

-- --------------------------------------------------------

--
-- Table structure for table `grupos_usuarios`
--

CREATE TABLE IF NOT EXISTS `grupos_usuarios` (
  `id_grupo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY  (`id_usuario`),
  KEY `fk_grupos_usuarios_1` (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `grupos_usuarios`
--

INSERT INTO `grupos_usuarios` (`id_grupo`, `id_usuario`) VALUES
(1, 1),
(2, 2),
(2, 4),
(2, 5),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `ingresos`
--

CREATE TABLE IF NOT EXISTS `ingresos` (
  `id_ingreso` int(11) NOT NULL auto_increment COMMENT 'id para identificar el ingreso',
  `concepto` varchar(100) NOT NULL COMMENT 'concepto en lo que se ingreso',
  `monto` float NOT NULL COMMENT 'lo que costo este ingreso',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'fecha del ingreso',
  `fecha_ingreso` timestamp NOT NULL default '0000-00-00 00:00:00' COMMENT 'Fecha que selecciono el empleado en el sistema',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en la que se hizo el ingreso',
  `id_usuario` int(11) NOT NULL COMMENT 'usuario que registro el ingreso',
  `nota` varchar(512) NOT NULL COMMENT 'nota adicional para complementar la descripcion del ingreso',
  PRIMARY KEY  (`id_ingreso`),
  KEY `usuario_ingreso` (`id_usuario`),
  KEY `sucursal_ingreso` (`id_sucursal`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=203 ;

--
-- Dumping data for table `ingresos`
--

INSERT INTO `ingresos` (`id_ingreso`, `concepto`, `monto`, `fecha`, `fecha_ingreso`, `id_sucursal`, `id_usuario`, `nota`) VALUES
(202, 'PRESTAMO', 50, '2010-12-24 02:31:56', '2010-12-13 06:00:00', 1, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `id_producto` int(11) NOT NULL auto_increment COMMENT 'id del producto',
  `descripcion` varchar(30) collate utf8_unicode_ci NOT NULL COMMENT 'descripcion del producto',
  `precio_intersucursal` float NOT NULL,
  `costo` float NOT NULL,
  `medida` enum('fraccion','unidad') collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `inventario`
--

INSERT INTO `inventario` (`id_producto`, `descripcion`, `precio_intersucursal`, `costo`, `medida`) VALUES
(1, 'papas fritas 330', 7.5, 7.5, 'unidad');

-- --------------------------------------------------------

--
-- Table structure for table `pagos_compra`
--

CREATE TABLE IF NOT EXISTS `pagos_compra` (
  `id_pago` int(11) NOT NULL auto_increment COMMENT 'identificador del pago',
  `id_compra` int(11) NOT NULL COMMENT 'identificador de la compra a la que pagamos',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha en que se abono',
  `monto` float NOT NULL COMMENT 'monto que se abono',
  PRIMARY KEY  (`id_pago`),
  KEY `pagos_compra_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `pagos_compra`
--


-- --------------------------------------------------------

--
-- Table structure for table `pagos_venta`
--

CREATE TABLE IF NOT EXISTS `pagos_venta` (
  `id_pago` int(11) NOT NULL auto_increment COMMENT 'id de pago del cliente',
  `id_venta` int(11) NOT NULL COMMENT 'id de la venta a la que se esta pagando',
  `id_sucursal` int(11) NOT NULL COMMENT 'Donde se realizo el pago',
  `id_usuario` int(11) NOT NULL COMMENT 'Quien cobro este pago',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'Fecha en que se registro el pago',
  `monto` float NOT NULL COMMENT 'total de credito del cliente',
  PRIMARY KEY  (`id_pago`),
  KEY `pagos_venta_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `pagos_venta`
--


-- --------------------------------------------------------

--
-- Table structure for table `productos_proveedor`
--

CREATE TABLE IF NOT EXISTS `productos_proveedor` (
  `id_producto` int(11) NOT NULL auto_increment COMMENT 'identificador del producto',
  `clave_producto` varchar(20) collate utf8_unicode_ci NOT NULL COMMENT 'clave de producto para el proveedor',
  `id_proveedor` int(11) NOT NULL COMMENT 'clave del proveedor',
  `id_inventario` int(11) NOT NULL COMMENT 'clave con la que entra a nuestro inventario',
  `descripcion` varchar(200) collate utf8_unicode_ci NOT NULL COMMENT 'Descripcion del producto que nos vende el proveedor',
  `precio` int(11) NOT NULL COMMENT 'precio al que se compra el producto (sin descuento)',
  PRIMARY KEY  (`id_producto`),
  UNIQUE KEY `clave_producto` (`clave_producto`,`id_proveedor`),
  UNIQUE KEY `id_proveedor` (`id_proveedor`,`id_inventario`),
  KEY `productos_proveedor_proveedor` (`id_proveedor`),
  KEY `productos_proveedor_producto` (`id_inventario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `productos_proveedor`
--


-- --------------------------------------------------------

--
-- Table structure for table `proveedor`
--

CREATE TABLE IF NOT EXISTS `proveedor` (
  `id_proveedor` int(11) NOT NULL auto_increment COMMENT 'identificador del proveedor',
  `rfc` varchar(20) collate utf8_unicode_ci NOT NULL COMMENT 'rfc del proveedor',
  `nombre` varchar(30) collate utf8_unicode_ci NOT NULL COMMENT 'nombre del proveedor',
  `direccion` varchar(100) collate utf8_unicode_ci default NULL COMMENT 'direccion del proveedor',
  `telefono` varchar(20) collate utf8_unicode_ci default NULL COMMENT 'telefono',
  `e_mail` varchar(60) collate utf8_unicode_ci default NULL COMMENT 'email del provedor',
  `activo` tinyint(2) NOT NULL default '1' COMMENT 'Indica si la cuenta esta activada o desactivada',
  PRIMARY KEY  (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `proveedor`
--


-- --------------------------------------------------------

--
-- Table structure for table `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
  `id_sucursal` int(11) NOT NULL auto_increment COMMENT 'Identificador de cada sucursal',
  `gerente` int(11) NOT NULL COMMENT 'Gerente de esta sucursal',
  `descripcion` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'nombre o descripcion de sucursal',
  `direccion` varchar(200) collate utf8_unicode_ci NOT NULL COMMENT 'direccion de la sucursal',
  `rfc` varchar(20) collate utf8_unicode_ci default NULL COMMENT 'El RFC de la sucursal',
  `telefono` varchar(20) collate utf8_unicode_ci default NULL COMMENT 'El telefono de la sucursal',
  `token` varchar(512) collate utf8_unicode_ci default NULL COMMENT 'Token de seguridad para esta sucursal',
  `letras_factura` varchar(10) collate utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL default '1',
  `fecha_apertura` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'Fecha de apertura de esta sucursal',
  `saldo_a_favor` float NOT NULL default '0' COMMENT 'es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras',
  PRIMARY KEY  (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sucursal`
--

INSERT INTO `sucursal` (`id_sucursal`, `gerente`, `descripcion`, `direccion`, `rfc`, `telefono`, `token`, `letras_factura`, `activo`, `fecha_apertura`, `saldo_a_favor`) VALUES
(1, 2, 'Sucursal Arboledas', 'Arboledas 2da Secc, Celaya', '123', '4611741449', '192.168.1.11', 'D', 1, '2010-12-20 01:00:28', 0),
(2, 0, 'asdfasdfsdaf', 'asdfasdfsadf', '234234324', 'asdfasdf', NULL, 'b', 0, '2010-12-25 09:03:09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL auto_increment COMMENT 'identificador del usuario',
  `RFC` varchar(40) collate utf8_unicode_ci NOT NULL COMMENT 'RFC de este usuario',
  `nombre` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'nombre del empleado',
  `contrasena` varchar(128) collate utf8_unicode_ci NOT NULL,
  `id_sucursal` int(11) default NULL COMMENT 'Id de la sucursal a que pertenece',
  `activo` tinyint(1) NOT NULL COMMENT 'Guarda el estado de la cuenta del usuario',
  `finger_token` varchar(1024) collate utf8_unicode_ci default NULL COMMENT 'Una cadena que sera comparada con el token que mande el scanner de huella digital',
  `salario` float NOT NULL COMMENT 'Salario actual',
  `direccion` varchar(512) collate utf8_unicode_ci NOT NULL COMMENT 'Direccion del empleado',
  `telefono` varchar(16) collate utf8_unicode_ci NOT NULL COMMENT 'Telefono del empleado',
  `fecha_inicio` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Fecha cuando este usuario comenzo a laborar',
  PRIMARY KEY  (`id_usuario`),
  KEY `fk_usuario_1` (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `RFC`, `nombre`, `contrasena`, `id_sucursal`, `activo`, `finger_token`, `salario`, `direccion`, `telefono`, `fecha_inicio`) VALUES
(1, '12213324432', 'Alan Gonzalez Hernandez', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 0, '', '', '2010-12-20 00:54:42'),
(2, 'SP4598347597', 'Sancho Pansa', '202cb962ac59075b964b07152d234b70', 1, 1, NULL, 1000, 'Oliva #123', '6149974', '2010-12-20 00:00:00'),
(3, 'AMR3345399', 'ALANISS MORRISETTE', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, NULL, 500, 'IUYTREWQASDFG66', '', '2010-12-22 00:00:00'),
(4, 'asdfasdf', 'asdfasdf', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 1234, 'asdfasdfasdf', 'asdfasdf', '2010-12-25 00:00:00'),
(5, 'sadfasdf', 'asdfasdf', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 32, 'asdfsadfasdf', '234234234', '2010-12-25 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE IF NOT EXISTS `ventas` (
  `id_venta` int(11) NOT NULL auto_increment COMMENT 'id de venta',
  `id_cliente` int(11) NOT NULL COMMENT 'cliente al que se le vendio',
  `tipo_venta` enum('credito','contado') collate utf8_unicode_ci NOT NULL COMMENT 'tipo de venta, contado o credito',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha de venta',
  `subtotal` float default NULL COMMENT 'subtotal de la venta, puede ser nulo',
  `iva` float default NULL COMMENT 'iva agregado por la venta, depende de cada sucursal',
  `descuento` float NOT NULL default '0' COMMENT 'descuento aplicado a esta venta',
  `total` float NOT NULL default '0' COMMENT 'total de esta venta',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal de la venta',
  `id_usuario` int(11) NOT NULL COMMENT 'empleado que lo vendio',
  `pagado` float NOT NULL default '0' COMMENT 'porcentaje pagado de esta venta',
  `ip` varchar(16) collate utf8_unicode_ci NOT NULL default '0.0.0.0' COMMENT 'ip de donde provino esta compra',
  PRIMARY KEY  (`id_venta`),
  KEY `ventas_cliente` (`id_cliente`),
  KEY `ventas_sucursal` (`id_sucursal`),
  KEY `ventas_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=65 ;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `tipo_venta`, `fecha`, `subtotal`, `iva`, `descuento`, `total`, `id_sucursal`, `id_usuario`, `pagado`, `ip`) VALUES
(1, -1, 'contado', '2010-12-20 01:12:37', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(2, -1, 'contado', '2010-12-20 01:14:01', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(3, -1, 'contado', '2010-12-20 01:14:05', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(4, -1, 'contado', '2010-12-20 01:30:50', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(5, -1, 'contado', '2010-12-20 01:32:22', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(6, -1, 'contado', '2010-12-20 01:32:27', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(7, -1, 'contado', '2010-12-20 01:33:08', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(8, -1, 'contado', '2010-12-20 01:33:39', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(9, -1, 'contado', '2010-12-20 01:36:02', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(10, -1, 'contado', '2010-12-20 01:36:08', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(11, -1, 'contado', '2010-12-20 01:36:19', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(12, -1, 'contado', '2010-12-20 01:36:43', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(13, -1, 'contado', '2010-12-20 01:36:51', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(14, -1, 'contado', '2010-12-20 01:39:52', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(15, -1, 'contado', '2010-12-20 01:39:58', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(26, -1, 'contado', '2010-12-20 01:48:50', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(27, -1, 'contado', '2010-12-20 01:48:50', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(28, -1, 'contado', '2010-12-20 01:48:50', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(29, -1, 'contado', '2010-12-20 01:48:50', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(30, -1, 'contado', '2010-12-20 01:49:03', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(31, -1, 'contado', '2010-12-20 01:49:15', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(32, -1, 'contado', '2010-12-20 01:49:57', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(36, -1, 'contado', '2010-12-20 01:50:48', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(37, -1, 'contado', '2010-12-20 01:51:47', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(38, -1, 'contado', '2010-12-20 02:04:11', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(39, -1, 'contado', '2010-12-20 02:04:32', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(40, -1, 'contado', '2010-12-20 02:10:43', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(41, -1, 'contado', '2010-12-20 06:50:34', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(42, -1, 'contado', '2010-12-20 06:50:34', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(43, -1, 'contado', '2010-12-20 06:51:12', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(44, -1, 'contado', '2010-12-20 06:51:21', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.11'),
(45, -1, 'contado', '2010-12-20 06:51:22', 0, NULL, 0, 0, 1, 2, 0, '192.168.1.11'),
(46, 1, 'contado', '2010-12-22 00:12:48', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.3'),
(47, -1, 'contado', '2010-12-22 00:14:50', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.67'),
(48, -1, 'contado', '2010-12-22 01:04:40', 2335, NULL, 0, 2335, 1, 2, 2335, '192.168.1.67'),
(49, -1, 'contado', '2010-12-22 01:04:40', 2335, NULL, 0, 2335, 1, 2, 2335, '192.168.1.3'),
(50, -1, 'contado', '2010-12-22 01:12:56', 2250, NULL, 0, 2250, 1, 3, 2250, '192.168.1.67'),
(51, -1, 'contado', '2010-12-22 01:12:56', 2265, NULL, 0, 2265, 1, 2, 2265, '192.168.1.3'),
(52, -1, 'contado', '2010-12-22 01:15:58', 200, NULL, 0, 200, 1, 2, 200, '192.168.1.3'),
(54, -1, 'contado', '2010-12-22 01:24:44', 5, NULL, 0, 5, 1, 3, 5, '192.168.1.67'),
(55, -1, 'contado', '2010-12-22 01:27:34', 30, NULL, 0, 30, 1, 2, 30, '192.168.1.3'),
(56, -1, 'contado', '2010-12-22 01:42:10', 5, NULL, 0, 5, 1, 3, 5, '192.168.1.67'),
(57, -1, 'contado', '2010-12-22 01:45:44', 25, NULL, 0, 25, 1, 2, 25, '192.168.1.3'),
(58, -1, 'contado', '2010-12-22 01:45:59', 5, NULL, 0, 5, 1, 3, 5, '192.168.1.67'),
(59, 1, 'contado', '2010-12-22 01:47:18', 2965, NULL, 0, 2965, 1, 3, 2965, '192.168.1.67'),
(60, 1, 'contado', '2010-12-22 01:47:18', 5, NULL, 0, 5, 1, 2, 5, '192.168.1.3'),
(61, -1, 'contado', '2010-12-24 03:30:06', 9.45, NULL, 0, 9.45, 1, 2, 9.45, '192.168.1.67'),
(62, -1, 'contado', '2010-12-24 03:31:23', 9.5, NULL, 0, 9.5, 1, 2, 9.5, '192.168.1.67'),
(63, -1, 'contado', '2010-12-24 04:23:01', 9.5, NULL, 0, 9.5, 1, 2, 9.5, '192.168.1.67'),
(64, -1, 'contado', '2010-12-24 04:26:37', 9.5, NULL, 0, 9.5, 1, 2, 9.5, '192.168.1.67');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `compras_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Constraints for table `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON UPDATE CASCADE;

--
-- Constraints for table `detalle_inventario`
--
ALTER TABLE `detalle_inventario`
  ADD CONSTRAINT `detalle_inventario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_inventario_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `factura_compra`
--
ALTER TABLE `factura_compra`
  ADD CONSTRAINT `factura_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE;

--
-- Constraints for table `factura_venta`
--
ALTER TABLE `factura_venta`
  ADD CONSTRAINT `factura_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

--
-- Constraints for table `grupos_usuarios`
--
ALTER TABLE `grupos_usuarios`
  ADD CONSTRAINT `grupos_usuarios_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `grupos_usuarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Constraints for table `pagos_compra`
--
ALTER TABLE `pagos_compra`
  ADD CONSTRAINT `pagos_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE;

--
-- Constraints for table `pagos_venta`
--
ALTER TABLE `pagos_venta`
  ADD CONSTRAINT `pagos_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `productos_proveedor`
--
ALTER TABLE `productos_proveedor`
  ADD CONSTRAINT `productos_proveedor_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_proveedor_ibfk_2` FOREIGN KEY (`id_inventario`) REFERENCES `inventario` (`id_producto`) ON UPDATE CASCADE;

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE;

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

