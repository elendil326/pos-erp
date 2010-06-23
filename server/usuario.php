<?php 
	class usuario{
		var $id_usuario;	 	 	 	 	 	 	
		var $nombre;	 	 	 	 	 	 
		var $usuario; 	 	
		var $contrasena;
		var $nivel; 	 	
		var $bd;

		function __construct($nombre,$usuario,$contrasena,$nivel){ 
				 $this->nombre=$nombre; 
				 $this->usuario=$usuario;
				 $this->contrasena=$contrasena;
				 $this->nivel=$nivel;
				 $this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO  usuario values(NULL,?,?,?,?);";
			$params=array($this->nombre,$this->usuario, base64_encode($this->contrasena),$this->nivel);
			if($this->bd->ejecuta($insert,$params)){
				$query="select max(id_usuario) from usuario;";
				$this->id_usuario=$this->bd->select_un_campo($query,array());
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE  usuario SET  `nombre` =  ?, `usuario` =  ?, `nivel` =  ? WHERE  `id_usuario` =?;";
			$params=array($this->nombre,$this->usuario,$this->nivel,$this->id_usuario);
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
			$this->id_usuario=$datos[id_usuario];			
			$this->nombre=$datos[nombre];	 	 	 	 	 	 	 
			$this->usuario=$datos[usuario];			
			$this->contrasena=$datos[contrasena];	 	 	 	 	 	 	 
			$this->nivel=$datos[nivel];
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
		function login(){
			$query="select nivel from usuario where id_usuario=? and contraseña=?;";
			$params=array($this->id_usuario, base64_encode($this->contrasena));
			$nivel=($this->bd->select_un_campo($query,$params));
			return(isset($nivel))?nivel:0;
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
?>