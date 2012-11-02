<?php 



    define("BYPASS_INSTANCE_CHECK", false);

    require_once("../../../server/bootstrap.php");

    $page = new GerenciaComponentPage();

    $page->addComponent( new TitleComponent( "Cajas" ) );

    $tabla = new TableComponent(
        array(
            "id_sucursal" => "Sucursal",
            "descripcion"=> "Descripcion",
            "saldo"=> "Saldo",
            "control_billetes" => "Control de Billetes",
            "abierta"=> "Abierta",
            "activa"=>"Activa"
        ),
        SucursalesController::ListaCaja( )
    );


    $tabla->addColRender("id_sucursal", "funcion_sucursal");
    $tabla->addColRender("control_billetes", "funcion_control_billetes");
    $tabla->addColRender("abierta", "funcion_abierta");
    $tabla->addColRender("activa", "funcion_activa");

    $tabla->addOnClick( "id_caja", "(function(a){window.location = 'sucursales.caja.ver.php?cid='+a;})" );

    $page->addComponent( $tabla );

    $page->render();