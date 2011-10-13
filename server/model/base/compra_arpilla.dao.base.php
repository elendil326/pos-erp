<?php
/** CompraArpilla Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link CompraArpilla }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class CompraArpillaDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_compra_arpilla ){
			$pk = "";
			$pk .= $id_compra_arpilla . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_compra_arpilla){
			$pk = "";
			$pk .= $id_compra_arpilla . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_compra_arpilla ){
			$pk = "";
			$pk .= $id_compra_arpilla . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CompraArpilla} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param CompraArpilla [$compra_arpilla] El objeto de tipo CompraArpilla
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$compra_arpilla )
	{
		if(  self::getByPK(  $compra_arpilla->getIdCompraArpilla() ) !== NULL )
		{
			try{ return CompraArpillaDAOBase::update( $compra_arpilla) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return CompraArpillaDAOBase::create( $compra_arpilla) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link CompraArpilla} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CompraArpilla} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CompraArpilla Un objeto del tipo {@link CompraArpilla}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_compra_arpilla )
	{
		if(self::recordExists(  $id_compra_arpilla)){
			return self::getRecord( $id_compra_arpilla );
		}
		$sql = "SELECT * FROM compra_arpilla WHERE (id_compra_arpilla = ? ) LIMIT 1;";
		$params = array(  $id_compra_arpilla );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new CompraArpilla( $rs );
			self::pushRecord( $foo,  $id_compra_arpilla );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CompraArpilla}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link CompraArpilla}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from compra_arpilla";
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
			$bar = new CompraArpilla($foo);
    		array_push( $allData, $bar);
			//id_compra_arpilla
    		self::pushRecord( $bar, $foo["id_compra_arpilla"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraArpilla} de la base de datos. 
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
	  * @param CompraArpilla [$compra_arpilla] El objeto de tipo CompraArpilla
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $compra_arpilla , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from compra_arpilla WHERE ("; 
		$val = array();
		if( $compra_arpilla->getIdCompraArpilla() != NULL){
			$sql .= " id_compra_arpilla = ? AND";
			array_push( $val, $compra_arpilla->getIdCompraArpilla() );
		}

		if( $compra_arpilla->getIdCompra() != NULL){
			$sql .= " id_compra = ? AND";
			array_push( $val, $compra_arpilla->getIdCompra() );
		}

		if( $compra_arpilla->getPesoOrigen() != NULL){
			$sql .= " peso_origen = ? AND";
			array_push( $val, $compra_arpilla->getPesoOrigen() );
		}

		if( $compra_arpilla->getFechaOrigen() != NULL){
			$sql .= " fecha_origen = ? AND";
			array_push( $val, $compra_arpilla->getFechaOrigen() );
		}

		if( $compra_arpilla->getFolio() != NULL){
			$sql .= " folio = ? AND";
			array_push( $val, $compra_arpilla->getFolio() );
		}

		if( $compra_arpilla->getNumeroDeViaje() != NULL){
			$sql .= " numero_de_viaje = ? AND";
			array_push( $val, $compra_arpilla->getNumeroDeViaje() );
		}

		if( $compra_arpilla->getPesoRecibido() != NULL){
			$sql .= " peso_recibido = ? AND";
			array_push( $val, $compra_arpilla->getPesoRecibido() );
		}

		if( $compra_arpilla->getArpillas() != NULL){
			$sql .= " arpillas = ? AND";
			array_push( $val, $compra_arpilla->getArpillas() );
		}

		if( $compra_arpilla->getPesoPorArpilla() != NULL){
			$sql .= " peso_por_arpilla = ? AND";
			array_push( $val, $compra_arpilla->getPesoPorArpilla() );
		}

		if( $compra_arpilla->getProductor() != NULL){
			$sql .= " productor = ? AND";
			array_push( $val, $compra_arpilla->getProductor() );
		}

		if( $compra_arpilla->getMermaPorArpilla() != NULL){
			$sql .= " merma_por_arpilla = ? AND";
			array_push( $val, $compra_arpilla->getMermaPorArpilla() );
		}

		if( $compra_arpilla->getTotalOrigen() != NULL){
			$sql .= " total_origen = ? AND";
			array_push( $val, $compra_arpilla->getTotalOrigen() );
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
			$bar =  new CompraArpilla($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_compra_arpilla"] );
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
	  * @param CompraArpilla [$compra_arpilla] El objeto de tipo CompraArpilla a actualizar.
	  **/
	private static final function update( $compra_arpilla )
	{
		$sql = "UPDATE compra_arpilla SET  id_compra = ?, peso_origen = ?, fecha_origen = ?, folio = ?, numero_de_viaje = ?, peso_recibido = ?, arpillas = ?, peso_por_arpilla = ?, productor = ?, merma_por_arpilla = ?, total_origen = ? WHERE  id_compra_arpilla = ?;";
		$params = array( 
			$compra_arpilla->getIdCompra(), 
			$compra_arpilla->getPesoOrigen(), 
			$compra_arpilla->getFechaOrigen(), 
			$compra_arpilla->getFolio(), 
			$compra_arpilla->getNumeroDeViaje(), 
			$compra_arpilla->getPesoRecibido(), 
			$compra_arpilla->getArpillas(), 
			$compra_arpilla->getPesoPorArpilla(), 
			$compra_arpilla->getProductor(), 
			$compra_arpilla->getMermaPorArpilla(), 
			$compra_arpilla->getTotalOrigen(), 
			$compra_arpilla->getIdCompraArpilla(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CompraArpilla suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CompraArpilla dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CompraArpilla [$compra_arpilla] El objeto de tipo CompraArpilla a crear.
	  **/
	private static final function create( &$compra_arpilla )
	{
		$sql = "INSERT INTO compra_arpilla ( id_compra_arpilla, id_compra, peso_origen, fecha_origen, folio, numero_de_viaje, peso_recibido, arpillas, peso_por_arpilla, productor, merma_por_arpilla, total_origen ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$compra_arpilla->getIdCompraArpilla(), 
			$compra_arpilla->getIdCompra(), 
			$compra_arpilla->getPesoOrigen(), 
			$compra_arpilla->getFechaOrigen(), 
			$compra_arpilla->getFolio(), 
			$compra_arpilla->getNumeroDeViaje(), 
			$compra_arpilla->getPesoRecibido(), 
			$compra_arpilla->getArpillas(), 
			$compra_arpilla->getPesoPorArpilla(), 
			$compra_arpilla->getProductor(), 
			$compra_arpilla->getMermaPorArpilla(), 
			$compra_arpilla->getTotalOrigen(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $compra_arpilla->setIdCompraArpilla( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraArpilla} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CompraArpilla}.
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
	  * @param CompraArpilla [$compra_arpilla] El objeto de tipo CompraArpilla
	  * @param CompraArpilla [$compra_arpilla] El objeto de tipo CompraArpilla
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $compra_arpillaA , $compra_arpillaB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from compra_arpilla WHERE ("; 
		$val = array();
		if( (($a = $compra_arpillaA->getIdCompraArpilla()) !== NULL) & ( ($b = $compra_arpillaB->getIdCompraArpilla()) !== NULL) ){
				$sql .= " id_compra_arpilla >= ? AND id_compra_arpilla <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_compra_arpilla = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_arpillaA->getIdCompra()) !== NULL) & ( ($b = $compra_arpillaB->getIdCompra()) !== NULL) ){
				$sql .= " id_compra >= ? AND id_compra <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_compra = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_arpillaA->getPesoOrigen()) !== NULL) & ( ($b = $compra_arpillaB->getPesoOrigen()) !== NULL) ){
				$sql .= " peso_origen >= ? AND peso_origen <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " peso_origen = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_arpillaA->getFechaOrigen()) !== NULL) & ( ($b = $compra_arpillaB->getFechaOrigen()) !== NULL) ){
				$sql .= " fecha_origen >= ? AND fecha_origen <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " fecha_origen = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_arpillaA->getFolio()) !== NULL) & ( ($b = $compra_arpillaB->getFolio()) !== NULL) ){
				$sql .= " folio >= ? AND folio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " folio = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_arpillaA->getNumeroDeViaje()) !== NULL) & ( ($b = $compra_arpillaB->getNumeroDeViaje()) !== NULL) ){
				$sql .= " numero_de_viaje >= ? AND numero_de_viaje <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " numero_de_viaje = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_arpillaA->getPesoRecibido()) !== NULL) & ( ($b = $compra_arpillaB->getPesoRecibido()) !== NULL) ){
				$sql .= " peso_recibido >= ? AND peso_recibido <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " peso_recibido = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_arpillaA->getArpillas()) !== NULL) & ( ($b = $compra_arpillaB->getArpillas()) !== NULL) ){
				$sql .= " arpillas >= ? AND arpillas <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " arpillas = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_arpillaA->getPesoPorArpilla()) !== NULL) & ( ($b = $compra_arpillaB->getPesoPorArpilla()) !== NULL) ){
				$sql .= " peso_por_arpilla >= ? AND peso_por_arpilla <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " peso_por_arpilla = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_arpillaA->getProductor()) !== NULL) & ( ($b = $compra_arpillaB->getProductor()) !== NULL) ){
				$sql .= " productor >= ? AND productor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " productor = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_arpillaA->getMermaPorArpilla()) !== NULL) & ( ($b = $compra_arpillaB->getMermaPorArpilla()) !== NULL) ){
				$sql .= " merma_por_arpilla >= ? AND merma_por_arpilla <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " merma_por_arpilla = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_arpillaA->getTotalOrigen()) !== NULL) & ( ($b = $compra_arpillaB->getTotalOrigen()) !== NULL) ){
				$sql .= " total_origen >= ? AND total_origen <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " total_origen = ? AND"; 
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
    		array_push( $ar, new CompraArpilla($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CompraArpilla suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param CompraArpilla [$compra_arpilla] El objeto de tipo CompraArpilla a eliminar
	  **/
	public static final function delete( &$compra_arpilla )
	{
		if(self::getByPK($compra_arpilla->getIdCompraArpilla()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM compra_arpilla WHERE  id_compra_arpilla = ?;";
		$params = array( $compra_arpilla->getIdCompraArpilla() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
