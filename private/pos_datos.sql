-- phpMyAdmin SQL Dump
-- version 3.3.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 29, 2010 at 10:19 PM
-- Server version: 5.0.51
-- PHP Version: 5.3.3-0.dotdeb.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `pos`
--

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
(11, -1, 1, '2010-12-26 01:14:36', '2010-12-26 01:14:36', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"cantidad":"1"}]}'),
(13, -1, 1, '2010-12-28 17:26:58', '2010-12-28 17:26:58', 3, '{"clave":"209","descripcion":"Envio de productos","productos":[{"id_producto":1,"cantidad":"100"}]}');

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `rfc`, `nombre`, `direccion`, `ciudad`, `telefono`, `e_mail`, `limite_credito`, `descuento`, `activo`, `id_usuario`, `id_sucursal`, `fecha_ingreso`) VALUES
(-2, '234234324', 'Caja Comun', 'asdfasdfsadf', '', 'asdfasdf', '', 0, 0, 1, -1, 2, '2010-12-25 09:03:09'),
(-1, 'ARBOL2349237', 'Caja Comun', 'Arboledas 2da Secc', '', '6149974', '', 0, 0, 1, -1, 1, '2010-12-20 01:00:28'),
(1, 'AGH6666543', 'ALAN GONZAEZ HERNANDEZ', 'Monte Balcanes #107, 2Da Seccion Arboledas', 'CELAYA', '1741449', 'ALABOY', 0, 0, 1, 2, 1, '2010-12-20 08:12:23');

--
-- Dumping data for table `compras`
--


--
-- Dumping data for table `corte`
--


--
-- Dumping data for table `detalle_compra`
--


--
-- Dumping data for table `detalle_inventario`
--

INSERT INTO `detalle_inventario` (`id_producto`, `id_sucursal`, `precio_venta`, `min`, `existencias`) VALUES
(1, 1, 9.5, 100, 494);

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
(64, 1, 1, 9.5),
(65, 1, 1, 9.5),
(66, 1, 1, 9.5);

--
-- Dumping data for table `equipo`
--

INSERT INTO `equipo` (`id_equipo`, `token`, `full_ua`) VALUES
(1, 'X8s5z', 'sid={X8s5z} (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10'),
(2, 'asdf', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; es-ES) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5'),
(3, 'asdfsdaf', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10'),
(4, '0000', 'Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');

--
-- Dumping data for table `equipo_sucursal`
--

INSERT INTO `equipo_sucursal` (`id_equipo`, `id_sucursal`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1);

--
-- Dumping data for table `factura_compra`
--


--
-- Dumping data for table `factura_venta`
--

INSERT INTO `factura_venta` (`folio`, `id_venta`) VALUES
(1, 59);

--
-- Dumping data for table `gastos`
--

INSERT INTO `gastos` (`id_gasto`, `folio`, `concepto`, `monto`, `fecha`, `fecha_ingreso`, `id_sucursal`, `id_usuario`, `nota`) VALUES
(1, 'RE', 'agua', 55, '2010-12-24 02:25:37', '2010-12-28 06:00:00', 1, 2, 'JUMAPA'),
(2, 'TEXSERU', 'telefono', 52.55, '2010-12-24 02:48:04', '2010-12-04 06:00:00', 1, 2, '');

--
-- Dumping data for table `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `nombre`, `descripcion`) VALUES
(0, 'inge', 'inge'),
(1, 'admin', 'Administrador del Sistema'),
(2, 'gerente', 'Gerente'),
(3, 'cajero', 'maneja las cajas'),
(4, 'limpieza', 'maneja la limpieza'),
(5, 'contador', 'maneja los dineros'),
(6, 'audi', 'maneja el audi de la empresa');

--
-- Dumping data for table `grupos_usuarios`
--

INSERT INTO `grupos_usuarios` (`id_grupo`, `id_usuario`) VALUES
(0, 7),
(0, 8),
(1, 1),
(2, 2),
(2, 4),
(2, 5),
(3, 3);

--
-- Dumping data for table `ingresos`
--

INSERT INTO `ingresos` (`id_ingreso`, `concepto`, `monto`, `fecha`, `fecha_ingreso`, `id_sucursal`, `id_usuario`, `nota`) VALUES
(202, 'PRESTAMO', 50, '2010-12-24 02:31:56', '2010-12-13 06:00:00', 1, 2, '');

--
-- Dumping data for table `inventario`
--

INSERT INTO `inventario` (`id_producto`, `descripcion`, `precio_intersucursal`, `costo`, `medida`) VALUES
(1, 'papas fritas 330', 7.5, 7.5, 'unidad');

--
-- Dumping data for table `pagos_compra`
--


--
-- Dumping data for table `pagos_venta`
--


--
-- Dumping data for table `productos_proveedor`
--


--
-- Dumping data for table `proveedor`
--


--
-- Dumping data for table `sucursal`
--

INSERT INTO `sucursal` (`id_sucursal`, `gerente`, `descripcion`, `direccion`, `rfc`, `telefono`, `token`, `letras_factura`, `activo`, `fecha_apertura`, `saldo_a_favor`) VALUES
(1, 0, 'Sucursal Arboledas', 'Arboledas 2da Secc, Celaya', '123', '4611741449', '192.168.1.11', 'D', 0, '2010-12-20 01:00:28', 0),
(2, 0, 'asdfasdfsdaf', 'asdfasdfsadf', '234234324', 'asdfasdf', NULL, 'b', 0, '2010-12-25 09:03:09', 0);

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `RFC`, `nombre`, `contrasena`, `id_sucursal`, `activo`, `finger_token`, `salario`, `direccion`, `telefono`, `fecha_inicio`) VALUES
(1, '12213324432', 'Alan Gonzalez Hernandez', '912ec803b2ce49e4a541068d495ab570', NULL, 1, NULL, 0, '', '', '2010-12-20 00:54:42'),
(2, 'SP4598347597', 'Sancho Pansa', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 1000, 'Oliva #123', '6149974', '2010-12-20 00:00:00'),
(3, 'AMR3345399', 'ALANISS MORRISETTE', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, NULL, 500, 'IUYTREWQASDFG66', '', '2010-12-22 00:00:00'),
(4, 'asdfasdf', 'asdfasdf', '202cb962ac59075b964b07152d234b70', NULL, 0, NULL, 1234, 'asdfasdfasdf', 'asdfasdf', '2010-12-25 00:00:00'),
(5, 'JCV8979873', 'Jose Cardenaz Vilar', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 500, 'Monte Tauro #123 Col. Centro, Celaya', '234234234', '2010-12-25 00:00:00'),
(7, 'adfasdf', 'asdf', '912ec803b2ce49e4a541068d495ab570', NULL, 1, NULL, 0, '0', '0', '2010-12-28 00:00:00'),
(8, 'AGH', 'Alan Gonzalez', 'da54dd5a0398011cdfa50d559c2c0ef8', NULL, 1, NULL, 0, '0', '0', '2010-12-28 00:00:00');

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
(64, -1, 'contado', '2010-12-24 04:26:37', 9.5, NULL, 0, 9.5, 1, 2, 9.5, '192.168.1.67'),
(65, -1, 'contado', '2010-12-27 19:44:09', 9.5, NULL, 0, 9.5, 1, 2, 9.5, '127.0.0.1'),
(66, -1, 'contado', '2010-12-28 17:24:02', 9.5, NULL, 0, 9.5, 1, 2, 9.5, '192.168.1.4');

