<?php
/*
	FUNCIONES RELACIONADAS A LA SESION DEL USUARIO
*/
?>
<?php

	function estaLoggeado(){
	
		if(!isset($_SESSION['user']))
		{
			fail('Usuario no esta loggeado');
		}
		else
		{
			ok();
		}
		
		
		return; //no deberia llegar hasta aca
	}
	
	function login(){
	
		$bd = new bd_default();
		
		if(!$bd)
		{ 
			//echo "error grave";
			header("Location: ../www/?error");
			exit;
		}
	
		if(!isset($_POST['user']) || !isset($_POST['pwd']) )
		{
			//Se accedio a esta pagina directamente ejecutando el script, o de una forma no esperada
			header("Location: ../www/");
			exit;
		}
		else
		{
			//Un poco de seguridad
			$usr = $_POST['user'];
			$pwd = strip_tags($_POST['pwd']);
	
			$params = array($usr);
			$qry = "SELECT usuario,contrasena FROM usuario WHERE usuario = ? ";
			
			try{
				$result = $bd->con->Execute($qry,$params);
			}catch(Exception $e){
			
				header("Location: ../www/?error");
				exit;
			}
	
	
			if(!$result)
			{
				//Entra aqui si ocurre algo grave, o la consulta esta mal
				header("Location: ../www/?error");
			}
			else
			{
				if($result->RecordCount() != 1)
				{
					//Si no regresa tuplas, no existe el usuario
					header("Location: ../www/?nousuario");
				}
				else
				{
					//Obtenemos resultado
					$row = $result->FetchNextObj($toupper=false);
					$pwdStored = $row->contrasena;
			
					//Comprobamos que las contrasenas coincidan
					if($pwd != $pwdStored) header("Location: ../www/?contrasena");
					else {
			
						//Si todo bien, se fija la variable de session con el usuario y se redirige
						$_SESSION['user'] = $usr;
						header("Location: ../www/index-sencha.html");
					}
			
				}
			}

		}

	
	}

?>
