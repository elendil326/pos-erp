-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 11-04-2013 a las 22:38:53
-- Versión del servidor: 5.5.29
-- Versión de PHP: 5.4.6-1ubuntu1.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pos_instance_116`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `abasto_proveedor`
--

CREATE TABLE IF NOT EXISTS `abasto_proveedor` (
  `id_abasto_proveedor` int(11) NOT NULL AUTO_INCREMENT,
  `id_proveedor` int(11) NOT NULL COMMENT 'Id del proveedor que abastese, se usara -1 cuando la entrada sea por inventario fisico',
  `id_almacen` int(11) NOT NULL COMMENT 'Id del almacen abastesido',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario que registra',
  `fecha` int(11) NOT NULL COMMENT 'Fecha del movimiento',
  `motivo` varchar(255) NOT NULL COMMENT 'Motivo de la entrada del producto',
  PRIMARY KEY (`id_abasto_proveedor`),
  KEY `abasto_proveedor_ibfk_3` (`id_usuario`),
  KEY `abasto_proveedor_ibfk_1` (`id_proveedor`),
  KEY `abasto_proveedor_ibfk_2` (`id_almacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Registro de abastesimientos de un proveedor' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `abono_compra`
--

CREATE TABLE IF NOT EXISTS `abono_compra` (
  `id_abono_compra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id prestamo al que se le abona',
  `id_compra` int(11) NOT NULL COMMENT 'Id de la compra',
  `id_sucursal` int(11) DEFAULT NULL,
  `monto` float NOT NULL,
  `id_caja` int(11) DEFAULT NULL COMMENT 'Id de la caja donde se registra el abono',
  `id_deudor` int(11) NOT NULL COMMENT 'Id del usuario que abona',
  `id_receptor` int(11) NOT NULL COMMENT 'Id del usuario que registra el abono',
  `nota` varchar(255) DEFAULT NULL COMMENT 'Nota del abono',
  `fecha` int(11) NOT NULL COMMENT 'Fecha en que se realiza el abono',
  `tipo_de_pago` enum('cheque','tarjeta','efectivo') NOT NULL COMMENT 'Si el tipo de pago es con tarjeta, con cheque, o en efectivo',
  `cancelado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Si este abono es cancelado',
  `motivo_cancelacion` varchar(255) DEFAULT NULL COMMENT 'Motivo por el cual se realiza la cancelacion',
  PRIMARY KEY (`id_abono_compra`),
  KEY `abono_compra_ibfk_5` (`id_receptor`),
  KEY `abono_compra_ibfk_1` (`id_compra`),
  KEY `abono_compra_ibfk_2` (`id_sucursal`),
  KEY `abono_compra_ibfk_3` (`id_caja`),
  KEY `abono_compra_ibfk_4` (`id_deudor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle de la compra y los abonos de la misma' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `abono_prestamo`
--

CREATE TABLE IF NOT EXISTS `abono_prestamo` (
  `id_abono_prestamo` int(11) NOT NULL AUTO_INCREMENT,
  `id_prestamo` int(11) NOT NULL COMMENT 'Id prestamo al que se le abona',
  `id_sucursal` int(11) DEFAULT NULL,
  `monto` float NOT NULL,
  `id_caja` int(11) DEFAULT NULL COMMENT 'Id de la caja donde se registra el abono',
  `id_deudor` int(11) NOT NULL COMMENT 'Id del usuario que abona',
  `id_receptor` int(11) NOT NULL COMMENT 'Id del usuario que registra el abono',
  `nota` varchar(255) DEFAULT NULL COMMENT 'Nota del abono',
  `fecha` int(11) NOT NULL COMMENT 'Fecha en que se realiza el abono',
  `tipo_de_pago` enum('cheque','tarjeta','efectivo') NOT NULL COMMENT 'Si el tipo de pago es con tarjeta, con cheque, o en efectivo',
  `cancelado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Si este abono es cancelado',
  `motivo_cancelacion` varchar(255) DEFAULT NULL COMMENT 'Motivo por el cual se realiza la cancelacion',
  PRIMARY KEY (`id_abono_prestamo`),
  KEY `abono_prestamo_ibfk_5` (`id_receptor`),
  KEY `abono_prestamo_ibfk_1` (`id_prestamo`),
  KEY `abono_prestamo_ibfk_2` (`id_sucursal`),
  KEY `abono_prestamo_ibfk_3` (`id_caja`),
  KEY `abono_prestamo_ibfk_4` (`id_deudor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle abono prestamo' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `abono_venta`
--

CREATE TABLE IF NOT EXISTS `abono_venta` (
  `id_abono_venta` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la venta',
  `id_venta` int(11) NOT NULL COMMENT 'Id prestamo al que se le abona',
  `id_sucursal` int(11) DEFAULT NULL,
  `monto` float NOT NULL,
  `id_caja` int(11) DEFAULT NULL COMMENT 'Id de la caja donde se registra el abono',
  `id_deudor` int(11) NOT NULL COMMENT 'Id del usuario que abona',
  `id_receptor` int(11) NOT NULL COMMENT 'Id del usuario que registra el abono',
  `nota` varchar(255) DEFAULT NULL COMMENT 'Nota del abono',
  `fecha` int(11) NOT NULL COMMENT 'Fecha en que se realiza el abono',
  `tipo_de_pago` enum('cheque','tarjeta','efectivo') NOT NULL COMMENT 'Si el tipo de pago es con tarjeta, con cheque, o en efectivo',
  `cancelado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Si este abono es cancelado',
  `motivo_cancelacion` varchar(255) DEFAULT NULL COMMENT 'Motivo por el cual se realiza la cancelacion',
  PRIMARY KEY (`id_abono_venta`),
  KEY `abono_venta_ibfk_5` (`id_receptor`),
  KEY `abono_venta_ibfk_1` (`id_venta`),
  KEY `abono_venta_ibfk_2` (`id_sucursal`),
  KEY `abono_venta_ibfk_3` (`id_caja`),
  KEY `abono_venta_ibfk_4` (`id_deudor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle de la venta y sus abonos' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen`
--

CREATE TABLE IF NOT EXISTS `almacen` (
  `id_almacen` int(11) NOT NULL AUTO_INCREMENT,
  `id_sucursal` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL COMMENT 'Id de la empresa de la cual pertenecen los productos que se almacenaran en este almacen',
  `id_tipo_almacen` int(11) NOT NULL COMMENT 'el tipo de almacen de que este tipo es',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del almacen',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion larga del almacen',
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Si el almacen esta activo o no',
  PRIMARY KEY (`id_almacen`),
  KEY `id_tipo_almacen` (`id_tipo_almacen`),
  KEY `almacen_ibfk_1` (`id_sucursal`),
  KEY `almacen_ibfk_2` (`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apertura_caja`
--

CREATE TABLE IF NOT EXISTS `apertura_caja` (
  `id_apertura_caja` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de la apertura de la caja',
  `id_caja` int(11) NOT NULL COMMENT 'Id de la caja que se abre',
  `fecha` int(11) NOT NULL COMMENT 'Fecha en que se realizo la apertura de caja',
  `saldo` float NOT NULL COMMENT 'Saldo con que inicia operaciones la caja',
  `id_cajero` int(11) DEFAULT NULL COMMENT 'Id del usuario que realizarÃƒÆ’Ã‚Â¡ las funciones de cajero',
  PRIMARY KEY (`id_apertura_caja`),
  KEY `apertura_caja_ibfk_2` (`id_cajero`),
  KEY `apertura_caja_ibfk_1` (`id_caja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla que lleva el control de la apertura de cajas' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autorizacion`
--

CREATE TABLE IF NOT EXISTS `autorizacion` (
  `id_autorizacion` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_autorizacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `billete`
--

CREATE TABLE IF NOT EXISTS `billete` (
  `id_billete` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla billete',
  `id_moneda` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `valor` float NOT NULL,
  `foto_billete` varchar(100) DEFAULT NULL COMMENT 'Url de la foto del billete',
  `activo` tinyint(1) NOT NULL COMMENT 'Si este billete esta activo o ya no se usa',
  PRIMARY KEY (`id_billete`),
  KEY `billete_ibfk_1` (`id_moneda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Billetes para llevar control en la caja' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `billete_apertura_caja`
--

CREATE TABLE IF NOT EXISTS `billete_apertura_caja` (
  `id_billete` int(11) NOT NULL,
  `id_apertura_caja` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL COMMENT 'Cantidad de billetes dejados en la apertura de caja',
  PRIMARY KEY (`id_billete`,`id_apertura_caja`),
  KEY `billete_apertura_caja_ibfk_2` (`id_apertura_caja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle apertura de caja billetes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `billete_caja`
--

CREATE TABLE IF NOT EXISTS `billete_caja` (
  `id_billete` int(11) NOT NULL,
  `id_caja` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL COMMENT 'Cantidad de estos billetes en la caja',
  PRIMARY KEY (`id_billete`,`id_caja`),
  KEY `billete_caja_ibfk_2` (`id_caja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle billetes caja';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `billete_cierre_caja`
--

CREATE TABLE IF NOT EXISTS `billete_cierre_caja` (
  `id_billete` int(11) NOT NULL,
  `id_cierre_caja` int(11) NOT NULL,
  `cantidad_encontrada` int(11) NOT NULL COMMENT 'Cantidad de billetes encontrados en el cierre de caja',
  `cantidad_sobrante` int(11) NOT NULL COMMENT 'Cantidad de billetes saobrante en el cierre de caja',
  `cantidad_faltante` int(1) NOT NULL DEFAULT '0' COMMENT 'Cantidad de billetes faltante en el cierre de caja',
  PRIMARY KEY (`id_billete`,`id_cierre_caja`),
  KEY `billete_cierre_caja_ibfk_2` (`id_cierre_caja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle billetes cierre de caja';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `billete_corte_caja`
--

CREATE TABLE IF NOT EXISTS `billete_corte_caja` (
  `id_billete` int(11) NOT NULL,
  `id_corte_caja` int(11) NOT NULL,
  `cantidad_encontrada` int(11) NOT NULL COMMENT 'Cantidad de este billete encontrado en la caja al hacer el corte',
  `cantidad_dejada` int(11) NOT NULL COMMENT 'Cantidad de este billete dejada al finalizar el corte',
  `cantidad_sobrante` int(11) NOT NULL DEFAULT '0' COMMENT 'Cantidad de billetes saobrante en el corte de caja',
  `cantidad_faltante` int(11) NOT NULL DEFAULT '0' COMMENT 'Cantidad de billetes faltante en el corte de caja',
  PRIMARY KEY (`id_billete`,`id_corte_caja`),
  KEY `billete_corte_caja_ibfk_2` (`id_corte_caja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle billetes corte de caja';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE IF NOT EXISTS `caja` (
  `id_caja` int(11) NOT NULL AUTO_INCREMENT,
  `id_sucursal` int(11) NOT NULL COMMENT 'a que sucursal pertenece esta caja',
  `token` varchar(32) NOT NULL COMMENT 'el token que genero el pos client',
  `descripcion` varchar(32) DEFAULT NULL COMMENT 'alguna descripcion para esta caja',
  `abierta` tinyint(1) NOT NULL COMMENT 'Si esta abierta la caja o no',
  `saldo` float NOT NULL DEFAULT '0' COMMENT 'Saldo actual de la caja',
  `control_billetes` tinyint(1) NOT NULL COMMENT 'Si esta caja esta llevando control de billetes o no',
  `activa` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Si la caja esta activa o ha sido eliminada',
  PRIMARY KEY (`id_caja`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo_cuentas`
--

CREATE TABLE IF NOT EXISTS `catalogo_cuentas` (
  `id_catalogo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'El id del catalogo de cuentas',
  `descripcion` varchar(150) NOT NULL COMMENT 'La descripción del catalogo de cuentas.',
  `id_empresa` int(11) NOT NULL COMMENT 'El id de la empresa a la que va vinculada ésta cuenta',
  PRIMARY KEY (`id_catalogo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_unidad_medida`
--

CREATE TABLE IF NOT EXISTS `categoria_unidad_medida` (
  `id_categoria_unidad_medida` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria de la tabla',
  `descripcion` varchar(50) NOT NULL COMMENT 'Descripcion de la categoria unidad de medida',
  `activa` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_categoria_unidad_medida`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Categorias de unidad de medida' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cheque`
--

CREATE TABLE IF NOT EXISTS `cheque` (
  `id_cheque` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del cheque',
  `nombre_banco` varchar(100) NOT NULL COMMENT 'Nombre del banco del que se expide el cheque',
  `monto` float NOT NULL COMMENT 'Monto del cheque',
  `numero` varchar(4) NOT NULL COMMENT 'Los ultimos cuatro numeros del cheque',
  `expedido` tinyint(1) NOT NULL COMMENT 'Verdadero si el cheque es expedido por la empresa, falso si es recibido',
  `id_usuario` int(11) DEFAULT NULL COMMENT 'Id del usuario que registra el cheque',
  PRIMARY KEY (`id_cheque`),
  KEY `cheque_ibfk_1` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cheque_abono_compra`
--

CREATE TABLE IF NOT EXISTS `cheque_abono_compra` (
  `id_cheque` int(11) NOT NULL COMMENT 'Id del cheque con el que se abono',
  `id_abono_compra` int(11) NOT NULL COMMENT 'Id del abono que se pago con ese cheque',
  PRIMARY KEY (`id_cheque`,`id_abono_compra`),
  KEY `cheque_abono_compra_ibfk_2` (`id_abono_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle cheque abono compra';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cheque_abono_prestamo`
--

CREATE TABLE IF NOT EXISTS `cheque_abono_prestamo` (
  `id_cheque` int(11) NOT NULL COMMENT 'Id del cheque con el que se abono',
  `id_abono_prestamo` int(11) NOT NULL COMMENT 'Id del abono que se pago con ese cheque',
  PRIMARY KEY (`id_cheque`,`id_abono_prestamo`),
  KEY `cheque_abono_prestamo_ibfk_2` (`id_abono_prestamo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle cheque abono prestamo';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cheque_abono_venta`
--

CREATE TABLE IF NOT EXISTS `cheque_abono_venta` (
  `id_cheque` int(11) NOT NULL COMMENT 'Id del cheque con el que se abono',
  `id_abono_venta` int(11) NOT NULL COMMENT 'Id del abono que se pago con ese cheque',
  PRIMARY KEY (`id_cheque`,`id_abono_venta`),
  KEY `cheque_abono_venta_ibfk_2` (`id_abono_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle cheque abono venta';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cheque_compra`
--

CREATE TABLE IF NOT EXISTS `cheque_compra` (
  `id_cheque` int(11) NOT NULL COMMENT 'Id del cheque con el que se compro',
  `id_compra` int(11) NOT NULL COMMENT 'Id de la compra que se pago con ese cheque',
  PRIMARY KEY (`id_cheque`,`id_compra`),
  KEY `cheque_compra_ibfk_2` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle cheque compra';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cheque_venta`
--

CREATE TABLE IF NOT EXISTS `cheque_venta` (
  `id_cheque` int(11) NOT NULL COMMENT 'Id del cheque con el que se pago la venta',
  `id_venta` int(11) NOT NULL COMMENT 'Id de la venta que se pago con el cheque',
  PRIMARY KEY (`id_cheque`,`id_venta`),
  KEY `cheque_venta_ibfk_2` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle cheque venta';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_caja`
--

CREATE TABLE IF NOT EXISTS `cierre_caja` (
  `id_cierre_caja` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del cierre de caja',
  `id_caja` int(11) NOT NULL COMMENT 'Id de la caja que se cierra',
  `id_cajero` int(11) DEFAULT NULL COMMENT 'Id del usuario que realiza las funciones de cajero al momento de cerrar la caja',
  `fecha` int(11) NOT NULL COMMENT 'fecha en que se realiza la operacion',
  `saldo_real` float NOT NULL COMMENT 'Saldo de la caja',
  `saldo_esperado` float NOT NULL COMMENT 'Saldo que deberÃƒÆ’Ã‚Â­a de haber en la caja despuÃƒÆ’Ã‚Â©s de todos los movimientos del dÃƒÆ’Ã‚Â­a',
  PRIMARY KEY (`id_cierre_caja`),
  KEY `cierre_caja_ibfk_2` (`id_cajero`),
  KEY `cierre_caja_ibfk_1` (`id_caja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla que lleva el control del cierre de cajas' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudad`
--

CREATE TABLE IF NOT EXISTS `ciudad` (
  `id_ciudad` int(11) NOT NULL AUTO_INCREMENT,
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  PRIMARY KEY (`id_ciudad`),
  KEY `id_estado` (`id_estado`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion_cliente`
--

CREATE TABLE IF NOT EXISTS `clasificacion_cliente` (
  `id_clasificacion_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `clave_interna` varchar(20) NOT NULL COMMENT 'Clave interna del tipo de cliente',
  `nombre` varchar(16) NOT NULL COMMENT 'un nombre corto para esta clasificacion',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion larga de la clasificacion del cliente',
  `id_tarifa_compra` int(11) NOT NULL COMMENT 'Id de la tarifa de compra por default para esta clasificacion de cliente',
  `id_tarifa_venta` int(11) NOT NULL COMMENT 'Id de la tarifa de venta por default para esta clasificacion de cliente',
  PRIMARY KEY (`id_clasificacion_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion_producto`
--

CREATE TABLE IF NOT EXISTS `clasificacion_producto` (
  `id_clasificacion_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(64) NOT NULL COMMENT 'el nombre de esta clasificacion',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion larga de la clasificacion del producto',
  `id_categoria_padre` int(11) DEFAULT NULL COMMENT 'numero de meses que tendran los productos de esta clasificacion',
  `activa` tinyint(1) NOT NULL COMMENT 'Si esta claificacion esta activa',
  PRIMARY KEY (`id_clasificacion_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion_proveedor`
--

CREATE TABLE IF NOT EXISTS `clasificacion_proveedor` (
  `id_clasificacion_proveedor` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla clasificacion_proveedor',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de la clasificacion',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion larga de la clasificacion del proveedor',
  `activa` tinyint(1) NOT NULL COMMENT 'Si esta clasificacion esat activa o no',
  `id_tarifa_compra` int(11) NOT NULL COMMENT 'Id de la tarifa de compra por default para esta clasificacion de proveedor',
  `id_tarifa_venta` int(11) NOT NULL COMMENT 'Id de la tarifa de venta por default para esta clasificacion de proveedor',
  PRIMARY KEY (`id_clasificacion_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla que especifica las clasificaciones de proveedores' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion_servicio`
--

CREATE TABLE IF NOT EXISTS `clasificacion_servicio` (
  `id_clasificacion_servicio` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre del servicio',
  `garantia` int(11) DEFAULT NULL COMMENT 'Numero de meses de garantia que tendran los servicios de esta clasificacion los servicios ',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion larga de la clasificacion del servicio',
  `activa` tinyint(1) NOT NULL COMMENT 'Si esta categoria de servicio esta fija o no',
  PRIMARY KEY (`id_clasificacion_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_aval`
--

CREATE TABLE IF NOT EXISTS `cliente_aval` (
  `id_cliente` int(11) NOT NULL,
  `id_aval` int(11) NOT NULL,
  `tipo_aval` enum('hipoteca','prendario') NOT NULL,
  PRIMARY KEY (`id_cliente`,`id_aval`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Cartera de avales de clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_seguimiento`
--

CREATE TABLE IF NOT EXISTS `cliente_seguimiento` (
  `id_cliente_seguimiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` int(11) NOT NULL,
  `texto` text NOT NULL,
  PRIMARY KEY (`id_cliente_seguimiento`),
  KEY `id_usuario` (`id_usuario`,`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE IF NOT EXISTS `compra` (
  `id_compra` int(11) NOT NULL AUTO_INCREMENT,
  `id_caja` int(11) DEFAULT NULL COMMENT 'la caja donde se hizo la venta, esta puede ser null ya que un gerente puede vender en el sistema web',
  `id_compra_caja` int(11) DEFAULT NULL COMMENT 'el id unico de esta caja para las compras',
  `id_vendedor_compra` int(11) NOT NULL COMMENT 'El id del usuario que nos esta vendiendo, cliente, o proveedor, etc, en caso de sucursal es el valor negativo de esa suc',
  `tipo_de_compra` enum('contado','credito') NOT NULL COMMENT 'nota si esta fue compra a contado o a credito',
  `fecha` int(11) NOT NULL COMMENT 'la fecha de esta venta',
  `subtotal` float NOT NULL,
  `impuesto` float NOT NULL,
  `descuento` float DEFAULT NULL,
  `total` float NOT NULL COMMENT 'el total a pagar',
  `id_sucursal` int(11) DEFAULT NULL COMMENT 'el id de donde se hizo la venta, aunque ya tenemos en que caja se hizo, guardaremos la sucursal ya que la caja puede haberse ido ademas para hacer busquedas mas rapidas',
  `id_usuario` int(11) NOT NULL COMMENT 'el usuario que hizo esta compra',
  `id_empresa` int(11) NOT NULL COMMENT 'Id de la empresa que realiza la compra',
  `saldo` float NOT NULL COMMENT 'el saldo pendiente por abonar en esta compra',
  `cancelada` tinyint(1) NOT NULL COMMENT 'Si la compra ha sido cancelada o no',
  `tipo_de_pago` enum('cheque','tarjeta','efectivo') DEFAULT NULL COMMENT 'Si la compra fue pagada con tarjeta, cheque o efectivo',
  `retencion` float NOT NULL COMMENT 'Monto de retencion',
  PRIMARY KEY (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_arpilla`
--

CREATE TABLE IF NOT EXISTS `compra_arpilla` (
  `id_compra_arpilla` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla compra por arpilla',
  `id_compra` int(11) NOT NULL COMMENT 'Id de la compra a la que se refiere',
  `peso_origen` float DEFAULT NULL COMMENT 'El peso del camion en el origen',
  `fecha_origen` int(11) DEFAULT NULL COMMENT 'Fecha en la que se envÃƒÆ’Ã‚Â­o el embarque',
  `folio` varchar(11) DEFAULT NULL COMMENT 'Folio del camion',
  `numero_de_viaje` varchar(11) DEFAULT NULL COMMENT 'NÃƒÆ’Ã‚Âºmero de viaje',
  `peso_recibido` float NOT NULL COMMENT 'Peso del camion al llegar',
  `arpillas` float NOT NULL COMMENT 'Cantidad de arpillas recibidas',
  `peso_por_arpilla` float NOT NULL COMMENT 'El peso por arpilla promedio',
  `productor` varchar(64) DEFAULT NULL COMMENT 'Nombre del productor',
  `merma_por_arpilla` float NOT NULL COMMENT 'La merma de producto por arpilla',
  `total_origen` float DEFAULT NULL COMMENT 'El valor del embarque segÃƒÆ’Ã‚Âºn el proveedor',
  PRIMARY KEY (`id_compra_arpilla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla que detalla una compra realizada a un proveedor median' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_producto`
--

CREATE TABLE IF NOT EXISTS `compra_producto` (
  `id_compra` int(11) NOT NULL COMMENT 'Id de la compra',
  `id_producto` int(11) NOT NULL COMMENT 'Id del producto comprado',
  `cantidad` float NOT NULL COMMENT 'Cantidad del producto comprado, puede ser en kilogramos',
  `precio` float NOT NULL COMMENT 'Precio unitario del producto',
  `descuento` float NOT NULL COMMENT 'Descuento unitario del producto',
  `impuesto` float NOT NULL COMMENT 'Impuesto unitario del producto',
  `id_unidad` int(11) NOT NULL COMMENT 'Id de la unidad del producto',
  `retencion` float NOT NULL COMMENT 'Retencion unitaria del producto',
  PRIMARY KEY (`id_compra`,`id_producto`,`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle de la compra y los productos de la misma';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `concepto_gasto`
--

CREATE TABLE IF NOT EXISTS `concepto_gasto` (
  `id_concepto_gasto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla concepto gasto',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre del concepto',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion detallada del concepto',
  `monto` float DEFAULT NULL COMMENT 'monto del concepto si este es fijo siempre',
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Si este concepto de gasto esta activo',
  PRIMARY KEY (`id_concepto_gasto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Conceptos de gasto' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `concepto_ingreso`
--

CREATE TABLE IF NOT EXISTS `concepto_ingreso` (
  `id_concepto_ingreso` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del concepto de ingreso',
  `nombre` varchar(50) NOT NULL COMMENT 'nombre del concepto',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion del concepto',
  `monto` float DEFAULT NULL COMMENT 'Si el concepto tienen un monto fijo',
  `activo` tinyint(1) NOT NULL COMMENT 'Si este concepto de ingreso esta activo',
  PRIMARY KEY (`id_concepto_ingreso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Concepto de ingreso' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE IF NOT EXISTS `configuracion` (
  `id_configuracion` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(128) NOT NULL,
  `valor` varchar(2048) NOT NULL COMMENT 'Cadena en formato de JSON que describe una configuracion',
  `id_usuario` int(11) NOT NULL COMMENT 'id_usuario que realizo la ultima modificaciÃ³n ',
  `fecha` int(11) NOT NULL COMMENT 'fecha de la ultima modificaciÃ³n, descrita en formato UNIX ',
  PRIMARY KEY (`id_configuracion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Almacena las configuraciones bÃ¡sicas del sistema' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_empresa`
--

CREATE TABLE IF NOT EXISTS `configuracion_empresa` (
  `id_configuracion` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id_configuracion`,`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consignacion`
--

CREATE TABLE IF NOT EXISTS `consignacion` (
  `id_consignacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL COMMENT 'Id del usuario al que se le consignan los productos',
  `id_usuario` int(11) NOT NULL COMMENT 'el usuario que inicio la consigacion',
  `id_usuario_cancelacion` int(11) DEFAULT NULL COMMENT 'Id del usuario que cancela la consignacion',
  `fecha_creacion` int(11) NOT NULL COMMENT 'la fecha que se creo esta consignacion',
  `tipo_consignacion` enum('credito','contado') NOT NULL COMMENT 'Si al terminar la consignacion la venta sera a credito o de contado',
  `activa` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Si la consignacion esta activa',
  `cancelada` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Si esta consignacion fue cancelada o no',
  `motivo_cancelacion` varchar(255) DEFAULT NULL COMMENT 'Justificacion de la cancelacion si esta consginacion fue cancelada',
  `folio` varchar(50) NOT NULL COMMENT 'Folio de la consignacion',
  `fecha_termino` int(11) NOT NULL COMMENT 'Fecha en que se termino la consignacion, si la consignacion fue cancelada, la fecha de cancelacion se guardara aqui',
  `impuesto` float DEFAULT NULL COMMENT 'Monto generado por impuestos para esta consignacion',
  `descuento` float DEFAULT NULL COMMENT 'Monto a descontar de esta consignacion',
  `retencion` float DEFAULT NULL COMMENT 'Monto generado por retenciones',
  `saldo` float NOT NULL DEFAULT '0' COMMENT 'Saldo que ha sido abonado a la consignacion',
  PRIMARY KEY (`id_consignacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consignacion_producto`
--

CREATE TABLE IF NOT EXISTS `consignacion_producto` (
  `id_consignacion` int(11) NOT NULL COMMENT 'Id de la consignacion',
  `id_producto` int(11) NOT NULL COMMENT 'Id del producto consignado',
  `id_unidad` int(11) NOT NULL COMMENT 'Id de la unidad del producto',
  `cantidad` float NOT NULL COMMENT 'Cantidad de ese producto en esa consignacion',
  `impuesto` float DEFAULT NULL COMMENT 'Monto generado por impuestos para este producto',
  `descuento` float DEFAULT NULL COMMENT 'Monto a descontar de este producto',
  `retencion` float DEFAULT NULL COMMENT 'Monto generado por retenciones',
  `precio` float NOT NULL COMMENT 'Precio del producto por ser de consignacion',
  PRIMARY KEY (`id_consignacion`,`id_producto`,`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle de la consignacion con su producto';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `corte_de_caja`
--

CREATE TABLE IF NOT EXISTS `corte_de_caja` (
  `id_corte_de_caja` int(11) NOT NULL AUTO_INCREMENT,
  `id_caja` int(11) NOT NULL COMMENT 'Id de la caja a la que se le realiza el corte',
  `id_cajero` int(11) DEFAULT NULL COMMENT 'Id del usuario que funje como cajero',
  `id_cajero_nuevo` int(11) DEFAULT NULL COMMENT 'Id del usuario que entrara como nuevo cajero si es que hubo un cambio de turno con el corte de caja',
  `fecha` int(11) NOT NULL COMMENT 'fecha en la que se realiza el corte de caja',
  `saldo_real` float NOT NULL COMMENT 'Saldo actual de la caja',
  `saldo_esperado` float NOT NULL COMMENT 'Saldo que se espera de acuerdo a las ventas realizadas apartir del ÃƒÆ’Ã‚Âºltimo corte de caja o a la apertura de la misma',
  `saldo_final` float NOT NULL COMMENT 'Saldo que se deja en caja despuÃƒÆ’Ã‚Â©s de realizar el corte',
  PRIMARY KEY (`id_corte_de_caja`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `corte_de_sucursal`
--

CREATE TABLE IF NOT EXISTS `corte_de_sucursal` (
  `id_corte_sucursal` int(11) NOT NULL AUTO_INCREMENT,
  `id_sucursal` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `inicio` int(11) NOT NULL,
  `fin` int(11) NOT NULL,
  `fecha_corte` int(11) NOT NULL,
  PRIMARY KEY (`id_corte_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_contable`
--

CREATE TABLE IF NOT EXISTS `cuenta_contable` (
  `id_cuenta_contable` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(45) COLLATE utf8_spanish_ci NOT NULL COMMENT 'La clave que se le darÃ¡ a la nueva cuenta contable',
  `nivel` int(11) NOT NULL COMMENT 'Nivel de profundidad que tendra la cuenta en el arbol de cuentas',
  `consecutivo_en_nivel` int(11) NOT NULL COMMENT 'Dependiendo del nivel de profundidad de la cuenta contable, este valor indicara dentro de su nivel que numero consecutivo le corresponde con respecto a las mismas que estan en su mismo nivel',
  `nombre_cuenta` varchar(100) COLLATE utf8_spanish_ci NOT NULL COMMENT 'El nombre de la cuenta',
  `tipo_cuenta` enum('Balance','Estado de Resultados') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Si la cuenta es de Balance o Estado de Resultados',
  `naturaleza` enum('Acreedora','Deudora') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Si es deudora o acreedora',
  `clasificacion` enum('Activo Circulante','Activo Fijo','Activo Diferido','Pasivo Circulante','Pasivo Largo Plazo','Capital Contable','Ingresos','Egresos') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Clasificacion a la que pertenecera la cuenta',
  `cargos_aumentan` tinyint(1) NOT NULL COMMENT 'Si es igual 1 significa que en los movimientos cuando se cargue a esta cuenta los cargos aumentaran',
  `abonos_aumentan` tinyint(1) NOT NULL COMMENT 'si abonos aumentan es igual a 1 significa que en los movimientos los abonos aumentantaran',
  `es_cuenta_orden` tinyint(1) NOT NULL COMMENT 'si la cuenta no se contemplara en los estados financieros',
  `es_cuenta_mayor` tinyint(1) NOT NULL COMMENT 'Indica si la cuenta es de mayor',
  `afectable` tinyint(1) NOT NULL COMMENT 'indica si sobre esta cuenta ya se pueden realizar operaciones',
  `id_cuenta_padre` int(11) DEFAULT NULL COMMENT 'id de la cuenta de la que depende',
  `activa` tinyint(1) NOT NULL COMMENT 'Indica si la cuenta estÃ¡ disponible para su uso o no.',
  `id_catalogo_cuentas` int(11) NOT NULL COMMENT 'Id del catalogo de cuentas al que pertenece esta cuenta',
  PRIMARY KEY (`id_cuenta_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion_sobre_compra`
--

CREATE TABLE IF NOT EXISTS `devolucion_sobre_compra` (
  `id_devolucion_sobre_compra` int(11) NOT NULL AUTO_INCREMENT,
  `id_compra` int(11) NOT NULL COMMENT 'Id de la compra a cancelar',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario que realiza la devolucion',
  `fecha` int(11) NOT NULL COMMENT 'Fecha en que se realiza la devolucion',
  `motivo` varchar(255) NOT NULL COMMENT 'Motivo por el cual se realiza la devolucion',
  PRIMARY KEY (`id_devolucion_sobre_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion_sobre_venta`
--

CREATE TABLE IF NOT EXISTS `devolucion_sobre_venta` (
  `id_devolucion_sobre_venta` int(11) NOT NULL AUTO_INCREMENT,
  `id_venta` int(11) NOT NULL COMMENT 'Id de la venta a cancelar',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario que realiza la devolucion',
  `fecha` int(11) NOT NULL COMMENT 'Fecha en que se realiza la devolucion',
  `motivo` varchar(255) NOT NULL COMMENT 'Motivo por el cual se realiza la devolucion',
  PRIMARY KEY (`id_devolucion_sobre_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE IF NOT EXISTS `direccion` (
  `id_direccion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'El id de esta direccion',
  `calle` varchar(128) DEFAULT NULL,
  `numero_exterior` varchar(8) DEFAULT NULL,
  `numero_interior` varchar(8) DEFAULT NULL,
  `referencia` varchar(256) DEFAULT NULL,
  `colonia` varchar(128) DEFAULT NULL,
  `id_ciudad` int(11) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `telefono` varchar(32) DEFAULT NULL,
  `telefono2` varchar(32) DEFAULT NULL COMMENT 'Telefono alterno de la direccion',
  `ultima_modificacion` int(11) NOT NULL COMMENT 'La ultima vez que este registro se modifico',
  `id_usuario_ultima_modificacion` int(11) NOT NULL COMMENT 'quien fue el usuario que modifico este registro la ultima vez',
  PRIMARY KEY (`id_direccion`),
  KEY `id_ciudad` (`id_ciudad`,`id_usuario_ultima_modificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE IF NOT EXISTS `documento` (
  `id_documento` int(11) NOT NULL AUTO_INCREMENT,
  `id_documento_base` int(11) NOT NULL,
  `folio` varchar(8) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` int(11) NOT NULL,
  `id_operacion` int(11) NOT NULL,
  PRIMARY KEY (`id_documento`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_base`
--

CREATE TABLE IF NOT EXISTS `documento_base` (
  `id_documento_base` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) DEFAULT NULL,
  `id_sucursal` int(11) DEFAULT NULL,
  `nombre` varchar(32) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `json_impresion` longtext NOT NULL,
  `ultima_modificacion` int(11) NOT NULL,
  PRIMARY KEY (`id_documento_base`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicio`
--

CREATE TABLE IF NOT EXISTS `ejercicio` (
  `id_ejercicio` int(11) NOT NULL AUTO_INCREMENT,
  `anio` int(4) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `inicio` int(11) NOT NULL,
  `fin` int(11) NOT NULL,
  `vigente` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_ejercicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicio_empresa`
--

CREATE TABLE IF NOT EXISTS `ejercicio_empresa` (
  `id_ejercicio` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id_ejercicio`,`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE IF NOT EXISTS `empresa` (
  `id_empresa` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla empresa',
  `id_direccion` int(11) NOT NULL COMMENT 'Id de la direccion de la empresa',
  `rfc` varchar(30) NOT NULL COMMENT 'RFC de la empresa',
  `razon_social` varchar(100) NOT NULL COMMENT 'Razon social de la empresa',
  `representante_legal` varchar(100) DEFAULT NULL COMMENT 'Representante legal de la empresa, puede ser persona o empresa',
  `fecha_alta` int(11) NOT NULL COMMENT 'Fecha en que se creo esta empresa',
  `fecha_baja` int(11) DEFAULT NULL COMMENT 'Fecha en que se desactivo esa empresa',
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Si esta empresa esta activa o no',
  `direccion_web` varchar(20) DEFAULT NULL COMMENT 'Direccion web de la empresa',
  `cedula` varchar(100) CHARACTER SET armscii8 DEFAULT NULL,
  `id_logo` int(11) NOT NULL,
  `mensaje_morosos` text CHARACTER SET utf8 COMMENT 'Mensaje para clientes y proveedores morosos',
  PRIMARY KEY (`id_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='tabla de empresas' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE IF NOT EXISTS `estado` (
  `id_estado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del estado en el sistema',
  `nombre` varchar(16) NOT NULL COMMENT 'Nombre del estado',
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extra_params_estructura`
--

CREATE TABLE IF NOT EXISTS `extra_params_estructura` (
  `id_extra_params_estructura` int(11) NOT NULL AUTO_INCREMENT,
  `tabla` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `campo` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `tipo` enum('text','textarea','enum','password','string','int','float','bool','date') COLLATE utf8_spanish_ci NOT NULL,
  `enum` longtext NULL,
  `longitud` int(11) NOT NULL,
  `obligatorio` tinyint(1) NOT NULL,
  `caption` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`id_extra_params_estructura`),
  UNIQUE KEY `tabla` (`tabla`,`campo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extra_params_valores`
--

CREATE TABLE IF NOT EXISTS `extra_params_valores` (
  `id_extra_params_valores` int(11) NOT NULL AUTO_INCREMENT,
  `id_extra_params_estructura` int(11) NOT NULL,
  `id_pk_tabla` int(11) NOT NULL COMMENT 'el id del objeto en la tabla a la que se le agrego la columna',
  `val` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_extra_params_valores`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto`
--

CREATE TABLE IF NOT EXISTS `gasto` (
  `id_gasto` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL COMMENT 'el id de la empresa a quien pertenece este gasto',
  `id_usuario` int(11) NOT NULL COMMENT 'el usuario que inserto este gasto',
  `id_concepto_gasto` int(11) DEFAULT NULL COMMENT 'el id del concepto de este gasto',
  `id_orden_de_servicio` int(11) DEFAULT NULL COMMENT 'Si este gasto se aplico a una orden de servicio',
  `id_caja` int(11) DEFAULT NULL COMMENT 'Id de la caja de la cual se sustrae el dinero para pagar el gasto',
  `fecha_del_gasto` int(11) NOT NULL COMMENT 'la fecha de cuando el gasto se hizo',
  `fecha_de_registro` int(11) NOT NULL COMMENT 'fecha de cuando el gasto se ingreso en el sistema',
  `id_sucursal` int(11) DEFAULT NULL COMMENT 'si el gasto pertenece a una sucursal especifica, este es el id de esa sucursal',
  `nota` varchar(64) DEFAULT NULL COMMENT 'alguna nota extra para el gasto',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion del gasto en caso de que no este contemplado en la lista de  conceptos de gasto',
  `folio` varchar(50) DEFAULT NULL COMMENT 'Folio de la factura del gasto',
  `monto` float NOT NULL COMMENT 'Monto del gasto si no esta definido por el concepto de gasto',
  `cancelado` tinyint(1) NOT NULL COMMENT 'Si este gasto ha sido cancelado o no',
  `motivo_cancelacion` varchar(255) DEFAULT NULL COMMENT 'Motivo por el cual se realiza la cancelacion',
  PRIMARY KEY (`id_gasto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impresora`
--

CREATE TABLE IF NOT EXISTS `impresora` (
  `id_impresora` int(11) NOT NULL AUTO_INCREMENT,
  `puerto` varchar(16) NOT NULL,
  PRIMARY KEY (`id_impresora`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impresora_caja`
--

CREATE TABLE IF NOT EXISTS `impresora_caja` (
  `id_impresora` int(11) NOT NULL COMMENT 'Id de la impresora',
  `id_caja` int(11) NOT NULL COMMENT 'Id de la caja que utiliza la impresora',
  PRIMARY KEY (`id_impresora`,`id_caja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre una caja y las impresoras que utiliza';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto`
--

CREATE TABLE IF NOT EXISTS `impuesto` (
  `id_impuesto` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT 'Determina el cÃ³digo para identificar el impuesto',
  `importe` float NOT NULL COMMENT 'El monto o el porcentaje correspondiente del impuesto',
  `incluido_precio` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Determina si el importe estÃ¡ incluido en el precio',
  `aplica` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT 'ambos' COMMENT 'Determina el Ã¡mbito al que aplica el impuesto (compra, venta, ambos)',
  `tipo` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT 'porcentaje' COMMENT 'Determina el tipo de impuesto: porcentaje, importe_fijo, ninguno, saldo_pendiente.',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del impuesto',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion larga del impuesto',
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Determina si estÃ¡ activo el impuesto',
  PRIMARY KEY (`id_impuesto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_clasificacion_cliente`
--

CREATE TABLE IF NOT EXISTS `impuesto_clasificacion_cliente` (
  `id_impuesto` int(11) NOT NULL COMMENT 'Id del impuesto a aplicar al tipo de cliente',
  `id_clasificacion_cliente` int(11) NOT NULL COMMENT 'Id de la clasificacion del cliente',
  PRIMARY KEY (`id_impuesto`,`id_clasificacion_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle impuesto clasificacion cliente';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_clasificacion_producto`
--

CREATE TABLE IF NOT EXISTS `impuesto_clasificacion_producto` (
  `id_impuesto` int(11) NOT NULL COMMENT 'Id del impuesto a aplicarl al tipo de producto',
  `id_clasificacion_producto` int(11) NOT NULL COMMENT 'Id de la clasificacion del producto',
  PRIMARY KEY (`id_impuesto`,`id_clasificacion_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle impuesto clasificacion producto';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_clasificacion_proveedor`
--

CREATE TABLE IF NOT EXISTS `impuesto_clasificacion_proveedor` (
  `id_impuesto` int(11) NOT NULL COMMENT 'Id del impuesto a aplicar al tipo de proveedor',
  `id_clasificacion_proveedor` int(11) NOT NULL COMMENT 'Id de la clasificacion del proveedor',
  PRIMARY KEY (`id_impuesto`,`id_clasificacion_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle impuesto clasificacion proveedor';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_clasificacion_servicio`
--

CREATE TABLE IF NOT EXISTS `impuesto_clasificacion_servicio` (
  `id_impuesto` int(11) NOT NULL COMMENT 'Id del impuesto a aplicar al tipo de servicio',
  `id_clasificacion_servicio` int(11) NOT NULL COMMENT 'Id de la clasificacion del servicio',
  PRIMARY KEY (`id_impuesto`,`id_clasificacion_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre impuesto clasificacion servicio';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_empresa`
--

CREATE TABLE IF NOT EXISTS `impuesto_empresa` (
  `id_impuesto` int(11) NOT NULL COMMENT 'Id del impuesto que se aplicara a la empresa',
  `id_empresa` int(11) NOT NULL COMMENT 'id de la empresa',
  PRIMARY KEY (`id_impuesto`,`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre los impuestos con las empresas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_producto`
--

CREATE TABLE IF NOT EXISTS `impuesto_producto` (
  `id_impuesto` int(11) NOT NULL COMMENT 'Id del impuesto a aplicar al producto',
  `id_producto` int(11) NOT NULL COMMENT 'Id del producto al que se le aplica el impuesto',
  PRIMARY KEY (`id_impuesto`,`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle impuesto producto';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_servicio`
--

CREATE TABLE IF NOT EXISTS `impuesto_servicio` (
  `id_impuesto` int(11) NOT NULL COMMENT 'Id del impuesto a aplicar al servicio',
  `id_servicio` int(11) NOT NULL COMMENT 'Id del servicio al que se le aplicara el impuesto',
  PRIMARY KEY (`id_impuesto`,`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle impuesto servicio';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_sucursal`
--

CREATE TABLE IF NOT EXISTS `impuesto_sucursal` (
  `id_impuesto` int(11) NOT NULL COMMENT 'Id del impuesto que se aplicara a la sucursal',
  `id_sucursal` int(11) NOT NULL COMMENT 'Id de la sucursal que tiene diversos impuestos',
  PRIMARY KEY (`id_impuesto`,`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre los impuestos con las sucursales';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_usuario`
--

CREATE TABLE IF NOT EXISTS `impuesto_usuario` (
  `id_impuesto` int(11) NOT NULL COMMENT 'Id del impuesto que se aplica al usuario',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario al que se le cargan impuestos',
  PRIMARY KEY (`id_impuesto`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre los impuestos y los usuarios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso`
--

CREATE TABLE IF NOT EXISTS `ingreso` (
  `id_ingreso` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL COMMENT 'el id de la empresa a quien pertenece este ingreso',
  `id_usuario` int(11) NOT NULL COMMENT 'el usuario que inserto este ingreso',
  `id_concepto_ingreso` int(11) DEFAULT NULL COMMENT 'el id del concepto de este ingreso',
  `fecha_del_ingreso` int(11) NOT NULL COMMENT 'la fecha de cuando el ingreso se hizo',
  `fecha_de_registro` int(11) NOT NULL COMMENT 'fecha de cuando el ingreso se registro en el sistema',
  `id_sucursal` int(11) DEFAULT NULL COMMENT 'si el ingreso pertenece a una sucursal especifica, este es el id de esa sucursal',
  `id_caja` int(11) DEFAULT NULL COMMENT 'si el ingreso se recibe en una caja, este es su id',
  `nota` varchar(64) DEFAULT NULL COMMENT 'alguna nota extra para el ingreso',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion del ingreso en caso de que no este contemplado en la lista de  conceptos de ingreso',
  `folio` varchar(50) DEFAULT NULL COMMENT 'Folio de la factura del ingreso',
  `monto` float NOT NULL COMMENT 'Monto del ingreso si no esta definido por el concepto de gasto',
  `cancelado` tinyint(1) NOT NULL COMMENT 'Si este ingreso ha sido cancelado o no',
  `motivo_cancelacion` varchar(255) DEFAULT NULL COMMENT 'Motivo por el cual se realiza la cancelacion',
  PRIMARY KEY (`id_ingreso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspeccion_consignacion`
--

CREATE TABLE IF NOT EXISTS `inspeccion_consignacion` (
  `id_inspeccion_consignacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_consignacion` int(11) NOT NULL COMMENT 'Id de la consignacion a la que se le hace la inspeccion',
  `id_usuario` int(11) DEFAULT NULL COMMENT 'Id del usuario que realiza la inspeccion',
  `id_caja` int(11) DEFAULT NULL COMMENT 'Id de la caja en la que se deposita el monto',
  `fecha_inspeccion` int(11) NOT NULL COMMENT 'fecha en que se programa la inspeccion',
  `monto_abonado` float NOT NULL COMMENT 'Monto abonado a la inspeccion',
  `cancelada` tinyint(1) NOT NULL COMMENT 'Si esta inspeccion sigue programada o se ha cancelado',
  `motivo_cancelacion` varchar(255) DEFAULT NULL COMMENT 'motivo por el cual se ha cancelado la inspeccion',
  PRIMARY KEY (`id_inspeccion_consignacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspeccion_consignacion_producto`
--

CREATE TABLE IF NOT EXISTS `inspeccion_consignacion_producto` (
  `id_inspeccion_consignacion` int(11) NOT NULL COMMENT 'Id de la isnpeccion de consignacion',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `id_unidad` int(11) NOT NULL COMMENT 'Id de la unidad del producto',
  `cantidad_actual` float NOT NULL COMMENT 'cantidad del producto actualmente',
  `cantidad_solicitada` float NOT NULL COMMENT 'cantidad del producto solicitado',
  `cantidad_devuelta` float NOT NULL COMMENT 'cantidad del producto devuelto',
  PRIMARY KEY (`id_inspeccion_consignacion`,`id_producto`,`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla detalle entre una inspeccion de consignacion y los pro';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logo`
--

CREATE TABLE IF NOT EXISTS `logo` (
  `id_logo` int(11) NOT NULL AUTO_INCREMENT,
  `imagen` text NOT NULL,
  `tipo` varchar(5) NOT NULL,
  PRIMARY KEY (`id_logo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote`
--

CREATE TABLE IF NOT EXISTS `lote` (
  `id_lote` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del lote',
  `id_almacen` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario que creo el lote',
  `folio` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id_lote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Manejo de lotes' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote_entrada`
--

CREATE TABLE IF NOT EXISTS `lote_entrada` (
  `id_lote_entrada` int(11) NOT NULL AUTO_INCREMENT,
  `id_lote` int(11) NOT NULL COMMENT 'Id del almacen al cual entra producto',
  `id_documento` int(11) DEFAULT NULL COMMENT 'Id del documento que genero esta entrada',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario que registra',
  `fecha_registro` int(11) NOT NULL COMMENT 'Fecha en que se registra el movimiento',
  `motivo` varchar(255) DEFAULT NULL COMMENT 'motivo por le cual entra producto al almacen',
  PRIMARY KEY (`id_lote_entrada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Registro de entradas de un lote' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote_entrada_producto`
--

CREATE TABLE IF NOT EXISTS `lote_entrada_producto` (
  `id_lote_entrada` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_unidad` int(11) NOT NULL,
  `cantidad` float NOT NULL COMMENT 'Cantidad de producto que sale del almacen en cierta unidad',
  PRIMARY KEY (`id_lote_entrada`,`id_producto`,`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle producto entrada almacen';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote_producto`
--

CREATE TABLE IF NOT EXISTS `lote_producto` (
  `id_lote` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del lote',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `cantidad` float NOT NULL COMMENT 'cantidad de producto',
  `id_unidad` int(11) NOT NULL,
  PRIMARY KEY (`id_lote`,`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='relaciona un producto con un lote' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote_salida`
--

CREATE TABLE IF NOT EXISTS `lote_salida` (
  `id_lote_salida` int(11) NOT NULL AUTO_INCREMENT,
  `id_lote` int(11) NOT NULL COMMENT 'Id del almacen del cual sale producto',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario que registra',
  `id_documento` int(11) DEFAULT NULL COMMENT 'Id del documento que genero esta entrada',
  `fecha_registro` int(11) NOT NULL COMMENT 'Fecha en que se registra el movimiento',
  `motivo` varchar(255) NOT NULL COMMENT 'motivo por le cual sale producto del almacen',
  PRIMARY KEY (`id_lote_salida`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Registro de salidas de un lote' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote_salida_producto`
--

CREATE TABLE IF NOT EXISTS `lote_salida_producto` (
  `id_lote_salida` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_unidad` int(11) NOT NULL,
  `cantidad` float NOT NULL COMMENT 'Cantidad de producto que sale del almacen en cierta unidad',
  PRIMARY KEY (`id_lote_salida`,`id_producto`,`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle producto salida almacen';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote_ubicacion`
--

CREATE TABLE IF NOT EXISTS `lote_ubicacion` (
  `id_lote` int(11) NOT NULL COMMENT 'id del lote',
  `id_ubicacion` int(11) NOT NULL COMMENT 'id de la ubicacion',
  PRIMARY KEY (`id_lote`,`id_ubicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relaciona un lote con una ubicacion';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `moneda`
--

CREATE TABLE IF NOT EXISTS `moneda` (
  `id_moneda` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla moneda',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de la moneda',
  `simbolo` varchar(10) NOT NULL COMMENT 'Simbolo de la moneda (US$,NP$)',
  `activa` tinyint(1) NOT NULL COMMENT 'Si esta moneda esta activa o ya no se usa',
  PRIMARY KEY (`id_moneda`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla que contendrÃƒÆ’Ã‚Â¡ las distintas monedas que usa el uusa' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_de_servicio`
--

CREATE TABLE IF NOT EXISTS `orden_de_servicio` (
  `id_orden_de_servicio` int(11) NOT NULL AUTO_INCREMENT,
  `id_servicio` int(11) NOT NULL COMMENT 'Id del servicio entregado',
  `id_usuario_venta` int(11) NOT NULL COMMENT 'Id del usuario al que se le relaiza la orden',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario que realiza la orden',
  `id_usuario_asignado` int(11) DEFAULT NULL COMMENT 'Id del usuario que tiene asignada esta orden (responsable)',
  `fecha_orden` int(11) NOT NULL COMMENT 'fecha en la que se realiza la orden',
  `fecha_entrega` int(11) NOT NULL COMMENT 'fecha en la que se entrega la orden',
  `activa` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Si la orden esta activa',
  `cancelada` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Si la orden esta cancelada',
  `descripcion` varchar(255) NOT NULL COMMENT 'Descripcion de la orden',
  `motivo_cancelacion` varchar(255) DEFAULT NULL COMMENT 'Motivo por la cual fue cancelada la orden',
  `adelanto` float NOT NULL COMMENT 'Cantidad de dinero pagada por adelantado',
  `precio` float NOT NULL COMMENT 'El precio de esta orden de servicio',
  `extra_params` text COMMENT 'Un json con valores extra que se necesitan llenar',
  PRIMARY KEY (`id_orden_de_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_de_servicio_paquete`
--

CREATE TABLE IF NOT EXISTS `orden_de_servicio_paquete` (
  `id_servicio` int(11) NOT NULL COMMENT 'Id del servicio',
  `id_paquete` int(11) NOT NULL COMMENT 'Id del paquete',
  `cantidad` float NOT NULL COMMENT 'Cantidad de ordenes de servicio incluidos en el paquete',
  PRIMARY KEY (`id_servicio`,`id_paquete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle orden de servicio paquete';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquete`
--

CREATE TABLE IF NOT EXISTS `paquete` (
  `id_paquete` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla paquete',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del paquete',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion larga del paquete',
  `foto_paquete` varchar(255) DEFAULT NULL COMMENT 'Url de la foto del paquete',
  `costo_estandar` float DEFAULT NULL COMMENT 'Costo estandar del paquete',
  `precio` float DEFAULT NULL COMMENT 'Precio dijo del paquete',
  `activo` tinyint(1) NOT NULL COMMENT 'Si el paquete esta activo o no',
  PRIMARY KEY (`id_paquete`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Paquetes de productos y/o servicios' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquete_empresa`
--

CREATE TABLE IF NOT EXISTS `paquete_empresa` (
  `id_paquete` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id_paquete`,`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='detalle paquete empresa';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquete_sucursal`
--

CREATE TABLE IF NOT EXISTS `paquete_sucursal` (
  `id_paquete` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  PRIMARY KEY (`id_paquete`,`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle paquete sucursal';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodo`
--

CREATE TABLE IF NOT EXISTS `periodo` (
  `id_periodo` int(11) NOT NULL AUTO_INCREMENT,
  `periodo` int(2) NOT NULL,
  `inicio` int(11) NOT NULL,
  `fin` int(11) NOT NULL,
  PRIMARY KEY (`id_periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE IF NOT EXISTS `permiso` (
  `id_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `permiso` varchar(64) NOT NULL COMMENT 'el nombre de la funcion en el api a la que se le dara permiso',
  PRIMARY KEY (`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso_rol`
--

CREATE TABLE IF NOT EXISTS `permiso_rol` (
  `id_permiso` int(11) NOT NULL COMMENT 'Id del permiso del rol en esa empresa',
  `id_rol` int(11) NOT NULL COMMENT 'Id del rol que tiene el permiso en esa empresa',
  PRIMARY KEY (`id_permiso`,`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla detalle entre los permisos de los roles en las empreas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso_usuario`
--

CREATE TABLE IF NOT EXISTS `permiso_usuario` (
  `id_permiso` int(11) NOT NULL COMMENT 'Id del permiso del usuario en la empresa',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario con el permiso en la empresa',
  PRIMARY KEY (`id_permiso`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre los permisos con los usuarios en las empresas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE IF NOT EXISTS `prestamo` (
  `id_prestamo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del prestamo',
  `id_solicitante` int(11) NOT NULL COMMENT 'Id de la sucursal o usuario que solicita el prestamo, la sucursal sera negativa',
  `id_empresa_presta` int(11) NOT NULL COMMENT 'Id de la emresa que realiza el prestamo',
  `id_sucursal_presta` int(11) NOT NULL COMMENT 'Id de la sucursal que presta',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario que realiza el prestamo',
  `monto` float NOT NULL COMMENT 'Monto que se solicita',
  `saldo` float NOT NULL COMMENT 'Saldo que lleva abonado el prestamo',
  `interes_mensual` float NOT NULL COMMENT 'Porcentaje de interes mensual del prestamo',
  `fecha` int(11) NOT NULL COMMENT 'Fecha en que se realiza el prestamo',
  PRIMARY KEY (`id_prestamo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Prestamo de una sucursal a un solicitante' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE IF NOT EXISTS `producto` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `compra_en_mostrador` tinyint(1) NOT NULL COMMENT 'Verdadero si el producto se puede comprar en mostrador',
  `visible_en_vc` tinyint(1) NOT NULL COMMENT '1 para mostrar el productos en VC.',
  `metodo_costeo` enum('precio','costo','variable') NOT NULL COMMENT 'Si el precio se toma del precio base o del costo del producto',
  `activo` tinyint(1) NOT NULL COMMENT 'Si el producto esta activo o no',
  `codigo_producto` varchar(128) NOT NULL COMMENT 'Codigo interno del producto',
  `nombre_producto` varchar(256) NOT NULL COMMENT 'Nombre del producto',
  `garantia` int(11) DEFAULT NULL COMMENT 'Si este producto cuenta con un numero de meses de garantia',
  `costo_estandar` float DEFAULT NULL COMMENT 'Costo estandar del producto',
  `control_de_existencia` int(11) DEFAULT NULL COMMENT '00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripcion del producto',
  `foto_del_producto` varchar(100) DEFAULT NULL COMMENT 'Url a una foto de este producto',
  `costo_extra_almacen` float DEFAULT NULL COMMENT 'Si este producto produce un costo extra en el almacen',
  `codigo_de_barras` varchar(30) DEFAULT NULL COMMENT 'El codigo de barras de este producto',
  `peso_producto` float DEFAULT NULL COMMENT 'El peso de este producto en Kg',
  `id_unidad` int(11) DEFAULT NULL COMMENT 'Id de la unidad en la que usualmente se maneja este producto',
  `precio` float DEFAULT NULL COMMENT 'El precio fijo del producto',
  `id_unidad_compra` int(11) DEFAULT NULL COMMENT 'Id de la unidad de compra del producto',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_abasto_proveedor`
--

CREATE TABLE IF NOT EXISTS `producto_abasto_proveedor` (
  `id_abasto_proveedor` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_unidad` int(11) NOT NULL COMMENT 'Id de la unidad',
  `cantidad` float NOT NULL COMMENT 'Cantidad de producto abastesido',
  PRIMARY KEY (`id_abasto_proveedor`,`id_producto`,`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle producto Abasto proveedor';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_clasificacion`
--

CREATE TABLE IF NOT EXISTS `producto_clasificacion` (
  `id_producto` int(11) NOT NULL COMMENT 'Id del producto con esa clasificacion',
  `id_clasificacion_producto` int(11) NOT NULL COMMENT 'Id de la clasificacion del producto',
  PRIMARY KEY (`id_producto`,`id_clasificacion_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle prodcuto clasificacion';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_empresa`
--

CREATE TABLE IF NOT EXISTS `producto_empresa` (
  `id_producto` int(11) NOT NULL COMMENT 'Id del producto que se vende en la empresa',
  `id_empresa` int(11) NOT NULL COMMENT 'Id de la empresa que ofrece ese producto',
  PRIMARY KEY (`id_producto`,`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle producto empresa';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_orden_de_servicio`
--

CREATE TABLE IF NOT EXISTS `producto_orden_de_servicio` (
  `id_orden_de_servicio` int(11) NOT NULL COMMENT 'id de la orden de servicio',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto a vender',
  `precio` float NOT NULL COMMENT 'precio unitario con el que se va a vender el producto',
  `cantidad` int(11) NOT NULL COMMENT 'cantidad de producto que se vendera',
  `descuento` float NOT NULL COMMENT 'descuento que se aplicara al producto',
  `impuesto` float NOT NULL COMMENT 'impuesto que se aplicara al producto',
  `retencion` float NOT NULL COMMENT 'Retencion unitaria en el producto',
  `id_unidad` int(11) NOT NULL COMMENT 'Id de la unidad del producto',
  PRIMARY KEY (`id_orden_de_servicio`,`id_producto`,`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla detalle entre una orden de servicio y los productos qu';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_paquete`
--

CREATE TABLE IF NOT EXISTS `producto_paquete` (
  `id_producto` int(11) NOT NULL COMMENT 'Id de producto',
  `id_paquete` int(11) NOT NULL COMMENT 'Id del paquete',
  `cantidad` float NOT NULL COMMENT 'Cantidad del producto ofrecido en el paquete',
  `id_unidad` int(11) NOT NULL COMMENT 'Id de la unidad del producto en ese paquete',
  PRIMARY KEY (`id_producto`,`id_paquete`,`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle paquete producto';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `regla`
--

CREATE TABLE IF NOT EXISTS `regla` (
  `id_regla` int(11) NOT NULL AUTO_INCREMENT,
  `id_version` int(11) NOT NULL COMMENT 'Id de la version a la que pertenece esta regla',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de la regla',
  `id_producto` int(11) DEFAULT NULL COMMENT 'Id del producto al que se le aplicara esta regla',
  `id_clasificacion_producto` int(11) DEFAULT NULL COMMENT 'Id de la clasificacion del producto al que se le aplicara esta regla',
  `id_unidad` int(11) DEFAULT NULL COMMENT 'Id de la unidad a la cual aplicara esta regla',
  `id_servicio` int(11) DEFAULT NULL COMMENT 'Id del servicio al cual se le aplicara esta regla',
  `id_clasificacion_servicio` int(11) DEFAULT NULL COMMENT 'Id de la clasificacion del servicio a la que se le aplicara esta regla',
  `id_paquete` int(11) DEFAULT NULL COMMENT 'Id del paquete al cual se le aplicara esta regla',
  `cantidad_minima` float NOT NULL DEFAULT '1' COMMENT 'Cantidad minima de objeto necesarios apra aplicar esta regla',
  `id_tarifa` int(11) NOT NULL COMMENT 'Id de la tarifa en la cual se basa esta tarifa para obtener el precio base',
  `porcentaje_utilidad` float NOT NULL DEFAULT '0' COMMENT 'Porcentaje de utilidad que se le ganara al precio base del objeto',
  `utilidad_neta` float NOT NULL DEFAULT '0' COMMENT 'Utilidad neta que se le ganara al comerciar con el objeto',
  `metodo_redondeo` float NOT NULL DEFAULT '0' COMMENT 'Falta definir por Manuel',
  `margen_min` float NOT NULL DEFAULT '0' COMMENT 'Falta definir por Manuel',
  `margen_max` float NOT NULL DEFAULT '0' COMMENT 'Falta definir por Manuel',
  `secuencia` int(11) NOT NULL COMMENT 'Secuencia de la regla',
  PRIMARY KEY (`id_regla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte`
--

CREATE TABLE IF NOT EXISTS `reporte` (
  `id_reporte` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del reporte',
  PRIMARY KEY (`id_reporte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla que contendrÃƒÆ’Ã‚Â¡ los reportes generados' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion`
--

CREATE TABLE IF NOT EXISTS `retencion` (
  `id_retencion` int(11) NOT NULL AUTO_INCREMENT,
  `monto_porcentaje` float NOT NULL COMMENT 'El monto o el porcentaje de la retencionde la ',
  `es_monto` tinyint(1) NOT NULL COMMENT 'Verdadero si el valor del campo monto_porcentaje es un monto, false si es un porcentaje',
  `nombre` varchar(100) NOT NULL COMMENT 'El nombre de la retencion',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'DEscripcion larga de la retencion',
  PRIMARY KEY (`id_retencion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_clasificacion_cliente`
--

CREATE TABLE IF NOT EXISTS `retencion_clasificacion_cliente` (
  `id_retencion` int(11) NOT NULL COMMENT 'Id del retencion a aplicar al tipo de cliente',
  `id_clasificacion_cliente` int(11) NOT NULL COMMENT 'Id de la clasificacion del cliente',
  PRIMARY KEY (`id_retencion`,`id_clasificacion_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre retencion clasificacion cliente';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_clasificacion_producto`
--

CREATE TABLE IF NOT EXISTS `retencion_clasificacion_producto` (
  `id_retencion` int(11) NOT NULL COMMENT 'Id del retencion a aplicar al tipo de producto',
  `id_clasificacion_producto` int(11) NOT NULL COMMENT 'Id de la clasificacion del producto',
  PRIMARY KEY (`id_retencion`,`id_clasificacion_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre retencion clasificacion producto';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_clasificacion_proveedor`
--

CREATE TABLE IF NOT EXISTS `retencion_clasificacion_proveedor` (
  `id_retencion` int(11) NOT NULL COMMENT 'Id del retencion a aplicar al tipo de proveedor',
  `id_clasificacion_proveedor` int(11) NOT NULL COMMENT 'Id de la clasificacion del proveedor',
  PRIMARY KEY (`id_retencion`,`id_clasificacion_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre retencion clasificacion proveedor';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_clasificacion_servicio`
--

CREATE TABLE IF NOT EXISTS `retencion_clasificacion_servicio` (
  `id_retencion` int(11) NOT NULL COMMENT 'Id del retencion a aplicar al tipo de servicio',
  `id_clasificacion_servicio` int(11) NOT NULL COMMENT 'Id de la clasificacion del servicio',
  PRIMARY KEY (`id_retencion`,`id_clasificacion_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre retencion clasificacion servicio';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_empresa`
--

CREATE TABLE IF NOT EXISTS `retencion_empresa` (
  `id_retencion` int(11) NOT NULL COMMENT 'Id de la retencion que se aplica a la empreas',
  `id_empresa` int(11) NOT NULL COMMENT 'Id de la empresa a la que se le aplica la retencion',
  PRIMARY KEY (`id_retencion`,`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre las retenciones y las empresas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_producto`
--

CREATE TABLE IF NOT EXISTS `retencion_producto` (
  `id_retencion` int(11) NOT NULL COMMENT 'Id de la retencion que se aplica al producto',
  `id_producto` int(11) NOT NULL COMMENT 'Id del producto al que se le aplica la retencion',
  PRIMARY KEY (`id_retencion`,`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle retencion producto';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_servicio`
--

CREATE TABLE IF NOT EXISTS `retencion_servicio` (
  `id_retencion` int(11) NOT NULL COMMENT 'Id de la retencion que se aplica al servicio',
  `id_servicio` int(11) NOT NULL COMMENT 'Id del servicio al que se le aplica la retencion',
  PRIMARY KEY (`id_retencion`,`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle retencion servicio';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_sucursal`
--

CREATE TABLE IF NOT EXISTS `retencion_sucursal` (
  `id_retencion` int(11) NOT NULL COMMENT 'Id de la retencion que se aplica a la sucursal',
  `id_sucursal` int(11) NOT NULL COMMENT 'Id de la sucursal que tiene la retencion',
  PRIMARY KEY (`id_retencion`,`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre las retenciones y las sucursales';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_usuario`
--

CREATE TABLE IF NOT EXISTS `retencion_usuario` (
  `id_retencion` int(11) NOT NULL COMMENT 'Id de la retencion que se aplica al usuario',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario que tiene la retencion',
  PRIMARY KEY (`id_retencion`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre las retenciones y los usuarios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE IF NOT EXISTS `rol` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL COMMENT 'Nombre del rol',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'descripcion larga de este rol',
  `salario` float DEFAULT NULL COMMENT 'Si los usuarios con dicho rol contaran con un salario',
  `id_tarifa_compra` int(11) NOT NULL COMMENT 'Id de la tarifa de compra por default para los usuarios de este rol',
  `id_tarifa_venta` int(11) NOT NULL COMMENT 'Id de la tarifa de venta por default para los usuarios de este rol',
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento_de_servicio`
--

CREATE TABLE IF NOT EXISTS `seguimiento_de_servicio` (
  `id_seguimiento_de_servicio` int(11) NOT NULL AUTO_INCREMENT,
  `id_orden_de_servicio` int(11) NOT NULL COMMENT 'Id orden de servicio a la que se le realiza el seguimiento',
  `id_localizacion` int(11) DEFAULT NULL COMMENT 'Id de la sucursal en la que se encuentra el servicio actualmente',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario que realiza el seguimiento',
  `id_sucursal` int(11) DEFAULT NULL COMMENT 'Id de la sucursal de donde se realiza el seguimiento',
  `estado` varchar(255) DEFAULT NULL COMMENT 'Estado en la que se encuentra la orden',
  `fecha_seguimiento` int(11) NOT NULL COMMENT 'Fecha en la que se realizo el seguimiento',
  PRIMARY KEY (`id_seguimiento_de_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE IF NOT EXISTS `servicio` (
  `id_servicio` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_servicio` varchar(50) NOT NULL COMMENT 'nombre del servicio',
  `metodo_costeo` enum('precio','costo','variable') NOT NULL COMMENT 'Si el precio final se tomara del precio base de este servicio o de su costo',
  `codigo_servicio` varchar(20) NOT NULL COMMENT 'Codigo de control del servicio manejado por la empresa, no se puede repetir',
  `compra_en_mostrador` tinyint(1) NOT NULL COMMENT 'Verdadero si este servicio se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador',
  `activo` tinyint(1) NOT NULL COMMENT 'Si el servicio esta activo',
  `descripcion_servicio` varchar(255) DEFAULT NULL COMMENT 'Descripcion del servicio',
  `costo_estandar` float NOT NULL COMMENT 'Valor del costo estandar del servicio',
  `garantia` int(11) DEFAULT NULL COMMENT 'Si este servicio tiene una garantÃƒÆ’Ã‚Â­a en meses.',
  `control_existencia` int(11) DEFAULT NULL COMMENT '00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = LoteCaractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote',
  `foto_servicio` varchar(50) DEFAULT NULL COMMENT 'Url de la foto del servicio',
  `precio` float DEFAULT NULL COMMENT 'El precio fijo del servicio',
  `extra_params` text COMMENT 'Un json con valores extra que se necesitan llenar',
  PRIMARY KEY (`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_clasificacion`
--

CREATE TABLE IF NOT EXISTS `servicio_clasificacion` (
  `id_servicio` int(11) NOT NULL COMMENT 'Id del servicio ',
  `id_clasificacion_servicio` int(11) NOT NULL COMMENT 'Id de la clasificacio dnel servicio',
  PRIMARY KEY (`id_servicio`,`id_clasificacion_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle servicio clasificacion';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_empresa`
--

CREATE TABLE IF NOT EXISTS `servicio_empresa` (
  `id_servicio` int(11) NOT NULL COMMENT 'Id del servicio ',
  `id_empresa` int(11) NOT NULL COMMENT 'Id de la empresa en la que se ofrece este servicio',
  PRIMARY KEY (`id_servicio`,`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle servicio empresa';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_sucursal`
--

CREATE TABLE IF NOT EXISTS `servicio_sucursal` (
  `id_servicio` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  PRIMARY KEY (`id_servicio`,`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle servicio sucusal';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesion`
--

CREATE TABLE IF NOT EXISTS `sesion` (
  `id_sesion` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `auth_token` varchar(64) NOT NULL,
  `fecha_de_vencimiento` int(11) NOT NULL,
  `client_user_agent` varchar(64) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_sesion`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  KEY `auth_token` (`auth_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Mantiene un seguimiento de las sesiones activas en el sistem' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
  `id_sucursal` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla sucursal',
  `id_direccion` int(11) NOT NULL COMMENT 'Id de la direccion de la sucursal',
  `rfc` varchar(30) DEFAULT NULL COMMENT 'RFC de la sucursal',
  `razon_social` varchar(100) NOT NULL COMMENT 'Razon social de la sucursal',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Descrpicion de la sucursal',
  `id_gerente` int(11) DEFAULT NULL COMMENT 'Id del usuario que funje como gerente general de la sucursal',
  `saldo_a_favor` float NOT NULL COMMENT 'Saldo a favor de la sucursal',
  `fecha_apertura` int(11) NOT NULL COMMENT 'Fecha en que se creo la sucursal',
  `activa` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Si esta sucursal esta activa o no',
  `fecha_baja` int(11) DEFAULT NULL COMMENT 'Fecha en que se dio de baja esta sucursal',
  PRIMARY KEY (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tabla de sucursales' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal_empresa`
--

CREATE TABLE IF NOT EXISTS `sucursal_empresa` (
  `id_sucursal` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id_sucursal`,`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tabla detalle entre sucursal y las empresas a la que pertene';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifa`
--

CREATE TABLE IF NOT EXISTS `tarifa` (
  `id_tarifa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de la tarifa',
  `tipo_tarifa` enum('compra','venta') NOT NULL COMMENT 'Si el tipo de tarifa es de compra o de venta',
  `activa` tinyint(1) NOT NULL COMMENT 'Si la tarifa es activa o no',
  `id_moneda` int(11) NOT NULL COMMENT 'Moneda con la que se realizan los calclos de esta tarifa',
  `default` tinyint(1) NOT NULL COMMENT 'Si esta tarifa es la default del sistema o no',
  `id_version_default` int(11) DEFAULT NULL COMMENT 'Id de la version default de esta tarifa',
  `id_version_activa` int(11) DEFAULT NULL COMMENT 'Id de la version activa de esta tarifa',
  PRIMARY KEY (`id_tarifa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_almacen`
--

CREATE TABLE IF NOT EXISTS `tipo_almacen` (
  `id_tipo_almacen` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(64) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Si esta activo = 1, 0 = Inactivo',
  PRIMARY KEY (`id_tipo_almacen`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traspaso`
--

CREATE TABLE IF NOT EXISTS `traspaso` (
  `id_traspaso` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla traspaso',
  `id_usuario_programa` int(11) NOT NULL COMMENT 'Id del usuario que programa el traspaso',
  `id_usuario_envia` int(11) NOT NULL COMMENT 'Id del usuario que envia',
  `id_almacen_envia` int(11) NOT NULL COMMENT 'Id del almacen que envia los productos',
  `fecha_envio_programada` int(11) NOT NULL COMMENT 'Fecha de envio programada para este traspaso',
  `fecha_envio` int(11) NOT NULL COMMENT 'Fecha en que se envia',
  `id_usuario_recibe` int(11) NOT NULL COMMENT 'Id del usuario que recibe',
  `id_almacen_recibe` int(11) NOT NULL COMMENT 'Id del almacen que recibe los productos',
  `fecha_recibo` int(11) NOT NULL COMMENT 'Fecha en que se recibe el envio',
  `estado` enum('Envio programado','Enviado','Cancelado','Recibido') NOT NULL COMMENT 'Si el traspaso esta en solicitud, en envio o si ya fue recibida',
  `cancelado` tinyint(1) NOT NULL COMMENT 'Si la solicitud de traspaso fue cancelada',
  `completo` tinyint(1) NOT NULL COMMENT 'Verdadero si se enviaron todos los productos solicitados al inicio del traspaso',
  PRIMARY KEY (`id_traspaso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Traspasos entre un almacen y otro' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traspaso_producto`
--

CREATE TABLE IF NOT EXISTS `traspaso_producto` (
  `id_traspaso` int(11) NOT NULL COMMENT 'Id del traspaso',
  `id_producto` int(11) NOT NULL COMMENT 'Id del producto a traspasar',
  `id_unidad` int(11) NOT NULL,
  `cantidad_enviada` float NOT NULL DEFAULT '0' COMMENT 'cantidad de producto a traspasar',
  `cantidad_recibida` float NOT NULL DEFAULT '0' COMMENT 'Cantidad de producto recibida',
  `id_lote_origen` int(11) NOT NULL COMMENT 'id del lote de donde provienen los productos',
  PRIMARY KEY (`id_traspaso`,`id_producto`,`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='detalle traspaso producto';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

CREATE TABLE IF NOT EXISTS `ubicacion` (
  `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT,
  `pasillo` varchar(128) NOT NULL,
  `estante` varchar(128) NOT NULL,
  `fila` varchar(128) NOT NULL,
  `caja` varchar(128) NOT NULL,
  PRIMARY KEY (`id_ubicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Maneja las ubicaciones fÃƒÆ’Ã‚Â­sicas de los productos en el alm' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_medida`
--

CREATE TABLE IF NOT EXISTS `unidad_medida` (
  `id_unidad_medida` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria de la llave',
  `id_categoria_unidad_medida` int(11) NOT NULL COMMENT 'Id de la categoria de unidad de medidad a la que pertenece',
  `descripcion` varchar(50) NOT NULL COMMENT 'Descripcion de la nueva unidad de medida',
  `abreviacion` varchar(50) NOT NULL COMMENT 'Descripcion corta de la nueva unidad de medida',
  `tipo_unidad_medida` enum('Referencia UdM para esta categoria','Mayor que la UdM de referencia','Menor que la UdM de referencia') NOT NULL COMMENT 'Indica que tipo de unidad de medida',
  `factor_conversion` float NOT NULL COMMENT 'Numero de veces que es mas grande esta UdM que la de referencia',
  `activa` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_unidad_medida`),
  KEY `id_categoria_unidad_medida` (`id_categoria_unidad_medida`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Almacena las diferentes unidades de medida para un producto' AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla usuario',
  `id_direccion` int(11) DEFAULT NULL COMMENT 'Id de la direccion del usuario',
  `id_direccion_alterna` int(11) DEFAULT NULL COMMENT 'Id de la direccion alterna del usuario',
  `id_sucursal` int(11) DEFAULT NULL COMMENT 'Id sucursal en la que labora este usuario o dodne se dio de alta',
  `id_rol` int(11) NOT NULL COMMENT 'Id del rol que desempeÃƒÆ’Ã‚Â±ara el usuario en la instancia',
  `id_clasificacion_cliente` int(11) DEFAULT NULL COMMENT 'Id de la clasificaiocn del cliente',
  `id_clasificacion_proveedor` int(11) DEFAULT NULL COMMENT 'Id de la clasificacion del proveedor',
  `id_moneda` int(11) DEFAULT NULL COMMENT 'Id moneda de preferencia del usuario',
  `fecha_asignacion_rol` int(11) NOT NULL COMMENT 'Fecha en que se asigno o modifico el rol de este usuario',
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT 'Nombre del agente',
  `rfc` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT 'RFC del agente',
  `curp` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT 'CURP del agente',
  `comision_ventas` float DEFAULT NULL COMMENT 'Comision sobre las ventas que recibira este agente',
  `telefono_personal1` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Telefono personal del agente',
  `telefono_personal2` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Telefono personal del agente',
  `fecha_alta` int(11) NOT NULL COMMENT 'Fecha en que se creo este usuario',
  `fecha_baja` int(11) DEFAULT NULL COMMENT 'fecha en que se desactivo este usuario',
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'si este usuario esta activo o no',
  `limite_credito` float NOT NULL DEFAULT '0' COMMENT 'Limite de credito del usuario',
  `descuento` float DEFAULT NULL COMMENT 'Porcentaje del descuento del usuario',
  `password` varchar(64) NOT NULL COMMENT 'Password del usuario',
  `last_login` int(11) DEFAULT NULL COMMENT 'Fecha en la que ingreso el usuario por ultima vez',
  `consignatario` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Si el usuario es consignatario',
  `salario` float DEFAULT NULL COMMENT 'El salario que recibe el usuaario actualmente',
  `correo_electronico` varchar(30) DEFAULT NULL COMMENT 'Correo electronico del usuario',
  `pagina_web` varchar(30) DEFAULT NULL COMMENT 'Pagina Web del usuario',
  `saldo_del_ejercicio` float NOT NULL DEFAULT '0' COMMENT 'Saldo del ejercicio del cliente',
  `ventas_a_credito` int(11) DEFAULT NULL COMMENT 'Ventas a credito del cliente',
  `representante_legal` varchar(100) DEFAULT NULL COMMENT 'Nombre del representante legal del usuario',
  `facturar_a_terceros` tinyint(1) DEFAULT NULL COMMENT 'Si el cliente puede facturar a terceros',
  `dia_de_pago` int(11) DEFAULT NULL COMMENT 'Fecha de pago del cliente',
  `mensajeria` tinyint(1) DEFAULT NULL COMMENT 'Si el cliente cuenta con una cuenta de mensajerÃƒÆ’Ã‚Â­a y paqueterÃƒÆ’Ã‚Â­a',
  `intereses_moratorios` float DEFAULT NULL COMMENT 'Intereses moratorios del cliente',
  `denominacion_comercial` varchar(100) DEFAULT NULL COMMENT 'DenominaciÃƒÆ’Ã‚Â³n comercial del cliente',
  `dias_de_credito` int(11) DEFAULT NULL COMMENT 'DÃƒÆ’Ã‚Â­as de crÃƒÆ’Ã‚Â©dito que se le darÃƒÆ’Ã‚Â¡n al cliente',
  `cuenta_de_mensajeria` varchar(50) DEFAULT NULL COMMENT 'Cuenta de mensajeria del cliente',
  `dia_de_revision` int(11) DEFAULT NULL COMMENT 'Fecha de revisiÃƒÆ’Ã‚Â³n del cliente',
  `codigo_usuario` varchar(50) DEFAULT NULL COMMENT 'Codigo del usuario para uso interno de la empresa',
  `dias_de_embarque` int(11) DEFAULT NULL COMMENT 'Dias de embarque del proveedor (Lunes, Martes, etc)',
  `tiempo_entrega` int(11) DEFAULT NULL COMMENT 'Tiempo de entrega del proveedor en dÃƒÆ’Ã‚Â­as',
  `cuenta_bancaria` varchar(50) DEFAULT NULL COMMENT 'Cuenta bancaria del usuario',
  `id_tarifa_compra` int(11) NOT NULL COMMENT 'Id de la tarifa de compra por default para este usuario',
  `tarifa_compra_obtenida` enum('rol','proveedor','cliente','usuario') NOT NULL COMMENT 'Indica de donde fue obtenida la tarifa de compra',
  `id_tarifa_venta` int(11) NOT NULL COMMENT 'Id de la tarifa de venta por default para este usuario',
  `tarifa_venta_obtenida` enum('rol','proveedor','cliente','usuario') NOT NULL COMMENT 'Indica de donde fue obtenida la tarifa de venta',
  `token_recuperacion_pass` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT 'El token que se envia por correo para recuperar contrasena',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='tabla de usuarios' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_seguimiento`
--

CREATE TABLE IF NOT EXISTS `usuario_seguimiento` (
  `id_usuario_seguimiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_redacto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` int(11) NOT NULL,
  `texto` text NOT NULL,
  PRIMARY KEY (`id_usuario_seguimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE IF NOT EXISTS `venta` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `es_cotizacion` int(1) NOT NULL COMMENT 'verdadero si es una cotizacion',
  `id_caja` int(11) DEFAULT NULL COMMENT 'la caja donde se hizo la venta, esta puede ser null ya que un gerente puede vender en el sistema web',
  `id_venta_caja` int(11) DEFAULT NULL COMMENT 'el id de la venta de esta caja',
  `id_comprador_venta` int(11) NOT NULL COMMENT 'Id del usuario al que se le vende',
  `tipo_de_venta` enum('contado','credito') NOT NULL COMMENT 'nota si esta fue venta a contado o a credito',
  `fecha` int(11) NOT NULL COMMENT 'la fecha de esta venta',
  `subtotal` float NOT NULL,
  `impuesto` float NOT NULL,
  `descuento` float DEFAULT NULL,
  `total` float NOT NULL COMMENT 'el total a pagar',
  `id_sucursal` int(11) DEFAULT NULL COMMENT 'el id de donde se hizo la venta, aunque ya tenemos en que caja se hizo, guardaremos la sucursal ya que la caja puede haberse ido ademas para hacer busquedas mas rapidas',
  `id_usuario` int(11) NOT NULL COMMENT 'el usuario que hizo esta venta',
  `saldo` float NOT NULL COMMENT 'el saldo pendiente por abonar en esta venta',
  `cancelada` tinyint(1) NOT NULL COMMENT 'Si la venta ha sido cancelada',
  `tipo_de_pago` enum('cheque','tarjeta','efectivo') DEFAULT NULL COMMENT 'Si la venta fue pagada con tarjeta, cheque, o en efectivo',
  `retencion` float NOT NULL COMMENT 'Monto de retencion',
  PRIMARY KEY (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_arpilla`
--

CREATE TABLE IF NOT EXISTS `venta_arpilla` (
  `id_venta_arpilla` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la venta por arpilla',
  `id_venta` int(11) NOT NULL COMMENT 'Id de la venta en arpillas',
  `peso_destino` float NOT NULL COMMENT 'Peso del embarque en el destino',
  `fecha_origen` int(11) NOT NULL COMMENT 'Fecha en la que se envÃƒÆ’Ã‚Â­a el embarque',
  `folio` varchar(11) DEFAULT NULL COMMENT 'Folio de la entrega',
  `numero_de_viaje` varchar(11) DEFAULT NULL COMMENT 'Numero de viaje',
  `peso_origen` float NOT NULL COMMENT 'Peso del embarque en el origen',
  `arpillas` float NOT NULL COMMENT 'Numero de arpillas enviadas',
  `peso_por_arpilla` float NOT NULL COMMENT 'Promedio de peso por arpilla',
  `productor` varchar(64) DEFAULT NULL COMMENT 'Nombre del productor',
  `merma_por_arpilla` float NOT NULL COMMENT 'Merma por arpilla',
  `total_origen` float DEFAULT NULL COMMENT 'Valor del embarque',
  PRIMARY KEY (`id_venta_arpilla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla que detalla una venta realizada mediante un embarque d' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_aval`
--

CREATE TABLE IF NOT EXISTS `venta_aval` (
  `id_venta` int(11) NOT NULL,
  `id_aval` int(11) NOT NULL,
  PRIMARY KEY (`id_venta`,`id_aval`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_empresa`
--

CREATE TABLE IF NOT EXISTS `venta_empresa` (
  `id_venta` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `total` float NOT NULL COMMENT 'El total correspondiente',
  `saldada` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Si la venta ya fue saldada o aun no lo ha sido',
  PRIMARY KEY (`id_venta`,`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle entre venta y empresa';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_orden`
--

CREATE TABLE IF NOT EXISTS `venta_orden` (
  `id_venta` int(11) NOT NULL COMMENT 'Id de la venta en la que se vendieron las ordenes de servicio',
  `id_orden_de_servicio` int(11) NOT NULL COMMENT 'Id de la orden de servicio que se vendio',
  `precio` float NOT NULL COMMENT 'El precio de la orden',
  `descuento` float NOT NULL COMMENT 'El descuento de la orden',
  `impuesto` float NOT NULL COMMENT 'Cantidad aÃƒÆ’Ã‚Â±adida por los impuestos',
  `retencion` float NOT NULL COMMENT 'Cantidad aÃƒÆ’Ã‚Â±adida por las retenciones',
  PRIMARY KEY (`id_venta`,`id_orden_de_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle venta ordenes de servicio';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_paquete`
--

CREATE TABLE IF NOT EXISTS `venta_paquete` (
  `id_venta` int(11) NOT NULL,
  `id_paquete` int(11) NOT NULL,
  `cantidad` float NOT NULL,
  `precio` float NOT NULL,
  `descuento` float NOT NULL,
  PRIMARY KEY (`id_venta`,`id_paquete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='detalle venta paquete';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_producto`
--

CREATE TABLE IF NOT EXISTS `venta_producto` (
  `id_venta` int(11) NOT NULL COMMENT 'id de la venta',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto vendido',
  `precio` float NOT NULL COMMENT 'precio unitario con el que se vendio el producto',
  `cantidad` float NOT NULL COMMENT 'cantidad de producto que se vendio',
  `descuento` float NOT NULL COMMENT 'descuento que se aplico al producto',
  `impuesto` float NOT NULL COMMENT 'impuesto que se aplico al producto',
  `retencion` float NOT NULL COMMENT 'Retencion unitaria en el producto',
  `id_unidad` int(11) NOT NULL COMMENT 'Id de la unidad del producto',
  PRIMARY KEY (`id_venta`,`id_producto`,`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla detalle entre una venta y los productos que se vendier';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `version`
--

CREATE TABLE IF NOT EXISTS `version` (
  `id_version` int(11) NOT NULL AUTO_INCREMENT,
  `id_tarifa` int(11) NOT NULL COMMENT 'Id de la tarifa a la que pertenece esta version',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de la version',
  `activa` tinyint(1) NOT NULL COMMENT 'Si la version es la version activa de esta tarifa',
  `fecha_inicio` int(11) DEFAULT NULL COMMENT 'Fecha a partir de la cual se aplican las reglas de esta version',
  `fecha_fin` int(11) DEFAULT NULL COMMENT 'Fecha a partir de la cual se dejaran de aplicar las reglas de esta version',
  `default` tinyint(1) NOT NULL COMMENT 'Si esta version es la version default de la tarifa',
  PRIMARY KEY (`id_version`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
