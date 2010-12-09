<?php

/**
 *	Controller para personal
 */
 

require_once('../server/model/usuario.dao.php');
require_once('../server/model/grupos_usuarios.dao.php');
require_once('../server/model/grupos.dao.php');
require_once('../server/model/sucursal.dao.php');


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

    if( !isset($args['data']) )
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }
    
    try
    {
        $data = json_decode( $args['data'] );
        //$data = json_decode( $data);
    }
    catch(Exception $e)
    {
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
        die ( '{"success": false, "id":"' . $id . '", "reason": "Ya existe un empleado con este RFC." }' );
    }
    
	$user->setRFC( $data->RFC );
	$user->setNombre( $data->nombre );
	$user->setContrasena( $data->contrasena );
	$user->setIdSucursal( $_SESSION['sucursal'] );
	$user->setActivo( 1 );
	$user->setSalario( $data->salario) ;
    $user->setTelefono( $data->telefono );
    $user->setDireccion( $data->direccion );
	
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

function listarEmpleados( )
{

	$gru1 = new GruposUsuarios();
    $gru1->setIdGrupo('3');

    $gru2 = new GruposUsuarios();
    $gru2->setIdGrupo('100');

    $result = GruposUsuariosDAO::byRange($gru1, $gru2);

    $array_empleados = array();

    foreach($result as $r)
    {
        $usuario = new Usuario();
        $usuario->setIdSucursal( $_SESSION['sucursal'] );
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
                'puesto' => $descripcion_tipo->getNombre()
            ) );
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
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }
    
    try
    {
        $data = json_decode( $args['data'] );
    }
    catch(Exception $e)
    {
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

    if( isset( $data->activo ) )
    {
        $usuario->setActivo( $data->activo );
    }
	
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

function cambiarEstadoEmpleado( $args )
{

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

	$action = ( $args['activo'] == 1)? 'activado':'desactivado';

	printf ( ' { "success" : true, "info": "La cuenta del empleado se ha  %s " } ', $action );

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

switch($args['action']){


    case 500:
        insertarEmpleado( $args );
    break;

    case 501:
        $listaEmpleados = listarEmpleados( );
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

    case 506://verEstadisticasVenta
        
    break;

    case 507://verHorario
        
    break;
	
    case 599://para que era esto?

        $page = $nro_registros = $sortname = $sortorder = null; 

        if(isset($args['pagina']))
        {
            $page = $args['pagina'];
        }

        if(isset($args['nro_reg']))
        {
            $nro_registros = $args['nro_reg'];
        }

        if(isset($args['sortname']))
        {
            $sortname = $args['sortname'];
        }

        if(isset($args['sortorder']))
        {
            $sortorder = $args['sortorder'];
        }


        echo getDataGridUsuarios($page, $nro_registros, $sortname, $sortorder);

    break;

    default:
        printf ( '{ "success" : "false" }' );
    break;
}
?>
