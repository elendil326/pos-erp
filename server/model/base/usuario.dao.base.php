<?php
/** Usuario Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Usuario }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class UsuarioDAOBase
{

	/**
	  *	metodo save 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Usuario} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara 
	  *	no esta definicda en el objeto, entonces save() creara una nueva fila.
	  *	
	  *	@static
	  * @param Usuario [$usuario] El objeto de tipo Usuario
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$usuario )
	{
		if(  $usuario->getIdUsuario()  )
		{
			return UsuarioDAOBase::update( $usuario) ;
		}else{
			return UsuarioDAOBase::create( $usuario) ;
		}
	}


	/**
	  *	Obtener {@link Usuario} por llave primaria. 
	  *	
	  * This will create and load {@link Usuario} objects contents from database 
	  * using given Primary-Key as identifier. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Usuario}.
	  **/
	public static final function getByPK(  $id_usuario )
	{
		$sql = "SELECT * FROM usuario WHERE (id_usuario = ?) LIMIT 1;";
		$params = array(  $id_usuario );
		global $db;
		$rs = $db->GetRow($sql, $params);
		return new Usuario( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Usuario}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link Usuario}.
	  **/
	public static final function getAll( )
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
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Usuario} de la base de datos. 
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
	  * @param Objeto Un objeto del tipo {@link Usuario}.
	  **/
	public static final function search( $usuario )
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
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @param Objeto El objeto del tipo {@link Usuario} a actualizar. 
	  **/
	private static final function update( $usuario )
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
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Usuario suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Usuario.
	  *	
	  * @internal private information for advanced developers only
	  * @param Objeto El objeto del tipo {@link Usuario} a crear. 
	  **/
	private static final function create( &$usuario )
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
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Usuario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). Restaurarlo solo se puede hacer usando el metodo create(), 
	  * pero el objeto resultante tendra una diferente clave primaria de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente, NotFoundException sera lanzada.
	  *	
	  * @param Objeto El objeto del tipo {@link Usuario} a eliminar. 
	  **/
	public static final function delete( &$usuario )
	{
		$sql = "DELETE FROM usuario WHERE  id_usuario = ?;";

		$params = array( 
			$usuario->getIdUsuario(), );

		global $db;

		$db->Execute($sql, $params);
	}


}
