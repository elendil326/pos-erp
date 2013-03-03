<?php
/**
  *
  *
  *
  **/
	
  interface IDocumentos {
  
  
	/**
 	 *
 	 *El documento base es de donde se crean instancias de documentos.
 	 *
 	 * @param json_impresion json El json que se utilizara para imprimir este documento.
 	 * @param nombre string Nombre del documento base
 	 * @param activo bool Si esta activo o si no se puede realizar documentos de este tipo.
 	 * @param extra_params json 
 	 * @param foliado json El json que describe como sera el foliado de este documento. Incluye en que folio va.
 	 * @param foliado json 
 	 * @param id_empresa int Si pertence a una empresa en especifico, o puede realizarse en cualquier empresa.
 	 * @param id_sucursal int Si pertenece a una sucursal en especifico o puede realizarse en cualquier sucursal.
 	 * @return id_documento_base int Id del nuevo documento
 	 **/
  static function NuevoBase
	(
		$json_impresion, 
		$nombre, 
		$activo =  1 , 
		$extra_params = null, 
		$foliado = "", 
		$foliado = "", 
		$id_empresa = null, 
		$id_sucursal = null
	);  
  
  
	
  
	/**
 	 *
 	 *Lista los documentos en el sistema. Se puede filtrar por activos y por la empresa. Se puede ordenar por sus atributos
 	 *
 	 * @param activos bool Si no se obtiene este valor, se listaran los documentos activos e inactivos. Si su valor es true, mostrara solo los documentos activos, si es false, mostrara solo los documentos inactivos.
 	 * @param id_empresa int Id de la empresa de la cual se tomaran sus documentos.
 	 * @param nombre string Buscar por nombre
 	 * @return resultados json Objeto que contendr la informacin de los documentos.
 	 * @return numero_de_resultados int 
 	 **/
  static function Buscar
	(
		$activos = "", 
		$id_empresa = null, 
		$nombre = null
	);  
  
  
	
  
	/**
 	 *
 	 *Update : Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
 	 *
 	 * @param id_documento int Id del documento a editar.
 	 * @param activo bool 
 	 * @param foliado json 
 	 * @param id_empresa int 
 	 * @param id_sucursal int 
 	 * @param json_impresion string 
 	 * @param nombre string 
 	 **/
  static function Editar
	(
		$id_documento, 
		$activo = null, 
		$foliado = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$json_impresion = null, 
		$nombre = null
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
 	 *Crea un nuevo documento.

 	 *
 	 * @param extra_params json 
 	 * @param id_empresa int Si pertence a una empresa en especifico, o puede realizarse en cualquier empresa.
 	 * @param id_sucursal int Si pertenece a una sucursal en especifico o puede realizarse en cualquier sucursal.
 	 * @return id_documento int Id del nuevo documento
 	 **/
  static function Nuevo
	(
		$extra_params = null, 
		$id_empresa = null, 
		$id_sucursal = null
	);  
  
  
	
  }
