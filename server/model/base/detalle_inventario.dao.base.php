<?php
/** DetalleInventario Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link DetalleInventario }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class DetalleInventarioDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleInventario} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$detalle_inventario )
	{
		if( self::getByPK(  $detalle_inventario->getIdProducto() , $detalle_inventario->getIdSucursal() ) === NULL )
		{
			return DetalleInventarioDAOBase::create( $detalle_inventario) ;
		}else{
			return DetalleInventarioDAOBase::update( $detalle_inventario) ;
		}
	}


	/**
	  *	Obtener {@link DetalleInventario} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleInventario} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link DetalleInventario}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_producto, $id_sucursal )
	{
		$sql = "SELECT * FROM detalle_inventario WHERE (id_producto = ?,id_sucursal = ?) LIMIT 1;";
		$params = array(  $id_producto, $id_sucursal );
		global $db;
		$rs = $db->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new DetalleInventario( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleInventario}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link DetalleInventario}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from detalle_inventario ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new DetalleInventario($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleInventario} de la base de datos. 
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
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario
	  **/
	public static final function search( $detalle_inventario )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $cliente->getIdProducto() );
		}

		if($cliente->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $cliente->getIdSucursal() );
		}

		if($cliente->getPrecioVenta() != NULL){
			$sql .= " precio_venta = ? AND";
			array_push( $val, $cliente->getPrecioVenta() );
		}

		if($cliente->getMin() != NULL){
			$sql .= " min = ? AND";
			array_push( $val, $cliente->getMin() );
		}

		if($cliente->getExistencias() != NULL){
			$sql .= " existencias = ? AND";
			array_push( $val, $cliente->getExistencias() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new DetalleInventario($foo));
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
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario a actualizar.
	  **/
	private static final function update( $detalle_inventario )
	{
		$sql = "UPDATE detalle_inventario SET  precio_venta = ?, min = ?, existencias = ? WHERE  id_producto = ? AND id_sucursal = ?;";
		$params = array( 
			$detalle_inventario->getPrecioVenta(), 
			$detalle_inventario->getMin(), 
			$detalle_inventario->getExistencias(), 
			$detalle_inventario->getIdProducto(),$detalle_inventario->getIdSucursal(), );
		global $db;
		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleInventario suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleInventario dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario a crear.
	  **/
	private static final function create( &$detalle_inventario )
	{
		$sql = "INSERT INTO detalle_inventario ( id_producto, id_sucursal, precio_venta, min, existencias ) VALUES ( ?, ?, ?, ?, ?);";
		$params = array( 
			$detalle_inventario->getIdProducto(), 
			$detalle_inventario->getIdSucursal(), 
			$detalle_inventario->getPrecioVenta(), 
			$detalle_inventario->getMin(), 
			$detalle_inventario->getExistencias(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$ar = $db->Affected_Rows();
		if($ar == 0) return 0;
		
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleInventario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario a eliminar
	  **/
	public static final function delete( &$detalle_inventario )
	{
		if(self::getByPK($detalle_inventario->getIdProducto(), $detalle_inventario->getIdSucursal()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_inventario WHERE  id_producto = ? AND id_sucursal = ?;";
		$params = array( $detalle_inventario->getIdProducto(), $detalle_inventario->getIdSucursal() );
		global $db;

		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


}
