<?php

	require_once("controller/inventario.controller.php");
	require_once("controller/sucursales.controller.php");
	require_once('controller/clientes.controller.php');
	require_once('model/cliente.dao.php');



	$iMaestro = listarInventarioMaestro(200, POS_SOLO_ACTIVOS) ;
?>

<script>
	jQuery("#MAIN_TITLE").html("Venta a cliente");
</script>

<h2>Detalles del Cliente</h2>


<?php
	if(!isset($_REQUEST['cid'])){
	    $clientes = listarClientes();
    
        ?>
                <script>
                    var CLIENTE = null;
                </script>
        <?php

		if(sizeof($clientes ) > 0){
			echo '<select id="cliente_selector"> ';    
			foreach( $clientes as $c ){
				echo "<option value='" . $c['id_cliente'] . "' >" . $c['nombre']  . "</option>";
			}
			echo '</select>';    
		}else{
		
			echo "<h3>No hay clientes a quien realizarle la venta</h3>";
		}
	}else{
	
		$cliente = ClienteDAO::getByPK( $_REQUEST['cid'] );
		
		if($cliente === null){
			?>
                <h3>Este cliente no existe</h3>

            <?php
		}else{
		
		?>
            <script>
                var CLIENTE = <?php echo $cliente->getIdCliente(); ?>;
            </script>
			<table border="0" cellspacing="1" cellpadding="1">
				<tr><td><b>Nombre</b></td><td><?php echo $cliente->getNombre(); ?></td><td rowspan=12><div id="map_canvas"></div></td></tr>
				<tr><td><b>RFC</b></td><td><?php echo $cliente->getRFC(); ?></td></tr>
				<tr><td><b>Limite de Credito</b></td><td><?php echo moneyFormat($cliente->getLimiteCredito()); ?></td></tr>	
				<tr><td><b>Descuento</b></td><td><?php echo percentFormat( $cliente->getDescuento() ); ?></td></tr>
			</table>
		
		<?php
		
		}
	
	}


?>
<style>
	.tabla-inventario{
		font-size: 12px;
	}
</style>

<script>

	var carrito = [];
    var venta = {
           tipo_de_venta : 'credito', //{ credito, contado }
           tipo_de_pago : null, //{ efectivo, tarjeta, cheque }           
           factura : false,
    };

	
	function remove(data){
		jQuery("#" + data).css("color", "");
		jQuery("#" + data).css("background-color", "");	
		renderCarrito();

	}
	
	function add(data){
	
		//buscar en el carrito
		
		for( a = 0; a < carrito.length; a++){
			if( carrito[a].tablaid == data ){
			
				//ya esta, hay que quitarlo
				carrito.splice( a, 1 );
				return remove(data);
			}
		}
		
		jQuery("#" + data).css("color", "#fff ");
		jQuery("#" + data).css("background-color", "#3F8CE9 !important");
		
        //buscar este producto en el arreglo de inventario maestro
        producto = inventario_maestro[ data.substring(7) ];

		carrito.push( {
			qty : 1,
			tablaid : data,
            id_producto : producto.id_producto,
            id_compra_proveedor : producto.id_compra_proveedor,
            procesado : false,
            precio : producto.precio_por_kg
		});
		
		renderCarrito();
	}
	
	function doMath(){


        //total de importe, y cantidad
        var t_qty = 0, t_importe = 0;

        for(a =0; a < carrito.length; a++){
            
            //recorreer los inputs
            inputs = jQuery("#finalcart" + carrito[a].tablaid).children().children();


            procesada  =    jQuery( inputs[0] ).val();
            cantidad =      parseFloat( jQuery( inputs[1] ).val() );
            precio =        parseFloat( jQuery( inputs[2] ).val() );
            
            carrito[a].qty = cantidad;
            carrito[a].precio = precio;

            t_qty += cantidad;
            t_importe += ( cantidad * precio );

            jQuery( inputs[3] ).val( cf(cantidad * precio) );

        }


           jQuery("#total_importe").html( cf( t_importe ) )
           jQuery("#total_qty").html( t_qty )
	}

	
    function dovender(){

        json = {
            id_cliente: CLIENTE !== null ? CLIENTE : jQuery("cliente_selector").val() ,
            tipo_venta: venta.tipo_de_venta,
            tipo_pago:  venta.tipo_de_pago,
            factura:    venta.factura,
            items: []
        };


        for(a =0; a < carrito.length; a++){
            d = jQuery("#finalcart" + carrito[a].tablaid).children();

            //recorreer los inputs
            jQuery( jQuery("#finalcart" + carrito[a].tablaid).children().children()[0] ).val()

            json.items.push({
                id_producto: carrito[a].id_producto,
                id_compra_proveedor: carrito[a].id_compra_proveedor,
                procesado: carrito[a].procesado,
                precio: carrito[a].precio,
                cantidad: carrito[a].qty
            });

        }


        console.log(json);
    	jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 101, 
            payload : jQuery.JSON.encode(json)
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


                reason = "El cargamento se ha registrado con exito";
                window.location = 'inventario.php?action=maestro&success=true&reason=' + reason;
	      }
	    });

    }




	function renderCarrito (){
		html = "<table style='width:100%'>";
		html += "<tr align=left ><th>Remision</th><th>Producto</th><th>Procesada</th><th>Cantidad</th><th>Precio</th><th>Importe</th></tr>";

        tots_qty = 0;
        tots_importe = 0;

		for(a = 0; a < carrito.length; a++){
		
			foo = jQuery("#" + carrito[a].tablaid).children();

			html += "<tr id='finalcart"+ carrito[a].tablaid +"' ><td >" + 	foo[0].innerHTML +"</td>";
			html += "<td>" + foo[1].innerHTML +"</td>";
			html += "<td><input type='checkbox' >" + "</td>";
			html += "<td><input type='text'     onKeyUp='doMath()' value='"+ carrito[a].qty +"'></td>";
			html += "<td><input type='text'     onKeyUp='doMath()' value='"+ carrito[a].precio +"'>" + "</td>";
			html += "<td><input type='text'                        value='"+ cf(carrito[a].precio * carrito[a].qty) +"' disabled></td>";
			html += "</tr>";

            tots_qty += carrito[a].qty;
            tots_importe += (carrito[a].qty) * carrito[a].precio;
			
		}

        //totales
		html += "<tr>";
        html += "<td colspan=3></td>";
        html += "<td id='total_qty'>" + tots_qty +"</td>";
        html += "<td ></td>";
        html += "<td id='total_importe'>" + cf(tots_importe) + "</td>";
		html += "</tr>";

		html += "</table>";
		
        


		
		jQuery("#cart").html(html);
		jQuery("#cart input").uniform();

	}


    function setPaymentType( tipo ){
        venta.tipo_de_venta = tipo;
        switch(tipo){
            case "contado" : 
                jQuery("#tipoDePago").show();
            break;

            case "credito" : 
            default:
                jQuery("#tipoDePago").hide();
                setPayment(null);
        }
    }

    function setPayment( tipo ){
        jQuery("#tipoDePagoInfo").show();
        venta.tipo_de_pago = tipo;

        switch(tipo){
            case "efectivo" : 
                jQuery("#tipoDePagoInfo").html('Efectivo <input type="text">');
            break;

            case "cheque" :
                jQuery("#tipoDePagoInfo").html('Referencia <input type="text">');                
            break;

            case "tarjeta":
                jQuery("#tipoDePagoInfo").html('Datos <input type="text">');
            break;

            default:
                jQuery("#tipoDePagoInfo").hide();
        }
    }

</script>




<h2>Inventario maestro</h2>
<div class="tabla-inventario">
<?php

function toUnit( $e )
{
	return "<b>" . $e . "</b>kg";
}

function toDateS( $d ){
	$foo = toDate($d);
	$bar = explode(" ", $foo);
	return $bar[0];
	 
}



echo "<script>";
echo " var inventario_maestro = [";
foreach($iMaestro as $i){
	echo json_encode($i) . ",";
}
echo "];";
echo "</script>";


$header = array(
	"folio" 			=> "Remision",
	"producto_desc" 	=> "Producto",
	"variedad" 	 		=> "Variedad",
	"arpillas"			=> "Arpillas origen",
	"peso_por_arpilla"	=> "Kg/Arpilla",
	"productor"			=> "Productor",
	"fecha"				=> "Llegada",
	//"transporte"				=> "Transporte",
	"merma_por_arpilla"			=> "Merma",
	//"sitio_descarga_desc"		=> "Sitio de descarga",
	"existencias"				=> "Existencias",
	"existencias_procesadas"	=> "Limpias" );
	
$tabla = new Tabla( $header, $iMaestro );
$tabla->renderRowId("carrito"); //darle id's a las columnas
$tabla->addOnClick("folio", "add", false, true); //enviar el id de la columna al javascriptooor
$tabla->addColRender( "existencias", "toUnit" );
$tabla->addColRender( "existencias_procesadas", "toUnit" );
$tabla->addColRender( "fecha", "toDateS" );
$tabla->render();

?>
</div>




	<!-- -------------------------------
			TABALA DE PRODUCTOS SELECCIONADOS
	  ------------------------------- -->    
<h2>Productos a vender</h2>
    <div id='cart'>

    </div>


	<!-- -------------------------------
            OPCIONES DE PAGO
	  ------------------------------- -->   
<h2>Opciones de pago</h2>

<table width="100%" border=0 align=center>
   <tr >
    <td valign="top" >
        <h3>Tipo de venta</h3>
      <input type="radio" name="tipo_venta_input" onChange="setPaymentType(this.value)" value="credito" checked="checked" /> Credito<br />
      <input type="radio" name="tipo_venta_input" onChange="setPaymentType(this.value)" value="contado"  /> Contado<br />
    </td>


    <td valign="top" id="tipoDePago" style="display:none;">
      <h3>Tipo de pago</h3>
      <input type="radio" name="tipo_pago_input" onChange="setPayment(this.value)" value="efectivo" /> Efectivo<br />
      <input type="radio" name="tipo_pago_input" onChange="setPayment(this.value)" value="cheque"  /> Cheque<br />
      <input type="radio" name="tipo_pago_input" onChange="setPayment(this.value)" value="tarjeta" /> Tarjeta<br />
    </td>

    <td valign="top" id="tipoDePagoInfo" style="display:none;">

    </td>
    </tr>
</table>



    <h4 align="center" id="do-sell">
    	<input type="button" value="Vender" onClick="dovender()">
    	<img src="../media/loader.gif" id="loader" style="display: none;">
    </h4>



