<?php
/** Proveedor Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Proveedor }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class ProveedorDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Proveedor} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor
	  * @return Un entero mayor o igual a cero denotando las filas afectadas, o un string con el error si es que hubo alguno.
	  **/
	public static final function save( &$proveedor )
	{
		if( self::getByPK(  $proveedor->getIdProveedor() ) === NULL )
		{
			return ProveedorDAOBase::create( $proveedor) ;
		}else{
			return ProveedorDAOBase::update( $proveedor) ;
		}
	}


	/**
	  *	Obtener {@link Proveedor} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Proveedor} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Proveedor}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_proveedor )
	{
		$sql = "SELECT * FROM proveedor WHERE (id_proveedor = ? ) LIMIT 1;";
		$params = array(  $id_proveedor );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new Proveedor( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Proveedor}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link Proveedor}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from proveedor ;";
		global $conn;
		$rs = $conn->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Proveedor($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Proveedor} de la base de datos. 
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
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor
	  **/
	public static final function search( $proveedor )
	{
		$sql = "SELECT * from proveedor WHERE ("; 
		$val = array();
		if( $proveedor->getIdProveedor() != NULL){
			$sql .= " id_proveedor = ? AND";
			array_push( $val, $proveedor->getIdProveedor() );
		}

		if( $proveedor->getRfc() != NULL){
			$sql .= " rfc = ? AND";
			array_push( $val, $proveedor->getRfc() );
		}

		if( $proveedor->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $proveedor->getNombre() );
		}

		if( $proveedor->getDireccion() != NULL){
			$sql .= " direccion = ? AND";
			array_push( $val, $proveedor->getDireccion() );
		}

		if( $proveedor->getTelefono() != NULL){
			$sql .= " telefono = ? AND";
			array_push( $val, $proveedor->getTelefono() );
		}

		if( $proveedor->getEMail() != NULL){
			$sql .= " e_mail = ? AND";
			array_push( $val, $proveedor->getEMail() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Proveedor($foo));
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
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor a actualizar.
	  **/
	private static final function update( $proveedor )
	{
		$sql = "UPDATE proveedor SET  rfc = ?, nombre = ?, direccion = ?, telefono = ?, e_mail = ? WHERE  id_proveedor = ?;";
		$params = array( 
			$proveedor->getRfc(), 
			$proveedor->getNombre(), 
			$proveedor->getDireccion(), 
			$proveedor->getTelefono(), 
			$proveedor->getEMail(), 
			$proveedor->getIdProveedor(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ return $e->getMessage(); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Proveedor suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Proveedor dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor a crear.
	  **/
	private static final function create( &$proveedor )
	{
		$sql = "INSERT INTO proveedor ( rfc, nombre, direccion, telefono, e_mail ) VALUES ( ?, ?, ?, ?, ?);";
		$params = array( 
			$proveedor->getRfc(), 
			$proveedor->getNombre(), 
			$proveedor->getDireccion(), 
			$proveedor->getTelefono(), 
			$proveedor->getEMail(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ return $e->getMessage(); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		$proveedor->setIdProveedor( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Proveedor suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor a eliminar
	  **/
	public static final function delete( &$proveedor )
	{
		if(self::getByPK($proveedor->getIdProveedor()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM proveedor WHERE  id_proveedor = ?;";
		$params = array( $proveedor->getIdProveedor() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
