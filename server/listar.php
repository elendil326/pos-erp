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
		
		/*
			Funcion de listar pero con formato requerido para el uso en el Grid de jQuery, con las busquedas, paginado y sorting habilitado
		*/
		function lista_jgrid(){
			/*$result=$this->bd->select_arr($this->query,$this->params);
			if(count($result)>0){
				return " { success : true, total : ".count($result)." ,rows : ". json_encode($result)."}";
			}else{
				return " { success : false }";
			}*/
			$page = strip_tags($_POST['page']);
			$rp = strip_tags($_POST['rp']);
			$sortname = strip_tags($_POST['sortname']);
			$sortorder = strip_tags($_POST['sortorder']);

			if (!$sortname) $sortname = 'name';
			if (!$sortorder) $sortorder = 'desc';

			$sort = "ORDER BY $sortname $sortorder";

			if (!$page) $page = 1;
			if (!$rp) $rp = 10;

			$start = (($page-1) * $rp);

			$limit = "LIMIT $start, $rp";
			
			if(isset($_POST['query']) && !empty($_POST['query']))
			{
				$search = strip_tags($_POST['query']);
				$qtype = strip_tags($_POST['qtype']);
				
				$this->query .= " WHERE $qtype LIKE '%$search%'";
			}
			
			try{
				$result = $this->bd->con->Execute($this->query." ".$sort." ".$limit, $this->params);
			}catch(Exception $e){
			
				return false;
			}
			
			//$count = $result->RecordCount();
			
			//$array_result = array();
			$array_obj = array();
			while( $row = $result->FetchRow() )
			{
				array_push($array_obj, array("id"=>$row[0], "cell"=>$row));
			}
			
			$array_result = '{ "page": '.$page.', "total": '.count($array_obj).', "rows" : '.json_encode($array_obj).'}';
			
			echo $array_result;
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
