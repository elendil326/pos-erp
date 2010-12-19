<?php

require_once("../server/model/usuario.dao.php");
require_once("../server/model/grupos_usuarios.dao.php");
require_once("../server/model/grupos.dao.php");
require_once("../server/model/sucursal.dao.php");

function login( $args )
{
	
	$u = $args['u'];
	$p = $args['p'];

	$user = new Usuario();
	$user->setIdUsuario( $u );
	$user->setContrasena( $p );	

	try{
		$res = UsuarioDAO::search( $user );		
	}catch(Exception $e){
		echo "{\"success\": false , \"reason\": 101, \"text\" : \"Error interno.\" }";
		return;		
	}

	//este usuario no existe
	if(count($res) != 1){
		if( isset( $_SESSION[ 'c' ] )) $_SESSION[ 'c' ] ++; else $_SESSION[ 'c' ] = 1;
		die(  "{\"success\": false , \"reason\": \"Invalidas\", \"text\" : \"Credenciales invalidas. Intento numero <b>". $_SESSION[ 'c' ] . "</b>. \" }" );
	}
	

	//login correcto 
	unset( $_SESSION[ 'c' ] );	

	//buscar en que grupo esta este usuario
	$user = $res[0];

	$grpu = new GruposUsuarios();
	$grpu->setIdUsuario( $user->getIdUsuario() );
	$res = GruposUsuariosDAO::search( $grpu );
	
	if(count($res) < 1){
		echo "{\"success\": false , \"reason\": 101,  \"text\" : \"Aun no perteneces a ningun grupo.\" }";
		return;
	}

	$grpu = $res[0];



	$_SESSION['userid'] =  $user->getIdUsuario();
	$_SESSION['grupo'] = $grpu->getIdGrupo();

    if($grpu->getIdGrupo() == 1){
        //es amdin
    	$_SESSION['token'] = crypt($user->getIdUsuario() ."-". $grpu->getIdGrupo() . "kaffeina" . "/" . $_SERVER['HTTP_USER_AGENT'] );
    }else{
        //es cajero o gerente
    	$_SESSION['token'] = crypt($user->getIdUsuario() ."-". $grpu->getIdGrupo() . "-" . $user->getIdSucursal() . "kaffeina". "/" . $_SERVER['HTTP_USER_AGENT'] );
    }


	//$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);


	if( $grpu->getIdGrupo() == 1 ){
        //si es gerente dejarlo pasar
    	echo "{\"success\": true , \"payload\": { \"sucursaloverride\": true , \"type\": \"" . $grpu->getIdGrupo() . "\" }}";
        return;
    }


    if($user->getIdSucursal() != $_SESSION['sucursal']){
        //no perteneces a esta sucursal
        die( "{\"success\": false , \"reason\": 101,  \"text\" : \"No perteneces a esta sucursal.\" }" );
    }

	echo "{\"success\": true , \"payload\": { \"sucursaloverride\": false , \"type\": \"" . $grpu->getIdGrupo() . "\" }}";
		
	return;

}


function getUserType(){
    echo $_SESSION['grupo'];
    return;
}


function dispatch($args){
	
	if(!isset($_SESSION['grupo'])){
		die( "Accesso no autorizado." );
	}
	
	if(!isset($_SERVER['HTTP_REFERER'])){
		//este request tiene que venir de alguien mas
		die( "Acceso no autorizado." );		
	}
	
	$debug = isset($args['DEBUG']) ? "?debug" : "";


	switch($_SESSION['grupo']){
		case "1" : echo "<script>window.location = 'admin.html".$debug."'</script>"; break;
		case "2" : echo "<script>window.location = 'sucursal.html".$debug."'</script>"; break;
		case "3" : echo "<script>window.location = 'sucursal.html".$debug."'</script>"; break;				
	}
}


function checkSecurityToken()
{
	
    return true;
		
	if (crypt($current_token, $_SESSION['token']) == $_SESSION['token']) {
	 	return true;
	}else{
		return false;
	}
}


function checkCurrentSession()
{
	
	//revisar si estoy loginiiiado
	if( isset( $_SESSION['token'] ) && 
		isset( $_SESSION['userid'] ) &&
		isset( $_SESSION['sucursal'] ) &&
		isset( $_SESSION['grupo'] ) &&
		isset( $_SESSION['token'] ) && 
		isset( $_SESSION['HTTP_USER_AGENT'] )
	){

		$_SESSION[ 'c' ] = 0;
		return checkSecurityToken();
		
	}else{
		//si no estoy loginiiiado, revisar cuantos intentos de login llevo
		if(isset($_SESSION[ 'c' ]))
		{
			$_SESSION[ 'c' ] ++;
		}else{
			$_SESSION[ 'c' ] = 0;
		}
		return false;
	}
	
}


function logOut( $verbose = true  )
{

    $grupo = $_SESSION['grupo'];

	unset( $_SESSION['token'] ); 
	unset( $_SESSION['userid'] );
	unset( $_SESSION['sucursal'] );
	unset( $_SESSION['grupo'] );
	unset( $_SESSION['timeout'] );
	unset( $_SESSION['token'] );
	unset( $_SESSION['HTTP_USER_AGENT'] );

    if($verbose){
        if($grupo == 1)	
        	die ('<script>window.location= "./admin/"</script>');
        else
        	die ('<script>window.location= "."</script>');        
    }


}





/* 
revisar si el token que me esta enviando pertenece a una sucursal valida
*/
function basicTest( $verbose = true ){
	
	//revisar ip's
	$ext_ip = getip();

	$sucursal = new Sucursal();
    $sucursal->setToken( $ext_ip );
    $res = SucursalDAO::search( $sucursal);
	
	if(sizeof( $res ) != 1){
		die(  "{\"success\": false, \"from\": \"".$ext_ip."\", \"response\" : \"Para acceder al punto de venta. Debes estar conectado desde una computadora dentro de la sucursal.\"  }" ) ;
	}
	$sucursal_actual = $res[0];
	
    if($verbose){
	    //revisar si hay una sesion activa
	    if( checkCurrentSession() ){
		    echo  "{\"success\": true , \"sesion\" : true,  \"sucursal\" : " . $sucursal_actual . " }";
	    }else{
		    echo  "{\"success\": true , \"sesion\" : false, \"reason\" : \"Sesion invalida, o no iniciada.\",  \"sucursal\" : " . $sucursal_actual . " }";
	    }
    }

	
	$_SESSION['sucursal'] = $sucursal_actual->getIdSucursal();
	
}


function validip($ip) {
 	

	if (!( !empty($ip) && ip2long($ip)!=-1)) {
		return false;
	}
 
	$reserved_ips = array (

		array('0.0.0.0','2.255.255.255'),

		array('10.0.0.0','10.255.255.255'),

		array('127.0.0.0','127.255.255.255'),

		array('169.254.0.0','169.254.255.255'),

		array('172.16.0.0','172.31.255.255'),

		array('192.0.2.0','192.0.2.255'),

		array('192.168.0.0','192.168.255.255'),

		array('255.255.255.0','255.255.255.255')

	);


	foreach ($reserved_ips as $r) {

		$min = ip2long($r[0]);

		$max = ip2long($r[1]);

		if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;

	}

	return true;
 
}
 
function getip() {

	if ( isset($_SERVER["HTTP_CLIENT_IP"]) && validip($_SERVER["HTTP_CLIENT_IP"])) {
		return $_SERVER["HTTP_CLIENT_IP"] ;
	}

	if( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ){
		foreach (explode(",",$_SERVER["HTTP_X_FORWARDED_FOR"]) as $ip) {
	 		if (validip(trim($ip))) {
	 			return $ip ;
	 		}
	 	}		
	}

 
	if ( isset($_SERVER["HTTP_X_FORWARDED"]) && validip($_SERVER["HTTP_X_FORWARDED"])) {
 
		return $_SERVER["HTTP_X_FORWARDED"] ;
 
	} elseif ( isset($_SERVER["HTTP_FORWARDED_FOR"]) && validip($_SERVER["HTTP_FORWARDED_FOR"])) {
 
		return $_SERVER["HTTP_FORWARDED_FOR"] ;
 
	} elseif ( isset($_SERVER["HTTP_FORWARDED"]) && validip($_SERVER["HTTP_FORWARDED"])) {
 
		return $_SERVER["HTTP_FORWARDED"] ;
 
	} elseif ( isset($_SERVER["HTTP_X_FORWARDED"]) && validip($_SERVER["HTTP_X_FORWARDED"])) {
 
		return $_SERVER["HTTP_X_FORWARDED"] ;
 
	} else {
 
		return $_SERVER["REMOTE_ADDR"] ;
 
	}

}


switch($args['action'])
{
	 
	case '2001':
		basicTest( );
	break;

	case '2002':
		logOut();
	break;

	case '2003':
		basicTest();
	break;

	case '2004':
		login($args);
	break;
	
	case '2005':
		dispatch($args);
	break;

	case '2007':
		getUserType();
	break;
}




