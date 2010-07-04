<?php 

	class cliente{
		var $id_cliente;	 	 	 	 	 	 	
		var $rfc;	 	 	 	 	 	 	 
		var $nombre;	 	 	 	 	 	 	 
		var $direccion; 	 	 	 	 	 	 
		var $telefono;	 	 	 	 	 	 	 
		var $e_mail;	 	 	 	 	 	 	 
		var $limite_credito;
		var $bd;

		function __construct($rfc,$nombre,$direccion,$telefono,$e_mail,$limite_credito){ 

				 $this->rfc=$rfc; 
				 $this->nombre=$nombre;
				 $this->direccion=$direccion;
				 $this->telefono=$telefono;
				 $this->e_mail=$e_mail;
				 $this->limite_credito=$limite_credito;
				 $this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO  cliente values(NULL,?,?,?,?,?,?);";
			$params=array($this->rfc,$this->nombre,$this->direccion,$this->telefono,$this->e_mail,$this->limite_credito);
			if($this->bd->ejecuta($insert,$params)){
				$this->id_cliente=$this->bd->con->Insert_ID();
				return true;
			}else return false;
		}
		function actualiza(){
			$update="UPDATE  cliente SET  `rfc` =  ?, `nombre` =  ?, `direccion` =  ?, `telefono` =?, `e_mail` = ?, `limite_credito` = ? WHERE  `id_cliente` =?;";
			$params=array($this->rfc,$this->nombre,$this->direccion,$this->telefono,$this->e_mail,$this->limite_credito,$this->id_cliente);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from cliente where id_cliente =?;";
			$params=array($this->id_cliente);
			return $this->bd->select_json($query,$params);
		}
		function borra (){
			$query="delete from cliente where id_cliente=?;";
			$params=array($this->id_cliente);
			//echo $query." ".print_r($params);
			return $this->bd->ejecuta($query,$params);
		}
		function obtener_datos($id){
			$query="select * from cliente where id_cliente=?;";
			$params=array($id);
			$datos=$this->bd->select_uno($query,$params);
			$this->id_cliente=$datos['id_cliente'];			
			$this->rfc=$datos['rfc'];	 	 	 	 	 	 	 
			$this->nombre=$datos['nombre'];	 	 	 	 	 	 	 
			$this->direccion=$datos['direccion']; 	 	 	 	 	 	 
			$this->telefono=$datos['telefono'];	 	 	 	 	 	 	 
			$this->e_mail=$datos['e_mail'];	 	 	 	 	 	 	 
			$this->limite_credito=$datos['limite_credito'];
		}
		function existe(){
			$query="select id_cliente from cliente where id_cliente=?;";
			$params=array($this->id_cliente);
			return $this->bd->existe($query,$params);
		}
	}
	class cliente_vacio extends cliente {       
		public function __construct( ) {
			$this->bd=new bd_default();
		}
	}
	class cliente_existente extends cliente {
		public function __construct($id) {
			//echo "me cree: ".$id;
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>
