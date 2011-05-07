<?php
/** PosConfig Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link PosConfig }. 
  * @author no author especified
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class PosConfigDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $opcion ){
			$pk = "";
			$pk .= $opcion . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $opcion){
			$pk = "";
			$pk .= $opcion . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $opcion ){
			$pk = "";
			$pk .= $opcion . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link PosConfig} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param PosConfig [$pos_config] El objeto de tipo PosConfig
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$pos_config )
	{
		if(  self::getByPK(  $pos_config->getOpcion() ) !== NULL )
		{
			try{ return PosConfigDAOBase::update( $pos_config) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return PosConfigDAOBase::create( $pos_config) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link PosConfig} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link PosConfig} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link PosConfig Un objeto del tipo {@link PosConfig}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $opcion )
	{
		if(self::recordExists(  $opcion)){
			return self::getRecord( $opcion );
		}
		$sql = "SELECT * FROM pos_config WHERE (opcion = ? ) LIMIT 1;";
		$params = array(  $opcion );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new PosConfig( $rs );
			self::pushRecord( $foo,  $opcion );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link PosConfig}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link PosConfig}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from pos_config";
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
			$bar = new PosConfig($foo);
    		array_push( $allData, $bar);
			//opcion
    		self::pushRecord( $bar, $foo["opcion"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PosConfig} de la base de datos. 
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
	  * @param PosConfig [$pos_config] El objeto de tipo PosConfig
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $pos_config , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from pos_config WHERE ("; 
		$val = array();
		if( $pos_config->getOpcion() != NULL){
			$sql .= " opcion = ? AND";
			array_push( $val, $pos_config->getOpcion() );
		}

		if( $pos_config->getValue() != NULL){
			$sql .= " value = ? AND";
			array_push( $val, $pos_config->getValue() );
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
			$bar =  new PosConfig($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["opcion"] );
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
	  * @param PosConfig [$pos_config] El objeto de tipo PosConfig a actualizar.
	  **/
	private static final function update( $pos_config )
	{
		$sql = "UPDATE pos_config SET  value = ? WHERE  opcion = ?;";
		$params = array( 
			$pos_config->getValue(), 
			$pos_config->getOpcion(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto PosConfig suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto PosConfig dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param PosConfig [$pos_config] El objeto de tipo PosConfig a crear.
	  **/
	private static final function create( &$pos_config )
	{
		$sql = "INSERT INTO pos_config ( opcion, value ) VALUES ( ?, ?);";
		$params = array( 
			$pos_config->getOpcion(), 
			$pos_config->getValue(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PosConfig} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link PosConfig}.
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
	  * @param PosConfig [$pos_config] El objeto de tipo PosConfig
	  * @param PosConfig [$pos_config] El objeto de tipo PosConfig
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $pos_configA , $pos_configB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from pos_config WHERE ("; 
		$val = array();
		if( (($a = $pos_configA->getOpcion()) != NULL) & ( ($b = $pos_configB->getOpcion()) != NULL) ){
				$sql .= " opcion >= ? AND opcion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " opcion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $pos_configA->getValue()) != NULL) & ( ($b = $pos_configB->getValue()) != NULL) ){
				$sql .= " value >= ? AND value <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " value = ? AND"; 
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
    		array_push( $ar, new PosConfig($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto PosConfig suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param PosConfig [$pos_config] El objeto de tipo PosConfig a eliminar
	  **/
	public static final function delete( &$pos_config )
	{
		if(self::getByPK($pos_config->getOpcion()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM pos_config WHERE  opcion = ?;";
		$params = array( $pos_config->getOpcion() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
