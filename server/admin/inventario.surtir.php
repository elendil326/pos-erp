<?php


	require_once("model/sucursal.dao.php");
    require_once('model/autorizacion.dao.php');
	require_once("controller/clientes.controller.php");
	require_once("controller/sucursales.controller.php");
	require_once("controller/inventario.controller.php");

    if(isset( $_REQUEST['aut'])){
        $autorizacion = AutorizacionDAO::getByPK( $_REQUEST['aut'] );
        $autorizacionDetalles = json_decode( $autorizacion->getParametros() );
    }
	
	$foo = new Sucursal();
    $foo->setActivo(1);
    $foo->setIdSucursal(1);

    $bar = new Sucursal();
    $bar->setIdSucursal(99);

    $sucursales = SucursalDAO::byRange($foo, $bar);

	function toUnit( $e )
	{
		return "<b>" . number_format($e, 2) . "</b>kg";
	}
?>


<style>
    table{
		font-size: 11px;
    }
</style>

<script type="text/javascript" charset="utf-8">
	var sucursales = [];
	
	<?php 
		foreach( $sucursales as $suc ){	
			echo " sucursales[" . $suc->getIdSucursal() . "] = \"" .  $suc->getDescripcion()  . "\";";
	}
	?>


    var currentSuc = null;

	function seleccionarSucursal(){


        if(currentSuc !== null){
    		jQuery("#actual" + currentSuc).slideUp();
        }


        <?php 
            if(isset($_REQUEST['aut'])) { 
                echo 'jQuery("#Solicitud").slideDown();';
        }?>            

		jQuery("#actual" + jQuery('#sucursal').val()).slideDown();
		jQuery("#InvMaestro").slideDown();
		jQuery("#ASurtir").slideDown();
        currentSuc = jQuery('#sucursal').val();
        jQuery("#select_sucursal").slideUp();
	}

    carrito = [];


	function domath(){
		totals_importe = 0;
		totals_cantidad = 0;

		values = jQuery("#ASurtirTabla input");
		
		for(a = 0; a < values.length; a+=5){
			values[ a + 4 ].value = ( values[ a ].value - values[ a + 3 ].value ) * values[ a + 2 ].value;
			
			totals_cantidad += ( values[ a ].value - values[ a + 3 ].value );
			totals_importe += ( values[ a ].value - values[ a + 3 ].value ) * values[ a + 2 ].value;
		}

		jQuery("#totales_cantidad").html( totals_cantidad );
		jQuery("#totales_importe").html(cf( totals_importe ));
	}

	function tr(s){return "<tr>"+s+"</tr>";}
	function td(s){return "<td>"+s+"</td>";}
	
    function agregarProducto(data){

		o = jQuery.JSON.decodeSecure(Url.decode(data));

		carrito.push( o );

		html = "";
		html += td( o.folio );
		html += td( o.producto_desc );

		html += td( "<input style='width: 100px' onkeyup='domath()' value='0' id='cart_table_cantidad" + o.id_compra_proveedor + "_" + o.id_producto +"' 	type='text'>" );

		var procesadas = parseFloat( o.existencias_procesadas );
		
		if(procesadas > 0){
			html += td( "<input style='width: 100px' id='cart_table_procesada" + o.id_compra_proveedor + "_" + o.id_producto +"' 	type='checkbox'>" );			
		}else{
			html += td( "<input style='width: 100px' id='cart_table_procesada" + o.id_compra_proveedor + "_" + o.id_producto +"' 	type='checkbox' disabled>" );
		}


		html += td( "<input style='width: 100px' onkeyup='domath()' value='"+ ( o.precio_por_kg )+"' id='cart_table_precio" + o.id_compra_proveedor + "_" + o.id_producto +"' 	type='text'>" );
		html += td( "<input style='width: 100px' onkeyup='domath()' value='0' id='cart_table_descuento" + o.id_compra_proveedor + "_" + o.id_producto +"' 	type='text'>" );
		html += td( "<input style='width: 100px'					 		 id='cart_table_importe" + o.id_compra_proveedor + "_" + o.id_producto +"' 		type=text disabled>" );
		

		jQuery("#ASurtirTablaHeader").after( tr(html) );		
		jQuery("#ASurtirTabla input").uniform();	
		
		return;
    }


	function renderConfirm( obj ){
		html = "<h1>Confirme el embarque</h1>";
		html += "<b>Destino</b>: " + sucursales[ obj.sucursal ];
		html += "<h2>Detalles del pedido</h2>";
		html += "<table style='width: 100%'>";		
		html += "<tr>";
		
		html += "<th>Cantidad</th>";
		html += "<th>Descripcion</th>";
		html += "<th>Precio</th>";
		html += "<th>Importe</th>";
		html += "</tr>";
        stotal = 0;				
		for(a = 0; a < obj.productos.length; a++){
			html += "<tr>";
			html += "<td>"+ obj.productos[a].cantidad 		+ "&nbsp;-&nbsp;" +obj.productos[a].descuento + "</td>";
			html += "<td><b>"+ obj.productos[a].id_producto +"</b>&nbsp;" +obj.productos[a].producto_desc 	+"</td>";
			html += "<td>"+ cf(obj.productos[a].precio) 	+"</td>";
			html += "<td>"+ cf((obj.productos[a].cantidad - obj.productos[a].descuento)*obj.productos[a].precio) 	+"</td>";
			html += "</tr>";
            stotal += (obj.productos[a].cantidad - obj.productos[a].descuento)*obj.productos[a].precio;
		}
        html += "<tr style=\"border-top: 1px solid #3F8CE9;\">"
        +"<td></td><td></td><td><b>Total:</b></td>"
        +"<td>"+ cf(stotal) +"</td>"
        +"</tr>";
		html += "</table>";
		html += "<div align='center'><input type='button' value='Aceptar' onclick='confirmed()'><input type='button' value='Cancelar' onclick='jQuery(document).trigger(\"close.facebox\");'></div>"
		return html;
	}

	var readyDATA = null;

    function doSurtir(){
	
		if(carrito.length == 0){
			jQuery("#ajax_failure").html("Seleccione uno o mas prouductos para surtir a esta sucursal").show();
			return;
		}
	
		values = jQuery("#ASurtirTabla input");
		
		json = {
			sucursal : currentSuc,
			productos : []
		};
		
		var foo = 0, vacio = false;
		
		for(a = carrito.length -1 ; a >= 0; a--, foo += 5){
			
			json.productos.push({
				id_producto: 	carrito[a].id_producto,
				producto_desc :	carrito[a].producto_desc,
				procesada:		jQuery(values[foo+1]).is(':checked'),
				cantidad:		parseFloat( values[foo].value ),
				descuento:		parseFloat( values[foo+3].value),
				precio:			parseFloat( values[foo+2].value),
				id_compra:		carrito[a].id_compra_proveedor
			});
			
			if( values[foo].value == 0  ){
				jQuery("#ajax_failure").html("No puede hacer un embarque con algun producto vacio").show();
				return;
			}
			
			/*			
			console.log("********** ******* ");
			console.log("analizando ", carrito[a].id_producto, carrito[a].id_proveedor);
			console.log("cantidad:" 	+ values[foo].value );
			console.log("lavada:" 		, jQuery(values[foo+1]).is(':checked') );
			console.log("precio:" 		+ values[foo+2].value );
			console.log("descuento:" 	+ values[foo+3].value );
			console.log("importe:" 		+ values[foo+4].value );
			*/
		}


		

		readyDATA = json;
		jQuery.facebox( renderConfirm( json ) );
		return;
    }


function confirmed()
{
	//cerrar el facebox
	jQuery(document).trigger('close.facebox');

 	//hacer ajaxaso
        jQuery.ajaxSettings.traditional = true;

		jQuery("#submitButtons").fadeOut("slow",function(){
			jQuery("#loader").fadeIn();
			
			jQuery.ajax({
			url: "../proxy.php",
			data: { 
				action : 1005, 
				data : jQuery.JSON.encode( readyDATA ),
			},
			cache: false,
			success: function(data){
				try{
			  		response = jQuery.parseJSON(data);
			  		//console.log(response, data.responseText)
				}catch(e){
				
					jQuery("#loader").fadeOut('slow', function(){
						jQuery("#submitButtons").fadeIn();
						jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
					});                
					return;                    
				}
		

				if(response.success === false){
					jQuery("#loader").fadeOut('slow', function(){
						jQuery("#submitButtons").fadeIn();      				
						jQuery("#ajax_failure").html(response.reason).show();
					});                
					return ;
				}

				reason = "El caragmento se enuentra ahora en transito";
				window.location = "inventario.php?action=transit&success=true&reason=" + reason;
		
			}
			});
		});
}


	function restart()
	{
	
		
		jQuery.facebox('<h1>Volver a comenzar</h1>Todos los cambios que ha realizado se perderan. &iquest; Esta seguro que desea comenzar de nuevo ?'
				+ "<br><div align='center'>"
				+ "			<input type='button' onclick=\"window.location = 'inventario.php?action=surtir'\" value='Si'>"
				+ "&nbsp;	<input type='button' onclick=\"jQuery(document).trigger('close.facebox')\" value='No'></div>"
			);
	}
</script>



<h1>Surtir una sucursal</h1>

<!-- 
		SELECCIONAR SUCURSAL
 -->
<?php if(!isset($_REQUEST['sid'])) { ?>
	<div id="select_sucursal">
    <h2>Seleccione la sucursal que desea surtir</h2>
    <form id="newClient">
    <table border="0" cellspacing="5" cellpadding="5">
	    <tr><td>Sucursal</td>
		    <td>
			    <select id="sucursal"> 
			    <?php
			

				    foreach( $sucursales as $suc ){
					    echo "<option value='" . $suc->getIdSucursal() . "' >" .  $suc->getDescripcion()  . "</option>";
				    }
			
			    ?>
	
	            </select>
		    </td>
            <td><input type="button" onClick="seleccionarSucursal()" value="Seleccionar"/> </td>
	    </tr>
    </table>
    </form>
    </div>
<?php }else{ ?>
    <input type="hidden" value="<?php echo $_REQUEST['sid']; ?>" id="sucursal" />
<?php } ?>






<?php

//get sucursales
$sucursales = listarSucursales();




foreach( $sucursales as $sucursal ){
	
	print ("<div id='actual" . $sucursal["id_sucursal"] . "' style='display: none'>");
	print ("<h2>Inventario actual de " . $sucursal["descripcion"] . "</h2>");
	
	//obtener los clientes del controller de clientes
	$inventario = listarInventario( $sucursal["id_sucursal"] );

	//render the table
	$header = array( 
		"productoID" 		=> "ID",
		"descripcion"		=> "Descripcion",
		"precioVenta"		=> "Precio a la venta",
		"existenciasOriginales"		=> "Originales",
		"existenciasProcesadas"		=> "Procesadas" );
		

	
	$tabla = new Tabla( $header, $inventario );
	$tabla->addColRender( "precioVenta", "moneyFormat" ); 
	$tabla->addColRender( "precioIntersucursal", "moneyFormat" ); 
    $tabla->addNoData("Esta sucursal no tiene nigun registro de productos en su inventario");
	$tabla->render();
	printf("</div>");
}

?>



<!--
<div id="Solicitud" style="display: none;">
<h2>Solicitud de producto</h2>
<h3>Esta es la lista de productos solicitados.</h3>

            <table>
                <tr><td>Producto solicitado</td><td>Cantidad solicitada</td></tr>
                <?php
                /*
                foreach ($autorizacionDetalles->productos as $producto)
                {
                    ?><tr><td><?php //echo $producto->id_producto; ?></td><td><?php // echo $producto->cantidad; ?></td></tr><?php
                }*/
                ?>
                <tr><td></td><td></td></tr>
            </table>

</div>
-->



<!-- 
		MOSTRAR INVENTARIO MAESTRO
 -->
<div id="InvMaestro" style="display: none;">
<h2>Productos disponibles</h2><h3>Seleccione los productos que desee surtir a esta sucursal.</h3><?php


	
	$iMaestro = listarInventarioMaestro(50, POS_SOLO_ACTIVOS) ;

	$header = array(
		"folio" 	=> "Remision",
		"producto_desc" 			=> "Producto",
		"variedad" 	 				=> "Variedad",
		"arpillas"					=> "Arpillas",
		"peso_por_arpilla"			=> "Kg/Arpilla",
		"productor"					=> "Productor",
		"fecha"						=> "Llegada",
		//"transporte"				=> "Transporte",
		"merma_por_arpilla"			=> "Merma",
		"sitio_descarga_desc"		=> "Sitio de descarga",
		"existencias"				=> "Existencias",
		"existencias_procesadas"	=> "Limpias" );
	
	$tabla = new Tabla( $header, $iMaestro );
    $tabla->addOnClick( "id_producto", "agregarProducto", true);	
	$tabla->addColRender("existencias", "toUnit");
	$tabla->addColRender("existencias_procesadas", "toUnit");	
	$tabla->render();


?> 
</div>





<!-- 
		SELECCIONAR PRODUCTOS A SURTIR
   -->

<div id="ASurtir" style="display: none;">
<h2>Productos a surtir</h2>

<table id="ASurtirTabla" style="width: 100%">
    <tr id="ASurtirTablaHeader">
    	<th>Folio</th>
    	<th>Producto</th>
    	<th>Cantidad</th>
    	<th>Procesada</th>
    	<th>Precio unitario</th>
    	<th>Descuento</th>
    	<th>Importe</th>
    </tr>
    
    <tr style="font-size: 15px;">
    	<td><b>Totales</b></td>
    	<td></td>
    	<td  id="totales_cantidad"></td>
    	<td></td>
    	<td></td>
    	<td></td>
    	<td id="totales_importe"></td>
    </tr>
</table>

	<div id="submitButtons" align='center'><input type="button" value="Surtir" onclick="doSurtir()"><input type="button" value="Volver a comenzar" onclick="restart()"></div>
	<div style="display: none;" align="center" id="loader" ><img src="../media/loader.gif"></div>
</div>




