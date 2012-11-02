<?php 



    define("BYPASS_INSTANCE_CHECK", false);

    require_once("../../../server/bootstrap.php");

    $page = new GerenciaComponentPage();

    $page->addComponent( new TitleComponent( "Clasificaciones de Servicio" ) );
    $page->addComponent( new MessageComponent( "Lista de clasificaciones de servicio" ) );

    $tabla = new TableComponent( 
        array(
            "nombre" => "Nombre",
            "garantia" => "Garantia",
            "descripcion" => "Descripcion",
            "activa" => "Activa"
        ),
        ClasificacionServicioDAO::getAll( )
    );



    $tabla->addColRender("activa", "funcion_activa");
    $tabla->addOnClick( "id_clasificacion_servicio", "(function(a){ window.location = 'servicios.clasificacion.ver.php?cid=' + a; })" );

    $page->addComponent( $tabla );

    $page->render();
