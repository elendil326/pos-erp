<?php
/** Cliente Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Cliente }. 
  * @author no author especified
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class ClienteDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_cliente ){
			$pk = "";
			$pk .= $id_cliente . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_cliente){
			$pk = "";
			$pk .= $id_cliente . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_cliente ){
			$pk = "";
			$pk .= $id_cliente . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Cliente} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Cliente [$cliente] El objeto de tipo Cliente
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$cliente )
	{
		if(  self::getByPK(  $cliente->getIdCliente() ) !== NULL )
		{
			try{ return ClienteDAOBase::update( $cliente) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return ClienteDAOBase::create( $cliente) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Cliente} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Cliente} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Cliente Un objeto del tipo {@link Cliente}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_cliente )
	{
		if(self::recordExists(  $id_cliente)){
			return self::getRecord( $id_cliente );
		}
		$sql = "SELECT * FROM cliente WHERE (id_cliente = ? ) LIMIT 1;";
		$params = array(  $id_cliente );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Cliente( $rs );
			self::pushRecord( $foo,  $id_cliente );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Cliente}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Cliente}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from cliente";
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
			$bar = new Cliente($foo);
    		array_push( $allData, $bar);
			//id_cliente
    		self::pushRecord( $bar, $foo["id_cliente"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Cliente} de la base de datos. 
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
	  * @param Cliente [$cliente] El objeto de tipo Cliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $cliente , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if( $cliente->getIdCliente() != NULL){
			$sql .= " id_cliente = ? AND";
			array_push( $val, $cliente->getIdCliente() );
		}

		if( $cliente->getRfc() != NULL){
			$sql .= " rfc = ? AND";
			array_push( $val, $cliente->getRfc() );
		}

		if( $cliente->getRazonSocial() != NULL){
			$sql .= " razon_social = ? AND";
			array_push( $val, $cliente->getRazonSocial() );
		}

		if( $cliente->getCalle() != NULL){
			$sql .= " calle = ? AND";
			array_push( $val, $cliente->getCalle() );
		}

		if( $cliente->getNumeroExterior() != NULL){
			$sql .= " numero_exterior = ? AND";
			array_push( $val, $cliente->getNumeroExterior() );
		}

		if( $cliente->getNumeroInterior() != NULL){
			$sql .= " numero_interior = ? AND";
			array_push( $val, $cliente->getNumeroInterior() );
		}

		if( $cliente->getColonia() != NULL){
			$sql .= " colonia = ? AND";
			array_push( $val, $cliente->getColonia() );
		}

		if( $cliente->getReferencia() != NULL){
			$sql .= " referencia = ? AND";
			array_push( $val, $cliente->getReferencia() );
		}

		if( $cliente->getMunicipio() != NULL){
			$sql .= " municipio = ? AND";
			array_push( $val, $cliente->getMunicipio() );
		}

		if( $cliente->getEstado() != NULL){
			$sql .= " estado = ? AND";
			array_push( $val, $cliente->getEstado() );
		}

		if( $cliente->getPais() != NULL){
			$sql .= " pais = ? AND";
			array_push( $val, $cliente->getPais() );
		}

		if( $cliente->getCodigoPostal() != NULL){
			$sql .= " codigo_postal = ? AND";
			array_push( $val, $cliente->getCodigoPostal() );
		}

		if( $cliente->getTelefono() != NULL){
			$sql .= " telefono = ? AND";
			array_push( $val, $cliente->getTelefono() );
		}

		if( $cliente->getEMail() != NULL){
			$sql .= " e_mail = ? AND";
			array_push( $val, $cliente->getEMail() );
		}

		if( $cliente->getLimiteCredito() != NULL){
			$sql .= " limite_credito = ? AND";
			array_push( $val, $cliente->getLimiteCredito() );
		}

		if( $cliente->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $cliente->getDescuento() );
		}

		if( $cliente->getActivo() != NULL){
			$sql .= " activo = ? AND";
			array_push( $val, $cliente->getActivo() );
		}

		if( $cliente->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $cliente->getIdUsuario() );
		}

		if( $cliente->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $cliente->getIdSucursal() );
		}

		if( $cliente->getFechaIngreso() != NULL){
			$sql .= " fecha_ingreso = ? AND";
			array_push( $val, $cliente->getFechaIngreso() );
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
			$bar =  new Cliente($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_cliente"] );
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
	  * @param Cliente [$cliente] El objeto de tipo Cliente a actualizar.
	  **/
	private static final function update( $cliente )
	{
		$sql = "UPDATE cliente SET  rfc = ?, razon_social = ?, calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?, referencia = ?, municipio = ?, estado = ?, pais = ?, codigo_postal = ?, telefono = ?, e_mail = ?, limite_credito = ?, descuento = ?, activo = ?, id_usuario = ?, id_sucursal = ?, fecha_ingreso = ? WHERE  id_cliente = ?;";
		$params = array( 
			$cliente->getRfc(), 
			$cliente->getRazonSocial(), 
			$cliente->getCalle(), 
			$cliente->getNumeroExterior(), 
			$cliente->getNumeroInterior(), 
			$cliente->getColonia(), 
			$cliente->getReferencia(), 
			$cliente->getMunicipio(), 
			$cliente->getEstado(), 
			$cliente->getPais(), 
			$cliente->getCodigoPostal(), 
			$cliente->getTelefono(), 
			$cliente->getEMail(), 
			$cliente->getLimiteCredito(), 
			$cliente->getDescuento(), 
			$cliente->getActivo(), 
			$cliente->getIdUsuario(), 
			$cliente->getIdSucursal(), 
			$cliente->getFechaIngreso(), 
			$cliente->getIdCliente(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Cliente suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Cliente dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Cliente [$cliente] El objeto de tipo Cliente a crear.
	  **/
	private static final function create( &$cliente )
	{
		$sql = "INSERT INTO cliente ( id_cliente, rfc, razon_social, calle, numero_exterior, numero_interior, colonia, referencia, municipio, estado, pais, codigo_postal, telefono, e_mail, limite_credito, descuento, activo, id_usuario, id_sucursal, fecha_ingreso ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$cliente->getIdCliente(), 
			$cliente->getRfc(), 
			$cliente->getRazonSocial(), 
			$cliente->getCalle(), 
			$cliente->getNumeroExterior(), 
			$cliente->getNumeroInterior(), 
			$cliente->getColonia(), 
			$cliente->getReferencia(), 
			$cliente->getMunicipio(), 
			$cliente->getEstado(), 
			$cliente->getPais(), 
			$cliente->getCodigoPostal(), 
			$cliente->getTelefono(), 
			$cliente->getEMail(), 
			$cliente->getLimiteCredito(), 
			$cliente->getDescuento(), 
			$cliente->getActivo(), 
			$cliente->getIdUsuario(), 
			$cliente->getIdSucursal(), 
			$cliente->getFechaIngreso(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $cliente->setIdCliente( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Cliente} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Cliente}.
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
	  * @param Cliente [$cliente] El objeto de tipo Cliente
	  * @param Cliente [$cliente] El objeto de tipo Cliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $clienteA , $clienteB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if( (($a = $clienteA->getIdCliente()) != NULL) & ( ($b = $clienteB->getIdCliente()) != NULL) ){
				$sql .= " id_cliente >= ? AND id_cliente <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_cliente = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getRfc()) != NULL) & ( ($b = $clienteB->getRfc()) != NULL) ){
				$sql .= " rfc >= ? AND rfc <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " rfc = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getRazonSocial()) != NULL) & ( ($b = $clienteB->getRazonSocial()) != NULL) ){
				$sql .= " razon_social >= ? AND razon_social <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " razon_social = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getCalle()) != NULL) & ( ($b = $clienteB->getCalle()) != NULL) ){
				$sql .= " calle >= ? AND calle <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " calle = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getNumeroExterior()) != NULL) & ( ($b = $clienteB->getNumeroExterior()) != NULL) ){
				$sql .= " numero_exterior >= ? AND numero_exterior <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " numero_exterior = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getNumeroInterior()) != NULL) & ( ($b = $clienteB->getNumeroInterior()) != NULL) ){
				$sql .= " numero_interior >= ? AND numero_interior <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " numero_interior = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getColonia()) != NULL) & ( ($b = $clienteB->getColonia()) != NULL) ){
				$sql .= " colonia >= ? AND colonia <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " colonia = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getReferencia()) != NULL) & ( ($b = $clienteB->getReferencia()) != NULL) ){
				$sql .= " referencia >= ? AND referencia <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " referencia = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getMunicipio()) != NULL) & ( ($b = $clienteB->getMunicipio()) != NULL) ){
				$sql .= " municipio >= ? AND municipio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " municipio = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getEstado()) != NULL) & ( ($b = $clienteB->getEstado()) != NULL) ){
				$sql .= " estado >= ? AND estado <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " estado = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getPais()) != NULL) & ( ($b = $clienteB->getPais()) != NULL) ){
				$sql .= " pais >= ? AND pais <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " pais = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getCodigoPostal()) != NULL) & ( ($b = $clienteB->getCodigoPostal()) != NULL) ){
				$sql .= " codigo_postal >= ? AND codigo_postal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " codigo_postal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getTelefono()) != NULL) & ( ($b = $clienteB->getTelefono()) != NULL) ){
				$sql .= " telefono >= ? AND telefono <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " telefono = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getEMail()) != NULL) & ( ($b = $clienteB->getEMail()) != NULL) ){
				$sql .= " e_mail >= ? AND e_mail <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " e_mail = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getLimiteCredito()) != NULL) & ( ($b = $clienteB->getLimiteCredito()) != NULL) ){
				$sql .= " limite_credito >= ? AND limite_credito <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " limite_credito = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getDescuento()) != NULL) & ( ($b = $clienteB->getDescuento()) != NULL) ){
				$sql .= " descuento >= ? AND descuento <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descuento = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getActivo()) != NULL) & ( ($b = $clienteB->getActivo()) != NULL) ){
				$sql .= " activo >= ? AND activo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " activo = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getIdUsuario()) != NULL) & ( ($b = $clienteB->getIdUsuario()) != NULL) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getIdSucursal()) != NULL) & ( ($b = $clienteB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $clienteA->getFechaIngreso()) != NULL) & ( ($b = $clienteB->getFechaIngreso()) != NULL) ){
				$sql .= " fecha_ingreso >= ? AND fecha_ingreso <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha_ingreso = ? AND"; 
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
    		array_push( $ar, new Cliente($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Cliente suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Cliente [$cliente] El objeto de tipo Cliente a eliminar
	  **/
	public static final function delete( &$cliente )
	{
		if(self::getByPK($cliente->getIdCliente()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM cliente WHERE  id_cliente = ?;";
		$params = array( $cliente->getIdCliente() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
