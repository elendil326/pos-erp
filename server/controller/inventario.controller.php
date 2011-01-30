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
 * Regresa un obeto ActualizacionDePrecio con la informacion de la ultima actualizacion de precio
 * @param $id_producto es el id del producto al cual nos referimos
 * @return ActualizacionDePrecio objeto de tipo ActualizacionDePrecio que contiene la informacion mas actualizada de los precios del producto
 * 
 * */
function obtenerActualizacionDePrecio( $id_producto ){

    //verificamos que el producto este registrado en el inventario
    if( ! InventarioDAO::getByPK( $id_producto ) ){
        Logger::log("el producto : {$id_producto} no se tiene registrado en el inventario");
        die( '{"success": false, "reason": "el producto : ' . $id_producto . ' no se tiene registrado en el inventario" }' );
    }

    $act_precio = new ActualizacionDePrecio();
    $act_precio -> setIdProducto(  $id_producto );
    $result = ActualizacionDePrecioDAO::search( $act_precio, 'fecha', 'desc' );
    
    return $result[0] ;
    
}

/*
 * listar las existencias para la sucursal dada sucursal
 * */
function listarInventario( $sucID = null ){
    
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

        $actualizacion_de_precio =  obtenerActualizacionDePrecio( $producto->getIdProducto() );

        Array_push( $json , array(
            "productoID" => $productoData->getIdProducto(),
            "descripcion" => $productoData->getDescripcion(),
            "tratamiento" => $productoData->getTratamiento(),
            "precioVenta" => $producto->getPrecioVenta(),
            "precioVentaSinProcesar" => $actualizacion_de_precio ->  getPrecioVentaSinProcesar(),
            "existenciasOriginales" => $producto->getExistencias(),
            "existenciasProcesadas" => $producto->getExistenciasProcesadas(),
            "medida" => $productoData->getEscala(),
            "precioIntersucursal" => $actualizacion_de_precio -> getPrecioIntersucursal(),
            "precioIntersucursalSinProcesar" => $actualizacion_de_precio -> getPrecioIntersucursalSinProcesar()
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
define("POS_TODOS", 	0);
define("POS_SOLO_VACIOS", 	1);
define("POS_SOLO_ACTIVOS", 	2);
function listarInventarioMaestro( $n = 50, $show = POS_TODOS )
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

			

			if($iM->getExistencias() == 0 ){
				if($show == POS_SOLO_ACTIVOS)continue;
			}else{
				if($show == POS_SOLO_VACIOS)continue;			
			}

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
    
    $info_venta=new stdClass();
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
		die('{ "success": false, "reason" : "Error : no hay suficientes datos para procesar el producto." }');
	}
	
	$data = parseJSON( $json );
	
	if( !( isset( $data -> id_compra_proveedor ) && isset( $data -> id_producto )  && isset( $data -> resultante )  && isset( $data -> desecho )  && isset( $data -> subproducto )    ) ){
		Logger::log("Json invalido para iniciar el procesado de producto");
		die('{"success": false , "reason": "Error : verifique que esten llenos todos los campos necesarios." }');
	}
	
	if(  $data -> id_compra_proveedor == null || $data -> id_producto  == null || $data -> resultante  == null ){
		Logger::log("Json invalido para crear un nuevo proceso de producto");
		die('{"success": false , "reason": "Error : verifique que esten llenos todos los campos necesarios." }');
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
    
    //verificar que exista el id_compra_proveedor
    if( !( CompraProveedorDAO::getByPK( $data -> id_compra_proveedor ) ) ){
        Logger::log( "No se tiene registro de la compra a proveedor : " . $data -> id_compra_proveedor );
		die( '{"success": false, "reason": "No se tiene registro de la compra a proveedor : ' . $data -> id_compra_proveedor . '"}' );
    }    
    
    //verificar que existan el producto en esa compora a proveedor
    if( !( $detalle_compra_proveedor = DetalleCompraProveedorDAO::getByPK( $data -> id_compra_proveedor, $data -> id_producto ) ) ){
        Logger::log( "No se tiene registro de la compra del producto : " .  $data -> id_producto . " en la compra a proveedor : " . $data -> id_compra_proveedor );
		die( '{"success": false, "reason": "No se tiene registro de la compra del producto : ' . $data -> id_producto  . ' en la compra a proveedor : ' . $data -> id_compra_proveedor . '"}' );
    }   
    
    //verificar que el resultante + desecho + ( resultante * subproducto ) <= existencias 
    
    $suma = 0;
    
     $inventario_maestro =  InventarioMaestroDAO::getByPK( $data -> id_producto, $data -> id_compra_proveedor );
    
    foreach( $data -> subproducto as $subproducto){
        $suma += $subproducto -> cantidad_procesada;
    }
    
    $consecuente = $data -> resultante + $data -> desecho + $suma;
    
    if( $consecuente > $inventario_maestro -> getExistencias() ){
        Logger::log( "Error : verifique la cantidad de otros productos" );
		die( '{"success": false, "reason": "Error : verifique la cantidad de otros productos"}' );
    }
    
   
    
    //  1.- Restar en inventario maestro a sus existencias : el deshecho + la suma de las cantidades procesadas de los subproductos
    
    
    
    $suma +=  $data -> desecho + $data -> resultante;
    
    $inventario_maestro -> setExistencias( $inventario_maestro -> getExistencias() - $suma );
    
    /*
         2.- Sumar en IM a existencias_procesadas : 
	            "id_compra_proveedor":1,
	            "id_producto":5,
	            "resultante":10.12,
    */    
    
    $inventario_maestro -> setExistenciasProcesadas( $inventario_maestro -> getExistenciasProcesadas( ) + $data -> resultante );
      DAO::transBegin();
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
        
        $inventario_maestro -> setExistencias( $inventario_maestro -> getExistencias( ) + $subproducto  -> cantidad_procesada );
       
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
	
	if( !( isset( $data -> id_compra_proveedor ) && isset( $data -> id_producto ) ) ){
		Logger::log("Json invalido para crear un nuevo proceso de producto");
		die('{"success": false , "reason": "Parametros invalidos." }');
	}
	
	if( $data -> id_compra_proveedor == null ||  $data -> id_producto == null ){
		Logger::log("Json invalido para crear un nuevo proceso de producto");
		die('{"success": false , "reason": "Parametros invalidos." }');
	}
	
	$inventario_maestro =  InventarioMaestroDAO::getByPK( $data -> id_producto, $data -> id_compra_proveedor );			
		
	DAO::transBegin();		
	
	$existencias = $inventario_maestro -> getExistencias( );
	$existencias_procesadas = $inventario_maestro -> getExistenciasProcesadas( );
		
	$inventario_maestro -> setExistencias( 0 );
	$inventario_maestro -> setExistenciasProcesadas( 0 );
		
	try{
		InventarioMaestroDAO::save( $inventario_maestro );
	}catch(Exception $e){
		Logger::log("Error al editar producto en inventario maestro:" . $e);
		DAO::transRollback();	
		die( '{"success": false, "reason": "Error al editar producto en inventario maestro"}' );
	}	

	
	if( $data -> restante != null ){
	
	    $inventario_maestro =  InventarioMaestroDAO::getByPK( $data -> restante  -> id_producto,$data -> restante  -> id_compra_proveedor );	
	    $inventario_maestro -> setExistencias( $inventario_maestro -> getExistencias( ) + $existencias  );
	    $inventario_maestro -> setExistenciasProcesadas( $inventario_maestro -> getExistenciasProcesadas() + $existencias_procesadas );
	    
	    try{
		    InventarioMaestroDAO::save( $inventario_maestro );
	    }catch(Exception $e){
		    Logger::log("Error al transferir producto en inventario maestro : " . $e);
		    DAO::transRollback();	
		    die( '{"success": false, "reason": "Error al transferir el producto en la otra compra."}' );
	    }
	    
	}
	
	
	
	DAO::transEnd();
	
	Logger::log("termiando proceso de terminar cargamento compra con exito!");
	
	printf('{"success":true}');
	
	return;

}

	/**Procesar producto sucursal.
	  *
	  * Este metodo procesa un producto de DetalleInventario. Recibe como argumento un JSON con un producto primario
	  * y uno o varios productos secundarios (subproductos). El producto primario tiene "id_producto", "procesado" y "desecho"
	  * y los subproductos contienen "id_producto" y "procesado". Se comprueba que existan los productos.
	  * Se valida que (existencias-existenciasProcesado) sean mayor que la suma de "procesado"+"desecho" del producto primario
	  * mas la sumatoria del valor "procesado" de los productos secundarios. A las existencias del producto primario se
	  * resta "desecho" y a las existenciasProcesadas se les suma "procesado". A los subproductos se suma "procesado" a 
	  * las existencias y a existenciasProcesadas
	  * Ejemplo de JSON.
	  * <code>
	  * {
 	  *	   "id_producto": 1,
	  *	    "procesado": 25,
	  *	    "desecho": 5,
	  *	    "subproducto": [{
      *      					"id_producto": 2,
	  *		            		"procesado":10
	  *		        		},
	  *		        		{
      *      					"id_producto": 3,
	  *		            		"procesado": 11
	  *		        		}]
	  * }
	  * 
	  * </code>
	  * @param string es un JSON 
	  * @return void
	  **/
	function procesarProductoSucursal($json){
		
		DetalleInventarioDAO::transBegin();
		
		$datos=parseJSON($json);
		$subproducto=$datos->subproducto;
		$di=DetalleInventarioDAO::getByPK($datos->id_producto,$_SESSION["sucursal"]);
		if($di==NULL){
			DetalleInventarioDAO::transRollback();
			die('{"success":false,"reason":"No existe el producto"}');
		}
		$suma=$datos->procesado+$datos->desecho;
		for($i=0;$i<sizeof($subproducto);$i++){
			$suma+=$subproducto[$i]->procesado;
		}
		
		if($suma > ( $di->getExistencias() - $di->getExistenciasProcesadas() ) ){
			
			Logger::log( "Imposible procesar producto, " . $suma . " <= " . $di->getExistencias() . " - " . $di->getExistenciasProcesadas() );
			
			DetalleInventarioDAO::transRollback();
			die('{"success":false,"reason":"No se pudo procesar el producto, hay menos existencias de las que se pretenden procesar."}');
		}
		
		$di->setExistencias($di->getExistencias()-$datos->desecho);
		$di->setExistenciasProcesadas($di->getExistenciasProcesadas()+$datos->procesado);
		
		try{
			DetalleInventarioDAO::save($di);

		}catch(Exception $e){
			Logger::log($e);
			DetalleInventarioDAO::transRollback();
			die('{"success":false,"reason":"No se pudo procesar el producto, intente de nuevo."}');
		}
		

		//echo $subproducto[0]->id_producto;
		for($i=0;$i<sizeof($subproducto);$i++){

				$dis[$i]=DetalleInventarioDAO::getByPK($subproducto[$i]->id_producto,$_SESSION["sucursal"]);
				if($dis[$i]==NULL){
					DetalleInventarioDAO::transRollback();
					die('{"success":false,"reason":"No existe el subproducto"}');
				}
			
				$dis[$i]->setExistencias($dis[$i]->getExistencias()+$subproducto[$i]->procesado);
				$dis[$i]->setExistenciasProcesadas($dis[$i]->getExistenciasProcesadas()+$subproducto[$i]->procesado);

				try{

					DetalleInventarioDAO::save($dis[$i]);

				}catch(Exception $e){
					Logger::log($e);
					DetalleInventarioDAO::transRollback();
					die('{"success":false,"reason":"No se pudo procesar el producto, intente de nuevo."}');
				}
			
		}
				DetalleInventarioDAO::transEnd();
				echo '{"success":true}';
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
		
			procesarProducto( $args['data'] );
			
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
		
		case 408://procesar producto sucursal
			procesarProductoSucursal($args["data"]);
		break;

	   case 499:
        echo obtenerPrecioIntersucursal( $args['id_producto'] );
	   break;
	}
}

//$objecto = new stdClass();


