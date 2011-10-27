<?php
/** PrecioPaqueteRol Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link PrecioPaqueteRol }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class PrecioPaqueteRolDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_paquete, $id_rol ){
			$pk = "";
			$pk .= $id_paquete . "-";
			$pk .= $id_rol . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_paquete, $id_rol){
			$pk = "";
			$pk .= $id_paquete . "-";
			$pk .= $id_rol . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_paquete, $id_rol ){
			$pk = "";
			$pk .= $id_paquete . "-";
			$pk .= $id_rol . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link PrecioPaqueteRol} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param PrecioPaqueteRol [$precio_paquete_rol] El objeto de tipo PrecioPaqueteRol
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$precio_paquete_rol )
	{
		if( ! is_null ( self::getByPK(  $precio_paquete_rol->getIdPaquete() , $precio_paquete_rol->getIdRol() ) ) )
		{
			try{ return PrecioPaqueteRolDAOBase::update( $precio_paquete_rol) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return PrecioPaqueteRolDAOBase::create( $precio_paquete_rol) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link PrecioPaqueteRol} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link PrecioPaqueteRol} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link PrecioPaqueteRol Un objeto del tipo {@link PrecioPaqueteRol}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_paquete, $id_rol )
	{
		if(self::recordExists(  $id_paquete, $id_rol)){
			return self::getRecord( $id_paquete, $id_rol );
		}
		$sql = "SELECT * FROM precio_paquete_rol WHERE (id_paquete = ? AND id_rol = ? ) LIMIT 1;";
		$params = array(  $id_paquete, $id_rol );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new PrecioPaqueteRol( $rs );
			self::pushRecord( $foo,  $id_paquete, $id_rol );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link PrecioPaqueteRol}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link PrecioPaqueteRol}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from precio_paquete_rol";
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
			$bar = new PrecioPaqueteRol($foo);
    		array_push( $allData, $bar);
			//id_paquete
			//id_rol
    		self::pushRecord( $bar, $foo["id_paquete"],$foo["id_rol"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PrecioPaqueteRol} de la base de datos. 
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
	  * @param PrecioPaqueteRol [$precio_paquete_rol] El objeto de tipo PrecioPaqueteRol
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $precio_paquete_rol , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from precio_paquete_rol WHERE ("; 
		$val = array();
		if( ! is_null( $precio_paquete_rol->getIdPaquete() ) ){
			$sql .= " id_paquete = ? AND";
			array_push( $val, $precio_paquete_rol->getIdPaquete() );
		}

		if( ! is_null( $precio_paquete_rol->getIdRol() ) ){
			$sql .= " id_rol = ? AND";
			array_push( $val, $precio_paquete_rol->getIdRol() );
		}

		if( ! is_null( $precio_paquete_rol->getPrecioUtilidad() ) ){
			$sql .= " precio_utilidad = ? AND";
			array_push( $val, $precio_paquete_rol->getPrecioUtilidad() );
		}

		if( ! is_null( $precio_paquete_rol->getEsMargenUtilidad() ) ){
			$sql .= " es_margen_utilidad = ? AND";
			array_push( $val, $precio_paquete_rol->getEsMargenUtilidad() );
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
			$bar =  new PrecioPaqueteRol($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_paquete"],$foo["id_rol"] );
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
	  * @param PrecioPaqueteRol [$precio_paquete_rol] El objeto de tipo PrecioPaqueteRol a actualizar.
	  **/
	private static final function update( $precio_paquete_rol )
	{
		$sql = "UPDATE precio_paquete_rol SET  precio_utilidad = ?, es_margen_utilidad = ? WHERE  id_paquete = ? AND id_rol = ?;";
		$params = array( 
			$precio_paquete_rol->getPrecioUtilidad(), 
			$precio_paquete_rol->getEsMargenUtilidad(), 
			$precio_paquete_rol->getIdPaquete(),$precio_paquete_rol->getIdRol(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto PrecioPaqueteRol suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto PrecioPaqueteRol dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param PrecioPaqueteRol [$precio_paquete_rol] El objeto de tipo PrecioPaqueteRol a crear.
	  **/
	private static final function create( &$precio_paquete_rol )
	{
		$sql = "INSERT INTO precio_paquete_rol ( id_paquete, id_rol, precio_utilidad, es_margen_utilidad ) VALUES ( ?, ?, ?, ?);";
		$params = array( 
			$precio_paquete_rol->getIdPaquete(), 
			$precio_paquete_rol->getIdRol(), 
			$precio_paquete_rol->getPrecioUtilidad(), 
			$precio_paquete_rol->getEsMargenUtilidad(), 
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
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PrecioPaqueteRol} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link PrecioPaqueteRol}.
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
	  * @param PrecioPaqueteRol [$precio_paquete_rol] El objeto de tipo PrecioPaqueteRol
	  * @param PrecioPaqueteRol [$precio_paquete_rol] El objeto de tipo PrecioPaqueteRol
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $precio_paquete_rolA , $precio_paquete_rolB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from precio_paquete_rol WHERE ("; 
		$val = array();
		if( ( !is_null (($a = $precio_paquete_rolA->getIdPaquete()) ) ) & ( ! is_null ( ($b = $precio_paquete_rolB->getIdPaquete()) ) ) ){
				$sql .= " id_paquete >= ? AND id_paquete <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " id_paquete = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $precio_paquete_rolA->getIdRol()) ) ) & ( ! is_null ( ($b = $precio_paquete_rolB->getIdRol()) ) ) ){
				$sql .= " id_rol >= ? AND id_rol <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " id_rol = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $precio_paquete_rolA->getPrecioUtilidad()) ) ) & ( ! is_null ( ($b = $precio_paquete_rolB->getPrecioUtilidad()) ) ) ){
				$sql .= " precio_utilidad >= ? AND precio_utilidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " precio_utilidad = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $precio_paquete_rolA->getEsMargenUtilidad()) ) ) & ( ! is_null ( ($b = $precio_paquete_rolB->getEsMargenUtilidad()) ) ) ){
				$sql .= " es_margen_utilidad >= ? AND es_margen_utilidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " es_margen_utilidad = ? AND"; 
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
    		array_push( $ar, new PrecioPaqueteRol($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto PrecioPaqueteRol suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param PrecioPaqueteRol [$precio_paquete_rol] El objeto de tipo PrecioPaqueteRol a eliminar
	  **/
	public static final function delete( &$precio_paquete_rol )
	{
		if( is_null( self::getByPK($precio_paquete_rol->getIdPaquete(), $precio_paquete_rol->getIdRol()) ) ) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM precio_paquete_rol WHERE  id_paquete = ? AND id_rol = ?;";
		$params = array( $precio_paquete_rol->getIdPaquete(), $precio_paquete_rol->getIdRol() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
