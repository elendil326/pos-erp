<?php
require_once("Efectivo.interface.php");
/**
  *
  *
  *
  **/
	
  class EfectivoController implements IEfectivo{
  
  
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
	public function NuevoBillete
	(
		$nombre, 
		$valor, 
		$id_moneda, 
		$foto_billete = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion de un billete
 	 *
 	 * @param id_billete int Id del billete a editar
 	 * @param nombre string Nombre del billete, valor en texto, "cincuenta", "cien", etc
 	 * @param valor int Valor del billete
 	 * @param id_moneda int Id de la moneda a la que pertenece el billete
 	 * @param foto_billete string Url de la foto del billete
 	 **/
	public function EditarBillete
	(
		$id_billete, 
		$nombre, 
		$valor, 
		$id_moneda, 
		$foto_billete = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Desactiva un billete
 	 *
 	 * @param id_billete int Id del billete a desactivar
 	 **/
	public function EliminarBillete
	(
		$id_billete
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista los billetes de una instancia
 	 *
 	 * @param ordenar json Valor que determina el orden de la lista
 	 * @param activo bool Si este valor no es obtenido, se listaran tanto activos como inactivos, si es verdadero, se listaran solo los activos, si es falso, se listaran solo los inactivos
 	 * @return billetes json Lista de billetes
 	 **/
	public function ListaBillete
	(
		$ordenar = null, 
		$activo = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crea una moneda, "pesos", "dolares", etc.
 	 *
 	 * @param nombre string Nombre de la moneda
 	 * @param simbolo string Simbolo de la moneda
 	 * @return id_moneda int Id de la moneda recien creada
 	 **/
	public function NuevaMoneda
	(
		$nombre, 
		$simbolo
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion de una moneda
 	 *
 	 * @param id_moneda int Id de la moneda a editar
 	 * @param nombre string Nombre de la moneda
 	 * @param simbolo string Simbolo de la moneda
 	 **/
	public function EditarMoneda
	(
		$id_moneda, 
		$nombre, 
		$simbolo
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Desactiva una moneda
 	 *
 	 * @param id_moneda int Id de la moneda a desactivar
 	 **/
	public function EliminarMoneda
	(
		$id_moneda
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista las monedas de una instancia
 	 *
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @param activo bool Si este valor no es recibido, se listaran tanto activos como inactivos, si es verdadero, se listaran solo los activos, si es falso, se listaran solo los inactivos.
 	 * @return monedas json Lista de monedas
 	 **/
	public function ListaMoneda
	(
		$orden = null, 
		$activo = null
	)
	{  
  
  
	}
  }
