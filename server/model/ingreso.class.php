<?php 
	class ingreso{
		var $id_ingreso;	 	 	 	 	 	 	
		var $concepto;	 	 	 	 	 	 	 
		var $monto;	 	 	 	 	 	 	 
		var $fecha; 	 	 	 	 	 	 
		var $id_sucursal;	 	 
		var $id_usuario;
		var $bd;
		
		function __construct($concepto,$monto,$fecha,$id_sucursal,$id_usuario){ 
				$this->concepto=$concepto;	 	 	 	 	 	 	 
				$this->monto=$monto;	 	 	 	 	 	 
				$this->id_sucursal=$id_sucursal;	 	 
				$this->id_usuario=$id_usuario;
				$this->fecha=$fecha;	 
				$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO ingresos values(NULL,?,?,?,?,?);";
			$params=array($this->concepto,$this->monto,$this->fecha,$this->id_sucursal,$this->id_usuario);
			if($this->bd->ejecuta($insert,$params)){
				$this->id_ingreso=$this->bd->con->Insert_ID();
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE  ingresos SET  `concepto`=?,`monto` =?, `fecha`=?, `id_sucursal` =?, `id_usuario`=? where id_ingreso=?;";
			$params=array($this->concepto,$this->monto,$this->fecha,$this->id_sucursal,$this->id_usuario,$this->id_ingreso);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from ingresos where id_ingreso =?;";
			$params=array($this->id_ingreso);
			return $this->bd->select_json($query,$params);
		}
		function borra(){
			$query="delete from ingresos where id_ingreso=?;";
			$params=array($this->id_ingreso);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from ingresos where id_ingreso=?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_ingreso=$datos['id_ingreso'];	 	 	 		
			$this->concepto=$datos['concepto'];	 	 	 		
			$this->monto=$datos['monto'];	 	 	 		
			$this->fecha=$datos['fecha'];	 	 		
			$this->id_sucursal=$datos['id_sucursal'];	 	 	 		
			$this->id_usuario=$datos['id_usuario'];	 		
		}
		
				
		function existe(){
			$query="select id_ingreso from ingresos where id_ingreso=?;";
			$params=array($this->id_ingreso);
			return $this->bd->existe($query,$params);
		}
	}
	
	
?>
