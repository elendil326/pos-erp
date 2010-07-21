<?php
/** Proveedor Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Proveedor }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class ProveedorDAOBase
{

	/**
	  *	metodo save 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Proveedor} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara 
	  *	no esta definicda en el objeto, entonces save() creara una nueva fila.
	  *	
	  *	@static
	  * @param Proveedor [$proveedor] El objeto de tipo Proveedor
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$proveedor )
	{
		if(  $proveedor->getIdProveedor()  )
		{
			return ProveedorDAOBase::update( $proveedor) ;
		}else{
			return ProveedorDAOBase::create( $proveedor) ;
		}
	}


	/**
	  *	Obtener {@link Proveedor} por llave primaria. 
	  *	
	  * This will create and load {@link Proveedor} objects contents from database 
	  * using given Primary-Key as identifier. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Proveedor}.
	  **/
	public static final function getByPK(  $id_proveedor )
	{
		$sql = "SELECT * FROM proveedor WHERE (id_proveedor = ?) LIMIT 1;";
		$params = array(  $id_proveedor );
		global $db;
		$rs = $db->GetRow($sql, $params);
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
		global $db;
		$rs = $db->Execute($sql);
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
	  * @param Objeto Un objeto del tipo {@link Proveedor}.
	  **/
	public static final function search( $proveedor )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdProveedor() != NULL){
			$sql .= " id_proveedor = ? AND";
			array_push( $val, $cliente->getIdProveedor() );
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

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
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
	  * @param Objeto El objeto del tipo {@link Proveedor} a actualizar. 
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
		global $db;
		$db->Execute($sql, $params);
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Proveedor suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Proveedor.
	  *	
	  * @internal private information for advanced developers only
	  * @param Objeto El objeto del tipo {@link Proveedor} a crear. 
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
		global $db;
		$db->Execute($sql, $params);
		$proveedor->setIdProveedor( $db->Insert_ID() );
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Proveedor suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). Restaurarlo solo se puede hacer usando el metodo create(), 
	  * pero el objeto resultante tendra una diferente clave primaria de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente, NotFoundException sera lanzada.
	  *	
	  * @param Objeto El objeto del tipo {@link Proveedor} a eliminar. 
	  **/
	public static final function delete( &$proveedor )
	{
		$sql = "DELETE FROM proveedor WHERE  id_proveedor = ?;";

		$params = array( 
			$proveedor->getIdProveedor(), );

		global $db;

		$db->Execute($sql, $params);
	}


}
