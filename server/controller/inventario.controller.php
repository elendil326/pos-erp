<?php

require_once('model/inventario.dao.php');
require_once('model/detalle_inventario.dao.php');
require_once('model/detalle_venta.dao.php');
require_once('model/ventas.dao.php');
require_once('model/proveedor.dao.php');
require_once('model/actualizacion_de_precio.dao.php');
require_once('model/compra_proveedor.dao.php');
require_once('model/detalle_compra_proveedor.dao.php');
require_once('model/compra_proveedor_fragmentacion.dao.php');
require_once('model/compra_proveedor_flete.dao.php');
require_once('model/inventario_maestro.dao.php');
require_once('logger.php');




/**
 * Evaluar el valor del inventario actual
 * 
 * 
 * 
 * @param id_sucursal El id de la sucursal a evaluar
 * **/
function valorDelInventarioActual($id_sucursal){ 
	//obtener el inventario de esta sucursal
	
	$inventario = listarInventario( $id_sucursal );
	
	$valor_total = 0;
	
	
	foreach($inventario as $producto_existencias ){
		
		
		// calcular el valor, con la ultima actualizacion
		// de precio multiplicado por la cantidad
		// de producto orignal que hay
		
		//primero hay que saber si el precio que 
		//voy a ver es por agrupacion o por unidad
		if( $producto_existencias["precioPorAgrupacion"] ){
			
			//el precio es por agrupacion, sacar el tamano
			//de la agrupacion y calcular el precio por unidad
			$valor_unitario = $producto_existencias["precioVenta"] / $producto_existencias["agrupacionTam"];
			
			
		}else{
			
			$valor_unitario = $producto_existencias["precioVenta"];
			
		}
		
		$valor_total +=  ( $valor_unitario * $producto_existencias["existencias"] );
		
		
		
		
		// Tambien se trata este producto, 
		// hay que contabilizar los productos
		// procesados si es que hay
		if($producto_existencias["tratamiento"]){
			
		}
		
		
		
	}
	
	
	return $valor_total;

}






/**
 * Evaluar el costo del inventario actual
 * 
 * 
 * 
 * @param id_sucursal El id de la sucursal a evaluar
 * **/
function costoDelInventarioActual($id_sucursal){ 
	
}








/*
 * Regresa un objeto ActualizacionDePrecio con la informacion de la ultima actualizacion de precio
 * @param $id_producto es el id del producto al cual nos referimos
 * @return ActualizacionDePrecio objeto de tipo ActualizacionDePrecio que contiene la informacion mas actualizada de los precios del producto
 * 
 * */

function obtenerActualizacionDePrecio($id_producto) {

//verificamos que el producto este registrado en el inventario
    if (!InventarioDAO::getByPK($id_producto)) {
        Logger::log("el producto : {$id_producto} no se tiene registrado en el inventario");
        die('{"success": false, "reason": "el producto : ' . $id_producto . ' no se tiene registrado en el inventario" }');
    }

    $act_precio = new ActualizacionDePrecio();
    $act_precio->setIdProducto($id_producto);
    $result = ActualizacionDePrecioDAO::search($act_precio, 'fecha', 'desc');

    return $result[0];
}





/*
 * listar las existencias para la sucursal dada sucursal
 * */
function listarInventario($sucID = null) {
	/*
	Logger::log("--------------------------------");
		        $d = debug_backtrace();
				$track = "ESTOY USANDO LISTARINVENTARIO ! : ";
				for ($i= 1; $i < sizeof($d) -1 ; $i++) { 
					$track .= isset($d[$i]["function"]) ? "->" . $d[$i]["function"] . "()": "*" ;
					$track .= isset($d[$i]["file"]) ? substr( strrchr( $d[$i]["file"], "/" ), 1 )  : "*"; 
					$track .= isset($d[$i]["line"]) ? ":" .  $d[$i]["line"] ." "  : "* " ;
				}
				Logger::log($track);
	Logger::log("--------------------------------");
	*/
	

    if (!$sucID) {
        return null;
    }

    $q = new DetalleInventario();

    $q->setIdSucursal($sucID);

    $results = DetalleInventarioDAO::search($q);

    $json = array();

    foreach ($results as $producto) {
        $productoData = InventarioDAO::getByPK($producto->getIdProducto());

		//buscar esta actualizacion de precio
        $actualizacion_de_precio = obtenerActualizacionDePrecio($producto->getIdProducto());

        Array_push($json, array(

			//detalles basicos que jamas cambian
            "productoID"			=> $productoData->getIdProducto(),
            "descripcion" 			=> $productoData->getDescripcion(),
            "tratamiento" 			=> $productoData->getTratamiento(),
            "medida" 				=> $productoData->getEscala(),
            "agrupacion" 			=> $productoData->getAgrupacion(),
            "agrupacionTam" 		=> $productoData->getAgrupacionTam(),
            "precioPorAgrupacion" 	=> $productoData->getPrecioPorAgrupacion() == "1",

			//precios de la tabla de detalle inventario !
            "precioVenta" 			=> $producto->getPrecioVenta(),
            "precioVentaProcesado"  => $producto->getPrecioVentaProcesado(),

			//las existencias originales, son las existencias
			//totales menos las existencias procesadas
            "existencias" => ($producto->getExistencias() - $producto->getExistenciasProcesadas()),			

			//mantendre existenciasOriginales para 
			//backwards compatibility
            "existenciasOriginales" => ($producto->getExistencias() - $producto->getExistenciasProcesadas()),
            "existenciasProcesadas" => $producto->getExistenciasProcesadas(),

			// estos precios si vienen de la tabla de
			// actualizacion de precio
            "precioIntersucursal" 			=> $actualizacion_de_precio->getPrecioIntersucursal(),
            "precioIntersucursalProcesado" 	=> $actualizacion_de_precio->getPrecioIntersucursalProcesado()

        ));
    }

    return $json;
}







function detalleProductoSucursal($args) {

    if (!isset($args['id_producto'])) {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }


    if (!($producto = DetalleInventarioDAO::getByPK($args['id_producto'], $_SESSION['sucursal']) )) {
        die('{"success": false, "reason": "No se tiene registros de ese producto." }');
    }

    printf('{ "success": true, "datos": %s }', $producto);
}

/**
 * Obtiene las ultimas n entradas del inventario maestro ordenadas por fecha.
 * Por default n es 50.
 * 
 * */
define("POS_TODOS", 0);
define("POS_SOLO_VACIOS", 1);
define("POS_SOLO_ACTIVOS", 2);

function listarInventarioMaestro($n = 50, $show = POS_TODOS) {

//meter el inventario aqui, para no estar haciendo querys
    $inventario = InventarioDAO::getAll();

//meter aqui las sucursales para no estar buscando en la base
    $sucursales = SucursalDAO::getAll();

    $registro = array();

//obtener todas las compras a proveedores
    $compras = CompraProveedorDAO::getAll(1, $n, 'fecha', 'desc');

    foreach ($compras as $compra) {

//obtener todos los productos de esa compra
        $dc = new DetalleCompraProveedor();
        $dc->setIdCompraProveedor($compra->getIdCompraProveedor());
        $detalles = DetalleCompraProveedorDAO::search($dc);

        $flete = CompraProveedorFleteDAO::getByPK($compra->getIdCompraProveedor());


//ciclar por los detalles
        foreach ($detalles as $detalle) {

            $iM = InventarioMaestroDAO::getByPK($detalle->getIdProducto(), $compra->getIdCompraProveedor());

            if ($iM->getExistencias() == 0) {
                if ($show == POS_SOLO_ACTIVOS)
                    continue;
            }else {
                if ($show == POS_SOLO_VACIOS)
                    continue;
            }

//buscar la descripcion del producto
            foreach ($inventario as $i) {
                if ($i->getIdProducto() == $detalle->getIdProducto()) {
                    $p = $i;
                    break;
                }
            }


            foreach ($sucursales as $s) {
                if ($s->getIdSucursal() == $iM->getSitioDescarga()) {
                    $sitio = $s->getDescripcion();
                    break;
                }
            }

            $bar = array_merge($compra->asArray(), $iM->asArray(), $detalle->asArray(), $flete->asArray());

            $bar['producto_desc'] = $p->getDescripcion();
            $bar['producto_tratamiento'] = $p->getTratamiento();
            $bar['medida'] = $p->getEscala();
            $bar['agrupacion'] = $p->getAgrupacion();
            $bar['agrupacionTam'] = $p->getAgrupacionTam();
            $bar['precio_por_agrupacion'] = $p->getPrecioPorAgrupacion();
//$bar['costo_con_flete'] 		= 	$flete->costoFlete();

            if ($p->getTratamiento() === null) {
                $bar["existencias_procesadas"] = "NA";
            }

            $bar['sitio_descarga_desc'] = isset($sitio) ? $sitio : null;

            $fecha = explode(" ", toDate($bar['fecha']));
            $bar['fecha'] = $fecha[0];

            array_push($registro, $bar);
        }
    }

    return $registro;
}

function comprasSucursal($args) {

//$_SESSION['sucursal']

    if (isset($args['id_sucursal']) && !empty($args['id_sucursal'])) {
        $id_sucursal = $args['id_sucursal'];
    } else {
        $id_sucursal = $_SESSION['sucursal'];
    }

    $query = new Compras();
    $query->setIdSucursal($id_sucursal);

    $compras = ComprasDAO::search($query);

    $array_compras = array();

    foreach ($compras as $compra) {

        $proveedor = ProveedorDAO::getByPk($compra->getIdProveedor());

        array_push($array_compras, array(
            "id_compra" => $compra->getIdCompra(),
            //"proveedor" => $proveedor->getNombre(),
//"tipo_compra" => $compra->getTipoCompra(),
            "fecha" => $compra->getFecha(),
            "subtotal" => $compra->getSubtotal(),
            "id_usuario" => $compra->getIdUsuario()
        ));
    }

    $info_compras->num_compras = count($array_compras);
    $info_compras->compras = $array_compras;

    return $info_compras;
}

function detalleCompra($args) {

    if (!isset($args['id_compra'])) {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    } elseif (empty($args['id_compra'])) {
        die('{"success": false, "reason": "Verifique los datos." }');
    }

//verificamos que exista esa compra
    if (!( $compra = ComprasDAO::getByPK($args['id_compra']) )) {
        die('{"success": false, "reason": "No se tiene registro de esa compra." }');
    }

    $q = new DetalleCompra();
    $q->setIdCompra($args['id_compra']);

    $detalle_compra = DetalleCompraDAO::search($q);

    $array_detalle_compra = array();

    foreach ($detalle_compra as $producto) {

        $productoData = InventarioDAO::getByPK($producto->getIdProducto());

        array_push($array_detalle_compra, array(
            "id_producto" => $producto->getIdProducto(),
            "descripcion" => $productoData->getDescripcion(),
            "cantidad" => $producto->getCantidad(),
            "precio" => $producto->getPrecio()
        ));
    }

    $info_compra->id_compra = $compra->getIdCompra();
    $info_compra->total = $compra->getSubtotal();
    $info_compra->num_compras = count($array_detalle_compra);
    $info_compra->compras = $array_detalle_compra;

    return $info_compra;
}

function detalleVentas($id) {

    if (!isset($id)) {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    } elseif (empty($id)) {
        die('{"success": false, "reason": "Verifique los datos." }');
    }

//verificamos que exista esa venta
    if (!( $venta = VentasDAO::getByPK($id) )) {
        die('{"success": false, "reason": "No se tiene registro de esa venta." }');
    }

    $q = new DetalleVenta();
    $q->setIdVenta($id);

    $detalle_venta = DetalleVentaDAO::search($q);

    $array_detalle_venta = array();

    foreach ($detalle_venta as $producto) {

        $productoData = InventarioDAO::getByPK($producto->getIdProducto());

        array_push($array_detalle_venta, array(
            "id_producto" => $producto->getIdProducto(),
            "descripcion" => $productoData->getDescripcion(),
            "cantidad" => $producto->getCantidad(),
            "precio" => $producto->getPrecio(),
            "cantidad_procesada" => $producto->getCantidadProcesada(),
            "precio_procesada" => $producto->getPrecioProcesada()
        ));
    }

    $info_venta = new stdClass();
    $info_venta->id_venta = $venta->getIdVenta();
    $info_venta->total = $venta->getTotal();
    $info_venta->num_ventas = count($array_detalle_venta);
    $info_venta->ventas = $array_detalle_venta;

    return $info_venta;
}

function nuevoProducto($data) {


    try {
        $jsonData = parseJSON($data);
    } catch (Exception $e) {
        Logger::log("Json invalido para nuevo producto" . $e);
        return array("success" => false, "reason" => "Datos invalidos");
    }


    if (!( isset($jsonData->precio_venta) && isset($jsonData->descripcion) && isset($jsonData->escala) && isset($jsonData->tratamiento) )) {
        Logger::log("Faltan parametros para insertar nuevo producto");
        die('{ "success" : false, "reason" : "Datos incompletos." }');
    }

    if ($jsonData->descripcion == "") {
        Logger::log("Error : se intenta dar de alta un producto sin descripcion");
        die('{ "success" : false, "reason" : "Producto no cuenta con una descripcion." }');
    }

    if (strlen($jsonData->descripcion) < 3) {
        Logger::log("Error : La descripcion del producto debe ser mayor a 2 caracteres");
        die('{ "success" : false, "reason" : "La descripcion del producto debe ser mayor a 2 caracteres." }');
    }

    if (strlen($jsonData->descripcion) > 15) {
        Logger::log("Error : La descripcion del producto debe ser menor a 13 caracteres");
        die('{ "success" : false, "reason" : "La descripcion del producto debe ser menor a 15 caracteres." }');
    }



    $inventario = new Inventario();
    $inventario->setDescripcion($jsonData->descripcion);
    $inventario->setEscala($jsonData->escala == "null" ? null : $jsonData->escala );
    $inventario->setTratamiento($jsonData->tratamiento == "null" ? null : $jsonData->tratamiento );
    $inventario->setAgrupacion($jsonData->agrupacion == "null" ? null : $jsonData->agrupacion );
    $inventario->setAgrupacionTam($jsonData->agrupacion == "null" ? null : $jsonData->agrupacionTam );
    $inventario->setPrecioPorAgrupacion($jsonData->tipo_de_precio == "agrupacion");
    $inventario->setActivo(1);
    DAO::transBegin();

    try {
        Logger::log("Insertando nuevo producto: " . $jsonData->descripcion . ", " . $jsonData->escala . ", " . $jsonData->tratamiento);
        InventarioDAO::save($inventario);
    } catch (Exception $e) {
        DAO::transRollback();
        Logger::log("Imposible crear nuevo producto:" . $e);
        return array("success" => false, "reason" => "Error al crear el nuevo producto, porfavor intente de nuevo.");
    }


//insertar actualizacion de precio
    $actualizacion = new ActualizacionDePrecio();

    $actualizacion->setIdProducto($inventario->getIdProducto());
    $actualizacion->setIdUsuario($_SESSION['userid']);
    $actualizacion->setPrecioVenta($jsonData->precio_venta);

    if (POS_COMPRA_A_CLIENTES) {
        $actualizacion->setPrecioCompra($jsonData->precio_compra);
    }else{
	    $actualizacion->setPrecioCompra(0);
	}


    if (!isset($jsonData->precio_intersucursal))
        $actualizacion->setPrecioVentaSinProcesar(0);
    else
        $actualizacion->setPrecioVentaSinProcesar($jsonData->precio_intersucursal);

    if (POS_MULTI_SUCURSAL) {
        $actualizacion->setPrecioIntersucursal($jsonData->precio_intersucursal);
        $actualizacion->setPrecioIntersucursalSinProcesar($jsonData->precio_intersucursal);
    } else {
        $actualizacion->setPrecioIntersucursal(0);
        $actualizacion->setPrecioIntersucursalSinProcesar(0);
    }


    try {
        ActualizacionDePrecioDAO::save($actualizacion);
    } catch (Exception $e) {
        DAO::transRollback();
        Logger::log("Imposible crear nuevo producto:" . $e);
        return array("success" => false, "reason" => $e);
    }

    Logger::log("Nuevo producto creado !");
    DAO::transEnd();
    return array("success" => true, "id" => $inventario->getIdProducto());
}

/* {
 *     "id_compra_proveedor":1,
 *     "id_producto":5,
 *     "resultante":10.12,
 *     "desecho":23.14,
 *     "subproducto":[
 *         {"id_producto":1,"id_compra_proveedor":2,"cantidad_procesada":10.45}
 *     ]
 * }
 */

function procesarProducto($json = null) {

    Logger::log("Iniciando el proceso de procesado de producto");

    if ($json == null) {
        Logger::log("No hay parametros para procesar el producto.");
        die('{ "success": false, "reason" : "Error : no hay suficientes datos para procesar el producto." }');
    }

    $data = parseJSON($json);

    //var_dump($data);

    if (!( isset($data->id_compra_proveedor) && isset($data->id_producto) && isset($data->resultante) && isset($data->desecho) && isset($data->subproducto) )) {
        Logger::log("Json invalido para iniciar el procesado de producto");
        die('{"success": false , "reason": "Error : verifique que esten llenos todos los campos necesarios." }');
    }

    if ($data->id_compra_proveedor == null || $data->id_producto == null || $data->resultante == null) {
        Logger::log("Json invalido para crear un nuevo proceso de producto");
        die('{"success": false , "reason": "Error : verifique que esten llenos todos los campos necesarios." }');
    }

    //verificamos que exista el producto
    if (!($producto = InventarioDAO::getByPK($data->id_producto))) {
        Logger::log("No se tiene registro del producto : {$data->id_producto}");
        die('{"success": false, "reason": "No se tiene registro del producto : ' . $data->id_producto . '"}');
    }

    //verificar que exista el id_compra_proveedor
    if (!( $compra_proveedor = CompraProveedorDAO::getByPK($data->id_compra_proveedor) )) {
        Logger::log("No se tiene registro de la compra a proveedor : " . $data->id_compra_proveedor);
        die('{"success": false, "reason": "No se tiene registro de la compra a proveedor : ' . $data->id_compra_proveedor . '"}');
    }

    //verificar que existan el producto en esa compora a proveedor
    if (!( $detalle_compra_proveedor = DetalleCompraProveedorDAO::getByPK($data->id_compra_proveedor, $data->id_producto) )) {
        Logger::log("No se tiene registro de la compra del producto : " . $data->id_producto . " en la compra a proveedor : " . $data->id_compra_proveedor);
        die('{"success": false, "reason": "No se tiene registro de la compra del producto : ' . $data->id_producto . ' en la compra a proveedor : ' . $data->id_compra_proveedor . '"}');
    }



    //  1.- Restar en inventario maestro a sus existencias :el desecho + la suma de las cantidades procesadas de los subproductos (EGRESO)
    //  2.- Sumar en IM a existencias_procesadas : (INGRESO)
    //  3.- Sumar a existencia y existencias procesadas en el inventario del subproducto (INGRESO)

    $suma = 0;

    $inventario_maestro = InventarioMaestroDAO::getByPK($data->id_producto, $data->id_compra_proveedor);

    foreach ($data->subproducto as $subproducto) {
        $suma += $subproducto->cantidad_procesada;
    }

    //verificar que el resultante + desecho + ( resultante * subproducto ) <= existencias 
    $consecuente = $data->resultante + $data->desecho + $suma;

    if ($consecuente > $inventario_maestro->getExistencias()) {
        Logger::log("Error : verifique la cantidad de otros productos");
        die('{"success": false, "reason": "Error : verifique la cantidad de otros productos"}');
    }


    //  1.- Restar en inventario maestro a sus existencias : el deshecho + la suma de las cantidades procesadas de los subproductos
    //$suma = $suma + $data->desecho + $data->resultante;
    $suma = $suma + $data->resultante;

    $inventario_maestro->setExistencias($inventario_maestro->getExistencias() - $suma);


    //registramos este egreso en el inventario maestro

    $compra_proveedor_fragmentacion = new CompraProveedorFragmentacion();
    $compra_proveedor_fragmentacion->setIdCompraProveedor($inventario_maestro->getIdCompraProveedor());
    $compra_proveedor_fragmentacion->setIdProducto($inventario_maestro->getIdProducto());
    $compra_proveedor_fragmentacion->setDescripcion("HUBO UN EGRESO DE {$suma}KG. DEL PRODUCTO {$producto->getDescripcion()} ORIGINAL POR CONCEPTO DE UN PROCESO EN LA REMISION CON EL FOLIO {$compra_proveedor->getFolio()}.");
    $compra_proveedor_fragmentacion->setProcesada(false);
    $compra_proveedor_fragmentacion->setCantidad(($suma * -1));
    $compra_proveedor_fragmentacion->setPrecio($detalle_compra_proveedor->getPrecioPorKg());
    $compra_proveedor_fragmentacion->setDescripcionRefId($inventario_maestro->getIdCompraProveedor());

    try {
        CompraProveedorFragmentacionDAO::save($compra_proveedor_fragmentacion);
    } catch (Exception $e) {
        DAO::transRollback();
        Logger::log("Error, al guardar los datos del historial del producto del inventario maestro. : " . $e);
    }

    $inventario_maestro->setExistencias($inventario_maestro->getExistencias() - $data->desecho);

    //registramos este egreso de merma en el inventario maestro
    $compra_proveedor_fragmentacion = new CompraProveedorFragmentacion();
    $compra_proveedor_fragmentacion->setIdCompraProveedor($inventario_maestro->getIdCompraProveedor());
    $compra_proveedor_fragmentacion->setIdProducto($inventario_maestro->getIdProducto());
    $compra_proveedor_fragmentacion->setDescripcion("HUBO UN EGRESO DE {$data->desecho}KG. DEL PRODUCTO {$producto->getDescripcion()} ORIGINAL POR CONCEPTO DE MERMA EN UN PROCESO EN LA REMISION CON EL FOLIO {$compra_proveedor->getFolio()}.");
    $compra_proveedor_fragmentacion->setProcesada(false);
    $compra_proveedor_fragmentacion->setCantidad(($data->desecho * -1));
    $compra_proveedor_fragmentacion->setPrecio($detalle_compra_proveedor->getPrecioPorKg());
    $compra_proveedor_fragmentacion->setDescripcionRefId($inventario_maestro->getIdCompraProveedor());

    try {
        CompraProveedorFragmentacionDAO::save($compra_proveedor_fragmentacion);
    } catch (Exception $e) {
        DAO::transRollback();
        Logger::log("Error, al guardar los datos del historial del producto del inventario maestro. : " . $e);
    }



    //  2.- Sumar en IM a existencias_procesadas

    $inventario_maestro->setExistenciasProcesadas($inventario_maestro->getExistenciasProcesadas() + $data->resultante);
    $inventario_maestro->setExistencias($inventario_maestro->getExistencias() + $data->resultante);

    DAO::transBegin();
    try {
        InventarioMaestroDAO::save($inventario_maestro);
    } catch (Exception $e) {
        Logger::log("Error al editar producto en inventario maestro:" . $e);
        DAO::transRollback();
        die('{"success": false, "reason": "Error al editar producto en inventario maestro"}');
    }

    //registramos este ingreso en el inventario maestro
    $compra_proveedor_fragmentacion = new CompraProveedorFragmentacion();
    $compra_proveedor_fragmentacion->setIdCompraProveedor($inventario_maestro->getIdCompraProveedor());
    $compra_proveedor_fragmentacion->setIdProducto($inventario_maestro->getIdProducto());
    $compra_proveedor_fragmentacion->setDescripcion("HUBO UN INGRESO DE {$data->resultante}KG. DEL PRODUCTO {$producto->getDescripcion()} PROCESADO COMO RESULTADO DE UN PROCESO DE ESTA MISMA REMISION CON EL FOLIO {$compra_proveedor->getFolio()}.");
    $compra_proveedor_fragmentacion->setProcesada(true);
    $compra_proveedor_fragmentacion->setCantidad($data->resultante);
    //$compra_proveedor_fragmentacion->setPrecio($detalle_compra_proveedor->getPrecioPorKg());
    $compra_proveedor_fragmentacion->setPrecio(0);
    $compra_proveedor_fragmentacion->setDescripcionRefId($inventario_maestro->getIdCompraProveedor());

    try {
        CompraProveedorFragmentacionDAO::save($compra_proveedor_fragmentacion);
    } catch (Exception $e) {
        DAO::transRollback();
        Logger::log("Error, al guardar los datos del historial del producto del inventario maestro. : " . $e);
        die('{"success": false, "reason": "Error, al guardar los datos del historial del producto del inventario maestro." }');
    }

    //3.- Sumar a existencia y existencias procesadas en el inventario del subproducto

    foreach ($data->subproducto as $subproducto) {

        $inventario_maestro = InventarioMaestroDAO::getByPK($subproducto->id_producto, $subproducto->id_compra_proveedor);

        $inventario_maestro->setExistenciasProcesadas($inventario_maestro->getExistenciasProcesadas() + $subproducto->cantidad_procesada);

        $inventario_maestro->setExistencias($inventario_maestro->getExistencias() + $subproducto->cantidad_procesada);


        try {
            InventarioMaestroDAO::save($inventario_maestro);
        } catch (Exception $e) {
            Logger::log("Error  la guardar producto procesado" . $e);
            DAO::transRollback();
            die('{"success": false, "reason": "Error  la guardar producto procesado"}');
        }

        $_producto = InventarioDAO::getByPK($subproducto->id_producto);

        if (!( $detalle_compra_proveedor = DetalleCompraProveedorDAO::getByPK($subproducto->id_compra_proveedor, $subproducto->id_producto) )) {
            Logger::log("No se tiene registro de la compra del producto : " . $subproducto->id_producto . " en la compra a proveedor : " . $subproducto->id_compra_proveedor);
            die('{"success": false, "reason": "No se tiene registro de la compra del producto : ' . $subproducto->id_producto . ' en la compra a proveedor : ' . $subproducto->id_compra_proveedor . '"}');
        }

        //registramos este ingreso en el inventario maestro
        $compra_proveedor_fragmentacion = new CompraProveedorFragmentacion();
        $compra_proveedor_fragmentacion->setIdCompraProveedor($inventario_maestro->getIdCompraProveedor());
        $compra_proveedor_fragmentacion->setIdProducto($inventario_maestro->getIdProducto());
        $compra_proveedor_fragmentacion->setDescripcion("HUBO UN INGRESO DE {$subproducto->cantidad_procesada}KG. DEL PRODUCTO {$_producto->getDescripcion()} PROCESADO POR CONCEPTO DE UN PROCESO EN LA REMISION {$compra_proveedor->getFolio()} DEL PRODUCTO {$producto->getDescripcion()}.");
        $compra_proveedor_fragmentacion->setProcesada(true);
        $compra_proveedor_fragmentacion->setCantidad($subproducto->cantidad_procesada);
        $compra_proveedor_fragmentacion->setPrecio(0);
        $compra_proveedor_fragmentacion->setDescripcionRefId($compra_proveedor->getIdCompraProveedor());

        try {
            CompraProveedorFragmentacionDAO::save($compra_proveedor_fragmentacion);
        } catch (Exception $e) {
            DAO::transRollback();
            Logger::log("Error, al guardar los datos del historial del producto del inventario maestro. : " . $e);
            die('{"success": false, "reason": "Error, al guardar los datos del historial del producto del inventario maestro." }');
        }
    }

    DAO::transEnd();

    Logger::log("termiando proceso de lavar producto con exito!");

    printf('{"success":true}');

    return;
}

function terminarCargamentoCompra($json = null) {

    Logger::log("Iniciando proceso de terminar cargamento compra ");

    if ($json == null) {
        Logger::log("No hay parametros para procesar el prodcuto.");
        die('{ "success": false, "reason" : "Parametros invalidos" }');
    }

    $data = parseJSON($json);

    if (!( isset($data->id_compra_proveedor) && isset($data->id_producto) )) {
        Logger::log("Json invalido para crear un nuevo proceso de producto");
        die('{"success": false , "reason": "Parametros invalidos." }');
    }

    if ($data->id_compra_proveedor == null || $data->id_producto == null) {
        Logger::log("Json invalido para crear un nuevo proceso de producto");
        die('{"success": false , "reason": "Parametros invalidos." }');
    }

    $inventario_maestro = InventarioMaestroDAO::getByPK($data->id_producto, $data->id_compra_proveedor);

    DAO::transBegin();

    $existencias = $inventario_maestro->getExistencias();
    $existencias_procesadas = $inventario_maestro->getExistenciasProcesadas();

    $total_existencias_originales = $existencias - $existencias_procesadas;
    $total_existencias_procesadas = $existencias_procesadas;

    $inventario_maestro->setExistencias(0);
    $inventario_maestro->setExistenciasProcesadas(0);

//------------------------ AGREGAMOS LA FRAGMENTACION

    if (!($inventario = InventarioDAO::getByPK($data->id_producto))) {
        Logger::log("Error al obtener los datos del producto {$data->id_producto}.");
        die('{"success": false , "reason": "Error al obtener los datos del producto ' . $data->id_producto . '." }');
    }

    if (!($remision = CompraProveedorDAO::getByPK($data->id_compra_proveedor))) {
        Logger::log("Error al obtener los datos de la compra al proveedor.");
        die('{"success": false , "reason": "Error al obtener los datos de la compra al proveedor." }');
    }

    if (!($detalle_compra_proveedor = DetalleCompraProveedorDAO::getByPK($data->id_compra_proveedor, $data->id_producto))) {
        Logger::log("Error al obtener los datos del detalle de la compra al proveedor.");
        die('{"success": false , "reason": "Error al obtener los datos del detalle de la compra al proveedor." }');
    }

    if ($total_existencias_procesadas > 0) {

        $compra_proveedor_fragmentacion = new CompraProveedorFragmentacion();
        $compra_proveedor_fragmentacion->setIdCompraProveedor($data->id_compra_proveedor);
        $compra_proveedor_fragmentacion->setIdProducto($data->id_producto);
        $compra_proveedor_fragmentacion->setDescripcion("SE DIO POR TERMINADO EL PRODUCTO {$inventario->getDescripcion()} PROCESADA DE LA REMISION {$remision->getFolio()} ");
        $compra_proveedor_fragmentacion->setProcesada(1);
        $compra_proveedor_fragmentacion->setCantidad($total_existencias_procesadas * -1);
        $compra_proveedor_fragmentacion->setPrecio($detalle_compra_proveedor->getPrecioPorKg());
        $compra_proveedor_fragmentacion->setDescripcionRefId(0);

        try {
            CompraProveedorFragmentacionDAO::save($compra_proveedor_fragmentacion);
        } catch (Exception $e) {
            DAO::transRollback();
            Logger::log("Error, al guardar los datos del historial del producto del inventario maestro. : " . $e);
            die('{"success": false, "reason": "Error, al guardar los datos del historial del producto del inventario maestro." }');
        }
    }

    if ($total_existencias_originales > 0) {

        $compra_proveedor_fragmentacion = new CompraProveedorFragmentacion();
        $compra_proveedor_fragmentacion->setIdCompraProveedor($data->id_compra_proveedor);
        $compra_proveedor_fragmentacion->setIdProducto($data->id_producto);
        $compra_proveedor_fragmentacion->setDescripcion("SE DIO POR TERMINADO EL PRODUCTO {$inventario->getDescripcion()} ORIGINAL DE LA REMISION {$remision->getFolio()} ");
        $compra_proveedor_fragmentacion->setProcesada(0);
        $compra_proveedor_fragmentacion->setCantidad($total_existencias_originales * -1);
        $compra_proveedor_fragmentacion->setPrecio($detalle_compra_proveedor->getPrecioPorKg());
        $compra_proveedor_fragmentacion->setDescripcionRefId(0);

        try {
            CompraProveedorFragmentacionDAO::save($compra_proveedor_fragmentacion);
        } catch (Exception $e) {
            DAO::transRollback();
            Logger::log("Error, al guardar los datos del historial del producto del inventario maestro. : " . $e);
            die('{"success": false, "reason": "Error, al guardar los datos del historial del producto del inventario maestro." }');
        }
    }


//------------------------

    try {
        InventarioMaestroDAO::save($inventario_maestro);
    } catch (Exception $e) {
        Logger::log("Error al editar producto en inventario maestro:" . $e);
        DAO::transRollback();
        die('{"success": false, "reason": "Error al editar producto en inventario maestro"}');
    }


    if ($data->restante != null) {

        $inventario_maestro = InventarioMaestroDAO::getByPK($data->restante->id_producto, $data->restante->id_compra_proveedor);
        $inventario_maestro->setExistencias($inventario_maestro->getExistencias() + $existencias);
        $inventario_maestro->setExistenciasProcesadas($inventario_maestro->getExistenciasProcesadas() + $existencias_procesadas);

        try {
            InventarioMaestroDAO::save($inventario_maestro);
        } catch (Exception $e) {
            Logger::log("Error al transferir producto en inventario maestro : " . $e);
            DAO::transRollback();
            die('{"success": false, "reason": "Error al transferir el producto en la otra compra."}');
        }

        if (!($inventario = InventarioDAO::getByPK($data->restante->id_producto))) {
            Logger::log("Error al obtener los datos del producto {$data->restante->id_producto}.");
            die('{"success": false , "reason": "Error al obtener los datos del producto ' . $data->restante->id_producto . '." }');
        }

        if (!($remision = CompraProveedorDAO::getByPK($data->restante->id_compra_proveedor))) {
            Logger::log("Error al obtener los datos de la compra al proveedor.");
            die('{"success": false , "reason": "Error al obtener los datos de la compra al proveedor." }');
        }

        if (!($detalle_compra_proveedor = DetalleCompraProveedorDAO::getByPK($data->restante->id_compra_proveedor, $data->restante->id_producto))) {
            Logger::log("Error al obtener los datos del detalle de la compra al proveedor.");
            die('{"success": false , "reason": "Error al obtener los datos del detalle de la compra al proveedor." }');
        }

//------------------------------

        if ($total_existencias_procesadas > 0) {

            $compra_proveedor_fragmentacion = new CompraProveedorFragmentacion();
            $compra_proveedor_fragmentacion->setIdCompraProveedor($data->restante->id_compra_proveedor);
            $compra_proveedor_fragmentacion->setIdProducto($data->restante->id_producto);
            $compra_proveedor_fragmentacion->setDescripcion("SE TRANSLADO EL PRODUCTO {$inventario->getDescripcion()} PROCESADA DE LA REMISION {$remision->getFolio()} ");
            $compra_proveedor_fragmentacion->setProcesada(1);
            $compra_proveedor_fragmentacion->setCantidad($total_existencias_procesadas);
            $compra_proveedor_fragmentacion->setPrecio($detalle_compra_proveedor->getPrecioPorKg());
            $compra_proveedor_fragmentacion->setDescripcionRefId($remision->getIdCompraProveedor());

            try {
                CompraProveedorFragmentacionDAO::save($compra_proveedor_fragmentacion);
            } catch (Exception $e) {
                DAO::transRollback();
                Logger::log("Error, al guardar los datos del historial del producto del inventario maestro. : " . $e);
                die('{"success": false, "reason": "Error, al guardar los datos del historial del producto del inventario maestro." }');
            }
        }

        if ($total_existencias_originales > 0) {

            $compra_proveedor_fragmentacion = new CompraProveedorFragmentacion();
            $compra_proveedor_fragmentacion->setIdCompraProveedor($data->restante->id_compra_proveedor);
            $compra_proveedor_fragmentacion->setIdProducto($data->restante->id_producto);
            $compra_proveedor_fragmentacion->setDescripcion("SE TRANSLADO EL PRODUCTO {$inventario->getDescripcion()} ORIGINAL DE LA REMISION {$remision->getFolio()} ");
            $compra_proveedor_fragmentacion->setProcesada(0);
            $compra_proveedor_fragmentacion->setCantidad($total_existencias_originales);
            $compra_proveedor_fragmentacion->setPrecio($detalle_compra_proveedor->getPrecioPorKg());
            $compra_proveedor_fragmentacion->setDescripcionRefId($remision->getIdCompraProveedor());

            try {
                CompraProveedorFragmentacionDAO::save($compra_proveedor_fragmentacion);
            } catch (Exception $e) {
                DAO::transRollback();
                Logger::log("Error, al guardar los datos del historial del producto del inventario maestro. : " . $e);
                die('{"success": false, "reason": "Error, al guardar los datos del historial del producto del inventario maestro." }');
            }
        }

//------------------------------
    }



    DAO::transEnd();

    Logger::log("termiando proceso de terminar cargamento compra con exito!");

    printf('{"success":true}');

    return;
}

/* * Procesar producto sucursal.
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
 * 	   "id_producto": 1,
 * 	    "procesado": 25,
 * 	    "desecho": 5,
 * 	    "subproducto": [{
 *      					"id_producto": 2,
 * 		            		"procesado":10
 * 		        		},
 * 		        		{
 *      					"id_producto": 3,
 * 		            		"procesado": 11
 * 		        		}]
 * }
 * 
 * </code>
 * @param string es un JSON 
 * @return void
 * */

function procesarProductoSucursal($json) {

    Logger::log("iniciando proceso de lavado en sucursal.");

    DetalleInventarioDAO::transBegin();

    $datos = parseJSON($json);

    $subproducto = $datos->subproducto;

    $di = DetalleInventarioDAO::getByPK($datos->id_producto, $_SESSION["sucursal"]);

    if ($di == NULL) {
        DetalleInventarioDAO::transRollback();
        die('{"success":false,"reason":"No existe el producto"}');
    }

//sumamos el desecho y los otros productos resultantes
    $suma = $datos->procesado + $datos->desecho;

    for ($i = 0; $i < sizeof($subproducto); $i++) {
        $suma+=$subproducto[$i]->procesado;
    }

    Logger::log("El desecho mas los productos resultantes son : " . $suma);

//verificamos que los productos no sean mayores qeu los insumos
    if ($suma > $di->getExistencias()) {

        Logger::log("Imposible procesar producto, " . $suma . " <= " . $di->getExistencias());

        DetalleInventarioDAO::transRollback();
        die('{"success":false,"reason":"No se pudo procesar el producto, hay menos existencias de las que se pretenden procesar."}');
    }

//a las existencias le restamos los otros productos y el desecho				
    $di->setExistencias($di->getExistencias() - $suma);
    $di->setExistenciasProcesadas($di->getExistenciasProcesadas() + $datos->procesado);

    try {
        DetalleInventarioDAO::save($di);
    } catch (Exception $e) {
        Logger::log($e);
        DetalleInventarioDAO::transRollback();
        die('{"success" : false, "reason" : "Error : Los productos no pueden superar a los insumos, verifique sus datos."}');
    }


//echo $subproducto[0]->id_producto;
    for ($i = 0; $i < sizeof($subproducto); $i++) {

        $dis[$i] = DetalleInventarioDAO::getByPK($subproducto[$i]->id_producto, $_SESSION["sucursal"]);
        if ($dis[$i] == NULL) {
            DetalleInventarioDAO::transRollback();
            die('{"success":false,"reason":"Error : No se tiene registro de uno o mas subproductos, verifique sus datos."}');
        }

        $dis[$i]->setExistenciasProcesadas($dis[$i]->getExistenciasProcesadas() + $subproducto[$i]->procesado);

        try {

            DetalleInventarioDAO::save($dis[$i]);
        } catch (Exception $e) {
            Logger::log($e);
            DetalleInventarioDAO::transRollback();
            die('{"success":false,"reason":"Error al procesar el producto, verifique sus datos e intente de nuevo."}');
        }
    }
    DetalleInventarioDAO::transEnd();
    Logger::log("termiando proceso de lavado en sucursal.");
    echo '{"success":true}';
}

if (isset($args['action'])) {
    switch ($args['action']) {
        case 400:

			Logger::log("Listando el inventario....");
			
            $json = json_encode(listarInventario($_SESSION["sucursal"]));
            if (isset($args['hashCheck'])) {
				//revisar hashes
                if (md5($json) == $args['hashCheck']) {
                    return;
                }
            }

            printf('{ "success": true, "hash" : "%s" , "datos": %s }', md5($json), $json);

            break;

        case 401://regresa el detalle del producto en la sucursal actual
            detalleProductoSucursal($args);
            break;

        case 402://regresa las compras de una sucursal
            printf('{ "success": true, "datos": %s }', json_encode(comprasSucursal($args)));
            break;

        case 403://regresa el detalle de la compra
            printf('{ "success": true, "datos": %s }', json_encode(detalleCompra($args)));
            break;

        case 404://regresa el detalle de la venta
            printf('{ "success": false, "datos": %s }', json_encode(detalleVentas($args['id_venta'])));
            break;

        case 405://nuevo producto
            echo json_encode(nuevoProducto($args['data']));
            break;

        case 406://procesar producto
//porocesar producto (lavar)

            if (!( isset($args['data']) && $args['data'] != null )) {
                Logger::log("No hay parametros para procesar el producto.");
                die('{"success": false , "reason": "Parametros invalidos." }');
            }

//{"id_compra_proveedor":1,"id_producto":5,"resultante":10.12,"desecho":23.14,"subproducto":[{"id_producto":1,"id_compra_proveedor":2,"cantidad_procesada":10.45}]}

            procesarProducto($args['data']);

            break;

        case 407://termianr cargamento de compra

            if (!( isset($args['data']) && $args['data'] != null )) {
                Logger::log("No hay parametros para procesar el producto.");
                die('{"success": false , "reason": "Parametros invalidos." }');
            }

//{"id_compra":1,"id_producto":5,"cantidad_procesada":10}

            terminarCargamentoCompra($args['data']);

            break;

        case 408://procesar producto sucursal
            procesarProductoSucursal($args["data"]);
            break;

        case 499:
            echo obtenerPrecioIntersucursal($args['id_producto']);
            break;
    }
}

//$objecto = new stdClass();


