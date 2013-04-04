<?php

    define("BYPASS_INSTANCE_CHECK", true);

    require_once("../../server/bootstrap.php");

    $p = new JediComponentPage("Detalles de la instancia");

    $p->requireParam("id", "GET", "Esta instancia no existe.", "InstanciasController::Detalles");

    $instancia = InstanciasController::Detalles($_GET["id"]);

    $p->addComponent(new TitleComponent("Detalles de la instancia" ));

    $p->addComponent("<br><a href='../". $instancia["instance_token"] ."/' target='_new'><div class='POS Boton'>Visitar la instancia</div></a>");

    $p->addComponent("<a href='./instancias.editar.php?id=" . $_GET["id"]. "'><div class='POS Boton'>Editar Informaci&oacute;n</div></a>");

    $p->addComponent(new TitleComponent($instancia["instance_token"], 3));

    $t = new TableComponent(array(
             "instance_id" => "Id",
             "activa" => "Activa",
             "instance_token" => "Token",
             "descripcion" => "Descripcion",
             "db_name" => "db_name",
             "db_host" => "db_host",
             "fecha_creacion" => "Creaci&oacute;n"
        ), array($instancia));

    $t->addColRender( "fecha_creacion", "FormatTime" );
    $t->addColRender( "activa", "FormatBoolean" );

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

    $p->addComponent($t);

    if (!empty($instancia["request"])) {
        $p->addComponent(new TitleComponent("Request", 3));

        $tt = new TableComponent(array(
                  "id_request" => "Id",
                  "email" => "Email",
                  "fecha" => "Fecha",
                  "ip" => "Ip",
                  "date_validated" => "Validado",
                  "date_installed" => "Instalado"
             ), array($instancia["request"]));

        $tt->addColRender("fecha", "FormatTime");
        $tt->addColRender("date_validated", "FormatTime");
        $tt->addColRender("date_installed", "FormatTime");

        $p->addComponent($tt);
    }

    $p->render();