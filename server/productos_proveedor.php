<?php include_once("libBD.php");
	class productos_proveedor{
		var $id_producto;	 	 	 	 	 	 	
		var $clave_producto;	 	 	 	 	 	 	
		var $id_proveedor;	 	
		var $id_inventario;	 	 	
		var $descripcion;	 	 	
		var $precio;	 	 	
		var $bd;
		
		function __construct($clave_producto,$id_proveedor,$id_inventario,$descripcion,$precio){ 	 	
			$this->clave_producto=$clave_producto;	 	 	 	 	 	 	
			$this->id_proveedor=$id_proveedor;
			$this->id_inventario=$id_inventario;
			$this->descripcion=$descripcion;
			$this->precio=$precio;
			$this->bd=new bd_default();
		}
		function __destruct(){
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO productos_proveedor values(null,?,?,?,?,?);";
			$params=array($this->clave_producto,$this->id_proveedor,$this->id_inventario,$this->descripcion,$this->precio);
			if($this->bd->ejecuta($insert,$params)){
				$query="select max(id_producto) from productos_proveedor;";
				$this->id_producto=$this->bd->select_un_campo($query,array());
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE productos_proveedor SET clave_producto=?, id_proveedor=?, id_inventario=?, descripcion=?, precio=? where id_producto=?";
			$params=array($this->clave_producto,$this->id_proveedor,$this->id_inventario,$this->descripcion,$this->precio,$this->id_producto);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from productos_proveedor where id_producto =?;";
			$params=array($this->id_producto);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from productos_proveedor where id_producto =?;";
			$params=array($this->id_producto);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from productos_proveedor where id_producto =?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_producto=$datos[id_producto];	 	 	 	 	 	 	
			$this->clave_producto=$datos[clave_producto];	 	 	 	 	 	 	
			$this->id_proveedor=$datos[id_proveedor];
			$this->id_inventario=$datos[id_inventario];
			$this->descripcion=$datos[descripcion];
			$this->precio=$datos[precio];
		}
		function existe(){
			$query="select id_producto from productos_proveedor where id_producto=?;";
			$params=array($this->id_producto);
			return $this->bd->existe($query,$params);
		}
	}
	class productos_proveedor_vacio extends productos_proveedor {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class productos_proveedor_existente extends productos_proveedor {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>