<?php
/** CompraProveedorFragmentacion Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link CompraProveedorFragmentacion }. 
  * @author no author especified
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class CompraProveedorFragmentacionDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_fragmentacion ){
			$pk = "";
			$pk .= $id_fragmentacion . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_fragmentacion){
			$pk = "";
			$pk .= $id_fragmentacion . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_fragmentacion ){
			$pk = "";
			$pk .= $id_fragmentacion . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CompraProveedorFragmentacion} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param CompraProveedorFragmentacion [$compra_proveedor_fragmentacion] El objeto de tipo CompraProveedorFragmentacion
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$compra_proveedor_fragmentacion )
	{
		if(  self::getByPK(  $compra_proveedor_fragmentacion->getIdFragmentacion() ) !== NULL )
		{
			try{ return CompraProveedorFragmentacionDAOBase::update( $compra_proveedor_fragmentacion) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return CompraProveedorFragmentacionDAOBase::create( $compra_proveedor_fragmentacion) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link CompraProveedorFragmentacion} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CompraProveedorFragmentacion} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CompraProveedorFragmentacion Un objeto del tipo {@link CompraProveedorFragmentacion}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_fragmentacion )
	{
		if(self::recordExists(  $id_fragmentacion)){
			return self::getRecord( $id_fragmentacion );
		}
		$sql = "SELECT * FROM compra_proveedor_fragmentacion WHERE (id_fragmentacion = ? ) LIMIT 1;";
		$params = array(  $id_fragmentacion );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new CompraProveedorFragmentacion( $rs );
			self::pushRecord( $foo,  $id_fragmentacion );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CompraProveedorFragmentacion}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link CompraProveedorFragmentacion}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from compra_proveedor_fragmentacion";
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
			$bar = new CompraProveedorFragmentacion($foo);
    		array_push( $allData, $bar);
			//id_fragmentacion
    		self::pushRecord( $bar, $foo["id_fragmentacion"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraProveedorFragmentacion} de la base de datos. 
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
	  * @param CompraProveedorFragmentacion [$compra_proveedor_fragmentacion] El objeto de tipo CompraProveedorFragmentacion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $compra_proveedor_fragmentacion , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from compra_proveedor_fragmentacion WHERE ("; 
		$val = array();
		if( $compra_proveedor_fragmentacion->getIdFragmentacion() != NULL){
			$sql .= " id_fragmentacion = ? AND";
			array_push( $val, $compra_proveedor_fragmentacion->getIdFragmentacion() );
		}

		if( $compra_proveedor_fragmentacion->getIdCompraProveedor() != NULL){
			$sql .= " id_compra_proveedor = ? AND";
			array_push( $val, $compra_proveedor_fragmentacion->getIdCompraProveedor() );
		}

		if( $compra_proveedor_fragmentacion->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $compra_proveedor_fragmentacion->getIdProducto() );
		}

		if( $compra_proveedor_fragmentacion->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $compra_proveedor_fragmentacion->getFecha() );
		}

		if( $compra_proveedor_fragmentacion->getDescripcion() != NULL){
			$sql .= " descripcion = ? AND";
			array_push( $val, $compra_proveedor_fragmentacion->getDescripcion() );
		}

		if( $compra_proveedor_fragmentacion->getCantidad() != NULL){
			$sql .= " cantidad = ? AND";
			array_push( $val, $compra_proveedor_fragmentacion->getCantidad() );
		}

		if( $compra_proveedor_fragmentacion->getProcesada() != NULL){
			$sql .= " procesada = ? AND";
			array_push( $val, $compra_proveedor_fragmentacion->getProcesada() );
		}

		if( $compra_proveedor_fragmentacion->getPrecio() != NULL){
			$sql .= " precio = ? AND";
			array_push( $val, $compra_proveedor_fragmentacion->getPrecio() );
		}

		if( $compra_proveedor_fragmentacion->getDescripcionRefId() != NULL){
			$sql .= " descripcion_ref_id = ? AND";
			array_push( $val, $compra_proveedor_fragmentacion->getDescripcionRefId() );
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
			$bar =  new CompraProveedorFragmentacion($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_fragmentacion"] );
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
	  * @param CompraProveedorFragmentacion [$compra_proveedor_fragmentacion] El objeto de tipo CompraProveedorFragmentacion a actualizar.
	  **/
	private static final function update( $compra_proveedor_fragmentacion )
	{
		$sql = "UPDATE compra_proveedor_fragmentacion SET  id_compra_proveedor = ?, id_producto = ?, fecha = ?, descripcion = ?, cantidad = ?, procesada = ?, precio = ?, descripcion_ref_id = ? WHERE  id_fragmentacion = ?;";
		$params = array( 
			$compra_proveedor_fragmentacion->getIdCompraProveedor(), 
			$compra_proveedor_fragmentacion->getIdProducto(), 
			$compra_proveedor_fragmentacion->getFecha(), 
			$compra_proveedor_fragmentacion->getDescripcion(), 
			$compra_proveedor_fragmentacion->getCantidad(), 
			$compra_proveedor_fragmentacion->getProcesada(), 
			$compra_proveedor_fragmentacion->getPrecio(), 
			$compra_proveedor_fragmentacion->getDescripcionRefId(), 
			$compra_proveedor_fragmentacion->getIdFragmentacion(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CompraProveedorFragmentacion suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CompraProveedorFragmentacion dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CompraProveedorFragmentacion [$compra_proveedor_fragmentacion] El objeto de tipo CompraProveedorFragmentacion a crear.
	  **/
	private static final function create( &$compra_proveedor_fragmentacion )
	{
		$sql = "INSERT INTO compra_proveedor_fragmentacion ( id_fragmentacion, id_compra_proveedor, id_producto, fecha, descripcion, cantidad, procesada, precio, descripcion_ref_id ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$compra_proveedor_fragmentacion->getIdFragmentacion(), 
			$compra_proveedor_fragmentacion->getIdCompraProveedor(), 
			$compra_proveedor_fragmentacion->getIdProducto(), 
			$compra_proveedor_fragmentacion->getFecha(), 
			$compra_proveedor_fragmentacion->getDescripcion(), 
			$compra_proveedor_fragmentacion->getCantidad(), 
			$compra_proveedor_fragmentacion->getProcesada(), 
			$compra_proveedor_fragmentacion->getPrecio(), 
			$compra_proveedor_fragmentacion->getDescripcionRefId(), 
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
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraProveedorFragmentacion} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CompraProveedorFragmentacion}.
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
	  * @param CompraProveedorFragmentacion [$compra_proveedor_fragmentacion] El objeto de tipo CompraProveedorFragmentacion
	  * @param CompraProveedorFragmentacion [$compra_proveedor_fragmentacion] El objeto de tipo CompraProveedorFragmentacion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $compra_proveedor_fragmentacionA , $compra_proveedor_fragmentacionB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from compra_proveedor_fragmentacion WHERE ("; 
		$val = array();
		if( (($a = $compra_proveedor_fragmentacionA->getIdFragmentacion()) != NULL) & ( ($b = $compra_proveedor_fragmentacionB->getIdFragmentacion()) != NULL) ){
				$sql .= " id_fragmentacion >= ? AND id_fragmentacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_fragmentacion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedor_fragmentacionA->getIdCompraProveedor()) != NULL) & ( ($b = $compra_proveedor_fragmentacionB->getIdCompraProveedor()) != NULL) ){
				$sql .= " id_compra_proveedor >= ? AND id_compra_proveedor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_compra_proveedor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedor_fragmentacionA->getIdProducto()) != NULL) & ( ($b = $compra_proveedor_fragmentacionB->getIdProducto()) != NULL) ){
				$sql .= " id_producto >= ? AND id_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedor_fragmentacionA->getFecha()) != NULL) & ( ($b = $compra_proveedor_fragmentacionB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedor_fragmentacionA->getDescripcion()) != NULL) & ( ($b = $compra_proveedor_fragmentacionB->getDescripcion()) != NULL) ){
				$sql .= " descripcion >= ? AND descripcion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descripcion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedor_fragmentacionA->getCantidad()) != NULL) & ( ($b = $compra_proveedor_fragmentacionB->getCantidad()) != NULL) ){
				$sql .= " cantidad >= ? AND cantidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " cantidad = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedor_fragmentacionA->getProcesada()) != NULL) & ( ($b = $compra_proveedor_fragmentacionB->getProcesada()) != NULL) ){
				$sql .= " procesada >= ? AND procesada <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " procesada = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedor_fragmentacionA->getPrecio()) != NULL) & ( ($b = $compra_proveedor_fragmentacionB->getPrecio()) != NULL) ){
				$sql .= " precio >= ? AND precio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " precio = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedor_fragmentacionA->getDescripcionRefId()) != NULL) & ( ($b = $compra_proveedor_fragmentacionB->getDescripcionRefId()) != NULL) ){
				$sql .= " descripcion_ref_id >= ? AND descripcion_ref_id <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descripcion_ref_id = ? AND"; 
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
    		array_push( $ar, new CompraProveedorFragmentacion($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CompraProveedorFragmentacion suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param CompraProveedorFragmentacion [$compra_proveedor_fragmentacion] El objeto de tipo CompraProveedorFragmentacion a eliminar
	  **/
	public static final function delete( &$compra_proveedor_fragmentacion )
	{
		if(self::getByPK($compra_proveedor_fragmentacion->getIdFragmentacion()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM compra_proveedor_fragmentacion WHERE  id_fragmentacion = ?;";
		$params = array( $compra_proveedor_fragmentacion->getIdFragmentacion() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
