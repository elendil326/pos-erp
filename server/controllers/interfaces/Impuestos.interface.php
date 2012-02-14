<?php
/**
  *
  *
  *
  **/
	
  interface IImpuestos {
  
  
	/**
 	 *
 	 *Edita la informacion de un impuesto
 	 *
 	 * @param aplicacion string Aplica a ventas (0), compras (1), todas (2)
 	 * @param codigo string Código del impuesto
 	 * @param descripcion string Puede usarse para poner la descripcin de un impuesto o un cdigo.
 	 * @param id_impuesto int Id del impuesto a editar
 	 * @param importe float Para impuestos de tipo porcentaje, introdusca valor % entre 0-1
 	 * @param impuestos_en_hijos bool Indica si el calculo de impuestos se basa en el calculo de los impuestos hijos en lugar del importe total.
 	 * @param impuestos_hijos json En caso de que el impuesto tenga.
 	 * @param incluido_en_importe_base bool Indica si el importe del impuesto debe ser incluido en el importe base para el calculo de los siguientes impuestos
 	 * @param secuencia int Es usado para ordenar las lineas de impuestos de menor a mayor secuencia. El orden es importante si un impuesto tiene varios impuestos hijos. En este caso, el orden de evaluacin es importante.
 	 * @param tipo int El mtodo de calculo del importe del impuesto. Porcentaje (0), Importe fijo (1), ninguno (2), saldo pendiente (3)
 	 * @param nombre string Nombre del impuesto
 	 **/
  static function Editar
	(
		$aplicacion, 
		$codigo, 
		$descripcion, 
		$id_impuesto, 
		$importe, 
		$impuestos_en_hijos, 
		$impuestos_hijos, 
		$incluido_en_importe_base, 
		$secuencia, 
		$tipo, 
		$nombre = null
	);  
  
  
	
  
	/**
 	 *
 	 *Listas los impuestos
 	 *
 	 * @param limit string Indica hasta que registro se desea obtener a partir del conjunto de resultados productos de la bsqueda.
 	 * @param query string Valor que se buscara en la consulta
 	 * @param sort string Propiedad por la cual se ordenaran el conjunto de registros
 	 * @param start string Indica desde que registro se desea obtener a partir del conjunto de resultados productos de la bsqueda.
 	 * @return impuestos json Lista de impuestos
 	 **/
  static function Lista
	(
		$limit = null, 
		$query = null, 
		$sort = null, 
		$start = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crear un nuevo impuesto. Falta revisar bien lo de contabilidad, para saber como se van a ligar los impuestos con las cuentas, pero omitiendo las ligas con las cuentas seria esto.
 	 *
 	 * @param aplicacion int Aplica a ventas (0), compras (1), todas (2)
 	 * @param codigo string Cdigo del impuesto
 	 * @param importe float Para impuestos de tipo porcentaje, introdusca valor % entre 0-1
 	 * @param impuestos_en_hijos bool Indica si el calculo de impuestos se basa en el calculo de los impuestos hijos en lugar del importe total.
 	 * @param impuestos_hijos json En caso de que el impuesto tenga. (son objetos del mismo tipo)
 	 * @param incluido_en_importe_base bool Indica si el importe del impuesto debe ser incluido en el importe base para el calculo de los siguientes impuestos
 	 * @param nombre string Nombre del impuesto
 	 * @param secuencia int Es usado para ordenar las lineas de impuestos de menor a mayor secuencia. El orden es importante si un impuesto tiene varios impuestos hijos. En este caso, el orden de evaluacin es importante. 
 	 * @param tipo int El metodo de calculo del importe del impuesto. Porcentaje (0), Importe fijo (1), ninguno (2), saldo pendiente (3)
 	 * @param descripcion string Puede usarse para poner la descripcin de un impuesto o un cdigo. 
 	 * @return id_impuesto int Id del impuesto insertado.
 	 **/
  static function Nuevo
	(
		$aplicacion, 
		$codigo, 
		$importe, 
		$impuestos_en_hijos, 
		$impuestos_hijos, 
		$incluido_en_importe_base, 
		$nombre, 
		$secuencia, 
		$tipo, 
		$descripcion = null
	);  
  
  
	
  }
