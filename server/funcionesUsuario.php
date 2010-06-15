<?	include_once("AddAllClass.php");
	
	function addUser(){
		if((!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['usuario']))&&(!empty($_REQUEST['contraseña']))&&(!empty($_REQUEST['nivel']))){
			$nombre=$_REQUEST['nombre'];
			$usuario=$_REQUEST['usuario'];
			$contraseña=$_REQUEST['contraseña'];
			$nivel=$_REQUEST['nivel'];
			$user=new usuario($nombre,$usuario,$contraseña,$nivel);
			if(!$user->existe_usuario()){
				if($user->inserta())	ok();
				else					fail("Error al guardar usuario.");
			}else 						fail("Ya existe un usuario con este nick de usuario.");
		}else 							fail("faltan datos");
		return;
	}
	function deleteUser(){
		if(!empty($_REQUEST['id_usuario'])){
			$id=$_REQUEST['id_usuario'];
			$user=new usuario_existente($id);
			if($user->existe()){
				if($user->borra())		ok();
				else					fail("Error al borrar usuario.");
			}else						fail("El usuario que desea eliminar no existe.");
		}else							fail("faltan datos");
		return;
	}
	
	function cambiaPass(){
		if((!empty($_REQUEST['id_usuario']))&&(!empty($_REQUEST['password']))){
			$id=$_REQUEST['id_usuario'];
			$password=$_REQUEST['password'];
			$user=new usuario_existente($id);
			if($user->existe()){
			$user->contrasena=$password;
				if($user->actualiza_pass())		ok();
				else 							fail("Error al cambiar el password.");
			}else 								fail("El usuario al que desea cambiar el password no existe.");
		}else 									fail("faltan datos");
		return;
	}
	
	function cambiaDatos(){
		if((!empty($_REQUEST['id_usuario']))&&(!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['usuario']))&&(!empty($_REQUEST['nivel']))){
			$id=$_REQUEST['id_usuario'];
			$nombre=$_REQUEST['nombre'];
			$usuario=$_REQUEST['usuario'];
			$nivel=$_REQUEST['nivel'];
			$user=new usuario_existente($id);
			$usu=$user->usuario;
			if($user->existe()){
			$user->nombre=$nombre;
			$user->usuario=$usuario;
			$user->nivel=$nivel;
				if(($usu==$usuario)||(!($user->existe_usuario()))){
					if($user->actualiza())		ok();
					else						fail("Error al cambiar el password.");
				}else							fail("El nick de usuario que desea asignar ya existe.");
			}else 								fail("El usuario que desea modificar no existe.");
		}else fail("faltan datos");
		return;
	}
	
	if(!empty($_REQUEST['method']))
	{
		switch($_REQUEST["method"]){
			case "addUser" : 			addUser(); break;
			case "deleteUser" : 		deleteUser(); break;
			case "cambiaPass" : 		cambiaPass(); break;
			case "cambiaDatos" : 		cambiaDatos(); break;
			default: echo "-1"; 
		}
	}
?>