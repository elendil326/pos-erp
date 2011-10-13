<?php
/** Usuario Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Usuario }. 
  * @author Andres
  * @access private
  * @abstract
  * @package docs
  * 
  */
abstract class UsuarioDAOBase extends DAO
{

		private static $loadedRecords = array();

		private static function recordExists(  $id_usuario ){
			$pk = "";
			$pk .= $id_usuario . "-";
			return array_key_exists ( $pk , self::$loadedRecords );
		}
		private static function pushRecord( $inventario,  $id_usuario){
			$pk = "";
			$pk .= $id_usuario . "-";
			self::$loadedRecords [$pk] = $inventario;
		}
		private static function getRecord(  $id_usuario ){
			$pk = "";
			$pk .= $id_usuario . "-";
			return self::$loadedRecords[$pk];
		}
	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Usuario} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Usuario [$usuario] El objeto de tipo Usuario
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	public static final function save( &$usuario )
	{
		if(  self::getByPK(  $usuario->getIdUsuario() ) !== NULL )
		{
			try{ return UsuarioDAOBase::update( $usuario) ; } catch(Exception $e){ throw $e; }
		}else{
			try{ return UsuarioDAOBase::create( $usuario) ; } catch(Exception $e){ throw $e; }
		}
	}


	/**
	  *	Obtener {@link Usuario} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Usuario} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Usuario Un objeto del tipo {@link Usuario}. NULL si no hay tal registro.
	  **/
	public static final function getByPK(  $id_usuario )
	{
		if(self::recordExists(  $id_usuario)){
			return self::getRecord( $id_usuario );
		}
		$sql = "SELECT * FROM usuario WHERE (id_usuario = ? ) LIMIT 1;";
		$params = array(  $id_usuario );
		global $conn;
		$rs = $conn->GetRow($sql, $params);
		if(count($rs)==0)return NULL;
			$foo = new Usuario( $rs );
			self::pushRecord( $foo,  $id_usuario );
			return $foo;
	}


	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Usuario}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link Usuario}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from usuario";
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
			$bar = new Usuario($foo);
    		array_push( $allData, $bar);
			//id_usuario
    		self::pushRecord( $bar, $foo["id_usuario"] );
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Usuario} de la base de datos. 
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
	  * @param Usuario [$usuario] El objeto de tipo Usuario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function search( $usuario , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from usuario WHERE ("; 
		$val = array();
		if( $usuario->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $usuario->getIdUsuario() );
		}

		if( $usuario->getIdDireccion() != NULL){
			$sql .= " id_direccion = ? AND";
			array_push( $val, $usuario->getIdDireccion() );
		}

		if( $usuario->getIdDireccionAlterna() != NULL){
			$sql .= " id_direccion_alterna = ? AND";
			array_push( $val, $usuario->getIdDireccionAlterna() );
		}

		if( $usuario->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $usuario->getIdSucursal() );
		}

		if( $usuario->getIdRol() != NULL){
			$sql .= " id_rol = ? AND";
			array_push( $val, $usuario->getIdRol() );
		}

		if( $usuario->getIdClasificacionCliente() != NULL){
			$sql .= " id_clasificacion_cliente = ? AND";
			array_push( $val, $usuario->getIdClasificacionCliente() );
		}

		if( $usuario->getIdClasificacionProveedor() != NULL){
			$sql .= " id_clasificacion_proveedor = ? AND";
			array_push( $val, $usuario->getIdClasificacionProveedor() );
		}

		if( $usuario->getIdMoneda() != NULL){
			$sql .= " id_moneda = ? AND";
			array_push( $val, $usuario->getIdMoneda() );
		}

		if( $usuario->getFechaAsignacionRol() != NULL){
			$sql .= " fecha_asignacion_rol = ? AND";
			array_push( $val, $usuario->getFechaAsignacionRol() );
		}

		if( $usuario->getNombre() != NULL){
			$sql .= " nombre = ? AND";
			array_push( $val, $usuario->getNombre() );
		}

		if( $usuario->getRfc() != NULL){
			$sql .= " rfc = ? AND";
			array_push( $val, $usuario->getRfc() );
		}

		if( $usuario->getCurp() != NULL){
			$sql .= " curp = ? AND";
			array_push( $val, $usuario->getCurp() );
		}

		if( $usuario->getComisionVentas() != NULL){
			$sql .= " comision_ventas = ? AND";
			array_push( $val, $usuario->getComisionVentas() );
		}

		if( $usuario->getTelefonoPersonal1() != NULL){
			$sql .= " telefono_personal1 = ? AND";
			array_push( $val, $usuario->getTelefonoPersonal1() );
		}

		if( $usuario->getTelefonoPersonal2() != NULL){
			$sql .= " telefono_personal2 = ? AND";
			array_push( $val, $usuario->getTelefonoPersonal2() );
		}

		if( $usuario->getFechaAlta() != NULL){
			$sql .= " fecha_alta = ? AND";
			array_push( $val, $usuario->getFechaAlta() );
		}

		if( $usuario->getFechaBaja() != NULL){
			$sql .= " fecha_baja = ? AND";
			array_push( $val, $usuario->getFechaBaja() );
		}

		if( $usuario->getActivo() != NULL){
			$sql .= " activo = ? AND";
			array_push( $val, $usuario->getActivo() );
		}

		if( $usuario->getLimiteCredito() != NULL){
			$sql .= " limite_credito = ? AND";
			array_push( $val, $usuario->getLimiteCredito() );
		}

		if( $usuario->getDescuento() != NULL){
			$sql .= " descuento = ? AND";
			array_push( $val, $usuario->getDescuento() );
		}

		if( $usuario->getPassword() != NULL){
			$sql .= " password = ? AND";
			array_push( $val, $usuario->getPassword() );
		}

		if( $usuario->getLastLogin() != NULL){
			$sql .= " last_login = ? AND";
			array_push( $val, $usuario->getLastLogin() );
		}

		if( $usuario->getConsignatario() != NULL){
			$sql .= " consignatario = ? AND";
			array_push( $val, $usuario->getConsignatario() );
		}

		if( $usuario->getSalario() != NULL){
			$sql .= " salario = ? AND";
			array_push( $val, $usuario->getSalario() );
		}

		if( $usuario->getCorreoElectronico() != NULL){
			$sql .= " correo_electronico = ? AND";
			array_push( $val, $usuario->getCorreoElectronico() );
		}

		if( $usuario->getPaginaWeb() != NULL){
			$sql .= " pagina_web = ? AND";
			array_push( $val, $usuario->getPaginaWeb() );
		}

		if( $usuario->getSaldoDelEjercicio() != NULL){
			$sql .= " saldo_del_ejercicio = ? AND";
			array_push( $val, $usuario->getSaldoDelEjercicio() );
		}

		if( $usuario->getVentasACredito() != NULL){
			$sql .= " ventas_a_credito = ? AND";
			array_push( $val, $usuario->getVentasACredito() );
		}

		if( $usuario->getRepresentanteLegal() != NULL){
			$sql .= " representante_legal = ? AND";
			array_push( $val, $usuario->getRepresentanteLegal() );
		}

		if( $usuario->getFacturarATerceros() != NULL){
			$sql .= " facturar_a_terceros = ? AND";
			array_push( $val, $usuario->getFacturarATerceros() );
		}

		if( $usuario->getDiaDePago() != NULL){
			$sql .= " dia_de_pago = ? AND";
			array_push( $val, $usuario->getDiaDePago() );
		}

		if( $usuario->getMensajeria() != NULL){
			$sql .= " mensajeria = ? AND";
			array_push( $val, $usuario->getMensajeria() );
		}

		if( $usuario->getInteresesMoratorios() != NULL){
			$sql .= " intereses_moratorios = ? AND";
			array_push( $val, $usuario->getInteresesMoratorios() );
		}

		if( $usuario->getDenominacionComercial() != NULL){
			$sql .= " denominacion_comercial = ? AND";
			array_push( $val, $usuario->getDenominacionComercial() );
		}

		if( $usuario->getDiasDeCredito() != NULL){
			$sql .= " dias_de_credito = ? AND";
			array_push( $val, $usuario->getDiasDeCredito() );
		}

		if( $usuario->getCuentaDeMensajeria() != NULL){
			$sql .= " cuenta_de_mensajeria = ? AND";
			array_push( $val, $usuario->getCuentaDeMensajeria() );
		}

		if( $usuario->getDiaDeRevision() != NULL){
			$sql .= " dia_de_revision = ? AND";
			array_push( $val, $usuario->getDiaDeRevision() );
		}

		if( $usuario->getCodigoUsuario() != NULL){
			$sql .= " codigo_usuario = ? AND";
			array_push( $val, $usuario->getCodigoUsuario() );
		}

		if( $usuario->getDiasDeEmbarque() != NULL){
			$sql .= " dias_de_embarque = ? AND";
			array_push( $val, $usuario->getDiasDeEmbarque() );
		}

		if( $usuario->getTiempoEntrega() != NULL){
			$sql .= " tiempo_entrega = ? AND";
			array_push( $val, $usuario->getTiempoEntrega() );
		}

		if( $usuario->getCuentaBancaria() != NULL){
			$sql .= " cuenta_bancaria = ? AND";
			array_push( $val, $usuario->getCuentaBancaria() );
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
			$bar =  new Usuario($foo);
    		array_push( $ar,$bar);
    		self::pushRecord( $bar, $foo["id_usuario"] );
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
	  * @param Usuario [$usuario] El objeto de tipo Usuario a actualizar.
	  **/
	private static final function update( $usuario )
	{
		$sql = "UPDATE usuario SET  id_direccion = ?, id_direccion_alterna = ?, id_sucursal = ?, id_rol = ?, id_clasificacion_cliente = ?, id_clasificacion_proveedor = ?, id_moneda = ?, fecha_asignacion_rol = ?, nombre = ?, rfc = ?, curp = ?, comision_ventas = ?, telefono_personal1 = ?, telefono_personal2 = ?, fecha_alta = ?, fecha_baja = ?, activo = ?, limite_credito = ?, descuento = ?, password = ?, last_login = ?, consignatario = ?, salario = ?, correo_electronico = ?, pagina_web = ?, saldo_del_ejercicio = ?, ventas_a_credito = ?, representante_legal = ?, facturar_a_terceros = ?, dia_de_pago = ?, mensajeria = ?, intereses_moratorios = ?, denominacion_comercial = ?, dias_de_credito = ?, cuenta_de_mensajeria = ?, dia_de_revision = ?, codigo_usuario = ?, dias_de_embarque = ?, tiempo_entrega = ?, cuenta_bancaria = ? WHERE  id_usuario = ?;";
		$params = array( 
			$usuario->getIdDireccion(), 
			$usuario->getIdDireccionAlterna(), 
			$usuario->getIdSucursal(), 
			$usuario->getIdRol(), 
			$usuario->getIdClasificacionCliente(), 
			$usuario->getIdClasificacionProveedor(), 
			$usuario->getIdMoneda(), 
			$usuario->getFechaAsignacionRol(), 
			$usuario->getNombre(), 
			$usuario->getRfc(), 
			$usuario->getCurp(), 
			$usuario->getComisionVentas(), 
			$usuario->getTelefonoPersonal1(), 
			$usuario->getTelefonoPersonal2(), 
			$usuario->getFechaAlta(), 
			$usuario->getFechaBaja(), 
			$usuario->getActivo(), 
			$usuario->getLimiteCredito(), 
			$usuario->getDescuento(), 
			$usuario->getPassword(), 
			$usuario->getLastLogin(), 
			$usuario->getConsignatario(), 
			$usuario->getSalario(), 
			$usuario->getCorreoElectronico(), 
			$usuario->getPaginaWeb(), 
			$usuario->getSaldoDelEjercicio(), 
			$usuario->getVentasACredito(), 
			$usuario->getRepresentanteLegal(), 
			$usuario->getFacturarATerceros(), 
			$usuario->getDiaDePago(), 
			$usuario->getMensajeria(), 
			$usuario->getInteresesMoratorios(), 
			$usuario->getDenominacionComercial(), 
			$usuario->getDiasDeCredito(), 
			$usuario->getCuentaDeMensajeria(), 
			$usuario->getDiaDeRevision(), 
			$usuario->getCodigoUsuario(), 
			$usuario->getDiasDeEmbarque(), 
			$usuario->getTiempoEntrega(), 
			$usuario->getCuentaBancaria(), 
			$usuario->getIdUsuario(), );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		return $conn->Affected_Rows();
	}


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Usuario suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Usuario dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Usuario [$usuario] El objeto de tipo Usuario a crear.
	  **/
	private static final function create( &$usuario )
	{
		$sql = "INSERT INTO usuario ( id_usuario, id_direccion, id_direccion_alterna, id_sucursal, id_rol, id_clasificacion_cliente, id_clasificacion_proveedor, id_moneda, fecha_asignacion_rol, nombre, rfc, curp, comision_ventas, telefono_personal1, telefono_personal2, fecha_alta, fecha_baja, activo, limite_credito, descuento, password, last_login, consignatario, salario, correo_electronico, pagina_web, saldo_del_ejercicio, ventas_a_credito, representante_legal, facturar_a_terceros, dia_de_pago, mensajeria, intereses_moratorios, denominacion_comercial, dias_de_credito, cuenta_de_mensajeria, dia_de_revision, codigo_usuario, dias_de_embarque, tiempo_entrega, cuenta_bancaria ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = array( 
			$usuario->getIdUsuario(), 
			$usuario->getIdDireccion(), 
			$usuario->getIdDireccionAlterna(), 
			$usuario->getIdSucursal(), 
			$usuario->getIdRol(), 
			$usuario->getIdClasificacionCliente(), 
			$usuario->getIdClasificacionProveedor(), 
			$usuario->getIdMoneda(), 
			$usuario->getFechaAsignacionRol(), 
			$usuario->getNombre(), 
			$usuario->getRfc(), 
			$usuario->getCurp(), 
			$usuario->getComisionVentas(), 
			$usuario->getTelefonoPersonal1(), 
			$usuario->getTelefonoPersonal2(), 
			$usuario->getFechaAlta(), 
			$usuario->getFechaBaja(), 
			$usuario->getActivo(), 
			$usuario->getLimiteCredito(), 
			$usuario->getDescuento(), 
			$usuario->getPassword(), 
			$usuario->getLastLogin(), 
			$usuario->getConsignatario(), 
			$usuario->getSalario(), 
			$usuario->getCorreoElectronico(), 
			$usuario->getPaginaWeb(), 
			$usuario->getSaldoDelEjercicio(), 
			$usuario->getVentasACredito(), 
			$usuario->getRepresentanteLegal(), 
			$usuario->getFacturarATerceros(), 
			$usuario->getDiaDePago(), 
			$usuario->getMensajeria(), 
			$usuario->getInteresesMoratorios(), 
			$usuario->getDenominacionComercial(), 
			$usuario->getDiasDeCredito(), 
			$usuario->getCuentaDeMensajeria(), 
			$usuario->getDiaDeRevision(), 
			$usuario->getCodigoUsuario(), 
			$usuario->getDiasDeEmbarque(), 
			$usuario->getTiempoEntrega(), 
			$usuario->getCuentaBancaria(), 
		 );
		global $conn;
		try{$conn->Execute($sql, $params);}
		catch(Exception $e){ throw new Exception ($e->getMessage()); }
		$ar = $conn->Affected_Rows();
		if($ar == 0) return 0;
		/* save autoincremented value on obj */  $usuario->setIdUsuario( $conn->Insert_ID() ); /*  */ 
		return $ar;
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Usuario} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Usuario}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda (los valores 0 y false no son tomados como NULL) .
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
	  * @param Usuario [$usuario] El objeto de tipo Usuario
	  * @param Usuario [$usuario] El objeto de tipo Usuario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	public static final function byRange( $usuarioA , $usuarioB , $orderBy = null, $orden = 'ASC')
	{
		$sql = "SELECT * from usuario WHERE ("; 
		$val = array();
		if( (($a = $usuarioA->getIdUsuario()) !== NULL) & ( ($b = $usuarioB->getIdUsuario()) !== NULL) ){
				$sql .= " id_usuario >= ? AND id_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_usuario = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getIdDireccion()) !== NULL) & ( ($b = $usuarioB->getIdDireccion()) !== NULL) ){
				$sql .= " id_direccion >= ? AND id_direccion <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_direccion = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getIdDireccionAlterna()) !== NULL) & ( ($b = $usuarioB->getIdDireccionAlterna()) !== NULL) ){
				$sql .= " id_direccion_alterna >= ? AND id_direccion_alterna <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_direccion_alterna = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getIdSucursal()) !== NULL) & ( ($b = $usuarioB->getIdSucursal()) !== NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getIdRol()) !== NULL) & ( ($b = $usuarioB->getIdRol()) !== NULL) ){
				$sql .= " id_rol >= ? AND id_rol <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_rol = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getIdClasificacionCliente()) !== NULL) & ( ($b = $usuarioB->getIdClasificacionCliente()) !== NULL) ){
				$sql .= " id_clasificacion_cliente >= ? AND id_clasificacion_cliente <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_clasificacion_cliente = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getIdClasificacionProveedor()) !== NULL) & ( ($b = $usuarioB->getIdClasificacionProveedor()) !== NULL) ){
				$sql .= " id_clasificacion_proveedor >= ? AND id_clasificacion_proveedor <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_clasificacion_proveedor = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getIdMoneda()) !== NULL) & ( ($b = $usuarioB->getIdMoneda()) !== NULL) ){
				$sql .= " id_moneda >= ? AND id_moneda <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " id_moneda = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getFechaAsignacionRol()) !== NULL) & ( ($b = $usuarioB->getFechaAsignacionRol()) !== NULL) ){
				$sql .= " fecha_asignacion_rol >= ? AND fecha_asignacion_rol <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " fecha_asignacion_rol = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getNombre()) !== NULL) & ( ($b = $usuarioB->getNombre()) !== NULL) ){
				$sql .= " nombre >= ? AND nombre <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " nombre = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getRfc()) !== NULL) & ( ($b = $usuarioB->getRfc()) !== NULL) ){
				$sql .= " rfc >= ? AND rfc <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " rfc = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getCurp()) !== NULL) & ( ($b = $usuarioB->getCurp()) !== NULL) ){
				$sql .= " curp >= ? AND curp <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " curp = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getComisionVentas()) !== NULL) & ( ($b = $usuarioB->getComisionVentas()) !== NULL) ){
				$sql .= " comision_ventas >= ? AND comision_ventas <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " comision_ventas = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getTelefonoPersonal1()) !== NULL) & ( ($b = $usuarioB->getTelefonoPersonal1()) !== NULL) ){
				$sql .= " telefono_personal1 >= ? AND telefono_personal1 <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " telefono_personal1 = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getTelefonoPersonal2()) !== NULL) & ( ($b = $usuarioB->getTelefonoPersonal2()) !== NULL) ){
				$sql .= " telefono_personal2 >= ? AND telefono_personal2 <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " telefono_personal2 = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getFechaAlta()) !== NULL) & ( ($b = $usuarioB->getFechaAlta()) !== NULL) ){
				$sql .= " fecha_alta >= ? AND fecha_alta <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " fecha_alta = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getFechaBaja()) !== NULL) & ( ($b = $usuarioB->getFechaBaja()) !== NULL) ){
				$sql .= " fecha_baja >= ? AND fecha_baja <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " fecha_baja = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getActivo()) !== NULL) & ( ($b = $usuarioB->getActivo()) !== NULL) ){
				$sql .= " activo >= ? AND activo <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " activo = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getLimiteCredito()) !== NULL) & ( ($b = $usuarioB->getLimiteCredito()) !== NULL) ){
				$sql .= " limite_credito >= ? AND limite_credito <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " limite_credito = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getDescuento()) !== NULL) & ( ($b = $usuarioB->getDescuento()) !== NULL) ){
				$sql .= " descuento >= ? AND descuento <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " descuento = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getPassword()) !== NULL) & ( ($b = $usuarioB->getPassword()) !== NULL) ){
				$sql .= " password >= ? AND password <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " password = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getLastLogin()) !== NULL) & ( ($b = $usuarioB->getLastLogin()) !== NULL) ){
				$sql .= " last_login >= ? AND last_login <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " last_login = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getConsignatario()) !== NULL) & ( ($b = $usuarioB->getConsignatario()) !== NULL) ){
				$sql .= " consignatario >= ? AND consignatario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " consignatario = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getSalario()) !== NULL) & ( ($b = $usuarioB->getSalario()) !== NULL) ){
				$sql .= " salario >= ? AND salario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " salario = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getCorreoElectronico()) !== NULL) & ( ($b = $usuarioB->getCorreoElectronico()) !== NULL) ){
				$sql .= " correo_electronico >= ? AND correo_electronico <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " correo_electronico = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getPaginaWeb()) !== NULL) & ( ($b = $usuarioB->getPaginaWeb()) !== NULL) ){
				$sql .= " pagina_web >= ? AND pagina_web <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " pagina_web = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getSaldoDelEjercicio()) !== NULL) & ( ($b = $usuarioB->getSaldoDelEjercicio()) !== NULL) ){
				$sql .= " saldo_del_ejercicio >= ? AND saldo_del_ejercicio <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " saldo_del_ejercicio = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getVentasACredito()) !== NULL) & ( ($b = $usuarioB->getVentasACredito()) !== NULL) ){
				$sql .= " ventas_a_credito >= ? AND ventas_a_credito <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " ventas_a_credito = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getRepresentanteLegal()) !== NULL) & ( ($b = $usuarioB->getRepresentanteLegal()) !== NULL) ){
				$sql .= " representante_legal >= ? AND representante_legal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " representante_legal = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getFacturarATerceros()) !== NULL) & ( ($b = $usuarioB->getFacturarATerceros()) !== NULL) ){
				$sql .= " facturar_a_terceros >= ? AND facturar_a_terceros <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " facturar_a_terceros = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getDiaDePago()) !== NULL) & ( ($b = $usuarioB->getDiaDePago()) !== NULL) ){
				$sql .= " dia_de_pago >= ? AND dia_de_pago <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " dia_de_pago = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getMensajeria()) !== NULL) & ( ($b = $usuarioB->getMensajeria()) !== NULL) ){
				$sql .= " mensajeria >= ? AND mensajeria <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " mensajeria = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getInteresesMoratorios()) !== NULL) & ( ($b = $usuarioB->getInteresesMoratorios()) !== NULL) ){
				$sql .= " intereses_moratorios >= ? AND intereses_moratorios <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " intereses_moratorios = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getDenominacionComercial()) !== NULL) & ( ($b = $usuarioB->getDenominacionComercial()) !== NULL) ){
				$sql .= " denominacion_comercial >= ? AND denominacion_comercial <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " denominacion_comercial = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getDiasDeCredito()) !== NULL) & ( ($b = $usuarioB->getDiasDeCredito()) !== NULL) ){
				$sql .= " dias_de_credito >= ? AND dias_de_credito <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " dias_de_credito = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getCuentaDeMensajeria()) !== NULL) & ( ($b = $usuarioB->getCuentaDeMensajeria()) !== NULL) ){
				$sql .= " cuenta_de_mensajeria >= ? AND cuenta_de_mensajeria <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " cuenta_de_mensajeria = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getDiaDeRevision()) !== NULL) & ( ($b = $usuarioB->getDiaDeRevision()) !== NULL) ){
				$sql .= " dia_de_revision >= ? AND dia_de_revision <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " dia_de_revision = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getCodigoUsuario()) !== NULL) & ( ($b = $usuarioB->getCodigoUsuario()) !== NULL) ){
				$sql .= " codigo_usuario >= ? AND codigo_usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " codigo_usuario = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getDiasDeEmbarque()) !== NULL) & ( ($b = $usuarioB->getDiasDeEmbarque()) !== NULL) ){
				$sql .= " dias_de_embarque >= ? AND dias_de_embarque <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " dias_de_embarque = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getTiempoEntrega()) !== NULL) & ( ($b = $usuarioB->getTiempoEntrega()) !== NULL) ){
				$sql .= " tiempo_entrega >= ? AND tiempo_entrega <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " tiempo_entrega = ? AND"; 
			$a = $a === NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $usuarioA->getCuentaBancaria()) !== NULL) & ( ($b = $usuarioB->getCuentaBancaria()) !== NULL) ){
				$sql .= " cuenta_bancaria >= ? AND cuenta_bancaria <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a !== NULL|| $b !== NULL ){
			$sql .= " cuenta_bancaria = ? AND"; 
			$a = $a === NULL ? $b : $a;
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
    		array_push( $ar, new Usuario($foo));
		}
		return $ar;
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Usuario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return int El numero de filas afectadas.
	  * @param Usuario [$usuario] El objeto de tipo Usuario a eliminar
	  **/
	public static final function delete( &$usuario )
	{
		if(self::getByPK($usuario->getIdUsuario()) === NULL) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM usuario WHERE  id_usuario = ?;";
		$params = array( $usuario->getIdUsuario() );
		global $conn;

		$conn->Execute($sql, $params);
		return $conn->Affected_Rows();
	}


}
