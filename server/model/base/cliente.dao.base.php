<?php
/* Cliente Data Access Object (DAO).
 * Esta clase contiene toda la manipulación de bases de datos que se necesita para 
 * almacenar de forma permanente y recuperar instancias de objetos Cliente. 
 */

class ClienteDAOBase
{

	private function __construct()
	{
		//prevent instatiation of this class by marking it private
	}


	/**
	  *	Este método guarda el estado actual de objeto Cliente en la base de datos. La llave 
	  *	primaria indicará qué instancia va a ser actualizado en base de datos. Si la llave primara 
	  *	no esta definicda en el objeto, entonces save() creara una nueva fila.
	  **/
	final public function save( &$cliente )
	{
		if(  $cliente->getIdCliente()  )
		{
			return ClienteDAOBase::update( $cliente) ;
		}else{
			return ClienteDAOBase::create( $cliente) ;
		}
	}


	/**
	  * getObject-method. This will create and load valueObject contents from database 
	  * using given Primary-Key as identifier. This method is just a convenience method 
	  * for the real load-method which accepts the valueObject as a parameter. Returned
	  * valueObject will be created using the createValueObject() method.
	  **/
	final public function getByPK(  $id_cliente )
	{
		$sql = "SELECT * FROM cliente WHERE (id_cliente = ?) LIMIT 1;";
		$params = array(  $id_cliente );
		global $db;
		$rs = $db->GetRow($sql, $params);
		return new Cliente( $rs );
	}


	/**
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo Cliente. Tenga en cuenta que este método
	  * se consumen enormes cantidades de recursos si la tabla tiene muchas de las filas. 
	  * Esto sólo debe usarse cuando las tablas de destino tienen sólo pequeñas cantidades de datos
	  **/
	final public function getAll( )
	{
		$sql = "SELECT * from cliente ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Cliente($foo));
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
	final public function search( $cliente )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdCliente() != NULL){
			$sql .= " id_cliente = ? AND";
			array_push( $val, $cliente->getIdCliente() );
		}

		if($cliente->getRfc() != NULL){
			$sql .= " rfc = ? AND";
			array_push( $val, $cliente->getRfc() );
		}

		if($cliente->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $cliente->getNombre() );
		}

		if($cliente->getDireccion() != NULL){
			$sql .= " direccion = ? AND";
			array_push( $val, $cliente->getDireccion() );
		}

		if($cliente->getTelefono() != NULL){
			$sql .= " telefono = ? AND";
			array_push( $val, $cliente->getTelefono() );
		}

		if($cliente->getEMail() != NULL){
			$sql .= " e_mail = ? AND";
			array_push( $val, $cliente->getEMail() );
		}

		if($cliente->getLimiteCredito() != NULL){
			$sql .= " limite_credito = ? AND";
			array_push( $val, $cliente->getLimiteCredito() );
		}

		if($cliente->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $cliente->getDescuento() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Cliente($foo));
		}
		return $allData;
	}


	/**
	  * Este método es un método de ayuda para uso interno. Se ejecutará todas las manipulaciones 
	  * base de datos que va a cambiar la información en tablas. consultas SELECT no se ejecutará
	  * aquí, sin embargo. El valor de retorno indica cuántas filas se vieron afectados. 
	  **/
	final public function update( $cliente )
	{
		$sql = "UPDATE cliente SET  rfc = ?, nombre = ?, direccion = ?, telefono = ?, e_mail = ?, limite_credito = ?, descuento = ? WHERE  id_cliente = ?;";
		$params = array( 
			$cliente->getRfc(), 
			$cliente->getNombre(), 
			$cliente->getDireccion(), 
			$cliente->getTelefono(), 
			$cliente->getEMail(), 
			$cliente->getLimiteCredito(), 
			$cliente->getDescuento(), 
			$cliente->getIdCliente(), );
		global $db;
		$db->Execute($sql, $params);
	}


	/**
	  * Este metodo creará una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Cliente suministrado. Asegúrese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Después del comando INSERT, este método asignara la clave 
	  * primaria generada en el objeto Cliente.
	  **/
	final public function create( &$cliente )
	{
		$sql = "INSERT INTO cliente ( rfc, nombre, direccion, telefono, e_mail, limite_credito, descuento ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$cliente->getRfc(), 
			$cliente->getNombre(), 
			$cliente->getDireccion(), 
			$cliente->getTelefono(), 
			$cliente->getEMail(), 
			$cliente->getLimiteCredito(), 
			$cliente->getDescuento(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$cliente->setIdCliente( $db->Insert_ID() );
	}


	/**
	  * Este método se eliminará la información de base de datos identificados por la clave primaria
	  * en el objeto Cliente suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). Restaurar sólo se puede hacer usando el método create(), 
	  * pero el objeto resultante tendrá una diferente clave primaria de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente, NotFoundException será lanzada.
	  **/
	final public function delete( &$cliente )
	{
		$sql = "DELETE FROM cliente WHERE  id_cliente = ?;";

		$params = array( 
			$cliente->getIdCliente(), );

		global $db;

		$db->Execute($sql, $params);
		$cliente = NULL;
	}


}
