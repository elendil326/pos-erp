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
                echo ClienteDAO::getByPK( $venta->getIdCliente() )->getRazonSocial();
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

			$suc = SucursalDAO::getByPK( $venta->getIdSucursal() );

			if($suc)
				echo $suc->getDescripcion();
			else 
				echo "Sucursal invalida";
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

function renderProd($qty, $row){
	if($qty == 0) return "";
	return number_format($qty, 2) . "&nbsp;" . $row['escala'] . "s";
}

function renderMoney($money, $row){
	if($money == 0) return "";
	return moneyFormat($money) ;
}

//render the table
$header = array( 
	"id_producto" => "ID", 
	"descripcion" => "Descripcion", 
	"cantidad" => "Cantidad orignal",
	"precio" => "Precio original",
	"cantidadProc" => "Cantidad procesada",
	"precioProc" => "Precio procesada" );

$tabla = new Tabla( $header, $detalles['items'] );
$tabla->addColRender( 'precio', "renderMoney" );
$tabla->addColRender( 'precioProc', "renderMoney" );
$tabla->addColRender( 'cantidad', 'renderProd');
$tabla->addColRender( 'cantidadProc', 'renderProd');
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
    $tabla->addColRender( 'fecha', "toDate" );
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
		//please print
		if(isset($_REQUEST["pp"]) && $_REQUEST["pp"]){
			?>
				Ext.Msg.confirm("Surtir sucursal",
				"Venta exitosa. &iquest; Desea imprimir un comprobante ?",
				function(res){

					if(res == "yes"){
						printComprobante();
					}
				} )
			<?php
		}
	?>
	
	function facturar(){
		//window.location = "../proxy.php?action=1200&id_venta=<?php echo $_REQUEST["id"]; ?>";
		
	    //hacer ajaxaso
	    jQuery.ajaxSettings.traditional = true;

	    jQuery("#submitButtons").fadeOut("slow",function(){
	        jQuery("#loader").fadeIn();

	        jQuery.ajax({
	        url: "../proxy.php",
	        data: { 
	            action : 1200, 
	            id_venta : <?php echo $_REQUEST["id"]; ?>
	        },
	        cache: false,
	        success: function(data){
	            try{
	                response = jQuery.parseJSON(data);
	            }catch(e){

	                jQuery("#loader").fadeOut('slow', function(){
	                    jQuery("#submitButtons").fadeIn();
	                    window.scroll(0,0);                         
	                    jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
	                    jQuery("#submitButtons").fadeIn();
	                });                
	                return;                    
	            }


	            if(response.success === false){

	                jQuery("#loader").fadeOut('slow', function(){
	                    //jQuery("#submitButtons").fadeIn();    
	                    window.scroll(0,0);                                                             
	                    jQuery("#ajax_failure").html(response.reason).show();
	                    jQuery("#submitButtons").fadeIn();                  
	                });                
	                return ;
	            }

	            reason = "Su venta ha sido facturada.";
	            window.location = "ventas.php?action=detalles&id=<?php echo $_REQUEST["id"]; ?>&success=true&reason=" + reason;

	        }
	        });
	    });
	}
	
</script>


<h4 id="submitButtons">
	<input type=button value="Imprimir comprobante" onClick="printComprobante()">
	<?php
		if($venta->getLiquidada() && !$venta->getCancelada()){
			$q = new FacturaVenta();
			$q->setIdVenta( $venta->getIdVenta() );
			$res = FacturaVentaDAO::search(  $q  );
		
			if(sizeof($res) == 0){
				//no se ha hecho factura
				?><input type="button" value="Facturar esta venta" onClick='facturar()' ><?php
			}else{
				//ya se ha hecho factura !
				?><input type="button" value="Reimprimir factura" onClick='window.location = "../proxy.php?action=1305&id_venta=<?php echo $_REQUEST["id"]; ?>";'><?php
			}
		}
	?>
</h4>
<div id="loader" 		style="display: none;" align="center"  >
	Procesando <img src="../media/loader.gif"> 
</div>