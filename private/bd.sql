-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 16, 2010 at 01:38 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` int(11) NOT NULL auto_increment COMMENT 'identificador del cliente',
  `rfc` varchar(20) collate utf8_unicode_ci NOT NULL COMMENT 'rfc del cliente si es que tiene',
  `nombre` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'nombre del cliente',
  `direccion` varchar(300) collate utf8_unicode_ci NOT NULL COMMENT 'domicilio del cliente calle, no, colonia',
  `telefono` varchar(25) collate utf8_unicode_ci default NULL COMMENT 'Telefono del cliete',
  `e_mail` varchar(60) collate utf8_unicode_ci NOT NULL default '0' COMMENT 'dias de credito para que pague el cliente',
  `limite_credito` float NOT NULL default '0' COMMENT 'Limite de credito otorgado al cliente',
  `descuento` tinyint(4) NOT NULL default '0' COMMENT 'Taza porcentual de descuento de 0 a 100',
  `activo` tinyint(2) NOT NULL default '1' COMMENT 'Indica si la cuenta esta activada o desactivada',
  PRIMARY KEY  (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=201 ;

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
  PRIMARY KEY  (`id_compra`),
  KEY `compras_proveedor` (`id_proveedor`),
  KEY `compras_sucursal` (`id_sucursal`),
  KEY `compras_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `corte`
--

CREATE TABLE IF NOT EXISTS `corte` (
  `num_corte` int(11) NOT NULL auto_increment COMMENT 'numero de corte',
  `anio` year(4) NOT NULL COMMENT 'año del corte',
  `inicio` timestamp NOT NULL default '0000-00-00 00:00:00' COMMENT 'año del corte',
  `fin` timestamp NOT NULL default '0000-00-00 00:00:00' COMMENT 'fecha de fin del corte',
  `ventas` float NOT NULL COMMENT 'ventas al contado en ese periodo',
  `abonosVentas` float NOT NULL COMMENT 'pagos de abonos en este periodo',
  `compras` float NOT NULL COMMENT 'compras realizadas en ese periodo',
  `AbonosCompra` float NOT NULL COMMENT 'pagos realizados en ese periodo',
  `gastos` float NOT NULL COMMENT 'gastos echos en ese periodo',
  `ingresos` float NOT NULL COMMENT 'ingresos obtenidos en ese periodo',
  `gananciasNetas` float NOT NULL COMMENT 'ganancias netas dentro del periodo',
  PRIMARY KEY  (`num_corte`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `cotizacion`
--

CREATE TABLE IF NOT EXISTS `cotizacion` (
  `id_cotizacion` int(11) NOT NULL auto_increment COMMENT 'id de la cotizacion',
  `id_cliente` int(11) NOT NULL COMMENT 'id del cliente',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'fecha de cotizacion',
  `subtotal` float NOT NULL COMMENT 'subtotal de la cotizacion',
  `iva` float NOT NULL COMMENT 'iva sobre el subtotal',
  `id_sucursal` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY  (`id_cotizacion`),
  KEY `cotizacion_cliente` (`id_cliente`),
  KEY `fk_cotizacion_1` (`id_sucursal`),
  KEY `fk_cotizacion_2` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_compra`
--

CREATE TABLE IF NOT EXISTS `detalle_compra` (
  `id_compra` int(11) NOT NULL COMMENT 'id de la compra',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `cantidad` float NOT NULL COMMENT 'cantidad comprada',
  `precio` float NOT NULL COMMENT 'costo de compra',
  `peso_arpillaPagado` float default '0',
  `peso_arpillaReal` float default '0',
  PRIMARY KEY  (`id_compra`,`id_producto`),
  KEY `detalle_compra_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_corte`
--

CREATE TABLE IF NOT EXISTS `detalle_corte` (
  `num_corte` int(11) NOT NULL COMMENT 'id del corte al que hace referencia',
  `nombre` varchar(100) NOT NULL COMMENT 'nombre del encargado de sucursal al momento del corte',
  `total` float NOT NULL COMMENT 'total que le corresponde al encargado al momento del corte',
  `deben` float NOT NULL COMMENT 'lo que deben en la sucursal del encargado al momento del corte',
  PRIMARY KEY  (`num_corte`,`nombre`),
  KEY `corte_detalleCorte` (`num_corte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_cotizacion`
--

CREATE TABLE IF NOT EXISTS `detalle_cotizacion` (
  `id_cotizacion` int(11) NOT NULL COMMENT 'id de la cotizacion',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `cantidad` float NOT NULL COMMENT 'cantidad cotizado',
  `precio` float NOT NULL COMMENT 'precio al que cotizo el producto',
  PRIMARY KEY  (`id_cotizacion`,`id_producto`),
  KEY `detalle_cotizacion_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `encargado`
--

CREATE TABLE IF NOT EXISTS `encargado` (
  `id_usuario` int(11) NOT NULL COMMENT 'Este id es el del usuario encargado de su sucursal',
  `porciento` float NOT NULL COMMENT 'este es el porciento de las ventas que le tocan al encargado',
  PRIMARY KEY  (`id_usuario`),
  KEY `fk_encargado_1` (`id_usuario`),
  KEY `usuario_encargado` (`id_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `factura_venta`
--

CREATE TABLE IF NOT EXISTS `factura_venta` (
  `folio` varchar(15) collate utf8_unicode_ci NOT NULL COMMENT 'folio que tiene la factura',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la factura',
  PRIMARY KEY  (`folio`),
  KEY `factura_venta_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gastos`
--

CREATE TABLE IF NOT EXISTS `gastos` (
  `id_gasto` int(11) NOT NULL auto_increment COMMENT 'id para identificar el gasto',
  `concepto` varchar(100) NOT NULL COMMENT 'concepto en lo que se gasto',
  `monto` float NOT NULL COMMENT 'lo que costo este gasto',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'fecha del gasto',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en la que se hizo el gasto',
  `id_usuario` int(11) NOT NULL COMMENT 'usuario que registro el gasto',
  PRIMARY KEY  (`id_gasto`),
  KEY `fk_gastos_1` (`id_usuario`),
  KEY `usuario_gasto` (`id_usuario`),
  KEY `sucursal_gasto` (`id_sucursal`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=211 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `grupos_usuarios`
--

CREATE TABLE IF NOT EXISTS `grupos_usuarios` (
  `id_grupo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY  (`id_grupo`,`id_usuario`),
  KEY `fk_grupos_usuarios_1` (`id_grupo`),
  KEY `fk_grupos_usuarios_2` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `impuesto`
--

CREATE TABLE IF NOT EXISTS `impuesto` (
  `id_impuesto` int(11) NOT NULL auto_increment,
  `descripcion` varchar(100) collate utf8_unicode_ci NOT NULL,
  `valor` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  PRIMARY KEY  (`id_impuesto`),
  KEY `fk_impuesto_1` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ingresos`
--

CREATE TABLE IF NOT EXISTS `ingresos` (
  `id_ingreso` int(11) NOT NULL auto_increment COMMENT 'id para identificar el ingreso',
  `concepto` varchar(100) NOT NULL COMMENT 'concepto en lo que se ingreso',
  `monto` float NOT NULL COMMENT 'lo que costo este ingreso',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'fecha del ingreso',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en la que se hizo el ingreso',
  `id_usuario` int(11) NOT NULL COMMENT 'usuario que registro el ingreso',
  PRIMARY KEY  (`id_ingreso`),
  KEY `fk_ingresos_1` (`id_usuario`),
  KEY `usuario_ingreso` (`id_usuario`),
  KEY `sucursal_ingreso` (`id_sucursal`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=202 ;

-- --------------------------------------------------------

--
-- Table structure for table `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `id_producto` int(11) NOT NULL auto_increment COMMENT 'id del producto',
  `nombre` varchar(90) collate utf8_unicode_ci NOT NULL COMMENT 'Descripcion o nombre del producto',
  `denominacion` varchar(30) collate utf8_unicode_ci NOT NULL COMMENT 'es lo que se le mostrara a los clientes',
  PRIMARY KEY  (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=202 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `pagos_venta`
--

CREATE TABLE IF NOT EXISTS `pagos_venta` (
  `id_pago` int(11) NOT NULL auto_increment COMMENT 'id de pago del cliente',
  `id_venta` int(11) NOT NULL COMMENT 'id de la venta a la que se esta pagando',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'Fecha en que se registro el pago',
  `monto` float NOT NULL COMMENT 'total de credito del cliente',
  PRIMARY KEY  (`id_pago`),
  KEY `pagos_venta_venta` (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=207 ;

-- --------------------------------------------------------

--
-- Table structure for table `permisos`
--

CREATE TABLE IF NOT EXISTS `permisos` (
  `id_permiso` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY  (`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=43 ;

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
  `activo` tinyint(2) default NULL COMMENT 'Indica si la cuenta esta activada o desactivada',
  PRIMARY KEY  (`id_proveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
  `id_sucursal` int(11) NOT NULL auto_increment COMMENT 'Identificador de cada sucursal',
  `descripcion` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'nombre o descripcion de sucursal',
  `direccion` varchar(200) collate utf8_unicode_ci NOT NULL COMMENT 'direccion de la sucursal',
  `e_mail` varchar(200) collate utf8_unicode_ci default NULL COMMENT 'La dirección de correo electrónico',
  `rfc` varchar(20) collate utf8_unicode_ci default NULL COMMENT 'El RFC de la sucursal',
  `telefono` varchar(20) collate utf8_unicode_ci default NULL COMMENT 'El telefono de la sucursal',
  `token` varchar(512) collate utf8_unicode_ci default NULL COMMENT 'Token de seguridad para esta sucursal',
  `letras_factura` varchar(10) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL auto_increment COMMENT 'identificador del usuario',
  `nombre` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'nombre del empleado',
  `usuario` varchar(50) collate utf8_unicode_ci NOT NULL,
  `contrasena` varchar(128) collate utf8_unicode_ci NOT NULL,
  `id_sucursal` int(11) NOT NULL COMMENT 'Id de la sucursal a que pertenece',
  `activo` tinyint(1) NOT NULL COMMENT 'Guarda el estado de la cuenta del usuario',
  `finger_token` varchar(1024) collate utf8_unicode_ci default NULL COMMENT 'Una cadena que sera comparada con el token que mande el scanner de huella digital',
  `last_check` timestamp NULL default NULL COMMENT 'Ultima vez que este usuario hizo una peticion al servidor',
  PRIMARY KEY  (`id_usuario`),
  KEY `fk_usuario_1` (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=35 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=114 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_compras`
--
CREATE TABLE IF NOT EXISTS `view_compras` (
`id_compra` int(11)
,`proveedor` varchar(30)
,`id_proveedor` int(11)
,`tipo_compra` enum('credito','contado')
,`fecha` timestamp
,`subtotal` float
,`iva` float
,`sucursal` varchar(100)
,`id_sucursal` int(11)
,`usuario` varchar(100)
,`id_usuario` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_detalle_compra`
--
CREATE TABLE IF NOT EXISTS `view_detalle_compra` (
`id_compra` int(11)
,`id_producto` int(11)
,`denominacion` varchar(30)
,`cantidad` float
,`precio` float
,`fecha` timestamp
,`tipo_compra` enum('credito','contado')
,`id_sucursal` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_detalle_venta`
--
CREATE TABLE IF NOT EXISTS `view_detalle_venta` (
`id_venta` int(11)
,`id_producto` int(11)
,`denominacion` varchar(30)
,`cantidad` float
,`precio` float
,`fecha` timestamp
,`tipo_venta` enum('credito','contado')
,`id_sucursal` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_gastos`
--
CREATE TABLE IF NOT EXISTS `view_gastos` (
`id_gasto` int(11)
,`monto` float
,`fecha` timestamp
,`sucursal` varchar(100)
,`id_sucursal` int(11)
,`usuario` varchar(100)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_ingresos`
--
CREATE TABLE IF NOT EXISTS `view_ingresos` (
`id_ingreso` int(11)
,`monto` float
,`fecha` timestamp
,`sucursal` varchar(100)
,`id_sucursal` int(11)
,`usuario` varchar(100)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_ventas`
--
CREATE TABLE IF NOT EXISTS `view_ventas` (
`id_venta` int(11)
,`cliente` varchar(100)
,`id_cliente` int(11)
,`tipo_venta` enum('credito','contado')
,`fecha` timestamp
,`subtotal` float
,`iva` float
,`sucursal` varchar(100)
,`id_sucursal` int(11)
,`usuario` varchar(100)
,`id_usuario` int(11)
);
-- --------------------------------------------------------

--
-- Structure for view `view_compras`
--
DROP TABLE IF EXISTS `view_compras`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_compras` AS select `c`.`id_compra` AS `id_compra`,`p`.`nombre` AS `proveedor`,`p`.`id_proveedor` AS `id_proveedor`,`c`.`tipo_compra` AS `tipo_compra`,`c`.`fecha` AS `fecha`,`c`.`subtotal` AS `subtotal`,`c`.`iva` AS `iva`,`s`.`descripcion` AS `sucursal`,`c`.`id_sucursal` AS `id_sucursal`,`u`.`nombre` AS `usuario`,`c`.`id_usuario` AS `id_usuario` from (`compras` `c` join ((`proveedor` `p` join `sucursal` `s`) join `usuario` `u`) on(((`c`.`id_proveedor` = `p`.`id_proveedor`) and (`c`.`id_sucursal` = `s`.`id_sucursal`) and (`c`.`id_usuario` = `u`.`id_usuario`))));

-- --------------------------------------------------------

--
-- Structure for view `view_detalle_compra`
--
DROP TABLE IF EXISTS `view_detalle_compra`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_detalle_compra` AS select `d`.`id_compra` AS `id_compra`,`d`.`id_producto` AS `id_producto`,`i`.`denominacion` AS `denominacion`,`d`.`cantidad` AS `cantidad`,`d`.`precio` AS `precio`,`c`.`fecha` AS `fecha`,`c`.`tipo_compra` AS `tipo_compra`,`c`.`id_sucursal` AS `id_sucursal` from (`detalle_compra` `d` join (`inventario` `i` join `compras` `c`) on(((`d`.`id_compra` = `c`.`id_compra`) and (`d`.`id_producto` = `i`.`id_producto`))));

-- --------------------------------------------------------

--
-- Structure for view `view_detalle_venta`
--
DROP TABLE IF EXISTS `view_detalle_venta`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_detalle_venta` AS select `d`.`id_venta` AS `id_venta`,`d`.`id_producto` AS `id_producto`,`i`.`denominacion` AS `denominacion`,`d`.`cantidad` AS `cantidad`,`d`.`precio` AS `precio`,`v`.`fecha` AS `fecha`,`v`.`tipo_venta` AS `tipo_venta`,`v`.`id_sucursal` AS `id_sucursal` from (`detalle_venta` `d` join (`inventario` `i` join `ventas` `v`) on(((`d`.`id_venta` = `v`.`id_venta`) and (`d`.`id_producto` = `i`.`id_producto`))));

-- --------------------------------------------------------

--
-- Structure for view `view_gastos`
--
DROP TABLE IF EXISTS `view_gastos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_gastos` AS select `g`.`id_gasto` AS `id_gasto`,`g`.`monto` AS `monto`,`g`.`fecha` AS `fecha`,`s`.`descripcion` AS `sucursal`,`g`.`id_sucursal` AS `id_sucursal`,`u`.`nombre` AS `usuario` from (`gastos` `g` join (`sucursal` `s` join `usuario` `u`) on(((`g`.`id_sucursal` = `s`.`id_sucursal`) and (`g`.`id_usuario` = `u`.`id_usuario`))));

-- --------------------------------------------------------

--
-- Structure for view `view_ingresos`
--
DROP TABLE IF EXISTS `view_ingresos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_ingresos` AS select `i`.`id_ingreso` AS `id_ingreso`,`i`.`monto` AS `monto`,`i`.`fecha` AS `fecha`,`s`.`descripcion` AS `sucursal`,`i`.`id_sucursal` AS `id_sucursal`,`u`.`nombre` AS `usuario` from (`ingresos` `i` join (`sucursal` `s` join `usuario` `u`) on(((`i`.`id_sucursal` = `s`.`id_sucursal`) and (`i`.`id_usuario` = `u`.`id_usuario`))));

-- --------------------------------------------------------

--
-- Structure for view `view_ventas`
--
DROP TABLE IF EXISTS `view_ventas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_ventas` AS select `v`.`id_venta` AS `id_venta`,`c`.`nombre` AS `cliente`,`v`.`id_cliente` AS `id_cliente`,`v`.`tipo_venta` AS `tipo_venta`,`v`.`fecha` AS `fecha`,`v`.`subtotal` AS `subtotal`,`v`.`iva` AS `iva`,`s`.`descripcion` AS `sucursal`,`v`.`id_sucursal` AS `id_sucursal`,`u`.`nombre` AS `usuario`,`v`.`id_usuario` AS `id_usuario` from (`ventas` `v` join ((`cliente` `c` join `sucursal` `s`) join `usuario` `u`) on(((`v`.`id_cliente` = `c`.`id_cliente`) and (`v`.`id_sucursal` = `s`.`id_sucursal`) and (`v`.`id_usuario` = `u`.`id_usuario`))));

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
-- Constraints for table `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD CONSTRAINT `cotizacion_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cotizacion_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cotizacion_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Constraints for table `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON UPDATE CASCADE;

--
-- Constraints for table `detalle_corte`
--
ALTER TABLE `detalle_corte`
  ADD CONSTRAINT `corte_detalleCorte` FOREIGN KEY (`num_corte`) REFERENCES `corte` (`num_corte`) ON UPDATE CASCADE;

--
-- Constraints for table `detalle_cotizacion`
--
ALTER TABLE `detalle_cotizacion`
  ADD CONSTRAINT `detalle_cotizacion_ibfk_1` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_cotizacion_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `factura_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON UPDATE CASCADE;

--
-- Constraints for table `grupos_usuarios`
--
ALTER TABLE `grupos_usuarios`
  ADD CONSTRAINT `grupos_usuarios_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `grupos_usuarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Constraints for table `impuesto`
--
ALTER TABLE `impuesto`
  ADD CONSTRAINT `impuesto_ibfk_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE;

--
-- Constraints for table `pagos_compra`
--
ALTER TABLE `pagos_compra`
  ADD CONSTRAINT `pagos_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE;

--
-- Constraints for table `pagos_venta`
--
ALTER TABLE `pagos_venta`
  ADD CONSTRAINT `pagos_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON UPDATE CASCADE;

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

