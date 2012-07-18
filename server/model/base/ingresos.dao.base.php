<?php
/** Ingresos Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Ingresos }. 
  * @author caffeina
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class IngresosDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_ingreso ){
			$pk = "";
			$pk .= $id_ingreso . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_ingreso){
			$pk = "";
			$pk .= $id_ingreso . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_ingreso ){
			$pk = "";
			$pk .= $id_ingreso . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Ingresos} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$ingresos )
	{
		if(  self::getByPK(  $ingresos->getIdIngreso() ) !== NULL )
		{
			try{ return IngresosDAOBase::update( $ingresos) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return IngresosDAOBase::create( $ingresos) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Ingresos} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Ingresos} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Ingresos Un objeto del tipo {@link Ingresos}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_ingreso )
	{
		if(self::recordExists(  $id_ingreso)){
			return self::getRecord( $id_ingreso );
		}
		$sql = "SELECT * FROM ingresos WHERE (id_ingreso = ? ) LIMIT 1;";
		$params = array(  $id_ingreso );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Ingresos( $rs );
			self::pushRecord( $foo,  $id_ingreso );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Ingresos}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Ingresos}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from ingresos";
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
			$bar = new Ingresos($foo);
    		array_push( $allData, $bar);
			//id_ingreso
    		self::pushRecord( $bar, $foo["id_ingreso"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ingresos} de la base de datos. 
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
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $ingresos , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from ingresos WHERE ("; 
		$val = array();
		if( $ingresos->getIdIngreso() != NULL){
			$sql .= " id_ingreso = ? AND";
			array_push( $val, $ingresos->getIdIngreso() );
		}

		if( $ingresos->getConcepto() != NULL){
			$sql .= " concepto = ? AND";
			array_push( $val, $ingresos->getConcepto() );
		}

		if( $ingresos->getMonto() != NULL){
			$sql .= " monto = ? AND";
			array_push( $val, $ingresos->getMonto() );
		}

		if( $ingresos->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $ingresos->getFecha() );
		}

		if( $ingresos->getFechaIngreso() != NULL){
			$sql .= " fecha_ingreso = ? AND";
			array_push( $val, $ingresos->getFechaIngreso() );
		}

		if( $ingresos->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $ingresos->getIdSucursal() );
		}

		if( $ingresos->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $ingresos->getIdUsuario() );
		}

		if( $ingresos->getNota() != NULL){
			$sql .= " nota = ? AND";
			array_push( $val, $ingresos->getNota() );
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
			$bar =  new Ingresos($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_ingreso"] );
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
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos a actualizar.
	  **/
	private static final function update( $ingresos )
	{
		$sql = "UPDATE ingresos SET  concepto = ?, monto = ?, fecha = ?, fecha_ingreso = ?, id_sucursal = ?, id_usuario = ?, nota = ? WHERE  id_ingreso = ?;";
		$params = array( 
			$ingresos->getConcepto(), 
			$ingresos->getMonto(), 
			$ingresos->getFecha(), 
			$ingresos->getFechaIngreso(), 
			$ingresos->getIdSucursal(), 
			$ingresos->getIdUsuario(), 
			$ingresos->getNota(), 
			$ingresos->getIdIngreso(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Ingresos suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Ingresos dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos a crear.
	  **/
	private static final function create( &$ingresos )
	{
		$sql = "INSERT INTO ingresos ( id_ingreso, concepto, monto, fecha, fecha_ingreso, id_sucursal, id_usuario, nota ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$ingresos->getIdIngreso(), 
			$ingresos->getConcepto(), 
			$ingresos->getMonto(), 
			$ingresos->getFecha(), 
			$ingresos->getFechaIngreso(), 
			$ingresos->getIdSucursal(), 
			$ingresos->getIdUsuario(), 
			$ingresos->getNota(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $ingresos->setIdIngreso( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ingresos} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Ingresos}.
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
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $ingresosA , $ingresosB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from ingresos WHERE ("; 
		$val = array();
		if( (($a = $ingresosA->getIdIngreso()) != NULL) & ( ($b = $ingresosB->getIdIngreso()) != NULL) ){
				$sql .= " id_ingreso >= ? AND id_ingreso <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_ingreso = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ingresosA->getConcepto()) != NULL) & ( ($b = $ingresosB->getConcepto()) != NULL) ){
				$sql .= " concepto >= ? AND concepto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " concepto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ingresosA->getMonto()) != NULL) & ( ($b = $ingresosB->getMonto()) != NULL) ){
				$sql .= " monto >= ? AND monto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " monto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ingresosA->getFecha()) != NULL) & ( ($b = $ingresosB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ingresosA->getFechaIngreso()) != NULL) & ( ($b = $ingresosB->getFechaIngreso()) != NULL) ){
				$sql .= " fecha_ingreso >= ? AND fecha_ingreso <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha_ingreso = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ingresosA->getIdSucursal()) != NULL) & ( ($b = $ingresosB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ingresosA->getIdUsuario()) != NULL) & ( ($b = $ingresosB->getIdUsuario()) != NULL) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ingresosA->getNota()) != NULL) & ( ($b = $ingresosB->getNota()) != NULL) ){
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
    		array_push( $ar, new Ingresos($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Ingresos suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos a eliminar
	  **/
	public static final function delete( &$ingresos )
	{
		if(self::getByPK($ingresos->getIdIngreso()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM ingresos WHERE  id_ingreso = ?;";
		$params = array( $ingresos->getIdIngreso() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
