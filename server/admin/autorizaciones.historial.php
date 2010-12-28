<h1>Todas las autorizaciones</h1>



<h2>Mostrando las ultimas 50 autorizaciones</h2><?php

    require_once('model/autorizacion.dao.php');
    require_once('model/sucursal.dao.php');
    require_once('model/usuario.dao.php');

    $autorizaciones = AutorizacionDAO::getAll(1, 50, "fecha_peticion", "DESC");


 

$header = array(
           "id_autorizacion" => "ID",
           "fecha_peticion" => "Fecha",
           "estado" => "Estado",
           "id_usuario" => "Usuario",
           "parametros" => "Descripcion",
           "id_sucursal" => "Sucursal" );

function renderEstado ($edo){
    switch( $edo ){
        case "1" : return "uno";
        case "2" : return "dos";
        case "3" : return "tres";
        case "4" : return "cuato";

    }
}

function renderSucursal($sucID){
    $foo = SucursalDAO::getByPK($sucID);
    if($foo)
        return $foo->getDescripcion();
    else
        return "";
}

function renderParam( $json )
{
    $obj = json_decode($json);

    return $obj->descripcion;
}


function renderUsuario ($usr){
    $foo = UsuarioDAO::getByPK($usr);
    if($foo)
        return $foo->getNombre();
    else
        return "";
}

$tabla = new Tabla($header, $autorizaciones );
$tabla->addColRender("parametros", "renderParam");
$tabla->addColRender("estado", "renderEstado");
$tabla->addColRender("id_sucursal", "renderSucursal");
$tabla->addColRender("id_usuario", "renderUsuario");
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
