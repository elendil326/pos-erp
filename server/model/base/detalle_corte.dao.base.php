<?php
/** DetalleCorte Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link DetalleCorte }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class DetalleCorteDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleCorte} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$detalle_corte )
	{
		if( self::getByPK(  $detalle_corte->getNumCorte() , $detalle_corte->getNombre() ) === NULL )
		{
			return DetalleCorteDAOBase::create( $detalle_corte) ;
		}else{
			return DetalleCorteDAOBase::update( $detalle_corte) ;
		}
	}


	/**
	  *	Obtener {@link DetalleCorte} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleCorte} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link DetalleCorte}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $num_corte, $nombre )
	{
		$sql = "SELECT * FROM detalle_corte WHERE (num_corte = ?,nombre = ?) LIMIT 1;";
		$params = array(  $num_corte, $nombre );
		global $db;
		$rs = $db->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new DetalleCorte( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleCorte}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link DetalleCorte}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from detalle_corte ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new DetalleCorte($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCorte} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  $cliente = new Cliente();
	  *	  $cliente->setLimiteCredito("20000");
	  *	  $resultados = ClienteDAO::search($cliente);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte
	  **/
	public static final function search( $detalle_corte )
	{
		$sql = "SELECT * from detalle_corte WHERE ("; 
		$val = array();
		if( $detalle_corte->getNumCorte() != NULL){
			$sql .= " num_corte = ? AND";
			array_push( $val, $detalle_corte->getNumCorte() );
		}

		if( $detalle_corte->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $detalle_corte->getNombre() );
		}

		if( $detalle_corte->getTotal() != NULL){
			$sql .= " total = ? AND";
			array_push( $val, $detalle_corte->getTotal() );
		}

		if( $detalle_corte->getDeben() != NULL){
			$sql .= " deben = ? AND";
			array_push( $val, $detalle_corte->getDeben() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new DetalleCorte($foo));
		}
		return $allData;
	}


	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte a actualizar.
	  **/
	private static final function update( $detalle_corte )
	{
		$sql = "UPDATE detalle_corte SET  total = ?, deben = ? WHERE  num_corte = ? AND nombre = ?;";
		$params = array( 
			$detalle_corte->getTotal(), 
			$detalle_corte->getDeben(), 
			$detalle_corte->getNumCorte(),$detalle_corte->getNombre(), );
		global $db;
		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleCorte suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleCorte dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte a crear.
	  **/
	private static final function create( &$detalle_corte )
	{
		$sql = "INSERT INTO detalle_corte ( num_corte, nombre, total, deben ) VALUES ( ?, ?, ?, ?);";
		$params = array( 
			$detalle_corte->getNumCorte(), 
			$detalle_corte->getNombre(), 
			$detalle_corte->getTotal(), 
			$detalle_corte->getDeben(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$ar = $db->Affected_Rows();
		if($ar == 0) return 0;
		
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleCorte suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte a eliminar
	  **/
	public static final function delete( &$detalle_corte )
	{
		if(self::getByPK($detalle_corte->getNumCorte(), $detalle_corte->getNombre()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_corte WHERE  num_corte = ? AND nombre = ?;";
		$params = array( $detalle_corte->getNumCorte(), $detalle_corte->getNombre() );
		global $db;

		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


}
