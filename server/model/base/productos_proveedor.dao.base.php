<?php
/** ProductosProveedor Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link ProductosProveedor }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class ProductosProveedorDAOBase extends TablaDAO
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
	  * @param ProductosProveedor [$productos_proveedor] El objeto de tipo ProductosProveedor
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$productos_proveedor )
	{
		if( self::getByPK(  $productos_proveedor->getIdProducto() ) === NULL )
		{
			return ProductosProveedorDAOBase::create( $productos_proveedor) ;
		}else{
			return ProductosProveedorDAOBase::update( $productos_proveedor) ;
		}
	}


	/**
	  *	Obtener {@link ProductosProveedor} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link ProductosProveedor} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link ProductosProveedor}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_producto )
	{
		$sql = "SELECT * FROM productos_proveedor WHERE (id_producto = ?) LIMIT 1;";
		$params = array(  $id_producto );
		global $db;
		$rs = $db->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new ProductosProveedor( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ProductosProveedor}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link ProductosProveedor}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from productos_proveedor ;";
		global $db;
		$rs = $db->Execute($sql);
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
	  **/
	public static final function search( $productos_proveedor )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $cliente->getIdProducto() );
		}

		if($cliente->getClaveProducto() != NULL){
			$sql .= " clave_producto = ? AND";
			array_push( $val, $cliente->getClaveProducto() );
		}

		if($cliente->getIdProveedor() != NULL){
			$sql .= " id_proveedor = ? AND";
			array_push( $val, $cliente->getIdProveedor() );
		}

		if($cliente->getIdInventario() != NULL){
			$sql .= " id_inventario = ? AND";
			array_push( $val, $cliente->getIdInventario() );
		}

		if($cliente->getDescripcion() != NULL){
			$sql .= " descripcion = ? AND";
			array_push( $val, $cliente->getDescripcion() );
		}

		if($cliente->getPrecio() != NULL){
			$sql .= " precio = ? AND";
			array_push( $val, $cliente->getPrecio() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new ProductosProveedor($foo));
		}
		return $allData;
	}


	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas
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
		global $db;
		$db->Execute($sql, $params);
		return $db->Affected_Rows();
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
	  * @return Filas afectadas
	  * @param ProductosProveedor [$productos_proveedor] El objeto de tipo ProductosProveedor a crear.
	  **/
	private static final function create( &$productos_proveedor )
	{
		$sql = "INSERT INTO productos_proveedor ( clave_producto, id_proveedor, id_inventario, descripcion, precio ) VALUES ( ?, ?, ?, ?, ?);";
		$params = array( 
			$productos_proveedor->getClaveProducto(), 
			$productos_proveedor->getIdProveedor(), 
			$productos_proveedor->getIdInventario(), 
			$productos_proveedor->getDescripcion(), 
			$productos_proveedor->getPrecio(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$ar = $db->Affected_Rows();
		if($ar == 0) return 0;
		$productos_proveedor->setIdProducto( $db->Insert_ID() );
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
		global $db;

		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


}
