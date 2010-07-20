<?php
/* DetalleInventario Data Access Object (DAO).
 * Esta clase contiene toda la manipulación de bases de datos que se necesita para 
 * almacenar de forma permanente y recuperar instancias de objetos DetalleInventario. 
 */

class DetalleInventarioDAOBase
{

	private function __construct()
	{
		//prevent instatiation of this class by marking it private
	}


	/**
	  *	Este método guarda el estado actual de objeto DetalleInventario en la base de datos. La llave 
	  *	primaria indicará qué instancia va a ser actualizado en base de datos. Si la llave primara 
	  *	no esta definicda en el objeto, entonces save() creara una nueva fila.
	  **/
	final public function save( &$detalle_inventario )
	{
		if(  $detalle_inventario->getIdProducto() && $detalle_inventario->getIdSucursal()  )
		{
			return DetalleInventarioDAOBase::update( $detalle_inventario) ;
		}else{
			return DetalleInventarioDAOBase::create( $detalle_inventario) ;
		}
	}


	/**
	  * getObject-method. This will create and load valueObject contents from database 
	  * using given Primary-Key as identifier. This method is just a convenience method 
	  * for the real load-method which accepts the valueObject as a parameter. Returned
	  * valueObject will be created using the createValueObject() method.
	  **/
	final public function getByPK(  $id_producto, $id_sucursal )
	{
		$sql = "SELECT * FROM detalle_inventario WHERE (id_producto = ?,id_sucursal = ?) LIMIT 1;";
		$params = array(  $id_producto, $id_sucursal );
		global $db;
		$rs = $db->GetRow($sql, $params);
		return new DetalleInventario( $rs );
	}


	/**
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo DetalleInventario. Tenga en cuenta que este método
	  * se consumen enormes cantidades de recursos si la tabla tiene muchas de las filas. 
	  * Esto sólo debe usarse cuando las tablas de destino tienen sólo pequeñas cantidades de datos
	  **/
	final public function getAll( )
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
	 * searchMatching-Method. This method provides searching capability to 
	 * get matching valueObjects from database. It works by searching all 
	 * objects that match permanent instance variables of given object.
	 * Upper layer should use this by setting some parameters in valueObject
	 * and then  call searchMatching. The result will be 0-N objects in vector, 
	 * all matching those criteria you specified. Those instance-variables that
	 * have NULL values are excluded in search-criteria.
	 *
	 * @param valueObject  This parameter contains the class instance where search will be based.
	 *                     Primary-key field should not be set.
	 */
	final public function search( $detalle_inventario )
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
	  * Este método es un método de ayuda para uso interno. Se ejecutará todas las manipulaciones 
	  * base de datos que va a cambiar la información en tablas. consultas SELECT no se ejecutará
	  * aquí, sin embargo. El valor de retorno indica cuántas filas se vieron afectados. 
	  **/
	final public function update( $detalle_inventario )
	{
		$sql = "UPDATE detalle_inventario SET  precio_venta = ?, min = ?, existencias = ? WHERE  id_producto = ? AND id_sucursal = ?;";
		$params = array( 
			$detalle_inventario->getPrecioVenta(), 
			$detalle_inventario->getMin(), 
			$detalle_inventario->getExistencias(), 
			$detalle_inventario->getIdProducto(),$detalle_inventario->getIdSucursal(), );
		global $db;
		$db->Execute($sql, $params);
	}


	/**
	  * Este metodo creará una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleInventario suministrado. Asegúrese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Después del comando INSERT, este método asignara la clave 
	  * primaria generada en el objeto DetalleInventario.
	  **/
	final public function create( &$detalle_inventario )
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
		
	}


	/**
	  * Este método se eliminará la información de base de datos identificados por la clave primaria
	  * en el objeto DetalleInventario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). Restaurar sólo se puede hacer usando el método create(), 
	  * pero el objeto resultante tendrá una diferente clave primaria de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente, NotFoundException será lanzada.
	  **/
	final public function delete( &$detalle_inventario )
	{
		$sql = "DELETE FROM detalle_inventario WHERE  id_producto = ? AND id_sucursal = ?;";

		$params = array( 
			$detalle_inventario->getIdProducto(),$detalle_inventario->getIdSucursal(), );

		global $db;

		$db->Execute($sql, $params);
		$detalle_inventario = NULL;
	}


}
