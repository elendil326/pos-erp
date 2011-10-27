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
 	 * @param valor int Valor del billete
 	 * @param foto_billete string Url de la foto del billete
 	 * @param nombre string Nombre del billete, valor en texto, "cincuenta", "cien", etc
 	 * @param id_moneda int Id de la moneda a la que pertenece el billete
 	 **/
  static function EditarBillete
	(
		$id_billete, 
		$valor = "", 
		$foto_billete = "", 
		$nombre = "", 
		$id_moneda = ""
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
 	 * @param ordenar json Valor que determina el orden de la lista
 	 * @param activo bool Si este valor no es obtenido, se listaran tanto activos como inactivos, si es verdadero, se listaran solo los activos, si es falso, se listaran solo los inactivos
 	 * @return billetes json Lista de billetes
 	 **/
  static function ListaBillete
	(
		$ordenar = "", 
		$activo = ""
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un nuevo billete, se puede utilizar para monedas tambien.
 	 *
 	 * @param nombre string Nombre del billete, puede ser el valor en texto, "cincuenta", "cien", etc.
 	 * @param valor int Valor del billete
 	 * @param id_moneda int Id de la moneda a la que pertence el billete
 	 * @param foto_billete string Url de la foto del billete
 	 * @return id_billete int Id del billete autogenerado
 	 **/
  static function NuevoBillete
	(
		$nombre, 
		$valor, 
		$id_moneda, 
		$foto_billete = ""
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
		$nombre = "", 
		$simbolo = ""
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
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @param activo bool Si este valor no es recibido, se listaran tanto activos como inactivos, si es verdadero, se listaran solo los activos, si es falso, se listaran solo los inactivos.
 	 * @return monedas json Lista de monedas
 	 **/
  static function ListaMoneda
	(
		$orden = "", 
		$activo = ""
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
