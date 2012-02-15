<?php
/** SalidaAlmacen Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link SalidaAlmacen }. 
  * @author Anonymous
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class SalidaAlmacenDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_salida_almacen ){
			$pk = "";
			$pk .= $id_salida_almacen . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_salida_almacen){
			$pk = "";
			$pk .= $id_salida_almacen . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_salida_almacen ){
			$pk = "";
			$pk .= $id_salida_almacen . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link SalidaAlmacen} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param SalidaAlmacen [$salida_almacen] El objeto de tipo SalidaAlmacen
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$salida_almacen )
	{
		if( ! is_null ( self::getByPK(  $salida_almacen->getIdSalidaAlmacen() ) ) )
		{
			try{ return SalidaAlmacenDAOBase::update( $salida_almacen) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return SalidaAlmacenDAOBase::create( $salida_almacen) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link SalidaAlmacen} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link SalidaAlmacen} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link SalidaAlmacen Un objeto del tipo {@link SalidaAlmacen}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_salida_almacen )
	{
		if(self::recordExists(  $id_salida_almacen)){
			return self::getRecord( $id_salida_almacen );
		}
		$sql = "SELECT * FROM salida_almacen WHERE (id_salida_almacen = ? ) LIMIT 1;";
		$params = array(  $id_salida_almacen );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new SalidaAlmacen( $rs );
			self::pushRecord( $foo,  $id_salida_almacen );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link SalidaAlmacen}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link SalidaAlmacen}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from salida_almacen";
		if( ! is_null ( $orden ) )
		{ $sql .= " ORDER BY " . $orden . " " . $tipo_de_orden;	}
		if( ! is_null ( $pagina ) )
		{
			$sql .= " LIMIT " . (( $pagina - 1 )*$columnas_por_pagina) . "," . $columnas_por_pagina; 
		}
		global $conn;
		$rs = $conn->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
			$bar = new SalidaAlmacen($foo);
    		array_push( $allData, $bar);
			//id_salida_almacen
    		self::pushRecord( $bar, $foo["id_salida_almacen"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link SalidaAlmacen} de la base de datos. 
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
	  * @param SalidaAlmacen [$salida_almacen] El objeto de tipo SalidaAlmacen
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $salida_almacen , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from salida_almacen WHERE ("; 
		$val = array();
		if( ! is_null( $salida_almacen->getIdSalidaAlmacen() ) ){
			$sql .= " id_salida_almacen = ? AND";
			array_push( $val, $salida_almacen->getIdSalidaAlmacen() );
		}

		if( ! is_null( $salida_almacen->getIdAlmacen() ) ){
			$sql .= " id_almacen = ? AND";
			array_push( $val, $salida_almacen->getIdAlmacen() );
		}

		if( ! is_null( $salida_almacen->getIdUsuario() ) ){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $salida_almacen->getIdUsuario() );
		}

		if( ! is_null( $salida_almacen->getFechaRegistro() ) ){
			$sql .= " fecha_registro = ? AND";
			array_push( $val, $salida_almacen->getFechaRegistro() );
		}

		if( ! is_null( $salida_almacen->getMotivo() ) ){
			$sql .= " motivo = ? AND";
			array_push( $val, $salida_almacen->getMotivo() );
		}

		if(sizeof($val) == 0){return array();}
		$sql = substr($sql, 0, -3) . " )";
		if( ! is_null ( $orderBy ) ){
		    $sql .= " order by " . $orderBy . " " . $orden ;
		
		}
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array();
		foreach ($rs as $foo) {
			$bar =  new SalidaAlmacen($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_salida_almacen"] );
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
	  * @param SalidaAlmacen [$salida_almacen] El objeto de tipo SalidaAlmacen a actualizar.
	  **/
	private static final function update( $salida_almacen )
	{
		$sql = "UPDATE salida_almacen SET  id_almacen = ?, id_usuario = ?, fecha_registro = ?, motivo = ? WHERE  id_salida_almacen = ?;";
		$params = array( 
			$salida_almacen->getIdAlmacen(), 
			$salida_almacen->getIdUsuario(), 
			$salida_almacen->getFechaRegistro(), 
			$salida_almacen->getMotivo(), 
			$salida_almacen->getIdSalidaAlmacen(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto SalidaAlmacen suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto SalidaAlmacen dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param SalidaAlmacen [$salida_almacen] El objeto de tipo SalidaAlmacen a crear.
	  **/
	private static final function create( &$salida_almacen )
	{
		$sql = "INSERT INTO salida_almacen ( id_salida_almacen, id_almacen, id_usuario, fecha_registro, motivo ) VALUES ( ?, ?, ?, ?, ?);";
		$params = array( 
			$salida_almacen->getIdSalidaAlmacen(), 
			$salida_almacen->getIdAlmacen(), 
			$salida_almacen->getIdUsuario(), 
			$salida_almacen->getFechaRegistro(), 
			$salida_almacen->getMotivo(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $salida_almacen->setIdSalidaAlmacen( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link SalidaAlmacen} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link SalidaAlmacen}.
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
	  * @param SalidaAlmacen [$salida_almacen] El objeto de tipo SalidaAlmacen
	  * @param SalidaAlmacen [$salida_almacen] El objeto de tipo SalidaAlmacen
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $salida_almacenA , $salida_almacenB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from salida_almacen WHERE ("; 
		$val = array();
		if( ( !is_null (($a = $salida_almacenA->getIdSalidaAlmacen()) ) ) & ( ! is_null ( ($b = $salida_almacenB->getIdSalidaAlmacen()) ) ) ){
				$sql .= " id_salida_almacen >= ? AND id_salida_almacen <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " id_salida_almacen = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $salida_almacenA->getIdAlmacen()) ) ) & ( ! is_null ( ($b = $salida_almacenB->getIdAlmacen()) ) ) ){
				$sql .= " id_almacen >= ? AND id_almacen <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " id_almacen = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $salida_almacenA->getIdUsuario()) ) ) & ( ! is_null ( ($b = $salida_almacenB->getIdUsuario()) ) ) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " id_usuario = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $salida_almacenA->getFechaRegistro()) ) ) & ( ! is_null ( ($b = $salida_almacenB->getFechaRegistro()) ) ) ){
				$sql .= " fecha_registro >= ? AND fecha_registro <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " fecha_registro = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $salida_almacenA->getMotivo()) ) ) & ( ! is_null ( ($b = $salida_almacenB->getMotivo()) ) ) ){
				$sql .= " motivo >= ? AND motivo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " motivo = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		$sql = substr($sql, 0, -3) . " )";
		if( !is_null ( $orderBy ) ){
		    $sql .= " order by " . $orderBy . " " . $orden ;
		
		}
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array();
		foreach ($rs as $foo) {
    		array_push( $ar, new SalidaAlmacen($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto SalidaAlmacen suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param SalidaAlmacen [$salida_almacen] El objeto de tipo SalidaAlmacen a eliminar
	  **/
	public static final function delete( &$salida_almacen )
	{
		if( is_null( self::getByPK($salida_almacen->getIdSalidaAlmacen()) ) ) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM salida_almacen WHERE  id_salida_almacen = ?;";
		$params = array( $salida_almacen->getIdSalidaAlmacen() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
