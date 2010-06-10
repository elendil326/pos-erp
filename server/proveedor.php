<?php include_once("libBD.php");
	class proveedor{
		var $id_proveedor;	 	 	 	 	 	 	
		var $rfc;	 	 	 	 	 	 	 
		var $nombre;	 	 	 	 	 	 	 
		var $direccion; 	 	 	 	 	 	 
		var $telefono;	 	 	 	 	 	 	 
		var $e_mail;	 	 
		var $bd;
		function __construct($rfc,$nombre,$direccion,$telefono,$e_mail){ 
				 $this->rfc=$rfc; 
				 $this->nombre=$nombre;
				 $this->direccion=$direccion;
				 $this->telefono=$telefono;
				 $this->e_mail=$e_mail;
				 $this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
		//verificar que no exista el proveedor (id): NO ES NECESARIO, EL ID ES AUTOINCREMENT, EL ID NO SE REPETIRA EN LAS INSERCIONES
		//que devuelva el error de porque no logro insertar
			$insert="INSERT INTO  proveedor values(NULL,?,?,?,?,?);";
			$params=array($this->rfc,$this->nombre,$this->direccion,$this->telefono,$this->e_mail);
			if($this->bd->ejecuta($insert,$params)){
				$query="select max(id_proveedor) from proveedor;";
				$this->id_proveedor=$this->bd->select_un_campo($query,array());
				return true;
			}else return false;
		}
		function actualiza(){
		//que solo cambien datos permitidos
		//que devuelva el error
			$update="UPDATE  proveedor SET  `rfc` =  ?, `nombre` =  ?, `direccion` =  ?, `telefono` =?, `e_mail` = ? WHERE  `id_proveedor` =?;";
			$params=array($this->rfc,$this->nombre,$this->direccion,$this->telefono,$this->e_mail,$this->id_proveedor);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from proveedor where id_proveedor=?;";
			$params=array($this->id_proveedor);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
		//que exista el que se quiere borrar
		//que devuelva errores
			$query="delete from proveedor where id_proveedor=?;";
			$params=array($this->id_proveedor);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
		//nos regrese los datos correctos
			$query="select * from proveedor where id_proveedor=?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_proveedor=$datos[id_proveedor];			
			$this->rfc=$datos[rfc];	 	 	 	 	 	 	 
			$this->nombre=$datos[nombre];	 	 	 	 	 	 	 
			$this->direccion=$datos[direccion]; 	 	 	 	 	 	 
			$this->telefono=$datos[telefono];	 	 	 	 	 	 	 
			$this->e_mail=$datos[e_mail];	 	 	 	 		
		}
		function existe(){
			$query="select id_proveedor from proveedor where id_proveedor=?;";
			$params=array($this->id_proveedor);
			return $this->bd->existe($query,$params);
		}
	}
	class proveedor_vacio extends proveedor {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
	class proveedor_existente extends proveedor {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>