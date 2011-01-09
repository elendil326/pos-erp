<?php

require_once('model/proveedor.dao.php');
require_once('model/inventario.dao.php');
require_once('model/sucursal.dao.php');


?>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

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
      * Uniform 
      *
      * */
	$(function(){   $("input, select").uniform();   });
    
    
    /**
      *
      *	Seleccionar proveedor
      *
      * */
    function seleccionarProveedor()
    {
    	p = $('#proveedores').val();
    	
    	for ( i = 0; i < provs.length; i++)
    	{
    		if(provs[i].id == p){
				break;
    		}
    	}
    	
    	$('#chooseSupplier').slideUp('fast', function (){
    		$('#chooseSupplierDetails').html("<h3>" + provs[i].nombre + "</h3>");
    		$('#chooseSupplierDetails').slideDown(); 		
    		$('#chooseProds').slideDown();    		
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
    
	    p = $('#addProds').val();

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
	    
    	$('#prodsTableFooter').before(html);
    	$("input:text").uniform();
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
    			globals.total_arpillas += parseFloat( $('#cart-' + id + '-arpillas').val());
    		}catch(e){
    			return;
    		}
    	}
    	
    	$("#detalles-total-arpillas").val( globals.total_arpillas );
    	
    	
    	
    	globals.peso_origen = parseFloat( $("#detalles-peso-recibido").val() );
		globals.promedio_arpilla = globals.peso_origen / globals.total_arpillas;
    	globals.importe_total = 0;
    	globals.peso_calculado = 0;
    	
    	$("#detalles-peso-por-arpilla").val(globals.promedio_arpilla);
    	
         //bucle de productos
    	for (i = 0; i < _cart.length; i++){
    		id = _cart[i].id_producto;
    		
   			$('#cart-' + id + '-promedio').val( globals.promedio_arpilla );


			este_peso = globals.promedio_arpilla * parseFloat( $('#cart-' + id + '-arpillas').val() );
   			$('#cart-' + id + '-peso').val( este_peso );
			globals.peso_calculado += este_peso;
			   			
   			este_importe =  parseFloat( $('#cart-' + id + '-peso').val() ) * parseFloat( $('#cart-' + id + '-precio').val() );
			globals.importe_total += este_importe;
			
   			$('#cart-' + id + '-importe').val( cf(este_importe) );
    	}
    	
    	
    	//totales-arpillas
    	$("#totales-arpillas").html	(globals.total_arpillas);
    	
    	//totales-importe
    	$("#totales-importe").html	( cf( globals.importe_total) );
    	
    	//totales-peso
    	$("#totales-peso").html		(globals.peso_calculado); 
    	
    	
    	$("#doneButton").fadeIn();
    }
    
    function done(){
 
    	var obj = {
			embarque : {
				id_proveedor:		$('#proveedores').val(),
				folio: 				$("#detalles-folio").val(),
				merma_por_arpilla: 	$("#detalles-merma-arpilla").val(),
				numero_de_viaje:	$("#detalles-nuermo-viaje").val(),
				peso_por_arpilla: 	$("#detalles-peso-por-arpilla").val(),
				peso_origen : 		$("#detalles-peso-origen").val(),
				peso_recibido : 	$("#detalles-peso-recibido").val(),
				productor : 		$("#detalles-productor").val(),
				importe_total: 		globals.importe_total,
				total_arpillas: 	$("#totales-arpillas").html(),
				costo_flete : 		$("#detalles-flete").val()
			},
			conductor : {
				nombre_chofer : 	$("#detalles-chofer").val(),
				placas : 			$("#detalles-placas").val(),
				marca_camion : 		$("#detalles-marca").val(),
				modelo_camion : 	$("#detalles-modelo").val() 
			},
			productos: [ ]
		};									


    	

    	//bucle de productos
    	for (i = 0; i < _cart.length; i++){
    		id = _cart[i].id_producto;
    		prod = {
    			id_producto : id,
    			variedad : 			$('#cart-' + id + '-variedad').val(),
    			arpillas: 			$('#cart-' + id + '-arpillas').val(),
    			precio_kg: 			$('#cart-' + id + '-precio').val(),
    			sitio_descarga: 	$('#cart-' + id + '-sitio').val()
    		};
    		
			obj.productos.push(prod);
		}
		
		$("#loader").show();
		
    	jQuery.ajaxSettings.traditional = true;


        $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 1000, 
            data : $.JSON.encode(obj)
           },
	      cache: false,
	      success: function(data){
	      		try{
			        response = jQuery.parseJSON(data);
			    }catch(e){
           			$("#loader").hide();
                    return $("#ajax_failure").html("Error en el servidor. Intente de nuevo.").show();			    
			    }

                if(response.success == false){
           			$("#loader").hide();
                    return $("#ajax_failure").html(response.reason).show();
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
	<table style='width:100%' border=1>
		<tr><td colspan=2 ><h3>Detalles del producto</h3></td>
			<td colspan=2 ><h3>Detalles del flete</h3></td></tr>
			
		<tr><td>Folio</td>				<td><input type='text' id='detalles-folio' ></td>
			<td>Nombre del chofer</td>	<td><input type='text' id='detalles-chofer' ></td></tr>
		
		<tr><td>Fecha</td>				<td><input type='text' id='detalles-fecha' value="0" disabled></td>
			<td>Placas</td>				<td><input type='text' id='detalles-placas' ></td></tr>
			
		<tr><td>Total arpillas</td>		<td><input type='text' id='detalles-total-arpillas' value="0" disabled>
			<td>Marca camion</td>		<td><input type='text' id='detalles-marca'  ></td></tr>
			
		<tr><td>Merma por arpilla</td>	<td><input type='text' id='detalles-merma-arpilla'></td>
			<td>Modelo camion</td>		<td><input type='text' id='detalles-modelo'  ></td></tr>
			
		<tr><td>Numero de viaje</td>	<td><input type='text' id='detalles-nuermo-viaje'></td>
			<td>Costo total del flete</td><td><input type='text' id='detalles-flete'  ></td></tr>
			
		<tr><td>Peso por arpilla</td>	<td><input type='text' id='detalles-peso-por-arpilla' value="0" disabled></td></tr>	
		<tr><td>Peso origen</td>		<td><input type='text' id='detalles-peso-origen' onkeyup="$('#detalles-peso-recibido').val($('#detalles-peso-origen').val()); doMath();" ></td></tr>	
		<tr><td>Peso recibido</td>		<td><input type='text' id='detalles-peso-recibido' onkeyup="doMath()" ></td></tr>			
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

