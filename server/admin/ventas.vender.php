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
					var datosClientes = <?php echo json_encode( $clientes ); ?>;
					
					function getCliente( id_cliente ){
						for (var c = datosClientes.length - 1; c >= 0; c--){
							if( parseInt(datosClientes[c].id_cliente) == id_cliente){
								return datosClientes[c];
							}
						}
						return null;
					}
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
            id_cliente: CLIENTE !== null ? CLIENTE : jQuery("#cliente_selector").val() ,
            tipo_venta: venta.tipo_de_venta,
            tipo_pago:  venta.tipo_de_pago,
            factura:    venta.factura,
            items: [  ]
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




				window.open( "../impresora/pdf.php?json=%7B%22tipo_venta%22%3A%20%22contado%22%2C%22items%22%3A%20%5B%7B%22descripcion%22%3A%20%22papas%20primeras%22%2C%22existencias%22%3A%20%221123%22%2C%22existencias_procesadas%22%3A%20%22462%22%2C%22tratamiento%22%3A%20%22limpia%22%2C%22precioVenta%22%3A%20%2211%22%2C%22precioVentaSinProcesar%22%3A%20%228.5%22%2C%22precio%22%3A%20%2211%22%2C%22id_producto%22%3A%201%2C%22escala%22%3A%20%22kilogramo%22%2C%22precioIntersucursal%22%3A%20%2210%22%2C%22precioIntersucursalSinProcesar%22%3A%20%229%22%2C%22procesado%22%3A%20%22true%22%2C%22cantidad%22%3A%204%2C%22idUnique%22%3A%20%221_1%22%7D%2C%7B%22descripcion%22%3A%20%22papas%20primeras%22%2C%22existencias%22%3A%20%221123%22%2C%22existencias_procesadas%22%3A%20%22462%22%2C%22tratamiento%22%3A%20%22limpia%22%2C%22precioVenta%22%3A%20%2211%22%2C%22precioVentaSinProcesar%22%3A%20%228.5%22%2C%22precio%22%3A%20%2211%22%2C%22id_producto%22%3A%201%2C%22escala%22%3A%20%22kilogramo%22%2C%22precioIntersucursal%22%3A%20%2210%22%2C%22precioIntersucursalSinProcesar%22%3A%20%229%22%2C%22procesado%22%3A%20%22true%22%2C%22cantidad%22%3A%204%2C%22idUnique%22%3A%20%221_1%22%7D%2C%7B%22descripcion%22%3A%20%22papas%20primeras%22%2C%22existencias%22%3A%20%221123%22%2C%22existencias_procesadas%22%3A%20%22462%22%2C%22tratamiento%22%3A%20%22limpia%22%2C%22precioVenta%22%3A%20%2211%22%2C%22precioVentaSinProcesar%22%3A%20%228.5%22%2C%22precio%22%3A%20%2211%22%2C%22id_producto%22%3A%201%2C%22escala%22%3A%20%22kilogramo%22%2C%22precioIntersucursal%22%3A%20%2210%22%2C%22precioIntersucursalSinProcesar%22%3A%20%229%22%2C%22procesado%22%3A%20%22true%22%2C%22cantidad%22%3A%204%2C%22idUnique%22%3A%20%221_1%22%7D%2C%7B%22descripcion%22%3A%20%22papas%20primeras%22%2C%22existencias%22%3A%20%221123%22%2C%22existencias_procesadas%22%3A%20%22462%22%2C%22tratamiento%22%3A%20%22limpia%22%2C%22precioVenta%22%3A%20%2211%22%2C%22precioVentaSinProcesar%22%3A%20%228.5%22%2C%22precio%22%3A%20%2211%22%2C%22id_producto%22%3A%201%2C%22escala%22%3A%20%22kilogramo%22%2C%22precioIntersucursal%22%3A%20%2210%22%2C%22precioIntersucursalSinProcesar%22%3A%20%229%22%2C%22procesado%22%3A%20%22true%22%2C%22cantidad%22%3A%204%2C%22idUnique%22%3A%20%221_1%22%7D%2C%7B%22descripcion%22%3A%20%22papas%20primeras%22%2C%22existencias%22%3A%20%221123%22%2C%22existencias_procesadas%22%3A%20%22462%22%2C%22tratamiento%22%3A%20%22limpia%22%2C%22precioVenta%22%3A%20%2211%22%2C%22precioVentaSinProcesar%22%3A%20%228.5%22%2C%22precio%22%3A%20%2211%22%2C%22id_producto%22%3A%201%2C%22escala%22%3A%20%22kilogramo%22%2C%22precioIntersucursal%22%3A%20%2210%22%2C%22precioIntersucursalSinProcesar%22%3A%20%229%22%2C%22procesado%22%3A%20%22true%22%2C%22cantidad%22%3A%204%2C%22idUnique%22%3A%20%221_1%22%7D%2C%7B%22descripcion%22%3A%20%22papas%20primeras%22%2C%22existencias%22%3A%20%221123%22%2C%22existencias_procesadas%22%3A%20%22462%22%2C%22tratamiento%22%3A%20%22limpia%22%2C%22precioVenta%22%3A%20%2211%22%2C%22precioVentaSinProcesar%22%3A%20%228.5%22%2C%22precio%22%3A%20%2211%22%2C%22id_producto%22%3A%201%2C%22escala%22%3A%20%22kilogramo%22%2C%22precioIntersucursal%22%3A%20%2210%22%2C%22precioIntersucursalSinProcesar%22%3A%20%229%22%2C%22procesado%22%3A%20%22true%22%2C%22cantidad%22%3A%204%2C%22idUnique%22%3A%20%221_1%22%7D%2C%7B%22descripcion%22%3A%20%22papa%20segunda%22%2C%22existencias%22%3A%20%221984%22%2C%22existencias_procesadas%22%3A%20%221477%22%2C%22tratamiento%22%3A%20%22limpia%22%2C%22precioVenta%22%3A%20%229%22%2C%22precioVentaSinProcesar%22%3A%20%220%22%2C%22precio%22%3A%20%229%22%2C%22id_producto%22%3A%202%2C%22escala%22%3A%20%22kilogramo%22%2C%22precioIntersucursal%22%3A%20%229%22%2C%22precioIntersucursalSinProcesar%22%3A%20%220%22%2C%22procesado%22%3A%20%22true%22%2C%22cantidad%22%3A%206%2C%22idUnique%22%3A%20%222_2%22%7D%2C%7B%22descripcion%22%3A%20%22papas%20primeras%22%2C%22existencias%22%3A%20%221123%22%2C%22existencias_procesadas%22%3A%20%22462%22%2C%22tratamiento%22%3A%20%22limpia%22%2C%22precioVenta%22%3A%20%2211%22%2C%22precioVentaSinProcesar%22%3A%20%228.5%22%2C%22precio%22%3A%20%228.5%22%2C%22id_producto%22%3A%201%2C%22escala%22%3A%20%22kilogramo%22%2C%22precioIntersucursal%22%3A%20%2210%22%2C%22precioIntersucursalSinProcesar%22%3A%20%229%22%2C%22procesado%22%3A%20%22false%22%2C%22cantidad%22%3A%204%2C%22idUnique%22%3A%20%221_3%22%7D%2C%7B%22descripcion%22%3A%20%22papa%20segunda%22%2C%22existencias%22%3A%20%221984%22%2C%22existencias_procesadas%22%3A%20%221477%22%2C%22tratamiento%22%3A%20%22limpia%22%2C%22precioVenta%22%3A%20%229%22%2C%22precioVentaSinProcesar%22%3A%20%220%22%2C%22precio%22%3A%206%2C%22id_producto%22%3A%202%2C%22escala%22%3A%20%22kilogramo%22%2C%22precioIntersucursal%22%3A%20%229%22%2C%22precioIntersucursalSinProcesar%22%3A%20%220%22%2C%22procesado%22%3A%20%22false%22%2C%22cantidad%22%3A%202%2C%22idUnique%22%3A%20%222_4%22%7D%5D%2C%22cliente%22%3A%20%7B%22id_cliente%22%3A%20%221%22%2C%22rfc%22%3A%20%22jija6778787%22%2C%22nombre%22%3A%20%22Jose%20alfredo%20jimenez%22%2C%22direccion%22%3A%20%22monte%20alban%20123%20col%20rosalinda%22%2C%22ciudad%22%3A%20%22celayaa%22%2C%22telefono%22%3A%20%22%22%2C%22e_mail%22%3A%20%22%22%2C%22limite_credito%22%3A%20%2219000%22%2C%22descuento%22%3A%20%220%22%2C%22activo%22%3A%20%221%22%2C%22id_usuario%22%3A%20%22101%22%2C%22id_sucursal%22%3A%20%221%22%2C%22fecha_ingreso%22%3A%20%222011-01-09%2002%3A11%3A30%22%2C%22credito_restante%22%3A%2015991%7D%2C%22factura%22%3A%20false%2C%22tipo_pago%22%3A%20%22efectivo%22%2C%22subtotal%22%3A%205250%2C%22total%22%3A%200%2C%22pagado%22%3A%20%22150%22%2C%22id_venta%22%3A%2060%2C%22empleado%22%3A%20%22Alan%20gonzalez%20hernandez%22%2C%22sucursal%22%3A%20%7B%22id_sucursal%22%3A%20%221%22%2C%22gerente%22%3A%20null%2C%22descripcion%22%3A%20%22papas%20supremas%201%22%2C%22direccion%22%3A%20%22monte%20radiante%20123%20col%20centro%2C%20celaya%22%2C%22rfc%22%3A%20%22alskdfjlasdj8787%22%2C%22telefono%22%3A%20%221726376672%22%2C%22token%22%3A%20null%2C%22letras_factura%22%3A%20%22c%22%2C%22activo%22%3A%20%221%22%2C%22fecha_apertura%22%3A%20%222011-01-09%2001%3A38%3A26%22%2C%22saldo_a_favor%22%3A%20%220%22%7D%2C%22ticket%22%3A%20true%7D" );


                reason = "Venta exitosa.";
                window.location = 'ventas.php?action=lista&success=true&reason=' + reason;
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
	if($e == "NA"){
		return  "<i>N/A</i>";
	}
	return "<b>" . number_format($e, 2) . "</b>kg";
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



