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
    		jQuery('#chooseSupplierDetails').html("<h3>" + provs[i].nombre + "</h3>");
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
	    html += '<td><img style="margin-right: 5px;" src="../media/icons/close_16.png" onClick="removeMe(' + prods[i].id_producto + ')">'+ prods[i].descripcion +'</td>';	    
	    id = prods[i].id_producto;
	    // Variedad
	    html += '<td><input id="cart-' + id + '-variedad" style="width: 50px;" type="text" ></td>';
	    
	    //Arpillas
	    html += '<td><input id="cart-' + id + '-arpillas" style="width: 50px;" type="text" onKeyUp="doMath()"></td>';
	    
	    //Promedio
	    html += '<td><input id="cart-' + id + '-promedio" disabled style="width: 70px;" value="0" type="text"></td>';
	    
	    //Peso Total
	    html += '<td><input id="cart-' + id + '-peso"  disabled style="width: 70px;" value="0" type="text"></td>';
	    
	    //Precio KG
	    html += '<td><input id="cart-' + id + '-precio" style="width: 50px;" type="text" onKeyUp="doMath()"></td>';
	    
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
				id_proveedor:		jQuery('#proveedores').val(),
				folio: 				jQuery("#detalles-folio").val(),
				merma_por_arpilla: 	jQuery("#detalles-merma-arpilla").val(),
				numero_de_viaje:	jQuery("#detalles-nuermo-viaje").val(),
				peso_por_arpilla: 	jQuery("#detalles-peso-por-arpilla").val(),
				peso_origen : 		jQuery("#detalles-peso-origen").val(),
				peso_recibido : 	jQuery("#detalles-peso-recibido").val(),
				productor : 		jQuery("#detalles-productor").val(),
				importe_total: 		globals.importe_total,
				total_arpillas: 	jQuery("#totales-arpillas").html(),
				costo_flete : 		jQuery("#detalles-flete").val()
			},
			conductor : {
				nombre_chofer : 	jQuery("#detalles-chofer").val(),
				placas : 			jQuery("#detalles-placas").val(),
				marca_camion : 		jQuery("#detalles-marca").val(),
				modelo_camion : 	jQuery("#detalles-modelo").val() 
			},
			productos: [ ]
		};									


    	

    	//bucle de productos
    	for (i = 0; i < _cart.length; i++){
    		id = _cart[i].id_producto;
    		prod = {
    			id_producto : id,
    			variedad : 			jQuery('#cart-' + id + '-variedad').val(),
    			arpillas: 			jQuery('#cart-' + id + '-arpillas').val(),
    			precio_kg: 			jQuery('#cart-' + id + '-precio').val(),
    			sitio_descarga: 	jQuery('#cart-' + id + '-sitio').val()
    		};
    		
			obj.productos.push(prod);
		}
		
		jQuery("#loader").show();
		
    	jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 1000, 
            data : jQuery.JSON.encode(obj)
           },
	      cache: false,
	      success: function(data){
	      		try{
			        response = jQuery.parseJSON(data);
			    }catch(e){
           			jQuery("#loader").hide();
                    return jQuery("#ajax_failure").html("Error en el servidor. Intente de nuevo.").show();			    
			    }

                if(response.success == false){
           			jQuery("#loader").hide();
                    return jQuery("#ajax_failure").html(response.reason).show();
                }


                reason = "El cargamento se ha registrado con exito.";
                window.location = 'inventario.php?action=lista&success=true&reason=' + reason;
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
			<td colspan=2 ><h3>Detalles del flete</h3></td></tr>
			
		<tr><td>Remision</td>			<td><input type='text' id='detalles-folio' ></td>
			<td>Nombre del chofer</td>	<td><input type='text' id='detalles-chofer' ></td></tr>
		
		<tr><td>Fecha</td>				<td><input type='text' id='detalles-fecha' value="<?php echo date("d/m/y") ?>" disabled></td>
			<td>Placas</td>				<td><input type='text' id='detalles-placas' ></td></tr>
			
		<tr><td>Total arpillas</td>		<td><input type='text' id='detalles-total-arpillas' value="0" disabled>
			<td>Marca camion</td>		<td><input type='text' id='detalles-marca'  ></td></tr>
			
		<tr><td>Merma por arpilla</td>	<td><input type='text' id='detalles-merma-arpilla' onkeyup="doMath()"></td>
			<td>Modelo camion</td>		<td><input type='text' id='detalles-modelo'  ></td></tr>
			
		<tr><td>Numero de viaje</td>	<td><input type='text' id='detalles-nuermo-viaje'></td>
			<td>Costo total del flete</td><td><input type='text' id='detalles-flete'  ></td></tr>
			
		<tr><td>Peso por arpilla</td>	<td><input type='text' id='detalles-peso-por-arpilla' value="0" disabled></td></tr>	
		
		<tr><td>Peso origen</td>		<td><input type='text' id='detalles-peso-origen'
				 onkeyup="jQuery('#detalles-peso-recibido').val(jQuery('#detalles-peso-origen').val()); doMath();" ></td></tr>	
				 
		<tr><td>Peso recibido</td>		<td><input type='text' id='detalles-peso-recibido' onkeyup="doMath()" ></td></tr>
		
		<tr><td>Peso real</td>			<td><input type='text' id='detalles-peso-real' disabled></td></tr>
		
		<tr><td>Productor</td>			<td><input type='text' id='detalles-productor'></td></tr>	
	</table>



	<!-- -------------------------------
			AGREGAR PRODUCTOS
	  ------------------------------- -->
	<h2>Productos</h2>
	<h3>Agregue los productos que vienen en este embarque</h3>
	    <table border="0" cellspacing="5" cellpadding="5">
	    <tr><td>Proveedor</td>
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
	 
    <table id='prodsTable' style="width:100%;" id='cart'>
    	<tr id='prodsTableHeader' ><th>Producto</th><th>Variedad</th><th>Arpillas</th><th>Promedio</th><th>Peso</th><th>Precio KG</th><th>Importe</th><th>Sitio de descarga</th></tr>
    	<tr id='prodsTableFooter'>					   <td colspan=2 align="right"><b>Totales</b></td><td><div id='totales-arpillas'></div></td><td></td><td><div id='totales-peso'></div></td><td></td><td><div id='totales-importe'></div></td></tr>    	
    </table>
    
    
    <!-- -------------------------------
			BOTON CONFIRMAR
	  ------------------------------- -->
    <div id='doneButton' align="center" style="display:none;">
    	<input type='button' value='Confirmar' onClick='done()'>
    	<img id="loader" style="display:none;" src="../media/loader.gif">
    </div>
</div>

