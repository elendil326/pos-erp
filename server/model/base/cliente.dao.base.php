<?php
/** Cliente Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Cliente }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class ClienteDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Cliente} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @param Cliente [$cliente] El objeto de tipo Cliente
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$cliente )
	{
		if( self::getByPK(  $cliente->getIdCliente() ) === NULL )
		{
			return ClienteDAOBase::create( $cliente) ;
		}else{
			return ClienteDAOBase::update( $cliente) ;
		}
	}


	/**
	  *	Obtener {@link Cliente} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Cliente} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Cliente}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_cliente )
	{
		$sql = "SELECT * FROM cliente WHERE (id_cliente = ?) LIMIT 1;";
		$params = array(  $id_cliente );
		global $db;
		$rs = $db->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new Cliente( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Cliente}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link Cliente}.
	  **/
	public static final function getAll( )
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
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Cliente} de la base de datos. 
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
	  * @param Cliente [$cliente] El objeto de tipo Cliente
	  **/
	public static final function search( $cliente )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if( $cliente->getIdCliente() != NULL){
			$sql .= " id_cliente = ? AND";
			array_push( $val, $cliente->getIdCliente() );
		}

		if( $cliente->getRfc() != NULL){
			$sql .= " rfc = ? AND";
			array_push( $val, $cliente->getRfc() );
		}

		if( $cliente->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $cliente->getNombre() );
		}

		if( $cliente->getDireccion() != NULL){
			$sql .= " direccion = ? AND";
			array_push( $val, $cliente->getDireccion() );
		}

		if( $cliente->getTelefono() != NULL){
			$sql .= " telefono = ? AND";
			array_push( $val, $cliente->getTelefono() );
		}

		if( $cliente->getEMail() != NULL){
			$sql .= " e_mail = ? AND";
			array_push( $val, $cliente->getEMail() );
		}

		if( $cliente->getLimiteCredito() != NULL){
			$sql .= " limite_credito = ? AND";
			array_push( $val, $cliente->getLimiteCredito() );
		}

		if( $cliente->getDescuento() != NULL){
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
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas
	  * @param Cliente [$cliente] El objeto de tipo Cliente a actualizar.
	  **/
	private static final function update( $cliente )
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
		return $db->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Cliente suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Cliente dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas
	  * @param Cliente [$cliente] El objeto de tipo Cliente a crear.
	  **/
	private static final function create( &$cliente )
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
		$ar = $db->Affected_Rows();
		if($ar == 0) return 0;
		$cliente->setIdCliente( $db->Insert_ID() );
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Cliente suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Cliente [$cliente] El objeto de tipo Cliente a eliminar
	  **/
	public static final function delete( &$cliente )
	{
		if(self::getByPK($cliente->getIdCliente()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM cliente WHERE  id_cliente = ?;";
		$params = array( $cliente->getIdCliente() );
		global $db;

		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


}
