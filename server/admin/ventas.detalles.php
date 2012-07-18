<script>
    jQuery("#MAIN_TITLE").html( "Detalles de la venta");
</script><?php
require_once("controller/ventas.controller.php");
require_once("controller/clientes.controller.php");
require_once("model/cliente.dao.php");
require_once("model/sucursal.dao.php");
require_once("model/usuario.dao.php");

$detalles = detalleVenta($_REQUEST['id']);
$venta = $detalles['detalles'];
?><h2>Detalles</h2>


<table cellspacing="2" cellpadding="2" border=0 style="width:100%">
    <tr>
        <td ><b>ID Venta</b></td>
        <td><?php echo $venta->getIdVenta(); ?></td>
        <td><b>Subtotal</b></td>
        <td><?php echo moneyFormat($venta->getSubtotal()); ?></td>
    </tr>

    <tr>
        <td><b>Cliente</b></td>
        <td><?php
if ($venta->getIdCliente() < 0) {
    echo "Caja Comun";
} else {
    echo ClienteDAO::getByPK($venta->getIdCliente())->getRazonSocial();
}
?></td>
        <td><b>Descuento</b></td>
        <td><?php echo "<span style = '" . ($venta->getDescuento() > 0 ? "color:red" : "") . "'>" . moneyFormat($venta->getDescuento()) . "</span>"; ?></td>

    </tr>

    <tr>
        <td><b>Tipo Venta</b></td>
        <td><?php echo $venta->getTipoVenta(); ?></td>
        <td><b>Total</b></td>
        <td><?php echo moneyFormat($venta->getTotal()); ?></td>
    </tr>

    <tr>
        <td><b>Fecha</b></td>
        <td><?php echo toDate($venta->getFecha()); ?></td>
        <td><b>Pagado</b></td>
        <td><?php echo moneyFormat($venta->getPagado()); ?></td>
    </tr>





    <tr>
        <td><b>Sucursal</b></td>
        <td><?php
            $suc = SucursalDAO::getByPK($venta->getIdSucursal());

            if ($suc)
                echo $suc->getDescripcion();
            else
                echo "Sucursal invalida";
?></td>

        <?php if ($venta->getTipoVenta() == 'credito') {
 ?>
                <td><b>Saldo pendiente</b></td>
                <td><b style="color: red"><?php echo moneyFormat($venta->getPagado() - $venta->getTotal()); ?></b></td>
<?php } ?>
        </tr>

        <tr>
            <td><b>Cajero</b></td>
            <td><?php
            echo UsuarioDAO::getByPK($venta->getIdUsuario())->getNombre();
?></td>
    </tr>



</table>


<h2>Articulos en la venta</h2><?php

            function renderProd($qty, $row) {
                if ($qty == 0)
                    return "";
                return number_format($qty, 2) . "&nbsp;" . $row['escala'] . "s";
            }

            function renderMoney($money, $row) {
                if ($money == 0)
                    return "";
                return moneyFormat($money);
            }

//buscar si el prodcuto es procesable
//render the table
            $header = array(
                "id_producto" => "ID",
                "descripcion" => "Descripcion",
                "cantidad" => "Cantidad original",
                "precio" => "Precio original",
                "cantidadProc" => "Cantidad procesada",
                "precioProc" => "Precio procesada",
                "descuento" => "Descuento");

            /* echo "<pre>";
              var_dump($detalles);
              var_dump($venta);
              echo "</pre>"; */

            $tabla = new Tabla($header, $detalles['items']);
            $tabla->addColRender('precio', "renderMoney");
            $tabla->addColRender('precioProc', "renderMoney");
            $tabla->addColRender('cantidad', 'renderProd');
            $tabla->addColRender('cantidadProc', 'renderProd');
            $tabla->addColRender('descuento', 'renderProd');
            $tabla->render();








            if ($venta->getTipoVenta() == 'credito') {
?><h2>Abonos a esta venta</h2><?php
                $abonos = listarAbonos($venta->getIdCliente(), $venta->getIdVenta());

                $header = array(
                    "id_pago" => "Pago",
                    "id_venta" => "Venta",
                    "sucursal" => "Sucursal",
                    "cajero" => "Cajero",
                    "fecha" => "Fecha",
                    "monto" => "Monto");

                $tabla = new Tabla($header, $abonos);
                $tabla->addColRender('precio', "moneyFormat");
                $tabla->addColRender('monto', "moneyFormat");
                $tabla->addColRender('fecha', "toDate");
                $tabla->addNoData("No se han hecho abonos a esta venta");
                $tabla->render();
            }
?>
            <script>
                function cancelar(){
                    jQuery("#abonar").slideDown("slow", function (){
                        jQuery("#abonar_detalles").slideUp("slow", function (){


                        });
                    });
                }

                function abonar(){
                    jQuery("#abonar").slideUp("slow", function (){
                        jQuery("#abonar_detalles").slideDown("slow", function (){


                        });
                    });
                }

                var payment = "efectivo";

                function setPayment(tipo){
                    payment = tipo;

                }

                function doAbonar(){
                    jQuery.ajaxSettings.traditional = true;
                    json = {
                        id_venta : <?php echo $_REQUEST['id']; ?>,
                        monto :     parseFloat( jQuery("#abonar_cantidad").val() ),
                        tipo_pago : payment
                    };

                    jQuery.ajax({
                        url: "../proxy.php",
                        data: {
                            action : 305,
                            data : jQuery.JSON.encode(json)
                        },
                        cache: false,
                        success: function(data){
                            try{
                                response = jQuery.parseJSON(data);
                            }catch(e){
                                jQuery("#loader").hide();
                                window.scroll(0,0);
                                return jQuery("#ajax_failure").html("Error en el servidor. Intente de nuevo.").show();
                            }

                            if(response.success == false){
                                jQuery("#loader").hide();
                                window.scroll(0,0);
                                return jQuery("#ajax_failure").html(response.reason).show();
                            }


                            reason = "Abono registrado correctamente";
                            window.location = 'ventas.php?action=detalles&success=true&id=<?php echo $_REQUEST["id"]; ?>&reason=' + reason;
                        }
                    });
                }
            </script>




<?php if ($venta->getTipoVenta() == 'credito' && $venta->getLiquidada() != 1) { ?>


                <br><br><br>
                <div id="abonar">
                    <h4><input type="button" value="Abonar a esta venta" onClick="abonar()" ></h4>
                </div>


                <div id="abonar_detalles" style="display:none;">
                    <h2>Detalles del nuevo abono</h2>
                    <table style="width:100%">
                        <tr>
                            <td>Cantidad </td>
                            <td><input type="text" id="abonar_cantidad" ></td>
                        </tr>
                        <tr>
                            <td>Tipo de pago </td>
                            <td>
                                <input type="radio" name="tipo_pago_input" onChange="setPayment(this.value)" value="efectivo" checked="checked" /> Efectivo<br />
                                <input type="radio" name="tipo_pago_input" onChange="setPayment(this.value)" value="cheque"  /> Cheque<br />
                                <input type="radio" name="tipo_pago_input" onChange="setPayment(this.value)" value="tarjeta" /> Tarjeta<br />
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <h4><input type="button" value="Cancelar" onClick="cancelar()" >
                                    <input type="button" value="Abonar" onClick="doAbonar()" ></h4>
                            </td>
                        </tr>
                    </table>
                </div>
<?php } ?>



            <script>
                function printComprobante(){
                    window.location = "../proxy.php?action=1306&id_venta=<?php echo $_REQUEST['id']; ?>" ;
                }

<?php
            /**
             * Please print
             *
             * */
            if (isset($_REQUEST["pp"]) && $_REQUEST["pp"]) {
?>
                        Ext.Msg.confirm(
                        "Surtir sucursal",
                        "Venta exitosa. &iquest; Desea imprimir un comprobante ?",
                        function(res){
                            if(res == "yes"){
                                printComprobante();
                            }
                        }
                    );
<?php
            }
?>
                var facturaMode = "generica";



                /**
                 *
                 **/
                function showModoFactura(){

                    jQuery("#submitButtons").slideUp("fast",function(){
                        jQuery("#factura-mode").slideDown();
                    });

                }


                /**
                 *
                 **/
                function seleccionarModoFactura(element){
                    //document.getElementById('factura_general').style.display = 'block';

                    facturaMode = element.value;

                    switch(element.value){
                        case 'generica':
                            document.getElementById('factura_generica').style.display = 'block';
                            break;
                        case 'detallada':
                            document.getElementById('factura_generica').style.display = 'none';
                            break;
                    }

                }



                /**
                 *
                 **/
                function hideModoFactura(){

                    jQuery("#submitButtons").slideDown("fast",function(){
                        jQuery("#factura-mode").slideUp();
                    });

                }



                /**
                 *
                 **/
                function facturar(){
                    //window.location = "../proxy.php?action=1200&id_venta=<?php echo $_REQUEST["id"]; ?>";

                    var factura_generica = null;

                    if(facturaMode == "generica"){

                        if(jQuery("#factura-concepto").val().replace(/ /g,"").length == 0){
                            Ext.Msg.alert("Error","Debe de ingresar un concepto para realizar una factura generica.");
                            return;
                        }else{
                            factura_generica = {
                                id_producto: 'GEN01',
                                descripcion: jQuery("#factura-concepto").val(),
                                unidad: 	'unidad'
                            };
                        }

                    }

                    //hacer ajaxaso
                    jQuery.ajaxSettings.traditional = true;

                    jQuery("#loader").fadeIn("slow", function(){
                        jQuery("#factura-mode").slideUp();
                    });


                    jQuery.ajax({
                        url: "../proxy.php",
                        data: {
                            action : 1200,
                            factura_generica : jQuery.JSON.encode(factura_generica),
                            id_venta : <?php echo $_REQUEST["id"]; ?>
                        },
                        cache: false,
                        success: function(data){
                            try{
                                response = jQuery.parseJSON(data);
                            }catch(e){

                                jQuery("#loader").fadeOut('slow', function(){

                                    window.scroll(0,0);
                                    jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
                                    jQuery("#submitButtons").fadeIn();
                                });
                                return;
                            }


                            if(!response.success){
                                jQuery("#ajax_failure").html(response.reason).show();

                                jQuery("#loader").fadeOut('slow', function(){
                                    window.scroll(0,0);
                                    jQuery("#submitButtons").fadeIn();
                                });
                                return ;
                            }

                            window.open("../proxy.php?action=1308&id_venta=<?php echo $_REQUEST["id"]; ?>");

                            reason = "Su venta ha sido facturada.";
                            window.location = "ventas.php?action=detalles&id=<?php echo $_REQUEST["id"]; ?>&success=true&reason=" + reason;

                        }
                    });

                }

                function cancelarFactura(id_venta){
                    jQuery.ajax({
                        url: "../proxy.php",
                        data: {
                            action : 1201,
                            id_venta : id_venta
                        },
                        cache: false,
                        success: function(data){
                            try{
                                response = jQuery.parseJSON(data);
                            }catch(e){

                                jQuery("#loader").fadeOut('slow', function(){

                                    window.scroll(0,0);
                                    jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
                                    jQuery("#submitButtons").fadeIn();
                                });
                                return;
                            }


                            if(!response.success){
                                jQuery("#ajax_failure").html(response.reason).show();

                                jQuery("#loader").fadeOut('slow', function(){
                                    window.scroll(0,0);
                                    jQuery("#submitButtons").fadeIn();
                                });
                                return ;
                            }

                            reason = "Su venta ha sido cancelada, este al pendiente de su confirmación.";
                            window.location = "ventas.php?action=detalles&id=<?php echo $_REQUEST["id"]; ?>&success=true&reason=" + reason;

                        }
                    });
                }



            </script>

            <div align=center>
                <h4 id="submitButtons">

                    <input type=button value="Imprimir comprobante" onClick="printComprobante()">
                    <?php
                    //if ($venta->getLiquidada() && !$venta->getCancelada()) {
                        if ((POS_FACTURACION_ALL ? true : $venta->getLiquidada() && !$venta->getCancelada() ) ) {
                            $q = new FacturaVenta();
                            $q->setIdVenta($venta->getIdVenta());
                            $q->setActiva(1);
                            $res = FacturaVentaDAO::search($q);

                            if (sizeof($res) == 0) {
                            //no se ha hecho factura
                    ?>
                    
                    <input type="button" value="Facturar esta venta" onClick='showModoFactura()' >

                    <?php
                            } else {
                            //ya se ha hecho factura !
                    ?>

                    <input type="button" value="Imprimir Factura" onClick='window.location = "../proxy.php?action=1308&id_venta=<?php echo $_REQUEST["id"]; ?>";'>
                    <!--<input type="button" value="Cancelar Factura" onClick='window.location = "../proxy.php?action=1201&id_venta=<?php //echo $_REQUEST["id"]; ?>";'> -->
                    <input type="button" value="Cancelar Factura" onClick="cancelarFactura('<?php echo $_REQUEST["id"]; ?>')">

                    <?php
                            }
                        }
                    ?>
    </h4>
</div>


<div id="loader" style="display: none;" align="center"  >
	Procesando <img src="../media/loader.gif"> 
</div>



<div id="factura-mode" style="display:none">
    <h2>Seleccione el modo de facturacion</h2>
    <br/>
    <table width ="100%" align="center">
        <tr>
            <td>
                <input type="radio" name="group-factura" value="generica"  onClick = "seleccionarModoFactura(this);" checked> Factura Generica<br>
                <input type="radio" name="group-factura" value="detallada" onClick = "seleccionarModoFactura(this);" > Factura Detallada<br>                    
            </td>
            <td>
                <div id="factura_generica" align=center style="display:block">
                    <b>Concepto</b> <input id ="factura-concepto" style ="height:25px; width: 250px; font-size:120% "type ="text" placeholder="Ingrese el concepto de la factura">               
                </div>
            </td>
        </tr>
        <tr>
            <td colspan ="2" align="center">
                <h4><input type ="button" value ="Cancelar" onClick='hideModoFactura()'><input type ="button" value ="Facturar" onClick='facturar()'></h4>
            </td>
        </tr>
    </table>		
</div>