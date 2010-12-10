<h1>Nuevo Producto</h1>

<div class="g-unit g-first nav"> 
	<div class="ga-container-nav-side"> 
	Aqui puede crear nuevos productos..
	</div> 
</div>


<div class="g-unit content"> 
<h2>Detalles del nuevo producto</h2><?php

/*
 * Nuevo Cliente
 */ 

	require_once("model/sucursal.dao.php");
	require_once("controller/clientes.controller.php");


?>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){
      $("input, select").uniform();
    });
</script>




<form id="newClient">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Nombre</td><td><input type="text" name="productoID" size="40"/></td></tr>
	<tr><td>Descripcion</td><td><input type="text" name="descripcion" size="40"/></td></tr>
	<tr><td>Precio Venta</td><td><input type="text" name="precioVenta" size="40"/></td></tr>
	<tr><td>Existencias Minimas</td><td><input type="text" name="existenciasMinimas" size="40"/></td></tr>
	<tr><td>Precio Intersucursal</td><td><input type="text" name="precioIntersucursal" size="40"/></td></tr>
	<tr><td>Medida</td>
		<td>
			<select name="medida"> 
				<option value='fraccion' >Fraccion</option>
				<option value='unidad' >Unidad</option>
	        </select>
		</td>
	</tr>
	<tr><td></td><td><input type="button" value="Cancelar"/><input type="button" onClick="save()" value="Guardar"/> </td></tr>
</table>
</form>



<script type="text/javascript" charset="utf-8">

	function validate ( args ){
		
		
		return true;
		
		
	}

	function returned( ok ){
		if(!ok){
			alert("Ha ocurrido un error porfavor intente de nuevo.");
		}else{
			
			//limpiar forma
			alert("El cliente se ha guardado satisfactoriamente.");
		}
		
	}

	function save(){
		console.log($('#newClient'))
		forma = $('#newClient');
		if(!validate(forma)){
			return;
		}
		
		//encode data to json format
		
		//make ajax call to save and show result
	}
</script>


<?php
