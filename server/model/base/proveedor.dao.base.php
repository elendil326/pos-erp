<?php
/** Proveedor Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Proveedor }. 
  * @author no author especified
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class ProveedorDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_proveedor ){
			$pk = "";
			$pk .= $id_proveedor . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_proveedor){
			$pk = "";
			$pk .= $id_proveedor . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_proveedor ){
			$pk = "";
			$pk .= $id_proveedor . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Proveedor} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$proveedor )
	{
		if(  self::getByPK(  $proveedor->getIdProveedor() ) !== NULL )
		{
			try{ return ProveedorDAOBase::update( $proveedor) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return ProveedorDAOBase::create( $proveedor) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Proveedor} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Proveedor} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Proveedor Un objeto del tipo {@link Proveedor}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_proveedor )
	{
		if(self::recordExists(  $id_proveedor)){
			return self::getRecord( $id_proveedor );
		}
		$sql = "SELECT * FROM proveedor WHERE (id_proveedor = ? ) LIMIT 1;";
		$params = array(  $id_proveedor );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Proveedor( $rs );
			self::pushRecord( $foo,  $id_proveedor );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Proveedor}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Proveedor}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from proveedor";
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
			$bar = new Proveedor($foo);
    		array_push( $allData, $bar);
			//id_proveedor
    		self::pushRecord( $bar, $foo["id_proveedor"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Proveedor} de la base de datos. 
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
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $proveedor , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from proveedor WHERE ("; 
		$val = array();
		if( $proveedor->getIdProveedor() != NULL){
			$sql .= " id_proveedor = ? AND";
			array_push( $val, $proveedor->getIdProveedor() );
		}

		if( $proveedor->getRfc() != NULL){
			$sql .= " rfc = ? AND";
			array_push( $val, $proveedor->getRfc() );
		}

		if( $proveedor->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $proveedor->getNombre() );
		}

		if( $proveedor->getDireccion() != NULL){
			$sql .= " direccion = ? AND";
			array_push( $val, $proveedor->getDireccion() );
		}

		if( $proveedor->getTelefono() != NULL){
			$sql .= " telefono = ? AND";
			array_push( $val, $proveedor->getTelefono() );
		}

		if( $proveedor->getEMail() != NULL){
			$sql .= " e_mail = ? AND";
			array_push( $val, $proveedor->getEMail() );
		}

		if( $proveedor->getActivo() != NULL){
			$sql .= " activo = ? AND";
			array_push( $val, $proveedor->getActivo() );
		}

		if( $proveedor->getTipoProveedor() != NULL){
			$sql .= " tipo_proveedor = ? AND";
			array_push( $val, $proveedor->getTipoProveedor() );
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
			$bar =  new Proveedor($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_proveedor"] );
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
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor a actualizar.
	  **/
	private static final function update( $proveedor )
	{
		$sql = "UPDATE proveedor SET  rfc = ?, nombre = ?, direccion = ?, telefono = ?, e_mail = ?, activo = ?, tipo_proveedor = ? WHERE  id_proveedor = ?;";
		$params = array( 
			$proveedor->getRfc(), 
			$proveedor->getNombre(), 
			$proveedor->getDireccion(), 
			$proveedor->getTelefono(), 
			$proveedor->getEMail(), 
			$proveedor->getActivo(), 
			$proveedor->getTipoProveedor(), 
			$proveedor->getIdProveedor(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Proveedor suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Proveedor dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor a crear.
	  **/
	private static final function create( &$proveedor )
	{
		$sql = "INSERT INTO proveedor ( id_proveedor, rfc, nombre, direccion, telefono, e_mail, activo, tipo_proveedor ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$proveedor->getIdProveedor(), 
			$proveedor->getRfc(), 
			$proveedor->getNombre(), 
			$proveedor->getDireccion(), 
			$proveedor->getTelefono(), 
			$proveedor->getEMail(), 
			$proveedor->getActivo(), 
			$proveedor->getTipoProveedor(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $proveedor->setIdProveedor( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Proveedor} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Proveedor}.
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
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $proveedorA , $proveedorB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from proveedor WHERE ("; 
		$val = array();
		if( (($a = $proveedorA->getIdProveedor()) != NULL) & ( ($b = $proveedorB->getIdProveedor()) != NULL) ){
				$sql .= " id_proveedor >= ? AND id_proveedor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_proveedor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $proveedorA->getRfc()) != NULL) & ( ($b = $proveedorB->getRfc()) != NULL) ){
				$sql .= " rfc >= ? AND rfc <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " rfc = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $proveedorA->getNombre()) != NULL) & ( ($b = $proveedorB->getNombre()) != NULL) ){
				$sql .= " nombre >= ? AND nombre <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " nombre = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $proveedorA->getDireccion()) != NULL) & ( ($b = $proveedorB->getDireccion()) != NULL) ){
				$sql .= " direccion >= ? AND direccion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " direccion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $proveedorA->getTelefono()) != NULL) & ( ($b = $proveedorB->getTelefono()) != NULL) ){
				$sql .= " telefono >= ? AND telefono <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " telefono = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $proveedorA->getEMail()) != NULL) & ( ($b = $proveedorB->getEMail()) != NULL) ){
				$sql .= " e_mail >= ? AND e_mail <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " e_mail = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $proveedorA->getActivo()) != NULL) & ( ($b = $proveedorB->getActivo()) != NULL) ){
				$sql .= " activo >= ? AND activo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " activo = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $proveedorA->getTipoProveedor()) != NULL) & ( ($b = $proveedorB->getTipoProveedor()) != NULL) ){
				$sql .= " tipo_proveedor >= ? AND tipo_proveedor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " tipo_proveedor = ? AND"; 
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
    		array_push( $ar, new Proveedor($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Proveedor suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor a eliminar
	  **/
	public static final function delete( &$proveedor )
	{
		if(self::getByPK($proveedor->getIdProveedor()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM proveedor WHERE  id_proveedor = ?;";
		$params = array( $proveedor->getIdProveedor() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
