<?php
switch ($args['action']) {
    case '1401':
        $id_cliente = $args['id_cliente'];
        unset($args);
		include_once("clientes_ventas.controller.php");
        $ans = list_client_sales( $id_cliente );
        echo $ans;
	break;
	
	case '1402':
		$id_venta = $args['id_venta'];
        unset($args);
		include_once("clientes_ventas.controller.php");
		$ans = sale_details( $id_venta );
        echo $ans;
	break;
	
	case '1403':
		$id_cliente = $args['id_cliente'];
        unset($args);
		include_once("clientes_ventas.controller.php");
        $ans = credit_clientSales( $id_cliente );
        echo $ans;
	break;
	
	case '1404':
		$id_venta = $args['id_venta'];
        unset($args);
		include_once("clientes_ventas.controller.php");
        $ans = sale_payments( $id_venta );
        echo $ans;
	break;
	
	case '1405':
		$id_venta = $args['id_venta'];
		$monto = $args['monto'];
        unset($args);
		include_once("pagos_ventas.controller.php");
        $ans = insert_payment( $id_venta, $monto );
        echo $ans;
	break;
	
	case '1406':
		$id_pago = $args['id_pago'];
		unset($args);
		include_once("pagos_ventas.controller.php");
		$ans = delete_payment($id_pago);
		echo $ans;
	break;
}//end switch
?>