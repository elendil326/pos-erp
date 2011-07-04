-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 04-07-2011 a las 15:55:54
-- Versión del servidor: 5.1.37
-- Versión de PHP: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `i1`
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
  `precio_venta_procesado` float NOT NULL,
  `precio_intersucursal` float NOT NULL,
  `precio_intersucursal_procesado` float NOT NULL,
  `precio_compra` float NOT NULL DEFAULT '0' COMMENT 'precio al que se le compra al publico este producto en caso de ser POS_COMPRA_A_CLIENTES',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_actualizacion`),
  KEY `id_producto` (`id_producto`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Actualizaciones de precios' AUTO_INCREMENT=192 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autorizacion`
--

CREATE TABLE IF NOT EXISTS `autorizacion` (
  `id_autorizacion` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL COMMENT 'Usuario que solicito esta autorizacion',
  `id_sucursal` int(11) NOT NULL COMMENT 'Sucursal de donde proviene esta autorizacion',
  `fecha_peticion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha cuando se genero esta autorizacion',
  `fecha_respuesta` timestamp NULL DEFAULT NULL COMMENT 'Fecha de cuando se resolvio esta aclaracion',
  `estado` int(11) NOT NULL COMMENT 'Estado actual de esta aclaracion',
  `parametros` varchar(2048) NOT NULL COMMENT 'Parametros en formato JSON que describen esta autorizacion',
  `tipo` enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto') NOT NULL COMMENT 'El tipo de autorizacion',
  PRIMARY KEY (`id_autorizacion`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=463 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del cliente',
  `rfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'rfc del cliente si es que tiene',
  `razon_social` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'razon social del cliente',
  `calle` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'calle del domicilio fiscal del cliente',
  `numero_exterior` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'numero exteriror del domicilio fiscal del cliente',
  `numero_interior` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'numero interior del domicilio fiscal del cliente',
  `colonia` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'colonia del domicilio fiscal del cliente',
  `referencia` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'referencia del domicilio fiscal del cliente',
  `localidad` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Localidad del domicilio fiscal',
  `municipio` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Municipio de este cliente',
  `estado` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Estado del domicilio fiscal del cliente',
  `pais` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Pais del domicilio fiscal del cliente',
  `codigo_postal` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Codigo postal del domicilio fiscal del cliente',
  `telefono` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Telefono del cliete',
  `e_mail` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'dias de credito para que pague el cliente',
  `limite_credito` float NOT NULL DEFAULT '0' COMMENT 'Limite de credito otorgado al cliente',
  `descuento` float NOT NULL DEFAULT '0' COMMENT 'Taza porcentual de descuento de 0.0 a 100.0',
  `activo` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'Indica si la cuenta esta activada o desactivada',
  `id_usuario` int(11) NOT NULL COMMENT 'Identificador del usuario que dio de alta a este cliente',
  `id_sucursal` int(11) NOT NULL COMMENT 'Identificador de la sucursal donde se dio de alta este cliente',
  `fecha_ingreso` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha cuando este cliente se registro en una sucursal',
  `password` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'el pass para que este cliente entre a descargar sus facturas',
  `last_login` timestamp NULL DEFAULT NULL,
  `grant_changes` tinyint(1) DEFAULT '0' COMMENT 'verdadero cuando el cliente ha cambiado su contrasena y puede hacer cosas',
  PRIMARY KEY (`id_cliente`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_cliente`
--

CREATE TABLE IF NOT EXISTS `compra_cliente` (
  `id_compra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de compra',
  `id_cliente` int(11) NOT NULL COMMENT 'cliente al que se le compro',
  `tipo_compra` enum('credito','contado') COLLATE utf8_unicode_ci NOT NULL COMMENT 'tipo de compra, contado o credito',
  `tipo_pago` enum('efectivo','cheque','tarjeta') COLLATE utf8_unicode_ci DEFAULT 'efectivo' COMMENT 'tipo de pago para esta compra en caso de ser a contado',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de compra',
  `subtotal` float DEFAULT NULL COMMENT 'subtotal de la compra, puede ser nulo',
  `impuesto` float DEFAULT '0' COMMENT 'impuesto agregado por la compra, depende de cada sucursal',
  `descuento` float NOT NULL DEFAULT '0' COMMENT 'descuento aplicado a esta compra',
  `total` float NOT NULL DEFAULT '0' COMMENT 'total de esta compra',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal de la compra',
  `id_usuario` int(11) NOT NULL COMMENT 'empleado que lo vendio',
  `pagado` float NOT NULL DEFAULT '0' COMMENT 'porcentaje pagado de esta compra',
  `cancelada` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'verdadero si esta compra ha sido cancelada, falso si no',
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT 'ip de donde provino esta compra',
  `liquidada` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Verdadero si esta compra ha sido liquidada, falso si hay un saldo pendiente',
  PRIMARY KEY (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra_cliente`
--

CREATE TABLE IF NOT EXISTS `detalle_compra_cliente` (
  `id_compra` int(11) NOT NULL COMMENT 'compra a que se referencia',
  `id_producto` int(11) NOT NULL COMMENT 'producto de la compra',
  `cantidad` float NOT NULL COMMENT 'cantidad que se compro',
  `precio` float NOT NULL COMMENT 'precio al que se compro',
  `descuento` float unsigned DEFAULT '0' COMMENT 'indica cuanto producto original se va a descontar de ese producto en esa compra',
  PRIMARY KEY (`id_compra`,`id_producto`),
  KEY `detalle_compra_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_inventario`
--

CREATE TABLE IF NOT EXISTS `detalle_inventario` (
  `id_producto` int(11) NOT NULL COMMENT 'identificador del proudcto en inventario',
  `id_sucursal` int(11) NOT NULL COMMENT 'id de la sucursal',
  `precio_venta` float NOT NULL COMMENT 'precio al que se vendera al publico',
  `precio_venta_procesado` float NOT NULL DEFAULT '0' COMMENT 'cuando este producto tiene tratamiento este sera su precio de venta al estar procesado',
  `existencias` float NOT NULL DEFAULT '0' COMMENT 'cantidad de producto que hay actualmente en almacen de esta sucursal (originales+procesadas)',
  `existencias_procesadas` float NOT NULL COMMENT 'cantidad de producto solamente procesado !',
  `precio_compra` float NOT NULL DEFAULT '0' COMMENT 'El precio de compra para este producto en esta sucursal, siempre y cuando este punto de venta tenga el modulo POS_COMPRA_A_CLIENTES',
  PRIMARY KEY (`id_producto`,`id_sucursal`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE IF NOT EXISTS `detalle_venta` (
  `id_venta` int(11) NOT NULL COMMENT 'venta a que se referencia',
  `id_producto` int(11) NOT NULL COMMENT 'producto de la venta',
  `cantidad` float NOT NULL COMMENT 'cantidad que se vendio',
  `cantidad_procesada` float NOT NULL,
  `precio` float NOT NULL COMMENT 'precio al que se vendio',
  `precio_procesada` float NOT NULL COMMENT 'el precio de los articulos procesados en esta venta',
  `descuento` float unsigned DEFAULT '0' COMMENT 'indica cuanto producto original se va a descontar de ese producto en esa venta',
  PRIMARY KEY (`id_venta`,`id_producto`),
  KEY `detalle_venta_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE IF NOT EXISTS `equipo` (
  `id_equipo` int(6) NOT NULL AUTO_INCREMENT COMMENT 'el identificador de este equipo',
  `token` varchar(128) DEFAULT NULL COMMENT 'el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado',
  `full_ua` varchar(256) NOT NULL COMMENT 'String de user-agent para este cliente',
  `descripcion` varchar(254) NOT NULL COMMENT 'descripcion de este equipo',
  `locked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'si este equipo esta lockeado para prevenir los cambios',
  PRIMARY KEY (`id_equipo`),
  UNIQUE KEY `full_ua` (`full_ua`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_venta`
--

CREATE TABLE IF NOT EXISTS `factura_venta` (
  `id_folio` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'folio que tiene la factura',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la factura',
  `id_usuario` int(10) NOT NULL COMMENT 'Id del usuario que hiso al ultima modificacion a la factura',
  `xml` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'xml en bruto',
  `lugar_emision` int(11) NOT NULL COMMENT 'id de la sucursal donde se emitio la factura',
  `tipo_comprobante` enum('ingreso','egreso') COLLATE utf8_unicode_ci NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada',
  `sellada` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indica si el WS ha timbrado la factura',
  `forma_pago` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_emision` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version_tfd` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `folio_fiscal` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_certificacion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `numero_certificado_sat` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `sello_digital_emisor` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `sello_digital_sat` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `cadena_original` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_folio`),
  KEY `factura_venta_venta` (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

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
  PRIMARY KEY (`id_gasto`),
  KEY `id_sucursal` (`id_sucursal`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impresora`
--

CREATE TABLE IF NOT EXISTS `impresora` (
  `id_impresora` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la impresora',
  `id_sucursal` int(11) NOT NULL COMMENT 'id de la sucursal donde se encuentra esta impresora',
  `descripcion` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT 'descripcion breve de la impresora',
  `identificador` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'es el nombre de como esta dada de alta la impresora en la sucursal',
  PRIMARY KEY (`id_impresora`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Contiene informaci?n acerca de todas las impresoras de todas' AUTO_INCREMENT=3 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del producto',
  `descripcion` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'descripcion del producto',
  `escala` enum('kilogramo','pieza','litro','unidad') COLLATE utf8_unicode_ci NOT NULL,
  `tratamiento` enum('limpia') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tipo de tratatiento si es que existe para este producto.',
  `agrupacion` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'La agrupacion de este producto',
  `agrupacionTam` float DEFAULT NULL COMMENT 'El tamano de cada agrupacion',
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'si este producto esta activo o no en el sistema',
  `precio_por_agrupacion` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

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
  `tipo_pago` enum('efectivo','cheque','tarjeta') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'efectivo' COMMENT 'tipo de pago para este abono',
  PRIMARY KEY (`id_pago`),
  KEY `pagos_venta_venta` (`id_venta`),
  KEY `id_sucursal` (`id_sucursal`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=444 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
  `id_sucursal` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de cada sucursal',
  `gerente` int(11) DEFAULT NULL COMMENT 'Gerente de esta sucursal',
  `descripcion` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre o descripcion de sucursal',
  `razon_social` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'razon social de la sucursal',
  `rfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'El RFC de la sucursal',
  `calle` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'calle del domicilio fiscal',
  `numero_exterior` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nuemro exterior del domicilio fiscal',
  `numero_interior` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'numero interior del domicilio fiscal',
  `colonia` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'colonia del domicilio fiscal',
  `localidad` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'localidad del domicilio fiscal',
  `referencia` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'referencia del domicilio fiscal',
  `municipio` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'municipio del domicilio fiscal',
  `estado` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'estado del domicilio fiscal',
  `pais` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pais del domicilio fiscal',
  `codigo_postal` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'codigo postal del domicilio fiscal',
  `telefono` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'El telefono de la sucursal',
  `token` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Token de seguridad para esta sucursal',
  `letras_factura` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_apertura` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de apertura de esta sucursal',
  `saldo_a_favor` float NOT NULL DEFAULT '0' COMMENT 'es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras',
  PRIMARY KEY (`id_sucursal`),
  UNIQUE KEY `letras_factura` (`letras_factura`),
  KEY `gerente` (`gerente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE IF NOT EXISTS `ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de venta',
  `id_venta_equipo` int(11) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `id_cliente` int(11) NOT NULL COMMENT 'cliente al que se le vendio',
  `tipo_venta` enum('credito','contado') COLLATE utf8_unicode_ci NOT NULL COMMENT 'tipo de venta, contado o credito',
  `tipo_pago` enum('efectivo','cheque','tarjeta') COLLATE utf8_unicode_ci DEFAULT 'efectivo' COMMENT 'tipo de pago para esta venta en caso de ser a contado',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de venta',
  `subtotal` float DEFAULT NULL COMMENT 'subtotal de la venta, puede ser nulo',
  `iva` float DEFAULT '0' COMMENT 'iva agregado por la venta, depende de cada sucursal',
  `descuento` float NOT NULL DEFAULT '0' COMMENT 'descuento aplicado a esta venta',
  `total` float NOT NULL DEFAULT '0' COMMENT 'total de esta venta',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal de la venta',
  `id_usuario` int(11) NOT NULL COMMENT 'empleado que lo vendio',
  `pagado` float NOT NULL DEFAULT '0' COMMENT 'porcentaje pagado de esta venta',
  `cancelada` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'verdadero si esta venta ha sido cancelada, falso si no',
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT 'ip de donde provino esta compra',
  `liquidada` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Verdadero si esta venta ha sido liquidada, falso si hay un saldo pendiente',
  PRIMARY KEY (`id_venta`),
  UNIQUE KEY `id_venta_equipo` (`id_venta_equipo`,`id_equipo`),
  KEY `ventas_cliente` (`id_cliente`),
  KEY `ventas_sucursal` (`id_sucursal`),
  KEY `ventas_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7273 ;
