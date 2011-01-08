<?php 

require_once('model/inventario.dao.php');
require_once('model/detalle_inventario.dao.php');
require_once('model/detalle_venta.dao.php');
require_once('model/ventas.dao.php');
require_once('model/proveedor.dao.php');
require_once('model/actualizacion_de_precio.dao.php');
require_once('logger.php');

/*
 * listar las existencias para la sucursal dada sucursal
 * */
function listarInventario( $sucID = null){
    
	if(!$sucID){
		return null; 
	}

    $q = new DetalleInventario();
    $q->setIdSucursal( $sucID ); 
    
    $results = DetalleInventarioDAO::search( $q );
    
    $json = array();
    
    foreach( $results as $producto )
	{
        $productoData = InventarioDAO::getByPK( $producto->getIdProducto() );	
       
		$act_precio = new ActualizacionDePrecio();
		$act_precio -> setIdProducto( $producto->getIdProducto() );
		
		$resultados = ActualizacionDePrecioDAO::search( $act_precio );
		
		$fecha_mas_actual = strtotime("2000-1-1 00:00:00");
		
		//buscamos el cambio de precio mas actual (nunca enrtara si no hay una cambio de autorizacion de precio)
		foreach( $resultados as $r ){
		
			$r = parseJSON( $r );
		
			$fecha = strtotime($r->fecha);
			
			//echo "comparando: <br>";
			//echo "fecha acual :" . $fecha_mas_actual . " fecha : " . $fecha ."<br>";
			
			if( $fecha >  $fecha_mas_actual)
			{
				$fecha_mas_actual = $fecha;
				$precioIntersucursal = $r -> precio_intersucursal;
			}
			
		}
	
		
			   
        Array_push( $json , array(
            "productoID" => $productoData->getIdProducto(),
            "descripcion" => $productoData->getDescripcion(),
            "precioVenta" => $producto->getPrecioVenta(),
            "existenciasMinimas" => $producto->getMin(),
            "existencias" => $producto->getExistencias(),
            "medida" => $productoData->getEscala(),
            "precioIntersucursal" => $precioIntersucursal
        ));
    }

	return $json;
	
}

function detalleProductoSucursal( $args ){

    if( !isset( $args['id_producto'] ) )
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    
    if( !($producto = DetalleInventarioDAO::getByPK( $args['id_producto'], $_SESSION['sucursal'] ) ) )
    {
        die('{"success": false, "reason": "No se tiene registros de ese producto." }');
    }

    printf('{ "success": true, "datos": %s }',  $producto);

}



function listarInventarioMaestro ()
{
	
	return InventarioDAO::getAll();
	
	
}

function comprasSucursal( $args ){

    //$_SESSION['sucursal']

    if( isset( $args['id_sucursal'] ) && !empty( $args['id_sucursal'] ) )
    {
        $id_sucursal =$args['id_sucursal'];
    }
    else
    {
        $id_sucursal = $_SESSION['sucursal'];
    }

    $query = new Compras();
    $query->setIdSucursal( $id_sucursal ); 
    
    $compras = ComprasDAO::search( $query );
    
    $array_compras = array();
    
    foreach( $compras as $compra )
    {

        $proveedor = ProveedorDAO::getByPk( $compra->getIdProveedor() );

        array_push( $array_compras , array(
            "id_compra" => $compra->getIdCompra(),
            //"proveedor" => $proveedor->getNombre(),
            //"tipo_compra" => $compra->getTipoCompra(),
            "fecha" => $compra->getFecha(),
            "subtotal" => $compra->getSubtotal(),
            "id_usuario" => $compra->getIdUsuario()
        ));

    }

    $info_compras -> num_compras = count( $array_compras );
    $info_compras -> compras = $array_compras;

    return $info_compras; 

}


function detalleCompra( $args ){

    if( !isset( $args['id_compra'] ) )
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }
    elseif( empty( $args['id_compra'] ) )
    {
        die('{"success": false, "reason": "Verifique los datos." }');
    }

    //verificamos que exista esa compra
    if( !( $compra = ComprasDAO::getByPK( $args['id_compra'] ) ) )
    {
        die('{"success": false, "reason": "No se tiene registro de esa compra." }');
    }

    $q = new DetalleCompra();
    $q->setIdCompra( $args['id_compra'] ); 
    
    $detalle_compra = DetalleCompraDAO::search( $q );
    
    $array_detalle_compra = array();
    
    foreach( $detalle_compra as $producto )
    {
    
        $productoData = InventarioDAO::getByPK( $producto -> getIdProducto() );
        
        array_push( $array_detalle_compra , array(
            "id_producto" => $producto->getIdProducto(),
            "descripcion" => $productoData->getDescripcion(),
            "cantidad" => $producto->getCantidad(),
            "precio" => $producto->getPrecio()
        ));
    }

    $info_compra -> id_compra = $compra -> getIdCompra();
    $info_compra -> total = $compra -> getSubtotal();
    $info_compra -> num_compras = count( $array_detalle_compra );
    $info_compra -> compras = $array_detalle_compra;

    return $info_compra; 

}



function detalleVentas( $id ){

    if( !isset( $id ) )
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }
    elseif( empty( $id ) )
    {
        die('{"success": false, "reason": "Verifique los datos." }');
    }

    //verificamos que exista esa venta
    if( !( $venta = VentasDAO::getByPK( $id ) ) )
    {
        die('{"success": false, "reason": "No se tiene registro de esa venta." }');
    }

    $q = new DetalleVenta();
    $q->setIdVenta( $id ); 
    
    $detalle_venta = DetalleVentaDAO::search( $q );
    
    $array_detalle_venta = array();
    
    foreach( $detalle_venta as $producto )
    {
    
        $productoData = InventarioDAO::getByPK( $producto -> getIdProducto() );
        
        array_push( $array_detalle_venta , array(
            "id_producto" => $producto->getIdProducto(),
            "descripcion" => $productoData->getDescripcion(),
            "cantidad" => $producto->getCantidad(),
            "precio" => $producto->getPrecio()
        ));
    }

    $info_venta -> id_venta = $venta -> getIdVenta();
    $info_venta -> total = $venta -> getTotal();
    $info_venta -> num_ventas = count( $array_detalle_venta );
    $info_venta -> ventas = $array_detalle_venta;

    return $info_venta; 

}



function nuevoProducto($data)
{
    DAO::transBegin();

    try{

        $jsonData = parseJSON($data);
    }catch(Exception $e){
        Logger::log("json invalido para nuevo producto" . $e);
        DAO::transRollback();
        return array( "success" => false, "reason" => "bad json" );
    }

    $inventario = new Inventario();
    $inventario->setCosto ( $jsonData->precio_intersucursal );
    $inventario->setDescripcion ($jsonData->descripcion);
    $inventario->setMedida ($jsonData->medida);
    $inventario->setPrecioIntersucursal ($jsonData->precio_intersucursal);



    try{
        InventarioDAO::save( $inventario );
    }catch(Exception $e){
	    DAO::transRollback();
        return array( "success" => false, "reason" => $e );
        
    }


    //insertar actualizacion de precio
    $actualizacion = new ActualizacionDePrecio();

    $actualizacion->setIdProducto ( $inventario->getIdProducto() );
    $actualizacion->setIdUsuario ( $_SESSION['userid'] );
    $actualizacion->setPrecioCompra ( $inventario->getPrecioIntersucursal() );
    $actualizacion->setPrecioIntersucursal ( $inventario->getPrecioIntersucursal() );
    $actualizacion->setPrecioVenta ( $jsonData->precio_venta );


    try{
        ActualizacionDePrecioDAO::save( $actualizacion );
    }catch(Exception $e){
        DAO::transRollback();
        return array( "success" => false, "reason" => $e );
    }

    Logger::log("Nuevo producto creado !");
    DAO::transEnd();
    return array( "success" => true , "id" => $inventario->getIdProducto() );
}



if(isset($args['action'])){
	switch($args['action']){
	    case 400:
            $json = json_encode( listarInventario( $_SESSION["sucursal"] ) );            
            if(isset($args['hashCheck'])){
                //revisar hashes
                if(md5( $json ) == $args['hashCheck'] ){
                    return;
                }
            }

	    	printf('{ "success": true, "hash" : "%s" , "datos": %s }',  md5($json), $json );


	    break;

	    case 401://regresa el detalle del producto en la sucursal actual
	        detalleProductoSucursal( $args );
	    break;

        case 402://regresa las compras de una sucursal
            printf('{ "success": true, "datos": %s }',  json_encode( comprasSucursal( $args ) ) );
        break;

        case 403://regresa el detalle de la compra
            printf('{ "success": true, "datos": %s }',  json_encode( detalleCompra( $args ) ) );
        break;

        case 404://regresa el detalle de la venta
            printf('{ "success": false, "datos": %s }',  json_encode( detalleVentas( $args['id_venta'] ) ) );
        break;

        case 405://nuevo producto
            echo json_encode( nuevoProducto($args['data']) );
        break;

	    default:
	        printf( '{ "success" : "false" }' );
	    break;

	}
}

