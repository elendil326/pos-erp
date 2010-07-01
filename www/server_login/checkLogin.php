<?php
/*
	Archivo para controlar el acceso al POS
	
	Para estas consultas se usaran las funciones nativas de mysql
*/
?>
<?php
	
session_start();

//CONEXION A BASE DE DATOS
$con = mysql_connect("localhost","root","123");
if (!$con) die('Error grave');
else mysql_select_db("pos", $con);



if(!isset($_POST['user']) || !isset($_POST['pwd']) )
{
	//Se accedio a esta pagina directamente ejecutando el script, o de una forma no esperada
	header("Location: /");
	exit;
}
else
{
	//Un poco de seguridad
	$usr = htmlentities(strip_tags($_POST['user']));
	$pwd = htmlentities(strip_tags($_POST['pwd']));
	
	$qry = "SELECT usuario,contrasena FROM usuario WHERE usuario = '$usr' ";
	$result = mysql_query($qry);
	
	
	if(!$result)
	{
		//Entra aqui si ocurre algo grave, o la consulta esta mal
		header("Location: ../?error");
	}
	else
	{
		if(mysql_num_rows($result) != 1)
		{
			//Si no regresa tuplas, no existe el usuario
			header("Location: ../?nousuario");
		}
		else
		{
			//Obtenemos resultado
			$row = mysql_fetch_object($result);
			$pwdStored = $row->contrasena;
			
			//Comprobamos que las contrasenas coincidan
			if($pwd != $pwdStored) header("Location: ../?contrasena");
			else {
			
				//Si todo bien, se fija la variable de session con el usuario y se redirige
				$_SESSION['user'] = $usr;
				header("Location: ../index-sencha.html");
			}
			
		}
	}

}


?>
