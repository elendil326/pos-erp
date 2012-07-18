<?php

require_once('model/proveedor.dao.php');
require_once('model/inventario.dao.php');
require_once('model/sucursal.dao.php');


?>


<script>

	
	<?php
		$todos = ProveedorDAO::getAll();
		$foo = 'var provs = [ ';
		foreach($todos as $p){
			$foo .=   '{ "id" : ' . $p->getIdProveedor() . ', "nombre": "'.$p->getNombre() .'", "direccion": "'.$p->getDireccion() .'" }, ' ;
		}
		
		$foo .= '];';
		echo $foo;
		

		$todos = InventarioDAO::getAll();
		$foo = 'var prods = [ ';
		foreach($todos as $p){ $foo .= $p . ", "; }
		$foo .= '];';
		echo $foo;
		
		$todos = SucursalDAO::getAll();
		$foo = 'var sucs = [ ';
		foreach($todos as $p){ $foo .= $p . ", "; }
		$foo .= '];';
		echo $foo;
	?>

    /**
      *
      *	El carrito de compras
      *
      * */
	var _cart = [];


    
    
    /**
      *
      *	Seleccionar proveedor
      *
      * */
    function seleccionarProveedor()
    {
    	p = jQuery('#proveedores').val();
    	
    	for ( i = 0; i < provs.length; i++)
    	{
    		if(provs[i].id == p){
				break;
    		}
    	}
    	
    	jQuery('#chooseSupplier').slideUp('fast', function (){
    		html = "<h3>" + provs[i].nombre + "</h3>";
    		html += provs[i].direccion;
    		jQuery('#chooseSupplierDetails').html(html);
    		jQuery('#chooseSupplierDetails').slideDown(); 		
    		jQuery('#chooseProds').slideDown();    		
    	});
    }

	function removeMe( pid ){
	
	}

    /**
      *
      *	Agregar el producto que se encuentre en #addProds
      *
      * */
    function addProd(){
    
	    p = jQuery('#addProds').val();

	    for ( i = 0; i < _cart.length; i++)
    	{
    		if(_cart[i].id_producto === p){
				return;
    		}
    	}
	    
	    for ( i = 0; i < prods.length; i++)
    	{
    		if(prods[i].id_producto == p){
				break;
    		}
    	}

		_cart.push( prods[i] );
		if(_cart.length % 2 != 0)
		    html = '<tr style="background-color:#D7EAFF">';
		else
		    html = '<tr style="background-color: white">';
    	//     Producto | Variedad | Arpillas | Promedio | Peso total | Precio KG | Importe | Sitio de descarga
    	
    	//Producto
	    html += '<td><!--<img style="margin-right: 5px;" src="../media/icons/close_16.png" onClick="removeMe(' + prods[i].id_producto + ')">-->'+ prods[i].descripcion +'</td>';	    
	    id = prods[i].id_producto;

	    //Cantidad
	    html += '<td><input id="cart-' + id + '-cantidad" style="width: 50px;" type="text" value="0" onKeyUp="doMath()"></td>';
	    
	    //Importe
	    html += '<td><input id="cart-' + id + '-importe" style="width: 70px;" value="0" disabled></td>'
	    
	    //Sitio de descarga
	    html += '<td><select id="cart-' + id + '-sitio" > ';  
	    
	    <?php
	
		    $s = SucursalDAO::getAll();
		    foreach( $s as $ss ){
			    echo " html += '<option value=\"" . $ss->getIdSucursal() . "\" >" .  $ss->getDescripcion()  . "</option>';";
		    }
	
	    ?>
    
	    html += '</select></td></tr>';
	    
    	jQuery('#prodsTableFooter').before(html);
    	jQuery("input:text").uniform();
    }
    
    
    
    var globals = {
		total_arpillas : 0,
		peso_origen : 0   	
    };
    
    
    
    /**
      *
      *	Hacer los calculos automaticos
      *
      * */
    function doMath(){

		//calcular el costo total del flete
		
		jQuery("#detalles-flete").val(parseFloat(jQuery("#detalles-flete-ton").val())* (jQuery("#detalles-peso-origen").val() / 1000));
		
	    globals.total_arpillas = 0.0;

	    
     	//bucle de productos
    	for (i = 0; i < _cart.length; i++){
    		id = _cart[i].id_producto;
    		
    		try{
    			globals.total_arpillas += parseFloat( jQuery('#cart-' + id + '-arpillas').val());
    		}catch(e){
    			return;
    		}
    	}
    	
    	jQuery("#detalles-total-arpillas").val( globals.total_arpillas );
    	
    	
    	
    	globals.peso_origen = parseFloat( jQuery("#detalles-peso-recibido").val() );
		globals.promedio_arpilla = (globals.peso_origen -(globals.total_arpillas* parseFloat(jQuery('#detalles-merma-arpilla').val()))) / globals.total_arpillas;
    	globals.importe_total = 0;
    	globals.peso_calculado = 0;
    	
    	jQuery("#detalles-peso-real").val(globals.peso_origen -(globals.total_arpillas* parseFloat(jQuery('#detalles-merma-arpilla').val())));
    	
    	jQuery("#detalles-peso-por-arpilla").val(globals.promedio_arpilla);
    	
         //bucle de productos
    	for (i = 0; i < _cart.length; i++){
    		id = _cart[i].id_producto;
    		
   			jQuery('#cart-' + id + '-promedio').val( globals.promedio_arpilla );


			este_peso = globals.promedio_arpilla * parseFloat( jQuery('#cart-' + id + '-arpillas').val() );
   			jQuery('#cart-' + id + '-peso').val( este_peso );
			globals.peso_calculado += este_peso;
			   			
   			este_importe =  parseFloat( jQuery('#cart-' + id + '-peso').val() ) * parseFloat( jQuery('#cart-' + id + '-precio').val() );
			globals.importe_total += este_importe;
			
   			jQuery('#cart-' + id + '-importe').val( cf(este_importe) );
    	}
    	
    	
    	//totales-arpillas
    	jQuery("#totales-arpillas").html	(globals.total_arpillas);
    	
    	//totales-importe
    	jQuery("#totales-importe").html	( cf( globals.importe_total) );
    	
    	//totales-peso
    	jQuery("#totales-peso").html		(globals.peso_calculado); 
    	
    	
    	jQuery("#doneButton").fadeIn();
    }
    
    
    
    
    
    function done(){
 
    	var obj = {
			embarque : {
				fecha_origen: 		jQuery('#detalles-fecha-origen').val(),
				id_proveedor:		jQuery('#proveedores').val(),
				folio: 				jQuery("#detalles-folio").val(),
				importe_total: 		"0.0",//globals.importe_total,
				costo_flete : 		"0.0"
			},
			conductor : {
				nombre_chofer : 	" ",
				placas : 			" ",
				marca_camion : 		" ",
				modelo_camion : 	0 
			},
			productos: [ ]
		};									


    	

    	//bucle de productos
    	for (i = 0; i < _cart.length; i++){
    		id = _cart[i].id_producto;
    		prod = {
    			id_producto : 		id,
    			variedad : 			"wer",
    			arpillas: 			"0",
    			precio_kg: 			"0.0",
    			sitio_descarga: 	jQuery('#cart-' + id + '-sitio').val(),
    			cantidad:			jQuery('#cart-' + id + '-cantidad').val()
    		};
    		
			obj.productos.push(prod);
		}
		
		jQuery("#loader").show();
		
    	jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 1007, 
            data : jQuery.JSON.encode(obj)
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
                window.location = 'proveedor.php?action=detalleEmbarque&cid=' + response.id_compra + '&success=true&askForPrint=1&reason=' + reason;
	      }
	    });
   	
    }
</script>




<h1>Abastecer de proveedores</h1>
<h2>Proveedor</h2>
<div id="chooseSupplier">
<h3>Seleccione el proveedor de quien se desea abastecer</h3>
    <table border="0" cellspacing="5" cellpadding="5">
	    <tr><td>Proveedor</td>
		    <td>
			    <select id="proveedores"> 
			    <?php
			
				    $p = ProveedorDAO::getAll();
				    foreach( $p as $proveedor ){
					    echo "<option value='" . $proveedor->getIdProveedor() . "' >" .  $proveedor->getNombre()  . "</option>";
				    }
			
			    ?>
	
	            </select>
		    </td>
            <td><input type="button" onClick="seleccionarProveedor()" value="Seleccionar"/> </td>
	    </tr>
    </table>
</div>
<div id="chooseSupplierDetails">
	
</div>


		<!-- -------------------------------
		DETALLES DEL PRODUCTO Y DETALLES DEL FLETE
		------------------------------- -->
<div id="chooseProds" style='display: none'>

	<h2>Detalles del embarque</h2>
	<table style='width:100%' border=0>
		<tr><td colspan=2 ><h3>Detalles del producto</h3></td>
			
		<tr><td>Remision</td>			<td><input type='text' id='detalles-folio' class="wrong"></td>
		
		<tr><td>Fecha de origen</td>	<td><input type='text' id='detalles-fecha-origen'  class="wrong" value="" placeholder="dd/mm/aa"></td>
	</table>



	<!-- -------------------------------
			AGREGAR PRODUCTOS
	  ------------------------------- -->
	<h2>Productos</h2>
	<h3>Agregue los productos que vienen en este embarque</h3>
	    <table border="0" cellspacing="5" cellpadding="5">
	    <tr><td>Producto</td>
		    <td>
			    <select id="addProds"> 
			    <?php
			
				    $foo = InventarioDAO::getAll();
				    foreach( $foo as $prod ){
					    echo "<option value='" . $prod->getIdProducto() . "' >" .  $prod->getDescripcion()  . "</option>";
				    }
			
			    ?>
	
	            </select>
		    </td>
            <td><input type="button" onClick="addProd()" value="Agregar"/> </td>
	    </tr>
    </table>
    
    
    
	<!-- -------------------------------
			TABALA DE PRODUCTOS SELECCIONADOS
	  ------------------------------- -->    
	 
    <table id='prodsTable' style="width:100%;" >
    	<tr id='prodsTableHeader' ><th>Producto</th><th>Cantidad</th><th>Importe</th><th>Sitio de descarga</th></tr>
    	<tr id='prodsTableFooter'>					   <td colspan=2 align="right"><b>Totales</b></td><td><div id='totales-arpillas'></div></td><td></td><td><div id='totales-peso'></div></td><td></td><td><div id='totales-importe'></div></td></tr>    	
    </table>
    
    
    <!-- -------------------------------
			BOTON CONFIRMAR
	  ------------------------------- -->
    <h4 id='doneButton' align="center" style="display:none;">
    	<input type='button' value='Confirmar' onClick='done()'>
    	<img id="loader" style="display:none;" src="../media/loader.gif">
    </h4>
</div>

