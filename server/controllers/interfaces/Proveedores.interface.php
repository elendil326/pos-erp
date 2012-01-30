<?php
/**
  *
  *
  *
  **/
	
  interface IProveedores {
  
  
	/**
 	 *
 	 *Edita la informacion de una clasificacion de proveedor
 	 *
 	 * @param id_clasificacion_proveedor int Id de la clasificacion del proveedor a editar
 	 * @param descripcion string Descripcion de la clasificacion del proveedor
 	 * @param id_tarifa_compra int Id de la tarifa de compra que se aplicara a los proveedores de esta clasificacion. Si un usuario tiene esta clasificacion pero tiene otra tarifa entonces no se sobreescribira
 	 * @param id_tarifa_venta int Id de la tarifa de venta que se aplicara a los proveedores de esta clasificacion. Si un usuario tiene esta clasificacion pero tiene otra tarifa entonces no se sobreescribira
 	 * @param impuestos json Ids de los impuestos de la clasificacion del proveedor
 	 * @param nombre string Nombre de la clasificacion del proveedor
 	 * @param retenciones json Ids de las retenciones de la clasificacion de  proveedor
 	 **/
  static function EditarClasificacion
	(
		$id_clasificacion_proveedor, 
		$descripcion = null, 
		$id_tarifa_compra = null, 
		$id_tarifa_venta = null, 
		$impuestos = null, 
		$nombre = null, 
		$retenciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva una clasificacion de proveedor
 	 *
 	 * @param id_clasificacion_proveedor int Id de la clasificacion de proveedor a desactivar
 	 **/
  static function EliminarClasificacion
	(
		$id_clasificacion_proveedor
	);  
  
  
	
  
	/**
 	 *
 	 *Este emtodo lista las clasificaciones de proveedores
 	 *
 	 * @param activo bool Si este parametro es obtenido, se listaran las clasificaciones cumplan con el
 	 * @param orden string Nombre de la columna de la tabla mediante la cual se ordenara la lista
 	 * @return lista_clasificaciones json Objeto que contendra la lista de clasificaciones
 	 **/
  static function ListaClasificacion
	(
		$activo = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea una nueva clasificacion de proveedor
 	 *
 	 * @param nombre string Nombre de la clasificacion de proveedor
 	 * @param descripcion string Descripcion de la clasificacion del proveedor
 	 * @param id_tarifa_compra int Id de la tarifa de compra por default que se aplica a los proveedores de esta clasificacion
 	 * @param id_tarifa_venta int Id de la tarifa de venta por default que se aplica a los proveedores de esta clasificacion
 	 * @param impuestos json Ids de impuestos que afectan esta clasificacion de proveedor
 	 * @param retenciones json Ids de retenciones que afecta esta clasificacion de proveedor
 	 * @return id_clasificacion_proveedor int Id de la clasificacion del proveedor
 	 **/
  static function NuevaClasificacion
	(
		$nombre, 
		$descripcion = null, 
		$id_tarifa_compra = null, 
		$id_tarifa_venta = null, 
		$impuestos = null, 
		$retenciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de un proveedor. 
 	 *
 	 * @param id_proveedor int Id del proveedor a editar
 	 * @param activo bool Si el proveedor sera tomado como activo despues de la insercion o no.
 	 * @param calle string Calle de la direccion del proveedor
 	 * @param codigo_postal string Codigo postal de la direccion del proveedor
 	 * @param codigo_proveedor string Codigo con el que se peude identificar al proveedor
 	 * @param colonia string La colonia del proveedor
 	 * @param cuenta_bancaria string Cuenta bancaria del proveedor a la cual se le deposita
 	 * @param dias_de_credito int Dias de credito que otorga el proveedor
 	 * @param dias_embarque int Dias en que el proveedor embarca ( Lunes, Martes, Miercoles, Jueves..)
 	 * @param direccion_web string Pagina web del proveedor
 	 * @param email string E-mail del proveedor
 	 * @param id_ciudad int Id de la ciudad de la direccion del proveedor
 	 * @param id_moneda int Id de la moneda que maneja el proveedor
 	 * @param id_tarifa_compra int Id de la tarifa de compra por default que aplicara a este proveedor
 	 * @param id_tarifa_venta int Id de la tarifa de venta por default que aplicara a este proveedor
 	 * @param id_tipo_proveedor int El id del tipo de proveedor
 	 * @param impuestos json Arreglo de enteros que contendran los ids de impuestos por comprar a este proveedor
 	 * @param limite_credito float Limite de credito que otorga el proveedor
 	 * @param nombre string Nombre del proveedor
 	 * @param numero_exterior string Numero exterior de la direccion del proveedor
 	 * @param numero_interior string Numero interior de la direccion del proveedor
 	 * @param password string Password del proveedor para entrar al sistema
 	 * @param representante_legal string Representante legal del proveedor
 	 * @param retenciones json Retenciones que afectan a este proveedor
 	 * @param rfc string RFC del proveedor
 	 * @param telefono1 string Telefono 1 de la direccion del proveeor
 	 * @param telefono2 string Telefono 2 de la direccion del proveedor
 	 * @param telefono_personal string Telefono del proveedor
 	 * @param texto_extra string Referencia para el domicilio del proveedor
 	 * @param tiempo_entrega int Tiempo de entrega del proveedor en dias
 	 **/
  static function Editar
	(
		$id_proveedor, 
		$activo = 1, 
		$calle = null, 
		$codigo_postal = null, 
		$codigo_proveedor = null, 
		$colonia = null, 
		$cuenta_bancaria = null, 
		$dias_de_credito = null, 
		$dias_embarque = null, 
		$direccion_web = null, 
		$email = null, 
		$id_ciudad = null, 
		$id_moneda = null, 
		$id_tarifa_compra = null, 
		$id_tarifa_venta = null, 
		$id_tipo_proveedor = null, 
		$impuestos = null, 
		$limite_credito = null, 
		$nombre = null, 
		$numero_exterior = null, 
		$numero_interior = null, 
		$password = null, 
		$representante_legal = null, 
		$retenciones = null, 
		$rfc = null, 
		$telefono1 = null, 
		$telefono2 = null, 
		$telefono_personal = null, 
		$texto_extra = null, 
		$tiempo_entrega = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo desactiva un proveedor. Para que este metodo funcione, no debe de haber ordenes de compra hacia ese proveedor ??
 	 *
 	 * @param id_proveedor int Id del proveedor que sera eliminado
 	 **/
  static function Eliminar
	(
		$id_proveedor
	);  
  
  
	
  
	/**
 	 *
 	 *Obtener una lista de proveedores. Puede filtrarse por activo o inactivos, y puede ordenarse por sus atributos.
 	 *
 	 * @param activo bool Si el valor no es obtenido, se listaran los proveedores tanto activos como inactivos. Si su valor es true, se mostraran solo los proveedores activos, si es false, se mostraran solo los proveedores inactivos.
 	 * @param orden string Nombre de la columan por el cual se ordenara la lista
 	 * @return proveedores json Objeto que contendra la lista de proveedores.
 	 **/
  static function Lista
	(
		$activo = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un nuevo proveedor
 	 *
 	 * @param codigo_proveedor string Codigo interno para identificar al proveedor
 	 * @param id_tipo_proveedor int Id del tipo proveedor al que pertenece este usuario
 	 * @param nombre string Nombre del proveedor
 	 * @param password string Password del proveedor para entrar al sistema
 	 * @param activo bool Si este proveedor esta activo o no
 	 * @param cuenta_bancaria string Cuenta bancaria del proveedor
 	 * @param dias_de_credito int Dias de credito que otorga el proveedor
 	 * @param dias_embarque int Dias en que el proveedor embarca ( Lunes, Martes, Miercoles, Jueves..)
 	 * @param direcciones json [{    "tipo": 1,    "calle": "Francisco I Madero",    "numero_exterior": "1009A",    "numero_interior": 12,    "colonia": "centro",    "codigo_postal": "38000",    "telefono1": "4611223312",    "telefono2": "",    "email": "tortas.rosy@gmail.com",    "id_ciudad": 3,    "referencia": "El local naranja"}]
 	 * @param direccion_web string Direccion web del proveedor
 	 * @param email string Correo electronico del proveedor
 	 * @param id_moneda int Id de la moneda preferente del proveedor
 	 * @param id_tarifa_compra int Id de la tarifa de compra por default que aplicara a este porveedor
 	 * @param id_tarifa_venta int Id de la tarifa de venta por default que aplicara a etse proveedor
 	 * @param impuestos json Ids de los impuestos que afectan a este proveedor
 	 * @param limite_credito float Limite de credito que otorga el proveedor
 	 * @param representante_legal string Representante legal del proveedor
 	 * @param retenciones json Retenciones que afectan a este proveedor
 	 * @param rfc string RFC del proveedor
 	 * @param telefono_personal1 string Telefono personal del cliente
 	 * @param telefono_personal2 string Telefono personal alterno del proveedor
 	 * @param tiempo_entrega string Tiempo de entrega del proveedor en dias
 	 * @return id_proveedor int Id autogenerado por la insercin del nuevo proveedor.
 	 **/
  static function Nuevo
	(
		$codigo_proveedor, 
		$id_tipo_proveedor, 
		$nombre, 
		$password, 
		$activo = null, 
		$cuenta_bancaria = null, 
		$dias_de_credito = null, 
		$dias_embarque = true, 
		$direcciones = null, 
		$direccion_web = null, 
		$email = null, 
		$id_moneda = null, 
		$id_tarifa_compra = null, 
		$id_tarifa_venta = null, 
		$impuestos = null, 
		$limite_credito = null, 
		$representante_legal = null, 
		$retenciones = null, 
		$rfc = null, 
		$telefono_personal1 = null, 
		$telefono_personal2 = null, 
		$tiempo_entrega = null
	);  
  
  
	
  }
