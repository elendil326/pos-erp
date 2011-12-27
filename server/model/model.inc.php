<?php
/* Todos los includes de este sitema */

require_once ('Estructura.php');
require_once ('abasto_proveedor.dao.php');
require_once ('abono_compra.dao.php');
require_once ('abono_prestamo.dao.php');
require_once ('abono_venta.dao.php');
require_once ('almacen.dao.php');
require_once ('apertura_caja.dao.php');
require_once ('autorizacion.dao.php');
require_once ('billete.dao.php');
require_once ('billete_apertura_caja.dao.php');
require_once ('billete_caja.dao.php');
require_once ('billete_cierre_caja.dao.php');
require_once ('billete_corte_caja.dao.php');
require_once ('caja.dao.php');
require_once ('cheque.dao.php');
require_once ('cheque_abono_compra.dao.php');
require_once ('cheque_abono_prestamo.dao.php');
require_once ('cheque_abono_venta.dao.php');
require_once ('cheque_compra.dao.php');
require_once ('cheque_venta.dao.php');
require_once ('cierre_caja.dao.php');
require_once ('ciudad.dao.php');
require_once ('clasificacion_cliente.dao.php');
require_once ('clasificacion_producto.dao.php');
require_once ('clasificacion_proveedor.dao.php');
require_once ('clasificacion_servicio.dao.php');
require_once ('compra.dao.php');
require_once ('compra_arpilla.dao.php');
require_once ('compra_producto.dao.php');
require_once ('concepto_gasto.dao.php');
require_once ('concepto_ingreso.dao.php');
require_once ('consignacion.dao.php');
require_once ('consignacion_producto.dao.php');
require_once ('corte_de_caja.dao.php');
require_once ('devolucion_sobre_compra.dao.php');
require_once ('devolucion_sobre_venta.dao.php');
require_once ('direccion.dao.php');
require_once ('documento.dao.php');
require_once ('documento_cliente.dao.php');
require_once ('documento_compra.dao.php');
require_once ('documento_venta.dao.php');
require_once ('empresa.dao.php');
require_once ('entrada_almacen.dao.php');
require_once ('estado.dao.php');
require_once ('gasto.dao.php');
require_once ('impresora.dao.php');
require_once ('impresora_caja.dao.php');
require_once ('impuesto.dao.php');
require_once ('impuesto_clasificacion_cliente.dao.php');
require_once ('impuesto_clasificacion_producto.dao.php');
require_once ('impuesto_clasificacion_proveedor.dao.php');
require_once ('impuesto_clasificacion_servicio.dao.php');
require_once ('impuesto_empresa.dao.php');
require_once ('impuesto_producto.dao.php');
require_once ('impuesto_servicio.dao.php');
require_once ('impuesto_sucursal.dao.php');
require_once ('impuesto_usuario.dao.php');
require_once ('ingreso.dao.php');
require_once ('inspeccion_consignacion.dao.php');
require_once ('inspeccion_consignacion_producto.dao.php');
require_once ('moneda.dao.php');
require_once ('orden_de_servicio.dao.php');
require_once ('orden_de_servicio_paquete.dao.php');
require_once ('paquete.dao.php');
require_once ('paquete_empresa.dao.php');
require_once ('paquete_sucursal.dao.php');
require_once ('permiso.dao.php');
require_once ('permiso_rol.dao.php');
require_once ('permiso_usuario.dao.php');
require_once ('precio_paquete_rol.dao.php');
require_once ('precio_paquete_tipo_cliente.dao.php');
require_once ('precio_paquete_usuario.dao.php');
require_once ('precio_producto_rol.dao.php');
require_once ('precio_producto_tipo_cliente.dao.php');
require_once ('precio_producto_usuario.dao.php');
require_once ('precio_servicio_rol.dao.php');
require_once ('precio_servicio_tipo_cliente.dao.php');
require_once ('precio_servicio_usuario.dao.php');
require_once ('prestamo.dao.php');
require_once ('producto.dao.php');
require_once ('producto_abasto_proveedor.dao.php');
require_once ('producto_almacen.dao.php');
require_once ('producto_clasificacion.dao.php');
require_once ('producto_empresa.dao.php');
require_once ('producto_entrada_almacen.dao.php');
require_once ('producto_orden_de_servicio.dao.php');
require_once ('producto_paquete.dao.php');
require_once ('producto_salida_almacen.dao.php');
require_once ('reporte.dao.php');
require_once ('retencion.dao.php');
require_once ('retencion_clasificacion_cliente.dao.php');
require_once ('retencion_clasificacion_producto.dao.php');
require_once ('retencion_clasificacion_proveedor.dao.php');
require_once ('retencion_clasificacion_servicio.dao.php');
require_once ('retencion_empresa.dao.php');
require_once ('retencion_producto.dao.php');
require_once ('retencion_servicio.dao.php');
require_once ('retencion_sucursal.dao.php');
require_once ('retencion_usuario.dao.php');
require_once ('rol.dao.php');
require_once ('salida_almacen.dao.php');
require_once ('seguimiento_de_servicio.dao.php');
require_once ('servicio.dao.php');
require_once ('servicio_clasificacion.dao.php');
require_once ('servicio_empresa.dao.php');
require_once ('servicio_sucursal.dao.php');
require_once ('sesion.dao.php');
require_once ('sucursal.dao.php');
require_once ('sucursal_empresa.dao.php');
require_once ('tipo_almacen.dao.php');
require_once ('traspaso.dao.php');
require_once ('traspaso_producto.dao.php');
require_once ('unidad.dao.php');
require_once ('unidad_equivalencia.dao.php');
require_once ('usuario.dao.php');
require_once ('venta.dao.php');
require_once ('venta_arpilla.dao.php');
require_once ('venta_empresa.dao.php');
require_once ('venta_orden.dao.php');
require_once ('venta_paquete.dao.php');
require_once ('venta_producto.dao.php');
