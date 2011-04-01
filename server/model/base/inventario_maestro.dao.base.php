<?php
/** InventarioMaestro Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link InventarioMaestro }. 
  * @author no author especified
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class InventarioMaestroDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_producto, $id_compra_proveedor ){
			$pk = "";
			$pk .= $id_producto . "-";
			$pk .= $id_compra_proveedor . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_producto, $id_compra_proveedor){
			$pk = "";
			$pk .= $id_producto . "-";
			$pk .= $id_compra_proveedor . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_producto, $id_compra_proveedor ){
			$pk = "";
			$pk .= $id_producto . "-";
			$pk .= $id_compra_proveedor . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link InventarioMaestro} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param InventarioMaestro [$inventario_maestro] El objeto de tipo InventarioMaestro
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$inventario_maestro )
	{
		if(  self::getByPK(  $inventario_maestro->getIdProducto() , $inventario_maestro->getIdCompraProveedor() ) !== NULL )
		{
			try{ return InventarioMaestroDAOBase::update( $inventario_maestro) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return InventarioMaestroDAOBase::create( $inventario_maestro) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link InventarioMaestro} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link InventarioMaestro} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link InventarioMaestro Un objeto del tipo {@link InventarioMaestro}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_producto, $id_compra_proveedor )
	{
		if(self::recordExists(  $id_producto, $id_compra_proveedor)){
			return self::getRecord( $id_producto, $id_compra_proveedor );
		}
		$sql = "SELECT * FROM inventario_maestro WHERE (id_producto = ? AND id_compra_proveedor = ? ) LIMIT 1;";
		$params = array(  $id_producto, $id_compra_proveedor );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new InventarioMaestro( $rs );
			self::pushRecord( $foo,  $id_producto, $id_compra_proveedor );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link InventarioMaestro}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link InventarioMaestro}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from inventario_maestro";
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
			$bar = new InventarioMaestro($foo);
    		array_push( $allData, $bar);
			//id_producto
			//id_compra_proveedor
    		self::pushRecord( $bar, $foo["id_producto"],$foo["id_compra_proveedor"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link InventarioMaestro} de la base de datos. 
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
	  * @param InventarioMaestro [$inventario_maestro] El objeto de tipo InventarioMaestro
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $inventario_maestro , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from inventario_maestro WHERE ("; 
		$val = array();
		if( $inventario_maestro->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $inventario_maestro->getIdProducto() );
		}

		if( $inventario_maestro->getIdCompraProveedor() != NULL){
			$sql .= " id_compra_proveedor = ? AND";
			array_push( $val, $inventario_maestro->getIdCompraProveedor() );
		}

		if( $inventario_maestro->getExistencias() != NULL){
			$sql .= " existencias = ? AND";
			array_push( $val, $inventario_maestro->getExistencias() );
		}

		if( $inventario_maestro->getExistenciasProcesadas() != NULL){
			$sql .= " existencias_procesadas = ? AND";
			array_push( $val, $inventario_maestro->getExistenciasProcesadas() );
		}

		if( $inventario_maestro->getSitioDescarga() != NULL){
			$sql .= " sitio_descarga = ? AND";
			array_push( $val, $inventario_maestro->getSitioDescarga() );
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
			$bar =  new InventarioMaestro($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_producto"],$foo["id_compra_proveedor"] );
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
	  * @param InventarioMaestro [$inventario_maestro] El objeto de tipo InventarioMaestro a actualizar.
	  **/
	private static final function update( $inventario_maestro )
	{
		$sql = "UPDATE inventario_maestro SET  existencias = ?, existencias_procesadas = ?, sitio_descarga = ? WHERE  id_producto = ? AND id_compra_proveedor = ?;";
		$params = array( 
			$inventario_maestro->getExistencias(), 
			$inventario_maestro->getExistenciasProcesadas(), 
			$inventario_maestro->getSitioDescarga(), 
			$inventario_maestro->getIdProducto(),$inventario_maestro->getIdCompraProveedor(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto InventarioMaestro suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto InventarioMaestro dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param InventarioMaestro [$inventario_maestro] El objeto de tipo InventarioMaestro a crear.
	  **/
	private static final function create( &$inventario_maestro )
	{
		$sql = "INSERT INTO inventario_maestro ( id_producto, id_compra_proveedor, existencias, existencias_procesadas, sitio_descarga ) VALUES ( ?, ?, ?, ?, ?);";
		$params = array( 
			$inventario_maestro->getIdProducto(), 
			$inventario_maestro->getIdCompraProveedor(), 
			$inventario_maestro->getExistencias(), 
			$inventario_maestro->getExistenciasProcesadas(), 
			$inventario_maestro->getSitioDescarga(), 
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
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link InventarioMaestro} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link InventarioMaestro}.
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
	  * @param InventarioMaestro [$inventario_maestro] El objeto de tipo InventarioMaestro
	  * @param InventarioMaestro [$inventario_maestro] El objeto de tipo InventarioMaestro
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $inventario_maestroA , $inventario_maestroB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from inventario_maestro WHERE ("; 
		$val = array();
		if( (($a = $inventario_maestroA->getIdProducto()) != NULL) & ( ($b = $inventario_maestroB->getIdProducto()) != NULL) ){
				$sql .= " id_producto >= ? AND id_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $inventario_maestroA->getIdCompraProveedor()) != NULL) & ( ($b = $inventario_maestroB->getIdCompraProveedor()) != NULL) ){
				$sql .= " id_compra_proveedor >= ? AND id_compra_proveedor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_compra_proveedor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $inventario_maestroA->getExistencias()) != NULL) & ( ($b = $inventario_maestroB->getExistencias()) != NULL) ){
				$sql .= " existencias >= ? AND existencias <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " existencias = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $inventario_maestroA->getExistenciasProcesadas()) != NULL) & ( ($b = $inventario_maestroB->getExistenciasProcesadas()) != NULL) ){
				$sql .= " existencias_procesadas >= ? AND existencias_procesadas <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " existencias_procesadas = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $inventario_maestroA->getSitioDescarga()) != NULL) & ( ($b = $inventario_maestroB->getSitioDescarga()) != NULL) ){
				$sql .= " sitio_descarga >= ? AND sitio_descarga <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " sitio_descarga = ? AND"; 
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
    		array_push( $ar, new InventarioMaestro($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto InventarioMaestro suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param InventarioMaestro [$inventario_maestro] El objeto de tipo InventarioMaestro a eliminar
	  **/
	public static final function delete( &$inventario_maestro )
	{
		if(self::getByPK($inventario_maestro->getIdProducto(), $inventario_maestro->getIdCompraProveedor()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM inventario_maestro WHERE  id_producto = ? AND id_compra_proveedor = ?;";
		$params = array( $inventario_maestro->getIdProducto(), $inventario_maestro->getIdCompraProveedor() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
