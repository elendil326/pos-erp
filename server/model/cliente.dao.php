<?php

require_once ('Estructura.php');
require_once("base/cliente.dao.base.php");
require_once("base/cliente.vo.base.php");

/** Cliente Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Cliente }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */
class ClienteDAO extends ClienteDAOBase
{


	static function getClientesAll_grid($page,$rp,$sortname,$sortorder,$search,$qtype){
	
	
		global $logger;
		
		if (isset($sortname) && !empty($sortname) ){/* $sortname = 'name';
                if (!$sortorder) $sortorder = 'desc';*/

                	$sort = "ORDER BY $sortname $sortorder";
                }
                else
                {
                	$sort = "";
                }

                if (!$page) $page = 1;
                if (!$rp) $rp = 10;

                $start = (($page-1) * $rp);
		$end  = $rp;
                $limit = "LIMIT $start, $end";
                
                $sql="SELECT SQL_CALC_FOUND_ROWS `id_cliente` as 'ID',`nombre` as 'Nombre',`rfc` as 'RFC',`direccion` as 'Direccion' ,`telefono` as Telefono ,`e_mail` as 'E-mail',`limite_credito` as 'Limite de credito' from cliente";
                
                if(isset($search) && !empty($search))
                {
                        $sql .= " WHERE $qtype LIKE '%$search%'";
                }
        
                
                global $conn;
                
                try{
                        $rs = $conn->Execute($sql." ".$sort." ".$limit);
                        $totalrs = $conn->Execute("SELECT FOUND_ROWS();"); //obtenemos el total de rows de la consulta anterior ignorando el LIMIT
                }catch(Exception $e){
                
                        $logger->log($e->getMessage(), PEAR_LOG_ERR);
                        
                        return array();
                
                }
                
                $allData = array();
                while( $row = $rs->FetchRow() )
                {
                        array_push($allData, array("id"=>$row[0], "cell"=>$row));
                }

                return array("total"=>$totalrs->fields[0], "data"=>$allData);
	
	}


	 /**
        *       Funcion para obtener los datos de los clientes que compraron a credito y debe
        *       Se obtienen id_cliente, nombre del cliente, saldo, rfc del cliente, direccion, telefono y email.
        *
        *       @author Rene Michel <rene@caffeina.mx>
        *       @static
        *       @access public
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */
	static function getClientesCreditoDeudores($id_cliente, $de, $al){
	
		global $logger;
	
		/*
		$id_cliente=$_REQUEST['id_cliente'];
                $de=$_REQUEST['de'];
                $al=$_REQUEST['al'];
                */
                $cliente=!empty($id_cliente);
                $fecha=(!empty($de)&&!empty($al));
                $params=array();
                $query="SELECT v.id_venta, (
                                v.subtotal + v.iva
                                ) AS  'Total', IF(SUM( pv.monto )>0,SUM(pv.monto),0) AS  'Pagado',
                                if((v.subtotal + v.iva - SUM( pv.monto ))>0,(v.subtotal + v.iva - SUM( pv.monto )),(v.subtotal + v.iva)
                                ) AS  'Debe', c.nombre AS  'Nombre', DATE( v.fecha ) AS  'Fecha'
                                FROM  `pagos_venta` pv
                                RIGHT JOIN ventas v ON ( pv.id_venta = v.id_venta ) 
                                NATURAL JOIN cliente c
                                GROUP BY v.id_venta,c.id_cliente,v.fecha,v.tipo_venta,v.tipo_venta
                                having Pagado < Total and v.tipo_venta =2 ";
                if($cliente){
                        $query.=" and c.id_cliente=? ";
                        array_push($params,$id_cliente);
                }
                if($fecha){
                        $query.=" and DATE(v.fecha) BETWEEN ? AND ? ";
                        array_push($params,$de);
                        array_push($params,$al);
                }
                
		global $conn;
                
                try{
                        $rs = $conn->Execute($query, $params);
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
        *       Funcion para obtener los datos de los clientes que compraron a credito y debe
        *       para el componente grid de jQuery Flexigrid {@link http://www.flexigrid.info/}. 
        *       Se obtienen id_venta, nombre del cliente, subtotal, tipo de venta, fecha y sucursal.
        *
        *	@param	int	$page		Pagina actual que se muestra en el componente Flexigrid
        *	@param	int	$rp		Numero de filas que deben mostrarse en cada pagina en el componente Flexigrid
        *	@param	String	$sortname	Columna con la que se ordenaran el grid
        *	@param	String	$sortorder	Direccion de ordenamiento [asc | desc]
        *	@param	String	$search		Palabra que se buscara en los datos de la tabla
        *	@param	String	$qtype		Columna en la que se buscara si existe una peticion de busqueda (si $search existe)
        *	@param	date	$de		Fecha inicio para obtener datos dentro de un rango de fechas
        *	@param	date	$al		Fecha final para obtener datos dentro de un rango de fechas
        *	@param	int	$id_cliente	Id de un cliente, si se especifica este valor, los datos que se obtienen especificos de ese cliente
        *
        *       @author Rene Michel <rene@caffeina.mx>
        *       @static
        *       @access static
        *       @return Array un arreglo con los datos obtenidos de la consulta con formato array("id"=> {int} , "cell" => {[]} )
        */
	static function getClientesCreditoDeudores_grid($page, $rp, $sortname, $sortorder, $search, $qtype, $de, $al, $id_cliente){
		
		
		global $logger;
                
                if (isset($sortname) && !empty($sortname) ){/* $sortname = 'name';
                if (!$sortorder) $sortorder = 'desc';*/

                	$sort = "ORDER BY $sortname $sortorder";
                }
                else
                {
                	$sort = "";
                }

                if (!$page) $page = 1;
                if (!$rp) $rp = 10;

                $start = (($page-1) * $rp);
		$end  = $rp;
                $limit = "LIMIT $start, $end";
                
                
                 $cliente=!empty($id_cliente);
                $fecha=(!empty($de)&&!empty($al));
                $params=array();
                $query="SELECT SQL_CALC_FOUND_ROWS v.id_venta, (
                                v.subtotal + v.iva
                                ) AS  'Total', IF(SUM( pv.monto )>0,SUM(pv.monto),0) AS  'Pagado',
                                if((v.subtotal + v.iva - SUM( pv.monto ))>0,(v.subtotal + v.iva - SUM( pv.monto )),(v.subtotal + v.iva)
                                ) AS  'Debe', c.nombre AS  'Nombre', DATE( v.fecha ) AS  'Fecha'
                                FROM  `pagos_venta` pv
                                RIGHT JOIN ventas v ON ( pv.id_venta = v.id_venta ) 
                                NATURAL JOIN cliente c
                                GROUP BY v.id_venta,c.id_cliente,v.fecha,v.tipo_venta,v.tipo_venta
                                having Pagado < Total and v.tipo_venta =2 ";
                if($cliente){
                        $query.=" and c.id_cliente=? ";
                        array_push($params,$id_cliente);
                }
                if($fecha){
                        $query.=" and DATE(v.fecha) BETWEEN ? AND ? ";
                        array_push($params,$de);
                        array_push($params,$al);
                }
                
                if(isset($search) && !empty($search))
                {
                        $query .= " AND $qtype LIKE '%$search%'";
                }
        
                
                global $conn;
                
                try{
                        $rs = $conn->Execute($query." ".$sort." ".$limit, $params);
                        $totalrs = $conn->Execute("SELECT FOUND_ROWS();");
                }catch(Exception $e){
                
                        $logger->log($e->getMessage(), PEAR_LOG_ERR);
                        
                        return array();
                
                }
                
                $allData = array();
                while( $row = $rs->FetchRow() )
                {
                        array_push($allData, array("id"=>$row[0], "cell"=>$row));
                }

                return array("total"=>$totalrs->fields[0], "data"=>$allData);
	
	}
	
	
	static function getClientesCreditoPagado($de, $al, $id_cliente){
	
		global $logger;
	
		
	        $cliente=!empty($id_cliente);
	        $fecha=(!empty($de)&&!empty($al));
	        $params=array();
	        $query="SELECT v.id_venta, (
	                        v.subtotal + v.iva
	                        ) AS  'Total', IF(SUM( pv.monto )>0,SUM(pv.monto),0) AS  'Pagado', 
	                        if((v.subtotal + v.iva - SUM( pv.monto ))>0,(v.subtotal + v.iva - SUM( pv.monto )),(v.subtotal + v.iva)
	                        ) AS  'Debe', c.nombre AS  'Nombre', DATE( v.fecha ) AS  'Fecha'
	                        FROM  `pagos_venta` pv
	                        RIGHT JOIN ventas v ON ( pv.id_venta = v.id_venta ) 
	                        NATURAL JOIN cliente c
	                        GROUP BY v.id_venta,c.id_cliente,v.fecha,v.tipo_venta
	                        having Pagado >= Total and v.tipo_venta =2 ";
	        if($cliente){
	                $query.=" and c.id_cliente=? ";
	                array_push($params,$id_cliente);
	        }
	        if($fecha){
	                $query.=" and DATE(v.fecha) BETWEEN ? AND ? ";
	                array_push($params,$de);
	                array_push($params,$al);
	        }
	        $query.=" ORDER BY v.fecha;";
	        
	        global $conn;
                
                try{
                        $rs = $conn->Execute($query, $params);
                }catch(Exception $e){
                
                        $logger->log($e->getMessage(), PEAR_LOG_ERR);
                        
                        return array();
                
                }
                
                $allData = array();
                foreach ($rs as $foo) {
                        array_push( $allData, $foo);
                }

                return $allData;
	
	
	}


	static function getClientesCreditoPagado_grid($page, $rp, $sortname, $sortorder, $search, $qtype, $de, $al, $id_cliente){
	
	
		global $logger;
                
                if (isset($sortname) && !empty($sortname) ){/* $sortname = 'name';
                if (!$sortorder) $sortorder = 'desc';*/

                	$sort = "ORDER BY $sortname $sortorder";
                }
                else
                {
                	$sort = "";
                }

                if (!$page) $page = 1;
                if (!$rp) $rp = 10;

                $start = (($page-1) * $rp);
		$end  = $rp;
                $limit = "LIMIT $start, $end";
	
		$cliente=!empty($id_cliente);
	        $fecha=(!empty($de)&&!empty($al));
	        $params=array();
	        $query="SELECT SQL_CALC_FOUND_ROWS v.id_venta, (
	                        v.subtotal + v.iva
	                        ) AS  'Total', IF(SUM( pv.monto )>0,SUM(pv.monto),0) AS  'Pagado', 
	                        if((v.subtotal + v.iva - SUM( pv.monto ))>0,(v.subtotal + v.iva - SUM( pv.monto )),(v.subtotal + v.iva)
	                        ) AS  'Debe', c.nombre AS  'Nombre', DATE( v.fecha ) AS  'Fecha'
	                        FROM  `pagos_venta` pv
	                        RIGHT JOIN ventas v ON ( pv.id_venta = v.id_venta ) 
	                        NATURAL JOIN cliente c
	                        GROUP BY v.id_venta,c.id_cliente,v.fecha,v.tipo_venta
	                        having Pagado >= Total and v.tipo_venta =2 ";
	        if($cliente){
	                $query.=" and c.id_cliente=? ";
	                array_push($params,$id_cliente);
	        }
	        if($fecha){
	                $query.=" and DATE(v.fecha) BETWEEN ? AND ? ";
	                array_push($params,$de);
	                array_push($params,$al);
	        }
	        //$query.=" ORDER BY v.fecha;";
	        
	        if(isset($search) && !empty($search))
                {
                        $query .= " AND $qtype LIKE '%$search%'";
                }
	        
	        global $conn;
                
                try{
                        $rs = $conn->Execute($query." ".$sort." ".$limit, $params);
                        $totalrs = $conn->Execute("SELECT FOUND_ROWS();");
                }catch(Exception $e){
                
                        $logger->log($e->getMessage(), PEAR_LOG_ERR);
                        
                        return array();
                
                }
                
                $allData = array();
                while( $row = $rs->FetchRow() )
                {
                        array_push($allData, array("id"=>$row[0], "cell"=>$row));
                }

                return array("total"=>$totalrs->fields[0], "data"=>$allData);
	
	}

}
