<?php

require_once('model/sucursal.dao.php');
require_once('model/ventas.dao.php');
require_once('model/usuario.dao.php');
require_once('model/grupos_usuarios.dao.php');
require_once('model/cliente.dao.php');
require_once('model/corte.dao.php');

require_once('logger.php');

function listarSucursales() {

    $foo = new Sucursal();
    $foo->setActivo(1);
    $foo->setIdSucursal(1);

    $bar = new Sucursal();
    $bar->setIdSucursal(99);

    $sucursales = SucursalDAO::byRange($foo, $bar);

    $array_sucursales = array();

    foreach ($sucursales as $sucursal) {

        if (( $_SESSION['sucursal'] == $sucursal->getIdSucursal())) {
            continue;
        }

        array_push($array_sucursales, array(
            'id_sucursal' => $sucursal->getIdSucursal(),
            'descripcion' => $sucursal->getDescripcion(),
            'text' => $sucursal->getDescripcion(),
            'value' => $sucursal->getIdSucursal()
        ));
    }

    return $array_sucursales;
}

function ventasSucursal($sid = null) {

    if (!$sid) {
        return null;
    }

    $cC = array();

    $ventas = new Ventas();
    $ventas->setIdSucursal($sid);

    if (isset($tipo_venta)) {
        $ventas->setTipoVenta($tipo_venta);
    }

    $comprasCliente = VentasDAO::search($ventas);


    foreach ($comprasCliente as $c) {
        //make readable data

        $sucursal = SucursalDAO::getByPK($c->getIdSucursal());
        $cajero = UsuarioDAO::getByPK($c->getIdUsuario());

        if ($c->getIdCliente() < 0) {
            $cliente = "Caja Comun";
        } else {
            $cliente = ClienteDAO::getByPK($c->getIdCliente())->getNombre();
        }



        $data = array(
            "cajero" => $cajero ? $cajero->getNombre() : "<b>Error</b>",
            "sucursal" => $sucursal ? $sucursal->getDescripcion() : "<b>Error</b>",
            "descuento" => $c->getDescuento(),
            "fecha" => $c->getFecha(),
            "id_cliente" => $c->getIdCliente(),
            "cliente" => $cliente,
            "id_sucursal" => $c->getIdSucursal(),
            "id_usuario" => $c->getIdUsuario(),
            "id_venta" => $c->getIdVenta(),
            "ip" => $c->getIp(),
            "iva" => $c->getIva(),
            "pagado" => $c->getPagado(),
            "subtotal" => $c->getSubtotal(),
            "tipo_venta" => $c->getTipoVenta(),
            "total" => $c->getTotal()
        );



        array_push($cC, $data);
    }

    return $cC;
}

function detallesSucursal($sid = null) {
    if (!$sid) {
        return null;
    }

    return SucursalDAO::getByPK($sid);
}

/*
 *
 * 	@param detalles( gerente, descripcion, direccion, prefijo_factura, rfc, telefono )
 *
 *
 * */

function abrirSucursal($json = null) {

    //solo el admin puede abrir sucursales
    if ($_SESSION['grupo'] != 1) {
        die('{"success" : false, "reason": "Accesso denegado."}');
    }

    if (!isset($json) || $json == null) {
        Logger::log("Parametros invalidos para abrir sucursal");
        die('{"success" : false, "reason": "Parametros invalidos."}');
    }

    $foo = $json;
    $json = parseJSON($json);

    if ($json == null) {
        Logger::log("Parametros invalidos para abrir sucursal: Impsible parsear json: " . $foo);
        die('{"success" : false, "reason": "Parametros invalidos."}');
    }

    if (!( isset($json->gerente) &&
            isset($json->descripcion) &&
            isset($json->calle) &&
            isset($json->prefijo_factura) &&
            isset($json->rfc) &&
            isset($json->telefono))) {
        Logger::log("Parametros invalidos para abrir sucursal:" . $foo);
        die('{"success" : false, "reason": "Por favor llene todos los campos para abrir una sucursal."}');
    }


    if (( strlen($json->gerente) < 1 ||
            strlen($json->descripcion) < 1 ||
            strlen($json->calle) < 1 ||
            strlen($json->prefijo_factura) < 1 ||
            strlen($json->rfc) < 1 ||
            strlen($json->telefono) < 1)) {
        Logger::log("Hay campos muy cortos para abrir sucursal" . $foo);
        die('{"success" : false, "reason": "Por favor llene todos los campos para abrir una sucursal."}');
    }

    $gerente = UsuarioDAO::getByPK($json->gerente);

    if ($gerente == null) {
        Logger::log("Gerente no existe en lista de usuarios:" . $foo);
        die('{"success" : false, "reason": "Este usuario no existe."}');
    }


    //revisar que pertenesca al grupo de gerentes
    $gu = GruposUsuariosDAO::getByPK($json->gerente);
    if ($gu->getIdGrupo() != 2) {
        die('{"success" : false, "reason": "Este usuario no pertenece al grupo de gerentes."}');
    }


    $sucursal = new Sucursal();

    //validar los datos
    //revisar que no sea gerente ya de una sucursal
    $suc = new Sucursal();
    $suc->setGerente($json->gerente);

    if (sizeof(SucursalDAO::search($suc)) > 0) {
        die('{"success" : false, "reason": "Este empleado ya es gerente de una sucursal."}');
    }

    $sucursal->setActivo("1");
    $sucursal->setDescripcion($json->descripcion);
    $sucursal->setRazonSocial($json->descripcion);
    $sucursal->setCalle($json->calle);
    $sucursal->setGerente($json->gerente);
    $sucursal->setLetrasFactura($json->prefijo_factura);
    $sucursal->setRfc($json->rfc);
    $sucursal->setPais("Mexico");
    $sucursal->setTelefono($json->telefono);
    $sucursal->setSaldoAfavor(0);



    $sucursal->setNumeroExterior($json->numero_exterior);
    $sucursal->setNumeroInterior($json->numero_interior);
    $sucursal->setColonia($json->colonia);
    $sucursal->setLocalidad($json->localidad);
    $sucursal->setMunicipio($json->municipio);
    $sucursal->setReferencia($json->referencia);
    $sucursal->setEstado($json->estado);
    $sucursal->setCodigoPostal($json->codigo_postal);




    $buscarSucursal = new Sucursal();
    $buscarSucursal->setLetrasFactura($json->prefijo_factura);
    $resultados = SucursalDAO::search($buscarSucursal);
    if (sizeof($resultados) > 0) {
        Logger::log("Prefijo de factura duplicado");
        die('{"success" : false, "reason": "Este prefijo de factura ya esta siendo utilizado por otra sucursal."}');
    }

    DAO::transBegin();
    try {
        SucursalDAO::save($sucursal);
    } catch (Exception $e) {
        DAO::transRollback();
        Logger::log("Error al insertar nueva sucursal " . $e);
        die('{"success" : false, "reason": "Error, intente de nuevo."}');
    }


    //mover a este gerente a la nueva sucursal
    $gerente->setIdSucursal($sucursal->getIdSucursal());

    try {
        UsuarioDAO::save($gerente);
    } catch (Exception $e) {
        DAO::transRollback();
        Logger::log($e);
        die('{"success" : false, "reason": "Error, porfavor intente de nuevo."}');
    }


    //crear su caaja comun
    $cajaComun = new Cliente();

    $cajaComun->setActivo(1);
    $cajaComun->setMunicipio("");
    $cajaComun->setNumeroExterior("");
    $cajaComun->setColonia("");
    $cajaComun->setEstado("");
    $cajaComun->setPais("");
    $cajaComun->setCodigoPostal("");
    $cajaComun->setDescuento(0);
    $cajaComun->setCalle("");
    $cajaComun->setEMail("");
    $cajaComun->setIdCliente("-" . $sucursal->getIdSucursal());
    $cajaComun->setIdSucursal($sucursal->getIdSucursal());
    $cajaComun->setIdUsuario($_SESSION['userid']);
    $cajaComun->setLimiteCredito(0);
    $cajaComun->setRazonSocial("Caja Comun");
    $cajaComun->setRfc($json->rfc);
    $cajaComun->setTelefono($json->telefono);

    try {
        ClienteDAO::save($cajaComun);
    } catch (Exception $e) {
        DAO::transRollback();
        Logger::log($e);
        die('{"success" : false, "reason": "Error, porfavor intente de nuevo."}');
    }


    DAO::transEnd();
    echo '{"success" : true, "nid": ' . $sucursal->getIdSucursal() . ' }';
    Logger::log("Sucursal " . $sucursal->getIdSucursal() . " creada !");
    return;
}

function editarSucursal($sid, $payloadJSON, $verbose = true) {

    Logger::log("editar detalles de sucursal iniciado...");

    $suc = SucursalDAO::getByPK($sid);

    if (sizeof($suc) < 1) {
        if ($verbose) {
            echo '{ "success" : false, "reason" : "Esta sucursal no existe." }';
        }
        Logger::log("intentando editar una sucursal que no existe");
        return false;
    }

    try {
        $payload = parseJSON($payloadJSON);
    } catch (Exception $e) {
        if ($verbose) {
            echo '{ "success" : false, "reason" : "Invalid JSON." }';
        }
        Logger::log("json invalido " . $e);
        return false;
    }

    if ($payload === null) {
        if ($verbose) {
            echo '{ "success" : false, "reason" : "Invalid DATA." }';
        }

        return false;
    }


    //Verificamos si existe la letra de la factura en otra sucursal
    $this_sucursal = SucursalDAO::getByPK($sid);

    if ($this_sucursal->getLetrasFactura() != $payload->letras_factura) {

        $buscarSucursal = new Sucursal();
        $buscarSucursal->setLetrasFactura($payload->letras_factura);
        $resultados = SucursalDAO::search($buscarSucursal);


        if (sizeof($resultados) > 0) {
            Logger::log("Prefijo de factura duplicado");
            die('{"success" : false, "reason": "Este prefijo de factura ya esta siendo utilizado por otra sucursal."}');
        }
    }


    if (isset($payload->descripcion)) {
        $suc->setDescripcion($payload->descripcion);
    }

    if (isset($payload->razon_social)) {
        $suc->setRazonSocial($payload->razon_social);
    }

    if (isset($payload->rfc)) {
        $suc->setRFC($payload->rfc);
    }

    if (isset($payload->calle)) {
        $suc->setCalle($payload->calle);
    }

    if (isset($payload->numero_exterior)) {
        $suc->setNumeroExterior($payload->numero_exterior);
    }

    if (isset($payload->numero_interior)) {
        $suc->setNumeroInterior($payload->numero_interior);
    }

    if (isset($payload->colonia)) {
        $suc->setColonia($payload->colonia);
    }

    if (isset($payload->localidad)) {
        $suc->setLocalidad($payload->localidad);
    }

    if (isset($payload->referencia)) {
        $suc->setReferencia($payload->referencia);
    }

    if (isset($payload->municipio)) {
        $suc->setMunicipio($payload->municipio);
    }

    if (isset($payload->estado)) {
        $suc->setEstado($payload->estado);
    }

    if (isset($payload->codigo_postal)) {
        $suc->setCodigoPostal($payload->codigo_postal);
    }

    if (isset($payload->pais)) {
        $suc->setPais($payload->pais);
    }

    if (isset($payload->letras_factura)) {
        $suc->setLetrasFactura($payload->letras_factura);
    }

    if (isset($payload->telefono)) {
        $suc->setTelefono($payload->telefono);
    }




    try {
        SucursalDAO::save($suc);
    } catch (Exception $e) {
        if ($verbose) {
            echo '{ "success" : false, "reason" : "Error al editar la sucursal. Intente de nuevo : ' . $e . '." }';
        }
        Logger::log("error al editar sucursal " . $e);
        return false;
    }

    if ($verbose) {
        echo '{ "success" : true }';
    }

    Logger::log("detalles de sucursal editados !");
    return true;
}

function cerrarSucursal($sid, $verbose = true) {

    Logger::log("CERRANDO SUCURSAL INICIADO", 2);

    $suc = SucursalDAO::getByPK($sid);

    if (sizeof($suc) < 1) {
        if ($verbose) {
            echo '{ "success" : false, "reason" : "Esta sucursal no existe." }';
        }

        return false;
    }

    //verificar que no este ya cerrada
    if ($suc->getActivo() == "0") {
        if ($verbose) {
            echo '{ "success" : false, "reason" : "Esta sucursal ya esta cerrada." }';
        }

        return false;
    }

    //obtener el gerente de esta sucursal
    $gerente = $suc->getGerente();

    if ($gerente !== null) {
        //desasignar a este gerente
        $gU = UsuarioDAO::getByPK($gerente);
        $gU->setIdSucursal(null);
        UsuarioDAO::save($gU);
    }



    $suc->setGerente(null);
    $suc->setActivo(0);
    SucursalDAO::save($suc);


    if ($verbose) {
        echo '{ "success" : true }';
    }

    return true;
}

function listarPersonal() {
    
}

function estadisticasVentas() {
    
}

function presindirEmpleado() {
    
}

function agregarGerente() {
    
}

function realizarCorte( $id_sucursal, $verbose = true ) {
    Logger::log("---- Realizando Corte ----");
	
    DAO::transBegin();
    
	$c = new Corte();
	$c->setIdSucursal($id_sucursal);
	$c->setTotalVentas(0);
	$c->setTotalVentasAbonado(0);
	$c->setTotalVentasSaldo(0);
	$c->setTotalCompras(0);
	$c->setTotalComprasAbonado(0);
	$c->setTotalGastos(0);
	$c->setTotalGastosAbonado(0);
	$c->setTotalIngresos(0);
	$c->setTotalGananciaNeta(0);
	
	try{
		CorteDAO::save( $c );
	}catch(Exception $e){
		Logger::log($e);
		echo '{ "success" : false, "reason" : "Error, intente de nuevo" }';
		DAO::transRollback();
	    
		return;
	}
	
	DAO::transEnd();
	if($verbose){
		echo '{ "success" : true }';
	}

	
	Logger::log("---- Fin de Corte ----");
}

function clientesDeudores() {
    
}

function inventarioSucursal() {
    //esta ya esta en inventario
}



//obtiene la informacion de la sucursal actual
function informacionSucursal() {

    if (!isset($_SESSION['sucursal']))
	{
		Logger::log("Su cuenta no esta ligada a una sucursal especifica.");
		return null;
    }

    if (!( $sucursal = SucursalDAO::getByPK($_SESSION['sucursal']) ))
	{
		Logger::log("No se tiene registros de esa sucursal.");
		return null;
    }
	
	return $sucursal->asArray();

}



//operaciones intersucursales
function venderASucursal($json) {
    if ($json == NULL) {
        die('{"success":false,"reason":"No se ha agregado ningun producto para la operacion."}');
    }
    DAO::transBegin();
    $datos = parseJSON($json);
    $productos = $datos->items;
    //iniciar la venta
    $venta = new Ventas();
    $venta->setCancelada(0);
    $venta->setDescuento(0);
    $venta->setFecha(time());
    $venta->setIdCliente("-" . $_SESSION["sucursal"]);
    $venta->setIdSucursal($_SESSION["sucursal"]);
    $venta->setIdUsuario($_SESSION["userid"]);
    $venta->setIp(getip());
    $venta->setPagado(0);
    $venta->setTipoVenta("credito");
    $venta->setLiquidada(0);

    $cantidad = 0;
    $cantidadprocesada = 0;
    $subtotalcantidad = 0;
    $subtotalcantidadprocesada = 0;

    for ($i = 0; $i < sizeof($productos); $i++) {

        $existe = $di[$i] = DetalleInventarioDAO::getByPK($productos[$i]->id_producto, $_SESSION["sucursal"]);
        if ($existe == NULL) {
            DAO::transRollback();
            die('{"success":false,"reason":"No existe el producto"}');
        }
        if ($productos[$i]->cantidad > $di[$i]->getExistencias()) {
            DAO::transRollback();
            die('{"success":false,"reason":"Existencias insuficientes"}');
        }
        if ($productos[$i]->cantidad_procesada > $di[$i]->getExistenciasProcesadas()) {
            DAO::transRollback();
            die('{"success":false,"reason":"Existencias procesadas insuficientes"}');
        }

        $di[$i]->setExistencias($di[$i]->getExistencias() - $productos[$i]->cantidad);
        $di[$i]->setExistenciasProcesadas($di[$i]->getExistenciasProcesadas() - $productos[$i]->cantidad_procesada);
        try {
            DetalleInventarioDAO::save($di[$i]);
        } catch (Exception $e) {
            Logger::log($e);
            DAO::transRollback();
            die('{"success":false,"reason":"No se pudo hacer la venta a sucursal. Intente de nuevo."}');
        }

        //aki obtengo el precio de cada producto
        $cantidad+=$productos[$i]->cantidad;
        $cantidadprocesada+=$productos[$i]->cantidad_procesada;
        $pu = obtenerPrecioIntersucursal($productos[$i]->id_producto);
        $subtotalcantidad+=$pu * $productos[$i]->cantidad;
        $subtotalcantidadprocesada+=$pu * $productos[$i]->cantidad_procesada;
    }
    $subtotal = $subtotalcantidad + $subtotalcantidadprocesada;
    $total = $subtotal;
    $totalcantidad = $subtotalcantidad;
    $totalcantidadprocesada = $subtotalcantidadprocesada;

    $venta->setSubtotal($subtotal);
    $venta->setIva(0);
    $venta->setTotal($total);
    try {
        VentasDAO::save($venta);
        $id_venta = $venta->getIdVenta() . "idventa";
    } catch (Exception $e) {
        Logger::log($e);
        DAO::transRollback();
        die('{"success":false,"reason":"No se pudo realizar la venta. Intente de nuevo."}');
    }

//		DetalleVentaDAO::transBegin();
    $detalleventa = new DetalleVenta();
    $detalleventa->setIdVenta($id_venta);
    for ($i = 0; $i < sizeof($productos); $i++) {
        $detalleventa->setIdProducto($productos[$i]->id_producto);
        $detalleventa->setCantidad($productos[$i]->cantidad);
        $detalleventa->setCantidadProcesada($productos[$i]->cantidad_procesada);
        $pu = obtenerPrecioIntersucursal($productos[$i]->id_producto);
        $detalleventa->setPrecio($productos[$i]->cantidad * $pu);
        $detalleventa->setPrecioProcesada($productos[$i]->cantidad_procesada * $pu);
        try {
            DetalleVentaDAO::save($detalleventa);
        } catch (Exception $e) {
            Logger::log($e);
            DAO::transRollback();
            die('{"success":false,"reason":"No se pudo realizar la venta. Intente de nuevo."}');
        }
    }
    DAO::transEnd();
    echo '{"success":true}';
}

//venderASucursal

/*

  Detalle Gasto


 */

function detalleGasto($gid) {

    $gasto = GastosDAO::getByPK($gid);
    $items = array();
    if ($gasto == null) {
        $items = null;
    } else {

        $usuario = UsuarioDAO::getByPK($gasto->getIdUsuario());
        $sucursal = SucursalDAO::getByPK($gasto->getIdSucursal());

        array_push($items, array(
            "id_gasto" => $gasto->getIdGasto(),
            "id_sucursal" => $sucursal->getDescripcion(),
            "id_usuario" => $usuario->getNombre(),
            "folio" => $gasto->getFolio(),
            "concepto" => $gasto->getConcepto(),
            "monto" => $gasto->getMonto(),
            "nota" => $gasto->getNota(),
            "fecha" => $gasto->getFecha(),
            "fecha_ingreso" => $gasto->getFechaIngreso()
        ));
    }
    return $items;
}

/*

  Detalle Ingreso


 */

function detalleIngreso($iid) {

    $ingreso = IngresosDAO::getByPK($iid);
    $items = array();
    if ($ingreso == null) {
        $items = null;
    } else {

        $usuario = UsuarioDAO::getByPK($ingreso->getIdUsuario());
        $sucursal = SucursalDAO::getByPK($ingreso->getIdSucursal());

        array_push($items, array(
            "id_ingreso" => $ingreso->getIdIngreso(),
            "id_sucursal" => $sucursal->getDescripcion(),
            "id_usuario" => $usuario->getNombre(),
            "concepto" => $ingreso->getConcepto(),
            "monto" => $ingreso->getMonto(),
            "nota" => $ingreso->getNota(),
            "fecha" => $ingreso->getFecha(),
            "fecha_ingreso" => $ingreso->getFechaIngreso()
        ));
    }
    return $items;
}

/**
 * Crea un nuevo puesto de trabajo
 * @param <type> $data
 * @return string
 */
function nuevoPuesto($args) {

    $grupo = new Grupos();

    if(!isset($args['data'])){
        die('{"success":false,"reason":"No se enviaron los parametros"}');
    }

    $json = parseJSON($args['data']);

    $grupo->setNombre($json->nombre);
    $grupo->setDescripcion($json->descripcion);
    
    
    try {
        GruposDAO::save($grupo);
    } catch (Exception $e) {
        Logger::log($e);        
        die('{"success":false,"reason":"No se pudo registrar el nuevo puesto : ' . $e . '"}');
    }

    echo '{"success" : true}';

    return true;
}

/**
 * Edita un puesto de trabajo especifico
 * @param <type> $data
 * @return string
 */
function editarPuesto($args) {


    if(!isset($args['data'])){
        die('{"success":false,"reason":"No se enviaron los parametros"}');
    }

    $json = parseJSON($args['data']);

    if(!($grupo = GruposDAO::getByPK($json->id))){
        die('{"success":false,"reason":"No se pudo editar el puesto, no se encontro el registro"}');
    }

    $grupo->setNombre($json->nombre);
    $grupo->setDescripcion($json->descripcion);


    try {
        GruposDAO::save($grupo);
    } catch (Exception $e) {
        Logger::log($e);
        die('{"success":false,"reason":"No se pudo modificar el puesto : ' . $e . '"}');
    }

    echo '{"success" : true}';

    return true;
}

if (isset($args['action'])) {

    switch ($args['action']) {
        case 700://listar sucursales
            printf('{"success" : true, "datos": %s}', json_encode(listarSucursales()));
            break;

        case 701://abrir sucursal

            if (!isset($args['data'])) {
                die('{"success" : false, "reason": "Parametros invalidos."}');
            }

            abrirSucursal($args['data']);

            break;

        case 702://editar detalle sucursal
            if (!isset($args['sid']) || !isset($args['payload'])) {
                die('{"success" : false, "reason": "Parametros invalidos."}');
            }

            editarSucursal($args['sid'], $args['payload']);
            break;

        case 703://cerrar sucursal
            if (!isset($args['sid'])) {
                die('{"success" : false, "reason": "Parametros invalidos."}');
            }
            cerrarSucursal($args['sid']);
            break;

        case 704://listar personal
            listarPersonal();
            break;

        /*
          case 705://estadisticas de venta por empleado
          estadisticasVentas( $args );
          break;
         */
        case 706://presindir empleado
            presindirEmpleado($args);
            break;

        case 707://agregar gerentes
            agregarGerente($args);
            break;

        case 708://hacer corte
            realizarCorte( $args["id_sucursal"] );
            break;

        case 709://clientes deudores sucursal (arrojara le total de las deudas de la sucursal)
            clientesDeudores($args);
            break;

        case 710://flujo de efectivo

            break;

        case 711://inventario por sucursal
            inventarioSucursal($args);
            break;

        case 712:
            informacionSucursal();
            break;

        case 713:
            venderASucursal($args["data"]);
            break;
        case 714:
            nuevoPuesto($args);
            break;

        case 715:
            editarPuesto($args);
            break;
    }
}

