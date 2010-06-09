<?php include_once("libBD.php");
	class inventario{
		var $id_producto;	 	 	 	 	 	 	
		var $nombre;	 	 	 	 	 	 	
		var $precio_venta;	 	
		var $minimo;	 	 	
		var $bd;
		
		function __construct($nombre,$precio_venta,$minimo){ 	 	
			$this->nombre=$nombre;	 	 	 	 	 	 	
			$this->precio_venta=$precio_venta;
			$this->minimo=$minimo;
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO inventario values(null,?,?,?);";
			$params=array($this->nombre,$this->precio_venta,$this->minimo);
			if($this->bd->ejecuta($insert,$params)){
				$query="select max(id_producto) from inventario;";
				$this->id_producto=$this->bd->select_un_campo($query,array());
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE inventario SET nombre=?,precio_venta=?, minimo=? where id_producto=?";
			$params=array($this->nombre,$this->precio_venta,$this->minimo,$this->id_producto);
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
			$this->id_producto=$datos[id_producto];	
			$this->nombre=$datos[nombre];	
			$this->precio_venta=$datos[precio_venta];	
			$this->minimo=$datos[minimo];	
		}
		function existe(){
			$query="select id_producto from inventario where id_producto=?;";
			$params=array($this->id_producto);
			return $this->bd->existe($query,$params);
		}
	}
	class inventario_vacio extends inventario {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class inventario_existente extends inventario {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>