<?php
require_once("interfaces/Impuestos.interface.php");
/**
  *
  *
  *
  **/
	
  class ImpuestosController implements IImpuestos{
  
	
	/**
	*
	*Crear un nuevo impuesto. Falta revisar bien lo de contabilidad, para saber como se van a ligar los impuestos con las cuentas, pero omitiendo las ligas con las cuentas seria esto.
	*
 	 * @param codigo string Cdigo del impuesto
 	 * @param importe float Para impuestos de tipo porcentaje, introdusca valor % entre 0-1
 	 * @param nombre string Nombre del impuesto
 	 * @param descripcion string Puede usarse para poner la descripcin de un impuesto o un cdigo. 
 	 * @param secuencia int Es usado para ordenar las lineas de impuestos de menor a mayor secuencia. El orden es importante si un impuesto tiene varios impuestos hijos. En este caso, el orden de evaluacin es importante. 
 	 * @param tipo int El metodo de calculo del importe del impuesto. Porcentaje (0), Importe fijo (1), ninguno (2), saldo pendiente (3)
 	 * @return id_impuesto int Id del impuesto insertado.
 	 **/
	public static function Nuevo
	(
		$monto_porcentaje, 
		$nombre, 
		$tipo = ""
	)
	{  
  		Logger::log("Nuevo impuesto...");

		if($monto_porcentaje > 1){
			throw new InvalidDataException( "Porcentaje debe expresarse en un rango de 0 a 1" );
		}

		$ni = new Impuesto();
		$ni->setMontoPorcentaje( $monto_porcentaje );
		$ni->setEsMonto( 0 );
		$ni->setNombre( $nombre );
		$ni->setDescripcion( $nombre );
  
		try{
			ImpuestoDAO::save($ni);
				
		}catch(Exception $e){
			throw new InvalidDatabaseException($e);
			
		}

		return array( "id_impuesto" => $ni->getIdImpuesto() );
	}
	
	/**
	*
	*Edita la informacion de un impuesto
	*
 	 **/
	public static function Editar
	(
		$id_impuesto, 
		$monto_porcentaje = null, 
		$nombre = null, 
		$tipo = null
	)
	{  
  
  
	}
	
	/**
	*
	*Listas los impuestos
	*
 	 * @param query string Valor que se buscara en la consulta
 	 * @return resultados json Lista de impuestos
 	 * @return numero_de_resultados int 
 	 **/
	public static function Lista
	(
		$query = null
	)
	{  
  		$i =ImpuestoDAO::getAll();
		
		return array(
			"resultados" => $i,
			"numero_de_resultados" => sizeof($i)
		);
  
	}
  }
