<?php
/** DetalleCompraCliente Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link DetalleCompraCliente }. 
  * @author no author especified
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class DetalleCompraClienteDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_compra, $id_producto ){
			$pk = "";
			$pk .= $id_compra . "-";
			$pk .= $id_producto . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_compra, $id_producto){
			$pk = "";
			$pk .= $id_compra . "-";
			$pk .= $id_producto . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_compra, $id_producto ){
			$pk = "";
			$pk .= $id_compra . "-";
			$pk .= $id_producto . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleCompraCliente} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$detalle_compra_cliente )
	{
		if(  self::getByPK(  $detalle_compra_cliente->getIdCompra() , $detalle_compra_cliente->getIdProducto() ) !== NULL )
		{
			try{ return DetalleCompraClienteDAOBase::update( $detalle_compra_cliente) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return DetalleCompraClienteDAOBase::create( $detalle_compra_cliente) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link DetalleCompraCliente} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleCompraCliente} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleCompraCliente Un objeto del tipo {@link DetalleCompraCliente}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_compra, $id_producto )
	{
		if(self::recordExists(  $id_compra, $id_producto)){
			return self::getRecord( $id_compra, $id_producto );
		}
		$sql = "SELECT * FROM detalle_compra_cliente WHERE (id_compra = ? AND id_producto = ? ) LIMIT 1;";
		$params = array(  $id_compra, $id_producto );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new DetalleCompraCliente( $rs );
			self::pushRecord( $foo,  $id_compra, $id_producto );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleCompraCliente}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link DetalleCompraCliente}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from detalle_compra_cliente";
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
			$bar = new DetalleCompraCliente($foo);
    		array_push( $allData, $bar);
			//id_compra
			//id_producto
    		self::pushRecord( $bar, $foo["id_compra"],$foo["id_producto"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraCliente} de la base de datos. 
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
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $detalle_compra_cliente , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from detalle_compra_cliente WHERE ("; 
		$val = array();
		if( $detalle_compra_cliente->getIdCompra() != NULL){
			$sql .= " id_compra = ? AND";
			array_push( $val, $detalle_compra_cliente->getIdCompra() );
		}

		if( $detalle_compra_cliente->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $detalle_compra_cliente->getIdProducto() );
		}

		if( $detalle_compra_cliente->getCantidad() != NULL){
			$sql .= " cantidad = ? AND";
			array_push( $val, $detalle_compra_cliente->getCantidad() );
		}

		if( $detalle_compra_cliente->getPrecio() != NULL){
			$sql .= " precio = ? AND";
			array_push( $val, $detalle_compra_cliente->getPrecio() );
		}

		if( $detalle_compra_cliente->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $detalle_compra_cliente->getDescuento() );
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
			$bar =  new DetalleCompraCliente($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_compra"],$foo["id_producto"] );
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
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente a actualizar.
	  **/
	private static final function update( $detalle_compra_cliente )
	{
		$sql = "UPDATE detalle_compra_cliente SET  cantidad = ?, precio = ?, descuento = ? WHERE  id_compra = ? AND id_producto = ?;";
		$params = array( 
			$detalle_compra_cliente->getCantidad(), 
			$detalle_compra_cliente->getPrecio(), 
			$detalle_compra_cliente->getDescuento(), 
			$detalle_compra_cliente->getIdCompra(),$detalle_compra_cliente->getIdProducto(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleCompraCliente suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleCompraCliente dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente a crear.
	  **/
	private static final function create( &$detalle_compra_cliente )
	{
		$sql = "INSERT INTO detalle_compra_cliente ( id_compra, id_producto, cantidad, precio, descuento ) VALUES ( ?, ?, ?, ?, ?);";
		$params = array( 
			$detalle_compra_cliente->getIdCompra(), 
			$detalle_compra_cliente->getIdProducto(), 
			$detalle_compra_cliente->getCantidad(), 
			$detalle_compra_cliente->getPrecio(), 
			$detalle_compra_cliente->getDescuento(), 
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
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraCliente} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleCompraCliente}.
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
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $detalle_compra_clienteA , $detalle_compra_clienteB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from detalle_compra_cliente WHERE ("; 
		$val = array();
		if( (($a = $detalle_compra_clienteA->getIdCompra()) != NULL) & ( ($b = $detalle_compra_clienteB->getIdCompra()) != NULL) ){
				$sql .= " id_compra >= ? AND id_compra <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_compra = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_compra_clienteA->getIdProducto()) != NULL) & ( ($b = $detalle_compra_clienteB->getIdProducto()) != NULL) ){
				$sql .= " id_producto >= ? AND id_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_compra_clienteA->getCantidad()) != NULL) & ( ($b = $detalle_compra_clienteB->getCantidad()) != NULL) ){
				$sql .= " cantidad >= ? AND cantidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " cantidad = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_compra_clienteA->getPrecio()) != NULL) & ( ($b = $detalle_compra_clienteB->getPrecio()) != NULL) ){
				$sql .= " precio >= ? AND precio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " precio = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_compra_clienteA->getDescuento()) != NULL) & ( ($b = $detalle_compra_clienteB->getDescuento()) != NULL) ){
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
    		array_push( $ar, new DetalleCompraCliente($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleCompraCliente suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente a eliminar
	  **/
	public static final function delete( &$detalle_compra_cliente )
	{
		if(self::getByPK($detalle_compra_cliente->getIdCompra(), $detalle_compra_cliente->getIdProducto()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_compra_cliente WHERE  id_compra = ? AND id_producto = ?;";
		$params = array( $detalle_compra_cliente->getIdCompra(), $detalle_compra_cliente->getIdProducto() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
