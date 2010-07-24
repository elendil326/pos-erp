<?php
switch ($args['method']) {
	
    case '201':
        $rfc = $args['rfc'];
        $nombre = $args['nombre'];
        $direccion = $args['direccion'];
        $telefono = $args['telefono'];
        $e_mail = $args['e_mail'];
        unset($args);
        $ans = insert_provider($rfc, $nombre, $direccion, $telefono , $e_mail);
        echo $ans;
	break;
	
	case '202':
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
	
	case '203':
		$id = $args['id'];
        unset($args);
        $ans = delete_provider($id);
        echo $ans;
	break;
	
	case '204':
		$id = $args['id'];
        unset($args);
        $ans = show_provider($id);
        echo $ans;
	break;
	
	case '105':
        unset($args);
        $ans = list_providers();
        echo $ans;
	break;
}// end switch
?>