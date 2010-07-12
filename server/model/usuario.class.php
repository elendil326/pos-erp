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
			$this->id_sucursal=$datos['sucursal_id'];
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
	
	
	
?>
