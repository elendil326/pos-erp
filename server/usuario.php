<?php 
	class usuario{
		var $id_usuario;	 	 	 	 	 	 	
		var $nombre;	 	 	 	 	 	 
		var $usuario; 	 	
		var $contrasena;
		var $nivel; 	 	
		var $id_sucursal; 	 	
		var $bd;

		function __construct($nombre,$usuario,$contrasena,$nivel,$id_sucursal){ 
				 $this->nombre=$nombre; 
				 $this->usuario=$usuario;
				 $this->contrasena=$contrasena;
				 $this->nivel=$nivel;
				 $this->id_sucursal=$id_sucursal;
				 $this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO  usuario values(NULL,?,?,?,?,?);";
			$params=array($this->nombre,$this->usuario, base64_encode($this->contrasena),$this->nivel,$this->id_sucursal);
			if($this->bd->ejecuta($insert,$params)){
				$this->id_usuario=$this->bd->con->Insert_ID();
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE  usuario SET  `nombre` =  ?, `usuario` =  ?, `nivel` =  ?, id_sucursal=? WHERE  `id_usuario` =?;";
			$params=array($this->nombre,$this->usuario,$this->nivel,$this->id_sucursal,$this->id_usuario);
			return $this->bd->ejecuta($update,$params);
		}
		function actualiza_pass(){
			$update="UPDATE  usuario SET  `contrasena` =  ? WHERE  `id_usuario` =?;";
			$params=array(base64_encode($this->contrasena),$this->id_usuario);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from usuario where id_usuario =?;";
			$params=array($this->id_usuario);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from usuario where id_usuario=?;";
			$params=array($this->id_usuario);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from usuario where id_usuario=?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_usuario=$datos['id_usuario'];			
			$this->nombre=$datos['nombre'];	 	 	 	 	 	 	 
			$this->usuario=$datos['usuario'];			
			$this->contrasena=$datos['contrasena'];	 	 	 	 	 	 	 
			$this->nivel=$datos['nivel'];
			$this->id_sucursal=$datos['id_sucursal'];
		}
		function existe(){
			$query="select id_usuario from usuario where id_usuario=?;";
			$params=array($this->id_usuario);
			return $this->bd->existe($query,$params);
		}
		function existe_usuario(){
			$query="select id_usuario from usuario where usuario=?;";
			$params=array($this->usuario);
			return $this->bd->existe($query,$params);
		}
	}
	class usuario_vacio extends usuario {       
		public function __construct( ) {
			$this->bd=new bd_default();
		}
	}
	class usuario_existente extends usuario {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
	class usuario_nombre_pass extends usuario {
		public function __construct($usuario,$pass) {
			$this->bd=new bd_default(); 	 	 	 	 	 	
			$this->usuario=$usuario;	 
			$this->contrasena=$pass;
		}
		function login(){
			$this->datos();
			return ($this->existe())?true:false;
		}
		function datos(){
			$query="select * from usuario where usuario=? and contrasena=?;";
			$params=array($this->usuario, base64_encode($this->contrasena));
			$datos=$this->bd->select_uno($query,$params);
			$this->id_usuario=$datos['id_usuario'];			
			$this->nombre=$datos['nombre'];	 	 	 	 	 	 	 
			$this->usuario=$datos['usuario'];			
			$this->contrasena=$datos['contrasena'];	 	 	 	 	 	 	 
			$this->nivel=$datos['nivel'];
			$this->id_sucursal=$datos['id_sucursal'];
		}
	}
?>