<?php
/** ViewCompras Data Access Object (DAO) Base.
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * <b>recuperar</b> instancias de objetos {@link ViewCompras }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access private
  * 
  */
abstract class ViewComprasDAOBase extends VistaDAO
{

	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la vista en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ViewCompras}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos
	  *	
	  *	@static
	  * @return Array Un arreglo que contiene objetos del tipo {@link ViewCompras}.
	  **/
	public static final function getAll( )
	{
		$sql = "SELECT * from view_compras ;";
		global $conn;
		$rs = $conn->Execute($sql);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new ViewCompras($foo));
		}
		return $allData;
	}


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ViewCompras} de la base de datos. 
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
	  * @param Objeto Un objeto del tipo {@link ViewCompras}.
	  * @return Array Un arreglo de objetos del tipo {@link ViewCompras} que coinciden con el objeto de busqueda.
	  **/
	public static final function search( $view_compras )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = array();
		if( $view_compras->getIdCompra() != NULL){
			$sql .= " id_compra = ? AND";
			array_push( $val, $view_compras->getIdCompra() );
		}

		if( $view_compras->getProveedor() != NULL){
			$sql .= " proveedor = ? AND";
			array_push( $val, $view_compras->getProveedor() );
		}

		if( $view_compras->getIdProveedor() != NULL){
			$sql .= " id_proveedor = ? AND";
			array_push( $val, $view_compras->getIdProveedor() );
		}

		if( $view_compras->getTipoCompra() != NULL){
			$sql .= " tipo_compra = ? AND";
			array_push( $val, $view_compras->getTipoCompra() );
		}

		if( $view_compras->getFecha() != NULL){
			$sql .= " fecha = ? AND";
			array_push( $val, $view_compras->getFecha() );
		}

		if( $view_compras->getSubtotal() != NULL){
			$sql .= " subtotal = ? AND";
			array_push( $val, $view_compras->getSubtotal() );
		}

		if( $view_compras->getIva() != NULL){
			$sql .= " iva = ? AND";
			array_push( $val, $view_compras->getIva() );
		}

		if( $view_compras->getSucursal() != NULL){
			$sql .= " sucursal = ? AND";
			array_push( $val, $view_compras->getSucursal() );
		}

		if( $view_compras->getIdSucursal() != NULL){
			$sql .= " id_sucursal = ? AND";
			array_push( $val, $view_compras->getIdSucursal() );
		}

		if( $view_compras->getUsuario() != NULL){
			$sql .= " usuario = ? AND";
			array_push( $val, $view_compras->getUsuario() );
		}

		if( $view_compras->getIdUsuario() != NULL){
			$sql .= " id_usuario = ? AND";
			array_push( $val, $view_compras->getIdUsuario() );
		}

		$sql = substr($sql, 0, -3) . " )";
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$allData = array();
		foreach ($rs as $foo) {
    		array_push( $allData, new ViewCompras($foo));
		}
		return $allData;
	}


}
