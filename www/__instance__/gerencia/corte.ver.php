<?php


/**
  * Description:
  *
  *
  * Author:
  *     Alan Gonzalez (alan)
  *
  ***/
    define("BYPASS_INSTANCE_CHECK", false);

    require_once("../../../server/bootstrap.php");

    $page = new GerenciaComponentPage();

    $page->requireParam(  "cid", "GET", "Este corte no existe." );

    $corte = CorteDeSucursalDAO::getByPk(  $_GET["cid"] );

    if ( is_null( $corte ) )
    {
        //whops
        $page->render( );
    }


    $page->addComponent( new TitleComponent( "Corte" ) );

    $page->render();