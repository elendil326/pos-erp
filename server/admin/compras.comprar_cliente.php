<?php
	require_once("model/inventario.dao.php");
	require_once("model/actualizacion_de_precio.dao.php");
	require_once("model/cliente.dao.php");
	require_once("controller/clientes.controller.php");


	$clientes = ClienteDAO::getAll();
	
?>


<h2>Seleccion de cliente</h2>
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
		echo '<select id="cliente_selector" > ';    
		foreach( $clientes as $c ){
			if($c['id_cliente'] <= 0 )continue;
			echo "<option value='" . $c['id_cliente'] . "' >" . $c['razon_social']  . "</option>";
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
            var CLIENTE = "<?php echo $cliente->getIdCliente(); ?>";
            var NOMBRECLIENTE = "<?php echo $cliente->getRazonSocial(); ?>";
            var RFCCLIENTE = "<?php echo $cliente->getRFC(); ?>";
            var DIRECCIONCLIENTE = "<?php echo $cliente->getCalle(); ?>";
            var CIUDADCLIENTE = "<?php echo $cliente->getMunicipio(); ?>";
        </script>
		<table border="0" cellspacing="1" cellpadding="1">
			<tr><td><b>Nombre</b></td><td><?php echo $cliente->getRazonSocial(); ?></td><td rowspan=12><div id="map_canvas"></div></td></tr>
			<tr><td><b>RFC</b></td><td><?php echo $cliente->getRFC(); ?></td></tr>
			<tr><td><b>Limite de Credito</b></td><td><?php echo moneyFormat($cliente->getLimiteCredito()); ?></td></tr>	
			<tr><td><b>Descuento</b></td><td><?php echo percentFormat( $cliente->getDescuento() ); ?></td></tr>
		</table>
	
	<?php
	
	}
}
?>


<div  >
	<h2>Productos disponibles</h2>
		<?php
		
		$productos = InventarioDAO::getAll();
		$javascriptWithProds = "";
		
		echo "<table border=0 style='width: 100%; font-size: 14px; cursor: pointer;'>";
		echo "<tr>";
		
		for($a = 0; $a < sizeof($productos); $a++){

				//buscar su precio sugerido actual
				$act = new ActualizacionDePrecio();
				$act->setIdProducto( $productos[$a]->getIdProducto() );
				$res = ActualizacionDePrecioDAO::search($act, "fecha", "desc");
				$lastOne = $res[0];

				$productWithPrice = $productos[$a]->asArray();
				$productWithPrice["precio_compra"] = $lastOne->getPrecioCompra();
				$productWithPrice["precio_venta"] = $lastOne->getPrecioVenta();
				
				//agraarlo al javascirpt
				$javascriptWithProds .= "productos.push(". json_encode($productWithPrice) .");\n";
				
				if($a % 5 == 0){
					echo "</tr><tr>";
				}

				echo "<td class='rounded' id='producto-" . $productWithPrice["id_producto"] . "' ";
				echo " onClick='agregarACompra( " .  $productWithPrice["id_producto"] . " )' ";
				echo " onmouseover=\"this.style.backgroundColor = '#D7EAFF'\" onmouseout=\"this.style.backgroundColor = 'white'\"><img style='float:left;' src='../media/icons/basket_32.png'>" . $productWithPrice["descripcion"] . "<br>";
				echo " " . moneyFormat( $productWithPrice["precio_compra"] ) .  "<br>";
				echo "</td>";
			}
			
		echo "</tr>";
		echo "</table>";
		?>
</div>

<script>

	var productos = [];
		
	<?php
		//imprimir el javascript que genere arriba
		echo $javascriptWithProds;
	?>
	
	var carritoDeCompra = [];
	
	function buscarProductoEnCarrito( id_producto ){
		//buscar el producto en la lista del carrito
		for (var p=0; p < carritoDeCompra.length; p++) {
			if(carritoDeCompra[p].id_producto == id_producto){
				return carritoDeCompra[p];
			}
		};
		return null;
	}
	
	function doMath(  ){
		

		
		//si hay un producto con cantidad zero
		var zero_qty = false,
		
			//si hay algun precio en zeros
			zero_price = false,
			
			//precio total para comprar
			final_price = 0,
			
			estimated_earnings_at_sell_time = 0,
			
			final_weight = 0,
			
			//escala de los productos
			tipo_de_producto = null;
		
		for (var i=0; i < carritoDeCompra.length; i++) {
			
			id = carritoDeCompra[i].id_producto;
			
			if( isNaN(jQuery("#item-cantidad-"+id).val()) || (jQuery("#item-cantidad-"+id).val().length == 0))
				jQuery("#item-cantidad-"+id).val(0);
			
			if(jQuery("#item-cantidad-"+id).val() == 0)
				zero_qty = true;	
				
			if( isNaN(jQuery("#item-precio-"+id).val())|| (jQuery("#item-precio-"+id).val().length == 0) )
				jQuery("#item-precio-"+id).val(0);	
				
			if(jQuery("#item-precio-"+id).val() == 0)				
				zero_price = true;

			if( isNaN(jQuery("#item-descuento-"+id).val()) || (jQuery("#item-descuento-"+id).val().length == 0) )
				jQuery("#item-descuento-"+id).val(0);
			
			var sub_total = (jQuery("#item-cantidad-"+id).val() - jQuery("#item-descuento-"+id).val()) * jQuery("#item-precio-"+id).val();
			var sub_total_venta = (jQuery("#item-cantidad-"+id).val() - jQuery("#item-descuento-"+id).val()) * parseFloat(carritoDeCompra[i].precio_venta);

			final_weight += parseFloat(jQuery("#item-cantidad-"+id).val() - jQuery("#item-descuento-"+id).val());
			estimated_earnings_at_sell_time += sub_total_venta;
			final_price += sub_total;
			
			jQuery("#item-importe-"+ id).html( cf(sub_total) );
			
			
			//calcular los rendimientos
			jQuery("#rendimeinto-importe-"+ id).html(cf(sub_total) ) ; //importe
			jQuery("#rendimeinto-alaventa-"+ id).html(cf(carritoDeCompra[i].precio_venta)) ; 			//a la venta
			
			var rendimiento = sub_total - sub_total_venta;
			
			if(rendimiento <= 0){
				//le perdera dinero, mostar en rojo
				rendimiento = div( cf(rendimiento), "style='color:red'" );
			}else{
				//le ganara dinero, mostarr en verde
				rendimiento = div( "+" + cf(rendimiento), "style='color:green'" );				
			}
			
			jQuery("#rendimeinto-rendimiento-"+ id).html(  rendimiento ) ; //rendimiento		
			
			
			if(tipo_de_producto == null){
				tipo_de_producto = carritoDeCompra[i].escala;
			}else{
				if(carritoDeCompra[i].escala != tipo_de_producto){
					//productos varios
				}
			}

		}
		
		//mostrar totales del carrito
		
		jQuery("#compra-totales-peso").html(  final_weight + " " + tipo_de_producto + "s"  )
		jQuery("#compra-totales-importe").html(  cf(final_price)  )
				
		//mostrar los totales de rendimiento
		jQuery("#rendimientos-totales-rendimiento").html(  cf(final_price-estimated_earnings_at_sell_time)  )
   

		if( carritoDeCompra.length > 0 
			&& !zero_qty 
			&& !zero_price 
		){
			jQuery("#readyToBuy").fadeIn();
			jQuery("#rendimientos").fadeIn();
		}else{
			jQuery("#readyToBuy").fadeOut();
			jQuery("#rendimientos").fadeOut();			
		}
			

	}
	
	function agregarACompra( id_producto ){
		
		if( buscarProductoEnCarrito( id_producto ) != null ){
			//ya esta en el carrito, hacer highlight
			return;
		}
		
		//buscar el producto en la lista de productos
		var prod = null;
		for (var p=0; p < productos.length; p++) {
			if(productos[p].id_producto == id_producto){
				prod = productos[p];
				break;
			}
		};
		
		if(!prod){
			console.warn("Este producto no existe.");
			return;
		}
		
		carritoDeCompra.push( prod )

		//agregarlo a la tabla de venta
		var html = "";
		html += td( "" );
		html += td( prod.descripcion );
		html += td( "<input id='item-cantidad-"+id_producto+"' onKeyUp='doMath( )' value='1' type=text >" + prod.escala + "s" );
		html += td( "<input id='item-descuento-"+id_producto+"' onKeyUp='doMath(  )'  type=text>" );
		html += td( "<input id='item-precio-"+id_producto+"' onKeyUp='doMath(  )' value='"+ prod.precio_compra +"' type=text>" );
		html += td( div("", "id='item-importe-"+id_producto+"'")  );		
		html = tr(html);
		
		jQuery("#TablaDeEstaCompra").after( html );
		
		//agregarlo a la tabla de rendimientos
		html = "";
		html += td( prod.descripcion );
		html += td( div("", "id='rendimeinto-importe-"+ id_producto + "'" ) ); //importe
		html += td( div("", "id='rendimeinto-alaventa-"+ id_producto + "'" ) ); //a la venta
		html += td( div("", "id='rendimeinto-rendimiento-"+ id_producto + "'") ); //rendimiento
		html = tr(html);
		
		jQuery("#TablaDeRendimientos").after( html );
		
		doMath();
	}

	function doSell(){
		
		var objToSend = {
			id_cliente : parseInt(jQuery("#cliente_selector").val()),
			tipo_compra : "contado",
			tipo_pago	: "efectivo",
			productos : []
		};
		
		for (var i=0; i < carritoDeCompra.length; i++) {
			
			id = carritoDeCompra[i].id_producto;

			objToSend.productos.push({
				id_producto : id,
				cantidad 	: parseFloat(jQuery("#item-cantidad-"+id).val()),
				precio 		: parseFloat(jQuery("#item-precio-"+id).val()),
				descuento 	: parseFloat(jQuery("#item-descuento-"+id).val())
			});

		}
		

	    //hacer ajaxaso
	    jQuery.ajaxSettings.traditional = true;

	    jQuery("#readyToBuy").fadeOut("slow",function(){
	        jQuery("#loader").fadeIn();

	        jQuery.ajax({
	        url: "../proxy.php",
	        data: { 
	            action : 1006, 
	            data : jQuery.JSON.encode( objToSend ),
	        },
	        cache: false,
	        success: function(data){
	            try{
	                response = jQuery.parseJSON(data);
	                //console.log(response, data.responseText)
	            }catch(e){

	                jQuery("#loader").fadeOut('slow', function(){
	                    jQuery("#readyToBuy").fadeIn();
	                    window.scroll(0,0);                         
	                    jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
	                    jQuery("#readyToBuy").fadeIn();
	                });                
	                return;                    
	            }


	            if(response.success === false){

	                jQuery("#loader").fadeOut('slow', function(){
	                    //jQuery("#readyToBuy").fadeIn();    
	                    window.scroll(0,0);
						try{
	                    	jQuery("#ajax_failure").html(response.reason).show();
						}catch(e){
							jQuery("#ajax_failure").html("Error inesperado").show();
						}

	                    jQuery("#readyToBuy").fadeIn();
	                });                
	                return ;
	            }

				window.location = "compras.php?action=detalleCompraCliente&id=" + response.id_compra + "&pp=1";

	        }
	        });
	    });
	}
</script>





<h2>Detalles en esta compra</h2>
<div>
	<table style="width:100%">
    <tr id="TablaDeEstaCompra">
        <td></td>
        <td>Producto</td>
        <td>Cantidad</td>
        <td>Descuento</td>
        <td>Precio de compra</td>
        <td>Importe</td>  
    </tr>
    <tr >
        <td></td>
        <td ></td>
        <td class="rounded" style='background-color: #D7EAFF;' id="compra-totales-peso"></td>	
        <td ></td>  
        <td ></td>  
        <td class="rounded" style='background-color: #3F8CE9; color:white ;' id="compra-totales-importe"></td>	
    </tr>
    </table>
</div>






<div id="rendimientos" style="display:none" >
	<h2>Rendimiento para esta compra</h4>
		<table style="width:100%">
	    <tr id="TablaDeRendimientos">
	        <td>Producto</td>
	        <td>Importe de producto</td>
	        <td>Precio a la venta actual</td>  
	        <td>Rendimiento</td>	
	    </tr>
	    <tr >
	        <td></td>
	        <td ></td>
	        <td ></td>  
	        <td class="rounded" style='background-color: #D7EAFF; ' id="rendimientos-totales-rendimiento"></td>	
	 <!-- 	        <td style='border-top: 1px solid #3F8CE9;' id="rendimientos-totales-rendimiento"></td>	 -->
	    </tr>
	    </table>
</div>
<div id="loader" 		style="display: none;" align="center"  >
	Procesando <img src="../media/loader.gif">
</div>
<div id="readyToBuy" style="display:none" align=center>
	<h4><input type=button value="Realizar la compra" onClick="doSell()"></h4>
</div>
