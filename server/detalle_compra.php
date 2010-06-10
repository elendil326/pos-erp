<?php include_once("libBD.php");
	class detalle_compra{
		var $id_compra;	 	 	 	 	 	 	
		var $id_producto;	 	 	 	 	 	 	
		var $cantidad;	 	 	 	 	 	
		var $precio;	
		var $bd;
		
		function __construct($id_compra,$id_producto,$cantidad,$precio){ 	 	 	 	 	 	
			$this->id_compra=$id_compra;		 	 	
			$this->id_producto=$id_producto;	 	 	 	 	 	 	
			$this->cantidad=$cantidad;
			$this->precio=$precio;
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO  detalle_compra values(?,?,?,?);";
			$params=array($this->id_compra,$this->id_producto,$this->cantidad,$this->precio);
			return ($this->bd->ejecuta($insert,$params))?true:false;
		}
		function actualiza(){
			$update="UPDATE  detalle_compra SET cantidad=?,precio=? where id_compra=? and id_producto=?";
			$params=array($this->cantidad,$this->precio,$this->id_compra,$this->id_producto);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from detalle_compra where id_compra =? and id_producto =?;";
			$params=array($this->id_compra,$this->id_producto);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from detalle_compra where id_compra =? and id_producto =?;";
			$params=array($this->id_compra,$this->id_producto);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id_c,$id_p){
			$query="select * from detalle_compra where id_compra =? and id_producto =?;";
			$params=array($id_c,$id_p);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_compra=$datos[id_compra];	
			$this->id_producto=$datos[id_producto];	
			$this->cantidad=$datos[cantidad];	
			$this->precio=$datos[precio];	
		}
		function existe(){
			$query="select id_compra from detalle_compra where id_compra=? and id_producto=?;";
			$params=array($this->id_compra,$this->id_producto);
			return $this->bd->existe($query,$params);
		}
	}
	class detalle_compra_vacio extends detalle_compra {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class detalle_compra_existente extends detalle_compra {
		public function __construct($idc,$idp) {
			$this->bd=new bd_default();
			$this->obtener_datos($idc,$idp);
		}
	}
?>