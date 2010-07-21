<?php

require_once("base/ventas.dao.base.php");
/** Ventas Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Ventas }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */
class VentasDAO extends VentasDAOBase
{

	/**
	*	Funcion para obtener los datos especificos de las ventas. 
	*	Se obtienen id_venta, nombre del cliente, subtotal, tipo de venta, fecha y sucursal.
	*
	*	@author Rene Michel <rene@caffeina.mx>
	*	@static
	*	@access public
	*	@return Array un arreglo con los datos obtenidos de la consulta
	*/
	static function getVentasPorClientes(){
	
		$sql = "SELECT id_venta AS  'ID', nombre AS  'Cliente', ( subtotal + iva ) AS  'Total', IF( tipo_venta =1,  'Contado',  'Credito' ) AS  'Tipo', date(fecha) AS  'Fecha', sucursal AS  'Sucursal' FROM  `ventas` NATURAL JOIN cliente";
                global $conn;
                
                try{
                	$rs = $conn->Execute($sql);
                }catch(Exception $e){
                
                	$logger->log($e->getMessage(), PEAR_LOG_ERR);
                
                }
                
                $allData = array();
                foreach ($rs as $foo) {
                	array_push( $allData, $foo);
                }
                return $allData;
	
	}
	
	/**
	*	Funcion para obtener los datos especificos de las ventas con datos extras para poder paginar, buscar, etc. necesarios
	*	para el componente grid de jQuery Flexigrid {@link http://www.flexigrid.info/}. 
	*	Se obtienen id_venta, nombre del cliente, subtotal, tipo de venta, fecha y sucursal.
	*
	*	@author Rene Michel <rene@caffeina.mx>
	*	@static
	*	@access public
	*	@return Array un arreglo con los datos obtenidos de la consulta con formato array("id"=> {int} , "cell" => {[]} )
	*/
	static function getVentasPorClientes_grid(){
	
		$page = strip_tags($_POST['page']);
		$rp = strip_tags($_POST['rp']);
		$sortname = strip_tags($_POST['sortname']);
		$sortorder = strip_tags($_POST['sortorder']);

		if (!$sortname) $sortname = 'name';
		if (!$sortorder) $sortorder = 'desc';

		$sort = "ORDER BY $sortname $sortorder";

		if (!$page) $page = 1;
		if (!$rp) $rp = 10;

		$start = (($page-1) * $rp);

		$limit = "LIMIT $start, $rp";
		
		
		$sql = "SELECT id_venta AS  'ID', nombre AS  'Cliente', ( subtotal + iva ) AS  'Total', IF( tipo_venta =1,  'Contado',  'Credito' ) AS  'Tipo', date(fecha) AS  'Fecha', sucursal AS  'Sucursal' FROM  `ventas` NATURAL JOIN cliente";
		
		if(isset($_POST['query']) && !empty($_POST['query']))
		{
			$search = strip_tags($_POST['query']);
			$qtype = strip_tags($_POST['qtype']);
			
			$sql .= " WHERE $qtype LIKE '%$search%'";
		}
	
		
                global $conn;
                
                try{
                	$rs = $conn->Execute($sql." ".$sort." ".$limit);
                }catch(Exception $e){
                
                	$logger->log($e->getMessage(), PEAR_LOG_ERR);
                	
                	return array();
                
                }
                
                $allData = array();
                while( $row = $rs->FetchRow() )
		{
			array_push($allData, array("id"=>$row[0], "cell"=>$row));
		}

                return $allData;
	
	}


}
