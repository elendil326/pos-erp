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

	$(function(){
        $("input, select").uniform();
    });
    
    function seleccionarProveedor()
    {
    	p = $('#proveedores').val();
    	
    	for (var i = 0; i < provs.length; i)
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
    
	var _cart = [];
	
    function addProd(){
	    p = $('#addProds').val();

	    for (var i = 0; i < _cart.length; i++)
    	{
    		if(_cart[i].id_producto == p){
				return;
    		}
    	}
	    
	    for (var i = 0; i < prods.length; i++)
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
	    html += '<td><img style="margin-right: 5px;" src="../media/icons/close_16.png">'+ prods[i].descripcion +'</td>';	    
	    id = prods[i].id_producto;
	    // Variedad
	    html += '<td><input id="cart-' + id + '-variedad" style="width: 50px;" type="text"></td>';
	    
	    //Arpillas
	    html += '<td><input id="cart-' + id + '-arpillas" style="width: 50px;" type="text"></td>';
	    
	    //Promedio
	    html += '<td><input id="cart-' + id + '-promedio" disabled style="width: 50px;" value="0" type="text"></td>';
	    
	    //Peso Total
	    html += '<td><input id="cart-' + id + '-peso"  disabled style="width: 50px;" value="0" type="text"></td>';
	    
	    //Precio KG
	    html += '<td><input id="cart-' + id + '-precio" style="width: 50px;" type="text"></td>';
	    
	    //Importe
	    html += '<td><input id="cart-' + id + '-importe" style="width: 50px;" value="0" disabled></td>'
	    
	    //Sitio de descarga
	    html += '<td><select id="cart-' + id + '-sitio" > ';  
	    
			    <?php
			
				    $s = SucursalDAO::getAll();
				    foreach( $s as $ss ){
					    echo " html += '<option value=\"" . $ss->getIdSucursal() . "\" >" .  $ss->getDescripcion()  . "</option>';";
				    }
			
			    ?>
	
	            
	    
	    html += '</select></td></tr>';
	    
    	$(html).appendTo('#prodsTable');
    	$("input:text").uniform();
    }
    
    
    function done()
    {
    	//bucle de productos
    	for (i = 0; i < _cart.length; i++){
    		
    		//recolectar los datos
    		for(j = 0; j < 7; j++){
    			console.log( $("#cart-" + _cart[i].id_producto).val() )
    		}
    		
    	}
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

<div id="chooseProds" style='display: block'>

	<h2>Detalles del embarque</h2>
	<table style='width:100%' border=1>
		<tr><td colspan=2 ><h3>Detalles del producto</h3></td>
			<td colspan=2 ><h3>Detalles del flete</h3></td></tr>
			
		<tr><td>Folio</td>				<td><input type='text' id='detalles-folio' ></td>
			<td>Nombre del chofer</td>	<td><input type='text' id='detalles-folio' ></td></tr>
		
		<tr><td>Fecha</td>				<td><input type='text' id='detalles-fecha' value="0" disabled></td>
			<td>Placas</td>				<td><input type='text' id='detalles-fecha' value="0" disabled></td></tr>
			
		<tr><td>Total arpillas</td>		<td><input type='text' id='detalles-total-arpillas' value="0" disabled>
			<td>Marca camion</td>		<td><input type='text' id='detalles-fecha' value="0" disabled></td></tr>
			
		<tr><td>Merma por arpilla</td>	<td><input type='text' id='detalles-merma-arpilla'></td>
			<td>Modelo camion</td>		<td><input type='text' id='detalles-fecha' value="0" disabled></td></tr>
			
		<tr><td>Numero de viaje</td>	<td><input type='text' id='detalles-nuermo-viaje'></td>
			<td>Costo total del flete</td><td><input type='text' id='detalles-fecha' value="0" disabled></td></tr>
			
		<tr><td>Peso por arpilla</td>	<td><input type='text' id='detalles-peso-por-arpilla' value="0" disabled></td></tr>	
		<tr><td>Peso origen</td>		<td><input type='text' id='detalles-peso-origen'></td></tr>	
		<tr><td>Peso recibido</td>		<td><input type='text' id='detalles-peso-recibido'></td></tr>			
		<tr><td>Productor</td>			<td><input type='text' id='detalles-productor'></td></tr>	
	</table>




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
    
    <table id='prodsTable' style='width:100%' id='cart'>
    	<tr><th>Producto</th><th>Variedad</th><th>Arpillas</th><th>Promedio</th><th>Peso</th><th>Precio KG</th><th>Importe</th><th>Sitio de descarga</th></tr>
    </table>
    
    <div id='doneButton' style>
    <input type='button' value='Confirmar' onClick='done()'>
    </div>
</div>

