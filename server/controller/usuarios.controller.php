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



function insertUser($nombre, $user2, $pwd, $sucursal)
{	
	//echo $user."===========";

	$user = new Usuario();
	$user->setNombre($nombre);
	$user->setUsuario($user2);
	$user->setContrasena($pwd);
	$user->setIdSucursal($sucursal);
	
	return UsuarioDAO::save($user);
}


switch($args['action']){

	
	case '2301':
	
		$nombre2 = $args['nombre'];
		$user2 = $args['user2'];
		$pwd2 = $args['password'];
		$sucursal2 = $args['sucursal'];
		
		try{
			
			$result = insertUser($nombre2, $user2, $pwd2, $sucursal2);
			
			if($result == 1)
			{
				echo "[{ \"success\" : true, \"message\": 'Usuario insertado correctamente'}]";
			}
			else
			{
				$result = "Occuri&oacute; un error al insertar el usuario nuevo, intente nuevamente";
				echo "[{ \"success\" : false, \"error\": '$result'}]";
			}
			
		}catch(Exception $e){
		
			$result = "Occuri&oacute; un error al insertar el usuario nuevo, intente nuevamente";
			echo "[{ \"success\" : false, \"error\": '$result'}]";
		
		}
		
		
		
	break;
	
	}

?>
