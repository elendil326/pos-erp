<?php
/**
  *
  *
  *
  **/

  interaface ITransportacion y fletes {
  
  
	/**
 	 *
 	 *Ver los detalles e historial de un carro especifico
 	 *
 	 **/
	protected function Detalle();  
  
  
  
  
	/**
 	 *
 	 *Lista todos los carros de la instancia. Puede filtrarse por empresa, por su estado y ordenarse por sus atributos
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Crea un nuevo carro. La fecha de creacion sera tomada del servidor.
 	 *
 	 **/
	protected function Nuevo();  
  
  
  
  
	/**
 	 *
 	 *Realizar un cargamento a un carro. El id de la sucursal sera tomada de la sesion actual. La fecha sera tomada del servidor. El inventario de la sucursal que carga el camion se vera afectado por esta operacion.
 	 *
 	 **/
	protected function Cargar();  
  
  
  
  
	/**
 	 *
 	 *Descargar producto de un carro. El id de la sucursal se tomara de la sesion actual. La fecha se tomara del servidor. El almacen de la sucursal que realiza la operacion se vera afectada.
 	 *
 	 **/
	protected function Descargar();  
  
  
  
  
	/**
 	 *
 	 *Mover mercancia de un carro a otro. 
<h4>UPDATE</h4><br>
Se movera parcial o totalmente la carga?
 	 *
 	 **/
	protected function Transbordo();  
  
  
  
  
	/**
 	 *
 	 *Enviar un cargamento. No necesariamente debe tener cargamento. Seria excelente calcular el kilometraje. La sucursal origen sera tomada de la sesion actual.
 	 *
 	 **/
	protected function Enrutar();  
  
  
  
  
	/**
 	 *
 	 *Registra la llegada de un carro a una sucursal. La fecha sera tomada del servidor
 	 *
 	 **/
	protected function Registrar_llegada();  
  
  
  
  
	/**
 	 *
 	 *Edita la informacion de un carro
 	 *
 	 **/
	protected function Editar();  
  
  
  
  
	/**
 	 *
 	 *Agrega un nuevo tipo de carro ( camion, camioneta, etc)
 	 *
 	 **/
	protected function NuevoTipo();  
  
  
  
  
	/**
 	 *
 	 *Edita un registro de tipo de carro (camion, camioneta, etc)
 	 *
 	 **/
	protected function EditarTipo();  
  
  
  
  
	/**
 	 *
 	 *Crea un nuevo modelo de carro
 	 *
 	 **/
	protected function NuevoModelo();  
  
  
  
  
	/**
 	 *
 	 *Editar el modelo del carro
 	 *
 	 **/
	protected function EditarModelo();  
  
  
  
  
	/**
 	 *
 	 *Agrega una nueva marca de carro
 	 *
 	 **/
	protected function NuevoMarca();  
  
  
  
  
	/**
 	 *
 	 *Edita una marca de un carro
 	 *
 	 **/
	protected function EditarMarca();  
  
  
  
  }
