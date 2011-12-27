<?php


class GerenciaComponentPage extends StdComponentPage{

	private $permisos_controller;
	private $main_menu_json;




	function __construct()
	{

		parent::__construct();

		//check for user login status
		if(SesionController::isLoggedIn() === FALSE){
			die(header("Location: ../"));
		}
		
		$this->createMainMenu();
		
		return;
		
	}//__construct





	/**
	 *
	 *	Crear los menues gracias a un json,
	 *  en un futuro este json puede
	 *  estar en la base de datos y ser diferente 
	 *  para cada usuario ! nice ! 
	 **/
	private function createMainMenu()
	{
		$this->main_menu_json = '
				{
    "main_menu": [
        {
            "title": "Autorizaciones",
            "url": "autorizaciones.php",
            "children": [
                {
                    "title": "Editar cliente",
                    "url": "autorizaciones.editar.cliente.php"
                },
                {
                    "title": "Devolucion de compra",
                    "url": "autorizaciones.devolucion.compra.php"
                },
                {
                    "title": "Detalle",
                    "url": "autorizaciones.detalle.php"
                },
                {
                    "title": "Editar",
                    "url": "autorizaciones.editar.php"
                },
                {
                    "title": "Editar precio de cliente",
                    "url": "autorizaciones.editar.precio.cliente.php"
                },
                {
                    "title": "Gasto",
                    "url": "autorizaciones.gasto.php"
                },
                {
                    "title": "Lista",
                    "url": "autorizaciones.lista.php"
                },
                {
                    "title": "Responder",
                    "url": "autorizaciones.responder.php"
                },
                {
                    "title": "Solicitar producto",
                    "url": "autorizaciones.solicitar.producto.php"
                },
                {
                    "title": "Devolucion de venta",
                    "url": "autorizaciones.devolucion.venta.php"
                }
                
            ]
        },
        {
            "title": "Cargos_y_abonos",
            "url": "cargos_y_abonos.php",
            "children": [
                {
                    "title": "Lista abono",
                    "url": "cargos_y_abonos.lista.abono.php"
                },
                {
                    "title": "Nuevo abono",
                    "url": "cargos_y_abonos.nuevo.abono.php"
                },
                {
                    "title": "Lista concepto de gasto",
                    "url": "cargos_y_abonos.lista.concepto.gasto.php"
                },
                {
                    "title": "Nuevo concepto de gasto",
                    "url": "cargos_y_abonos.nuevo.concepto.gasto.php"
                },
                {
                    "title": "Lista gasto",
                    "url": "cargos_y_abonos.lista.gasto.php"
                },
                {
                    "title": "Nuevo gasto",
                    "url": "cargos_y_abonos.nuevo.gasto.php"
                },
                {
                    "title": "Lista concepto de ingreso",
                    "url": "cargos_y_abonos.lista.concepto.ingreso.php"
                },
                {
                    "title": "Nuevo concepto de ingreso",
                    "url": "cargos_y_abonos.nuevo.concepto.ingreso.php"
                },
                {
                    "title": "Lista ingreso",
                    "url": "cargos_y_abonos.lista.ingreso.php"
                },
                {
                    "title": "Nuevo ingreso",
                    "url": "cargos_y_abonos.nuevo.ingreso.php"
                }
            ]
        },
        {
            "title": "Clientes",
            "url": "clientes.lista.php",
            "children" : [
                {
            		"title" : "Clasificacion de clientes",
            		"url"   : "clientes.lista.clasificacion.php"
            	},
                {
            		"title" : "Nueva clasificacion",
            		"url"   : "clientes.nueva.clasificacion.php"
            	},
                {
            		"title" : "Lista de clientes",
            		"url"   : "clientes.lista.php"
            	},
            	{
            		"title" : "Nuevo cliente",
            		"url"   : "clientes.nuevo.php"
            	}
            ]
        },
        {
            "title": "Compras",
            "url": "compras.php",
            "children": [
                {
                        "title" : "Cancelar",
                        "url"   : "compras.cancelar.php"
                },
                {
                        "title" : "Detalle",
                        "url"   : "compras.detalle.php"
                },
                {
                        "title" : "Detalle de compra en arpillas",
                        "url"   : "compras.detalle.compra.arpilla.php"
                },
                {
                        "title" : "Lista",
                        "url"   : "compras.lista.php"
                },
                {
                        "title" : "Nueva",
                        "url"   : "compras.nueva.php"
                },
                {
                        "title" : "Nueva compra en arpillas",
                        "url"   : "compras.nueva.compra.arpilla.php"
                }
            ]
        },
        {
            "title": "Consignaciones",
            "url": "consignaciones.php",
            "children": [
                {
                        "title" : "Desactivar consignatario",
                        "url"   : "consignaciones.desactivar.consignatario.php"
                },
                {
                        "title" : "Nuevo consignatario",
                        "url"   : "consignaciones.nuevo.consignatario.php"
                },
                {
                        "title" : "Editar",
                        "url"   : "consignaciones.editar.consignatario.php"
                },
                {
                        "title" : "Abonar a inspeccion",
                        "url"   : "consignaciones.abonar.inspeccion.php"
                },
                {
                        "title" : "Cambiar fecha de inspeccion",
                        "url"   : "consignaciones.cambiar_fecha.inspeccion.php"
                },
                {
                        "title" : "Cancelar inspeccion",
                        "url"   : "consignaciones.cancelar.inspeccion.php"
                },
                {
                        "title" : "Nueva inspeccion",
                        "url"   : "consignaciones.nueva.inspeccion.php"
                },
                {
                        "title" : "Registrar inspeccion",
                        "url"   : "consignaciones.registrar.inspeccion.php"
                },
                {
                        "title" : "Lista",
                        "url"   : "consignaciones.lista.php"
                },
                {
                        "title" : "Nueva",
                        "url"   : "consignaciones.nueva.php"
                },
                {
                        "title" : "Terminar",
                        "url"   : "consignaciones.terminar.php"
                }
            ]
        },
        {
            "title": "Contabilidad",
            "url": "contabilidad.php",
            "children": [
                {
                        "title" : "Lista de facturas",
                        "url"   : "contabilidad.lista.facturas.php"
                }
            ]
        },
        {
            "title": "Documentos",
            "url": "documentos.php",
            "children": [
                {
                        "title" : "Editar",
                        "url"   : "documentos.editar.php"
                },
                {
                        "title" : "Imprimir estado de cuenta",
                        "url"   : "documentos.imprimir.estado.cuenta.php"
                },
                {
                        "title" : "Cancelar factura",
                        "url"   : "documentos.cancelar.factura.php"
                },
                {
                        "title" : "Generar factura",
                        "url"   : "documentos.generar.factura.php"
                },
                {
                        "title" : "Imprimir factura",
                        "url"   : "documentos.imprimir.factura.php"
                },
                {
                        "title" : "Imprimir XML de factura",
                        "url"   : "documentos.imprimir_xml.factura.php"
                },
                {
                        "title" : "Lista",
                        "url"   : "documentos.lista.php"
                },
                {
                        "title" : "Imprimir nota de venta",
                        "url"   : "documentos.imprimir.nota_de_venta.php"
                },
                {
                        "title" : "Nuevo",
                        "url"   : "documentos.nuevo.php"
                }
            ]
        },
        {
            "title": "Efectivo",
            "url": "efectivo.php",
            "children" : [
                {
                        "title" : "Lista billete",
                        "url"   : "efectivo.lista.billete.php"
                },
                {
                        "title" : "Nuevo billete",
                        "url"   : "efectivo.nuevo.billete.php"
                },
                {
                        "title" : "Lista moneda",
                        "url"   : "efectivo.lista.moneda.php"
                },
                {
                        "title" : "Nueva moneda",
                        "url"   : "efectivo.nueva.moneda.php"
                }
            ]
        },
        {
            "title": "Empresas",
            "url": "empresas.lista.php",
            "children" : [
                {
            		"title" : "Agregar sucursales",
            		"url" 	: "empresas.agregar.sucursales.php"
            	},
                {
            		"title" : "Lista",
            		"url" 	: "empresas.lista.php"
            	},
            	{
            		"title" : "Nueva",
            		"url" 	: "empresas.nuevo.php"
            	}
            ]
        },
        {
            "title": "Impuestos",
            "url": "impuestos.php",
            "children": [
                {
                        "title" : "Lista impuestos",
                        "url"   : "impuestos.lista.impuesto.php"
                },
                {
                        "title" : "Nuevo impuesto",
                        "url"   : "impuestos.nuevo.impuesto.php"
                },
                {
                        "title" : "Lista retenciones",
                        "url"   : "impuestos.lista.retencion.php"
                },
                {
                        "title" : "Nueva retencion",
                        "url"   : "impuestos.nueva.retencion.php"
                }
            ]
        },
        {
            "title": "Inventario",
            "url": "inventario.php",
            "children": [
                {
                        "title" : "Compras de sucursal",
                        "url"   : "inventario.compras.sucursal.php"
                },
                {
                        "title" : "Existencias",
                        "url"   : "inventario.existencias.php"
                },
                {
                        "title" : "Procesar producto",
                        "url"   : "inventario.procesar.producto.php"
                },
                {
                        "title" : "Terminar cargamento de compra",
                        "url"   : "inventario.terminar.cargamento.compra.php"
                },
                {
                        "title" : "Ventas de sucursal",
                        "url"   : "inventario.ventas.sucursal.php"
                }
            ]
        },
        {
            "title": "Paquetes",
            "url": "paquetes.php",
            "children": [
                {
                        "title" : "Detalle",
                        "url"   : "paquetes.detalle.php"
                },
                {
                        "title" : "Lista",
                        "url"   : "paquetes.lista.php"
                },
                {
                        "title" : "Nuevo",
                        "url"   : "paquetes.nuevo.php"
                }
            ]
        },
        {
            "title": "Productos",
            "url": "productos.lista.php",
            "children": [
                {
                        "title" : "Lista categoria",
                        "url"   : "productos.lista.categoria.php"
                },
                {
                        "title" : "Nueva categoria",
                        "url"   : "productos.nueva.categoria.php"
                },
                {
                        "title" : "Lista",
                        "url"   : "productos.lista.php"
                },
                {
                        "title" : "Nuevo",
                        "url"   : "productos.nuevo.php"
                },
                {
                        "title" : "Nuevo en volumen",
                        "url"   : "productos.nuevo.en_volumen.php"
                },
                {
                        "title" : "Editar equivalencia de unidad",
                        "url"   : "productos.editar.equivalencia.unidad.php"
                },
                {
                        "title" : "Eliminar equivalencia de unidad",
                        "url"   : "productos.eliminar.equivalencia.unidad.php"
                },
                {
                        "title" : "Lista unidad",
                        "url"   : "productos.lista.unidad.php"
                },
                {
                        "title" : "Lista equivalencia de unidad",
                        "url"   : "productos.lista.equivalencia.unidad.php"
                },
                {
                        "title" : "Nueva unidad",
                        "url"   : "productos.nueva.unidad.php"
                },
                {
                        "title" : "Nueva equivalencia unidad",
                        "url"   : "productos.nueva.equivalencia.unidad.php"
                }
            ]
        },
        {
            "title": "Personal",
            "url": "personal.php",
            "children": [
                {
                        "title" : "Lista rol",
                        "url"   : "personal.lista.rol.php"
                },
                {
                        "title" : "Nuevo rol",
                        "url"   : "personal.nuevo.rol.php"
                },
                {
                        "title" : "Asignar permiso a rol",
                        "url"   : "personal.asignar.permiso.rol.php"
                },
                {
                        "title" : "Lista permiso de rol",
                        "url"   : "personal.lista.permiso.rol.php"
                },
                {
                        "title" : "Remover permiso de rol",
                        "url"   : "personal.remover.permiso.rol.php"
                },
                {
                        "title" : "Lista usuario",
                        "url"   : "personal.lista.usuario.php"
                },
                {
                        "title" : "Nuevo usuario",
                        "url"   : "personal.nuevo.usuario.php"
                },
                {
                        "title" : "Asignar permiso a usuario",
                        "url"   : "personal.asignar.permiso.usuario.php"
                },
                {
                        "title" : "Lista permiso de usuario",
                        "url"   : "personal.lista.permiso.usuario.php"
                },
                {
                        "title" : "Remover permiso de usuario",
                        "url"   : "personal.remover.permiso.usuario.php"
                }
            ]
        },
        {
            "title": "Precios",
            "url": "precios.php",
            "children": [
                {
                        "title" : "Editar precio de paquete para rol",
                        "url"   : "precios.editar.precio.paquete.rol.php"
                },
                {
                        "title" : "Editar precio de paquete para tipo de cliente",
                        "url"   : "precios.editar.precio.paquete.tipo_cliente.php"
                },
                {
                        "title" : "Editar precio de paquete para usuario",
                        "url"   : "precios.editar.precio.paquete.usuario.php"
                },
                {
                        "title" : "Eliminar precio de paquete para rol",
                        "url"   : "precios.eliminar.precio.paquete.rol.php"
                },
                {
                        "title" : "Eliminar precio de paquete para tipo de cliente",
                        "url"   : "precios.eliminar.precio.paquete.tipo_cliente.php"
                },
                {
                        "title" : "Eliminar precio de paquete para usuario",
                        "url"   : "precios.eliminar.precio.paquete.usuario.php"
                },
                {
                        "title" : "Nuevo precio de paquete para rol",
                        "url"   : "precios.nuevo.precio.paquete.rol.php"
                },
                {
                        "title" : "Nuevo precio de paquete para tipo de cliente",
                        "url"   : "precios.nuevo.precio.paquete.tipo_cliente.php"
                },
                {
                        "title" : "Nuevo precio de paquete para usuario",
                        "url"   : "precios.nuevo.precio.paquete.usuario.php"
                },
                {
                        "title" : "Editar precio de producto para rol",
                        "url"   : "precios.editar.precio.producto.rol.php"
                },
                {
                        "title" : "Editar precio de producto para tipo de cliente",
                        "url"   : "precios.editar.precio.producto.tipo_cliente.php"
                },
                {
                        "title" : "Editar precio de producto para usuario",
                        "url"   : "precios.editar.precio.producto.usuario.php"
                },
                {
                        "title" : "Eliminar precio de producto para rol",
                        "url"   : "precios.eliminar.precio.producto.rol.php"
                },
                {
                        "title" : "Eliminar precio de producto para tipo de cliente",
                        "url"   : "precios.eliminar.precio.producto.tipo_cliente.php"
                },
                {
                        "title" : "Eliminar precio de producto para usuario",
                        "url"   : "precios.eliminar.precio.producto.usuario.php"
                },
                {
                        "title" : "Nuevo precio de producto para rol",
                        "url"   : "precios.nuevo.precio.producto.rol.php"
                },
                {
                        "title" : "Nuevo precio de producto para tipo de cliente",
                        "url"   : "precios.nuevo.precio.producto.tipo_cliente.php"
                },
                {
                        "title" : "Nuevo precio de producto para usuario",
                        "url"   : "precios.nuevo.precio.producto.usuario.php"
                },
                {
                        "title" : "Editar precio de servicio para rol",
                        "url"   : "precios.editar.precio.servicio.rol.php"
                },
                {
                        "title" : "Editar precio de servicio para tipo de cliente",
                        "url"   : "precios.editar.precio.servicio.tipo_cliente.php"
                },
                {
                        "title" : "Editar precio de servicio para usuario",
                        "url"   : "precios.editar.precio.servicio.usuario.php"
                },
                {
                        "title" : "Eliminar precio de servicio para rol",
                        "url"   : "precios.eliminar.precio.servicio.rol.php"
                },
                {
                        "title" : "Eliminar precio de servicio para tipo de cliente",
                        "url"   : "precios.eliminar.precio.servicio.tipo_cliente.php"
                },
                {
                        "title" : "Eliminar precio de servicio para usuario",
                        "url"   : "precios.eliminar.precio.servicio.usuario.php"
                },
                {
                        "title" : "Nuevo precio de servicio para rol",
                        "url"   : "precios.nuevo.precio.servicio.rol.php"
                },
                {
                        "title" : "Nuevo precio de servicio para tipo de cliente",
                        "url"   : "precios.nuevo.precio.servicio.tipo_cliente.php"
                },
                {
                        "title" : "Nuevo precio de servicio para usuario",
                        "url"   : "precios.nuevo.precio.servicio.usuario.php"
                }
            ]
        },
        {
            "title": "Proveedores",
            "url": "proveedores.lista.php",
            "children": [
		        {
		                "title" : "Proveedores",
		                "url"   : "proveedores.lista.php"
		        },
		        {
		                "title" : "Nuevo proveedor",
		                "url"   : "proveedores.nuevo.php"
		        },
                {
                        "title" : "Clasificacion",
                        "url"   : "proveedores.lista.clasificacion.php"
                },
                {
                        "title" : "Nueva clasificacion",
                        "url"   : "proveedores.nueva.clasificacion.php"
                }
            ]
        },
        {
            "title": "Reportes",
            "url": "reportes.php",
            "children": [
                {
                        "title" : "Productos por cliente",
                        "url"   : "reportes.productos.cliente.php"
                },
                {
                        "title" : "Detalle",
                        "url"   : "reportes.detalle.php"
                },
                {
                        "title" : "Lista",
                        "url"   : "reportes.lista.php"
                },
                {
                        "title" : "Nuevo",
                        "url"   : "reportes.nuevo.php"
                },
                {
                        "title" : "Revisar sintaxis de nuevo",
                        "url"   : "reportes.revisar_sintaxys.nuevo.php"
                },
                {
                        "title" : "Servicio por cliente",
                        "url"   : "reportes.servicio.cliente.php"
                }
            ]
        },
        {
            "title": "Servicios",
            "url": "servicios.lista.orden.php",
            "children": [
                {
                        "title" : "Lista clasificacion",
                        "url"   : "servicios.lista.clasificacion.php"
                },
                {
                        "title" : "Nueva clasificacion",
                        "url"   : "servicios.nueva.clasificacion.php"
                },
                {
                        "title" : "Lista",
                        "url"   : "servicios.lista.php"
                },
                {
                        "title" : "Nuevo",
                        "url"   : "servicios.nuevo.php"
                },
                {
                        "title" : "Lista orden",
                        "url"   : "servicios.lista.orden.php"
                },
                {
                        "title" : "Nueva orden",
                        "url"   : "servicios.nueva.orden.php"
                }
            ]
        },
        {
            "title": "Sucursales",
            "url": "sucursales.php",
            "children": [
                {
                        "title" : "Entrada a almacen",
                        "url"   : "sucursales.entrada.almacen.php"
                },
                {
                        "title" : "Lista almacen",
                        "url"   : "sucursales.lista.almacen.php"
                },
                {
                        "title" : "Nuevo almacen",
                        "url"   : "sucursales.nuevo.almacen.php"
                },
                {
                        "title" : "Salida de almacen",
                        "url"   : "sucursales.salida.almacen.php"
                },
                {
                        "title" : "Lista traspaso de almacen",
                        "url"   : "sucursales.lista.traspaso.almacen.php"
                },
                {
                        "title" : "Programar traspaso a almacen",
                        "url"   : "sucursales.programar.traspaso.almacen.php"
                },
                {
                        "title" : "Comprar desde caja",
                        "url"   : "sucursales.comprar.caja.php"
                },
                {
                        "title" : "Lista caja",
                        "url"   : "sucursales.lista.caja.php"
                },
                {
                        "title" : "Nueva caja",
                        "url"   : "sucursales.nueva.caja.php"
                },
                {
                        "title" : "Vender desde caja",
                        "url"   : "sucursales.vender.caja.php"
                },
                {
                        "title" : "Lista",
                        "url"   : "sucursales.lista.php"
                },
                {
                        "title" : "Nueva",
                        "url"   : "sucursales.nueva.php"
                },
                {
                        "title" : "Lista tipo de almacen",
                        "url"   : "sucursales.lista.tipo_almacen.php"
                },
                {
                        "title" : "Nuevo tipo de almacen",
                        "url"   : "sucursales.nuevo.tipo_almacen.php"
                }
            ]
        },
        {
            "title": "Transportacion",
            "url": "transportacion.php",
            "children": [
                {
                        "title" : "Cargar",
                        "url"   : "transportacion.cargar.php"
                },
                {
                        "title" : "Descargar",
                        "url"   : "transportacion.descargar.php"
                },
                {
                        "title" : "Detalle",
                        "url"   : "transportacion.detalle.php"
                },
                {
                        "title" : "Editar",
                        "url"   : "transportacion.editar.php"
                },
                {
                        "title" : "Enrutar",
                        "url"   : "transportacion.enrutar.php"
                },
                {
                        "title" : "Lista",
                        "url"   : "transportacion.lista.php"
                },
                {
                        "title" : "Editar marca",
                        "url"   : "transportacion.editar.marca.php"
                },
                {
                        "title" : "Nueva marca",
                        "url"   : "transportacion.nueva.marca.php"
                },
                {
                        "title" : "Editar modelo",
                        "url"   : "transportacion.editar.modelo.php"
                },
                {
                        "title" : "Nuevo modelo",
                        "url"   : "transportacion.nuevo.modelo.php"
                },
                {
                        "title" : "Nuevo",
                        "url"   : "transportacion.nuevo.php"
                },
                {
                        "title" : "Registrar llegada",
                        "url"   : "transportacion.registrar.llegada.php"
                },
                {
                        "title" : "Editar tipo",
                        "url"   : "transportacion.editar.tipo.php"
                },
                {
                        "title" : "Nuevo tipo",
                        "url"   : "transportacion.nuevo.tipo.php"
                },
                {
                        "title" : "Transbordo",
                        "url"   : "transportacion.transbordo.php"
                }
            ]
        },
        {
            "title": "Ventas",
            "url": "ventas.php",
            "children": [
                {
                        "title" : "Cancelar",
                        "url"   : "ventas.cancelar.php"
                },
                {
                        "title" : "Detalle",
                        "url"   : "ventas.detalle.php"
                },
                {
                        "title" : "Detalle de venta por arpilla",
                        "url"   : "ventas.detalle.venta.arpilla.php"
                },
                {
                        "title" : "Lista",
                        "url"   : "ventas.lista.php"
                },
                {
                        "title" : "Nueva",
                        "url"   : "ventas.nueva.php"
                },
                {
                        "title" : "Nueva venta por arpilla",
                        "url"   : "ventas.nueva.venta.arpilla.php"
                }
            ]
        }
    ]
}
				';
		
	}




	/**
      * End page creation and ask for login
      * optionally sending a message to user
	  **/
	private function dieWithLogin($message = null)
	{
		$login_cmp = new LoginComponent();

		if( $message != null )
		{
			self::addComponent(new MessageComponent($message));				
		}

		self::addComponent($login_cmp);
		parent::render();
		exit();
	}


	function render(  )
	{
		?>
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
		<head>
		<title>POS</title>

			<!--
			<link rel="stylesheet" type="text/css" href="http://api.caffeina.mx/ext-latest/resources/css/ext-all.css" /> 
		    <script type="text/javascript" src="http://api.caffeina.mx/ext-latest/adapter/ext/ext-base.js"></script> 
		    <script type="text/javascript" src="http://api.caffeina.mx/ext-latest/ext-all.js"></script> 
			-->
			
			<link rel="stylesheet" type="text/css" href="http://api.caffeina.mx/ext-4.0.0/resources/css/ext-all.css" /> 

		    <script type="text/javascript" src="http://api.caffeina.mx/ext-4.0.0/ext-all.js"></script>
					

			<link type="text/css" rel="stylesheet" href="../../../css/basic.css"/>
			<script type="text/javascript" src="./gerencia.js"></script>
			
		</head>
		<body class="">
		<div id="FB_HiddenContainer" style="position:absolute; top:-10000px; width:0px; height:0px;"></div>
		<div class="devsitePage">
			<div class="menu">
				<div class="content">
					<a class="logo" href="index.php">
						<img class="img" src="../../../media/N2f0JA5UPFU.png" alt="" width="166" height="17"/>
					</a>


					<a class="l" href="./configuracion.php">Configuracion</a>
					<a class="l" href="./&close_session">Salir</a>

					<a class="l">
						<img style="margin-top:8px; display: none;" id="ajax_loader" src="../../../media/loader.gif">
					</a>


					<div class="search">
						<form method="get" action="/search">
							<div class="uiTypeahead" id="u272751_1">
								<div class="wrap">
									<input type="hidden" autocomplete="off" class="hiddenInput" name="path" value=""/>
									<div class="innerWrap">
										<span class="uiSearchInput textInput">
										<span>
										
										<input 
											type="text" 
											class="inputtext DOMControl_placeholder" 
											name="selection" 
											placeholder="Buscar" 
											autocomplete="off" 
											onfocus="" 
											spellcheck="false"
											title="Search Documentation / Apps"/>
										<button type="submit" title="Search Documentation / Apps">
										<span class="hidden_elem">
										</span>
										</button>
										</span>
										</span>
									</div>
								</div>
											
								


							</div>
						</form>
					</div>
					<div class="clear">
					</div>
				</div>
			</div>
			<div class="body nav">
				<div class="content">
					<div id="bodyMenu" class="bodyMenu">
						<div class="toplevelnav">
							<ul>

							<?php
							################ Main Menu ################

								$mm = json_decode( $this->main_menu_json );

								foreach ( $mm->main_menu as $item )
								{

									echo "<li ";

									if(isset( $item->children ))
									{
										echo "class='withsubsections'";
									}

									echo "><a href='". $item->url  ."'><div class='navSectionTitle'>" . $item->title . "</div></a>";

									$foo = explode( "/" ,  $_SERVER["SCRIPT_FILENAME"] );
									$foo = array_pop( $foo );
									
									$foo = explode( "." , $foo );
									$foo = $foo[0];
									

									if(strtolower( $foo ) == strtolower( $item->title )){
										if(isset( $item->children ) ){

											foreach( $item->children as $subitem )
											{
												echo '<ul class="subsections">';
												echo "<li>";
												echo '<a href="'. $subitem->url .'">' . $subitem->title . '</a>';
												echo "</li>";
												echo "</ul>";
											}

										}										
									}


									echo "</li>";

								}

							################ Main Menu ################
							?>

						</div>
						<!--
						<ul id="navsubsectionpages">
							<li>asdf</li>
						</ul>
						-->
					</div>
					<div id="bodyText" class="bodyText">
						<div class="header">
							<div class="content">
							<!-- ----------------------------------------------------------------------
											CONTENIDO
								 ---------------------------------------------------------------------- -->

								 <?php
									foreach( $this->components as $cmp )
									{
										echo $cmp->renderCmp();
									}
								 ?>

								 <!--
								<div class="breadcrumbs">
									<a href=".">POS ERP</a> 
									&rsaquo; <a href=".">Cargos y abonos</a>							
								</div>								
								-->

								
									
							</div>
						</div>


						<div class="mtm pvm uiBoxWhite topborder">
							<div class="mbm">
								
							</div>
							<abbr class="timestamp">Generado <?php echo date("r",time()); ?></abbr>
						</div>

					</div>

					<div class="clear">
					</div>

				</div>
			</div>
			<div class="footer">
				<div class="content">
					
					<div class="copyright">
					Caffeina
					</div>

					<div class="links">
						<a href="">About</a>
						<a href="">Platform Policies</a>
						<a href="">Privacy Policy</a>
					</div>
				</div>
			</div>

			<div id="fb-root"></div>
			
			<div id="fb-root"></div>
			
		</div>

		</body>
		</html>
		<?php
		exit;
	}


}