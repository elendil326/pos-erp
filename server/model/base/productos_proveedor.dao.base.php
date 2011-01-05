<?php
/** ProductosProveedor Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link ProductosProveedor }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class ProductosProveedorDAOBase extends DAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link ProductosProveedor} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param ProductosProveedor [$productos_proveedor] El objeto de tipo ProductosProveedor
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$productos_proveedor )
	{
		if( self::getByPK(  $productos_proveedor->getIdProducto() ) === NULL )
		{
			try{ return ProductosProveedorDAOBase::create( $productos_proveedor) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return ProductosProveedorDAOBase::update( $productos_proveedor) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link ProductosProveedor} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link ProductosProveedor} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link ProductosProveedor Un objeto del tipo {@link ProductosProveedor}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_producto )
	{
		$sql = "SELECT * FROM productos_proveedor WHERE (id_producto = ? ) LIMIT 1;";
		$params = array(  $id_producto );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new ProductosProveedor( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ProductosProveedor}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link ProductosProveedor}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from productos_proveedor";
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
    		array_push( $allData, new ProductosProveedor($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ProductosProveedor} de la base de datos. 
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
	  * @param ProductosProveedor [$productos_proveedor] El objeto de tipo ProductosProveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $productos_proveedor , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from productos_proveedor WHERE ("; 
		$val = array();
		if( $productos_proveedor->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $productos_proveedor->getIdProducto() );
		}

		if( $productos_proveedor->getClaveProducto() != NULL){
			$sql .= " clave_producto = ? AND";
			array_push( $val, $productos_proveedor->getClaveProducto() );
		}

		if( $productos_proveedor->getIdProveedor() != NULL){
			$sql .= " id_proveedor = ? AND";
			array_push( $val, $productos_proveedor->getIdProveedor() );
		}

		if( $productos_proveedor->getIdInventario() != NULL){
			$sql .= " id_inventario = ? AND";
			array_push( $val, $productos_proveedor->getIdInventario() );
		}

		if( $productos_proveedor->getDescripcion() != NULL){
			$sql .= " descripcion = ? AND";
			array_push( $val, $productos_proveedor->getDescripcion() );
		}

		if( $productos_proveedor->getPrecio() != NULL){
			$sql .= " precio = ? AND";
			array_push( $val, $productos_proveedor->getPrecio() );
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
    		array_push( $ar, new ProductosProveedor($foo));
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
	  * @param ProductosProveedor [$productos_proveedor] El objeto de tipo ProductosProveedor a actualizar.
	  **/
	private static final function update( $productos_proveedor )
	{
		$sql = "UPDATE productos_proveedor SET  clave_producto = ?, id_proveedor = ?, id_inventario = ?, descripcion = ?, precio = ? WHERE  id_producto = ?;";
		$params = array( 
			$productos_proveedor->getClaveProducto(), 
			$productos_proveedor->getIdProveedor(), 
			$productos_proveedor->getIdInventario(), 
			$productos_proveedor->getDescripcion(), 
			$productos_proveedor->getPrecio(), 
			$productos_proveedor->getIdProducto(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto ProductosProveedor suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto ProductosProveedor dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param ProductosProveedor [$productos_proveedor] El objeto de tipo ProductosProveedor a crear.
	  **/
	private static final function create( &$productos_proveedor )
	{
		$sql = "INSERT INTO productos_proveedor ( id_producto, clave_producto, id_proveedor, id_inventario, descripcion, precio ) VALUES ( ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$productos_proveedor->getIdProducto(), 
			$productos_proveedor->getClaveProducto(), 
			$productos_proveedor->getIdProveedor(), 
			$productos_proveedor->getIdInventario(), 
			$productos_proveedor->getDescripcion(), 
			$productos_proveedor->getPrecio(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		$productos_proveedor->setIdProducto( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ProductosProveedor} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link ProductosProveedor}.
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
	  * @param ProductosProveedor [$productos_proveedor] El objeto de tipo ProductosProveedor
	  * @param ProductosProveedor [$productos_proveedor] El objeto de tipo ProductosProveedor
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $productos_proveedorA , $productos_proveedorB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from productos_proveedor WHERE ("; 
		$val = array();
		if( (($a = $productos_proveedorA->getIdProducto()) != NULL) & ( ($b = $productos_proveedorB->getIdProducto()) != NULL) ){
				$sql .= " id_producto >= ? AND id_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productos_proveedorA->getClaveProducto()) != NULL) & ( ($b = $productos_proveedorB->getClaveProducto()) != NULL) ){
				$sql .= " clave_producto >= ? AND clave_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " clave_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productos_proveedorA->getIdProveedor()) != NULL) & ( ($b = $productos_proveedorB->getIdProveedor()) != NULL) ){
				$sql .= " id_proveedor >= ? AND id_proveedor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_proveedor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productos_proveedorA->getIdInventario()) != NULL) & ( ($b = $productos_proveedorB->getIdInventario()) != NULL) ){
				$sql .= " id_inventario >= ? AND id_inventario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_inventario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productos_proveedorA->getDescripcion()) != NULL) & ( ($b = $productos_proveedorB->getDescripcion()) != NULL) ){
				$sql .= " descripcion >= ? AND descripcion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descripcion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productos_proveedorA->getPrecio()) != NULL) & ( ($b = $productos_proveedorB->getPrecio()) != NULL) ){
				$sql .= " precio >= ? AND precio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " precio = ? AND"; 
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
    		array_push( $ar, new ProductosProveedor($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto ProductosProveedor suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param ProductosProveedor [$productos_proveedor] El objeto de tipo ProductosProveedor a eliminar
	  **/
	public static final function delete( &$productos_proveedor )
	{
		if(self::getByPK($productos_proveedor->getIdProducto()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM productos_proveedor WHERE  id_producto = ?;";
		$params = array( $productos_proveedor->getIdProducto() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
