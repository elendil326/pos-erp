<?php 
	class detalle_cotizacion{
		var $id_cotizacion;	 	 	 	 	 	 	
		var $id_producto;	 	 	 	 	 	 	
		var $cantidad;	 	 	 	 	 	
		var $precio;	
		var $bd;
		
		function __construct($id_cotizacion,$id_producto,$cantidad,$precio){ 	 	 	 	 	 	
			$this->id_cotizacion=$id_cotizacion;		 	 	
			$this->id_producto=$id_producto;	 	 	 	 	 	 	
			$this->cantidad=$cantidad;
			$this->precio=$precio;
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO  detalle_cotizacion values(?,?,?,?);";
			$params=array($this->id_cotizacion,$this->id_producto,$this->cantidad,$this->precio);
			return ($this->bd->ejecuta($insert,$params));
		}
		function actualiza(){	
			$update="UPDATE  detalle_cotizacion SET cantidad=?,precio=? where id_cotizacion=? and id_producto=?";
			$params=array($this->cantidad,$this->precio,$this->id_cotizacion,$this->id_producto);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from detalle_cotizacion where id_cotizacion=? and id_producto =?;";
			$params=array($this->id_cotizacion,$this->id_producto);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from detalle_cotizacion where id_cotizacion =? and id_producto =?;";
			$params=array($this->id_cotizacion,$this->id_producto);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id_c,$id_p){
			$query="select * from detalle_cotizacion where id_cotizacion =? and id_producto =?;";
			$params=array($id_c,$id_p);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_cotizacion=$datos['id_cotizacion'];	
			$this->id_producto=$datos['id_producto'];	
			$this->cantidad=$datos['cantidad'];	
			$this->precio=$datos['precio'];	
		}
		function existe(){
			$query="select id_cotizacion from detalle_cotizacion where id_cotizacion=? and id_producto=?;";
			$params=array($this->id_cotizacion,$this->id_producto);
			return $this->bd->existe($query,$params);
		}
	}
	
	
?>