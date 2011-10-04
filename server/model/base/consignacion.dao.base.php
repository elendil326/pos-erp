<?php
/** Consignacion Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Consignacion }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class ConsignacionDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_consignacion ){
			$pk = "";
			$pk .= $id_consignacion . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_consignacion){
			$pk = "";
			$pk .= $id_consignacion . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_consignacion ){
			$pk = "";
			$pk .= $id_consignacion . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Consignacion} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Consignacion [$consignacion] El objeto de tipo Consignacion
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$consignacion )
	{
		if(  self::getByPK(  $consignacion->getIdConsignacion() ) !== NULL )
		{
			try{ return ConsignacionDAOBase::update( $consignacion) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return ConsignacionDAOBase::create( $consignacion) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Consignacion} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Consignacion} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Consignacion Un objeto del tipo {@link Consignacion}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_consignacion )
	{
		if(self::recordExists(  $id_consignacion)){
			return self::getRecord( $id_consignacion );
		}
		$sql = "SELECT * FROM consignacion WHERE (id_consignacion = ? ) LIMIT 1;";
		$params = array(  $id_consignacion );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Consignacion( $rs );
			self::pushRecord( $foo,  $id_consignacion );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Consignacion}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Consignacion}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from consignacion";
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
			$bar = new Consignacion($foo);
    		array_push( $allData, $bar);
			//id_consignacion
    		self::pushRecord( $bar, $foo["id_consignacion"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Consignacion} de la base de datos. 
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
	  * @param Consignacion [$consignacion] El objeto de tipo Consignacion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $consignacion , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from consignacion WHERE ("; 
		$val = array();
		if( $consignacion->getIdConsignacion() != NULL){
			$sql .= " id_consignacion = ? AND";
			array_push( $val, $consignacion->getIdConsignacion() );
		}

		if( $consignacion->getIdCliente() != NULL){
			$sql .= " id_cliente = ? AND";
			array_push( $val, $consignacion->getIdCliente() );
		}

		if( $consignacion->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $consignacion->getIdUsuario() );
		}

		if( $consignacion->getIdAlmacen() != NULL){
			$sql .= " id_almacen = ? AND";
			array_push( $val, $consignacion->getIdAlmacen() );
		}

		if( $consignacion->getIdUsuarioCancelacion() != NULL){
			$sql .= " id_usuario_cancelacion = ? AND";
			array_push( $val, $consignacion->getIdUsuarioCancelacion() );
		}

		if( $consignacion->getFechaCreacion() != NULL){
			$sql .= " fecha_creacion = ? AND";
			array_push( $val, $consignacion->getFechaCreacion() );
		}

		if( $consignacion->getActiva() != NULL){
			$sql .= " activa = ? AND";
			array_push( $val, $consignacion->getActiva() );
		}

		if( $consignacion->getCancelada() != NULL){
			$sql .= " cancelada = ? AND";
			array_push( $val, $consignacion->getCancelada() );
		}

		if( $consignacion->getMotivoCancelacion() != NULL){
			$sql .= " motivo_cancelacion = ? AND";
			array_push( $val, $consignacion->getMotivoCancelacion() );
		}

		if( $consignacion->getFolio() != NULL){
			$sql .= " folio = ? AND";
			array_push( $val, $consignacion->getFolio() );
		}

		if( $consignacion->getFechaTermino() != NULL){
			$sql .= " fecha_termino = ? AND";
			array_push( $val, $consignacion->getFechaTermino() );
		}

		if( $consignacion->getImpuesto() != NULL){
			$sql .= " impuesto = ? AND";
			array_push( $val, $consignacion->getImpuesto() );
		}

		if( $consignacion->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $consignacion->getDescuento() );
		}

		if( $consignacion->getRetencion() != NULL){
			$sql .= " retencion = ? AND";
			array_push( $val, $consignacion->getRetencion() );
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
			$bar =  new Consignacion($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_consignacion"] );
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
	  * @param Consignacion [$consignacion] El objeto de tipo Consignacion a actualizar.
	  **/
	private static final function update( $consignacion )
	{
		$sql = "UPDATE consignacion SET  id_cliente = ?, id_usuario = ?, id_almacen = ?, id_usuario_cancelacion = ?, fecha_creacion = ?, activa = ?, cancelada = ?, motivo_cancelacion = ?, folio = ?, fecha_termino = ?, impuesto = ?, descuento = ?, retencion = ? WHERE  id_consignacion = ?;";
		$params = array( 
			$consignacion->getIdCliente(), 
			$consignacion->getIdUsuario(), 
			$consignacion->getIdAlmacen(), 
			$consignacion->getIdUsuarioCancelacion(), 
			$consignacion->getFechaCreacion(), 
			$consignacion->getActiva(), 
			$consignacion->getCancelada(), 
			$consignacion->getMotivoCancelacion(), 
			$consignacion->getFolio(), 
			$consignacion->getFechaTermino(), 
			$consignacion->getImpuesto(), 
			$consignacion->getDescuento(), 
			$consignacion->getRetencion(), 
			$consignacion->getIdConsignacion(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Consignacion suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Consignacion dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Consignacion [$consignacion] El objeto de tipo Consignacion a crear.
	  **/
	private static final function create( &$consignacion )
	{
		$sql = "INSERT INTO consignacion ( id_consignacion, id_cliente, id_usuario, id_almacen, id_usuario_cancelacion, fecha_creacion, activa, cancelada, motivo_cancelacion, folio, fecha_termino, impuesto, descuento, retencion ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$consignacion->getIdConsignacion(), 
			$consignacion->getIdCliente(), 
			$consignacion->getIdUsuario(), 
			$consignacion->getIdAlmacen(), 
			$consignacion->getIdUsuarioCancelacion(), 
			$consignacion->getFechaCreacion(), 
			$consignacion->getActiva(), 
			$consignacion->getCancelada(), 
			$consignacion->getMotivoCancelacion(), 
			$consignacion->getFolio(), 
			$consignacion->getFechaTermino(), 
			$consignacion->getImpuesto(), 
			$consignacion->getDescuento(), 
			$consignacion->getRetencion(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $consignacion->setIdConsignacion( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Consignacion} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Consignacion}.
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
	  * @param Consignacion [$consignacion] El objeto de tipo Consignacion
	  * @param Consignacion [$consignacion] El objeto de tipo Consignacion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $consignacionA , $consignacionB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from consignacion WHERE ("; 
		$val = array();
		if( (($a = $consignacionA->getIdConsignacion()) != NULL) & ( ($b = $consignacionB->getIdConsignacion()) != NULL) ){
				$sql .= " id_consignacion >= ? AND id_consignacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_consignacion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getIdCliente()) != NULL) & ( ($b = $consignacionB->getIdCliente()) != NULL) ){
				$sql .= " id_cliente >= ? AND id_cliente <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_cliente = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getIdUsuario()) != NULL) & ( ($b = $consignacionB->getIdUsuario()) != NULL) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getIdAlmacen()) != NULL) & ( ($b = $consignacionB->getIdAlmacen()) != NULL) ){
				$sql .= " id_almacen >= ? AND id_almacen <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_almacen = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getIdUsuarioCancelacion()) != NULL) & ( ($b = $consignacionB->getIdUsuarioCancelacion()) != NULL) ){
				$sql .= " id_usuario_cancelacion >= ? AND id_usuario_cancelacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario_cancelacion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getFechaCreacion()) != NULL) & ( ($b = $consignacionB->getFechaCreacion()) != NULL) ){
				$sql .= " fecha_creacion >= ? AND fecha_creacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha_creacion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getActiva()) != NULL) & ( ($b = $consignacionB->getActiva()) != NULL) ){
				$sql .= " activa >= ? AND activa <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " activa = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getCancelada()) != NULL) & ( ($b = $consignacionB->getCancelada()) != NULL) ){
				$sql .= " cancelada >= ? AND cancelada <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " cancelada = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getMotivoCancelacion()) != NULL) & ( ($b = $consignacionB->getMotivoCancelacion()) != NULL) ){
				$sql .= " motivo_cancelacion >= ? AND motivo_cancelacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " motivo_cancelacion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getFolio()) != NULL) & ( ($b = $consignacionB->getFolio()) != NULL) ){
				$sql .= " folio >= ? AND folio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " folio = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getFechaTermino()) != NULL) & ( ($b = $consignacionB->getFechaTermino()) != NULL) ){
				$sql .= " fecha_termino >= ? AND fecha_termino <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha_termino = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getImpuesto()) != NULL) & ( ($b = $consignacionB->getImpuesto()) != NULL) ){
				$sql .= " impuesto >= ? AND impuesto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " impuesto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getDescuento()) != NULL) & ( ($b = $consignacionB->getDescuento()) != NULL) ){
				$sql .= " descuento >= ? AND descuento <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descuento = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $consignacionA->getRetencion()) != NULL) & ( ($b = $consignacionB->getRetencion()) != NULL) ){
				$sql .= " retencion >= ? AND retencion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " retencion = ? AND"; 
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
    		array_push( $ar, new Consignacion($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Consignacion suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Consignacion [$consignacion] El objeto de tipo Consignacion a eliminar
	  **/
	public static final function delete( &$consignacion )
	{
		if(self::getByPK($consignacion->getIdConsignacion()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM consignacion WHERE  id_consignacion = ?;";
		$params = array( $consignacion->getIdConsignacion() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
