<?php 
	class cuenta_proveedor{
		var $id_proveedor;	 	 	 	 	 	 	
		var $saldo;
		var $bd;
		
		function __construct($id_proveedor,$saldo){ 	 	 	 	 	 	
			$this->id_proveedor=$id_proveedor;		 	 	
			$this->saldo=$saldo;	
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO  cuenta_proveedor values(?,?);";
			$params=array($this->id_proveedor,$this->saldo);
			if($this->bd->ejecuta($insert,$params)){
				$query ="select * cuenta_proveedor where id_proveedor=?;";
				$params=array($this->id_proveedor);
				$this->saldo=$this->bd->select_un_campo($query,$params);
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE  cuenta_proveedor SET `id_proveedor`=?, `saldo`=? where id_proveedor=?";
			$params=array($this->id_proveedor,$this->saldo,$this->id_proveedor);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from cuenta_proveedor where id_proveedor =?;";
			$params=array($this->id_proveedor);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from cuenta_proveedor where id_proveedor=?;";
			$params=array($this->id_proveedor);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from cuenta_proveedor where id_proveedor=?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);	
			$this->id_proveedor=$datos['id_proveedor'];	
			$this->saldo=$datos['saldo'];	
		}
		function existe(){
			$query="select id_proveedor from cuenta_proveedor where id_proveedor=?;";
			$params=array($this->id_proveedor);
			return $this->bd->existe($query,$params);
		}
	}
	class cuenta_proveedor_vacio extends cuenta_proveedor {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class cuenta_proveedor_existente extends cuenta_proveedor {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>