<?php

    require_once('model/ventas.dao.php');
    require_once('model/inventario.dao.php');
    require_once('model/detalle_venta.dao.php');






    //obtener todos los productos
    $inventario = InventarioDAO::getAll(  );
    
    $tabla = array();

    foreach ($inventario as $producto)
    {

        $prodData = array();

        $prodData['id_producto'] = $producto->getIdProducto();
        $prodData['descripcion'] = $producto->getDescripcion();

        //calcular fechas de hoy
        $now = new DateTime("now");
        $date = $now;

	    $now->setTime ( 23 , 59, 59 );
	    $v1 = new Ventas();
	    $v1->setFecha( $date->format('Y-m-d H:i:s') );



	    $date->setTime ( 0, 0, 0 );
	    $v2 = new Ventas();
	    $v2->setFecha( $date->format('Y-m-d H:i:s') );

	    $results = VentasDAO::byRange($v1, $v2);
        $totalProductos = 0;

        foreach ($results as $venta)
        {
            $dv = DetalleVentaDAO::getByPK( $venta->getIdVenta(), $producto->getIdProducto() );
            if( $dv == null ) continue;
            $totalProductos += $dv->getCantidad();            
        }

        $prodData['dia'] = $totalProductos;




        //calcular fecha de hace una semana
        $date->sub( new DateInterval("P1W") );
	    $v2 = new Ventas();
	    $v2->setFecha( $date->format('Y-m-d H:i:s') );

	    $results = VentasDAO::byRange($v1, $v2);
        $totalProductos = 0;

        foreach ($results as $venta)
        {
            $dv = DetalleVentaDAO::getByPK( $venta->getIdVenta(), $producto->getIdProducto() );
            if( $dv == null ) continue;
            $totalProductos += $dv->getCantidad();            
        }

        $prodData['semana'] = $totalProductos;






        //calcular fecha de hace un mes
        $date->add( new DateInterval("P1W") );
        $date->sub( new DateInterval("P1M") );
	    $v2 = new Ventas();
	    $v2->setFecha( $date->format('Y-m-d H:i:s') );

	    $results = VentasDAO::byRange($v1, $v2);
        $totalProductos = 0;

        foreach ($results as $venta)
        {
            $dv = DetalleVentaDAO::getByPK( $venta->getIdVenta(), $producto->getIdProducto() );
            if( $dv == null ) continue;
            $totalProductos += $dv->getCantidad();            
        }

        $prodData['mes'] = $totalProductos;

        array_push( $tabla, $prodData );    
    }

    
    $header = array( 
	    "id_producto" =>  "Id",
    	"descripcion" =>  "Descripcion",
    	"dia" =>  "Ultimo dia",
    	"semana" =>  "Ultima semana",
    	"mes" =>  "Ultimo mes");


    $t = new Tabla( $header, $tabla );
    

?>

<h1>Ventas por producto</h1>
<?php $t->render(); ?>

