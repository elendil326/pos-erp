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
 	 * @param nombre string Nombre de la clasificacion del proveedor
 	 * @param retenciones json Ids de las retenciones de la clasificacion de  proveedor
 	 * @param descripcion string Descripcion de la clasificacion del proveedor
 	 * @param impuestos json Ids de los impuestos de la clasificacion del proveedor
 	 **/
  function EditarClasificacion
	(
		$id_clasificacion_proveedor, 
		$nombre, 
		$retenciones, 
		$descripcion = null, 
		$impuestos = null
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
 	 * @param password string Password del proveedor para entrar al sistema
 	 * @param codigo_proveedor string Codigo con el que se peude identificar al proveedor
 	 * @param id_tipo_proveedor int El id del tipo de proveedor
 	 * @param id_proveedor int Id del proveedor a editar
 	 * @param nombre string Nombre del proveedor
 	 * @param dias_de_credito int Dias de credito que otorga el proveedor
 	 * @param limite_credito float Limite de credito que otorga el proveedor
 	 * @param tiempo_entrega int Tiempo de entrega del proveedor en dias
 	 * @param codigo_postal string Codigo postal de la direccion del proveedor
 	 * @param id_ciudad int Id de la ciudad de la direccion del proveedor
 	 * @param texto_extra string Referencia para el domicilio del proveedor
 	 * @param numero_interior string Numero interior de la direccion del proveedor
 	 * @param numero_exterior string Numero exterior de la direccion del proveedor
 	 * @param direccion_web string Pagina web del proveedor
 	 * @param retenciones json Retenciones que afectan a este proveedor
 	 * @param cuenta_bancaria string Cuenta bancaria del proveedor a la cual se le deposita
 	 * @param id_moneda int Id de la moneda que maneja el proveedor
 	 * @param activo bool Si el proveedor sera tomado como activo despues de la insercion o no.
 	 * @param representante_legal string Representante legal del proveedor
 	 * @param rfc string RFC del proveedor
 	 * @param calle string Calle de la direccion del proveedor
 	 * @param telefono_personal string Telefono del proveedor
 	 * @param email string E-mail del proveedor
 	 * @param dias_embarque int Dias en que el proveedor embarca ( Lunes, Martes, Miercoles, Jueves..)
 	 * @param impuestos json Arreglo de enteros que contendr&#65533;n los ids de impuestos por comprar a este proveedor
 	 * @param telefono1 string Telefono 1 de la direccion del proveeor
 	 * @param telefono2 string Telefono 2 de la direccion del proveedor
 	 **/
  function Editar
	(
		$password, 
		$codigo_proveedor, 
		$id_tipo_proveedor, 
		$id_proveedor, 
		$nombre, 
		$dias_de_credito = null, 
		$limite_credito = null, 
		$tiempo_entrega = null, 
		$codigo_postal = null, 
		$id_ciudad = null, 
		$texto_extra = null, 
		$numero_interior = null, 
		$numero_exterior = null, 
		$direccion_web = null, 
		$retenciones = null, 
		$cuenta_bancaria = null, 
		$id_moneda = null, 
		$activo = null, 
		$representante_legal = null, 
		$rfc = null, 
		$calle = null, 
		$telefono_personal = null, 
		$email = null, 
		$dias_embarque = null, 
		$impuestos = null, 
		$telefono1 = null, 
		$telefono2 = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo desactiva un proveedor. Para que este metodo funcione, no debe de haber ordenes de compra hacia ese proveedor <b>??</b>
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
 	 * @param codigo_proveedor string Codigo interno para identificar al proveedor
 	 * @param nombre string Nombre del proveedor
 	 * @param password string Password del proveedor para entrar al sistema
 	 * @param id_tipo_proveedor int Id del tipo proveedor al que pertenece este usuario
 	 * @param dias_de_credito int Dias de credito que otorga el proveedor
 	 * @param limite_credito float Limite de credito que otorga el proveedor
 	 * @param tiempo_entrega string Tiempo de entrega del proveedor en dias
 	 * @param representante_legal string Representante legal del proveedor
 	 * @param activo bool Si este proveedor esta activo o no
 	 * @param cuenta_bancaria string Cuenta bancaria del proveedor
 	 * @param id_moneda int Id de la moneda preferente del proveedor
 	 * @param rfc string RFC del proveedor
 	 * @param calle string Calle de la direccion del proveedor
 	 * @param email string Correo electronico del proveedor
 	 * @param telefono_personal string Telefono personal del cliente
 	 * @param dias_embarque int Dias en que el proveedor embarca ( Lunes, Martes, Miercoles, Jueves..)
 	 * @param impuestos json Ids de los impuestos que afectan a este proveedor
 	 * @param retenciones json Retenciones que afectan a este proveedor
 	 * @param direccion_web string Direccion web del proveedor
 	 * @param numero_exterior string Numero exterior de la direccion del proveedor
 	 * @param numero_interior string Numero interior de la direccion del proveedor
 	 * @param texto_extra string Referencia de la direcciond el proveedor
 	 * @param id_ciudad int Id de la ciudad de la direccion del proveedor
 	 * @param codigo_postal string Codigo postal de la direccion del proveedor
 	 * @param telefono1 string Telefono 1 de la direccion del proveedor
 	 * @param telefono2 string Telefono 2 de la direccion del proveedor
 	 * @return id_proveedor int Id autogenerado por la inserción del nuevo proveedor.
 	 **/
  function Nuevo
	(
		$codigo_proveedor, 
		$nombre, 
		$password, 
		$id_tipo_proveedor, 
		$dias_de_credito = null, 
		$limite_credito = null, 
		$tiempo_entrega = null, 
		$representante_legal = null, 
		$activo = null, 
		$cuenta_bancaria = null, 
		$id_moneda = null, 
		$rfc = null, 
		$calle = null, 
		$email = null, 
		$telefono_personal = null, 
		$dias_embarque = null, 
		$impuestos = null, 
		$retenciones = null, 
		$direccion_web = null, 
		$numero_exterior = null, 
		$numero_interior = null, 
		$texto_extra = null, 
		$id_ciudad = null, 
		$codigo_postal = null, 
		$telefono1 = null, 
		$telefono2 = null
	);  
  
  
	
  }
