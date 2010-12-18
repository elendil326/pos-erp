<h1>Autorizaciones pendientes</h1><?php



require_once('controller/autorizaciones.controller.php');




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

$tabla = new Tabla($header, $autorizaciones );
$tabla->addColRender("parametros", "renderParam");
$tabla->addOnClick("id_autorizacion", "detalle");
$tabla->render();




?>
<script>
    function detalle(id)
    {
        window.location = "autorizaciones.php?action=detalle&id=" + id;
    }
</script>




