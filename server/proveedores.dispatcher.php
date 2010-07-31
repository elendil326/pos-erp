<?php
switch ($args['action']) {
	
    case '1101':
        $rfc = $args['rfc'];
        $nombre = $args['nombre'];
        $direccion = $args['direccion'];
        $telefono = $args['telefono'];
        $e_mail = $args['e_mail'];
        unset($args);
		include_once("controller/proveedores.controller.php");
        $ans = insert_provider($rfc, $nombre, $direccion, $telefono , $e_mail);
        echo $ans;
	break;
	
	case '1102':
		$id = $args['id'];
        $rfc = $args['rfc'];
        $nombre = $args['nombre'];
        $direccion = $args['direccion'];
        $telefono = $args['telefono'];
        $e_mail = $args['e_mail'];
        unset($args);
        $ans = update_provider($id, $rfc, $nombre, $direccion, $telefono , $e_mail);
        echo $ans;
	break;
	
	case '1103':
		$id = $args['id'];
        unset($args);
        $ans = delete_provider($id);
        echo $ans;
	break;
	
	case '1104':
		$id = $args['id'];
        unset($args);
        $ans = show_provider($id);
        echo $ans;
	break;
	
	case '1105':
        unset($args);
		include_once("controller/proveedores.controller.php");
        $ans = list_providers();
        echo $ans;
	break;
}// end switch
?>