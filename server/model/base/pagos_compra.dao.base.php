<?php
/** PagosCompra Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link PagosCompra }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class PagosCompraDAOBase
{

	/**
	  *	metodo save 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link PagosCompra} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara 
	  *	no esta definicda en el objeto, entonces save() creara una nueva fila.
	  *	
	  *	@static
	  * @param PagosCompra [$pagos_compra] El objeto de tipo PagosCompra
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$pagos_compra )
	{
		if(  $pagos_compra->getIdPago()  )
		{
			return PagosCompraDAOBase::update( $pagos_compra) ;
		}else{
			return PagosCompraDAOBase::create( $pagos_compra) ;
		}
	}


	/**
	  *	Obtener {@link PagosCompra} por llave primaria. 
	  *	
	  * This will create and load {@link PagosCompra} objects contents from database 
	  * using given Primary-Key as identifier. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link PagosCompra}.
	  **/
	public static final function getByPK(  $id_pago )
	{
		$sql = "SELECT * FROM pagos_compra WHERE (id_pago = ?) LIMIT 1;";
		$params = array(  $id_pago );
		global $db;
		$rs = $db->GetRow($sql, $params);
		return new PagosCompra( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link PagosCompra}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link PagosCompra}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from pagos_compra ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new PagosCompra($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PagosCompra} de la base de datos. 
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
	  * @param Objeto Un objeto del tipo {@link PagosCompra}.
	  **/
	public static final function search( $pagos_compra )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdPago() != NULL){
			$sql .= " id_pago = ? AND";
			array_push( $val, $cliente->getIdPago() );
		}

		if($cliente->getIdCompra() != NULL){
			$sql .= " id_compra = ? AND";
			array_push( $val, $cliente->getIdCompra() );
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
    		array_push( $allData, new PagosCompra($foo));
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
	  * @param Objeto El objeto del tipo {@link PagosCompra} a actualizar. 
	  **/
	private static final function update( $pagos_compra )
	{
		$sql = "UPDATE pagos_compra SET  id_compra = ?, fecha = ?, monto = ? WHERE  id_pago = ?;";
		$params = array( 
			$pagos_compra->getIdCompra(), 
			$pagos_compra->getFecha(), 
			$pagos_compra->getMonto(), 
			$pagos_compra->getIdPago(), );
		global $db;
		$db->Execute($sql, $params);
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto PagosCompra suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto PagosCompra.
	  *	
	  * @internal private information for advanced developers only
	  * @param Objeto El objeto del tipo {@link PagosCompra} a crear. 
	  **/
	private static final function create( &$pagos_compra )
	{
		$sql = "INSERT INTO pagos_compra ( id_compra, fecha, monto ) VALUES ( ?, ?, ?);";
		$params = array( 
			$pagos_compra->getIdCompra(), 
			$pagos_compra->getFecha(), 
			$pagos_compra->getMonto(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$pagos_compra->setIdPago( $db->Insert_ID() );
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto PagosCompra suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). Restaurarlo solo se puede hacer usando el metodo create(), 
	  * pero el objeto resultante tendra una diferente clave primaria de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente, NotFoundException sera lanzada.
	  *	
	  * @param Objeto El objeto del tipo {@link PagosCompra} a eliminar. 
	  **/
	public static final function delete( &$pagos_compra )
	{
		$sql = "DELETE FROM pagos_compra WHERE  id_pago = ?;";

		$params = array( 
			$pagos_compra->getIdPago(), );

		global $db;

		$db->Execute($sql, $params);
	}


}
