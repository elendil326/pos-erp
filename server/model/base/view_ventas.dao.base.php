<?php
/** ViewVentas Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * <b>recuperar</b> instancias de objetos {@link ViewVentas }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class ViewVentasDAOBase extends VistaDAO
{

	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la vista en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ViewVentas}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link ViewVentas}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from view_ventas ;";
		global $conn;
		$rs = $conn->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new ViewVentas($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ViewVentas} de la base de datos. 
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
	  * @param Objeto Un objeto del tipo {@link ViewVentas}.
	  * @return Array Un arreglo de objetos del tipo {@link ViewVentas} que coinciden con el objeto de busqueda.
	  **/
	public static final function search( $view_ventas )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if( $view_ventas->getIdVenta() != NULL){
			$sql .= " id_venta = ? AND";
			array_push( $val, $view_ventas->getIdVenta() );
		}

		if( $view_ventas->getCliente() != NULL){
			$sql .= " cliente = ? AND";
			array_push( $val, $view_ventas->getCliente() );
		}

		if( $view_ventas->getIdCliente() != NULL){
			$sql .= " id_cliente = ? AND";
			array_push( $val, $view_ventas->getIdCliente() );
		}

		if( $view_ventas->getTipoVenta() != NULL){
			$sql .= " tipo_venta = ? AND";
			array_push( $val, $view_ventas->getTipoVenta() );
		}

		if( $view_ventas->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $view_ventas->getFecha() );
		}

		if( $view_ventas->getSubtotal() != NULL){
			$sql .= " subtotal = ? AND";
			array_push( $val, $view_ventas->getSubtotal() );
		}

		if( $view_ventas->getIva() != NULL){
			$sql .= " iva = ? AND";
			array_push( $val, $view_ventas->getIva() );
		}

		if( $view_ventas->getSucursal() != NULL){
			$sql .= " sucursal = ? AND";
			array_push( $val, $view_ventas->getSucursal() );
		}

		if( $view_ventas->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $view_ventas->getIdSucursal() );
		}

		if( $view_ventas->getUsuario() != NULL){
			$sql .= " usuario = ? AND";
			array_push( $val, $view_ventas->getUsuario() );
		}

		if( $view_ventas->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $view_ventas->getIdUsuario() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new ViewVentas($foo));
		}
		return $allData;
	}


}
