<?php

	require_once("model/sucursal.dao.php");
	require_once("controller/clientes.controller.php");

?>



<h1>Registrar nuevo proveedor</h1>
<h2>Detalles del nuevo proveedor</h2>
<form id="newClient">
	<table border="0" cellspacing="5" cellpadding="5">
		<tr><td>Nombre</td><td><input type="text"               id="nombre" size="40"/></td></tr>
		<tr><td>Direccion</td><td><input type="text"            id="direccion" size="40"/></td></tr>
		<tr><td>RFC</td><td><input type="text"                  id="rfc" size="40"/></td></tr>
		<tr><td>Telefono</td><td><input type="text"             id="telefono" size="40"/></td></tr>
		<tr><td>E Mail</td><td><input type="text"               id="e_mail" size="40"/></td></tr>		
		<tr><td>Tipo de proveedor</td>
			<td>
			<select id="tipo_proveedor">
				<option value='admin' 	>Surte a centro de distribucion</option>
				<option value='sucursal'>Surte a sucursales</option>
				<option value='ambos' 	>Surte a ambos</option>

	        </select>
			</td></tr>
		<tr><td colspan=2>
			<h4>
				<input id="submit" type="button" onClick="validar()" value="Crear el nuevo proveedor"/><div id="loader" style='display:none'><img src="../media/loader.gif"></div>
			</h4>	
			</td></tr>
	</table>
</form>


<script type="text/javascript" charset="utf-8">


		
    function validar(){

        if(jQuery('#nombre').val().length < 8){
            return jQuery("#ajax_failure").html("El nombre es muy corto.").show();
        }


        if(jQuery('#direccion').val().length < 8){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();            
        }

        obj = {
            nombre : 	jQuery('#nombre').val(), 
            direccion : jQuery("#direccion").val(), 
            rfc : 		jQuery("#rfc").val(),
            e_mail: 	jQuery("e_mail").val(),
            telefono : 	jQuery("#telefono").val(),
            tipo_proveedor : 	jQuery("#tipo_proveedor").val()            
        };        

        guardar(obj);
    }

    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;
		jQuery("#loader").fadeIn();
		jQuery("#submit").attr('disabled', true);

        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 901, 
            data : jQuery.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
	      
	      		try{
			        response = jQuery.parseJSON(data);
			    }catch(e){
					jQuery("#loader").fadeOut('fast', function (){
						jQuery("#submit").removeAttr('disabled');
						jQuery("#ajax_failure").html("Error, porfavor intente de nuevo.").show();
					window.scroll(0,0);           									
					});
					
                    return;
			    }

                if(response.success === false){
                	jQuery("#loader").fadeOut('fast', function (){
						jQuery("#submit").removeAttr('disabled');
						jQuery("#ajax_failure").html(response.reason).show();
					window.scroll(0,0);           									
					});
					return;
                }


                reason = "Los datos se han editado con exito !";
                
                window.location = 'proveedor.php?action=lista&success=true&reason=' + reason;
	      }
	    });
    }

</script>


<?php
