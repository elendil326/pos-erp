<?php
/**
  *
  *
  *
  **/
	
  interface IVentas {
  
  
	/**
 	 *
 	 *Metodo que cancela una venta
 	 *
 	 * @param id_venta int Id de la venta a cancelar
 	 * @param billetes json Arreglo de billetes que saldran de la caja si esta lleva control de los mismos
 	 * @param id_caja int Id de la caja a la cual saldra el dinero
 	 **/
  static function Cancelar
	(
		$id_venta, 
		$billetes = null, 
		$id_caja = null
	);  
  
  
	
  
	/**
 	 *
 	 *Lista el detalle de una venta, se puede ordenar de acuerdo a sus atributos.
 	 *
 	 * @param id_venta int Id de la venta a revisar
 	 * @return detalle_venta json Objeto que contiene el detalle de la venta.
 	 **/
  static function Detalle
	(
		$id_venta
	);  
  
  
	
  
	/**
 	 *
 	 *Muestra el detalle de una venta por arpilla. Este metodo no muestra los productos de una venta, sino los detalles del embarque de la misma. Para ver los productos de una venta refierase a api/ventas/detalle
 	 *
 	 * @param id_venta int Id de la venta de la cual se listaran las ventas por arpilla
 	 * @return detalle_venta_arpilla json Objeto que contendra los detalles de las ventas por arpilla
 	 **/
  static function Detalle_venta_arpilla
	(
		$id_venta
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las ventas, puede filtrarse por empresa, sucursal, por el total, si estan liquidadas o no, por canceladas, y puede ordenarse por sus atributos.
 	 *
 	 * @param canceladas bool Si no se obtiene este valor, se listaran las ventas tanto canceladas como las que no, si es true, se listaran solo las ventas que estan canceladas, si es false, se listaran las ventas que no estan canceladas solamente.
 	 * @param id_sucursal int Id de la sucursal de la cuals e listaran sus ventas
 	 * @param liquidados bool Si este valor no es obtenido, se listaran tanto las ventas liquidadas, como las no liquidadas, si es true, se listaran solo las ventas liquidadas, si es false, se listaran las ventas no liquidadas solamente.
 	 * @param ordenar string Nombre de la columan por el cual se ordenara la lista
 	 * @param total_igual_a float Si este valor es obtenido, se listaran las ventas cuyo total sea igual al valor obtenido
 	 * @param total_inferior_a float Si este valor es obtenido, se listaran las empresas cuyo total sea inferior al valor obtenido.
 	 * @param total_superior_a float Si ese valor es obtenido, se listaran las ventas cuyo total sea superior al valor obtenido.
 	 * @return ventas json Objeto que contendra la lista de ventas
 	 **/
  static function Lista
	(
		$canceladas = null, 
		$id_sucursal = null, 
		$liquidados = null, 
		$ordenar = null, 
		$total_igual_a = null, 
		$total_inferior_a = null, 
		$total_superior_a = null
	);  
  
  
	
  
	/**
 	 *
 	 *Genera una venta fuera de caja, puede usarse para que el administrador venda directamente a clientes especiales. EL usuario y la sucursal seran tomados de la sesion. La fecha se tomara del servidor. La empresa sera tomada del alamacen del que fueron tomados los productos.

Si hay dos productos en una misma sucursal pero disntintos almacenes entonces se intentara nivelar los almacenes al mismo valor.
 	 *
 	 * @param descuento float Monto descontado por descuentos
 	 * @param id_comprador_venta int Id del usuario al que se le vende, si es a una sucursal, el id se pasa negativo
 	 * @param id_sucursal int Id de la sucursal de la cual se tomaran los productos.
 	 * @param impuesto float Monto aportado por impuestos
 	 * @param retencion float Monto aportado por retenciones
 	 * @param subtotal float Subtotal de la venta antes de ser afectada por impuestos, descuentos y retenciones
 	 * @param tipo_venta string Si esta es una venta a  credito o de contado
 	 * @param total float Total de la venta
 	 * @param datos_cheque json Si el tipo de pago fue en cheque, se pasan el nombre del banco, el monto y los ultimos 4 numeros de cada cheque que se uso para pagar la venta
 	 * @param detalle_orden json Objetos que contendran los ids y las cantidades de las ordenes que se venden
 	 * @param detalle_paquete json Arreglo de ids de los paquetes con sus cantidades que se venden
 	 * @param detalle_venta json {            id_producto: 5,            cantidad: 1,            precio: 5,            descuento: 0,            impuesto: 0,            retencion: 0,            id_unidad: 1}Un arreglo en forma de json co los parametros de cada producto.
 	 * @param saldo float Saldo que ha sido aportado a la venta
 	 * @param tipo_de_pago string Si la venta es pagada con tarjeta, con efectivo o con cheque
 	 * @return id_venta int Id autogenerado de la nueva venta
 	 **/
  static function Nueva
	(
		$descuento, 
		$id_comprador_venta, 
		$id_sucursal, 
		$impuesto, 
		$retencion, 
		$subtotal, 
		$tipo_venta, 
		$total, 
		$datos_cheque = null, 
		$detalle_orden = null, 
		$detalle_paquete = null, 
		$detalle_venta = null, 
		$saldo = 0, 
		$tipo_de_pago = null
	);  
  
  
	
  
	/**
 	 *
 	 *Realiza una nueva venta por arpillas. Este m?todo tiene que llamarse en conjunto con el metodo api/ventas/nueva.
 	 *
 	 * @param arpillas float Nmero de arpillas enviadas
 	 * @param fecha_origen string Fecha en la que se envo el embarque
 	 * @param id_venta int Id de la venta relacionada con esta entrega
 	 * @param merma_por_arpilla float La merma que resulto por arpilla
 	 * @param peso_destino float Peso del embarque en el destino
 	 * @param peso_origen float Peso del embarque en el origen
 	 * @param peso_por_arpilla float Peso promedio por arpilla
 	 * @param folio string Folio de la entrega
 	 * @param numero_de_viaje string Numero del viaje
 	 * @param productor string Nombre del productor
 	 * @param total_origen float Valor del embarque segun el origen
 	 * @return id_venta_arpilla int Id autogenerado por la insercion
 	 **/
  static function Nueva_venta_arpillas
	(
		$arpillas, 
		$fecha_origen, 
		$id_venta, 
		$merma_por_arpilla, 
		$peso_destino, 
		$peso_origen, 
		$peso_por_arpilla, 
		$folio = null, 
		$numero_de_viaje = null, 
		$productor = null, 
		$total_origen = null
	);  
  
  
	
  }
