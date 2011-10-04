<?php
/** ProductoPaquete Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link ProductoPaquete }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class ProductoPaqueteDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_producto, $id_paquete ){
			$pk = "";
			$pk .= $id_producto . "-";
			$pk .= $id_paquete . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_producto, $id_paquete){
			$pk = "";
			$pk .= $id_producto . "-";
			$pk .= $id_paquete . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_producto, $id_paquete ){
			$pk = "";
			$pk .= $id_producto . "-";
			$pk .= $id_paquete . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link ProductoPaquete} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param ProductoPaquete [$producto_paquete] El objeto de tipo ProductoPaquete
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$producto_paquete )
	{
		if(  self::getByPK(  $producto_paquete->getIdProducto() , $producto_paquete->getIdPaquete() ) !== NULL )
		{
			try{ return ProductoPaqueteDAOBase::update( $producto_paquete) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return ProductoPaqueteDAOBase::create( $producto_paquete) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link ProductoPaquete} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link ProductoPaquete} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link ProductoPaquete Un objeto del tipo {@link ProductoPaquete}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_producto, $id_paquete )
	{
		if(self::recordExists(  $id_producto, $id_paquete)){
			return self::getRecord( $id_producto, $id_paquete );
		}
		$sql = "SELECT * FROM producto_paquete WHERE (id_producto = ? AND id_paquete = ? ) LIMIT 1;";
		$params = array(  $id_producto, $id_paquete );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new ProductoPaquete( $rs );
			self::pushRecord( $foo,  $id_producto, $id_paquete );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ProductoPaquete}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link ProductoPaquete}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from producto_paquete";
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
			$bar = new ProductoPaquete($foo);
    		array_push( $allData, $bar);
			//id_producto
			//id_paquete
    		self::pushRecord( $bar, $foo["id_producto"],$foo["id_paquete"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ProductoPaquete} de la base de datos. 
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
	  * @param ProductoPaquete [$producto_paquete] El objeto de tipo ProductoPaquete
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $producto_paquete , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from producto_paquete WHERE ("; 
		$val = array();
		if( $producto_paquete->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $producto_paquete->getIdProducto() );
		}

		if( $producto_paquete->getIdPaquete() != NULL){
			$sql .= " id_paquete = ? AND";
			array_push( $val, $producto_paquete->getIdPaquete() );
		}

		if( $producto_paquete->getCantidad() != NULL){
			$sql .= " cantidad = ? AND";
			array_push( $val, $producto_paquete->getCantidad() );
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
			$bar =  new ProductoPaquete($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_producto"],$foo["id_paquete"] );
		}
		return $ar;
	}


	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cu�ntas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param ProductoPaquete [$producto_paquete] El objeto de tipo ProductoPaquete a actualizar.
	  **/
	private static final function update( $producto_paquete )
	{
		$sql = "UPDATE producto_paquete SET  cantidad = ? WHERE  id_producto = ? AND id_paquete = ?;";
		$params = array( 
			$producto_paquete->getCantidad(), 
			$producto_paquete->getIdProducto(),$producto_paquete->getIdPaquete(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto ProductoPaquete suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto ProductoPaquete dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param ProductoPaquete [$producto_paquete] El objeto de tipo ProductoPaquete a crear.
	  **/
	private static final function create( &$producto_paquete )
	{
		$sql = "INSERT INTO producto_paquete ( id_producto, id_paquete, cantidad ) VALUES ( ?, ?, ?);";
		$params = array( 
			$producto_paquete->getIdProducto(), 
			$producto_paquete->getIdPaquete(), 
			$producto_paquete->getCantidad(), 
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
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ProductoPaquete} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link ProductoPaquete}.
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
	  * @param ProductoPaquete [$producto_paquete] El objeto de tipo ProductoPaquete
	  * @param ProductoPaquete [$producto_paquete] El objeto de tipo ProductoPaquete
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $producto_paqueteA , $producto_paqueteB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from producto_paquete WHERE ("; 
		$val = array();
		if( (($a = $producto_paqueteA->getIdProducto()) != NULL) & ( ($b = $producto_paqueteB->getIdProducto()) != NULL) ){
				$sql .= " id_producto >= ? AND id_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $producto_paqueteA->getIdPaquete()) != NULL) & ( ($b = $producto_paqueteB->getIdPaquete()) != NULL) ){
				$sql .= " id_paquete >= ? AND id_paquete <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_paquete = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $producto_paqueteA->getCantidad()) != NULL) & ( ($b = $producto_paqueteB->getCantidad()) != NULL) ){
				$sql .= " cantidad >= ? AND cantidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " cantidad = ? AND"; 
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
    		array_push( $ar, new ProductoPaquete($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto ProductoPaquete suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param ProductoPaquete [$producto_paquete] El objeto de tipo ProductoPaquete a eliminar
	  **/
	public static final function delete( &$producto_paquete )
	{
		if(self::getByPK($producto_paquete->getIdProducto(), $producto_paquete->getIdPaquete()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM producto_paquete WHERE  id_producto = ? AND id_paquete = ?;";
		$params = array( $producto_paquete->getIdProducto(), $producto_paquete->getIdPaquete() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}