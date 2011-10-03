<?php
/**
  *
  *
  *
  **/

  interaface ISucursales {
  
  
	/**
 	 *
 	 *Creara un nuevo almacen en una sucursal, este almacen contendra lotes.
 	 *
 	 **/
	protected function NuevoAlmacen();  
  
  
  
  
	/**
 	 *
 	 *listar almacenes de la isntancia. Se pueden filtrar por empresa, por sucursal, por tipo de almacen, por activos e inactivos y ordenar por sus atributos.
 	 *
 	 **/
	protected function ListaAlmacen();  
  
  
  
  
	/**
 	 *
 	 *Vender productos desde el mostrador de una sucursal. Cualquier producto vendido aqui sera descontado del inventario de esta sucursal. La fecha será tomada del servidor, el usuario y la sucursal serán tomados del servidor. La ip será tomada de la máquina que manda a llamar al método. El valor del campo liquidada dependerá de los campos total y pagado. La empresa se tomara del alamcen de donde salieron los productos
 	 *
 	 **/
	protected function VenderCaja();  
  
  
  
  
	/**
 	 *
 	 *Comprar productos en mostrador. No debe confundirse con comprar productos a un proveedor. Estos productos se agregaran al inventario de esta sucursal de manera automatica e instantanea. La IP será tomada de la máquina que realiza la compra. El usuario y la sucursal serán tomados de la sesion activa. El estado del campo liquidada será tomado de acuerdo al campo total y pagado.
 	 *
 	 **/
	protected function ComprarCaja();  
  
  
  
  
	/**
 	 *
 	 *Lista las sucursales relacionadas con esta instancia. Se puede filtrar por empresa,  saldo inferior o superior a, fecha de apertura, ordenar por fecha de apertura u ordenar por saldo. Se agregará un link en cada una para poder acceder a su detalle.
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Valida si una maquina que realizara peticiones al servidor pertenece a una sucursal.
 	 *
 	 **/
	protected function AbrirCaja();  
  
  
  
  
	/**
 	 *
 	 *Metodo que crea una nueva sucursal
 	 *
 	 **/
	protected function Nueva();  
  
  
  
  
	/**
 	 *
 	 *Edita los datos de una sucursal
 	 *
 	 **/
	protected function Editar();  
  
  
  
  
	/**
 	 *
 	 *Edita la gerencia de una sucursal
 	 *
 	 **/
	protected function EditarGerencia();  
  
  
  
  
	/**
 	 *
 	 *Hace un corte en los flujos de dinero de la sucursal. El Id de la sucursal se tomara de la sesion actual. La fehca se tomara del servidor.
 	 *
 	 **/
	protected function CerrarCaja();  
  
  
  
  
	/**
 	 *
 	 *Metodo que surte una sucursal por parte de un proveedor. La sucursal sera tomada de la sesion actual.

<h2>Update</h2>
Creo que este metodo tiene que estar bajo sucursal.
 	 *
 	 **/
	protected function EntradaAlmacen();  
  
  
  
  
	/**
 	 *
 	 *Este metodo creara una caja asociada a una sucursal. Debe haber una caja por CPU. 
 	 *
 	 **/
	protected function NuevaCaja();  
  
  
  
  
	/**
 	 *
 	 *Envia productos fuera del almacen. Ya sea que sea un traspaso de un alamcen a otro o por motivos de inventarios fisicos.
 	 *
 	 **/
	protected function SalidaAlmacen();  
  
  
  
  
	/**
 	 *
 	 *Realiza un corte de caja. Este metodo reduce el dinero de la caja y va registrando el dinero acumulado de esa caja. Si faltase dinero se carga una deuda al cajero. La fecha sera tomada del servidor. El usuario sera tomado de la sesion.
 	 *
 	 **/
	protected function CorteCaja();  
  
  
  
  
	/**
 	 *
 	 *Descativa un almacen. Para poder desactivar un almacen, este tiene que estar vacío
 	 *
 	 **/
	protected function EliminarAlmacen();  
  
  
  
  
	/**
 	 *
 	 *Edita la informacion de un almacen
 	 *
 	 **/
	protected function EditarAlmacen();  
  
  
  
  
	/**
 	 *
 	 *Calcula el saldo esperado para una caja a partir de los cortes que le han realizado, la fecha de apertura y la fecha en que se realiza el calculo. La caja sera tomada de la sesion, la fecha sera tomada del servidor. Para poder realizar este metodo, la caja tiene que estar abierta
 	 *
 	 **/
	protected function Calcular_saldo_esperadoCaja();  
  
  
  
  
	/**
 	 *
 	 *Edita la informacion de una caja
 	 *
 	 **/
	protected function EditarCaja();  
  
  
  
  
	/**
 	 *
 	 *Desactiva una caja, para que la caja pueda ser desactivada, tieneq ue estar cerrada
 	 *
 	 **/
	protected function EliminarCaja();  
  
  
  
  
	/**
 	 *
 	 *Desactiva una sucursal. Para poder desactivar una sucursal su saldo a favor tiene que ser mayor a cero y sus almacenes tienen que estar vacios.
 	 *
 	 **/
	protected function Eliminar();  
  
  
  
  
	/**
 	 *
 	 *Crea un registro de traspaso de producto de un almacen a otro. El usuario que envia sera tomada de la sesion.
 	 *
 	 **/
	protected function ProgramarTraspasoAlmacen();  
  
  
  
  
	/**
 	 *
 	 *Cambia el estado del traspaso a enviado y captura la fecha de envio del servidor. El usuario que envia sera tomado del servidor.
 	 *
 	 **/
	protected function EnviarTraspasoAlmacen();  
  
  
  
  
	/**
 	 *
 	 *Cambia el estado de un traspaso a recibido. La  bandera de completo se prende si los productos enviados son los mismos que los recibidos. La fecha de recibo es tomada del servidor. El usuario que recibe sera tomada de la sesion actual.
 	 *
 	 **/
	protected function RecibirTraspasoAlmacen();  
  
  
  
  
	/**
 	 *
 	 *Para poder cancelar un traspaso, este no tuvo que haber sido enviado aun.
 	 *
 	 **/
	protected function CancelarTraspasoAlmacen();  
  
  
  
  
	/**
 	 *
 	 *Lista los traspasos de almacenes. Puede filtrarse por empresa, por sucursal, por almacen, por producto, cancelados, completos, estado
 	 *
 	 **/
	protected function ListaTraspasoAlmacen();  
  
  
  
  
	/**
 	 *
 	 *Para poder editar un traspaso,este no tuvo que haber sido enviado aun
 	 *
 	 **/
	protected function EditarTraspasoAlmacen();  
  
  
  
  }
