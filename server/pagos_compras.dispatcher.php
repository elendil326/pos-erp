<?php
switch ($args['action']) {
    case '1301':
        $id_compra = $args['id_compra'];
        $monto = $args['monto'];
		unset($args);
		include_once("controller/pagos_compra.controller.php");
        $ans = insert_payment($id_compra , $monto);
        echo $ans;
	break;
	
	case '1302':
		$id_pago = $args['id_pago'];
		unset($args);
		include_once("controller/pagos_compra.controller.php");
		$ans = delete_payment($id_pago);
		echo $ans;
}
?>