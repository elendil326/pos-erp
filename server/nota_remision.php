<?php include_once("libBD.php");
	class nota_remision{
		var $id_nota;	 	 	 	 	 	 	
		var $id_venta;	
		var $bd;
		
		function __construct($id_nota,$id_venta){ 	 	 	 	 	 	
			$this->id_nota=$id_nota;
			$this->id_venta=$id_venta;
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO nota_remision values(?,?);";
			$params=array($this->id_nota,$this->id_venta);
			if($this->bd->ejecuta($insert,$params)){
				$query="select max(id_nota) from nota_remision;";
				$this->id_nota=$this->bd->select_un_campo($query,array());
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE  nota_remision SET id_nota=?, id_venta=? where id_nota=? and id_venta=?";
			$params=array($this->id_nota,$this->id_venta,$this->id_nota,$this->id_venta);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from nota_remision where id_nota =? and id_venta=?;";
			$params=array($this->id_nota,$this->id_venta);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from nota_remision where id_nota =? and id_venta=?;";
			$params=array($this->id_nota,$this->id_venta);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id_p,$id_s){
			$query="select * from nota_remision where id_nota=? and id_venta=?;";
			$params=array($id_p,$id_s);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_nota=$datos[id_nota];		
			$this->id_venta=$datos[id_venta];	
		}
		function existe(){
			$query="select id_nota from nota_remision where id_nota=? and id_venta=?;";
			$params=array($this->id_nota,$this->id_venta);
			return $this->bd->existe($query,$params);
		}
	}
	class nota_remision_vacio extends nota_remision {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class nota_remision_existente extends nota_remision {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>