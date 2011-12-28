<?php
/**
  *
  *
  *
  **/
	
  interface IConsignaciones {
  
  
	/**
 	 *
 	 *Este metodo solo debe ser usado cuando no se ha vendido ningun producto de la consginacion y todos seran devueltos
 	 *
 	 * @param productos_almacen json Arreglo que contendra los ids de producto, ids de unidad, cantidad y el id del almacen al que iran.
 	 **/
  static function Cancelar
	(
		$productos_almacen
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva la bandera de consignatario a un cliente y elimina su almacen correspondiente. Para poder hacer esto, el almacen debera estar vacio.
 	 *
 	 * @param id_cliente int Id del cliente a desactivar como consignatario
 	 **/
  static function DesactivarConsignatario
	(
		$id_cliente
	);  
  
  
	
  
	/**
 	 *
 	 *Un consignatario ya es un cliente. Al crear un nuevo consignatario, se le crea un nuevo almacen a la sucursal que hace la consignacion. El nombre de ese almacen sera el rfc o la clave del cliente. Se agregara este nuevo id_almacen al cliente y su bandera de consignatario se pondra activa.
 	 *
 	 * @param id_cliente int Id del cliente que sera el consignatario
 	 * @return id_almacen int Id del almacen que se creo de la operacion
 	 **/
  static function NuevoConsignatario
	(
		$id_cliente
	);  
  
  
	
  
	/**
 	 *
 	 *Edita una consignacion, ya sea que agregue o quite productos a la misma. La fecha se toma del sistema.
 	 *
 	 * @param agregar bool Si estos productos seran agregados a la consignacion o seran quitados de la misma.
 	 * @param id_consignacion int Id de la consignacion a editar
 	 * @param productos json Objeto que contendra los ids de los productos y sus cantidades que ahora tendra esta consignacion
 	 **/
  static function Editar
	(
		$agregar, 
		$id_consignacion, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Abona un monto de dinero a una inspeccion ya registrada. La fecha sera tomada del sistema. Este metodo sera usado cuando el inspector llegue a la sucursal y deposite el dinero en la caja, de tal forma que se lleve un registro de cuando y cuanto deposito el dinero por el pago de la consignacion, asi como saber si el inspector ya realizo el deposito del dinero que se le consigno.
 	 *
 	 * @param id_caja int Id de la caja a la que se le abona
 	 * @param id_inspeccion int Id de la inspeccion
 	 * @param monto float monto que sera abonado a la inspeccion
 	 **/
  static function AbonarInspeccion
	(
		$id_caja, 
		$id_inspeccion, 
		$monto
	);  
  
  
	
  
	/**
 	 *
 	 *Usese este metodo cuando se posterga o se adelanta una inspeccion
 	 *
 	 * @param id_inspeccion int Id de la inspeccion a cambiar de fecha
 	 * @param nueva_fecha string Nueva fecha en que se llevara acabo la inspeccion
 	 **/
  static function Cambiar_fechaInspeccion
	(
		$id_inspeccion, 
		$nueva_fecha
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo sirve para cancelar una inspeccion que aun no ha sido registrada. Sirve cuando se cancela una orden de consignacion y por consiguiente se tienen que cancelar las inspecciones programadas para esa consignacion.
 	 *
 	 * @param id_inspeccion int Id de la inspeccion a cancelar
 	 **/
  static function CancelarInspeccion
	(
		$id_inspeccion
	);  
  
  
	
  
	/**
 	 *
 	 *Registra en que fecha se le hara una inspeccion a un cliente con consignacion 
 	 *
 	 * @param fecha_revision string Fecha en que se hara la revision.
 	 * @param id_consignacion int Id de la consignacion a la que se le hara la revision
 	 * @param id_inspector int Id del usuario al que se le asignara esta inspeccion
 	 * @return id_inspeccion int Id de la inspeccion creada
 	 **/
  static function NuevaInspeccion
	(
		$fecha_revision, 
		$id_consignacion, 
		$id_inspector = null
	);  
  
  
	
  
	/**
 	 *
 	 *Se llama cuando se realiza una revision a una orden de consigacion. Actualiza el estado del almacen del cliente, se facturan a credito o de contado las ventas realizadas y se registra la entrada de dinero. La fecha sera tomada del servidor.
 	 *
 	 * @param id_inspeccion int Id de la inspeccion realizada
 	 * @param productos_actuales json Ojeto que contendra los ids de los productos con sus cantidades con los que cuenta actualmente el cliente, puede ser un json vacio. Este campo no se ve afectado por los campos producto_solicitado ni producto_devuelto.
 	 * @param id_inspector int Id del usuario que realiza la inspeccion
 	 * @param monto_abonado float Si la consignacion fue de contado, el cobrador debe registrar el monto equivalente a las ventas del cliente
 	 * @param producto_devuelto json Objeto que contendra los ids de los productos y sus cantidades que seran devueltos. Estos productos seran devueltos al almacen  de la sucursal de donde fueron extraidos.
 	 * @param producto_solicitado json Objeto que contendra los ids de los productos y sus cantidades que el cliente solicita, si este campo es obtenido, se editara la consignacion original agregando estos productos
 	 **/
  static function RegistrarInspeccion
	(
		$id_inspeccion, 
		$productos_actuales, 
		$id_inspector = "", 
		$monto_abonado = null, 
		$producto_devuelto = null, 
		$producto_solicitado = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo lista las consignaciones de la instancia. Puede filtrarse por empresa, por sucursal, por cliente, por producto y se puede ordenar por sus atributos.
 	 *
 	 * @param id_cliente int Id del cliente del cual se mostraran las consignaciones
 	 * @param id_empresa int Id de la empresa de la cual se mostraran las consignaciones
 	 * @param id_producto int Id del producto del cual se mostraran las consignaciones
 	 * @param id_sucursal int Id de la sucursal de la cual se mostraran las consignaciones
 	 * @param orden string Nombre de la columan por el cual se ordenara la lista
 	 * @return lista_consignaciones json Objeto que contendra la lista de consignaciones
 	 **/
  static function Lista
	(
		$id_cliente = null, 
		$id_empresa = null, 
		$id_producto = null, 
		$id_sucursal = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Iniciar una orden de consignaci?n. La fecha sera tomada del servidor.
 	 *
 	 * @param fecha_termino string Fecha en el que se termina la consignacion
 	 * @param folio string Folio de la consignacion
 	 * @param id_consignatario int Id del cliente al que se le hace la consignacion
 	 * @param productos json Objeto que contendra los ids de los productos que se daran a consignacion a ese cliente con sus cantidades. Se incluira el id del almacen del cual seran tomados para determinar a que empresa pertenece esta consignacion
 	 * @param tipo_consignacion string Especifica si la venta generada por esta consignacion se hara a credito o de contado
 	 * @param fecha_envio_programada string Sera la fecha de envio de los productos de los almacenes de los que seran tomados al almacen del consignatario. Si no se recibe se toma la fecha actual como la fecha de envio 
 	 * @return id_consignacion int Id de la consignacion autogenerado por la insercion.
 	 **/
  static function Nueva
	(
		$fecha_termino, 
		$folio, 
		$id_consignatario, 
		$productos, 
		$tipo_consignacion, 
		$fecha_envio_programada = null
	);  
  
  
	
  
	/**
 	 *
 	 *Dar por terminada una consignacion, bien se termino el inventario en consigacion o se va a regresar al inventario de alguna sucursal.
 	 *
 	 * @param id_consignacion int identifiacdor de consignacion
 	 * @param productos_actuales json Objeto que contendra los productos actuales de la consignacion.
 	 * @param motivo string Motivo por el cual se termino la consignacion, por que el cliente no vendia, o a peticion del cliente, etc.
 	 * @param tipo_pago string Si el tipo de pago es en cheque, tarjeta o en efectivo
 	 **/
  static function Terminar
	(
		$id_consignacion, 
		$productos_actuales, 
		$motivo = null, 
		$tipo_pago = null
	);  
  
  
	
  }
