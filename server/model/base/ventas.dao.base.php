<?php
/** Ventas Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Ventas }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class VentasDAOBase extends DAO
{

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Ventas} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Ventas [$ventas] El objeto de tipo Ventas
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$ventas )
	{
		if(  self::getByPK(  $ventas->getIdVenta() ) !== NULL )
		{
			try{ return VentasDAOBase::update( $ventas) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return VentasDAOBase::create( $ventas) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Ventas} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Ventas} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Ventas Un objeto del tipo {@link Ventas}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_venta )
	{
		$sql = "SELECT * FROM ventas WHERE (id_venta = ? ) LIMIT 1;";
		$params = array(  $id_venta );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
		return new Ventas( $rs );
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Ventas}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Ventas}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from ventas";
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
    		array_push( $allData, new Ventas($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ventas} de la base de datos. 
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
	  * @param Ventas [$ventas] El objeto de tipo Ventas
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $ventas , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from ventas WHERE ("; 
		$val = array();
		if( $ventas->getIdVenta() != NULL){
			$sql .= " id_venta = ? AND";
			array_push( $val, $ventas->getIdVenta() );
		}

		if( $ventas->getIdCliente() != NULL){
			$sql .= " id_cliente = ? AND";
			array_push( $val, $ventas->getIdCliente() );
		}

		if( $ventas->getTipoVenta() != NULL){
			$sql .= " tipo_venta = ? AND";
			array_push( $val, $ventas->getTipoVenta() );
		}

		if( $ventas->getTipoPago() != NULL){
			$sql .= " tipo_pago = ? AND";
			array_push( $val, $ventas->getTipoPago() );
		}

		if( $ventas->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $ventas->getFecha() );
		}

		if( $ventas->getSubtotal() != NULL){
			$sql .= " subtotal = ? AND";
			array_push( $val, $ventas->getSubtotal() );
		}

		if( $ventas->getIva() != NULL){
			$sql .= " iva = ? AND";
			array_push( $val, $ventas->getIva() );
		}

		if( $ventas->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $ventas->getDescuento() );
		}

		if( $ventas->getTotal() != NULL){
			$sql .= " total = ? AND";
			array_push( $val, $ventas->getTotal() );
		}

		if( $ventas->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $ventas->getIdSucursal() );
		}

		if( $ventas->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $ventas->getIdUsuario() );
		}

		if( $ventas->getPagado() != NULL){
			$sql .= " pagado = ? AND";
			array_push( $val, $ventas->getPagado() );
		}

		if( $ventas->getCancelada() != NULL){
			$sql .= " cancelada = ? AND";
			array_push( $val, $ventas->getCancelada() );
		}

		if( $ventas->getIp() != NULL){
			$sql .= " ip = ? AND";
			array_push( $val, $ventas->getIp() );
		}

		if( $ventas->getLiquidada() != NULL){
			$sql .= " liquidada = ? AND";
			array_push( $val, $ventas->getLiquidada() );
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
    		array_push( $ar, new Ventas($foo));
		}
		return $ar;
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
	  * @param Ventas [$ventas] El objeto de tipo Ventas a actualizar.
	  **/
	private static final function update( $ventas )
	{
		$sql = "UPDATE ventas SET  id_cliente = ?, tipo_venta = ?, tipo_pago = ?, fecha = ?, subtotal = ?, iva = ?, descuento = ?, total = ?, id_sucursal = ?, id_usuario = ?, pagado = ?, cancelada = ?, ip = ?, liquidada = ? WHERE  id_venta = ?;";
		$params = array( 
			$ventas->getIdCliente(), 
			$ventas->getTipoVenta(), 
			$ventas->getTipoPago(), 
			$ventas->getFecha(), 
			$ventas->getSubtotal(), 
			$ventas->getIva(), 
			$ventas->getDescuento(), 
			$ventas->getTotal(), 
			$ventas->getIdSucursal(), 
			$ventas->getIdUsuario(), 
			$ventas->getPagado(), 
			$ventas->getCancelada(), 
			$ventas->getIp(), 
			$ventas->getLiquidada(), 
			$ventas->getIdVenta(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Ventas suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Ventas dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Ventas [$ventas] El objeto de tipo Ventas a crear.
	  **/
	private static final function create( &$ventas )
	{
		$sql = "INSERT INTO ventas ( id_venta, id_cliente, tipo_venta, tipo_pago, fecha, subtotal, iva, descuento, total, id_sucursal, id_usuario, pagado, cancelada, ip, liquidada ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$ventas->getIdVenta(), 
			$ventas->getIdCliente(), 
			$ventas->getTipoVenta(), 
			$ventas->getTipoPago(), 
			$ventas->getFecha(), 
			$ventas->getSubtotal(), 
			$ventas->getIva(), 
			$ventas->getDescuento(), 
			$ventas->getTotal(), 
			$ventas->getIdSucursal(), 
			$ventas->getIdUsuario(), 
			$ventas->getPagado(), 
			$ventas->getCancelada(), 
			$ventas->getIp(), 
			$ventas->getLiquidada(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		 $ventas->setIdVenta( $conn->Insert_ID() );
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ventas} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Ventas}.
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
	  * @param Ventas [$ventas] El objeto de tipo Ventas
	  * @param Ventas [$ventas] El objeto de tipo Ventas
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $ventasA , $ventasB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from ventas WHERE ("; 
		$val = array();
		if( (($a = $ventasA->getIdVenta()) != NULL) & ( ($b = $ventasB->getIdVenta()) != NULL) ){
				$sql .= " id_venta >= ? AND id_venta <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_venta = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getIdCliente()) != NULL) & ( ($b = $ventasB->getIdCliente()) != NULL) ){
				$sql .= " id_cliente >= ? AND id_cliente <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_cliente = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getTipoVenta()) != NULL) & ( ($b = $ventasB->getTipoVenta()) != NULL) ){
				$sql .= " tipo_venta >= ? AND tipo_venta <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " tipo_venta = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getTipoPago()) != NULL) & ( ($b = $ventasB->getTipoPago()) != NULL) ){
				$sql .= " tipo_pago >= ? AND tipo_pago <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " tipo_pago = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getFecha()) != NULL) & ( ($b = $ventasB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getSubtotal()) != NULL) & ( ($b = $ventasB->getSubtotal()) != NULL) ){
				$sql .= " subtotal >= ? AND subtotal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " subtotal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getIva()) != NULL) & ( ($b = $ventasB->getIva()) != NULL) ){
				$sql .= " iva >= ? AND iva <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " iva = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getDescuento()) != NULL) & ( ($b = $ventasB->getDescuento()) != NULL) ){
				$sql .= " descuento >= ? AND descuento <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descuento = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getTotal()) != NULL) & ( ($b = $ventasB->getTotal()) != NULL) ){
				$sql .= " total >= ? AND total <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " total = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getIdSucursal()) != NULL) & ( ($b = $ventasB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getIdUsuario()) != NULL) & ( ($b = $ventasB->getIdUsuario()) != NULL) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_usuario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getPagado()) != NULL) & ( ($b = $ventasB->getPagado()) != NULL) ){
				$sql .= " pagado >= ? AND pagado <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " pagado = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getCancelada()) != NULL) & ( ($b = $ventasB->getCancelada()) != NULL) ){
				$sql .= " cancelada >= ? AND cancelada <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " cancelada = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getIp()) != NULL) & ( ($b = $ventasB->getIp()) != NULL) ){
				$sql .= " ip >= ? AND ip <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " ip = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $ventasA->getLiquidada()) != NULL) & ( ($b = $ventasB->getLiquidada()) != NULL) ){
				$sql .= " liquidada >= ? AND liquidada <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " liquidada = ? AND"; 
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
    		array_push( $ar, new Ventas($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Ventas suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Ventas [$ventas] El objeto de tipo Ventas a eliminar
	  **/
	public static final function delete( &$ventas )
	{
		if(self::getByPK($ventas->getIdVenta()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM ventas WHERE  id_venta = ?;";
		$params = array( $ventas->getIdVenta() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
