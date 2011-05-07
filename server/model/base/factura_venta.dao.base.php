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

		if( $factura_venta->getXml() != NULL){
			$sql .= " xml = ? AND";
			array_push( $val, $factura_venta->getXml() );
		}

		if( $factura_venta->getLugarEmision() != NULL){
			$sql .= " lugar_emision = ? AND";
			array_push( $val, $factura_venta->getLugarEmision() );
		}

		if( $factura_venta->getTipoComprobante() != NULL){
			$sql .= " tipo_comprobante = ? AND";
			array_push( $val, $factura_venta->getTipoComprobante() );
		}

		if( $factura_venta->getActiva() != NULL){
			$sql .= " activa = ? AND";
			array_push( $val, $factura_venta->getActiva() );
		}

		if( $factura_venta->getSellada() != NULL){
			$sql .= " sellada = ? AND";
			array_push( $val, $factura_venta->getSellada() );
		}

		if( $factura_venta->getFormaPago() != NULL){
			$sql .= " forma_pago = ? AND";
			array_push( $val, $factura_venta->getFormaPago() );
		}

		if( $factura_venta->getFechaEmision() != NULL){
			$sql .= " fecha_emision = ? AND";
			array_push( $val, $factura_venta->getFechaEmision() );
		}

		if( $factura_venta->getFolioFiscal() != NULL){
			$sql .= " folio_fiscal = ? AND";
			array_push( $val, $factura_venta->getFolioFiscal() );
		}

		if( $factura_venta->getFechaCertificacion() != NULL){
			$sql .= " fecha_certificacion = ? AND";
			array_push( $val, $factura_venta->getFechaCertificacion() );
		}

		if( $factura_venta->getNumeroCertificadoSat() != NULL){
			$sql .= " numero_certificado_sat = ? AND";
			array_push( $val, $factura_venta->getNumeroCertificadoSat() );
		}

		if( $factura_venta->getSelloDigitalEmisor() != NULL){
			$sql .= " sello_digital_emisor = ? AND";
			array_push( $val, $factura_venta->getSelloDigitalEmisor() );
		}

		if( $factura_venta->getSelloDigitalSat() != NULL){
			$sql .= " sello_digital_sat = ? AND";
			array_push( $val, $factura_venta->getSelloDigitalSat() );
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
		$sql = "UPDATE factura_venta SET  id_venta = ?, id_usuario = ?, xml = ?, lugar_emision = ?, tipo_comprobante = ?, activa = ?, sellada = ?, forma_pago = ?, fecha_emision = ?, folio_fiscal = ?, fecha_certificacion = ?, numero_certificado_sat = ?, sello_digital_emisor = ?, sello_digital_sat = ? WHERE  id_folio = ?;";
		$params = array( 
			$factura_venta->getIdVenta(), 
			$factura_venta->getIdUsuario(), 
			$factura_venta->getXml(), 
			$factura_venta->getLugarEmision(), 
			$factura_venta->getTipoComprobante(), 
			$factura_venta->getActiva(), 
			$factura_venta->getSellada(), 
			$factura_venta->getFormaPago(), 
			$factura_venta->getFechaEmision(), 
			$factura_venta->getFolioFiscal(), 
			$factura_venta->getFechaCertificacion(), 
			$factura_venta->getNumeroCertificadoSat(), 
			$factura_venta->getSelloDigitalEmisor(), 
			$factura_venta->getSelloDigitalSat(), 
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
		$sql = "INSERT INTO factura_venta ( id_folio, id_venta, id_usuario, xml, lugar_emision, tipo_comprobante, activa, sellada, forma_pago, fecha_emision, folio_fiscal, fecha_certificacion, numero_certificado_sat, sello_digital_emisor, sello_digital_sat ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$factura_venta->getIdFolio(), 
			$factura_venta->getIdVenta(), 
			$factura_venta->getIdUsuario(), 
			$factura_venta->getXml(), 
			$factura_venta->getLugarEmision(), 
			$factura_venta->getTipoComprobante(), 
			$factura_venta->getActiva(), 
			$factura_venta->getSellada(), 
			$factura_venta->getFormaPago(), 
			$factura_venta->getFechaEmision(), 
			$factura_venta->getFolioFiscal(), 
			$factura_venta->getFechaCertificacion(), 
			$factura_venta->getNumeroCertificadoSat(), 
			$factura_venta->getSelloDigitalEmisor(), 
			$factura_venta->getSelloDigitalSat(), 
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

		if( (($a = $factura_ventaA->getXml()) != NULL) & ( ($b = $factura_ventaB->getXml()) != NULL) ){
				$sql .= " xml >= ? AND xml <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " xml = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getLugarEmision()) != NULL) & ( ($b = $factura_ventaB->getLugarEmision()) != NULL) ){
				$sql .= " lugar_emision >= ? AND lugar_emision <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " lugar_emision = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getTipoComprobante()) != NULL) & ( ($b = $factura_ventaB->getTipoComprobante()) != NULL) ){
				$sql .= " tipo_comprobante >= ? AND tipo_comprobante <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " tipo_comprobante = ? AND"; 
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

		if( (($a = $factura_ventaA->getSellada()) != NULL) & ( ($b = $factura_ventaB->getSellada()) != NULL) ){
				$sql .= " sellada >= ? AND sellada <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " sellada = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getFormaPago()) != NULL) & ( ($b = $factura_ventaB->getFormaPago()) != NULL) ){
				$sql .= " forma_pago >= ? AND forma_pago <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " forma_pago = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getFechaEmision()) != NULL) & ( ($b = $factura_ventaB->getFechaEmision()) != NULL) ){
				$sql .= " fecha_emision >= ? AND fecha_emision <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha_emision = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getFolioFiscal()) != NULL) & ( ($b = $factura_ventaB->getFolioFiscal()) != NULL) ){
				$sql .= " folio_fiscal >= ? AND folio_fiscal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " folio_fiscal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getFechaCertificacion()) != NULL) & ( ($b = $factura_ventaB->getFechaCertificacion()) != NULL) ){
				$sql .= " fecha_certificacion >= ? AND fecha_certificacion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha_certificacion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getNumeroCertificadoSat()) != NULL) & ( ($b = $factura_ventaB->getNumeroCertificadoSat()) != NULL) ){
				$sql .= " numero_certificado_sat >= ? AND numero_certificado_sat <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " numero_certificado_sat = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getSelloDigitalEmisor()) != NULL) & ( ($b = $factura_ventaB->getSelloDigitalEmisor()) != NULL) ){
				$sql .= " sello_digital_emisor >= ? AND sello_digital_emisor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " sello_digital_emisor = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $factura_ventaA->getSelloDigitalSat()) != NULL) & ( ($b = $factura_ventaB->getSelloDigitalSat()) != NULL) ){
				$sql .= " sello_digital_sat >= ? AND sello_digital_sat <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " sello_digital_sat = ? AND"; 
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
