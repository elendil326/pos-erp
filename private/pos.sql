-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 18-01-2011 a las 12:18:55
-- Versión del servidor: 5.0.51
-- Versión de PHP: 5.3.3-0.dotdeb.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `pos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actualizacion_de_precio`
--

CREATE TABLE IF NOT EXISTS `actualizacion_de_precio` (
  `id_actualizacion` int(12) NOT NULL auto_increment COMMENT 'id de actualizacion de precio',
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `precio_venta` float NOT NULL,
  `precio_intersucursal` float NOT NULL,
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id_actualizacion`),
  KEY `id_producto` (`id_producto`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Actualizaciones de precios' AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autorizacion`
--

CREATE TABLE IF NOT EXISTS `autorizacion` (
  `id_autorizacion` int(11) unsigned NOT NULL auto_increment,
  `id_usuario` int(11) NOT NULL COMMENT 'Usuario que solicito esta autorizacion',
  `id_sucursal` int(11) NOT NULL COMMENT 'Sucursal de donde proviene esta autorizacion',
  `fecha_peticion` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'Fecha cuando se genero esta autorizacion',
  `fecha_respuesta` timestamp NULL default NULL COMMENT 'Fecha de cuando se resolvio esta aclaracion',
  `estado` int(11) NOT NULL COMMENT 'Estado actual de esta aclaracion',
  `parametros` varchar(2048) NOT NULL COMMENT 'Parametros en formato JSON que describen esta autorizacion',
  PRIMARY KEY  (`id_autorizacion`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
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
  PRIMARY KEY  (`id_cliente`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_proveedor`
--

CREATE TABLE IF NOT EXISTS `compra_proveedor` (
  `id_compra_proveedor` int(11) NOT NULL auto_increment COMMENT 'identificador de la compra',
  `peso_origen` float NOT NULL,
  `id_proveedor` int(11) NOT NULL COMMENT 'id del proveedor a quien se le hizo esta compra',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha de cuando se recibio el embarque',
  `fecha_origen` date NOT NULL COMMENT 'fecha de cuando se envio este embarque',
  `folio` varchar(11) character set latin1 collate latin1_general_cs default NULL COMMENT 'folio de la remision',
  `numero_de_viaje` varchar(11) character set latin1 collate latin1_general_cs default NULL COMMENT 'numero de viaje',
  `peso_recibido` float NOT NULL COMMENT 'peso en kilogramos reportado en la remision',
  `arpillas` float NOT NULL COMMENT 'numero de arpillas en el camion',
  `peso_por_arpilla` float NOT NULL COMMENT 'peso promedio en kilogramos por arpilla',
  `productor` varchar(64) NOT NULL,
  `calidad` tinyint(3) unsigned default NULL COMMENT 'Describe la calidad del producto asignando una calificacion en eel rango de 0-100',
  `merma_por_arpilla` float NOT NULL,
  `total_origen` float default NULL COMMENT 'Es lo que vale el embarque segun el proveedor',
  PRIMARY KEY  (`id_compra_proveedor`),
  KEY `id_proveedor` (`id_proveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_proveedor_flete`
--

CREATE TABLE IF NOT EXISTS `compra_proveedor_flete` (
  `id_compra_proveedor` int(11) NOT NULL,
  `chofer` varchar(64) NOT NULL,
  `marca_camion` varchar(64) default NULL,
  `placas_camion` varchar(64) NOT NULL,
  `modelo_camion` varchar(64) default NULL,
  `costo_flete` float NOT NULL,
  PRIMARY KEY  (`id_compra_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_sucursal`
--

CREATE TABLE IF NOT EXISTS `compra_sucursal` (
  `id_compra` int(11) NOT NULL auto_increment COMMENT 'id de la compra',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha de compra',
  `subtotal` float NOT NULL COMMENT 'subtotal de compra',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en que se compro',
  `id_usuario` int(11) NOT NULL COMMENT 'quien realizo la compra',
  `pagado` float NOT NULL default '0' COMMENT 'total de pago abonado a esta compra',
  `liquidado` tinyint(1) NOT NULL default '0' COMMENT 'indica si la cuenta ha sido liquidada o no',
  `total` float NOT NULL,
  PRIMARY KEY  (`id_compra`),
  KEY `compras_sucursal` (`id_sucursal`),
  KEY `compras_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `corte`
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
  PRIMARY KEY  (`id_corte`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra_proveedor`
--

CREATE TABLE IF NOT EXISTS `detalle_compra_proveedor` (
  `id_compra_proveedor` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `variedad` varchar(64) NOT NULL,
  `arpillas` int(11) NOT NULL,
  `kg` int(11) NOT NULL,
  `precio_por_kg` int(11) NOT NULL,
  PRIMARY KEY  (`id_compra_proveedor`,`id_producto`),
  KEY `id_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra_sucursal`
--

CREATE TABLE IF NOT EXISTS `detalle_compra_sucursal` (
  `id_compra` int(11) NOT NULL COMMENT 'id de la compra',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `cantidad` float NOT NULL COMMENT 'cantidad comprada',
  `precio` float NOT NULL COMMENT 'costo de compra',
  `descuento` int(11) NOT NULL,
  `procesadas` tinyint(1) NOT NULL COMMENT 'verdadero si este detalle se refiere a compras procesadas (limpias)',
  PRIMARY KEY  (`id_compra`,`id_producto`),
  KEY `id_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_inventario`
--

CREATE TABLE IF NOT EXISTS `detalle_inventario` (
  `id_producto` int(11) NOT NULL COMMENT 'id del producto al que se refiere',
  `id_sucursal` int(11) NOT NULL COMMENT 'id de la sucursal',
  `precio_venta` float NOT NULL COMMENT 'precio al que se vendera al publico',
  `min` float NOT NULL default '0' COMMENT 'cantidad minima que debe de haber del producto en almacen de esta sucursal',
  `existencias` float NOT NULL default '0' COMMENT 'cantidad de producto que hay actualmente en almacen de esta sucursal',
  `existencias_procesadas` float NOT NULL,
  PRIMARY KEY  (`id_producto`,`id_sucursal`),
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
  PRIMARY KEY  (`id_venta`,`id_producto`),
  KEY `detalle_venta_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE IF NOT EXISTS `equipo` (
  `id_equipo` int(6) NOT NULL auto_increment COMMENT 'el identificador de este equipo',
  `token` varchar(128) default NULL COMMENT 'el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado',
  `full_ua` varchar(256) NOT NULL COMMENT 'String de user-agent para este cliente',
  `descripcion` varchar(254) NOT NULL COMMENT 'descripcion de este equipo',
  `locked` tinyint(1) NOT NULL default '0' COMMENT 'si este equipo esta lockeado para prevenir los cambios',
  PRIMARY KEY  (`id_equipo`),
  UNIQUE KEY `full_ua` (`full_ua`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo_sucursal`
--

CREATE TABLE IF NOT EXISTS `equipo_sucursal` (
  `id_equipo` int(6) NOT NULL COMMENT 'identificador del equipo ',
  `id_sucursal` int(6) NOT NULL COMMENT 'identifica una sucursal',
  PRIMARY KEY  (`id_equipo`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_compra`
--

CREATE TABLE IF NOT EXISTS `factura_compra` (
  `folio` varchar(15) collate utf8_unicode_ci NOT NULL,
  `id_compra` int(11) NOT NULL COMMENT 'COMPRA A LA QUE CORRESPONDE LA FACTURA',
  PRIMARY KEY  (`folio`),
  KEY `factura_compra_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_venta`
--

CREATE TABLE IF NOT EXISTS `factura_venta` (
  `folio` int(11) unsigned NOT NULL auto_increment COMMENT 'folio que tiene la factura',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la factura',
  PRIMARY KEY  (`folio`),
  KEY `factura_venta_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
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
  PRIMARY KEY  (`id_gasto`),
  KEY `id_sucursal` (`id_sucursal`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE IF NOT EXISTS `grupos` (
  `id_grupo` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL COMMENT 'Nombre del Grupo',
  `descripcion` varchar(256) NOT NULL,
  PRIMARY KEY  (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos_usuarios`
--

CREATE TABLE IF NOT EXISTS `grupos_usuarios` (
  `id_grupo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY  (`id_usuario`),
  KEY `fk_grupos_usuarios_1` (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `id_producto` int(11) NOT NULL auto_increment COMMENT 'id del producto',
  `descripcion` varchar(30) collate utf8_unicode_ci NOT NULL COMMENT 'descripcion del producto',
  `escala` enum('kilogramo','pieza','litro','unidad') collate utf8_unicode_ci NOT NULL,
  `tratamiento` enum('limpia') collate utf8_unicode_ci default NULL COMMENT 'Tipo de tratatiento si es que existe para este producto.',
  PRIMARY KEY  (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_maestro`
--

CREATE TABLE IF NOT EXISTS `inventario_maestro` (
  `id_producto` int(11) NOT NULL,
  `id_compra_proveedor` int(11) NOT NULL,
  `existencias` float NOT NULL,
  `existencias_procesadas` float NOT NULL,
  `sitio_descarga` int(11) NOT NULL,
  PRIMARY KEY  (`id_producto`,`id_compra_proveedor`),
  KEY `id_compra_proveedor` (`id_compra_proveedor`),
  KEY `sitio_descarga` (`sitio_descarga`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_compra`
--

CREATE TABLE IF NOT EXISTS `pagos_compra` (
  `id_pago` int(11) NOT NULL auto_increment COMMENT 'identificador del pago',
  `id_compra` int(11) NOT NULL COMMENT 'identificador de la compra a la que pagamos',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha en que se abono',
  `monto` float NOT NULL COMMENT 'monto que se abono',
  PRIMARY KEY  (`id_pago`),
  KEY `pagos_compra_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_venta`
--

CREATE TABLE IF NOT EXISTS `pagos_venta` (
  `id_pago` int(11) NOT NULL auto_increment COMMENT 'id de pago del cliente',
  `id_venta` int(11) NOT NULL COMMENT 'id de la venta a la que se esta pagando',
  `id_sucursal` int(11) NOT NULL COMMENT 'Donde se realizo el pago',
  `id_usuario` int(11) NOT NULL COMMENT 'Quien cobro este pago',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'Fecha en que se registro el pago',
  `monto` float NOT NULL COMMENT 'total de credito del cliente',
  `tipo_pago` enum('efectivo','cheque','tarjeta') collate utf8_unicode_ci NOT NULL default 'efectivo' COMMENT 'tipo de pago para este abono',
  PRIMARY KEY  (`id_pago`),
  KEY `pagos_venta_venta` (`id_venta`),
  KEY `id_sucursal` (`id_sucursal`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE IF NOT EXISTS `proveedor` (
  `id_proveedor` int(11) NOT NULL auto_increment COMMENT 'identificador del proveedor',
  `rfc` varchar(20) collate utf8_unicode_ci default NULL COMMENT 'rfc del proveedor',
  `nombre` varchar(30) collate utf8_unicode_ci NOT NULL COMMENT 'nombre del proveedor',
  `direccion` varchar(100) collate utf8_unicode_ci default NULL COMMENT 'direccion del proveedor',
  `telefono` varchar(20) collate utf8_unicode_ci default NULL COMMENT 'telefono',
  `e_mail` varchar(60) collate utf8_unicode_ci default NULL COMMENT 'email del provedor',
  `activo` tinyint(2) NOT NULL default '1' COMMENT 'Indica si la cuenta esta activada o desactivada',
  `tipo_proveedor` enum('admin','sucursal','ambos') collate utf8_unicode_ci NOT NULL default 'admin' COMMENT 'si este proveedor surtira al admin, a las sucursales o a ambos',
  PRIMARY KEY  (`id_proveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
  `id_sucursal` int(11) NOT NULL auto_increment COMMENT 'Identificador de cada sucursal',
  `gerente` int(11) default NULL COMMENT 'Gerente de esta sucursal',
  `descripcion` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'nombre o descripcion de sucursal',
  `direccion` varchar(200) collate utf8_unicode_ci NOT NULL COMMENT 'direccion de la sucursal',
  `rfc` varchar(20) collate utf8_unicode_ci default NULL COMMENT 'El RFC de la sucursal',
  `telefono` varchar(20) collate utf8_unicode_ci default NULL COMMENT 'El telefono de la sucursal',
  `token` varchar(512) collate utf8_unicode_ci default NULL COMMENT 'Token de seguridad para esta sucursal',
  `letras_factura` char(1) collate utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL default '1',
  `fecha_apertura` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'Fecha de apertura de esta sucursal',
  `saldo_a_favor` float NOT NULL default '0' COMMENT 'es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras',
  PRIMARY KEY  (`id_sucursal`),
  UNIQUE KEY `letras_factura` (`letras_factura`),
  KEY `gerente` (`gerente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=104 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE IF NOT EXISTS `ventas` (
  `id_venta` int(11) NOT NULL auto_increment COMMENT 'id de venta',
  `id_cliente` int(11) NOT NULL COMMENT 'cliente al que se le vendio',
  `tipo_venta` enum('credito','contado') collate utf8_unicode_ci NOT NULL COMMENT 'tipo de venta, contado o credito',
  `tipo_pago` enum('efectivo','cheque','tarjeta') collate utf8_unicode_ci default 'efectivo' COMMENT 'tipo de pago para esta venta en caso de ser a contado',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha de venta',
  `subtotal` float default NULL COMMENT 'subtotal de la venta, puede ser nulo',
  `iva` float default NULL COMMENT 'iva agregado por la venta, depende de cada sucursal',
  `descuento` float NOT NULL default '0' COMMENT 'descuento aplicado a esta venta',
  `total` float NOT NULL default '0' COMMENT 'total de esta venta',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal de la venta',
  `id_usuario` int(11) NOT NULL COMMENT 'empleado que lo vendio',
  `pagado` float NOT NULL default '0' COMMENT 'porcentaje pagado de esta venta',
  `cancelada` tinyint(1) NOT NULL default '0' COMMENT 'verdadero si esta venta ha sido cancelada, falso si no',
  `ip` varchar(16) collate utf8_unicode_ci NOT NULL default '0.0.0.0' COMMENT 'ip de donde provino esta compra',
  PRIMARY KEY  (`id_venta`),
  KEY `ventas_cliente` (`id_cliente`),
  KEY `ventas_sucursal` (`id_sucursal`),
  KEY `ventas_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `actualizacion_de_precio`
--
ALTER TABLE `actualizacion_de_precio`
  ADD CONSTRAINT `actualizacion_de_precio_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `actualizacion_de_precio_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `autorizacion`
--
ALTER TABLE `autorizacion`
  ADD CONSTRAINT `autorizacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `autorizacion_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `compra_proveedor`
--
ALTER TABLE `compra_proveedor`
  ADD CONSTRAINT `compra_proveedor_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`);

--
-- Filtros para la tabla `compra_proveedor_flete`
--
ALTER TABLE `compra_proveedor_flete`
  ADD CONSTRAINT `compra_proveedor_flete_ibfk_1` FOREIGN KEY (`id_compra_proveedor`) REFERENCES `compra_proveedor` (`id_compra_proveedor`);

--
-- Filtros para la tabla `compra_sucursal`
--
ALTER TABLE `compra_sucursal`
  ADD CONSTRAINT `compra_sucursal_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `compra_sucursal_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `corte`
--
ALTER TABLE `corte`
  ADD CONSTRAINT `corte_ibfk_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`);

--
-- Filtros para la tabla `detalle_compra_proveedor`
--
ALTER TABLE `detalle_compra_proveedor`
  ADD CONSTRAINT `detalle_compra_proveedor_ibfk_1` FOREIGN KEY (`id_compra_proveedor`) REFERENCES `compra_proveedor` (`id_compra_proveedor`),
  ADD CONSTRAINT `detalle_compra_proveedor_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`);

--
-- Filtros para la tabla `detalle_compra_sucursal`
--
ALTER TABLE `detalle_compra_sucursal`
  ADD CONSTRAINT `detalle_compra_sucursal_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra_sucursal` (`id_compra`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalle_compra_sucursal_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE NO ACTION;

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
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `equipo_sucursal`
--
ALTER TABLE `equipo_sucursal`
  ADD CONSTRAINT `equipo_sucursal_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`),
  ADD CONSTRAINT `equipo_sucursal_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`);

--
-- Filtros para la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
  ADD CONSTRAINT `factura_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra_sucursal` (`id_compra`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura_venta`
--
ALTER TABLE `factura_venta`
  ADD CONSTRAINT `factura_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  ADD CONSTRAINT `gastos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `grupos_usuarios`
--
ALTER TABLE `grupos_usuarios`
  ADD CONSTRAINT `grupos_usuarios_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `grupos_usuarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_ibfk_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  ADD CONSTRAINT `ingresos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `inventario_maestro`
--
ALTER TABLE `inventario_maestro`
  ADD CONSTRAINT `inventario_maestro_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`),
  ADD CONSTRAINT `inventario_maestro_ibfk_2` FOREIGN KEY (`id_compra_proveedor`) REFERENCES `compra_proveedor` (`id_compra_proveedor`),
  ADD CONSTRAINT `inventario_maestro_ibfk_3` FOREIGN KEY (`sitio_descarga`) REFERENCES `sucursal` (`id_sucursal`);

--
-- Filtros para la tabla `pagos_compra`
--
ALTER TABLE `pagos_compra`
  ADD CONSTRAINT `pagos_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra_sucursal` (`id_compra`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
  ADD CONSTRAINT `pagos_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `pagos_venta_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  ADD CONSTRAINT `pagos_venta_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD CONSTRAINT `sucursal_ibfk_1` FOREIGN KEY (`gerente`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_4` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `ventas_ibfk_5` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  ADD CONSTRAINT `ventas_ibfk_6` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

