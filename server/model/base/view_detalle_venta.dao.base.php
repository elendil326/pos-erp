<?php
/** ViewDetalleVenta Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * <b>recuperar</b> instancias de objetos {@link ViewDetalleVenta }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class ViewDetalleVentaDAOBase extends VistaDAO
{

	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la vista en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ViewDetalleVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link ViewDetalleVenta}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from view_detalle_venta ;";
		global $db;
		$rs = $db->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new ViewDetalleVenta($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ViewDetalleVenta} de la base de datos. 
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
	  * @param Objeto Un objeto del tipo {@link ViewDetalleVenta}.
	  * @return Array Un arreglo de objetos del tipo {@link ViewDetalleVenta} que coinciden con el objeto de busqueda.
	  **/
	public static final function search( $view_detalle_venta )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if($cliente->getIdVenta() != NULL){
			$sql .= " id_venta = ? AND";
			array_push( $val, $cliente->getIdVenta() );
		}

		if($cliente->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $cliente->getIdProducto() );
		}

		if($cliente->getDenominacion() != NULL){
			$sql .= " denominacion = ? AND";
			array_push( $val, $cliente->getDenominacion() );
		}

		if($cliente->getCantidad() != NULL){
			$sql .= " cantidad = ? AND";
			array_push( $val, $cliente->getCantidad() );
		}

		if($cliente->getPrecio() != NULL){
			$sql .= " precio = ? AND";
			array_push( $val, $cliente->getPrecio() );
		}

		if($cliente->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $cliente->getFecha() );
		}

		if($cliente->getTipoVenta() != NULL){
			$sql .= " tipo_venta = ? AND";
			array_push( $val, $cliente->getTipoVenta() );
		}

		if($cliente->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $cliente->getIdSucursal() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $db;
		$rs = $db->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new ViewDetalleVenta($foo));
		}
		return $allData;
	}


}
