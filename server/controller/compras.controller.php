 <?php 

require_once('model/inventario.dao.php');
require_once('model/compra_proveedor.dao.php');
require_once('model/detalle_compra_proveedor.dao.php');
require_once('model/compra_proveedor_flete.dao.php');
require_once('model/inventario_maestro.dao.php');

require_once('logger.php');


function nuevaCompraProveedor( $json = null ){

	if($json == null){
        Logger::log("No hay parametros para ingresar nueva compra a proveedor.");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}

	$data = parseJSON( $json );

	if($data == null){
		Logger::log("Json invalido para crear nueva compra proveedor:" . $json);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}
	
	/*
	
	{
    "embarque" : {
        "folio": "456",
        "merma_por_arpilla": 0,
        "numero_de_viaje": null,
        "peso_por_arpilla": 55.45,
        "peso_origen" : 12345,
        "peso_recibido" : 12345,
        "productor" : "Jorge Nolasco",
        "importe_total": 3702,
        "total_arpillas": 1,
        "costo_flete" : 123 
    },
    "conductor" : {
        "nombre_chofer" : "Alan Gonzalez",
        "placas" : "afsdf67t78",
        "marca_camion" : "Chrysler",
        "modelo_camion" : "1977" 
    },
    "productos": [
        {
            "id_producto": 3,
            "variedad" : "fianas",
            "arpillas" : 12,
            "precio_kg" : 5.35,
            "sitio_descarga" : 0 
        } 
    ]
}
	
	*/
	
	if( !( isset( $data -> embarque ) && isset( $data -> conductor ) && isset( $data -> productos ) ) ){
		Logger::log("Json invalido para crear nueva compra proveedor:" . $json);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}

	Logger::log("Iniciando le proceso de registro de nueva compra a proveedor");
	
	
	//creamos la compra al proveedor
	$id_compra_proveedor = compraProveedor( $data->embarque, $data->productos );
	
	//damos de alta el flete
	compraProveedorFlete($data->conductor, $id_compra_proveedor, $data->embarque->costo_flete);
	
	//damos de alta el detalle de la compra al proveedor
	ingresarDetallecompraProveedor( $data->productos, $id_compra_proveedor, $data->embarque->peso_por_arpilla);
	
	//isertamos en el inventario maestro
	insertarProductoInventarioMaestro($data->productos, $id_compra_proveedor, $data->embarque->peso_por_arpilla);
	
	
}

function compraProveedor( $data = null, $productos = null ){

	/*if($json == null){
        Logger::log("No hay parametros para ingresar nueva compra a proveedor.");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}

	$data = parseJSON( $json );*/
	
	/*
	
	
	NOTA: FATA PESO ORIGEN EN LA BD
	
	*/
	
	if($data == null){
		Logger::log("Json invalido para crear nueva compra proveedor:" . $json);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}
	
	/*
	
	"embarque" : {
        "folio": "456",
        "merma_por_arpilla": 0,
        "numero_de_viaje": null,
        "peso_por_arpilla": 55.45,
        "peso_origen" : 12345,
        "peso_recibido" : 12345,
        "productor" : "Jorge Nolasco",
        "importe_total": 3702,
        "total_arpillas": 1,
        "costo_flete" : 123 
    }
	
	*/
	
	if(!( /*isset($data->id_proveedor) &&*/
			isset($data->peso_recibido) &&
			isset($data->total_arpillas) &&
			isset($data->peso_por_arpilla) &&
			isset($data->productor) &&
			isset($data->merma_por_arpilla) &&
			isset($data->peso_origen)
		)){
		Logger::log("Faltan parametros para crear la compra a proveedor");
		die('{ "success": false, "reason" : "Faltan parametros. (compra_proveedor)" }');

	}
	
	//calculamos cuanto vale el viaje segun el proveedor
	$peso_promedio_origen = ( $data->peso_origen / $data->total_arpillas );
	$precio_total_origen = 0;
	
	foreach( $productos as $producto ){
		$precio_total_origen += $producto->precio_kg * $peso_promedio_origen;
	}

	//creamos el objeto compra
    $compra = new CompraProveedor();
	//$compra -> setIdProveedor( $data->id_proveedor );
	$compra -> setIdProveedor( 1 );
	
	if(isset($data->folio))
	$compra -> setFolio( $data->folio );
	
	if(isset($data->numero_de_viaje))
		$compra -> setNumeroDeViaje( $data->numero_de_viaje );
	
	$compra -> setPesoRecibido( $data->peso_recibido );
	$compra -> setArpillas( $data->total_arpillas );
	$compra -> setPesoPorArpilla( $data->peso_por_arpilla );
	$compra -> setProductor( $data->productor );
    $compra -> setMermaPorArpilla( $data->merma_por_arpilla );	
	$compra -> setTotalOrigen( $precio_total_origen );
	
	DAO::transBegin();	
	
	try{
		CompraProveedorDAO::save( $compra );
	}catch(Exception $e){
        Logger::log("Error al guardar la nueva compra a proveedor:" . $e);
		DAO::transRollback();	
	    die( '{"success": false, "reason": "Error al guardar la compra" }' );
	}

	Logger::log("Compra a proveedor creada !");
	
	return $compra -> getIdCompraProveedor();
	
    //printf('{"success": true, "id": "%s"}' , $compra->getIdCompraProveedor());
	

}

function compraProveedorFlete( $data = null, $id_compra_proveedor = null, $costo_flete = null ){

	/*if($json == null){
        Logger::log("No hay parametros para ingresar nuevo flete a compra a proveedor.");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}

	$data = parseJSON( $json );*/

	if($data == null){
		Logger::log("Json invalido para crear un nuevo flete a compra proveedor:" . $json);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}
	
	
	/*
	
	"conductor" : {
        "nombre_chofer" : "Alan Gonzalez",
        "placas" : "afsdf67t78",
        "marca_camion" : "Chrysler",
        "modelo_camion" : "1977" 
    }
	
	*/
	
	if(!( $id_compra_proveedor != null &&
			$costo_flete != null &&
			isset($data->nombre_chofer) &&
			isset($data->marca_camion) &&
			isset($data->placas) &&
			isset($data->marca_camion)
		)){
		Logger::log("Faltan parametros para crear el nuevo flete a compra a proveedor:" . $json);
		die('{ "success": false, "reason" : "Faltan parametros." }');

	}

    $compra = new CompraProveedorFlete();
	$compra -> setIdCompraProveedor( $id_compra_proveedor );
	$compra -> setChofer( $data->nombre_chofer );
	$compra -> setMarcaCamion( $data->marca_camion );
	$compra -> setPlacasCamion( $data->placas );
	$compra -> setModeloCamion( $data->modelo_camion );
	$compra -> setCostoFlete( $costo_flete );	
	
	try{
		CompraProveedorFleteDAO::save( $compra );
	}catch(Exception $e){
        Logger::log("Error al guardar el nuevo flete a compra a proveedor:" . $e);
		DAO::transRollback();
	    die( '{"success": false, "reason": "Error al guardar el flete" }' );
	}

	Logger::log("Flete a compra a proveedor creado !");
	
    //printf('{"success": true}');
	return;

}


function editarCompraProveedor( $json ){

	if($json == null){
        Logger::log("No hay parametros para editar la compra a proveedor.");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}

	$data = parseJSON( $json );

	if($data == null){
		Logger::log("Json invalido para crear editar la compra a proveedor:" . $json);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}	
	
	if( !isset( $data->id_compra_proveedor ) ){
		Logger::log("No se ha especificado que compra a proveedor se desea editar");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}

    $compra = new CompraProveedor( );
	
	$compra -> setIdCompraProveedor( $data->id_compra_proveedor );
	
	if( isset( $data->id_proveedor ) )
		$compra -> setIdProveedor( $data->id_proveedor );
		
	if( isset( $data->folio ) )
		$compra -> setFolio( $data->folio );
		
	if( isset( $data->numero_de_viaje ) )
		$compra -> setNumeroDeViaje( $data->numero_de_viaje );
		
	if( isset( $data->peso_recibido ) )
		$compra -> setPesoRecibido( $data->peso_recibido );
		
	if( isset( $data->arpillas ) )
		$compra -> setArpillas( $data->arpillas );
		
	if( isset( $data->peso_por_arpilla ) )
		$compra -> setPesoPorArpilla( $data->peso_por_arpilla );
				
		
	if( isset( $data->merma_por_arpilla ) )
		$compra -> setMermaPorArpilla( $data->merma_por_arpilla );
	
	if( isset( $data->productor ) )
		$compra -> setProductor( $data->productor );
	
	/*if( isset( $data->total_origen ) )
		$compra -> setTotalOrigen( $data->total_origen );*/
		
	try{
		CompraProveedorDAO::save( $compra );
	}catch(Exception $e){
        Logger::log("Error al guardar la edicion de la compra a proveedor:" . $e);
	    die( '{"success": false, "reason": "Error" }' );
	}

    printf('{"success": true, "id": "%s"}' , $compra->getIdCompraProveedor());
	Logger::log("Compra a proveedor modificada !");

}

function detalleCompraProveedor( $id_compra ){

    if( !isset( $id_compra ) )
    {
		Logger::log("Error : no se especifico el id_compra");
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }
    elseif( empty( $id_compra ) )
    {
		Logger::log("Error : el id_compra esta vacio");
        die('{"success": false, "reason": "Verifique los datos." }');
    }

    //verificamos que exista esa compra
    if( !( $compra = CompraProveedorDAO::getByPK( $id_compra ) ) )
    {
		Logger::log("Error : no se tiene registro de la compra a proveedor " . $id_compra);
        die('{"success": false, "reason": "No se tiene registro de esa compra a proveedor." }');
    }

    $q = new DetalleCompraProveedor();
    $q->setIdCompraProveedor( $id_compra );
    
    $detalle_compra = DetalleCompraProveedorDAO::search( $q );
    
    $array_detalle_compra = array();
    
    foreach( $detalle_compra as $producto )
    {
    
        $productoData = InventarioMaestroDAO::getByPK( $producto -> getIdProducto(), $producto -> getIdCompraProveedor() );

        array_push( $array_detalle_compra , array(
            "id_producto" => $producto -> getIdProducto(),
            "variedad" => $producto -> getVariedad(),
			"arpillas" => $producto -> getArpillas(),
			"kg" => $producto -> getKg(),
			"precio_por_kg" => $producto -> getPrecioPorKg()
        ));
    }

    $info_compra -> id_compra_proveedor = $compra -> getIdCompraProveedor();
    //$info_compra -> total_origen = $compra -> getTotalOrigen(); //<--POR NO ESTA EN LA DOVUMENTACION?
    $info_compra -> num_compras = count( $array_detalle_compra );
    $info_compra -> articulos = $array_detalle_compra;

    return $info_compra; 

}

function listarComprasProveedor( ){
    
	return CompraProveedorDAO::getAll();
	
}



function editarCompraProveedorFlete( $json = null ){

	if($json == null){
        Logger::log("No hay parametros para modificar flete a compra a proveedor.");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}

	$data = parseJSON( $json );

	if($data == null){
		Logger::log("Json invalido para crear un nuevo flete a compra proveedor:" . $json);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}
	
	
	if(!( isset( $data->id_compra_proveedor) )){
		Logger::log("Faltan parametros para crear el nuevo flete a compra a proveedor:" . $json);
		die('{ "success": false, "reason" : "Faltan parametros." }');

	}

    $compra = new CompraProveedorFlete();

	$compra->setIdCompraProveedor( $data->id_compra_proveedor );
	
	if( isset( $data->id_compra_proveedor ) )
		$compra -> setIdCompraProveedor( $data->id_compra_proveedor );
		
	if( isset( $data->chofer ) )
		$compra -> setChofer( $data->chofer );
		
	if( isset( $data->marca_camion ) )
		$compra -> setMarcaCamion( $data->marca_camion );
		
	if( isset( $data->placas_camion ) )
		$compra -> setPlacasCamion( $data->placas_camion );
		
	if( isset( $data->modelo_camion ) )
		$compra -> setModeloCamion( $data->modelo_camion );
		
	if( isset( $data->costo_flete ) )
		$compra -> setCostoFlete( $data->costo_flete );	
	
	try{
		CompraProveedorFleteDAO::save( $compra );
	}catch(Exception $e){
        Logger::log("Error al guardar el nuevo flete a compra a proveedor:" . $e);
	    die( '{"success": false, "reason": "Error al guardar el flete" }' );
	}

	Logger::log("Flete a compra a proveedor creado !");
	
    printf('{"success": true}');

}

function ingresarDetallecompraProveedor( $data = null, $id_compra_proveedor =null, $peso_por_arpilla = null ){


	if($data == null){
		Logger::log("Array de productos invalido:" . $data);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}
	
	Logger::log("Iniciando proceso de creacion detalle compra proveedor");
	
	foreach( $data as $producto ){
		
		if( !( isset( $producto -> id_producto ) && 
			isset( $producto -> variedad ) &&
			isset( $producto -> arpillas ) && 
			isset( $producto -> precio_kg ) && 			
			$id_compra_proveedor != null && $peso_por_arpilla != null
			) ){
			
			Logger::log("error al agregar producto a detalle compra proveedor");
			DAO::transRollback();		
		}
		
		$detalle_compra_proveedor = new DetalleCompraProveedor();
	
		$detalle_compra_proveedor -> setIdCompraProveedor( $id_compra_proveedor );
		$detalle_compra_proveedor -> setIdProducto( $producto -> id_producto );
		$detalle_compra_proveedor -> setVariedad( $producto -> variedad );
		$detalle_compra_proveedor -> setArpillas( $producto -> arpillas );
		$detalle_compra_proveedor -> setKg( ( $peso_por_arpilla * $producto -> arpillas ) );
		$detalle_compra_proveedor -> setPrecioPorKg( $producto -> precio_kg );
		
		try{
			DetalleCompraProveedorDAO::save( $detalle_compra_proveedor );
		}catch(Exception $e){
			Logger::log("Error al guardar producto en detalle compra proveedor:" . $e);
			DAO::transRollback();	
			die( '{"success": false, "reason": "Error al guardar detalle compra proveedor"}' );
		}
	
	}

	Logger::log("Proceso de alta a detalle proveedor finalizado con exito!");

}

function insertarProductoInventarioMaestro($data = null, $id_compra_proveedor = null, $peso_por_arpilla = null, $sitio_descarga = null){

	
	if($data == null){
		Logger::log("Array de productos invalido:" . $data);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}
	
	Logger::log("Iniciando proceso de insercion de producto a inventario maestro");
		
	foreach( $data as $producto ){

		$existencias = $producto -> arpillas * $peso_por_arpilla;
	
		$inventatio_maestro = new InventarioMaestro();
		
		$inventatio_maestro -> setIdProducto( $producto->id_producto );
		$inventatio_maestro -> setIdCompraProveedor( $id_compra_proveedor );
		$inventatio_maestro -> setExistencias( $existencias );
		$inventatio_maestro -> setExistenciasProcesadas( 0 );
		$inventario_maestro -> setSitioDescarga( $producto->sitio_descarga );
		
		try{
			InventarioMaestroDAO::save( $inventatio_maestro );
		}catch(Exception $e){
			Logger::log("Error al guardar producto en inventario maestro:" . $e);
			DAO::transRollback();	
			die( '{"success": false, "reason": "Error al guardar producto en inventario maestro"}' );
		}
	
	}
	
	DAO::transEnd();
	
	Logger::log("Proceso de alta a inventario maestro finalizado con exito!");
	
	printf('{"success": true}');
	
}


if(isset($args['action'])){
	switch($args['action']){

		case 1000://recibe el json para crear una compra  aproveedor		
			nuevaCompraProveedor( $args['data'] );
		break;
	
		case 1001://nueva compra a proveedor (admin)		
			//http://127.0.0.1/pos/www/proxy.php?action=1000&data={%22id_proveedor%22:%221%22,%22folio%22:%22234%22,%22numero_de_viaje%22:%2212%22,%22peso_recibido%22:%2212200%22,%22arpillas%22:%22340%22,%22peso_por_arpilla%22:%2265%22,%22productor%22:%22El fenix%22,%22merma_por_arpilla%22:%225%22,%22total_origen%22:%2217900%22}
			//printf('{ "success": true, "datos": %s }',  json_encode( compraProveedor( $args['data'] ) ) );
			//compraProveedor( $args['data'] );
		break;
		
		case 1002://modificar compra a proveedor (admin)
			//http://127.0.0.1/pos/www/proxy.php?action=1001&data={"id_compra_proveedor":"2","id_proveedor":"1","folio":"234","numero_de_viaje":"12","peso_recibido":"12200","arpillas":"340","peso_por_arpilla":"65","productor":"El%20fenix de Celaya","merma_por_arpilla":"5","total_origen":"17900"}
			//printf('{ "success": true, "datos": %s }',  json_encode( editarCompraProveedor( $args['data'] ) ) );
			editarCompraProveedor( $args['data'] );
		break;
	
        case 1003://regresa las compras realizadas por el admin
            printf('{ "success": true, "datos": %s }',  json_encode( listarComprasProveedor(  ) ) );
        break;

        case 1004://regresa el detalle de la compra
            printf('{ "success": true, "datos": %s }',  json_encode( detalleCompraProveedor( $args['id_compra_proveedor'] ) ) );
        break;
		
		case 1006://modificar flete
			editarCompraProveedorFlete( $args['data'] );
		break;
		
	    default:
	        printf( '{ "success" : "false" }' );
	    break;

	}
}

 