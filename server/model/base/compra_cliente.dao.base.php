<?php
/** CompraCliente Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link CompraCliente }. 
  * @author no author especified
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class CompraClienteDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_compra ){
			$pk = "";
			$pk .= $id_compra . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_compra){
			$pk = "";
			$pk .= $id_compra . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_compra ){
			$pk = "";
			$pk .= $id_compra . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CompraCliente} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$compra_cliente )
	{
		if(  self::getByPK(  $compra_cliente->getIdCompra() ) !== NULL )
		{
			try{ return CompraClienteDAOBase::update( $compra_cliente) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return CompraClienteDAOBase::create( $compra_cliente) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link CompraCliente} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CompraCliente} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CompraCliente Un objeto del tipo {@link CompraCliente}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_compra )
	{
		if(self::recordExists(  $id_compra)){
			return self::getRecord( $id_compra );
		}
		$sql = "SELECT * FROM compra_cliente WHERE (id_compra = ? ) LIMIT 1;";
		$params = array(  $id_compra );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new CompraCliente( $rs );
			self::pushRecord( $foo,  $id_compra );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CompraCliente}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link CompraCliente}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from compra_cliente";
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
			$bar = new CompraCliente($foo);
    		array_push( $allData, $bar);
			//id_compra
    		self::pushRecord( $bar, $foo["id_compra"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraCliente} de la base de datos. 
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
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $compra_cliente , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from compra_cliente WHERE ("; 
		$val = array();
		if( $compra_cliente->getIdCompra() != NULL){
			$sql .= " id_compra = ? AND";
			array_push( $val, $compra_cliente->getIdCompra() );
		}

		if( $compra_cliente->getIdCliente() != NULL){
			$sql .= " id_cliente = ? AND";
			array_push( $val, $compra_cliente->getIdCliente() );
		}

		if( $compra_cliente->getTipoCompra() != NULL){
			$sql .= " tipo_compra = ? AND";
			array_push( $val, $compra_cliente->getTipoCompra() );
		}

		if( $compra_cliente->getTipoPago() != NULL){
			$sql .= " tipo_pago = ? AND";
			array_push( $val, $compra_cliente->getTipoPago() );
		}

		if( $compra_cliente->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $compra_cliente->getFecha() );
		}

		if( $compra_cliente->getSubtotal() != NULL){
			$sql .= " subtotal = ? AND";
			array_push( $val, $compra_cliente->getSubtotal() );
		}

		if( $compra_cliente->getIva() != NULL){
			$sql .= " iva = ? AND";
			array_push( $val, $compra_cliente->getIva() );
		}

		if( $compra_cliente->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $compra_cliente->getDescuento() );
		}

		if( $compra_cliente->getTotal() != NULL){
			$sql .= " total = ? AND";
			array_push( $val, $compra_cliente->getTotal() );
		}

		if( $compra_cliente->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $compra_cliente->getIdSucursal() );
		}

		if( $compra_cliente->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $compra_cliente->getIdUsuario() );
		}

		if( $compra_cliente->getPagado() != NULL){
			$sql .= " pagado = ? AND";
			array_push( $val, $compra_cliente->getPagado() );
		}

		if( $compra_cliente->getCancelada() != NULL){
			$sql .= " cancelada = ? AND";
			array_push( $val, $compra_cliente->getCancelada() );
		}

		if( $compra_cliente->getIp() != NULL){
			$sql .= " ip = ? AND";
			array_push( $val, $compra_cliente->getIp() );
		}

		if( $compra_cliente->getLiquidada() != NULL){
			$sql .= " liquidada = ? AND";
			array_push( $val, $compra_cliente->getLiquidada() );
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
			$bar =  new CompraCliente($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_compra"] );
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
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente a actualizar.
	  **/
	private static final function update( $compra_cliente )
	{
		$sql = "UPDATE compra_cliente SET  id_cliente = ?, tipo_compra = ?, tipo_pago = ?, fecha = ?, subtotal = ?, iva = ?, descuento = ?, total = ?, id_sucursal = ?, id_usuario = ?, pagado = ?, cancelada = ?, ip = ?, liquidada = ? WHERE  id_compra = ?;";
		$params = array( 
			$compra_cliente->getIdCliente(), 
			$compra_cliente->getTipoCompra(), 
			$compra_cliente->getTipoPago(), 
			$compra_cliente->getFecha(), 
			$compra_cliente->getSubtotal(), 
			$compra_cliente->getIva(), 
			$compra_cliente->getDescuento(), 
			$compra_cliente->getTotal(), 
			$compra_cliente->getIdSucursal(), 
			$compra_cliente->getIdUsuario(), 
			$compra_cliente->getPagado(), 
			$compra_cliente->getCancelada(), 
			$compra_cliente->getIp(), 
			$compra_cliente->getLiquidada(), 
			$compra_cliente->getIdCompra(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CompraCliente suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CompraCliente dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente a crear.
	  **/
	private static final function create( &$compra_cliente )
	{
		$sql = "INSERT INTO compra_cliente ( id_compra, id_cliente, tipo_compra, tipo_pago, fecha, subtotal, iva, descuento, total, id_sucursal, id_usuario, pagado, cancelada, ip, liquidada ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$compra_cliente->getIdCompra(), 
			$compra_cliente->getIdCliente(), 
			$compra_cliente->getTipoCompra(), 
			$compra_cliente->getTipoPago(), 
			$compra_cliente->getFecha(), 
			$compra_cliente->getSubtotal(), 
			$compra_cliente->getIva(), 
			$compra_cliente->getDescuento(), 
			$compra_cliente->getTotal(), 
			$compra_cliente->getIdSucursal(), 
			$compra_cliente->getIdUsuario(), 
			$compra_cliente->getPagado(), 
			$compra_cliente->getCancelada(), 
			$compra_cliente->getIp(), 
			$compra_cliente->getLiquidada(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $compra_cliente->setIdCompra( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraCliente} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CompraCliente}.
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
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $compra_clienteA , $compra_clienteB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from compra_cliente WHERE ("; 
		$val = array();
		if( (($a = $compra_clienteA->getIdCompra()) != NULL) & ( ($b = $compra_clienteB->getIdCompra()) != NULL) ){
				$sql .= " id_compra >= ? AND id_compra <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_compra = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getIdCliente()) != NULL) & ( ($b = $compra_clienteB->getIdCliente()) != NULL) ){
				$sql .= " id_cliente >= ? AND id_cliente <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_cliente = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getTipoCompra()) != NULL) & ( ($b = $compra_clienteB->getTipoCompra()) != NULL) ){
				$sql .= " tipo_compra >= ? AND tipo_compra <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " tipo_compra = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getTipoPago()) != NULL) & ( ($b = $compra_clienteB->getTipoPago()) != NULL) ){
				$sql .= " tipo_pago >= ? AND tipo_pago <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " tipo_pago = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getFecha()) != NULL) & ( ($b = $compra_clienteB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getSubtotal()) != NULL) & ( ($b = $compra_clienteB->getSubtotal()) != NULL) ){
				$sql .= " subtotal >= ? AND subtotal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " subtotal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getIva()) != NULL) & ( ($b = $compra_clienteB->getIva()) != NULL) ){
				$sql .= " iva >= ? AND iva <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " iva = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getDescuento()) != NULL) & ( ($b = $compra_clienteB->getDescuento()) != NULL) ){
				$sql .= " descuento >= ? AND descuento <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descuento = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getTotal()) != NULL) & ( ($b = $compra_clienteB->getTotal()) != NULL) ){
				$sql .= " total >= ? AND total <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getIdSucursal()) != NULL) & ( ($b = $compra_clienteB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getIdUsuario()) != NULL) & ( ($b = $compra_clienteB->getIdUsuario()) != NULL) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getPagado()) != NULL) & ( ($b = $compra_clienteB->getPagado()) != NULL) ){
				$sql .= " pagado >= ? AND pagado <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " pagado = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getCancelada()) != NULL) & ( ($b = $compra_clienteB->getCancelada()) != NULL) ){
				$sql .= " cancelada >= ? AND cancelada <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " cancelada = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getIp()) != NULL) & ( ($b = $compra_clienteB->getIp()) != NULL) ){
				$sql .= " ip >= ? AND ip <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " ip = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $compra_clienteA->getLiquidada()) != NULL) & ( ($b = $compra_clienteB->getLiquidada()) != NULL) ){
				$sql .= " liquidada >= ? AND liquidada <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " liquidada = ? AND"; 
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
    		array_push( $ar, new CompraCliente($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CompraCliente suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente a eliminar
	  **/
	public static final function delete( &$compra_cliente )
	{
		if(self::getByPK($compra_cliente->getIdCompra()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM compra_cliente WHERE  id_compra = ?;";
		$params = array( $compra_cliente->getIdCompra() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
