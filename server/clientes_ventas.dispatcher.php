<?php
switch ($args['action']) {
    case '1401':
        $id_cliente = $args['id_cliente'];
        unset($args);
		include_once("controller/clientes_ventas.controller.php");
        $ans = list_client_sales( $id_cliente );
        echo $ans;
	break;
	
	case '1402':
		$id_venta = $args['id_venta'];
        unset($args);
		include_once("controller/clientes_ventas.controller.php");
		$ans = sale_details( $id_venta );
        echo $ans;
	break;
	
	case '1403':
		$id_cliente = $args['id_cliente'];
        unset($args);
		include_once("controller/clientes_ventas.controller.php");
        $ans = credit_clientSales( $id_cliente );
        echo $ans;
	break;
	
	case '1404':
		$id_venta = $args['id_venta'];
        unset($args);
		include_once("controller/clientes_ventas.controller.php");
        $ans = sale_payments( $id_venta );
        echo $ans;
	break;
	
	case '1405':

	break;
}//end switch
?>