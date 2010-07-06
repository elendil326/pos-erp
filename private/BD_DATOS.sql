-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 17-06-2010 a las 22:06:30
-- Versión del servidor: 5.0.45
-- Versión de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `pos`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cliente`
-- 

DROP TABLE IF EXISTS cliente;
CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL auto_increment COMMENT 'identificador del cliente',
  `rfc` varchar(20) collate utf8_unicode_ci NOT NULL COMMENT 'rfc del cliente si es que tiene',
  `nombre` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'nombre del cliente',
  `direccion` varchar(300) collate utf8_unicode_ci NOT NULL COMMENT 'domicilio del cliente calle, no, colonia',
  `telefono` varchar(25) collate utf8_unicode_ci default NULL COMMENT 'Telefono del cliete',
  `e_mail` varchar(60) collate utf8_unicode_ci NOT NULL default '0' COMMENT 'dias de credito para que pague el cliente',
  `limite_credito` float NOT NULL default '0' COMMENT 'Limite de credito otorgado al cliente',
  PRIMARY KEY  (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `compras`
-- 

DROP TABLE IF EXISTS compras;
CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL COMMENT 'id de la compra',
  `id_proveedor` int(11) NOT NULL COMMENT 'PROVEEDOR AL QUE SE LE COMPRO',
  `tipo_compra` int(11) NOT NULL COMMENT 'tipo de compra, contado o credito',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha de compra',
  `subtotal` float NOT NULL COMMENT 'subtotal de compra',
  `iva` float NOT NULL COMMENT 'iva de la compra',
  `sucursal` int(11) NOT NULL COMMENT 'sucursal en que se compro',
  `id_usuario` int(11) NOT NULL COMMENT 'quien realizo la compra',
  PRIMARY KEY  (`id_compra`),
  KEY `compras_proveedor` (`id_proveedor`),
  KEY `compras_sucursal` (`sucursal`),
  KEY `compras_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Volcar la base de datos para la tabla `compras`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cotizacion`
-- 

DROP TABLE IF EXISTS cotizacion; 
CREATE TABLE `cotizacion` (
  `id_cotizacion` int(11) NOT NULL auto_increment COMMENT 'id de la cotizacion',
  `id_cliente` int(11) NOT NULL COMMENT 'id del cliente',
  `fecha` date NOT NULL COMMENT 'fecha de cotizacion',
  `subtotal` float NOT NULL COMMENT 'subtotal de la cotizacion',
  `iva` float NOT NULL COMMENT 'iva sobre el subtotal',
  PRIMARY KEY  (`id_cotizacion`),
  KEY `cotizacion_cliente` (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cuenta_cliente`
-- 

DROP TABLE IF EXISTS cuenta_cliente;
CREATE TABLE `cuenta_cliente` (
  `id_cliente` int(11) NOT NULL COMMENT 'id del cliente',
  `saldo` int(11) NOT NULL COMMENT 'saldo del cliente',
  PRIMARY KEY  (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Volcar la base de datos para la tabla `cuenta_cliente`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cuenta_proveedor`
-- 

DROP TABLE IF EXISTS cuenta_proveedor;
CREATE TABLE `cuenta_proveedor` (
  `id_proveedor` int(11) NOT NULL COMMENT 'id del proveedor al que le debemos',
  `saldo` float NOT NULL COMMENT 'cantidad que adeudamos al proveedor',
  PRIMARY KEY  (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 
-- Volcar la base de datos para la tabla `cuenta_proveedor`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `detalle_compra`
-- 

DROP TABLE IF EXISTS detalle_compra;
CREATE TABLE `detalle_compra` (
  `id_compra` int(11) NOT NULL COMMENT 'id de la compra',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `cantidad` float NOT NULL COMMENT 'cantidad comprada',
  `precio` float NOT NULL COMMENT 'costo de compra',
  PRIMARY KEY  (`id_compra`,`id_producto`),
  KEY `detalle_compra_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Volcar la base de datos para la tabla `detalle_compra`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `detalle_cotizacion`
-- 

DROP TABLE IF EXISTS detalle_cotizacion;
CREATE TABLE `detalle_cotizacion` (
  `id_cotizacion` int(11) NOT NULL COMMENT 'id de la cotizacion',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `cantidad` float NOT NULL COMMENT 'cantidad cotizado',
  `precio` float NOT NULL COMMENT 'precio al que cotizo el producto',
  PRIMARY KEY  (`id_cotizacion`,`id_producto`),
  KEY `detalle_cotizacion_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `detalle_inventario`
-- 

DROP TABLE IF EXISTS detalle_inventario;
CREATE TABLE `detalle_inventario` (
  `id_producto` int(11) NOT NULL COMMENT 'id del producto al que se refiere',
  `id_sucursal` int(11) NOT NULL COMMENT 'id de la sucursal',
  `precio_venta` float NOT NULL COMMENT 'precio al que se vendera al publico',
  `min` float NOT NULL default '0' COMMENT 'cantidad minima que debe de haber del producto en almacen de esta sucursal',
  `existencias` float NOT NULL default '0' COMMENT 'cantidad de producto que hay actualmente en almacen de esta sucursal',
  PRIMARY KEY  (`id_producto`,`id_sucursal`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `detalle_venta`
-- 

DROP TABLE IF EXISTS detalle_venta;
CREATE TABLE `detalle_venta` (
  `id_venta` int(11) NOT NULL COMMENT 'venta a que se referencia',
  `id_producto` int(11) NOT NULL COMMENT 'producto de la venta',
  `cantidad` float NOT NULL COMMENT 'cantidad que se vendio',
  `precio` float NOT NULL COMMENT 'precio al que se vendio',
  PRIMARY KEY  (`id_venta`,`id_producto`),
  KEY `detalle_venta_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Volcar la base de datos para la tabla `detalle_venta`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `factura_compra`
-- 

DROP TABLE IF EXISTS factura_compra;
CREATE TABLE `factura_compra` (
  `id_factura` int(20) NOT NULL auto_increment COMMENT 'NUMERO DE FACTURA',
  `folio` varchar(15) collate utf8_unicode_ci NOT NULL,
  `id_compra` int(11) NOT NULL COMMENT 'COMPRA A LA QUE CORRESPONDE LA FACTURA',
  PRIMARY KEY  (`id_factura`),
  KEY `factura_compra_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `factura_compra`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `factura_venta`
-- 

DROP TABLE IF EXISTS factura_venta;
CREATE TABLE `factura_venta` (
  `id_factura` int(20) NOT NULL auto_increment COMMENT 'Numero de factura al cliente',
  `folio` varchar(15) collate utf8_unicode_ci NOT NULL COMMENT 'folio que tiene la factura',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la factura',
  PRIMARY KEY  (`id_factura`),
  KEY `factura_venta_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `factura_venta`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `impuesto`
-- 

DROP TABLE IF EXISTS impuesto;
CREATE TABLE `impuesto` (
  `id_impuesto` int(11) NOT NULL auto_increment,
  `descripcion` varchar(100) collate utf8_unicode_ci NOT NULL,
  `valor` int(11) NOT NULL,
  PRIMARY KEY  (`id_impuesto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `inventario`
-- 

DROP TABLE IF EXISTS inventario;
CREATE TABLE `inventario` (
  `id_producto` int(11) NOT NULL auto_increment COMMENT 'id del producto',
  `nombre` varchar(90) collate utf8_unicode_ci NOT NULL COMMENT 'Descripcion o nombre del producto',
  `denominacion` varchar(30) collate utf8_unicode_ci NOT NULL COMMENT 'es lo que se le mostrara a los clientes',
  PRIMARY KEY  (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `nota_remision`
-- 

DROP TABLE IF EXISTS nota_remision;
CREATE TABLE `nota_remision` (
  `id_nota` int(11) NOT NULL auto_increment COMMENT 'numero de nota a clienes',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la nota',
  PRIMARY KEY  (`id_nota`),
  KEY `nota_remision_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `nota_remision`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `pagos_compra`
-- 

DROP TABLE IF EXISTS pagos_compra;
CREATE TABLE `pagos_compra` (
  `id_pago` int(11) NOT NULL auto_increment COMMENT 'identificador del pago',
  `id_compra` int(11) NOT NULL COMMENT 'identificador de la compra a la que pagamos',
  `fecha` date NOT NULL COMMENT 'fecha en que se abono',
  `monto` float NOT NULL COMMENT 'monto que se abono',
  PRIMARY KEY  (`id_pago`),
  KEY `pagos_compra_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `pagos_compra`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `pagos_venta`
-- 

DROP TABLE IF EXISTS pagos_venta;
CREATE TABLE `pagos_venta` (
  `id_pago` int(11) NOT NULL auto_increment COMMENT 'id de pago del cliente',
  `id_venta` int(11) NOT NULL COMMENT 'id de la venta a la que se esta pagando',
  `fecha` date NOT NULL COMMENT 'fecha de pago',
  `monto` float NOT NULL COMMENT 'total de credito del cliente',
  PRIMARY KEY  (`id_pago`),
  KEY `pagos_venta_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `pagos_venta`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `productos_proveedor`
-- 

DROP TABLE IF EXISTS productos_proveedor;
CREATE TABLE `productos_proveedor` (
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
-- Volcar la base de datos para la tabla `productos_proveedor`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `proveedor`
-- 

DROP TABLE IF EXISTS proveedor;
CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL auto_increment COMMENT 'identificador del proveedor',
  `rfc` varchar(20) collate utf8_unicode_ci NOT NULL COMMENT 'rfc del proveedor',
  `nombre` varchar(30) collate utf8_unicode_ci NOT NULL COMMENT 'nombre del proveedor',
  `direccion` varchar(100) collate utf8_unicode_ci default NULL COMMENT 'direccion del proveedor',
  `telefono` varchar(20) collate utf8_unicode_ci default NULL COMMENT 'telefono',
  `e_mail` varchar(60) collate utf8_unicode_ci default NULL COMMENT 'email del provedor',
  PRIMARY KEY  (`id_proveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `sucursal`
-- 

DROP TABLE IF EXISTS sucursal;
CREATE TABLE `sucursal` (
  `id_sucursal` int(11) NOT NULL auto_increment COMMENT 'Identificador de cada sucursal',
  `descripcion` varchar(100) collate utf8_unicode_ci default NULL COMMENT 'nombre o descripcion de sucursal',
  `direccion` varchar(200) collate utf8_unicode_ci default NULL COMMENT 'direccion de la sucursal',
  PRIMARY KEY  (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `usuario`
-- 

DROP TABLE IF EXISTS usuario;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL auto_increment COMMENT 'identificador del usuario',
  `nombre` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'nombre del empleado',
  `usuario` varchar(50) collate utf8_unicode_ci NOT NULL,
  `contrasena` varchar(50) collate utf8_unicode_ci NOT NULL,
  `nivel` int(11) NOT NULL,
  `sucursal_id` int(11) NOT NULL COMMENT 'Id de la sucursal a que pertenece',
  PRIMARY KEY  (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `usuario`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `ventas`
-- 

DROP TABLE IF EXISTS ventas;
CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL COMMENT 'id de venta',
  `id_cliente` int(11) NOT NULL COMMENT 'cliente al que se le vendio',
  `tipo_venta` int(11) NOT NULL COMMENT 'tipo de venta, contado o credito',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha de venta',
  `subtotal` float NOT NULL COMMENT 'subtotal de la venta',
  `iva` float NOT NULL COMMENT 'iva agregado por la venta',
  `sucursal` int(11) NOT NULL COMMENT 'sucursal de la venta',
  `id_usuario` int(11) NOT NULL COMMENT 'empleado que lo vendio',
  PRIMARY KEY  (`id_venta`),
  KEY `ventas_cliente` (`id_cliente`),
  KEY `ventas_sucursal` (`sucursal`),
  KEY `ventas_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Volcar la base de datos para la tabla `ventas`
-- 


-- 
-- Filtros para las tablas descargadas (dump)
-- 

-- 
-- Filtros para la tabla `detalle_compra`
-- 
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`),
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`);

-- 
-- Filtros para la tabla `detalle_cotizacion`
-- 
ALTER TABLE `detalle_cotizacion`
  ADD CONSTRAINT `detalle_cotizacion_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`),
  ADD CONSTRAINT `detalle_cotizacion_ibfk_1` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`);

-- 
-- Filtros para la tabla `detalle_inventario`
-- 
ALTER TABLE `detalle_inventario`
  ADD CONSTRAINT `detalle_inventario_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  ADD CONSTRAINT `detalle_inventario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`);

-- 
-- Filtros para la tabla `detalle_venta`
-- 
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`),
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

-- 
-- Filtros para la tabla `factura_compra`
-- 
ALTER TABLE `factura_compra`
  ADD CONSTRAINT `factura_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`);

-- 
-- Filtros para la tabla `factura_venta`
-- 
ALTER TABLE `factura_venta`
  ADD CONSTRAINT `factura_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

-- 
-- Filtros para la tabla `nota_remision`
-- 
ALTER TABLE `nota_remision`
  ADD CONSTRAINT `nota_remision_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

-- 
-- Filtros para la tabla `pagos_compra`
-- 
ALTER TABLE `pagos_compra`
  ADD CONSTRAINT `pagos_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`);

-- 
-- Filtros para la tabla `pagos_venta`
-- 
ALTER TABLE `pagos_venta`
  ADD CONSTRAINT `pagos_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

-- 
-- Filtros para la tabla `productos_proveedor`
-- 
ALTER TABLE `productos_proveedor`
  ADD CONSTRAINT `productos_proveedor_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `productos_proveedor_ibfk_2` FOREIGN KEY (`id_inventario`) REFERENCES `inventario` (`id_producto`);

-- 
-- Filtros para la tabla `ventas`
-- 
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`sucursal`) REFERENCES `sucursal` (`id_sucursal`);

  ------------------------------------------------------------------
  --INSERCIONES DE DATOS:
  ----------------------------------------------------------------
  
-- 
-- Volcar la base de datos para la tabla `cotizacion`
-- 

INSERT INTO `cotizacion` (`id_cotizacion`, `id_cliente`, `fecha`, `subtotal`, `iva`) VALUES 
(1, 24, '2010-06-16', 57.5, 9.2),
(2, 25, '2010-06-16', 44.5, 7.12),
(3, 28, '2010-06-16', 83, 13.28);

-- 
-- Volcar la base de datos para la tabla `cliente`
-- 

INSERT INTO `cliente` (`id_cliente`, `rfc`, `nombre`, `direccion`, `telefono`, `e_mail`, `limite_credito`) VALUES 
(24, 'JMHR220486', 'JUAN MANUEL HERNANDEZ REYES', 'CALLEJON SANTO NI', '4151858028', 'figu@gmail.com', 10000),
(25, 'JMGC121185', 'JUAN MANUEL GARCIA CARMONA', 'DIEGO RIVERA', '4611469489', 'zonademanel@hotmail.com', 4000),
(28, 'GJHER140203', 'Guadalupe de Jesus Hernandez Reyes', 'Santo nino #3 int 1', '4151858028', 'chucho@chucho.com', 10),
(34, 'JDDHER??', 'Juan de Dios Hernandez XXX', 'Callejon Santo nino no 3 Int 1', '4151858028', 'jddios@live.com.mx', 5000);

-- 
-- Volcar la base de datos para la tabla `detalle_cotizacion`
-- 

INSERT INTO `detalle_cotizacion` (`id_cotizacion`, `id_producto`, `cantidad`, `precio`) VALUES 
(1, 4, 1, 9.5),
(1, 5, 4, 12),
(2, 4, 1, 9.5),
(2, 5, 2, 12),
(2, 6, 1, 11),
(3, 4, 4, 9.5),
(3, 5, 1, 12),
(3, 6, 3, 11);

-- 
-- Volcar la base de datos para la tabla `detalle_inventario`
-- 

INSERT INTO `detalle_inventario` (`id_producto`, `id_sucursal`, `precio_venta`, `min`, `existencias`) VALUES 
(4, 1, 9.5, 1000, 0),
(4, 2, 10, 200, 0),
(5, 1, 9, 1000, 0);

-- 
-- Volcar la base de datos para la tabla `impuesto`
-- 

INSERT INTO `impuesto` (`id_impuesto`, `descripcion`, `valor`) VALUES 
(5, 'IVA', 16);

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

-- 
-- Volcar la base de datos para la tabla `proveedor`
-- 

INSERT INTO `proveedor` (`id_proveedor`, `rfc`, `nombre`, `direccion`, `telefono`, `e_mail`) VALUES 
(4, 'RFCPRIMERP', 'LA MORENASA DE FUEGO', 'DIRECCION DE LA MORENA', '461456789', 'email@morena.com'),
(5, 'RFC2', 'PAPAS CHIDAS', 'DIREC', '455555', 'mail@mail.com');


-- 
-- Volcar la base de datos para la tabla `sucursal`
-- 

INSERT INTO `sucursal` (`id_sucursal`, `descripcion`, `direccion`) VALUES 
(1, 'Bodega', 'Central de Abastos de Celaya Salida Apaseo'),
(2, 'Sucursal de la Central', 'Central de Abastos Celaya'),
(3, 'Sucursal 2', 'Rancho Nuevo');
