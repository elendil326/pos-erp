INSERT INTO `usuario` (`id_usuario`, `RFC`, `nombre`, `contrasena`, `id_sucursal`, `activo`, `finger_token`, `salario`, `direccion`, `telefono`, `fecha_inicio`) VALUES
(77, 'GOHE39874', 'Alan Gonzalez Hernandez', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 0, '0', '0', '2011-01-07 01:12:29');

INSERT INTO `grupos` (`id_grupo`, `nombre`, `descripcion`) VALUES
(0, 'Ingeniero', 'Ingeniero'),
(1, 'Administrador', 'Administrador'),
(2, 'Gerente', 'Gerente'),
(3, 'Cajero', 'Cajero');

INSERT INTO `grupos_usuarios` (`id_grupo`, `id_usuario`) VALUES
(0, 77);


INSERT INTO `sucursal` (`id_sucursal`, `gerente`, `descripcion`, `direccion`, `rfc`, `telefono`, `token`, `letras_factura`, `activo`, `fecha_apertura`, `saldo_a_favor`) VALUES 
( 0, NULL, 'CENTRO DE DISTRIBUCION', '', NULL, NULL, NULL, 'A', '1', CURRENT_TIMESTAMP, '0');