<?php
require_once( 'model/sucursal.dao.php' );
$suc = SucursalDAO::getByPK($_REQUEST['sid']);
?>



<h1>Editar detalles de sucursal</h1>


<h2>Detalles de la Sucursal</h2>
<form id="edit">
    <table border="0" cellspacing="5" cellpadding="5">
        <tr>
            <td>Descripcion</td>
            <td><input type="text" id="detalle-sucursal-descripcion" placeholder="obligatorio" value="<?php echo $suc->getDescripcion(); ?>" size="40"/></td>
            <td>Razon Social</td>
            <td><input type="text" id="detalle-sucursal-razon_social"  value="<?php echo $suc->getRazonSocial(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>RFC</td>
            <td><input type="text" id="detalle-sucursal-rfc"  value="<?php echo $suc->getRfc(); ?>" size="40"/></td>
            <td>Calle</td>
            <td><input type="text" id="detalle-sucursal-calle"  placeholder="obligatorio"  value="<?php echo $suc->getCalle(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Numero Exterior</td>
            <td><input type="text" id="detalle-sucursal-numero_exterior"  placeholder="obligatorio"  value="<?php echo $suc->getNumeroExterior(); ?>" size="40"/></td>
            <td>Numero Interior</td>
            <td><input type="text" id="detalle-sucursal-numero_interior"  value="<?php echo $suc->getNumeroInterior(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Colonia</td>
            <td><input type="text" id="detalle-sucursal-colonia"  placeholder="obligatorio"  value="<?php echo $suc->getColonia(); ?>" size="40"/></td>
            <td>Localidad</td>
            <td><input type="text" id="detalle-sucursal-localidad"  value="<?php echo $suc->getLocalidad(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Referencia</td>
            <td><input type="text" id="detalle-sucursal-referencia"  value="<?php echo $suc->getReferencia(); ?>" size="40"/></td>
            <td>Municipio</td>
            <td><input type="text" id="detalle-sucursal-municipio"  placeholder="obligatorio"  value="<?php echo $suc->getMunicipio(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Estado</td>
            <td><input type="text" id="detalle-sucursal-estado"  placeholder="obligatorio"  value="<?php echo $suc->getEstado(); ?>" size="40"/></td>
            <td>Codigo Postal</td>
            <td><input type="text" id="detalle-sucursal-codigo_postal"  placeholder="obligatorio"  value="<?php echo $suc->getCodigoPostal(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Pais</td>
            <td><input type="text" id="detalle-sucursal-pais"  placeholder="obligatorio"  value="<?php echo $suc->getPais(); ?>" size="40"/></td>
            <td>Prefijo Factura</td>
            <td><input type="text" id="detalle-sucursal-letras_factura"  placeholder="obligatorio"  value="<?php echo $suc->getLetrasFactura(); ?>" size="40"/></td>
        </tr>
        <tr>
            <td>Telefono</td>
            <td><input type="text" id="detalle-sucursal-telefono" value="<?php echo $suc->getTelefono(); ?>"  size="40"/></td>
            <td><!--Activo--></td>
            <td><!--<select><option value ="1" --><?php /*if($suc->getActivo())echo " selected";*/ ?> <!-- >Sí</option><option value ="0" --><?php /*if(!$suc->getActivo())echo " selected"; */?> <!-- >No</option></select>--></td>
        </tr>
        <tr>
            <td align ="center" colspan ="4"><input type="button" onClick="validar()" value="Guardar Cambios"/> </td>
        </tr>
    </table>
</form>



<h2>Cerrar Sucursal</h2>
<h4><input type="button" onClick="seguroDeCerrar()" value="Cerrar esta sucursal"/></h4>


<script>
    function seguroDeCerrar(){
        html = "<h1>Cerrar sucursal</h1>";
        html += "&iquest; Esta seguro de que desea cerrar esta sucursal ?<br><br>";
        html += "<div align='center'><input type='button' value='Aceptar' onclick='cerrar()'><input type='button' value='Cancelar' onclick='jQuery(document).trigger(\"close.facebox\");'></div>"

        jQuery.facebox( html );
    }
	
    function cerrar(){
        
        jQuery.ajax({
            url: "../proxy.php",
            data: { 
                action : 703, 
                sid : <?php echo $_REQUEST['sid']; ?>
            },
            cache: false,
            success: function(data){
                response = jQuery.parseJSON(data);

                if(response.success == false){
                    window.scroll(0,0);	
                    return jQuery("#ajax_failure").html(response.reason).show();
                }

                reason = "La sucursal se ha cerrado con exito.";
                window.location = "sucursales.php?action=lista&success=true&reason=" + reason;
            }
        });
    }


    function validar(){
    
        if(jQuery('#detalle-sucursal-descripcion').val().length < 5 ){
            return jQuery("#ajax_failure").html("La descripcion de la sucursal es muy corta.").show();
        }
        
        if(jQuery('#detalle-sucursal-calle').val().length < 3 ){
            return jQuery("#ajax_failure").html("La descripcion de la calle es muy corta.").show();
        }
        
        if(jQuery('#detalle-sucursal-numero_exterior').val().length < 1 ){
            return jQuery("#ajax_failure").html("Indique el numero exterior.").show();
        }
        
        if(jQuery('#detalle-sucursal-colonia').val().length < 3 ){
            return jQuery("#ajax_failure").html("La descripcion de la colonia es muy corta.").show();
        }
        
        if(jQuery('#detalle-sucursal-municipio').val().length < 5 ){
            return jQuery("#ajax_failure").html("La descripcion del municipio es muy corta.").show();
        }
        
        if(jQuery('#detalle-sucursal-estado').val().length < 5 ){
            return jQuery("#ajax_failure").html("La descripcion del estado es muy corta.").show();
        }
        
        if(jQuery('#detalle-sucursal-codigo_postal').val().length < 5 ){
            return jQuery("#ajax_failure").html("La descripcion del codigo postal es muy corta.").show();
        }
        
        if(jQuery('#detalle-sucursal-pais').val().length < 5 ){
            return jQuery("#ajax_failure").html("La descripcion del pais es muy corta.").show();
        }
        if(jQuery('#detalle-sucursal-letras_factura').val().length < 1 ){
            return jQuery("#ajax_failure").html("Indique un prefijo para las facturas.").show();
        }
        
    
        obj = {
            descripcion : jQuery('#detalle-sucursal-descripcion').val,
            razon_social : jQuery('#detalle-sucursal-razon_social').val(),
            
            rfc : jQuery('#detalle-sucursal-rfc').val(),
            calle : jQuery('#detalle-sucursal-calle').val(),
            
            numero_exterior : jQuery('#detalle-sucursal-numero_exterior').val(),
            numero_interior : jQuery('#detalle-sucursal-numero_interior').val(),
            
            colonia : jQuery('#detalle-sucursal-colonia').val(),
            localidad : jQuery('#detalle-sucursal-localidad').val(),
            
            referencia : jQuery('#detalle-sucursal-referencia').val(),
            municipio : jQuery('#detalle-sucursal-municipio').val(),
            
            estado : jQuery('#detalle-sucursal-estado').val(),
            codigo_postal : jQuery('#detalle-sucursal-codigo_postal').val(),
            
            pais : jQuery('#detalle-sucursal-pais').val(),
            letras_factura :  jQuery('#detalle-sucursal-letras_factura').val(),
            
            telefono : jQuery('#detalle-sucursal-telefono').val()
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
                    window.scroll(0,0);           				
                    return jQuery("#ajax_failure").html(response.reason).show();
                }

                reason = "La sucursal se ha editado correctamente";
                window.location = "sucursales.php?action=detalles&id=<?php echo $_REQUEST['sid']; ?>&success=true&reason=" + reason;
            }
        });
    }
</script>
