<?php

require_once('model/proveedor.dao.php');
require_once('model/inventario.dao.php');


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
    

    function addProd(){
	    p = $('#addProds').val();
	    for (var i = 0; i < prods.length; i++)
    	{
    		if(prods[i].id_producto == p){
				break;
    		}
    	}
    	console.log(prods);
//   	<tr><th>Producto</th><th>Variedad</th><th>Arpillas</th><th>Promedio</th><th>Peso total</th><th>Precio KG</th><th>Importe</th></tr>
	    html = '<tr>';
	    html += '<td>'+ prods[i].id_producto +'</td>';	    
	    html += '</tr>';
	    
    	$(html).appendTo('#prodsTable');
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
    	<tr><th>Producto</th><th>Variedad</th><th>Arpillas</th><th>Promedio</th><th>Peso total</th><th>Precio KG</th><th>Importe</th></tr>
    </table>
    
</div>

