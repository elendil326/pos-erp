<?php
/**
  *
  *
  *
  **/

  interaface IDocumentos {
  
  
	/**
 	 *
 	 *Lista los documentos en el sistema. Se puede filtrar por activos y por la empresa. Se puede ordenar por sus atributos
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Crea un nuevo documento.

<br/><br/><b>Update :</b> Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
 	 *
 	 **/
	protected function Nuevo();  
  
  
  
  
	/**
 	 *
 	 *<br/><br/><b>Update :</b> Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
 	 *
 	 **/
	protected function Editar();  
  
  
  
  
	/**
 	 *
 	 *Imprime el xml de una factura.

<br/><br/><b>Update :</b> No se si este metodo tenga una utilidad real, ya que cuando se recibe el XML timbrado, se crea el archivo .xml y en el unico momento que se vuelve a ocupar es para enviarlo por correo al cliente.
 	 *
 	 **/
	protected function Imprimir_xmlFactura();  
  
  
  
  
	/**
 	 *
 	 *Imprime una factura
<br/><br/><b>Update :</b> La respuesta solo debería de contener success :true | false, y en caso de error, su descripcion, no se necesita apra anda en el JSON de respuesta una propiedad factura.
 	 *
 	 **/
	protected function ImprimirFactura();  
  
  
  
  
	/**
 	 *
 	 *Cancela una factura.
 	 *
 	 **/
	protected function CancelarFactura();  
  
  
  
  
	/**
 	 *
 	 *Genera una factura según la información de un cliente y la venta realizada.

<br/><br/><b>Update :</b> Falta especificar si seria una factura detallada (cuando en los conceptos de la factura describe a cada articulo) o generica (un solo concepto que engloba a todos los productos).
 	 *
 	 **/
	protected function GenerarFactura();  
  
  
  
  
	/**
 	 *
 	 *Imprime una nota de venta de acuerdo al id_venta y al id_impresora
 	 *
 	 **/
	protected function ImprimirNota_de_venta();  
  
  
  
  
	/**
 	 *
 	 *Imprime un estado de cuenta de un cliente.
 	 *
 	 **/
	protected function ImprimirEstado_de_cuenta();  
  
  
  
  }
