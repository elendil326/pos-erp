SET FOREIGN_KEY_CHECKS = 0;








-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 14-07-2010 a las 02:11:49
-- Versión del servidor: 5.1.30
-- Versión de PHP: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `pos`
--

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `adeudan_sucursal`
--
DROP VIEW IF EXISTS `adeudan_sucursal`;
CREATE TABLE IF NOT EXISTS `adeudan_sucursal` (
`id_venta` int(11)
,`sucursal` int(11)
,`id_usuario` int(11)
,`Deben` double
,`fecha` datetime
,`porciento` float
);
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
  `descuento` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Taza porcentual de descuento de 0 a 100',
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=54 ;

--
-- Volcar la base de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `rfc`, `nombre`, `direccion`, `telefono`, `e_mail`, `limite_credito`, `descuento`) VALUES
(1, '', 'default', '', NULL, '0', 0, 0),
(24, 'JMHR220486', 'JUAN MANUEL HERNANDEZ REYES', 'CALLEJON SANTO NINO No. 8', '4151858028', 'figu@gmail.com', 10000, 0),
(25, 'GACJUM85', 'Juan Manuel Garcia Carmona', 'Diego Rivera', '4614234', 'mane@name.com', 900, 0),
(28, 'GJHER140203', 'GUADALUPE DE JESUS HERNANDEZ REYES', 'Callejon Santo nino #8', '4151858028', 'chucho@chucho.com', 10000, 0),
(36, 'RFCMIGUEL', 'Don Mike Azul', 'Direccion de Miguel', '46134873', 'donmike_azul@hotmail.com', 4000, 0),
(37, 'LUM86393MD', 'Luis Michel Morales', 'la misma que rene', '4690834', 'luis@mail.com', 5000, 0),
(38, 'RFCRENE', 'CARLOS RENE MICHEL MORALES', 'Direccion de rene', '', '', 10000, 0),
(40, 'ALGONI345436', 'Alejandro Gomez Nicasio', 'Direccion de nick', '4129833', 'corre de nick', 600, 0),
(45, 'RFCDIEGOVEN', 'Diego Ventura', 'Arboledas', '6713456', 'dventuras11@hotmail.com', 2500, 0),
(47, 'RFCALANBOY', 'Alan Gonzalez Hernandez', 'direcion de alan boy', '467865', 'alan.gohe@alanboy.com', 40000, 0),
(48, 'zohanrfc', 'Zohan Villaverde', 'Hidalgo #456', '', 'emailzohan@kll.com', 5000, 0),
(49, 'BEL037842JP', 'BERNARDO LOPEZ', 'Direccion de Bernarado', '43237832', 'brr@fjfd.com', 50000, 0),
(50, 'ZULTRERGHDF', 'ZULEMA TICOMAN', '', '', '', 1000, 0),
(51, 'CUABLCO/58748', 'Cuau Blanco Bravo', '', '', '', 5000, 0),
(52, 'RMONA54344', 'Ramon Ayala', '', '', '', 1000, 0),
(53, 'RILOPZ34543', 'Ricardo Lopez ', 'Insurgentes #49', '43839', 'email@ricardo.com', 7500, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compra`, `id_proveedor`, `tipo_compra`, `fecha`, `subtotal`, `iva`, `sucursal`, `id_usuario`) VALUES
(3, 6, 2, '2010-07-13 16:21:03', 1000, 160, 3, 11),
(4, 5, 2, '2010-07-13 16:21:03', 1000, 160, 2, 8),
(5, 7, 1, '2010-07-13 21:54:24', 788, 230, 2, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `corte`
--

DROP TABLE IF EXISTS `corte`;
CREATE TABLE IF NOT EXISTS `corte` (
  `num_corte` int(11) NOT NULL AUTO_INCREMENT COMMENT 'numero de corte',
  `anio` year(4) NOT NULL COMMENT 'año del corte',
  `inicio` date NOT NULL COMMENT 'año del corte',
  `fin` date NOT NULL COMMENT 'fecha de fin del corte',
  `ventas` float NOT NULL COMMENT 'ventas al contado en ese periodo',
  `abonosVentas` float NOT NULL COMMENT 'pagos de abonos en este periodo',
  `compras` float NOT NULL COMMENT 'compras realizadas en ese periodo',
  `AbonosCompra` float NOT NULL COMMENT 'pagos realizados en ese periodo',
  `gastos` float NOT NULL COMMENT 'gastos echos en ese periodo',
  `ingresos` float NOT NULL COMMENT 'ingresos obtenidos en ese periodo',
  `gananciasNetas` float NOT NULL COMMENT 'ganancias netas dentro del periodo',
  PRIMARY KEY (`num_corte`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Volcar la base de datos para la tabla `corte`
--

INSERT INTO `corte` (`num_corte`, `anio`, `inicio`, `fin`, `ventas`, `abonosVentas`, `compras`, `AbonosCompra`, `gastos`, `ingresos`, `gananciasNetas`) VALUES
(35, 2010, '2010-07-01', '2010-07-30', 4534, 113, 1018, 550, 4410, 4700, 3369);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

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
(37, -100),
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
(50, 0),
(51, 0),
(52, 0),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `cuenta_proveedor`
--

INSERT INTO `cuenta_proveedor` (`id_proveedor`, `saldo`) VALUES
(4, 100),
(5, 300),
(6, 1200),
(7, 1000);

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

INSERT INTO `detalle_compra` (`id_compra`, `id_producto`, `cantidad`, `precio`) VALUES
(3, 6, 100, 5),
(3, 7, 40, 9),
(4, 6, 25, 20),
(4, 9, 50, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_corte`
--

DROP TABLE IF EXISTS `detalle_corte`;
CREATE TABLE IF NOT EXISTS `detalle_corte` (
  `num_corte` int(11) NOT NULL COMMENT 'id del corte al que hace referencia',
  `nombre` varchar(100) NOT NULL COMMENT 'nombre del encargado de sucursal al momento del corte',
  `total` float NOT NULL COMMENT 'total que le corresponde al encargado al momento del corte',
  `deben` float NOT NULL COMMENT 'lo que deben en la sucursal del encargado al momento del corte',
  PRIMARY KEY (`num_corte`,`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `detalle_corte`
--

INSERT INTO `detalle_corte` (`num_corte`, `nombre`, `total`, `deben`) VALUES
(35, 'alan Gonzalez', 1347.6, 748),
(35, 'dventura', 842.25, 0),
(35, 'Juan Manuel Hernandez', 1179.15, 1370);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `detalle_cotizacion`
--

INSERT INTO `detalle_cotizacion` (`id_cotizacion`, `id_producto`, `cantidad`, `precio`) VALUES
(2, 4, 1, 9.5),
(2, 6, 1, 11),
(2, 8, 2, 8);

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
(3, 7, 20, 10),
(3, 8, 50, 11),
(4, 8, 100, 10);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `detalle_inventario`
--

INSERT INTO `detalle_inventario` (`id_producto`, `id_sucursal`, `precio_venta`, `min`, `existencias`) VALUES
(4, 1, 9.5, 1000, 1000),
(4, 2, 10, 200, 1000),
(5, 1, 9, 1000, 1000),
(5, 2, 9.5, 200, 1000),
(6, 1, 8.5, 1000, 1000),
(6, 2, 9, 100, 1000),
(7, 1, 8.5, 100, 1000),
(7, 2, 8.9, 100, 1000),
(8, 1, 8, 50, 1000),
(8, 2, 8, 50, 1000);

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

INSERT INTO `detalle_venta` (`id_venta`, `id_producto`, `cantidad`, `precio`) VALUES
(29, 7, 100, 8),
(29, 8, 10, 20),
(30, 6, 100, 10),
(30, 9, 50, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encargado`
--

DROP TABLE IF EXISTS `encargado`;
CREATE TABLE IF NOT EXISTS `encargado` (
  `id_usuario` int(11) NOT NULL COMMENT 'Este id es el del usuario encargado de su sucursal',
  `porciento` float NOT NULL COMMENT 'este es el porciento de las ventas que le tocan al encargado',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `encargado`
--

INSERT INTO `encargado` (`id_usuario`, `porciento`) VALUES
(6, 25),
(7, 40),
(8, 35);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `factura_compra`
--

INSERT INTO `factura_compra` (`id_factura`, `folio`, `id_compra`) VALUES
(1, '78799', 4),
(2, '10000', 3);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `factura_venta`
--

INSERT INTO `factura_venta` (`id_factura`, `folio`, `id_venta`, `fecha`, `subtotal`, `iva`) VALUES
(3, '10020', 32, '2010-07-13 16:20:25', 100, 16),
(4, '10000', 30, '2010-07-13 16:20:25', 100, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

DROP TABLE IF EXISTS `gastos`;
CREATE TABLE IF NOT EXISTS `gastos` (
  `id_gasto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id para identificar el gasto',
  `concepto` varchar(100) NOT NULL COMMENT 'concepto en lo que se gasto',
  `monto` float NOT NULL COMMENT 'lo que costo este gasto',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha del gasto',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en la que se hizo el gasto',
  `id_usuario` int(11) NOT NULL COMMENT 'usuario que registro el gasto',
  PRIMARY KEY (`id_gasto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcar la base de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id_gasto`, `concepto`, `monto`, `fecha`, `id_sucursal`, `id_usuario`) VALUES
(5, 'compra de bolsas', 100, '2010-07-13 15:56:47', 2, 11),
(6, 'pago a cargador extra', 120, '2010-07-13 15:56:47', 3, 8),
(7, 'pago de luz', 190, '2010-07-13 15:57:20', 1, 10),
(8, 'pago de renta', 4000, '2010-07-13 15:57:20', 1, 7);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `impuesto`
--

INSERT INTO `impuesto` (`id_impuesto`, `descripcion`, `valor`) VALUES
(1, 'iva', 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

DROP TABLE IF EXISTS `ingresos`;
CREATE TABLE IF NOT EXISTS `ingresos` (
  `id_ingreso` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id para identificar el ingreso',
  `concepto` varchar(100) NOT NULL COMMENT 'concepto en lo que se ingreso',
  `monto` float NOT NULL COMMENT 'lo que costo este ingreso',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha del ingreso',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en la que se hizo el ingreso',
  `id_usuario` int(11) NOT NULL COMMENT 'usuario que registro el ingreso',
  PRIMARY KEY (`id_ingreso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Volcar la base de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id_ingreso`, `concepto`, `monto`, `fecha`, `id_sucursal`, `id_usuario`) VALUES
(6, 'Bonificacion por pago cumplido renta central', 1000, '2010-07-13 15:55:24', 2, 11),
(7, 'descuento del proveedor por cantidad', 1500, '2010-07-13 15:55:24', 3, 9),
(8, 'venta de equipo', 1800, '2010-07-13 15:56:00', 1, 6),
(9, 'prestamo de camioneta', 400, '2010-07-13 15:56:00', 1, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

DROP TABLE IF EXISTS `inventario`;
CREATE TABLE IF NOT EXISTS `inventario` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del producto',
  `nombre` varchar(90) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descripcion o nombre del producto',
  `denominacion` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'es lo que se le mostrara a los clientes',
  `unidad_venta` int(11) NOT NULL COMMENT 'id de la unidad por la que se vendera',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Volcar la base de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_producto`, `nombre`, `denominacion`, `unidad_venta`) VALUES
(4, '1as', 'Papa Grande', 1),
(5, '2as', 'Papa Mediana', 1),
(6, '3as', 'Papa Chica', 1),
(7, '4as', 'Papa Morada', 1),
(8, 'Mixtas', 'Papa Surtida', 1),
(9, 'Roñas', 'Papa baja', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_remision`
--

DROP TABLE IF EXISTS `nota_remision`;
CREATE TABLE IF NOT EXISTS `nota_remision` (
  `id_nota` int(11) NOT NULL AUTO_INCREMENT COMMENT 'numero de nota a clienes',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la nota',
  PRIMARY KEY (`id_nota`),
  UNIQUE KEY `id_venta` (`id_venta`),
  KEY `nota_remision_venta` (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Volcar la base de datos para la tabla `nota_remision`
--

INSERT INTO `nota_remision` (`id_nota`, `id_venta`) VALUES
(6, 22),
(8, 24),
(5, 25),
(4, 26),
(3, 27),
(7, 28),
(10, 29),
(2, 30),
(1, 32);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `pagos_compra`
--

INSERT INTO `pagos_compra` (`id_pago`, `id_compra`, `fecha`, `monto`) VALUES
(1, 4, '2010-07-13', 50),
(2, 3, '2010-07-13', 500);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=67 ;

--
-- Volcar la base de datos para la tabla `pagos_venta`
--

INSERT INTO `pagos_venta` (`id_pago`, `id_venta`, `fecha`, `monto`) VALUES
(63, 23, '2010-07-13', 13),
(64, 23, '2010-07-13', 50),
(65, 27, '2010-07-13', 50),
(66, 25, '2010-07-14', 100);

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
  `precio` float NOT NULL COMMENT 'precio al que se compra el producto (sin descuento)',
  PRIMARY KEY (`id_producto`),
  UNIQUE KEY `clave_producto` (`clave_producto`,`id_proveedor`),
  UNIQUE KEY `id_proveedor` (`id_proveedor`,`id_inventario`),
  KEY `productos_proveedor_proveedor` (`id_proveedor`),
  KEY `productos_proveedor_producto` (`id_inventario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

--
-- Volcar la base de datos para la tabla `productos_proveedor`
--

INSERT INTO `productos_proveedor` (`id_producto`, `clave_producto`, `id_proveedor`, `id_inventario`, `descripcion`, `precio`) VALUES
(14, 'primsp', 7, 9, 'papas de primer nivel', 10),
(15, 'ppmed', 6, 7, 'papas buenas', 1),
(16, 'prod3prov1', 4, 6, 'papas normales', 7),
(17, 'prod4prov1', 4, 7, 'desperdicio', 2),
(18, 'pmas', 5, 4, 'papas gigantes', 12),
(19, 'pdas', 5, 5, 'papas grandes', 10),
(20, 'ptras', 5, 6, 'papas normales', 8),
(21, 'pctas', 5, 7, 'papas de colores', 8),
(24, 'pqtas', 5, 8, 'papas medio feas', 6),
(25, 'pstas', 5, 9, 'papas feas', 3);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Volcar la base de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `rfc`, `nombre`, `direccion`, `telefono`, `e_mail`) VALUES
(4, 'PPDLBJIO', 'PAPAS DEL BAJIO', 'DIRECCION DE LA MORENA', '461456789', 'morena_sales@morena.com'),
(5, 'CDASPPS', 'MICHOACAN VERDURAS SA. de C.V.', 'DIREC', '4555555', 'mail@mail.com'),
(6, 'MCHISRFC', 'Sembradios Los Mochis', 'Los Mochis Sinaloa', '45558474', 'sales@sembradios.com'),
(7, 'SRTRADGO', 'SURTIDORA DURANGO', 'Avenida Insurgente #466 Colonia Centro', '', 'mail');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id_sucursal`, `descripcion`, `direccion`) VALUES
(1, 'Bodega', 'Central de Abastos de Celaya Salida Apaseo'),
(2, 'Sucursal de la Central', 'Central de Abastos Celaya'),
(3, 'Sucursal 2', 'Rancho Nuevo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_venta`
--

DROP TABLE IF EXISTS `unidad_venta`;
CREATE TABLE IF NOT EXISTS `unidad_venta` (
  `id_unidad` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la unidad que venderemos',
  `descripcion` varchar(50) NOT NULL COMMENT 'si es kg, metros, pieza, m2, litros, etc.',
  `entero` tinyint(1) NOT NULL COMMENT 'indica si la unidad semaneja por enteros, de lo contrario maneja punto',
  PRIMARY KEY (`id_unidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `unidad_venta`
--

INSERT INTO `unidad_venta` (`id_unidad`, `descripcion`, `entero`) VALUES
(1, 'kilogramo', 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Volcar la base de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `usuario`, `contrasena`, `nivel`, `sucursal_id`) VALUES
(6, 'Diego Ventura', 'dventura', '123', 1, 1),
(7, 'alan Gonzalez', 'alanboy', '123', 1, 2),
(8, 'Juan Manuel Hernandez', 'figu', '123', 1, 3),
(9, 'juan manuel garcia', 'mane', '123', 2, 1),
(10, 'Rene Michel', 'rene', '123', 2, 2),
(11, 'luis michel', 'luis', '123', 2, 3);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34 ;

--
-- Volcar la base de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `tipo_venta`, `fecha`, `subtotal`, `iva`, `sucursal`, `id_usuario`) VALUES
(22, 49, 1, '2010-07-13 16:03:37', 100, 160, 2, 11),
(23, 48, 2, '2010-07-13 16:03:37', 100, 15, 2, 7),
(24, 25, 1, '2010-07-13 16:04:07', 300, 48, 3, 11),
(25, 37, 2, '2010-07-13 16:04:07', 1000, 160, 3, 6),
(26, 1, 1, '2010-07-13 16:04:33', 300, 16, 1, 8),
(27, 48, 2, '2010-07-13 16:04:33', 100, 160, 3, 6),
(28, 40, 1, '2010-07-13 16:05:05', 12, 2, 3, 8),
(29, 40, 2, '2010-07-13 16:05:05', 100, 16, 2, 7),
(30, 47, 1, '2010-07-13 16:05:46', 100, 16, 2, 8),
(31, 28, 2, '2010-07-13 16:05:46', 500, 80, 2, 9),
(32, 38, 1, '2010-07-13 16:06:24', 2000, 320, 3, 9),
(33, 48, 1, '2010-07-13 16:06:24', 1000, 160, 1, 10);

-- --------------------------------------------------------

--
-- Estructura para la vista `adeudan_sucursal`
--
DROP TABLE IF EXISTS `adeudan_sucursal`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `adeudan_sucursal` AS select `v`.`id_venta` AS `id_venta`,`v`.`sucursal` AS `sucursal`,`e`.`id_usuario` AS `id_usuario`,if((((`v`.`subtotal` + `v`.`iva`) - sum(`pv`.`monto`)) > 0),((`v`.`subtotal` + `v`.`iva`) - sum(`pv`.`monto`)),(`v`.`subtotal` + `v`.`iva`)) AS `Deben`,if((max(`pv`.`fecha`) <> NULL),max(`pv`.`fecha`),`v`.`fecha`) AS `fecha`,`e`.`porciento` AS `porciento` from (`encargado` `e` left join ((`ventas` `v` left join `pagos_venta` `pv` on((`pv`.`id_venta` = `v`.`id_venta`))) left join `usuario` `u` on((`u`.`sucursal_id` = `v`.`sucursal`))) on((`u`.`id_usuario` = `e`.`id_usuario`))) where (`v`.`tipo_venta` = 2) group by `v`.`tipo_venta`,`v`.`sucursal`,`v`.`id_venta`,`e`.`id_usuario`;

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compras_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cuenta_proveedor`
--
ALTER TABLE `cuenta_proveedor`
  ADD CONSTRAINT `cuenta_proveedor_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`);

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









SET FOREIGN_KEY_CHECKS = 1;
