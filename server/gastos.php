<?php 
	class gasto{
		var $id_gasto;	 	 	 	 	 	 	
		var $concepto;	 	 	 	 	 	 	 
		var $monto;	 	 	 	 	 	 	 
		var $fecha; 	 	 	 	 	 	 
		var $id_sucursal;	 	 
		var $id_usuario;
		var $bd;
		
		function __construct($concepto,$monto,$fecha,$id_sucursal,$id_usuario){ 
				$this->concepto=$concepto;	 	 	 	 	 	 	 
				$this->monto=$monto;	 	 	 	 	 	 
				$this->id_sucursal=$id_sucursal;	 	 
				$this->id_usuario=$id_usuario;
				$this->fecha=$fecha;	 
				$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO gastos values(NULL,?,?,?,?,?);";
			$params=array($this->concepto,$this->monto,$this->fecha,$this->id_sucursal,$this->id_usuario);
			if($this->bd->ejecuta($insert,$params)){
				$this->id_gasto=$this->bd->con->Insert_ID();
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE  gastos SET  `concepto`=?,`monto` =?, `fecha`=?, `id_sucursal` =?, `id_usuario`=? where id_gasto=?;";
			$params=array($this->concepto,$this->monto,$this->fecha,$this->id_sucursal,$this->id_usuario,$this->id_gasto);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from gastos where id_gasto =?;";
			$params=array($this->id_gasto);
			return $this->bd->select_json($query,$params);
		}
		function borra(){
			$query="delete from gastos where id_gasto=?;";
			$params=array($this->id_gasto);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from gastos where id_gasto=?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_gasto=$datos['id_gasto'];	 	 	 		
			$this->concepto=$datos['concepto'];	 	 	 		
			$this->monto=$datos['monto'];	 	 	 		
			$this->fecha=$datos['fecha'];	 	 		
			$this->id_sucursal=$datos['id_sucursal'];	 	 	 		
			$this->id_usuario=$datos['id_usuario'];	 		
		}
		
				
		function existe(){
			$query="select id_gasto from gastos where id_gasto=?;";
			$params=array($this->id_gasto);
			return $this->bd->existe($query,$params);
		}
	}
	class gasto_vacio extends gasto {   
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class gasto_existente extends gasto {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>
