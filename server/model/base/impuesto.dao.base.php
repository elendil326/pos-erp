<?php
/** Impuesto Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Impuesto }. 
  * @author Anonymous
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class ImpuestoDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_impuesto ){
			$pk = "";
			$pk .= $id_impuesto . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_impuesto){
			$pk = "";
			$pk .= $id_impuesto . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_impuesto ){
			$pk = "";
			$pk .= $id_impuesto . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Impuesto} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Impuesto [$impuesto] El objeto de tipo Impuesto
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$impuesto )
	{
		if( ! is_null ( self::getByPK(  $impuesto->getIdImpuesto() ) ) )
		{
			try{ return ImpuestoDAOBase::update( $impuesto) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return ImpuestoDAOBase::create( $impuesto) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Impuesto} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Impuesto} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Impuesto Un objeto del tipo {@link Impuesto}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_impuesto )
	{
		if(self::recordExists(  $id_impuesto)){
			return self::getRecord( $id_impuesto );
		}
		$sql = "SELECT * FROM impuesto WHERE (id_impuesto = ? ) LIMIT 1;";
		$params = array(  $id_impuesto );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Impuesto( $rs );
			self::pushRecord( $foo,  $id_impuesto );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Impuesto}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Impuesto}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from impuesto";
		if( ! is_null ( $orden ) )
		{ $sql .= " ORDER BY " . $orden . " " . $tipo_de_orden;	}
		if( ! is_null ( $pagina ) )
		{
			$sql .= " LIMIT " . (( $pagina - 1 )*$columnas_por_pagina) . "," . $columnas_por_pagina; 
		}
		global $conn;
		$rs = $conn->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
			$bar = new Impuesto($foo);
    		array_push( $allData, $bar);
			//id_impuesto
    		self::pushRecord( $bar, $foo["id_impuesto"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Impuesto} de la base de datos. 
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
	  * @param Impuesto [$impuesto] El objeto de tipo Impuesto
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $impuesto , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from impuesto WHERE ("; 
		$val = array();
		if( ! is_null( $impuesto->getIdImpuesto() ) ){
			$sql .= " id_impuesto = ? AND";
			array_push( $val, $impuesto->getIdImpuesto() );
		}

		if( ! is_null( $impuesto->getMontoPorcentaje() ) ){
			$sql .= " monto_porcentaje = ? AND";
			array_push( $val, $impuesto->getMontoPorcentaje() );
		}

		if( ! is_null( $impuesto->getEsMonto() ) ){
			$sql .= " es_monto = ? AND";
			array_push( $val, $impuesto->getEsMonto() );
		}

		if( ! is_null( $impuesto->getNombre() ) ){
			$sql .= " nombre = ? AND";
			array_push( $val, $impuesto->getNombre() );
		}

		if( ! is_null( $impuesto->getDescripcion() ) ){
			$sql .= " descripcion = ? AND";
			array_push( $val, $impuesto->getDescripcion() );
		}

		if(sizeof($val) == 0){return array();}
		$sql = substr($sql, 0, -3) . " )";
		if( ! is_null ( $orderBy ) ){
		    $sql .= " order by " . $orderBy . " " . $orden ;
		
		}
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array();
		foreach ($rs as $foo) {
			$bar =  new Impuesto($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_impuesto"] );
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
	  * @param Impuesto [$impuesto] El objeto de tipo Impuesto a actualizar.
	  **/
	private static final function update( $impuesto )
	{
		$sql = "UPDATE impuesto SET  monto_porcentaje = ?, es_monto = ?, nombre = ?, descripcion = ? WHERE  id_impuesto = ?;";
		$params = array( 
			$impuesto->getMontoPorcentaje(), 
			$impuesto->getEsMonto(), 
			$impuesto->getNombre(), 
			$impuesto->getDescripcion(), 
			$impuesto->getIdImpuesto(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Impuesto suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Impuesto dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Impuesto [$impuesto] El objeto de tipo Impuesto a crear.
	  **/
	private static final function create( &$impuesto )
	{
		$sql = "INSERT INTO impuesto ( id_impuesto, monto_porcentaje, es_monto, nombre, descripcion ) VALUES ( ?, ?, ?, ?, ?);";
		$params = array( 
			$impuesto->getIdImpuesto(), 
			$impuesto->getMontoPorcentaje(), 
			$impuesto->getEsMonto(), 
			$impuesto->getNombre(), 
			$impuesto->getDescripcion(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $impuesto->setIdImpuesto( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Impuesto} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Impuesto}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda (los valores 0 y false no son tomados como NULL) .
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
	  * @param Impuesto [$impuesto] El objeto de tipo Impuesto
	  * @param Impuesto [$impuesto] El objeto de tipo Impuesto
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $impuestoA , $impuestoB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from impuesto WHERE ("; 
		$val = array();
		if( ( !is_null (($a = $impuestoA->getIdImpuesto()) ) ) & ( ! is_null ( ($b = $impuestoB->getIdImpuesto()) ) ) ){
				$sql .= " id_impuesto >= ? AND id_impuesto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " id_impuesto = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $impuestoA->getMontoPorcentaje()) ) ) & ( ! is_null ( ($b = $impuestoB->getMontoPorcentaje()) ) ) ){
				$sql .= " monto_porcentaje >= ? AND monto_porcentaje <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " monto_porcentaje = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $impuestoA->getEsMonto()) ) ) & ( ! is_null ( ($b = $impuestoB->getEsMonto()) ) ) ){
				$sql .= " es_monto >= ? AND es_monto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " es_monto = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $impuestoA->getNombre()) ) ) & ( ! is_null ( ($b = $impuestoB->getNombre()) ) ) ){
				$sql .= " nombre >= ? AND nombre <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " nombre = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $impuestoA->getDescripcion()) ) ) & ( ! is_null ( ($b = $impuestoB->getDescripcion()) ) ) ){
				$sql .= " descripcion >= ? AND descripcion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " descripcion = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		$sql = substr($sql, 0, -3) . " )";
		if( !is_null ( $orderBy ) ){
		    $sql .= " order by " . $orderBy . " " . $orden ;
		
		}
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array();
		foreach ($rs as $foo) {
    		array_push( $ar, new Impuesto($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Impuesto suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Impuesto [$impuesto] El objeto de tipo Impuesto a eliminar
	  **/
	public static final function delete( &$impuesto )
	{
		if( is_null( self::getByPK($impuesto->getIdImpuesto()) ) ) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM impuesto WHERE  id_impuesto = ?;";
		$params = array( $impuesto->getIdImpuesto() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
