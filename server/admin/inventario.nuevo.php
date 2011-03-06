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
			<select id="escala" onChange="escalaSeleccionada(this.value)">
				<option value='kilogramo' 	>Kilogramos</option>
				<option value='pieza' 		>Piezas</option>
				<option value='litro' 		>Litros</option>
				<option value='unidad' 		>Unidades</option>
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
	<tr><td>Agrupacion</td>
		<td>
			<select id="agrupacion" onChange="agrupacionSeleccionada(this.value)">
				<option value='null' 		>Sin agrupacion</option>
				<option value='arpilla'	>Arpillas</option>
				<option value='bulto'		>Bultos</option>
				<option value='costal'	>Costales</option>
				<option value='caja'		>Cajas</option>
	        </select>
		</td>
		<td id="agrupacionBox" style="display:none">
			<input type="text" id="agrupacionTam" size="20"/><span id="agrupacionCaption"></span>
		</td>
	</tr>
	<tr><td></td><td> </td></tr>
</table>
<h4><input type="button" onClick="save()" value="Guardar"/></h4>



<script type="text/javascript" charset="utf-8">

	function agrupacionSeleccionada(val){
		if(val != "null"){
			//mostrar la caja
			if(!jQuery( "#agrupacionBox" ).is(":visible")){
				jQuery( "#agrupacionBox" ).fadeIn();	
			}

			jQuery( "#agrupacionCaption" ).html(" " + jQuery("#escala").val() + "s por " + val);

		}else{
			//ocultar la caja
			jQuery( "#agrupacionBox" ).fadeOut();
		}
		
	}
	
	function escalaSeleccionada(val){
		agrupacionSeleccionada( jQuery("#agrupacion").val() );
	}

	jQuery("#MAIN_TITLE").html("Nuevo producto");
	
	
    function save(){
        //validar

        if(jQuery('#descripcion').val().length < 2 ){
	        return jQuery("#ajax_failure").html("La descripcion debe ser cuando menos de de 3 caracteres.").show();
        }
        
        if(jQuery('#descripcion').val().length > 15 ){
	        return jQuery("#ajax_failure").html("La descripcion debe ser menor a 15 caracteres, dado que no cabria en el ticket de venta.").show();
        }

        if(jQuery('#precioVenta').val().length ==  0 ){
	        return jQuery("#ajax_failure").html("El precio sugerido no puede dejarse vacio.").show();
        }

        if(jQuery('#precioIntersucursal').val().length ==  0 ){
	        return jQuery("#ajax_failure").html("El precio intersucursal no puede dejarse vacio.").show();
        }
        
        data = {
                descripcion : 			jQuery('#descripcion').val(),
                escala : 				jQuery('#escala').val(),
                tratamiento:			jQuery('#tratamiento').val(),
                precio_venta : 			jQuery('#precioVenta').val(),
                precio_intersucursal : 	jQuery('#precioIntersucursal').val(),
				agrupacion : 			jQuery('#agrupacion').val(),
				agrupacionTam: 			jQuery('#agrupacionTam').val() 
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
