<?php

require_once("model/usuario.dao.php");
require_once("model/grupos_usuarios.dao.php");
require_once("model/grupos.dao.php");
require_once("model/sucursal.dao.php");
require_once("model/equipo.dao.php");
require_once("model/equipo_sucursal.dao.php");
require_once("logger.php");



function login( $u, $p )
{

	$user = new Usuario();
	$user->setIdUsuario( $u );
	$user->setContrasena( $p );	

	if(strlen($p) < 5 || strlen($u) < 1){
		if( isset( $_SESSION[ 'c' ] )) $_SESSION[ 'c' ] ++; else $_SESSION[ 'c' ] = 1;

        Logger::log("Credenciales muy cortas para el usuario " . $u . " intento:" . $_SESSION[ 'c' ], 1);
		die(  "{\"success\": false , \"reason\": \"Invalidas\", \"text\" : \"Credenciales invalidas. Intento numero <b>". $_SESSION[ 'c' ] . "</b>. \" }" );	
	}

	try{
		$res = UsuarioDAO::search( $user );		
	}catch(Exception $e){
		echo "{\"success\": false , \"reason\": 101, \"text\" : \"Error interno.\" }";
        Logger::log($e);
		return;		
	}


	if(count($res) != 1){
    	//este usuario no existe
		if( isset( $_SESSION[ 'c' ] )) $_SESSION[ 'c' ] ++; else $_SESSION[ 'c' ] = 1;

        Logger::log("Credenciales invalidas para el usuario " . $u . " intento:" . $_SESSION[ 'c' ], 1);
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
        Logger::log("Usuario  " . $u . " no pertenence a ningun grupo." , 1);
		return;
	}


    //usuario valido, y grupo valido
	$grpu = $res[0];

    $_SESSION['ip'] = getip();
    $_SESSION['pass'] = $p;
    $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
	$_SESSION['grupo']  =  $grpu->getIdGrupo();
	$_SESSION['userid'] =  $user->getIdUsuario();


    

    if($grpu->getIdGrupo() == 3){

        if($user->getIdSucursal() != $_SESSION['sucursal']){
            Logger::log("cajero intento loggearse en una sucursal que no es suya");
            die( "{\"success\": false , \"reason\": 101,  \"text\" : \"No perteneces a esta sucursal.\" }" );
        }

    }


    Logger::log("Accesso autorizado para usuario  " . $u );
	echo "{\"success\": true , \"payload\": { \"sucursaloverride\": false , \"type\": \"" . $grpu->getIdGrupo() . "\" }}";
		
	return true;

}




function getUserType(){
	if(isset($_SESSION['grupo']))
	    echo $_SESSION['grupo'];
	else
        die( '{"success": false , "reason": "Accesso denegado" }' );

}








/*
    regresa verdadero si la sesion actual 
    es valida para el grupo de usuario dado
    regresa falso si no hay sesion alguna
    o bien si los parametros de session
    no concuerdan
 */
function checkCurrentSession()
{
	
	if( !isset( $_SESSION['grupo'] ) ){
        Logger::log("session[grupo] not set !");
        return false;
	}


    if(!isset($_SESSION['userid'])){
        Logger::log("session[userid] not set !");
        return false;
    }

    $ip = getip();
    if( !(isset( $_SESSION['ip'] ) && $_SESSION['ip'] == $ip) ){
        Logger::log("session[ip] not set or wrong!");
        Logger::log("session:" . $_SESSION['ip'] . " actual:" . $ip );
        return false;
    }

    $user = UsuarioDAO::getByPK( $_SESSION['userid'] );

    if($user === null){
        Logger::log("Usuario en sesion no existe en la base de datos");
        return false;
    }

    $pass = $user->getContrasena();

    if( !(isset( $_SESSION['pass'] ) && $_SESSION['pass'] == $pass) ){
        Logger::log("session[pass] not set or wrong !");
        return false;
    }

    if( !(isset( $_SESSION['ua'] ) &&  $_SESSION['ua'] == $_SERVER['HTTP_USER_AGENT']) ){
        Logger::log("session[ua] not set or wrong!");
        return false;
    } 

    $grupoUsuario = GruposUsuariosDAO::getByPK( $_SESSION['userid'] );
    
    if( $grupoUsuario->getIdGrupo() != $_SESSION['grupo'] ){
        Logger::log("session[grupo] wrong ! !");
        return false;
    }


    //si es cajero, revisar que este en su sucursal
    if( $_SESSION['grupo'] == 3 ){
        if( $_SESSION['sucursal'] != $user->getIdSucursal() ){
             Logger::log("session[sucursal] wrong for cajero !");
            return false;
        }
    }

    //Logger::log("Sesion actual valida para usuario : {$_SESSION['userid']}" );
    return true;

}




function logOut( $verbose = true  )
{
    
    if(isset($_SESSION['userid']))
        Logger::log("Cerrando sesion para {$_SESSION['userid']}");
    else
        Logger::log("Cerrando sesion generica");

    if($verbose){
        if(isset($_SESSION['grupo'])){
            if($_SESSION['grupo'] <= 1)	
                	print ('<script>window.location= "./admin/"</script>');
            else
                	print ('<script>window.location= "."</script>');
        }else{
        	print ('<script>window.location= "."</script>');                
        }
    }

    
    

    session_unset ();

}





/* 
    revisar si vengo de una sucursal valida
    regresa verdadero si es una sucursal valida
    si es una sucursal valida, la pone en
    la variable de sesion de sucursal
    */
function sucursalTest( ){
	
    //obtener el User agent que me envian
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if(POS_SUCURSAL_TEST_TOKEN == 'FULL_UA'){
            Logger::log("Testing sucursal via " . POS_SUCURSAL_TEST_TOKEN);
            $equipo = new Equipo();
            $equipo->setFullUa( $ua );
            $search = EquipoDAO::search( $equipo );

            if(sizeof($search) != 1){
                Logger::log("UA: >" . $_SERVER['HTTP_USER_AGENT'] . "< not found in database", 2);

                return false;
            }

            $equipo = $search[0];
            Logger::log("Full UA found !", 2);            
            

    }elseif(POS_SUCURSAL_TEST_TOKEN == 'SID_TOKEN'){
            $pos = strrpos( $ua, "sid={" );

            if($pos === FALSE){
                //no se encuentra la cadena
                Logger::log("user agent no contiene token !", 1);
                return false;
            }


            //buscar ese token en la lista de quipos
            $equipoToken = substr($ua, stripos($ua, "sid={") + 5 , 5);

            $equipo = new Equipo();
            $equipo->setToken( $equipoToken );
            $search = EquipoDAO::search( $equipo );

            if(sizeof($search) != 1){
                Logger::log("UA sent token { " . $equipoToken  ." } not found in DB", 2);
                return false;
            }

            $equipo = $search[0];
            Logger::log("UA sent token { " . $equipoToken  ." } found for equipo={$equipo->getIdEquipo()}", 2);

    }else{
        Logger::log('Modo de verificacion invalido en configuracion.');
        return false;
    }





    $esuc = new EquipoSucursal();
    $esuc->setIdEquipo($equipo->getIdEquipo());

    $search = EquipoSucursalDAO::search( $esuc );    

    if(sizeof($search) != 1){
        Logger::log("equipo {$equipo->getIdEquipo()} no se encuentra vinculado a ninguna sucursal");
        return false;
    }

    $suc = $search[0];

    //ver que si exista esta sucursal
    $suc = SucursalDAO::getByPK($suc->getIdSucursal());
    
    if($suc === null){
        Logger::log("equipo {$equipo->getIdEquipo()} vinculado a sucursal {$esuc->getIdSucursal()} pero esta no existe !", 2);
        return false;
    }
    
    if($suc->getActivo() == 0){
        Logger::log("equipo {$equipo->getIdEquipo()} vinculado a suc {$suc->getIdSucursal()} pero esta no esta activa !", 2);
        return false; 
    }

    Logger::log("Equipo validado !");
    $_SESSION['sucursal'] = $suc->getIdSucursal();
    return true;

}



function dispatch($args){
	Logger::log("Dispatching route for user group {$_SESSION['grupo']}");
	if(!isset($_SESSION['grupo'])){
		die( "Accesso no autorizado." );
	}
	
	if(!isset($_SERVER['HTTP_REFERER'])){
		//este request tiene que venir de alguien mas
        Logger::log("No hay HTTP_REFERER para esta solicitud de dispatching !", 1);
		die( "Acceso no autorizado." );		
	}
	
	$debug = isset($args['DEBUG']) ? "?debug" : "";


	switch($_SESSION['grupo']){
		case "1" : echo "<script>window.location = 'admin.html".$debug."'</script>"; break;
		case "2" : echo "<script>window.location = 'sucursal.html".$debug."'</script>"; break;
		case "3" : echo "<script>window.location = 'sucursal.html".$debug."'</script>"; break;
        case "0" : echo "<script>window.location = 'ingenieria.html".$debug."'</script>"; break;
	}
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




function login_controller_dispatch($args){

	if(isset($args['action'])){

		switch($args['action'])
		{
			 
			case '2001':

		        //revisar estado de sesion en sucursal
				if(!sucursalTest()){
		            //si no pasa el test de la sucursal...
		           print(  '{"success": false, "response" : "Porfavor utilize un punto de venta destinado para esta sucursal."  }' ) ;

		        }else{

		            //la sucursal esta bien, hay que ver si esta logginiado
		            if(checkCurrentSession()){
		               //logged in !
		                print(  '{"success":true,"sesion":true}' );
		            }else{
		                //not logged in
		                $sucursal = SucursalDAO::getByPK( $_SESSION['sucursal'] );
		                Logger::log("Sesion invalida");
		                logOut(false);
		                print(  '{"success":true,"sesion":false,"sucursal":"' .$sucursal->getDescripcion(). '"}' );                    
		            }
		        }
			break;

			case '2002':
				logOut(true);
			break;

			/*
			case '2003':
				sucursalTest();
			break;
			*/

			case '2004':
		        //login desde la sucursal
				if(!sucursalTest()){
		            //si no pasa el test de la sucursal...
		           print(  '{"success": false, "response" : "Porfavor utilize un punto de venta destinado para esta sucursal."  }' ) ;
		        }else{
		            //enviar login
		            login($args['u'], $args['p']);
		        }
			break;

			case '2099':
			    //login desde otro lado
			    if(!isset($args['u'])){
			    	$u = "";
			    }else{
			    	$u = $args['u'];
			    }
			    
			    if(!isset($args['p'])){
			    	$p = "";
			    }else{
			    	$p = $args['p'];
			    }
			    
		        login($u, $p);
			break;


			case '2005':
				dispatch($args);
			break;

			case '2007':
				getUserType();
			break;

			case '2009':
			   
			break;
		}
	}

}






