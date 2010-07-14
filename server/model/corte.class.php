<?php 
	class corte{
		var $num_corte;	 	 	 	 	 	 
		var $inicio;	 	 	 	 	 	 	 
		var $anio;	 	 	 	 	 	 	 
		var $fin; 	 	 	 	 	 	 
		var $ventas;	 	 	 	 	 	 	 
		var $abonosVentas;	 	 	 	 	 	 	 
		var $compras;	 	 
		var $abonosCompras;
		var $gastos;
		var $ingresos;
		var $gananciasNetas;
		var $bd;
		
		function __construct($inicio,$fin,$ventas,$abonosVentas,$compras,$abonosCompras,$gastos,$ingresos,$gananciasNetas){ 
				$this->inicio=$inicio;	 	 	 	 	 	 
				$this->anio=substr($fecha, 0, 4);	 	 	 	 	 	 
				$this->fin=$fin;	 	 	 	 	 	 
				$this->ventas=$ventas;	 	 	 	 	 	 
				$this->abonosVentas=$abonosVentas;	 	 	 	 	 	 
				$this->compras=$compras;
				$this->abonosCompras=$abonosCompras;	
				$this->gastos=$gastos;	
				$this->ingresos=$ingresos;	
				$this->gananciasNetas=$gananciasNetas;	
				$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="insert into corte VALUES (NULL,?,?,?,?,?,?,?,?,?,?);";
			$params=array($this->anio,$this->inicio,$this->fin,$this->ventas,$this->abonosVentas,$this->compras,$this->abonosCompras,$this->gastos,$this->ingresos,$this->gananciasNetas);
			if($this->bd->ejecuta($insert,$params)){
				$this->num_corte=$this->bd->con->Insert_ID();
				return true;
			}else 
				return false;
		}
		
		function actualiza(){
			$update="UPDATE  corte SET  `anio`=?,`inicio` =?,`fin` =?,`ventas` =?,`abonosVentas` =?,`compras` =?, `abonosCompras`=? , `gastos`=? , `ingresos`=? , `gananciasNetas`=? where num_corte=?;";
			$params=array($this->anio,$this->inicio,$this->fin,$this->ventas,$this->abonosVentas,$this->compras,$this->abonosCompras,$this->gastos,$this->ingresos,$this->gananciasNetas);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from corte where num_corte =?;";
			$params=array($this->num_corte);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from corte where num_corte=?;";
			$params=array($this->num_corte);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from corte where num_corte=?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->num_corte=$datos['num_corte'];	 	 	 		
			$this->anio=$datos['anio'];	 	 	 		
			$this->inicio=$datos['inicio'];	 	 	 		
			$this->fin=$datos['fin'];	 	 	 		
			$this->ventas=$datos['ventas'];	 	 	 		
			$this->abonosVentas=$datos['abonosVentas'];	 	 	 		
			$this->compras=$datos['compras'];	 	 	 		
			$this->abonosCompras=$datos['abonosCompras'];	 		
			$this->gastos=$datos['gastos'];	 		
			$this->ingresos=$datos['ingresos'];	 		
			$this->gananciasNetas=$datos['gananciasNetas'];	 		
		}
		
		function detalle_corte($id){
			$query = "SELECT nombre,total,deben from detalle_corte where num_corte=?;";
			$params=array($id);
			return 	$productos=$this->bd->select_arr($query,$params);
		}
		
		function existe(){
			$query="select num_corte from corte where num_corte=?;";
			$params=array($this->num_corte);
			return $this->bd->existe($query,$params);
		}
		
		function obtenVentas(){
			$params=array($this->inicio,$this->fin);
			$query="
					SELECT if(sum(v.subtotal + v.iva) is null,0,sum(v.subtotal + v.iva)) as ventas
					FROM 
					ventas v 
					where 
					(DATE(v.fecha) BETWEEN ? AND ? and v.tipo_venta=1)
			";
			$this->ventas= $this->bd->select_un_campo($query,$params);
		}
		
		function obtenAbonosVentas(){
			$params=array($this->inicio,$this->fin);
			$query="
					SELECT if(sum( pv.monto ) is null,0,sum( pv.monto )) as abonos
					FROM 
					pagos_venta pv 
					where
					(DATE(pv.fecha) BETWEEN  ? AND ?);
			";
			$this->abonosVentas= $this->bd->select_un_campo($query,$params);
		}
		
		function obtenCompras(){
			$params=array($this->inicio,$this->fin);
			$query="
					SELECT if(sum(c.subtotal + c.iva) is null,0,sum(c.subtotal + c.iva)) as compras
					FROM 
					compras c 
					where 
					(DATE(c.fecha) BETWEEN ? AND ? and c.tipo_compra=1)
			";
			$this->compras= $this->bd->select_un_campo($query,$params);
		}
		
		function obtenabonosCompras(){
			$params=array($this->inicio,$this->fin);
			$query="
					SELECT if(sum( pc.monto ) is null,0,sum( pc.monto )) as abonos
					FROM 
					pagos_compra pc 
					where
					(DATE(pc.fecha) BETWEEN  ? AND ?);
			";
			$this->abonosCompras= $this->bd->select_un_campo($query,$params);
		}
		
		function obtenerIngresos(){
			$params=array($this->inicio,$this->fin);
			$query="SELECT sum(monto) 
							from ingresos 
							where 
							DATE(fecha) BETWEEN ? AND ? 
							";
			$this->ingresos= $this->bd->select_un_campo($query,$params);
		}
		
		
		function obtenerGastos(){
			$params=array($this->inicio,$this->fin);
			$query="SELECT sum(monto) 
						FROM gastos
						where 
						DATE(fecha) BETWEEN ? AND ? ;
						";
			$this->gastos=$this->bd->select_un_campo($query,$params);
		}
		
		function calculagananciasNetas(){
			$this->gananciasNetas=$this->ventas+$this->abonosVentas+$this->ingresos-$this->gastos-$this->compras-$this->abonosCompras;
		}
		
		function creaVista(){
			//$queryVista="drop view if exists adeudan_sucursal; CREATE VIEW `adeudan_sucursal` AS select `v`.`id_venta` AS `id_venta`,`v`.`sucursal` AS `sucursal`,`e`.`id_usuario` AS `id_usuario`,if((((`v`.`subtotal` + `v`.`iva`) - sum(`pv`.`monto`)) > 0),((`v`.`subtotal` + `v`.`iva`) - sum(`pv`.`monto`)),(`v`.`subtotal` + `v`.`iva`)) AS `Deben`,if((max(`pv`.`fecha`) <> NULL),max(`pv`.`fecha`),`v`.`fecha`) AS `fecha`,`e`.`porciento` AS `porciento` from (`encargado` `e` left join ((`ventas` `v` left join `pagos_venta` `pv` on((`pv`.`id_venta` = `v`.`id_venta`))) left join `usuario` `u` on((`u`.`sucursal_id` = `v`.`sucursal`))) on((`u`.`id_usuario` = `e`.`id_usuario`))) where (`v`.`tipo_venta` = 2) group by `v`.`tipo_venta`,`v`.`sucursal`,`v`.`id_venta`,`e`.`id_usuario`;";
			//$con->ejecuta($queryVista,array());
		}
		
		function borraVista(){
			//$queryDeleteVista="drop view if exists adeudan_sucursal;";
			//$con->ejecuta($queryDeleteVista,array());
		}
		
		function Reporte(){
			$query="select u.nombre,?*(e.porciento*.01) as total,if(sum(deben) is null,0,sum(deben)) as deben
						from encargado e
						join usuario u
						on (e.id_usuario=u.id_usuario)
						left join adeudan_sucursal a
						on (e.id_usuario=a.id_usuario)
						where date(fecha)<?
						or fecha is null
						group by u.nombre,e.porciento;";
			$params=array($this->gananciasNetas,$this->fin);
			
					
			$listar = new listar($query,$params);
			$salida="ventas: $this->ventas, ";
			$salida.="abonosVentas: $this->abonosVentas, ";
			$salida.="compras: $this->compras, ";
			$salida.="abonosCompras: $this->abonosCompras, ";
			$salida.=" gastos: $this->gastos, ";
			$salida.=" ingresos: $this->ingresos, ";
			$salida.=" gananciasNetas: $this->gananciasNetas, ";	
			$salida.=$listar->lista_datos("datos");	
			ok_datos($salida);
		}
		
		function Reporte_corte(){
			$query="select nombre,total,deben
						from detalle_corte
						where num_corte=?;";
			$params=array($this->num_corte);		
			$listar = new listar($query,$params);
			$salida="ventas: $this->ventas, ";
			$salida.="abonosVentas: $this->abonosVentas, ";
			$salida.="compras: $this->compras, ";
			$salida.="abonosCompras: $this->abonosCompras, ";
			$salida.=" gastos: $this->gastos, ";
			$salida.=" ingresos: $this->ingresos, ";
			$salida.=" gananciasNetas: $this->gananciasNetas, ";	
			$salida.=$listar->lista_datos("datos");	
			ok_datos($salida);
		}
		
		function insertaDetalles(){
			$query="insert into detalle_corte
					select ? as num_corte,u.nombre,?*(e.porciento*.01) as total,if(sum(deben) is null,0,sum(deben)) as deben
						from encargado e
						join usuario u
						on (e.id_usuario=u.id_usuario)
						left join adeudan_sucursal a
						on (e.id_usuario=a.id_usuario)
						where date(fecha)<?
						or fecha is null
						group by u.nombre,e.porciento;";
			$params=array($this->num_corte,$this->gananciasNetas,$this->fin);
						
			if($this->bd->ejecuta($query,$params))
				return true;
			else 
				return false;
			
		}
		
	}

?>