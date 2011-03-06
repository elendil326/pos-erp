<?php
/** Gastos Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Gastos }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class GastosDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_gasto ){
			$pk = "";
			$pk .= $id_gasto . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_gasto){
			$pk = "";
			$pk .= $id_gasto . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_gasto ){
			$pk = "";
			$pk .= $id_gasto . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Gastos} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Gastos [$gastos] El objeto de tipo Gastos
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$gastos )
	{
		if(  self::getByPK(  $gastos->getIdGasto() ) !== NULL )
		{
			try{ return GastosDAOBase::update( $gastos) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return GastosDAOBase::create( $gastos) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Gastos} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Gastos} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Gastos Un objeto del tipo {@link Gastos}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_gasto )
	{
		if(self::recordExists(  $id_gasto)){
			return self::getRecord( $id_gasto );
		}
		$sql = "SELECT * FROM gastos WHERE (id_gasto = ? ) LIMIT 1;";
		$params = array(  $id_gasto );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Gastos( $rs );
			self::pushRecord( $foo,  $id_gasto );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Gastos}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Gastos}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from gastos";
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
			$bar = new Gastos($foo);
    		array_push( $allData, $bar);
			//id_gasto
    		self::pushRecord( $bar, $foo["id_gasto"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Gastos} de la base de datos. 
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
	  * @param Gastos [$gastos] El objeto de tipo Gastos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $gastos , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from gastos WHERE ("; 
		$val = array();
		if( $gastos->getIdGasto() != NULL){
			$sql .= " id_gasto = ? AND";
			array_push( $val, $gastos->getIdGasto() );
		}

		if( $gastos->getFolio() != NULL){
			$sql .= " folio = ? AND";
			array_push( $val, $gastos->getFolio() );
		}

		if( $gastos->getConcepto() != NULL){
			$sql .= " concepto = ? AND";
			array_push( $val, $gastos->getConcepto() );
		}

		if( $gastos->getMonto() != NULL){
			$sql .= " monto = ? AND";
			array_push( $val, $gastos->getMonto() );
		}

		if( $gastos->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $gastos->getFecha() );
		}

		if( $gastos->getFechaIngreso() != NULL){
			$sql .= " fecha_ingreso = ? AND";
			array_push( $val, $gastos->getFechaIngreso() );
		}

		if( $gastos->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $gastos->getIdSucursal() );
		}

		if( $gastos->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $gastos->getIdUsuario() );
		}

		if( $gastos->getNota() != NULL){
			$sql .= " nota = ? AND";
			array_push( $val, $gastos->getNota() );
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
			$bar =  new Gastos($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_gasto"] );
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
	  * @param Gastos [$gastos] El objeto de tipo Gastos a actualizar.
	  **/
	private static final function update( $gastos )
	{
		$sql = "UPDATE gastos SET  folio = ?, concepto = ?, monto = ?, fecha = ?, fecha_ingreso = ?, id_sucursal = ?, id_usuario = ?, nota = ? WHERE  id_gasto = ?;";
		$params = array( 
			$gastos->getFolio(), 
			$gastos->getConcepto(), 
			$gastos->getMonto(), 
			$gastos->getFecha(), 
			$gastos->getFechaIngreso(), 
			$gastos->getIdSucursal(), 
			$gastos->getIdUsuario(), 
			$gastos->getNota(), 
			$gastos->getIdGasto(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Gastos suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Gastos dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Gastos [$gastos] El objeto de tipo Gastos a crear.
	  **/
	private static final function create( &$gastos )
	{
		$sql = "INSERT INTO gastos ( id_gasto, folio, concepto, monto, fecha, fecha_ingreso, id_sucursal, id_usuario, nota ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$gastos->getIdGasto(), 
			$gastos->getFolio(), 
			$gastos->getConcepto(), 
			$gastos->getMonto(), 
			$gastos->getFecha(), 
			$gastos->getFechaIngreso(), 
			$gastos->getIdSucursal(), 
			$gastos->getIdUsuario(), 
			$gastos->getNota(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $gastos->setIdGasto( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Gastos} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Gastos}.
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
	  * @param Gastos [$gastos] El objeto de tipo Gastos
	  * @param Gastos [$gastos] El objeto de tipo Gastos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $gastosA , $gastosB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from gastos WHERE ("; 
		$val = array();
		if( (($a = $gastosA->getIdGasto()) != NULL) & ( ($b = $gastosB->getIdGasto()) != NULL) ){
				$sql .= " id_gasto >= ? AND id_gasto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_gasto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $gastosA->getFolio()) != NULL) & ( ($b = $gastosB->getFolio()) != NULL) ){
				$sql .= " folio >= ? AND folio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " folio = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $gastosA->getConcepto()) != NULL) & ( ($b = $gastosB->getConcepto()) != NULL) ){
				$sql .= " concepto >= ? AND concepto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " concepto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $gastosA->getMonto()) != NULL) & ( ($b = $gastosB->getMonto()) != NULL) ){
				$sql .= " monto >= ? AND monto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " monto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $gastosA->getFecha()) != NULL) & ( ($b = $gastosB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $gastosA->getFechaIngreso()) != NULL) & ( ($b = $gastosB->getFechaIngreso()) != NULL) ){
				$sql .= " fecha_ingreso >= ? AND fecha_ingreso <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha_ingreso = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $gastosA->getIdSucursal()) != NULL) & ( ($b = $gastosB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $gastosA->getIdUsuario()) != NULL) & ( ($b = $gastosB->getIdUsuario()) != NULL) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $gastosA->getNota()) != NULL) & ( ($b = $gastosB->getNota()) != NULL) ){
				$sql .= " nota >= ? AND nota <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " nota = ? AND"; 
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
    		array_push( $ar, new Gastos($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Gastos suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Gastos [$gastos] El objeto de tipo Gastos a eliminar
	  **/
	public static final function delete( &$gastos )
	{
		if(self::getByPK($gastos->getIdGasto()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM gastos WHERE  id_gasto = ?;";
		$params = array( $gastos->getIdGasto() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
