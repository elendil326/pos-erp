<?php
/** FacturaVenta Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link FacturaVenta }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class FacturaVentaDAOBase
{

	/**
	  *	metodo save 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link FacturaVenta} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara 
	  *	no esta definicda en el objeto, entonces save() creara una nueva fila.
	  *	
	  *	@static
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta
	  * @return bool Verdadero si el metodo guardo correctamente este objeto, falso si no.
	  **/
	public static final function save( &$factura_venta )
	{
		if(  $factura_venta->getFolio()  )
		{
			return FacturaVentaDAOBase::update( $factura_venta) ;
		}else{
			return FacturaVentaDAOBase::create( $factura_venta) ;
		}
	}


	/**
	  *	Obtener {@link FacturaVenta} por llave primaria. 
	  *	
	  * This will create and load {@link FacturaVenta} objects contents from database 
	  * using given Primary-Key as identifier. 
	  *	
	  *	@static
	  * @return Objeto Un objeto del tipo {@link FacturaVenta}.
	  **/
	public static final function getByPK(  $folio )
	{
		$sql = "SELECT * FROM factura_venta WHERE (folio = ?) LIMIT 1;";
		$params = array(  $folio );
		global $db;
		$rs = $db->GetRow($sql, $params);
		return new FacturaVenta( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link FacturaVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link FacturaVenta}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from factura_venta ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new FacturaVenta($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link FacturaVenta} de la base de datos. 
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
	  * @param Objeto Un objeto del tipo {@link FacturaVenta}.
	  **/
	public static final function search( $factura_venta )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getFolio() != NULL){
			$sql .= " folio = ? AND";
			array_push( $val, $cliente->getFolio() );
		}

		if($cliente->getIdVenta() != NULL){
			$sql .= " id_venta = ? AND";
			array_push( $val, $cliente->getIdVenta() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new FacturaVenta($foo));
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
	  * @param Objeto El objeto del tipo {@link FacturaVenta} a actualizar. 
	  **/
	private static final function update( $factura_venta )
	{
		$sql = "UPDATE factura_venta SET  id_venta = ? WHERE  folio = ?;";
		$params = array( 
			$factura_venta->getIdVenta(), 
			$factura_venta->getFolio(), );
		global $db;
		$db->Execute($sql, $params);
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto FacturaVenta suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto FacturaVenta.
	  *	
	  * @internal private information for advanced developers only
	  * @param Objeto El objeto del tipo {@link FacturaVenta} a crear. 
	  **/
	private static final function create( &$factura_venta )
	{
		$sql = "INSERT INTO factura_venta ( folio, id_venta ) VALUES ( ?, ?);";
		$params = array( 
			$factura_venta->getFolio(), 
			$factura_venta->getIdVenta(), 
		 );
		global $db;
		$db->Execute($sql, $params);
		
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto FacturaVenta suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). Restaurarlo solo se puede hacer usando el metodo create(), 
	  * pero el objeto resultante tendra una diferente clave primaria de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente, NotFoundException sera lanzada.
	  *	
	  * @param Objeto El objeto del tipo {@link FacturaVenta} a eliminar. 
	  **/
	public static final function delete( &$factura_venta )
	{
		$sql = "DELETE FROM factura_venta WHERE  folio = ?;";

		$params = array( 
			$factura_venta->getFolio(), );

		global $db;

		$db->Execute($sql, $params);
	}


}
