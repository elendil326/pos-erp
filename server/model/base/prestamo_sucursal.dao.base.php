<?php
/** PrestamoSucursal Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link PrestamoSucursal }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class PrestamoSucursalDAOBase extends DAO
{

		private static $loadedRecords = array();
		private static function recordExists( $id ){
			return array_key_exists ( $id , self::$loadedRecords );
		}
		private static function pushRecord( $inventario, $id ){
			self::$loadedRecords [$id] = $inventario;
		}
		private static function getRecord( $id ){
			return self::$loadedRecords[$id];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link PrestamoSucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param PrestamoSucursal [$prestamo_sucursal] El objeto de tipo PrestamoSucursal
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$prestamo_sucursal )
	{
		if(  self::getByPK(  $prestamo_sucursal->getIdPrestamo() ) !== NULL )
		{
			try{ return PrestamoSucursalDAOBase::update( $prestamo_sucursal) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return PrestamoSucursalDAOBase::create( $prestamo_sucursal) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link PrestamoSucursal} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link PrestamoSucursal} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link PrestamoSucursal Un objeto del tipo {@link PrestamoSucursal}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_prestamo )
	{
		if(self::recordExists(  $id_prestamo)){
			return self::getRecord( $id_prestamo );
		}
		$sql = "SELECT * FROM prestamo_sucursal WHERE (id_prestamo = ? ) LIMIT 1;";
		$params = array(  $id_prestamo );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new PrestamoSucursal( $rs );
			self::pushRecord( $foo,  $id_prestamo );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link PrestamoSucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link PrestamoSucursal}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from prestamo_sucursal";
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
    		array_push( $allData, new PrestamoSucursal($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PrestamoSucursal} de la base de datos. 
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
	  * @param PrestamoSucursal [$prestamo_sucursal] El objeto de tipo PrestamoSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $prestamo_sucursal , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from prestamo_sucursal WHERE ("; 
		$val = array();
		if( $prestamo_sucursal->getIdPrestamo() != NULL){
			$sql .= " id_prestamo = ? AND";
			array_push( $val, $prestamo_sucursal->getIdPrestamo() );
		}

		if( $prestamo_sucursal->getPrestamista() != NULL){
			$sql .= " prestamista = ? AND";
			array_push( $val, $prestamo_sucursal->getPrestamista() );
		}

		if( $prestamo_sucursal->getDeudor() != NULL){
			$sql .= " deudor = ? AND";
			array_push( $val, $prestamo_sucursal->getDeudor() );
		}

		if( $prestamo_sucursal->getMonto() != NULL){
			$sql .= " monto = ? AND";
			array_push( $val, $prestamo_sucursal->getMonto() );
		}

		if( $prestamo_sucursal->getSaldo() != NULL){
			$sql .= " saldo = ? AND";
			array_push( $val, $prestamo_sucursal->getSaldo() );
		}

		if( $prestamo_sucursal->getLiquidado() != NULL){
			$sql .= " liquidado = ? AND";
			array_push( $val, $prestamo_sucursal->getLiquidado() );
		}

		if( $prestamo_sucursal->getConcepto() != NULL){
			$sql .= " concepto = ? AND";
			array_push( $val, $prestamo_sucursal->getConcepto() );
		}

		if( $prestamo_sucursal->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $prestamo_sucursal->getFecha() );
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
    		array_push( $ar, new PrestamoSucursal($foo));
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
	  * @param PrestamoSucursal [$prestamo_sucursal] El objeto de tipo PrestamoSucursal a actualizar.
	  **/
	private static final function update( $prestamo_sucursal )
	{
		$sql = "UPDATE prestamo_sucursal SET  prestamista = ?, deudor = ?, monto = ?, saldo = ?, liquidado = ?, concepto = ?, fecha = ? WHERE  id_prestamo = ?;";
		$params = array( 
			$prestamo_sucursal->getPrestamista(), 
			$prestamo_sucursal->getDeudor(), 
			$prestamo_sucursal->getMonto(), 
			$prestamo_sucursal->getSaldo(), 
			$prestamo_sucursal->getLiquidado(), 
			$prestamo_sucursal->getConcepto(), 
			$prestamo_sucursal->getFecha(), 
			$prestamo_sucursal->getIdPrestamo(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto PrestamoSucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto PrestamoSucursal dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param PrestamoSucursal [$prestamo_sucursal] El objeto de tipo PrestamoSucursal a crear.
	  **/
	private static final function create( &$prestamo_sucursal )
	{
		$sql = "INSERT INTO prestamo_sucursal ( id_prestamo, prestamista, deudor, monto, saldo, liquidado, concepto, fecha ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$prestamo_sucursal->getIdPrestamo(), 
			$prestamo_sucursal->getPrestamista(), 
			$prestamo_sucursal->getDeudor(), 
			$prestamo_sucursal->getMonto(), 
			$prestamo_sucursal->getSaldo(), 
			$prestamo_sucursal->getLiquidado(), 
			$prestamo_sucursal->getConcepto(), 
			$prestamo_sucursal->getFecha(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $prestamo_sucursal->setIdPrestamo( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PrestamoSucursal} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link PrestamoSucursal}.
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
	  * @param PrestamoSucursal [$prestamo_sucursal] El objeto de tipo PrestamoSucursal
	  * @param PrestamoSucursal [$prestamo_sucursal] El objeto de tipo PrestamoSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $prestamo_sucursalA , $prestamo_sucursalB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from prestamo_sucursal WHERE ("; 
		$val = array();
		if( (($a = $prestamo_sucursalA->getIdPrestamo()) != NULL) & ( ($b = $prestamo_sucursalB->getIdPrestamo()) != NULL) ){
				$sql .= " id_prestamo >= ? AND id_prestamo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_prestamo = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $prestamo_sucursalA->getPrestamista()) != NULL) & ( ($b = $prestamo_sucursalB->getPrestamista()) != NULL) ){
				$sql .= " prestamista >= ? AND prestamista <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " prestamista = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $prestamo_sucursalA->getDeudor()) != NULL) & ( ($b = $prestamo_sucursalB->getDeudor()) != NULL) ){
				$sql .= " deudor >= ? AND deudor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " deudor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $prestamo_sucursalA->getMonto()) != NULL) & ( ($b = $prestamo_sucursalB->getMonto()) != NULL) ){
				$sql .= " monto >= ? AND monto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " monto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $prestamo_sucursalA->getSaldo()) != NULL) & ( ($b = $prestamo_sucursalB->getSaldo()) != NULL) ){
				$sql .= " saldo >= ? AND saldo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " saldo = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $prestamo_sucursalA->getLiquidado()) != NULL) & ( ($b = $prestamo_sucursalB->getLiquidado()) != NULL) ){
				$sql .= " liquidado >= ? AND liquidado <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " liquidado = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $prestamo_sucursalA->getConcepto()) != NULL) & ( ($b = $prestamo_sucursalB->getConcepto()) != NULL) ){
				$sql .= " concepto >= ? AND concepto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " concepto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $prestamo_sucursalA->getFecha()) != NULL) & ( ($b = $prestamo_sucursalB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
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
    		array_push( $ar, new PrestamoSucursal($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto PrestamoSucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param PrestamoSucursal [$prestamo_sucursal] El objeto de tipo PrestamoSucursal a eliminar
	  **/
	public static final function delete( &$prestamo_sucursal )
	{
		if(self::getByPK($prestamo_sucursal->getIdPrestamo()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM prestamo_sucursal WHERE  id_prestamo = ?;";
		$params = array( $prestamo_sucursal->getIdPrestamo() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
