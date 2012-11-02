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


