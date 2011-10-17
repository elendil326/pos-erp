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
 	 * @param retenciones json Ids de las retenciones de la clasificacion de  proveedor
 	 * @param impuestos json Ids de los impuestos de la clasificacion del proveedor
 	 * @param descripcion string Descripcion de la clasificacion del proveedor
 	 * @param nombre string Nombre de la clasificacion del proveedor
 	 **/
  function EditarClasificacion
	(
		$id_clasificacion_proveedor, 
		$retenciones = "", 
		$impuestos = "", 
		$descripcion = "", 
		$nombre = ""
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva una clasificacion de proveedor
 	 *
 	 * @param id_clasificacion_proveedor int Id de la clasificacion de proveedor a desactivar
 	 **/
  function EliminarClasificacion
	(
		$id_clasificacion_proveedor
	);  
  
  
	
  
	/**
 	 *
 	 *Crea una nueva clasificacion de proveedor
 	 *
 	 * @param nombre string Nombre de la clasificacion de proveedor
 	 * @param descripcion string Descripcion de la clasificacion del proveedor
 	 * @param impuestos json Ids de impuestos que afectan esta clasificacion de proveedor
 	 * @param retenciones json Ids de retenciones que afecta esta clasificacion de proveedor
 	 * @return id_clasificacion_proveedor int Id de la clasificacion del proveedor
 	 **/
  function NuevaClasificacion
	(
		$nombre, 
		$descripcion = null, 
		$impuestos = null, 
		$retenciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de un proveedor. 
 	 *
 	 * @param id_proveedor int Id del proveedor a editar
 	 * @param limite_credito float Limite de credito que otorga el proveedor
 	 * @param password string Password del proveedor para entrar al sistema
 	 * @param tiempo_entrega int Tiempo de entrega del proveedor en dias
 	 * @param codigo_postal string Codigo postal de la direccion del proveedor
 	 * @param id_ciudad int Id de la ciudad de la direccion del proveedor
 	 * @param texto_extra string Referencia para el domicilio del proveedor
 	 * @param direccion_web string Pagina web del proveedor
 	 * @param numero_interior string Numero interior de la direccion del proveedor
 	 * @param numero_exterior string Numero exterior de la direccion del proveedor
 	 * @param representante_legal string Representante legal del proveedor
 	 * @param activo bool Si el proveedor sera tomado como activo despues de la insercion o no.
 	 * @param rfc string RFC del proveedor
 	 * @param id_tipo_proveedor int El id del tipo de proveedor
 	 * @param dias_de_credito int Dias de credito que otorga el proveedor
 	 * @param calle string Calle de la direccion del proveedor
 	 * @param telefono_personal string Telefono del proveedor
 	 * @param nombre string Nombre del proveedor
 	 * @param email string E-mail del proveedor
 	 * @param dias_embarque int Dias en que el proveedor embarca ( Lunes, Martes, Miercoles, Jueves..)
 	 * @param impuestos json Arreglo de enteros que contendr&#65533;n los ids de impuestos por comprar a este proveedor
 	 * @param telefono2 string Telefono 2 de la direccion del proveedor
 	 * @param telefono1 string Telefono 1 de la direccion del proveeor
 	 * @param cuenta_bancaria string Cuenta bancaria del proveedor a la cual se le deposita
 	 * @param id_moneda int Id de la moneda que maneja el proveedor
 	 * @param retenciones json Retenciones que afectan a este proveedor
 	 * @param codigo_proveedor string Codigo con el que se peude identificar al proveedor
 	 **/
  function Editar
	(
		$id_proveedor, 
		$limite_credito = null, 
		$password = null, 
		$tiempo_entrega = null, 
		$codigo_postal = null, 
		$id_ciudad = null, 
		$texto_extra = null, 
		$direccion_web = null, 
		$numero_interior = null, 
		$numero_exterior = null, 
		$representante_legal = null, 
		$activo = 1, 
		$rfc = null, 
		$id_tipo_proveedor = null, 
		$dias_de_credito = null, 
		$calle = null, 
		$telefono_personal = null, 
		$nombre = null, 
		$email = null, 
		$dias_embarque = null, 
		$impuestos = null, 
		$telefono2 = null, 
		$telefono1 = null, 
		$cuenta_bancaria = null, 
		$id_moneda = null, 
		$retenciones = null, 
		$codigo_proveedor = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo desactiva un proveedor. Para que este metodo funcione, no debe de haber ordenes de compra hacia ese proveedor ??
 	 *
 	 * @param id_proveedor int Id del proveedor que sera eliminado
 	 **/
  function Eliminar
	(
		$id_proveedor
	);  
  
  
	
  
	/**
 	 *
 	 *Obtener una lista de proveedores. Puede filtrarse por activo o inactivos, y puede ordenarse por sus atributos.
 	 *
 	 * @param activo bool Si el valor no es obtenido, se listaran los proveedores tanto activos como inactivos. Si su valor es true, se mostraran solo los proveedores activos, si es false, se mostraran solo los proveedores inactivos.
 	 * @param ordenar json Valor que determinara el orden de la lista.
 	 * @return proveedores json Objeto que contendra la lista de proveedores.
 	 **/
  function Lista
	(
		$activo = null, 
		$ordenar = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un nuevo proveedor
 	 *
 	 * @param id_tipo_proveedor int Id del tipo proveedor al que pertenece este usuario
 	 * @param password string Password del proveedor para entrar al sistema
 	 * @param nombre string Nombre del proveedor
 	 * @param codigo_proveedor string Codigo interno para identificar al proveedor
 	 * @param codigo_postal string Codigo postal de la direccion del proveedor
 	 * @param id_ciudad int Id de la ciudad de la direccion del proveedor
 	 * @param texto_extra string Referencia de la direcciond el proveedor
 	 * @param numero_interior string Numero interior de la direccion del proveedor
 	 * @param numero_exterior string Numero exterior de la direccion del proveedor
 	 * @param direccion_web string Direccion web del proveedor
 	 * @param retenciones json Retenciones que afectan a este proveedor
 	 * @param impuestos json Ids de los impuestos que afectan a este proveedor
 	 * @param dias_embarque int Dias en que el proveedor embarca ( Lunes, Martes, Miercoles, Jueves..)
 	 * @param telefono_personal string Telefono personal del cliente
 	 * @param rfc string RFC del proveedor
 	 * @param calle string Calle de la direccion del proveedor
 	 * @param email string Correo electronico del proveedor
 	 * @param id_moneda int Id de la moneda preferente del proveedor
 	 * @param cuenta_bancaria string Cuenta bancaria del proveedor
 	 * @param activo bool Si este proveedor esta activo o no
 	 * @param representante_legal string Representante legal del proveedor
 	 * @param tiempo_entrega string Tiempo de entrega del proveedor en dias
 	 * @param limite_credito float Limite de credito que otorga el proveedor
 	 * @param dias_de_credito int Dias de credito que otorga el proveedor
 	 * @param telefono1 string Telefono 1 de la direccion del proveedor
 	 * @param telefono2 string Telefono 2 de la direccion del proveedor
 	 * @return id_proveedor int Id autogenerado por la inserción del nuevo proveedor.
 	 **/
  function Nuevo
	(
		$id_tipo_proveedor, 
		$password, 
		$nombre, 
		$codigo_proveedor, 
		$codigo_postal = null, 
		$id_ciudad = null, 
		$texto_extra = null, 
		$numero_interior = null, 
		$numero_exterior = null, 
		$direccion_web = null, 
		$retenciones = null, 
		$impuestos = null, 
		$dias_embarque = true, 
		$telefono_personal = null, 
		$rfc = null, 
		$calle = 1, 
		$email = null, 
		$id_moneda = null, 
		$cuenta_bancaria = null, 
		$activo = null, 
		$representante_legal = null, 
		$tiempo_entrega = null, 
		$limite_credito = null, 
		$dias_de_credito = null, 
		$telefono1 = null, 
		$telefono2 = null
	);  
  
  
	
  }
