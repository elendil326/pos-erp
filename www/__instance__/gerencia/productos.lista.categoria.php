<?php 



    define("BYPASS_INSTANCE_CHECK", false);

    require_once("../../../server/bootstrap.php");

    $page = new GerenciaComponentPage( );

    $page->addComponent( new TitleComponent( "Categorias de Producto" ) );
    $page->addComponent( new MessageComponent( "Lista de categorias de producto" ) );

    $tabla = new TableComponent( 
        array(
            "nombre" => "Nombre",
            "id_categoria_padre"	=> "Categoria Padre",
            "descripcion" 		=> "Descripcion",
            "activa" 			=> "Activa"
        ),
        ClasificacionProductoDAO::getAll( )
    );



    $tabla->addColRender("id_categoria_padre", "funcion_id_categoria_padre");

    $tabla->addOnClick( "id_clasificacion_producto", "(function(a){ window.location = 'productos.categoria.ver.php?cid=' + a; })" );


    $page->addComponent( $tabla );
    $page->render();
