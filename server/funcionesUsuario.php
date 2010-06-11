<?	include_once("AddAllClass.php");
	
	function addUser(){
		$nombre=$_REQUEST['nombre'];
		$usuario=$_REQUEST['usuario'];
		$contraseña=$_REQUEST['contraseña'];
		$nivel=$_REQUEST['nivel'];
		$user=new usuario($nombre,$usuario,$contraseña,$nivel);
		if(!$user->existe_usuario()){
			if($user->inserta()){
				echo "{success : true}";
			}false{
				fail("Error al guardar usuario.");
				return;
			}
		}else {
			fail("Ya existe este usuario.");
			return;
		}
	}
	function deleteUser(){
		$id=$_REQUEST['id_usuario'];
		$user=new usuario_existente($id);
		if($user->existe()){
			if($user->borra()){
				echo "{success : true}";
			}false{
				fail("Error al borrar usuario.");
				return;
			}
		}else {
			fail("El usuario que desea eliminar no existe.");
			return;
		}
	}
	function cambiaPass(){
		$id=$_REQUEST['id_usuario'];
		$password=$_REQUEST['password'];
		$user=new usuario_existente($id);
		if($user->existe()){
		$user->contrasena=$password;
			if($user->actualiza_pass()){
				echo "{success : true}";
			}false{
				fail("Error al cambiar el password.");
				return;
			}
		}else {
			fail("El usuario que no existe.");
			return;
		}
	}
	function cambiaDatos(){
		$id=$_REQUEST['id_usuario'];
		$nombre=$_REQUEST['nombre'];
		$usuario=$_REQUEST['usuario'];
		$nivel=$_REQUEST['nivel'];
		$user=new usuario_existente($id);
		$usu=$user=new usuario;
		if($user->existe()){
		$user->nombre=$nombre;
		$user->usuario=$usuario;
		$user->nivel=$nivel;
			if(!(($usu!=$usuario)&&($user->existe_usuario()))){
				if($user->actualiza()){
					echo "{success : true}";
				}false{
					fail("Error al cambiar el password.");
					return;
				}
			}else{
				fail("El nick de usuario que desea asignar ya existe.");
				return;
			}
		}else {
			fail("El usuario que desea modificar no existe.");
			return;
		}
	}
	
	function fail($razon){
		echo "{success : false, error: '".$razon."'}"
		return;
	}
	if(isset($_REQUEST['method']))
	{
		switch($_REQUEST["method"]){
			case "addUser" : 			addUser(); break;
			case "deleteUser" : 		deleteUser(); break;
			case "cambiaPass" : 		cambiaPass(); break;
			default: echo "-1"; 
		}
	}
?>