<?php
/** DetalleVenta Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link DetalleVenta }. 
  * @author no author especified
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class DetalleVentaDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_venta, $id_producto ){
			$pk = "";
			$pk .= $id_venta . "-";
			$pk .= $id_producto . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_venta, $id_producto){
			$pk = "";
			$pk .= $id_venta . "-";
			$pk .= $id_producto . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_venta, $id_producto ){
			$pk = "";
			$pk .= $id_venta . "-";
			$pk .= $id_producto . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleVenta} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$detalle_venta )
	{
		if(  self::getByPK(  $detalle_venta->getIdVenta() , $detalle_venta->getIdProducto() ) !== NULL )
		{
			try{ return DetalleVentaDAOBase::update( $detalle_venta) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return DetalleVentaDAOBase::create( $detalle_venta) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link DetalleVenta} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleVenta} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleVenta Un objeto del tipo {@link DetalleVenta}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_venta, $id_producto )
	{
		if(self::recordExists(  $id_venta, $id_producto)){
			return self::getRecord( $id_venta, $id_producto );
		}
		$sql = "SELECT * FROM detalle_venta WHERE (id_venta = ? AND id_producto = ? ) LIMIT 1;";
		$params = array(  $id_venta, $id_producto );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new DetalleVenta( $rs );
			self::pushRecord( $foo,  $id_venta, $id_producto );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link DetalleVenta}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from detalle_venta";
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
			$bar = new DetalleVenta($foo);
    		array_push( $allData, $bar);
			//id_venta
			//id_producto
    		self::pushRecord( $bar, $foo["id_venta"],$foo["id_producto"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleVenta} de la base de datos. 
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
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $detalle_venta , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from detalle_venta WHERE ("; 
		$val = array();
		if( $detalle_venta->getIdVenta() != NULL){
			$sql .= " id_venta = ? AND";
			array_push( $val, $detalle_venta->getIdVenta() );
		}

		if( $detalle_venta->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $detalle_venta->getIdProducto() );
		}

		if( $detalle_venta->getCantidad() != NULL){
			$sql .= " cantidad = ? AND";
			array_push( $val, $detalle_venta->getCantidad() );
		}

		if( $detalle_venta->getCantidadProcesada() != NULL){
			$sql .= " cantidad_procesada = ? AND";
			array_push( $val, $detalle_venta->getCantidadProcesada() );
		}

		if( $detalle_venta->getPrecio() != NULL){
			$sql .= " precio = ? AND";
			array_push( $val, $detalle_venta->getPrecio() );
		}

		if( $detalle_venta->getPrecioProcesada() != NULL){
			$sql .= " precio_procesada = ? AND";
			array_push( $val, $detalle_venta->getPrecioProcesada() );
		}

		if( $detalle_venta->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $detalle_venta->getDescuento() );
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
			$bar =  new DetalleVenta($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_venta"],$foo["id_producto"] );
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
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta a actualizar.
	  **/
	private static final function update( $detalle_venta )
	{
		$sql = "UPDATE detalle_venta SET  cantidad = ?, cantidad_procesada = ?, precio = ?, precio_procesada = ?, descuento = ? WHERE  id_venta = ? AND id_producto = ?;";
		$params = array( 
			$detalle_venta->getCantidad(), 
			$detalle_venta->getCantidadProcesada(), 
			$detalle_venta->getPrecio(), 
			$detalle_venta->getPrecioProcesada(), 
			$detalle_venta->getDescuento(), 
			$detalle_venta->getIdVenta(),$detalle_venta->getIdProducto(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleVenta suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleVenta dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta a crear.
	  **/
	private static final function create( &$detalle_venta )
	{
		$sql = "INSERT INTO detalle_venta ( id_venta, id_producto, cantidad, cantidad_procesada, precio, precio_procesada, descuento ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$detalle_venta->getIdVenta(), 
			$detalle_venta->getIdProducto(), 
			$detalle_venta->getCantidad(), 
			$detalle_venta->getCantidadProcesada(), 
			$detalle_venta->getPrecio(), 
			$detalle_venta->getPrecioProcesada(), 
			$detalle_venta->getDescuento(), 
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
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleVenta} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleVenta}.
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
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $detalle_ventaA , $detalle_ventaB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from detalle_venta WHERE ("; 
		$val = array();
		if( (($a = $detalle_ventaA->getIdVenta()) != NULL) & ( ($b = $detalle_ventaB->getIdVenta()) != NULL) ){
				$sql .= " id_venta >= ? AND id_venta <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_venta = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_ventaA->getIdProducto()) != NULL) & ( ($b = $detalle_ventaB->getIdProducto()) != NULL) ){
				$sql .= " id_producto >= ? AND id_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_ventaA->getCantidad()) != NULL) & ( ($b = $detalle_ventaB->getCantidad()) != NULL) ){
				$sql .= " cantidad >= ? AND cantidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " cantidad = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_ventaA->getCantidadProcesada()) != NULL) & ( ($b = $detalle_ventaB->getCantidadProcesada()) != NULL) ){
				$sql .= " cantidad_procesada >= ? AND cantidad_procesada <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " cantidad_procesada = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_ventaA->getPrecio()) != NULL) & ( ($b = $detalle_ventaB->getPrecio()) != NULL) ){
				$sql .= " precio >= ? AND precio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " precio = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_ventaA->getPrecioProcesada()) != NULL) & ( ($b = $detalle_ventaB->getPrecioProcesada()) != NULL) ){
				$sql .= " precio_procesada >= ? AND precio_procesada <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " precio_procesada = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_ventaA->getDescuento()) != NULL) & ( ($b = $detalle_ventaB->getDescuento()) != NULL) ){
				$sql .= " descuento >= ? AND descuento <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descuento = ? AND"; 
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
    		array_push( $ar, new DetalleVenta($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleVenta suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta a eliminar
	  **/
	public static final function delete( &$detalle_venta )
	{
		if(self::getByPK($detalle_venta->getIdVenta(), $detalle_venta->getIdProducto()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_venta WHERE  id_venta = ? AND id_producto = ?;";
		$params = array( $detalle_venta->getIdVenta(), $detalle_venta->getIdProducto() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
