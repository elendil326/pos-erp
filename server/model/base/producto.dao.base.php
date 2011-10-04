<?php
/** Producto Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Producto }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class ProductoDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_producto ){
			$pk = "";
			$pk .= $id_producto . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_producto){
			$pk = "";
			$pk .= $id_producto . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_producto ){
			$pk = "";
			$pk .= $id_producto . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Producto} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Producto [$producto] El objeto de tipo Producto
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$producto )
	{
		if(  self::getByPK(  $producto->getIdProducto() ) !== NULL )
		{
			try{ return ProductoDAOBase::update( $producto) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return ProductoDAOBase::create( $producto) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Producto} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Producto} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Producto Un objeto del tipo {@link Producto}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_producto )
	{
		if(self::recordExists(  $id_producto)){
			return self::getRecord( $id_producto );
		}
		$sql = "SELECT * FROM producto WHERE (id_producto = ? ) LIMIT 1;";
		$params = array(  $id_producto );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Producto( $rs );
			self::pushRecord( $foo,  $id_producto );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Producto}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Producto}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from producto";
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
			$bar = new Producto($foo);
    		array_push( $allData, $bar);
			//id_producto
    		self::pushRecord( $bar, $foo["id_producto"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Producto} de la base de datos. 
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
	  * @param Producto [$producto] El objeto de tipo Producto
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $producto , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from producto WHERE ("; 
		$val = array();
		if( $producto->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $producto->getIdProducto() );
		}

		if( $producto->getCompraEnMostrador() != NULL){
			$sql .= " compra_en_mostrador = ? AND";
			array_push( $val, $producto->getCompraEnMostrador() );
		}

		if( $producto->getMetodoCosteo() != NULL){
			$sql .= " metodo_costeo = ? AND";
			array_push( $val, $producto->getMetodoCosteo() );
		}

		if( $producto->getActivo() != NULL){
			$sql .= " activo = ? AND";
			array_push( $val, $producto->getActivo() );
		}

		if( $producto->getCodigoProducto() != NULL){
			$sql .= " codigo_producto = ? AND";
			array_push( $val, $producto->getCodigoProducto() );
		}

		if( $producto->getNombreProducto() != NULL){
			$sql .= " nombre_producto = ? AND";
			array_push( $val, $producto->getNombreProducto() );
		}

		if( $producto->getGarantia() != NULL){
			$sql .= " garantia = ? AND";
			array_push( $val, $producto->getGarantia() );
		}

		if( $producto->getCostoEstandar() != NULL){
			$sql .= " costo_estandar = ? AND";
			array_push( $val, $producto->getCostoEstandar() );
		}

		if( $producto->getControlDeExistencia() != NULL){
			$sql .= " control_de_existencia = ? AND";
			array_push( $val, $producto->getControlDeExistencia() );
		}

		if( $producto->getMargenDeUtilidad() != NULL){
			$sql .= " margen_de_utilidad = ? AND";
			array_push( $val, $producto->getMargenDeUtilidad() );
		}

		if( $producto->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $producto->getDescuento() );
		}

		if( $producto->getDescripcion() != NULL){
			$sql .= " descripcion = ? AND";
			array_push( $val, $producto->getDescripcion() );
		}

		if( $producto->getFotoDelProducto() != NULL){
			$sql .= " foto_del_producto = ? AND";
			array_push( $val, $producto->getFotoDelProducto() );
		}

		if( $producto->getCostoExtraAlmacen() != NULL){
			$sql .= " costo_extra_almacen = ? AND";
			array_push( $val, $producto->getCostoExtraAlmacen() );
		}

		if( $producto->getCodigoDeBarras() != NULL){
			$sql .= " codigo_de_barras = ? AND";
			array_push( $val, $producto->getCodigoDeBarras() );
		}

		if( $producto->getPesoProducto() != NULL){
			$sql .= " peso_producto = ? AND";
			array_push( $val, $producto->getPesoProducto() );
		}

		if( $producto->getIdUnidadNoConvertible() != NULL){
			$sql .= " id_unidad_no_convertible = ? AND";
			array_push( $val, $producto->getIdUnidadNoConvertible() );
		}

		if( $producto->getIdUnidadConvertible() != NULL){
			$sql .= " id_unidad_convertible = ? AND";
			array_push( $val, $producto->getIdUnidadConvertible() );
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
			$bar =  new Producto($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_producto"] );
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
	  * @param Producto [$producto] El objeto de tipo Producto a actualizar.
	  **/
	private static final function update( $producto )
	{
		$sql = "UPDATE producto SET  compra_en_mostrador = ?, metodo_costeo = ?, activo = ?, codigo_producto = ?, nombre_producto = ?, garantia = ?, costo_estandar = ?, control_de_existencia = ?, margen_de_utilidad = ?, descuento = ?, descripcion = ?, foto_del_producto = ?, costo_extra_almacen = ?, codigo_de_barras = ?, peso_producto = ?, id_unidad_no_convertible = ?, id_unidad_convertible = ? WHERE  id_producto = ?;";
		$params = array( 
			$producto->getCompraEnMostrador(), 
			$producto->getMetodoCosteo(), 
			$producto->getActivo(), 
			$producto->getCodigoProducto(), 
			$producto->getNombreProducto(), 
			$producto->getGarantia(), 
			$producto->getCostoEstandar(), 
			$producto->getControlDeExistencia(), 
			$producto->getMargenDeUtilidad(), 
			$producto->getDescuento(), 
			$producto->getDescripcion(), 
			$producto->getFotoDelProducto(), 
			$producto->getCostoExtraAlmacen(), 
			$producto->getCodigoDeBarras(), 
			$producto->getPesoProducto(), 
			$producto->getIdUnidadNoConvertible(), 
			$producto->getIdUnidadConvertible(), 
			$producto->getIdProducto(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Producto suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Producto dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Producto [$producto] El objeto de tipo Producto a crear.
	  **/
	private static final function create( &$producto )
	{
		$sql = "INSERT INTO producto ( id_producto, compra_en_mostrador, metodo_costeo, activo, codigo_producto, nombre_producto, garantia, costo_estandar, control_de_existencia, margen_de_utilidad, descuento, descripcion, foto_del_producto, costo_extra_almacen, codigo_de_barras, peso_producto, id_unidad_no_convertible, id_unidad_convertible ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$producto->getIdProducto(), 
			$producto->getCompraEnMostrador(), 
			$producto->getMetodoCosteo(), 
			$producto->getActivo(), 
			$producto->getCodigoProducto(), 
			$producto->getNombreProducto(), 
			$producto->getGarantia(), 
			$producto->getCostoEstandar(), 
			$producto->getControlDeExistencia(), 
			$producto->getMargenDeUtilidad(), 
			$producto->getDescuento(), 
			$producto->getDescripcion(), 
			$producto->getFotoDelProducto(), 
			$producto->getCostoExtraAlmacen(), 
			$producto->getCodigoDeBarras(), 
			$producto->getPesoProducto(), 
			$producto->getIdUnidadNoConvertible(), 
			$producto->getIdUnidadConvertible(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $producto->setIdProducto( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Producto} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Producto}.
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
	  * @param Producto [$producto] El objeto de tipo Producto
	  * @param Producto [$producto] El objeto de tipo Producto
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $productoA , $productoB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from producto WHERE ("; 
		$val = array();
		if( (($a = $productoA->getIdProducto()) != NULL) & ( ($b = $productoB->getIdProducto()) != NULL) ){
				$sql .= " id_producto >= ? AND id_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getCompraEnMostrador()) != NULL) & ( ($b = $productoB->getCompraEnMostrador()) != NULL) ){
				$sql .= " compra_en_mostrador >= ? AND compra_en_mostrador <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " compra_en_mostrador = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getMetodoCosteo()) != NULL) & ( ($b = $productoB->getMetodoCosteo()) != NULL) ){
				$sql .= " metodo_costeo >= ? AND metodo_costeo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " metodo_costeo = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getActivo()) != NULL) & ( ($b = $productoB->getActivo()) != NULL) ){
				$sql .= " activo >= ? AND activo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " activo = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getCodigoProducto()) != NULL) & ( ($b = $productoB->getCodigoProducto()) != NULL) ){
				$sql .= " codigo_producto >= ? AND codigo_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " codigo_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getNombreProducto()) != NULL) & ( ($b = $productoB->getNombreProducto()) != NULL) ){
				$sql .= " nombre_producto >= ? AND nombre_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " nombre_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getGarantia()) != NULL) & ( ($b = $productoB->getGarantia()) != NULL) ){
				$sql .= " garantia >= ? AND garantia <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " garantia = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getCostoEstandar()) != NULL) & ( ($b = $productoB->getCostoEstandar()) != NULL) ){
				$sql .= " costo_estandar >= ? AND costo_estandar <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " costo_estandar = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getControlDeExistencia()) != NULL) & ( ($b = $productoB->getControlDeExistencia()) != NULL) ){
				$sql .= " control_de_existencia >= ? AND control_de_existencia <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " control_de_existencia = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getMargenDeUtilidad()) != NULL) & ( ($b = $productoB->getMargenDeUtilidad()) != NULL) ){
				$sql .= " margen_de_utilidad >= ? AND margen_de_utilidad <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " margen_de_utilidad = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getDescuento()) != NULL) & ( ($b = $productoB->getDescuento()) != NULL) ){
				$sql .= " descuento >= ? AND descuento <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descuento = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getDescripcion()) != NULL) & ( ($b = $productoB->getDescripcion()) != NULL) ){
				$sql .= " descripcion >= ? AND descripcion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " descripcion = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getFotoDelProducto()) != NULL) & ( ($b = $productoB->getFotoDelProducto()) != NULL) ){
				$sql .= " foto_del_producto >= ? AND foto_del_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " foto_del_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getCostoExtraAlmacen()) != NULL) & ( ($b = $productoB->getCostoExtraAlmacen()) != NULL) ){
				$sql .= " costo_extra_almacen >= ? AND costo_extra_almacen <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " costo_extra_almacen = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getCodigoDeBarras()) != NULL) & ( ($b = $productoB->getCodigoDeBarras()) != NULL) ){
				$sql .= " codigo_de_barras >= ? AND codigo_de_barras <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " codigo_de_barras = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getPesoProducto()) != NULL) & ( ($b = $productoB->getPesoProducto()) != NULL) ){
				$sql .= " peso_producto >= ? AND peso_producto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " peso_producto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getIdUnidadNoConvertible()) != NULL) & ( ($b = $productoB->getIdUnidadNoConvertible()) != NULL) ){
				$sql .= " id_unidad_no_convertible >= ? AND id_unidad_no_convertible <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_unidad_no_convertible = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $productoA->getIdUnidadConvertible()) != NULL) & ( ($b = $productoB->getIdUnidadConvertible()) != NULL) ){
				$sql .= " id_unidad_convertible >= ? AND id_unidad_convertible <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_unidad_convertible = ? AND"; 
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
    		array_push( $ar, new Producto($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Producto suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Producto [$producto] El objeto de tipo Producto a eliminar
	  **/
	public static final function delete( &$producto )
	{
		if(self::getByPK($producto->getIdProducto()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM producto WHERE  id_producto = ?;";
		$params = array( $producto->getIdProducto() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
