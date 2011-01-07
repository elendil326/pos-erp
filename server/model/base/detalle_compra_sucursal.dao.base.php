<?php
/** DetalleCompraSucursal Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link DetalleCompraSucursal }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class DetalleCompraSucursalDAOBase extends DAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleCompraSucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param DetalleCompraSucursal [$detalle_compra_sucursal] El objeto de tipo DetalleCompraSucursal
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$detalle_compra_sucursal )
	{
		if(  self::getByPK(  $detalle_compra_sucursal->getIdCompra() , $detalle_compra_sucursal->getIdProducto() ) !== NULL )
		{
			try{ return DetalleCompraSucursalDAOBase::update( $detalle_compra_sucursal) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return DetalleCompraSucursalDAOBase::create( $detalle_compra_sucursal) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link DetalleCompraSucursal} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleCompraSucursal} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleCompraSucursal Un objeto del tipo {@link DetalleCompraSucursal}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_compra, $id_producto )
	{
		$sql = "SELECT * FROM detalle_compra_sucursal WHERE (id_compra = ? AND id_producto = ? ) LIMIT 1;";
		$params = array(  $id_compra, $id_producto );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new DetalleCompraSucursal( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleCompraSucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link DetalleCompraSucursal}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from detalle_compra_sucursal";
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
    		array_push( $allData, new DetalleCompraSucursal($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraSucursal} de la base de datos. 
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
	  * @param DetalleCompraSucursal [$detalle_compra_sucursal] El objeto de tipo DetalleCompraSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $detalle_compra_sucursal , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from detalle_compra_sucursal WHERE ("; 
		$val = array();
		if( $detalle_compra_sucursal->getIdCompra() != NULL){
			$sql .= " id_compra = ? AND";
			array_push( $val, $detalle_compra_sucursal->getIdCompra() );
		}

		if( $detalle_compra_sucursal->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $detalle_compra_sucursal->getIdProducto() );
		}

		if( $detalle_compra_sucursal->getCantidad() != NULL){
			$sql .= " cantidad = ? AND";
			array_push( $val, $detalle_compra_sucursal->getCantidad() );
		}

		if( $detalle_compra_sucursal->getPrecio() != NULL){
			$sql .= " precio = ? AND";
			array_push( $val, $detalle_compra_sucursal->getPrecio() );
		}

		if( $detalle_compra_sucursal->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $detalle_compra_sucursal->getDescuento() );
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
    		array_push( $ar, new DetalleCompraSucursal($foo));
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
	  * @param DetalleCompraSucursal [$detalle_compra_sucursal] El objeto de tipo DetalleCompraSucursal a actualizar.
	  **/
	private static final function update( $detalle_compra_sucursal )
	{
		$sql = "UPDATE detalle_compra_sucursal SET  cantidad = ?, precio = ?, descuento = ? WHERE  id_compra = ? AND id_producto = ?;";
		$params = array( 
			$detalle_compra_sucursal->getCantidad(), 
			$detalle_compra_sucursal->getPrecio(), 
			$detalle_compra_sucursal->getDescuento(), 
			$detalle_compra_sucursal->getIdCompra(),$detalle_compra_sucursal->getIdProducto(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleCompraSucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleCompraSucursal dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleCompraSucursal [$detalle_compra_sucursal] El objeto de tipo DetalleCompraSucursal a crear.
	  **/
	private static final function create( &$detalle_compra_sucursal )
	{
		$sql = "INSERT INTO detalle_compra_sucursal ( id_compra, id_producto, cantidad, precio, descuento ) VALUES ( ?, ?, ?, ?, ?);";
		$params = array( 
			$detalle_compra_sucursal->getIdCompra(), 
			$detalle_compra_sucursal->getIdProducto(), 
			$detalle_compra_sucursal->getCantidad(), 
			$detalle_compra_sucursal->getPrecio(), 
			$detalle_compra_sucursal->getDescuento(), 
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
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraSucursal} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleCompraSucursal}.
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
	  * @param DetalleCompraSucursal [$detalle_compra_sucursal] El objeto de tipo DetalleCompraSucursal
	  * @param DetalleCompraSucursal [$detalle_compra_sucursal] El objeto de tipo DetalleCompraSucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $detalle_compra_sucursalA , $detalle_compra_sucursalB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from detalle_compra_sucursal WHERE ("; 
		$val = array();
		if( (($a = $detalle_compra_sucursalA->getIdCompra()) != NULL) & ( ($b = $detalle_compra_sucursalB->getIdCompra()) != NULL) ){
				$sql .= " id_compra >= ? AND id_compra <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_compra = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_compra_sucursalA->getIdProducto()) != NULL) & ( ($b = $detalle_compra_sucursalB->getIdProducto()) != NULL) ){
				$sql .= " id_producto >= ? AND id_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_compra_sucursalA->getCantidad()) != NULL) & ( ($b = $detalle_compra_sucursalB->getCantidad()) != NULL) ){
				$sql .= " cantidad >= ? AND cantidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " cantidad = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_compra_sucursalA->getPrecio()) != NULL) & ( ($b = $detalle_compra_sucursalB->getPrecio()) != NULL) ){
				$sql .= " precio >= ? AND precio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " precio = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $detalle_compra_sucursalA->getDescuento()) != NULL) & ( ($b = $detalle_compra_sucursalB->getDescuento()) != NULL) ){
				$sql .= " descuento >= ? AND descuento <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descuento = ? AND"; 
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
    		array_push( $ar, new DetalleCompraSucursal($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleCompraSucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param DetalleCompraSucursal [$detalle_compra_sucursal] El objeto de tipo DetalleCompraSucursal a eliminar
	  **/
	public static final function delete( &$detalle_compra_sucursal )
	{
		if(self::getByPK($detalle_compra_sucursal->getIdCompra(), $detalle_compra_sucursal->getIdProducto()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_compra_sucursal WHERE  id_compra = ? AND id_producto = ?;";
		$params = array( $detalle_compra_sucursal->getIdCompra(), $detalle_compra_sucursal->getIdProducto() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
