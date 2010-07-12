<?php 
	class encargado{
		var $id_usuario;	 	 	 	 	 	 
		var $porciento; 	 	
		var $bd;

		function __construct($id_usuario,$porciento){ 
				 $this->id_usuario=$id_usuario; 
				 $this->porciento=$porciento;
				 $this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
		
		function inserta(){
			$insert="INSERT INTO  encargado values(?,?);";
			$params=array($this->id_usuario,$this->porciento);
			if($this->bd->ejecuta($insert,$params))				return true;
			else return;
		}
		function actualiza(){
			$update="UPDATE  encargado SET  `id_usuario` =  ?, `porciento` =  ? WHERE  `id_usuario` =?;";
			$params=array($this->id_usuario,$this->porciento,$this->id_usuario);
			return $this->bd->ejecuta($update,$params);
		}
		function json(){
			$query="select * from encargado where id_usuario =?;";
			$params=array($this->id_usuario);
			return $this->bd->select_json($query,$params);
		}
		function borra(){
			$query="delete from encargado where id_usuario=? ;";
			$params=array($this->id_usuario);
			return ($this->bd->ejecuta($query,$params))?true:false;
		}
		function obtener_datos($id){
			$query="select * from encargado where id_usuario=?;";
			$params=array($id);
			$datos=($this->bd->select_uno($query,$params));
			$this->id_usuario=$datos['id_usuario'];
			$this->porciento=$datos['porciento'];
		}
		function existe(){
			$query="select id_usuario from encargado where id_usuario=?;";
			$params=array($this->id_usuario);
			return $this->bd->existe($query,$params);
		}
		function cambiar(){
			$query="delete from encargado 
					where id_usuario in(
						select id_usuario from usuario 
						where sucursal_id=(
							select sucursal_id from usuario where id_usuario=?
							)
						);";
			if($this->bd->ejecuta($query,array($this->id_usuario)))
			{
				if($this->porcientoValido())
					return $this->inserta();
				else 
					return false;
			}
			else			return false;
		}
		
		function porcientoValido(){
			$query="select if(sum(porciento)+?>100,false,true) from encargado;";
			return $this->bd->select_un_campo($query,array($this->porciento));
		}
	}
	
	
?>
