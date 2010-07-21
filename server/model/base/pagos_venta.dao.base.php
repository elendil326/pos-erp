<?php
/** PagosVenta Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link PagosVenta }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class PagosVentaDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link PagosVenta} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$pagos_venta )
	{
		if( self::getByPK(  $pagos_venta->getIdPago() ) === NULL )
		{
			return PagosVentaDAOBase::create( $pagos_venta) ;
		}else{
			return PagosVentaDAOBase::update( $pagos_venta) ;
		}
	}


	/**
	  *	Obtener {@link PagosVenta} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link PagosVenta} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link PagosVenta}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_pago )
	{
		$sql = "SELECT * FROM pagos_venta WHERE (id_pago = ?) LIMIT 1;";
		$params = array(  $id_pago );
		global $db;
		$rs = $db->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new PagosVenta( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link PagosVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link PagosVenta}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from pagos_venta ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new PagosVenta($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PagosVenta} de la base de datos. 
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
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta
	  **/
	public static final function search( $pagos_venta )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdPago() != NULL){
			$sql .= " id_pago = ? AND";
			array_push( $val, $cliente->getIdPago() );
		}

		if($cliente->getIdVenta() != NULL){
			$sql .= " id_venta = ? AND";
			array_push( $val, $cliente->getIdVenta() );
		}

		if($cliente->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $cliente->getFecha() );
		}

		if($cliente->getMonto() != NULL){
			$sql .= " monto = ? AND";
			array_push( $val, $cliente->getMonto() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new PagosVenta($foo));
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
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta a actualizar.
	  **/
	private static final function update( $pagos_venta )
	{
		$sql = "UPDATE pagos_venta SET  id_venta = ?, fecha = ?, monto = ? WHERE  id_pago = ?;";
		$params = array( 
			$pagos_venta->getIdVenta(), 
			$pagos_venta->getFecha(), 
			$pagos_venta->getMonto(), 
			$pagos_venta->getIdPago(), );
		global $db;
		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto PagosVenta suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto PagosVenta dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta a crear.
	  **/
	private static final function create( &$pagos_venta )
	{
		$sql = "INSERT INTO pagos_venta ( id_venta, fecha, monto ) VALUES ( ?, ?, ?);";
		$params = array( 
			$pagos_venta->getIdVenta(), 
			$pagos_venta->getFecha(), 
			$pagos_venta->getMonto(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$ar = $db->Affected_Rows();
		if($ar == 0) return 0;
		$pagos_venta->setIdPago( $db->Insert_ID() );
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto PagosVenta suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta a eliminar
	  **/
	public static final function delete( &$pagos_venta )
	{
		if(self::getByPK($pagos_venta->getIdPago()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM pagos_venta WHERE  id_pago = ?;";
		$params = array( $pagos_venta->getIdPago() );
		global $db;

		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


}
