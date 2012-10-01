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
                                
        
        
        /* ********************************************************************* 
         * Corte
         * ********************************************************************* */
        $page->nextTab("Corte");               
        
        $page->addComponent(new TitleComponent("Sucursal " . SucursalDAO::getByPK($_REQUEST['s'])->getRazonSocial(),1));               
                
        $table = "";
                
        $table .= "<table>";        
        $table .= "  <tr>";       
        $table .= "    <td><b>Corte</b></td>";
        $table .= "    <td><b>Del</b></td>";        
        $table .= "    <td colspan= \"2\">14/SEP/2012</td>";        
        $table .= "    <td><b>" . date("d/m/Y g:i:s A", time()) . "</b></td>";        
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
        $table .= "    <td>Subtotal</td>";
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td></td>";        
        $table .= "    <td>$0.00</td>";        
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
        $table .= "    <td>$0.00</td>";
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
                                        
        $menu = new MenuComponent();        
        $menu->addItem("Realizar Corte", "ventas.corte.php");
        $page->addComponent( $menu);

        /* ********************************************************************* 
         * Sucursales
         * ********************************************************************* */
        //$page->nextTab("<a href=\"ventas.php#Corte\">Sucurlsales</a>");         
        $page->nextTab("Sucurlsales");                                         
        
        $sucursales = SucursalDAO::getAll();
        
        $html = "";
        
        $html .= "<script>";
        
        $html .= "  var corteSucursal = function(combo){";                
        
        $html .= "      var indice = combo.selectedIndex;";
        $html .= "      var valor = combo.options[combo.selectedIndex].text;";                        
        $html .= "      location.href = 'ventas.corte.php?s=' + indice; ";
                
        $html .= "  }";
        
        $html .= "</script>";
        
        $html .= "<table>";        
        $html .= "  <tr>";        
        $html .= "    <td>Sucursal:</td>";        
        $html .= "    <td>";        
        $html .= "      <SELECT onChange=\"corteSucursal(this);\">";
        $html .= "       <OPTION VALUE=\"#\">Seleccione una Sucursal</OPTION>";
        
        foreach($sucursales as $sucursal){
            $html .= "       <OPTION value=\"". $sucursal->getIdSucursal() . "\">" . $sucursal->getRazonSocial() . "</OPTION>";
        }
        
        $html .= "      </SELECT>";        
        $html .= "    </td>";        
        $html .= "  </tr>";        
        $html .= "</table>";                
        
        $page->addComponent($html);

	$page->render();
