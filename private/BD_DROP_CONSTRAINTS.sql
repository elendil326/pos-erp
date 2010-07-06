
ALTER TABLE `detalle_compra`
 DROP FOREIGN KEY IF EXISTS  `detalle_compra_ibfk_1`,
 DROP FOREIGN KEY IF EXISTS  `detalle_compra_ibfk_2` ;

--
-- Filtros para la tabla `detalle_cotizacion`
--
ALTER TABLE `detalle_cotizacion`
 DROP FOREIGN KEY IF EXISTS  `detalle_cotizacion_ibfk_1` ,
 DROP FOREIGN KEY IF EXISTS  `detalle_cotizacion_ibfk_2` ;

--
-- Filtros para la tabla `detalle_inventario`
--
ALTER TABLE `detalle_inventario`
 DROP FOREIGN KEY IF EXISTS  `detalle_inventario_ibfk_1`,
 DROP FOREIGN KEY IF EXISTS  `detalle_inventario_ibfk_2`;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
 DROP FOREIGN KEY IF EXISTS  `detalle_venta_ibfk_1`,
 DROP FOREIGN KEY IF EXISTS  `detalle_venta_ibfk_2`;

--
-- Filtros para la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
 DROP FOREIGN KEY IF EXISTS  `factura_compra_ibfk_1`;

--
-- Filtros para la tabla `factura_venta`
--
ALTER TABLE `factura_venta`
 DROP FOREIGN KEY IF EXISTS  `factura_venta_ibfk_1`;

--
-- Filtros para la tabla `nota_remision`
--
ALTER TABLE `nota_remision`
 DROP FOREIGN KEY IF EXISTS  `nota_remision_ibfk_1`;

--
-- Filtros para la tabla `pagos_compra`
--
ALTER TABLE `pagos_compra`
 DROP FOREIGN KEY IF EXISTS  `pagos_compra_ibfk_1` ;

--
-- Filtros para la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
 DROP FOREIGN KEY IF EXISTS  `pagos_venta_ibfk_1`;

--
-- Filtros para la tabla `productos_proveedor`
--
ALTER TABLE `productos_proveedor`
 DROP FOREIGN KEY IF EXISTS  `productos_proveedor_ibfk_1` ,
 DROP FOREIGN KEY IF EXISTS  `productos_proveedor_ibfk_2` ;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
 DROP FOREIGN KEY IF EXISTS  `ventas_ibfk_1` ,
 DROP FOREIGN KEY IF EXISTS  `ventas_ibfk_2` ,
 DROP FOREIGN KEY IF EXISTS  `ventas_ibfk_3` ;
