<?php

    define("BYPASS_INSTANCE_CHECK", true);

    require_once("../../server/bootstrap.php");

    $p = new JediComponentPage( );

    $p->addComponent( new TitleComponent( "Instancias"));

    $p->addComponent( new TitleComponent( "Instancias instaladas", 3 ));

    $headers = array(
        "instance_id" => "instance_id",
        "instance_token" => "instance_token",
        "fecha_creacion" => "creada",
        "descripcion" => "descripcion",
        "activa" => "activa",
        "status" => "status"
    );

    $t = new TableComponent($headers , InstanciasController::Buscar(/*$activa = true, $query = "P", $order_by = "instance_token", $order = "DESC", $start = 0, $limit = 100*/));

    $t->addColRender( "activa", "FormatBoolean" );

    $t->addColRender( "pos_instance", "FormatBoolean" );

    function FormatBoolean($activa)
    {
        if($activa === "0"){
            return "<font style = \"color:red;\">no</font>";
        }

        if($activa === "1"){
            return "s&iacute;";
        }

        return "undefined";
    }

    $t->addColRender( "fecha_creacion", "FormatTime");

    $t->addOnClick( "instance_id" , "(function(i){window.location='instancias.ver.php?id='+i; })");

    $p->addComponent( new FreeHtmlComponent( '<div class="POS Boton OK"  onclick="window.location=\'instancias.bd.php\'">BD de Instancias</div>'));

    $p->addComponent( $t );

    $p->render( );






