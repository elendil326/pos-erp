<?php

/**
 *	Controller para personal
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
 * @return cadena en formato JSON que informa si la operacion se realizo con exito. { "success" : true } o { "success" : false }.
 **/

function insertarEmpleado($args)
{	

    Logger::log("insertar empleado iniciado...");

    if( !isset($args['data']) )
    {
        Logger::log("no hay parametros para insertar nuevo empleado");
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }
    
    try
    {
        $data = json_decode( $args['data'] );

    }
    catch(Exception $e)
    {
        Logger::log("json invalido " . $e);
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    $user = new Usuario();

    $user->setRFC( $data->RFC );

    //buscar que no exista ya un empleado con este RFC
    if( count($us = UsuarioDAO::search( $user )) > 0 )
    {
        foreach($us as $u)
        {
            $id = $u->getIdUsuario();
            break;
        }
        Logger::log("ya existe un empleado con el rfc:" . $data->RFC );
        die ( '{"success": false, "id":"' . $id . '", "reason": "Ya existe un empleado con este RFC." }' );
    }
    
	$user->setRFC( $data->RFC );
	$user->setNombre( $data->nombre );
	$user->setContrasena( $data->contrasena );

    //si soy admin ponerle null
    if($_SESSION['grupo'] == 1){
    	$user->setIdSucursal( null );
    }else{
    	$user->setIdSucursal( $_SESSION['sucursal'] );
    }


	$user->setActivo( 1 );
	$user->setSalario( $data->salario) ;
    $user->setTelefono( $data->telefono );
    $user->setDireccion( $data->direccion );
    $now = new DateTime("now");
    $user->setFechaInicio( $now->format('Y-m-d') );
	
	$gruposUsuarios = new GruposUsuarios();
	$gruposUsuarios->setIdGrupo( $data->grupo );
	
    try{
        if( UsuarioDAO::save( $user ) == 1);
        {        
            $gruposUsuarios->setIdUsuario( $user->getIdUsuario() );
            if( GruposUsuariosDAO::save( $gruposUsuarios) )
            {
                
                printf(' { "success" : "true", "id_usuario": "%s" } ', $user->getIdUsuario() );
            }
            else
            {
                die(' { "success" : "false", "reason" : "Error al enrolar al empleado a su grupo" } ' );
            }
        }
    }
    catch( Exception $e )
    {
        die( ' { "success" : "false", "reason" : "' . $e . '"} ' );
    } 
   
}//insertarEmpleado

/**
 * Función para obtener una lista de usuarios en una sucursal.
 *  
 * @param $id_sucursal Id de la cursal a la cual se desea listar sus empleados.
 * @param $activo indica si se desea listar a usuarios activos o inactivos.
 * @return cadena en formato JSON que contiene los datos de los empleados.
 **/

function listarEmpleados( $sid )
{




        $empleados = new Usuario();
        $empleados->setIdSucursal( $sid );
        $empleados->setActivo("1"); 


        $empleados = UsuarioDAO::search($empleados);


        $empleadosArray = array();

        foreach($empleados as $e){

            //si es el gerente, a la verga

            $foo = $e->asArray();

            $grupo = new GruposUsuarios();
            $grupo->setIdUsuario( $e->getIdUsuario() );

            $searchGrupo = GruposUsuariosDAO::search( $grupo );

            if(count($searchGrupo) == 0){
                //no esta asignado
                $foo['puesto'] = "No asignado";

            }else{

                if($searchGrupo[0]->getIdGrupo() <= 2){
                    //no motrar administradores ni gerentes
                    continue;
                }
                $foo['tipo'] = $searchGrupo[0]->getIdGrupo();
                $foo['puesto'] = GruposDAO::getByPK( $searchGrupo[0]->getIdGrupo() )->getDescripcion();;
            }


            array_push( $empleadosArray, $foo );
            
        }

    return $empleadosArray;
/*
	$gru1 = new GruposUsuarios();
    $gru1->setIdGrupo('3');

    $gru2 = new GruposUsuarios();
    $gru2->setIdGrupo('100');

    $result = GruposUsuariosDAO::byRange($gru1, $gru2);

    $array_empleados = array();

    foreach($result as $r)
    {
        $usuario = new Usuario();
        $usuario->setIdSucursal( $sid );
        $usuario->setActivo( 1 );
        $usuario->setIdUsuario( $r->getIdUsuario() );
        
        $jsonUsuarios = UsuarioDAO::search( $usuario );

        $descripcion_tipo = GruposDAOBase::getByPK($r->getIdGrupo());

        foreach($jsonUsuarios as $empleado)
        {
            array_push( $array_empleados, array(
                'id_usuario' => $empleado->getIdUsuario(),
                'RFC' => $empleado->getRfc(),
                'nombre' => $empleado->getNombre(),
                'id_sucursal' => $empleado->getIdSucursal(),
                'activo' => $empleado->getActivo(),
                'finger_token' => $empleado->getFingerToken(),
                'salario' => $empleado->getSalario(),
                'direccion' => $empleado->getDireccion(),
                'telefono' => $empleado->getTelefono(),
                'tipo' => $r->getIdGrupo(),
                'puesto' => $descripcion_tipo->getNombre(),
                'fecha_inicio' => $empleado->getFechaInicio()
            ) );
        }
        
    }

    return $array_empleados;
*/
}









function listarGerentes($asignados = null)
{
    $array_empleados = array();


    //todos
    if($asignados === null){

        //todos los gerentes
        $gru1 = new GruposUsuarios();
        $gru1->setIdGrupo('2');
        $result = GruposUsuariosDAO::search($gru1);

        foreach($result as $r)
        {
            $gerente = UsuarioDAO::getByPK($r->getIdUsuario());
            if($gerente->getActivo() == "0") continue;
                    
            $suc = new Sucursal();
            $suc->setActivo(1);
            $suc->setGerente($r->getIdUsuario());

            //buscar una sucursal con este gerente
            $tSuc = SucursalDAO::search($suc);
            if(count($tSuc) ==  0){
                //no es gerente
                $gerente = $gerente->asArray();
                $gerente['gerencia_sucursal_desc'] = null;
                $gerente['gerencia_sucursal_id'] = null;
                $gerente['contrasena'] = null;
              
                array_push($array_empleados, $gerente );
            }else{
                //si es gerente
                $gerente = $gerente->asArray();
                $gerente['gerencia_sucursal_desc'] = SucursalDAO::getByPK($tSuc[0]->getIdSucursal())->getDescripcion();
                $gerente['gerencia_sucursal_id'] = $tSuc[0]->getIdSucursal();
                $gerente['contrasena'] = null;
                array_push($array_empleados, $gerente );
            }

            
        }

        return $array_empleados;

    }




    //asignados o no asignados
    if($asignados){

        $suc = new Sucursal();
        $suc->setActivo(1);
        $sucursales = SucursalDAO::search($suc);

        foreach ($sucursales as $s )
        {
            $gerenteSuc = $s->getGerente();

            if($gerenteSuc != null){

                $data = UsuarioDAO::getByPK($gerenteSuc)->asArray();
                if($data['activo'] == 0) continue;
                $data['gerencia_sucursal_desc'] = SucursalDAO::getByPK($s->getIdSucursal())->getDescripcion();
                $data['gerencia_sucursal_id'] = $s->getIdSucursal();
                $data['contrasena'] = null;

                array_push( $array_empleados, $data ) ;
            }

        }
    }else{
        //no asignados
        $gru1 = new GruposUsuarios();
        $gru1->setIdGrupo('2');
        $result = GruposUsuariosDAO::search($gru1);

        foreach($result as $r)
        {

            $suc = new Sucursal();
            $suc->setActivo(1);
            $suc->setGerente($r->getIdUsuario());

            //buscar una sucursal con este gerente
            if(count(SucursalDAO::search($suc)) < 1){
                $gerente = UsuarioDAO::getByPK($r->getIdUsuario());
                if($gerente->getActivo() == "0") continue;
                $gerente->setContrasena(null);
                array_push($array_empleados, $gerente );
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
 **/

function modificarEmpleado( $args )
{



    if( !isset($args['data']) )
    {
        Logger::log("no hay parametros para modificar empleado");
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }
    
    try
    {
        $data = json_decode( $args['data'] );
    }
    catch(Exception $e)
    {
        Logger::log("json invalido " . $e);
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }



    if($data === null){
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    $usuario = UsuarioDAO::getByPK($data->id_usuario);

    if( isset( $data->RFC) )
    {
        $usuario->setRFC( $data->RFC );
    }

    if( isset( $data->nombre) )
    {
        $usuario->setNombre( $data->nombre );
    }
    
    if( isset( $data->contrasena ) )
    {
        $usuario->setContrasena( $data->contrasena );
    }

    if( isset( $data->id_sucursal ) )
    {
        $usuario->setIdSucursal( $data->id_sucursal );
    }

    //PARA ESTOY HAY UNA FUNCION ENTERA QUE SE ENCARGA DE LO NECESARIO
    /*
    if( isset( $data->activo ) )
    {
        $usuario->setActivo( $data->activo );
    }*/
	
    if( isset( $data->finger_token ) )
    {
        $usuario->setFingerToken( $data->finger_token );
    }

    if( isset( $data->salario ) )
    {
        $usuario->setSalario( $data->salario );
    }
	
    if(isset( $data->direccion ))
    {
        $usuario->setDireccion( $data->direccion );
    }

    if(isset( $data->telefono ))
    {
        $usuario->setTelefono( $data->telefono );
    }

	try
    {

		if( UsuarioDAO::save( $usuario ) )
        {

            
            if( isset( $data->grupo ) )
            {
                //entra aqui en caso de que se mande el dato grupo, para cambiar de puesto al empleado

                if( !( $grupoUsuarios = GruposUsuariosDAO::getByPK( $data->grupo, $usuario->getIdUsuario() ) ) )
                {
                    //si entro aqui significa que el puesto que se le va a asegnar es distinto al que ya tenia
                    // y por lo tanto hay que eliminar el registro

                    $gruposUsuarios = new GruposUsuarios();
                    $gruposUsuarios->setIdUsuario( $usuario->getIdUsuario() );

                    $find = GruposUsuariosDAO::search( $gruposUsuarios );

                    foreach( $find as $f )
                    {
                        //eliminamos todos los registros donde este ese usuario enrolado en un puesto
                        GruposUsuariosDAO::delete( $f );
                    }
                    
                }


                $gruposUsuarios = new GruposUsuarios();
                $gruposUsuarios->setIdGrupo( $data->grupo );
                $gruposUsuarios->setIdUsuario( $usuario->getIdUsuario() );
                if( !GruposUsuariosDAO::save( $gruposUsuarios) )
                {
                    die(' { "success" : "false", "reason" : "Se actualizaron los datos del empleado, pero se origino un error al enrolar al empleado a su nuevo grupo" } ' );
                }

            }

            printf ( ' { "success" : "true", "info": "Se actualizaron correctamente los datos de '.$usuario->getNombre().'" } ' );
        }
        else
        {
            die ( ' { "success" : "false", "reason" : "No se actualizaron los datos del empleado" } ' );
        }
        
	}
	catch( Exception $e )
	{
        Logger::log($e);
		die ( ' { "success" : "false", "reason" : "' . $e . '" } ' );
	}
	
	

}

/**
 * Activa o desactiva la cuenta de un empleado.
 *  
 * @param $id_usuario Id del empleado
 * @param $status indica en nuevo estadod el empleado 1 = activo, 0 = inactivo
 * @return cadena en formato JSON que informa si la operacion se realizo con exito. { "success" : "true", "info" :"mensaje"  } o { "success" : "false", "reason" : "mensaje"}
 **/
//AQUI FALTA QUITARLO DE LA GERENCIA DE UNA SUCURSAL SI ES QUE ES UN GERENTE CON UNA SUCURSAL
function cambiarEstadoEmpleado( $args )
{
    //
    if( ( !isset( $args['id_empleado'] ) && !isset( $args['activo'] ) ) ||  $args['activo'] == null)
    {
        die( ' { "success" : false, "reason" : "Faltan parametros" } ' );
    }

	if( $usuario = UsuarioDAO::getByPK( $args['id_empleado'] ) )
    {
        $usuario->setActivo( $args['activo'] );
    }
    else
    {
        die( ' { "success" : false, "reason" : "No se tiene registro del empleado ' . $args['id_empleado'] . '" } ' );
    }		
	
	try{		
		UsuarioDAO::save( $usuario );	
	}
	catch( Exception $e )
	{		
		die( ' { "success" : false, "reason" : "No se pudo modificar el estado del usuario" } ' );
	}


    //todo bien, ahora hay que ver que pedo con su gerencia
    Logger::log("cambiando estado de user=".$args['id_empleado']." a " . $args['activo'] );

    $suc = new Sucursal();
    $suc->setGerente($args['id_empleado']);
    $res = SucursalDAO::search($suc);

    $msg = "";

    if(count($res) == 1){
        //es gerente de una sucursal
        $suc = $res[0];
        $suc->setGerente(null);
        try{
            SucursalDAO::save( $suc );
        }catch(Exception $e){
            Logger::log($e);
        }
        $msg = "La sucursal " . $suc->getDescripcion() . " se ha quedado sin gerente.";

        Logger::log("La sucursal " . $suc->getDescripcion() . " se ha quedado sin gerente.", 1);
    }


	$action = ( $args['activo'] == 1)? 'activado':'desactivado';
	printf ( ' { "success" : true, "info": "La cuenta del empleado se ha %s. %s" } ', $action, $msg );

}




function listarBajoPerfil(){

    $p1 = new Grupos();
    $p1->setIdGrupo('3');
    $p2 = new Grupos();
    $p2->setIdGrupo('100');
    
    $result = GruposDAO::byRange($p1, $p2);

    $perfiles = array();

    foreach($result as $r)
    {
        array_push( $perfiles , array(
                'id_grupo' => $r->getIdGrupo(),
                'nombre' => $r->getNombre(),
                'descripcion' => $r->getDescripcion(),
                'text' => $r->getDescripcion(),
                'value' => $r->getIdGrupo()
        ) );
    }

    return $perfiles;

}


/**
 * Esta funcion es empleada para que regrese una lista con los posibles responsables de
 * la caja en la sucursal, se emplea para cuando se va a prestar dinero a una sucursal y hacer
 * responsable a una persona por ese dinero.
 */
function listarResponsables( $args ){

    if( !isset( $args['id_sucursal'] ) )
    {
        die( ' { "success" : false, "reason" : "Faltan parametros" } ' );
    }

    $gr1 = new GruposUsuarios();
    $gr1->setIdGrupo(2);
    $gr2 = new GruposUsuarios();
    $gr2->setIdGrupo(3);

    //obtenemos todos los cajeros y gerentes
    $responsables = GruposUsuariosDAO::byRange($gr1, $gr2);

    $array_responsables = array();

    //los filtramos por sucursal
    foreach( $responsables as  $responsable )
    {

        $empleado = UsuarioDAO::getByPK( $responsable->getIdUsuario() );
        $descripcion_tipo = GruposDAOBase::getByPK( $responsable->getIdGrupo() );

        if( $empleado->getIdSucursal() ==  $args['id_sucursal'] && $empleado->getActivo())
        {
            array_push( $array_responsables, array(
                'text' => $empleado->getNombre(),
                'value' => $empleado->getIdUsuario(),
                'id_sucursal' => $empleado->getIdSucursal(),
                'activo' => $empleado->getActivo(),
                'descripcion' => $descripcion_tipo->getIdGrupo()
            ) );
        }
    }

    return $array_responsables;

}

function editarGerencias ($data){

    $sucursales = json_decode($data['data']);

    foreach ($sucursales as $sucursalData)
    {

        $id_gerente = $sucursalData->id_gerente;
        $id_sucursal = $sucursalData->id_sucursal;

        $sucursal = SucursalDAO::getByPK($id_sucursal);
        $oldGerente = $sucursal->getGerente();


        //asignar el nuevo gerente a la sucursal
        if($id_gerente == -1 ){
            $sucursal->setGerente( null );
        }else{
            $gerente = UsuarioDAO::getByPK($id_gerente);
            $sucursal->setGerente( $id_gerente );
        }


        try{
            SucursalDAO::save($sucursal);    
        }catch(Exception $e){
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
    foreach($tGerentes as $ger){

        $gerente = UsuarioDAO::GetByPK($ger['id_usuario']);

        $suc = new Sucursal();
        $suc->setGerente( $ger['id_usuario'] );
        $sucursal = SucursalDAO::search($suc);

        if(sizeof($sucursal) == 0){
             $gerente->setIdSucursal( null );
        }else{
             $gerente->setIdSucursal( $sucursal[0]->getIdSucursal() );
        }

        try{
            if(!UsuarioDAO::save($gerente)){
                //return array('success' => false, 'reason' => $gerente->getIdUsuario());
            }
        }catch(Exception $e){
            return array('success' => false, 'reason' => $e);
        }
    }


    return array('success' => true );

}



if(isset($args['action'])){


    switch($args['action']){


        case 500:
            insertarEmpleado( $args );
        break;

        case 501:
            $listaEmpleados = listarEmpleados($_SESSION['sucursal'] );
            printf( '{"success": true, "empleados": %s}' , json_encode( $listaEmpleados ) );
        break;

        case 502:
            modificarEmpleado( $args );
        break;

        case 503:
            cambiarEstadoEmpleado( $args );
        break;

        case 504:
            $listaBajoPerfil = listarBajoPerfil( );
            printf( '{"success": true, "datos": %s}' , json_encode( $listaBajoPerfil ) );
        break;

        case 505:
            $listaResponsables = listarResponsables( $args );
            printf( '{"success": true, "datos": %s}' , json_encode( $listaResponsables ) );
        break;

        case 506: //editarGerencias
            echo json_encode(editarGerencias($args));
        break;

        case 507://verHorario
            
        break;
	


        default:
            printf ( '{ "success" : "false" }' );
        break;
    }
}
?>
