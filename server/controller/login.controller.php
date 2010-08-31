<?php

require_once("../server/model/usuario.dao.php");
require_once("../server/model/grupos_usuarios.dao.php");
require_once("../server/model/grupos.dao.php");
require_once("../server/model/sucursal.dao.php");
include_once("mostrador.controller.php");

function sendLogin( $u, $p )
{
	
	
	global $logger;
	//$logger->setIdent("Login");
	//$logger->log("Peticion de autenticacion para el usuario " . $u . " desde " . $_SERVER['REMOTE_ADDR'] );


		
	
	$user = new Usuario();
	$user->setUsuario( $u );
	$user->setContrasena( $p );
	

	try{
		$res = UsuarioDAO::search( $user );		
	}catch(Exception $e){
		echo "{\"succes\": false , \"reason\": 101, \"text\" : \"Error interno.\" }";
		return;		
	}

	
	

	if(count($res) != 1){
		//loggear intento fallido
		//$logger->log("Credenciales Invalidas !!", PEAR_LOG_WARNING);
		
		if($_SESSION[ 'c' ] < 3 )
		{
			echo "{\"succes\": false , \"reason\": 100, \"text\" : \"Credenciales invalidas.\", \"intentos\": ".$_SESSION[ 'c' ]." }";
		}else{
			echo "{\"succes\": false , \"reason\": 101, \"text\" : \"Porfavor, pongase en contacto consu administrador para recordar su contrasena.\", \"intentos\": ".$_SESSION[ 'c' ]." }";
		}

		return;
	}else{
		
		//$logger->log("Credenciales Aceptadas.");
		unset( $_SESSION[ 'c' ] );
	}

	
	$user = $res[0];

	$grpu = new GruposUsuarios();
	$grpu->setIdUsuario( $user->getIdUsuario() );
	$res = GruposUsuariosDAO::search( $grpu );
	
	if(count($res) < 1){
		echo "{\"succes\": false , \"reason\": 101,  \"text\" : \"Este usuario no pertenece a ningun grupo.\" }";
		//$logger->log("Usuario " . $u . " no pertenece a niungun grupo.", PEAR_LOG_WARNING);
		return;
	}

	$grpu = $res[0];
	
	$grp = new Grupos();
	$grp->setIdGrupo( $grpu->getIdGrupo() );
	
	
	$res = GruposDAO::search($grp);
	
	if(count($res) < 1){
		echo "{\"succes\": false , \"reason\": 101,  \"text\" : \"Este usuario pertenece a un grupo invalido.\" }";
		return;
	}
	
	$grp = $res[0];
	
	
	
	$suc = SucursalDAO::getByPK( $user->getIdSucursal() );
	
	//token de seguridad, 
	
	switch($grp->getNombre()){
		
		//$__ADMIN_TIME_OUT;
		//global $__GERENTE_TIME_OUT;
		//global $__CAJERO_TIME_OUT;		
		
		case 'Administrador' : 
			echo "{\"succes\": true , \"payload\": {  \"sucursal\": null,\"redir\": \"admin/\" }}";	
			
			$_SESSION['userid'] =  $user->getIdUsuario();
			$_SESSION['sucursal'] =  $user->getIdSucursal();
			$_SESSION['grupo'] = $grpu->getIdGrupo();			
			//$_SESSION['timeout'] = $__ADMIN_TIME_OUT;
			$_SESSION['token'] = crypt( $grpu->getIdGrupo() . "-" . $user->getIdSucursal() . "kaffeina" );
			$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
			
			//$logger->log( "Aceptando usuario " . $u . " como Admin ");
			
		break;
		
		case 'Gerente':
			echo "{\"succes\": true , \"payload\": {  \"sucursal_add\": \"" . $suc->getDireccion() . "\", \"sucursal\": " . $user->getIdSucursal() . ", \"redir\": \"pos-start.html\" }}";	
			$_SESSION['userid'] =  $user->getIdUsuario();
			$_SESSION['sucursal'] =  $user->getIdSucursal();
			$_SESSION['grupo'] = $grpu->getIdGrupo();			
			//$_SESSION['timeout'] = $__GERENTE_TIME_OUT;
			$_SESSION['token'] = crypt( $grpu->getIdGrupo() . "-" . $user->getIdSucursal() . "kaffeina" );
			$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
			
			//$logger->log( "Aceptando usuario " . $u . " como Gerente ");
		break;
		
		case 'Cajero':
			echo "{\"succes\": true , \"payload\": {  \"sucursal_add\": \"" . $suc->getDireccion() . "\", \"sucursal\": " . $user->getIdSucursal() . ", \"redir\": \"pos-start.html\" }}";	
			$_SESSION['userid'] =  $user->getIdUsuario();
			$_SESSION['sucursal'] =  $user->getIdSucursal();
			$_SESSION['grupo'] = $grpu->getIdGrupo();
			//$_SESSION['timeout'] = $__CAJERO_TIME_OUT;			
			$_SESSION['token'] = crypt( $user->getIdUsuario()."-".$grpu->getIdGrupo() . "-" . $user->getIdSucursal() . "kaffeina" );
			$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
			
			//$logger->log( "Aceptando usuario " . $u . " como Cajero ");
		break;
	}
	



	

}


function checkSecurityToken()
{
	$current_token = $_SESSION['userid']."-".$_SESSION['grupo']."-".$_SESSION['sucursal']."kaffeina";
	
	if (crypt($curren_token, $_SESSION['token']) == $_SESSION['token']) {
	 	return true;
	}else{
		return false;
	}
}


function checkCurrentSession()
{
	
	//revisar si estoy loginiiiado, salirme
	if( isset( $_SESSION['token'] ) || 
		isset( $_SESSION['userid'] ) || 
		isset( $_SESSION['sucursal'] ) || 
		isset( $_SESSION['grupo'] ) || 
		isset( $_SESSION['timeout'] ) ||
		isset( $_SESSION['token'] ) || 
		isset( $_SESSION['HTTP_USER_AGENT'] )
	){
		logOut();
		$_SESSION[ 'c' ] = 1;

	}else{
		//si no estoy loginiiiado, revisar cuantos intentos de login llevo
		if(isset($_SESSION[ 'c' ]))
		{
			$_SESSION[ 'c' ] ++;
		}else{
			$_SESSION[ 'c' ] = 1;
		}
		
	}
	
}


function logOut( $verbose = false )
{
	//cerrar sesion
	//global $logger;
	//$logger->log("Cerrando Sesion proveniente de " . $_SERVER['REMOTE_ADDR'] . " correspondiente al usuario : " . $_SESSION['userid']);
	
	unset( $_SESSION['token'] ); 
	unset( $_SESSION['userid'] );
	unset( $_SESSION['sucursal'] );
	unset( $_SESSION['grupo'] );
	unset( $_SESSION['timeout'] );
	unset( $_SESSION['token'] );
	unset( $_SESSION['HTTP_USER_AGENT'] );
	
	if($verbose) { 
		echo "{\"succes\": true , \"payload\": { \"text\": \"bye bye\" }}";
	}
}



function askSucursal(){
	
	$tipo = NULL;
	
	$usuario = UsuarioDAO::getByPK( $_SESSION['userid'] );
	$sucursal = SucursalDAO::getByPK( $usuario->getIdSucursal() );
	$iva = getIVA(false);
	

	$tipo_q = new GruposUsuarios();
    $tipo_q->setIdUsuario( $_SESSION['userid'] );
    $tipo_g = GruposUsuariosDAO::search($tipo_q);

	$foo = new Grupos();
	$foo->setIdGrupo( $tipo_g[0]->getIdGrupo() );
	$tipo_r = GruposDAO::search( $foo );
	
	$tipo = $tipo_r[0]->getNombre();

	echo  "{\"sucess\": true, payload : {\"sucursal\": \"" .$sucursal->getDescripcion(). "\", cajero_nombre: \"" . $usuario->getNombre() . "\", \"iva\": \"" . $iva . "\", \"tipo\" : \"" . $tipo . "\"}}";
	
}



function welcome( $token ){
	
	echo  "{\"sucess\": true , \"response\" : \"". $token ."\"}";
	
}

switch($args['action'])
{
	 
	case '2001':
	
		if(isset($args['subaction'])){

			if(isset($args['token']))
				welcome( $args['token'] );
			else
				echo ":(";
			
		}else{
			
			checkCurrentSession();

			sendLogin($args['u'], $args['p']);			
		}

	break;

	case '2002':
		logOut(true);
	break;

	case '2003':
		askSucursal();
	break;


}




