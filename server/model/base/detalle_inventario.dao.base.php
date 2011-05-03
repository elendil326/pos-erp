<?php
/** DetalleInventario Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link DetalleInventario }. 
  * @author no author especified
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class DetalleInventarioDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_producto, $id_sucursal ){
			$pk = "";
			$pk .= $id_producto . "-";
			$pk .= $id_sucursal . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_producto, $id_sucursal){
			$pk = "";
			$pk .= $id_producto . "-";
			$pk .= $id_sucursal . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_producto, $id_sucursal ){
			$pk = "";
			$pk .= $id_producto . "-";
			$pk .= $id_sucursal . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleInventario} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$detalle_inventario )
	{
		if(  self::getByPK(  $detalle_inventario->getIdProducto() , $detalle_inventario->getIdSucursal() ) !== NULL )
		{
			try{ return DetalleInventarioDAOBase::update( $detalle_inventario) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return DetalleInventarioDAOBase::create( $detalle_inventario) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link DetalleInventario} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleInventario} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleInventario Un objeto del tipo {@link DetalleInventario}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_producto, $id_sucursal )
	{
		if(self::recordExists(  $id_producto, $id_sucursal)){
			return self::getRecord( $id_producto, $id_sucursal );
		}
		$sql = "SELECT * FROM detalle_inventario WHERE (id_producto = ? AND id_sucursal = ? ) LIMIT 1;";
		$params = array(  $id_producto, $id_sucursal );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new DetalleInventario( $rs );
			self::pushRecord( $foo,  $id_producto, $id_sucursal );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleInventario}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link DetalleInventario}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from detalle_inventario";
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
			$bar = new DetalleInventario($foo);
    		array_push( $allData, $bar);
			//id_producto
			//id_sucursal
    		self::pushRecord( $bar, $foo["id_producto"],$foo["id_sucursal"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleInventario} de la base de datos. 
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
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $detalle_inventario , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from detalle_inventario WHERE ("; 
		$val = array();
		if( $detalle_inventario->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $detalle_inventario->getIdProducto() );
		}

		if( $detalle_inventario->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $detalle_inventario->getIdSucursal() );
		}

		if( $detalle_inventario->getPrecioVenta() != NULL){
			$sql .= " precio_venta = ? AND";
			array_push( $val, $detalle_inventario->getPrecioVenta() );
		}

		if( $detalle_inventario->getPrecioCompra() != NULL){
			$sql .= " precio_compra = ? AND";
			array_push( $val, $detalle_inventario->getPrecioCompra() );
		}

		if( $detalle_inventario->getExistencias() != NULL){
			$sql .= " existencias = ? AND";
			array_push( $val, $detalle_inventario->getExistencias() );
		}

		if( $detalle_inventario->getExistenciasProcesadas() != NULL){
			$sql .= " existencias_procesadas = ? AND";
			array_push( $val, $detalle_inventario->getExistenciasProcesadas() );
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
			$bar =  new DetalleInventario($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_producto"],$foo["id_sucursal"] );
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
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario a actualizar.
	  **/
	private static final function update( $detalle_inventario )
	{
		$sql = "UPDATE detalle_inventario SET  precio_venta = ?, precio_compra = ?, existencias = ?, existencias_procesadas = ? WHERE  id_producto = ? AND id_sucursal = ?;";
		$params = array( 
			$detalle_inventario->getPrecioVenta(), 
			$detalle_inventario->getPrecioCompra(), 
			$detalle_inventario->getExistencias(), 
			$detalle_inventario->getExistenciasProcesadas(), 
			$detalle_inventario->getIdProducto(),$detalle_inventario->getIdSucursal(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleInventario suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleInventario dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario a crear.
	  **/
	private static final function create( &$detalle_inventario )
	{
		$sql = "INSERT INTO detalle_inventario ( id_producto, id_sucursal, precio_venta, precio_compra, existencias, existencias_procesadas ) VALUES ( ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$detalle_inventario->getIdProducto(), 
			$detalle_inventario->getIdSucursal(), 
			$detalle_inventario->getPrecioVenta(), 
			$detalle_inventario->getPrecioCompra(), 
			$detalle_inventario->getExistencias(), 
			$detalle_inventario->getExistenciasProcesadas(), 
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
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleInventario} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleInventario}.
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
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $detalle_inventarioA , $detalle_inventarioB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from detalle_inventario WHERE ("; 
		$val = array();
		if( (($a = $detalle_inventarioA->getIdProducto()) != NULL) & ( ($b = $detalle_inventarioB->getIdProducto()) != NULL) ){
				$sql .= " id_producto >= ? AND id_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_inventarioA->getIdSucursal()) != NULL) & ( ($b = $detalle_inventarioB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_inventarioA->getPrecioVenta()) != NULL) & ( ($b = $detalle_inventarioB->getPrecioVenta()) != NULL) ){
				$sql .= " precio_venta >= ? AND precio_venta <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " precio_venta = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_inventarioA->getPrecioCompra()) != NULL) & ( ($b = $detalle_inventarioB->getPrecioCompra()) != NULL) ){
				$sql .= " precio_compra >= ? AND precio_compra <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " precio_compra = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_inventarioA->getExistencias()) != NULL) & ( ($b = $detalle_inventarioB->getExistencias()) != NULL) ){
				$sql .= " existencias >= ? AND existencias <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " existencias = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_inventarioA->getExistenciasProcesadas()) != NULL) & ( ($b = $detalle_inventarioB->getExistenciasProcesadas()) != NULL) ){
				$sql .= " existencias_procesadas >= ? AND existencias_procesadas <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " existencias_procesadas = ? AND"; 
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
    		array_push( $ar, new DetalleInventario($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleInventario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario a eliminar
	  **/
	public static final function delete( &$detalle_inventario )
	{
		if(self::getByPK($detalle_inventario->getIdProducto(), $detalle_inventario->getIdSucursal()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_inventario WHERE  id_producto = ? AND id_sucursal = ?;";
		$params = array( $detalle_inventario->getIdProducto(), $detalle_inventario->getIdSucursal() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
