-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 06-07-2010 a las 02:56:02
-- Versión del servidor: 5.1.30
-- Versión de PHP: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `pos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del cliente',
  `rfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'rfc del cliente si es que tiene',
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre del cliente',
  `direccion` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'domicilio del cliente calle, no, colonia',
  `telefono` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Telefono del cliete',
  `e_mail` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'dias de credito para que pague el cliente',
  `limite_credito` float NOT NULL DEFAULT '0' COMMENT 'Limite de credito otorgado al cliente',
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=54 ;

--
-- Volcar la base de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `rfc`, `nombre`, `direccion`, `telefono`, `e_mail`, `limite_credito`) VALUES
(24, 'JMHR220486', 'JUAN MANUEL HERNANDEZ REYES', 'CALLEJON SANTO NINO No. 8', '4151858028', 'figu@gmail.com', 10000),
(25, 'GACJUM85', 'Juan Manuel Garcia Carmona', 'Diego Rivera', '4614234', 'mane@name.com', 900),
(28, 'GJHER140203', 'GUADALUPE DE JESUS HERNANDEZ REYES', 'Callejon Santo nino #8', '4151858028', 'chucho@chucho.com', 10000),
(36, 'RFCMIGUEL', 'Don Mike Azul', 'Direccion de Miguel', '46134873', 'donmike_azul@hotmail.com', 4000),
(37, 'LUM86393MD', 'Luis Michel Morales', 'la misma que rene', '4690834', 'luis@mail.com', 5000),
(38, 'RFCRENE', 'CARLOS RENE MICHEL MORALES', 'Direccion de rene', '', '', 10000),
(40, 'ALGONI345436', 'Alejandro Gomez Nicasio', 'Direccion de nick', '4129833', 'corre de nick', 600),
(45, 'RFCDIEGOVEN', 'Diego Ventura', 'Arboledas', '6713456', 'dventuras11@hotmail.com', 2500),
(47, 'RFCALANBOY', 'Alan Gonzalez Hernandez', 'direcion de alan boy', '467865', 'alan.gohe@alanboy.com', 40000),
(48, 'zohanrfc', 'Zohan Villaverde', 'Hidalgo #456', '', 'emailzohan@kll.com', 5000),
(49, 'BEL037842JP', 'BERNARDO LOPEZ', 'Direccion de Bernarado', '43237832', 'brr@fjfd.com', 50000),
(50, 'ZULTRERGHDF', 'ZULEMA TICOMAN', '', '', '', 1000),
(51, 'CUABLCO/58748', 'Cuau Blanco Bravo', '', '', '', 5000),
(52, 'RMONA54344', 'Ramon Ayala', '', '', '', 1000),
(53, 'RILOPZ34543', 'Ricardo Lopez ', 'Insurgentes #49', '43839', 'email@ricardo.com', 7500);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

DROP TABLE IF EXISTS `compras`;
CREATE TABLE IF NOT EXISTS `compras` (
  `id_compra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la compra',
  `id_proveedor` int(11) NOT NULL COMMENT 'PROVEEDOR AL QUE SE LE COMPRO',
  `tipo_compra` int(11) NOT NULL COMMENT 'tipo de compra, contado o credito',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de compra',
  `subtotal` float NOT NULL COMMENT 'subtotal de compra',
  `iva` float NOT NULL COMMENT 'iva de la compra',
  `sucursal` int(11) NOT NULL COMMENT 'sucursal en que se compro',
  `id_usuario` int(11) NOT NULL COMMENT 'quien realizo la compra',
  PRIMARY KEY (`id_compra`),
  KEY `compras_proveedor` (`id_proveedor`),
  KEY `compras_sucursal` (`sucursal`),
  KEY `compras_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `compras`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

DROP TABLE IF EXISTS `cotizacion`;
CREATE TABLE IF NOT EXISTS `cotizacion` (
  `id_cotizacion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la cotizacion',
  `id_cliente` int(11) NOT NULL COMMENT 'id del cliente',
  `fecha` date NOT NULL COMMENT 'fecha de cotizacion',
  `subtotal` float NOT NULL COMMENT 'subtotal de la cotizacion',
  `iva` float NOT NULL COMMENT 'iva sobre el subtotal',
  PRIMARY KEY (`id_cotizacion`),
  KEY `cotizacion_cliente` (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `cotizacion`
--

INSERT INTO `cotizacion` (`id_cotizacion`, `id_cliente`, `fecha`, `subtotal`, `iva`) VALUES
(2, 34, '2010-06-18', 36.5, 5.84);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_cliente`
--

DROP TABLE IF EXISTS `cuenta_cliente`;
CREATE TABLE IF NOT EXISTS `cuenta_cliente` (
  `id_cliente` int(11) NOT NULL COMMENT 'id del cliente',
  `saldo` int(11) NOT NULL COMMENT 'saldo del cliente',
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `cuenta_cliente`
--

INSERT INTO `cuenta_cliente` (`id_cliente`, `saldo`) VALUES
(24, 0),
(25, 0),
(28, 0),
(36, 0),
(37, 0),
(38, 0),
(40, 0),
(41, 0),
(42, 0),
(43, 0),
(44, 0),
(45, 0),
(46, 0),
(47, 30),
(48, -100),
(49, 0),
(50, -260),
(51, 0),
(52, -50),
(53, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_proveedor`
--

DROP TABLE IF EXISTS `cuenta_proveedor`;
CREATE TABLE IF NOT EXISTS `cuenta_proveedor` (
  `id_proveedor` int(11) NOT NULL COMMENT 'id del proveedor al que le debemos',
  `saldo` float NOT NULL COMMENT 'cantidad que adeudamos al proveedor',
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `cuenta_proveedor`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

DROP TABLE IF EXISTS `detalle_compra`;
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
-- Estructura de tabla para la tabla `detalle_cotizacion`
--

DROP TABLE IF EXISTS `detalle_cotizacion`;
CREATE TABLE IF NOT EXISTS `detalle_cotizacion` (
  `id_cotizacion` int(11) NOT NULL COMMENT 'id de la cotizacion',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `cantidad` float NOT NULL COMMENT 'cantidad cotizado',
  `precio` float NOT NULL COMMENT 'precio al que cotizo el producto',
  PRIMARY KEY (`id_cotizacion`,`id_producto`),
  KEY `detalle_cotizacion_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `detalle_cotizacion`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

DROP TABLE IF EXISTS `detalle_factura`;
CREATE TABLE IF NOT EXISTS `detalle_factura` (
  `id_factura` int(11) NOT NULL COMMENT 'factura a que se referencia',
  `id_producto` int(11) NOT NULL COMMENT 'producto de la factura',
  `cantidad` float NOT NULL COMMENT 'cantidad que se vendio',
  `precio` float NOT NULL COMMENT 'precio al que se vendio',
  PRIMARY KEY (`id_factura`,`id_producto`),
  KEY `detalle_factura_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `detalle_factura`
--

INSERT INTO `detalle_factura` (`id_factura`, `id_producto`, `cantidad`, `precio`) VALUES
(1, 6, 20, 19),
(1, 7, 9, 19),
(1, 8, 9, 19),
(2, 8, 9, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_inventario`
--

DROP TABLE IF EXISTS `detalle_inventario`;
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
(6, 3, 10, 0, 80),
(7, 3, 13, 100, 1234),
(8, 2, 18, 100, 1000),
(8, 3, 16, 100, 1000),
(9, 2, 14, 100, 198),
(9, 3, 14, 100, 1300);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

DROP TABLE IF EXISTS `detalle_venta`;
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


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_compra`
--

DROP TABLE IF EXISTS `factura_compra`;
CREATE TABLE IF NOT EXISTS `factura_compra` (
  `id_factura` int(20) NOT NULL AUTO_INCREMENT COMMENT 'NUMERO DE FACTURA',
  `folio` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `id_compra` int(11) NOT NULL COMMENT 'COMPRA A LA QUE CORRESPONDE LA FACTURA',
  PRIMARY KEY (`id_factura`),
  KEY `factura_compra_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `factura_compra`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_venta`
--

DROP TABLE IF EXISTS `factura_venta`;
CREATE TABLE IF NOT EXISTS `factura_venta` (
  `id_factura` int(20) NOT NULL AUTO_INCREMENT COMMENT 'Numero de factura al cliente',
  `folio` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'folio que tiene la factura',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la factura',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha en que se facturo',
  `subtotal` float NOT NULL COMMENT 'subtotal de los productos facturados',
  `iva` float NOT NULL COMMENT 'iva de los productos facturados',
  PRIMARY KEY (`id_factura`),
  KEY `factura_venta_venta` (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `factura_venta`
--

INSERT INTO `factura_venta` (`id_factura`, `folio`, `id_venta`, `fecha`, `subtotal`, `iva`) VALUES
(1, 'folio-19', 11, '2010-07-06 00:55:19', 50, 9),
(2, 'folio-1', 10, '2010-07-06 00:54:59', 50, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto`
--

DROP TABLE IF EXISTS `impuesto`;
CREATE TABLE IF NOT EXISTS `impuesto` (
  `id_impuesto` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `valor` int(11) NOT NULL,
  PRIMARY KEY (`id_impuesto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `impuesto`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

DROP TABLE IF EXISTS `inventario`;
CREATE TABLE IF NOT EXISTS `inventario` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del producto',
  `nombre` varchar(90) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descripcion o nombre del producto',
  `denominacion` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'es lo que se le mostrara a los clientes',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Volcar la base de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_producto`, `nombre`, `denominacion`) VALUES
(4, '1as', 'Papa Grande'),
(5, '2as', 'Papa Mediana'),
(6, '3as', 'Papa Chica'),
(7, '4as', 'Papa Morada'),
(8, 'Mixtas', 'Papa Surtida'),
(9, 'Roñas', 'Papa baja');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_remision`
--

DROP TABLE IF EXISTS `nota_remision`;
CREATE TABLE IF NOT EXISTS `nota_remision` (
  `id_nota` int(11) NOT NULL AUTO_INCREMENT COMMENT 'numero de nota a clienes',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la nota',
  PRIMARY KEY (`id_nota`),
  KEY `nota_remision_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `nota_remision`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_compra`
--

DROP TABLE IF EXISTS `pagos_compra`;
CREATE TABLE IF NOT EXISTS `pagos_compra` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del pago',
  `id_compra` int(11) NOT NULL COMMENT 'identificador de la compra a la que pagamos',
  `fecha` date NOT NULL COMMENT 'fecha en que se abono',
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

DROP TABLE IF EXISTS `pagos_venta`;
CREATE TABLE IF NOT EXISTS `pagos_venta` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de pago del cliente',
  `id_venta` int(11) NOT NULL COMMENT 'id de la venta a la que se esta pagando',
  `fecha` date NOT NULL COMMENT 'fecha de pago',
  `monto` float NOT NULL COMMENT 'total de credito del cliente',
  PRIMARY KEY (`id_pago`),
  KEY `pagos_venta_venta` (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=59 ;

--
-- Volcar la base de datos para la tabla `pagos_venta`
--

INSERT INTO `pagos_venta` (`id_pago`, `id_venta`, `fecha`, `monto`) VALUES
(56, 10, '2010-07-05', 100),
(57, 12, '2010-07-05', 50),
(58, 11, '2010-07-05', 260);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_proveedor`
--

DROP TABLE IF EXISTS `productos_proveedor`;
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Volcar la base de datos para la tabla `productos_proveedor`
--

INSERT INTO `productos_proveedor` (`id_producto`, `clave_producto`, `id_proveedor`, `id_inventario`, `descripcion`, `precio`) VALUES
(6, 'primsp', 9, 8, 'papas de primer nivel', 10),
(7, 'segsp', 9, 9, 'papas casi perfectas', 8),
(8, 'tersp', 9, 7, 'papas normales', 5),
(9, 'podsp', 9, 6, 'papas enlamadas', 2),
(10, 'pppre', 8, 8, 'papas premier', 11),
(11, 'ppmed', 8, 9, 'papas buenas', 8),
(12, 'ppnor', 8, 7, 'papas normalitas', 6),
(13, 'ppfea', 8, 6, 'papa en descompocision', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

DROP TABLE IF EXISTS `proveedor`;
CREATE TABLE IF NOT EXISTS `proveedor` (
  `id_proveedor` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del proveedor',
  `rfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'rfc del proveedor',
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre del proveedor',
  `direccion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'direccion del proveedor',
  `telefono` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'telefono',
  `e_mail` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'email del provedor',
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Volcar la base de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `rfc`, `nombre`, `direccion`, `telefono`, `e_mail`) VALUES
(8, 'papas1', 'proveedor papas', 'direccion del proveedor numero 1', '9897898', 'hkjhjk@kkhjh.kjhjkh'),
(9, 'mdud9897', 'sabritas costaleras', 'hkjhkj', '76787', 'hjkhkjh@jhkhkj.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

DROP TABLE IF EXISTS `sucursal`;
CREATE TABLE IF NOT EXISTS `sucursal` (
  `id_sucursal` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de cada sucursal',
  `descripcion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'nombre o descripcion de sucursal',
  `direccion` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'direccion de la sucursal',
  PRIMARY KEY (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id_sucursal`, `descripcion`, `direccion`) VALUES
(1, 'Bodega', 'Central de Abastos de Celaya Salida Apaseo'),
(2, 'Sucursal de la Central', 'Central de Abastos Celaya'),
(3, 'Sucursal 2', 'Rancho Nuevo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del usuario',
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre del empleado',
  `usuario` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contrasena` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nivel` int(11) NOT NULL,
  `sucursal_id` int(11) NOT NULL COMMENT 'Id de la sucursal a que pertenece',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `usuario`, `contrasena`, `nivel`, `sucursal_id`) VALUES
(4, 'Alan Gonzalez Hernandez', 'alanboy', '123', 1, 1),
(5, 'diego ventura', 'dventura', '123', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

DROP TABLE IF EXISTS `ventas`;
CREATE TABLE IF NOT EXISTS `ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de venta',
  `id_cliente` int(11) NOT NULL COMMENT 'cliente al que se le vendio',
  `tipo_venta` int(11) NOT NULL COMMENT 'tipo de venta, contado o credito',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de venta',
  `subtotal` float NOT NULL COMMENT 'subtotal de la venta',
  `iva` float NOT NULL COMMENT 'iva agregado por la venta',
  `sucursal` int(11) NOT NULL COMMENT 'sucursal de la venta',
  `id_usuario` int(11) NOT NULL COMMENT 'empleado que lo vendio',
  PRIMARY KEY (`id_venta`),
  KEY `ventas_cliente` (`id_cliente`),
  KEY `ventas_sucursal` (`sucursal`),
  KEY `ventas_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Volcar la base de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `tipo_venta`, `fecha`, `subtotal`, `iva`, `sucursal`, `id_usuario`) VALUES
(9, 49, 1, '2010-07-05 22:59:19', 100, 150, 3, 5),
(10, 48, 2, '2010-07-05 22:59:19', 1000, 160, 2, 5),
(11, 50, 2, '2010-07-05 22:59:19', 100, 160, 1, 4),
(12, 52, 2, '2010-07-05 22:59:19', 100, 160, 2, 4),
(13, 25, 2, '2010-07-05 22:59:19', 200, 32, 2, 4),
(14, 48, 2, '2010-07-06 01:49:27', 300, 48, 2, 4);

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_cotizacion`
--
ALTER TABLE `detalle_cotizacion`
  ADD CONSTRAINT `detalle_cotizacion_ibfk_1` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_cotizacion_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `detalle_factura_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura_venta` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_factura_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_inventario`
--
ALTER TABLE `detalle_inventario`
  ADD CONSTRAINT `detalle_inventario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_inventario_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
  ADD CONSTRAINT `factura_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura_venta`
--
ALTER TABLE `factura_venta`
  ADD CONSTRAINT `factura_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `nota_remision`
--
ALTER TABLE `nota_remision`
  ADD CONSTRAINT `nota_remision_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_compra`
--
ALTER TABLE `pagos_compra`
  ADD CONSTRAINT `pagos_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
  ADD CONSTRAINT `pagos_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_proveedor`
--
ALTER TABLE `productos_proveedor`
  ADD CONSTRAINT `productos_proveedor_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_proveedor_ibfk_2` FOREIGN KEY (`id_inventario`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `mi_proc`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `mi_proc`(venta INT)
SET @id_venta = venta$$

DELIMITER ;
