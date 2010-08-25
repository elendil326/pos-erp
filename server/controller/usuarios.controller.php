<?php

/**
 *	Controller para usuarios
 *
 *	Contiene todas las funciones que se conectan con la vista, generalmente JSON
 *	@author Rene Michel <rene@caffeina.mx>
 */
 
/**
 *
 */

/**
 *
 */
require_once('../server/model/usuario.dao.php');
require_once('../server/model/grupos_usuarios.dao.php');


/**
*	Funcion para insertar usuarios a la base de datos incluyendo permisos de acceso
*
*/
function insertUser($nombre, $user2, $pwd, $sucursal, $acceso)
{	
	//echo $user."===========";

	$user = new Usuario();
	$user->setNombre($nombre);
	$user->setUsuario($user2);
	$user->setContrasena($pwd);
	$user->setIdSucursal($sucursal);
	
	$gruposUsuarios = new GruposUsuarios();
	$gruposUsuarios->setIdGrupo($acceso);
	
	if (UsuarioDAO::save($user) == 1)
	{
		$gruposUsuarios->setIdUsuario($user->getIdUsuario());
		return GruposUsuariosDAO::save($gruposUsuarios);
		
	}
	else
	{
		return false;
	}

}


switch($args['action']){

	
	case '2301':
	
		@$nombre2 = $args['nombre'];
		@$user2 = $args['user2'];
		@$pwd2 = $args['password'];
		@$sucursal2 = $args['sucursal'];
		@$acceso = $args['acceso'];
		
		try{
			
			$result = insertUser($nombre2, $user2, $pwd2, $sucursal2, $acceso);
			
			if($result == 1)
			{
				echo "{ \"success\": true, \"message\": \"Usuario insertado correctamente\"}";
			}
			else
			{
				$result = "Occuri&oacute; un error al insertar el usuario nuevo, intente nuevamente";
				echo "{ \"success\": false, \"error\": \"$result\"}";
			}
			
		}catch(Exception $e){
		
			$result = "Occuri&oacute; un error al insertar el usuario nuevo, intente nuevamente";
			echo "{ \"success\": false, \"error\": \"$result\"}";
		
		}
		
		
		
	break;
	
	}

?>
