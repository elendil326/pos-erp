<?php
switch ($args['method']) {
    case '101':
        $rfc = $args['rfc'];
        $nombre = $args['nombre'];
        $direccion = $args['direccion'];
        $limite_credito = $args['limite_credito'];
        $telefono = $args['telefono'];
        $e_mail = $args['e_mail'];
        unset($args);
        $ans = insert_customer($rfc, $nombre, $direccion, $limite_credito, $telefono, $e_mail);
        echo $ans;
	break;
	
	case '102':
		$id = $args['id'];
        $rfc = $args['rfc'];
        $nombre = $args['nombre'];
        $direccion = $args['direccion'];
        $limite_credito = $args['limite_credito'];
        $telefono = $args['telefono'];
        $e_mail = $args['e_mail'];
		$descuento = $args['descuento'];
        unset($args);
        $ans = update_customer($id, $rfc, $nombre, $direccion, $limite_credito, $telefono, $e_mail, $descuento);
        echo $ans;
	break;
	
	case '103':
		$id = $args['id'];
        unset($args);
        $ans = delete_customer($id);
        echo $ans;
	break;
	
	case '104':
		$id = $args['id'];
        unset($args);
        $ans = show_customer($id);
        echo $ans;
	break;
	
	case '105':
        unset($args);
        $ans = list_customers();
        echo $ans;
	break;
}//end switch
?>