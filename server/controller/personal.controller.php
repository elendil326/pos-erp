<?php

/**
 * 	Controller para personal
 */
require_once('model/usuario.dao.php');
require_once('model/grupos_usuarios.dao.php');
require_once('model/grupos.dao.php');
require_once('model/sucursal.dao.php');
require_once('logger.php');

/**
 * Funcion para insertar usuarios a la base de datos incluyendo permisos de acceso.
 *  
 * @param $rfc Registro federal de contribuyentes del empleado.
 * @param $nombre Nombre del empleado.
 * @param $pwd Password de la cuenta del empleado.
 * @param $sucursal Id de la sucursal a la cual pertenece el emplado.
 * @param $salario Salario mensual que percibe el empleado.
 * @param $acceso Permisos que tiene sobre el sistema el empleado.
 * @param $grupo
 * @return cadena en formato JSON que informa si la operacion se realizo con exito. { "success" : true } o { "success" : false }.
 * */
function insertarEmpleado($args) {

    if (!isset($args['data'])) {
        Logger::log("No hay parametros para insertar nuevo empleado.");
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    $data = parseJSON($args['data']);

    if ($data == NULL) {
        Logger::log("JSON invalido :" . $args['data']);
        die('{"success": false, "reason": "Parametros invalidos." }');
    }


    //validar que vengan todos los datos
    if (!(isset($data->RFC) &&
            isset($data->nombre) &&
            isset($data->contrasena) &&
            isset($data->salario) &&
            isset($data->telefono) &&
            isset($data->direccion) &&
            isset($data->grupo) )) {
        Logger::log("Faltan parametros para insertar empleado: JSON:" . $args['data']);
        die('{"success": false, "reason": "Parametros invalidos." }');
    }


    if ($data->grupo <= 3 && $data->grupo > 0) {
        if (strlen($data->contrasena) < 5) {
            die('{"success": false, "reason": "Contraseña debe ser de por lo menos 5 caracteres." }');
        }
    }


    if ($data->salario < 0) {
        die('{"success": false, "reason": "No puede asignar un salario negativo." }');
    }

    if ($data->salario > 10000) {
        die('{"success": false, "reason": "No puede asignar un salario mayor a $10,000.00." }');
    }

    if (strlen($data->nombre) < 10) {
        die('{ "success" : false, "reason" : "El nombre debe ser cuando menos de 10 caracteres."}');
    }

    if (strlen($data->RFC) < 10) {
        die('{ "success" : false, "reason" : "El RFC es muy corto."}');
    }

    DAO::transBegin();

    $user = new Usuario();
    $user->setRFC($data->RFC);

    try {
        $us = UsuarioDAO::search($user);
    } catch (Exception $e) {
        Logger::log("Error buscando usuario : " . $e);
        DAO::transRollback();
        die('{ "success" : false, "reason": "Parametros invalidos." }');
    }

    //buscar que no exista ya un empleado con este RFC
    if (count($us) > 0) {
        foreach ($us as $u) {
            $id = $u->getIdUsuario();
            break;
        }
        Logger::log("Ya existe un empleado con el rfc:" . $data->RFC);
        DAO::transRollback();
        die('{"success": false, "id":"' . $id . '", "reason": "Ya existe un empleado con este RFC." }');
    }

    $user->setRFC(strtoupper($data->RFC));
    $user->setNombre(strtoupper($data->nombre));
    $user->setContrasena($data->contrasena);
    //$user->setOnline(0);
    //si soy admin ponerle el que mando, de lo contrario, soy gerente, poner mi sucursal
    if ($_SESSION['grupo'] == 1 || $_SESSION['grupo'] == 0) {
        if (isset($data->sucursal)) {

            if (sizeof(SucursalDAO::getByPK($data->sucursal)) == 1) {
                $user->setIdSucursal($data->sucursal);
            } else {
                DAO::transRollback();
                die('{ "success" : false, "reason": "Esta sucursal no existe." }');
            }
        }else
            $user->setIdSucursal(null);
    }else {
        $user->setIdSucursal($_SESSION['sucursal']);
    }


    $user->setActivo(1);
    $user->setSalario($data->salario == null ? 0 : $data->salario);
    $user->setTelefono($data->telefono == null ? 0 : strtoupper($data->telefono));
    $user->setDireccion($data->direccion == null ? 0 : strtoupper($data->direccion));
    $now = new DateTime("now");
    $user->setFechaInicio($now->format('Y-m-d'));

    $gruposUsuarios = new GruposUsuarios();
    $gruposUsuarios->setIdGrupo($data->grupo);

    try {
        UsuarioDAO::save($user);
        $gruposUsuarios->setIdUsuario($user->getIdUsuario());
        GruposUsuariosDAO::save($gruposUsuarios);
    } catch (Exception $e) {

        DAO::transRollback();
        Logger::log("Error al guardar usuario : {$e}.");
        die('{ "success" : false, "reason" : "Grupo Inexistente"}');
    }


    printf(' { "success" : true, "id_usuario": %s } ', $user->getIdUsuario());
    Logger::log("Empleado insertado correctamente.");
    DAO::transEnd();
}

/**
 * Función para obtener una lista de usuarios en una sucursal.
 *  
 * @param $id_sucursal Id de la cursal a la cual se desea listar sus empleados.
 * @param $activo indica si se desea listar a usuarios activos o inactivos.
 * @return cadena en formato JSON que contiene los datos de los empleados.
 * */
function listarEmpleados($sid = null, $all = null) {




    $empleados = new Usuario();
    if ($sid !== null) {
        $empleados->setIdSucursal($sid);
        $empleados->setActivo("1");
    }

    if ($all == null) {
        $empleados->setActivo("1");
    }


    $empleados = UsuarioDAO::search($empleados);


    $empleadosArray = array();

    foreach ($empleados as $e) {

        //si es el gerente, a la verga

        $foo = $e->asArray();

        $grupo = new GruposUsuarios();
        $grupo->setIdUsuario($e->getIdUsuario());

        $searchGrupo = GruposUsuariosDAO::search($grupo);

        if (count($searchGrupo) == 0) {
            //no esta asignado
            $foo['puesto'] = "No asignado";
            $foo['_activo'] = $foo['activo'] == "1" ? "Si" : "No";
        } else {

            if ($searchGrupo[0]->getIdGrupo() <= 1) {
                //no motrar administradores ni gerentes
                continue;
            }
            $foo['tipo'] = $searchGrupo[0]->getIdGrupo();

            $descripcion = GruposDAO::getByPK($searchGrupo[0]->getIdGrupo());

            if($descripcion != null){
                $descripcion = $descripcion->getDescripcion();
            }else{
                $descripcion = "Indefinido";
            }

            $foo['puesto'] = $descripcion;
            $foo['_activo'] = $foo['activo'] == "1" ? "Si" : "No";
        }


        array_push($empleadosArray, $foo);
    }

    return $empleadosArray;
}

function listarGerentes($asignados = null) {
    $array_empleados = array();


    //todos
    if ($asignados === null) {

        //todos los gerentes
        $gru1 = new GruposUsuarios();
        $gru1->setIdGrupo('2');
        $result = GruposUsuariosDAO::search($gru1);

        foreach ($result as $r) {
            $gerente = UsuarioDAO::getByPK($r->getIdUsuario());
            if ($gerente->getActivo() == "0")
                continue;

            $suc = new Sucursal();
            $suc->setActivo(1);
            $suc->setGerente($r->getIdUsuario());

            //buscar una sucursal con este gerente
            $tSuc = SucursalDAO::search($suc);
            if (count($tSuc) == 0) {
                //no es gerente
                $gerente = $gerente->asArray();
                $gerente['gerencia_sucursal_desc'] = null;
                $gerente['gerencia_sucursal_id'] = null;
                $gerente['contrasena'] = null;

                array_push($array_empleados, $gerente);
            } else {
                //si es gerente
                $gerente = $gerente->asArray();
                $gerente['gerencia_sucursal_desc'] = SucursalDAO::getByPK($tSuc[0]->getIdSucursal())->getDescripcion();
                $gerente['gerencia_sucursal_id'] = $tSuc[0]->getIdSucursal();
                $gerente['contrasena'] = null;
                array_push($array_empleados, $gerente);
            }
        }

        return $array_empleados;
    }




    //asignados o no asignados
    if ($asignados) {

        $suc = new Sucursal();
        $suc->setActivo(1);
        $sucursales = SucursalDAO::search($suc);

        foreach ($sucursales as $s) {
            $gerenteSuc = $s->getGerente();

            if ($gerenteSuc != null) {

                $data = UsuarioDAO::getByPK($gerenteSuc);

                if (!$data) {
                    Logger::log("ERROR: al buscar al usuario ( " . $gerenteSuc . " )");
                    continue;
                } else {
                    $data = $data->asArray();
                }

                if ($data['activo'] == 0)
                    continue;
                $data['gerencia_sucursal_desc'] = SucursalDAO::getByPK($s->getIdSucursal())->getDescripcion();
                $data['gerencia_sucursal_id'] = $s->getIdSucursal();
                $data['contrasena'] = null;

                array_push($array_empleados, $data);
            }
        }
    }else {
        //no asignados
        $gru1 = new GruposUsuarios();
        $gru1->setIdGrupo('2');
        $result = GruposUsuariosDAO::search($gru1);

        foreach ($result as $r) {

            $suc = new Sucursal();
            $suc->setActivo(1);
            $suc->setGerente($r->getIdUsuario());

            //buscar una sucursal con este gerente
            if (count(SucursalDAO::search($suc)) < 1) {
                $gerente = UsuarioDAO::getByPK($r->getIdUsuario());
                if ($gerente->getActivo() == "0")
                    continue;
                $gerente->setContrasena(null);
                array_push($array_empleados, $gerente);
            }
        }
    }



    return $array_empleados;
}

/**
 * Modifica los datos de un empleado.
 *  
 * @param $id_usuario Id del usuarioq eu se desea modificar.
 * @param $rfc Registro federal de contribuyentes del empleado.
 * @param $nombre Nombre del empleado.
 * @param $password Password de la cuenta del empleado.
 * @param salario Salario mensual que percibe el empleado.
 * @return cadena en formato JSON que informa si la operacion se realizo con exito. { "success" : "true", "info" :"mensaje"  } o { "success" : "false", "reason" : "mensaje"}
 * */
function modificarEmpleado($args) {



    if (!isset($args['data'])) {
        Logger::log("No hay parametros para modificar empleado.");
        die('{"success": false, "reason": "Parametros invalidos." }');
    }


    $data = parseJSON($args['data']);


    if ($data === null) {
        die('{"success": false, "reason": "Parametros invalidos." }');
    }


    if (!isset($data->id_usuario)) {
        Logger::log("No se ha enviado id_usuario");
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    $usuario = UsuarioDAO::getByPK($data->id_usuario);

    if ($usuario == null) {
        Logger::log("Empleado a modificar no existe.");
        die('{"success": false, "reason": "Este usuario no existe." }');
    }


    //validar datos nuevos
    if (isset($data->RFC)) {
        if (strlen($data->RFC) < 10) {
            Logger::log("El nuevo RFC es muy corto");
            die('{"success": false, "reason": "El nuevo RFC es muy corto." }');
        }

        $usuario->setRFC($data->RFC);
    }


    //posible error
    if (isset($data->rfc)) {
        Logger::log("Se envio parametros RFC en minisculas");
        die('{"success": false, "reason": "El parametros invalidos." }');
    }

    if (isset($data->nombre)) {
        if (strlen($data->nombre) < 10) {
            Logger::log("El nuevo nombre es muy corto");
            die('{"success": false, "reason": "El nuevo nombre es muy corto." }');
        }
        $usuario->setNombre($data->nombre);
    }


    if (isset($data->contrasena)) {
        if (strlen($data->contrasena) < 5) {
            die('{"success": false, "reason": "La nueva contrase%26ntilde;a es muy corta." }');
        }
        $usuario->setContrasena($data->contrasena);
    }


    if (isset($data->id_sucursal)) {
        if (SucursalDAO::getByPK($data->id_sucursal) == null) {
            die('{"success": false, "reason": "Esa sucursal no existe." }');
        }
        $usuario->setIdSucursal($data->id_sucursal);
    }


    if (isset($data->activo)) {
        $foo = (int) $data->activo;

        if (!($foo == 1 || $foo == 0)) {
            die('{"success": false, "reason": "Parametros invalidos." }');
        }

        $usuario->setActivo($data->activo);
    }


    if (isset($data->salario)) {
        $foo = (float) $data->salario;
        if ($foo < 0) {
            die('{"success": false, "reason": "No puede establecer un salario negativo." }');
        }
        Logger::log("Establenciendo nuevo salario:" . $data->salario);
        $usuario->setSalario($data->salario);
    }

    if (isset($data->direccion)) {

        $usuario->setDireccion($data->direccion);
    }

    if (isset($data->telefono)) {
        $usuario->setTelefono($data->telefono);
    }



    try {
        UsuarioDAO::save($usuario);
    } catch (Exception $e) {
        Logger::log($e);
        die(' { "success" : false, "reason" : "Error al modificar el empleado." } ');
    }

    if (isset($data->grupo)) {
        // TODO: Arreglar esto apra que solo trabaje 
        // con el id_usuario y solo pueda tener una cuenta a la vez
        //validar que exista el grupo
        if (GruposDAO::getByPK($data->grupo) == null) {
            Logger::log("Grupo {$data->grupo} no existe");
            die(' { "success" : false, "reason" : "Este grupo no existe." } ');
        }


        if (!( $grupoUsuarios = GruposUsuariosDAO::getByPK($usuario->getIdUsuario()) )) {
            //si entro aqui significa que el puesto que se le va a asegnar es 
            // distinto al que ya tenia y por lo tanto hay que eliminar el registro

            $gruposUsuarios = new GruposUsuarios();
            $gruposUsuarios->setIdUsuario($usuario->getIdUsuario());

            $find = GruposUsuariosDAO::search($gruposUsuarios);

            foreach ($find as $f) {
                //eliminamos todos los registros donde este ese usuario enrolado en un puesto	
                GruposUsuariosDAO::delete($f);
            }
        }


        $gruposUsuarios = new GruposUsuarios();
        $gruposUsuarios->setIdGrupo($data->grupo);
        $gruposUsuarios->setIdUsuario($usuario->getIdUsuario());
        GruposUsuariosDAO::save($gruposUsuarios);
    }


    printf(' { "success" : true } ');
}

/**
 * Activa o desactiva la cuenta de un empleado.
 *  
 * @param $id_usuario Id del empleado
 * @param $status indica en nuevo estadod el empleado 1 = activo, 0 = inactivo
 * @return cadena en formato JSON que informa si la operacion se realizo con exito. { "success" : "true", "info" :"mensaje"  } o { "success" : "false", "reason" : "mensaje"}
 * */
//AQUI FALTA QUITARLO DE LA GERENCIA DE UNA SUCURSAL SI ES QUE ES UN GERENTE CON UNA SUCURSAL
function cambiarEstadoEmpleado($args) {

    if (!(isset($args['id_empleado']) &&
            isset($args['activo']))) {
        die(' { "success" : false, "reason" : "Parametros invalidos" }');
    }

    if ($args['activo'] == null) {
        die(' { "success" : false, "reason" : "Parametros invalidos" }');
    }

    if ($usuario = UsuarioDAO::getByPK($args['id_empleado'])) {
        $usuario->setActivo($args['activo']);
    } else {
        die('{ "success" : false, "reason" : "Este empleado no existe." }');
    }

    try {
        UsuarioDAO::save($usuario);
    } catch (Exception $e) {

        die(' { "success" : false, "reason" : "No se pudo modificar el estado del usuario" } ');
    }


    //todo bien, ahora hay que ver que pedo con su gerencia
    Logger::log("cambiando estado de user=" . $args['id_empleado'] . " a " . $args['activo']);

    $suc = new Sucursal();
    $suc->setGerente($args['id_empleado']);
    $res = SucursalDAO::search($suc);

    $msg = "";

    if (count($res) == 1) {
        //es gerente de una sucursal
        $suc = $res[0];
        $suc->setGerente(null);
        try {
            SucursalDAO::save($suc);
        } catch (Exception $e) {
            Logger::log($e);
        }
        $msg = "La sucursal " . $suc->getDescripcion() . " se ha quedado sin gerente.";

        Logger::log("La sucursal " . $suc->getDescripcion() . " se ha quedado sin gerente.", 1);
    }


    $action = ( $args['activo'] == 1) ? 'activado' : 'desactivado';
    printf(' { "success" : true, "info": "La cuenta del empleado se ha %s. %s" } ', $action, $msg);
}

function listarBajoPerfil() {

    $p1 = new Grupos();
    $p1->setIdGrupo('3');
    $p2 = new Grupos();
    $p2->setIdGrupo('100');

    $result = GruposDAO::byRange($p1, $p2);

    $perfiles = array();

    foreach ($result as $r) {
        array_push($perfiles, array(
            'id_grupo' => $r->getIdGrupo(),
            'nombre' => $r->getNombre(),
            'descripcion' => $r->getDescripcion(),
            'text' => $r->getDescripcion(),
            'value' => $r->getIdGrupo()
        ));
    }

    return $perfiles;
}

/**
 * Esta funcion es empleada para que regrese una lista con los posibles responsables de
 * la caja en la sucursal, se emplea para cuando se va a prestar dinero a una sucursal y hacer
 * responsable a una persona por ese dinero.
 */
function listarResponsables($args) {

    if (!isset($args['id_sucursal'])) {
        die('{ "success" : false, "reason" : "Parametros invalidos" }');
    }

    if (sizeof(SucursalDAO::getByPK($args['id_sucursal'])) != 1) {
        die('{ "success" : false, "reason" : "Esta sucursal no existe." }');
    }

    $gr1 = new GruposUsuarios();
    $gr1->setIdGrupo(2);

    $gr2 = new GruposUsuarios();
    $gr2->setIdGrupo(3);

    //obtenemos todos los cajeros y gerentes
    $responsables = GruposUsuariosDAO::byRange($gr1, $gr2);

    $array_responsables = array();

    //los filtramos por sucursal
    foreach ($responsables as $responsable) {

        $empleado = UsuarioDAO::getByPK($responsable->getIdUsuario());
        $descripcion_tipo = GruposDAOBase::getByPK($responsable->getIdGrupo());

        if ($empleado->getIdSucursal() == $args['id_sucursal'] && $empleado->getActivo()) {
            array_push($array_responsables, array(
                'text' => $empleado->getNombre(),
                'value' => $empleado->getIdUsuario(),
                'id_sucursal' => $empleado->getIdSucursal(),
                'activo' => $empleado->getActivo(),
                'descripcion' => $descripcion_tipo->getIdGrupo()
            ));
        }
    }

    return $array_responsables;
}

function editarGerencias($data) {

    if (!isset($data['data'])) {
        Logger::log("Parametros invalidos para editar gerencias");
        die('{ "success" : false, "reason" : "Parametros invalidos" }');
    }

    $sucursales = parseJSON($data['data']);

    foreach ($sucursales as $sucursalData) {

        $id_gerente = $sucursalData->id_gerente;
        $id_sucursal = $sucursalData->id_sucursal;

        $sucursal = SucursalDAO::getByPK($id_sucursal);
        $oldGerente = $sucursal->getGerente();


        //asignar el nuevo gerente a la sucursal
        if ($id_gerente == -1) {
            $sucursal->setGerente(null);
        } else {
            $gerente = UsuarioDAO::getByPK($id_gerente);
            $sucursal->setGerente($id_gerente);
        }


        try {
            SucursalDAO::save($sucursal);
        } catch (Exception $e) {

            Logger::log($e);
            return array('success' => false, 'reason' => $e);
        }


        /*
          //quitar al anterior gerente de esa sucursal
          $oldGerente = UsuarioDAO::getByPK( $oldGerente );
          if($oldGerente){
          $oldGerente->setIdSucursal(null);

          try{
          UsuarioDAO::save($oldGerente);
          }catch(Exception $e){
          return array('success' => false, 'reason' => $e);
          }
          }
         */
    }


    //ya esta todo hecho en la tabla de sucursales,
    //ahora hay que sincronizar el campo de sucursal en usuario
    //buscar todos los gerentes
    $tGerentes = listarGerentes();

    //ver si tienes sucursal a su cargo
    //y asignarsela, de lo contrario asignarle null
    //en id_sucursal
    foreach ($tGerentes as $ger) {

        $gerente = UsuarioDAO::GetByPK($ger['id_usuario']);

        $suc = new Sucursal();
        $suc->setGerente($ger['id_usuario']);
        $sucursal = SucursalDAO::search($suc);

        if (sizeof($sucursal) == 0) {
            $gerente->setIdSucursal(null);
        } else {
            $gerente->setIdSucursal($sucursal[0]->getIdSucursal());
        }

        try {
            if (!UsuarioDAO::save($gerente)) {
                //return array('success' => false, 'reason' => $gerente->getIdUsuario());
            }
        } catch (Exception $e) {
            return array('success' => false, 'reason' => $e);
        }
    }

    Logger::log("gerencias editadas satisfactoriamente");
    return array('success' => true);
}


if (isset($args['action'])) {


    switch ($args['action']) {


        case 500:
            insertarEmpleado($args);
            break;

        case 501:

            if (isset($_SESSION['sucursal'])) {
                $listaEmpleados = listarEmpleados($_SESSION['sucursal']);
            } else {
                $listaEmpleados = listarEmpleados();
            }
            if ($listaEmpleados !== null)
                printf('{"success": true, "empleados": %s}', json_encode($listaEmpleados));
            else
                printf('{"success": false, "reason": "Intente de nuevo."}');
            break;

        case 502:
            modificarEmpleado($args);
            break;

        case 503:
            cambiarEstadoEmpleado($args);
            break;

        case 504:
            $listaBajoPerfil = listarBajoPerfil();
            printf('{"success": true, "datos": %s}', json_encode($listaBajoPerfil));
            break;

        case 505:
            $listaResponsables = listarResponsables($args);
            printf('{"success": true, "datos": %s}', json_encode($listaResponsables));
            break;

        case 506: //editarGerencias
            echo json_encode(editarGerencias($args));
            break;

        default:
            printf('{ "success" : "false" }');
            break;
    }
}
?>
