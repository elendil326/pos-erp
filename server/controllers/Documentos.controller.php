<?php
require_once("interfaces/Documentos.interface.php");
/**
  *
  *
  *
  **/
	
  class DocumentosController implements IDocumentos{
  
  
	/**
 	 *
 	 *Lista los documentos en el sistema. Se puede filtrar por activos y por la empresa. Se puede ordenar por sus atributos
 	 *
 	 * @param activos bool Si no se obtiene este valor, se listaran los documentos activos e inactivos. Si su valor es true, mostrara solo los documentos activos, si es false, mostrara solo los documentos inactivos.
 	 * @param id_empresa int Id de la empresa de la cual se tomaran sus documentos.
 	 * @return documentos json Objeto que contendrá la información de los documentos.
 	 **/
	public function Lista
	(
		$activos, 
		$id_empresa = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crea un nuevo documento.

Update : Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
 	 *
 	 * @return id_documento int Id del nuevo documento
 	 **/
	public function Nuevo
	(
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Update : Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
 	 *
 	 * @param id_documento int Id del documento a editar.
 	 **/
	public function Editar
	(
		$id_documento
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Imprime el xml de una factura.

Update : No se si este metodo tenga una utilidad real, ya que cuando se recibe el XML timbrado, se crea el archivo .xml y en el unico momento que se vuelve a ocupar es para enviarlo por correo al cliente.
 	 *
 	 **/
	public function Imprimir_xmlFactura
	(
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Imprime una factura
Update : La respuesta solo deber?de contener success :true | false, y en caso de error, su descripcion, no se necesita apra anda en el JSON de respuesta una propiedad factura.
 	 *
 	 * @param id_folio int Id de la factura que se desea imprimir.
 	 * @return factura json Objeto con la informacion de la factura
 	 **/
	public function ImprimirFactura
	(
		$id_folio
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Cancela una factura.
 	 *
 	 * @param id_folio int Id de la factura a eliminar
 	 **/
	public function CancelarFactura
	(
		$id_folio
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Genera una factura seg?a informaci?e un cliente y la venta realizada.

Update : Falta especificar si seria una factura detallada (cuando en los conceptos de la factura describe a cada articulo) o generica (un solo concepto que engloba a todos los productos).
 	 *
 	 * @param id_venta int Id de la venta sobre la cual se facturara
 	 * @param id_cliente int Id del cliente al cual se le va a facturar
 	 * @return id_folio int Id de la factura generada
 	 **/
	public function GenerarFactura
	(
		$id_venta, 
		$id_cliente
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Imprime una nota de venta de acuerdo al id_venta y al id_impresora
 	 *
 	 * @param id_venta int Id de la venta que se imprimira
 	 * @param id_impresora int Id de la impresora en la que se imprimira
 	 **/
	public function ImprimirNota_de_venta
	(
		$id_venta, 
		$id_impresora
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Imprime un estado de cuenta de un cliente.
 	 *
 	 * @param id_cliente int Id del cliente del cual se imprimirán 
 	 * @return estado_cuenta json Objeto que contendrá la información del estado de cuenta del cliente
 	 **/
	public function ImprimirEstado_de_cuenta
	(
		$id_cliente
	)
	{  
  
  
	}
  }
