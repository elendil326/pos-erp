<?php

	require_once("model/sucursal.dao.php");
	require_once("controller/clientes.controller.php");

?>



<script>document.getElementById("MAIN_TITLE").innerHTML = "Nuevo cliente";</script>

<h2>Detalles del nuevo cliente</h2>
<form id="newClient">
	<table border="0" cellspacing="5" cellpadding="5">
		<tr><td>Nombre</td><td><input type="text"               id="nombre" size="40"/></td></tr>
		<tr><td>RFC</td><td><input type="text"                  id="rfc" size="40"/></td></tr>
		<tr><td>Direccion</td><td><input type="text"            id="direccion" size="40"/></td></tr>
		<tr><td>Ciudad</td><td><input type="text"            	id="ciudad" size="40"/></td></tr>	
		<tr><td>Telefono</td><td><input type="text"             id="telefono" size="40"/></td></tr>
		<tr><td>Descuento</td><td><input type="text"            id="descuento" size="40"/></td></tr>
		<tr><td>Limite de credito</td><td><input type="text"    id="limite_credito" size="40"/></td></tr>
		
		<tr><td>Sucursal</td><td>
			    <select id="sucursal"> 
			    <?php
			
				    $sucursales = SucursalDAO::getAll();
				    foreach( $sucursales as $suc ){
					    echo "<option value='" . $suc->getIdSucursal() . "' >" .  $suc->getDescripcion()  . "</option>";
				    }
			
			    ?>
	
	            </select>
		    </td>
		</tr>
		<tr><td></td><td><input type="button" onClick="validar()" value="Crear el nuevo cliente"/> </td></tr>
	</table>
</form>


<script type="text/javascript" charset="utf-8">
    function validar(){

        if(jQuery('#nombre').val().length < 8){
            return jQuery("#ajax_failure").html("El nombre es muy corto.").show();
        }


        if(jQuery('#direccion').val().length < 10){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();            
        }

        if(jQuery('#rfc').val().length < 7){
            return jQuery("#ajax_failure").html("El RFC es muy corto.").show();
            return;
        }


        obj = {
            nombre : 	jQuery('#nombre').val(), 
            direccion : jQuery("#direccion").val(), 
            rfc : 		jQuery("#rfc").val(), 
            descuento:	jQuery("#descuento").val(),
            telefono : 	jQuery("#telefono").val(),
            ciudad :	jQuery("#ciudad").val(),
            limite_credito:	jQuery("#limite_credito").val(),
            id_sucursal:	jQuery('#sucursal').val(),
            id_usuario : <?php echo $_SESSION['userid']; ?>
        };        

        guardar(obj);
    }






    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 301, 
            data : jQuery.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }


                reason = "Los datos se han editado con exito !";
                window.location = 'clientes.php?action=lista&success=true&reason=' + reason;
	      }
	    });
    }

</script>


<?php
