<?php
/** Corte Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Corte }. 
  * @author no author especified
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class CorteDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_corte ){
			$pk = "";
			$pk .= $id_corte . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_corte){
			$pk = "";
			$pk .= $id_corte . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_corte ){
			$pk = "";
			$pk .= $id_corte . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Corte} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Corte [$corte] El objeto de tipo Corte
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$corte )
	{
		if(  self::getByPK(  $corte->getIdCorte() ) !== NULL )
		{
			try{ return CorteDAOBase::update( $corte) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return CorteDAOBase::create( $corte) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Corte} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Corte} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Corte Un objeto del tipo {@link Corte}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_corte )
	{
		if(self::recordExists(  $id_corte)){
			return self::getRecord( $id_corte );
		}
		$sql = "SELECT * FROM corte WHERE (id_corte = ? ) LIMIT 1;";
		$params = array(  $id_corte );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Corte( $rs );
			self::pushRecord( $foo,  $id_corte );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Corte}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Corte}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from corte";
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
			$bar = new Corte($foo);
    		array_push( $allData, $bar);
			//id_corte
    		self::pushRecord( $bar, $foo["id_corte"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Corte} de la base de datos. 
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
	  * @param Corte [$corte] El objeto de tipo Corte
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $corte , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from corte WHERE ("; 
		$val = array();
		if( $corte->getIdCorte() != NULL){
			$sql .= " id_corte = ? AND";
			array_push( $val, $corte->getIdCorte() );
		}

		if( $corte->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $corte->getFecha() );
		}

		if( $corte->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $corte->getIdSucursal() );
		}

		if( $corte->getTotalVentas() != NULL){
			$sql .= " total_ventas = ? AND";
			array_push( $val, $corte->getTotalVentas() );
		}

		if( $corte->getTotalVentasAbonado() != NULL){
			$sql .= " total_ventas_abonado = ? AND";
			array_push( $val, $corte->getTotalVentasAbonado() );
		}

		if( $corte->getTotalVentasSaldo() != NULL){
			$sql .= " total_ventas_saldo = ? AND";
			array_push( $val, $corte->getTotalVentasSaldo() );
		}

		if( $corte->getTotalCompras() != NULL){
			$sql .= " total_compras = ? AND";
			array_push( $val, $corte->getTotalCompras() );
		}

		if( $corte->getTotalComprasAbonado() != NULL){
			$sql .= " total_compras_abonado = ? AND";
			array_push( $val, $corte->getTotalComprasAbonado() );
		}

		if( $corte->getTotalGastos() != NULL){
			$sql .= " total_gastos = ? AND";
			array_push( $val, $corte->getTotalGastos() );
		}

		if( $corte->getTotalGastosAbonado() != NULL){
			$sql .= " total_gastos_abonado = ? AND";
			array_push( $val, $corte->getTotalGastosAbonado() );
		}

		if( $corte->getTotalIngresos() != NULL){
			$sql .= " total_ingresos = ? AND";
			array_push( $val, $corte->getTotalIngresos() );
		}

		if( $corte->getTotalGananciaNeta() != NULL){
			$sql .= " total_ganancia_neta = ? AND";
			array_push( $val, $corte->getTotalGananciaNeta() );
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
			$bar =  new Corte($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_corte"] );
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
	  * @param Corte [$corte] El objeto de tipo Corte a actualizar.
	  **/
	private static final function update( $corte )
	{
		$sql = "UPDATE corte SET  fecha = ?, id_sucursal = ?, total_ventas = ?, total_ventas_abonado = ?, total_ventas_saldo = ?, total_compras = ?, total_compras_abonado = ?, total_gastos = ?, total_gastos_abonado = ?, total_ingresos = ?, total_ganancia_neta = ? WHERE  id_corte = ?;";
		$params = array( 
			$corte->getFecha(), 
			$corte->getIdSucursal(), 
			$corte->getTotalVentas(), 
			$corte->getTotalVentasAbonado(), 
			$corte->getTotalVentasSaldo(), 
			$corte->getTotalCompras(), 
			$corte->getTotalComprasAbonado(), 
			$corte->getTotalGastos(), 
			$corte->getTotalGastosAbonado(), 
			$corte->getTotalIngresos(), 
			$corte->getTotalGananciaNeta(), 
			$corte->getIdCorte(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Corte suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Corte dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Corte [$corte] El objeto de tipo Corte a crear.
	  **/
	private static final function create( &$corte )
	{
		$sql = "INSERT INTO corte ( id_corte, fecha, id_sucursal, total_ventas, total_ventas_abonado, total_ventas_saldo, total_compras, total_compras_abonado, total_gastos, total_gastos_abonado, total_ingresos, total_ganancia_neta ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$corte->getIdCorte(), 
			$corte->getFecha(), 
			$corte->getIdSucursal(), 
			$corte->getTotalVentas(), 
			$corte->getTotalVentasAbonado(), 
			$corte->getTotalVentasSaldo(), 
			$corte->getTotalCompras(), 
			$corte->getTotalComprasAbonado(), 
			$corte->getTotalGastos(), 
			$corte->getTotalGastosAbonado(), 
			$corte->getTotalIngresos(), 
			$corte->getTotalGananciaNeta(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $corte->setIdCorte( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Corte} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Corte}.
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
	  * @param Corte [$corte] El objeto de tipo Corte
	  * @param Corte [$corte] El objeto de tipo Corte
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $corteA , $corteB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from corte WHERE ("; 
		$val = array();
		if( (($a = $corteA->getIdCorte()) != NULL) & ( ($b = $corteB->getIdCorte()) != NULL) ){
				$sql .= " id_corte >= ? AND id_corte <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_corte = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getFecha()) != NULL) & ( ($b = $corteB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getIdSucursal()) != NULL) & ( ($b = $corteB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getTotalVentas()) != NULL) & ( ($b = $corteB->getTotalVentas()) != NULL) ){
				$sql .= " total_ventas >= ? AND total_ventas <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total_ventas = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getTotalVentasAbonado()) != NULL) & ( ($b = $corteB->getTotalVentasAbonado()) != NULL) ){
				$sql .= " total_ventas_abonado >= ? AND total_ventas_abonado <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total_ventas_abonado = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getTotalVentasSaldo()) != NULL) & ( ($b = $corteB->getTotalVentasSaldo()) != NULL) ){
				$sql .= " total_ventas_saldo >= ? AND total_ventas_saldo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total_ventas_saldo = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getTotalCompras()) != NULL) & ( ($b = $corteB->getTotalCompras()) != NULL) ){
				$sql .= " total_compras >= ? AND total_compras <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total_compras = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getTotalComprasAbonado()) != NULL) & ( ($b = $corteB->getTotalComprasAbonado()) != NULL) ){
				$sql .= " total_compras_abonado >= ? AND total_compras_abonado <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total_compras_abonado = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getTotalGastos()) != NULL) & ( ($b = $corteB->getTotalGastos()) != NULL) ){
				$sql .= " total_gastos >= ? AND total_gastos <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total_gastos = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getTotalGastosAbonado()) != NULL) & ( ($b = $corteB->getTotalGastosAbonado()) != NULL) ){
				$sql .= " total_gastos_abonado >= ? AND total_gastos_abonado <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total_gastos_abonado = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getTotalIngresos()) != NULL) & ( ($b = $corteB->getTotalIngresos()) != NULL) ){
				$sql .= " total_ingresos >= ? AND total_ingresos <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total_ingresos = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getTotalGananciaNeta()) != NULL) & ( ($b = $corteB->getTotalGananciaNeta()) != NULL) ){
				$sql .= " total_ganancia_neta >= ? AND total_ganancia_neta <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total_ganancia_neta = ? AND"; 
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
    		array_push( $ar, new Corte($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Corte suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Corte [$corte] El objeto de tipo Corte a eliminar
	  **/
	public static final function delete( &$corte )
	{
		if(self::getByPK($corte->getIdCorte()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM corte WHERE  id_corte = ?;";
		$params = array( $corte->getIdCorte() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
