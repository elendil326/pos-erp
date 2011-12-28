<?php
/**
  *
  *
  *
  **/
	
  interface IDocumentos {
  
  
	/**
 	 *
 	 *Update : Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
 	 *
 	 * @param id_documento int Id del documento a editar.
 	 **/
  static function Editar
	(
		$id_documento
	);  
  
  
	
  
	/**
 	 *
 	 *Imprime un estado de cuenta de un cliente.
 	 *
 	 * @param id_cliente int Id del cliente del cual se imprimirán 
 	 * @return estado_cuenta json Objeto que contendrá la información del estado de cuenta del cliente
 	 **/
  static function ImprimirEstado_de_cuenta
	(
		$id_cliente
	);  
  
  
	
  
	/**
 	 *
 	 *Cancela una factura.
 	 *
 	 * @param id_folio int Id de la factura a eliminar
 	 **/
  static function CancelarFactura
	(
		$id_folio
	);  
  
  
	
  
	/**
 	 *
 	 *Genera una factura seg?n la informaci?n de un cliente y la venta realizada.

Update : Falta especificar si seria una factura detallada (cuando en los conceptos de la factura describe a cada articulo) o generica (un solo concepto que engloba a todos los productos).
 	 *
 	 * @param id_cliente int Id del cliente al cual se le va a facturar
 	 * @param id_venta int Id de la venta sobre la cual se facturara
 	 * @return id_folio int Id de la factura generada
 	 **/
  static function GenerarFactura
	(
		$id_cliente, 
		$id_venta
	);  
  
  
	
  
	/**
 	 *
 	 *Imprime una factura
Update : La respuesta solo deber?a de contener success :true | false, y en caso de error, su descripcion, no se necesita apra anda en el JSON de respuesta una propiedad factura.
 	 *
 	 * @param id_folio int Id de la factura que se desea imprimir.
 	 * @return factura json Objeto con la informacion de la factura
 	 **/
  static function ImprimirFactura
	(
		$id_folio
	);  
  
  
	
  
	/**
 	 *
 	 *Imprime el xml de una factura.

Update : No se si este metodo tenga una utilidad real, ya que cuando se recibe el XML timbrado, se crea el archivo .xml y en el unico momento que se vuelve a ocupar es para enviarlo por correo al cliente.
 	 *
 	 **/
  static function Imprimir_xmlFactura
	(
	);  
  
  
	
  
	/**
 	 *
 	 *Lista los documentos en el sistema. Se puede filtrar por activos y por la empresa. Se puede ordenar por sus atributos
 	 *
 	 * @param activos bool Si no se obtiene este valor, se listaran los documentos activos e inactivos. Si su valor es true, mostrara solo los documentos activos, si es false, mostrara solo los documentos inactivos.
 	 * @param id_empresa int Id de la empresa de la cual se tomaran sus documentos.
 	 * @return documentos json Objeto que contendrá la información de los documentos.
 	 **/
  static function Lista
	(
		$activos, 
		$id_empresa = null
	);  
  
  
	
  
	/**
 	 *
 	 *Imprime una nota de venta de acuerdo al id_venta y al id_impresora
 	 *
 	 * @param id_impresora int Id de la impresora en la que se imprimira
 	 * @param id_venta int Id de la venta que se imprimira
 	 **/
  static function ImprimirNota_de_venta
	(
		$id_impresora, 
		$id_venta
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un nuevo documento.

Update : Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
 	 *
 	 * @return id_documento int Id del nuevo documento
 	 **/
  static function Nuevo
	(
	);  
  
  
	
  }
