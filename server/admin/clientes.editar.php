<?php
require_once("controller/clientes.controller.php");


//obtener el cliente
$cliente = ClienteDAO::getByPK($_REQUEST['id']);



//titulo
?><script>
    jQuery("#MAIN_TITLE").html("Editar Cliente");
</script>



<h2>Detalles personales</h2>
<form id="edit">
    <table border="0" cellspacing="5" cellpadding="5" style="width:100%">
        <tr>
            <td>Razon social</td>
            <td><input type="text" id="detalle-cliente-razon_social" value="<?php echo $cliente->getRazonSocial(); ?>" size="40"/></td>
            <td>RFC</td>
            <td><input type="text" id="detalle-cliente-rfc" value="<?php echo $cliente->getRfc(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Calle</td>
            <td><input type="text" id="detalle-cliente-calle" value="<?php echo $cliente->getCalle(); ?>" size="40"/></td>
            <td>Colonia</td>
            <td><input type="text" id="detalle-cliente-colonia" value="<?php echo $cliente->getColonia(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Numero Exterior</td>
            <td><input type="text" id="detalle-cliente-numero_exterior" value="<?php echo $cliente->getNumeroExterior(); ?>" size="40"/></td>
            <td>Numero Interior</td>
            <td><input type="text" id="detalle-cliente-numero_interior" value="<?php echo $cliente->getNumeroInterior(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Referencia</td>
            <td><input type="text" id="detalle-cliente-referencia" value="<?php echo $cliente->getReferencia(); ?>" size="40"/></td>
            <td>Localidad</td>
            <td><input type="text" id="detalle-cliente-localidad" value="<?php echo $cliente->getLocalidad(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Municipio</td>
            <td><input type="text" id="detalle-cliente-municipio" value="<?php echo $cliente->getMunicipio(); ?>" size="40"/></td>
            <td>Estado</td>
            <td><input type="text" id="detalle-cliente-estado" value="<?php echo $cliente->getEstado(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Codigo Postal</td>
            <td><input type="text" id="detalle-cliente-codigo_postal" value="<?php echo $cliente->getCodigoPostal(); ?>" size="40"/></td>
            <td>Pais</td>
            <td><input type="text" id="detalle-cliente-pais" value="<?php echo $cliente->getPais(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Telefono</td>
            <td><input type="text" id="detalle-cliente-telefono" value="<?php echo $cliente->getTelefono(); ?>" size="40"/></td>
            <td>E Mail</td>
            <td><input type="text" id="detalle-cliente-e_mail" value="<?php echo $cliente->getEMail(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Limite de Credito</td>
            <td><input type="text" id="detalle-cliente-limite_credito" value="<?php echo $cliente->getLimiteCredito(); ?>" size="40"/></td>
            <td>Descuento</td>
            <td><input type="text" id="detalle-cliente-descuento" value="<?php echo $cliente->getDescuento(); ?>" size="40"/></td>
        </tr>
    </table>
    <h4><input type="button" onClick="validar()" value="Guardar Cambios"/></h4>
</form>



<script type="text/javascript" charset="utf-8">

    function validar(){

        if(jQuery('#detalle-cliente-razon_social').val().length < 8){
            return jQuery("#ajax_failure").html("La razon social muy corta.").show();
        }
        
        if(jQuery('#detalle-cliente-rfc').val().length < 7){
            return jQuery("#ajax_failure").html("El rfc es muy corto.").show();
        }
        
        /*if(jQuery('#detalle-cliente-calle').val().length < 3){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        
        if(jQuery('#detalle-cliente-colonia').val().length < 3){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        
        if(jQuery('#detalle-cliente-numero_exterior').val().length < 1){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        
        if(jQuery('#detalle-cliente-numero_interior').val().length < 10){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        
        if(jQuery('#ddetalle-cliente-referencia').val().length < 10){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        
        if(jQuery('#detalle-cliente-localidad').val().length < 10){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        
        if(jQuery('#detalle-cliente-municipio').val().length < 10){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        
        if(jQuery('#detalle-cliente-estado').val().length < 10){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        
        if(jQuery('#detalle-cliente-codigo_postal').val().length < 10){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        
        if(jQuery('#detalle-cliente-pais').val().length < 10){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        
        if(jQuery('#detalle-cliente-telefono').val().length < 10){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        
        if(jQuery('#detalle-cliente-e_mail').val().length < 10){
            return jQuery("#ajax_failure").html("La direccion es muy corta.").show();
        }
        */
        if( isNaN(jQuery('#detalle-cliente-descuento').val()) || jQuery('#detalle-cliente-descuento').val().length == 0){
            return jQuery("#ajax_failure").html("El descuento no es un numero valido.").show();
        }

        if( (jQuery('#detalle-cliente-descuento').val() >= 100) || (jQuery('#detalle-cliente-descuento').val() < 0) ){
            return jQuery("#ajax_failure").html("El descuento debe ser la taza porcentual de descuento, entre 0% y 100%").show();            
        }

        if( isNaN(jQuery('#detalle-cliente-limite_credito').val()) || jQuery('#detalle-cliente-limite_credito').val().length == 0){
            return jQuery("#ajax_failure").html("El limite de credito debe ser un nuemero valido.").show();            
        }

        if(  (jQuery('#detalle-cliente-limite_credito').val() < 0) ){
            return jQuery("#ajax_failure").html("El limite credito debe ser una cantidad en pesos mayor a 0.").show();            
        }

        obj = {
            id_cliente : <?php echo $_REQUEST['id']; ?>,
            
            razon_social : jQuery("#detalle-cliente-razon_social").val(),
            rfc : jQuery("#detalle-cliente-rfc").val(),
            
            calle : jQuery("#detalle-cliente-calle").val(),
            colonia : jQuery("#detalle-cliente-colonia").val(),
            
            numero_exterior : jQuery("#detalle-cliente-numero_exterior").val(),
            numero_interior : jQuery("#detalle-cliente-numero_interior").val(),
            
            referencia : jQuery("#detalle-cliente-referencia").val(),
            localidad : jQuery("#detalle-cliente-localidad").val(),
            
            municipio : jQuery("#detalle-cliente-municipio").val(),
            estado : jQuery("#detalle-cliente-estado").val(),
            
            codigo_postal : jQuery("#detalle-cliente-codigo_postal").val(),
            pais : jQuery("#detalle-cliente-pais").val(),
            
            
            descuento : jQuery("#detalle-cliente-descuento").val(),
            limite_credito : jQuery("#detalle-cliente-limite_credito").val(),
            
            telefono : jQuery("#detalle-cliente-telefono").val(),
            e_mail : jQuery("#detalle-cliente-e_mail").val(),                        
            
            
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
