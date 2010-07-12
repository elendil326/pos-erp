<?php	
/*este documentotiene todas las funciones de usuario
como insertar, eliminar, actualizar, consultas, listar 
y algunas otras funciones
*/
	//esta funcion inserta un usuario
	function insertarUsuario()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['usuario']))&&(!empty($_REQUEST['contrasena']))&&(!empty($_REQUEST['nivel']))&&(!empty($_REQUEST['id_sucursal'])))
		{
			//asignamos valores obtenidos a las variables
			$nombre=$_REQUEST['nombre'];
			$usuario=$_REQUEST['usuario'];
			$contrasena=$_REQUEST['contrasena'];
			$nivel=$_REQUEST['nivel'];
			$id_sucursal=$_REQUEST['id_sucursal'];
			//creamos objeto - usuario
			$user=new usuario($nombre,$usuario,$contrasena,$nivel,$id_sucursal);
			//creamos objeto - sucursal
			$sucursal=new sucursal_existente($id_sucursal);
			//verificamos que la sucursal exista
			if($sucursal->existe())
			{
				//verificamos que no exista el usuario
				if(!$user->existe_usuario())
				{
					if($user->inserta())	ok();														//insertado correctamente
					else					fail("Error al guardar usuario.");							//error al insetar
				}//if existe usuario
				else 						fail("Ya existe un usuario con este nick de usuario.");		//usuario existente
			}//if existe sucursal
			else 							fail("La Sucursal no existe.");								//sucursal inexistente
		}//if verifica datos
		else 								fail("faltan datos");										//datos incompletos
		return;
	}
	//funcion insertar usuario
	
	//esta funcion elimina un usuario
	function eliminarUsuario()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_usuario']))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_usuario'];
			//creamos objeto - usuario existente
			$user=new usuarioExistente($id);
			//verificamos que el usuario exista
			if($user->existe())
			{
				//intentamos borrar
				if($user->borra())		ok();															//borrado exitoso
				else					fail("Error al borrar usuario.");								//fallo el borrado
			}//if existe usuario
			else						fail("El usuario que desea eliminar no existe.");				//usuario inexistente
		}//if verifica datos
		else							fail("faltan datos");											//datos incompletos
		return;
	}
	//funcion eliminar usuario
	
	//esta funcion cambia la contraseña de un usuario
	function cambiaPassword()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_usuario']))&&(!empty($_REQUEST['password'])))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_usuario'];
			$password=$_REQUEST['password'];
			//creamos objeto - usuario existente
			$user=new usuarioExistente($id);
			//verificamos que exista el usuario
			if($user->existe())
			{
				//cambiamos el la contraseña al objeto
				$user->contrasena=$password;
				//intentamos actualizar
				if($user->actualiza_pass())		ok();													//actualizadocorrectamente
				else 							fail("Error al cambiar el password.");					//fallo actualizacion
			}//if existe usuario
			else 								fail("El usuario al que desea cambiar el password no existe.");//usuario inexistente
		}//if verifica datos
		else 									fail("faltan datos");									//datos incompletos
		return;
	}
	//funcion cambia password
	
	
	//esta funcion actualiza los datos de un usuario a excepcion del password
	function actualizarUsuario()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_usuario']))&&(!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['usuario']))&&(!empty($_REQUEST['nivel']))&&(!empty($_REQUEST['id_sucursal'])))
		{			
			//asignamos valores obtenidos a las variables$id=$_REQUEST['id_usuario'];
			$nombre=$_REQUEST['nombre'];
			$usuario=$_REQUEST['usuario'];
			$nivel=$_REQUEST['nivel'];
			$id_sucursal=$_REQUEST['id_sucursal'];
			//creamos objeto - usuario existente
			$user=new usuarioExistente($id);
			//cambiamos el nick de usuario al objeto
			$usu=$user->usuario;
			//verificamos que el usuario existe
			if($user->existe())
			{
				//asignamos los valores de las variables a el objeto
				$user->nombre=$nombre;
				$user->usuario=$usuario;
				$user->nivel=$nivel;
				$user->id_sucursal=$id_sucursal;
				//creamos objeto - sucursal existente
				$sucursal=new sucursal_existente($id_sucursal);
				//verificamos que la sucursal exista
				if($sucursal->existe())
				{
					//verificamos que sea el mismo nick y si no es que no exista otro usuario con ese nick
					if(($usu==$usuario)||(!($user->existe_usuario())))
					{
						//intentamos actualizar
						if($user->actualiza())		ok();												//actualizacion correcta
						else						fail("Error al cambiar el password.");				//error al actualizar
					}//verifica nick de usuario
					else							fail("El nick de usuario que desea asignar ya existe.");//nick existente
				}//if existe sucursal
				else								fail("La sucursal no existe.");						//sucursal inexistente
			}//if existe usuario
			else 									fail("El usuario que desea modificar no existe.");	//usuario inexistente
		}//if verifica datos
		else 										fail("faltan datos");								//datos incompletos
		return;
	}
	//funcion actualizar usuario
	
	//esta funcion lista a todos los usuario y sus campos
	function listarUsuario()
	{
		//creamos objeto - listar con la consulta
		$listar = new listar("select * from usuario",array());
		//imprimimos los datos en un json
		echo $listar->lista();
		return;
	}
	//funcion listar usuario
	
	//esta funcion regresa un json con los datos de un usuario
	function datosUsuario()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_usuario'])))
		{
			$id=$_REQUEST['id_usuario'];
			//creamos objeto - usuario existente
			$user=new usuarioExistente($id);
			//verificamos que exista el usuario
			if($user->existe())									ok_datos("datos: ".$user->json());			//regresa el json del usuario
			else												fail("El usuario no existe.");				//usuario inexistente
		}//if verifica datos
		else 													fail("faltan datos");						//datos incompletos
		return;
	}
	//funcion datosUsuario
	
	//esta funcion loggea a un usuario y nos regresa su informacion
	function loginUsuario()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['usuario']))&&(!empty($_REQUEST['password'])))
		{
			//asignamos valores obtenidos a las variables
			$usuario=$_REQUEST['usuario'];
			$pass=$_REQUEST['password'];
			//creamos objeto - usuario pasandole el nick y contraseña
			$user=new usuarioNombrePass($usuario,$pass);
			//intentamos loggear al usuario
			if($user->login())
				//imprimimos los datos del usuario
				ok_datos("datos: { id_usuario: ".$user->id_usuario." ,nivel : ".$user->nivel." , id_sucursal : ".$user->id_sucursal. "}");
			else												fail("El usuario no existe.");				//usuario inexistente
		}//if verifica datos	
		else 													fail("faltan datos");						//datos inexistentes
		return;
	}
	//funcion login usuario
?>
