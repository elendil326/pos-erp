<?php
/** GruposUsuarios Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link GruposUsuarios }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class GruposUsuariosDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link GruposUsuarios} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @param GruposUsuarios [$grupos_usuarios] El objeto de tipo GruposUsuarios
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$grupos_usuarios )
	{
		if( self::getByPK(  $grupos_usuarios->getIdGrupo() , $grupos_usuarios->getIdUsuario() ) === NULL )
		{
			return GruposUsuariosDAOBase::create( $grupos_usuarios) ;
		}else{
			return GruposUsuariosDAOBase::update( $grupos_usuarios) ;
		}
	}


	/**
	  *	Obtener {@link GruposUsuarios} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link GruposUsuarios} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link GruposUsuarios}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_grupo, $id_usuario )
	{
		$sql = "SELECT * FROM grupos_usuarios WHERE (id_grupo = ?,id_usuario = ?) LIMIT 1;";
		$params = array(  $id_grupo, $id_usuario );
		global $db;
		$rs = $db->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new GruposUsuarios( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link GruposUsuarios}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link GruposUsuarios}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from grupos_usuarios ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new GruposUsuarios($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link GruposUsuarios} de la base de datos. 
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
	  * @param GruposUsuarios [$grupos_usuarios] El objeto de tipo GruposUsuarios
	  **/
	public static final function search( $grupos_usuarios )
	{
		$sql = "SELECT * from grupos_usuarios WHERE ("; 
		$val = array();
		if( $grupos_usuarios->getIdGrupo() != NULL){
			$sql .= " id_grupo = ? AND";
			array_push( $val, $grupos_usuarios->getIdGrupo() );
		}

		if( $grupos_usuarios->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $grupos_usuarios->getIdUsuario() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new GruposUsuarios($foo));
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
	  * @param GruposUsuarios [$grupos_usuarios] El objeto de tipo GruposUsuarios a actualizar.
	  **/
	private static final function update( $grupos_usuarios )
	{
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto GruposUsuarios suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto GruposUsuarios dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas
	  * @param GruposUsuarios [$grupos_usuarios] El objeto de tipo GruposUsuarios a crear.
	  **/
	private static final function create( &$grupos_usuarios )
	{
		$sql = "INSERT INTO grupos_usuarios ( id_grupo, id_usuario ) VALUES ( ?, ?);";
		$params = array( 
			$grupos_usuarios->getIdGrupo(), 
			$grupos_usuarios->getIdUsuario(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$ar = $db->Affected_Rows();
		if($ar == 0) return 0;
		
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto GruposUsuarios suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param GruposUsuarios [$grupos_usuarios] El objeto de tipo GruposUsuarios a eliminar
	  **/
	public static final function delete( &$grupos_usuarios )
	{
		if(self::getByPK($grupos_usuarios->getIdGrupo(), $grupos_usuarios->getIdUsuario()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM grupos_usuarios WHERE  id_grupo = ? AND id_usuario = ?;";
		$params = array( $grupos_usuarios->getIdGrupo(), $grupos_usuarios->getIdUsuario() );
		global $db;

		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


}
