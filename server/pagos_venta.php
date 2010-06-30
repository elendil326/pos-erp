<?php 
	class pagos_venta{
		var $id_pago;	 	 	 	 	 	 	
		var $id_venta;	 	 	 	 	 	 	
		var $fecha;	 	 	 	 	 	
		var $monto;
		var $bd;
		
		function __construct($id_venta,$monto){	 	 	 	 	 	
			$this->id_venta=$id_venta;		 	 	
			$this->monto=$monto;	 
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO  pagos_venta values(NULL,?,CURDATE( ),?);";
			$params=array($this->id_venta,$this->monto);
			if($this->bd->ejecuta($insert,$params)){
				$query="select max(id_pago) from pagos_venta;";
				$this->id_pago=$this->bd->select_un_campo($query,array());
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE  pagos_venta SET `id_venta`=?, `fecha`=curdate(), `monto`=? where id_pago=?";
			$params=array($this->id_venta,$this->monto,$this->id_pago);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from pagos_venta where id_pago =?;";
			$params=array($this->id_pago);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from pagos_venta where id_pago=?;";
			$params=array($this->id_pago);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from pagos_venta where id_pago=?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_pago=$datos['id_pago'];	
			$this->id_venta=$datos['id_venta'];	
			$this->fecha=$datos['fecha'];	
			$this->monto=$datos['monto'];	
		}
		function existe(){
			$query="select id_pago from pagos_venta where id_pago=?;";
			$params=array($this->id_pago);
			return $this->bd->existe($query,$params);
		}
		function suma_pagos(){
			$query="select sum(monto) from pagos_venta where id_venta=?;";
			$params=array($this->id_venta);
			return $this->bd->select_un_campo($query,$params);
		}
	}
	class pagos_venta_vacio extends pagos_venta {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class pagos_venta_existente extends pagos_venta {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>