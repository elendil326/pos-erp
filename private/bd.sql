-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 14-06-2010 a las 22:22:15
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- RELACIONES PARA LA TABLA `compras`:
--   `id_usuario`
--       `usuario` -> `id_usuario`
--   `sucursal`
--       `sucursal` -> `id_sucursal`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- RELACIONES PARA LA TABLA `cotizacion`:
--   `id_cliente`
--       `cliente` -> `id_cliente`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_cliente`
--

DROP TABLE IF EXISTS `cuenta_cliente`;
CREATE TABLE IF NOT EXISTS `cuenta_cliente` (
  `id_cliente` int(11) NOT NULL COMMENT 'id del cliente',
  `saldo` int(11) NOT NULL COMMENT 'saldo del cliente',
  PRIMARY KEY (`id_cliente`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELACIONES PARA LA TABLA `cuenta_cliente`:
--   `id_cliente`
--       `cliente` -> `id_cliente`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_proveedor`
--

DROP TABLE IF EXISTS `cuenta_proveedor`;
CREATE TABLE IF NOT EXISTS `cuenta_proveedor` (
  `id_proveedor` int(11) NOT NULL COMMENT 'id del proveedor al que le debemos',
  `saldo` float NOT NULL COMMENT 'cantidad que adeudamos al proveedor',
  PRIMARY KEY (`id_proveedor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- RELACIONES PARA LA TABLA `cuenta_proveedor`:
--   `id_proveedor`
--       `proveedor` -> `id_proveedor`
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELACIONES PARA LA TABLA `detalle_compra`:
--   `id_compra`
--       `compras` -> `id_compra`
--   `id_producto`
--       `productos_proveedor` -> `id_producto`
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- RELACIONES PARA LA TABLA `detalle_cotizacion`:
--   `id_cotizacion`
--       `cotizacion` -> `id_cotizacion`
--   `id_producto`
--       `inventario` -> `id_producto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_inventario`
--

DROP TABLE IF EXISTS `detalle_inventario`;
CREATE TABLE IF NOT EXISTS `detalle_inventario` (
  `id_producto` int(11) NOT NULL COMMENT 'id del producto al que se refiere',
  `existencia` float NOT NULL DEFAULT '0' COMMENT 'existencia en la sucursal',
  `sucursal` int(11) NOT NULL COMMENT 'sucursal a la que se refiere',
  PRIMARY KEY (`id_producto`,`sucursal`),
  KEY `detalle_inventario_sucursal` (`sucursal`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- RELACIONES PARA LA TABLA `detalle_inventario`:
--   `id_producto`
--       `inventario` -> `id_producto`
--   `sucursal`
--       `sucursal` -> `id_sucursal`
--

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELACIONES PARA LA TABLA `detalle_venta`:
--   `id_producto`
--       `inventario` -> `id_producto`
--   `id_venta`
--       `ventas` -> `id_venta`
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
  UNIQUE KEY `id_compra` (`id_compra`),
  UNIQUE KEY `folio` (`folio`),
  UNIQUE KEY `id_compra_2` (`id_compra`),
  KEY `factura_compra_compra` (`id_compra`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- RELACIONES PARA LA TABLA `factura_compra`:
--   `id_compra`
--       `compras` -> `id_compra`
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
  PRIMARY KEY (`id_factura`),
  UNIQUE KEY `id_venta` (`id_venta`),
  UNIQUE KEY `folio` (`folio`),
  UNIQUE KEY `id_venta_2` (`id_venta`),
  KEY `factura_venta_venta` (`id_venta`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- RELACIONES PARA LA TABLA `factura_venta`:
--   `id_venta`
--       `ventas` -> `id_venta`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

DROP TABLE IF EXISTS `inventario`;
CREATE TABLE IF NOT EXISTS `inventario` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del producto',
  `nombre` varchar(90) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descripcion o nombre del producto',
  `precio_venta` float NOT NULL COMMENT 'precio al que se vende el producto',
  `minimo` float NOT NULL DEFAULT '0' COMMENT 'minimo de el producto',
  PRIMARY KEY (`id_producto`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- RELACIONES PARA LA TABLA `nota_remision`:
--   `id_venta`
--       `ventas` -> `id_venta`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- RELACIONES PARA LA TABLA `pagos_compra`:
--   `id_compra`
--       `compras` -> `id_compra`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- RELACIONES PARA LA TABLA `pagos_venta`:
--   `id_venta`
--       `ventas` -> `id_venta`
--

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
  KEY `productos_proveedor_proveedor` (`id_proveedor`),
  KEY `productos_proveedor_producto` (`id_inventario`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- RELACIONES PARA LA TABLA `productos_proveedor`:
--   `id_inventario`
--       `inventario` -> `id_producto`
--   `id_proveedor`
--       `proveedor` -> `id_proveedor`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=46 ;

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
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- RELACIONES PARA LA TABLA `ventas`:
--   `id_cliente`
--       `cliente` -> `id_cliente`
--   `id_sucursal`
--       `sucursal` -> `id_sucursal`
--   `id_usuario`
--       `usuario` -> `id_usuario`
--   `sucursal`
--       `sucursal` -> `id_sucursal`
--


#aqui van las llaves foraneas
#tabla compras
ALTER TABLE compras DROP FOREIGN KEY compras_proveedor;

alter table compras
add CONSTRAINT compras_proveedor FOREIGN KEY (id_proveedor)
REFERENCES  proveedores(id_proveedor) ON DELETE CASCADE;

ALTER TABLE compras DROP FOREIGN KEY compras_sucursal;

alter table compras
add CONSTRAINT compras_sucursal FOREIGN KEY (sucursal)
REFERENCES  sucursal(id_sucursal) ON DELETE CASCADE;

ALTER TABLE compras DROP FOREIGN KEY compras_usuario;

alter table compras
add CONSTRAINT compras_usuario FOREIGN KEY (id_usuario)
REFERENCES  usuario(id_usuario) ON DELETE CASCADE;

# tabla cotizacion

ALTER TABLE cotizacion DROP FOREIGN KEY cotizacion_cliente;

alter table cotizacion
add CONSTRAINT cotizacion_cliente FOREIGN KEY (id_cliente)
REFERENCES  cliente(id_cliente) ON DELETE CASCADE;

#tabla cuenta_cliente

ALTER TABLE cuenta_cliente  DROP FOREIGN KEY cuenta_de_cliente;

alter table cuenta_cliente
add CONSTRAINT cuenta_de_cliente FOREIGN KEY (id_cliente)
REFERENCES  cliente(id_cliente) ON DELETE CASCADE;

#tabla cuenta proveedor

ALTER TABLE cuenta_proveedor DROP FOREIGN KEY cuenta_de_proveedor;

alter table cuenta_proveedor
add CONSTRAINT cuenta_de_proveedor FOREIGN KEY (id_proveedor)
REFERENCES  proveedor(id_proveedor) ON DELETE CASCADE;

#tabla detalle compra

ALTER TABLE detalle_compra DROP FOREIGN KEY detalle_compra_compra;

alter table detalle_compra
add CONSTRAINT detalle_compra_compra FOREIGN KEY (id_compra)
REFERENCES  compras(id_compra) ON DELETE CASCADE;

ALTER TABLE detalle_compra DROP FOREIGN KEY detalle_compra_producto;

alter table detalle_compra
add CONSTRAINT detalle_compra_producto FOREIGN KEY (id_producto)
REFERENCES  productos_proveedor(id_producto) ON DELETE CASCADE;

#tabla detalle cotizacion
ALTER TABLE detalle_cotizacion DROP FOREIGN KEY detalle_cotizacion_cotizacion;

alter table detalle_cotizacion
add CONSTRAINT detalle_cotizacion_cotizacion FOREIGN KEY (id_cotizacion)
REFERENCES cotizacion(id_cotizacion) ON DELETE CASCADE;

ALTER TABLE detalle_cotizacion DROP FOREIGN KEY detalle_cotizacion_producto;

alter table detalle_cotizacion
add CONSTRAINT detalle_cotizacion_producto FOREIGN KEY (id_producto)
REFERENCES  inventario(id_producto) ON DELETE CASCADE;

#tabla detalle venta
ALTER TABLE detalle_venta DROP FOREIGN KEY detalle_venta_venta;

alter table detalle_venta
add CONSTRAINT detalle_venta_venta FOREIGN KEY (id_venta)
REFERENCES  ventas(id_venta) ON DELETE CASCADE;

ALTER TABLE detalle_venta DROP FOREIGN KEY detalle_venta_producto;

alter table detalle_venta
add CONSTRAINT detalle_venta_producto FOREIGN KEY (id_producto)
REFERENCES  inventario(id_producto) ON DELETE CASCADE;


#tabla factura compra

ALTER TABLE factura_compra DROP FOREIGN KEY factura_compra_compra;

alter table factura_compra
add CONSTRAINT factura_compra_compra FOREIGN KEY (id_compra)
REFERENCES  compras(id_compra) ON DELETE CASCADE;

#tabla factura venta

ALTER TABLE factura_venta DROP FOREIGN KEY factura_venta_venta;

alter table factura_venta
add CONSTRAINT factura_venta_venta FOREIGN KEY (id_venta)
REFERENCES  ventas(id_venta) ON DELETE CASCADE;

#tabla detalle_inventario

ALTER TABLE detalle_inventario DROP FOREIGN KEY inventario_producto;

alter table detalle_inventario
add CONSTRAINT inventario_producto FOREIGN KEY (id_producto)
REFERENCES  inventario(id_producto) ON DELETE CASCADE;

alter table detalle_inventario
add CONSTRAINT inventario_sucursal FOREIGN KEY (sucursal)
REFERENCES  sucursal(id_sucursal) ON DELETE CASCADE;

#tabla nota_remision

ALTER TABLE nota_remision DROP FOREIGN KEY nota_remision_venta;

alter table nota_remision
add CONSTRAINT nota_remision_venta FOREIGN KEY (id_venta)
REFERENCES  ventas(id_venta) ON DELETE CASCADE;

#tabla pagos_compra
ALTER TABLE pagos_compra DROP FOREIGN KEY pagos_compra_compra ;

alter table pagos_compra
add CONSTRAINT pagos_compra_compra FOREIGN KEY (id_compra)
REFERENCES  compras(id_compra) ON DELETE CASCADE;

#tabla pagos_venta

ALTER TABLE pagos_venta DROP FOREIGN KEY pagos_venta_venta;

alter table pagos_venta
add CONSTRAINT pagos_venta_venta FOREIGN KEY (id_venta)
REFERENCES  ventas(id_venta) ON DELETE CASCADE;

#tabla productos_proveedor

ALTER TABLE productos_proveedor DROP FOREIGN KEY productos_proveedor_proveedor;

alter table productos_proveedor
add CONSTRAINT productos_proveedor_proveedor FOREIGN KEY (id_proveedor)
REFERENCES  proveedor(id_proveedor) ON DELETE CASCADE;

ALTER TABLE productos_proveedor DROP FOREIGN KEY productos_proveedor_producto;

alter table productos_proveedor
add CONSTRAINT productos_proveedor_producto FOREIGN KEY (id_inventario)
REFERENCES  inventario(id_producto) ON DELETE CASCADE;

#tabla ventas


ALTER TABLE ventas DROP FOREIGN KEY ventas_cliente;

alter table ventas
add CONSTRAINT ventas_cliente  FOREIGN KEY (id_cliente)
REFERENCES  clientes(id_cliente) ON DELETE CASCADE;

ALTER TABLE ventas DROP FOREIGN KEY ventas_sucursal;

alter table ventas
add CONSTRAINT ventas_sucursal FOREIGN KEY (sucursal)
REFERENCES  sucursal(id_sucursal) ON DELETE CASCADE;

ALTER TABLE ventas DROP FOREIGN KEY ventas_usuario;

alter table ventas
add CONSTRAINT ventas_usuario FOREIGN KEY (id_usuario)
REFERENCES  usuario(id_usuario) ON DELETE CASCADE;


#detalle inventarios

ALTER TABLE detalle_inventario DROP FOREIGN KEY detalle_inventario_producto;
alter table detalle_inventario
add CONSTRAINT detalle_inventario_producto FOREIGN KEY (id_producto)
REFERENCES  inventario(id_producto) ON DELETE CASCADE;

ALTER TABLE detalle_inventario DROP FOREIGN KEY detalle_inventario_sucursal;

alter table detalle_inventario
add CONSTRAINT detalle_inventario_sucursal FOREIGN KEY (sucursal)
REFERENCES  sucursal(id_sucursal)
ON DELETE CASCADE;


