<?php

require_once("base/cliente.dao.base.php");
require_once("base/cliente.vo.base.php");
require_once ('Estructura.php');
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

	 /**
        *       Funcion para obtener los datos de los clientes que compraron a credito y debe
        *       Se obtienen id_cliente, nombre del cliente, saldo, rfc del cliente, direccion, telefono y email.
        *
        *       @author Rene Michel <rene@caffeina.mx>
        *       @static
        *       @access public
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */
	static function getClientesDeudores(){
	
	
		$sql = "SELECT `id_cliente` as 'ID', nombre AS  'Nombre', saldo AS  'Saldo',  `rfc` AS  'RFC',  `direccion` AS  'Direccion',  `telefono` AS Telefono,  `e_mail` AS  'E-mail' FROM cliente c NATURAL JOIN cuenta_cliente cc"; 
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
        *
        *       @author Rene Michel <rene@caffeina.mx>
        *       @static
        *       @access static
        *       @return Array un arreglo con los datos obtenidos de la consulta con formato array("id"=> {int} , "cell" => {[]} )
        */
	static function getClientesDeudores_grid($page, $rp, $sortname, $sortorder, $search, $qtype){
		
		

                
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
                
                
                $sql="SELECT `id_cliente` as 'ID', nombre AS  'Nombre', saldo AS  'Saldo',  `rfc` AS  'RFC',  `direccion` AS  'Direccion',  `telefono` AS Telefono,  `e_mail` AS  'E-mail' FROM cliente c NATURAL JOIN cuenta_cliente cc"; 
                
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


}
