<?

require_once("../server/model/usuario.dao.php");
require_once("../server/model/grupos_usuarios.dao.php");
require_once("../server/model/grupos.dao.php");
require_once("../server/model/sucursal.dao.php");

function sendLogin( $u, $p )
{
	$user = new Usuario();
	$user->setUsuario( $u );
	$user->setContrasena( $p );
	
	$res ;
	try{
		$res = UsuarioDAO::search( $user );		
	}catch(Exception $e){
		echo "{\"succes\": false , \"reason\": 101, \"text\" : \"Error interno.\" }";
		return;		
	}

	
	

	if(count($res) != 1){
		//loggear intento fallido
		if($_SESSION[ 'c' ] < 3 )
		{
			echo "{\"succes\": false , \"reason\": 100, \"text\" : \"Credenciales invalidas.\", \"intentos\": ".$_SESSION[ 'c' ]." }";
		}else{
			echo "{\"succes\": false , \"reason\": 101, \"text\" : \"Porfavor, pongase en contacto consu administrador para recordar su contrasena.\", \"intentos\": ".$_SESSION[ 'c' ]." }";
		}

		return;
	}else{
		unset( $_SESSION[ 'c' ] );
	}

	
	$user = $res[0];
	

	
	$grpu = new GruposUsuarios();
	$grpu->setIdUsuario( $user->getIdUsuario() );
	$res = GruposUsuariosDAO::search( $grpu );
	
	if(count($res) < 1){
		echo "{\"succes\": false , \"reason\": 101,  \"text\" : \"Este usuario no pertenece a ningun grupo.\" }";
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
	
	
	switch($grp->getNombre()){
		case 'Administrador' : 
			echo "{\"succes\": true , \"payload\": {  \"sucursal\": null,\"redir\": \"admin/\" }}";	
			$_SESSION['userid'] =  $user->getIdUsuario() ;
			$_SESSION['grupo'] = crypt( $grpu->getIdGrupo() );
			$_SESSION['sucursal'] = crypt( $user->getIdSucursal() );
			$_SESSION['timeout'] = $__ADMIN_TIME_OUT;
			$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		break;
		
		case 'Gerente':
			echo "{\"succes\": true , \"payload\": {  \"sucursal_add\": \"" . $suc->getDireccion() . "\", \"sucursal\": " . $user->getIdSucursal() . ", \"redir\": \"pos-start.html\" }}";	
			$_SESSION['userid'] =  $user->getIdUsuario() ;
			$_SESSION['grupo'] = crypt( $grpu->getIdGrupo() );
			$_SESSION['sucursal'] = crypt( $user->getIdSucursal() );
			$_SESSION['timeout'] = $__GERENTE_TIME_OUT;
			$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		break;
		
		case 'Cajero':
			echo "{\"succes\": true , \"payload\": {  \"sucursal_add\": \"" . $suc->getDireccion() . "\", \"sucursal\": " . $user->getIdSucursal() . ", \"redir\": \"pos-start.html\" }}";	
			$_SESSION['userid'] =  $user->getIdUsuario() ;
			$_SESSION['grupo'] = crypt( $grpu->getIdGrupo() );
			$_SESSION['sucursal'] = crypt( $user->getIdSucursal() );
			$_SESSION['timeout'] = $__CAJERO_TIME_OUT;
			$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		break;
	}
	



	

}


function checkCurrentSession()
{
	
	//revisar si estoy loginiiiado, salirme
	if( isset( $_SESSION['grupo'] ) || 
		isset( $_SESSION['sucursal'] ) || 
		isset( $_SESSION['timeout'] ) ||
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
	unset( $_SESSION['grupo'] );
	unset( $_SESSION['sucursal'] );
	unset( $_SESSION['timeout'] );
	unset( $_SESSION['HTTP_USER_AGENT'] );
	
	if($verbose) { 
		echo "{\"succes\": true , \"payload\": { \"text\": \"bye bye\" }}";
	}
}





switch($args['action'])
{
	 
	case '1201':
	
		checkCurrentSession();
		
		sendLogin($args['u'], $args['p']);
	break;

	case '1202':
		logOut(true);
	break;

	default:

}




