<?php 

require_once('model/inventario.dao.php');
require_once('model/detalle_inventario.dao.php');
require_once('model/detalle_venta.dao.php');
require_once('model/ventas.dao.php');
require_once('model/proveedor.dao.php');
require_once('model/actualizacion_de_precio.dao.php');
require_once('model/compra_proveedor.dao.php');
require_once('model/detalle_compra_proveedor.dao.php');
require_once('model/inventario_maestro.dao.php');
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
		
		//buscamos el cambio de precio mas actual (nunca entrara si no hay una cambio de autorizacion de precio)
		$precioIntersucursal = "No def";
		
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
            "tratamiento" => $productoData->getTratamiento(),
            "precioVenta" => $producto->getPrecioVenta(),
            "existenciasMinimas" => $producto->getMin(),
            "existenciasOriginales" => $producto->getExistencias(),
            "existenciasProcesadas" => $producto->getExitenciasProcesadas(),
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


/**
  * Obtiene las ultimas n entradas del inventario maestro ordenadas por fecha.
  * Por default n es 50.
  * 
  * */
function listarInventarioMaestro( $n = 50 )
{
	
	//meter el inventario aqui, para no estar haciendo querys
	$inventario = InventarioDAO::getAll(  );
	
	//meter aqui las sucursales para no estar buscando en la base
	$sucursales = SucursalDAO::getAll();
	
	$registro = array();

	//obtener todas las compras a proveedores
	$compras = CompraProveedorDAO::getAll(1, $n , 'fecha', 'desc');

	foreach( $compras as $compra ){

		//obtener todos los productos de esa compra
		$dc = new DetalleCompraProveedor();
		$dc->setIdCompraProveedor( $compra->getIdCompraProveedor() );
		$detalles = DetalleCompraProveedorDAO::search( $dc );

		//ciclar por los detalles
		foreach($detalles as $detalle){

			$iM = InventarioMaestroDAO::getByPK( $detalle->getIdProducto(), $compra->getIdCompraProveedor() );

			//buscar la descripcion del producto
			foreach($inventario as $i){
				if($i->getIdProducto() == $detalle->getIdProducto()){
					$p = $i;
					break;
				}
			}
			
			
			foreach($sucursales as $s){
				if($s->getIdSucursal() == $iM->getSitioDescarga()){
					$sitio = $s->getDescripcion();
					break;
				}
			}			
			
			$bar = array_merge( $compra->asArray(), $iM->asArray(),  $detalle->asArray() );
			$bar['producto_desc'] = $p->getDescripcion();
			
			$bar['sitio_descarga_desc'] = $sitio;
			
			$fecha = explode( " ", $bar['fecha']);
			$bar['fecha'] = $fecha[0];
						
			array_push( $registro,  $bar );
		}

	}

	return $registro;

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


    try{
        $jsonData = parseJSON($data);
    }catch(Exception $e){
        Logger::log("Json invalido para nuevo producto" . $e);
        return array( "success" => false, "reason" => "bad json" );
    }


	if(!( isset($jsonData->precio_venta) && isset($jsonData->descripcion) && isset($jsonData->escala) && isset($jsonData->tratamiento) ) ){
		Logger::log("Faltan parametros para insertar nuevo producto");
		die('{ "success" : false, "reason" : "Datos incompletos." }');	
	}

    if( $jsonData->descripcion == "" ){
        Logger::log("Error : se intenta dar de alta un producto sin descripcion");
		die('{ "success" : false, "reason" : "Producto no cuenta con una descripcion." }');	
    }
    
    if(  strlen($jsonData->descripcion) < 3 ){
        Logger::log("Error : La descripcion del producto debe ser mayor a 2 caracteres");
		die('{ "success" : false, "reason" : "La descripcion del producto debe ser mayor a 2 caracteres." }');	
    }
    
    if(  strlen($jsonData->descripcion) > 13){
        Logger::log("Error : La descripcion del producto debe ser menor a 13 caracteres");
		die('{ "success" : false, "reason" : "La descripcion del producto debe ser menor a 13 caracteres." }');	
    }

    $inventario = new Inventario();

    $inventario->setDescripcion ($jsonData->descripcion);
    $inventario->setEscala 		($jsonData->escala == "null" ? null : $jsonData->escala);
    $inventario->setTratamiento ($jsonData->tratamiento);

    DAO::transBegin();

    try{
        InventarioDAO::save( $inventario );
    }catch(Exception $e){
	    DAO::transRollback();
        return array( "success" => false, "reason" => $e );
        
    }


    //insertar actualizacion de precio
    $actualizacion = new ActualizacionDePrecio();

    $actualizacion->setIdProducto 			( $inventario->getIdProducto() );
    $actualizacion->setIdUsuario 			( $_SESSION['userid'] );
    $actualizacion->setPrecioIntersucursal 	( $jsonData->precio_intersucursal );
    $actualizacion->setPrecioVenta 			( $jsonData->precio_venta );


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



function procesarProducto( $json = null ){

	Logger::log("Iniciando el proceso de procesado de producto");

	if($json == null){
        Logger::log("No hay parametros para procesar el producto.");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}
	
	$data = parseJSON( $json );
	
	if( !( isset( $data -> id_compra_proveedor ) && isset( $data -> id_producto )  && isset( $data -> resultante )  && isset( $data -> desecho )  && isset( $data -> subproducto )    ) ){
		Logger::log("Json invalido para iniciar el procesado de producto");
		die('{"success": false , "reason": "Parametros invalidos." }');
	}
	
	if(  $data -> id_compra_proveedor == null || $data -> id_producto  == null || $data -> resultante  == null || $data -> desecho  == null || $data -> subproducto  == null ){
		Logger::log("Json invalido para crear un nuevo proceso de producto");
		die('{"success": false , "reason": "Parametros invalidos." }');
	}
	
	//http://127.0.0.1/pos/trunk/www/proxy.php?action=408&data={%22id_compra_proveedor%22:2,%22id_producto%22:2,%22resultante%22:200,%22desecho%22:10.5,%22subproducto%22:[{%22id_producto%22:2,%22id_compra_proveedor%22:0,%22cantidad_procesada%22:50}]}
	
	/*{
	        "id_compra_proveedor":1,
	        "id_producto":5,
	        "resultante":10.12,
	        "desecho":23.14,
	        "subproducto":[
	            {"id_producto":1,"id_compra_proveedor":2,"cantidad_procesada":10.45}
	        ]
	    }
	    
	    1.- Restar en inventario maestro a sus existencias : el deshecho + la suma de las cantidades procesadas de los subproductos
	    2.- Sumar en IM a existencias_procesadas : 
	            "id_compra_proveedor":1,
	            "id_producto":5,
	            "resultante":10.12,
	    3.- Sumar a existencia y existencias procesadas en el inventario del subproducto
	    
	*/
    
    $inventario_maestro =  InventarioMaestroDAO::getByPK( $data -> id_producto, $data -> id_compra_proveedor );
    
    //  1.- Restar en inventario maestro a sus existencias : el deshecho + la suma de las cantidades procesadas de los subproductos
    
    $suma = 0;
    
    foreach( $data -> subproducto as $subproducto){
        $suma += $subproducto -> cantidad_procesada;
    }
    
    $inventario_maestro -> setExistencias( $inventario_maestro -> getExistencias() + $suma  );
    
    /*
         2.- Sumar en IM a existencias_procesadas : 
	            "id_compra_proveedor":1,
	            "id_producto":5,
	            "resultante":10.12,
    */    
    
    $inventario_maestro -> setExistenciasProcesadas( $inventario_maestro -> getExistenciasProcesadas( ) + $data -> resultante );
    
    try{
		InventarioMaestroDAO::save( $inventario_maestro );
	}catch(Exception $e){
		Logger::log("Error al editar producto en inventario maestro:" . $e);
		DAO::transRollback();	
		die( '{"success": false, "reason": "Error al editar producto en inventario maestro"}' );
	}	
    
    //3.- Sumar a existencia y existencias procesadas en el inventario del subproducto
    
    foreach( $data -> subproducto as $subproducto){
       
        $inventario_maestro =  InventarioMaestroDAO::getByPK( $subproducto -> id_producto, $subproducto -> id_compra_proveedor );
       
        $inventario_maestro -> setExistenciasProcesadas( $inventario_maestro -> getExistenciasProcesadas( ) + $subproducto  -> cantidad_procesada );
        
        $inventario_maestro -> setExistencias( $inventario_maestro -> getExistenciasProcesadas( ) + $subproducto  -> cantidad_procesada );
       
       try{
		    InventarioMaestroDAO::save( $inventario_maestro );
	    }catch(Exception $e){
		    Logger::log("Error  la guardar producto procesado" . $e);
		    DAO::transRollback();	
		    die( '{"success": false, "reason": "Error  la guardar producto procesado"}' );
	    }	
       
    }
    
    DAO::transEnd();
    
    Logger::log("termiando proceso de lavar producto con exito!");
	
	printf('{"success":true}');
	
	return;

}



function terminarCargamentoCompra( $json = null ){

	Logger::log("Iniciando proceso de terminar cargamento compra ");

	if($json == null){
        Logger::log("No hay parametros para procesar el prodcuto.");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}
	
	$data = parseJSON( $json );
	
	if( !( isset( $data -> id_compra ) && isset( $data -> id_producto ) ) ){
		Logger::log("Json invalido para crear un nuevo proceso de producto");
		die('{"success": false , "reason": "Parametros invalidos." }');
	}
	
	if( $data -> id_compra == null ||  $data -> id_producto == null ){
		Logger::log("Json invalido para crear un nuevo proceso de producto");
		die('{"success": false , "reason": "Parametros invalidos." }');
	}
	
	$inventario_maestro =  InventarioMaestroDAO::getByPK( $data -> id_producto, $data -> id_compra );			
		
	DAO::transBegin();		
		
	$inventario_maestro -> setExistencias( 0 );
	$inventario_maestro -> setExistenciasProcesadas( 0 );
		
	try{
		InventarioMaestroDAO::save( $inventario_maestro );
	}catch(Exception $e){
		Logger::log("Error al editar producto en inventario maestro:" . $e);
		DAO::transRollback();	
		die( '{"success": false, "reason": "Error al editar producto en inventario maestro"}' );
	}	
	
	DAO::transEnd();
	
	Logger::log("termiando proceso de terminar cargamento compra con exito!");
	
	printf('{"success":true}');
	
	return;

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
		
		case 406://procesar producto
		
			//porocesar producto (lavar)
		    
		    if( !( isset( $args['data'] ) && $args['data'] != null ) )
			{
				Logger::log("No hay parametros para procesar el producto.");
				die('{"success": false , "reason": "Parametros invalidos." }');
			}
		
			//{"id_compra_proveedor":1,"id_producto":5,"resultante":10.12,"desecho":23.14,"subproducto":[{"id_producto":1,"id_compra_proveedor":2,"cantidad_procesada":10.45}]}
		
			insertarSubproducto( $args['data'] );
			
		break;
		
		case 407://termianr cargamento de compra
		
			if( !( isset( $args['data'] ) && $args['data'] != null ) )
			{
				Logger::log("No hay parametros para procesar el producto.");
				die('{"success": false , "reason": "Parametros invalidos." }');
			}
		
			//{"id_compra":1,"id_producto":5,"cantidad_procesada":10}
		
			terminarCargamentoCompra( $args['data'] );
			
		break;

	    default:
	        printf( '{ "success" : "false" }' );
	    break;

	}
}

//$objecto = new stdClass();


