<?php
/** Impresora Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Impresora }. 
  * @author Alan Gonzalez
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class ImpresoraDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_impresora ){
			$pk = "";
			$pk .= $id_impresora . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_impresora){
			$pk = "";
			$pk .= $id_impresora . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_impresora ){
			$pk = "";
			$pk .= $id_impresora . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Impresora} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Impresora [$impresora] El objeto de tipo Impresora
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$impresora )
	{
		if(  self::getByPK(  $impresora->getIdImpresora() ) !== NULL )
		{
			try{ return ImpresoraDAOBase::update( $impresora) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return ImpresoraDAOBase::create( $impresora) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Impresora} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Impresora} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Impresora Un objeto del tipo {@link Impresora}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_impresora )
	{
		if(self::recordExists(  $id_impresora)){
			return self::getRecord( $id_impresora );
		}
		$sql = "SELECT * FROM impresora WHERE (id_impresora = ? ) LIMIT 1;";
		$params = array(  $id_impresora );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Impresora( $rs );
			self::pushRecord( $foo,  $id_impresora );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Impresora}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Impresora}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from impresora";
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
			$bar = new Impresora($foo);
    		array_push( $allData, $bar);
			//id_impresora
    		self::pushRecord( $bar, $foo["id_impresora"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Impresora} de la base de datos. 
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
	  * @param Impresora [$impresora] El objeto de tipo Impresora
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $impresora , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from impresora WHERE ("; 
		$val = array();
		if( $impresora->getIdImpresora() != NULL){
			$sql .= " id_impresora = ? AND";
			array_push( $val, $impresora->getIdImpresora() );
		}

		if( $impresora->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $impresora->getIdSucursal() );
		}

		if( $impresora->getDescripcion() != NULL){
			$sql .= " descripcion = ? AND";
			array_push( $val, $impresora->getDescripcion() );
		}

		if( $impresora->getIdentificador() != NULL){
			$sql .= " identificador = ? AND";
			array_push( $val, $impresora->getIdentificador() );
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
			$bar =  new Impresora($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_impresora"] );
		}
		return $ar;
	}


	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cu‡ntas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Impresora [$impresora] El objeto de tipo Impresora a actualizar.
	  **/
	private static final function update( $impresora )
	{
		$sql = "UPDATE impresora SET  id_sucursal = ?, descripcion = ?, identificador = ? WHERE  id_impresora = ?;";
		$params = array( 
			$impresora->getIdSucursal(), 
			$impresora->getDescripcion(), 
			$impresora->getIdentificador(), 
			$impresora->getIdImpresora(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Impresora suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Impresora dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Impresora [$impresora] El objeto de tipo Impresora a crear.
	  **/
	private static final function create( &$impresora )
	{
		$sql = "INSERT INTO impresora ( id_impresora, id_sucursal, descripcion, identificador ) VALUES ( ?, ?, ?, ?);";
		$params = array( 
			$impresora->getIdImpresora(), 
			$impresora->getIdSucursal(), 
			$impresora->getDescripcion(), 
			$impresora->getIdentificador(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $impresora->setIdImpresora( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Impresora} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Impresora}.
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
	  * @param Impresora [$impresora] El objeto de tipo Impresora
	  * @param Impresora [$impresora] El objeto de tipo Impresora
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $impresoraA , $impresoraB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from impresora WHERE ("; 
		$val = array();
		if( (($a = $impresoraA->getIdImpresora()) != NULL) & ( ($b = $impresoraB->getIdImpresora()) != NULL) ){
				$sql .= " id_impresora >= ? AND id_impresora <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_impresora = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $impresoraA->getIdSucursal()) != NULL) & ( ($b = $impresoraB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $impresoraA->getDescripcion()) != NULL) & ( ($b = $impresoraB->getDescripcion()) != NULL) ){
				$sql .= " descripcion >= ? AND descripcion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descripcion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $impresoraA->getIdentificador()) != NULL) & ( ($b = $impresoraB->getIdentificador()) != NULL) ){
				$sql .= " identificador >= ? AND identificador <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " identificador = ? AND"; 
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
    		array_push( $ar, new Impresora($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Impresora suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Impresora [$impresora] El objeto de tipo Impresora a eliminar
	  **/
	public static final function delete( &$impresora )
	{
		if(self::getByPK($impresora->getIdImpresora()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM impresora WHERE  id_impresora = ?;";
		$params = array( $impresora->getIdImpresora() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
