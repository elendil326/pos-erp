<?php

	require_once("model/sucursal.dao.php");
	require_once("controller/clientes.controller.php");
?>

<h2>Detalles del nuevo producto</h2>

<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Descripcion</td><td><input type="text" id="descripcion" size="40"/></td></tr>
	<tr><td>Precio sugerido</td><td><input type="text" id="precioVenta" size="40"/></td></tr>
	<tr><td>Precio intersucursal</td><td><input type="text" id="precioIntersucursal" size="40"/></td></tr>
	<tr><td>Escala</td>
		<td>
			<select id="escala">
				<option value='kilogramo' 	>Kilogramo(s)</option>
				<option value='pieza' 		>Pieza(s)</option>
				<option value='litro' 		>Litro(s)</option>
				<option value='unidad' 		>Unidad(es)</option>
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

	jQuery("#MAIN_TITLE").html("Nuevo producto");
	
	
    function save(){
        //validar
        data = {
                descripcion : 			jQuery('#descripcion').val(),
                escala : 				jQuery('#escala').val(),
                tratamiento:			jQuery('#tratamiento').val(),
                precio_venta : 			jQuery('#precioVenta').val(),
                precio_intersucursal : 	jQuery('#precioIntersucursal').val()
            };

        jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 405, 
            data : jQuery.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
	      		try{
			        response = jQuery.parseJSON(data);
			    }catch(e){
                    return jQuery("#ajax_failure").html("Error").show();			    
			    }

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }


                reason = "Nuevo producto creado.";
                window.location = "inventario.php?action=detalle&id="+ response.id+"&success=true&reason=" + reason ;
	      }
	    });
    }
</script>


<?php
