<?php
/** Ingresos Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Ingresos }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class IngresosDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Ingresos} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$ingresos )
	{
		if( self::getByPK(  $ingresos->getIdIngreso() ) === NULL )
		{
			return IngresosDAOBase::create( $ingresos) ;
		}else{
			return IngresosDAOBase::update( $ingresos) ;
		}
	}


	/**
	  *	Obtener {@link Ingresos} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Ingresos} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Ingresos}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_ingreso )
	{
		$sql = "SELECT * FROM ingresos WHERE (id_ingreso = ?) LIMIT 1;";
		$params = array(  $id_ingreso );
		global $db;
		$rs = $db->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new Ingresos( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Ingresos}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link Ingresos}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from ingresos ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Ingresos($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ingresos} de la base de datos. 
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
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos
	  **/
	public static final function search( $ingresos )
	{
		$sql = "SELECT * from ingresos WHERE ("; 
		$val = array();
		if( $ingresos->getIdIngreso() != NULL){
			$sql .= " id_ingreso = ? AND";
			array_push( $val, $ingresos->getIdIngreso() );
		}

		if( $ingresos->getConcepto() != NULL){
			$sql .= " concepto = ? AND";
			array_push( $val, $ingresos->getConcepto() );
		}

		if( $ingresos->getMonto() != NULL){
			$sql .= " monto = ? AND";
			array_push( $val, $ingresos->getMonto() );
		}

		if( $ingresos->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $ingresos->getFecha() );
		}

		if( $ingresos->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $ingresos->getIdSucursal() );
		}

		if( $ingresos->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $ingresos->getIdUsuario() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Ingresos($foo));
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
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos a actualizar.
	  **/
	private static final function update( $ingresos )
	{
		$sql = "UPDATE ingresos SET  concepto = ?, monto = ?, fecha = ?, id_sucursal = ?, id_usuario = ? WHERE  id_ingreso = ?;";
		$params = array( 
			$ingresos->getConcepto(), 
			$ingresos->getMonto(), 
			$ingresos->getFecha(), 
			$ingresos->getIdSucursal(), 
			$ingresos->getIdUsuario(), 
			$ingresos->getIdIngreso(), );
		global $db;
		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Ingresos suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Ingresos dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos a crear.
	  **/
	private static final function create( &$ingresos )
	{
		$sql = "INSERT INTO ingresos ( concepto, monto, fecha, id_sucursal, id_usuario ) VALUES ( ?, ?, ?, ?, ?);";
		$params = array( 
			$ingresos->getConcepto(), 
			$ingresos->getMonto(), 
			$ingresos->getFecha(), 
			$ingresos->getIdSucursal(), 
			$ingresos->getIdUsuario(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$ar = $db->Affected_Rows();
		if($ar == 0) return 0;
		$ingresos->setIdIngreso( $db->Insert_ID() );
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Ingresos suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos a eliminar
	  **/
	public static final function delete( &$ingresos )
	{
		if(self::getByPK($ingresos->getIdIngreso()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM ingresos WHERE  id_ingreso = ?;";
		$params = array( $ingresos->getIdIngreso() );
		global $db;

		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


}
