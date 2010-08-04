<?php
switch ($args['action']) {
    case '1001':
        $rfc = $args['rfc'];
        $nombre = $args['nombre'];
        $direccion = $args['direccion'];
        $limite_credito = $args['limite_credito'];
        $telefono = $args['telefono'];
        $e_mail = $args['e_mail'];
        unset($args);
		include_once("controller/clientes.controller.php");
        $ans = insert_customer($id, $rfc, $nombre, $direccion, $limite_credito, $telefono, $e_mail, $descuento = 0);
        echo $ans;
	break;
	
	case '1002':
		$id = $args['id'];
        $rfc = $args['rfc'];
        $nombre = $args['nombre'];
        $direccion = $args['direccion'];
        $limite_credito = $args['limite_credito'];
        $telefono = $args['telefono'];
        $e_mail = $args['e_mail'];
		$descuento = $args['descuento'];
        unset($args);
		include_once("controller/clientes.controller.php");
        $ans = update_customer($id, $rfc, $nombre, $direccion, $limite_credito, $telefono, $e_mail, $descuento = 0);
        echo $ans;
	break;
	
	case '1003':
		$id = $args['id'];
        unset($args);
        $ans = delete_customer($id);
        echo $ans;
	break;
	
	case '1004':
		$id = $args['id'];
        unset($args);
        $ans = show_customer($id);
        echo $ans;
	break;
	
	case '1005':
        unset($args);
		include_once("controller/clientes.controller.php");
        $ans = list_customers();
        echo $ans;
	break;
}//end switch
?>