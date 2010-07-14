<?php 
	class inventario{
		var $id_producto;	 	 	 	 	 	 	
		var $nombre;	 	 	 	 	 	 	
		var $denominacion;	 	
		var $unidad_venta;	 	
		var $bd;
		
		function __construct($nombre,$denominacion,$unidad_venta){ 	 	
			$this->nombre=$nombre;	 	 	 	 	 	 	
			$this->denominacion=$denominacion;
			$this->unidad_venta=$unidad_venta;
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO inventario values(null,?,?,?);";
			$params=array($this->nombre,$this->denominacion,$this->unidad_venta);
			if($this->bd->ejecuta($insert,$params)){
				$this->id_producto=$this->bd->con->Insert_ID();
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE inventario SET nombre=?,denominacion=?,unidad_venta=? where id_producto=?";
			$params=array($this->nombre,$this->denominacion,$this->id_producto,$this->unidad_venta);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from inventario where id_producto =?;";
			$params=array($this->id_producto);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from inventario where id_producto =?;";
			$params=array($this->id_producto);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from inventario where id_producto =?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_producto=$datos['id_producto'];	
			$this->nombre=$datos['nombre'];	
			$this->denominacion=$datos['denominacion'];	
			$this->unidad_venta=$datos['unidad_venta'];	
		}
		function existe(){
			$query="select id_producto from inventario where id_producto=?;";
			$params=array($this->id_producto);
			return $this->bd->existe($query,$params);
		}
	}
	
	
?>
