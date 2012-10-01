<?php

require_once ('Estructura.php');
require_once("base/abono_venta.dao.base.php");
require_once("base/abono_venta.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** AbonoVenta Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link AbonoVenta }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class AbonoVentaDAO extends AbonoVentaDAOBase
{
	public static function TotalAbonadoAVentasDesdeHasta($desde, $hasta){
	
		$sql = "SELECT 
				sum(monto) from abono_venta 
			where 
				cancelado = 0
				";
					
	
	}
}
