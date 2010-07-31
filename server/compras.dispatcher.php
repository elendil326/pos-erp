<?php
switch ($args['action']) {
    case '1201':
        $jsonItems = $args['jsonItems'];
        $id_proveedor = $args['id_proveedor'];
        $tipo_compra = $args['tipo_compra'];
        $modo_compra = $args['modo_compra'];
		unset($args);
		include_once("controller/compras.controller.php");
        $ans = insert_purchase($jsonItems, $id_proveedor, $tipo_compra, $modo_compra);
        echo $ans;
	break;
	
	case '1202':
		$id_compra = $args['id_compra'];
        unset($args);
        $ans = delete_purchase($id_compra);
        echo $ans;
	break;
	
	case '1203':
		$jsonItems = $args['jsonItems'];
        $id_compra = $args['id_compra'];
		$id_proveedor = $args['id_proveedor'];
		$tipo_compra = $args['tipo_compra'];
        $modo_compra = $args['modo_compra'];
        unset($args);
        $ans = addItems_Existent_purchase($jsonItems,$id_compra, $id_proveedor, $tipo_compra, $modo_compra);
        echo $ans;
	break;
	
	case '1204':
		$jsonItems = $args['jsonItems'];
        unset($args);
        $ans = removeItem_Existent_purchase($jsonItems);
        echo $ans;
	break;
	
	case '1205':
		$id_compra = $args['id_compra'];
		$id_producto = $args['id_producto'];
		$precio = $args['precio'];
        $cantidad = $args['cantidad'];
        unset($args);
        $ans = EditItem_Existent_purchase( $id_compra, $id_producto, $precio, $cantidad );
        echo $ans;
	break;
	
	case '1206':
		$id_proveedor = $args['id_proveedor'];
		unset($args);
		include_once("controller/compras.controller.php");
		$ans= list_sucursal_purchases( $id_proveedor );
		echo $ans;
	break;
	
	case '1207':
		$id_compra = $args['id_compra'];
		unset($args);
		include_once("controller/compras.controller.php");
		$ans= purchase_details( $id_compra );
		echo $ans;
	break;
	
	case '1208':
		$id_compra = $args['id_compra'];
		unset($args);
		include_once("controller/compras.controller.php");
		$ans= purchase_payments( $id_compra );
		echo $ans;
	break;
	
	case '1209':
		$id_proveedor = $args['id_proveedor'];
		unset($args);
		include_once("controller/compras.controller.php");
		$ans= credit_providerPurchases( $id_proveedor );
		echo $ans;
	break;
	
	case '1210':
		$id_producto = $args['id_producto'];
		$id_proveedor = $args['id_proveedor'];
		unset($args);
		include_once("controller/compras.controller.php");
		$ans= itemExistence_sucursal( $id_producto, $id_proveedor );
		echo $ans;
	break;
}//end switch
?>