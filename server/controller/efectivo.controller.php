<?php

require_once("model/ingresos.dao.php");
require_once("model/gastos.dao.php");
require_once("model/gastos_fijos.dao.php");
require_once("model/ventas.dao.php");
require_once("model/sucursal.dao.php");
require_once("model/autorizacion.dao.php");
require_once("model/pagos_compra.dao.php");
require_once("model/compra_sucursal.dao.php");
require_once("model/prestamo_sucursal.dao.php");
require_once("model/pago_prestamo_sucursal.dao.php");

require_once('logger.php');

/**
 *
 * 	listarGastosPorSucursal
 *
 *  Regresa un arreglo con objetos Gasto para esta sucursal
 *
 *  @access public
 *  @return array
 * 	@params int [$id_sucursal] sucursal
 * 	
 * */
function listarGastosSucursal($sid = null) {
    if ($sid === null)
        return array();

    $gastos = new Gastos();
    $gastos->setIdSucursal($sid);

    Logger::log("Listando gastos de la sucursal : {$sid}.");
    return GastosDAO::search($gastos);
}

/**
 *
 * 	listarIngresosPorSucursal
 *
 *  Regresa un arreglo con objetos Ingresos para esta sucursal
 *
 *  @access public
 *  @return array
 * 	@params int [$id_sucursal] sucursal
 * 	
 * */
function listarIngresosSucursal($sid = null) {
    if ($sid === null)
        return array();

    $Ingresos = new Ingresos( );
    $Ingresos->setIdSucursal($sid);

    return IngresosDAO::search($Ingresos);
}

/**
 *
 * 	nuevoGasto
 *
 * 	Esta funcion nos regresa un JSON el resultado de la operacion de guardado de un gasto en una sucursal
 *
 * @access public
 * @return json con el resultado del guardado
 * 	@params args array
 * @see GastosDAO::save() 
 * 	
 * */
function nuevoGasto($args) { //600
    if (!isset($args['data'])) {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    $data = parseJSON($args['data']);

    //por lo menos, concepto y monto
    if (!isset($data->concepto) || !isset($data->monto)) {
        die('{"success": false, "reason": "Faltan parametros." }');
    }

    if (!is_numeric($data->monto)) {
        die('{ "success" : "false" , "reason" : "El monto no es una cantidad valida." }');
    }

    $gasto = new Gastos();

    $gasto->setFolio($data->folio);
    $gasto->setConcepto($data->concepto);
    $gasto->setMonto($data->monto);
    $gasto->setNota($data->nota);

    if (isset($data->fecha)) {
        $gasto->setFechaIngreso($data->fecha);
    }

    $gasto->setIdSucursal($_SESSION['sucursal']);
    $gasto->setIdUsuario($_SESSION['userid']);

    try {
        if (GastosDAO::save($gasto) > 0) {
            printf('{ "success" : "true" }');
        } else {
            die('{"success": false, "reason": "No se guardo la el gasto." }');
        }
    } catch (Exception $e) {
        die('{"success": false, "reason": "No se pudo guardar el gasto. ' . $e . ' " }');
    }
}

/**
 *
 * 	eliminarGasto
 *
 * 	Esta funcion nos regresa un JSON el resultado de la operacion de borrado de un gasto en una sucursal
 *
 *       @access public
 *       @return json con el resultado del borrado
 * 	@params int [$idGasto] id del gasto a eliminar
 * 	@see GastosDAO::search(), GastosDAO::delete() 
 * 	
 */
function eliminarGasto($args) {

    if (!isset($args['id_gasto'])) {
        die('{ "success" : "false" , "reason" : "Faltan datos" }');
    }

    try {
        $gasto = GastosDAO::getByPK($args['id_gasto']);

        if (is_null($gasto)) {
            die('{ "succes" : "false" , "reason" : "El gasto que desea eliminar no existe."}');
        }

        if (GastosDAO::delete($gasto) > 0) {
            printf('{ "succes" : true }');
        } else {
            die('{ "succes" : "false" , "reason" : "No se pudo eliminar el gasto."}');
        }
    } catch (Exception $e) {
        die('{ "succes" : "false" , "reason" : "Error al intentar borrar el gasto."}');
    }
}

/**
 *
 * 	actualizarGasto
 *
 *
 * 	Esta funcion nos regresa un JSON el resultado de la operacion de actualizado de un gasto en una sucursal
 *
 *       @access public
 *       @return json con el resultado de la actualizacion
 * 	@params int [$idGasto] id del gasto a eliminar
 * 	@params String [$concepto] cadena que indica la causa de el gasto
 * 	@params float [$monto] cantidad que se gasto
 * 	@params timestamp [$fecha] fecha en que se realizo el gasto
 * 	@see GastosDAO::save() , GastosDAO::search()

 * 	
 */
function actualizarGasto($args) {//602
    if (!isset($args['data'])) {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    $data = parseJSON($args['data']);


    if (!( $gasto = GastosDAO::getByPK($data->id_gasto) )) {
        die('{"success": false, "reason": "No se tiene registro de ese gasto." }');
    }

    if (isset($data->folio)) {
        $gasto->setFolio($data->folio);
    }

    if (isset($data->concepto)) {
        $gasto->setConcepto($data->concepto);
    }

    if (isset($data->monto)) {
        $gasto->setMonto($data->monto);
    }

    if (isset($data->fecha)) {
        $gasto->setFechaIngreso($data->fecha);
    }

    try {
        if (GastosDAO::save($gasto) > 0) {
            printf('{ "succes" : true }');
        } else {
            die('{"success": false, "reason": "No se pudo actualizar el gasto, no ha modificado ningun valor." }');
        }
    } catch (Exception $e) {
        die('{ "succes" : "false" , "reason" : "Error al intentar actualizar el gasto."}');
    }
}

/**
 *
 * 	nuevoIngreso
 *
 * 	Esta funcion nos regresa un JSON el resultado de la operacion de guardado de un Ingreso en una sucursal
 *
 *       @access public
 *       @return json con el resultado del guardado
 * 	@params String [$concepto] cadena que indica la causa de el Ingreso
 * 	@params float [$monto] cantidad que se Ingreso
 * 	@params timestamp [$fecha] fecha en que se realizo el Ingreso
 * 	@see IngresosDAO::save() 
 * 	
 */
function nuevoIngreso($args) { //603
    if (!isset($args['data'])) {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    $data = parseJSON($args['data']);

    if (!isset($data->concepto) || !isset($data->monto) || !isset($data->fecha)) {
        die('{"success": false, "reason": "Faltan parametros." }');
    }

    if (!is_numeric($data->monto)) {
        die('{ "success" : "false" , "reason" : "No es una cantidad valida." }');
    }

    $ingreso = new Ingresos();

    $ingreso->setConcepto($data->concepto);
    $ingreso->setMonto($data->monto);
    $ingreso->setFechaIngreso($data->fecha);
    $ingreso->setIdSucursal($_SESSION['sucursal']);
    $ingreso->setIdUsuario($_SESSION['userid']);
    //TODO: descomentar esta linea cuando este el nuevo DAO que incluya la nota
    $ingreso->setNota($data->nota);

    try {
        if (IngresosDAO::save($ingreso) > 0) {
            printf('{ "success" : "true" }');
        } else {
            die('{"success": false, "reason": "No se guardo el Ingreso." }');
        }
    } catch (Exception $e) {
        die('{"success": false, "reason": "No se pudo guardae el Ingreso. ' . $e . ' " }');
    }
}

/**
 *
 * 	eliminarIngreso
 *
 * 	Esta funcion nos regresa un JSON el resultado de la operacion de borrado de un Ingreso en una sucursal
 *
 *       @access public
 *       @return json con el resultado del borrado
 * 	@params int [$ingreso] id del Ingreso a eliminar
 * 	@see IngresosDAO::search(), IngresosDAO::delete() 
 * 	
 */
function eliminarIngreso($args) {//604
    if (!isset($args['id_ingreso'])) {
        die('{ "success" : "false" , "reason" : "Faltan datos" }');
    }

    try {
        $ingreso = IngresosDAO::getByPK($args['id_ingreso']);

        if (is_null($ingreso)) {
            die('{ "succes" : "false" , "reason" : "El gasto que desea eliminar no existe."}');
        }

        if (GastosDAO::delete($ingreso) > 0) {
            printf('{ "succes" : true }');
        } else {
            die('{ "succes" : "false" , "reason" : "No se pudo eliminar el gasto."}');
        }
    } catch (Exception $e) {
        die('{ "succes" : "false" , "reason" : "Error al intentar borrar el gasto."}');
    }
}

/**
 *
 * 	actualizarIngreso
 *
 *
 * 	Esta funcion nos regresa un JSON el resultado de la operacion de actualizado de un Ingreso en una sucursal
 *
 *       @access public
 *       @return json con el resultado de la actualizacion
 * 	@params int [$idIngreso] id del Ingreso a eliminar
 * 	@params String [$concepto] cadena que indica la causa de el Ingreso
 * 	@params float [$monto] cantidad que se Ingreso
 * 	@params timestamp [$fecha] fecha en que se realizo el Ingreso
 * 	@see IngresosDAO::save() , IngresosDAO::search()

 * 	
 */
function actualizarIngreso($args) {//605
    if (!isset($args['data'])) {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    $data = parseJSON($args['data']);

    if (!( $ingreso = GastosDAO::getByPK($data->id_gasto) )) {
        die('{"success": false, "reason": "No se tiene registro de ese ingreso." }');
    }

    if (isset($data->concepto)) {
        $ingreso->setConcepto($data->concepto);
    }

    if (isset($data->monto)) {
        $ingreso->setMonto($data->monto);
    }

    if (isset($data->fecha)) {
        $ingreso->setFechaIngreso($data->fecha);
    }

    try {
        if (GastosDAO::save($ingreso) > 0) {
            printf('{ "succes" : true }');
        } else {
            die('{"success": false, "reason": "No se pudo actualizar el ingreso, no ha modificado ningun valor." }');
        }
    } catch (Exception $e) {
        die('{ "succes" : "false" , "reason" : "Error al intentar actualizar el ingreso."}');
    }
}

/**
 *
 * 	nuevoAbono
 *
 * 	Esta funcion nos regresa un JSON el resultado de la operacion de guardado de un abono en una sucursal
 *
 *  @access public
 *  @return json con el resultado del guardado
 * 	@params String [$concepto] cadena que indica la causa de el gasto
 * 	@params float [$monto] cantidad que se gasto
 * 	@params timestamp [$fecha] fecha en que se realizo el gasto
 * 	@see GastosDAO::save() 
 * 	
 * */
function nuevoAbono($args) { //606
    if (!isset($_SESSION['monto'])) {
        die('{ "succes" : "false" , "reason" : "Faltan parametros."}');
    }

    doNuevoAbono($args['monto']);

    //si todo salio bien llegara hasta aqui nuevamenten
    printf('{ "success": true}');
}

function doNuevoAbono($monto) {




    /*

      1.-Tratar de obtener la compra mas antigua sin liquidar
      si no se encontro:
      GOTO 2;
      si se encontro:
      la diferencia entre el monto y el saldo de la compra mas antigua es <= 0?
      si:
      GOTO 3
      no:
      GOTO 4

      2.- Agregar el monto del abono directamente al saldo a favor de la sucursal
      GOTO 5;

      3.- Sumar a la compra mas antigua en el campo de pagado el monto
      GOTO 5

      4.-
      a)Liquidar la compra mas antigua
      b)Igualar a monto con el resultado de la diferencia anterior
      c)GO TO 1

      5.- End

     */


    //obtenemos todas las compras (de al mas antigua a la mas reciente)
    $c = new CompraSucursal();
    $c->setLiquidado('0');

    $compras = CompraSucursalDAO::search($c);
    //echo sizeof($compras)."  eee";
    $compra_a_abonar = null;

    $found = false;

    //obtenemos la compra mas reciente
    foreach ($compras as $compra) {
        $found = true;
        $compra_a_abonar = $compra;
        $saldo_compra = $compra->getTotal() - $compra->getPagado();
        break;
    }//foreach

    if (!$found) {
        //si no se encontro una compra sin liquidar se abona el monto directamente al saldo a favor de la sucursal
        $sucursal = SucursalDAO::getByPK($_SESSION['sucursal']);
        $sucursal->setSaldoAFavor($sucursal->getSaldoAFavor() + monto);

        try {
            if (!( SucursalDAO::save($sucursal) > 0 )) {
                die('{ "succes" : "false" , "reason" : "No se registro el nuevo abono, no cambio el saldo a  favor en la sucursal."}');
            }//if
            //si llegas aqui se abono directamente al saldo a favor de la sucursal
            //FIN DE LAS OPERACIONES
            return;
        } catch (Exception $e) {
            die('{ "succes" : "false" , "reason" : "' . $e . '"}');
        }//catch
    }//if


    /*

      si llega aqui es por que se encontro almenos una cuenta sin liquidar

     */


    $saldo = $monto - $saldo;
    /*
      si $saldo < 0 : el monto no liquida la cuenta
      si $saldo = 0 : si liquida la cuenta
      si $saldo > 0 : si liquida la cuenta y ademas sobra dinero
     */




    //actualizamos el campo de pagado en la compra
    $compra_a_abonar->setPagado($compra_a_abonar->getPagado() + $monto);

    if ($saldo >= 0) {
        //significa que la compra se liquido y hay que cambia su estado liquidada
        $compra_a_abonar->setLiquidado(1);
    }//if

    try {
        //intentamos guardar los cambios
        if (!( CompraSucursalDAO::save($compra_a_abonar) > 0 )) {
            die('{ "success" : "false" , "reason" : "No se registro el nuevo abono"}');
        }

        //ya que se actualizo el saldo de la compra, insertamos el abono en abonos compra

        $nuevo_abono = new PagosCompra();
        $nuevo_abono->setIdCompra($compra_a_abonar->getIdCompra());
        $nuevo_abono->setMonto($monto);

        try {
            //intentamos guardar el abono
            if (!( PagosCompraDAO::save($nuevo_abono) > 0 )) {
                die('{ "success" : "false" , "reason" : "No se registro el nuevo abono en pagos compra"}');
            }

            //si se registro el abono entonces verificamos que haya sobrado dinero para abonarlo y repetir el algoritmo
            if ($saldo <= 0) {
                //FIN DE LAS OPERACIONES
                return;
            } else {
                //llamamos nuevamente a este metodo y le pasamos el sobrante
                doNuevoAbono($saldo);
            }
        } catch (Exception $e) {
            die('{ "succes" : "false" , "reason" : "' . $e . '"}');
        }
    } catch (Exception $e) {
        die('{ "success" : "false" , "reason" : "Exception: No se registro el nuevo abono"}');
    }//catch
}

/**
 *   Nuevo Prestamo a Sucursal
 *
 *
 */
function nuevoPrestamoSucursal($args) {

    Logger::log("Iniciando proceso de prestamo de efectivo a sucursal");

    if (!isset($args['data'])) {
        Logger::log("Sin parametros para realizar el prestamo");
        die('{"success": false, "reason": "No hay parametros para realizar el prestamo." }');
    }

    try {
        $data = parseJSON($args['data']);
    } catch (Exception $e) {
        Logger::log("json invalido para realizar el prestamo : " . $e);
        die('{"success": false, "reason": "Parametros invalidos, verifique sus datos.." }');
    }

    if ($data == null) {
        Logger::log("el parseo del json del nuevo prestamo a sucursal en un objeto nulo");
        die('{"success": false, "reason": "Parametros invalidos, verifique sus datos.." }');
    }

    //verificamos que se manden todos los parametros necesarios
    if (!( isset($data->sucursal) && isset($data->concepto) && isset($data->monto) )) {
        Logger::log("Falta uno o mas parametros");
        die('{"success": false, "reason": "Verifique sus datos, falta uno o mas parametros." }');
    }

    //validamos el monto
    if (!( is_numeric($data->monto) && $data->monto > 0 )) {
        Logger::log("Verifique el monto");
        die('{"success": false, "reason": "Verifique el monto." }');
    }

    //verificamos que la sucursal exista
    if (!( SucursalDAO::getByPK($data->sucursal) )) {
        Logger::log("No se tienen registros de la scursal {$data->sucursal}");
        die('{"success": false, "reason": "No se tiene registros de la sucursal." }');
    }

    $prestamo_sucursal = new PrestamoSucursal();

    $prestamo_sucursal->setPrestamista($_SESSION ['sucursal']);
    $prestamo_sucursal->setDeudor($data->sucursal);
    $prestamo_sucursal->setMonto($data->monto);
    $prestamo_sucursal->setSaldo($data->monto);
    $prestamo_sucursal->setLiquidado(0);
    $prestamo_sucursal->setConcepto($data->concepto);

    DAO::transBegin();

    try {
        PrestamoSucursalDAO::save($prestamo_sucursal);
    } catch (Exception $e) {
        Logger::log($e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudo registrar el nuevo prestamo a sucursal." }');
    }


    $empleado = UsuarioDAO::getByPK($_SESSION['userid']);

    $sucursal_origen = SucursalDAO::getByPK($_SESSION ['sucursal']);

    $sucursal_destino = SucursalDAO::getByPK($data->sucursal);

    DAO::transEnd();
    Logger::log("Terminado proceso de prestamo de efectivo a sucursal");


    printf('{ "success" : true, "id_prestamo": %s , "empleado" : "%s", "sucursal_origen" : %s, "sucursal_destino" : %s }', $prestamo_sucursal->getIdPrestamo(), $empleado->getNombre(), $sucursal_origen, $sucursal_destino);
}

//nuevoPrestamoSucursal

/**
 *   Aboanr a Prestamo de Sucursal
 *
 */
function abonarPrestamoSucursal($args) {

    Logger::log("Iniciando proceso de abonar a prestamo sucursal.");

    //verificamos que vengas todos los valores necesarios
    if (!( isset($args['monto']) && isset($args['id_prestamo']) )) {
        Logger::log("Error: Falta uno o mas parametros");
        die('{"success": false, "reason": "Verifique sus datos, falta uno o mas parametros." }');
    }

    //verificamos que el monto sea valido
    if (!( is_numeric($args['monto']) && $args['monto'] > 0 )) {
        Logger::log("Error: Verifique el valor del monto");
        die('{"success": false, "reason": "Verifique el monto." }');
    }

    //validamos que exista el prestamo
    if (!( $prestamo = PrestamoSucursalDAO::getByPK($args['id_prestamo']) )) {
        Logger::log("Error: No se tiene registro del prestamo {$args['id_prestamo']}.");
        die('{"success": false, "reason": "No se tiene registro del prestamo ' . $args['id_prestamo'] . '." }');
    }

    //validamos que no este saldada al deuda
    if ($prestamo->getLiquidado() == "1") {
        Logger::log("Error: La deusa del prestamo  {$args['id_prestamo']} ya esta saldada.");
        die('{"success": false, "reason": "No se registro el abono debido a que la deusa del prestamo ' . $args['id_prestamo'] . ' ya estaba saldada." }');
    }

    //validamos que no se abone mas de lo que se debe
    $saldo = $prestamo->getSaldo();
    $abono = $args['monto'];

    if ($abono > $saldo) {
        $abono = $saldo;
    }

    //registramos el nuevo abono
    $pago_prestamo = new PagoPrestamoSucursal();
    $pago_prestamo->setIdPrestamo($prestamo->getIdPrestamo());
    $pago_prestamo->setIdUsuario($_SESSION['userid']);
    $pago_prestamo->setMonto($abono);

    DAO::transBegin();

    //intentamos guardar en nuevo abono
    try {
        PagoPrestamoSucursalDAO::save($pago_prestamo);
    } catch (Exception $e) {
        Logger::log($e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudo registrar el nuevo abono a prestamo." }');
    }

    //modificamos el saldo del prestamo
    $prestamo->setSaldo($saldo - $abono);

    //verificamos si se salda
    if ($prestamo->getSaldo() <= 0) {
        $prestamo->setLiquidado(1);
    }

    //intentamos guardar el nuevo saldo
    try {
        PrestamoSucursalDAO::save($prestamo);
    } catch (Exception $e) {
        Logger::log($e);
        DAO::transRollback();
        die('{"success": false, "reason": "No se pudo registrar el nuevo abono a prestamo." }');
    }

    DAO::transEnd();
    Logger::log("Termiando proceso de abonar a prestamo sucursal.");
    printf('{ "success" : true }');
}

//abonarPrestamoSucursal

/**
 *   Listar Prestamos de Sucursal
 *
 */
function listarPrestamosSucursal($args) {

    //obtenemos todas las sucursales
    $sucursales = SucursalDAO::getAll();

    //obtenemos todas las ventas a credito que no estan liquidadas
    $prestamos_sucursal = new PrestamoSucursal();
    $prestamos_sucursal->setLiquidado("0");
    $prestamos_sucursal = PrestamoSucursalDAO::search($prestamos_sucursal);

    $prestamos = array();

    foreach ($prestamos_sucursal as $prestamo) {

        //TODO: Cuando esto qeude listo hay qeu filtrar la sucursal actual

        $sucursal = SucursalDAO::getByPK($prestamo->getDeudor());

        array_push($prestamos, array(
            "id_prestamo" => $prestamo->getIdPrestamo(),
            "prestamista" => $prestamo->getPrestamista(),
            "deudor" => $prestamo->getDeudor(),
            "sucursal" => $sucursal->getDescripcion(),
            "concepto" => $prestamo->getConcepto(),
            "monto" => $prestamo->getMonto(),
            "saldo" => $prestamo->getSaldo(),
            "liquidado" => $prestamo->getLiquidado()
        ));
    }

    return $prestamos;
}

//listarPrestamosSucursal

/**
 *   Listar Prestamos de Sucursal
 *
 */
function listarGastosFijos() {

    $gastos_fijos = GastosFijosDAO::getAll();

    $array_gastos_fijos = array();

    foreach ($gastos_fijos as $gasto_fijo) {
        array_push($array_gastos_fijos, array(
            'text' => $gasto_fijo->getNombre(),
            'value' => $gasto_fijo->getDescripcion()
        ));
    }

    return $array_gastos_fijos;
}

/**
 * Crea un nuevo gasto en la base de datos
 * @param <type> $args 
 */
function definirNuevoGasto($args) {
    
    $gasto = new GastosFijos();

    if (!isset($args['data'])) {
        die('{"success":false,"reason":"No se enviaron los parametros"}');
    }

    $json = parseJSON($args['data']);

    $gasto->setNombre($json->nombre);
    $gasto->setDescripcion($json->descripcion);

    try {
        GastosFijosDAO::save($gasto);
    } catch (Exception $e) {
        Logger::log($e);
        die('{"success":false,"reason":"No se pudo registrar el nuevo gasto : ' . $e . '"}');
    }

    echo '{"success" : true}';

    return true;
}

/**
 * Edita la definicion de un gasto en la base de datos
 * @param <type> $args
 */
function editarConceptoGasto($args) {

    if(!isset($args['data'])){
        die('{"success":false,"reason":"No se enviaron los parametros"}');
    }

    $json = parseJSON($args['data']);

    if(!($gasto = GastosFijosDAO::getByPK($json->id))){
        die('{"success":false,"reason":"No se pudo editar el concepto del gasto, no se encontro el registro"}');
    }

    $gasto->setNombre($json->nombre);
    $gasto->setDescripcion($json->descripcion);


    try {
        GastosFijosDAO::save($gasto);
    } catch (Exception $e) {
        Logger::log($e);
        die('{"success":false,"reason":"No se pudo modificar el puesto : ' . $e . '"}');
    }

    echo '{"success" : true}';

    return true;
    
}

if (isset($args['action'])) {
    switch ($args['action']) {
        case 600:
            nuevoGasto($args);
            break;

        case 601:
            eliminarGasto($args);
            break;

        case 602:
            actualizarGasto($args);
            break;

        case 603:
            nuevoIngreso($args);
            break;

        case 604:
            eliminarIngreso($args);
            break;

        case 605:
            actualizarIngreso($args);
            break;

        case 606:
            nuevoAbono($args);
            break;

        case 607:
            nuevoPrestamoSucursal($args);
            break;

        case 608:
            abonarPrestamoSucursal($args);
            break;

        case 609:
            //printf( '{ "succes" : true, "datos": [%s] }',  json_encode( listarPrestamosSucursal( $args ) ) );


            $json = json_encode(listarPrestamosSucursal($args));

            if (isset($args['hashCheck'])) {
                //revisar hashes
                if (md5($json) == $args['hashCheck']) {
                    return;
                }
            }

            printf('{ "success": true, "hash" : "%s" , "data": %s }', md5($json), $json);


            break;

        case 610:
            printf('{ "success": true, "data": %s }', json_encode(listarGastosFijos()));
            break;

        case 611:
            definirNuevoGasto($args);
            break;

        case 612:
            editarConceptoGasto($args);
            break;

        default:
            printf('{ "success" : "false" }');
            break;
    }//switch
}
?>
