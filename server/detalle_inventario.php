<?php include_once("libBD.php");
	class detalle_inventario{
		var $id_producto;	 	 	 	 	 	 	
		var $id_sucursal;	
		var $precio_venta;	
		var $minimo;	
		var $existencias; 	 	 	 	
		var $bd;
		
		function __construct($id_producto,$id_sucursal,$precio_venta,$min,$existencias){ 	 	 	 	 	 	
			$this->id_producto=$id_producto;
			$this->id_sucursal=$id_sucursal;
			$this->precio_venta=$precio_venta;
			$this->minimo=$min;
			$this->existencias=$existencias;
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO  detalle_inventario values(?,?,?,?,?);";
			$params=array($this->id_producto,$this->id_sucursal,$this->precio_venta,$this->minimo,$this->existencias);
			return ($this->bd->ejecuta($insert,$params))?true:false;
		}
		function actualiza(){

			$update="UPDATE  detalle_inventario SET id_producto=?,id_sucursal=?,precio_venta=?,min=?, existencias=? where id_producto=? and id_sucursal=?";
			$params=array($this->id_producto,$this->id_sucursal,$this->precio_venta,$this->minimo,$this->existencias,$this->id_producto,$this->id_sucursal);

			$update="UPDATE  detalle_inventario SET id_producto=?,id_sucursal=?,precio_venta=?,min=?, existencias=? where id_producto=? and id_sucursal=?";
			$params=array($this->id_producto,$this->id_sucursal,$this->precio_venta,$this->min,$this->existencias,$this->id_producto,$this->id_sucursal);

			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from detalle_inventario where id_producto =? and id_sucursal=?;";
			$params=array($this->id_producto,$this->id_sucursal);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from detalle_inventario where id_producto =? and id_sucursal=?;";
			$params=array($this->id_producto,$this->id_sucursal);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id_p,$id_s){
			$query="select * from detalle_inventario where id_producto=? and id_sucursal=?;";
			$params=array($id_p,$id_s);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_producto=$datos[id_producto];	
			$this->id_sucursal=$datos[id_sucursal];	
			$this->precio_venta=$datos[precio_venta];	
			$this->minimo=$datos[min];	
			$this->existencias=$datos[existencias];	
		}
		function existe(){
			$query="select id_producto from detalle_inventario where id_producto=? and id_sucursal=?;";
			$params=array($this->id_producto,$this->id_sucursal);
			return $this->bd->existe($query,$params);
		}
	}
	class detalle_inventario_vacio extends detalle_inventario {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class detalle_inventario_existente extends detalle_inventario {
		public function __construct($idp,$ids) {
			$this->bd=new bd_default();
			$this->obtener_datos($idp,$ids);
		}
	}
?>