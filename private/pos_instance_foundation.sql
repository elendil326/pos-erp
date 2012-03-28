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
(2, 'Consignacion');

INSERT INTO `tarifa` (`id_tarifa`, `nombre`, `tipo_tarifa`, `activa`, `id_moneda`, `default`, `id_version_default`, `id_version_activa`) VALUES
(1, 'none', 'venta', 1, 1, 1, NULL, 1);




INSERT INTO `usuario` (`id_usuario`, `id_direccion`, `id_direccion_alterna`, `id_sucursal`, `id_rol`, `id_clasificacion_cliente`, `id_clasificacion_proveedor`, `id_moneda`, `fecha_asignacion_rol`, `nombre`, `rfc`, `curp`, `comision_ventas`, `telefono_personal1`, `telefono_personal2`, `fecha_alta`, `fecha_baja`, `activo`, `limite_credito`, `descuento`, `password`, `last_login`, `consignatario`, `salario`, `correo_electronico`, `pagina_web`, `saldo_del_ejercicio`, `ventas_a_credito`, `representante_legal`, `facturar_a_terceros`, `dia_de_pago`, `mensajeria`, `intereses_moratorios`, `denominacion_comercial`, `dias_de_credito`, `cuenta_de_mensajeria`, `dia_de_revision`, `codigo_usuario`, `dias_de_embarque`, `tiempo_entrega`, `cuenta_bancaria`, `id_tarifa_compra`, `tarifa_compra_obtenida`, `id_tarifa_venta`, `tarifa_venta_obtenida`) VALUES
(1, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2011-10-24 18:28:24', 'Administrador', NULL, NULL, NULL, NULL, NULL, '2011-10-24 18:28:34', NULL, 1, 0, NULL, '202cb962ac59075b964b07152d234b70', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 0, 'rol', 0, 'rol');


INSERT INTO `version` (`id_version`, `id_tarifa`, `nombre`, `activa`, `fecha_inicio`, `fecha_fin`, `default`) VALUES
(1, 1, 'none', 1, NULL, NULL, 1);

INSERT INTO `moneda` (`id_moneda`, `nombre`, `simbolo`, `activa`) VALUES ('1', 'Peso', 'MXN', '1');
