<?php
/** ViewDetalleCompra Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * <b>recuperar</b> instancias de objetos {@link ViewDetalleCompra }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class ViewDetalleCompraDAOBase extends VistaDAO
{

	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la vista en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ViewDetalleCompra}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link ViewDetalleCompra}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from view_detalle_compra ;";
		global $conn;
		$rs = $conn->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new ViewDetalleCompra($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ViewDetalleCompra} de la base de datos. 
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
	  * @param Objeto Un objeto del tipo {@link ViewDetalleCompra}.
	  * @return Array Un arreglo de objetos del tipo {@link ViewDetalleCompra} que coinciden con el objeto de busqueda.
	  **/
	public static final function search( $view_detalle_compra )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if( $view_detalle_compra->getIdCompra() != NULL){
			$sql .= " id_compra = ? AND";
			array_push( $val, $view_detalle_compra->getIdCompra() );
		}

		if( $view_detalle_compra->getIdProducto() != NULL){
			$sql .= " id_producto = ? AND";
			array_push( $val, $view_detalle_compra->getIdProducto() );
		}

		if( $view_detalle_compra->getDenominacion() != NULL){
			$sql .= " denominacion = ? AND";
			array_push( $val, $view_detalle_compra->getDenominacion() );
		}

		if( $view_detalle_compra->getCantidad() != NULL){
			$sql .= " cantidad = ? AND";
			array_push( $val, $view_detalle_compra->getCantidad() );
		}

		if( $view_detalle_compra->getPrecio() != NULL){
			$sql .= " precio = ? AND";
			array_push( $val, $view_detalle_compra->getPrecio() );
		}

		if( $view_detalle_compra->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $view_detalle_compra->getFecha() );
		}

		if( $view_detalle_compra->getTipoCompra() != NULL){
			$sql .= " tipo_compra = ? AND";
			array_push( $val, $view_detalle_compra->getTipoCompra() );
		}

		if( $view_detalle_compra->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $view_detalle_compra->getIdSucursal() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new ViewDetalleCompra($foo));
		}
		return $allData;
	}


}
