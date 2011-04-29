<?php
/** FacturaVenta Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link FacturaVenta }. 
  * @author Alan Gonzalez
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class FacturaVentaDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_folio ){
			$pk = "";
			$pk .= $id_folio . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_folio){
			$pk = "";
			$pk .= $id_folio . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_folio ){
			$pk = "";
			$pk .= $id_folio . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link FacturaVenta} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$factura_venta )
	{
		if(  self::getByPK(  $factura_venta->getIdFolio() ) !== NULL )
		{
			try{ return FacturaVentaDAOBase::update( $factura_venta) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return FacturaVentaDAOBase::create( $factura_venta) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link FacturaVenta} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link FacturaVenta} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link FacturaVenta Un objeto del tipo {@link FacturaVenta}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_folio )
	{
		if(self::recordExists(  $id_folio)){
			return self::getRecord( $id_folio );
		}
		$sql = "SELECT * FROM factura_venta WHERE (id_folio = ? ) LIMIT 1;";
		$params = array(  $id_folio );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new FacturaVenta( $rs );
			self::pushRecord( $foo,  $id_folio );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link FacturaVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link FacturaVenta}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from factura_venta";
		if($orden != NULL)
		{ $sql .= " ORDER BY " . $orden . " " . $tipo_de_orden;	}
		if($pagina != NULL)
		{
			$sql .= " LIMIT " . (( $pagina - 1 )*$columnas_por_pagina) . "," . $columnas_por_pagina; 
		}
		global $conn;
		$rs = $conn->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
			$bar = new FacturaVenta($foo);
    		array_push( $allData, $bar);
			//id_folio
    		self::pushRecord( $bar, $foo["id_folio"] );
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
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $factura_venta , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from factura_venta WHERE ("; 
		$val = array();
		if( $factura_venta->getIdFolio() != NULL){
			$sql .= " id_folio = ? AND";
			array_push( $val, $factura_venta->getIdFolio() );
		}

		if( $factura_venta->getIdVenta() != NULL){
			$sql .= " id_venta = ? AND";
			array_push( $val, $factura_venta->getIdVenta() );
		}

		if( $factura_venta->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $factura_venta->getIdUsuario() );
		}

		if( $factura_venta->getActiva() != NULL){
			$sql .= " activa = ? AND";
			array_push( $val, $factura_venta->getActiva() );
		}

		if( $factura_venta->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $factura_venta->getFecha() );
		}

		if( $factura_venta->getCertificado() != NULL){
			$sql .= " certificado = ? AND";
			array_push( $val, $factura_venta->getCertificado() );
		}

		if( $factura_venta->getAprovacion() != NULL){
			$sql .= " aprovacion = ? AND";
			array_push( $val, $factura_venta->getAprovacion() );
		}

		if( $factura_venta->getAnioAprovacion() != NULL){
			$sql .= " anio_aprovacion = ? AND";
			array_push( $val, $factura_venta->getAnioAprovacion() );
		}

		if( $factura_venta->getCadenaOriginal() != NULL){
			$sql .= " cadena_original = ? AND";
			array_push( $val, $factura_venta->getCadenaOriginal() );
		}

		if( $factura_venta->getSelloDigital() != NULL){
			$sql .= " sello_digital = ? AND";
			array_push( $val, $factura_venta->getSelloDigital() );
		}

		if( $factura_venta->getSelloDigitalProveedor() != NULL){
			$sql .= " sello_digital_proveedor = ? AND";
			array_push( $val, $factura_venta->getSelloDigitalProveedor() );
		}

		if( $factura_venta->getPac() != NULL){
			$sql .= " pac = ? AND";
			array_push( $val, $factura_venta->getPac() );
		}

		if(sizeof($val) == 0){return array();}
		$sql = substr($sql, 0, -3) . " )";
		if( $orderBy !== null ){
		    $sql .= " order by " . $orderBy . " " . $orden ;
		
		}
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array();
		foreach ($rs as $foo) {
			$bar =  new FacturaVenta($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_folio"] );
		}
		return $ar;
	}


	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cu‡ntas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta a actualizar.
	  **/
	private static final function update( $factura_venta )
	{
		$sql = "UPDATE factura_venta SET  id_venta = ?, id_usuario = ?, activa = ?, fecha = ?, certificado = ?, aprovacion = ?, anio_aprovacion = ?, cadena_original = ?, sello_digital = ?, sello_digital_proveedor = ?, pac = ? WHERE  id_folio = ?;";
		$params = array( 
			$factura_venta->getIdVenta(), 
			$factura_venta->getIdUsuario(), 
			$factura_venta->getActiva(), 
			$factura_venta->getFecha(), 
			$factura_venta->getCertificado(), 
			$factura_venta->getAprovacion(), 
			$factura_venta->getAnioAprovacion(), 
			$factura_venta->getCadenaOriginal(), 
			$factura_venta->getSelloDigital(), 
			$factura_venta->getSelloDigitalProveedor(), 
			$factura_venta->getPac(), 
			$factura_venta->getIdFolio(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto FacturaVenta suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto FacturaVenta dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta a crear.
	  **/
	private static final function create( &$factura_venta )
	{
		$sql = "INSERT INTO factura_venta ( id_folio, id_venta, id_usuario, activa, fecha, certificado, aprovacion, anio_aprovacion, cadena_original, sello_digital, sello_digital_proveedor, pac ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$factura_venta->getIdFolio(), 
			$factura_venta->getIdVenta(), 
			$factura_venta->getIdUsuario(), 
			$factura_venta->getActiva(), 
			$factura_venta->getFecha(), 
			$factura_venta->getCertificado(), 
			$factura_venta->getAprovacion(), 
			$factura_venta->getAnioAprovacion(), 
			$factura_venta->getCadenaOriginal(), 
			$factura_venta->getSelloDigital(), 
			$factura_venta->getSelloDigitalProveedor(), 
			$factura_venta->getPac(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $factura_venta->setIdFolio( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link FacturaVenta} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link FacturaVenta}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $factura_ventaA , $factura_ventaB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from factura_venta WHERE ("; 
		$val = array();
		if( (($a = $factura_ventaA->getIdFolio()) != NULL) & ( ($b = $factura_ventaB->getIdFolio()) != NULL) ){
				$sql .= " id_folio >= ? AND id_folio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_folio = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getIdVenta()) != NULL) & ( ($b = $factura_ventaB->getIdVenta()) != NULL) ){
				$sql .= " id_venta >= ? AND id_venta <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_venta = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getIdUsuario()) != NULL) & ( ($b = $factura_ventaB->getIdUsuario()) != NULL) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getActiva()) != NULL) & ( ($b = $factura_ventaB->getActiva()) != NULL) ){
				$sql .= " activa >= ? AND activa <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " activa = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getFecha()) != NULL) & ( ($b = $factura_ventaB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getCertificado()) != NULL) & ( ($b = $factura_ventaB->getCertificado()) != NULL) ){
				$sql .= " certificado >= ? AND certificado <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " certificado = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getAprovacion()) != NULL) & ( ($b = $factura_ventaB->getAprovacion()) != NULL) ){
				$sql .= " aprovacion >= ? AND aprovacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " aprovacion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getAnioAprovacion()) != NULL) & ( ($b = $factura_ventaB->getAnioAprovacion()) != NULL) ){
				$sql .= " anio_aprovacion >= ? AND anio_aprovacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " anio_aprovacion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getCadenaOriginal()) != NULL) & ( ($b = $factura_ventaB->getCadenaOriginal()) != NULL) ){
				$sql .= " cadena_original >= ? AND cadena_original <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " cadena_original = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getSelloDigital()) != NULL) & ( ($b = $factura_ventaB->getSelloDigital()) != NULL) ){
				$sql .= " sello_digital >= ? AND sello_digital <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " sello_digital = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getSelloDigitalProveedor()) != NULL) & ( ($b = $factura_ventaB->getSelloDigitalProveedor()) != NULL) ){
				$sql .= " sello_digital_proveedor >= ? AND sello_digital_proveedor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " sello_digital_proveedor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getPac()) != NULL) & ( ($b = $factura_ventaB->getPac()) != NULL) ){
				$sql .= " pac >= ? AND pac <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " pac = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		$sql = substr($sql, 0, -3) . " )";
		if( $orderBy !== null ){
		    $sql .= " order by " . $orderBy . " " . $orden ;
		
		}
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array();
		foreach ($rs as $foo) {
    		array_push( $ar, new FacturaVenta($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto FacturaVenta suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta a eliminar
	  **/
	public static final function delete( &$factura_venta )
	{
		if(self::getByPK($factura_venta->getIdFolio()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM factura_venta WHERE  id_folio = ?;";
		$params = array( $factura_venta->getIdFolio() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
