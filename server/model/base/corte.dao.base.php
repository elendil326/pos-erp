<?php
/** Corte Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Corte }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class CorteDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Corte} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Corte [$corte] El objeto de tipo Corte
	  * @return Un entero mayor o igual a cero denotando las filas afectadas, o un string con el error si es que hubo alguno.
	  **/
	public static final function save( &$corte )
	{
		if( self::getByPK(  $corte->getNumCorte() ) === NULL )
		{
			try{ return CorteDAOBase::create( $corte) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return CorteDAOBase::update( $corte) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Corte} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Corte} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Corte}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $num_corte )
	{
		$sql = "SELECT * FROM corte WHERE (num_corte = ? ) LIMIT 1;";
		$params = array(  $num_corte );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new Corte( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Corte}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Corte}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from corte";
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
    		array_push( $allData, new Corte($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Corte} de la base de datos. 
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
	  * @param Corte [$corte] El objeto de tipo Corte
	  * @param bool [$json] Verdadero para obtener los resultados en forma JSON y no objetos. En caso de no presentare este parametro se tomara el valor default de false.
	  **/
	public static final function search( $corte , $json = false)
	{
		$sql = "SELECT * from corte WHERE ("; 
		$val = array();
		if( $corte->getNumCorte() != NULL){
			$sql .= " num_corte = ? AND";
			array_push( $val, $corte->getNumCorte() );
		}

		if( $corte->getAnio() != NULL){
			$sql .= " anio = ? AND";
			array_push( $val, $corte->getAnio() );
		}

		if( $corte->getInicio() != NULL){
			$sql .= " inicio = ? AND";
			array_push( $val, $corte->getInicio() );
		}

		if( $corte->getFin() != NULL){
			$sql .= " fin = ? AND";
			array_push( $val, $corte->getFin() );
		}

		if( $corte->getVentas() != NULL){
			$sql .= " ventas = ? AND";
			array_push( $val, $corte->getVentas() );
		}

		if( $corte->getAbonosVentas() != NULL){
			$sql .= " abonosVentas = ? AND";
			array_push( $val, $corte->getAbonosVentas() );
		}

		if( $corte->getCompras() != NULL){
			$sql .= " compras = ? AND";
			array_push( $val, $corte->getCompras() );
		}

		if( $corte->getAbonosCompra() != NULL){
			$sql .= " AbonosCompra = ? AND";
			array_push( $val, $corte->getAbonosCompra() );
		}

		if( $corte->getGastos() != NULL){
			$sql .= " gastos = ? AND";
			array_push( $val, $corte->getGastos() );
		}

		if( $corte->getIngresos() != NULL){
			$sql .= " ingresos = ? AND";
			array_push( $val, $corte->getIngresos() );
		}

		if( $corte->getGananciasNetas() != NULL){
			$sql .= " gananciasNetas = ? AND";
			array_push( $val, $corte->getGananciasNetas() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		if($json === false){
			$ar = array();
			foreach ($rs as $foo) {
    			array_push( $ar, new Corte($foo));
			}
			return $ar;
		}else{
			$allData = '[';
			foreach ($rs as $foo) {
    			$allData .= new Corte($foo) . ',';
			}
    		$allData = substr($allData, 0 , -1) . ']';
			return $allData;
		}
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
	  * @param Corte [$corte] El objeto de tipo Corte a actualizar.
	  **/
	private static final function update( $corte )
	{
		$sql = "UPDATE corte SET  anio = ?, inicio = ?, fin = ?, ventas = ?, abonosVentas = ?, compras = ?, AbonosCompra = ?, gastos = ?, ingresos = ?, gananciasNetas = ? WHERE  num_corte = ?;";
		$params = array( 
			$corte->getAnio(), 
			$corte->getInicio(), 
			$corte->getFin(), 
			$corte->getVentas(), 
			$corte->getAbonosVentas(), 
			$corte->getCompras(), 
			$corte->getAbonosCompra(), 
			$corte->getGastos(), 
			$corte->getIngresos(), 
			$corte->getGananciasNetas(), 
			$corte->getNumCorte(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Corte suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Corte dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Corte [$corte] El objeto de tipo Corte a crear.
	  **/
	private static final function create( &$corte )
	{
		$sql = "INSERT INTO corte ( anio, inicio, fin, ventas, abonosVentas, compras, AbonosCompra, gastos, ingresos, gananciasNetas ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$corte->getAnio(), 
			$corte->getInicio(), 
			$corte->getFin(), 
			$corte->getVentas(), 
			$corte->getAbonosVentas(), 
			$corte->getCompras(), 
			$corte->getAbonosCompra(), 
			$corte->getGastos(), 
			$corte->getIngresos(), 
			$corte->getGananciasNetas(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		$corte->setNumCorte( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Corte} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Corte}.
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
	  * @param Corte [$corte] El objeto de tipo Corte
	  * @param Corte [$corte] El objeto de tipo Corte
	  * @param bool [$json] Verdadero para obtener los resultados en forma JSON y no objetos. En caso de no presentare este parametro se tomara el valor default de false.
	  **/
	public static final function byRange( $corteA , $corteB , $json = false)
	{
		$sql = "SELECT * from corte WHERE ("; 
		$val = array();
		if( (($a = $corteA->getNumCorte()) != NULL) & ( ($b = $corteB->getNumCorte()) != NULL) ){
				$sql .= " num_corte >= ? AND num_corte <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " num_corte = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getAnio()) != NULL) & ( ($b = $corteB->getAnio()) != NULL) ){
				$sql .= " anio >= ? AND anio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " anio = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getInicio()) != NULL) & ( ($b = $corteB->getInicio()) != NULL) ){
				$sql .= " inicio >= ? AND inicio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " inicio = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getFin()) != NULL) & ( ($b = $corteB->getFin()) != NULL) ){
				$sql .= " fin >= ? AND fin <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fin = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getVentas()) != NULL) & ( ($b = $corteB->getVentas()) != NULL) ){
				$sql .= " ventas >= ? AND ventas <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " ventas = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getAbonosVentas()) != NULL) & ( ($b = $corteB->getAbonosVentas()) != NULL) ){
				$sql .= " abonosVentas >= ? AND abonosVentas <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " abonosVentas = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getCompras()) != NULL) & ( ($b = $corteB->getCompras()) != NULL) ){
				$sql .= " compras >= ? AND compras <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " compras = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getAbonosCompra()) != NULL) & ( ($b = $corteB->getAbonosCompra()) != NULL) ){
				$sql .= " AbonosCompra >= ? AND AbonosCompra <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " AbonosCompra = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getGastos()) != NULL) & ( ($b = $corteB->getGastos()) != NULL) ){
				$sql .= " gastos >= ? AND gastos <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " gastos = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getIngresos()) != NULL) & ( ($b = $corteB->getIngresos()) != NULL) ){
				$sql .= " ingresos >= ? AND ingresos <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " ingresos = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $corteA->getGananciasNetas()) != NULL) & ( ($b = $corteB->getGananciasNetas()) != NULL) ){
				$sql .= " gananciasNetas >= ? AND gananciasNetas <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " gananciasNetas = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		if($json === false){
			$ar = array();
			foreach ($rs as $foo) {
    			array_push( $ar, new Corte($foo));
			}
			return $ar;
		}else{
			$allData = '[';
			foreach ($rs as $foo) {
    			$allData .= new Corte($foo) . ',';
			}
    		$allData = substr($allData, 0 , -1) . ']';
			return $allData;
		}
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Corte suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Corte [$corte] El objeto de tipo Corte a eliminar
	  **/
	public static final function delete( &$corte )
	{
		if(self::getByPK($corte->getNumCorte()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM corte WHERE  num_corte = ?;";
		$params = array( $corte->getNumCorte() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
