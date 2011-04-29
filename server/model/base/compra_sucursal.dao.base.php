<?php
/** CompraSucursal Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link CompraSucursal }. 
  * @author Alan Gonzalez
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class CompraSucursalDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_compra ){
			$pk = "";
			$pk .= $id_compra . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_compra){
			$pk = "";
			$pk .= $id_compra . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_compra ){
			$pk = "";
			$pk .= $id_compra . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CompraSucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param CompraSucursal [$compra_sucursal] El objeto de tipo CompraSucursal
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$compra_sucursal )
	{
		if(  self::getByPK(  $compra_sucursal->getIdCompra() ) !== NULL )
		{
			try{ return CompraSucursalDAOBase::update( $compra_sucursal) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return CompraSucursalDAOBase::create( $compra_sucursal) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link CompraSucursal} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CompraSucursal} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CompraSucursal Un objeto del tipo {@link CompraSucursal}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_compra )
	{
		if(self::recordExists(  $id_compra)){
			return self::getRecord( $id_compra );
		}
		$sql = "SELECT * FROM compra_sucursal WHERE (id_compra = ? ) LIMIT 1;";
		$params = array(  $id_compra );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new CompraSucursal( $rs );
			self::pushRecord( $foo,  $id_compra );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CompraSucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link CompraSucursal}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from compra_sucursal";
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
			$bar = new CompraSucursal($foo);
    		array_push( $allData, $bar);
			//id_compra
    		self::pushRecord( $bar, $foo["id_compra"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraSucursal} de la base de datos. 
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
	  * @param CompraSucursal [$compra_sucursal] El objeto de tipo CompraSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $compra_sucursal , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from compra_sucursal WHERE ("; 
		$val = array();
		if( $compra_sucursal->getIdCompra() != NULL){
			$sql .= " id_compra = ? AND";
			array_push( $val, $compra_sucursal->getIdCompra() );
		}

		if( $compra_sucursal->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $compra_sucursal->getFecha() );
		}

		if( $compra_sucursal->getSubtotal() != NULL){
			$sql .= " subtotal = ? AND";
			array_push( $val, $compra_sucursal->getSubtotal() );
		}

		if( $compra_sucursal->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $compra_sucursal->getIdSucursal() );
		}

		if( $compra_sucursal->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $compra_sucursal->getIdUsuario() );
		}

		if( $compra_sucursal->getIdProveedor() != NULL){
			$sql .= " id_proveedor = ? AND";
			array_push( $val, $compra_sucursal->getIdProveedor() );
		}

		if( $compra_sucursal->getPagado() != NULL){
			$sql .= " pagado = ? AND";
			array_push( $val, $compra_sucursal->getPagado() );
		}

		if( $compra_sucursal->getLiquidado() != NULL){
			$sql .= " liquidado = ? AND";
			array_push( $val, $compra_sucursal->getLiquidado() );
		}

		if( $compra_sucursal->getTotal() != NULL){
			$sql .= " total = ? AND";
			array_push( $val, $compra_sucursal->getTotal() );
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
			$bar =  new CompraSucursal($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_compra"] );
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
	  * @param CompraSucursal [$compra_sucursal] El objeto de tipo CompraSucursal a actualizar.
	  **/
	private static final function update( $compra_sucursal )
	{
		$sql = "UPDATE compra_sucursal SET  fecha = ?, subtotal = ?, id_sucursal = ?, id_usuario = ?, id_proveedor = ?, pagado = ?, liquidado = ?, total = ? WHERE  id_compra = ?;";
		$params = array( 
			$compra_sucursal->getFecha(), 
			$compra_sucursal->getSubtotal(), 
			$compra_sucursal->getIdSucursal(), 
			$compra_sucursal->getIdUsuario(), 
			$compra_sucursal->getIdProveedor(), 
			$compra_sucursal->getPagado(), 
			$compra_sucursal->getLiquidado(), 
			$compra_sucursal->getTotal(), 
			$compra_sucursal->getIdCompra(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CompraSucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CompraSucursal dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CompraSucursal [$compra_sucursal] El objeto de tipo CompraSucursal a crear.
	  **/
	private static final function create( &$compra_sucursal )
	{
		$sql = "INSERT INTO compra_sucursal ( id_compra, fecha, subtotal, id_sucursal, id_usuario, id_proveedor, pagado, liquidado, total ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$compra_sucursal->getIdCompra(), 
			$compra_sucursal->getFecha(), 
			$compra_sucursal->getSubtotal(), 
			$compra_sucursal->getIdSucursal(), 
			$compra_sucursal->getIdUsuario(), 
			$compra_sucursal->getIdProveedor(), 
			$compra_sucursal->getPagado(), 
			$compra_sucursal->getLiquidado(), 
			$compra_sucursal->getTotal(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $compra_sucursal->setIdCompra( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraSucursal} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CompraSucursal}.
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
	  * @param CompraSucursal [$compra_sucursal] El objeto de tipo CompraSucursal
	  * @param CompraSucursal [$compra_sucursal] El objeto de tipo CompraSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $compra_sucursalA , $compra_sucursalB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from compra_sucursal WHERE ("; 
		$val = array();
		if( (($a = $compra_sucursalA->getIdCompra()) != NULL) & ( ($b = $compra_sucursalB->getIdCompra()) != NULL) ){
				$sql .= " id_compra >= ? AND id_compra <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_compra = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_sucursalA->getFecha()) != NULL) & ( ($b = $compra_sucursalB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_sucursalA->getSubtotal()) != NULL) & ( ($b = $compra_sucursalB->getSubtotal()) != NULL) ){
				$sql .= " subtotal >= ? AND subtotal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " subtotal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_sucursalA->getIdSucursal()) != NULL) & ( ($b = $compra_sucursalB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_sucursalA->getIdUsuario()) != NULL) & ( ($b = $compra_sucursalB->getIdUsuario()) != NULL) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_sucursalA->getIdProveedor()) != NULL) & ( ($b = $compra_sucursalB->getIdProveedor()) != NULL) ){
				$sql .= " id_proveedor >= ? AND id_proveedor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_proveedor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_sucursalA->getPagado()) != NULL) & ( ($b = $compra_sucursalB->getPagado()) != NULL) ){
				$sql .= " pagado >= ? AND pagado <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " pagado = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_sucursalA->getLiquidado()) != NULL) & ( ($b = $compra_sucursalB->getLiquidado()) != NULL) ){
				$sql .= " liquidado >= ? AND liquidado <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " liquidado = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_sucursalA->getTotal()) != NULL) & ( ($b = $compra_sucursalB->getTotal()) != NULL) ){
				$sql .= " total >= ? AND total <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total = ? AND"; 
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
    		array_push( $ar, new CompraSucursal($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CompraSucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param CompraSucursal [$compra_sucursal] El objeto de tipo CompraSucursal a eliminar
	  **/
	public static final function delete( &$compra_sucursal )
	{
		if(self::getByPK($compra_sucursal->getIdCompra()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM compra_sucursal WHERE  id_compra = ?;";
		$params = array( $compra_sucursal->getIdCompra() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
