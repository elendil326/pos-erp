<?php
/** CuentaContable Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link CuentaContable }. 
  * @author Anonymous
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class CuentaContableDAOBase extends DAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CuentaContable} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param CuentaContable [$cuenta_contable] El objeto de tipo CuentaContable
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$cuenta_contable )
	{
		if( ! is_null ( self::getByPK(  $cuenta_contable->getIdCuentaContable() ) ) )
		{
			try{ return CuentaContableDAOBase::update( $cuenta_contable) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return CuentaContableDAOBase::create( $cuenta_contable) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link CuentaContable} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CuentaContable} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CuentaContable Un objeto del tipo {@link CuentaContable}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_cuenta_contable )
	{
		$sql = "SELECT * FROM cuenta_contable WHERE (id_cuenta_contable = ? ) LIMIT 1;";
		$params = array(  $id_cuenta_contable );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new CuentaContable( $rs );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CuentaContable}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link CuentaContable}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from cuenta_contable";
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
			$bar = new CuentaContable($foo);
    		array_push( $allData, $bar);
			//id_cuenta_contable
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CuentaContable} de la base de datos. 
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
	  * @param CuentaContable [$cuenta_contable] El objeto de tipo CuentaContable
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $cuenta_contable , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from cuenta_contable WHERE ("; 
		$val = array();
		if( ! is_null( $cuenta_contable->getIdCuentaContable() ) ){
			$sql .= " `id_cuenta_contable` = ? AND";
			array_push( $val, $cuenta_contable->getIdCuentaContable() );
		}

		if( ! is_null( $cuenta_contable->getClave() ) ){
			$sql .= " `clave` = ? AND";
			array_push( $val, $cuenta_contable->getClave() );
		}

		if( ! is_null( $cuenta_contable->getNivel() ) ){
			$sql .= " `nivel` = ? AND";
			array_push( $val, $cuenta_contable->getNivel() );
		}

		if( ! is_null( $cuenta_contable->getConsecutivoEnNivel() ) ){
			$sql .= " `consecutivo_en_nivel` = ? AND";
			array_push( $val, $cuenta_contable->getConsecutivoEnNivel() );
		}

		if( ! is_null( $cuenta_contable->getNombreCuenta() ) ){
			$sql .= " `nombre_cuenta` = ? AND";
			array_push( $val, $cuenta_contable->getNombreCuenta() );
		}

		if( ! is_null( $cuenta_contable->getTipoCuenta() ) ){
			$sql .= " `tipo_cuenta` = ? AND";
			array_push( $val, $cuenta_contable->getTipoCuenta() );
		}

		if( ! is_null( $cuenta_contable->getNaturaleza() ) ){
			$sql .= " `naturaleza` = ? AND";
			array_push( $val, $cuenta_contable->getNaturaleza() );
		}

		if( ! is_null( $cuenta_contable->getClasificacion() ) ){
			$sql .= " `clasificacion` = ? AND";
			array_push( $val, $cuenta_contable->getClasificacion() );
		}

		if( ! is_null( $cuenta_contable->getCargosAumentan() ) ){
			$sql .= " `cargos_aumentan` = ? AND";
			array_push( $val, $cuenta_contable->getCargosAumentan() );
		}

		if( ! is_null( $cuenta_contable->getAbonosAumentan() ) ){
			$sql .= " `abonos_aumentan` = ? AND";
			array_push( $val, $cuenta_contable->getAbonosAumentan() );
		}

		if( ! is_null( $cuenta_contable->getEsCuentaOrden() ) ){
			$sql .= " `es_cuenta_orden` = ? AND";
			array_push( $val, $cuenta_contable->getEsCuentaOrden() );
		}

		if( ! is_null( $cuenta_contable->getEsCuentaMayor() ) ){
			$sql .= " `es_cuenta_mayor` = ? AND";
			array_push( $val, $cuenta_contable->getEsCuentaMayor() );
		}

		if( ! is_null( $cuenta_contable->getAfectable() ) ){
			$sql .= " `afectable` = ? AND";
			array_push( $val, $cuenta_contable->getAfectable() );
		}

		if( ! is_null( $cuenta_contable->getIdCuentaPadre() ) ){
			$sql .= " `id_cuenta_padre` = ? AND";
			array_push( $val, $cuenta_contable->getIdCuentaPadre() );
		}

		if( ! is_null( $cuenta_contable->getActiva() ) ){
			$sql .= " `activa` = ? AND";
			array_push( $val, $cuenta_contable->getActiva() );
		}

		if( ! is_null( $cuenta_contable->getIdCatalogoCuentas() ) ){
			$sql .= " `id_catalogo_cuentas` = ? AND";
			array_push( $val, $cuenta_contable->getIdCatalogoCuentas() );
		}

		if(sizeof($val) == 0){return self::getAll(/* $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' */);}
		$sql = substr($sql, 0, -3) . " )";
		if( ! is_null ( $orderBy ) ){
		    $sql .= " order by " . $orderBy . " " . $orden ;
		
		}
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array();
		foreach ($rs as $foo) {
			$bar =  new CuentaContable($foo);
    		array_push( $ar,$bar);
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
	  * @param CuentaContable [$cuenta_contable] El objeto de tipo CuentaContable a actualizar.
	  **/
	private static final function update( $cuenta_contable )
	{
		$sql = "UPDATE cuenta_contable SET  `clave` = ?, `nivel` = ?, `consecutivo_en_nivel` = ?, `nombre_cuenta` = ?, `tipo_cuenta` = ?, `naturaleza` = ?, `clasificacion` = ?, `cargos_aumentan` = ?, `abonos_aumentan` = ?, `es_cuenta_orden` = ?, `es_cuenta_mayor` = ?, `afectable` = ?, `id_cuenta_padre` = ?, `activa` = ?, `id_catalogo_cuentas` = ? WHERE  `id_cuenta_contable` = ?;";
		$params = array( 
			$cuenta_contable->getClave(), 
			$cuenta_contable->getNivel(), 
			$cuenta_contable->getConsecutivoEnNivel(), 
			$cuenta_contable->getNombreCuenta(), 
			$cuenta_contable->getTipoCuenta(), 
			$cuenta_contable->getNaturaleza(), 
			$cuenta_contable->getClasificacion(), 
			$cuenta_contable->getCargosAumentan(), 
			$cuenta_contable->getAbonosAumentan(), 
			$cuenta_contable->getEsCuentaOrden(), 
			$cuenta_contable->getEsCuentaMayor(), 
			$cuenta_contable->getAfectable(), 
			$cuenta_contable->getIdCuentaPadre(), 
			$cuenta_contable->getActiva(), 
			$cuenta_contable->getIdCatalogoCuentas(), 
			$cuenta_contable->getIdCuentaContable(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CuentaContable suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CuentaContable dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CuentaContable [$cuenta_contable] El objeto de tipo CuentaContable a crear.
	  **/
	private static final function create( &$cuenta_contable )
	{
		$sql = "INSERT INTO cuenta_contable ( `id_cuenta_contable`, `clave`, `nivel`, `consecutivo_en_nivel`, `nombre_cuenta`, `tipo_cuenta`, `naturaleza`, `clasificacion`, `cargos_aumentan`, `abonos_aumentan`, `es_cuenta_orden`, `es_cuenta_mayor`, `afectable`, `id_cuenta_padre`, `activa`, `id_catalogo_cuentas` ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$cuenta_contable->getIdCuentaContable(), 
			$cuenta_contable->getClave(), 
			$cuenta_contable->getNivel(), 
			$cuenta_contable->getConsecutivoEnNivel(), 
			$cuenta_contable->getNombreCuenta(), 
			$cuenta_contable->getTipoCuenta(), 
			$cuenta_contable->getNaturaleza(), 
			$cuenta_contable->getClasificacion(), 
			$cuenta_contable->getCargosAumentan(), 
			$cuenta_contable->getAbonosAumentan(), 
			$cuenta_contable->getEsCuentaOrden(), 
			$cuenta_contable->getEsCuentaMayor(), 
			$cuenta_contable->getAfectable(), 
			$cuenta_contable->getIdCuentaPadre(), 
			$cuenta_contable->getActiva(), 
			$cuenta_contable->getIdCatalogoCuentas(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $cuenta_contable->setIdCuentaContable( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CuentaContable} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CuentaContable}.
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
	  * @param CuentaContable [$cuenta_contable] El objeto de tipo CuentaContable
	  * @param CuentaContable [$cuenta_contable] El objeto de tipo CuentaContable
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $cuenta_contableA , $cuenta_contableB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from cuenta_contable WHERE ("; 
		$val = array();
		if( ( !is_null (($a = $cuenta_contableA->getIdCuentaContable()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getIdCuentaContable()) ) ) ){
				$sql .= " `id_cuenta_contable` >= ? AND `id_cuenta_contable` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `id_cuenta_contable` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getClave()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getClave()) ) ) ){
				$sql .= " `clave` >= ? AND `clave` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `clave` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getNivel()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getNivel()) ) ) ){
				$sql .= " `nivel` >= ? AND `nivel` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `nivel` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getConsecutivoEnNivel()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getConsecutivoEnNivel()) ) ) ){
				$sql .= " `consecutivo_en_nivel` >= ? AND `consecutivo_en_nivel` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `consecutivo_en_nivel` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getNombreCuenta()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getNombreCuenta()) ) ) ){
				$sql .= " `nombre_cuenta` >= ? AND `nombre_cuenta` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `nombre_cuenta` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getTipoCuenta()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getTipoCuenta()) ) ) ){
				$sql .= " `tipo_cuenta` >= ? AND `tipo_cuenta` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `tipo_cuenta` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getNaturaleza()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getNaturaleza()) ) ) ){
				$sql .= " `naturaleza` >= ? AND `naturaleza` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `naturaleza` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getClasificacion()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getClasificacion()) ) ) ){
				$sql .= " `clasificacion` >= ? AND `clasificacion` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `clasificacion` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getCargosAumentan()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getCargosAumentan()) ) ) ){
				$sql .= " `cargos_aumentan` >= ? AND `cargos_aumentan` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `cargos_aumentan` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getAbonosAumentan()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getAbonosAumentan()) ) ) ){
				$sql .= " `abonos_aumentan` >= ? AND `abonos_aumentan` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `abonos_aumentan` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getEsCuentaOrden()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getEsCuentaOrden()) ) ) ){
				$sql .= " `es_cuenta_orden` >= ? AND `es_cuenta_orden` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `es_cuenta_orden` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getEsCuentaMayor()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getEsCuentaMayor()) ) ) ){
				$sql .= " `es_cuenta_mayor` >= ? AND `es_cuenta_mayor` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `es_cuenta_mayor` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getAfectable()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getAfectable()) ) ) ){
				$sql .= " `afectable` >= ? AND `afectable` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `afectable` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getIdCuentaPadre()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getIdCuentaPadre()) ) ) ){
				$sql .= " `id_cuenta_padre` >= ? AND `id_cuenta_padre` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `id_cuenta_padre` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getActiva()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getActiva()) ) ) ){
				$sql .= " `activa` >= ? AND `activa` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `activa` = ? AND"; 
			$a = is_null ( $a ) ? $b : $a;
			array_push( $val, $a);
			
		}

		if( ( !is_null (($a = $cuenta_contableA->getIdCatalogoCuentas()) ) ) & ( ! is_null ( ($b = $cuenta_contableB->getIdCatalogoCuentas()) ) ) ){
				$sql .= " `id_catalogo_cuentas` >= ? AND `id_catalogo_cuentas` <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( !is_null ( $a ) || !is_null ( $b ) ){
			$sql .= " `id_catalogo_cuentas` = ? AND"; 
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
    		array_push( $ar, new CuentaContable($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CuentaContable suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param CuentaContable [$cuenta_contable] El objeto de tipo CuentaContable a eliminar
	  **/
	public static final function delete( &$cuenta_contable )
	{
		if( is_null( self::getByPK($cuenta_contable->getIdCuentaContable()) ) ) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM cuenta_contable WHERE  id_cuenta_contable = ?;";
		$params = array( $cuenta_contable->getIdCuentaContable() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
