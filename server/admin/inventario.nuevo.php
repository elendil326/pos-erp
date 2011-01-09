<h2>Detalles del nuevo producto</h2><?php

	require_once("model/sucursal.dao.php");
	require_once("controller/clientes.controller.php");
?>


<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">
<script type="text/javascript" charset="utf-8">$(function(){$("input, select").uniform(); });</script>




<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Descripcion</td><td><input type="text" id="descripcion" size="40"/></td></tr>
	<tr><td>Precio Venta</td><td><input type="text" id="precioVenta" size="40"/></td></tr>
	<tr><td>Precio Intersucursal</td><td><input type="text" id="precioIntersucursal" size="40"/></td></tr>
	<tr><td>Escala</td>
		<td>
			<select id="escala">
				<option value='kilogramo' 	>Kilogramo(s)</option>
				<option value='pieza' 		>Pieza(s)</option>
				<option value='litro' 		>Litro(s)</option>
				<option value='metro' 		>Unidad(es)</option>
	        </select>
		</td>
	</tr>
	<tr><td>Proceso</td>
		<td>
			<select id="tratamiento">
				<option value='null' 		>Sin tratamientos</option>
				<option value='limpia'		>Limpia/Original</option>
	        </select>
		</td>
	</tr>	
	<tr><td></td><td><input type="button" onClick="save()" value="Guardar"/> </td></tr>
</table>




<script type="text/javascript" charset="utf-8">

    function save(){
        //validar
        data = {
                descripcion : 			$('#descripcion').val(),
                escala : 				$('#escala').val(),
                tratamiento:			$('#tratamiento').val(),
                precio_venta : 			$('#precioVenta').val(),
                precio_intersucursal : 	$('#precioIntersucursal').val()
            };

        jQuery.ajaxSettings.traditional = true;


        $.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 405, 
            data : $.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
	      		try{
			        response = jQuery.parseJSON(data);
			    }catch(e){
                    return $("#ajax_failure").html("Error").show();			    
			    }

                if(response.success == false){
                    return $("#ajax_failure").html(response.reason).show();
                }


                reason = "Nuevo producto creado.";
                window.location = "inventario.php?action=detalle&id="+ response.id+"&success=true&reason=" + reason ;
	      }
	    });
    }
</script>


<?php
