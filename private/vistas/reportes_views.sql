/******************************************************************
*	SCRIPT PARA GENERAR LAS VISTAS PARA LOS REPORTES Y GRAFICAS
*	
******************************************************************/




/********************************************************
*	VISTA DE VENTAS
********************************************************/


CREATE OR REPLACE VIEW view_ventas
AS SELECT `v`.`id_venta`, `c`.`nombre` AS `cliente`, `v`.`id_cliente`, `v`.`tipo_venta`, `v`.`fecha`, `v`.`subtotal`, `v`.`iva`, `s`.`descripcion` AS `sucursal`, `v`.`id_sucursal`, `u`.`nombre` AS `usuario`, `v`.`id_usuario`
FROM `ventas` AS v
INNER JOIN ( `cliente` AS c, `sucursal` AS s, `usuario` AS u)
ON  (`v`.`id_cliente` = `c`.`id_cliente`
AND `v`.`id_sucursal` = `s`.`id_sucursal`
AND `v`.`id_usuario` = `u`.`id_usuario`);



/********************************************************
*	VISTA DE COMPRAS
********************************************************/

CREATE OR REPLACE VIEW view_compras
AS SELECT `c`.`id_compra`, `p`.`nombre` AS `proveedor`, `p`.`id_proveedor`,`c`.`tipo_compra`, `c`.`fecha`, `c`.`subtotal`, `c`.`iva`, `s`.`descripcion` AS `sucursal`, `c`.`id_sucursal`, `u`.`nombre` AS `usuario`, `c`.`id_usuario`
FROM `compras` AS c
INNER JOIN ( `proveedor` AS p, `sucursal` AS s, `usuario` AS u)
ON  (`c`.`id_proveedor` = `p`.`id_proveedor`
AND `c`.`id_sucursal` = `s`.`id_sucursal`
AND `c`.`id_usuario` = `u`.`id_usuario`);


/********************************************************
*	VISTA DE GASTOS
********************************************************/

CREATE OR REPLACE VIEW view_gastos
AS SELECT `g`.`id_gasto`, `g`.`monto`, `g`.`fecha`, `s`.`descripcion` AS `sucursal`, `g`.`id_sucursal`, `u`.`nombre` AS `usuario`
FROM `gastos` AS g
INNER JOIN ( `sucursal` AS s, `usuario` AS u)
ON ( `g`.`id_sucursal` = `s`.`id_sucursal`
AND `g`.`id_usuario` = `u`.`id_usuario` );



/********************************************************
*	VISTA DE INGRESOS
********************************************************/

CREATE OR REPLACE VIEW view_ingresos
AS SELECT `i`.`id_ingreso`, `i`.`monto`, `i`.`fecha`, `s`.`descripcion` AS `sucursal`, `i`.`id_sucursal`, `u`.`nombre` AS `usuario`
FROM `ingresos` AS i
INNER JOIN ( `sucursal` AS s, `usuario` AS u)
ON ( `i`.`id_sucursal` = `s`.`id_sucursal`
AND `i`.`id_usuario` = `u`.`id_usuario` );




/********************************************************
*	VISTA DE DETALLES DE VENTA
********************************************************/

CREATE OR REPLACE VIEW view_detalle_venta
AS SELECT `d`.`id_venta`, `d`.`id_producto`,`i`.`denominacion`, `d`.`cantidad`, `d`.`precio`, `v`.`fecha`, `v`.`tipo_venta`
FROM `detalle_venta` AS d
INNER JOIN ( `inventario` AS i, `ventas` AS v)
ON ( `d`.`id_venta` = `v`.`id_venta`
AND `d`.`id_producto` = `i`.`id_producto` );


/********************************************************
*	VISTA DE DETALLES DE VENTA
********************************************************/

CREATE OR REPLACE VIEW view_detalle_compra
AS SELECT `d`.`id_compra`, `d`.`id_producto`,`i`.`denominacion`, `d`.`cantidad`, `d`.`precio`, `c`.`fecha`
FROM `detalle_compra` AS d
INNER JOIN ( `inventario` AS i, `compras` AS c)
ON ( `d`.`id_compra` = `c`.`id_compra`
AND `d`.`id_producto` = `i`.`id_producto` );

