
ALTER TABLE `detalle_compra`
 DROP FOREIGN KEY IF EXISTS  `detalle_compra`,
 DROP FOREIGN KEY IF EXISTS  `detalle_compra` ;

--
-- Filtros para la tabla `detalle_cotizacion`
--
ALTER TABLE `detalle_cotizacion`
 DROP FOREIGN KEY IF EXISTS  `detalle_cotizacion` ,
 DROP FOREIGN KEY IF EXISTS  `detalle_cotizacion` ;

--
-- Filtros para la tabla `detalle_inventario`
--
ALTER TABLE `detalle_inventario`
 DROP FOREIGN KEY IF EXISTS  `detalle_inventario`,
 DROP FOREIGN KEY IF EXISTS  `detalle_inventario`;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
 DROP FOREIGN KEY IF EXISTS  `detalle_venta`,
 DROP FOREIGN KEY IF EXISTS  `detalle_venta`;

--
-- Filtros para la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
 DROP FOREIGN KEY IF EXISTS  `factura_compra`;

--
-- Filtros para la tabla `factura_venta`
--
ALTER TABLE `factura_venta`
 DROP FOREIGN KEY IF EXISTS  `factura_venta`;

--
-- Filtros para la tabla `nota_remision`
--
ALTER TABLE `nota_remision`
 DROP FOREIGN KEY IF EXISTS  `nota_remision`;

--
-- Filtros para la tabla `pagos_compra`
--
ALTER TABLE `pagos_compra`
 DROP FOREIGN KEY IF EXISTS  `pagos_compra` ;

--
-- Filtros para la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
 DROP FOREIGN KEY IF EXISTS  `pagos_venta`;

--
-- Filtros para la tabla `productos_proveedor`
--
ALTER TABLE `productos_proveedor`
 DROP FOREIGN KEY IF EXISTS  `productos_proveedor` ,
 DROP FOREIGN KEY IF EXISTS  `productos_proveedor` ;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
 DROP FOREIGN KEY IF EXISTS  `ventas` ,
 DROP FOREIGN KEY IF EXISTS  `ventas` ,
 DROP FOREIGN KEY IF EXISTS  `ventas` ;
