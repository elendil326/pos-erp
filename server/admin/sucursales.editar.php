<?php 
    require_once( 'model/sucursal.dao.php' );
    $suc = SucursalDAO::getByPK($_REQUEST['sid']); 
?>



<h1>Editar detalles de sucursal</h1>


<h2>Detalles personales</h2>
<form id="edit">
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Descripcion</td><td><input type="text"        	id="descripcion"  value="<?php echo $suc->getDescripcion(); ?>" size="40"/></td></tr>
	<tr><td>Direccion</td><td><input type="text"        	id="direccion"  value="<?php echo $suc->getDireccion(); ?>" size="40"/></td></tr>
	<tr><td>Prefijo Factura</td><td><input type="text" 		id="letras_factura" value="<?php echo $suc->getLetrasFactura(); ?>"  size="40"/></td></tr>
	<tr><td>RFC</td><td><input type="text"              	id="rfc"        value="<?php echo $suc->getRFC(); ?>" size="40"/></td></tr>
	<tr><td>Telefono</td><td><input type="text"         	id="telefono"   value="<?php echo $suc->getTelefono(); ?>" size="40"/></td></tr>
	<tr><td></td><td><input type="button" onClick="validar()" value="Guardar Cambios"/> </td></tr>
</table>
</form>



<h2>Cerrar Sucursal</h2>
<input type="button" onClick="cerrar()" value="Cerrar"/>


<script>

    function cerrar(){
        
        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 703, 
            sid : <?php echo $_REQUEST['sid']; ?>,
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }

                reason = "La sucursal se ha cerrado con exito.";
                window.location = "sucursales.php?action=lista&success=true&reason=" + reason;
	      }
	    });
    }


    function validar(){
        obj = {
            descripcion : jQuery('#descripcion').val(),
            direccion : jQuery('#direccion').val(),
            letras_factura :  jQuery('#letras_factura').val(),
            rfc : jQuery('#rfc').val(),
            telefono : jQuery('#telefono').val()
        };

        save(obj);
    }

    function save(data){
                jQuery.ajaxSettings.traditional = true;


        jQuery.ajax({
	      url: "../proxy.php",
	      data: { 
            action : 702, 
            sid : <?php echo $_REQUEST['sid']; ?>,
            payload : jQuery.JSON.encode(data)
           },
	      cache: false,
	      success: function(data){
		        response = jQuery.parseJSON(data);

                if(response.success == false){
                    return jQuery("#ajax_failure").html(response.reason).show();
                }

                reason = "La sucursal se ha editado correctamente";
                window.location = "sucursales.php?action=detalles&id=<?php echo $_REQUEST['sid']; ?>&success=true&reason=" + reason;
	      }
	    });
    }
</script>
