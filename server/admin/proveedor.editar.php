<?php


require_once("controller/proveedor.controller.php");


//obtener el cliente
$proveedor = ProveedorDAO::getByPK( $_REQUEST['id'] );



//titulo
?><script>
	jQuery("#MAIN_TITLE").html("Editar Proveedor");
</script>



<h2>Detalles del proveedor</h2>

<form id="edit">
<table border="0" cellspacing="5" cellpadding="5" style="width:100%">
	<tr><td>Nombre</td><td><input type="text"           id="nombre"     value="<?php echo $proveedor->getNombre(); ?>" size="40"/></td>
		<td>E Mail</td><td><input type="text"           id="e_mail"     value="<?php echo $proveedor->getEMail(); ?>" size="40"/></td></tr>
	
	<tr><td>Tipo de Proveedor</td>
			<td>
				<?php $tipo = $proveedor->getTipoProveedor(); ?>
				<select id="tipo"> 
					<option value='admin' 		<?php if($tipo == "admin") echo "selected"; ?>>Surte a centro de distribucion</option> 
					<option value='sucursal' 	<?php if($tipo == "sucursal") echo "selected"; ?>>Surte a sucursales</option> 
					<option value='ambos' 		<?php if($tipo == "ambos") echo "selected"; ?>>Surte a ambos</option> 
				</select>
			</td>
		<td>RFC</td><td><input type="text"              id="rfc"        value="<?php echo $proveedor->getRFC(); ?>" size="40"/></td></tr>
	
	<tr><td>Direccion</td><td><input type="text"        id="direccion"  value="<?php echo $proveedor->getDireccion(); ?>" size="40"/></td>
		<td>Telefono</td><td><input type="text"         id="telefono"   value="<?php echo $proveedor->getTelefono(); ?>" size="40"/></td></tr>
		
	<tr><td>Activo</td><td><select id="activo"><option value=0>Inactivo</option><option value=1>Activo</option></select></td>
		<td></td><td></td></tr>
</table>
	<h4><input type="button" onClick="validar()" value="Guardar Cambios"/></h4>
</form>

	<input type="hidden"  id="estado"  value="<?php echo $proveedor->getActivo(); ?>" size="40"/>

<script type="text/javascript" charset="utf-8">
	
		document.getElementById('activo').selectedIndex=document.getElementById('estado').value;

    function validar(){

        if(jQuery('#nombre').val().length < 8){
            return jQuery("#ajax_failure").html("El nombre es muy corto.").show();
        }


        if(jQuery('#direccion').val().length < 10){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }

		/*
        if(jQuery('#rfc').val().length < 7){
            return jQuery("#ajax_failure").html("El RFC es muy corto.").show();
        }
		*/

        

      	obj = {
            nombre : 	jQuery("#nombre").val(),
            activo : 	document.getElementById('activo').selectedIndex,
            direccion : jQuery("#direccion").val(),
            e_mail : 	jQuery("#e_mail").val(),
            tipo : 		jQuery("#tipo").val(),
            rfc : 		jQuery("#rfc").val(),
            telefono : 	jQuery("#telefono").val(),
            id_proveedor: <?php echo $_REQUEST['id']; ?>
        };        

       jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 902, 
            data : jQuery.JSON.encode(obj)
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
