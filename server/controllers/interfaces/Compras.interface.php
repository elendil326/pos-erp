<?php
/**
  *
  *
  *
  **/
	
  interface ICompras {
  
  
	/**
 	 *
 	 *Cancela una compra
 	 *
 	 * @param id_compra int Id de la compra a cancelar
 	 * @param id_caja int Id de la caja a la que regresara el dinero si es que la compra fue de contado y existe la posibilidad de que regrese el dinero
 	 * @param billetes json Arreglo de billetes que regresan a la caja en cas ode que la caja lleve un control de billetes
 	 **/
  static function Cancelar
	(
		$id_compra, 
		$id_caja = null, 
		$billetes = null
	);  
  
  
	
  
	/**
 	 *
 	 *Muestra el detalle de una compra
 	 *
 	 * @param id_compra int Id de la compra a detallar
 	 * @return detalle_compra json Objeto que contendra los productos con sus cantidades de esa compra
 	 **/
  static function Detalle
	(
		$id_compra
	);  
  
  
	
  
	/**
 	 *
 	 *Muestra el detalle de una compra por arpillas. Este detalle no es el detalle por producto, este muestra los detalles por embarque de la compra. Para el detalle por producto refierase a api/compras/detalle

Update : Todo este metodo esta mal, habria que definir nuevamente como se van a manejar las compras a los proveedores ya que como esta definido aqui solo funcionaria para el POS de las papas.
 	 *
 	 * @param id_compra int Id de la compra de la que se detallaran las compras por arpilla
 	 * @return detalle_compra_arpilla json Objeto que contendrá la información del detalle de la compra
 	 **/
  static function Detalle_compra_arpilla
	(
		$id_compra
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las compras. Se puede filtrar por empresa, sucursal, caja, usuario que registra la compra, usuario al que se le compra, tipo de compra, si estan pagadas o no, por tipo de pago, canceladas o no, por el total, por fecha, por el tipo de pago y se puede ordenar por sus atributos.
 	 *
 	 * @param tipo_pago string Se listaran las compras pagadas con cheque, tarjeta o efectivo de acuerdo a este valor
 	 * @param fecha_inicial string Se listaran las compras cuya fecha sea mayor a la indicada aqui
 	 * @param id_vendedor_compra int Se listaran las compras realizadas a este usuario(proveedor), si es sucursal su id sera negativo
 	 * @param tipo_compra string Si es a credito, se listaran las compras que sean a credito, si es de contado, se listaran las compras de contado
 	 * @param id_caja int Se listaran las compras registradas en esta caja
 	 * @param id_usuario int Se listaran las compras que ha registrado este usuario
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus compras
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus compras
 	 * @param fecha_final string Se listaran las compras cuya fecha sea menor a la indicada aqui
 	 * @param total_maximo float Se listaran las compras cuyo total sea menor a este
 	 * @param saldada bool Si este valor no es obtenido, se listaran las compras tanto saldadas como las que no lo estan. Si es verdadero se listaran solo las compras saldadas, si es falso, se listaran solo las compras que no lo estan
 	 * @param total_minimo float Se listaran las compras cuyo total sea mayor a este
 	 * @param cancelada bool Si este valor no es obtenido, se listaran tanto compras canceladas como no canceladas. Si es verdadero, se listaran solo las compras canceladas, si su valor es falso, se listaran solo las compras que no lo esten
 	 * @param orden string Nombre de la columna por la cual se ordenaran las compras
 	 * @return compras json Objeto que contendra la lista de las compras
 	 **/
  static function Lista
	(
		$tipo_pago = null, 
		$fecha_inicial = null, 
		$id_vendedor_compra = null, 
		$tipo_compra = null, 
		$id_caja = null, 
		$id_usuario = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$fecha_final = null, 
		$total_maximo = null, 
		$saldada = null, 
		$total_minimo = null, 
		$cancelada = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Registra una nueva compra, la compra puede ser a cualquier usuario, siendo este un cliente, proveedor, o cualquiera. La compra siempre viene acompa?anda de un detalle de productos que han sido comprados, y cada uno estipula a que almacen y a que sucursal iran a parar.
 	 *
 	 * @param subtotal float Total de la compra antes de impuestos y descuentos.
 	 * @param descuento float Monto descontado por descuentos
 	 * @param id_usuario_compra int Id usuario al que se le compra, si es a una sucursal, se pone el id en negativo
 	 * @param id_empresa int Id de la empresa a nombre de la cual se hace la compra
 	 * @param total float Total de la compra a pagar
 	 * @param detalle json JSON que comprende un arreglo del tipo:{    id_producto   : 8,    id_sucursal   : 5,    id_almacen    : 2,    costo_total   : 5.55,    cantidad      : 44}id_producto es el producto comprado.id_sucursal es la sucursal donde se dejara ese producto y id_sucursal es la sucursal donde se dejara de esa sucursal. El costo total es por cada producto. Esto es que se debe mandar el costo total de este producto, y la suma de todos los productos debe concocordar con total.
 	 * @param impuesto float Monto agregado por impuestos.
 	 * @param retencion float Monto agregado por retenciones
 	 * @param tipo_compra string Si la compra es a credito o de contado
 	 * @param tipo_de_pago string Si esta compra es a contado, debera especificarse el tipo de pago, este debe ser : tarjeta, checque, efectivo
 	 * @param cheques json Si el tipo de pago es con cheque, se almacena el nombre del banco, el monto y los ultimos 4 numeros del o de los cheques
 	 * @param saldo float Cantidad pagada de la 
 	 * @param id_sucursal int Id de la sucursal en nombre de la cual se hace la compra
 	 * @return id_compra int Id autogenerado por la inserción de la compra
 	 **/
  static function Nueva
	(
		$subtotal, 
		$descuento, 
		$id_usuario_compra, 
		$id_empresa, 
		$total, 
		$detalle, 
		$impuesto, 
		$retencion, 
		$tipo_compra, 
		$tipo_de_pago = null, 
		$cheques = null, 
		$saldo = 0, 
		$id_sucursal = null
	);  
  
  
	
  
	/**
 	 *
 	 *Registra una nueva compra por arpillas. Este metodo tiene que usarse en conjunto con el metodo api/compras/nueva
Update : Todo este metodo esta mal, habria que definir nuevamente como se van a manejar las compras a los proveedores ya que como esta definido aqui solo funcionaria para el POS de las papas.
 	 *
 	 * @param peso_por_arpilla float peso por arpilla
 	 * @param arpillas float numero de arpillas recibidas
 	 * @param peso_recibido float peso que se recibi en kg
 	 * @param id_compra int Id de la compra a la que pertenece esta compra por arpilla
 	 * @param total_origen float Lo que vale el embarque segun el proveedor
 	 * @param merma_por_arpilla float Merma por arpilla
 	 * @param numero_de_viaje string numero de viaje
 	 * @param folio string folio de la remision
 	 * @param peso_origen float Peso del embarque en el origen.
 	 * @param fecha_origen string Fecha de cuando salio el embarque del proveedor
 	 * @param productor string Nombre del productor
 	 * @return id_compra_arpilla int ID autogenerado por la insercion
 	 **/
  static function Nueva_compra_arpilla
	(
		$peso_por_arpilla, 
		$arpillas, 
		$peso_recibido, 
		$id_compra, 
		$total_origen, 
		$merma_por_arpilla, 
		$numero_de_viaje = null, 
		$folio = null, 
		$peso_origen = null, 
		$fecha_origen = null, 
		$productor = null
	);  
  
  
	
  }
