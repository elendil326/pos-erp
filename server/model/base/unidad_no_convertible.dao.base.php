<?php
/** UnidadNoConvertible Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link UnidadNoConvertible }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class UnidadNoConvertibleDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_unidad_no_convertible ){
			$pk = "";
			$pk .= $id_unidad_no_convertible . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_unidad_no_convertible){
			$pk = "";
			$pk .= $id_unidad_no_convertible . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_unidad_no_convertible ){
			$pk = "";
			$pk .= $id_unidad_no_convertible . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link UnidadNoConvertible} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param UnidadNoConvertible [$unidad_no_convertible] El objeto de tipo UnidadNoConvertible
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$unidad_no_convertible )
	{
		if(  self::getByPK(  $unidad_no_convertible->getIdUnidadNoConvertible() ) !== NULL )
		{
			try{ return UnidadNoConvertibleDAOBase::update( $unidad_no_convertible) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return UnidadNoConvertibleDAOBase::create( $unidad_no_convertible) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link UnidadNoConvertible} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link UnidadNoConvertible} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link UnidadNoConvertible Un objeto del tipo {@link UnidadNoConvertible}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_unidad_no_convertible )
	{
		if(self::recordExists(  $id_unidad_no_convertible)){
			return self::getRecord( $id_unidad_no_convertible );
		}
		$sql = "SELECT * FROM unidad_no_convertible WHERE (id_unidad_no_convertible = ? ) LIMIT 1;";
		$params = array(  $id_unidad_no_convertible );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new UnidadNoConvertible( $rs );
			self::pushRecord( $foo,  $id_unidad_no_convertible );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link UnidadNoConvertible}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link UnidadNoConvertible}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from unidad_no_convertible";
		if($orden != NULL)
		{ $sql .= " ORDER BY " . $orden . " " . $tipo_de_orden;	}
		if($pagina != NULL)
		{
			$sql .= " LIMIT " . (( $pagina - 1 )*$columnas_por_pagina) . "," . $columnas_por_pagina; 
		}
		global $conn;
		$rs = $conn->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
			$bar = new UnidadNoConvertible($foo);
    		array_push( $allData, $bar);
			//id_unidad_no_convertible
    		self::pushRecord( $bar, $foo["id_unidad_no_convertible"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link UnidadNoConvertible} de la base de datos. 
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
	  * @param UnidadNoConvertible [$unidad_no_convertible] El objeto de tipo UnidadNoConvertible
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $unidad_no_convertible , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from unidad_no_convertible WHERE ("; 
		$val = array();
		if( $unidad_no_convertible->getIdUnidadNoConvertible() != NULL){
			$sql .= " id_unidad_no_convertible = ? AND";
			array_push( $val, $unidad_no_convertible->getIdUnidadNoConvertible() );
		}

		if( $unidad_no_convertible->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $unidad_no_convertible->getNombre() );
		}

		if( $unidad_no_convertible->getDescripcion() != NULL){
			$sql .= " descripcion = ? AND";
			array_push( $val, $unidad_no_convertible->getDescripcion() );
		}

		if( $unidad_no_convertible->getActiva() != NULL){
			$sql .= " activa = ? AND";
			array_push( $val, $unidad_no_convertible->getActiva() );
		}

		if(sizeof($val) == 0){return array();}
		$sql = substr($sql, 0, -3) . " )";
		if( $orderBy !== null ){
		    $sql .= " order by " . $orderBy . " " . $orden ;
		
		}
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array();
		foreach ($rs as $foo) {
			$bar =  new UnidadNoConvertible($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_unidad_no_convertible"] );
		}
		return $ar;
	}


	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param UnidadNoConvertible [$unidad_no_convertible] El objeto de tipo UnidadNoConvertible a actualizar.
	  **/
	private static final function update( $unidad_no_convertible )
	{
		$sql = "UPDATE unidad_no_convertible SET  nombre = ?, descripcion = ?, activa = ? WHERE  id_unidad_no_convertible = ?;";
		$params = array( 
			$unidad_no_convertible->getNombre(), 
			$unidad_no_convertible->getDescripcion(), 
			$unidad_no_convertible->getActiva(), 
			$unidad_no_convertible->getIdUnidadNoConvertible(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto UnidadNoConvertible suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto UnidadNoConvertible dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param UnidadNoConvertible [$unidad_no_convertible] El objeto de tipo UnidadNoConvertible a crear.
	  **/
	private static final function create( &$unidad_no_convertible )
	{
		$sql = "INSERT INTO unidad_no_convertible ( id_unidad_no_convertible, nombre, descripcion, activa ) VALUES ( ?, ?, ?, ?);";
		$params = array( 
			$unidad_no_convertible->getIdUnidadNoConvertible(), 
			$unidad_no_convertible->getNombre(), 
			$unidad_no_convertible->getDescripcion(), 
			$unidad_no_convertible->getActiva(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $unidad_no_convertible->setIdUnidadNoConvertible( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link UnidadNoConvertible} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link UnidadNoConvertible}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param UnidadNoConvertible [$unidad_no_convertible] El objeto de tipo UnidadNoConvertible
	  * @param UnidadNoConvertible [$unidad_no_convertible] El objeto de tipo UnidadNoConvertible
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $unidad_no_convertibleA , $unidad_no_convertibleB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from unidad_no_convertible WHERE ("; 
		$val = array();
		if( (($a = $unidad_no_convertibleA->getIdUnidadNoConvertible()) != NULL) & ( ($b = $unidad_no_convertibleB->getIdUnidadNoConvertible()) != NULL) ){
				$sql .= " id_unidad_no_convertible >= ? AND id_unidad_no_convertible <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_unidad_no_convertible = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $unidad_no_convertibleA->getNombre()) != NULL) & ( ($b = $unidad_no_convertibleB->getNombre()) != NULL) ){
				$sql .= " nombre >= ? AND nombre <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " nombre = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $unidad_no_convertibleA->getDescripcion()) != NULL) & ( ($b = $unidad_no_convertibleB->getDescripcion()) != NULL) ){
				$sql .= " descripcion >= ? AND descripcion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descripcion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $unidad_no_convertibleA->getActiva()) != NULL) & ( ($b = $unidad_no_convertibleB->getActiva()) != NULL) ){
				$sql .= " activa >= ? AND activa <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " activa = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		$sql = substr($sql, 0, -3) . " )";
		if( $orderBy !== null ){
		    $sql .= " order by " . $orderBy . " " . $orden ;
		
		}
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array();
		foreach ($rs as $foo) {
    		array_push( $ar, new UnidadNoConvertible($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto UnidadNoConvertible suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param UnidadNoConvertible [$unidad_no_convertible] El objeto de tipo UnidadNoConvertible a eliminar
	  **/
	public static final function delete( &$unidad_no_convertible )
	{
		if(self::getByPK($unidad_no_convertible->getIdUnidadNoConvertible()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM unidad_no_convertible WHERE  id_unidad_no_convertible = ?;";
		$params = array( $unidad_no_convertible->getIdUnidadNoConvertible() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
