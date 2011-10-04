<?php
require_once("Consignaciones.interface.php");
/**
  *
  *
  *
  **/
	
  class ConsignacionesController implements IConsignaciones{
  
  
	/**
 	 *
 	 *Desactiva la bandera de consignatario a un cliente y elimina su almacen correspondiente. Para poder hacer esto, el almacen debera estar vacio.
 	 *
 	 * @param id_cliente int Id del cliente a desactivar como consignatario
 	 **/
	public function DesactivarConsignatario
	(
		$id_cliente
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Iniciar una orden de consignaci?La fecha sera tomada del servidor.
 	 *
 	 * @param productos json Objeto que contendra los ids de los productos que se daran a consignacion a ese cliente con sus cantidades. Se incluira el id del almacen del cual seran tomados para determinar a que empresa pertenece esta consignacion
 	 * @param id_consignatario int Id del cliente al que se le hace la consignacion
 	 * @return id_consignacion int Id de la consignacion autogenerado por la insercion.
 	 **/
	public function Nueva
	(
		$productos, 
		$id_consignatario
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Un consignatario ya es un cliente. Al crear un nuevo consignatario, se le crea un nuevo almacen a la sucursal que hace la consignacion. El nombre de ese almacen sera el rfc o la clave del cliente. Se agregara este nuevo id_almacen al cliente y su bandera de consignatario se pondra activa.
 	 *
 	 * @param id_cliente int Id del cliente que sera el consignatario
 	 * @return id_almacen int Id del almacen que se creo de la operacion
 	 **/
	public function NuevoConsignatario
	(
		$id_cliente
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo lista las consignaciones de la instancia. Puede filtrarse por empresa, por sucursal, por cliente, por producto y se puede ordenar por sus atributos.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se mostraran las consignaciones
 	 * @param id_sucursal int Id de la sucursal de la cual se mostraran las consignaciones
 	 * @param id_cliente int Id del cliente del cual se mostraran las consignaciones
 	 * @param id_producto int Id del producto del cual se mostraran las consignaciones
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @return lista_consignaciones json Objeto que contendra la lista de consignaciones
 	 **/
	public function Lista
	(
		$id_empresa = null, 
		$id_sucursal = null, 
		$id_cliente = null, 
		$id_producto = null, 
		$orden = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Se llama cuando se realiza una revision a una orden de consigacion. Actualiza el estado del almacen del cliente, se facturan a credito o de contado las ventas realizadas y se registra la entrada de dinero. La fecha sera tomada del servidor.
 	 *
 	 * @param productos_actuales json Ojeto que contendra los ids de los productos con sus cantidades con los que cuenta actualmente el cliente, puede ser un json vacio. Este campo no se ve afectado por los campos producto_solicitado ni producto_devuelto.
 	 * @param id_inspeccion int Id de la inspeccion realizada
 	 * @param id_inspector int Id del usuario que realiza la inspeccion
 	 * @param monto_abonado float Si la consignacion fue de contado, el cobrador debe registrar el monto equivalente a las ventas del cliente
 	 * @param producto_solicitado json Objeto que contendra los ids de los productos y sus cantidades que el cliente solicita, si este campo es obtenido, se editara la consignacion original agregando estos productos
 	 * @param producto_devuelto json Objeto que contendra los ids de los productos y sus cantidades que seran devueltos. Estos productos seran devueltos al almacen  de la sucursal de donde fueron extraidos.
 	 **/
	public function RegistrarInspeccion
	(
		$productos_actuales, 
		$id_inspeccion, 
		$id_inspector, 
		$monto_abonado = null, 
		$producto_solicitado = null, 
		$producto_devuelto = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Dar por terminada una consignacion, bien se termino el inventario en consigacion o se va a regresar al inventario de alguna sucursal.
 	 *
 	 * @param id_consignacion int identifiacdor de consignacion
 	 * @param motivo string Motivo por el cual se termino la consignacion, por que el cliente no vendia, o a peticion del cliente, etc.
 	 **/
	public function Terminar
	(
		$id_consignacion, 
		$motivo = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Abona un monto de dinero a una inspeccion ya registrada. La fecha sera tomada del sistema. Este metodo sera usado cuando el inspector llegue a la sucursal y deposite el dinero en la caja, de tal forma que se lleve un registro de cuando y cuanto deposito el dinero por el pago de la consignacion, asi como saber si el inspector ya realizo el deposito del dinero que se le consigno.
 	 *
 	 * @param monto float monto que sera abonado a la inspeccion
 	 * @param id_inspeccion int Id de la inspeccion
 	 * @param id_caja int Id de la caja a la que se le abona
 	 **/
	public function AbonarInspeccion
	(
		$monto, 
		$id_inspeccion, 
		$id_caja
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Registra en que fecha se le hara una inspeccion a un cliente con consignacion 
 	 *
 	 * @param id_consignacion int Id de la consignacion a la que se le hara la revision
 	 * @param fecha_revision string Fecha en que se hara la revision.
 	 * @param id_inspector int Id del usuario al que se le asignara esta inspeccion
 	 * @return id_inspeccion int Id de la inspeccion creada
 	 **/
	public function NuevaInspeccion
	(
		$id_consignacion, 
		$fecha_revision, 
		$id_inspector = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Usese este metodo cuando se posterga o se adelanta una inspeccion
 	 *
 	 * @param id_inspeccion int Id de la inspeccion a cambiar de fecha
 	 * @param nueva_fecha string Nueva fecha en que se llevara acabo la inspeccion
 	 **/
	public function Cambiar_fechaInspeccion
	(
		$id_inspeccion, 
		$nueva_fecha
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo sirve para cancelar una inspeccion que aun no ha sido registrada. Sirve cuando se cancela una orden de consignacion y por consiguiente se tienen que cancelar las inspecciones programadas para esa consignacion.
 	 *
 	 * @param id_inspeccion int Id de la inspeccion a cancelar
 	 **/
	public function CancelarInspeccion
	(
		$id_inspeccion
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita una consignacion, ya sea que agregue o quite productos a la misma. La fecha se toma del sistema.
 	 *
 	 * @param id_consignacion int Id de la consignacion a editar
 	 * @param productos json Objeto que contendra los ids de los productos y sus cantidades que ahora tendra esta consignacion
 	 **/
	public function Editar
	(
		$id_consignacion, 
		$productos
	)
	{  
  
  
	}
  }
