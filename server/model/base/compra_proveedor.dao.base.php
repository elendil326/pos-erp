<?php
/** CompraProveedor Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link CompraProveedor }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class CompraProveedorDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_compra_proveedor ){
			$pk = "";
			$pk .= $id_compra_proveedor . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_compra_proveedor){
			$pk = "";
			$pk .= $id_compra_proveedor . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_compra_proveedor ){
			$pk = "";
			$pk .= $id_compra_proveedor . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CompraProveedor} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param CompraProveedor [$compra_proveedor] El objeto de tipo CompraProveedor
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$compra_proveedor )
	{
		if(  self::getByPK(  $compra_proveedor->getIdCompraProveedor() ) !== NULL )
		{
			try{ return CompraProveedorDAOBase::update( $compra_proveedor) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return CompraProveedorDAOBase::create( $compra_proveedor) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link CompraProveedor} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CompraProveedor} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CompraProveedor Un objeto del tipo {@link CompraProveedor}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_compra_proveedor )
	{
		if(self::recordExists(  $id_compra_proveedor)){
			return self::getRecord( $id_compra_proveedor );
		}
		$sql = "SELECT * FROM compra_proveedor WHERE (id_compra_proveedor = ? ) LIMIT 1;";
		$params = array(  $id_compra_proveedor );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new CompraProveedor( $rs );
			self::pushRecord( $foo,  $id_compra_proveedor );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CompraProveedor}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link CompraProveedor}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from compra_proveedor";
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
			$bar = new CompraProveedor($foo);
    		array_push( $allData, $bar);
			//id_compra_proveedor
    		self::pushRecord( $bar, $foo["id_compra_proveedor"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraProveedor} de la base de datos. 
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
	  * @param CompraProveedor [$compra_proveedor] El objeto de tipo CompraProveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $compra_proveedor , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from compra_proveedor WHERE ("; 
		$val = array();
		if( $compra_proveedor->getIdCompraProveedor() != NULL){
			$sql .= " id_compra_proveedor = ? AND";
			array_push( $val, $compra_proveedor->getIdCompraProveedor() );
		}

		if( $compra_proveedor->getPesoOrigen() != NULL){
			$sql .= " peso_origen = ? AND";
			array_push( $val, $compra_proveedor->getPesoOrigen() );
		}

		if( $compra_proveedor->getIdProveedor() != NULL){
			$sql .= " id_proveedor = ? AND";
			array_push( $val, $compra_proveedor->getIdProveedor() );
		}

		if( $compra_proveedor->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $compra_proveedor->getFecha() );
		}

		if( $compra_proveedor->getFechaOrigen() != NULL){
			$sql .= " fecha_origen = ? AND";
			array_push( $val, $compra_proveedor->getFechaOrigen() );
		}

		if( $compra_proveedor->getFolio() != NULL){
			$sql .= " folio = ? AND";
			array_push( $val, $compra_proveedor->getFolio() );
		}

		if( $compra_proveedor->getNumeroDeViaje() != NULL){
			$sql .= " numero_de_viaje = ? AND";
			array_push( $val, $compra_proveedor->getNumeroDeViaje() );
		}

		if( $compra_proveedor->getPesoRecibido() != NULL){
			$sql .= " peso_recibido = ? AND";
			array_push( $val, $compra_proveedor->getPesoRecibido() );
		}

		if( $compra_proveedor->getArpillas() != NULL){
			$sql .= " arpillas = ? AND";
			array_push( $val, $compra_proveedor->getArpillas() );
		}

		if( $compra_proveedor->getPesoPorArpilla() != NULL){
			$sql .= " peso_por_arpilla = ? AND";
			array_push( $val, $compra_proveedor->getPesoPorArpilla() );
		}

		if( $compra_proveedor->getProductor() != NULL){
			$sql .= " productor = ? AND";
			array_push( $val, $compra_proveedor->getProductor() );
		}

		if( $compra_proveedor->getCalidad() != NULL){
			$sql .= " calidad = ? AND";
			array_push( $val, $compra_proveedor->getCalidad() );
		}

		if( $compra_proveedor->getMermaPorArpilla() != NULL){
			$sql .= " merma_por_arpilla = ? AND";
			array_push( $val, $compra_proveedor->getMermaPorArpilla() );
		}

		if( $compra_proveedor->getTotalOrigen() != NULL){
			$sql .= " total_origen = ? AND";
			array_push( $val, $compra_proveedor->getTotalOrigen() );
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
			$bar =  new CompraProveedor($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_compra_proveedor"] );
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
	  * @param CompraProveedor [$compra_proveedor] El objeto de tipo CompraProveedor a actualizar.
	  **/
	private static final function update( $compra_proveedor )
	{
		$sql = "UPDATE compra_proveedor SET  peso_origen = ?, id_proveedor = ?, fecha = ?, fecha_origen = ?, folio = ?, numero_de_viaje = ?, peso_recibido = ?, arpillas = ?, peso_por_arpilla = ?, productor = ?, calidad = ?, merma_por_arpilla = ?, total_origen = ? WHERE  id_compra_proveedor = ?;";
		$params = array( 
			$compra_proveedor->getPesoOrigen(), 
			$compra_proveedor->getIdProveedor(), 
			$compra_proveedor->getFecha(), 
			$compra_proveedor->getFechaOrigen(), 
			$compra_proveedor->getFolio(), 
			$compra_proveedor->getNumeroDeViaje(), 
			$compra_proveedor->getPesoRecibido(), 
			$compra_proveedor->getArpillas(), 
			$compra_proveedor->getPesoPorArpilla(), 
			$compra_proveedor->getProductor(), 
			$compra_proveedor->getCalidad(), 
			$compra_proveedor->getMermaPorArpilla(), 
			$compra_proveedor->getTotalOrigen(), 
			$compra_proveedor->getIdCompraProveedor(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CompraProveedor suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CompraProveedor dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CompraProveedor [$compra_proveedor] El objeto de tipo CompraProveedor a crear.
	  **/
	private static final function create( &$compra_proveedor )
	{
		$sql = "INSERT INTO compra_proveedor ( id_compra_proveedor, peso_origen, id_proveedor, fecha, fecha_origen, folio, numero_de_viaje, peso_recibido, arpillas, peso_por_arpilla, productor, calidad, merma_por_arpilla, total_origen ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$compra_proveedor->getIdCompraProveedor(), 
			$compra_proveedor->getPesoOrigen(), 
			$compra_proveedor->getIdProveedor(), 
			$compra_proveedor->getFecha(), 
			$compra_proveedor->getFechaOrigen(), 
			$compra_proveedor->getFolio(), 
			$compra_proveedor->getNumeroDeViaje(), 
			$compra_proveedor->getPesoRecibido(), 
			$compra_proveedor->getArpillas(), 
			$compra_proveedor->getPesoPorArpilla(), 
			$compra_proveedor->getProductor(), 
			$compra_proveedor->getCalidad(), 
			$compra_proveedor->getMermaPorArpilla(), 
			$compra_proveedor->getTotalOrigen(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $compra_proveedor->setIdCompraProveedor( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraProveedor} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CompraProveedor}.
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
	  * @param CompraProveedor [$compra_proveedor] El objeto de tipo CompraProveedor
	  * @param CompraProveedor [$compra_proveedor] El objeto de tipo CompraProveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $compra_proveedorA , $compra_proveedorB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from compra_proveedor WHERE ("; 
		$val = array();
		if( (($a = $compra_proveedorA->getIdCompraProveedor()) != NULL) & ( ($b = $compra_proveedorB->getIdCompraProveedor()) != NULL) ){
				$sql .= " id_compra_proveedor >= ? AND id_compra_proveedor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_compra_proveedor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getPesoOrigen()) != NULL) & ( ($b = $compra_proveedorB->getPesoOrigen()) != NULL) ){
				$sql .= " peso_origen >= ? AND peso_origen <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " peso_origen = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getIdProveedor()) != NULL) & ( ($b = $compra_proveedorB->getIdProveedor()) != NULL) ){
				$sql .= " id_proveedor >= ? AND id_proveedor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_proveedor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getFecha()) != NULL) & ( ($b = $compra_proveedorB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getFechaOrigen()) != NULL) & ( ($b = $compra_proveedorB->getFechaOrigen()) != NULL) ){
				$sql .= " fecha_origen >= ? AND fecha_origen <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha_origen = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getFolio()) != NULL) & ( ($b = $compra_proveedorB->getFolio()) != NULL) ){
				$sql .= " folio >= ? AND folio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " folio = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getNumeroDeViaje()) != NULL) & ( ($b = $compra_proveedorB->getNumeroDeViaje()) != NULL) ){
				$sql .= " numero_de_viaje >= ? AND numero_de_viaje <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " numero_de_viaje = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getPesoRecibido()) != NULL) & ( ($b = $compra_proveedorB->getPesoRecibido()) != NULL) ){
				$sql .= " peso_recibido >= ? AND peso_recibido <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " peso_recibido = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getArpillas()) != NULL) & ( ($b = $compra_proveedorB->getArpillas()) != NULL) ){
				$sql .= " arpillas >= ? AND arpillas <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " arpillas = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getPesoPorArpilla()) != NULL) & ( ($b = $compra_proveedorB->getPesoPorArpilla()) != NULL) ){
				$sql .= " peso_por_arpilla >= ? AND peso_por_arpilla <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " peso_por_arpilla = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getProductor()) != NULL) & ( ($b = $compra_proveedorB->getProductor()) != NULL) ){
				$sql .= " productor >= ? AND productor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " productor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getCalidad()) != NULL) & ( ($b = $compra_proveedorB->getCalidad()) != NULL) ){
				$sql .= " calidad >= ? AND calidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " calidad = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getMermaPorArpilla()) != NULL) & ( ($b = $compra_proveedorB->getMermaPorArpilla()) != NULL) ){
				$sql .= " merma_por_arpilla >= ? AND merma_por_arpilla <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " merma_por_arpilla = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_proveedorA->getTotalOrigen()) != NULL) & ( ($b = $compra_proveedorB->getTotalOrigen()) != NULL) ){
				$sql .= " total_origen >= ? AND total_origen <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total_origen = ? AND"; 
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
    		array_push( $ar, new CompraProveedor($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CompraProveedor suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param CompraProveedor [$compra_proveedor] El objeto de tipo CompraProveedor a eliminar
	  **/
	public static final function delete( &$compra_proveedor )
	{
		if(self::getByPK($compra_proveedor->getIdCompraProveedor()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM compra_proveedor WHERE  id_compra_proveedor = ?;";
		$params = array( $compra_proveedor->getIdCompraProveedor() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
