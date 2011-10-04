<?php
require_once("Contabilidad.interface.php");
/**
  *
  *
  *
  **/
	
  class ContabilidadController implements IContabilidad{
  
  
	/**
 	 *
 	 *Lista todas las facturas emitadas. Puede filtrarse por empresa, sucursal, estado y ordenarse por sus atributos 

<br/><br/><b>Update :</b> ¿Es correcto como se esta manejando el argumento id_sucursal? Ya que entiendo que de esta manera solo se estan obteniendo las facturas de una sola sucursal.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran las facturas
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @param activos bool Si este valor no es obtenido, se listaran tanto facturas activas como canceladas, si es true, se listaran solo las facturas activas, si es false se listaran solo las facturas canceladas
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran las facturas
 	 * @return facturas json Objeto que contendra la lista de facturas.
 	 **/
	public function ListaFacturas
	(
		$id_empresa = null, 
		$orden = null, 
		$activos = null, 
		$id_sucursal = null
	)
	{  
  
  
	}
  }
