
<h2>Nuevo Cliente</h2><?php

/*
 * Lista de Clientes
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
	<tr><td>Nombre</td><td><input type="text" name="nombre" size="40"/></td></tr>
	<tr><td>Ciudad</td><td><input type="text" name="ciudad" size="40"/></td></tr>
	<tr><td>Descuento</td><td><input type="text" name="descuento" size="40"/></td></tr>
	<tr><td>Direccion</td><td><input type="text" name="direccion" size="40"/></td></tr>
	<tr><td>E Mail</td><td><input type="text" name="e_mail" size="40"/></td></tr>
	<tr><td>Sucursal</td>
		<td>
			<select name="sucursal"> 
			<?php
			
				$sucursales = SucursalDAO::getAll();
				foreach( $sucursales as $suc ){
					echo "<option value='" . $suc->getIdSucursal() . "' >" .  $suc->getDescripcion()  . "</option>";
				}
			
			?>
	
	        </select>
		</td>
	</tr>
	
	<tr><td>Limite de credito</td><td><input type="text" name="nombre" size="40"/></td></tr>
	<tr><td>RFC</td><td><input type="text" name="nombre" size="40"/></td></tr>
	<tr><td>Telefono</td><td><input type="text" name="nombre" size="40"/></td></tr>
	<tr><td></td><td><input type="button" onClick="save()" value="Guardar"/> </td></tr>
	<tr><td></td><td><input type="button" value="Cancelar"/> </td></tr>	
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
