<?php
/**
  *
  *
  *
  **/

  interaface IContabilidad {
  
  
	/**
 	 *
 	 *Lista todas las facturas emitadas. Puede filtrarse por empresa, sucursal, estado y ordenarse por sus atributos 

<br/><br/><b>Update :</b> ¿Es correcto como se esta manejando el argumento id_sucursal? Ya que entiendo que de esta manera solo se estan obteniendo las facturas de una sola sucursal.
 	 *
 	 **/
	protected function ListaFacturas();  
  
  
  
  }
