<?php

/**
 *  Controller para autorizaciones
 */
 
require_once("../server/model/autorizacion.dao.php");
require_once("../server/model/detalle_inventario.dao.php");


function solicitudDeAutorizacion( $auth ){

    $autorizacion = new Autorizacion();
    
    $autorizacion->setIdUsuario( $_SESSION['userid'] );
    $autorizacion->setIdSucursal( $_SESSION['sucursal'] );
    $autorizacion->setEstado('0');

    $autorizacion->setParametros( $auth );

    try
    {
        if( AutorizacionDAO::save( $autorizacion ) > 0 )
        {
            printf( '{ "success" : "true" }' );
        }
        else
        {
            die( '{ "success" : "false" , "reason" : "No se pudo enviar la autorizacion."}' );
        }
    }
    catch(Exception $e)
    {
        die( '{ "success" : "false" , "reason" : "No se pudo enviar la autorizacion."}' );
    }

}//solicitudDeAutorizacion


function autorizacionesPendientes(  ){
    
    $autorizacion = new Autorizacion();
    $autorizacion->setEstado('0');

    $array =  AutorizacionDAO::search( $autorizacion );

    return $array;

}//autorizacionesPendientes


function autorizacionesSucursal( ){

    $autorizacion = new Autorizacion();
    $autorizacion->setIdSucursal( $_SESSION['sucursal'] );

    $json = AutorizacionDAO::search($autorizacion, true);

    printf( '{ "success" : "true", "payload" : %s }', $json );

}//autorizacionesSucursal


//responder autorizacion de gasto (admin)
function respuestaAutorizacionGasto( $args ){

    if( !isset( $args['reply'] ) || !isset( $args['id_autorizacion'] )  )
    {
        die( '{"success": false, "reason": "Faltan parametros." }' );
    }

    if( !( $args['reply'] == 1 || $args['reply'] == 2 ) )
    {
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    $autorizacion = AutorizacionDAOBase::getByPK( $args['id_autorizacion'] );
    $autorizacion->setFechaRespuesta( strftime( "%Y-%m-%d-%H-%M-%S", time() ) );
    $autorizacion->setEstado( $args['reply'] );

    AutorizacionDAO::save( $autorizacion );

}

function eliminarAutorizacion( $args ){

    if(!isset($args['id_autorizacion'])) 
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    $autorizacion = new Autorizacion();
    $autorizacion->setIdAutorizacion( $args['id_autorizacion'] );

    try{
        if( AutorizacionDAOBase::delete( $autorizacion ) < 1 )
        {
            die('{"success": false, "reason": "Verifique que exista la autorizacion ' . $args['id_autorizacion'] . '." }');
        }
        else
        {
            printf( '{ "success" : "true" }' );
        }
    }
    catch(Exception $e)
    {
        die('{"success": false, "reason": "' . $e . '" }');
    }
}


function surtirProducto($args){

    if(!isset($args['id_autorizacion'])) 
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    if( !( $autorizacion = AutorizacionDAO::getByPK( $args['id_autorizacion'] ) ) )
    {
        die('{"success": false, "reason": "Verifique que exista la autorizacion ' . $args['id_autorizacion'] . '." }');
    }

    if( $autorizacion->getEstado() != 3 )
    {
         die('{"success": false, "reason": "El administrador no ha aprovado esta solicitud." }');
    }

    try
    {
        $data = json_decode( $autorizacion->getParametros() );
    }
    catch(Exception $e)
    {
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    $productos = json_decode( $data->productos );

    foreach( $productos as $producto )
    {
        //obtenemos el producto
        $p = DetalleInventarioDAO::getByPK($producto->id_producto, $_SESSION['sucursal']);

        //obtenemos las existencias
        $existencias = $p->getExistencias();

        //agregamos lo que se va a surtir
        $existencias += $producto->cantidad;
        $p->setExistencias( $existencias );

       //guardamos los cambios
        try{
            if ( DetalleInventarioDAO::save($p) < 1 )
            {
                die( '{"success": false, "reason": "Error al guardar las nuevas existencias" }' );
            } 
        }
        catch(Exception $e)
        {
            die( '{"success": false, "reason": "Exception al modificar le detalle del inventario" }' );
        }

    }

    //cambiamos el estado de la autorizacion a surtido
    $autorizacion->setEstado(4);

    try
    {
        if( AutorizacionDAO::save( $autorizacion ) > 0 )
        {
            printf( '{ "success" : "true" }' );
        }
        else
        {
            die( '{ "success" : "false" , "reason" : "No se pudo cambiar el estado a surtido."}' );
        }
    }
    catch(Exception $e)
    {
        die( '{ "success" : "false" , "reason" : "Exception al cambiar estado de la autorizacion."}' );
    }
    
}


//responder autorizacion surtir (admin)
function respuestaAutorizacionSurtir( $args ){

    //SUPER IMPORTANTE QUE DATA TENGA PARENTESIS CUADRADO 
    //data=[{"id_pruducto":"1","cantidad":"55.5"},{"id_pruducto":"1","cantidad":"2"}]

    //estado puede valer:
    // 0->sin revisar
    // 1->aprovado
    // 2->denegado                               <------ESTO ES LO NORMAL PARA EL CASO DE RESPUESTA DE SURTIR
    // 3->aprovado y sin surtir por el gerente   <------ESTO ES LO NORMAL PARA EL CASO DE RESPUESTA DE SURTIR
    // 4->aprovado y surtido por el gerente

    //FORMATO DE UNA AUTORIZACION DE SURTIR

    /*  {
            "id_autorizacion":"1",
            "id_usuario":"38",
            "id_sucursal":"54",
            "fecha_peticion":"2010-12-17 19:33:56",
            "fecha_respuesta":"2010-12-17 22:38:14",
            "estado":"2",
            "parametros":"{
                \"clave\":\"209\",
                \"descripcion\":\"Solicitud de producto\",
                \"productos\":[
                    {
                        \"id_producto\":\"1\",
                        \"cantidad\":\"55.5\"
                    },
                    {
                        \"id_producto\":\"1\",
                        \"cantidad\":\"2\"
                    }
                ]
            }"
        }  */




    if(!isset($args['data']) || !isset($args['id_autorizacion']) || !isset($args['estado']))
    {
        die('{"success": false, "reason": "Faltan parametros." }');
    }

    try
    {
        $data = json_decode( $args['data']);
    }
    catch(Exception $e)
    {
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    if( !( $autorizacion = AutorizacionDAO::getByPK( $args['id_autorizacion'] ) ) )
    {
        die('{"success": false, "reason": "Verifique que exista la autorizacion ' . $args['id_autorizacion'] . '." }');
    }

    //establecemos el nuevo estado
    $autorizacion->setEstado( $args['estado'] );

    $parametros = json_encode(array(
        'clave'=>'209',
        'descripcion'=>'Respuesta de producto',
        'productos'=>$data
    ));


    //definimos los nuevos parametros
    $autorizacion->setParametros( $parametros );

    try
    {
        if( AutorizacionDAO::save( $autorizacion ) > 0 )
        {
            printf( '{ "success" : "true" }' );
        }
        else
        {
            die( '{ "success" : "false" , "reason" : "Error al cambiar el estado de la autorización."}' );
        }
    }
    catch(Exception $e)
    {
        die( '{ "success" : "false" , "reason" : "Exception al cambiar estado de la autorización."}' );
    }

}


//funcion que regresa el detalle de una autorizacion
function detalleAutorizacion( $args ){

    if( !isset ($args['id_autorizacion'] ) )
    {
        die('{"success": false, "reason": "Faltan parametros." }');
    }

    if( !( $autorizacion = AutorizacionDAO::getByPK( $args['id_autorizacion'] ) ) )
    {
        die('{"success": false, "reason": "Verifique que exista la autorizacion ' . $args['id_autorizacion'] . '." }');
    }

    return $autorizacion;

}

//el admin puede surtir productos de la nada a las sucursales
function surtirProductosSucursal(){

}

switch( $args['action'] ){

    case 201://solicitud de autorizacion de gasto (gerente)

        if( !isset( $args['concepto'] ) || !isset( $args['monto'] ) )
        {
            die( '{ "success" : "false" , "reason" : "Faltan datos" }' );
        }
    
        if( !is_numeric( $args['monto'] ) )
        {
            die( '{ "success" : "false" , "reason" : "No es una cantidad valida." }' ); 
        }
    
        $descripcion = json_encode(array( 
            'clave'=>$args['action'],
            'descripcion'=>'Autorización de gasto', 
            'concepto'=>$args['concepto'], 
            'monto'=>$args['monto'] 
        ));
    
        solicitudDeAutorizacion( $descripcion );

    break;

    case 202://solicitud de autorizacion de cambio de limite de credito (gerente)

        if( !isset( $args['id_cliente'] ) || !isset( $args['cantidad'] ) )
        {
            die( '{ "success" : "false" , "reason" : "Faltan datos" }' );
        }

        if( !is_numeric( $args['cantidad'] ) )
        {
            die( '{ "success" : "false" , "reason" : "No es una cantidad valida." }' ); 
        }

        $descripcion = json_encode(array( 
            'clave'=>$args['action'], 
            'descripcion'=>'Autorización de limite de crédito',
            'id_cliente'=>$args['id_cliente'],
            'cantidad'=>$args['cantidad']
        ));

        solicitudDeAutorizacion( $descripcion );

    break;

    case 203://solicitud de autorizacion de devolucion (gerente)

        if( !isset($args['data']) )
        {
            die('{"success": false, "reason": "No hay parametros para ingresar." }');
        }
        
        try
        {
            $data = json_decode( $args['data'] ); //$data = json_decode( $data);
        }
        catch(Exception $e)
        {
            die( '{"success": false, "reason": "Parametros invalidos." }' );
        }

        if(!is_numeric( $data -> cantidad ))
        {
            die( '{ "success" : "false" , "reason" : "No es una cantidad valida." }' ); 
        }

        $descripcion = json_encode(array( 
            'clave'=>$args['action'], 
            'descripcion'=>'Autorización de devolución',
            'id_venta'=>$data -> id_venta,
            'id_producto'=>$data -> id_producto,
            'cantidad'=>$data -> cantidad
        ));

        solicitudDeAutorizacion( $descripcion );

    break;

    case 204://solicitud de autorizacion de cambio de precio (gerente)
        
        if( !isset( $args['id_producto'] ) || !isset( $args['precio'] ) )
        {
            die( '{ "success" : "false" , "reason" : "Faltan datos" }' );
        }

        if( !is_numeric( $args['precio'] ) )
        {
            die( '{ "success" : "false" , "reason" : "No es una cantidad valida." }' ); 
        }

        $descripcion = json_encode(array( 
            'clave'=>$args['action'], 
            'descripcion'=>'Autorización de cambio de precio',
            'id_producto'=>$args['id_producto'],
            'precio'=>$args['precio']
        ));

        solicitudDeAutorizacion( $descripcion );

    break;

    case 205:////solicitud de autorizacion de merma (gerente)

        if( !isset($args['data']) )
        {
            die('{"success": false, "reason": "No hay parametros para ingresar." }');
        }
        
        try
        {
            $data = json_decode( $args['data'] ); //$data = json_decode( $data);
        }
        catch(Exception $e)
        {
            die( '{"success": false, "reason": "Parametros invalidos." }' );
        }

        if(!is_numeric( $data -> cantidad ))
        {
            die( '{ "success" : "false" , "reason" : "No es una cantidad valida." }' ); 
        }

        $descripcion = json_encode(array( 
            'clave'=>$args['action'], 
            'descripcion'=>'Autorización de merma',
            'id_compra'=>$data -> id_compra,
            'id_producto'=>$data -> id_producto,
            'cantidad'=>$data -> cantidad
        ));

        solicitudDeAutorizacion( $descripcion );

    break;

    case 206://ver autorizaciones pendientes de todas las sucursales (admin)
        autorizacionesPendientes( );
    break;

    case 207://ver autorizaciones de su sucursal (gerente)
        autorizacionesSucursal(  );
    break;

    case 208://responder autorizacion de gasto (admin)
        respuestaAutorizacionGasto( $args );
    break;

    case 209://solicitud de uno o mas productos (gerente)

        //SUPER IMPORTANTE QUE DATA TENGA PARENTESIS CUADRADO 
        //data=[{"id_producto":"1","cantidad":"55.5"},{"id_producto":"1","cantidad":"2"}]

        if(!isset($args['data']))
        {
            die('{"success": false, "reason": "No hay parametros para ingresar." }');
        }

        try
        {
            $data = json_decode( $args['data']);
        }
        catch(Exception $e)
        {
            die( '{"success": false, "reason": "Parametros invalidos." }' );
        }

        $descripcion = json_encode(array(
            'clave'=>$args['action'],
            'descripcion'=>'Solicitud de producto',
            'productos'=>$data
        ));

        solicitudDeAutorizacion( $descripcion );

    break;

    case 210://respuesta de solicitud de producto (admin)
        respuestaAutorizacionSurtir( $args );
    break;

    case 211://surtir producto (gerente)
        //ya que el admin envio la mercancia a la sucursal y esta ha llegado a la
        //sucursal del gerente, este debera de verificar que conicida con lo enviado
        //por el admin, cuando todo este aclarado y listo, el gerente debera de liberar
        //la solicitud de producto para que se cargue al inventario lo que surtio el 
        //admin.

        surtirProducto( $args );

    break;

    case 212://eliminar autorizacion de la lista de autorizaciones (gerente)
        eliminarAutorizacion( $args );
    break;

    case 213://detalle de autorizacion (admin)
        detalleAutorizacion( $args );
    break;

    case 214://surtir productos sucursal (admin)
        surtirProductosSucursal( $args );
    default:
        printf ('{ "success" : "false" }');
    break;

}

//sigue inventario

?>
