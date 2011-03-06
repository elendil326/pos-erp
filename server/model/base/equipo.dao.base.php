<?php
/** Equipo Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Equipo }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class EquipoDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_equipo ){
			$pk = "";
			$pk .= $id_equipo . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_equipo){
			$pk = "";
			$pk .= $id_equipo . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_equipo ){
			$pk = "";
			$pk .= $id_equipo . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Equipo} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Equipo [$equipo] El objeto de tipo Equipo
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$equipo )
	{
		if(  self::getByPK(  $equipo->getIdEquipo() ) !== NULL )
		{
			try{ return EquipoDAOBase::update( $equipo) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return EquipoDAOBase::create( $equipo) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Equipo} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Equipo} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Equipo Un objeto del tipo {@link Equipo}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_equipo )
	{
		if(self::recordExists(  $id_equipo)){
			return self::getRecord( $id_equipo );
		}
		$sql = "SELECT * FROM equipo WHERE (id_equipo = ? ) LIMIT 1;";
		$params = array(  $id_equipo );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Equipo( $rs );
			self::pushRecord( $foo,  $id_equipo );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Equipo}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Equipo}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from equipo";
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
			$bar = new Equipo($foo);
    		array_push( $allData, $bar);
			//id_equipo
    		self::pushRecord( $bar, $foo["id_equipo"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Equipo} de la base de datos. 
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
	  * @param Equipo [$equipo] El objeto de tipo Equipo
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $equipo , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from equipo WHERE ("; 
		$val = array();
		if( $equipo->getIdEquipo() != NULL){
			$sql .= " id_equipo = ? AND";
			array_push( $val, $equipo->getIdEquipo() );
		}

		if( $equipo->getToken() != NULL){
			$sql .= " token = ? AND";
			array_push( $val, $equipo->getToken() );
		}

		if( $equipo->getFullUa() != NULL){
			$sql .= " full_ua = ? AND";
			array_push( $val, $equipo->getFullUa() );
		}

		if( $equipo->getDescripcion() != NULL){
			$sql .= " descripcion = ? AND";
			array_push( $val, $equipo->getDescripcion() );
		}

		if( $equipo->getLocked() != NULL){
			$sql .= " locked = ? AND";
			array_push( $val, $equipo->getLocked() );
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
			$bar =  new Equipo($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_equipo"] );
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
	  * @param Equipo [$equipo] El objeto de tipo Equipo a actualizar.
	  **/
	private static final function update( $equipo )
	{
		$sql = "UPDATE equipo SET  token = ?, full_ua = ?, descripcion = ?, locked = ? WHERE  id_equipo = ?;";
		$params = array( 
			$equipo->getToken(), 
			$equipo->getFullUa(), 
			$equipo->getDescripcion(), 
			$equipo->getLocked(), 
			$equipo->getIdEquipo(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Equipo suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Equipo dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Equipo [$equipo] El objeto de tipo Equipo a crear.
	  **/
	private static final function create( &$equipo )
	{
		$sql = "INSERT INTO equipo ( id_equipo, token, full_ua, descripcion, locked ) VALUES ( ?, ?, ?, ?, ?);";
		$params = array( 
			$equipo->getIdEquipo(), 
			$equipo->getToken(), 
			$equipo->getFullUa(), 
			$equipo->getDescripcion(), 
			$equipo->getLocked(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $equipo->setIdEquipo( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Equipo} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Equipo}.
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
	  * @param Equipo [$equipo] El objeto de tipo Equipo
	  * @param Equipo [$equipo] El objeto de tipo Equipo
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $equipoA , $equipoB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from equipo WHERE ("; 
		$val = array();
		if( (($a = $equipoA->getIdEquipo()) != NULL) & ( ($b = $equipoB->getIdEquipo()) != NULL) ){
				$sql .= " id_equipo >= ? AND id_equipo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_equipo = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $equipoA->getToken()) != NULL) & ( ($b = $equipoB->getToken()) != NULL) ){
				$sql .= " token >= ? AND token <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " token = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $equipoA->getFullUa()) != NULL) & ( ($b = $equipoB->getFullUa()) != NULL) ){
				$sql .= " full_ua >= ? AND full_ua <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " full_ua = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $equipoA->getDescripcion()) != NULL) & ( ($b = $equipoB->getDescripcion()) != NULL) ){
				$sql .= " descripcion >= ? AND descripcion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descripcion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $equipoA->getLocked()) != NULL) & ( ($b = $equipoB->getLocked()) != NULL) ){
				$sql .= " locked >= ? AND locked <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " locked = ? AND"; 
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
    		array_push( $ar, new Equipo($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Equipo suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Equipo [$equipo] El objeto de tipo Equipo a eliminar
	  **/
	public static final function delete( &$equipo )
	{
		if(self::getByPK($equipo->getIdEquipo()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM equipo WHERE  id_equipo = ?;";
		$params = array( $equipo->getIdEquipo() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
