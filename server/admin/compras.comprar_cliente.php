<?php
	require_once("model/inventario.dao.php");
	require_once("model/actualizacion_de_precio.dao.php");
	require_once("model/cliente.dao.php");
	require_once("controller/clientes.controller.php");

	$productos = InventarioDAO::getAll();
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

<script>

	var productos = [];
	
	<?php
		foreach($productos as $p){
			echo "productos.push(". $p .");\n";
		}
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
			zero_price = false;
		
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
			
			var sub_total = (jQuery("#item-cantidad-"+id).val() - jQuery("#item-cantidad-"+id).val()) 
				* jQuery("#item-precio-"+id).val();
			
			jQuery("#item-importe-"+ id).html( cf(sub_total) );

		}
		


		if( carritoDeCompra.length > 0 
			&& !zero_qty 
			&& !zero_price 
		){
			jQuery("#readyToBuy").fadeIn();
		}else{
			jQuery("#readyToBuy").fadeOut();
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
		
		var html = "";
		html += td( "" );
		html += td( prod.descripcion );
		html += td( "<input id='item-cantidad-"+id_producto+"' onKeyUp='doMath( )' type=text >" );
		html += td( "<input id='item-precio-"+id_producto+"' onKeyUp='doMath(  )' type=text>" );
		html += td( "<input id='item-descuento-"+id_producto+"' onKeyUp='doMath(  )' type=text>" );
		html += td( div("", "id='item-importe-"+id_producto+"'")  );		
		html = tr(html);
		
		jQuery("#TablaDeEstaCompra").after( html );
		
		doMath();
	}

	function doSell(){
		
		var objToSend = {
			id_cliente : parseInt(jQuery("#cliente_selector").val()),
			items : []
		};
		
		for (var i=0; i < carritoDeCompra.length; i++) {
			
			id = carritoDeCompra[i].id_producto;

			objToSend.items.push({
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
	            action : 1007, 
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
	            window.location = "compras.php?action=detalleCompraCliente&cid=" + response.compra_id + "&pp=1";

	        }
	        });
	    });
	}
</script>


<div  >
	<h2>Productos disponibles</h2>
		<?php
		echo "<table border=0 style='width: 100%; font-size: 14px; cursor: pointer;'>";
			echo "<tr>";
			for($a = 0; $a < sizeof($productos); $a++){

				//buscar su precio sugerido actual
				$act = new ActualizacionDePrecio();
				$act->setIdProducto( $productos[$a]->getIdProducto() );
				$res = ActualizacionDePrecioDAO::search($act, "fecha", "desc");
				$lastOne = $res[0];

				if($a % 5 == 0){
					echo "</tr><tr>";
				}

				echo "<td id='producto-" . $productos[$a]->getIdProducto() . "' ";
				echo " onClick='agregarACompra( " .  $productos[$a]->getIdProducto() . " )' ";
				echo " onmouseover=\"this.style.backgroundColor = '#D7EAFF'\" onmouseout=\"this.style.backgroundColor = 'white'\"><img style='float:left;' src='../media/icons/basket_32.png'>" . $productos[$a]->getDescripcion() . "<br>";
				echo " " . moneyFormat($lastOne->getPrecioVenta()) .  "<br><br>";
				echo "</td>";
			}
			echo "</tr>";
		echo "</table>";
		?>
</div>


<h2>Detalles en esta compra</h2>
<div>
	<table style="width:100%">
    <tr id="TablaDeEstaCompra">
        <td></td>
        <td>Producto</td>
        <td>Cantidad</td>
        <td>Precio</td>
        <td>Descuento</td>
        <td>Importe</td>  
    </tr>
    </table>
</div>


<div id="loader" 		style="display: none;" align="center"  >
	Procesando <img src="../media/loader.gif">
</div>

<div id="readyToBuy" style="display:none" align=center>
	<h4><input type=button value="Realizar la compra" onClick="doSell()"></h4>
</div>