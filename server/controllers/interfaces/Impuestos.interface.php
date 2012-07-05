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
 	 * @param id_impuesto int Id del impuesto a editar
 	 * @param monto_porcentaje float Para impuestos de tipo porcentaje, introdusca valor % entre 0-1
 	 * @param nombre string Nombre del impuesto
 	 * @param tipo int El mtodo de calculo del importe del impuesto. Porcentaje (0), Importe fijo (1), ninguno (2), saldo pendiente (3)
 	 **/
  static function Editar
	(
		$id_impuesto, 
		$monto_porcentaje = null, 
		$nombre = null, 
		$tipo = null
	);  
  
  
	
  
	/**
 	 *
 	 *Listas los impuestos
 	 *
 	 * @param query string Valor que se buscara en la consulta
 	 * @return resultados json Lista de impuestos
 	 * @return numero_de_resultados int 
 	 **/
  static function Lista
	(
		$query = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crear un nuevo impuesto. Falta revisar bien lo de contabilidad, para saber como se van a ligar los impuestos con las cuentas, pero omitiendo las ligas con las cuentas seria esto.
 	 *
 	 * @param monto_porcentaje float Para impuestos de tipo porcentaje, introdusca valor % entre 0-1
 	 * @param nombre string Nombre del impuesto
 	 * @param tipo int El metodo de calculo del importe del impuesto. Porcentaje (0), Importe fijo (1), ninguno (2), saldo pendiente (3)
 	 * @return id_impuesto int Id del impuesto insertado.
 	 **/
  static function Nuevo
	(
		$monto_porcentaje, 
		$nombre, 
		$tipo = ""
	);  
  
  
	
  }
