<?php

    define("BYPASS_INSTANCE_CHECK", true);

    require_once("../../server/bootstrap.php");

    $p = new JediComponentPage("Detalles de la instancia");

    $p->requireParam("id", "GET", "Esta instancia no existe.", "InstanciasController::Detalles");

    $instancia = InstanciasController::Detalles( $_GET["id"]);

    $p->addComponent(new TitleComponent("Detalles de la instancia" ));

    $p->addComponent(new TitleComponent($instancia["instance_token"], 3));

    $p->addComponent("<br><a href='../". $instancia["instance_token"] ."/' target='_new'><div class='POS Boton'>Visitar la instancia</div></a>");

    $t = new TableComponent(array(
            "instance_id" => "instance_id",
            "instance_token" => "instance_token",
            "descripcion" => "descripcion",
            "db_user" => "db_user",
            "db_name" => "db_name",
            "db_host" => "db_host"
        ), array($instancia));

    $p->addComponent($t);

    $p->render();