<?php
/* Usuario Data Access Object (DAO).
 * Esta clase contiene toda la manipulación de bases de datos que se necesita para 
 * almacenar de forma permanente y recuperar instancias de objetos Usuario. 
 */

class UsuarioDAOBase
{

	private function __construct()
	{
		//prevent instatiation of this class by marking it private
	}


	/**
	  *	Este método guarda el estado actual de objeto Usuario en la base de datos. La llave 
	  *	primaria indicará qué instancia va a ser actualizado en base de datos. Si la llave primara 
	  *	no esta definicda en el objeto, entonces save() creara una nueva fila.
	  **/
	final public function save( &$usuario )
	{
		if(  $usuario->getIdUsuario()  )
		{
			return UsuarioDAOBase::update( $usuario) ;
		}else{
			return UsuarioDAOBase::create( $usuario) ;
		}
	}


	/**
	  * getObject-method. This will create and load valueObject contents from database 
	  * using given Primary-Key as identifier. This method is just a convenience method 
	  * for the real load-method which accepts the valueObject as a parameter. Returned
	  * valueObject will be created using the createValueObject() method.
	  **/
	final public function getByPK(  $id_usuario )
	{
		$sql = "SELECT * FROM usuario WHERE (id_usuario = ?) LIMIT 1;";
		$params = array(  $id_usuario );
		global $db;
		$rs = $db->GetRow($sql, $params);
		return new Usuario( $rs );
	}


	/**
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo Usuario. Tenga en cuenta que este método
	  * se consumen enormes cantidades de recursos si la tabla tiene muchas de las filas. 
	  * Esto sólo debe usarse cuando las tablas de destino tienen sólo pequeñas cantidades de datos
	  **/
	final public function getAll( )
	{
		$sql = "SELECT * from usuario ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Usuario($foo));
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
	final public function search( $usuario )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $cliente->getIdUsuario() );
		}

		if($cliente->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $cliente->getNombre() );
		}

		if($cliente->getUsuario() != NULL){
			$sql .= " usuario = ? AND";
			array_push( $val, $cliente->getUsuario() );
		}

		if($cliente->getContrasena() != NULL){
			$sql .= " contrasena = ? AND";
			array_push( $val, $cliente->getContrasena() );
		}

		if($cliente->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $cliente->getIdSucursal() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Usuario($foo));
		}
		return $allData;
	}


	/**
	  * Este método es un método de ayuda para uso interno. Se ejecutará todas las manipulaciones 
	  * base de datos que va a cambiar la información en tablas. consultas SELECT no se ejecutará
	  * aquí, sin embargo. El valor de retorno indica cuántas filas se vieron afectados. 
	  **/
	final public function update( $usuario )
	{
		$sql = "UPDATE usuario SET  nombre = ?, usuario = ?, contrasena = ?, id_sucursal = ? WHERE  id_usuario = ?;";
		$params = array( 
			$usuario->getNombre(), 
			$usuario->getUsuario(), 
			$usuario->getContrasena(), 
			$usuario->getIdSucursal(), 
			$usuario->getIdUsuario(), );
		global $db;
		$db->Execute($sql, $params);
	}


	/**
	  * Este metodo creará una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Usuario suministrado. Asegúrese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Después del comando INSERT, este método asignara la clave 
	  * primaria generada en el objeto Usuario.
	  **/
	final public function create( &$usuario )
	{
		$sql = "INSERT INTO usuario ( nombre, usuario, contrasena, id_sucursal ) VALUES ( ?, ?, ?, ?);";
		$params = array( 
			$usuario->getNombre(), 
			$usuario->getUsuario(), 
			$usuario->getContrasena(), 
			$usuario->getIdSucursal(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$usuario->setIdUsuario( $db->Insert_ID() );
	}


	/**
	  * Este método se eliminará la información de base de datos identificados por la clave primaria
	  * en el objeto Usuario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). Restaurar sólo se puede hacer usando el método create(), 
	  * pero el objeto resultante tendrá una diferente clave primaria de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente, NotFoundException será lanzada.
	  **/
	final public function delete( &$usuario )
	{
		$sql = "DELETE FROM usuario WHERE  id_usuario = ?;";

		$params = array( 
			$usuario->getIdUsuario(), );

		global $db;

		$db->Execute($sql, $params);
		$usuario = NULL;
	}


}
