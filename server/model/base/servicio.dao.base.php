<?php
/** Servicio Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Servicio }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class ServicioDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_servicio ){
			$pk = "";
			$pk .= $id_servicio . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_servicio){
			$pk = "";
			$pk .= $id_servicio . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_servicio ){
			$pk = "";
			$pk .= $id_servicio . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Servicio} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Servicio [$servicio] El objeto de tipo Servicio
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$servicio )
	{
		if(  self::getByPK(  $servicio->getIdServicio() ) !== NULL )
		{
			try{ return ServicioDAOBase::update( $servicio) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return ServicioDAOBase::create( $servicio) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Servicio} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Servicio} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Servicio Un objeto del tipo {@link Servicio}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_servicio )
	{
		if(self::recordExists(  $id_servicio)){
			return self::getRecord( $id_servicio );
		}
		$sql = "SELECT * FROM servicio WHERE (id_servicio = ? ) LIMIT 1;";
		$params = array(  $id_servicio );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Servicio( $rs );
			self::pushRecord( $foo,  $id_servicio );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Servicio}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Servicio}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from servicio";
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
			$bar = new Servicio($foo);
    		array_push( $allData, $bar);
			//id_servicio
    		self::pushRecord( $bar, $foo["id_servicio"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Servicio} de la base de datos. 
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
	  * @param Servicio [$servicio] El objeto de tipo Servicio
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $servicio , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from servicio WHERE ("; 
		$val = array();
		if( $servicio->getIdServicio() != NULL){
			$sql .= " id_servicio = ? AND";
			array_push( $val, $servicio->getIdServicio() );
		}

		if( $servicio->getNombreServicio() != NULL){
			$sql .= " nombre_servicio = ? AND";
			array_push( $val, $servicio->getNombreServicio() );
		}

		if( $servicio->getMetodoCosteo() != NULL){
			$sql .= " metodo_costeo = ? AND";
			array_push( $val, $servicio->getMetodoCosteo() );
		}

		if( $servicio->getCodigoServicio() != NULL){
			$sql .= " codigo_servicio = ? AND";
			array_push( $val, $servicio->getCodigoServicio() );
		}

		if( $servicio->getCompraEnMostrador() != NULL){
			$sql .= " compra_en_mostrador = ? AND";
			array_push( $val, $servicio->getCompraEnMostrador() );
		}

		if( $servicio->getActivo() != NULL){
			$sql .= " activo = ? AND";
			array_push( $val, $servicio->getActivo() );
		}

		if( $servicio->getMargenDeUtilidad() != NULL){
			$sql .= " margen_de_utilidad = ? AND";
			array_push( $val, $servicio->getMargenDeUtilidad() );
		}

		if( $servicio->getDescripcionServicio() != NULL){
			$sql .= " descripcion_servicio = ? AND";
			array_push( $val, $servicio->getDescripcionServicio() );
		}

		if( $servicio->getCostoEstandar() != NULL){
			$sql .= " costo_estandar = ? AND";
			array_push( $val, $servicio->getCostoEstandar() );
		}

		if( $servicio->getGarantia() != NULL){
			$sql .= " garantia = ? AND";
			array_push( $val, $servicio->getGarantia() );
		}

		if( $servicio->getControlExistencia() != NULL){
			$sql .= " control_existencia = ? AND";
			array_push( $val, $servicio->getControlExistencia() );
		}

		if( $servicio->getFotoServicio() != NULL){
			$sql .= " foto_servicio = ? AND";
			array_push( $val, $servicio->getFotoServicio() );
		}

		if( $servicio->getPrecio() != NULL){
			$sql .= " precio = ? AND";
			array_push( $val, $servicio->getPrecio() );
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
			$bar =  new Servicio($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_servicio"] );
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
	  * @param Servicio [$servicio] El objeto de tipo Servicio a actualizar.
	  **/
	private static final function update( $servicio )
	{
		$sql = "UPDATE servicio SET  nombre_servicio = ?, metodo_costeo = ?, codigo_servicio = ?, compra_en_mostrador = ?, activo = ?, margen_de_utilidad = ?, descripcion_servicio = ?, costo_estandar = ?, garantia = ?, control_existencia = ?, foto_servicio = ?, precio = ? WHERE  id_servicio = ?;";
		$params = array( 
			$servicio->getNombreServicio(), 
			$servicio->getMetodoCosteo(), 
			$servicio->getCodigoServicio(), 
			$servicio->getCompraEnMostrador(), 
			$servicio->getActivo(), 
			$servicio->getMargenDeUtilidad(), 
			$servicio->getDescripcionServicio(), 
			$servicio->getCostoEstandar(), 
			$servicio->getGarantia(), 
			$servicio->getControlExistencia(), 
			$servicio->getFotoServicio(), 
			$servicio->getPrecio(), 
			$servicio->getIdServicio(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Servicio suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Servicio dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Servicio [$servicio] El objeto de tipo Servicio a crear.
	  **/
	private static final function create( &$servicio )
	{
		$sql = "INSERT INTO servicio ( id_servicio, nombre_servicio, metodo_costeo, codigo_servicio, compra_en_mostrador, activo, margen_de_utilidad, descripcion_servicio, costo_estandar, garantia, control_existencia, foto_servicio, precio ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$servicio->getIdServicio(), 
			$servicio->getNombreServicio(), 
			$servicio->getMetodoCosteo(), 
			$servicio->getCodigoServicio(), 
			$servicio->getCompraEnMostrador(), 
			$servicio->getActivo(), 
			$servicio->getMargenDeUtilidad(), 
			$servicio->getDescripcionServicio(), 
			$servicio->getCostoEstandar(), 
			$servicio->getGarantia(), 
			$servicio->getControlExistencia(), 
			$servicio->getFotoServicio(), 
			$servicio->getPrecio(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $servicio->setIdServicio( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Servicio} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Servicio}.
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
	  * @param Servicio [$servicio] El objeto de tipo Servicio
	  * @param Servicio [$servicio] El objeto de tipo Servicio
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $servicioA , $servicioB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from servicio WHERE ("; 
		$val = array();
		if( (($a = $servicioA->getIdServicio()) !== NULL) & ( ($b = $servicioB->getIdServicio()) !== NULL) ){
				$sql .= " id_servicio >= ? AND id_servicio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_servicio = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getNombreServicio()) !== NULL) & ( ($b = $servicioB->getNombreServicio()) !== NULL) ){
				$sql .= " nombre_servicio >= ? AND nombre_servicio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " nombre_servicio = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getMetodoCosteo()) !== NULL) & ( ($b = $servicioB->getMetodoCosteo()) !== NULL) ){
				$sql .= " metodo_costeo >= ? AND metodo_costeo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " metodo_costeo = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getCodigoServicio()) !== NULL) & ( ($b = $servicioB->getCodigoServicio()) !== NULL) ){
				$sql .= " codigo_servicio >= ? AND codigo_servicio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " codigo_servicio = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getCompraEnMostrador()) !== NULL) & ( ($b = $servicioB->getCompraEnMostrador()) !== NULL) ){
				$sql .= " compra_en_mostrador >= ? AND compra_en_mostrador <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " compra_en_mostrador = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getActivo()) !== NULL) & ( ($b = $servicioB->getActivo()) !== NULL) ){
				$sql .= " activo >= ? AND activo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " activo = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getMargenDeUtilidad()) !== NULL) & ( ($b = $servicioB->getMargenDeUtilidad()) !== NULL) ){
				$sql .= " margen_de_utilidad >= ? AND margen_de_utilidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " margen_de_utilidad = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getDescripcionServicio()) !== NULL) & ( ($b = $servicioB->getDescripcionServicio()) !== NULL) ){
				$sql .= " descripcion_servicio >= ? AND descripcion_servicio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " descripcion_servicio = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getCostoEstandar()) !== NULL) & ( ($b = $servicioB->getCostoEstandar()) !== NULL) ){
				$sql .= " costo_estandar >= ? AND costo_estandar <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " costo_estandar = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getGarantia()) !== NULL) & ( ($b = $servicioB->getGarantia()) !== NULL) ){
				$sql .= " garantia >= ? AND garantia <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " garantia = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getControlExistencia()) !== NULL) & ( ($b = $servicioB->getControlExistencia()) !== NULL) ){
				$sql .= " control_existencia >= ? AND control_existencia <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " control_existencia = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getFotoServicio()) !== NULL) & ( ($b = $servicioB->getFotoServicio()) !== NULL) ){
				$sql .= " foto_servicio >= ? AND foto_servicio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " foto_servicio = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $servicioA->getPrecio()) !== NULL) & ( ($b = $servicioB->getPrecio()) !== NULL) ){
				$sql .= " precio >= ? AND precio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " precio = ? AND"; 
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
    		array_push( $ar, new Servicio($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Servicio suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Servicio [$servicio] El objeto de tipo Servicio a eliminar
	  **/
	public static final function delete( &$servicio )
	{
		if(self::getByPK($servicio->getIdServicio()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM servicio WHERE  id_servicio = ?;";
		$params = array( $servicio->getIdServicio() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
