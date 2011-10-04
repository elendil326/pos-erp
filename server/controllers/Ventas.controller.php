<?php
require_once("Ventas.interface.php");
/**
  *
  *
  *
  **/
	
  class VentasController implements IVentas{
  
  
	/**
 	 *
 	 *Realiza una nueva venta por arpillas. Este m?do tiene que llamarse en conjunto con el metodo api/ventas/nueva.
 	 *
 	 * @param peso_por_arpilla float Peso promedio por arpilla
 	 * @param merma_por_arpilla float La merma que resulto por arpilla
 	 * @param arpillas float Nmero de arpillas enviadas
 	 * @param peso_origen float Peso del embarque en el origen
 	 * @param fecha_origen string Fecha en la que se envo el embarque
 	 * @param peso_destino float Peso del embarque en el destino
 	 * @param id_venta int Id de la venta relacionada con esta entrega
 	 * @param productor string Nombre del productor
 	 * @param numero_de_viaje string Numero del viaje
 	 * @param folio string Folio de la entrega
 	 * @param total_origen float Valor del embarque segun el origen
 	 * @return id_venta_arpilla int Id autogenerado por la insercion
 	 **/
	public function Nueva_venta_arpillas
	(
		$peso_por_arpilla, 
		$merma_por_arpilla, 
		$arpillas, 
		$peso_origen, 
		$fecha_origen, 
		$peso_destino, 
		$id_venta, 
		$productor = null, 
		$numero_de_viaje = null, 
		$folio = null, 
		$total_origen = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista el detalle de una venta, se puede ordenar de acuerdo a sus atributos.
 	 *
 	 * @param id_venta int Id de la venta a revisar
 	 * @param ordenar json Valor que determina el orden de la lista
 	 * @return detalle_venta json Objeto que contiene el detalle de la venta.
 	 **/
	public function Detalle
	(
		$id_venta, 
		$ordenar
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista las ventas, puede filtrarse por empresa, sucursal, por el total, si estan liquidadas o no, por canceladas, y puede ordenarse por sus atributos.
 	 *
 	 * @param canceladas bool Si no se obtiene este valor, se listaran las ventas tanto canceladas como las que no, si es true, se listaran solo las ventas que estan canceladas, si es false, se listaran las ventas que no estan canceladas solamente.
 	 * @param ordenar json Valor que determinara la manera en que la lista sera ordenada.
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus ventas
 	 * @param id_sucursal int Id de la sucursal de la cuals e listaran sus ventas
 	 * @param total_superior_a float Si ese valor es obtenido, se listaran las ventas cuyo total sea superior al valor obtenido.
 	 * @param total_igual_a float Si este valor es obtenido, se listaran las ventas cuyo total sea igual al valor obtenido
 	 * @param total_inferior_a float Si este valor es obtenido, se listaran las empresas cuyo total sea inferior al valor obtenido.
 	 * @param liquidados bool Si este valor no es obtenido, se listaran tanto las ventas liquidadas, como las no liquidadas, si es true, se listaran solo las ventas liquidadas, si es false, se listaran las ventas no liquidadas solamente.
 	 * @return ventas json Objeto que contendra la lista de ventas
 	 **/
	public function Lista
	(
		$canceladas, 
		$ordenar, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$total_superior_a = null, 
		$total_igual_a = null, 
		$total_inferior_a = null, 
		$liquidados = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Metodo que cancela una venta
 	 *
 	 * @param id_venta string Id de la venta a cancelar
 	 **/
	public function Cancelar
	(
		$id_venta
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Muestra el detalle de una venta por arpilla. Este metodo no muestra los productos de una venta, sino los detalles del embarque de la misma. Para ver los productos de una venta refierase a api/ventas/detalle
 	 *
 	 * @param id_venta int Id de la venta de la cual se listaran las ventas por arpilla
 	 * @return detalle_venta_arpilla json Objeto que contendra los detalles de las ventas por arpilla
 	 **/
	public function Detalle_venta_arpilla
	(
		$id_venta
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Genera una venta fuera de caja, puede usarse para que el administrador venda directamente a clientes especiales. EL usuario y la sucursal seran tomados de la sesion. La fecha se tomara del servidor. La empresa sera tomada del alamacen del que fueron tomados los productos
 	 *
 	 * @param total float Total de la venta
 	 * @param retencion float Monto aportado por retenciones
 	 * @param descuento float Monto descontado por descuentos
 	 * @param tipo_venta string Si esta es una venta a  credito o de contado
 	 * @param impuesto float Monto aportado por impuestos
 	 * @param subtotal float Subtotal de la venta antes de ser afectada por impuestos, descuentos y retenciones
 	 * @param id_comprador_venta int Id del usuario al que se le vende, si es a una sucursal, el id se pasa negativo
 	 * @param detalle_venta json Objeto que contendra los ids y las cantidades de los productos que se vendieron con el id almacen de donde fueron seleccionados  para determinar a que empresa pertenecen
 	 * @param datos_cheque json Si el tipo de pago fue en cheque, se pasan el nombre del banco, el monto y los ultimos 4 numeros de cada cheque que se uso para pagar la venta
 	 * @param saldo float Saldo que ha sido aportado a la venta
 	 * @param tipo_de_pago string Si la venta es pagada con tarjeta, con efectivo o con cheque
 	 * @return id_venta int Id autogenerado de la nueva venta
 	 **/
	public function Nueva
	(
		$total, 
		$retencion, 
		$descuento, 
		$tipo_venta, 
		$impuesto, 
		$subtotal, 
		$id_comprador_venta, 
		$detalle_venta, 
		$datos_cheque = null, 
		$saldo = null, 
		$tipo_de_pago = null
	)
	{  
  
  
	}
  }
