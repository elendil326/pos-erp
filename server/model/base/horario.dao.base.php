<?php
/** Horario Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Horario }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class HorarioDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Horario} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Horario [$horario] El objeto de tipo Horario
	  * @return Un entero mayor o igual a cero denotando las filas afectadas, o un string con el error si es que hubo alguno.
	  **/
	public static final function save( &$horario )
	{
		if( self::getByPK(  $horario->getIdUsuario() , $horario->getFecha() ) === NULL )
		{
			try{ return HorarioDAOBase::create( $horario) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return HorarioDAOBase::update( $horario) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Horario} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Horario} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Horario}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_usuario, $fecha )
	{
		$sql = "SELECT * FROM horario WHERE (id_usuario = ? AND fecha = ? ) LIMIT 1;";
		$params = array(  $id_usuario, $fecha );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new Horario( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Horario}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Horario}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from horario";
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
    		array_push( $allData, new Horario($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Horario} de la base de datos. 
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
	  * @param Horario [$horario] El objeto de tipo Horario
	  * @param bool [$json] Verdadero para obtener los resultados en forma JSON y no objetos. En caso de no presentare este parametro se tomara el valor default de false.
	  **/
	public static final function search( $horario , $json = false)
	{
		$sql = "SELECT * from horario WHERE ("; 
		$val = array();
		if( $horario->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $horario->getIdUsuario() );
		}

		if( $horario->getAccion() != NULL){
			$sql .= " accion = ? AND";
			array_push( $val, $horario->getAccion() );
		}

		if( $horario->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $horario->getFecha() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		if($json === false){
			$ar = array();
			foreach ($rs as $foo) {
    			array_push( $ar, new Horario($foo));
			}
			return $ar;
		}else{
			$allData = '[';
			foreach ($rs as $foo) {
    			$allData .= new Horario($foo) . ',';
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
	  * @param Horario [$horario] El objeto de tipo Horario a actualizar.
	  **/
	private static final function update( $horario )
	{
		$sql = "UPDATE horario SET  accion = ? WHERE  id_usuario = ? AND fecha = ?;";
		$params = array( 
			$horario->getAccion(), 
			$horario->getIdUsuario(),$horario->getFecha(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Horario suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Horario dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Horario [$horario] El objeto de tipo Horario a crear.
	  **/
	private static final function create( &$horario )
	{
		$sql = "INSERT INTO horario ( id_usuario, accion, fecha ) VALUES ( ?, ?, ?);";
		$params = array( 
			$horario->getIdUsuario(), 
			$horario->getAccion(), 
			$horario->getFecha(), 
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
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Horario} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Horario}.
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
	  * @param Horario [$horario] El objeto de tipo Horario
	  * @param Horario [$horario] El objeto de tipo Horario
	  * @param bool [$json] Verdadero para obtener los resultados en forma JSON y no objetos. En caso de no presentare este parametro se tomara el valor default de false.
	  **/
	public static final function byRange( $horarioA , $horarioB , $json = false)
	{
		$sql = "SELECT * from horario WHERE ("; 
		$val = array();
		if( (($a = $horarioA->getIdUsuario()) != NULL) & ( ($b = $horarioB->getIdUsuario()) != NULL) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $horarioA->getAccion()) != NULL) & ( ($b = $horarioB->getAccion()) != NULL) ){
				$sql .= " accion >= ? AND accion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " accion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $horarioA->getFecha()) != NULL) & ( ($b = $horarioB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		if($json === false){
			$ar = array();
			foreach ($rs as $foo) {
    			array_push( $ar, new Horario($foo));
			}
			return $ar;
		}else{
			$allData = '[';
			foreach ($rs as $foo) {
    			$allData .= new Horario($foo) . ',';
			}
    		$allData = substr($allData, 0 , -1) . ']';
			return $allData;
		}
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Horario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Horario [$horario] El objeto de tipo Horario a eliminar
	  **/
	public static final function delete( &$horario )
	{
		if(self::getByPK($horario->getIdUsuario(), $horario->getFecha()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM horario WHERE  id_usuario = ? AND fecha = ?;";
		$params = array( $horario->getIdUsuario(), $horario->getFecha() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
