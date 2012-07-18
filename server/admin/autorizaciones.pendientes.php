<?php

require_once('controller/autorizaciones.controller.php');
require_once('model/usuario.dao.php');



?><h1>Autorizaciones pendientes</h1>



<h2>Autorizaciones pendientes</h2><?php






$autorizaciones = autorizacionesPendientes();

$header = array(
           "id_autorizacion" => "ID",
           "fecha_peticion" => "Fecha",
           "id_usuario" => "Usuario que realizo la peticion",
           "parametros" => "Descripcion",
           "id_sucursal" => "Sucursal" );


function renderParam( $json )
{
    $obj = json_decode($json);

    return $obj->descripcion;
}

function renderUsuario($id)
{
    $foo = UsuarioDAO::getByPK( $id );
    return $foo->getNombre();
}

function renderSucursal($id)
{
    $foo = SucursalDAO::getByPK( $id );
    return $foo->getDescripcion();
}


$tabla = new Tabla($header, $autorizaciones );
$tabla->addColRender("parametros", "renderParam");
$tabla->addColRender("id_usuario", "renderUsuario");
$tabla->addColRender("id_sucursal", "renderSucursal");
$tabla->addOnClick("id_autorizacion", "detalle");
$tabla->addNoData("No hay autorizaciones pendientes");
$tabla->render();




?>
<script>
    function detalle(id)
    {
        window.location = "autorizaciones.php?action=detalle&id=" + id;
    }
</script>




