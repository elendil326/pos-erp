<?php

require_once ('Estructura.php');
require_once("base/ventas.dao.base.php");
require_once("base/ventas.vo.base.php");

/** .
  * 
  * @author no author especified
  * @package docs
  * 
  */
/** Ventas Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Ventas }. 
  * @author no author especified
  * @access public
  * @package docs
  * 
  */
class VentasDAO extends VentasDAOBase
{
	/**
	  * Obtener un arreglo con las ultimas ventas por dia de los ultimos
	  * <i>$dias</i> dias. Si dias es negativo se regresaran todos las coincidencias
	  * y si sucursal es null, se regresara para todas las sucursales
	  **/
	public static function contarVentasPorDia($sucursal = null, $dias = 7){
		
		$sql = "select date_format(fecha,'%Y-%m-%d') as fecha, count(*) as ventas from ventas ";
		$val = array();
		
		if($sucursal != null){
			array_push( $val, $sucursal );
			$sql .= " where id_sucursal = ?";			
		}
		$sql .= " group by date_format(fecha,'%Y-%m-%d') order by fecha ";
		
		if($dias > 0 ){
			$sql .= " limit ?";
			array_push( $val, $dias );			
		}
		
		global $conn;
		

		$rs = $conn->Execute($sql, $val);

		$res = array();
		foreach ($rs as $foo) {
			array_push( $res, array( 
				"fecha" => $foo[0],
				"value" => $foo[1] ) );
		}
		return $res;

	}
	
	
	public static function contarVentasPorMes($sucursal , $meses = 1){
		$sql = "select date_format(fecha,'%Y-%m') as fecha, count(*) as ventas from ventas where id_sucursal = ? group by date_format(fecha,'%Y-%m') order by fecha limit ?";
		
		global $conn;
		$val = array( $sucursal, $meses );
		$rs = $conn->Execute($sql, $val);

		$res = array();
		foreach ($rs as $foo) {
			array_push( $res, array( $foo[0] , $foo[1] ) );
		}
		return $res;

	}
	
	
	public static function contarVentasDeCliente($id_cliente){
		$sql = "select date_format(fecha,'%Y-%m-%d') as fecha, count(*) as ventas from ventas where id_cliente = ? group by date_format(fecha,'%Y-%m-%d') order by fecha";
		$val = array($id_cliente);
		

		
		global $conn;
		

		$rs = $conn->Execute($sql, $val);

		$res = array();
		foreach ($rs as $foo) {
			array_push( $res, array( 
				"fecha" => $foo[0],
				"ventas" => $foo[1] ) );
		}
		return $res;
	}
	
	
	public static function rendimientoDiarioEnVentasAContadoPorSucursal($id_sucursal){
		$sql = "select date_format(fecha,'%Y-%m-%d') as fecha, SUM(total) as total from ventas where (id_sucursal = ? and  tipo_venta = 'contado' and liquidada =1) group by date_format(fecha,'%Y-%m-%d') order by fecha";
		$val = array($id_sucursal);

		global $conn;

		$rs = $conn->Execute($sql, $val);

		$res = array();
		foreach ($rs as $foo) {
			array_push( $res, array( 
				"fecha" => $foo[0],
				"value" => $foo[1] ) );
		}
		return $res;
	}
	
	
	public static function ventasPorProducto($id_producto, $id_sucursal = null){
		
		$val = array( $id_producto );
		
		$q = "SELECT 
				  sum(dv.cantidad), 
				  sum(dv.cantidad_procesada),
				  date_format(v.fecha,'%Y-%m-%d') as fecha
				FROM ventas as v, detalle_venta dv 
				where v.id_venta = dv.id_venta
				  and dv.id_producto = ? ";
				
		if($id_sucursal !== null){
			$q .=  "AND v.id_sucursal = ? ";
			array_push($val, $id_sucursal);
		}
			
				
		$q .= " group by date_format(v.fecha,'%Y-%m-%d') 
				order by fecha";
		global $conn;

		$rs = $conn->Execute($q, $val);
		$res = array();
		foreach ($rs as $foo) {
			array_push( $res, array( 
				"fecha" => $foo[2],
				"value" => $foo[1] + $foo[0] ));
		}
		return $res;
	}


	public static function totalVentasDesdeFecha($id_sucursal, $fecha){
		
		$val = array( $id_sucursal, $fecha );
		
		$q = "SELECT SUM( total ) 
				FROM ventas
				WHERE id_sucursal = ? 
				AND fecha >  ?" ;

		global $conn;

		$rs = $conn->GetRow($q, $val);

		return $rs[0];
	}
}








