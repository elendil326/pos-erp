<?php include_once("libBD.php");
	class factura_compra{
		var $id_factura;	 	 	 	 	 	 	
		var $folio;	 	 	 	 	 	 	
		var $id_compra;	 	 	
		var $bd;
		
		function __construct($folio,$id_compra){ 	 	
			$this->folio=$folio;	 	 	 	 	 	 	
			$this->id_compra=$id_compra;
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO factura_compra values(null,?,?);";
			$params=array($this->folio,$this->id_compra);			
			if($this->bd->ejecuta($insert,$params)){
				$query="select max(id_factura) from factura_venta;";
				$this->id_factura=$this->bd->select_un_campo($query,array());
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE factura_compra SET folio=?, id_compra=? where id_factura=?";
			$params=array($this->folio,$this->id_compra,$this->id_factura);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from factura_compra where id_factura =?;";
			$params=array($this->id_factura);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from factura_compra where  id_factura =?;";
			$params=array($this->id_factura);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from factura_compra where id_factura =?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_factura=$datos[id_factura];	
			$this->folio=$datos[folio];	
			$this->id_compra=$datos[id_compra];	
		}
		function existe(){
			$query="select id_factura from factura_compra where id_factura=?;";
			$params=array($this->id_factura);
			return $this->bd->existe($query,$params);
		}
	}
	class factura_compra_vacio extends factura_compra {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class factura_compra_existente extends factura_compra {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>