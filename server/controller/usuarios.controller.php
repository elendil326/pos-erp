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

/**
*	Funci�n para obtener una lista de usuarios en una sucursal
*
*	@author Luis Michel < luismichel@computer.org >
*	@params Integer id_sucursal La id de la sucursal
*/


function getUsersBySucursalId( $id_sucursal )
{

	$usuario = new Usuario();
	$usuario->setIdSucursal( $id_sucursal );
	$jsonUsuarios = array();
	$jsonUsuarios = UsuarioDAO::search( $usuario );
	
	return $jsonUsuarios;
}


/**
*
*	Modifica los datos de un usuario
*
*	@param <Integer> id_usuario El id del usuario de quien se quieren modificar los datos
*	@param <String> nombre Nuevo nombre del usuario
*	@param <String> usuario Nuevo usuario
*	@param <String> password Nuevo password
*/

function modificarUsuario( $id_usuario, $nombre, $username, $password )
{

	$usuario = UsuarioDAO::getByPK($id_usuario);

	$usuario->setNombre( $nombre );
	$usuario->setUsuario( $username );
	$usuario->setContrasena( $password );
	
	try{
		UsuarioDAO::save( $usuario );
	}
	catch( Exception $e )
	{
		echo ' { "success" : false, "error" : "No se pudieron actualizar los datos del usuario" } ';
	}
	
	echo ' { "success" : true, datos: "Se actualizaron correctamente los datos de '.$nombre.'" } ';

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
	
	case '2302':
		
		
		@$id_sucursal = $args['id_sucursal'];
		
		try{
			
			$Usuarios = getUsersBySucursalId( $id_sucursal );
			$result = array();
			
			//Arreglamos el arreglo porque no se forma bien el JSON con el arreglo devuelto por search
			foreach( $Usuarios as $usuario )
			{
			
				array_push($result, array( "id_usuario" => $usuario->getIdUsuario(),
										   "nombre" => $usuario->getNombre(),
										   "usuario" => $usuario->getUsuario(),
										   "id_sucursal" => $usuario->getIdSucursal()
				) );
			
			}
			
			echo ' { "success": true, "data": '.json_encode( $result ).' } ';
		
		}catch (Exception $e)
		{
			echo '{ "success" : false, "error" : "No se pudieron obtener datos de la base de datos" }';
		}
	break;
	
	case '2303':
	
		//Obtenemos el acceso del usuario
		if ( isset( $_SESSION['grupo'] ) )
		{
			echo ' { "success" : true, "datos": '. $_SESSION['grupo'].' } ';
		}
		else
		{
			echo ' { "success" : false, "error": "No se encontraron datos " } ';
		}
		
		
		
	break;
	
	case '2304':
			
			if( !isset( $args['nombre'] ) && !isset( $args['user2'] ) && !isset( $args['password'] ) && !isset( $args['id_usuario'] ) )
			{
				echo ' { "success" : false, "error" : "Faltan parametros" } ';
			}
			
			$id_usuario = $args['id_usuario'];
			$nombre = $args['nombre'];
			$usuario = $args['user2'];
			$password = $args['password'];
			
			
			modificarUsuario( $id_usuario, $nombre, $usuario, $password );
			
			
	break;
	
	}

?>
