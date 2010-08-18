<?php
/** Sucursal Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Sucursal }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class SucursalDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Sucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal
	  * @return Un entero mayor o igual a cero denotando las filas afectadas, o un string con el error si es que hubo alguno.
	  **/
	public static final function save( &$sucursal )
	{
		if( self::getByPK(  $sucursal->getIdSucursal() ) === NULL )
		{
			try{ return SucursalDAOBase::create( $sucursal) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return SucursalDAOBase::update( $sucursal) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Sucursal} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Sucursal} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Sucursal}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_sucursal )
	{
		$sql = "SELECT * FROM sucursal WHERE (id_sucursal = ? ) LIMIT 1;";
		$params = array(  $id_sucursal );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new Sucursal( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Sucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Sucursal}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from sucursal";
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
    		array_push( $allData, new Sucursal($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Sucursal} de la base de datos. 
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
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal
	  * @param bool [$json] Verdadero para obtener los resultados en forma JSON y no objetos. En caso de no presentare este parametro se tomara el valor default de false.
	  **/
	public static final function search( $sucursal , $json = false)
	{
		$sql = "SELECT * from sucursal WHERE ("; 
		$val = array();
		if( $sucursal->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $sucursal->getIdSucursal() );
		}

		if( $sucursal->getDescripcion() != NULL){
			$sql .= " descripcion = ? AND";
			array_push( $val, $sucursal->getDescripcion() );
		}

		if( $sucursal->getDireccion() != NULL){
			$sql .= " direccion = ? AND";
			array_push( $val, $sucursal->getDireccion() );
		}

		if( $sucursal->getToken() != NULL){
			$sql .= " token = ? AND";
			array_push( $val, $sucursal->getToken() );
		}

		if( $sucursal->getLetrasFactura() != NULL){
			$sql .= " letras_factura = ? AND";
			array_push( $val, $sucursal->getLetrasFactura() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		if($json === false){
			$ar = array();
			foreach ($rs as $foo) {
    			array_push( $ar, new Sucursal($foo));
			}
			return $ar;
		}else{
			$allData = '[';
			foreach ($rs as $foo) {
    			$allData .= new Sucursal($foo) . ',';
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
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal a actualizar.
	  **/
	private static final function update( $sucursal )
	{
		$sql = "UPDATE sucursal SET  descripcion = ?, direccion = ?, token = ?, letras_factura = ? WHERE  id_sucursal = ?;";
		$params = array( 
			$sucursal->getDescripcion(), 
			$sucursal->getDireccion(), 
			$sucursal->getToken(), 
			$sucursal->getLetrasFactura(), 
			$sucursal->getIdSucursal(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Sucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Sucursal dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal a crear.
	  **/
	private static final function create( &$sucursal )
	{
		$sql = "INSERT INTO sucursal ( id_sucursal, descripcion, direccion, token, letras_factura ) VALUES ( ?, ?, ?, ?, ?);";
		$params = array( 
			$sucursal->getIdSucursal(), 
			$sucursal->getDescripcion(), 
			$sucursal->getDireccion(), 
			$sucursal->getToken(), 
			$sucursal->getLetrasFactura(), 
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
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Sucursal} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Sucursal}.
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
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal
	  * @param bool [$json] Verdadero para obtener los resultados en forma JSON y no objetos. En caso de no presentare este parametro se tomara el valor default de false.
	  **/
	public static final function byRange( $sucursalA , $sucursalB , $json = false)
	{
		$sql = "SELECT * from sucursal WHERE ("; 
		$val = array();
		if( (($a = $sucursalA->getIdSucursal()) != NULL) & ( ($b = $sucursalB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $sucursalA->getDescripcion()) != NULL) & ( ($b = $sucursalB->getDescripcion()) != NULL) ){
				$sql .= " descripcion >= ? AND descripcion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descripcion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $sucursalA->getDireccion()) != NULL) & ( ($b = $sucursalB->getDireccion()) != NULL) ){
				$sql .= " direccion >= ? AND direccion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " direccion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $sucursalA->getToken()) != NULL) & ( ($b = $sucursalB->getToken()) != NULL) ){
				$sql .= " token >= ? AND token <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " token = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $sucursalA->getLetrasFactura()) != NULL) & ( ($b = $sucursalB->getLetrasFactura()) != NULL) ){
				$sql .= " letras_factura >= ? AND letras_factura <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " letras_factura = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		if($json === false){
			$ar = array();
			foreach ($rs as $foo) {
    			array_push( $ar, new Sucursal($foo));
			}
			return $ar;
		}else{
			$allData = '[';
			foreach ($rs as $foo) {
    			$allData .= new Sucursal($foo) . ',';
			}
    		$allData = substr($allData, 0 , -1) . ']';
			return $allData;
		}
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Sucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal a eliminar
	  **/
	public static final function delete( &$sucursal )
	{
		if(self::getByPK($sucursal->getIdSucursal()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM sucursal WHERE  id_sucursal = ?;";
		$params = array( $sucursal->getIdSucursal() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
