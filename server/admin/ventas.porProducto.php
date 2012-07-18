<?php

    require_once('model/ventas.dao.php');
    require_once('model/inventario.dao.php');
    require_once('model/detalle_venta.dao.php');
    require_once('model/sucursal.dao.php');

    //obtener todos los productos
    $inventario = InventarioDAO::getAll(  );
	
	$sucursal = SucursalDAO::getByPK(1);
	
	


	//obtener de todas las sucursales !
	
	
	$ventas_por_prod = new Reporte();

	$data_for_table = array();
	
	foreach ($inventario as $prod) {
		$qty = VentasDAO::ventasPorProducto( $prod->getIdProducto(), null );
		$ventas_por_prod->agregarMuestra( $prod->getDescripcion(), $qty, false  );
		array_merge( $data_for_table, $qty );
	}
	

	$ventas_por_prod->fechaDeInicio( strtotime(  $sucursal->getFechaApertura()  ) );
	$ventas_por_prod->setEscalaEnY("unidades");
	$ventas_por_prod->graficar("Ventas por productos");
	return;
	var_dump($data_for_table);
	
	for( ;; ){
		
	}
	
	$ventas_por_prod_tabla = new Tabla();
	//render the table
	$header = array( 
		"fecha" => "ID", 
		"value" => "Descripcion" );

	$ventas_por_prod_tabla = new Tabla( $header, $data_for_table );
	$ventas_por_prod_tabla->render();
?>



