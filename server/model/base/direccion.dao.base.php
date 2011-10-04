<?php
/** Direccion Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Direccion }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class DireccionDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_direccion ){
			$pk = "";
			$pk .= $id_direccion . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_direccion){
			$pk = "";
			$pk .= $id_direccion . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_direccion ){
			$pk = "";
			$pk .= $id_direccion . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Direccion} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Direccion [$direccion] El objeto de tipo Direccion
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$direccion )
	{
		if(  self::getByPK(  $direccion->getIdDireccion() ) !== NULL )
		{
			try{ return DireccionDAOBase::update( $direccion) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return DireccionDAOBase::create( $direccion) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Direccion} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Direccion} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Direccion Un objeto del tipo {@link Direccion}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_direccion )
	{
		if(self::recordExists(  $id_direccion)){
			return self::getRecord( $id_direccion );
		}
		$sql = "SELECT * FROM direccion WHERE (id_direccion = ? ) LIMIT 1;";
		$params = array(  $id_direccion );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Direccion( $rs );
			self::pushRecord( $foo,  $id_direccion );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Direccion}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Direccion}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from direccion";
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
			$bar = new Direccion($foo);
    		array_push( $allData, $bar);
			//id_direccion
    		self::pushRecord( $bar, $foo["id_direccion"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Direccion} de la base de datos. 
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
	  * @param Direccion [$direccion] El objeto de tipo Direccion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $direccion , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from direccion WHERE ("; 
		$val = array();
		if( $direccion->getIdDireccion() != NULL){
			$sql .= " id_direccion = ? AND";
			array_push( $val, $direccion->getIdDireccion() );
		}

		if( $direccion->getCalle() != NULL){
			$sql .= " calle = ? AND";
			array_push( $val, $direccion->getCalle() );
		}

		if( $direccion->getNumeroExterior() != NULL){
			$sql .= " numero_exterior = ? AND";
			array_push( $val, $direccion->getNumeroExterior() );
		}

		if( $direccion->getNumeroInterior() != NULL){
			$sql .= " numero_interior = ? AND";
			array_push( $val, $direccion->getNumeroInterior() );
		}

		if( $direccion->getReferencia() != NULL){
			$sql .= " referencia = ? AND";
			array_push( $val, $direccion->getReferencia() );
		}

		if( $direccion->getColonia() != NULL){
			$sql .= " colonia = ? AND";
			array_push( $val, $direccion->getColonia() );
		}

		if( $direccion->getIdCiudad() != NULL){
			$sql .= " id_ciudad = ? AND";
			array_push( $val, $direccion->getIdCiudad() );
		}

		if( $direccion->getCodigoPostal() != NULL){
			$sql .= " codigo_postal = ? AND";
			array_push( $val, $direccion->getCodigoPostal() );
		}

		if( $direccion->getTelefono() != NULL){
			$sql .= " telefono = ? AND";
			array_push( $val, $direccion->getTelefono() );
		}

		if( $direccion->getTelefono2() != NULL){
			$sql .= " telefono2 = ? AND";
			array_push( $val, $direccion->getTelefono2() );
		}

		if( $direccion->getUltimaModificacion() != NULL){
			$sql .= " ultima_modificacion = ? AND";
			array_push( $val, $direccion->getUltimaModificacion() );
		}

		if( $direccion->getIdUsuarioUltimaModificacion() != NULL){
			$sql .= " id_usuario_ultima_modificacion = ? AND";
			array_push( $val, $direccion->getIdUsuarioUltimaModificacion() );
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
			$bar =  new Direccion($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_direccion"] );
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
	  * @param Direccion [$direccion] El objeto de tipo Direccion a actualizar.
	  **/
	private static final function update( $direccion )
	{
		$sql = "UPDATE direccion SET  calle = ?, numero_exterior = ?, numero_interior = ?, referencia = ?, colonia = ?, id_ciudad = ?, codigo_postal = ?, telefono = ?, telefono2 = ?, ultima_modificacion = ?, id_usuario_ultima_modificacion = ? WHERE  id_direccion = ?;";
		$params = array( 
			$direccion->getCalle(), 
			$direccion->getNumeroExterior(), 
			$direccion->getNumeroInterior(), 
			$direccion->getReferencia(), 
			$direccion->getColonia(), 
			$direccion->getIdCiudad(), 
			$direccion->getCodigoPostal(), 
			$direccion->getTelefono(), 
			$direccion->getTelefono2(), 
			$direccion->getUltimaModificacion(), 
			$direccion->getIdUsuarioUltimaModificacion(), 
			$direccion->getIdDireccion(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Direccion suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Direccion dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Direccion [$direccion] El objeto de tipo Direccion a crear.
	  **/
	private static final function create( &$direccion )
	{
		$sql = "INSERT INTO direccion ( id_direccion, calle, numero_exterior, numero_interior, referencia, colonia, id_ciudad, codigo_postal, telefono, telefono2, ultima_modificacion, id_usuario_ultima_modificacion ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$direccion->getIdDireccion(), 
			$direccion->getCalle(), 
			$direccion->getNumeroExterior(), 
			$direccion->getNumeroInterior(), 
			$direccion->getReferencia(), 
			$direccion->getColonia(), 
			$direccion->getIdCiudad(), 
			$direccion->getCodigoPostal(), 
			$direccion->getTelefono(), 
			$direccion->getTelefono2(), 
			$direccion->getUltimaModificacion(), 
			$direccion->getIdUsuarioUltimaModificacion(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $direccion->setIdDireccion( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Direccion} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Direccion}.
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
	  * @param Direccion [$direccion] El objeto de tipo Direccion
	  * @param Direccion [$direccion] El objeto de tipo Direccion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $direccionA , $direccionB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from direccion WHERE ("; 
		$val = array();
		if( (($a = $direccionA->getIdDireccion()) != NULL) & ( ($b = $direccionB->getIdDireccion()) != NULL) ){
				$sql .= " id_direccion >= ? AND id_direccion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_direccion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $direccionA->getCalle()) != NULL) & ( ($b = $direccionB->getCalle()) != NULL) ){
				$sql .= " calle >= ? AND calle <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " calle = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $direccionA->getNumeroExterior()) != NULL) & ( ($b = $direccionB->getNumeroExterior()) != NULL) ){
				$sql .= " numero_exterior >= ? AND numero_exterior <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " numero_exterior = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $direccionA->getNumeroInterior()) != NULL) & ( ($b = $direccionB->getNumeroInterior()) != NULL) ){
				$sql .= " numero_interior >= ? AND numero_interior <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " numero_interior = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $direccionA->getReferencia()) != NULL) & ( ($b = $direccionB->getReferencia()) != NULL) ){
				$sql .= " referencia >= ? AND referencia <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " referencia = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $direccionA->getColonia()) != NULL) & ( ($b = $direccionB->getColonia()) != NULL) ){
				$sql .= " colonia >= ? AND colonia <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " colonia = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $direccionA->getIdCiudad()) != NULL) & ( ($b = $direccionB->getIdCiudad()) != NULL) ){
				$sql .= " id_ciudad >= ? AND id_ciudad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_ciudad = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $direccionA->getCodigoPostal()) != NULL) & ( ($b = $direccionB->getCodigoPostal()) != NULL) ){
				$sql .= " codigo_postal >= ? AND codigo_postal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " codigo_postal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $direccionA->getTelefono()) != NULL) & ( ($b = $direccionB->getTelefono()) != NULL) ){
				$sql .= " telefono >= ? AND telefono <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " telefono = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $direccionA->getTelefono2()) != NULL) & ( ($b = $direccionB->getTelefono2()) != NULL) ){
				$sql .= " telefono2 >= ? AND telefono2 <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " telefono2 = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $direccionA->getUltimaModificacion()) != NULL) & ( ($b = $direccionB->getUltimaModificacion()) != NULL) ){
				$sql .= " ultima_modificacion >= ? AND ultima_modificacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " ultima_modificacion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $direccionA->getIdUsuarioUltimaModificacion()) != NULL) & ( ($b = $direccionB->getIdUsuarioUltimaModificacion()) != NULL) ){
				$sql .= " id_usuario_ultima_modificacion >= ? AND id_usuario_ultima_modificacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario_ultima_modificacion = ? AND"; 
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
    		array_push( $ar, new Direccion($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Direccion suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Direccion [$direccion] El objeto de tipo Direccion a eliminar
	  **/
	public static final function delete( &$direccion )
	{
		if(self::getByPK($direccion->getIdDireccion()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM direccion WHERE  id_direccion = ?;";
		$params = array( $direccion->getIdDireccion() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
