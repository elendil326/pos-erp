<?php include("libBD.php");
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
			$insert="INSERT INTO  cliente values(NULL,'".$this->rfc."' ,'".$this->nombre."' ,'".$this->direccion."' ,'".$this->telefono."' ,'".$this->e_mail."' ,'".$this->limite_credito."');";
			return $this->bd->ejecuta($insert);
		}
		function actualiza(){
			$update="UPDATE  cliente SET  `rfc` =  '".$this->rfc."', `nombre` =  '".$this->nombre."', `direccion` =  '".$this->direccion."', `telefono` =  '".$this->telefono."', `e_mail` =  '".$this->e_mail."', `limite_credito` =  '".$this->limite_credito."' WHERE  `cliente`.`id_cliente` =".$this->id_cliente.";";
			return $this->bd->ejecuta($update);
		}
		function json(){
			$query="select * from cliente where id_cliente` =".$this->id_cliente.";";
			return $this->bd->select_json($query);
		}
		function borra (){
			$query="delete from cliente where id_cliente=".$this->id_cliente.";";
			return $this->bd->ejecuta($query);
		}
		function obtener_datos($id){
			$query="select * from cliente where id_cliente='$id';";
			$datos=$this->bd->select_uno($query);
			$this->id_cliente=$datos[id_cliente];			
			$this->rfc=$datos[rfc];	 	 	 	 	 	 	 
			$this->nombre=$datos[nombre];	 	 	 	 	 	 	 
			$this->direccion=$datos[direccion]; 	 	 	 	 	 	 
			$this->telefono=$datos[telefono];	 	 	 	 	 	 	 
			$this->e_mail=$datos[e_mail];	 	 	 	 	 	 	 
			$this->limite_credito=$datos[limite_credito];
		}
		function existe(){
			return $this->bd->existe("select id_cliente from cliente where id_cliente=".$this->id_cliente.";");
		}
	}
	class cliente_vacio extends cliente {       
		public function __construct( ) {
			$this->bd=new bd_default();
		}
	}
	class cliente_existente extends cliente {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>