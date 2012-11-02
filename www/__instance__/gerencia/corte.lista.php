<?php

    /**
      * Description:
      *
      *
      * Author:
      *     Manuel Garcia (manuel)
      *     Alan Gonzalez (alan)
      *
      ***/

    define("BYPASS_INSTANCE_CHECK", false);

    require_once("../../../server/bootstrap.php");


    $page = new GerenciaComponentPage( );

    $page->addComponent( new TitleComponent( "Cortes de sucursal" ) );
    $cortes = CorteDeSucursalDAO::getAll( );

    $tabla  = new TableComponent( array(
            "fecha_corte" => "Fecha corte",
            "id_sucursal" => "Sucursal",
            "id_usuario" => "Usuario",
            "inicio" => "Inicio",
            "fin" => "Fin"
            ),
            $cortes
        );


    $tabla->addColRender( "id_sucursal", "funcion_sucursal" );
    $tabla->addColRender( "id_usuario", "getUserName" );
    $tabla->addColRender( "inicio", "R::FriendlyDateFromUnixTime" );
    $tabla->addColRender( "fin", "R::FriendlyDateFromUnixTime" );
    $tabla->addColRender( "fecha_corte", "R::FriendlyDateFromUnixTime" );

    $page->addComponent( $tabla );

    $page->render( );

