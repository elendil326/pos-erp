<?php include_once("libBD.php");
	class compra{
		var $id_compra;	 	 	 	 	 	 	
		var $id_proveedor;	 	 	 	 	 	 	 
		var $tipo_compra;	 	 	 	 	 	 	 
		var $fecha; 	 	 	 	 	 	 
		var $subtotal;	 	 	 	 	 	 	 
		var $iva;	 	 	 	 	 	 	 
		var $sucursal;	 	 
		var $id_usuario;
		var $bd;
		
		function __construct($id_proveedor,$tipo_compra,$sucursal,$id_usuario){ 
				$this->id_proveedor=$id_proveedor;	 	 	 	 	 	 	 
				$this->tipo_compra=$tipo_compra;	 	 	 	 	 	 
				$this->sucursal=$sucursal;	 	 
				$this->id_usuario=$id_usuario;	 	 	 	 	 	 
				$this->subtotal=0;	 	 	 	 	 	 	 
				$this->iva=0;	
				$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO compras values(NULL,?,?,null,?,?,?,?);";
			$params=array($this->id_proveedor,$this->tipo_compra,$this->subtotal,$this->iva,$this->sucursal,$this->id_usuario);
			if($this->bd->ejecuta($insert,$params)){
				$query="select max(id_compra) from compras;";
				$this->id_compra=$this->bd->select_un_campo($query,array());
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE  compras SET  `id_proveedor`=?,`tipo_compra` =?,`fecha` =CURRENT_TIMESTAMP,`subtotal` =?,`iva` =?,`sucursal` =?, `id_usuario`=? where id_compra=?;";
			$params=array($this->id_proveedor,$this->tipo_compra,$this->subtotal,$this->iva,$this->sucursal,$this->id_usuario,$this->id_compra);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from compras where id_compra =?;";
			$params=array($this->id_compra);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from compras where id_compra=?;";
			$params=array($this->id_compra);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from compras where id_compra=?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_compra=$datos[id_compra];	 	 	 		
			$this->id_proveedor=$datos[id_proveedor];	 	 	 		
			$this->tipo_compra=$datos[tipo_compra];	 	 	 		
			$this->fecha=$datos[fecha];	 	 	 		
			$this->subtotal=$datos[subtotal];	 	 	 		
			$this->iva=$datos[iva];	 	 	 		
			$this->sucursal=$datos[sucursal];	 	 	 		
			$this->id_usuario=$datos[id_usuario];	 		
		}
		function existe(){
			$query="select id_compra from compras where id_compra=?;";
			$params=array($this->id_compra);
			return $this->bd->existe($query,$params);
		}
	}
	class compra_vacio extends compra {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class compra_existente extends compra {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>