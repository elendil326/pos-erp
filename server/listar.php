<?php 
	
	class listar{
		var $bd;
		var $query;
		var $params;
		function __construct($query,$params){ 	
			$this->bd=new bd_default(); 
			$this->query=$query;
			$this->params=$params;
		}
		function __destruct(){
			 return; 
		}
		function lista(){
			$result=$this->bd->select_arr($this->query,$this->params);
			if(count($result)>0){
				return " { success : true, datos : ". json_encode($result)."}";
			}else{
				return " { success : false }";
			}
		}
		function Resultadoconsulta(){
			$result=$this->bd->select_arr($this->query,$this->params);
			if(count($result)>0){
				/*echo "<br>NUMERO DE TUPLAS: ".count($result);
				echo "<br>desde Resultadoconsulta regreso: ".$result[0]["abonado"]."     ";
				echo "<br>   query:  ".$this->query;*/
				return $result;
			}else{
				/*if($result== null) echo "<br>************** nulo desde else";
				echo "<br>NUMERO DE TUPLAS: ".count($result);
				echo "<br>entre a no es mayor a 0 result: ".$result;
				echo " <br>  query:  ".$this->query;
				*/
				return 0;
			}
		}
		function lista_datos($nombre){
			$result=$this->bd->select_arr($this->query,$this->params);
				return "$nombre : ". json_encode($result);
		}
	}
?>