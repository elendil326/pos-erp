<?php include_once("libBD.php");
	class factura_venta{
		var $id_factura;	 	 	 	 	 	 	
		var $folio;	 	 	 	 	 	 	
		var $id_venta;	 	 	
		var $bd;
		
		function __construct($folio,$id_venta){ 	 	
			$this->folio=$folio;	 	 	 	 	 	 	
			$this->id_venta=$id_venta;
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO factura_venta values(null,?,?);";
			$params=array($this->folio,$this->id_venta);			
			if($this->bd->ejecuta($insert,$params)){
				$query="select max(id_factura) from factura_venta;";
				$this->id_factura=$this->bd->select_un_campo($query,array());
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE factura_venta SET folio=?, id_venta=? where id_factura=?";
			$params=array($this->folio,$this->id_venta,$this->id_factura);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from factura_venta where id_factura =?;";
			$params=array($this->id_factura);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from factura_venta where  id_factura =?;";
			$params=array($this->id_factura);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from factura_venta where id_factura =?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_factura=$datos[id_factura];	
			$this->folio=$datos[folio];	
			$this->id_venta=$datos[id_venta];	
		}
		function existe(){
			$query="select id_factura from factura_venta where id_factura=?;";
			$params=array($this->id_factura);
			return $this->bd->existe($query,$params);
		}
	}
	class factura_venta_vacio extends factura_venta {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class factura_venta_existente extends factura_venta {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>