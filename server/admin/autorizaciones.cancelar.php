<?php


require_once('model/autorizacion.dao.php');
require_once('model/usuario.dao.php');
require_once('model/sucursal.dao.php');
require_once('model/inventario.dao.php');
require_once('model/cliente.dao.php');
require_once('logger.php');

$autorizacion = AutorizacionDAO::getByPK( $_REQUEST['aut'] );
if($autorizacion==NULL){
die("autorizacion no existe");
}
//$autorizacionDetalles = json_decode( $autorizacion->getParametros() );

$usuario = UsuarioDAO::getByPK( $autorizacion->getIdUsuario() );
$sucursal = SucursalDAO::getByPK( $autorizacion->getIdSucursal() );
$autorizacion->setEstado(5);
	try{
		AutorizacionDAO::save($autorizacion);
	}catch(Exception $e){
        Logger::log("Error al guardar la autorizacion : " . $e);
	    die( '{"success": false, "reason": "Error al guardar la autorizacion." }' );
	}

?>



<?php
	if($usuario){
		$who = $usuario->getNombre();	
	}else{
		$who = "Admin";
	}
?>

<h1>Cancelar autorizacion</h1>
<h2>Detalles de la autorizacion</h2>

<table border="0" cellspacing="5" cellpadding="5">
	<tr><td><b>ID Autorizacion</b></td><td><?php    echo $autorizacion->getIdAutorizacion(); ?></td></tr>
	<tr><td><b>Usuario</b></td><td><?php            echo $who; ?></td></tr>
	<tr><td><b>Sucursal</b></td><td><?php           echo $sucursal->getDescripcion(); ?></td></tr>
	<tr><td><b>Fecha de peticion</b></td><td><?php  echo toDate($autorizacion->getFechaPeticion()); ?></td></tr>
	<tr><td><b>Estado</b></td><td> <?php        
	    switch( $autorizacion->getEstado() ){
	        case 0:
	            echo "Sin contestar";
	        break;
	        case 1:
	            echo "Aceptada";
	        break;
	        case 2:
	            echo "Rechazada";
	        break;
	        case 3:
	            echo "En transito";
	        break;
	        case 4:
	            echo "Embarque recibido";
	        break;
	        case 5:
	            echo "Eliminada";
	        break;
	        case 6:
	            echo "Aplicada";
	        break;
	        default:
	            echo "Indefinido (estado {$autorizacion->getEstado()}) ";
	    }
	 ?></td></tr>	

</table>








