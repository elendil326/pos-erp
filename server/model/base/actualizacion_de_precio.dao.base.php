<?php
/** ActualizacionDePrecio Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link ActualizacionDePrecio }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class ActualizacionDePrecioDAOBase extends DAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link ActualizacionDePrecio} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$actualizacion_de_precio )
	{
		if(  self::getByPK(  $actualizacion_de_precio->getIdActualizacion() ) !== NULL )
		{
			try{ return ActualizacionDePrecioDAOBase::update( $actualizacion_de_precio) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return ActualizacionDePrecioDAOBase::create( $actualizacion_de_precio) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link ActualizacionDePrecio} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link ActualizacionDePrecio} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link ActualizacionDePrecio Un objeto del tipo {@link ActualizacionDePrecio}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_actualizacion )
	{
		$sql = "SELECT * FROM actualizacion_de_precio WHERE (id_actualizacion = ? ) LIMIT 1;";
		$params = array(  $id_actualizacion );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new ActualizacionDePrecio( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ActualizacionDePrecio}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link ActualizacionDePrecio}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from actualizacion_de_precio";
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
    		array_push( $allData, new ActualizacionDePrecio($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ActualizacionDePrecio} de la base de datos. 
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
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $actualizacion_de_precio , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from actualizacion_de_precio WHERE ("; 
		$val = array();
		if( $actualizacion_de_precio->getIdActualizacion() != NULL){
			$sql .= " id_actualizacion = ? AND";
			array_push( $val, $actualizacion_de_precio->getIdActualizacion() );
		}

		if( $actualizacion_de_precio->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $actualizacion_de_precio->getIdProducto() );
		}

		if( $actualizacion_de_precio->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $actualizacion_de_precio->getIdUsuario() );
		}

		if( $actualizacion_de_precio->getPrecioVenta() != NULL){
			$sql .= " precio_venta = ? AND";
			array_push( $val, $actualizacion_de_precio->getPrecioVenta() );
		}

		if( $actualizacion_de_precio->getPrecioIntersucursal() != NULL){
			$sql .= " precio_intersucursal = ? AND";
			array_push( $val, $actualizacion_de_precio->getPrecioIntersucursal() );
		}

		if( $actualizacion_de_precio->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $actualizacion_de_precio->getFecha() );
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
    		array_push( $ar, new ActualizacionDePrecio($foo));
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
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio a actualizar.
	  **/
	private static final function update( $actualizacion_de_precio )
	{
		$sql = "UPDATE actualizacion_de_precio SET  id_producto = ?, id_usuario = ?, precio_venta = ?, precio_intersucursal = ?, fecha = ? WHERE  id_actualizacion = ?;";
		$params = array( 
			$actualizacion_de_precio->getIdProducto(), 
			$actualizacion_de_precio->getIdUsuario(), 
			$actualizacion_de_precio->getPrecioVenta(), 
			$actualizacion_de_precio->getPrecioIntersucursal(), 
			$actualizacion_de_precio->getFecha(), 
			$actualizacion_de_precio->getIdActualizacion(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto ActualizacionDePrecio suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto ActualizacionDePrecio dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio a crear.
	  **/
	private static final function create( &$actualizacion_de_precio )
	{
		$sql = "INSERT INTO actualizacion_de_precio ( id_actualizacion, id_producto, id_usuario, precio_venta, precio_intersucursal, fecha ) VALUES ( ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$actualizacion_de_precio->getIdActualizacion(), 
			$actualizacion_de_precio->getIdProducto(), 
			$actualizacion_de_precio->getIdUsuario(), 
			$actualizacion_de_precio->getPrecioVenta(), 
			$actualizacion_de_precio->getPrecioIntersucursal(), 
			$actualizacion_de_precio->getFecha(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $actualizacion_de_precio->setIdActualizacion( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ActualizacionDePrecio} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link ActualizacionDePrecio}.
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
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $actualizacion_de_precioA , $actualizacion_de_precioB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from actualizacion_de_precio WHERE ("; 
		$val = array();
		if( (($a = $actualizacion_de_precioA->getIdActualizacion()) != NULL) & ( ($b = $actualizacion_de_precioB->getIdActualizacion()) != NULL) ){
				$sql .= " id_actualizacion >= ? AND id_actualizacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_actualizacion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $actualizacion_de_precioA->getIdProducto()) != NULL) & ( ($b = $actualizacion_de_precioB->getIdProducto()) != NULL) ){
				$sql .= " id_producto >= ? AND id_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $actualizacion_de_precioA->getIdUsuario()) != NULL) & ( ($b = $actualizacion_de_precioB->getIdUsuario()) != NULL) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $actualizacion_de_precioA->getPrecioVenta()) != NULL) & ( ($b = $actualizacion_de_precioB->getPrecioVenta()) != NULL) ){
				$sql .= " precio_venta >= ? AND precio_venta <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " precio_venta = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $actualizacion_de_precioA->getPrecioIntersucursal()) != NULL) & ( ($b = $actualizacion_de_precioB->getPrecioIntersucursal()) != NULL) ){
				$sql .= " precio_intersucursal >= ? AND precio_intersucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " precio_intersucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $actualizacion_de_precioA->getFecha()) != NULL) & ( ($b = $actualizacion_de_precioB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
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
    		array_push( $ar, new ActualizacionDePrecio($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto ActualizacionDePrecio suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio a eliminar
	  **/
	public static final function delete( &$actualizacion_de_precio )
	{
		if(self::getByPK($actualizacion_de_precio->getIdActualizacion()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM actualizacion_de_precio WHERE  id_actualizacion = ?;";
		$params = array( $actualizacion_de_precio->getIdActualizacion() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
