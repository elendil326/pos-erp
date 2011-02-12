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
            if($venta->getIdCliente() < 0){
                echo "Caja Comun";
            }else{
                echo ClienteDAO::getByPK( $venta->getIdCliente() )->getNombre();
            }

        ?></td>
		    <td><b>Descuento</b></td>
	        <td><?php echo percentFormat($venta->getDescuento()); ?></td>

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
            echo SucursalDAO::getByPK( $venta->getIdSucursal() )->getDescripcion();
        ?></td>

	    <?php if($venta->getTipoVenta() == 'credito'){ ?>
	        <td><b>Saldo pendiente</b></td>
	        <td><b style="color: red"><?php echo moneyFormat($venta->getPagado()-$venta->getTotal()); ?></b></td>
	    <?php } ?>
    </tr>

    <tr>
        <td><b>Cajero</b></td>
        <td><?php 
            echo UsuarioDAO::getByPK( $venta->getIdUsuario() )->getNombre();
        ?></td>
    </tr>



</table>


<h2>Articulos en la venta</h2><?php


//render the table
$header = array( 
	"id_producto" => "ID", 
	"descripcion" => "Descripcion", 
	"cantidad" => "Cantidad",
	"precio" => "Precio" );

$tabla = new Tabla( $header, $detalles['items'] );
$tabla->addColRender( 'precio', "moneyFormat" );
$tabla->render();








if($venta->getTipoVenta() == 'credito'){
    ?><h2>Abonos a esta venta</h2><?php

    $abonos = listarAbonos($venta->getIdCliente(), $venta->getIdVenta() );

    $header = array( 
	    "id_pago" => "Pago", 
	    "id_venta" => "Venta", 
	    "sucursal" => "Sucursal",
	    "cajero" => "Cajero",
	    "fecha" => "Fecha",
	    "monto" => "Monto" );

    $tabla = new Tabla( $header, $abonos );
    $tabla->addColRender( 'precio', "moneyFormat" );
    $tabla->addColRender( 'monto', "moneyFormat" );
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
                window.location = 'ventas.php?action=detalles&success=true&id=<?php  echo $_REQUEST["id"]; ?>&reason=' + reason;
	      }
	    });
    }
</script>




<?php if($venta->getTipoVenta() == 'credito' && $venta->getLiquidada() != 1 ){ ?>


<br><br><br>
<div id="abonar">
<h4><input type="button" value="Abonar a esta venta" onClick="abonar()" ></h4>
</div>


<div id="abonar_detalles" style="display:none;">
	<h2>Detalles del nuevo abono</h2>
    <table >
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
