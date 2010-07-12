<?php
class usuarioNombrePass extends usuario {
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
