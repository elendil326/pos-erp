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
	  * Esta funcion leera todos los contenidos de la vista en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ViewGastos}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link ViewGastos}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from view_gastos ;";
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
	  * @param Objeto Un objeto del tipo {@link ViewGastos}.
	  * @return Array Un arreglo de objetos del tipo {@link ViewGastos} que coinciden con el objeto de busqueda.
	  **/
	public static final function search( $view_gastos )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if( $view_gastos->getIdGasto() != NULL){
			$sql .= " id_gasto = ? AND";
			array_push( $val, $view_gastos->getIdGasto() );
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
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new ViewGastos($foo));
		}
		return $allData;
	}


}
