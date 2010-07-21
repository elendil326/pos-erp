<?php
/** Cotizacion Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Cotizacion }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class CotizacionDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Cotizacion} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @param Cotizacion [$cotizacion] El objeto de tipo Cotizacion
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$cotizacion )
	{
		if( self::getByPK(  $cotizacion->getIdCotizacion() ) === NULL )
		{
			return CotizacionDAOBase::create( $cotizacion) ;
		}else{
			return CotizacionDAOBase::update( $cotizacion) ;
		}
	}


	/**
	  *	Obtener {@link Cotizacion} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Cotizacion} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Cotizacion}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_cotizacion )
	{
		$sql = "SELECT * FROM cotizacion WHERE (id_cotizacion = ?) LIMIT 1;";
		$params = array(  $id_cotizacion );
		global $db;
		$rs = $db->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new Cotizacion( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Cotizacion}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link Cotizacion}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from cotizacion ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Cotizacion($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Cotizacion} de la base de datos. 
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
	  * @param Cotizacion [$cotizacion] El objeto de tipo Cotizacion
	  **/
	public static final function search( $cotizacion )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdCotizacion() != NULL){
			$sql .= " id_cotizacion = ? AND";
			array_push( $val, $cliente->getIdCotizacion() );
		}

		if($cliente->getIdCliente() != NULL){
			$sql .= " id_cliente = ? AND";
			array_push( $val, $cliente->getIdCliente() );
		}

		if($cliente->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $cliente->getFecha() );
		}

		if($cliente->getSubtotal() != NULL){
			$sql .= " subtotal = ? AND";
			array_push( $val, $cliente->getSubtotal() );
		}

		if($cliente->getIva() != NULL){
			$sql .= " iva = ? AND";
			array_push( $val, $cliente->getIva() );
		}

		if($cliente->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $cliente->getIdSucursal() );
		}

		if($cliente->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $cliente->getIdUsuario() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Cotizacion($foo));
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
	  * @param Cotizacion [$cotizacion] El objeto de tipo Cotizacion a actualizar.
	  **/
	private static final function update( $cotizacion )
	{
		$sql = "UPDATE cotizacion SET  id_cliente = ?, fecha = ?, subtotal = ?, iva = ?, id_sucursal = ?, id_usuario = ? WHERE  id_cotizacion = ?;";
		$params = array( 
			$cotizacion->getIdCliente(), 
			$cotizacion->getFecha(), 
			$cotizacion->getSubtotal(), 
			$cotizacion->getIva(), 
			$cotizacion->getIdSucursal(), 
			$cotizacion->getIdUsuario(), 
			$cotizacion->getIdCotizacion(), );
		global $db;
		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Cotizacion suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Cotizacion dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas
	  * @param Cotizacion [$cotizacion] El objeto de tipo Cotizacion a crear.
	  **/
	private static final function create( &$cotizacion )
	{
		$sql = "INSERT INTO cotizacion ( id_cliente, fecha, subtotal, iva, id_sucursal, id_usuario ) VALUES ( ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$cotizacion->getIdCliente(), 
			$cotizacion->getFecha(), 
			$cotizacion->getSubtotal(), 
			$cotizacion->getIva(), 
			$cotizacion->getIdSucursal(), 
			$cotizacion->getIdUsuario(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$ar = $db->Affected_Rows();
		if($ar == 0) return 0;
		$cotizacion->setIdCotizacion( $db->Insert_ID() );
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Cotizacion suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Cotizacion [$cotizacion] El objeto de tipo Cotizacion a eliminar
	  **/
	public static final function delete( &$cotizacion )
	{
		if(self::getByPK($cotizacion->getIdCotizacion()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM cotizacion WHERE  id_cotizacion = ?;";
		$params = array( $cotizacion->getIdCotizacion() );
		global $db;

		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


}
