<?php


require_once("controller/proveedor.controller.php");


//obtener el cliente
$proveedor = ProveedorDAO::getByPK( $_REQUEST['id'] );



//titulo
?><script>
	jQuery("#MAIN_TITLE").html("Editar Proveedor");
</script>



<h2>Detalles personales</h2>
<form id="edit">
<table border="0" cellspacing="5" cellpadding="5" style="width:100%">
	<tr><td>Nombre</td><td><input type="text"           id="nombre"     value="<?php echo $proveedor->getNombre(); ?>" size="40"/></td>
		<td>E Mail</td><td><input type="text"           id="e_mail"     value="<?php echo $proveedor->getEMail(); ?>" size="40"/></td></tr>
	
	<tr><td>Tipo de Proveedor</td><td><input type="text"        id="tipo"  value="<?php echo $proveedor->getTipoProveedor(); ?>" size="40"/></td>
		<td>RFC</td><td><input type="text"              id="rfc"        value="<?php echo $proveedor->getRFC(); ?>" size="40"/></td></tr>
	
	<tr><td>Direccion</td><td><input type="text"        id="direccion"  value="<?php echo $proveedor->getDireccion(); ?>" size="40"/></td>
		<td>Telefono</td><td><input type="text"         id="telefono"   value="<?php echo $proveedor->getTelefono(); ?>" size="40"/></td></tr>
		
	<tr><td>Activo</td><td><input type="text"        id="activo"  value="<?php echo $proveedor->getActivo(); ?>" size="40"/></td>
		<td></td><td></td></tr>


	
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

        

      	obj = {
            nombre : jQuery("#nombre").val(),
            activo : jQuery("#activo").val(),
            direccion : jQuery("#direccion").val(),
            e_mail : jQuery("#e_mail").val(),
            tipo : jQuery("#tipo").val(),
            rfc : jQuery("#rfc").val(),
            telefono : jQuery("#telefono").val(),
            id_proveedor : <?php echo $_REQUEST['id']; ?>
        };        

        guardar(obj);
    }






    function guardar( data )
    {

        jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 902, 
            data : jQuery.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);


                if(response.success === false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }
                reason = "Los cambios se han guardado correctamente.";
                window.location = 'proveedor.php?action=detalles&id=<?php echo $_REQUEST['id']; ?>&success=true&reason=' + reason;
	      }
	    });
    }

</script>


<?php
