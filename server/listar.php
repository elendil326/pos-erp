<?php include_once("libBD.php");
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
			if(isset($result)){
				return "{success:true,datos:". json_encode($result);
			}else{
				return "{success:false}";
			}
		}
	}
?>