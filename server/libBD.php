<?	include("adodb5/adodb.inc.php");
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
			$this->base->$base;
			$this->conecta();
		}
		function __destruct(){ 
			$this->con->Close();
		}
		function conecta(){
			try{
				$dsn=$this->bd_man."://".$this->user.":".$this->password."@".$this->host."/".$this->base;
				$this->con=NewADOConnection($dsn);
			}catch (Exception $e) {
				return false;
			}
		}
		function conexion(){
			return $this->$con;
		}
		
		function ejecuta($query){
			$res=true;	
			if ($this->con->Execute($query) === false)$res=false;
			return $res;
		}
		
		function ejecuta_error($query){	
			if ($this->con->Execute($query) === false)
				$error= $this->con->ErrorMsg();
			return $error;
		}
		
		function select_todo($query){	
			if ($this->con->Execute($query) === false)	return;
			else 	return $this->con->GetAll($query);
		}
		
		function select_arr($query){	
			if (($res=$this->con->Execute($query)) === false)	return;
			else{
				$arr=array();
				while($fila = $res->FetchNextObject(false)) array_push($arr,$fila);
				return $arr;
			}
		}
		
		function select_json($query){	
			if (($res=$this->con->Execute($query)) === false)	return;
			else{
				$arr=array();
				while($fila = $res->FetchNextObject(false)) array_push($arr,$fila);
				return json_encode($arr);
			}
		}
		
		function select_uno($query){	
			if (($res=$this->con->Execute($query)) === false)	return;
			$arr=$res->GetRows();
			return $arr[0];
		}
		
		function existe($query){	
			if (($rs=$this->con->Execute($query)) === false)	return false;
			if (($rs->RecordCount())<1)return false;
			return true;
		}
	}
	class bd_default extends bd{
		function __construct(){ 
		//echo 'mysql://root:@localhost/POS2<br>';
			$this->bd_man="mysql";
			$this->user="root";
			$this->host="localhost";
			$this->password="";
			$this->base="POS2";
			$this->conecta();
		}
	}
?>