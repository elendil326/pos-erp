<?php include_once("libBD.php");
	class cuenta_cliente{
		var $id_cliente;	 	 	 	 	 	 	
		var $saldo;
		var $bd;
		
		function __construct($id_cliente,$saldo){ 	 	 	 	 	 	
			$this->id_cliente=$id_cliente;		 	 	
			$this->saldo=$saldo;	
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}

		function inserta(){
			$insert="INSERT INTO  cuenta_cliente values(?,?);";
			$params=array($this->id_cliente,$this->saldo);
			if($this->bd->ejecuta($insert,$params)){
				$query ="select * cuenta_cliente where id_cliente=?;";
				$params=array($this->id_cliente);
				$this->saldo=$this->bd->select_un_campo($query,$params);
				return true;
			}else return false;
		}
		function actualiza(){			
			$update="UPDATE  cuenta_cliente SET `id_cliente`=?, `saldo`=? where id_cliente=?";
			$params=array($this->id_cliente,$this->saldo,$this->id_cliente);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from cuenta_cliente where id_cliente =?;";
			$params=array($this->id_cliente);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from cuenta_cliente where id_cliente=?;";
			$params=array($this->id_cliente);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from cuenta_cliente where id_cliente=?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);	
			$this->id_cliente=$datos[id_cliente];	
			$this->saldo=$datos[saldo];	
		}
		function existe(){
			$query="select id_cliente from cuenta_cliente where id_cliente=?;";
			$params=array($this->id_cliente);
			return $this->bd->existe($query,$params);
		}
	}
	class cuenta_cliente_vacio extends cuenta_cliente {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class cuenta_cliente_existente extends cuenta_cliente {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>