SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


INSERT INTO `ciudad` (`id_ciudad`, `id_estado`, `nombre`) VALUES
(1, 11, 'Abasolo'),
(2, 11, 'Acambaro'),
(3, 11, 'Apaseo el Alto'),
(4, 11, 'Apaseo el Grande'),
(5, 11, 'Atarjea'),
(6, 11, 'Celaya'),
(7, 11, 'Comonfort'),
(8, 11, 'Coroneo'),
(9, 11, 'Cortazar'),
(10, 11, 'Cueramaro'),
(11, 11, 'Doctor Mora'),
(12, 11, 'Dolores Hidalgo'),
(13, 11, 'Guanajuato'),
(14, 11, 'Huanimaro'),
(15, 11, 'Irapuato'),
(16, 11, 'Jaral del Progreso'),
(17, 11, 'Jerecuaro'),
(18, 11, 'Leon'),
(19, 11, 'Manuel Doblado'),
(20, 11, 'Moroleon'),
(21, 11, 'Ocampo'),
(22, 11, 'Penjamo'),
(23, 11, 'Pueblo Nuevo'),
(25, 11, 'Romita'),
(26, 11, 'Salamanca'),
(27, 11, 'Salvatierra'),
(28, 11, 'San Diego de la Union'),
(29, 11, 'San Felipe'),
(30, 11, 'San Francisco del Rincon');

INSERT INTO `clasificacion_cliente` (`id_clasificacion_cliente`, `clave_interna`, `nombre`, `descripcion`, `id_tarifa_compra`, `id_tarifa_venta`) VALUES
(1, 'General', 'General', NULL, 2, 1);



INSERT INTO `rol` (`id_rol`, `nombre`, `descripcion`, `salario`, `id_tarifa_compra`, `id_tarifa_venta`) VALUES
(0, 'Administrador', 'Administrador del sistema', NULL, 0, 0),
(1, 'Socio', 'Socio mayoritario', NULL, 0, 0),
(2, 'Gerente', 'Gerentes de sucursales', NULL, 0, 0),
(3, 'Asistente', 'Asistentes de', NULL, 0, 0),
(5, 'Cliente', 'Clientes', NULL, 0, 0);



INSERT INTO `tipo_almacen` (`id_tipo_almacen`, `descripcion`) VALUES
(2, 'CONSIGNACION'),
(3, 'GENERAL');

INSERT INTO `tarifa` (`id_tarifa`, `nombre`, `tipo_tarifa`, `activa`, `id_moneda`, `default`, `id_version_default`, `id_version_activa`) VALUES
(1, 'none', 'venta', 1, 1, 1, NULL, 1);




INSERT INTO `usuario` (`id_usuario`, `id_direccion`, `id_direccion_alterna`, `id_sucursal`, `id_rol`, `id_clasificacion_cliente`, `id_clasificacion_proveedor`, `id_moneda`, `fecha_asignacion_rol`, `nombre`, `rfc`, `curp`, `comision_ventas`, `telefono_personal1`, `telefono_personal2`, `fecha_alta`, `fecha_baja`, `activo`, `limite_credito`, `descuento`, `password`, `last_login`, `consignatario`, `salario`, `correo_electronico`, `pagina_web`, `saldo_del_ejercicio`, `ventas_a_credito`, `representante_legal`, `facturar_a_terceros`, `dia_de_pago`, `mensajeria`, `intereses_moratorios`, `denominacion_comercial`, `dias_de_credito`, `cuenta_de_mensajeria`, `dia_de_revision`, `codigo_usuario`, `dias_de_embarque`, `tiempo_entrega`, `cuenta_bancaria`, `id_tarifa_compra`, `tarifa_compra_obtenida`, `id_tarifa_venta`, `tarifa_venta_obtenida`) VALUES
(1, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2011-10-24 18:28:24', 'Administrador', NULL, NULL, NULL, NULL, NULL, '2011-10-24 18:28:34', NULL, 1, 0, NULL, '202cb962ac59075b964b07152d234b70', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 0, 'rol', 0, 'rol');


INSERT INTO `version` (`id_version`, `id_tarifa`, `nombre`, `activa`, `fecha_inicio`, `fecha_fin`, `default`) VALUES
(1, 1, 'none', 1, NULL, NULL, 1);

INSERT INTO `moneda` (`id_moneda`, `nombre`, `simbolo`, `activa`) VALUES ('1', 'Peso', 'MXN', '1');


INSERT INTO `categoria_unidad_medida` (`id_categoria_unidad_medida`, `descripcion`, `activa`) VALUES
(1, 'Longitud', 1),
(2, 'Masa', 1),
(3, 'Tiempo', 1),
(4, 'Unidad', 1);

INSERT INTO `unidad_medida` (`id_unidad_medida`, `id_categoria_unidad_medida`, `descripcion`, `abreviacion`, `tipo_unidad_medida`, `factor_conversion`, `activa`) VALUES
(1, 1, 'METRO', 'M', '', 1, 1),
(2, 1, 'CENTIMETRO', 'CM', '', 0.01, 1),
(3, 1, 'MILIMETRO', 'MM', '', 0.001, 1),
(4, 2, 'KILOGRAMO', 'KG', '', 1, 1),
(5, 2, 'GRAMO', 'G', '', 0.001, 1),
(6, 4, 'UNIDAD', 'U', '', 1, 1),
(7, 4, 'MILLAR', 'MIL', '', 0.001, 1);

INSERT INTO `documento_base` (`id_documento_base`, `id_empresa`, `id_sucursal`, `nombre`, `activo`, `json_impresion`, `ultima_modificacion`) VALUES
(1, NULL, NULL, 'Inventario Fisico', 1, '', 1344036739),
(3, NULL, NULL, 'Cotización', 1, '', 1344037703),
(4, NULL, NULL, 'Factura', 1, '', 1344037703),
(5, NULL, NULL, 'Devolución sobre Venta', 1, '', 1344037703),
(6, NULL, NULL, 'Pago del Cliente', 1, '', 1344037703),
(7, NULL, NULL, 'Compra', 1, '', 1344037703),
(8, NULL, NULL, 'Abono del Cliente', 1, '', 1344037703),
(9, NULL, NULL, 'Entrada Almacén', 1, '', 1344037703),
(10, NULL, NULL, 'Salida Almacén', 1, '', 1344037703),
(11, NULL, NULL, 'Nota de Venta', 1, '', 1344037703),
(12, NULL, NULL, 'Devolución sobre Compra', 1, '', 1344037703);

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id_configuracion`, `descripcion`, `valor`, `id_usuario`, `fecha`) VALUES
(1, 'decimales', '{"cantidades" : 2, "cambio" : 4, "costos" : 2, "ventas" : 2}', 1, 1354911045);

--
-- Volcado de datos para la tabla `logo`
--

INSERT INTO `logo` (`id_logo`, `imagen`, `tipo`) VALUES
(-1, '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', 'png');