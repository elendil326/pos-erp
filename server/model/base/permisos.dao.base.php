<?php
/** Permisos Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Permisos }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class PermisosDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Permisos} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @param Permisos [$permisos] El objeto de tipo Permisos
	  * @return Un entero mayor o igual a cero denotando las filas afectadas, o un string con el error si es que hubo alguno.
	  **/
	public static final function save( &$permisos )
	{
		if( self::getByPK(  $permisos->getIdPermiso() ) === NULL )
		{
			return PermisosDAOBase::create( $permisos) ;
		}else{
			return PermisosDAOBase::update( $permisos) ;
		}
	}


	/**
	  *	Obtener {@link Permisos} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Permisos} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Permisos}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_permiso )
	{
		$sql = "SELECT * FROM permisos WHERE (id_permiso = ? ) LIMIT 1;";
		$params = array(  $id_permiso );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new Permisos( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Permisos}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link Permisos}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from permisos ;";
		global $conn;
		$rs = $conn->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Permisos($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Permisos} de la base de datos. 
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
	  * @param Permisos [$permisos] El objeto de tipo Permisos
	  **/
	public static final function search( $permisos )
	{
		$sql = "SELECT * from permisos WHERE ("; 
		$val = array();
		if( $permisos->getIdPermiso() != NULL){
			$sql .= " id_permiso = ? AND";
			array_push( $val, $permisos->getIdPermiso() );
		}

		if( $permisos->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $permisos->getNombre() );
		}

		if( $permisos->getDescripcion() != NULL){
			$sql .= " descripcion = ? AND";
			array_push( $val, $permisos->getDescripcion() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Permisos($foo));
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
	  * @param Permisos [$permisos] El objeto de tipo Permisos a actualizar.
	  **/
	private static final function update( $permisos )
	{
		$sql = "UPDATE permisos SET  nombre = ?, descripcion = ? WHERE  id_permiso = ?;";
		$params = array( 
			$permisos->getNombre(), 
			$permisos->getDescripcion(), 
			$permisos->getIdPermiso(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ return $e->getMessage(); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Permisos suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Permisos dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Permisos [$permisos] El objeto de tipo Permisos a crear.
	  **/
	private static final function create( &$permisos )
	{
		$sql = "INSERT INTO permisos ( id_permiso, nombre, descripcion ) VALUES ( ?, ?, ?);";
		$params = array( 
			$permisos->getIdPermiso(), 
			$permisos->getNombre(), 
			$permisos->getDescripcion(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ return $e->getMessage(); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Permisos suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Permisos [$permisos] El objeto de tipo Permisos a eliminar
	  **/
	public static final function delete( &$permisos )
	{
		if(self::getByPK($permisos->getIdPermiso()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM permisos WHERE  id_permiso = ?;";
		$params = array( $permisos->getIdPermiso() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
