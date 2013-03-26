<?php
require_once("interfaces/FormasPreimpresas.interface.php");
/**
  *
  *
  *
  **/
	
  class FormasPreimpresasController implements IFormasPreimpresas{
  
	
	/**
	*
	*Crea un PDF Gen?rico a partir de un JSON
	*
 	 * @param formato json Formato que describe el formato del documento
 	 * @return status string ok
 	 **/
	public static function GenericoPdf
	(
		$formato
	)
	{  
  
  
	}
	
	/**
	*
	*Esta funcionalidad permite crear un archivo de Excel 2007 a partir de un JSON, tambi?n usando plantillas predefinidas con palabras clave
	*
 	 * @param archivo_salida string La ruta y el nombre del archivo que se Va a crear con esta funcionalidad
 	 * @param datos json Es un arreglo asociativo que contiene los valores que se van a insertar dentro del archivo de salida. Si se usa plantilla las llaves apuntar�n a las palabras clave del archivo, si no se usa plantilla, las llaves del arreglo apuntaran a las coordenadas d�nde se desea insertar dichos valores. Si solo se envia un arreglo se toma como cabeceras, pero el arrelgo puede contener otro arreglo, el primero contiene las filas y los arreglos internos las columnas
 	 * @param archivo_plantilla string Si se establece, permite definir la ubicaci�n de la plantilla a usar
 	 * @param imagenes json Contiene un arreglo con las im�genes que se van a insertar en el archivo de salida
 	 * @return estado string Devuelve el estado de la funci�n, 0 para correcto; En caso de haber un error, devuelve la cadena con este.
 	 **/
	public static function GenerarExcel
	(
		$archivo_salida, 
		$datos, 
		$archivo_plantilla = "", 
		$imagenes = ""
	)
	{  
  
  
	}
	
	/**
	*
	*Esta funcionalidad permite leer las palabras clave de un archivo (Plantilla) y las devuelve en forma de arreglo
	*
 	 * @param archivo_plantilla string Indica el archivo que se va a leer
 	 * @return estado string Devuelve el estado de la ejecuci�n, 0 en caso de todo correcto
 	 * @return datos json Devuelve el arreglo asociativo de palabras clave encontradas con sus respectivas coordenadas
 	 **/
	public static function LeerpcExcel
	(
		$archivo_plantilla
	)
	{  
                              
	}
  }
