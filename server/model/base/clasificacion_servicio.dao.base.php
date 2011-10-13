<?php
/** ClasificacionServicio Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link ClasificacionServicio }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class ClasificacionServicioDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_clasificacion_servicio ){
			$pk = "";
			$pk .= $id_clasificacion_servicio . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_clasificacion_servicio){
			$pk = "";
			$pk .= $id_clasificacion_servicio . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_clasificacion_servicio ){
			$pk = "";
			$pk .= $id_clasificacion_servicio . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link ClasificacionServicio} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param ClasificacionServicio [$clasificacion_servicio] El objeto de tipo ClasificacionServicio
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$clasificacion_servicio )
	{
		if(  self::getByPK(  $clasificacion_servicio->getIdClasificacionServicio() ) !== NULL )
		{
			try{ return ClasificacionServicioDAOBase::update( $clasificacion_servicio) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return ClasificacionServicioDAOBase::create( $clasificacion_servicio) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link ClasificacionServicio} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link ClasificacionServicio} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link ClasificacionServicio Un objeto del tipo {@link ClasificacionServicio}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_clasificacion_servicio )
	{
		if(self::recordExists(  $id_clasificacion_servicio)){
			return self::getRecord( $id_clasificacion_servicio );
		}
		$sql = "SELECT * FROM clasificacion_servicio WHERE (id_clasificacion_servicio = ? ) LIMIT 1;";
		$params = array(  $id_clasificacion_servicio );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new ClasificacionServicio( $rs );
			self::pushRecord( $foo,  $id_clasificacion_servicio );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ClasificacionServicio}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link ClasificacionServicio}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from clasificacion_servicio";
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
			$bar = new ClasificacionServicio($foo);
    		array_push( $allData, $bar);
			//id_clasificacion_servicio
    		self::pushRecord( $bar, $foo["id_clasificacion_servicio"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ClasificacionServicio} de la base de datos. 
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
	  * @param ClasificacionServicio [$clasificacion_servicio] El objeto de tipo ClasificacionServicio
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $clasificacion_servicio , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from clasificacion_servicio WHERE ("; 
		$val = array();
		if( $clasificacion_servicio->getIdClasificacionServicio() != NULL){
			$sql .= " id_clasificacion_servicio = ? AND";
			array_push( $val, $clasificacion_servicio->getIdClasificacionServicio() );
		}

		if( $clasificacion_servicio->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $clasificacion_servicio->getNombre() );
		}

		if( $clasificacion_servicio->getGarantia() != NULL){
			$sql .= " garantia = ? AND";
			array_push( $val, $clasificacion_servicio->getGarantia() );
		}

		if( $clasificacion_servicio->getDescripcion() != NULL){
			$sql .= " descripcion = ? AND";
			array_push( $val, $clasificacion_servicio->getDescripcion() );
		}

		if( $clasificacion_servicio->getMargenUtilidad() != NULL){
			$sql .= " margen_utilidad = ? AND";
			array_push( $val, $clasificacion_servicio->getMargenUtilidad() );
		}

		if( $clasificacion_servicio->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $clasificacion_servicio->getDescuento() );
		}

		if( $clasificacion_servicio->getActiva() != NULL){
			$sql .= " activa = ? AND";
			array_push( $val, $clasificacion_servicio->getActiva() );
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
			$bar =  new ClasificacionServicio($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_clasificacion_servicio"] );
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
	  * @param ClasificacionServicio [$clasificacion_servicio] El objeto de tipo ClasificacionServicio a actualizar.
	  **/
	private static final function update( $clasificacion_servicio )
	{
		$sql = "UPDATE clasificacion_servicio SET  nombre = ?, garantia = ?, descripcion = ?, margen_utilidad = ?, descuento = ?, activa = ? WHERE  id_clasificacion_servicio = ?;";
		$params = array( 
			$clasificacion_servicio->getNombre(), 
			$clasificacion_servicio->getGarantia(), 
			$clasificacion_servicio->getDescripcion(), 
			$clasificacion_servicio->getMargenUtilidad(), 
			$clasificacion_servicio->getDescuento(), 
			$clasificacion_servicio->getActiva(), 
			$clasificacion_servicio->getIdClasificacionServicio(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto ClasificacionServicio suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto ClasificacionServicio dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param ClasificacionServicio [$clasificacion_servicio] El objeto de tipo ClasificacionServicio a crear.
	  **/
	private static final function create( &$clasificacion_servicio )
	{
		$sql = "INSERT INTO clasificacion_servicio ( id_clasificacion_servicio, nombre, garantia, descripcion, margen_utilidad, descuento, activa ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$clasificacion_servicio->getIdClasificacionServicio(), 
			$clasificacion_servicio->getNombre(), 
			$clasificacion_servicio->getGarantia(), 
			$clasificacion_servicio->getDescripcion(), 
			$clasificacion_servicio->getMargenUtilidad(), 
			$clasificacion_servicio->getDescuento(), 
			$clasificacion_servicio->getActiva(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $clasificacion_servicio->setIdClasificacionServicio( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ClasificacionServicio} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link ClasificacionServicio}.
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
	  * @param ClasificacionServicio [$clasificacion_servicio] El objeto de tipo ClasificacionServicio
	  * @param ClasificacionServicio [$clasificacion_servicio] El objeto de tipo ClasificacionServicio
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $clasificacion_servicioA , $clasificacion_servicioB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from clasificacion_servicio WHERE ("; 
		$val = array();
		if( (($a = $clasificacion_servicioA->getIdClasificacionServicio()) !== NULL) & ( ($b = $clasificacion_servicioB->getIdClasificacionServicio()) !== NULL) ){
				$sql .= " id_clasificacion_servicio >= ? AND id_clasificacion_servicio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_clasificacion_servicio = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clasificacion_servicioA->getNombre()) !== NULL) & ( ($b = $clasificacion_servicioB->getNombre()) !== NULL) ){
				$sql .= " nombre >= ? AND nombre <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " nombre = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clasificacion_servicioA->getGarantia()) !== NULL) & ( ($b = $clasificacion_servicioB->getGarantia()) !== NULL) ){
				$sql .= " garantia >= ? AND garantia <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " garantia = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clasificacion_servicioA->getDescripcion()) !== NULL) & ( ($b = $clasificacion_servicioB->getDescripcion()) !== NULL) ){
				$sql .= " descripcion >= ? AND descripcion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " descripcion = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clasificacion_servicioA->getMargenUtilidad()) !== NULL) & ( ($b = $clasificacion_servicioB->getMargenUtilidad()) !== NULL) ){
				$sql .= " margen_utilidad >= ? AND margen_utilidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " margen_utilidad = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clasificacion_servicioA->getDescuento()) !== NULL) & ( ($b = $clasificacion_servicioB->getDescuento()) !== NULL) ){
				$sql .= " descuento >= ? AND descuento <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " descuento = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clasificacion_servicioA->getActiva()) !== NULL) & ( ($b = $clasificacion_servicioB->getActiva()) !== NULL) ){
				$sql .= " activa >= ? AND activa <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " activa = ? AND"; 
			$a = $a === NULL ? $b : $a;
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
    		array_push( $ar, new ClasificacionServicio($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto ClasificacionServicio suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param ClasificacionServicio [$clasificacion_servicio] El objeto de tipo ClasificacionServicio a eliminar
	  **/
	public static final function delete( &$clasificacion_servicio )
	{
		if(self::getByPK($clasificacion_servicio->getIdClasificacionServicio()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM clasificacion_servicio WHERE  id_clasificacion_servicio = ?;";
		$params = array( $clasificacion_servicio->getIdClasificacionServicio() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
