 <?php 

require_once('model/inventario.dao.php');
require_once('model/compra_proveedor.dao.php');
require_once('model/detalle_compra_proveedor.dao.php');
require_once('model/detalle_compra_proveedor_flete.dao.php');
require_once('model/inventario_maestro.dao.php');

require_once('logger.php');




function compraProveedor( $json = null ){

	if($json == null){
        Logger::log("No hay parametros para ingresar nueva compra a proveedor.");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}

	$data = parseJSON( $json );

	if($data == null){
		Logger::log("Json invalido para crear nueva compra proveedor:" . $json);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}
	
	
	if(!( isset($data->id_proveedor) &&
			isset($data->folio) &&
			isset($data->numero_de_viaje) &&
			isset($data->peso_recibido) &&
			isset($data->arpillas) &&
			isset($data->peso_por_arpilla) &&			
			isset($data->productor) &&
			isset($data->merma_por_arpilla) &&
			isset($data->total_origen)
		)){
		Logger::log("Faltan parametros para crear la compra a proveedor:" . $json);
		die('{ "success": false, "reason" : "Faltan parametros." }');

	}

    $compra = new CompraProveedor();
	$compra -> setIdProveedor( $data->id_proveedor );
	$compra -> setFolio( $data->folio );
	$compra -> setNumeroDeViaje( $data->numero_de_viaje );
	$compra -> setPesoRecibido( $data->peso_recibido );
	$compra -> setArpillas( $data->arpillas );
	$compra -> setPesoPorArpilla( $data->peso_por_arpilla );
	$compra -> setProductor( $data->productor );
    $compra -> setMermaPorArpilla( $data->merma_por_arpilla );	
	
	try{
		CompraProveedorDAO::save( $compra );
	}catch(Exception $e){
        Logger::log("Error al guardar la nueva compra a proveedor:" . $e);
	    die( '{"success": false, "reason": "Error al guardar la compra" }' );
	}

	Logger::log("Compra a proveedor creada !");
	
    printf('{"success": true, "id": "%s"}' , $compra->getIdCompraProveedor());
	

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

function compraProveedorFlete( $json = null ){

	if($json == null){
        Logger::log("No hay parametros para ingresar nuevo flete a compra a proveedor.");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}

	$data = parseJSON( $json );

	if($data == null){
		Logger::log("Json invalido para crear un nuevo flete a compra proveedor:" . $json);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}
	
	
	if(!( isset($data->id_compra_proveedor) &&
			isset($data->chofer) &&
			isset($data->marca_camion) &&
			isset($data->placas_camion) &&
			isset($data->modelo_camion) &&
			isset($data->costo_flete)
		)){
		Logger::log("Faltan parametros para crear el nuevo flete a compra a proveedor:" . $json);
		die('{ "success": false, "reason" : "Faltan parametros." }');

	}

    $compra = new CompraProveedorFlete();
	$compra -> setIdCompraProveedor( $data->id_compra_proveedor );
	$compra -> setChofer( $data->chofer );
	$compra -> setMarcaCamion( $data->marca_camion );
	$compra -> setPlacasCamion( $data->placas_camion );
	$compra -> setModeloCamion( $data->modelo_camion );
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


if(isset($args['action'])){
	switch($args['action']){

		case 1000://nueva compra a proveedor (admin)		
			//http://127.0.0.1/pos/www/proxy.php?action=1000&data={%22id_proveedor%22:%221%22,%22folio%22:%22234%22,%22numero_de_viaje%22:%2212%22,%22peso_recibido%22:%2212200%22,%22arpillas%22:%22340%22,%22peso_por_arpilla%22:%2265%22,%22productor%22:%22El fenix%22,%22merma_por_arpilla%22:%225%22,%22total_origen%22:%2217900%22}
			//printf('{ "success": true, "datos": %s }',  json_encode( compraProveedor( $args['data'] ) ) );
			compraProveedor( $args['data'] );
		break;
		
		case 1001://modificar compra a proveedor (admin)
			//http://127.0.0.1/pos/www/proxy.php?action=1001&data={"id_compra_proveedor":"2","id_proveedor":"1","folio":"234","numero_de_viaje":"12","peso_recibido":"12200","arpillas":"340","peso_por_arpilla":"65","productor":"El%20fenix de Celaya","merma_por_arpilla":"5","total_origen":"17900"}
			//printf('{ "success": true, "datos": %s }',  json_encode( editarCompraProveedor( $args['data'] ) ) );
			editarCompraProveedor( $args['data'] );
		break;
	
        case 1002://regresa las compras realizadas por el admin
            printf('{ "success": true, "datos": %s }',  json_encode( listarComprasProveedor(  ) ) );
        break;

        case 1003://regresa el detalle de la compra
            printf('{ "success": true, "datos": %s }',  json_encode( detalleCompraProveedor( $args['id_compra_proveedor'] ) ) );
        break;

		case 1004://nuevo flete
			compraProveedorFlete( $args['data'] );
		break;
		
		case 1005://modificar flete
			editarCompraProveedorFlete( $args['data'] );
		break;
		
	    default:
	        printf( '{ "success" : "false" }' );
	    break;

	}
}

 