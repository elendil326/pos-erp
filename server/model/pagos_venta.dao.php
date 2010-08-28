<?php

require_once ('Estructura.php');
require_once("base/pagos_venta.dao.base.php");
require_once("base/pagos_venta.vo.base.php");
require_once("view_ventas.dao.php");
/** PagosVenta Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link PagosVenta }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */
class PagosVentaDAO extends PagosVentaDAOBase
{

	/**
	*
	*	Función que obtiene los abonos que se han hecho a ventas a crédito registradas
	*	alguna sucursal en un determinado período de tiempo
	*
	*	@params <Integer> id_sucursal El id de la sucursal de donde queremos obtener los datos
	*	@params <String> fechaInicio La fecha de inicio de un período de tiempo del cual obtendremos los datos
	*	@params <String> fechaFinal La fecha final de un periódo de tiempo del cual obtendremos los datos
	*	@access public
	*/
	
	static function abonosSucursal( $id_sucursal, $fechaInicio, $fechaFinal )
	{
		//Obtenemos un arreglo de ventas a credito qeu se hicieron en la sucursal indicada
		//después de cada venta vemos si tiene abono en pagos_venta y lo vamos sumando si es verdadera la condición
	
	
		//Creamos el objeto de view_venta
		$viewVentas = new ViewVentas();
		
		//Buscamos las ventas de cierto id de sucursal y que sean a credito
		$viewVentas->setIdSucursal($id_sucursal);
		$viewVentas->setTipoVenta('credito');
	
		$ventas = ViewVentasDAO::search($viewVentas);
	
		//Analizamos cada venta para ver si tiene abono, si es asi, lo sumamos
		
		$ventaCredito1 = new PagosVenta();
		$ventaCredito1->setFecha($fechaInicio);
		
		$ventaCredito2 = new PagosVenta();
		$ventaCredito2->setFecha($fechaFinal);
		
		$sumaAbono = 0;
		
		for( $i=0 ; $i < count($ventas) ; $i++ )
		{
			$idVenta = $ventas[$i]->getIdVenta();
			
			$ventaCredito1->setIdVenta($idVenta);
			$ventaCredito2->setIdVenta($idVenta);
			$resultVentaCredito = PagosVentaDAO::byRange($ventaCredito1, $ventaCredito2);
			
			if ( $resultVentaCredito != NULL )
			{
				for( $j=0 ; $j < count($resultVentaCredito) ; $j++)
				{
				
					$sumaAbono += $resultVentaCredito[$j]->getMonto();
				
				}
			}
			
		}
		$arrayResults = array();
		if ( count($ventas) > 0 )
		{
			array_push($arrayResults, array( "id_sucursal" => $id_sucursal, "sucursal" => $ventas[0]->getSucursal(), "abono" => $sumaAbono));
		}
		else
		{
			array_push($arrayResults, array( "error" => "No se encontraron datos" ));
		}
		
		return $arrayResults;
	
	}


}
