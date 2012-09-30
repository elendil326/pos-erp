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
        
        $menu = new MenuComponent();
        //VALIDAR SI SE HA ECHO CORTE O NO        
        $menu->addItem("Realizar Corte", "ventas.corte.php");
        $page->addComponent( $menu);
                
        $table = "";
                
        $table .= "<table>";        
        $table .= "  <tr>";       
        $table .= "    <td></td>";
        $table .= "    <td><b>Del</b></td>";        
        $table .= "    <td colspan= \"2\">14/SEP/2012</td>";        
        $table .= "    <td><b>Al</b></td>";        
        $table .= "    <td colspan= \"2\">14/SEP/2012</td>";        
        $table .= "    <td></td>";
        $table .= "  </tr>";        
        
        $table .= "  <tr>";       
        $table .= "    <td></td>";
        $table .= "    <td>Efectivo Inicial</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td>$0.00</td>";        
        $table .= "    <td></td>"; 
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";        
        
        $table .= "  <tr>";       
        $table .= "    <td></td>";
        $table .= "    <td>Ventas</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";       
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td>Efectivo</td>";        
        $table .= "    <td>$0.00</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";       
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td>Cheques</td>";        
        $table .= "    <td>$0.00</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";       
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td>Credito</td>";        
        $table .= "    <td style=\"border-bottom: 1px;border-bottom-color: black;border-style: solid;\">$0.00</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";       
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td>$0.00</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";       
        $table .= "    <td></td>";
        $table .= "    <td>Cuentas por cobrar recolectado</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td>$0.00</td>";        
        $table .= "    <td></td>"; 
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>"; 
        
        $table .= "  <tr>";       
        $table .= "    <td></td>";
        $table .= "    <td>Otros Ingresos</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td style=\"border-bottom: 1px;border-bottom-color: black;border-style: solid;\">$0.00</td>";
        $table .= "    <td></td>"; 
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";    
               
        
        $table .= "  <tr>";       
        $table .= "    <td><b>Subtotal</b></td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td><b>$0.00</b></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";       
        $table .= "    <td></td>";
        $table .= "    <td>Ventas a Credito</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td style=\"border-bottom: 1px;border-bottom-color: black;border-style: solid;\">$0.00</td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr style = \"background:#C4E8F8;\">";       
        $table .= "    <td><b>Total en Efectivo</b></td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td><b>$0.00</b></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td>Efectivo Pagado a Clientes</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td>Reintegro de Efectivo</td>";        
        $table .= "    <td>$0.00</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td>Deposito en Bancos</td>";        
        $table .= "    <td>$0.00</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td>Subtotal</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td style=\"border-bottom: 1px;border-bottom-color: black;border-style: solid;\">$0.00</td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td>Efectivo Pagado al Negocio</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td>Due&ntildeo, Socio</td>";        
        $table .= "    <td>$0.00</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td>Gastos micelaneos</td>";        
        $table .= "    <td style=\"border-bottom: 1px;border-bottom-color: black;border-style: solid;\">$0.00</td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td>Subtotal</td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td style=\"border-bottom: 1px;border-bottom-color: black;border-style: solid;\">$0.00</td>";     
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        
        $table .= "  <tr style = \"background:#C4E8F8;\">";       
        $table .= "    <td><b>Total en Efectivo Pagado</b></td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td style=\"border-bottom: 1px;border-bottom-color: black;border-style: solid;\"><b>$0.00</b></td>";     
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
                                        
        
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        
        $table .= "  <tr style = \"background:#C4E8F8;\">";       
        $table .= "    <td><b>Efectivo Disponible Recibido</b></td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td><b>$0.00</b></td>";                
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";        
        $table .= "    <td>Efectivo Disponible Real</td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";                     
        $table .= "    <td></td>";        
        $table .= "    <td style=\"border-bottom: 1px;border-bottom-color: black;border-style: solid;\">$0.00</td>";
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";      
        $table .= "    <td></td>";
        $table .= "  </tr>";
        
        $table .= "  <tr style = \"background:#C4E8F8;\">";        
        $table .= "    <td colspan = \"7\"><b>Flujo de efectivo:</b></td>";
        $table .= "    <td><b>$0.00</b></td>";                     
        $table .= "  </tr>";
        
        $table .= "</table>";
        
        $page->addComponent($table);
       
        
        /* ********************************************************************* 
         * Configuracion
         * ********************************************************************* */
        $page->nextTab("Configuracion");

        $page->addComponent( new TitleComponent( "Ventas" ) );


        
                                        


	$page->render();
