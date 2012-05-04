<?php 



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");




        function getUserName($id_usuario){
                $u = UsuarioDAO::getByPK($id_usuario);
                if(is_null($u)){
                        return "ERROR";
                }

                return $u->getNombre();
        }


        $page = new GerenciaTabPage();

        $page->addComponent(new TitleComponent("Ventas y cotizaciones",1));

        
        /* ********************************************************************* 
         * Ventas
         * ********************************************************************* */
        $page->nextTab("Ventas");
        $ventas = new Venta(array("es_cotizacion" => false, "cancelada" => false));
        $tabla = new TableComponent( 
                array(
                        "id_comprador_venta"     => "Cliente",
                        "tipo_de_venta"         => "Tipo de venta",
                        "subtotal"               => "Subtotal",
                        "descuento"              => "Descuento",
                        "total"                  => "Total",
                        "saldo"                  => "Saldo",
                        "fecha"                  => "Fecha"
                ),
                 VentaDAO::search( $ventas )
        );
        $tabla->addColRender("fecha", "FormatTime");
        $tabla->addColRender("subtotal", "FormatMoney");
        $tabla->addColRender("total", "FormatMoney");        
        $tabla->addColRender("saldo", "FormatMoney");        
        $tabla->addColRender("id_comprador_venta", "getUserName");
        $tabla->addOnClick( "id_venta", "(function(a){ window.location = 'ventas.detalle.php?vid=' + a; })" );
        $page->addComponent( $tabla );


        /* ********************************************************************* 
         * Cotizaciones
         * ********************************************************************* */
        $page->nextTab("Cotizaciones");
        $cotizaciones = new Venta(array("es_cotizacion" => true));
        $tabla = new TableComponent( 
                array(
                        "id_comprador_venta"     => "Cliente",
                        "subtotal"               => "Subtotal",
                        "total"                  => "Total",
                        "fecha"                  => "Fecha"
                ),
                 VentaDAO::search( $cotizaciones )
        );

        $tabla->addOnClick( "id_venta", "(function(a){ window.location = 'ventas.detalle.php?vid=' + a; })" );
        $tabla->addColRender("id_comprador_venta", "getUserName");
        $tabla->addColRender("total", "FormatMoney");        
        $tabla->addColRender("saldo", "FormatMoney");  
        $tabla->addColRender("fecha", "FormatTime");         
        $page->addComponent( $tabla );


        /* ********************************************************************* 
         * Ventas canceladas
         * ********************************************************************* */
        $page->nextTab("Ventas caneladas");
        $ventas = new Venta(array("es_cotizacion" => false, "cancelada" => true));
        $tabla = new TableComponent( 
                array(
                        "id_comprador_venta"     => "Cliente",
                        "tipo_de_venta"         => "Tipo de venta",
                        "subtotal"               => "Subtotal",
                        "descuento"              => "Descuento",
                        "total"                  => "Total",
                        "saldo"                  => "Saldo",
                        "fecha"                  => "Fecha"
                ),
                 VentaDAO::search( $ventas )
        );
        $tabla->addColRender("fecha", "FormatTime");
        $tabla->addColRender("subtotal", "FormatMoney");
        $tabla->addColRender("total", "FormatMoney");        
        $tabla->addColRender("saldo", "FormatMoney");        
        $tabla->addColRender("id_comprador_venta", "getUserName");
        $tabla->addOnClick( "id_venta", "(function(a){ window.location = 'ventas.detalle.php?vid=' + a; })" );
        $page->addComponent( $tabla );

        /* ********************************************************************* 
         * Configuracion
         * ********************************************************************* */
        $page->nextTab("Configuracion");

        $page->addComponent( new TitleComponent( "Ventas" ) );






	$page->render();
