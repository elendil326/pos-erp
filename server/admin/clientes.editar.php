<?php


require_once("controller/clientes.controller.php");


//obtener el cliente
$cliente = ClienteDAO::getByPK( $_REQUEST['id'] );



//titulo
?><script>
	jQuery("#MAIN_TITLE").html("Editar Cliente");
</script>



<h2>Detalles personales</h2>
<form id="edit">
<table border="0" cellspacing="5" cellpadding="5" style="width:100%">
	<tr><td>Nombre</td><td><input type="text"           id="nombre"     value="<?php echo $cliente->getNombre(); ?>" size="40"/></td>
		<td>E Mail</td><td><input type="text"           id="e_mail"     value="<?php echo $cliente->getEMail(); ?>" size="40"/></td></tr>
	
	<tr><td>Ciudad</td><td><input type="text"           id="ciudad"     value="<?php echo $cliente->getCiudad(); ?>" size="40"/></td>
		<td>Limite de credito</td><td><input type="text" id="limite_credito" value="<?php echo $cliente->getLimiteCredito(); ?>"  size="40"/></td></tr>
	
	<tr><td>Descuento</td><td><input type="text"        id="descuento"  value="<?php echo $cliente->getDescuento(); ?>" size="40"/></td>
		<td>RFC</td><td><input type="text"              id="rfc"        value="<?php echo $cliente->getRFC(); ?>" size="40"/></td></tr>
	
	<tr><td>Direccion</td><td><input type="text"        id="direccion"  value="<?php echo $cliente->getDireccion(); ?>" size="40"/></td>
		<td>Telefono</td><td><input type="text"         id="telefono"   value="<?php echo $cliente->getTelefono(); ?>" size="40"/></td></tr>
	

	
</table>
	<h4><input type="button" onClick="validar()" value="Guardar Cambios"/></h4>
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
        }

        if( isNaN(jQuery('#descuento').val()) || jQuery('#descuento').val().length == 0){
            return jQuery("#ajax_failure").html("El descuento no es nu numero valido.").show();
        }

        if( (jQuery('#descuento').val() >= 100) || (jQuery('#descuento').val() < 0) ){
            return jQuery("#ajax_failure").html("El descuento debe ser la taza porcentual de descuento, entre 0% y 100%").show();            
        }

        if( isNaN(jQuery('#limite_credito').val()) || jQuery('#limite_credito').val().length == 0){
            return jQuery("#ajax_failure").html("El limite de credito debe ser un nuemero valido.").show();            
        }

        if(  (jQuery('#limite_credito').val() < 0) ){
            return jQuery("#ajax_failure").html("El limite credito debe ser una cantidad en pesos mayor a 0.").show();            
        }

        obj = {
            nombre : jQuery("#nombre").val(),
            ciudad : jQuery("#ciudad").val(),
            descuento : jQuery("#descuento").val(),
            direccion : jQuery("#direccion").val(),
            e_mail : jQuery("#e_mail").val(),
            limite_credito : jQuery("#limite_credito").val(),
            rfc : jQuery("#rfc").val(),
            telefono : jQuery("#telefono").val(),
            id_cliente : <?php echo $_REQUEST['id']; ?>
        };        

        guardar(obj);
    }






    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 302, 
            data : jQuery.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);


                if(response.success === false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }
                reason = "Los cambios se han guardado correctamente.";
                window.location = 'clientes.php?action=detalles&id=<?php echo $_REQUEST['id']; ?>&success=true&reason=' + reason;
	      }
	    });
    }

</script>


<?php
