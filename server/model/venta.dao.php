<?php

require_once ('Estructura.php');
require_once("base/venta.dao.base.php");
require_once("base/venta.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** Venta Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Venta }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class VentaDAO extends VentaDAOBase
{



	public static function TotalVentasNoCanceladasAContadoDesdeHasta($desde, $hasta){

		$sql = "SELECT 
				sum(total) as total,
				sum(subtotal) as subtotal,
				sum(impuesto) as impuesto
			FROM `venta` WHERE 
				es_cotizacion = 0
				and tipo_de_venta = \"contado\"
				and fecha >= ?
				and fecha <= ?
				-- and id_sucursal  = ?
				-- and id_caja = ?
				and cancelada  = 0";


		
		  $params = array( $desde, $hasta, 0, 0 );

		  global $conn;

		  $conn->SetFetchMode(ADODB_FETCH_ASSOC);

		  $rs = $conn->GetRow($sql, $params);

		
		  if(count($rs) === 0)
		  {

			return NULL;
		  
		  }
		 
    		  return $rs;
	}



	public static function TotalVentasNoCanceladasACreditoDesdeHasta($desde, $hasta){
	
                 $sql = "SELECT
				sum(total) as total,
				sum(subtotal) as subtotal,
				sum(impuesto) as impuesto
				FROM `venta` WHERE																                                      es_cotizacion = 0
			       	and tipo_de_venta = \"credito\"
			        and fecha >= ?
			        and fecha <= ?
			        -- and id_sucursal  = ?
			        -- and id_caja = ?
			        and cancelada  = 0";



                  $params = array( $desde, $hasta, 0, 0 );
                  global $conn;

                  $conn->SetFetchMode(ADODB_FETCH_ASSOC);

                  $rs = $conn->GetRow($sql, $params);


                  if(count($rs) === 0)
                  {
                       return NULL;

		  }

                  return $rs;

	}
}
