<?php
/** AbonoPrestamo Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link AbonoPrestamo }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class AbonoPrestamoDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_abono_prestamo ){
			$pk = "";
			$pk .= $id_abono_prestamo . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_abono_prestamo){
			$pk = "";
			$pk .= $id_abono_prestamo . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_abono_prestamo ){
			$pk = "";
			$pk .= $id_abono_prestamo . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link AbonoPrestamo} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param AbonoPrestamo [$abono_prestamo] El objeto de tipo AbonoPrestamo
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$abono_prestamo )
	{
		if(  self::getByPK(  $abono_prestamo->getIdAbonoPrestamo() ) !== NULL )
		{
			try{ return AbonoPrestamoDAOBase::update( $abono_prestamo) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return AbonoPrestamoDAOBase::create( $abono_prestamo) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link AbonoPrestamo} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link AbonoPrestamo} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link AbonoPrestamo Un objeto del tipo {@link AbonoPrestamo}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_abono_prestamo )
	{
		if(self::recordExists(  $id_abono_prestamo)){
			return self::getRecord( $id_abono_prestamo );
		}
		$sql = "SELECT * FROM abono_prestamo WHERE (id_abono_prestamo = ? ) LIMIT 1;";
		$params = array(  $id_abono_prestamo );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new AbonoPrestamo( $rs );
			self::pushRecord( $foo,  $id_abono_prestamo );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link AbonoPrestamo}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link AbonoPrestamo}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from abono_prestamo";
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
			$bar = new AbonoPrestamo($foo);
    		array_push( $allData, $bar);
			//id_abono_prestamo
    		self::pushRecord( $bar, $foo["id_abono_prestamo"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link AbonoPrestamo} de la base de datos. 
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
	  * @param AbonoPrestamo [$abono_prestamo] El objeto de tipo AbonoPrestamo
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $abono_prestamo , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from abono_prestamo WHERE ("; 
		$val = array();
		if( $abono_prestamo->getIdAbonoPrestamo() != NULL){
			$sql .= " id_abono_prestamo = ? AND";
			array_push( $val, $abono_prestamo->getIdAbonoPrestamo() );
		}

		if( $abono_prestamo->getIdPrestamo() != NULL){
			$sql .= " id_prestamo = ? AND";
			array_push( $val, $abono_prestamo->getIdPrestamo() );
		}

		if( $abono_prestamo->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $abono_prestamo->getIdSucursal() );
		}

		if( $abono_prestamo->getMonto() != NULL){
			$sql .= " monto = ? AND";
			array_push( $val, $abono_prestamo->getMonto() );
		}

		if( $abono_prestamo->getIdCaja() != NULL){
			$sql .= " id_caja = ? AND";
			array_push( $val, $abono_prestamo->getIdCaja() );
		}

		if( $abono_prestamo->getIdDeudor() != NULL){
			$sql .= " id_deudor = ? AND";
			array_push( $val, $abono_prestamo->getIdDeudor() );
		}

		if( $abono_prestamo->getIdReceptor() != NULL){
			$sql .= " id_receptor = ? AND";
			array_push( $val, $abono_prestamo->getIdReceptor() );
		}

		if( $abono_prestamo->getNota() != NULL){
			$sql .= " nota = ? AND";
			array_push( $val, $abono_prestamo->getNota() );
		}

		if( $abono_prestamo->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $abono_prestamo->getFecha() );
		}

		if( $abono_prestamo->getTipoDePago() != NULL){
			$sql .= " tipo_de_pago = ? AND";
			array_push( $val, $abono_prestamo->getTipoDePago() );
		}

		if( $abono_prestamo->getCancelado() != NULL){
			$sql .= " cancelado = ? AND";
			array_push( $val, $abono_prestamo->getCancelado() );
		}

		if( $abono_prestamo->getMotivoCancelacion() != NULL){
			$sql .= " motivo_cancelacion = ? AND";
			array_push( $val, $abono_prestamo->getMotivoCancelacion() );
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
			$bar =  new AbonoPrestamo($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_abono_prestamo"] );
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
	  * @param AbonoPrestamo [$abono_prestamo] El objeto de tipo AbonoPrestamo a actualizar.
	  **/
	private static final function update( $abono_prestamo )
	{
		$sql = "UPDATE abono_prestamo SET  id_prestamo = ?, id_sucursal = ?, monto = ?, id_caja = ?, id_deudor = ?, id_receptor = ?, nota = ?, fecha = ?, tipo_de_pago = ?, cancelado = ?, motivo_cancelacion = ? WHERE  id_abono_prestamo = ?;";
		$params = array( 
			$abono_prestamo->getIdPrestamo(), 
			$abono_prestamo->getIdSucursal(), 
			$abono_prestamo->getMonto(), 
			$abono_prestamo->getIdCaja(), 
			$abono_prestamo->getIdDeudor(), 
			$abono_prestamo->getIdReceptor(), 
			$abono_prestamo->getNota(), 
			$abono_prestamo->getFecha(), 
			$abono_prestamo->getTipoDePago(), 
			$abono_prestamo->getCancelado(), 
			$abono_prestamo->getMotivoCancelacion(), 
			$abono_prestamo->getIdAbonoPrestamo(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto AbonoPrestamo suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto AbonoPrestamo dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param AbonoPrestamo [$abono_prestamo] El objeto de tipo AbonoPrestamo a crear.
	  **/
	private static final function create( &$abono_prestamo )
	{
		$sql = "INSERT INTO abono_prestamo ( id_abono_prestamo, id_prestamo, id_sucursal, monto, id_caja, id_deudor, id_receptor, nota, fecha, tipo_de_pago, cancelado, motivo_cancelacion ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$abono_prestamo->getIdAbonoPrestamo(), 
			$abono_prestamo->getIdPrestamo(), 
			$abono_prestamo->getIdSucursal(), 
			$abono_prestamo->getMonto(), 
			$abono_prestamo->getIdCaja(), 
			$abono_prestamo->getIdDeudor(), 
			$abono_prestamo->getIdReceptor(), 
			$abono_prestamo->getNota(), 
			$abono_prestamo->getFecha(), 
			$abono_prestamo->getTipoDePago(), 
			$abono_prestamo->getCancelado(), 
			$abono_prestamo->getMotivoCancelacion(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $abono_prestamo->setIdAbonoPrestamo( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link AbonoPrestamo} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link AbonoPrestamo}.
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
	  * @param AbonoPrestamo [$abono_prestamo] El objeto de tipo AbonoPrestamo
	  * @param AbonoPrestamo [$abono_prestamo] El objeto de tipo AbonoPrestamo
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $abono_prestamoA , $abono_prestamoB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from abono_prestamo WHERE ("; 
		$val = array();
		if( (($a = $abono_prestamoA->getIdAbonoPrestamo()) != NULL) & ( ($b = $abono_prestamoB->getIdAbonoPrestamo()) != NULL) ){
				$sql .= " id_abono_prestamo >= ? AND id_abono_prestamo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_abono_prestamo = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $abono_prestamoA->getIdPrestamo()) != NULL) & ( ($b = $abono_prestamoB->getIdPrestamo()) != NULL) ){
				$sql .= " id_prestamo >= ? AND id_prestamo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_prestamo = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $abono_prestamoA->getIdSucursal()) != NULL) & ( ($b = $abono_prestamoB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $abono_prestamoA->getMonto()) != NULL) & ( ($b = $abono_prestamoB->getMonto()) != NULL) ){
				$sql .= " monto >= ? AND monto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " monto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $abono_prestamoA->getIdCaja()) != NULL) & ( ($b = $abono_prestamoB->getIdCaja()) != NULL) ){
				$sql .= " id_caja >= ? AND id_caja <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_caja = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $abono_prestamoA->getIdDeudor()) != NULL) & ( ($b = $abono_prestamoB->getIdDeudor()) != NULL) ){
				$sql .= " id_deudor >= ? AND id_deudor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_deudor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $abono_prestamoA->getIdReceptor()) != NULL) & ( ($b = $abono_prestamoB->getIdReceptor()) != NULL) ){
				$sql .= " id_receptor >= ? AND id_receptor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_receptor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $abono_prestamoA->getNota()) != NULL) & ( ($b = $abono_prestamoB->getNota()) != NULL) ){
				$sql .= " nota >= ? AND nota <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " nota = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $abono_prestamoA->getFecha()) != NULL) & ( ($b = $abono_prestamoB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $abono_prestamoA->getTipoDePago()) != NULL) & ( ($b = $abono_prestamoB->getTipoDePago()) != NULL) ){
				$sql .= " tipo_de_pago >= ? AND tipo_de_pago <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " tipo_de_pago = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $abono_prestamoA->getCancelado()) != NULL) & ( ($b = $abono_prestamoB->getCancelado()) != NULL) ){
				$sql .= " cancelado >= ? AND cancelado <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " cancelado = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $abono_prestamoA->getMotivoCancelacion()) != NULL) & ( ($b = $abono_prestamoB->getMotivoCancelacion()) != NULL) ){
				$sql .= " motivo_cancelacion >= ? AND motivo_cancelacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " motivo_cancelacion = ? AND"; 
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
    		array_push( $ar, new AbonoPrestamo($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto AbonoPrestamo suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param AbonoPrestamo [$abono_prestamo] El objeto de tipo AbonoPrestamo a eliminar
	  **/
	public static final function delete( &$abono_prestamo )
	{
		if(self::getByPK($abono_prestamo->getIdAbonoPrestamo()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM abono_prestamo WHERE  id_abono_prestamo = ?;";
		$params = array( $abono_prestamo->getIdAbonoPrestamo() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
