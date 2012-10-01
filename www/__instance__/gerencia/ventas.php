<?php 



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");




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
        
        $menu = new MenuComponent();
        $menu->addItem("Nueva venta", "ventas.nueva.php");                                               
        $page->addComponent( $menu);


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
                 VentaDAO::search( $ventas, "fecha", "desc" )
        );
        $tabla->addColRender("fecha", "FormatTime");
        $tabla->addColRender("subtotal", "FormatMoney");
        $tabla->addColRender("total", "FormatMoney");        
        $tabla->addColRender("saldo", "FormatMoney");        
        $tabla->addColRender("descuento", "FormatMoney");        
        $tabla->addColRender("id_comprador_venta", "getUserName");
        $tabla->addOnClick( "id_venta", "(function(a){ window.location = 'ventas.detalle.php?vid=' + a; })" );
        $page->addComponent( $tabla );


        /* ********************************************************************* 
         * Cotizaciones
         * ********************************************************************* */
        $page->nextTab("Cotizaciones");

        $menu = new MenuComponent();
        $menu->addItem("Nueva cotizacion", "ventas.nueva.php");
        $page->addComponent( $menu);


        $cotizaciones = new Venta(array("es_cotizacion" => true));
        $tabla = new TableComponent( 
                array(
                        "id_comprador_venta"     => "Cliente",
                        "subtotal"               => "Subtotal",
                        "total"                  => "Total",
                        "fecha"                  => "Fecha"
                ),
                 VentaDAO::search( $cotizaciones, "fecha", "desc" )
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
        $page->nextTab("Canceladas");
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
                 VentaDAO::search( $ventas, "fecha", "desc"  )
        );
        $tabla->addColRender("fecha", "FormatTime");
        $tabla->addColRender("subtotal", "FormatMoney");
        $tabla->addColRender("total", "FormatMoney");        
        $tabla->addColRender("saldo", "FormatMoney");        
        $tabla->addColRender("id_comprador_venta", "getUserName");
        $tabla->addOnClick( "id_venta", "(function(a){ window.location = 'ventas.detalle.php?vid=' + a; })" );
        $page->addComponent( $tabla );

        
        
        
        
        /* ********************************************************************* 
         * Corte
         * ********************************************************************* */
        $page->nextTab("Corte");                
        
        $sucursales = SucursalDAO::getAll();
        
        $html = "";
        
        $html .= "<script>";
        
        $html .= "  var corteSucursal = function(combo){";                                        
        $html .= "      location.href = 'ventas.corte.php?s=' + combo.options[combo.selectedIndex].value; ";                
        $html .= "  }";
        
        $html .= "</script>";
        
        $html .= "<form method = \"post\" action=\"ventas.corte.php\">";                
        
        $html .= "  <table>";        
        $html .= "    <tr>";        
        $html .= "      <td>Sucursal:</td>";        
        $html .= "      <td>";        
        $html .= "        <SELECT name=\"s\">";        
        
        foreach($sucursales as $sucursal){
            $html .= "         <OPTION value=\"". $sucursal->getIdSucursal() . "\">" . $sucursal->getRazonSocial() . "</OPTION>";
        }
        
        $html .= "        </SELECT>";        
        $html .= "      </td>";        
        $html .= "    </tr>";        
        
        $html .= "    <tr>";        
        $html .= "      <td>Fondo Inicial</td>";        
        $html .= "      <td><input type=\"text\" name=\"fondo_inicial\"></td>";        
        $html .= "    </tr>";        
        
        $html .= "    <tr>";        
        $html .= "      <td>Efectivo en Caja</td>";        
        $html .= "      <td><input type=\"text\" name=\"efectivo\"></td>";        
        $html .= "    </tr>"; 
        
        $html .= "    <tr>";        
        $html .= "      <td colspan=\"2\"><input type=\"submit\" value=\"Realizar Corte\" /></td>";                
        $html .= "    </tr>"; 
        
        $html .= "  </table>";                
        
        $html .= "</form>";                
        
        $page->addComponent($html);
       
        
        /* ********************************************************************* 
         * Configuracion
         * ********************************************************************* */
        $page->nextTab("Configuracion");

        $page->addComponent( new TitleComponent( "Ventas" ) );

        
                                        


	$page->render();
