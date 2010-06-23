<?php 
	class sucursal{
		var $id_sucursal;	 	 	 	 	 	 	
		var $descripcion;	 	 	 	 	 	 
		var $direccion; 	 	
		var $bd;

		function __construct($descripcion,$direccion){ 
				 $this->descripcion=$descripcion; 
				 $this->direccion=$direccion;
				 $this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO  sucursal values(NULL,?,?);";
			$params=array($this->descripcion,$this->direccion);
			if($this->bd->ejecuta($insert,$params)){
				$query="select max(id_sucursal) from sucursal;";
				$this->id_sucursal=$this->bd->select_un_campo($query,array());
				return true;
			}else return;
		}
		function actualiza(){
			$update="UPDATE  sucursal SET  `descripcion` =  ?, `direccion` =  ? WHERE  `id_sucursal` =?;";
			$params=array($this->descripcion,$this->direccion,$this->id_sucursal);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from sucursal where id_sucursal =?;";
			$params=array($this->id_sucursal);
			return $this->bd->select_json($query,$params);
		}
		function borra(){
			$query="delete from sucursal where id_sucursal=? ;";
			$params=array($this->id_sucursal);
			return ($this->bd->ejecuta($query,$params))?true:false;
		}
		function obtener_datos($id){
			$query="select * from sucursal where id_sucursal=?;";
			$params=array($id);
			$datos=($this->bd->select_uno($query,$params));
			$this->id_sucursal=$datos[id_sucursal];			
			$this->descripcion=$datos[descripcion];	 	 	 	 	 	 	 
			$this->direccion=$datos[direccion];
		}
		function existe(){
			$query="select id_sucursal from sucursal where id_sucursal=?;";
			$params=array($this->id_sucursal);
			return $this->bd->existe($query,$params);
		}
	}
	class sucursal_vacio extends sucursal {       
		public function __construct( ) {
			$this->bd=new bd_default();
		}
	}
	class sucursal_existente extends sucursal {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>