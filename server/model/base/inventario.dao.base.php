<?php
/** Inventario Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Inventario }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class InventarioDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Inventario} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @param Inventario [$inventario] El objeto de tipo Inventario
	  * @return Un entero mayor o igual a cero denotando las filas afectadas, o un string con el error si es que hubo alguno.
	  **/
	public static final function save( &$inventario )
	{
		if( self::getByPK(  $inventario->getIdProducto() ) === NULL )
		{
			return InventarioDAOBase::create( $inventario) ;
		}else{
			return InventarioDAOBase::update( $inventario) ;
		}
	}


	/**
	  *	Obtener {@link Inventario} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Inventario} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Inventario}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_producto )
	{
		$sql = "SELECT * FROM inventario WHERE (id_producto = ? ) LIMIT 1;";
		$params = array(  $id_producto );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new Inventario( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Inventario}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link Inventario}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from inventario ;";
		global $conn;
		$rs = $conn->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Inventario($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Inventario} de la base de datos. 
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
	  * @param Inventario [$inventario] El objeto de tipo Inventario
	  **/
	public static final function search( $inventario )
	{
		$sql = "SELECT * from inventario WHERE ("; 
		$val = array();
		if( $inventario->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $inventario->getIdProducto() );
		}

		if( $inventario->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $inventario->getNombre() );
		}

		if( $inventario->getDenominacion() != NULL){
			$sql .= " denominacion = ? AND";
			array_push( $val, $inventario->getDenominacion() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Inventario($foo));
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
	  * @param Inventario [$inventario] El objeto de tipo Inventario a actualizar.
	  **/
	private static final function update( $inventario )
	{
		$sql = "UPDATE inventario SET  nombre = ?, denominacion = ? WHERE  id_producto = ?;";
		$params = array( 
			$inventario->getNombre(), 
			$inventario->getDenominacion(), 
			$inventario->getIdProducto(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ return $e->getMessage(); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Inventario suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Inventario dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Inventario [$inventario] El objeto de tipo Inventario a crear.
	  **/
	private static final function create( &$inventario )
	{
		$sql = "INSERT INTO inventario ( nombre, denominacion ) VALUES ( ?, ?);";
		$params = array( 
			$inventario->getNombre(), 
			$inventario->getDenominacion(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ return $e->getMessage(); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		$inventario->setIdProducto( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Inventario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Inventario [$inventario] El objeto de tipo Inventario a eliminar
	  **/
	public static final function delete( &$inventario )
	{
		if(self::getByPK($inventario->getIdProducto()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM inventario WHERE  id_producto = ?;";
		$params = array( $inventario->getIdProducto() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
