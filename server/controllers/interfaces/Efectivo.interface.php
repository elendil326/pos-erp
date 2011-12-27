<?php
/**
  *
  *
  *
  **/
	
  interface IEfectivo {
  
  
	/**
 	 *
 	 *Edita la informacion de un billete
 	 *
 	 * @param id_billete int Id del billete a editar
 	 * @param foto_billete string Url de la foto del billete
 	 * @param id_moneda int Id de la moneda a la que pertenece el billete
 	 * @param nombre string Nombre del billete, valor en texto, "cincuenta", "cien", etc
 	 * @param valor int Valor del billete
 	 **/
  static function EditarBillete
	(
		$id_billete, 
		$foto_billete = null, 
		$id_moneda = null, 
		$nombre = null, 
		$valor = null
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva un billete
 	 *
 	 * @param id_billete int Id del billete a desactivar
 	 **/
  static function EliminarBillete
	(
		$id_billete
	);  
  
  
	
  
	/**
 	 *
 	 *Lista los billetes de una instancia
 	 *
 	 * @param activo bool Si este valor no es obtenido, se listaran tanto activos como inactivos, si es verdadero, se listaran solo los activos, si es falso, se listaran solo los inactivos
 	 * @param ordenar json Valor que determina el orden de la lista
 	 * @return billetes json Lista de billetes
 	 **/
  static function ListaBillete
	(
		$activo = null, 
		$ordenar = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un nuevo billete, se puede utilizar para monedas tambien.
 	 *
 	 * @param id_moneda int Id de la moneda a la que pertence el billete
 	 * @param nombre string Nombre del billete, puede ser el valor en texto, "cincuenta", "cien", etc.
 	 * @param valor int Valor del billete
 	 * @param foto_billete string Url de la foto del billete
 	 * @return id_billete int Id del billete autogenerado
 	 **/
  static function NuevoBillete
	(
		$id_moneda, 
		$nombre, 
		$valor, 
		$foto_billete = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de una moneda
 	 *
 	 * @param id_moneda int Id de la moneda a editar
 	 * @param nombre string Nombre de la moneda
 	 * @param simbolo string Simbolo de la moneda
 	 **/
  static function EditarMoneda
	(
		$id_moneda, 
		$nombre = null, 
		$simbolo = null
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva una moneda
 	 *
 	 * @param id_moneda int Id de la moneda a desactivar
 	 **/
  static function EliminarMoneda
	(
		$id_moneda
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las monedas de una instancia
 	 *
 	 * @param activo bool Si este valor no es recibido, se listaran tanto activos como inactivos, si es verdadero, se listaran solo los activos, si es falso, se listaran solo los inactivos.
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @return monedas json Lista de monedas
 	 **/
  static function ListaMoneda
	(
		$activo = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea una moneda, "pesos", "dolares", etc.
 	 *
 	 * @param nombre string Nombre de la moneda
 	 * @param simbolo string Simbolo de la moneda
 	 * @return id_moneda int Id de la moneda recien creada
 	 **/
  static function NuevaMoneda
	(
		$nombre, 
		$simbolo
	);  
  
  
	
  }
