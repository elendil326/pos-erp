<?php
/** VentaProducto Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link VentaProducto }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class VentaProductoDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_venta, $id_producto, $id_unidad ){
			$pk = "";
			$pk .= $id_venta . "-";
			$pk .= $id_producto . "-";
			$pk .= $id_unidad . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_venta, $id_producto, $id_unidad){
			$pk = "";
			$pk .= $id_venta . "-";
			$pk .= $id_producto . "-";
			$pk .= $id_unidad . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_venta, $id_producto, $id_unidad ){
			$pk = "";
			$pk .= $id_venta . "-";
			$pk .= $id_producto . "-";
			$pk .= $id_unidad . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link VentaProducto} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param VentaProducto [$venta_producto] El objeto de tipo VentaProducto
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$venta_producto )
	{
		if(  self::getByPK(  $venta_producto->getIdVenta() , $venta_producto->getIdProducto() , $venta_producto->getIdUnidad() ) !== NULL )
		{
			try{ return VentaProductoDAOBase::update( $venta_producto) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return VentaProductoDAOBase::create( $venta_producto) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link VentaProducto} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link VentaProducto} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link VentaProducto Un objeto del tipo {@link VentaProducto}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_venta, $id_producto, $id_unidad )
	{
		if(self::recordExists(  $id_venta, $id_producto, $id_unidad)){
			return self::getRecord( $id_venta, $id_producto, $id_unidad );
		}
		$sql = "SELECT * FROM venta_producto WHERE (id_venta = ? AND id_producto = ? AND id_unidad = ? ) LIMIT 1;";
		$params = array(  $id_venta, $id_producto, $id_unidad );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new VentaProducto( $rs );
			self::pushRecord( $foo,  $id_venta, $id_producto, $id_unidad );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link VentaProducto}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link VentaProducto}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from venta_producto";
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
			$bar = new VentaProducto($foo);
    		array_push( $allData, $bar);
			//id_venta
			//id_producto
			//id_unidad
    		self::pushRecord( $bar, $foo["id_venta"],$foo["id_producto"],$foo["id_unidad"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link VentaProducto} de la base de datos. 
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
	  * @param VentaProducto [$venta_producto] El objeto de tipo VentaProducto
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $venta_producto , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from venta_producto WHERE ("; 
		$val = array();
		if( $venta_producto->getIdVenta() != NULL){
			$sql .= " id_venta = ? AND";
			array_push( $val, $venta_producto->getIdVenta() );
		}

		if( $venta_producto->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $venta_producto->getIdProducto() );
		}

		if( $venta_producto->getPrecio() != NULL){
			$sql .= " precio = ? AND";
			array_push( $val, $venta_producto->getPrecio() );
		}

		if( $venta_producto->getCantidad() != NULL){
			$sql .= " cantidad = ? AND";
			array_push( $val, $venta_producto->getCantidad() );
		}

		if( $venta_producto->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $venta_producto->getDescuento() );
		}

		if( $venta_producto->getImpuesto() != NULL){
			$sql .= " impuesto = ? AND";
			array_push( $val, $venta_producto->getImpuesto() );
		}

		if( $venta_producto->getRetencion() != NULL){
			$sql .= " retencion = ? AND";
			array_push( $val, $venta_producto->getRetencion() );
		}

		if( $venta_producto->getIdUnidad() != NULL){
			$sql .= " id_unidad = ? AND";
			array_push( $val, $venta_producto->getIdUnidad() );
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
			$bar =  new VentaProducto($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_venta"],$foo["id_producto"],$foo["id_unidad"] );
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
	  * @param VentaProducto [$venta_producto] El objeto de tipo VentaProducto a actualizar.
	  **/
	private static final function update( $venta_producto )
	{
		$sql = "UPDATE venta_producto SET  precio = ?, cantidad = ?, descuento = ?, impuesto = ?, retencion = ? WHERE  id_venta = ? AND id_producto = ? AND id_unidad = ?;";
		$params = array( 
			$venta_producto->getPrecio(), 
			$venta_producto->getCantidad(), 
			$venta_producto->getDescuento(), 
			$venta_producto->getImpuesto(), 
			$venta_producto->getRetencion(), 
			$venta_producto->getIdVenta(),$venta_producto->getIdProducto(),$venta_producto->getIdUnidad(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto VentaProducto suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto VentaProducto dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param VentaProducto [$venta_producto] El objeto de tipo VentaProducto a crear.
	  **/
	private static final function create( &$venta_producto )
	{
		$sql = "INSERT INTO venta_producto ( id_venta, id_producto, precio, cantidad, descuento, impuesto, retencion, id_unidad ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$venta_producto->getIdVenta(), 
			$venta_producto->getIdProducto(), 
			$venta_producto->getPrecio(), 
			$venta_producto->getCantidad(), 
			$venta_producto->getDescuento(), 
			$venta_producto->getImpuesto(), 
			$venta_producto->getRetencion(), 
			$venta_producto->getIdUnidad(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */   /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link VentaProducto} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link VentaProducto}.
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
	  * @param VentaProducto [$venta_producto] El objeto de tipo VentaProducto
	  * @param VentaProducto [$venta_producto] El objeto de tipo VentaProducto
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $venta_productoA , $venta_productoB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from venta_producto WHERE ("; 
		$val = array();
		if( (($a = $venta_productoA->getIdVenta()) !== NULL) & ( ($b = $venta_productoB->getIdVenta()) !== NULL) ){
				$sql .= " id_venta >= ? AND id_venta <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_venta = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $venta_productoA->getIdProducto()) !== NULL) & ( ($b = $venta_productoB->getIdProducto()) !== NULL) ){
				$sql .= " id_producto >= ? AND id_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_producto = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $venta_productoA->getPrecio()) !== NULL) & ( ($b = $venta_productoB->getPrecio()) !== NULL) ){
				$sql .= " precio >= ? AND precio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " precio = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $venta_productoA->getCantidad()) !== NULL) & ( ($b = $venta_productoB->getCantidad()) !== NULL) ){
				$sql .= " cantidad >= ? AND cantidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " cantidad = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $venta_productoA->getDescuento()) !== NULL) & ( ($b = $venta_productoB->getDescuento()) !== NULL) ){
				$sql .= " descuento >= ? AND descuento <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " descuento = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $venta_productoA->getImpuesto()) !== NULL) & ( ($b = $venta_productoB->getImpuesto()) !== NULL) ){
				$sql .= " impuesto >= ? AND impuesto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " impuesto = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $venta_productoA->getRetencion()) !== NULL) & ( ($b = $venta_productoB->getRetencion()) !== NULL) ){
				$sql .= " retencion >= ? AND retencion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " retencion = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $venta_productoA->getIdUnidad()) !== NULL) & ( ($b = $venta_productoB->getIdUnidad()) !== NULL) ){
				$sql .= " id_unidad >= ? AND id_unidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_unidad = ? AND"; 
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
    		array_push( $ar, new VentaProducto($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto VentaProducto suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param VentaProducto [$venta_producto] El objeto de tipo VentaProducto a eliminar
	  **/
	public static final function delete( &$venta_producto )
	{
		if(self::getByPK($venta_producto->getIdVenta(), $venta_producto->getIdProducto(), $venta_producto->getIdUnidad()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM venta_producto WHERE  id_venta = ? AND id_producto = ? AND id_unidad = ?;";
		$params = array( $venta_producto->getIdVenta(), $venta_producto->getIdProducto(), $venta_producto->getIdUnidad() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
