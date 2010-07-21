<?php
/** Sucursal Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Sucursal }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class SucursalDAOBase
{

	/**
	  *	metodo save 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Sucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara 
	  *	no esta definicda en el objeto, entonces save() creara una nueva fila.
	  *	
	  *	@static
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$sucursal )
	{
		if(  $sucursal->getIdSucursal()  )
		{
			return SucursalDAOBase::update( $sucursal) ;
		}else{
			return SucursalDAOBase::create( $sucursal) ;
		}
	}


	/**
	  *	Obtener {@link Sucursal} por llave primaria. 
	  *	
	  * This will create and load {@link Sucursal} objects contents from database 
	  * using given Primary-Key as identifier. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Sucursal}.
	  **/
	public static final function getByPK(  $id_sucursal )
	{
		$sql = "SELECT * FROM sucursal WHERE (id_sucursal = ?) LIMIT 1;";
		$params = array(  $id_sucursal );
		global $db;
		$rs = $db->GetRow($sql, $params);
		return new Sucursal( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Sucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link Sucursal}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from sucursal ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Sucursal($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Sucursal} de la base de datos. 
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
	  * @param Objeto Un objeto del tipo {@link Sucursal}.
	  **/
	public static final function search( $sucursal )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $cliente->getIdSucursal() );
		}

		if($cliente->getDescripcion() != NULL){
			$sql .= " descripcion = ? AND";
			array_push( $val, $cliente->getDescripcion() );
		}

		if($cliente->getDireccion() != NULL){
			$sql .= " direccion = ? AND";
			array_push( $val, $cliente->getDireccion() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Sucursal($foo));
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
	  * @param Objeto El objeto del tipo {@link Sucursal} a actualizar. 
	  **/
	private static final function update( $sucursal )
	{
		$sql = "UPDATE sucursal SET  descripcion = ?, direccion = ? WHERE  id_sucursal = ?;";
		$params = array( 
			$sucursal->getDescripcion(), 
			$sucursal->getDireccion(), 
			$sucursal->getIdSucursal(), );
		global $db;
		$db->Execute($sql, $params);
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Sucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Sucursal.
	  *	
	  * @internal private information for advanced developers only
	  * @param Objeto El objeto del tipo {@link Sucursal} a crear. 
	  **/
	private static final function create( &$sucursal )
	{
		$sql = "INSERT INTO sucursal ( descripcion, direccion ) VALUES ( ?, ?);";
		$params = array( 
			$sucursal->getDescripcion(), 
			$sucursal->getDireccion(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$sucursal->setIdSucursal( $db->Insert_ID() );
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Sucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). Restaurarlo solo se puede hacer usando el metodo create(), 
	  * pero el objeto resultante tendra una diferente clave primaria de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente, NotFoundException sera lanzada.
	  *	
	  * @param Objeto El objeto del tipo {@link Sucursal} a eliminar. 
	  **/
	public static final function delete( &$sucursal )
	{
		$sql = "DELETE FROM sucursal WHERE  id_sucursal = ?;";

		$params = array( 
			$sucursal->getIdSucursal(), );

		global $db;

		$db->Execute($sql, $params);
	}


}
