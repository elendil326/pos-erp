<?php 
	class unidad_venta{
		var $id_unidad;	 	 	 	 	 	 	
		var $descripcion;	 	 	 	 	 	 	
		var $entero;	 	
		var $bd;
		
		function __construct($descripcion,$entero){ 	 	
			$this->descripcion=$descripcion;	 	 	 	 	 	 	
			$this->entero=$entero;
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO unidad_venta values(null,?,?);";
			$params=array($this->descripcion,$this->entero);
			if($this->bd->ejecuta($insert,$params)){
				$this->id_unidad=$this->bd->con->Insert_ID();
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE unidad_venta SET descripcion=?,entero=? where id_unidad=?";
			$params=array($this->descripcion,$this->entero,$this->unidad_venta);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from unidad_venta where id_unidad =?;";
			$params=array($this->id_unidad);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from unidad_venta where id_unidad =?;";
			$params=array($this->id_unidad);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from unidad_venta where id_unidad =?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_unidad=$datos['id_unidad'];	
			$this->descripcion=$datos['descripcion'];	
			$this->entero=$datos['entero'];	
		}
		function existe(){
			$query="select id_unidad from unidad_venta where id_unidad=?;";
			$params=array($this->id_unidad);
			return $this->bd->existe($query,$params);
		}
	}
	
	
?>
