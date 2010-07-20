<?php
/* Ventas Data Access Object (DAO).
 * Esta clase contiene toda la manipulación de bases de datos que se necesita para 
 * almacenar de forma permanente y recuperar instancias de objetos Ventas. 
 */

class VentasDAOBase
{

	private function __construct()
	{
		//prevent instatiation of this class by marking it private
	}


	/**
	  *	Este método guarda el estado actual de objeto Ventas en la base de datos. La llave 
	  *	primaria indicará qué instancia va a ser actualizado en base de datos. Si la llave primara 
	  *	no esta definicda en el objeto, entonces save() creara una nueva fila.
	  **/
	final public function save( &$ventas )
	{
		if(  $ventas->getIdVenta()  )
		{
			return VentasDAOBase::update( $ventas) ;
		}else{
			return VentasDAOBase::create( $ventas) ;
		}
	}


	/**
	  * getObject-method. This will create and load valueObject contents from database 
	  * using given Primary-Key as identifier. This method is just a convenience method 
	  * for the real load-method which accepts the valueObject as a parameter. Returned
	  * valueObject will be created using the createValueObject() method.
	  **/
	final public function getByPK(  $id_venta )
	{
		$sql = "SELECT * FROM ventas WHERE (id_venta = ?) LIMIT 1;";
		$params = array(  $id_venta );
		global $db;
		$rs = $db->GetRow($sql, $params);
		return new Ventas( $rs );
	}


	/**
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo Ventas. Tenga en cuenta que este método
	  * se consumen enormes cantidades de recursos si la tabla tiene muchas de las filas. 
	  * Esto sólo debe usarse cuando las tablas de destino tienen sólo pequeñas cantidades de datos
	  **/
	final public function getAll( )
	{
		$sql = "SELECT * from ventas ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Ventas($foo));
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
	final public function search( $ventas )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdVenta() != NULL){
			$sql .= " id_venta = ? AND";
			array_push( $val, $cliente->getIdVenta() );
		}

		if($cliente->getIdCliente() != NULL){
			$sql .= " id_cliente = ? AND";
			array_push( $val, $cliente->getIdCliente() );
		}

		if($cliente->getTipoVenta() != NULL){
			$sql .= " tipo_venta = ? AND";
			array_push( $val, $cliente->getTipoVenta() );
		}

		if($cliente->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $cliente->getFecha() );
		}

		if($cliente->getSubtotal() != NULL){
			$sql .= " subtotal = ? AND";
			array_push( $val, $cliente->getSubtotal() );
		}

		if($cliente->getIva() != NULL){
			$sql .= " iva = ? AND";
			array_push( $val, $cliente->getIva() );
		}

		if($cliente->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $cliente->getIdSucursal() );
		}

		if($cliente->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $cliente->getIdUsuario() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Ventas($foo));
		}
		return $allData;
	}


	/**
	  * Este método es un método de ayuda para uso interno. Se ejecutará todas las manipulaciones 
	  * base de datos que va a cambiar la información en tablas. consultas SELECT no se ejecutará
	  * aquí, sin embargo. El valor de retorno indica cuántas filas se vieron afectados. 
	  **/
	final public function update( $ventas )
	{
		$sql = "UPDATE ventas SET  id_cliente = ?, tipo_venta = ?, fecha = ?, subtotal = ?, iva = ?, id_sucursal = ?, id_usuario = ? WHERE  id_venta = ?;";
		$params = array( 
			$ventas->getIdCliente(), 
			$ventas->getTipoVenta(), 
			$ventas->getFecha(), 
			$ventas->getSubtotal(), 
			$ventas->getIva(), 
			$ventas->getIdSucursal(), 
			$ventas->getIdUsuario(), 
			$ventas->getIdVenta(), );
		global $db;
		$db->Execute($sql, $params);
	}


	/**
	  * Este metodo creará una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Ventas suministrado. Asegúrese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Después del comando INSERT, este método asignara la clave 
	  * primaria generada en el objeto Ventas.
	  **/
	final public function create( &$ventas )
	{
		$sql = "INSERT INTO ventas ( id_cliente, tipo_venta, fecha, subtotal, iva, id_sucursal, id_usuario ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$ventas->getIdCliente(), 
			$ventas->getTipoVenta(), 
			$ventas->getFecha(), 
			$ventas->getSubtotal(), 
			$ventas->getIva(), 
			$ventas->getIdSucursal(), 
			$ventas->getIdUsuario(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$ventas->setIdVenta( $db->Insert_ID() );
	}


	/**
	  * Este método se eliminará la información de base de datos identificados por la clave primaria
	  * en el objeto Ventas suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). Restaurar sólo se puede hacer usando el método create(), 
	  * pero el objeto resultante tendrá una diferente clave primaria de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente, NotFoundException será lanzada.
	  **/
	final public function delete( &$ventas )
	{
		$sql = "DELETE FROM ventas WHERE  id_venta = ?;";

		$params = array( 
			$ventas->getIdVenta(), );

		global $db;

		$db->Execute($sql, $params);
		$ventas = NULL;
	}


}
