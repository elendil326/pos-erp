<?php
/** DetalleCorte Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link DetalleCorte }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class DetalleCorteDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleCorte} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte
	  * @return Un entero mayor o igual a cero denotando las filas afectadas, o un string con el error si es que hubo alguno.
	  **/
	public static final function save( &$detalle_corte )
	{
		if( self::getByPK(  $detalle_corte->getNumCorte() , $detalle_corte->getNombre() ) === NULL )
		{
			try{ return DetalleCorteDAOBase::create( $detalle_corte) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return DetalleCorteDAOBase::update( $detalle_corte) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link DetalleCorte} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleCorte} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link DetalleCorte}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $num_corte, $nombre )
	{
		$sql = "SELECT * FROM detalle_corte WHERE (num_corte = ? AND nombre = ? ) LIMIT 1;";
		$params = array(  $num_corte, $nombre );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new DetalleCorte( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleCorte}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link DetalleCorte}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from detalle_corte";
		if($pagina != NULL)
		{
			if($orden != NULL)
			{ $sql .= " ORDER BY " . $orden . " " . $tipo_de_orden;	}
			$sql .= " LIMIT " . (( $pagina - 1 )*$columnas_por_pagina) . "," . $columnas_por_pagina; 
		}
		global $conn;
		$rs = $conn->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new DetalleCorte($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCorte} de la base de datos. 
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
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte
	  * @param bool [$json] Verdadero para obtener los resultados en forma JSON y no objetos. En caso de no presentare este parametro se tomara el valor default de false.
	  **/
	public static final function search( $detalle_corte , $json = false)
	{
		$sql = "SELECT * from detalle_corte WHERE ("; 
		$val = array();
		if( $detalle_corte->getNumCorte() != NULL){
			$sql .= " num_corte = ? AND";
			array_push( $val, $detalle_corte->getNumCorte() );
		}

		if( $detalle_corte->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $detalle_corte->getNombre() );
		}

		if( $detalle_corte->getTotal() != NULL){
			$sql .= " total = ? AND";
			array_push( $val, $detalle_corte->getTotal() );
		}

		if( $detalle_corte->getDeben() != NULL){
			$sql .= " deben = ? AND";
			array_push( $val, $detalle_corte->getDeben() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		if($json === false){
			$ar = array();
			foreach ($rs as $foo) {
    			array_push( $ar, new DetalleCorte($foo));
			}
			return $ar;
		}else{
			$allData = '[';
			foreach ($rs as $foo) {
    			$allData .= new DetalleCorte($foo) . ',';
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
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte a actualizar.
	  **/
	private static final function update( $detalle_corte )
	{
		$sql = "UPDATE detalle_corte SET  total = ?, deben = ? WHERE  num_corte = ? AND nombre = ?;";
		$params = array( 
			$detalle_corte->getTotal(), 
			$detalle_corte->getDeben(), 
			$detalle_corte->getNumCorte(),$detalle_corte->getNombre(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleCorte suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleCorte dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte a crear.
	  **/
	private static final function create( &$detalle_corte )
	{
		$sql = "INSERT INTO detalle_corte ( num_corte, nombre, total, deben ) VALUES ( ?, ?, ?, ?);";
		$params = array( 
			$detalle_corte->getNumCorte(), 
			$detalle_corte->getNombre(), 
			$detalle_corte->getTotal(), 
			$detalle_corte->getDeben(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCorte} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleCorte}.
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
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte
	  * @param bool [$json] Verdadero para obtener los resultados en forma JSON y no objetos. En caso de no presentare este parametro se tomara el valor default de false.
	  **/
	public static final function byRange( $detalle_corteA , $detalle_corteB , $json = false)
	{
		$sql = "SELECT * from detalle_corte WHERE ("; 
		$val = array();
		if( (($a = $detalle_corteA->getNumCorte()) != NULL) & ( ($b = $detalle_corteB->getNumCorte()) != NULL) ){
				$sql .= " num_corte >= ? AND num_corte <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " num_corte = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_corteA->getNombre()) != NULL) & ( ($b = $detalle_corteB->getNombre()) != NULL) ){
				$sql .= " nombre >= ? AND nombre <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " nombre = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_corteA->getTotal()) != NULL) & ( ($b = $detalle_corteB->getTotal()) != NULL) ){
				$sql .= " total >= ? AND total <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_corteA->getDeben()) != NULL) & ( ($b = $detalle_corteB->getDeben()) != NULL) ){
				$sql .= " deben >= ? AND deben <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " deben = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		if($json === false){
			$ar = array();
			foreach ($rs as $foo) {
    			array_push( $ar, new DetalleCorte($foo));
			}
			return $ar;
		}else{
			$allData = '[';
			foreach ($rs as $foo) {
    			$allData .= new DetalleCorte($foo) . ',';
			}
    		$allData = substr($allData, 0 , -1) . ']';
			return $allData;
		}
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleCorte suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param DetalleCorte [$detalle_corte] El objeto de tipo DetalleCorte a eliminar
	  **/
	public static final function delete( &$detalle_corte )
	{
		if(self::getByPK($detalle_corte->getNumCorte(), $detalle_corte->getNombre()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_corte WHERE  num_corte = ? AND nombre = ?;";
		$params = array( $detalle_corte->getNumCorte(), $detalle_corte->getNombre() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
