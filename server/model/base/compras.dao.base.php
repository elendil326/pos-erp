<?php
/** Compras Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Compras }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class ComprasDAOBase
{

	/**
	  *	metodo save 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Compras} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara 
	  *	no esta definicda en el objeto, entonces save() creara una nueva fila.
	  *	
	  *	@static
	  * @param Compras [$compras] El objeto de tipo Compras
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$compras )
	{
		if(  $compras->getIdCompra()  )
		{
			return ComprasDAOBase::update( $compras) ;
		}else{
			return ComprasDAOBase::create( $compras) ;
		}
	}


	/**
	  *	Obtener {@link Compras} por llave primaria. 
	  *	
	  * This will create and load {@link Compras} objects contents from database 
	  * using given Primary-Key as identifier. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link Compras}.
	  **/
	public static final function getByPK(  $id_compra )
	{
		$sql = "SELECT * FROM compras WHERE (id_compra = ?) LIMIT 1;";
		$params = array(  $id_compra );
		global $db;
		$rs = $db->GetRow($sql, $params);
		return new Compras( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Compras}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link Compras}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from compras ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new Compras($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Compras} de la base de datos. 
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
	  * @param Objeto Un objeto del tipo {@link Compras}.
	  **/
	public static final function search( $compras )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdCompra() != NULL){
			$sql .= " id_compra = ? AND";
			array_push( $val, $cliente->getIdCompra() );
		}

		if($cliente->getIdProveedor() != NULL){
			$sql .= " id_proveedor = ? AND";
			array_push( $val, $cliente->getIdProveedor() );
		}

		if($cliente->getTipoCompra() != NULL){
			$sql .= " tipo_compra = ? AND";
			array_push( $val, $cliente->getTipoCompra() );
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
    		array_push( $allData, new Compras($foo));
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
	  * @param Objeto El objeto del tipo {@link Compras} a actualizar. 
	  **/
	private static final function update( $compras )
	{
		$sql = "UPDATE compras SET  id_proveedor = ?, tipo_compra = ?, fecha = ?, subtotal = ?, iva = ?, id_sucursal = ?, id_usuario = ? WHERE  id_compra = ?;";
		$params = array( 
			$compras->getIdProveedor(), 
			$compras->getTipoCompra(), 
			$compras->getFecha(), 
			$compras->getSubtotal(), 
			$compras->getIva(), 
			$compras->getIdSucursal(), 
			$compras->getIdUsuario(), 
			$compras->getIdCompra(), );
		global $db;
		$db->Execute($sql, $params);
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Compras suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Compras.
	  *	
	  * @internal private information for advanced developers only
	  * @param Objeto El objeto del tipo {@link Compras} a crear. 
	  **/
	private static final function create( &$compras )
	{
		$sql = "INSERT INTO compras ( id_proveedor, tipo_compra, fecha, subtotal, iva, id_sucursal, id_usuario ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$compras->getIdProveedor(), 
			$compras->getTipoCompra(), 
			$compras->getFecha(), 
			$compras->getSubtotal(), 
			$compras->getIva(), 
			$compras->getIdSucursal(), 
			$compras->getIdUsuario(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		$compras->setIdCompra( $db->Insert_ID() );
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Compras suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). Restaurarlo solo se puede hacer usando el metodo create(), 
	  * pero el objeto resultante tendra una diferente clave primaria de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente, NotFoundException sera lanzada.
	  *	
	  * @param Objeto El objeto del tipo {@link Compras} a eliminar. 
	  **/
	public static final function delete( &$compras )
	{
		$sql = "DELETE FROM compras WHERE  id_compra = ?;";

		$params = array( 
			$compras->getIdCompra(), );

		global $db;

		$db->Execute($sql, $params);
	}


}
