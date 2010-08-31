<?php
/** ViewGastos Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * <b>recuperar</b> instancias de objetos {@link ViewGastos }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class ViewGastosDAOBase extends VistaDAO
{

	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ViewGastos}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  * @return Array Un arreglo que contiene objetos del tipo {@link ViewGastos}.
	  **/
	public static final function getAll( $pagina = NULL, $columnas_por_pagina = NULL, $orden = NULL, $tipo_de_orden = 'ASC' )
	{
		$sql = "SELECT * from view_gastos";
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
    		array_push( $allData, new ViewGastos($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ViewGastos} de la base de datos. 
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
	  * @param ViewGastos [$view_gastos] El objeto de tipo ViewGastos
	  * @param bool [$json] Verdadero para obtener los resultados en forma JSON y no objetos. En caso de no presentare este parametro se tomara el valor default de false.
	  **/
	public static final function search( $view_gastos , $json = false)
	{
		$sql = "SELECT * from view_gastos WHERE ("; 
		$val = array();
		if( $view_gastos->getIdGasto() != NULL){
			$sql .= " id_gasto = ? AND";
			array_push( $val, $view_gastos->getIdGasto() );
		}

		if( $view_gastos->getConcepto() != NULL){
			$sql .= " concepto = ? AND";
			array_push( $val, $view_gastos->getConcepto() );
		}

		if( $view_gastos->getMonto() != NULL){
			$sql .= " monto = ? AND";
			array_push( $val, $view_gastos->getMonto() );
		}

		if( $view_gastos->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $view_gastos->getFecha() );
		}

		if( $view_gastos->getSucursal() != NULL){
			$sql .= " sucursal = ? AND";
			array_push( $val, $view_gastos->getSucursal() );
		}

		if( $view_gastos->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $view_gastos->getIdSucursal() );
		}

		if( $view_gastos->getUsuario() != NULL){
			$sql .= " usuario = ? AND";
			array_push( $val, $view_gastos->getUsuario() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		if($json === false){
			$ar = array();
			foreach ($rs as $foo) {
    			array_push( $ar, new ViewGastos($foo));
			}
			return $ar;
		}else{
			$allData = '[';
			foreach ($rs as $foo) {
    			$allData .= new ViewGastos($foo) . ',';
			}
    		$allData = substr($allData, 0 , -1) . ']';
			return $allData;
		}
	}


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ViewGastos} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link ViewGastos}.
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
	  * @param ViewGastos [$view_gastos] El objeto de tipo ViewGastos
	  * @param ViewGastos [$view_gastos] El objeto de tipo ViewGastos
	  * @param bool [$json] Verdadero para obtener los resultados en forma JSON y no objetos. En caso de no presentare este parametro se tomara el valor default de false.
	  **/
	public static final function byRange( $view_gastosA , $view_gastosB , $json = false)
	{
		$sql = "SELECT * from view_gastos WHERE ("; 
		$val = array();
		if( (($a = $view_gastosA->getIdGasto()) != NULL) & ( ($b = $view_gastosB->getIdGasto()) != NULL) ){
				$sql .= " id_gasto >= ? AND id_gasto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_gasto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $view_gastosA->getConcepto()) != NULL) & ( ($b = $view_gastosB->getConcepto()) != NULL) ){
				$sql .= " concepto >= ? AND concepto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " concepto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $view_gastosA->getMonto()) != NULL) & ( ($b = $view_gastosB->getMonto()) != NULL) ){
				$sql .= " monto >= ? AND monto <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " monto = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $view_gastosA->getFecha()) != NULL) & ( ($b = $view_gastosB->getFecha()) != NULL) ){
				$sql .= " fecha >= ? AND fecha <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " fecha = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $view_gastosA->getSucursal()) != NULL) & ( ($b = $view_gastosB->getSucursal()) != NULL) ){
				$sql .= " sucursal >= ? AND sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $view_gastosA->getIdSucursal()) != NULL) & ( ($b = $view_gastosB->getIdSucursal()) != NULL) ){
				$sql .= " id_sucursal >= ? AND id_sucursal <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " id_sucursal = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		if( (($a = $view_gastosA->getUsuario()) != NULL) & ( ($b = $view_gastosB->getUsuario()) != NULL) ){
				$sql .= " usuario >= ? AND usuario <= ? AND";
				array_push( $val, min($a,$b)); 
				array_push( $val, max($a,$b)); 
		}elseif( $a || $b ){
			$sql .= " usuario = ? AND"; 
			$a = $a == NULL ? $b : $a;
			array_push( $val, $a);
			
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		if($json === false){
			$ar = array();
			foreach ($rs as $foo) {
    			array_push( $ar, new ViewGastos($foo));
			}
			return $ar;
		}else{
			$allData = '[';
			foreach ($rs as $foo) {
    			$allData .= new ViewGastos($foo) . ',';
			}
    		$allData = substr($allData, 0 , -1) . ']';
			return $allData;
		}
	}


}
