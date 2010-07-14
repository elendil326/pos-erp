<?php 
	class detalle_corte{
		var $num_corte;	 	 	 	 	 	 	
		var $nombre;	 	 	 	 	 	 	
		var $total;	 	 	 	 	 	
		var $deben;
		var $bd;
		
		function __construct($num_corte,$nombre,$total,$deben){ 	 	 	 	 	 	
			$this->num_corte=$num_corte;		 	 	
			$this->nombre=$nombre;	 	 	 	 	 	 	
			$this->total=$total;
			$this->deben=$deben;
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO  detalle_corte values(?,?,?,?);";
			$params=array($this->num_corte,$this->nombre,$this->total,$this->deben);
			return ($this->bd->ejecuta($insert,$params))?true:false;
		}
		function actualiza(){
			$update="UPDATE  detalle_corte SET total=?,deben=? where num_corte=? and nombre=?";
			$params=array($this->total,$this->deben,$this->num_corte,$this->nombre);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from detalle_corte where num_corte =? and nombre =?;";
			$params=array($this->num_corte,$this->nombre);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from detalle_corte where num_corte =? and nombre =?;";
			$params=array($this->num_corte,$this->nombre);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($num_c,$nombre){
			$query="select * from detalle_corte where num_corte =? and nombre =?;";
			$params=array($num_c,$nombre);
			$datos=$this->bd->select_uno($query,$params);
			$this->num_corte=$datos['num_corte'];	
			$this->nombre=$datos['nombre'];	
			$this->total=$datos['total'];	
			$this->deben=$datos['deben'];
		}
		function existe(){
			$query="select num_corte from detalle_corte where num_corte=? and nombre=?;";
			$params=array($this->num_corte,$this->nombre);
			return $this->bd->existe($query,$params);
		}
	}
	
	
?>