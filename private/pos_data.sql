-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 14-04-2011 a las 16:37:22
-- Versión del servidor: 5.5.8
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pos`
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
  `precio_venta_sin_procesar` float NOT NULL COMMENT 'El precio a que se vende este producto sin procesar si es que se procesa',
  `precio_intersucursal` float NOT NULL,
  `precio_intersucursal_sin_procesar` float NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_actualizacion`),
  KEY `id_producto` (`id_producto`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Actualizaciones de precios' AUTO_INCREMENT=21 ;

--
-- Volcar la base de datos para la tabla `actualizacion_de_precio`
--

INSERT INTO `actualizacion_de_precio` (`id_actualizacion`, `id_producto`, `id_usuario`, `precio_venta`, `precio_venta_sin_procesar`, `precio_intersucursal`, `precio_intersucursal_sin_procesar`, `fecha`) VALUES
(1, 1, 101, 10, 8.5, 9.9, 9.8, '2011-02-11 00:01:43'),
(2, 1, 101, 11, 10, 10.5, 9.5, '2011-02-11 00:02:38'),
(3, 2, 101, 9, 8.5, 8, 7.5, '2011-02-11 00:04:49'),
(4, 3, 101, 8, 7.5, 7, 6.5, '2011-02-11 00:03:31'),
(5, 4, 101, 7, 5.5, 7, 4.5, '2011-02-22 17:32:38'),
(6, 5, 101, 6, 0, 6, 0, '2011-01-09 11:36:11'),
(7, 6, 101, 5, 0, 5, 0, '2011-01-09 11:36:39'),
(8, 7, 101, 0, 0, 0, 0, '2011-01-13 11:30:42'),
(9, 8, 101, 0, 0, 0, 0, '2011-01-13 11:31:39'),
(10, 9, 101, 5, 0, 4, 0, '2011-01-14 10:15:01'),
(11, 9, 101, 5.5, 0, 4.4, 0, '2011-01-14 10:29:34'),
(12, 9, 101, 5.5, 0, 4.4, 0, '2011-01-14 10:29:48'),
(13, 10, 101, 12, 0, 11, 0, '2011-01-14 10:32:15'),
(14, 10, 101, 12.12, 0, 11.11, 0, '2011-01-14 10:39:46'),
(15, 11, 101, 0, 0, 0, 0, '2011-01-14 10:46:46'),
(16, 12, 101, 0, 0, 0, 0, '2011-01-14 11:01:20'),
(17, 7, 101, 5, 0, 5, 0, '2011-01-16 23:35:22'),
(18, 13, 101, 5, 8, 8, 8, '2011-02-08 21:47:37'),
(19, 14, 101, 20, 20, 20, 20, '2011-02-08 21:48:12'),
(20, 15, 101, 7, 6, 6, 6, '2011-02-08 21:48:43');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=139 ;

--
-- Volcar la base de datos para la tabla `autorizacion`
--

INSERT INTO `autorizacion` (`id_autorizacion`, `id_usuario`, `id_sucursal`, `fecha_peticion`, `fecha_respuesta`, `estado`, `parametros`, `tipo`) VALUES
(1, 101, 1, '2011-01-22 03:36:41', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"13","id_producto":"2","cantidad":5,"procesada":false,"descuento":1,"precio":9}]}', 'envioDeProductosASucursal'),
(2, 101, 1, '2011-01-22 04:29:05', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"14","id_producto":"1","cantidad":5,"procesada":true,"descuento":0,"precio":5},{"id_compra":"14","id_producto":"1","cantidad":5,"procesada":false,"descuento":1,"precio":12},{"id_compra":"14","id_producto":"2","cantidad":10,"procesada":false,"descuento":2,"precio":4},{"id_compra":"14","id_producto":"1","cantidad":15,"procesada":false,"descuento":1,"precio":1}]}', 'envioDeProductosASucursal'),
(3, 101, 1, '2011-01-22 05:14:14', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"15","id_producto":"1","cantidad":5,"procesada":false,"descuento":0,"precio":12}]}', 'envioDeProductosASucursal'),
(4, 102, 1, '2011-01-26 01:19:38', NULL, 0, '{"clave":210,"descripcion":"Solicitud de producto","productos":[{"productoID":1,"descripcion":"papas primeras","tratamiento":"limpia","precioVenta":"11","precioVentaSinProcesar":"8.5","existenciasOriginales":"20","existenciasProcesadas":"1","medida":"kilogramo","precioIntersucursal":"10","precioIntersucursalSinProcesar":"9","cantidad":124,"id_producto":1},{"productoID":2,"descripcion":"papa segunda","tratamiento":"","precioVenta":"9","precioVentaSinProcesar":"0","existenciasOriginales":"15","existenciasProcesadas":"0","medida":"kilogramo","precioIntersucursal":"9","precioIntersucursalSinProcesar":"0","cantidad":455,"id_producto":2}]}', ''),
(5, 102, 1, '2011-01-26 01:41:55', NULL, 0, '{"clave":210,"descripcion":"Solicitud de producto","productos":[{"productoID":1,"descripcion":"papas primeras","tratamiento":"limpia","precioVenta":"11","precioVentaSinProcesar":"8.5","existenciasOriginales":"20","existenciasProcesadas":"1","medida":"kilogramo","precioIntersucursal":"10","precioIntersucursalSinProcesar":"9","cantidad":124,"id_producto":1},{"productoID":2,"descripcion":"papa segunda","tratamiento":"","precioVenta":"9","precioVentaSinProcesar":"0","existenciasOriginales":"15","existenciasProcesadas":"0","medida":"kilogramo","precioIntersucursal":"9","precioIntersucursalSinProcesar":"0","cantidad":455,"id_producto":2}]}', ''),
(6, 102, 1, '2011-01-26 02:05:25', NULL, 0, '{"clave":210,"descripcion":"Solicitud de producto","productos":[{"productoID":1,"descripcion":"papas primeras","tratamiento":"limpia","precioVenta":"11","precioVentaSinProcesar":"8.5","existenciasOriginales":"20","existenciasProcesadas":"1","medida":"kilogramo","precioIntersucursal":"10","precioIntersucursalSinProcesar":"9","cantidad":6,"id_producto":1}]}', ''),
(7, 101, 2, '2011-01-26 22:51:52', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"16","id_producto":"2","cantidad":100,"procesada":true,"descuento":0,"precio":10}]}', 'envioDeProductosASucursal'),
(8, 101, 2, '2011-01-26 22:56:26', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"17","id_producto":"2","cantidad":1,"procesada":true,"descuento":0,"precio":9}]}', 'envioDeProductosASucursal'),
(9, 101, 2, '2011-01-26 23:02:33', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"18","id_producto":"2","cantidad":1,"procesada":false,"descuento":0,"precio":9}]}', 'envioDeProductosASucursal'),
(10, 101, 1, '2011-01-27 00:18:18', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"3","cantidad":"5000"}', ''),
(11, 101, 1, '2011-01-27 23:46:47', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"19","id_producto":"1","cantidad":10,"procesada":true,"descuento":0,"precio":1}]}', 'envioDeProductosASucursal'),
(12, 101, 1, '2011-01-27 23:57:42', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"20","id_producto":"1","cantidad":0,"procesada":false,"descuento":0,"precio":5},{"id_compra":"20","id_producto":"1","cantidad":0,"procesada":true,"descuento":0,"precio":5}]}', 'envioDeProductosASucursal'),
(13, 101, 1, '2011-01-27 23:58:01', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"21","id_producto":"1","cantidad":0,"procesada":true,"descuento":0,"precio":5},{"id_compra":"21","id_producto":"1","cantidad":0,"procesada":true,"descuento":0,"precio":12}]}', 'envioDeProductosASucursal'),
(14, 101, 1, '2011-01-27 23:58:21', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"22","id_producto":"1","cantidad":0,"procesada":true,"descuento":0,"precio":5},{"id_compra":"22","id_producto":"1","cantidad":0,"procesada":true,"descuento":0,"precio":12}]}', 'envioDeProductosASucursal'),
(15, 102, 1, '2011-01-28 00:17:03', '2011-01-28 13:48:01', 1, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"5","id_producto":"1","cantidad":"1"}', 'solicitudDeDevolucion'),
(16, 101, 1, '2011-01-28 01:06:28', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"23","id_producto":"2","cantidad":2,"procesada":true,"descuento":0,"precio":9}]}', 'envioDeProductosASucursal'),
(17, 102, 1, '2011-01-28 02:20:28', '2011-01-28 13:43:58', 2, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"2","cantidad":"2000"}', 'solicitudDeCambioLimiteDeCredito'),
(18, 102, 1, '2011-01-28 02:23:12', NULL, 0, '{"clave":210,"descripcion":"Solicitud de producto","productos":[{"productoID":1,"descripcion":"papas primeras","tratamiento":"limpia","precioVenta":"11","precioVentaSinProcesar":"8.5","existenciasOriginales":"20","existenciasProcesadas":"1","medida":"kilogramo","precioIntersucursal":"10","precioIntersucursalSinProcesar":"9","cantidad":2000,"id_producto":1}]}', 'solicitudDeProductos'),
(19, 102, 1, '2011-01-30 01:52:27', '2011-01-30 01:58:57', 1, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"3","cantidad":"100"}', 'solicitudDeCambioLimiteDeCredito'),
(20, 102, 1, '2011-01-30 02:03:44', '2011-01-30 02:33:31', 2, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"3","cantidad":"150"}', 'solicitudDeCambioLimiteDeCredito'),
(21, 102, 1, '2011-01-30 02:31:50', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"3","cantidad":"152"}', 'solicitudDeCambioLimiteDeCredito'),
(22, 102, 1, '2011-01-30 02:32:00', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"3","cantidad":"50"}', 'solicitudDeCambioLimiteDeCredito'),
(23, 102, 1, '2011-01-30 02:33:02', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"3","cantidad":"155"}', 'solicitudDeCambioLimiteDeCredito'),
(24, 102, 1, '2011-01-30 02:40:27', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"2","id_producto":"1","cantidad":"1","cantidad_procesada":"1"}', 'solicitudDeDevolucion'),
(25, 102, 1, '2011-01-30 14:17:09', '2011-01-30 16:13:34', 1, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"9","id_producto":"1","cantidad":"1","cantidad_procesada":"1"}', 'solicitudDeDevolucion'),
(26, 102, 1, '2011-01-30 14:34:15', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"9","id_producto":"1","cantidad":"0","cantidad_procesada":"0"}', 'solicitudDeDevolucion'),
(27, 102, 1, '2011-01-30 22:33:47', '2011-01-30 22:45:50', 2, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"4"}', ''),
(28, 102, 1, '2011-01-30 22:34:37', '2011-01-30 22:45:47', 2, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"3"}', ''),
(29, 102, 1, '2011-01-30 22:40:42', '2011-01-30 22:45:44', 1, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"6","nombre":null}', ''),
(30, 102, 1, '2011-01-30 22:44:48', '2011-01-30 22:45:38', 1, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"3","nombre":"Dilba monica del moral cuevas"}', ''),
(31, 101, 1, '2011-01-30 22:55:08', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"24","id_producto":"1","cantidad":100,"procesada":false,"descuento":0,"precio":1}]}', 'envioDeProductosASucursal'),
(32, 101, 1, '2011-01-30 23:00:15', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"25","id_producto":"1","cantidad":40,"procesada":false,"descuento":0,"precio":6.5}]}', 'envioDeProductosASucursal'),
(33, 102, 1, '2011-02-03 04:57:30', NULL, 0, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"1","nombre":"Jose alfredo jimenez"}', ''),
(34, 102, 1, '2011-02-04 18:48:47', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"9","id_producto":"1","cantidad":"1","cantidad_procesada":"0"}', 'solicitudDeDevolucion'),
(35, 102, 1, '2011-02-07 14:57:38', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"4","cantidad":"1"}', 'solicitudDeCambioLimiteDeCredito'),
(36, 102, 1, '2011-02-07 20:11:48', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"4","cantidad":"1500"}', 'solicitudDeCambioLimiteDeCredito'),
(37, 102, 1, '2011-02-07 20:12:06', '2011-02-07 23:43:28', 1, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"6","nombre":"Cliente de Prueba 3"}', ''),
(38, 102, 1, '2011-02-07 20:54:56', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"1","id_producto":"1","cantidad":1,"cantidad_procesada":0}', 'solicitudDeDevolucion'),
(39, 102, 1, '2011-02-07 20:55:12', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"8","id_producto":"1","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(40, 102, 1, '2011-02-07 20:56:49', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"1","id_producto":"1","cantidad":0.55,"cantidad_procesada":0}', 'solicitudDeDevolucion'),
(41, 102, 1, '2011-02-07 21:02:13', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"1","id_producto":"1","cantidad":1,"cantidad_procesada":0}', 'solicitudDeDevolucion'),
(42, 102, 1, '2011-02-07 22:42:21', '2011-02-07 23:08:20', 1, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"2","nombre":"asdfasdfasdf"}', ''),
(43, 102, 1, '2011-02-08 00:00:24', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"4","cantidad":"1"}', 'solicitudDeCambioLimiteDeCredito'),
(44, 102, 1, '2011-02-08 00:00:29', NULL, 0, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"5","nombre":"Cliente de Prueba 2"}', ''),
(45, 102, 1, '2011-02-08 00:00:34', '2011-02-08 01:25:28', 1, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"3","nombre":"Dilba monica del moral cuevas"}', ''),
(46, 102, 1, '2011-02-08 00:00:41', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"5","cantidad":"56"}', 'solicitudDeCambioLimiteDeCredito'),
(47, 102, 1, '2011-02-08 00:00:54', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"1","id_producto":"1","cantidad":1,"cantidad_procesada":0}', 'solicitudDeDevolucion'),
(48, 102, 1, '2011-02-08 21:53:57', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"26","id_producto":"6","cantidad":1000,"procesada":false,"descuento":0,"precio":12},{"id_compra":"26","id_producto":"5","cantidad":1000,"procesada":false,"descuento":0,"precio":34},{"id_compra":"26","id_producto":"4","cantidad":1000,"procesada":false,"descuento":0,"precio":78},{"id_compra":"26","id_producto":"4","cantidad":1000,"procesada":false,"descuento":0,"precio":78},{"id_compra":"26","id_producto":"3","cantidad":500,"procesada":false,"descuento":0,"precio":8},{"id_compra":"26","id_producto":"3","cantidad":100,"procesada":false,"descuento":0,"precio":8}]}', 'envioDeProductosASucursal'),
(49, 102, 1, '2011-02-08 22:27:05', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"27","id_producto":"3","cantidad":500,"procesada":true,"descuento":0,"precio":8}]}', 'envioDeProductosASucursal'),
(50, 102, 1, '2011-02-09 14:51:32', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"28","id_producto":"3","cantidad":14,"procesada":false,"descuento":0,"precio":14}]}', 'envioDeProductosASucursal'),
(51, 102, 1, '2011-02-09 14:53:06', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_compra":"29","id_producto":"6","cantidad":15,"procesada":false,"descuento":0,"precio":12}]}', 'envioDeProductosASucursal'),
(52, 102, 1, '2011-02-09 15:52:26', '2011-02-09 15:52:51', 1, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"3","nombre":"Dilba monica del moral cuevas"}', ''),
(53, 102, 1, '2011-02-10 00:04:27', '2011-02-10 00:10:10', 6, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"3","nombre":"Dilba monica del moral cuevas"}', ''),
(54, 102, 1, '2011-02-10 00:09:35', '2011-02-10 01:52:44', 6, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"3","nombre":"Dilba monica del moral cuevas"}', ''),
(58, 102, 1, '2011-02-11 02:51:09', '2011-02-11 02:51:09', 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":"true","cantidad_procesada":1,"precio_procesada":"10.5","cantidad":1,"precio":"9.5"},{"id_producto":2,"procesado":"true","cantidad_procesada":1,"precio_procesada":"8","cantidad":1,"precio":"7.5"},{"id_producto":3,"procesado":"true","cantidad_procesada":1,"precio_procesada":"7","cantidad":0,"precio":0}]}', 'envioDeProductosASucursal'),
(59, 102, 2, '2011-02-11 03:01:03', '2011-02-11 03:01:03', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":"true","cantidad_procesada":4,"precio_procesada":"10.5","cantidad":0,"precio":0},{"id_producto":2,"procesado":"true","cantidad_procesada":4,"precio_procesada":"8","cantidad":0,"precio":0},{"id_producto":3,"procesado":"true","cantidad_procesada":2,"precio_procesada":"7","cantidad":0,"precio":0}]}', 'envioDeProductosASucursal'),
(60, 102, 2, '2011-02-11 03:02:45', '2011-02-11 03:02:45', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":"true","cantidad_procesada":3,"precio_procesada":"10.5","cantidad":3,"precio":"9.5"},{"id_producto":2,"procesado":"true","cantidad_procesada":3,"precio_procesada":"8","cantidad":3,"precio":"7.5"},{"id_producto":3,"procesado":"true","cantidad_procesada":3,"precio_procesada":"7","cantidad":0,"precio":0}]}', 'envioDeProductosASucursal'),
(61, 102, 2, '2011-02-11 03:03:34', '2011-02-11 03:03:34', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":3,"procesado":"true","cantidad_procesada":1,"precio_procesada":"7","cantidad":0,"precio":0}]}', 'envioDeProductosASucursal'),
(62, 102, 2, '2011-02-11 03:05:14', '2011-02-11 03:05:14', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":"true","cantidad_procesada":1,"precio_procesada":"10.5","cantidad":1,"precio":"9.5"},{"id_producto":2,"procesado":"true","cantidad_procesada":1,"precio_procesada":"8","cantidad":1,"precio":"7.5"},{"id_producto":3,"procesado":"true","cantidad_procesada":1,"precio_procesada":"7","cantidad":1,"precio":"6.5"}]}', 'envioDeProductosASucursal'),
(63, 102, 2, '2011-02-11 03:05:51', '2011-02-11 03:05:51', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":"true","cantidad_procesada":1,"precio_procesada":"10.5","cantidad":0,"precio":0},{"id_producto":2,"procesado":"true","cantidad_procesada":1,"precio_procesada":"8","cantidad":0,"precio":0},{"id_producto":3,"procesado":"true","cantidad_procesada":1,"precio_procesada":"7","cantidad":0,"precio":0}]}', 'envioDeProductosASucursal'),
(64, 102, 2, '2011-02-11 03:06:28', '2011-02-11 03:06:28', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":"true","cantidad_procesada":1,"precio_procesada":"10.5","cantidad":1,"precio":"9.5"},{"id_producto":2,"procesado":"true","cantidad_procesada":1,"precio_procesada":"8","cantidad":1,"precio":"7.5"},{"id_producto":3,"procesado":"true","cantidad_procesada":1,"precio_procesada":"7","cantidad":0,"precio":0}]}', 'envioDeProductosASucursal'),
(65, 102, 2, '2011-02-11 03:10:14', '2011-02-11 03:10:14', 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":"true","cantidad_procesada":12,"precio_procesada":"10.5","cantidad":65,"precio":"9.5"},{"id_producto":2,"procesado":"true","cantidad_procesada":90,"precio_procesada":"8","cantidad":57,"precio":"7.5"},{"id_producto":3,"procesado":"true","cantidad_procesada":68,"precio_procesada":"7","cantidad":97,"precio":"6.5"}]}', 'envioDeProductosASucursal'),
(66, 102, 2, '2011-02-11 03:32:35', '2011-02-11 03:32:35', 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":"true","cantidad_procesada":100,"precio_procesada":"10.5","cantidad":100,"precio":"9.5"},{"id_producto":2,"procesado":"true","cantidad_procesada":100,"precio_procesada":"8","cantidad":100,"precio":"7.5"},{"id_producto":3,"procesado":"true","cantidad_procesada":100,"precio_procesada":"7","cantidad":100,"precio":"6.5"}]}', 'envioDeProductosASucursal'),
(67, 102, 1, '2011-02-11 04:01:56', '2011-02-11 04:02:18', 1, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"3","nombre":"Dilba monica del moral cuevas"}', ''),
(68, 102, 1, '2011-02-11 16:59:32', NULL, 0, '{"clave":209,"descripcion":"Solicitud de producto","productos":[{"productoID":4,"descripcion":"papa cuarta","tratamiento":"limpia","precioVenta":"7","precioVentaSinProcesar":"0","existenciasOriginales":"1998","existenciasProcesadas":"0","medida":"kilogramo","precioIntersucursal":"7","precioIntersucursalSinProcesar":"0","cantidad":5,"id_producto":4},{"productoID":2,"descripcion":"papa segunda","tratamiento":"limpia","precioVenta":"9","precioVentaSinProcesar":"8.5","existenciasOriginales":"1700","existenciasProcesadas":"1199","medida":"kilogramo","precioIntersucursal":"8","precioIntersucursalSinProcesar":"7.5","cantidad":4,"id_producto":2}]}', 'solicitudDeProductos'),
(70, 102, 1, '2011-02-12 02:32:18', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"-2","cantidad":"5000"}', 'solicitudDeCambioLimiteDeCredito'),
(71, 102, 1, '2011-02-12 04:22:05', '2011-02-12 04:24:37', 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":200,"precio":4.5}]}', 'envioDeProductosASucursal'),
(72, 101, 1, '2011-02-12 04:41:59', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":517,"precio":8}]}', 'envioDeProductosASucursal'),
(73, 101, 1, '2011-02-12 04:44:38', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":2,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":200,"precio":10.5}]}', 'envioDeProductosASucursal'),
(74, 102, 1, '2011-02-15 22:53:02', NULL, 4, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":100,"precio":12}]}', 'envioDeProductosASucursal'),
(75, 102, 1, '2011-02-15 22:54:07', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":2,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":500,"precio":9}]}', 'envioDeProductosASucursal'),
(76, 102, 1, '2011-02-17 21:33:12', NULL, 0, '{"clave":209,"descripcion":"Solicitud de producto","productos":[{"productoID":6,"descripcion":"papa para dorar","tratamiento":"","precioVenta":"5","precioVentaSinProcesar":"0","existenciasOriginales":"1006","existenciasProcesadas":"0","medida":"pieza","precioIntersucursal":"5","precioIntersucursalSinProcesar":"0","cantidad":10,"id_producto":6},{"productoID":5,"descripcion":"papas quinta","tratamiento":"limpia","precioVenta":"6","precioVentaSinProcesar":"0","existenciasOriginales":"995","existenciasProcesadas":"0","medida":"kilogramo","precioIntersucursal":"6","precioIntersucursalSinProcesar":"0","cantidad":100,"id_producto":5},{"productoID":3,"descripcion":"papa tercera","tratamiento":"limpia","precioVenta":"8","precioVentaSinProcesar":"7.5","existenciasOriginales":"374","existenciasProcesadas":"276","medida":"pieza","precioIntersucursal":"7","precioIntersucursalSinProcesar":"6.5","cantidad":123,"id_producto":3}]}', 'solicitudDeProductos'),
(77, 101, 1, '2011-02-17 21:35:15', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":200,"precio":9}]}', 'envioDeProductosASucursal'),
(78, 102, 1, '2011-02-19 04:22:17', NULL, 0, '{"clave":209,"descripcion":"Solicitud de producto","productos":[{"productoID":5,"descripcion":"papas quinta","tratamiento":"limpia","precioVenta":"6","precioVentaSinProcesar":"0","existenciasOriginales":"995","existenciasProcesadas":"0","medida":"kilogramo","precioIntersucursal":"6","precioIntersucursalSinProcesar":"0","cantidad":3,"id_producto":5},{"productoID":6,"descripcion":"papa para dorar","tratamiento":"","precioVenta":"5","precioVentaSinProcesar":"0","existenciasOriginales":"1006","existenciasProcesadas":"0","medida":"pieza","precioIntersucursal":"5","precioIntersucursalSinProcesar":"0","cantidad":2,"id_producto":6},{"productoID":2,"descripcion":"papa segunda","tratamiento":"limpia","precioVenta":"9","precioVentaSinProcesar":"8.5","existenciasOriginales":"5677","existenciasProcesadas":"1179","medida":"kilogramo","precioIntersucursal":"8","precioIntersucursalSinProcesar":"7.5","cantidad":5,"id_producto":2}]}', 'solicitudDeProductos'),
(79, 102, 1, '2011-02-19 04:23:36', NULL, 0, '{"clave":209,"descripcion":"Solicitud de producto","productos":[{"productoID":1,"descripcion":"papas primeras","tratamiento":"limpia","precioVenta":"11","precioVentaSinProcesar":"10","existenciasOriginales":"2379","existenciasProcesadas":"348","medida":"kilogramo","precioIntersucursal":"10.5","precioIntersucursalSinProcesar":"9.5","cantidad":1,"id_producto":1},{"productoID":2,"descripcion":"papa segunda","tratamiento":"limpia","precioVenta":"9","precioVentaSinProcesar":"8.5","existenciasOriginales":"5677","existenciasProcesadas":"1179","medida":"kilogramo","precioIntersucursal":"8","precioIntersucursalSinProcesar":"7.5","cantidad":2,"id_producto":2},{"productoID":3,"descripcion":"papa tercera","tratamiento":"limpia","precioVenta":"8","precioVentaSinProcesar":"7.5","existenciasOriginales":"374","existenciasProcesadas":"276","medida":"pieza","precioIntersucursal":"7","precioIntersucursalSinProcesar":"6.5","cantidad":3,"id_producto":3}]}', 'solicitudDeProductos'),
(80, 102, 1, '2011-02-19 04:37:25', NULL, 0, '{"clave":209,"descripcion":"Solicitud de producto","productos":[{"productoID":1,"descripcion":"papas primeras","tratamiento":"limpia","precioVenta":"11","precioVentaSinProcesar":"10","existenciasOriginales":"2379","existenciasProcesadas":"347","medida":"kilogramo","precioIntersucursal":"10.5","precioIntersucursalSinProcesar":"9.5","cantidad":1,"procesado":"true","id_producto":1},{"productoID":3,"descripcion":"papa tercera","tratamiento":"limpia","precioVenta":"8","precioVentaSinProcesar":"7.5","existenciasOriginales":"374","existenciasProcesadas":"276","medida":"pieza","precioIntersucursal":"7","precioIntersucursalSinProcesar":"6.5","cantidad":1,"procesado":"true","id_producto":3},{"productoID":6,"descripcion":"papa para dorar","tratamiento":"","precioVenta":"5","precioVentaSinProcesar":"0","existenciasOriginales":"1006","existenciasProcesadas":"0","medida":"pieza","precioIntersucursal":"5","precioIntersucursalSinProcesar":"0","cantidad":1,"procesado":"true","id_producto":6}]}', 'solicitudDeProductos'),
(81, 102, 1, '2011-02-19 04:38:13', NULL, 0, '{"clave":209,"descripcion":"Solicitud de producto","productos":[{"productoID":3,"descripcion":"papa tercera","tratamiento":"limpia","precioVenta":"8","precioVentaSinProcesar":"7.5","existenciasOriginales":"374","existenciasProcesadas":"276","medida":"pieza","precioIntersucursal":"7","precioIntersucursalSinProcesar":"6.5","cantidad":3,"procesado":"true","id_producto":3},{"productoID":4,"descripcion":"papa cuarta","tratamiento":"limpia","precioVenta":"7","precioVentaSinProcesar":"0","existenciasOriginales":"1990","existenciasProcesadas":"0","medida":"kilogramo","precioIntersucursal":"7","precioIntersucursalSinProcesar":"0","cantidad":4,"procesado":"true","id_producto":4},{"productoID":5,"descripcion":"papas quinta","tratamiento":"limpia","precioVenta":"6","precioVentaSinProcesar":"0","existenciasOriginales":"995","existenciasProcesadas":"0","medida":"kilogramo","precioIntersucursal":"6","precioIntersucursalSinProcesar":"0","cantidad":5,"procesado":"true","id_producto":5}]}', 'solicitudDeProductos'),
(82, 102, 1, '2011-02-19 04:46:22', NULL, 0, '{"clave":209,"descripcion":"Solicitud de producto","productos":[{"productoID":5,"descripcion":"papas quinta","tratamiento":"limpia","precioVenta":"6","precioVentaSinProcesar":"0","existenciasOriginales":"995","existenciasProcesadas":"0","medida":"kilogramo","precioIntersucursal":"6","precioIntersucursalSinProcesar":"0","cantidad":5,"procesado":"true","id_producto":5},{"productoID":4,"descripcion":"papa cuarta","tratamiento":"limpia","precioVenta":"7","precioVentaSinProcesar":"0","existenciasOriginales":"1990","existenciasProcesadas":"0","medida":"kilogramo","precioIntersucursal":"7","precioIntersucursalSinProcesar":"0","cantidad":4,"procesado":"false","id_producto":4}]}', 'solicitudDeProductos'),
(83, 102, 2, '2011-02-19 16:06:29', '2011-02-19 16:06:29', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":"true","cantidad_procesada":1,"precio_procesada":"10.5","cantidad":0,"precio":0},{"id_producto":2,"procesado":"true","cantidad_procesada":1,"precio_procesada":"8","cantidad":0,"precio":0}]}', 'envioDeProductosASucursal'),
(84, 102, 2, '2011-02-19 16:06:30', '2011-02-19 16:06:30', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":"true","cantidad_procesada":1,"precio_procesada":"10.5","cantidad":0,"precio":0},{"id_producto":2,"procesado":"true","cantidad_procesada":1,"precio_procesada":"8","cantidad":0,"precio":0}]}', 'envioDeProductosASucursal'),
(85, 102, 1, '2011-02-22 00:44:23', NULL, 0, '{"clave":209,"descripcion":"Solicitud de producto","productos":[{"productoID":6,"descripcion":"papa para dorar","tratamiento":"","precioVenta":"5","precioVentaSinProcesar":"0","existenciasOriginales":"1006","existenciasProcesadas":"0","medida":"pieza","precioIntersucursal":"5","precioIntersucursalSinProcesar":"0","cantidad":3,"procesado":"true","idUnique":"6_1","id_producto":6},{"productoID":6,"descripcion":"papa para dorar","tratamiento":"","precioVenta":"5","precioVentaSinProcesar":"0","existenciasOriginales":"1006","existenciasProcesadas":"0","medida":"pieza","precioIntersucursal":"5","precioIntersucursalSinProcesar":"0","cantidad":5,"procesado":"false","idUnique":"6_2","id_producto":6},{"productoID":4,"descripcion":"papa cuarta","tratamiento":"limpia","precioVenta":"7","precioVentaSinProcesar":"0","existenciasOriginales":"1990","existenciasProcesadas":"0","medida":"kilogramo","precioIntersucursal":"7","precioIntersucursalSinProcesar":"0","cantidad":5,"procesado":"true","idUnique":"4_3","id_producto":4},{"productoID":3,"descripcion":"papa tercera","tratamiento":"limpia","precioVenta":"8","precioVentaSinProcesar":"7.5","existenciasOriginales":"374","existenciasProcesadas":"276","medida":"pieza","precioIntersucursal":"7","precioIntersucursalSinProcesar":"6.5","cantidad":4,"procesado":"true","idUnique":"3_4","id_producto":3},{"productoID":4,"descripcion":"papa cuarta","tratamiento":"limpia","precioVenta":"7","precioVentaSinProcesar":"0","existenciasOriginales":"1990","existenciasProcesadas":"0","medida":"kilogramo","precioIntersucursal":"7","precioIntersucursalSinProcesar":"0","cantidad":1,"procesado":"false","idUnique":"4_5","id_producto":4}]}', 'solicitudDeProductos'),
(86, 102, 1, '2011-02-22 01:12:41', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"4","cantidad":"1000"}', 'solicitudDeCambioLimiteDeCredito'),
(87, 102, 1, '2011-02-22 01:13:03', NULL, 0, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"6","nombre":"Cliente de Prueba 3"}', ''),
(88, 102, 1, '2011-02-22 01:22:25', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"1","cantidad":"190001"}', 'solicitudDeCambioLimiteDeCredito'),
(89, 102, 1, '2011-02-22 01:24:15', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"4","cantidad":"10"}', 'solicitudDeCambioLimiteDeCredito'),
(90, 102, 1, '2011-02-22 01:26:54', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"6","nombre":"Cliente de Prueba 3","cantidad":"200"}', 'solicitudDeCambioLimiteDeCredito'),
(91, 102, 1, '2011-02-22 01:29:20', NULL, 0, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"3","nombre":"Dilba monica del moral cuevas"}', ''),
(92, 102, 1, '2011-02-22 02:34:50', NULL, 0, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"4","nombre":"Cliente de Prueba 1"}', ''),
(93, 102, 1, '2011-02-22 20:20:45', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"2","nombre":"BRENDA ALFARO CARMONA","cantidad":"50000"}', 'solicitudDeCambioLimiteDeCredito'),
(94, 101, 1, '2011-02-23 14:34:54', NULL, 3, '{"clave":209,"descripcion":"Envio de productos","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":450,"precio":5.2}]}', 'envioDeProductosASucursal'),
(95, 101, 1, '2011-02-23 14:41:21', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":3,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":3.5,"precio":9}]}', 'envioDeProductosASucursal'),
(96, 101, 1, '2011-02-23 14:49:09', NULL, 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":2,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":1000,"precio":4}]}', 'envioDeProductosASucursal'),
(97, 102, 1, '2011-02-23 14:55:19', NULL, 4, '{"clave":209,"descripcion":"Envio de productos","conductor":"No especificado","productos":[{"id_producto":3,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":100,"precio":8}]}', 'envioDeProductosASucursal'),
(98, 102, 1, '2011-02-23 18:35:26', NULL, 4, '{"clave":209,"descripcion":"Envio de productos","conductor":"No especificado","productos":[{"id_producto":6,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":9000,"precio":5}]}', 'envioDeProductosASucursal'),
(99, 101, 2, '2011-02-24 02:21:43', NULL, 3, '{"clave":209,"descripcion":"Envio de productos","conductor":"No especificado","productos":[{"id_producto":6,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":5,"precio":12}]}', 'envioDeProductosASucursal'),
(100, 101, 1, '2011-02-25 13:33:18', NULL, 3, '{"clave":209,"descripcion":"Envio de productos","conductor":"alberto sendejas","productos":[{"id_producto":2,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":10,"precio":9}]}', 'envioDeProductosASucursal'),
(101, 101, 1, '2011-02-25 13:44:18', NULL, 3, '{"clave":209,"descripcion":"Envio de productos","conductor":"No especificado","productos":[{"id_producto":2,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":5,"precio":9}]}', 'envioDeProductosASucursal'),
(102, 102, 1, '2011-02-25 14:17:50', '2011-02-25 14:24:04', 6, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"2","nombre":"BRENDA ALFARO CARMONA"}', ''),
(103, 102, 1, '2011-02-26 01:49:41', NULL, 0, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"2","nombre":"BRENDA ALFARO CARMONA"}', ''),
(104, 102, 1, '2011-02-26 02:20:26', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"4","nombre":"ANA SOLACHE MALDONADO","cantidad":"3000"}', 'solicitudDeCambioLimiteDeCredito'),
(105, 102, 1, '2011-02-26 02:21:46', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"2","nombre":"BRENDA ALFARO CARMONA","cantidad":"100"}', 'solicitudDeCambioLimiteDeCredito'),
(106, 102, 1, '2011-02-26 02:24:02', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"1","id_producto":"1","cantidad":1,"cantidad_procesada":0}', 'solicitudDeDevolucion'),
(107, 102, 1, '2011-02-26 02:24:20', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"2","id_producto":"1","cantidad":1,"cantidad_procesada":0}', 'solicitudDeDevolucion'),
(108, 102, 1, '2011-02-26 02:24:46', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"4","id_producto":"1","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(109, 102, 1, '2011-02-26 02:25:01', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"6","id_producto":"1","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(110, 102, 1, '2011-02-26 02:25:15', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"9","id_producto":"1","cantidad":0,"cantidad_procesada":2}', 'solicitudDeDevolucion'),
(111, 102, 1, '2011-02-26 02:25:53', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"9","id_producto":"1","cantidad":1,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(112, 102, 1, '2011-02-26 02:35:06', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"21","id_producto":"2","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(113, 102, 1, '2011-02-26 03:00:46', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"46","id_producto":"2","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(114, 102, 1, '2011-02-26 03:08:38', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","id_venta":"102","id_producto":"1","cantidad":1,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(115, 102, 1, '2011-02-26 03:11:51', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","producto_descripcion":null,"id_venta":"104","id_producto":"1","cantidad":0,"cantidad_procesada":2}', 'solicitudDeDevolucion'),
(116, 102, 1, '2011-02-26 03:12:36', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","producto_descripcion":"papa segunda","id_venta":"104","id_producto":"2","cantidad":2,"cantidad_procesada":0}', 'solicitudDeDevolucion'),
(117, 102, 1, '2011-02-26 03:12:47', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","producto_descripcion":"papas primeras","id_venta":"104","id_producto":"1","cantidad":1,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(118, 102, 1, '2011-02-26 12:01:51', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","producto_descripcion":"papas primeras","id_venta":"55","id_producto":"1","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(119, 102, 1, '2011-02-26 12:04:13', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","producto_descripcion":"papas primeras","id_venta":"55","id_producto":"1","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(120, 102, 1, '2011-02-26 12:05:41', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","producto_descripcion":"papas primeras","id_venta":"78","id_producto":"1","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(121, 102, 1, '2011-02-26 12:06:29', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","producto_descripcion":"papas primeras","id_venta":"6","id_producto":"1","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(122, 102, 1, '2011-02-26 12:08:03', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","producto_descripcion":"papas primeras","id_venta":"68","id_producto":"1","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(123, 102, 1, '2011-02-26 12:09:43', NULL, 0, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","producto_descripcion":"papa segunda","id_venta":"46","id_producto":"2","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(124, 102, 1, '2011-02-26 17:33:26', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"4","nombre":"ANA SOLACHE MALDONADO","cantidad":"10000"}', 'solicitudDeCambioLimiteDeCredito'),
(125, 102, 1, '2011-02-28 00:37:08', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"4","nombre":"ANA SOLACHE MALDONADO","cantidad":"10000"}', 'solicitudDeCambioLimiteDeCredito'),
(126, 102, 1, '2011-02-28 00:49:49', '2011-02-28 01:48:12', 2, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"12","nombre":"ANDRES RIVERA CARRILLO 2"}', ''),
(127, 102, 1, '2011-02-28 00:50:01', '2011-02-28 01:46:21', 1, '{"clave":"203","descripcion":"Autorizaci\\u00f3n de devoluci\\u00f3n","producto_descripcion":"papas primeras","id_venta":"5","id_producto":"1","cantidad":0,"cantidad_procesada":1}', 'solicitudDeDevolucion'),
(128, 101, 1, '2011-02-28 01:00:36', NULL, 3, '{"clave":209,"descripcion":"Envio de productos","conductor":"Pedrito","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":1,"precio":9}]}', 'envioDeProductosASucursal'),
(129, 102, 1, '2011-02-28 01:09:03', NULL, 4, '{"clave":209,"descripcion":"Envio de productos","conductor":"adres fuentes","productos":[{"id_producto":3,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":10,"precio":34}]}', 'envioDeProductosASucursal'),
(130, 101, 1, '2011-03-01 18:55:35', NULL, 3, '{"clave":209,"descripcion":"Envio de productos","conductor":"joselino","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":2,"precio":9}]}', 'envioDeProductosASucursal'),
(131, 101, 1, '2011-03-01 18:56:20', NULL, 3, '{"clave":209,"descripcion":"Envio de productos","conductor":"No especificado","productos":[{"id_producto":2,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":5,"precio":12}]}', 'envioDeProductosASucursal'),
(132, 102, 1, '2011-03-06 00:20:48', NULL, 0, '{"clave":"202","descripcion":"Autorizaci\\u00f3n de limite de cr\\u00e9dito","id_cliente":"4","nombre":"ANA SOLACHE MALDONADO","cantidad":"2000"}', 'solicitudDeCambioLimiteDeCredito'),
(133, 102, 1, '2011-03-06 00:21:11', '2011-03-06 00:22:04', 1, '{"clave":"204","descripcion":"Autorizaci\\u00f3n de venta preferencial","id_cliente":"2","nombre":"BRENDA ALFARO CARMONA"}', ''),
(134, 102, 1, '2011-03-06 12:05:06', NULL, 4, '{"clave":209,"descripcion":"Envio de productos","conductor":"betillo","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":520,"precio":8.5}]}', 'envioDeProductosASucursal'),
(135, 101, 1, '2011-03-06 12:09:31', NULL, 3, '{"clave":209,"descripcion":"Envio de productos","conductor":"Alfredo","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":85.34,"precio":9.5}]}', 'envioDeProductosASucursal'),
(136, 101, 1, '2011-03-06 12:19:29', NULL, 3, '{"clave":209,"descripcion":"Envio de productos","conductor":"No especificado","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":705,"precio":8.6666666666667}]}', 'envioDeProductosASucursal'),
(137, 101, 1, '2011-03-06 12:28:27', NULL, 3, '{"clave":209,"descripcion":"Envio de productos","conductor":"No especificado","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":7,"precio":7}]}', 'envioDeProductosASucursal'),
(138, 102, 1, '2011-03-06 12:32:06', NULL, 4, '{"clave":209,"descripcion":"Envio de productos","conductor":"No especificado","productos":[{"id_producto":1,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":6,"precio":10.5},{"id_producto":2,"procesado":false,"cantidad_procesada":0,"precio_procesada":0,"cantidad":2,"precio":12}]}', 'envioDeProductosASucursal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del cliente',
  `rfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'rfc del cliente si es que tiene',
  `razon_social` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'razon social del cliente',
  `calle` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'calle del domicilio fiscal del cliente',
  `numero_exterior` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'numero exteriror del domicilio fiscal del cliente',
  `numero_interior` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'numero interior del domicilio fiscal del cliente',
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
  PRIMARY KEY (`id_cliente`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Volcar la base de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `rfc`, `razon_social`, `calle`, `numero_exterior`, `numero_interior`, `colonia`, `referencia`, `localidad`, `municipio`, `estado`, `pais`, `codigo_postal`, `telefono`, `e_mail`, `limite_credito`, `descuento`, `activo`, `id_usuario`, `id_sucursal`, `fecha_ingreso`) VALUES
(-2, 'Papas Supremas 2', 'Caja Comun', 'fsdfsdfdsfsadfasdfasdfasdfasdfasdfasdf', '', NULL, '', NULL, NULL, 'adfasdfasdfasdfasdfasdfasdfasdf', '', '', '', '6149974', '', 0, 0, 1, 101, 2, '2011-01-26 22:47:53'),
(-1, 'Papas Supremas 1', 'Caja Comun', 'EEDTTRRRRRRRRRRRRRRRRRR', '', NULL, '', NULL, NULL, 'CELAYA', '', '', '', '172637667', '', 0, 0, 1, 101, 1, '2011-01-09 01:38:26'),
(1, 'JICA870502XC2', 'JOSE ALFERDO JIMENEZ CARSO', 'monte alban', '123', '2', 'Rosalinda', 'Un lugar ', 'Celaya', 'Celaya', 'Guanajuato', 'Mexico', '38000', '4616112345', 'pepealfredo@caffeina.mx', 19000, 0, 1, 101, 1, '2011-01-09 02:11:30'),
(2, 'ALCB770612', 'BRENDA ALFARO CARMONA', 'MUTUALISMO #345, COL. CENTRO', '', NULL, '', NULL, NULL, 'CELAYA', '', '', '', 'a', '', 20, 2, 1, 101, 1, '2011-01-12 18:05:59'),
(3, 'MOJL570312', 'LUCIA MORALES JUNCO', 'FRANCISCO JUAREZ #466, CENTRO', '', NULL, '', NULL, NULL, 'CELAYA', '', '', '', '4616115623', '', 19000, 11, 1, 101, 1, '2011-01-14 14:32:37'),
(4, 'PRDC670822MODIFICADO', 'ANA SOLACHE MALDONADO MODIFICADA', 'Calle', 'NE', 'NI', 'Colonia', 'Referencia', 'Localidad', 'Municipo', 'Estado', 'Pais', '12345', 'TelefonoTelefono', 'EMAIL', 2000, 10, 1, 102, 1, '2011-01-30 21:50:50'),
(5, 'ESLN820412', 'NORMA SAMANTHA ESPARZA LAGUNA', 'RIO LERMA #304, COL. CENTRO', '', NULL, '', NULL, NULL, 'CELAYA', '', '', '', '4616162378', '', 1236, 20, 1, 102, 1, '2011-01-30 21:52:41'),
(6, 'Cliente de Prueba 3', 'ANDRES RIVERA CARRILLO', 'ANDRES RIVERA CARRILLO', '', NULL, '', NULL, NULL, 'CELAYA', '', '', '', 'Cliente de Prueba 3', '', 0, 0, 1, 102, 1, '2011-01-30 21:54:12'),
(7, 'JICA870502asdasd', 'JOSE ALFERDO JIMENEZ CARSO 2', 'monte alban #123 col rosalinda', '', NULL, '', NULL, NULL, 'celaya', '', '', '', '', '', 19000, 0, 1, 101, 1, '2011-01-09 02:11:30'),
(8, 'ALCB770612asdasd', 'BRENDA ALFARO CARMONA 2', 'MUTUALISMO #345, COL. CENTRO', '', NULL, '', NULL, NULL, 'CELAYA', '', '', '', 'a', '', 20, 2, 1, 101, 1, '2011-01-12 18:05:59'),
(9, 'MOJL570312asdasd', 'LUCIA MORALES JUNCO 2', 'FRANCISCO JUAREZ #466, CENTRO', '', NULL, '', NULL, NULL, 'CELAYA', '', '', '', '4616115623', '', 19000, 11, 1, 101, 1, '2011-01-14 14:32:37'),
(10, 'PRDC670822asdasd', 'ANA SOLACHE MALDONADO 2', 'GARAMBULLO #304 COL. DEL BOSQUE', '', NULL, '', NULL, NULL, 'CELAYA GTO', '', '', '', '46161167923', '', 2000, 11, 1, 102, 1, '2011-01-30 21:50:50'),
(11, 'ESLN820412asdasd', 'NORMA SAMANTHA ESPARZA LAGUNA 2', 'RIO LERMA #304, COL. CENTRO', '', NULL, '', NULL, NULL, 'CELAYA', '', '', '', '4616162378', '', 1236, 20, 1, 102, 1, '2011-01-30 21:52:41'),
(13, 'RFCC121212CD8', 'Una Razon Social', 'UNA CALLE EN ALGUN SITIO', 'NUMERO EXT', 'NUMERO INT', 'COLONIA', 'REFERENCIA', 'LOCALIDAD', 'MUNICIPIO', 'ESTADO', 'PAIS', '123145', '014611111111', 'ALGUN EMAIL', 5000, 5, 1, 102, 1, '2011-04-13 01:38:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_proveedor`
--

CREATE TABLE IF NOT EXISTS `compra_proveedor` (
  `id_compra_proveedor` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador de la compra',
  `peso_origen` float NOT NULL,
  `id_proveedor` int(11) NOT NULL COMMENT 'id del proveedor a quien se le hizo esta compra',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de cuando se recibio el embarque',
  `fecha_origen` date NOT NULL COMMENT 'fecha de cuando se envio este embarque',
  `folio` varchar(11) CHARACTER SET latin1 COLLATE latin1_general_cs DEFAULT NULL COMMENT 'folio de la remision',
  `numero_de_viaje` varchar(11) CHARACTER SET latin1 COLLATE latin1_general_cs DEFAULT NULL COMMENT 'numero de viaje',
  `peso_recibido` float NOT NULL COMMENT 'peso en kilogramos reportado en la remision',
  `arpillas` float NOT NULL COMMENT 'numero de arpillas en el camion',
  `peso_por_arpilla` float NOT NULL COMMENT 'peso promedio en kilogramos por arpilla',
  `productor` varchar(64) NOT NULL,
  `calidad` tinyint(3) unsigned DEFAULT NULL COMMENT 'Describe la calidad del producto asignando una calificacion en eel rango de 0-100',
  `merma_por_arpilla` float NOT NULL,
  `total_origen` float DEFAULT NULL COMMENT 'Es lo que vale el embarque segun el proveedor',
  PRIMARY KEY (`id_compra_proveedor`),
  KEY `id_proveedor` (`id_proveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Volcar la base de datos para la tabla `compra_proveedor`
--

INSERT INTO `compra_proveedor` (`id_compra_proveedor`, `peso_origen`, `id_proveedor`, `fecha`, `fecha_origen`, `folio`, `numero_de_viaje`, `peso_recibido`, `arpillas`, `peso_por_arpilla`, `productor`, `calidad`, `merma_por_arpilla`, `total_origen`) VALUES
(1, 0, 1, '2011-01-09 02:19:33', '0000-00-00', '1', '', 1600, 100, 16, 'jose jimenez e hijos', NULL, 0, 160),
(2, 0, 1, '2011-01-09 11:39:12', '0000-00-00', '756', '56', 17770, 340, 52.2647, 'NOLASCO', NULL, 0, 1567.94),
(4, 123, 1, '2011-01-13 22:14:00', '0000-00-00', 'asdf', '', 123, 123, 1, '', NULL, 0, NULL),
(5, 1231, 1, '2011-01-13 22:14:44', '0000-00-00', 'asdf', '', 1231, 123, 10.0081, '', NULL, 0, NULL),
(6, 5000, 1, '2011-01-14 12:31:19', '2010-12-31', 'a123', '', 4800, 95, 45.5263, 'nolasco', NULL, 5, 19576.3),
(8, 123, 1, '2011-01-16 10:38:40', '0000-00-00', '123', '', 123, 1, 122, '', NULL, 1, 1464),
(9, 123, 1, '2011-01-17 00:24:49', '2001-11-11', 'aa', '', 123, 3, 40, '', NULL, 1, 120),
(10, 20000, 1, '2011-02-08 21:50:36', '2011-04-14', '44', '123', 20000, 20, 1000, 'Nolasco', NULL, 0, 90000),
(11, 20000, 1, '2011-02-08 21:52:36', '2011-09-14', '213123', '', 20000, 63, 317.46, 'sdasdas', NULL, 0, 680000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_proveedor_flete`
--

CREATE TABLE IF NOT EXISTS `compra_proveedor_flete` (
  `id_compra_proveedor` int(11) NOT NULL,
  `chofer` varchar(64) NOT NULL,
  `marca_camion` varchar(64) DEFAULT NULL,
  `placas_camion` varchar(64) NOT NULL,
  `modelo_camion` varchar(64) DEFAULT NULL,
  `costo_flete` float NOT NULL,
  PRIMARY KEY (`id_compra_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `compra_proveedor_flete`
--

INSERT INTO `compra_proveedor_flete` (`id_compra_proveedor`, `chofer`, `marca_camion`, `placas_camion`, `modelo_camion`, `costo_flete`) VALUES
(1, 'alan gonzalez', '', '12345', '', 0),
(2, 'luis tapia', 'peterbrit negro', 'gl-42-735', '', 0),
(4, '', '', '', '', 123),
(5, '', '', '', '', 123),
(6, 'sr rodolfo', '', '123454', '', 5000),
(8, '', '', '', '', 12),
(9, '', '', '', '', 33),
(10, '', '', '', '', 4000),
(11, '', '', '', '', 4000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_proveedor_fragmentacion`
--

CREATE TABLE IF NOT EXISTS `compra_proveedor_fragmentacion` (
  `id_fragmentacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_compra_proveedor` int(11) NOT NULL COMMENT 'La compra a proveedor del producto',
  `id_producto` int(11) NOT NULL COMMENT 'El id del producto',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'la fecha de esta operacion',
  `descripcion` varchar(16) NOT NULL COMMENT 'la descripcion de lo que ha sucedido, vendido, surtido, basura... etc.',
  `cantidad` double NOT NULL DEFAULT '0' COMMENT 'cuanto fue consumido o agregado !!! en la escala que se tiene de este prod',
  `procesada` tinyint(1) NOT NULL COMMENT 'si estamos hablando de producto procesado, debera ser true',
  `precio` int(11) NOT NULL COMMENT 'a cuanto se vendio esta porcion del producto, si es el resultado de algun proceso sera 0 por ejemplo',
  `descripcion_ref_id` int(11) DEFAULT NULL COMMENT 'si se refiere a una venta, se puede poner el id de esa venta, si fue de surtir, el id de la compra, etc..',
  PRIMARY KEY (`id_fragmentacion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `compra_proveedor_fragmentacion`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_sucursal`
--

CREATE TABLE IF NOT EXISTS `compra_sucursal` (
  `id_compra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la compra',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de compra',
  `subtotal` float NOT NULL COMMENT 'subtotal de compra',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en que se compro',
  `id_usuario` int(11) NOT NULL COMMENT 'quien realizo la compra',
  `id_proveedor` int(11) DEFAULT NULL COMMENT 'En caso de ser una compra a un proveedor externo, contendra el id de ese proveedor, en caso de ser una compra a centro de distribucion este campo sera null.',
  `pagado` float NOT NULL DEFAULT '0' COMMENT 'total de pago abonado a esta compra',
  `liquidado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'indica si la cuenta ha sido liquidada o no',
  `total` float NOT NULL,
  PRIMARY KEY (`id_compra`),
  KEY `compras_sucursal` (`id_sucursal`),
  KEY `compras_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=58 ;

--
-- Volcar la base de datos para la tabla `compra_sucursal`
--

INSERT INTO `compra_sucursal` (`id_compra`, `fecha`, `subtotal`, `id_sucursal`, `id_usuario`, `id_proveedor`, `pagado`, `liquidado`, `total`) VALUES
(1, '2011-01-09 22:41:12', 49874.2, 1, 101, NULL, 0, 0, 49874.2),
(2, '2011-01-09 22:50:47', 700, 1, 101, NULL, 0, 0, 700),
(3, '2011-01-09 22:52:19', 900, 1, 101, NULL, 0, 0, 900),
(4, '2011-01-09 22:52:39', 800, 1, 101, NULL, 0, 0, 800),
(5, '2011-01-13 22:17:06', 999, 1, 101, NULL, 0, 0, 999),
(6, '2011-01-19 23:41:48', 0, 1, 101, NULL, 0, 0, 0),
(7, '2011-01-19 23:48:24', 0, 1, 101, NULL, 0, 0, 0),
(8, '2011-01-20 00:05:53', 0, 1, 101, NULL, 0, 0, 0),
(9, '2011-01-21 16:27:28', 2004, 1, 101, NULL, 0, 0, 2004),
(10, '2011-01-22 02:53:40', 92, 1, 101, NULL, 0, 0, 92),
(11, '2011-01-22 03:15:12', 36000, 1, 101, NULL, 0, 0, 36000),
(12, '2011-01-22 03:19:17', 45, 1, 101, NULL, 0, 0, 45),
(13, '2011-01-22 03:36:41', 36, 1, 101, NULL, 0, 0, 36),
(14, '2011-01-22 04:29:04', 119, 1, 101, NULL, 0, 0, 119),
(15, '2011-01-22 05:14:14', 60, 1, 101, NULL, 0, 0, 60),
(16, '2011-01-26 22:51:52', 1000, 2, 101, NULL, 0, 0, 1000),
(17, '2011-01-26 22:56:26', 9, 2, 101, NULL, 0, 0, 9),
(18, '2011-01-26 23:02:32', 9, 2, 101, NULL, 0, 0, 9),
(19, '2011-01-27 23:46:47', 10, 1, 101, NULL, 0, 0, 10),
(20, '2011-01-27 23:57:42', 0, 1, 101, NULL, 0, 0, 0),
(21, '2011-01-27 23:58:01', 0, 1, 101, NULL, 0, 0, 0),
(22, '2011-01-27 23:58:21', 0, 1, 101, NULL, 0, 0, 0),
(23, '2011-01-28 01:06:28', 18, 1, 101, NULL, 0, 0, 18),
(24, '2011-01-30 22:55:08', 100, 1, 101, NULL, 0, 0, 100),
(25, '2011-01-30 23:00:15', 260, 1, 101, NULL, 0, 0, 260),
(26, '2011-02-08 21:53:57', 206800, 1, 101, NULL, 0, 0, 206800),
(27, '2011-02-08 22:27:05', 4000, 1, 101, NULL, 0, 0, 4000),
(28, '2011-02-09 14:51:32', 196, 1, 101, NULL, 0, 0, 196),
(29, '2011-02-09 14:53:06', 180, 1, 101, NULL, 0, 0, 180),
(30, '2011-02-12 00:50:09', 4200, 1, 101, 0, 0, 0, 4200),
(31, '2011-02-12 02:07:17', 1100, 1, 101, 0, 0, 0, 1100),
(32, '2011-02-12 02:12:15', 900, 1, 101, 0, 0, 0, 900),
(33, '2011-02-12 02:15:14', 900, 1, 101, 0, 0, 0, 900),
(34, '2011-02-12 02:26:35', 8, 1, 101, 0, 0, 0, 8),
(35, '2011-02-12 04:22:05', 4.5, 1, 101, 0, 0, 0, 4.5),
(36, '2011-02-12 04:41:59', 8, 1, 101, 0, 0, 0, 8),
(37, '2011-02-12 04:44:38', 10.5, 1, 101, 0, 0, 0, 10.5),
(38, '2011-02-14 22:53:02', 12, 1, 101, 0, 0, 0, 12),
(39, '2011-02-14 22:54:07', 9, 1, 101, 0, 0, 0, 9),
(40, '2011-02-17 21:35:15', 9, 1, 101, 0, 0, 0, 9),
(41, '2011-02-23 14:34:54', 5.2, 1, 101, 0, 0, 0, 5.2),
(42, '2011-02-23 14:41:21', 9, 1, 101, 0, 0, 0, 9),
(43, '2011-02-23 14:49:09', 4, 1, 101, 0, 0, 0, 4),
(44, '2011-02-23 14:55:19', 8, 1, 101, 0, 0, 0, 8),
(45, '2011-02-23 18:35:26', 5, 1, 101, 0, 0, 0, 5),
(46, '2011-02-24 02:21:43', 12, 2, 101, 0, 0, 0, 12),
(47, '2011-02-25 13:33:18', 9, 1, 101, 0, 0, 0, 9),
(48, '2011-02-25 13:44:18', 9, 1, 101, 0, 0, 0, 9),
(49, '2011-02-28 01:00:36', 9, 1, 101, 0, 0, 0, 9),
(50, '2011-02-28 01:09:03', 34, 1, 101, 0, 0, 0, 34),
(51, '2011-03-01 18:55:35', 18, 1, 101, 0, 0, 0, 18),
(52, '2011-03-01 18:56:20', 60, 1, 101, 0, 0, 0, 60),
(53, '2011-03-06 12:05:06', 4420, 1, 101, 0, 0, 0, 4420),
(54, '2011-03-06 12:09:31', 810.73, 1, 101, 0, 0, 0, 810.73),
(55, '2011-03-06 12:19:29', 6110, 1, 101, 0, 0, 0, 6110),
(56, '2011-03-06 12:28:27', 49, 1, 101, 0, 0, 0, 49),
(57, '2011-03-06 12:32:06', 87, 1, 101, 0, 0, 0, 87);

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
  PRIMARY KEY (`id_corte`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `corte`
--


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
  PRIMARY KEY (`id_compra_proveedor`,`id_producto`),
  KEY `id_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `detalle_compra_proveedor`
--

INSERT INTO `detalle_compra_proveedor` (`id_compra_proveedor`, `id_producto`, `variedad`, `arpillas`, `kg`, `precio_por_kg`) VALUES
(1, 1, 'fianas', 100, 1600, 10),
(2, 2, 'FIANAS', 150, 7840, 9),
(2, 3, 'FIANAS', 120, 6272, 8),
(2, 4, 'FIANAS', 40, 2091, 7),
(2, 5, 'FIANAS', 30, 1568, 6),
(4, 1, 'fianas', 123, 123, 12),
(5, 1, 'fianas', 123, 1231, 12),
(6, 1, 'fianas', 50, 239750, 5),
(6, 2, 'fianas', 45, 215775, 4),
(8, 1, '', 1, 122, 12),
(9, 1, 'f', 3, 366, 1),
(10, 1, 'asdasdads', 20, 20000, 5),
(11, 3, '', 22, 6984, 8),
(11, 4, '', 19, 6032, 78),
(11, 5, '', 10, 3175, 34),
(11, 6, '', 12, 3810, 12);

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
  PRIMARY KEY (`id_compra`,`id_producto`),
  KEY `id_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `detalle_compra_sucursal`
--

INSERT INTO `detalle_compra_sucursal` (`id_compra`, `id_producto`, `cantidad`, `precio`, `descuento`, `procesadas`) VALUES
(1, 2, 2613, 6.75, 150, 0),
(2, 4, 100, 7, 0, 0),
(3, 2, 100, 9, 0, 0),
(4, 3, 100, 8, 0, 1),
(5, 2, 111, 9, 0, 0),
(6, 1, 0, 12, 0, 0),
(7, 1, 0, 12, 0, 0),
(8, 3, 0, 8, 0, 0),
(9, 2, 132, 4, 0, 0),
(10, 2, 23, 4, 0, 0),
(11, 2, 4000, 9, 0, 0),
(12, 2, 5, 9, 0, 0),
(13, 2, 5, 9, 1, 0),
(14, 1, 15, 1, 1, 0),
(15, 1, 5, 12, 0, 0),
(16, 2, 100, 10, 0, 1),
(17, 2, 1, 9, 0, 1),
(18, 2, 1, 9, 0, 0),
(19, 1, 10, 1, 0, 1),
(20, 1, 0, 5, 0, 1),
(21, 1, 0, 12, 0, 1),
(22, 1, 0, 12, 0, 1),
(23, 2, 2, 9, 0, 1),
(24, 1, 100, 1, 0, 0),
(25, 1, 40, 6.5, 0, 0),
(26, 3, 100, 8, 0, 0),
(26, 4, 1000, 78, 0, 0),
(26, 5, 1000, 34, 0, 0),
(26, 6, 1000, 12, 0, 0),
(27, 3, 500, 8, 0, 1),
(28, 3, 14, 14, 0, 0),
(29, 6, 15, 12, 0, 0),
(34, 1, 200, 8, 0, 0),
(35, 1, 200, 4.5, 0, 0),
(36, 1, 517, 8, 0, 0),
(37, 2, 200, 10.5, 0, 0),
(38, 1, 100, 12, 0, 0),
(39, 2, 500, 9, 0, 0),
(40, 1, 200, 9, 0, 0),
(41, 1, 450, 5.2, 0, 0),
(42, 3, 3.5, 9, 0, 0),
(43, 2, 1000, 4, 0, 0),
(44, 3, 100, 8, 0, 0),
(45, 6, 9000, 5, 0, 0),
(46, 6, 5, 12, 0, 0),
(47, 2, 10, 9, 0, 0),
(48, 2, 5, 9, 0, 0),
(49, 1, 1, 9, 0, 0),
(50, 3, 10, 34, 0, 0),
(51, 1, 2, 9, 0, 0),
(52, 2, 5, 12, 0, 0),
(53, 1, 520, 8.5, 0, 0),
(54, 1, 85.34, 9.5, 0, 0),
(55, 1, 705, 8.66667, 0, 0),
(56, 1, 7, 7, 0, 0),
(57, 1, 6, 10.5, 0, 0),
(57, 2, 2, 12, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_inventario`
--

CREATE TABLE IF NOT EXISTS `detalle_inventario` (
  `id_producto` int(11) NOT NULL COMMENT 'id del producto al que se refiere',
  `id_sucursal` int(11) NOT NULL COMMENT 'id de la sucursal',
  `precio_venta` float NOT NULL COMMENT 'precio al que se vendera al publico',
  `existencias` float NOT NULL DEFAULT '0' COMMENT 'cantidad de producto que hay actualmente en almacen de esta sucursal',
  `existencias_procesadas` float NOT NULL,
  PRIMARY KEY (`id_producto`,`id_sucursal`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `detalle_inventario`
--

INSERT INTO `detalle_inventario` (`id_producto`, `id_sucursal`, `precio_venta`, `existencias`, `existencias_procesadas`) VALUES
(1, 1, 11, 2717, 268),
(1, 2, 8, 165, 100),
(2, 1, 9, 4958, 2141),
(2, 2, 9, 3157, 1300),
(3, 1, 8, 389, 296),
(3, 2, 7, 197, 100),
(4, 1, 7, 1014, 910),
(5, 1, 6, 400, 510),
(6, 1, 5, 19000.3, 2.01746);

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

--
-- Volcar la base de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id_venta`, `id_producto`, `cantidad`, `cantidad_procesada`, `precio`, `precio_procesada`, `descuento`) VALUES
(1, 1, 1, 0, 12, 0, 0),
(2, 1, 1, 0, 1, 0, 0),
(3, 1, 0, 1, 0, 11, 0),
(4, 1, 0, 1, 0, 11, 0),
(5, 1, 0, -1, 0, 11, 0),
(6, 1, 0, 1, 0, 11, 0),
(7, 2, 0, 1, 0, 9, 0),
(8, 1, 0, 10, 0, 11, 0),
(9, 1, 4, 5, 8.5, 11, 0),
(10, 1, 7, 0, 8.5, 0, 0),
(10, 2, 0, 3, 0, 9, 0),
(11, 1, 0, 1, 0, 11, 0),
(12, 1, 0, 1, 0, 11, 0),
(13, 1, 0, 1, 0, 11, 0),
(14, 1, 0, 1, 0, 11, 0),
(15, 2, 0, 1, 0, 9, 0),
(16, 1, 0, 1, 0, 11, 0),
(17, 1, 0, 1, 0, 11, 0),
(18, 2, 0, 1, 0, 9, 0),
(19, 1, 0, 1, 0, 11, 0),
(20, 1, 0, 1, 0, 11, 0),
(21, 2, 0, 1, 0, 9, 0),
(22, 1, 0, 1, 0, 11, 0),
(23, 1, 0, 1, 0, 11, 0),
(24, 2, 0, 1, 0, 9, 0),
(25, 1, 0, 1, 0, 11, 0),
(26, 1, 0, 1, 0, 11, 0),
(27, 2, 0, 1, 0, 9, 0),
(28, 1, 0, 1, 0, 11, 0),
(29, 2, 0, 1, 0, 9, 0),
(30, 1, 0, 1, 0, 11, 0),
(31, 2, 0, 1, 0, 9, 0),
(32, 1, 0, 1, 0, 11, 0),
(33, 1, 1, 0, 8.5, 0, 0),
(33, 2, 0, 2, 0, 9, 0),
(34, 1, 3, 6, 8.5, 11, 0),
(34, 2, 0, 5, 0, 9, 0),
(35, 1, 5, 10, 8.5, 11, 0),
(35, 2, 5, 0, 5.5, 0, 0),
(36, 1, 4, 3, 8.5, 11, 0),
(36, 2, 3, 0, 9, 0, 0),
(37, 1, 0, 1, 0, 11, 0),
(38, 2, 0, 1, 0, 9, 0),
(39, 2, 0, 1, 0, 9, 0),
(40, 1, 0, 1, 0, 11, 0),
(41, 1, 0, 1, 0, 11, 0),
(42, 1, 0, 1, 0, 11, 0),
(43, 1, 0, 1, 0, 11, 0),
(44, 1, 0, 1, 0, 11, 0),
(45, 1, 0, 1, 0, 11, 0),
(46, 1, 0, 1, 0, 11, 0),
(46, 2, 0, 1, 0, 9, 0),
(47, 1, 0, 1, 0, 11, 0),
(47, 2, 0, 1, 0, 9, 0),
(48, 1, 0, 1, 0, 11, 0),
(48, 2, 0, 1, 0, 9, 0),
(49, 1, 0, 1, 0, 11, 0),
(50, 1, 0, 1, 0, 11, 0),
(51, 1, 0, 1, 0, 11, 0),
(52, 1, 0, 1, 0, 11, 0),
(53, 1, 0, 1, 0, 11, 0),
(54, 1, 0, 1, 0, 11, 0),
(55, 1, 0, 1, 0, 11, 0),
(56, 1, 0, 1, 0, 11, 0),
(57, 2, 0, 1, 0, 9, 0),
(58, 1, 0, 1, 0, 11, 0),
(59, 1, 0, 1, 0, 11, 0),
(60, 1, 4, 0, 8.5, 0, 0),
(60, 2, 2, 0, 6, 0, 0),
(61, 1, 0, 1, 0, 11, 0),
(62, 1, 0, 1, 0, 11, 0),
(63, 1, 0, 1, 0, 11, 0),
(64, 1, 0, 1, 0, 11, 0),
(65, 1, 0, 1, 0, 11, 0),
(66, 1, 0, 1, 0, 11, 0),
(67, 2, 0, 1, 0, 9, 0),
(68, 1, 0, 1, 0, 11, 0),
(69, 1, 0, 1, 0, 11, 0),
(70, 1, 0, 1, 0, 11, 0),
(71, 1, 0, 1, 0, 11, 0),
(72, 1, 0, 1, 0, 11, 0),
(73, 1, 0, 1, 0, 11, 0),
(74, 1, 0, 1, 0, 11, 0),
(75, 1, 0, 1, 0, 11, 0),
(76, 1, 0, 1, 0, 11, 0),
(77, 1, 0, 1, 0, 11, 0),
(78, 1, 0, 1, 0, 11, 0),
(79, 1, 0, 1, 0, 11, 0),
(80, 1, 0, 12, 0, 11, 0),
(80, 2, 0, 4, 0, 9, 0),
(81, 1, 4, 1, 8.5, 11, 0),
(82, 1, 0, 1, 0, 11, 0),
(83, 1, 0, 1, 0, 11, 0),
(84, 1, 0, 1, 0, 11, 0),
(85, 1, 0, 1, 0, 11, 0),
(86, 1, 0, 1, 0, 11, 0),
(87, 1, 0, 1, 0, 11, 0),
(88, 1, 0, 1, 0, 11, 0),
(89, 1, 0, 1, 0, 11, 0),
(90, 1, 0, 1, 0, 11, 0),
(91, 1, 0, 1, 0, 11, 0),
(92, 1, 0, 1, 0, 11, 0),
(93, 1, 0, 1, 0, 11, 0),
(94, 1, 0, 1, 0, 11, 0),
(94, 2, 0, 1, 0, 9, 0),
(95, 2, 0, 1, 0, 9, 0),
(96, 1, 0, 1, 0, 11, 0),
(97, 1, 0, 1, 0, 11, 0),
(98, 1, 0, 1, 0, 11, 0),
(99, 1, 1, 1, 2, 2, 0),
(99, 2, 1, 0, 2, 0, 0),
(100, 1, 1, 1, 3, 3, 0),
(100, 2, 1, 0, 3, 0, 0),
(101, 1, 1, 1, 6, 6, 0),
(101, 2, 1, 0, 6, 0, 0),
(102, 1, 3, 3, 8.5, 11, 0),
(102, 2, 3, 0, 10, 0, 0),
(102, 6, 3, 0, 9, 0, 0),
(103, 1, 0, 10, 0, 11, 0),
(103, 3, 0, 5, 0, 8, 0),
(104, 1, 7, 7, 8.5, 11, 0),
(104, 2, 7, 0, 45, 0, 0),
(104, 3, 7, 0, 96, 0, 0),
(105, 1, 3, 3, 15, 15, 0),
(105, 2, 3, 0, 15, 0, 0),
(106, 1, 3, 3, 15, 15, 0),
(106, 2, 3, 0, 15, 0, 0),
(107, 1, 4, 4, 8.5, 11, 0),
(107, 2, 4, 0, 96, 0, 0),
(108, 1, 4, 4, 8.5, 11, 0),
(108, 2, 4, 0, 96, 0, 0),
(109, 1, 4, 4, 8.5, 11, 0),
(109, 2, 4, 0, 8, 0, 0),
(110, 1, 0, 3, 0, 11, 0),
(110, 2, 3, 0, 10, 0, 0),
(111, 1, 0, 3, 0, 11, 0),
(111, 2, 3, 0, 10, 0, 0),
(112, 1, 5, 5, 8.5, 11, 0),
(112, 2, 5, 5, 9.5, 9, 0),
(113, 1, 2, 2, 8.5, 11, 0),
(113, 2, 2, 2, 8, 9, 0),
(113, 3, 2, 0, 7, 0, 0),
(113, 4, 2, 0, 6, 0, 0),
(114, 2, 0, 1, 0, 1, 0),
(115, 1, 0, 1, 0, 4, 0),
(116, 1, 0, 1, 0, 11, 0),
(117, 1, 0, 1, 0, 6, 0),
(118, 1, 1, 1, 9.5, 10.5, 0),
(118, 2, 1, 1, 7.5, 8, 0),
(123, 1, 1, 1, 9.5, 10.5, 0),
(123, 2, 1, 1, 7.5, 8, 0),
(123, 3, 0, 1, 0, 7, 0),
(124, 1, 0, 4, 0, 10.5, 0),
(124, 2, 0, 4, 0, 8, 0),
(124, 3, 0, 2, 0, 7, 0),
(125, 1, 3, 3, 9.5, 10.5, 0),
(125, 2, 3, 3, 7.5, 8, 0),
(125, 3, 0, 3, 0, 7, 0),
(126, 3, 0, 1, 0, 7, 0),
(127, 1, 1, 1, 9.5, 10.5, 0),
(127, 2, 1, 1, 7.5, 8, 0),
(127, 3, 1, 1, 6.5, 7, 0),
(128, 1, 0, 1, 0, 10.5, 0),
(128, 2, 0, 1, 0, 8, 0),
(128, 3, 0, 1, 0, 7, 0),
(129, 1, 1, 1, 9.5, 10.5, 0),
(129, 2, 1, 1, 7.5, 8, 0),
(129, 3, 0, 1, 0, 7, 0),
(130, 1, 65, 12, 9.5, 10.5, 0),
(130, 2, 57, 90, 7.5, 8, 0),
(130, 3, 97, 68, 6.5, 7, 0),
(131, 1, 100, 100, 9.5, 10.5, 0),
(131, 2, 100, 100, 7.5, 8, 0),
(131, 3, 100, 100, 6.5, 7, 0),
(132, 1, 0, 1, 0, 11, 0),
(133, 1, 0, 1, 0, 11, 0),
(134, 1, 0, 1, 0, 11, 0),
(135, 1, 0, 1, 0, 11, 0),
(136, 1, 0, 1, 0, 11, 0),
(137, 1, 0, 1, 0, 11, 0),
(137, 2, 0, 1, 0, 9, 0),
(137, 3, 0, 1, 0, 8, 0),
(138, 1, 0, 1, 0, 11, 0),
(139, 1, 0, 1, 0, 11, 0),
(140, 1, 0, 1, 0, 11, 0),
(141, 1, 0, 1, 0, 11, 0),
(142, 1, 0, 1, 0, 11, 0),
(143, 1, 0, 1, 0, 11, 0),
(144, 1, 0, 2, 0, 11, 0),
(144, 2, 0, 3, 0, 9, 0),
(144, 3, 0, 4, 0, 8, 0),
(145, 1, 0, 4, 0, 11, 0),
(145, 2, 0, 3, 0, 9, 0),
(145, 3, 0, 5, 0, 8, 0),
(146, 1, 0, 3, 0, 11, 0),
(146, 2, 0, 5, 0, 9, 0),
(146, 3, 0, 5, 0, 8, 0),
(147, 1, 0, 1, 0, 11, 0),
(148, 1, 0, 1, 0, 11, 0),
(148, 2, 0, 1, 0, 9, 0),
(149, 1, 0, 1, 0, 11, 0),
(150, 1, 0, 1, 0, 11, 0),
(151, 1, 0, 1, 0, 11, 0),
(152, 1, 0, 1, 0, 11, 0),
(153, 1, 0, 1, 0, 11, 0),
(154, 1, 0, 1, 0, 11, 0),
(155, 1, 0, 1, 0, 11, 0),
(156, 1, 0, 1, 0, 11, 0),
(157, 1, 0, 1, 0, 11, 0),
(158, 1, 0, 1, 0, 11, 0),
(159, 1, 101, 0, 10, 0, 0),
(160, 1, 0, 1, 0, 11, 0),
(161, 1, 0, 1, 0, 11, 0),
(162, 1, 0, 1, 0, 11, 0),
(163, 1, 0, 1, 0, 11, 0),
(164, 1, 0, 1, 0, 11, 0),
(165, 1, 0, 1, 0, 11, 0),
(166, 1, 0, 1, 0, 11, 0),
(167, 1, 0, 1, 0, 11, 0),
(168, 1, 0, 1, 0, 11, 0),
(169, 1, 3, 0, 10, 0, 0),
(169, 2, 4, 0, 8.5, 0, 0),
(169, 3, 2, 0, 7.5, 0, 0),
(169, 4, 8, 0, 10, 0, 0),
(169, 5, 5, 0, 8, 0, 0),
(170, 1, 3, 0, 10, 0, 0),
(170, 2, 3, 0, 8.5, 0, 0),
(170, 3, 4, 0, 7.5, 0, 0),
(171, 1, 3, 0, 10, 0, 0),
(171, 2, 3, 0, 8.5, 0, 0),
(171, 3, 4, 0, 7.5, 0, 0),
(172, 1, 3, 0, 10, 0, 0),
(172, 2, 3, 0, 8.5, 0, 0),
(172, 3, 4, 0, 7.5, 0, 0),
(173, 1, 3, 0, 10, 0, 0),
(173, 2, 3, 0, 8.5, 0, 0),
(173, 3, 4, 0, 7.5, 0, 0),
(174, 1, 3, 0, 10, 0, 0),
(174, 2, 3, 0, 8.5, 0, 0),
(174, 3, 4, 0, 7.5, 0, 0),
(175, 1, 3, 0, 10, 0, 0),
(175, 2, 3, 0, 8.5, 0, 0),
(175, 3, 4, 0, 7.5, 0, 0),
(176, 2, 1, 0, 8.5, 0, 0),
(177, 2, 1, 0, 8.5, 0, 0),
(178, 1, 0, 1, 0, 11, 0),
(178, 2, 0, 1, 0, 9, 0),
(179, 1, 79, 0, 10, 0, 0),
(180, 1, 0, 1, 0, 11, 0),
(181, 1, 0, 1, 0, 11, 0),
(182, 1, 0, 1, 0, 11, 0),
(182, 2, 0, 1, 0, 9, 0),
(182, 3, 0, 1, 0, 8, 0),
(183, 1, 0, 1, 0, 11, 0),
(183, 2, 0, 1, 0, 9, 0),
(183, 3, 0, 1, 0, 8, 0),
(184, 1, 0, 1, 0, 10.5, 0),
(184, 2, 0, 1, 0, 8, 0),
(184, 3, 0, 1, 0, 7, 0),
(185, 1, 0, 1, 0, 11, 0),
(185, 2, 0, 1, 0, 9, 0),
(185, 3, 0, 1, 0, 8, 0),
(186, 1, 0, 1, 0, 10.5, 0),
(186, 2, 0, 1, 0, 8, 0),
(186, 3, 0, 1, 0, 7, 0),
(187, 1, 0, 1, 0, 10.5, 0),
(187, 2, 0, 1, 0, 8, 0),
(187, 3, 0, 1, 0, 7, 0),
(188, 1, 0, 1, 0, 10.5, 0),
(188, 2, 0, 1, 0, 8, 0),
(188, 3, 0, 1, 0, 7, 0),
(189, 1, 0, 1, 0, 10.5, 0),
(189, 2, 0, 1, 0, 8, 0),
(189, 3, 0, 1, 0, 7, 0),
(190, 1, 0, 3, 0, 10.5, 0),
(190, 2, 0, 2, 0, 8, 0),
(190, 3, 0, 3, 0, 7, 0),
(191, 1, 0, 1, 0, 11, 0),
(191, 2, 0, 1, 0, 9, 0),
(191, 3, 0, 1, 0, 8, 0),
(192, 1, 0, 1, 0, 11, 0),
(192, 2, 0, 1, 0, 9, 0),
(193, 1, 0, 1, 0, 11, 0),
(194, 1, 0, 1, 0, 10.5, 0),
(194, 2, 0, 1, 0, 8, 0),
(195, 1, 0, 1, 0, 10.5, 0),
(195, 2, 0, 1, 0, 8, 0),
(196, 3, 0, 1, 0, 8, 0),
(196, 6, 1, 0, 5, 0, 0),
(197, 1, 0, 100, 0, 11, 0),
(198, 3, 0, 50, 0, 8, 0),
(199, 2, 0, 1, 0, 9, 0),
(200, 2, 0, 1, 0, 9, 0),
(201, 3, 0, 1, 0, 8, 0),
(202, 2, 0, 1, 0, 9, 0),
(203, 1, 0, 1, 0, 11, 0),
(204, 1, 0, 1, 0, 11, 0),
(205, 5, 38.3, 0, 0.055, 0, 0),
(206, 1, 0, 1, 0, 25, 0),
(206, 2, 0, 1, 0, 50, 0),
(207, 1, 0, 5, 0, 50, 0),
(207, 2, 0, 10, 0, 100, 0),
(208, 1, 0, 50, 0, 50, 0),
(208, 2, 0, 100, 0, 100, 0),
(209, 1, 0, 50, 0, 50, 0),
(209, 2, 0, 100, 0, 100, 0),
(210, 1, 0, 1, 0, 666, 0),
(210, 2, 1, 0, 8.5, 0, 0),
(210, 3, 0, 1, 0, 8, 0),
(210, 4, 1, 0, 777, 0, 0),
(211, 1, 0, 1, 0, 100, 0),
(211, 2, 1, 0, 200, 0, 0),
(212, 1, 0, 3, 0, 11, 0),
(212, 2, 0, 1, 0, 9, 0),
(212, 3, 0, 5, 0, 50, 0),
(212, 4, 0, 10, 0, 100, 0),
(213, 1, 0, 1, 0, 11, 0),
(214, 1, 0, 1, 0, 11, 0),
(215, 1, 4, 3, 10, 11, 0),
(215, 2, 2, 4, 8.5, 9, 0),
(216, 1, 0, 1, 0, 11, 0),
(217, 1, 0, 1, 0, 11, NULL),
(217, 2, 1, 0, 8.5, 0, NULL),
(217, 3, 0, 1, 0, 8, NULL),
(218, 1, 0, 1, 0, 11, NULL),
(218, 2, 1, 0, 8.5, 0, NULL),
(218, 3, 0, 1, 0, 8, NULL),
(219, 1, 1, 0, 10, 0, NULL),
(219, 2, 1, 0, 8.5, 0, NULL),
(219, 3, 0, 1, 0, 8, NULL),
(219, 4, 1, 0, 5.5, 0, NULL),
(220, 1, 0, 1, 0, 11, NULL),
(221, 1, 3, 3, 10, 11, NULL),
(221, 2, 3, 3, 8.5, 9, NULL),
(222, 1, 0, 1, 0, 11, 0),
(222, 2, 2, 0, 8.5, 0, 0.2),
(222, 3, 0, 3, 0, 8, 0),
(222, 4, 4, 0, 5.5, 0, 0.4),
(223, 1, 0, 5, 0, 11, 0),
(223, 2, 10, 0, 100, 0, 1),
(223, 3, 0, 5, 0, 8, 0),
(224, 1, 0, 1, 0, 11, 0),
(224, 2, 10, 1, 10, 9, 1),
(224, 3, 0, 1, 0, 8, 0),
(225, 1, 0, 1, 0, 12, 0),
(225, 2, 0, 1, 0, 9, 0),
(226, 1, 0, 1, 0, 11, 0),
(227, 1, 0, 3, 0, 11, 0),
(227, 2, 12, 0, 8.5, 0, 2),
(227, 3, 0, 1, 0, 8, 0),
(227, 6, 6, 0, 5, 0, 0),
(228, 1, 2, 0, 10, 0, 0),
(229, 1, 2, 0, 10, 0, 0),
(230, 1, 0, 2, 0, 11, 0),
(231, 1, 6, 0, 10, 0, 0),
(232, 1, 0, 1, 0, 100, 0),
(232, 2, 0, 1, 0, 200, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Volcar la base de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id_equipo`, `token`, `full_ua`, `descripcion`, `locked`) VALUES
(5, 'Macbook esfr', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; es-es) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4', 'Macbook esfr', 0),
(6, NULL, 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.237 Safari/534.10', 'Macbook ernesto', 0),
(7, 'ManesLap', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.204 Safari/534.16', 'ManesLap', 0),
(8, 'MANESLAPWINDOWS', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/9.0.597.107 Safari/534.13', 'MANESLAPWINDOWS', 0),
(9, NULL, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; es-ES) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4', 'acer', 0),
(10, 'ManesLapChrome', 'Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/9.0.597.94 Safari/534.13', 'ManesLapChrome', 0),
(11, NULL, 'Mozilla/5.0 (X11; U; Linux i686; es-MX; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13 WebSlideShow/1.4.0', 'hp mozilla', 0),
(12, NULL, 'Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Ubuntu/10.04 Chromium/9.0.597.94 Chrome/9.0.597.94 Safari/534.13', 'ManesLap SUC 2', 0),
(13, NULL, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/9.0.597.98 Safari/534.13', 'chromewin', 0),
(14, NULL, 'Mozilla/5.0 (Windows NT 6.1; rv:2.0) Gecko/20100101 Firefox/4.0', 'Mozillahp', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo_sucursal`
--

CREATE TABLE IF NOT EXISTS `equipo_sucursal` (
  `id_equipo` int(6) NOT NULL COMMENT 'identificador del equipo ',
  `id_sucursal` int(6) NOT NULL COMMENT 'identifica una sucursal',
  PRIMARY KEY (`id_equipo`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `equipo_sucursal`
--

INSERT INTO `equipo_sucursal` (`id_equipo`, `id_sucursal`) VALUES
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(13, 1),
(14, 1),
(12, 2);

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
  `id_folio` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'folio que tiene la factura',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la factura',
  `id_usuario` int(10) NOT NULL COMMENT 'Id del usuario que hiso al ultima modificacion a la factura',
  `activa` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha cuando se genero esta factura',
  `certificado` text COLLATE utf8_unicode_ci COMMENT 'sello digital, emitido por el pac',
  `aprovacion` text COLLATE utf8_unicode_ci COMMENT 'Numero de aprovacion de la factura electronica',
  `anio_aprovacion` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cadena_original` text COLLATE utf8_unicode_ci,
  `sello_digital` text COLLATE utf8_unicode_ci,
  `sello_digital_proveedor` text COLLATE utf8_unicode_ci,
  `pac` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id_folio`),
  KEY `factura_venta_venta` (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Volcar la base de datos para la tabla `factura_venta`
--

INSERT INTO `factura_venta` (`id_folio`, `id_venta`, `id_usuario`, `activa`, `fecha`, `certificado`, `aprovacion`, `anio_aprovacion`, `cadena_original`, `sello_digital`, `sello_digital_proveedor`, `pac`) VALUES
(1, 68, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(2, 70, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(3, 71, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(4, 72, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(5, 73, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(6, 74, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(7, 75, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(8, 76, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(9, 77, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(10, 78, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(11, 79, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(12, 81, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(13, 82, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(14, 83, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(15, 84, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', ''),
(16, 193, 0, 1, '0000-00-00 00:00:00', '', '', '', '', '', '', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id_gasto`, `folio`, `concepto`, `monto`, `fecha`, `fecha_ingreso`, `id_sucursal`, `id_usuario`, `nota`) VALUES
(1, '', 'otros', 5000, '2011-01-30 18:00:42', '2011-08-05 05:00:00', 1, 102, 'DESAYUNO'),
(2, 'ASD1337', 'luz', 1200, '2011-01-30 18:01:57', '2011-02-02 06:00:00', 1, 102, 'LA LUZ'),
(3, '-1', 'PROLE', 20, '2011-01-31 00:40:58', '2011-01-31 00:40:58', 1, 102, 'JUAN VINO'),
(4, '44R', 'telefono', 500, '2011-02-14 16:51:05', '2011-04-05 05:00:00', 1, 102, 'ssdfsdfsdfsdfsdfsdfsdf'),
(5, 'T6', 'nextel', 1000, '2011-03-06 12:35:50', '2011-03-07 06:00:00', 1, 102, '');

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
(0, 'Ingeniero', 'Ingeniero'),
(1, 'Administrador', 'Administrador'),
(2, 'gerente', 'Gerente'),
(3, 'Cajero', 'Cajero de la sucursal'),
(4, 'Chofer', 'Chofer de la sucursal');

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
(1, 101),
(2, 102),
(2, 103),
(2, 104),
(2, 106),
(3, 105);

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

--
-- Volcar la base de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id_ingreso`, `concepto`, `monto`, `fecha`, `fecha_ingreso`, `id_sucursal`, `id_usuario`, `nota`) VALUES
(1, 'un pago de algo', 522, '2011-03-06 12:35:16', '2010-01-02 06:00:00', 1, 102, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del producto',
  `descripcion` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'descripcion del producto',
  `escala` enum('kilogramo','pieza','litro','unidad') COLLATE utf8_unicode_ci NOT NULL,
  `tratamiento` enum('limpia') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tipo de tratatiento si es que existe para este producto.',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Volcar la base de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_producto`, `descripcion`, `escala`, `tratamiento`) VALUES
(1, 'papas primeras', 'kilogramo', 'limpia'),
(2, 'papa segunda', 'kilogramo', 'limpia'),
(3, 'papa tercera', 'pieza', 'limpia'),
(4, 'papa cuarta', 'kilogramo', 'limpia'),
(5, 'papas quinta', 'kilogramo', 'limpia'),
(6, 'papa para dorar', 'pieza', ''),
(7, 'esta es una desc', 'kilogramo', ''),
(8, 'asdf', 'kilogramo', ''),
(9, 'coca cola 300ml', 'unidad', 'limpia'),
(10, 'asdfasdf', 'kilogramo', 'limpia'),
(11, 'asdfasdf', 'kilogramo', ''),
(12, 'asdfsadfsadf', 'kilogramo', ''),
(13, 'papas terceras', 'kilogramo', 'limpia'),
(14, 'Papas doradas', 'pieza', ''),
(15, 'papas cuartas', 'kilogramo', 'limpia');

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
  PRIMARY KEY (`id_producto`,`id_compra_proveedor`),
  KEY `id_compra_proveedor` (`id_compra_proveedor`),
  KEY `sitio_descarga` (`sitio_descarga`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `inventario_maestro`
--

INSERT INTO `inventario_maestro` (`id_producto`, `id_compra_proveedor`, `existencias`, `existencias_procesadas`, `sitio_descarga`) VALUES
(1, 1, 0, 0, 1),
(1, 4, 16, 0, 1),
(1, 5, 781, 120, 1),
(1, 6, 10, 0, 1),
(1, 8, 0, 0, 1),
(1, 9, 0, 0, 1),
(1, 10, 9545, 0, 2),
(2, 2, 21, 8, 1),
(2, 6, 211310, 2000, 1),
(3, 2, 0, 0, 1),
(3, 11, 5000, 0, 1),
(4, 2, 0, 0, 1),
(4, 11, 4031.75, 0, 1),
(5, 2, 0, 0, 1),
(5, 11, 2164.6, 0, 1),
(6, 11, 2794.52, 0, 1);

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
  `tipo_pago` enum('efectivo','cheque','tarjeta') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'efectivo' COMMENT 'tipo de pago para este abono',
  PRIMARY KEY (`id_pago`),
  KEY `pagos_venta_venta` (`id_venta`),
  KEY `id_sucursal` (`id_sucursal`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=91 ;

--
-- Volcar la base de datos para la tabla `pagos_venta`
--

INSERT INTO `pagos_venta` (`id_pago`, `id_venta`, `id_sucursal`, `id_usuario`, `fecha`, `monto`, `tipo_pago`) VALUES
(1, 1, 1, 101, '2011-01-19 23:02:09', 0.5, 'efectivo'),
(2, 1, 1, 101, '2011-01-19 23:02:09', 0.5, 'efectivo'),
(3, 1, 1, 101, '2011-01-19 23:02:09', 0.5, 'efectivo'),
(4, 1, 1, 101, '2011-01-19 23:02:09', 0.5, 'efectivo'),
(5, 1, 1, 101, '2011-01-19 23:02:09', 0.5, 'efectivo'),
(6, 1, 1, 101, '2011-01-19 23:02:10', 0.5, 'efectivo'),
(7, 1, 1, 101, '2011-01-19 23:02:10', 0.5, 'efectivo'),
(8, 1, 1, 101, '2011-01-19 23:02:10', 0.5, 'efectivo'),
(9, 1, 1, 101, '2011-01-19 23:02:10', 0.5, 'efectivo'),
(10, 1, 1, 101, '2011-01-19 23:02:10', 0.5, 'efectivo'),
(11, 1, 1, 101, '2011-01-19 23:02:10', 0.5, 'efectivo'),
(12, 1, 1, 101, '2011-01-19 23:02:10', 0.5, 'efectivo'),
(13, 1, 1, 101, '2011-01-19 23:03:33', 100, 'efectivo'),
(14, 1, 1, 101, '2011-01-19 23:21:36', 100, 'efectivo'),
(15, 1, 1, 101, '2011-01-19 23:22:29', 123, 'cheque'),
(16, 30, 1, 102, '2011-02-03 02:06:09', 1, 'efectivo'),
(17, 1, 1, 102, '2011-02-03 02:27:16', 71, 'efectivo'),
(18, 1, 1, 102, '2011-02-03 02:27:33', 600, 'efectivo'),
(19, 1, 1, 102, '2011-02-03 02:28:34', 350, 'efectivo'),
(20, 1, 1, 102, '2011-02-03 02:40:22', 600, 'efectivo'),
(21, 1, 1, 102, '2011-02-03 02:41:01', 1, 'efectivo'),
(22, 1, 1, 102, '2011-02-03 02:43:51', 40, 'efectivo'),
(23, 30, 1, 102, '2011-02-03 02:50:44', 0.79, 'efectivo'),
(24, 30, 1, 102, '2011-02-03 03:03:15', 1, 'efectivo'),
(25, 30, 1, 102, '2011-02-03 03:05:09', 1, 'efectivo'),
(26, 30, 1, 102, '2011-02-03 03:10:53', 1, 'efectivo'),
(27, 30, 1, 102, '2011-02-03 03:11:38', 0.99, 'efectivo'),
(28, 30, 1, 102, '2011-02-03 03:13:14', 1, 'efectivo'),
(29, 30, 1, 102, '2011-02-03 03:25:36', 0.5, 'cheque'),
(30, 30, 1, 102, '2011-02-03 03:26:00', 0.5, 'efectivo'),
(31, 30, 1, 102, '2011-02-03 03:26:20', 1, 'efectivo'),
(32, 30, 1, 102, '2011-02-03 03:32:22', 0.01, 'efectivo'),
(33, 30, 1, 102, '2011-02-03 03:40:30', 0.1, 'efectivo'),
(34, 30, 1, 102, '2011-02-03 03:49:44', 0.3, 'efectivo'),
(35, 30, 1, 102, '2011-02-03 04:04:14', 0.05, 'efectivo'),
(36, 30, 1, 102, '2011-02-03 04:06:37', 0.05, 'efectivo'),
(37, 30, 1, 102, '2011-02-03 04:13:59', 0.05, 'efectivo'),
(38, 30, 1, 102, '2011-02-03 04:15:23', 10, 'efectivo'),
(39, 30, 1, 102, '2011-02-03 04:19:26', 0.66, 'efectivo'),
(40, 30, 1, 102, '2011-02-03 04:22:12', 30, 'cheque'),
(41, 30, 1, 102, '2011-02-03 04:23:02', 10, 'efectivo'),
(42, 30, 1, 102, '2011-02-03 04:23:54', 10, 'efectivo'),
(43, 30, 1, 102, '2011-02-03 04:26:39', 5, 'efectivo'),
(44, 30, 1, 102, '2011-02-03 04:27:00', 5, 'efectivo'),
(45, 30, 1, 102, '2011-02-03 04:27:51', 20, 'efectivo'),
(46, 30, 1, 102, '2011-02-03 04:30:04', 5, 'efectivo'),
(47, 30, 1, 102, '2011-02-03 04:30:12', 5, 'efectivo'),
(48, 30, 1, 102, '2011-02-03 04:30:54', 10, 'efectivo'),
(49, 30, 1, 102, '2011-02-03 04:31:11', 70, 'cheque'),
(50, 30, 1, 102, '2011-02-03 04:31:28', 10, 'cheque'),
(51, 30, 1, 102, '2011-02-03 04:33:21', 180, 'efectivo'),
(52, 30, 1, 102, '2011-02-03 04:33:43', 20, 'cheque'),
(53, 7, 1, 102, '2011-02-03 04:38:39', 677.64, 'efectivo'),
(54, 7, 1, 102, '2011-02-03 04:41:10', 692, 'efectivo'),
(55, 5, 1, 102, '2011-02-03 04:41:30', 27, 'efectivo'),
(56, 5, 1, 102, '2011-02-03 04:41:55', 50, 'efectivo'),
(57, 5, 1, 102, '2011-02-03 04:42:07', 450, 'efectivo'),
(58, 118, 1, 102, '2011-02-11 00:59:05', 10, 'efectivo'),
(59, 131, 1, 102, '2011-02-14 02:14:34', 900, 'efectivo'),
(60, 131, 1, 102, '2011-02-14 02:26:20', 900, 'efectivo'),
(61, 127, 1, 102, '2011-02-14 02:31:27', 49, 'efectivo'),
(62, 131, 1, 102, '2011-02-14 02:34:28', 100, 'efectivo'),
(63, 131, 1, 102, '2011-02-14 03:13:30', 500, 'efectivo'),
(64, 131, 1, 102, '2011-02-14 03:13:53', 50, 'efectivo'),
(65, 131, 1, 102, '2011-02-14 03:16:17', 100, 'efectivo'),
(66, 131, 1, 102, '2011-02-14 03:16:27', 800, 'efectivo'),
(67, 131, 1, 102, '2011-02-14 03:21:53', 100, 'efectivo'),
(68, 131, 1, 102, '2011-02-14 03:22:01', 900, 'efectivo'),
(69, 130, 1, 102, '2011-02-14 03:22:18', 0.5, 'efectivo'),
(70, 131, 1, 102, '2011-02-14 03:22:27', 500, 'efectivo'),
(71, 131, 1, 102, '2011-02-14 03:31:06', 500, 'efectivo'),
(72, 131, 1, 102, '2011-02-14 03:31:16', 1000, 'cheque'),
(73, 67, 1, 102, '2011-02-22 16:40:15', 8.01, 'efectivo'),
(74, 91, 1, 102, '2011-02-22 16:40:56', 9.79, 'efectivo'),
(75, 172, 1, 102, '2011-02-22 16:42:31', 76.095, 'efectivo'),
(76, 170, 1, 102, '2011-02-22 16:43:00', 76.095, 'efectivo'),
(77, 2, 1, 102, '2011-02-23 18:59:37', 0.08, 'efectivo'),
(78, 171, 1, 102, '2011-02-23 19:12:32', 76.095, 'efectivo'),
(79, 173, 1, 102, '2011-02-23 19:16:28', 4, 'efectivo'),
(80, 178, 1, 102, '2011-02-23 22:22:10', 17.8, 'efectivo'),
(81, 2, 1, 102, '2011-02-24 00:59:59', 0.1, 'efectivo'),
(82, 174, 1, 102, '2011-02-24 01:02:57', 6.1, 'efectivo'),
(83, 202, 1, 102, '2011-02-24 01:07:13', 4, 'efectivo'),
(84, 202, 1, 102, '2011-02-24 01:08:53', 1, 'efectivo'),
(85, 202, 1, 102, '2011-02-24 01:10:30', 1.5, 'efectivo'),
(86, 202, 1, 102, '2011-02-24 01:31:20', 0.5, 'efectivo'),
(87, 2, 1, 102, '2011-02-26 17:08:28', 0.1, 'efectivo'),
(88, 2, 1, 102, '2011-02-26 17:09:06', 0.2, 'efectivo'),
(89, 202, 1, 102, '2011-03-06 12:02:38', 0.5, 'efectivo'),
(90, 202, 1, 102, '2011-03-06 12:03:07', 0.5, 'efectivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_prestamo_sucursal`
--

CREATE TABLE IF NOT EXISTS `pago_prestamo_sucursal` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT COMMENT 'El identificador de este pago',
  `id_prestamo` int(11) NOT NULL COMMENT 'El id del prestamo al que pertenece este prestamo',
  `id_usuario` int(11) NOT NULL COMMENT 'El usurio que recibe este dinero',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'La fecha cuando se realizo este pago',
  `monto` float NOT NULL COMMENT 'El monto a abonar',
  PRIMARY KEY (`id_pago`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Volcar la base de datos para la tabla `pago_prestamo_sucursal`
--

INSERT INTO `pago_prestamo_sucursal` (`id_pago`, `id_prestamo`, `id_usuario`, `fecha`, `monto`) VALUES
(19, 7, 102, '2011-02-11 01:06:36', 20.5),
(18, 7, 102, '2011-02-11 00:42:27', 100),
(17, 2, 102, '2011-02-02 01:59:05', 102356),
(16, 1, 102, '2011-02-02 01:58:46', 10000),
(15, 1, 102, '2011-02-02 01:58:36', 107),
(20, 7, 102, '2011-02-11 01:07:07', 100),
(21, 7, 102, '2011-02-11 01:07:19', 100),
(22, 7, 102, '2011-02-11 01:07:32', 179.5),
(23, 3, 102, '2011-02-11 19:28:59', 500),
(24, 3, 102, '2011-02-11 19:29:17', 500),
(25, 32, 102, '2011-02-22 16:53:49', 50),
(26, 11, 102, '2011-02-23 18:27:56', 200),
(27, 3, 102, '2011-03-06 12:36:44', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pos_config`
--

CREATE TABLE IF NOT EXISTS `pos_config` (
  `opcion` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`opcion`),
  UNIQUE KEY `opcion` (`opcion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `pos_config`
--

INSERT INTO `pos_config` (`opcion`, `value`) VALUES
('emisor', '{     "emisor": {         "nombre" : "JUAN ANTONIO GARCIA TAPIA",         "rfc": "GATJ740714F48",         "calle": "AVENIDA CONSTITUYENTES",         "numeroExterior": "360",         "numeroInteior": "BODEGA 49",         "referencia": null,         "colonia": "CENTRO",         "localidad": null,         "municipio": "CELAYA",         "estado": "GUANAJUATO",         "pais": "MEXICO",         "codigoPostal": "38070"      } }'),
('llave_privada', '<RSAKeyValue><Modulus>98s1g5UleYF81Jl33fKBW1WciQpfgBhzEyWWVQnxWAbtGyLjOVtYGPLYcZJGZw3YzrTousm7ezYfdY8O0Y8938b7YwCQFYd08DWv6m1umsiLBOhdRATkIU/gTleX7FPcI17icmUhVV60lNw0xl1F5iW1VApsTmdwGPes+9MUQtk=</Modulus><Exponent>AQAB</Exponent><P>/Fn/S8tJ12o1vCs9OcRqpApmEQXibZixOtPxESWlBsAo28Wqs/vPmDgusz92XXJuaQH4Osdjama/CDhUCvRj7w==</P><Q>+2BXtIVDyesIzRNem+VosbXFVc4PGD76K+idru89/PPVErYdfKOdMFHpU47dudZumOetoWwHxhTGgEHsXB1dtw==</Q><DP>j1/Gp9aJTqWegBmFALQy7p6l3NgeDKyrTUQre9WKjGpTDIKi+P2Btfd9uQO+iVtBldGzqhmsx0A3G0F9pnex6w==</DP><DQ>gxRD57AuHxZeKoHVLbm8lB5S3mFq4ZvFXCwaPWQnkMWc7/ri+WFf0BiGcLnoyMUWOslkcu4gR5wBvlOh6o6tlQ==</DQ><InverseQ>3jk1GVeQ54uO7k34xuXR07ZSpVjn4WxABbcxS6C+Aztk7AzVdxmwoy942sKwIQMNUW9l/XPF5xVv099jCOLXdg==</InverseQ><D>7llSFUqebFzodRtV2HDOisszsgH/UJi0AAPUseHJezHWWpbZWC+L8xuMpNndzHzeQh50Ck/HfcHWyt6bk2mkSbp0yBVpBtCNRfsoVj/YMT0y77sRGwBIPRbPoxgbHBc1NqYCqs+s8cuX/NHMJDk8JtF+twGRCMWwxixc8Tj4w80=</D></RSAKeyValue>'),
('llave_publica', 'MIIEDTCCAvWgAwIBAgIUMDAwMDEwMDAwMDAxMDMwOTk5ODEwDQYJKoZIhvcNAQEFBQAwggE2MTgwNgYDVQQDDC9BLkMuIGRlbCBTZXJ2aWNpbyBkZSBBZG1pbmlzdHJhY2nDs24gVHJpYnV0YXJpYTEvMC0GA1UECgwmU2VydmljaW8gZGUgQWRtaW5pc3RyYWNpw7NuIFRyaWJ1dGFyaWExHzAdBgkqhkiG9w0BCQEWEGFjb2RzQHNhdC5nb2IubXgxJjAkBgNVBAkMHUF2LiBIaWRhbGdvIDc3LCBDb2wuIEd1ZXJyZXJvMQ4wDAYDVQQRDAUwNjMwMDELMAkGA1UEBhMCTVgxGTAXBgNVBAgMEERpc3RyaXRvIEZlZGVyYWwxEzARBgNVBAcMCkN1YXVodGVtb2MxMzAxBgkqhkiG9w0BCQIMJFJlc3BvbnNhYmxlOiBGZXJuYW5kbyBNYXJ0w61uZXogQ29zczAeFw0xMTAzMjAwMTA1NDNaFw0xMzAzMTkwMTA1NDNaMIGtMSIwIAYDVQQDExlKVUFOIEFOVE9OSU8gR0FSQ0lBIFRBUElBMSIwIAYDVQQpExlKVUFOIEFOVE9OSU8gR0FSQ0lBIFRBUElBMSIwIAYDVQQKExlKVUFOIEFOVE9OSU8gR0FSQ0lBIFRBUElBMRYwFAYDVQQtEw1HQVRKNzQwNzE0RjQ4MRswGQYDVQQFExJHQVRKNzQwNzE0SEdUUlBOMDMxCjAIBgNVBAsTASAwgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAPfLNYOVJXmBfNSZd93ygVtVnIkKX4AYcxMlllUJ8VgG7Rsi4zlbWBjy2HGSRmcN2M606LrJu3s2H3WPDtGPPd/G+2MAkBWHdPA1r+ptbprIiwToXUQE5CFP4E5Xl+xT3CNe4nJlIVVetJTcNMZdReYltVQKbE5ncBj3rPvTFELZAgMBAAGjHTAbMAwGA1UdEwEB/wQCMAAwCwYDVR0PBAQDAgbAMA0GCSqGSIb3DQEBBQUAA4IBAQCfusmkDoo/iygnPWwTooH4Itt9HsNGW8OnUwwhMxSwyzjzmO+CYNv77DtAD/UuJ7cu/kzprHRbLUMng8vEEzBvOlPyEx3naoxOjSCDFiY5ATPkLM+i8Xb7WyqPneCKKdTmm6n/cZroJZLwShhhsK1LFRJKJRyEOqiIXXGq/YJJcBVbsMuznxW5/dRiKTBlOtWRdh7dq9eWch4fOBsOa4+alHaBbXKlbybgWHzHq8gpLTKE8q0o1u6hGPvGZevh9dDfusMPR4lAFxadCYJbv2z+dfdPaNSnwZg3jgOP4pKOAey2PiSPApNLMV9lveDOGcrnlJWLIep67921bdcc2odF'),
('noCertificado', '00001000000103099981'),
('url_logo', 'http://upload.wikimedia.org/wikipedia/it/thumb/7/76/Caffeina_struttura.svg/150px-Caffeina_struttura.svg.png'),
('url_timbrado', 'http://201.151.142.236:666/Timbrado.asmx?wsdl');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo_sucursal`
--

CREATE TABLE IF NOT EXISTS `prestamo_sucursal` (
  `id_prestamo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'El identificador de este prestamo',
  `prestamista` int(11) NOT NULL COMMENT 'La sucursal que esta prestando',
  `deudor` int(11) NOT NULL COMMENT 'La sucursal que esta recibiendo',
  `monto` float NOT NULL COMMENT 'El monto prestado',
  `saldo` float NOT NULL COMMENT 'El saldo pendiente para liquidar',
  `liquidado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Bandera para buscar rapidamente los prestamos que aun no estan saldados',
  `concepto` varchar(256) NOT NULL COMMENT 'El concepto de este prestamo',
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha en la que se registro el gasto',
  PRIMARY KEY (`id_prestamo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Volcar la base de datos para la tabla `prestamo_sucursal`
--

INSERT INTO `prestamo_sucursal` (`id_prestamo`, `prestamista`, `deudor`, `monto`, `saldo`, `liquidado`, `concepto`, `fecha`) VALUES
(1, 1, 2, 10107, 0, 1, 'kokoko', '2011-02-02 01:58:46'),
(2, 1, 2, 102356, 0, 1, 'kakakakakka', '2011-02-02 01:59:05'),
(3, 1, 2, 1012360, 1011360, 0, 'dsfsdf', '2011-02-11 19:29:17'),
(4, 2, 1, 1000, 1000, 0, 'La comida', '2011-02-01 22:35:32'),
(5, 2, 1, 450, 450, 0, 'Unas cosas', '2011-02-01 22:35:32'),
(6, 1, 2, 78, 78, 0, 'ewrwer', '2011-02-02 17:20:15'),
(7, 1, 2, 500, 0, 1, 'Algo', '2011-02-11 01:07:32'),
(8, 1, 2, 111, 111, 0, 'DDD', '2011-02-11 17:37:03'),
(9, 1, 2, 500.5, 500.5, 0, 'Una Necesidad', '2011-02-11 18:27:25'),
(10, 1, 2, 1000, 1000, 0, 'DFGDS', NULL),
(11, 1, 2, 1500, 1300, 0, 'Pago del flete', NULL),
(12, 1, 2, 5000, 5000, 0, 'Pago de Telefono', NULL),
(13, 1, 2, 1111, 1111, 0, 'sdfsdfsdf', NULL),
(14, 1, 2, 1212, 1212, 0, 'dsfsdfsdf', NULL),
(15, 1, 2, 222, 222, 0, 'qqqqqqqqq', NULL),
(16, 1, 2, 333333000, 333333000, 0, '333333', NULL),
(17, 1, 2, 444, 444, 0, '4444444444444', NULL),
(18, 1, 2, 1300, 1300, 0, 'Pago de Internet', NULL),
(19, 1, 2, 400, 400, 0, 'Pago de la Pintura', NULL),
(20, 1, 2, 12563, 12563, 0, 'sdfsdfdsf', NULL),
(21, 1, 2, 124896, 124896, 0, 'asdasdasd', NULL),
(22, 1, 2, 10000, 10000, 0, 'Pago de Electrisista', NULL),
(23, 1, 2, 1050, 1050, 0, 'EL PAPEL', NULL),
(24, 1, 2, 111, 111, 0, 'wqeqweqwe', NULL),
(25, 1, 2, 23, 23, 0, 'asdasd', NULL),
(26, 1, 2, 111, 111, 0, 'asdasdasd', NULL),
(27, 1, 2, 1200, 1200, 0, 'Una Cosa Desconocida', NULL),
(28, 1, 2, 7500, 7500, 0, 'Pago de la reparacion de la lavadora de papas', NULL),
(29, 1, 2, 7500, 7500, 0, 'Reparacion de la lavadora de papas', NULL),
(30, 1, 2, 10000, 10000, 0, 'Reparacion de la lavadora de papas', NULL),
(31, 1, 2, 5633, 5633, 0, 'asdasdasdasd asdasadsd', NULL),
(32, 1, 2, 100, 50, 0, 'VBNM', NULL),
(33, 1, 2, 1500, 1500, 0, 'DFFFFF', NULL),
(34, 1, 2, 100, 100, 0, 'DFBV', NULL),
(35, 1, 2, 1000, 1000, 0, 'GGGGG', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE IF NOT EXISTS `proveedor` (
  `id_proveedor` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del proveedor',
  `rfc` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'rfc del proveedor',
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre del proveedor',
  `direccion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'direccion del proveedor',
  `telefono` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'telefono',
  `e_mail` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'email del provedor',
  `activo` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'Indica si la cuenta esta activada o desactivada',
  `tipo_proveedor` enum('admin','sucursal','ambos') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'admin' COMMENT 'si este proveedor surtira al admin, a las sucursales o a ambos',
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `rfc`, `nombre`, `direccion`, `telefono`, `e_mail`, `activo`, `tipo_proveedor`) VALUES
(1, '', 'jose jimenez e hijos', 'rolblaka sdklf 312 col centro', '6149974', NULL, 1, 'admin'),
(2, '', 'AAAAAAAAaaaaa', 'AAAAAAAAaa', '', NULL, 1, 'admin'),
(3, '', 'patamban', 'zamora mich.', '', NULL, 1, 'admin'),
(4, '', 'asdfasdfasdf', 'aaaaaaaaaa', '', NULL, 1, 'admin'),
(5, '', 'pepsi co.', 'celaya, gto', '', NULL, 1, 'ambos');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id_sucursal`, `gerente`, `descripcion`, `razon_social`, `rfc`, `calle`, `numero_exterior`, `numero_interior`, `colonia`, `localidad`, `referencia`, `municipio`, `estado`, `pais`, `codigo_postal`, `telefono`, `token`, `letras_factura`, `activo`, `fecha_apertura`, `saldo_a_favor`) VALUES
(1, 102, 'papas supremas 1', 'papas supremas 1', 'SUPA871102XD2', 'Anden 3', '442', '42', 'Central de Abastos', 'Celaya', 'Una bodega en el mercado', 'Celaya', 'Guanajuato', 'Mexico', '38080', '1726376672', NULL, 'c', 1, '2011-01-09 01:38:26', 0),
(2, 104, 'papas supremas 2', 'monte balcanes 107 2da secc arboledas celaya', 'sp9233876', '', '', NULL, '', '', NULL, '', '', '', '', '6149974', NULL, 'a', 1, '2011-01-26 22:47:53', 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=107 ;

--
-- Volcar la base de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `RFC`, `nombre`, `contrasena`, `id_sucursal`, `activo`, `finger_token`, `salario`, `direccion`, `telefono`, `fecha_inicio`) VALUES
(1, 'GOHE1235432', 'ALAN GONZALEZ HERNANDEZ', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 123, '123', '123', '2011-01-15 12:43:02'),
(88, 'GOHE39874', 'Alan Gonzalez Hernandez', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 0, '0', '0', '2011-01-07 01:12:29'),
(99, 'HERJ761212', 'JUAN MANUEL HERNANDEZ REYES', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 0, '0', '0', '2011-02-22 20:05:25'),
(100, 'LOMA870805', 'ADRIAN PASTOR LOPEZ MONRROY', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 0, '0', '0', '2011-02-22 20:06:01'),
(101, 'ESLI661206', 'IVAN ESCOBEDO LANDIN', '202cb962ac59075b964b07152d234b70', 1, 1, NULL, 0, '0', '0', '2011-02-22 20:06:36'),
(102, 'goha881703', 'Alan gonzalez hernandez', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, NULL, 2000, 'monte balcanes #107 2da secc arboledas', '6149974', '2011-01-28 13:02:28'),
(103, 'asdfasdfasdfff', 'dilbo martinez salgado', '827ccb0eea8a706c4c34a16891f84e7b', NULL, 0, NULL, 0.000001, 'asdfasdfasdf', 'asdfasdfsadf', '2011-01-13 00:00:00'),
(104, 'jgl12344332', 'jorge gerencias lopez', '202cb962ac59075b964b07152d234b70', 2, 1, NULL, 1000, 'monte balcanes #017 2da secc arboledas', '6149974', '2011-01-26 00:00:00'),
(105, 'MOAJ841109', 'Jose Luis Monrroy Aguilar y asociados', '027667670d4fada2eb3a2c8943178b07', 1, 1, NULL, 1500, 'OCOTE #111 COL. LAS INSURGENTES CELAYA GTO', '4616148526', '2011-02-22 00:00:00'),
(106, 'GACA900321', '-Alberto Garcia Carmona', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 1200, 'circuito Marabu #127A', '4616132441', '2011-03-06 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE IF NOT EXISTS `ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de venta',
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
  KEY `ventas_cliente` (`id_cliente`),
  KEY `ventas_sucursal` (`id_sucursal`),
  KEY `ventas_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=233 ;

--
-- Volcar la base de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `tipo_venta`, `tipo_pago`, `fecha`, `subtotal`, `iva`, `descuento`, `total`, `id_sucursal`, `id_usuario`, `pagado`, `cancelada`, `ip`, `liquidada`) VALUES
(1, 1, 'credito', NULL, '2011-01-19 22:28:05', 5000, NULL, 0, 5000, 1, 101, 1991, 1, '192.168.1.3', 0),
(2, 2, 'credito', NULL, '2011-01-20 03:27:16', 1, NULL, 2, 0.98, 1, 101, 0.48, 1, '192.168.1.88', 0),
(3, -1, 'contado', NULL, '2011-01-23 00:14:22', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(4, -1, 'contado', NULL, '2011-01-23 13:58:59', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(5, -2, 'credito', NULL, '2011-01-23 15:43:26', 528, NULL, 0, 528, 1, 102, 528, 1, '127.0.0.1', 1),
(6, -1, 'contado', 'efectivo', '2011-01-25 22:51:29', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(7, -2, 'credito', 'efectivo', '2011-01-26 23:17:23', 692, NULL, 0, 692, 2, 104, 692, 0, '127.0.0.1', 1),
(8, -1, 'contado', 'efectivo', '2011-01-30 02:44:50', 110, NULL, 0, 110, 1, 102, 110, 1, '127.0.0.1', 1),
(9, -1, 'contado', 'efectivo', '2011-01-30 04:04:29', 89, 0, 0, 89, 1, 102, 89, 1, '127.0.0.1', 1),
(10, 6, 'contado', 'efectivo', '2011-02-01 16:21:57', 190, NULL, 0, 190, 1, 102, 190, 1, '127.0.0.1', 1),
(11, -1, 'contado', 'cheque', '2011-02-01 16:24:25', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(12, -1, 'contado', 'efectivo', '2011-02-01 16:45:08', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(13, -1, 'contado', 'efectivo', '2011-02-01 16:47:36', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(14, -1, 'contado', 'efectivo', '2011-02-01 16:50:44', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(15, -1, 'contado', 'efectivo', '2011-02-01 16:53:14', 9, NULL, 0, 9, 1, 102, 9, 1, '127.0.0.1', 1),
(16, -1, 'contado', 'efectivo', '2011-02-01 16:53:57', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(17, -1, 'contado', 'efectivo', '2011-02-01 17:01:08', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(18, -1, 'contado', 'cheque', '2011-02-01 17:01:57', 9, NULL, 0, 9, 1, 102, 9, 1, '127.0.0.1', 1),
(19, -1, 'contado', 'efectivo', '2011-02-01 17:03:25', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(20, -1, 'contado', 'efectivo', '2011-02-01 17:06:22', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(21, -1, 'contado', 'cheque', '2011-02-01 17:06:31', 9, NULL, 0, 9, 1, 102, 9, 1, '127.0.0.1', 1),
(22, -1, 'contado', 'efectivo', '2011-02-01 17:10:33', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(23, -1, 'contado', 'efectivo', '2011-02-01 17:12:13', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(24, -1, 'contado', 'cheque', '2011-02-01 17:12:23', 9, NULL, 0, 9, 1, 102, 9, 1, '127.0.0.1', 1),
(25, -1, 'contado', 'efectivo', '2011-02-01 17:17:56', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(26, -1, 'contado', 'efectivo', '2011-02-01 17:21:50', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(27, -1, 'contado', 'efectivo', '2011-02-01 17:22:00', 9, NULL, 0, 9, 1, 102, 9, 1, '127.0.0.1', 1),
(28, -1, 'contado', 'efectivo', '2011-02-01 17:29:09', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(29, -1, 'credito', 'efectivo', '2011-02-01 17:29:18', 200, NULL, 0, 200, 1, 102, 0, 1, '127.0.0.1', 0),
(30, -2, 'credito', NULL, '2011-02-02 16:41:30', 200, NULL, 0, 200, 1, 102, 200, 0, '127.0.0.1', 1),
(31, -1, 'contado', 'efectivo', '2011-02-03 12:06:34', 9, NULL, 0, 9, 1, 102, 9, 0, '192.168.1.66', 1),
(32, -1, 'contado', 'efectivo', '2011-02-03 13:03:11', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(33, -1, 'contado', 'efectivo', '2011-02-03 13:05:01', 68, NULL, 0, 68, 1, 102, 68, 0, '192.168.1.66', 1),
(34, -1, 'contado', 'efectivo', '2011-02-03 13:06:50', 136.5, NULL, 0, 136.5, 1, 102, 136.5, 0, '192.168.1.66', 1),
(35, -1, 'contado', 'efectivo', '2011-02-03 13:09:23', 261.5, NULL, 0, 261.5, 1, 102, 261.5, 0, '192.168.1.66', 1),
(36, -1, 'contado', 'efectivo', '2011-02-03 13:11:57', 148, NULL, 0, 148, 1, 102, 148, 0, '192.168.1.66', 1),
(37, -1, 'contado', 'efectivo', '2011-02-03 13:14:33', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(38, -1, 'contado', 'efectivo', '2011-02-03 13:16:41', 9, NULL, 0, 9, 1, 102, 9, 0, '192.168.1.66', 1),
(39, -1, 'contado', 'efectivo', '2011-02-03 13:16:42', 9, NULL, 0, 9, 1, 102, 9, 0, '192.168.1.66', 1),
(40, -1, 'contado', 'efectivo', '2011-02-03 13:46:57', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(41, -1, 'contado', 'efectivo', '2011-02-03 13:48:14', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(42, -1, 'contado', 'efectivo', '2011-02-03 13:48:15', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(43, 1, 'contado', 'efectivo', '2011-02-03 13:51:18', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(44, 1, 'contado', 'efectivo', '2011-02-03 13:52:19', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(45, 1, 'contado', 'efectivo', '2011-02-03 14:03:27', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(46, 1, 'contado', 'efectivo', '2011-02-03 14:12:31', 20, NULL, 0, 20, 1, 102, 20, 0, '192.168.1.66', 1),
(47, 3, 'contado', 'efectivo', '2011-02-03 14:14:57', 20, NULL, 0, 17.8, 1, 102, 17.8, 0, '192.168.1.66', 1),
(48, 3, 'contado', 'efectivo', '2011-02-03 14:17:29', 20, NULL, 0, 17.8, 1, 102, 17.8, 0, '192.168.1.66', 1),
(49, 3, 'contado', 'efectivo', '2011-02-03 14:19:34', 11, NULL, 0, 9.79, 1, 102, 9.79, 0, '192.168.1.66', 1),
(50, 1, 'contado', 'efectivo', '2011-02-03 14:22:20', 11, 0, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(51, -1, 'contado', 'efectivo', '2011-02-03 14:24:21', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(52, -1, 'contado', 'efectivo', '2011-02-03 14:35:51', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(53, -1, 'contado', 'efectivo', '2011-02-03 14:37:23', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(54, -1, 'contado', 'efectivo', '2011-02-03 14:38:21', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(55, -1, 'contado', 'efectivo', '2011-02-03 14:39:35', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(56, -1, 'contado', 'efectivo', '2011-02-03 14:40:22', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(57, -1, 'contado', 'efectivo', '2011-02-03 14:40:44', 9, NULL, 0, 9, 1, 102, 9, 0, '192.168.1.66', 1),
(58, -1, 'contado', 'efectivo', '2011-02-03 14:42:33', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(59, -1, 'contado', 'efectivo', '2011-02-03 15:47:40', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.66', 1),
(60, 1, 'contado', 'efectivo', '2011-02-03 15:49:40', 202, NULL, 0, 202, 1, 102, 202, 0, '192.168.1.66', 1),
(61, 1, 'contado', NULL, '2011-02-04 02:08:59', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(62, 3, 'contado', 'cheque', '2011-02-04 02:10:00', 11, NULL, 0, 9.79, 1, 102, 9.79, 0, '127.0.0.1', 1),
(63, -1, 'contado', 'efectivo', '2011-02-04 02:11:32', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(64, -1, 'contado', 'cheque', '2011-02-04 02:17:31', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(65, -1, 'contado', 'efectivo', '2011-02-04 02:21:16', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(66, 1, 'credito', NULL, '2011-02-04 11:08:38', 11, NULL, 0, 11, 1, 102, 0, 0, '127.0.0.1', 0),
(67, 3, 'credito', NULL, '2011-02-04 12:50:41', 9, NULL, 0, 8.01, 1, 102, 8.01, 0, '127.0.0.1', 1),
(68, 1, 'contado', 'efectivo', '2011-02-04 13:11:03', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(69, 1, 'contado', 'efectivo', '2011-02-04 13:12:22', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(70, 1, 'contado', 'efectivo', '2011-02-04 13:13:21', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(71, 1, 'contado', 'efectivo', '2011-02-04 13:18:21', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(72, 3, 'contado', 'efectivo', '2011-02-04 13:20:57', 11, NULL, 0, 9.79, 1, 102, 9.79, 0, '127.0.0.1', 1),
(73, 3, 'contado', 'efectivo', '2011-02-04 13:30:52', 11, NULL, 0, 9.79, 1, 102, 9.79, 0, '127.0.0.1', 1),
(74, 3, 'contado', 'efectivo', '2011-02-04 13:32:19', 11, NULL, 0, 9.79, 1, 102, 9.79, 0, '127.0.0.1', 1),
(75, 1, 'contado', 'efectivo', '2011-02-04 13:33:37', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(76, 3, 'contado', 'efectivo', '2011-02-04 13:34:13', 11, NULL, 0, 9.79, 1, 102, 9.79, 0, '127.0.0.1', 1),
(77, 3, 'contado', 'efectivo', '2011-02-04 13:35:23', 11, NULL, 0, 9.79, 1, 102, 9.79, 0, '127.0.0.1', 1),
(78, 3, 'contado', 'efectivo', '2011-02-04 13:36:15', 11, NULL, 0, 9.79, 1, 102, 9.79, 0, '127.0.0.1', 1),
(79, 3, 'contado', 'efectivo', '2011-02-04 13:37:35', 11, NULL, 0, 9.79, 1, 102, 9.79, 0, '127.0.0.1', 1),
(80, -1, 'contado', 'efectivo', '2011-02-04 13:39:31', 244, NULL, 0, 244, 1, 102, 244, 0, '127.0.0.1', 1),
(81, 1, 'contado', 'efectivo', '2011-02-04 13:40:37', 45, NULL, 0, 45, 1, 102, 45, 0, '127.0.0.1', 1),
(82, 1, 'contado', 'efectivo', '2011-02-04 13:41:54', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(83, 2, 'contado', 'efectivo', '2011-02-04 13:44:33', 11, NULL, 0, 10.78, 1, 102, 10.78, 0, '127.0.0.1', 1),
(84, 1, 'contado', 'efectivo', '2011-02-04 13:45:01', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(85, -1, 'contado', 'efectivo', '2011-02-04 15:53:34', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(86, 3, 'credito', NULL, '2011-02-04 15:54:32', 11, NULL, 0, 9.79, 1, 102, 0, 0, '127.0.0.1', 0),
(87, -1, 'contado', 'efectivo', '2011-02-04 15:56:08', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(88, 1, 'credito', NULL, '2011-02-04 19:48:14', 11, NULL, 0, 11, 1, 102, 0, 0, '127.0.0.1', 0),
(89, 1, 'credito', NULL, '2011-02-04 20:08:10', 11, NULL, 0, 11, 1, 102, 0, 0, '10.0.0.4', 0),
(90, -1, 'contado', 'efectivo', '2011-02-04 20:36:17', 11, NULL, 0, 11, 1, 102, 11, 0, '10.0.0.4', 1),
(91, 3, 'credito', NULL, '2011-02-04 20:36:39', 11, NULL, 0, 9.79, 1, 102, 9.79, 1, '10.0.0.4', 1),
(92, 1, 'credito', NULL, '2011-02-08 00:10:22', 11, NULL, 0, 11, 1, 102, 0, 0, '127.0.0.1', 0),
(93, 3, 'credito', NULL, '2011-02-08 00:22:23', 11, NULL, 0, 9.79, 1, 102, 0, 0, '192.168.1.11', 0),
(94, 3, 'credito', NULL, '2011-02-08 01:38:56', 20, NULL, 0, 17.8, 1, 102, 0, 0, '127.0.0.1', 0),
(95, 6, 'contado', 'efectivo', '2011-02-08 01:39:12', 9, NULL, 0, 9, 1, 102, 9, 0, '127.0.0.1', 1),
(96, -1, 'contado', 'cheque', '2011-02-08 01:40:01', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(97, 2, 'contado', 'efectivo', '2011-02-08 01:43:17', 11, NULL, 0, 10.78, 1, 102, 10.78, 0, '127.0.0.1', 1),
(98, -1, 'contado', 'cheque', '2011-02-08 01:43:42', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(99, 3, 'contado', 'efectivo', '2011-02-08 21:32:50', 10, NULL, 0, 8.9, 1, 102, 8.9, 0, '127.0.0.1', 1),
(100, 3, 'contado', 'efectivo', '2011-02-08 21:35:53', 15, NULL, 0, 13.35, 1, 102, 13.35, 0, '127.0.0.1', 1),
(101, 3, 'contado', 'efectivo', '2011-02-08 21:37:46', 30, NULL, 0, 26.7, 1, 102, 26.7, 0, '127.0.0.1', 1),
(102, -1, 'contado', 'efectivo', '2011-02-08 22:21:02', 226.5, NULL, 0, 226.5, 1, 102, 226.5, 0, '127.0.0.1', 1),
(103, -1, 'contado', 'efectivo', '2011-02-08 22:29:03', 230, NULL, 0, 230, 1, 102, 230, 0, '127.0.0.1', 1),
(104, -1, 'contado', 'efectivo', '2011-02-08 22:35:05', 3615.5, NULL, 0, 3615.5, 1, 102, 3615.5, 0, '127.0.0.1', 1),
(105, -1, 'contado', 'efectivo', '2011-02-08 23:03:34', 225, NULL, 0, 225, 1, 102, 225, 0, '127.0.0.1', 1),
(106, -1, 'contado', 'efectivo', '2011-02-08 23:03:41', 225, NULL, 0, 225, 1, 102, 225, 0, '127.0.0.1', 1),
(107, -1, 'contado', 'efectivo', '2011-02-09 00:28:51', 882, NULL, 0, 882, 1, 102, 882, 0, '127.0.0.1', 1),
(108, -1, 'contado', 'efectivo', '2011-02-09 00:28:54', 882, NULL, 0, 882, 1, 102, 882, 0, '127.0.0.1', 1),
(109, -1, 'contado', 'efectivo', '2011-02-09 13:34:29', 178, NULL, 0, 178, 1, 102, 178, 0, '127.0.0.1', 1),
(110, -1, 'contado', 'efectivo', '2011-02-09 13:36:08', 120, NULL, 0, 120, 1, 102, 120, 0, '127.0.0.1', 1),
(111, -1, 'contado', 'cheque', '2011-02-09 13:36:40', 120, NULL, 0, 120, 1, 102, 120, 0, '127.0.0.1', 1),
(112, -1, 'contado', 'efectivo', '2011-02-09 14:39:09', 190, NULL, 0, 190, 1, 102, 190, 0, '127.0.0.1', 1),
(113, -1, 'contado', 'efectivo', '2011-02-09 14:41:05', 99, NULL, 0, 99, 1, 102, 99, 0, '127.0.0.1', 1),
(114, 3, 'contado', 'efectivo', '2011-02-09 22:38:41', 1, NULL, 0, 0.89, 1, 102, 0.89, 1, '127.0.0.1', 1),
(115, 3, 'contado', 'efectivo', '2011-02-10 00:06:22', 4, NULL, 0, 3.56, 1, 102, 3.56, 1, '127.0.0.1', 1),
(116, 3, 'contado', 'efectivo', '2011-02-10 00:10:10', 11, NULL, 0, 9.79, 1, 102, 9.79, 1, '127.0.0.1', 1),
(117, 3, 'contado', 'efectivo', '2011-02-10 01:52:44', 6, NULL, 0, 5.34, 1, 102, 5.34, 0, '127.0.0.1', 1),
(118, -1, 'credito', NULL, '2011-02-11 00:56:40', 35.5, NULL, 0, 35.5, 1, 102, 10, 0, '127.0.0.1', 0),
(123, -1, 'credito', NULL, '2011-02-11 02:51:09', 42.5, NULL, 0, 42.5, 1, 102, 0, 0, '127.0.0.1', 0),
(124, -2, 'credito', NULL, '2011-02-11 03:01:03', 88, NULL, 0, 88, 1, 102, 0, 0, '127.0.0.1', 0),
(125, -2, 'credito', NULL, '2011-02-11 03:02:45', 127.5, NULL, 0, 127.5, 1, 102, 0, 0, '127.0.0.1', 0),
(126, -2, 'credito', NULL, '2011-02-11 03:03:34', 7, NULL, 0, 7, 1, 102, 0, 0, '127.0.0.1', 0),
(127, -2, 'credito', NULL, '2011-02-11 03:05:14', 49, NULL, 0, 49, 1, 102, 49, 0, '127.0.0.1', 1),
(128, -2, 'credito', NULL, '2011-02-11 03:05:51', 25.5, NULL, 0, 25.5, 1, 102, 0, 0, '127.0.0.1', 0),
(129, -2, 'credito', NULL, '2011-02-11 03:06:28', 42.5, NULL, 0, 42.5, 1, 102, 0, 0, '127.0.0.1', 0),
(130, -2, 'credito', NULL, '2011-02-11 03:10:14', 2997.5, NULL, 0, 2997.5, 1, 102, 0.5, 0, '127.0.0.1', 0),
(131, -2, 'credito', NULL, '2011-02-11 03:32:35', 4900, NULL, 0, 4900, 1, 102, 3900, 0, '127.0.0.1', 0),
(132, -1, 'contado', 'efectivo', '2011-02-11 05:09:32', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(133, -1, 'contado', 'efectivo', '2011-02-11 05:09:52', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(134, -2, 'contado', 'efectivo', '2011-02-11 05:11:10', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(135, -1, 'contado', 'efectivo', '2011-02-11 05:17:31', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(136, -1, 'contado', 'efectivo', '2011-02-11 05:37:12', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(137, -1, 'contado', 'efectivo', '2011-02-11 15:56:57', 28, NULL, 0, 28, 1, 102, 28, 0, '192.168.1.11', 1),
(138, -1, 'contado', 'efectivo', '2011-02-11 17:38:41', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(139, -1, 'contado', 'efectivo', '2011-02-11 17:43:35', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(140, -1, 'contado', 'efectivo', '2011-02-11 17:44:14', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(141, -1, 'contado', NULL, '2011-02-11 17:44:50', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(142, -1, 'contado', 'efectivo', '2011-02-11 17:45:01', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(143, -1, 'contado', 'efectivo', '2011-02-11 18:10:08', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(144, -1, 'contado', 'efectivo', '2011-02-12 13:04:59', 81, NULL, 0, 81, 1, 102, 81, 0, '192.168.1.11', 1),
(145, -1, 'contado', 'efectivo', '2011-02-12 13:06:08', 111, NULL, 0, 111, 1, 102, 111, 0, '192.168.1.11', 1),
(146, -1, 'contado', 'efectivo', '2011-02-12 13:51:03', 118, NULL, 0, 118, 1, 102, 118, 0, '192.168.1.11', 1),
(147, -1, 'contado', 'efectivo', '2011-02-12 13:53:37', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(148, -1, 'contado', 'efectivo', '2011-02-12 14:05:56', 20, NULL, 0, 20, 1, 102, 20, 0, '192.168.1.11', 1),
(149, -1, 'contado', 'efectivo', '2011-02-12 14:06:22', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(150, -1, 'contado', 'efectivo', '2011-02-12 14:06:55', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(151, -1, 'contado', 'efectivo', '2011-02-12 14:08:56', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(152, -1, 'contado', 'efectivo', '2011-02-12 14:10:49', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(153, -1, 'contado', 'efectivo', '2011-02-12 14:11:57', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(154, -1, 'contado', 'efectivo', '2011-02-12 14:15:55', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.7', 1),
(155, -1, 'contado', 'efectivo', '2011-02-12 15:04:56', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(156, -1, 'contado', 'efectivo', '2011-02-12 15:05:38', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(157, -1, 'contado', 'efectivo', '2011-02-12 18:36:47', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(158, 3, 'credito', NULL, '2011-02-13 23:33:28', 11, NULL, 0, 9.79, 1, 102, 0, 0, '127.0.0.1', 0),
(159, -1, 'contado', 'efectivo', '2011-02-13 23:41:03', 1010, NULL, 0, 1010, 1, 102, 1010, 0, '127.0.0.1', 1),
(160, -1, 'contado', 'efectivo', '2011-02-14 04:12:26', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.7', 1),
(161, -1, 'contado', 'efectivo', '2011-02-14 04:20:25', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(162, -1, 'contado', 'efectivo', '2011-02-14 04:21:04', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(163, -1, 'contado', 'efectivo', '2011-02-14 04:55:29', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.7', 1),
(164, -1, 'contado', 'efectivo', '2011-02-14 04:56:00', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.7', 1),
(165, -1, 'contado', 'efectivo', '2011-02-14 04:59:34', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.7', 1),
(166, -1, 'contado', 'efectivo', '2011-02-14 05:00:29', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(167, -1, 'contado', 'efectivo', '2011-02-14 05:02:36', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(168, -1, 'contado', 'efectivo', '2011-02-14 05:05:04', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.7', 1),
(169, -1, 'contado', 'efectivo', '2011-02-14 05:08:55', 199, NULL, 0, 199, 1, 102, 199, 0, '192.168.1.11', 1),
(170, 3, 'credito', NULL, '2011-02-14 05:09:36', 85.5, NULL, 0, 76.095, 1, 102, 76.095, 0, '192.168.1.11', 1),
(171, 3, 'credito', NULL, '2011-02-14 05:09:54', 85.5, NULL, 0, 76.095, 1, 102, 76.095, 0, '192.168.1.11', 1),
(172, 3, 'credito', NULL, '2011-02-14 05:09:55', 85.5, NULL, 0, 76.095, 1, 102, 76.095, 0, '192.168.1.11', 1),
(173, 3, 'credito', NULL, '2011-02-14 05:09:56', 85.5, NULL, 0, 76.095, 1, 102, 4, 0, '192.168.1.11', 0),
(174, 3, 'credito', NULL, '2011-02-14 05:09:56', 85.5, NULL, 0, 76.095, 1, 102, 6.1, 0, '192.168.1.11', 0),
(175, 3, 'credito', NULL, '2011-02-14 05:10:04', 85.5, NULL, 0, 76.095, 1, 102, 0, 0, '192.168.1.11', 0),
(176, -1, 'contado', 'efectivo', '2011-02-14 05:10:32', 8.5, NULL, 0, 8.5, 1, 102, 8.5, 0, '192.168.1.7', 1),
(177, -1, 'contado', 'efectivo', '2011-02-14 05:15:07', 8.5, NULL, 0, 8.5, 1, 102, 8.5, 0, '192.168.1.7', 1),
(178, 3, 'credito', NULL, '2011-02-14 05:16:15', 20, NULL, 0, 17.8, 1, 102, 17.8, 1, '192.168.1.11', 1),
(179, -1, 'contado', 'efectivo', '2011-02-14 05:31:29', 790, NULL, 0, 790, 1, 102, 790, 1, '192.168.1.11', 1),
(180, -1, 'contado', 'efectivo', '2011-02-14 16:13:59', 11, NULL, 0, 11, 1, 102, 11, 1, '127.0.0.1', 1),
(181, -1, 'contado', 'efectivo', '2011-02-14 23:38:08', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(182, -1, 'contado', 'efectivo', '2011-02-15 14:44:35', 28, NULL, 0, 28, 1, 102, 28, 1, '192.168.1.11', 1),
(183, -1, 'contado', 'efectivo', '2011-02-15 14:44:36', 28, NULL, 0, 28, 1, 102, 28, 1, '192.168.1.11', 1),
(184, -2, 'contado', 'cheque', '2011-02-15 15:19:19', 25.5, NULL, 0, 25.5, 1, 102, 25.5, 0, '192.168.1.11', 1),
(185, -1, 'contado', 'efectivo', '2011-02-15 15:23:28', 28, NULL, 0, 28, 1, 102, 28, 0, '192.168.1.11', 1),
(186, -2, 'contado', 'efectivo', '2011-02-15 15:24:13', 25.5, NULL, 0, 25.5, 1, 102, 25.5, 0, '192.168.1.11', 1),
(187, -2, 'contado', 'efectivo', '2011-02-15 15:24:13', 25.5, NULL, 0, 25.5, 1, 102, 25.5, 0, '192.168.1.11', 1),
(188, -2, 'contado', 'efectivo', '2011-02-15 15:29:53', 25.5, NULL, 0, 25.5, 1, 102, 25.5, 0, '192.168.1.11', 1),
(189, -2, 'contado', 'efectivo', '2011-02-15 15:34:02', 25.5, NULL, 0, 25.5, 1, 102, 25.5, 0, '192.168.1.11', 1),
(190, -2, 'contado', 'efectivo', '2011-02-15 15:46:12', 68.5, NULL, 0, 68.5, 1, 102, 68.5, 0, '192.168.1.11', 1),
(191, -1, 'contado', 'efectivo', '2011-02-15 15:46:53', 28, NULL, 0, 28, 1, 102, 28, 0, '192.168.1.11', 1),
(192, -1, 'contado', 'efectivo', '2011-02-19 04:26:32', 20, NULL, 0, 20, 1, 102, 20, 0, '127.0.0.1', 1),
(193, 3, 'contado', 'efectivo', '2011-02-19 05:07:32', 11, NULL, 0, 9.79, 1, 102, 9.79, 0, '127.0.0.1', 1),
(194, -2, 'credito', NULL, '2011-02-19 16:06:29', 18.5, NULL, 0, 18.5, 1, 102, 0, 0, '127.0.0.1', 0),
(195, -2, 'credito', NULL, '2011-02-19 16:06:30', 18.5, NULL, 0, 18.5, 1, 102, 0, 0, '127.0.0.1', 0),
(196, -1, 'contado', 'efectivo', '2011-02-22 16:34:51', 13, NULL, 0, 13, 1, 102, 13, 1, '10.42.43.10', 1),
(197, -1, 'contado', 'cheque', '2011-02-22 17:28:31', 1100, NULL, 0, 1100, 1, 102, 1100, 0, '192.168.1.13', 1),
(198, 3, 'contado', 'efectivo', '2011-02-22 20:26:11', 400, NULL, 0, 356, 1, 102, 356, 0, '10.42.43.11', 1),
(199, 1, 'contado', 'efectivo', '2011-02-22 20:26:28', 9, NULL, 0, 9, 1, 102, 9, 0, '10.42.43.11', 1),
(200, -1, 'contado', 'efectivo', '2011-02-22 20:26:50', 9, NULL, 0, 9, 1, 102, 9, 0, '10.42.43.11', 1),
(201, -1, 'contado', 'efectivo', '2011-02-22 20:27:29', 8, NULL, 0, 8, 1, 102, 8, 0, '10.42.43.11', 1),
(202, 1, 'credito', NULL, '2011-02-22 20:28:21', 9, NULL, 0, 9, 1, 102, 8, 0, '10.42.43.11', 0),
(203, -1, 'contado', 'efectivo', '2011-02-23 18:31:57', 11, NULL, 0, 11, 1, 102, 11, 0, '192.168.1.11', 1),
(204, 2, 'contado', 'efectivo', '2011-02-25 14:24:03', 11, NULL, 0, 10.78, 1, 102, 10.78, 0, '127.0.0.1', 1),
(205, -1, 'contado', 'efectivo', '2011-02-28 05:17:53', 2.1065, NULL, 0, 2.1065, 1, 102, 2.1065, 0, '127.0.0.1', 1),
(206, -1, 'contado', 'efectivo', '2011-02-28 09:52:12', 75, NULL, 0, 75, 1, 102, 75, 0, '127.0.0.1', 1),
(207, -1, 'contado', 'efectivo', '2011-02-28 09:53:18', 1250, NULL, 0, 1250, 1, 102, 1250, 0, '127.0.0.1', 1),
(208, -1, 'contado', 'efectivo', '2011-02-28 09:59:53', 12500, NULL, 0, 12500, 1, 102, 12500, 0, '192.168.1.13', 1),
(209, -1, 'contado', 'efectivo', '2011-02-28 10:21:50', 12500, NULL, 0, 12500, 1, 102, 12500, 0, '192.168.1.13', 1),
(210, -1, 'contado', 'cheque', '2011-02-28 10:23:38', 1459.5, NULL, 0, 1459.5, 1, 102, 1459.5, 0, '192.168.1.13', 1),
(211, -1, 'contado', 'cheque', '2011-02-28 12:24:20', 300, NULL, 0, 300, 1, 102, 300, 0, '192.168.1.13', 1),
(212, 2, 'contado', 'efectivo', '2011-03-01 22:40:01', 1292, NULL, 0, 1266.16, 1, 102, 1266.16, 0, '127.0.0.1', 1),
(213, 2, 'contado', 'efectivo', '2011-03-02 02:55:24', 11, NULL, 0, 10.78, 1, 102, 10.78, 0, '192.168.1.11', 1),
(214, 2, 'contado', 'efectivo', '2011-03-02 02:55:25', 11, NULL, 0, 10.78, 1, 102, 10.78, 0, '192.168.1.11', 1),
(215, 2, 'contado', 'efectivo', '2011-03-02 03:09:13', 126, NULL, 0, 123.48, 1, 102, 123.48, 0, '192.168.1.11', 1),
(216, -1, 'contado', 'cheque', '2011-03-02 04:22:28', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(217, -1, 'contado', 'efectivo', '2011-03-02 18:53:10', 27.5, NULL, 0, 27.5, 1, 102, 27.5, 0, '127.0.0.1', 1),
(218, -1, 'contado', 'efectivo', '2011-03-02 18:55:54', 27.5, NULL, 0, 27.5, 1, 102, 27.5, 0, '127.0.0.1', 1),
(219, -1, 'contado', 'efectivo', '2011-03-02 19:10:58', 32, NULL, 0, 32, 1, 102, 32, 0, '127.0.0.1', 1),
(220, -1, 'contado', 'cheque', '2011-03-02 23:03:33', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(221, -1, 'contado', 'cheque', '2011-03-03 00:53:49', 115.5, NULL, 0, 115.5, 1, 102, 115.5, 0, '127.0.0.1', 1),
(222, -1, 'contado', 'cheque', '2011-03-03 00:57:31', 74, NULL, 0, 74, 1, 102, 74, 0, '127.0.0.1', 1),
(223, -1, 'contado', 'cheque', '2011-03-03 01:51:56', 1095, NULL, 0, 1095, 1, 102, 1095, 0, '192.168.1.11', 1),
(224, -1, 'contado', 'cheque', '2011-03-03 01:56:06', 128, NULL, 0, 128, 1, 102, 128, 0, '192.168.1.11', 1),
(225, -1, 'contado', 'efectivo', '2011-03-05 22:31:19', 21, NULL, 0, 21, 1, 102, 21, 0, '127.0.0.1', 1),
(226, -1, 'contado', 'cheque', '2011-03-05 22:41:20', 11, NULL, 0, 11, 1, 102, 11, 0, '127.0.0.1', 1),
(227, -1, 'contado', 'efectivo', '2011-03-06 11:37:11', 173, NULL, 0, 173, 1, 102, 173, 0, '192.168.1.11', 1),
(228, -1, 'contado', 'efectivo', '2011-03-06 11:48:40', 20, NULL, 0, 20, 1, 102, 20, 0, '192.168.1.11', 1),
(229, -1, 'contado', 'efectivo', '2011-03-06 11:49:47', 20, NULL, 0, 20, 1, 102, 20, 0, '192.168.1.11', 1),
(230, 2, 'contado', 'cheque', '2011-03-06 11:53:15', 22, NULL, 0, 21.56, 1, 102, 21.56, 0, '192.168.1.11', 1),
(231, 5, 'credito', NULL, '2011-03-06 11:57:34', 60, NULL, 0, 48, 1, 102, 0, 0, '192.168.1.11', 0),
(232, -1, 'contado', 'efectivo', '2011-03-24 17:04:48', 300, NULL, 0, 300, 1, 102, 300, 0, '127.0.0.1', 1);

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
