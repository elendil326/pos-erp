<?php include_once("libBD.php");
	class detalle_venta{
		var $id_venta;	 	 	 	 	 	 	
		var $id_producto;	 	 	 	 	 	 	
		var $cantidad;	 	 	 	 	 	
		var $precio;	
		var $bd;
		
		function __construct($id_venta,$id_producto,$cantidad,$precio){ 	 	 	 	 	 	
			$this->id_venta=$id_venta;		 	 	
			$this->id_producto=$id_producto;	 	 	 	 	 	 	
			$this->cantidad=$cantidad;
			$this->precio=$precio;
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
		//que la venta si exista
		//que no exista la tupla de id_venta e id_producto anteriormente
		//que la cantidad no sea negativa
		//que el precio no sea negativo
		//hacer cambios en inventario
			$insert="INSERT INTO  detalle_venta values(?,?,?,?);";
			$params=array($this->id_venta,$this->id_producto,$this->cantidad,$this->precio);
			return ($this->bd->ejecuta($insert,$params))?true:false;
		}
		function actualiza(){
		//que la venta si exista
		//que no exista la tupla de id_venta e id_producto anteriormente
		//que la cantidad no sea negativa
		//que el precio no sea negativo
		//hacer cambio en inventario
			$update="UPDATE  detalle_venta SET cantidad=?,precio=? where id_venta=? and id_producto=?";
			$params=array($this->cantidad,$this->precio,$this->id_venta,$this->id_producto);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from detalle_venta where id_venta =? and id_producto =?;";
			$params=array($this->id_venta,$this->id_producto);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from detalle_venta where id_venta =? and id_producto =?;";
			$params=array($this->id_venta,$this->id_producto);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id_c,$id_p){
			$query="select * from detalle_venta where id_venta =? and id_producto =?;";
			$params=array($id_c,$id_p);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_venta=$datos[id_venta];	
			$this->id_producto=$datos[id_producto];	
			$this->cantidad=$datos[cantidad];	
			$this->precio=$datos[precio];	
		}
		function existe(){
			$query="select id_venta from detalle_venta where id_venta=? and id_producto=?;";
			$params=array($this->id_venta,$this->id_producto);
			return $this->bd->existe($query,$params);
		}
	}
	class detalle_venta_vacio extends detalle_venta {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class detalle_venta_existente extends detalle_venta {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>