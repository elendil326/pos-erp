<?php
/* DetalleCorte Data Access Object (DAO).
 * Esta clase contiene toda la manipulación de bases de datos que se necesita para 
 * almacenar de forma permanente y recuperar instancias de objetos DetalleCorte. 
 */

class DetalleCorteDAOBase
{

	private function __construct()
	{
		//prevent instatiation of this class by marking it private
	}


	/**
	  *	Este método guarda el estado actual de objeto DetalleCorte en la base de datos. La llave 
	  *	primaria indicará qué instancia va a ser actualizado en base de datos. Si la llave primara 
	  *	no esta definicda en el objeto, entonces save() creara una nueva fila.
	  **/
	final public function save( &$detalle_corte )
	{
		if(  $detalle_corte->getNumCorte() && $detalle_corte->getNombre()  )
		{
			return DetalleCorteDAOBase::update( $detalle_corte) ;
		}else{
			return DetalleCorteDAOBase::create( $detalle_corte) ;
		}
	}


	/**
	  * getObject-method. This will create and load valueObject contents from database 
	  * using given Primary-Key as identifier. This method is just a convenience method 
	  * for the real load-method which accepts the valueObject as a parameter. Returned
	  * valueObject will be created using the createValueObject() method.
	  **/
	final public function getByPK(  $num_corte, $nombre )
	{
		$sql = "SELECT * FROM detalle_corte WHERE (num_corte = ?,nombre = ?) LIMIT 1;";
		$params = array(  $num_corte, $nombre );
		global $db;
		$rs = $db->GetRow($sql, $params);
		return new DetalleCorte( $rs );
	}


	/**
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo DetalleCorte. Tenga en cuenta que este método
	  * se consumen enormes cantidades de recursos si la tabla tiene muchas de las filas. 
	  * Esto sólo debe usarse cuando las tablas de destino tienen sólo pequeñas cantidades de datos
	  **/
	final public function getAll( )
	{
		$sql = "SELECT * from detalle_corte ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new DetalleCorte($foo));
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
	final public function search( $detalle_corte )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getNumCorte() != NULL){
			$sql .= " num_corte = ? AND";
			array_push( $val, $cliente->getNumCorte() );
		}

		if($cliente->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $cliente->getNombre() );
		}

		if($cliente->getTotal() != NULL){
			$sql .= " total = ? AND";
			array_push( $val, $cliente->getTotal() );
		}

		if($cliente->getDeben() != NULL){
			$sql .= " deben = ? AND";
			array_push( $val, $cliente->getDeben() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new DetalleCorte($foo));
		}
		return $allData;
	}


	/**
	  * Este método es un método de ayuda para uso interno. Se ejecutará todas las manipulaciones 
	  * base de datos que va a cambiar la información en tablas. consultas SELECT no se ejecutará
	  * aquí, sin embargo. El valor de retorno indica cuántas filas se vieron afectados. 
	  **/
	final public function update( $detalle_corte )
	{
		$sql = "UPDATE detalle_corte SET  total = ?, deben = ? WHERE  num_corte = ? AND nombre = ?;";
		$params = array( 
			$detalle_corte->getTotal(), 
			$detalle_corte->getDeben(), 
			$detalle_corte->getNumCorte(),$detalle_corte->getNombre(), );
		global $db;
		$db->Execute($sql, $params);
	}


	/**
	  * Este metodo creará una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleCorte suministrado. Asegúrese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Después del comando INSERT, este método asignara la clave 
	  * primaria generada en el objeto DetalleCorte.
	  **/
	final public function create( &$detalle_corte )
	{
		$sql = "INSERT INTO detalle_corte ( num_corte, nombre, total, deben ) VALUES ( ?, ?, ?, ?);";
		$params = array( 
			$detalle_corte->getNumCorte(), 
			$detalle_corte->getNombre(), 
			$detalle_corte->getTotal(), 
			$detalle_corte->getDeben(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		
	}


	/**
	  * Este método se eliminará la información de base de datos identificados por la clave primaria
	  * en el objeto DetalleCorte suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). Restaurar sólo se puede hacer usando el método create(), 
	  * pero el objeto resultante tendrá una diferente clave primaria de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente, NotFoundException será lanzada.
	  **/
	final public function delete( &$detalle_corte )
	{
		$sql = "DELETE FROM detalle_corte WHERE  num_corte = ? AND nombre = ?;";

		$params = array( 
			$detalle_corte->getNumCorte(),$detalle_corte->getNombre(), );

		global $db;

		$db->Execute($sql, $params);
		$detalle_corte = NULL;
	}


}
