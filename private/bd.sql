 Servidor: localhost     Base de datos: pos
 

#aqui van las llaves foraneas
#tabla compras
ALTER TABLE compras DROP FOREIGN KEY compras_proveedor;

alter table compras
add CONSTRAINT compras_proveedor FOREIGN KEY (id_proveedor)
REFERENCES  proveedores(id_proveedor);

ALTER TABLE compras DROP FOREIGN KEY compras_sucursal;

alter table compras
add CONSTRAINT compras_sucursal FOREIGN KEY (sucursal)
REFERENCES  sucursal(id_sucursal);

ALTER TABLE compras DROP FOREIGN KEY compras_usuario;

alter table compras
add CONSTRAINT compras_usuario FOREIGN KEY (id_usuario)
REFERENCES  usuario(id_usuario);

# tabla cotizacion

ALTER TABLE cotizacion DROP FOREIGN KEY cotizacion_cliente;

alter table cotizacion
add CONSTRAINT cotizacion_cliente FOREIGN KEY (id_cliente)
REFERENCES  cliente(id_cliente);

#tabla cuenta_cliente

ALTER TABLE cuenta_cliente  DROP FOREIGN KEY cuenta_de_cliente;

alter table cuenta_cliente
add CONSTRAINT cuenta_de_cliente FOREIGN KEY (id_cliente)
REFERENCES  cliente(id_cliente);

#tabla cuenta proveedor

ALTER TABLE cuenta_proveedor DROP FOREIGN KEY cuenta_de_proveedor;

alter table cuenta_proveedor
add CONSTRAINT cuenta_de_proveedor FOREIGN KEY (id_proveedor)
REFERENCES  proveedor(id_proveedor);

#tabla detalle compra

ALTER TABLE detalle_compra DROP FOREIGN KEY detalle_compra_compra;

alter table detalle_compra
add CONSTRAINT detalle_compra_compra FOREIGN KEY (id_compra)
REFERENCES  compras(id_compra);

ALTER TABLE detalle_compra DROP FOREIGN KEY detalle_compra_producto;

alter table detalle_compra
add CONSTRAINT detalle_compra_producto FOREIGN KEY (id_producto)
REFERENCES  productos_proveedor(id_producto);

#tabla detalle cotizacion
ALTER TABLE detalle_cotizacion DROP FOREIGN KEY detalle_cotizacion_cotizacion;

alter table detalle_cotizacion
add CONSTRAINT detalle_cotizacion_cotizacion FOREIGN KEY (id_cotizacion)
REFERENCES cotizacion(id_cotizacion);

ALTER TABLE detalle_cotizacion DROP FOREIGN KEY detalle_cotizacion_producto;

alter table detalle_cotizacion
add CONSTRAINT detalle_cotizacion_producto FOREIGN KEY (id_producto)
REFERENCES  inventario(id_producto);

#tabla detalle venta
ALTER TABLE detalle_venta DROP FOREIGN KEY detalle_venta_venta;

alter table detalle_venta
add CONSTRAINT detalle_venta_venta FOREIGN KEY (id_venta)
REFERENCES  ventas(id_venta);

ALTER TABLE detalle_venta DROP FOREIGN KEY detalle_venta_producto;

alter table detalle_venta
add CONSTRAINT detalle_venta_producto FOREIGN KEY (id_producto)
REFERENCES  inventario(id_producto);


#tabla factura compra

ALTER TABLE factura_compra DROP FOREIGN KEY factura_compra_compra;

alter table factura_compra
add CONSTRAINT factura_compra_compra FOREIGN KEY (id_compra)
REFERENCES  compras(id_compra);

#tabla factura venta

ALTER TABLE factura_venta DROP FOREIGN KEY factura_venta_venta;

alter table factura_venta
add CONSTRAINT factura_venta_venta FOREIGN KEY (id_venta)
REFERENCES  ventas(id_venta);

#tabla detalle_inventario

ALTER TABLE detalle_inventario DROP FOREIGN KEY inventario_producto;

alter table detalle_inventario
add CONSTRAINT inventario_producto FOREIGN KEY (id_producto)
REFERENCES  inventario(id_producto);

alter table detalle_inventario
add CONSTRAINT inventario_sucursal FOREIGN KEY (sucursal)
REFERENCES  sucursal(id_sucursal);

#tabla nota_remision

ALTER TABLE nota_remision DROP FOREIGN KEY nota_remision_venta;

alter table nota_remision
add CONSTRAINT nota_remision_venta FOREIGN KEY (id_venta)
REFERENCES  ventas(id_venta);

#tabla pagos_compra
ALTER TABLE pagos_compra DROP FOREIGN KEY pagos_compra_compra ;

alter table pagos_compra
add CONSTRAINT pagos_compra_compra FOREIGN KEY (id_compra)
REFERENCES  compras(id_compra);

#tabla pagos_venta

ALTER TABLE pagos_venta DROP FOREIGN KEY pagos_venta_venta;

alter table pagos_venta
add CONSTRAINT pagos_venta_venta FOREIGN KEY (id_venta)
REFERENCES  ventas(id_venta);

#tabla productos_proveedor

ALTER TABLE productos_proveedor DROP FOREIGN KEY productos_proveedor_proveedor;

alter table productos_proveedor
add CONSTRAINT productos_proveedor_proveedor FOREIGN KEY (id_proveedor)
REFERENCES  proveedor(id_proveedor);

ALTER TABLE productos_proveedor DROP FOREIGN KEY productos_proveedor_producto;

alter table productos_proveedor
add CONSTRAINT productos_proveedor_producto FOREIGN KEY (id_inventario)
REFERENCES  inventario(id_producto);

#tabla ventas


ALTER TABLE ventas DROP FOREIGN KEY ventas_cliente;

alter table ventas
add CONSTRAINT ventas_cliente  FOREIGN KEY (id_cliente)
REFERENCES  clientes(id_cliente);

ALTER TABLE ventas DROP FOREIGN KEY ventas_sucursal;

alter table ventas
add CONSTRAINT ventas_sucursal FOREIGN KEY (sucursal)
REFERENCES  sucursal(id_sucursal);

ALTER TABLE ventas DROP FOREIGN KEY ventas_usuario;

alter table ventas
add CONSTRAINT ventas_usuario FOREIGN KEY (id_usuario)
REFERENCES  usuario(id_usuario);


#detalle inventarios

ALTER TABLE detalle_inventario DROP FOREIGN KEY detalle_inventario_producto;
alter table detalle_inventario
add CONSTRAINT detalle_inventario_producto FOREIGN KEY (id_producto)
REFERENCES  inventario(id_producto);

ALTER TABLE detalle_inventario DROP FOREIGN KEY detalle_inventario_sucursal;
alter table detalle_inventario
add CONSTRAINT detalle_inventario_sucursal FOREIGN KEY (sucursal)
REFERENCES  sucursal(id_sucursal);


