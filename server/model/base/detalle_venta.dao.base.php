<?php
/** DetalleVenta Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link DetalleVenta }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class DetalleVentaDAOBase extends TablaDAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleVenta} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$detalle_venta )
	{
		if( self::getByPK(  $detalle_venta->getIdVenta() , $detalle_venta->getIdProducto() ) === NULL )
		{
			return DetalleVentaDAOBase::create( $detalle_venta) ;
		}else{
			return DetalleVentaDAOBase::update( $detalle_venta) ;
		}
	}


	/**
	  *	Obtener {@link DetalleVenta} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleVenta} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link DetalleVenta}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_venta, $id_producto )
	{
		$sql = "SELECT * FROM detalle_venta WHERE (id_venta = ?,id_producto = ?) LIMIT 1;";
		$params = array(  $id_venta, $id_producto );
		global $db;
		$rs = $db->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new DetalleVenta( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link DetalleVenta}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from detalle_venta ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new DetalleVenta($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleVenta} de la base de datos. 
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
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta
	  **/
	public static final function search( $detalle_venta )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdVenta() != NULL){
			$sql .= " id_venta = ? AND";
			array_push( $val, $cliente->getIdVenta() );
		}

		if($cliente->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $cliente->getIdProducto() );
		}

		if($cliente->getCantidad() != NULL){
			$sql .= " cantidad = ? AND";
			array_push( $val, $cliente->getCantidad() );
		}

		if($cliente->getPrecio() != NULL){
			$sql .= " precio = ? AND";
			array_push( $val, $cliente->getPrecio() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new DetalleVenta($foo));
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
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta a actualizar.
	  **/
	private static final function update( $detalle_venta )
	{
		$sql = "UPDATE detalle_venta SET  cantidad = ?, precio = ? WHERE  id_venta = ? AND id_producto = ?;";
		$params = array( 
			$detalle_venta->getCantidad(), 
			$detalle_venta->getPrecio(), 
			$detalle_venta->getIdVenta(),$detalle_venta->getIdProducto(), );
		global $db;
		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleVenta suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleVenta dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta a crear.
	  **/
	private static final function create( &$detalle_venta )
	{
		$sql = "INSERT INTO detalle_venta ( id_venta, id_producto, cantidad, precio ) VALUES ( ?, ?, ?, ?);";
		$params = array( 
			$detalle_venta->getIdVenta(), 
			$detalle_venta->getIdProducto(), 
			$detalle_venta->getCantidad(), 
			$detalle_venta->getPrecio(), 
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
	  * en el objeto DetalleVenta suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta a eliminar
	  **/
	public static final function delete( &$detalle_venta )
	{
		if(self::getByPK($detalle_venta->getIdVenta(), $detalle_venta->getIdProducto()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_venta WHERE  id_venta = ? AND id_producto = ?;";
		$params = array( $detalle_venta->getIdVenta(), $detalle_venta->getIdProducto() );
		global $db;

		$db->Execute($sql, $params);
		return $db->Affected_Rows();
	}


}
