<?php

require_once("base/corte.dao.base.php");
require_once("base/corte.vo.base.php");
require_once ('Estructura.php');
/** Corte Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Corte }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */
class CorteDAO extends CorteDAOBase
{
	
	
	
	
	/**
        *       Funcion que genera un corte para repartir las ganancias en el caso especifico de papas supremas
        *	recibe una fecha de inicio y una fecha de fin con el formato "YYYY-MM-DD"
        *	si no recibe un el parametro guardar o no recibe un verdadero, indica que no se guardara elcorte
        *	y nos regresara un json con los datos que se guardarian en caso de hacer un corte, 
        *	si se manda guardar=true intenta guardar el corte y nos devuelve un json con el resultado de la operacion.
        *	en caso de fallar nos envia la razon del fallo.
        *       @author Diego Ventura <diego@caffeina.mx>
        *       @static
        *       @access public
        *       @return json con el resultado de la funcion deseada (guardar o simular)
        *	@param date [$inicio] fecha de inicio del corte a realizar
        *	@param date [$fin] fecha de fin del corte a realizar
        *	@param bool [$guarda] Verdadero para indicar que guarde el corte, si no se envia o no es verdadero regresa un json con un corte simulado
        */
	static function reparticionGanancias($inicio,$fin,$guarda=false)
	{
	
		$corte=new Corte();
		$corte->setInicio($inicio);
		$corte->setFin($fin);
		$corte->setAnio(substr($fin, 0, 4));

		global $conn;		
		$params=array($inicio,$fin);
		$sql="
				SELECT if(sum(v.subtotal + v.iva) is null,0,sum(v.subtotal + v.iva)) as ventas
				FROM ventas v 
				where (DATE(v.fecha) BETWEEN ? AND ? and v.tipo_venta=1)
		";
		
		$rs=$conn->Execute($sql, $params);
		$ventas=$rs->fields[0];
		$corte->setVentas($ventas);


		$sql="
				SELECT if(sum( pv.monto ) is null,0,sum( pv.monto )) as abonos
				FROM pagos_venta pv 
				where (DATE(pv.fecha) BETWEEN  ? AND ?);
		";

		$rs=$conn->Execute($sql, $params);
		$abonosVenta=$rs->fields[0];
		$corte->setAbonosVentas($abonosVenta);

		$sql="
				SELECT if(sum(c.subtotal + c.iva) is null,0,sum(c.subtotal + c.iva)) as compras
				FROM compras c 
				where (DATE(c.fecha) BETWEEN ? AND ? and c.tipo_compra=1)
		";
		
		$rs=$conn->Execute($sql, $params);
		$compras=$rs->fields[0];
		$corte->setCompras($compras);


		$sql="
				SELECT if(sum( pc.monto ) is null,0,sum( pc.monto )) as abonos
				FROM pagos_compra pc 
				where (DATE(pc.fecha) BETWEEN  ? AND ?);
		";

		$rs=$conn->Execute($sql, $params);
		$abonosCompra=$rs->fields[0];

		$corte->setAbonosCompra($abonosCompra);

		$sql="SELECT sum(monto) 
			from ingresos 
			where 	DATE(fecha) BETWEEN ? AND ? 
		";
		
		$rs=$conn->Execute($sql, $params);
		$ingresos=$rs->fields[0];
		$corte->setIngresos($ingresos);


		$sql="SELECT sum(monto) 
			FROM gastos
			where	DATE(fecha) BETWEEN ? AND ? ;
		";
		
		$rs=$conn->Execute($sql, $params);
		$gastos=$rs->fields[0];
		$corte->setGastos($gastos);

		$netas=number_format($ventas+$abonosVenta+$ingresos-$compras-$abonosCompra-$gastos,2,'.','');
		$corte->setGananciasNetas($netas);
		
		$queryVista="CREATE OR REPLACE VIEW `adeudan_sucursal` AS select `v`.`id_venta` AS `id_venta`,`v`.`id_sucursal` AS `sucursal`,`e`.`id_usuario` AS `id_usuario`,if((((`v`.`subtotal` + `v`.`iva`) - sum(`pv`.`monto`)) > 0),((`v`.`subtotal` + `v`.`iva`) - sum(`pv`.`monto`)),(`v`.`subtotal` + `v`.`iva`)) AS `Deben`,if((max(`pv`.`fecha`) <> NULL),max(`pv`.`fecha`),`v`.`fecha`) AS `fecha`,`e`.`porciento` AS `porciento` from (`encargado` `e` left join ((`ventas` `v` left join `pagos_venta` `pv` on((`pv`.`id_venta` = `v`.`id_venta`))) left join `usuario` `u` on((`u`.`id_sucursal` = `v`.`id_sucursal`))) on((`u`.`id_usuario` = `e`.`id_usuario`))) where (`v`.`tipo_venta` = 2) group by `v`.`tipo_venta`,`v`.`id_sucursal`,`v`.`id_venta`,`e`.`id_usuario`;";
		$queryDeleteVista="drop view if exists adeudan_sucursal;";
		
		try
		{
			$conn->Execute($queryVista);
		}
		catch (exception $e) 
		{ 
				return '{ "success" : "false" , "reason" : "No se pudo guardar el corte." }';
		} 
		

		$params=array("0",$corte->getGananciasNetas(),$corte->getFin());

		$query="select ? as num_corte,u.nombre,TRUNCATE(?*(e.porciento*.01),2) as total,TRUNCATE(if(sum(deben) is null,0,sum(deben)),2) as deben
								from encargado e
								left join usuario u
								on (e.id_usuario=u.id_usuario)
								left join adeudan_sucursal a
								on (e.id_usuario=a.id_usuario)
								where date(fecha)<?
								or fecha is null
								group by u.nombre,e.porciento;";

		if(! $guarda === false)
		{
			try
			{
				CorteDAO::save($corte);
				$query="insert into detalle_corte ".$query; 
				$params=array($corte->getNumCorte(),$corte->getGananciasNetas(),$corte->getFin());
			}
			catch (exception $e)
			{
				$conn->Execute($queryDeleteVista);
				return '{ "success" : "false" , "reason" : "No se pudo guardar el corte." }';
			}
		}
		

		$rs=$conn->Execute($query,$params);
		
 		//$conn->Execute($queryDeleteVista);
		if(!$guarda === false)return '{ "success" : "true" }';
		
		$resul='{ "success" : "true" , "datos" : [';
		
		$arr=array();
				
		while(!$rs->EOF)
		{
			$algo=$rs->GetRowAssoc($toUpper=false);
			foreach($algo as $atributo => &$valor)$valor=utf8_encode($valor);
			array_push($arr,$algo);
			$rs->MoveNext();
		}
		
		
		
		$resul.='{"anio" : "'.$corte->getAnio().'", "inicio" : "'.$corte->getInicio().'", "fin" : "'.$corte->getFin().'", "ventas" : "'.$corte->getVentas().'", "abonosVentas" : "'.$corte->getAbonosVentas().'", "ingresos" : "'.$corte->getIngresos().'", "compras" : "'.$corte->getCompras().'", "abonosCompras" : "'.$corte->getabonosCompra().'", "gastos" : "'.$corte->getGastos().'", "gananciasNetas" : "'.$corte->getGananciasNetas().'"';
		$resul.=' "detalles" : '.json_encode($arr).'}"';
		$resul.=']}';
		$resul= str_replace('"num_corte":"0",','',$resul);
		return $resul;
	}
	//fin reparticionGanancias
}
