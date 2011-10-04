<?php
/**
  *
  *
  *
  **/

  interaface IEfectivo {
  
  
	/**
 	 *
 	 *Crea un nuevo billete, se puede utilizar para monedas tambien.
 	 *
 	 **/
	protected function NuevoBillete();  
  
  
  
  
	/**
 	 *
 	 *Edita la informacion de un billete
 	 *
 	 **/
	protected function EditarBillete();  
  
  
  
  
	/**
 	 *
 	 *Desactiva un billete
 	 *
 	 **/
	protected function EliminarBillete();  
  
  
  
  
	/**
 	 *
 	 *Lista los billetes de una instancia
 	 *
 	 **/
	protected function ListaBillete();  
  
  
  
  
	/**
 	 *
 	 *Crea una moneda, "pesos", "dolares", etc.
 	 *
 	 **/
	protected function NuevaMoneda();  
  
  
  
  
	/**
 	 *
 	 *Edita la informacion de una moneda
 	 *
 	 **/
	protected function EditarMoneda();  
  
  
  
  
	/**
 	 *
 	 *Desactiva una moneda
 	 *
 	 **/
	protected function EliminarMoneda();  
  
  
  
  
	/**
 	 *
 	 *Lista las monedas de una instancia
 	 *
 	 **/
	protected function ListaMoneda();  
  
  
  
  }
