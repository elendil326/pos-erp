<?php

require_once("base/ventas.dao.base.php");
require_once("base/ventas.vo.base.php");
require_once ('Estructura.php');
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
        *       Funcion para obtener los datos especificos de las ventas. 
        *       Se obtienen id_venta, nombre del cliente, subtotal, tipo de venta, fecha y sucursal.
        *
        *       @author Rene Michel <rene@caffeina.mx>
        *       @static
        *       @access public
        *       @return Array un arreglo con los datos obtenidos de la consulta
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
        *       Funcion para obtener los datos especificos de las ventas con datos extras para poder paginar, buscar, etc. necesarios
        *       para el componente grid de jQuery Flexigrid {@link http://www.flexigrid.info/}. 
        *       Se obtienen id_venta, nombre del cliente, subtotal, tipo de venta, fecha y sucursal.
        *
        *	@param	int	$page		Pagina actual que se muestra en el componente Flexigrid
        *	@param	int	$rp		Numero de filas que deben mostrarse en cada pagina en el componente Flexigrid
        *	@param	String	$sortname	Columna con la que se ordenaran el grid
        *	@param	String	$sortorder	Direccion de ordenamiento [asc | desc]
        *	@param	String	$search		Palabra que se buscara en los datos de la tabla
        *	@param	String	$qtype		Columna en la que se buscara si existe una peticion de busqueda (si $search existe)
        *
        *
        *       @author Rene Michel <rene@caffeina.mx>
        *       @static
        *       @access public
        *       @return Array un arreglo con los datos obtenidos de la consulta con formato array("id"=> {int} , "cell" => {[]} )
        */
        static function getVentasPorClientes_grid($page, $rp, $sortname, $sortorder, $search, $qtype){
        
                if (!$sortname){/* $sortname = 'name';
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
		$end  = $page * $rp;
                $limit = "LIMIT $start, $end";
                
                
                $sql = "SELECT id_venta AS  'ID', nombre AS  'Cliente', ( subtotal + iva ) AS  'Total', IF( tipo_venta =1,  'Contado',  'Credito' ) AS  'Tipo', date(fecha) AS  'Fecha', sucursal AS  'Sucursal' FROM  `ventas` NATURAL JOIN cliente";
                
                if(isset($search) && !empty($search))
                {                        
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
        
         /**
        *       Funcion para obtener los datos especificos de las ventas a credito y los datos reelevantes del cliente. 
        *
        *       @author Rene Michel <rene@caffeina.mx>
        *       @static
        *       @access public
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */
        static function getVentasACreditoPorClientes(){
        
        	$id_cliente=$_REQUEST['id_cliente'];
                $de=$_REQUEST['de'];
                $al=$_REQUEST['al'];
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
                                GROUP BY v.id_venta,c.id_cliente,v.fecha ,v.tipo_venta
                                having v.tipo_venta =2 "; 
                if($cliente){
                        $query.=" and c.id_cliente=? ";
                        array_push($params,$id_cliente);
                }
                if($fecha){
                        $query.=" and  DATE(v.fecha) BETWEEN ? AND ? ";
                        array_push($params,$de);
                        array_push($params,$al);
                }
                $query.=" ORDER BY v.fecha ;";
                
                global $conn;
                
                try{
                        $rs = $conn->Execute($query." ".$sort." ".$limit);
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
        
         /**
        *       Funcion para obtener los datos especificos de las ventas a credito y los datos reelevantes del cliente. 
        *       para el componente grid de jQuery Flexigrid {@link http://www.flexigrid.info/}. 
        *       Se obtienen id_venta, nombre del cliente, subtotal, tipo de venta, fecha y sucursal.
        *
        *	@param	int	$id_cliente	Id del cliente, si se especifica este parametro el resultado sera especifico para ese cliente
        *	@param	date	$de		Rango inferior para la obtencion de datos dentro de un periodo especicado
        *	@param	String	$al		Rango superior para la obtencion de datos dentro de un periodo especicado
        *
        *       @author Rene Michel <rene@caffeina.mx>
        *       @static
        *       @access public
        *       @return Array un arreglo con los datos obtenidos de la consulta con formato array("id"=> {int} , "cell" => {[]} )
        */
        static function getVentasACreditoPorClientes_grid($id_cliente, $de, $al){
        
        	
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
                                GROUP BY v.id_venta,c.id_cliente,v.fecha ,v.tipo_venta
                                having v.tipo_venta =2 "; 
                if($cliente){
                        $query.=" and c.id_cliente=? ";
                        array_push($params,$id_cliente);
                }
                if($fecha){
                        $query.=" and  DATE(v.fecha) BETWEEN ? AND ? ";
                        array_push($params,$de);
                        array_push($params,$al);
                }
                //$query.=" ORDER BY v.fecha ;";
                
                global $conn;
                
                try{
                        $rs = $conn->Execute($query." ".$sort." ".$limit);
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
        
        
         /**
        *       Funcion para obtener los datos especificos de las ventas de contado y los datos reelevantes del cliente. 
        *
        *       @author Rene Michel <rene@caffeina.mx>
        *       @static
        *       @access public
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */
        static function getVentasDeContadoPorClientes(){
        
        	$id_cliente=$_REQUEST['id_cliente'];
                $de=$_REQUEST['de'];
                $al=$_REQUEST['al'];
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
                                GROUP BY v.id_venta,c.id_cliente,v.fecha ,v.tipo_venta
                                having v.tipo_venta =1 "; 
                if($cliente){
                        $query.=" and c.id_cliente=? ";
                        array_push($params,$id_cliente);
                }
                if($fecha){
                        $query.=" and  DATE(v.fecha) BETWEEN ? AND ? ";
                        array_push($params,$de);
                        array_push($params,$al);
                }
                $query.=" ORDER BY v.fecha ;";
                
                global $conn;
                
                try{
                        $rs = $conn->Execute($query." ".$sort." ".$limit);
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
        
         /**
        *       Funcion para obtener los datos especificos de las ventas de contado y los datos reelevantes del cliente. 
        *       para el componente grid de jQuery Flexigrid {@link http://www.flexigrid.info/}. 
        *       Se obtienen id_venta, nombre del cliente, subtotal, tipo de venta, fecha y sucursal.
        *
        *	@param	int	$id_cliente	Id del cliente, si se especifica este parametro el resultado sera especifico para ese cliente
        *	@param	date	$de		Rango inferior para la obtencion de datos dentro de un periodo especicado
        *	@param	String	$al		Rango superior para la obtencion de datos dentro de un periodo especicado
        *
        *       @author Rene Michel <rene@caffeina.mx>
        *       @static
        *       @access public
        *       @return Array un arreglo con los datos obtenidos de la consulta con formato array("id"=> {int} , "cell" => {[]} )
        */
        static function getVentasDeContadoPorClientes_grid($id_cliente, $de, $al){
        
        	
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
                                GROUP BY v.id_venta,c.id_cliente,v.fecha ,v.tipo_venta
                                having v.tipo_venta =1 "; 
                if($cliente){
                        $query.=" and c.id_cliente=? ";
                        array_push($params,$id_cliente);
                }
                if($fecha){
                        $query.=" and  DATE(v.fecha) BETWEEN ? AND ? ";
                        array_push($params,$de);
                        array_push($params,$al);
                }
                //$query.=" ORDER BY v.fecha ;";
                
                global $conn;
                
                try{
                        $rs = $conn->Execute($query." ".$sort." ".$limit);
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
