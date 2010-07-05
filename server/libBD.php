<?php
include_once("adodb5/adodb-exceptions.inc.php"); 
include_once("adodb5/adodb.inc.php");
	class bd{
		var $con;
		var $bd_man;
		var $user;
		var $host;
		var $password;
		var $base;
		
		function __construct($bd_man,$user,$host,$password,$base){ 			
			$this->bd_man=$bd_man;
			$this->user=$user;
			$this->host=$host;
			$this->password=$password;
			$this->base=$base;
			$this->conecta();
		}
		function __destruct(){
			$this->con->Close();
		}
		function conecta(){
			try{
				//$dsn=$this->bd_man."://".$this->user.":".$this->password."@".$this->host."/".$this->base;
				//$this->con=NewADOConnection($dsn);
				
				$this->con = ADONewConnection($this->bd_man);
				@$this->con->Connect($this->host, $this->user, $this->password, $this->base);
				
			}catch (exception $e) {
				return false;
			}
		}
		function conexion(){
			return $this->$con;
		}
		
		function ejecuta($query,$arr){
			$res=true;
			if ($this->ejecuta_sanitizada($query,$arr)=== false)$res=false;
			return $res;
		}
		
		function ejecuta_error($query,$arr){	
			if ($this->ejecuta_sanitizada($query,$arr)=== false)
				$error= $this->con->ErrorMsg();
			return $error;
		}
		
		function select_todo($query){	
			if ($this->ejecuta_sanitizada($query,$arr) === false)	return;
			else{
				
				return $this->con->GetAll($query);
			}
		}
		
		function select_arr($query,$arr){	
			if (($res=$this->ejecuta_sanitizada($query,$arr)) === false) return;
			else{
				$arr=array();
				/*
				//este devuelve asociativa y por indice
				while (!$res->EOF){
					array_push($arr,$res->fields);
					$res->MoveNext();
				}			
				return $arr;
				*/
				while(!$res->EOF){
					$algo=$res->GetRowAssoc($toUpper=false);
					foreach($algo as $atributo => &$valor)$valor=utf8_encode($valor);
					array_push($arr,$algo);
					$res->MoveNext();
				}
				
				return $arr;
			}
		}
		
		function select_json($query,$arr){
				return json_encode($this->select_arr($query,$arr));
		}
		
		function select_uno($query,$arr){	
			if (($res=$this->ejecuta_sanitizada($query,$arr)) === false)	return;
			$arr=$res->GetRows();
			return $arr[0];
		}
		
		function select_un_campo($query,$arr){	
			if (($res=$this->ejecuta_sanitizada($query,$arr)) === false)	return;
			$arr=$res->GetRows();
			return $arr[0][0];
		}
		
		function existe($query,$arr){
			if (($rs=$this->ejecuta_sanitizada($query,$arr)) === false)	return false;
			if (($rs->RecordCount())<1)return false;
			return true;
		}
		function ejecuta_sanitizada($query,$params){
			$this->sanitize($params);
			try{
			
				return $rs=$this->con->Execute($query,$params);
				
			}catch(Exception $e){
				//echo "ENTRO AKA!!";
				return json_encode("error no actualiza ".$e->message);
			}
		}
		function sanitize(&$params) {
		    foreach($params as &$param) {
		        if (is_string($param)) {
		            $param = strip_tags($param);
		        }
			}
		}
	}
	
	class bd_default extends bd{
		function __construct(){ 
			$this->bd_man="mysql";
			$this->user="root";
			$this->host="localhost";
			$this->password="";
			$this->base="pos";
			
			return $this->conecta();
		}
	}
?>
